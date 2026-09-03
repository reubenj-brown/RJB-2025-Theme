/* ============================================================
   Watercolor Reveal — photography draft 2026
   ============================================================

   Ported verbatim (bar selectors) from watercolor-reveal-gallery.html,
   the canonical reference implementation. Clicking a card dissolves the
   photo away from the click point with an organic, pigment-like edge,
   revealing the caption underneath over 5 seconds; clicking again flows
   it back.

   Four things in here look wrong and are not — see the design reference:

   1. Chromatic fringe: each RGB channel is multiplied by its OWN alpha
      while the output alpha is the max of the three. Making the
      premultiplication consistent turns the tinted fringe flat grey.
   2. No UNPACK_FLIP_Y_WEBGL. The vertex shader already flips UV to a
      top-left origin. Flipping again on upload double-flips the photo.
   3. The non-interruptible click guard. Relaxing it without tracking
      true mid-flight radius reintroduces a visible snap on interruption.
   4. Poster layer must sit BEHIND the text layer, and must be removed
      on first successful texture upload — otherwise it permanently
      blocks the reveal from ever showing the caption.

   Contexts are created lazily and torn down again offscreen: browsers cap
   simultaneous live WebGL contexts (commonly 8–16), well under this page's
   card count, and exceeding it silently blanks older cards.
   ============================================================ */

(function () {
    // ---- shared, card-independent setup ----
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const ANIM_DUR_S = 5;
    const ANIM_DUR_MS = ANIM_DUR_S * 1000;
    const CURVE = [0.65, 0, 0.35, 1]; // cubic-bezier control points, ease-in-out

    function makeBezierEasing(x1, y1, x2, y2) {
        function a(a1, a2) { return 1.0 - 3.0 * a2 + 3.0 * a1; }
        function b(a1, a2) { return 3.0 * a2 - 6.0 * a1; }
        function c(a1) { return 3.0 * a1; }
        function calcBezier(t, a1, a2) { return ((a(a1, a2) * t + b(a1, a2)) * t + c(a1)) * t; }
        function calcSlope(t, a1, a2) { return 3.0 * a(a1, a2) * t * t + 2.0 * b(a1, a2) * t + c(a1); }
        function getTForX(x) {
            let t = x;
            for (let i = 0; i < 8; i++) {
                const slope = calcSlope(t, x1, x2);
                if (Math.abs(slope) < 1e-6) break;
                const xEst = calcBezier(t, x1, x2) - x;
                t -= xEst / slope;
            }
            t = Math.min(1, Math.max(0, t));
            return t;
        }
        return function (x) {
            if (x <= 0) return 0;
            if (x >= 1) return 1;
            return calcBezier(getTForX(x), y1, y2);
        };
    }
    const ease = makeBezierEasing(CURVE[0], CURVE[1], CURVE[2], CURVE[3]);

    const VERT_SRC = `
        attribute vec2 aPosition;
        varying vec2 vUv;
        void main() {
          vUv = vec2((aPosition.x + 1.0) * 0.5, 1.0 - (aPosition.y + 1.0) * 0.5);
          gl_Position = vec4(aPosition, 0.0, 1.0);
        }
      `;

    const FRAG_SRC = `
        precision highp float;
        varying vec2 vUv;

        uniform sampler2D uImage;
        uniform vec2 uImageSize;
        uniform vec2 uStageCss;
        uniform vec4 uCoverUV;
        uniform vec2 uCenter;
        uniform float uFront;
        uniform float uEdgeWidth;
        uniform float uEdgeAmp;
        uniform float uGranAmp;
        uniform float uChromaAmp;
        uniform float uBleedWidth;
        uniform float uTime;

        // --- Simplex 3D noise (Ashima Arts / Ian McEwan, webgl-noise, MIT) ---
        vec4 permute(vec4 x) { return mod(((x * 34.0) + 1.0) * x, 289.0); }
        vec4 taylorInvSqrt(vec4 r) { return 1.79284291400159 - 0.85373472095314 * r; }

        float snoise(vec3 v) {
          const vec2 C = vec2(1.0 / 6.0, 1.0 / 3.0);
          const vec4 D = vec4(0.0, 0.5, 1.0, 2.0);
          vec3 i  = floor(v + dot(v, C.yyy));
          vec3 x0 = v - i + dot(i, C.xxx);
          vec3 g = step(x0.yzx, x0.xyz);
          vec3 l = 1.0 - g;
          vec3 i1 = min(g.xyz, l.zxy);
          vec3 i2 = max(g.xyz, l.zxy);
          vec3 x1 = x0 - i1 + C.xxx;
          vec3 x2 = x0 - i2 + C.yyy;
          vec3 x3 = x0 - D.yyy;
          i = mod(i, 289.0);
          vec4 p = permute(permute(permute(
                     i.z + vec4(0.0, i1.z, i2.z, 1.0))
                   + i.y + vec4(0.0, i1.y, i2.y, 1.0))
                   + i.x + vec4(0.0, i1.x, i2.x, 1.0));
          float n_ = 1.0 / 7.0;
          vec3 ns = n_ * D.wyz - D.xzx;
          vec4 j = p - 49.0 * floor(p * ns.z * ns.z);
          vec4 x_ = floor(j * ns.z);
          vec4 y_ = floor(j - 7.0 * x_);
          vec4 x = x_ * ns.x + ns.yyyy;
          vec4 y = y_ * ns.x + ns.yyyy;
          vec4 h = 1.0 - abs(x) - abs(y);
          vec4 b0 = vec4(x.xy, y.xy);
          vec4 b1 = vec4(x.zw, y.zw);
          vec4 s0 = floor(b0) * 2.0 + 1.0;
          vec4 s1 = floor(b1) * 2.0 + 1.0;
          vec4 sh = -step(h, vec4(0.0));
          vec4 a0 = b0.xzyw + s0.xzyw * sh.xxyy;
          vec4 a1 = b1.xzyw + s1.xzyw * sh.zzww;
          vec3 p0 = vec3(a0.xy, h.x);
          vec3 p1 = vec3(a0.zw, h.y);
          vec3 p2 = vec3(a1.xy, h.z);
          vec3 p3 = vec3(a1.zw, h.w);
          vec4 norm = taylorInvSqrt(vec4(dot(p0, p0), dot(p1, p1), dot(p2, p2), dot(p3, p3)));
          p0 *= norm.x; p1 *= norm.y; p2 *= norm.z; p3 *= norm.w;
          vec4 m = max(0.6 - vec4(dot(x0, x0), dot(x1, x1), dot(x2, x2), dot(x3, x3)), 0.0);
          m = m * m;
          return 42.0 * dot(m * m, vec4(dot(p0, x0), dot(p1, x1), dot(p2, x2), dot(p3, x3)));
        }

        float fbm(vec3 p) {
          float sum = 0.0;
          float amp = 0.5;
          float freq = 1.0;
          for (int i = 0; i < 4; i++) {
            sum += amp * snoise(p * freq);
            freq *= 2.0;
            amp *= 0.5;
          }
          return sum;
        }

        void main() {
          vec2 fragCss = vUv * uStageCss;
          vec2 sampleUv = vUv * uCoverUV.xy + uCoverUV.zw;

          float edgeNoise = fbm(vec3(fragCss * 0.0065, uTime * 0.06));
          float dist = length(fragCss - uCenter) + edgeNoise * uEdgeAmp;

          float granNoise = fbm(vec3(fragCss * 0.035, uTime * 0.15 + 31.7));
          float granWobble = granNoise * uGranAmp * uEdgeWidth;

          float chromaVary = 0.5 + 0.5 * fbm(vec3(fragCss * 0.012, uTime * 0.05 + 91.0));
          float chroma = uChromaAmp * chromaVary;

          float dR = dist - (uFront + chroma);
          float dG = dist - uFront;
          float dB = dist - (uFront - chroma);

          float lo = -uEdgeWidth + granWobble;
          float hi = uEdgeWidth + granWobble;
          float alphaR = smoothstep(lo, hi, dR);
          float alphaG = smoothstep(lo, hi, dG);
          float alphaB = smoothstep(lo, hi, dB);

          vec4 tex = texture2D(uImage, sampleUv);

          vec2 texel = 1.0 / uImageSize;
          vec2 bstep = texel * 5.0;
          vec4 blur = tex;
          blur += texture2D(uImage, sampleUv + vec2(bstep.x, 0.0));
          blur += texture2D(uImage, sampleUv - vec2(bstep.x, 0.0));
          blur += texture2D(uImage, sampleUv + vec2(0.0, bstep.y));
          blur += texture2D(uImage, sampleUv - vec2(0.0, bstep.y));
          blur *= 0.2;

          float edgeProximity = 1.0 - smoothstep(0.0, uBleedWidth, abs(dG));
          vec3 bled = mix(tex.rgb, blur.rgb, edgeProximity * 0.85);

          float alphaOut = max(alphaR, max(alphaG, alphaB));
          vec3 colorOut = vec3(bled.r * alphaR, bled.g * alphaG, bled.b * alphaB);

          gl_FragColor = vec4(colorOut, alphaOut);
        }
      `;

    function compileShader(gl, type, src) {
        const shader = gl.createShader(type);
        gl.shaderSource(shader, src);
        gl.compileShader(shader);
        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            console.error('Shader compile error:', gl.getShaderInfoLog(shader));
            gl.deleteShader(shader);
            return null;
        }
        return shader;
    }

    // How far outside the actual viewport (in CSS px) a card activates
    // before becoming visible, and stays active after leaving — a buffer
    // so cards don't visibly pop in/out right at the viewport edge, and so
    // small scroll jitter right at the boundary doesn't thrash init/teardown.
    const ROOT_MARGIN = '400px 0px 400px 0px';

    // ---- one persistent controller per card ----
    // All per-card state and functions live in this one closure for the
    // card's entire lifetime on the page. init()/teardown() only create and
    // release the WebGL-specific resources (context, program, texture) —
    // everything else (the easing-driven animation math, click handling,
    // resize handling) is defined once and just reads whichever gl/texture/
    // etc. happen to be current, so re-activating a card after teardown is
    // exactly the same code path as activating it the first time.
    function setupCard(stage) {
        // Guard against this running more than once against the same stage
        // (some preview/hosting contexts re-execute inline scripts).
        if (stage.dataset.watercolorInit) return;
        stage.dataset.watercolorInit = '1';

        const imageSrc = stage.dataset.src;
        const textLayer = stage.querySelector('.wc-text');
        let posterLayer = stage.querySelector('.wc-poster');
        let canvas = stage.querySelector('.wc-gl');

        let gl = null, program = null, texture = null, uniforms = null;
        let sizeObserver = null;
        let rafId = null;
        let active = false;       // has a live WebGL context + render loop right now
        let usingFallback = false; // permanent: reduced-motion, or WebGL unavailable/failed

        let cachedImg = null; // decoded <img>, kept across teardown/reinit cycles
        let textureReady = false;
        let stageCssW = 0, stageCssH = 0, imgNaturalW = 0, imgNaturalH = 0;
        let coverUV = [1, 1, 0, 0];

        let currentFront = 0, center = [0, 0], transitioning = false;
        let animFrom = 0, animTo = 0, animStart = 0, lastTrigger = -Infinity;
        let revealed = false;

        textLayer.inert = true;

        function ensurePoster() {
            if (!posterLayer || !posterLayer.isConnected) {
                posterLayer = document.createElement('div');
                posterLayer.className = 'wc-layer wc-poster';
                stage.insertBefore(posterLayer, stage.firstChild);
            }
            posterLayer.style.backgroundImage = `url('${imageSrc}')`;
            posterLayer.style.opacity = '1';
        }

        // Opacity and inert-ness always move together: a hidden text layer
        // must not be tabbable or clickable, and opacity: 0 alone leaves it
        // both. Setting .inert is a no-op in browsers that lack it, which
        // just returns the pre-existing behaviour.
        function setRevealedState(isRevealed) {
            textLayer.style.opacity = isRevealed ? '1' : '0';
            textLayer.inert = !isRevealed;
        }

        function resetLogicalState() {
            revealed = false;
            currentFront = 0;
            transitioning = false;
            center = [0, 0];
            lastTrigger = -Infinity;
            // Snap back with no transition — this only ever runs while the
            // card is offscreen (that's what triggered teardown), so there's
            // nothing to animate for anyone to see.
            textLayer.style.transition = 'none';
            setRevealedState(false);
            void textLayer.offsetHeight; // flush, so a later real transition isn't skipped
        }

        function crossfadeToggle() {
            revealed = !revealed;
            posterLayer.style.opacity = revealed ? '0' : '1';
            setRevealedState(revealed);
        }

        function enableFallback() {
            usingFallback = true;
            if (canvas && canvas.isConnected) canvas.remove();
            ensurePoster();
            posterLayer.style.transition = 'opacity 0.4s ease';
            textLayer.style.transition = 'opacity 0.4s ease';
        }

        function computeCoverUV() {
            if (!stageCssW || !stageCssH || !imgNaturalW || !imgNaturalH) return;
            const stageAspect = stageCssW / stageCssH;
            const imageAspect = imgNaturalW / imgNaturalH;
            if (imageAspect > stageAspect) {
                const sx = stageAspect / imageAspect;
                coverUV = [sx, 1, (1 - sx) / 2, 0];
            } else {
                const sy = imageAspect / stageAspect;
                coverUV = [1, sy, 0, (1 - sy) / 2];
            }
        }

        function syncCanvasGeometry() {
            if (!gl) return;
            const rect = stage.getBoundingClientRect();
            stageCssW = rect.width;
            stageCssH = rect.height;
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            const w = Math.max(1, Math.round(stageCssW * dpr));
            const h = Math.max(1, Math.round(stageCssH * dpr));
            if (canvas.width !== w || canvas.height !== h) {
                canvas.width = w;
                canvas.height = h;
                gl.viewport(0, 0, w, h);
            }
            computeCoverUV();
        }

        function tuning() {
            const s = Math.min(stageCssW, stageCssH) || 480;
            return {
                edgeWidth: s * 0.02,
                edgeAmp: s * 0.05,
                granAmp: 0.9,
                chromaAmp: s * 0.012,
                bleedWidth: s * 0.05,
            };
        }

        function draw(timeSec) {
            if (!gl || !textureReady) return;
            gl.clearColor(0, 0, 0, 0);
            gl.clear(gl.COLOR_BUFFER_BIT);
            gl.useProgram(program);

            const t = tuning();
            gl.uniform1i(uniforms.uImage, 0);
            gl.activeTexture(gl.TEXTURE0);
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.uniform2f(uniforms.uImageSize, imgNaturalW, imgNaturalH);
            gl.uniform2f(uniforms.uStageCss, stageCssW, stageCssH);
            gl.uniform4f(uniforms.uCoverUV, coverUV[0], coverUV[1], coverUV[2], coverUV[3]);
            gl.uniform2f(uniforms.uCenter, center[0], center[1]);
            gl.uniform1f(uniforms.uFront, currentFront);
            gl.uniform1f(uniforms.uEdgeWidth, t.edgeWidth);
            gl.uniform1f(uniforms.uEdgeAmp, t.edgeAmp);
            gl.uniform1f(uniforms.uGranAmp, t.granAmp);
            gl.uniform1f(uniforms.uChromaAmp, t.chromaAmp);
            gl.uniform1f(uniforms.uBleedWidth, t.bleedWidth);
            gl.uniform1f(uniforms.uTime, timeSec);

            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }

        function frame(nowMs) {
            if (!active) return; // stop the loop once torn down
            rafId = requestAnimationFrame(frame);
            if (transitioning) {
                const elapsed = nowMs - animStart;
                const frac = Math.min(1, elapsed / ANIM_DUR_MS);
                currentFront = animFrom + (animTo - animFrom) * ease(frac);
                if (frac >= 1) {
                    transitioning = false;
                    currentFront = animTo;
                }
            }
            draw(nowMs / 1000);
        }

        function maxRadiusFor(x, y) {
            return Math.hypot(
                Math.max(x, stageCssW - x),
                Math.max(y, stageCssH - y)
            ) * 1.15;
        }

        function triggerAt(x, y) {
            if (!active || usingFallback) return; // offscreen/inactive card ignores input
            const now = performance.now();
            if (now - lastTrigger < ANIM_DUR_MS) return;
            lastTrigger = now;

            center = [x, y];
            const revealing = !revealed;
            animFrom = currentFront;
            animTo = revealing ? maxRadiusFor(x, y) : 0;
            animStart = now;
            transitioning = true;

            const curveCss = `cubic-bezier(${CURVE.join(',')})`;
            textLayer.style.transitionProperty = 'opacity';
            textLayer.style.transitionDuration = `${ANIM_DUR_S}s`;
            textLayer.style.transitionTimingFunction = curveCss;
            setRevealedState(revealing);

            revealed = revealing;
        }

        // Every upload is routed through a 2D canvas rather than handed the
        // <img> directly. Two reasons, both load-bearing:
        //
        // 1. COLOUR. A WebGL texture upload is not colour-managed — raw decoded
        //    pixels go to the GPU and get composited as though they were sRGB.
        //    A Display-P3 or Adobe RGB photo therefore renders visibly shifted
        //    on the canvas while the poster <img> underneath it renders
        //    correctly, so swapping the poster out for the canvas produced a
        //    jarring colour pop. drawImage() into a 2D canvas converts the
        //    image into the canvas colour space (sRGB) with proper management,
        //    so what reaches the GPU already matches what the poster showed.
        //
        // 2. SIZE. Full-resolution originals routinely run past the GPU's
        //    MAX_TEXTURE_SIZE — commonly 4096 on older mobile hardware.
        //    texImage2D fails on an oversized source and leaves the card blank,
        //    its poster having already been removed. Same canvas pass caps it.
        function makeTextureSource(img) {
            const maxDim = gl.getParameter(gl.MAX_TEXTURE_SIZE) || 4096;
            const longest = Math.max(img.naturalWidth, img.naturalHeight);
            const k = longest > maxDim ? maxDim / longest : 1;

            const c = document.createElement('canvas');
            c.width = Math.max(1, Math.round(img.naturalWidth * k));
            c.height = Math.max(1, Math.round(img.naturalHeight * k));
            c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
            return c;
        }

        function uploadTexture() {
            if (!gl || !texture) return; // torn down again before this image finished loading
            const source = makeTextureSource(cachedImg);
            // Whatever actually reached the GPU, not the original — uImageSize
            // drives the wet-bleed's texel step, and computeCoverUV wants the
            // uploaded aspect (identical, since any downscale is proportional).
            imgNaturalW = source.width;
            imgNaturalH = source.height;
            computeCoverUV();
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, source);
            // Release the intermediate immediately. At full resolution this
            // backing store is tens of MB, and iOS Safari in particular is slow
            // to reclaim canvas memory on GC alone. The decoded <img> stays
            // cached, so re-activating after teardown just redraws.
            source.width = source.height = 1;
            textureReady = true;
            if (posterLayer) { posterLayer.remove(); posterLayer = null; }
        }

        function teardown() {
            if (!active) return;
            active = false;
            if (rafId) cancelAnimationFrame(rafId);
            rafId = null;
            if (sizeObserver) { sizeObserver.disconnect(); sizeObserver = null; }
            if (gl) {
                const ext = gl.getExtension('WEBGL_lose_context');
                if (ext) ext.loseContext();
            }
            // A context explicitly lost this way doesn't reliably come back on
            // the same canvas just by calling getContext() again, so the
            // reliable way to get a fresh, live context next time is a fresh
            // <canvas> element.
            if (canvas && canvas.isConnected) {
                const fresh = document.createElement('canvas');
                fresh.className = canvas.className;
                canvas.replaceWith(fresh);
                canvas = fresh;
            }
            gl = null; program = null; texture = null; uniforms = null;
            textureReady = false;
            resetLogicalState();
            ensurePoster();
        }

        function init() {
            if (active || usingFallback) return;
            active = true;

            gl = canvas.getContext('webgl', { alpha: true, premultipliedAlpha: true })
                || canvas.getContext('experimental-webgl', { alpha: true, premultipliedAlpha: true });
            if (!gl) { enableFallback(); return; }

            const vertShader = compileShader(gl, gl.VERTEX_SHADER, VERT_SRC);
            const fragShader = compileShader(gl, gl.FRAGMENT_SHADER, FRAG_SRC);
            if (!vertShader || !fragShader) { enableFallback(); return; }

            program = gl.createProgram();
            gl.attachShader(program, vertShader);
            gl.attachShader(program, fragShader);
            gl.linkProgram(program);
            if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
                console.error('Program link error:', gl.getProgramInfoLog(program));
                enableFallback();
                return;
            }
            gl.useProgram(program);

            const quadBuffer = gl.createBuffer();
            gl.bindBuffer(gl.ARRAY_BUFFER, quadBuffer);
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
                -1, -1, 1, -1, -1, 1, 1, 1
            ]), gl.STATIC_DRAW);
            const aPosition = gl.getAttribLocation(program, 'aPosition');
            gl.enableVertexAttribArray(aPosition);
            gl.vertexAttribPointer(aPosition, 2, gl.FLOAT, false, 0, 0);

            uniforms = {};
            ['uImage', 'uImageSize', 'uStageCss', 'uCoverUV', 'uCenter', 'uFront', 'uEdgeWidth',
                'uEdgeAmp', 'uGranAmp', 'uChromaAmp', 'uBleedWidth', 'uTime'
            ].forEach((name) => { uniforms[name] = gl.getUniformLocation(program, name); });

            texture = gl.createTexture();
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, 1, 1, 0, gl.RGBA, gl.UNSIGNED_BYTE, new Uint8Array([0, 0, 0, 0]));

            gl.enable(gl.BLEND);
            gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA);

            textureReady = false;
            sizeObserver = new ResizeObserver(syncCanvasGeometry);
            sizeObserver.observe(stage);
            syncCanvasGeometry();

            if (cachedImg && cachedImg.complete && cachedImg.naturalWidth) {
                uploadTexture();
            } else if (!cachedImg) {
                cachedImg = new Image();
                cachedImg.onload = uploadTexture;
                cachedImg.onerror = function () {
                    console.error('Watercolor reveal: image failed to load:', imageSrc);
                    enableFallback();
                };
                cachedImg.src = imageSrc;
            }
            // else: cachedImg exists and is still loading from an earlier
            // activation — its onload (already pointed at uploadTexture) will
            // fire and pick up whatever gl/texture are current at that point.

            rafId = requestAnimationFrame(frame);
        }

        if (reduceMotion) {
            enableFallback();
            stage.addEventListener('click', function (e) {
                if (e.target.closest('.wc-read-more')) return;
                crossfadeToggle();
            });
            stage.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); crossfadeToggle(); }
            });
            return; // no WebGL, no lazy-load/teardown machinery needed at all
        }

        stage.addEventListener('click', (e) => {
            // Let the read-more link do its job without also firing a reveal.
            if (e.target.closest('.wc-read-more')) return;
            const rect = stage.getBoundingClientRect();
            triggerAt(e.clientX - rect.left, e.clientY - rect.top);
        });
        stage.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                triggerAt(stageCssW / 2, stageCssH / 2);
            }
        });

        const io = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    init();
                    if (usingFallback) { io.disconnect(); return; } // WebGL failed — no lazy management needed
                } else {
                    teardown();
                }
            }
        }, { rootMargin: ROOT_MARGIN, threshold: 0 });
        io.observe(stage);
    }

    /* ---------------------------------------------------------------
       Video cards — not interactive. They load deferred and loop; there
       is no reveal, no canvas and no click target on them.

       Same mechanic as the homepage hero (preload="none" + data-src,
       save-data guard), but gated per card by IntersectionObserver
       instead of a single page-load timer, since a grid can hold many
       video cards at once.
    --------------------------------------------------------------- */
    function loadCardVideo(card) {
        const video = card.querySelector('video[data-src]');
        if (!video) return;
        if (!video.canPlayType || !video.canPlayType('video/mp4')) return;
        const conn = navigator.connection;
        if (conn && (conn.saveData || /^(slow-2g|2g)$/.test(conn.effectiveType || ''))) return;

        video.src = video.dataset.src;
        video.addEventListener('canplaythrough', function () {
            card.classList.add('video-loaded'); // fades the poster out via CSS
        });
        video.addEventListener('ended', function () { video.currentTime = 0; video.play(); });
        video.addEventListener('pause', function () {
            if (card.classList.contains('video-loaded')) video.play();
        });
        video.addEventListener('error', function () { card.classList.remove('video-loaded'); });

        video.load();
        const p = video.play();
        if (p && p.catch) p.catch(function () { });
    }

    function boot() {
        const grid = document.getElementById('photoFloatingGrid');
        if (!grid) return;

        grid.querySelectorAll('.photo-card:not(.photo-card-video) .wc-stage')
            .forEach(setupCard);

        const videoCards = grid.querySelectorAll('.photo-card-video');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        loadCardVideo(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '200px 0px' });
            videoCards.forEach(function (card) { observer.observe(card); });
        } else {
            videoCards.forEach(loadCardVideo);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
