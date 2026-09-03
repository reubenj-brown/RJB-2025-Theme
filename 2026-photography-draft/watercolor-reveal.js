/* ============================================================
   Watercolor Reveal — photography draft 2026
   ============================================================

   Ported verbatim (bar selectors) from watercolor-reveal-gallery.html,
   the canonical reference implementation. Clicking a card dissolves the
   photo away from the click point with an organic, pigment-like edge,
   revealing the caption underneath over ANIM_DUR_S seconds; clicking again
   flows it back. Each card also carries an "expand" control that opens the
   full-size original in the site's shared .photo-lightbox.

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

    // Both directions share this and the curve below — deliberately, per the
    // design reference. It also sets the non-interruptible window, since a
    // trigger is ignored until the previous animation has run its course.
    const ANIM_DUR_S = 4;
    const ANIM_DUR_MS = ANIM_DUR_S * 1000;

    // Fast to leave, slow to settle. The reference curve was
    // cubic-bezier(0.65, 0, 0.35, 1), a symmetric ease-in-out, and its flat
    // start meant a click appeared to do nothing: measured on a 400x500 card
    // it advanced 0.14% in the first ten frames — 0.6px of a 409px travel —
    // and the first pixel didn't change for 488ms. Those figures are ratios
    // of the travel, so they held at every card size.
    //
    // This curve reaches 11.6% by frame ten and breaks the first pixel within
    // one, while still spending the bulk of the four seconds on the slow
    // spread. It stays a single constant used by both directions, the caption
    // fade and the lightbox backdrop, so the design reference's rule that
    // every part of the transition shares one curve still holds.
    const CURVE = [0.25, 0.7, 0.35, 1];

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

    // Fraction of the duration at which the eased value first reaches `y`.
    // Used to find when a transition stops changing anything on screen.
    function easeInverse(y) {
        if (y <= 0) return 0;
        if (y >= 1) return 1;
        let lo = 0, hi = 1;
        for (let i = 0; i < 24; i++) {
            const mid = (lo + hi) / 2;
            if (ease(mid) < y) lo = mid; else hi = mid;
        }
        return (lo + hi) / 2;
    }

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

          // Each channel is thresholded against a slightly different radius,
          // and where the three disagree the output reads as a tinted fringe.
          // Which way it tints is decided here: the channel given the SMALLER
          // front survives furthest into the boundary, so that channel is the
          // colour of the fringe.
          //
          // Red gets the smaller front, blue the larger — so blue lifts off
          // first and red stains longest, and the halo runs warm. (Swapping
          // these two lines is what turns it cool again.) It's also the more
          // faithful behaviour: in real watercolour the warm earth pigments
          // granulate and stain the paper while cooler ones lift more readily.
          float dR = dist - (uFront - chroma);
          float dG = dist - uFront;
          float dB = dist - (uFront + chroma);

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

    const UNIFORM_NAMES = [
        'uImage', 'uImageSize', 'uStageCss', 'uCoverUV', 'uCenter', 'uFront',
        'uEdgeWidth', 'uEdgeAmp', 'uGranAmp', 'uChromaAmp', 'uBleedWidth', 'uTime'
    ];

    // Absolute ceiling on the long edge of any texture, as a backstop against
    // a pathologically large original. The real limit is per-surface: each
    // caller asks for the number of pixels its own canvas can actually show
    // (see textureEdgeFor / the lightbox's applyTexture), so nothing is
    // uploaded larger than it will be displayed.
    //
    // This used to be a flat 1920 shared by the cards and the lightbox, which
    // meant a full-screen photo on a retina display was a 1920px texture
    // stretched across a ~2800px canvas — the whole point of fetching the
    // original was being thrown away on upload.
    const MAX_TEXTURE_EDGE = 4096;

    // Device pixels per CSS pixel, capped at 2. Above 2 the extra resolution
    // is past what the effect resolves and the fill-rate cost is real, and
    // syncCanvasSize() caps the backing store the same way — so the texture
    // budget has to use the same number or it oversamples for nothing.
    function pixelRatio() {
        return Math.min(window.devicePixelRatio || 1, 2);
    }

    // How many texture pixels a surface of this CSS size actually needs.
    // `headroom` buys a margin so an ordinary window resize doesn't
    // immediately undersample and force a re-upload.
    function textureEdgeFor(cssW, cssH, headroom) {
        return Math.ceil(Math.max(cssW, cssH) * pixelRatio() * (headroom || 1));
    }

    /* ---------------------------------------------------------------
       Shared WebGL plumbing. The cards and the lightbox run the same
       shader over the same quad and differ only in what drives uFront,
       so everything from context creation to the draw call lives here
       once rather than in each controller.
    --------------------------------------------------------------- */

    // Returns {gl, program, uniforms, texture} or null if WebGL is unavailable
    // or the program won't build. Callers fall back to a plain crossfade.
    function createWatercolorContext(canvas) {
        const gl = canvas.getContext('webgl', { alpha: true, premultipliedAlpha: true })
            || canvas.getContext('experimental-webgl', { alpha: true, premultipliedAlpha: true });
        if (!gl) return null;

        const vertShader = compileShader(gl, gl.VERTEX_SHADER, VERT_SRC);
        const fragShader = compileShader(gl, gl.FRAGMENT_SHADER, FRAG_SRC);
        if (!vertShader || !fragShader) return null;

        const program = gl.createProgram();
        gl.attachShader(program, vertShader);
        gl.attachShader(program, fragShader);
        gl.linkProgram(program);
        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
            console.error('Program link error:', gl.getProgramInfoLog(program));
            return null;
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

        const uniforms = {};
        UNIFORM_NAMES.forEach((name) => { uniforms[name] = gl.getUniformLocation(program, name); });

        const texture = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, 1, 1, 0, gl.RGBA, gl.UNSIGNED_BYTE, new Uint8Array([0, 0, 0, 0]));

        gl.enable(gl.BLEND);
        gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA);

        return { gl, program, uniforms, texture };
    }

    // Every upload is routed through a 2D canvas rather than handed the <img>
    // directly. Two reasons, both load-bearing:
    //
    // 1. COLOUR. A WebGL texture upload is not colour-managed — raw decoded
    //    pixels go to the GPU and get composited as though they were sRGB.
    //    A Display-P3 or Adobe RGB photo therefore renders visibly shifted
    //    against a colour-managed <img> of the same file. drawImage() into a
    //    2D canvas converts into the canvas colour space (sRGB) properly, so
    //    what reaches the GPU matches what the browser would have painted.
    //
    // 2. SIZE. The same pass resamples down to the caller's pixel budget, and
    //    clamps that against the GPU's MAX_TEXTURE_SIZE (commonly 4096 on
    //    older mobile). That second limit matters because texImage2D fails
    //    outright on an oversized source, leaving nothing drawn at all.
    //
    // `maxEdge` is the caller's own budget — the long edge, in texture pixels,
    // that its canvas can actually display. It is clamped by the GPU limit and
    // by MAX_TEXTURE_EDGE, and never upscales: a source smaller than the budget
    // is uploaded as-is.
    //
    // Returns the uploaded {width, height}, which is what the caller must use
    // for uImageSize and the cover-fit maths — not the original's dimensions.
    function uploadImage(ctx, img, maxEdge) {
        const { gl, texture } = ctx;
        const hardCap = Math.min(gl.getParameter(gl.MAX_TEXTURE_SIZE) || 4096, MAX_TEXTURE_EDGE);
        const maxDim = Math.max(1, Math.min(hardCap, maxEdge || hardCap));
        const longest = Math.max(img.naturalWidth, img.naturalHeight);
        const k = longest > maxDim ? maxDim / longest : 1;

        const c = document.createElement('canvas');
        c.width = Math.max(1, Math.round(img.naturalWidth * k));
        c.height = Math.max(1, Math.round(img.naturalHeight * k));
        const c2d = c.getContext('2d');
        // The default smoothing quality is 'low' — a cheap bilinear tap that
        // discards most of the source when the reduction is more than about
        // 2x, which is exactly what a 1920px file drawn into a ~900px card
        // does. On a detailed photograph that reads as aliasing, and aliasing
        // reads as compression. 'high' asks for a properly filtered resample.
        c2d.imageSmoothingEnabled = true;
        c2d.imageSmoothingQuality = 'high';
        c2d.drawImage(img, 0, 0, c.width, c.height);

        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, c);

        const size = { width: c.width, height: c.height };
        // Release the intermediate immediately. At full resolution this backing
        // store is tens of MB and iOS Safari is slow to reclaim it on GC alone.
        c.width = c.height = 1;
        return size;
    }

    // Cover-fit UV transform: [scaleX, scaleY, offsetX, offsetY].
    function coverUVFor(cssW, cssH, imgW, imgH) {
        if (!cssW || !cssH || !imgW || !imgH) return [1, 1, 0, 0];
        const stageAspect = cssW / cssH;
        const imageAspect = imgW / imgH;
        if (imageAspect > stageAspect) {
            const sx = stageAspect / imageAspect;
            return [sx, 1, (1 - sx) / 2, 0];
        }
        const sy = imageAspect / stageAspect;
        return [1, sy, 0, (1 - sy) / 2];
    }

    // Edge/granulation/chroma/bleed amounts, all scaled off the surface's
    // short side so the look holds from a small card up to a full-screen
    // lightbox. Set by eye against a handful of test photos.
    function tuningFor(cssW, cssH) {
        const s = Math.min(cssW, cssH) || 480;
        return {
            edgeWidth: s * 0.02,
            edgeAmp: s * 0.05,
            granAmp: 0.9,
            chromaAmp: s * 0.012,
            bleedWidth: s * 0.05,
        };
    }

    // view: {imgW, imgH, cssW, cssH, coverUV, center, front}
    function drawWatercolor(ctx, view, timeSec) {
        const { gl, program, uniforms, texture } = ctx;
        gl.clearColor(0, 0, 0, 0);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.useProgram(program);

        const t = tuningFor(view.cssW, view.cssH);
        gl.uniform1i(uniforms.uImage, 0);
        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, texture);
        gl.uniform2f(uniforms.uImageSize, view.imgW, view.imgH);
        gl.uniform2f(uniforms.uStageCss, view.cssW, view.cssH);
        gl.uniform4f(uniforms.uCoverUV, view.coverUV[0], view.coverUV[1], view.coverUV[2], view.coverUV[3]);
        gl.uniform2f(uniforms.uCenter, view.center[0], view.center[1]);
        gl.uniform1f(uniforms.uFront, view.front);
        gl.uniform1f(uniforms.uEdgeWidth, t.edgeWidth);
        gl.uniform1f(uniforms.uEdgeAmp, t.edgeAmp);
        gl.uniform1f(uniforms.uGranAmp, t.granAmp);
        gl.uniform1f(uniforms.uChromaAmp, t.chromaAmp);
        gl.uniform1f(uniforms.uBleedWidth, t.bleedWidth);
        gl.uniform1f(uniforms.uTime, timeSec);

        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    }

    // Backing-store size for a CSS-pixel surface, DPR capped at 2.
    function syncCanvasSize(gl, canvas, cssW, cssH) {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const w = Math.max(1, Math.round(cssW * dpr));
        const h = Math.max(1, Math.round(cssH * dpr));
        if (canvas.width !== w || canvas.height !== h) {
            canvas.width = w;
            canvas.height = h;
            gl.viewport(0, 0, w, h);
        }
    }

    // The "fully opaque" value for uFront.
    //
    // Zero is NOT fully opaque. alpha = smoothstep(-edge, +edge, dist - front),
    // and dist is measured from uCenter, so at front = 0 the pixels around the
    // centre point have dist ≈ 0 and land inside the transition band — leaving
    // a small noisy translucent notch there (at the top-left corner of a card
    // at rest, since uCenter starts at 0,0; dead centre of a lightboxed photo).
    //
    // Pushing the front negative by more than the worst-case excursion clears
    // the band for every pixel, so the smoothstep saturates at 1 everywhere:
    //   dist  is displaced by up to  1.0 * edgeAmp   (fbm's negative extreme)
    //   the band's top edge reaches  1.9 * edgeWidth (granulation wobble)
    //   the blue channel is offset a further chromaAmp
    // 2x edgeWidth covers the 1.9 with a little margin.
    function restFrontFor(cssW, cssH) {
        const t = tuningFor(cssW, cssH);
        return -(t.edgeAmp + t.chromaAmp + 2 * t.edgeWidth);
    }

    // The front past which nothing more can change: every pixel is already
    // fully clear once the front has passed the furthest corner plus the
    // worst-case noise displacement, granulation wobble and chroma offset.
    //
    // maxRadiusFrom() deliberately overshoots this by 15% so the ragged edge
    // clears the corners under any noise. That overshoot is invisible, but on
    // an ease-out curve it is not cheap: the last couple of percent of travel
    // eats the final second of a four-second animation. Knowing where the
    // picture is actually finished lets the transition stop there.
    function clearFrontFor(cssW, cssH, cx, cy) {
        const t = tuningFor(cssW, cssH);
        const d = Math.hypot(Math.max(cx, cssW - cx), Math.max(cy, cssH - cy));
        return d + t.edgeAmp + t.chromaAmp + 1.9 * t.edgeWidth;
    }

    // How long a run from `from` to `to` needs before it stops changing
    // anything, given it stops being visible once it passes `doneAt`.
    // Returns the full duration when the whole run matters.
    function visibleDurationMs(from, to, doneAt) {
        const span = to - from;
        if (!span) return ANIM_DUR_MS;
        const need = (doneAt - from) / span;
        // Never below a frame — a zero-length transition would read as a snap.
        return Math.max(1000 / 60, ANIM_DUR_MS * Math.min(1, easeInverse(need)));
    }

    // The furthest any point of a wxh surface sits from (x, y), plus headroom
    // so the noise-displaced edge still clears the corners.
    function maxRadiusFrom(x, y, w, h) {
        return Math.hypot(Math.max(x, w - x), Math.max(y, h - y)) * 1.15;
    }

    // How far outside the actual viewport (in CSS px) a card activates
    // before becoming visible, and stays active after leaving — a buffer
    // so cards don't visibly pop in/out right at the viewport edge, and so
    // small scroll jitter right at the boundary doesn't thrash init/teardown.
    const ROOT_MARGIN = '400px 0px 400px 0px';

    // Card textures are uploaded a little larger than the card currently is,
    // so the common small window resize costs nothing. The lightbox doesn't
    // take this margin: it is already at the size budget's ceiling, and it
    // re-uploads on resize instead.
    const CARD_TEXTURE_HEADROOM = 1.2;

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

        let ctx = null; // {gl, program, uniforms, texture} while active
        let sizeObserver = null;
        let rafId = null;
        let active = false;       // has a live WebGL context + render loop right now
        let usingFallback = false; // permanent: reduced-motion, or WebGL unavailable/failed

        let cachedImg = null; // decoded <img>, kept across teardown/reinit cycles
        let textureReady = false;
        let uploadedEdge = 0; // long edge of whatever is currently on the GPU
        let stageCssW = 0, stageCssH = 0, imgNaturalW = 0, imgNaturalH = 0;
        let coverUV = [1, 1, 0, 0];

        // Corrected to restFrontFor() by syncCanvasGeometry() before the first
        // draw — nothing is drawn until a texture is up, and that's later still.
        let currentFront = 0, center = [0, 0], transitioning = false;
        let animFrom = 0, animTo = 0, animStart = 0, lockUntil = -Infinity;
        let animCutMs = ANIM_DUR_MS; // when this run stops changing anything
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
            currentFront = restFrontFor(stageCssW, stageCssH);
            transitioning = false;
            center = [0, 0];
            lockUntil = -Infinity;
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
            coverUV = coverUVFor(stageCssW, stageCssH, imgNaturalW, imgNaturalH);
        }

        function syncCanvasGeometry() {
            if (!ctx) return;
            const rect = stage.getBoundingClientRect();
            stageCssW = rect.width;
            stageCssH = rect.height;
            syncCanvasSize(ctx.gl, canvas, stageCssW, stageCssH);
            computeCoverUV();
            // Rest position depends on the tuning, which scales off the card's
            // size — so it has to be recomputed whenever that size changes.
            if (!revealed && !transitioning) currentFront = restFrontFor(stageCssW, stageCssH);

            // A widened window (or a rotation) can make the card bigger than
            // the texture that was uploaded for it. Re-upload only when the
            // shortfall is real AND the source file actually has the extra
            // pixels, so a drag-resize doesn't re-upload on every frame and a
            // card that is already showing its source 1:1 never re-uploads.
            if (textureReady && cachedImg) {
                const want = textureEdgeFor(stageCssW, stageCssH, CARD_TEXTURE_HEADROOM);
                const available = Math.max(cachedImg.naturalWidth, cachedImg.naturalHeight);
                if (want > uploadedEdge * 1.1 && uploadedEdge < available) uploadTexture();
            }

            scheduleFrame();
        }

        function draw(timeSec) {
            if (!ctx || !textureReady) return;
            drawWatercolor(ctx, {
                imgW: imgNaturalW, imgH: imgNaturalH,
                cssW: stageCssW, cssH: stageCssH,
                coverUV: coverUV, center: center, front: currentFront,
            }, timeSec);
        }

        // Draw only when something has actually changed. The shader evaluates
        // 12 octaves of 3D simplex noise per pixel, and an idle card was
        // paying that every frame forever to redraw an identical image: at
        // rest the front is either fully in or fully out, so the noise fields
        // are saturated and invisible. They only show at a MOVING boundary,
        // which only exists mid-transition.
        function scheduleFrame() {
            if (!active || rafId !== null) return;
            rafId = requestAnimationFrame(frame);
        }

        function frame(nowMs) {
            rafId = null;
            if (!active) return; // torn down mid-flight
            if (transitioning) {
                const elapsed = nowMs - animStart;
                // frac is measured against the FULL duration, not the cut, so
                // the trajectory is identical to an uncut run — the cut only
                // decides when to stop, never how fast to move.
                const frac = Math.min(1, elapsed / ANIM_DUR_MS);
                currentFront = animFrom + (animTo - animFrom) * ease(frac);
                if (elapsed >= animCutMs) {
                    transitioning = false;
                    // Jump to the nominal end. Everything between here and
                    // there is already fully dissolved, so this is invisible,
                    // and it leaves the state exactly where an uncut run would
                    // have left it for the return trip.
                    currentFront = animTo;
                }
            }
            draw(nowMs / 1000);
            if (transitioning) scheduleFrame();
        }

        function triggerAt(x, y) {
            if (!active || usingFallback) return; // offscreen/inactive card ignores input
            const now = performance.now();
            if (now < lockUntil) return;

            center = [x, y];
            const revealing = !revealed;
            animFrom = currentFront;
            animTo = revealing
                ? maxRadiusFrom(x, y, stageCssW, stageCssH)
                : restFrontFor(stageCssW, stageCssH);

            // Revealing overshoots the corners by 15% so the ragged edge always
            // clears them, and the last of that is invisible — on this curve it
            // was costing about a second of every four. Stop when the picture
            // is actually gone. Returning ends exactly at the resting front, so
            // there's nothing spare to trim.
            animCutMs = revealing
                ? visibleDurationMs(animFrom, animTo, clearFrontFor(stageCssW, stageCssH, x, y))
                : ANIM_DUR_MS;

            animStart = now;
            // The card unlocks when it stops moving, not when the nominal
            // duration is up — waiting out invisible frames was what stopped
            // people closing a card the moment its caption had arrived.
            lockUntil = now + animCutMs;
            transitioning = true;
            scheduleFrame();

            const curveCss = `cubic-bezier(${CURVE.join(',')})`;
            textLayer.style.transitionProperty = 'opacity';
            // Matched to the trimmed run so the caption lands with the photo
            // rather than still creeping in after it.
            textLayer.style.transitionDuration = `${(animCutMs / 1000).toFixed(3)}s`;
            textLayer.style.transitionTimingFunction = curveCss;
            setRevealedState(revealing);

            revealed = revealing;
        }

        function uploadTexture() {
            if (!ctx) return; // torn down again before this image finished loading
            // Upload at the card's own display resolution rather than the
            // file's. A 1920px source minified onto a ~900px canvas by the
            // sampler (LINEAR, and WebGL 1 can't mipmap a non-power-of-two
            // texture) aliased badly; resampling once, properly, on the way in
            // and then sampling near 1:1 is both sharper and a fraction of the
            // texture memory.
            //
            // Whatever actually reached the GPU, not the original — uImageSize
            // drives the wet-bleed's texel step, and the cover-fit maths wants
            // the uploaded aspect (identical, since the cap is proportional).
            const size = uploadImage(
                ctx, cachedImg, textureEdgeFor(stageCssW, stageCssH, CARD_TEXTURE_HEADROOM)
            );
            imgNaturalW = size.width;
            imgNaturalH = size.height;
            uploadedEdge = Math.max(size.width, size.height);
            computeCoverUV();
            textureReady = true;
            if (posterLayer) { posterLayer.remove(); posterLayer = null; }
            scheduleFrame();
        }

        function teardown() {
            if (!active) return;
            active = false;
            if (rafId) cancelAnimationFrame(rafId);
            rafId = null;
            if (sizeObserver) { sizeObserver.disconnect(); sizeObserver = null; }
            if (ctx) {
                const ext = ctx.gl.getExtension('WEBGL_lose_context');
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
            ctx = null;
            textureReady = false;
            uploadedEdge = 0;
            resetLogicalState();
            ensurePoster();
        }

        function init() {
            if (active || usingFallback) return;
            active = true;

            ctx = createWatercolorContext(canvas);
            if (!ctx) { enableFallback(); return; }

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
            // fire and pick up whichever context is current at that point.

            scheduleFrame();
        }

        if (reduceMotion) {
            enableFallback();
            stage.addEventListener('click', function (e) {
                if (e.target.closest('.wc-card-actions')) return;
                crossfadeToggle();
            });
            stage.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); crossfadeToggle(); }
            });
            return; // no WebGL, no lazy-load/teardown machinery needed at all
        }

        stage.addEventListener('click', (e) => {
            // Let the card's own controls do their job without also firing a
            // reveal underneath them.
            if (e.target.closest('.wc-card-actions')) return;
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

    /* ---------------------------------------------------------------
       Lightbox — the same shader, driven the other way round.

       On a card, uFront GROWS from the click point, so the region inside
       it becomes transparent and the photo dissolves away. Run that in
       reverse — uFront shrinking from the far corner back to the centre —
       and the photo floods in from the outside edges instead. That's the
       whole trick here: opening animates uFront max -> 0, dismissing
       animates 0 -> max, and no shader change was needed for either.

       Centre is the middle of the frame rather than a click point, so
       both directions stay symmetrical.

       Reuses the site's .photo-lightbox component for the backdrop and
       close button; the figure and canvas are this page's addition.
    --------------------------------------------------------------- */
    function setupLightbox(grid) {
        const lightbox = document.getElementById('photoLightbox');
        if (!lightbox) return;
        const figure = lightbox.querySelector('.photo-lightbox-figure');
        const fallbackImg = lightbox.querySelector('.photo-lightbox-image');
        const canvas = lightbox.querySelector('.photo-lightbox-gl');
        const buttons = Array.prototype.slice.call(grid.querySelectorAll('.wc-expand'));
        if (!figure || !fallbackImg || !buttons.length) return;

        let ctx = null;
        let useGL = !reduceMotion && !!canvas;
        let isOpen = false, index = -1, lastFocused = null;
        let textureReady = false, rafId = null;
        let imgW = 0, imgH = 0, cssW = 0, cssH = 0, coverUV = [1, 1, 0, 0];
        let uploadedImg = null; // source behind the current texture, for re-upload on resize
        let front = 0, transitioning = false;
        let animFrom = 0, animTo = 0, animStart = 0, onSettled = null;
        let animCutMs = ANIM_DUR_MS;
        let pendingDismiss = false; // a click that arrived mid-animation
        let generation = 0; // guards against a slow full-res load landing late

        const imgCache = Object.create(null);

        // The backdrop is driven from this file's own constants so it moves
        // with the dissolve rather than merely alongside it: same duration,
        // and the same curve. Matching the curve matters as much as the
        // duration — two transitions of equal length on different curves
        // still visibly run apart, which is what made the wash look like it
        // was leading the dissolve.
        const FADE_QUICK = '0.25s';
        lightbox.style.setProperty('--lightbox-ease', 'cubic-bezier(' + CURVE.join(',') + ')');

        function setFade(seconds) {
            lightbox.style.setProperty('--lightbox-fade', seconds);
        }

        function loadImage(url) {
            return new Promise(function (resolve, reject) {
                let img = imgCache[url];
                if (img) {
                    if (img.complete && img.naturalWidth) { resolve(img); return; }
                } else {
                    img = new Image();
                    imgCache[url] = img;
                    img.src = url;
                }
                img.addEventListener('load', function () { resolve(img); }, { once: true });
                img.addEventListener('error', reject, { once: true });
            });
        }

        function fitFigure(w, h) {
            // The figure takes the photo's own aspect ratio inside the
            // 96vw/96vh box, so the canvas matches the image exactly and the
            // shader's cover-fit becomes a no-op — nothing is cropped.
            figure.style.setProperty('--lb-ratio', (w / h).toFixed(4));
            const rect = figure.getBoundingClientRect();
            cssW = rect.width;
            cssH = rect.height;
        }

        // Fit the frame BEFORE uploading. The figure's box comes from the
        // photo's aspect ratio, which the texture budget never changes — so
        // fitting first gives the real CSS size the canvas is about to have,
        // and therefore how many pixels the texture actually needs.
        //
        // This is the fix for the soft lightbox: the upload used to be capped
        // at the cards' 1920, so a full-screen photo on a retina display was a
        // 1920px texture magnified across a ~2800px canvas. The full-size file
        // was already on the wire; it just wasn't reaching the GPU.
        function applyTexture(img) {
            fitFigure(img.naturalWidth, img.naturalHeight);
            const size = uploadImage(ctx, img, textureEdgeFor(cssW, cssH));
            imgW = size.width;
            imgH = size.height;
            uploadedImg = img;
            syncCanvasSize(ctx.gl, canvas, cssW, cssH);
            coverUV = coverUVFor(cssW, cssH, imgW, imgH);
            textureReady = true;
            scheduleFrame();
        }

        function maxRadius() {
            return maxRadiusFrom(cssW / 2, cssH / 2, cssW, cssH);
        }

        function restFront() {
            return restFrontFor(cssW, cssH);
        }

        function scheduleFrame() {
            if (!isOpen || rafId !== null) return;
            rafId = requestAnimationFrame(frame);
        }

        function animate(to, done, cutMs) {
            animFrom = front;
            animTo = to;
            animCutMs = cutMs || ANIM_DUR_MS;
            animStart = performance.now();
            transitioning = true;
            onSettled = done || null;
            scheduleFrame();
        }

        // Same demand-driven rule as the cards: an idle lightbox is showing a
        // saturated front, so the noise fields have nothing to move.
        function frame(nowMs) {
            rafId = null;
            if (!isOpen) return;
            if (transitioning) {
                const elapsed = nowMs - animStart;
                // Eased against the full duration whatever the cut, so trimming
                // the tail never alters the speed of what's actually seen.
                const frac = Math.min(1, elapsed / ANIM_DUR_MS);
                front = animFrom + (animTo - animFrom) * ease(frac);
                if (elapsed >= animCutMs) {
                    transitioning = false;
                    front = animTo;
                    const done = onSettled;
                    onSettled = null;
                    if (done) done();
                    // A click that landed while this was still running. If
                    // `done` was finalise(), isOpen is already false and there's
                    // nothing left to dismiss.
                    if (pendingDismiss && isOpen) {
                        pendingDismiss = false;
                        beginDismiss();
                    }
                }
            }
            if (textureReady && isOpen) {
                drawWatercolor(ctx, {
                    imgW: imgW, imgH: imgH,
                    cssW: cssW, cssH: cssH,
                    coverUV: coverUV, center: [cssW / 2, cssH / 2], front: front,
                }, nowMs / 1000);
            }
            if (transitioning) scheduleFrame();
        }

        // Shows image `i`. `animateIn` is true when entering the lightbox and
        // false when arrowing between photos, which swaps straight to the next
        // one — a 4s dissolve per keypress would make browsing unusable.
        function show(i, animateIn) {
            index = (i + buttons.length) % buttons.length; // wraps both ways
            const btn = buttons[index];
            const gen = ++generation;

            const alt = btn.dataset.alt || '';
            fallbackImg.alt = alt;
            if (canvas) {
                // The fallback <img> is visibility:hidden while the canvas is
                // driving, which takes it out of the accessibility tree — so
                // the canvas has to carry the description itself.
                canvas.setAttribute('role', 'img');
                canvas.setAttribute('aria-label', alt || 'Photograph');
            }

            if (!useGL) {
                fallbackImg.src = btn.dataset.full;
                // The GL path sizes the figure in applyTexture(); this path
                // still needs the frame to match the photo.
                loadImage(btn.dataset.full).then(function (img) {
                    if (gen !== generation) return;
                    fitFigure(img.naturalWidth, img.naturalHeight);
                }).catch(function () {
                    console.error('Lightbox: image failed to load:', btn.dataset.full);
                });
                return;
            }

            textureReady = false;
            // The card's own file is already in the browser cache, so the
            // animation can start immediately on that; the full-size original
            // is fetched in parallel and swapped in underneath once decoded.
            //
            // The gallery now serves cards at full size, so the two are
            // usually the same file — in which case the first load is the
            // whole story and the second is skipped rather than re-uploading
            // an identical texture. The two-stage path stays for any
            // attachment where they do differ.
            const twoStage = btn.dataset.full !== btn.dataset.card;
            loadImage(btn.dataset.card).then(function (img) {
                if (gen !== generation || !isOpen) return;
                applyTexture(img);
                if (animateIn) {
                    front = maxRadius();
                    animate(restFront());
                } else {
                    front = restFront();
                }
            }).catch(function () {
                // On the two-stage path the full-size load below may still
                // rescue it; when there is no second stage this is the failure.
                if (!twoStage) console.error('Lightbox: image failed to load:', btn.dataset.card);
            });

            if (!twoStage) return;

            loadImage(btn.dataset.full).then(function (img) {
                if (gen !== generation || !isOpen) return;
                const wasReady = textureReady;
                applyTexture(img);
                // Straight swap of the same picture at higher resolution —
                // don't disturb an animation already in flight.
                if (!wasReady) {
                    front = animateIn ? maxRadius() : restFront();
                    if (animateIn) animate(restFront());
                }
            }).catch(function () {
                console.error('Lightbox: image failed to load:', btn.dataset.full);
            });
        }

        function open(i) {
            if (isOpen) return;
            if (useGL && !ctx) {
                ctx = createWatercolorContext(canvas);
                if (!ctx) useGL = false; // no WebGL — plain image from here on
            }
            figure.classList.toggle('wc-gl-active', useGL);

            lastFocused = document.activeElement;
            isOpen = true;
            setFade(useGL ? ANIM_DUR_S + 's' : FADE_QUICK);
            lightbox.classList.add('active');
            lightbox.focus(); // tabindex="-1" on the overlay

            front = restFront(); // show() resets it properly once sized
            transitioning = false;
            onSettled = null;
            pendingDismiss = false;
            show(i, true);
            if (useGL) scheduleFrame();
        }

        // Clears state once the exit has finished playing. The backdrop is
        // already mid-fade by the time this runs on an animated dismissal.
        function finalise() {
            isOpen = false;
            transitioning = false;
            onSettled = null;
            pendingDismiss = false;
            generation++; // orphan any in-flight image loads
            if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
            textureReady = false;
            uploadedImg = null;
            lightbox.classList.remove('active'); // no-op if already removed
            fallbackImg.src = '';
            index = -1;
            // Back where it came from rather than the top of the document. The
            // button lives in a text layer that may have been hidden or torn
            // down while the lightbox was up, hence the guard.
            if (lastFocused && lastFocused.isConnected) lastFocused.focus();
            lastFocused = null;
        }

        // Clicking anywhere plays the dissolve in reverse and closes once it
        // lands. A click that arrives while an animation is still running is
        // queued rather than dropped, so it always ends in a close — the
        // animation isn't cut short, for the same reason the cards are
        // non-interruptible: reversing without tracking true mid-flight state
        // reads as a snap. Escape still closes instantly at any point.
        function dismissAnimated() {
            if (!isOpen) return;
            if (!useGL || !textureReady) { closeNow(); return; }
            if (transitioning) { pendingDismiss = true; return; }
            beginDismiss();
        }

        // .active comes off at the START of the dissolve, not the end, so the
        // backdrop fades out across the same window the photo dissolves in —
        // one transition rather than the photo finishing and the mask then
        // fading separately. The fade duration matches, so the canvas stays
        // visible for the whole animation.
        function beginDismiss() {
            // Same trim as a card reveal, and it matters more here: the overlay
            // can't actually close until the run ends, so the invisible tail
            // was leaving a blank wash on screen for about a second after the
            // photo had gone.
            const to = maxRadius();
            const cut = visibleDurationMs(front, to, clearFrontFor(cssW, cssH, cssW / 2, cssH / 2));
            // The backdrop has to fade over the trimmed window too, or it would
            // still be fading after the overlay had been torn down.
            setFade((cut / 1000).toFixed(3) + 's');
            lightbox.classList.remove('active');
            animate(to, finalise, cut);
        }

        // Escape: out immediately, on a short fade rather than the full four
        // seconds. The duration is reset on the next open.
        function closeNow() {
            setFade(FADE_QUICK);
            finalise();
        }

        buttons.forEach(function (btn, i) {
            btn.addEventListener('click', function () { open(i); });
        });

        lightbox.addEventListener('click', dismissAnimated);

        document.addEventListener('keydown', function (e) {
            if (!isOpen) return;
            if (e.key === 'Escape') { closeNow(); }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); show(index - 1, false); }
            else if (e.key === 'ArrowRight') { e.preventDefault(); show(index + 1, false); }
        });

        window.addEventListener('resize', function () {
            if (!isOpen || !useGL || !textureReady) return;
            // A window that grew (or a rotation into landscape) can outrun the
            // texture uploaded for the old frame. applyTexture() refits and
            // re-uploads in one pass; otherwise just refit, which is free.
            const want = textureEdgeFor(cssW, cssH);
            const available = uploadedImg
                ? Math.max(uploadedImg.naturalWidth, uploadedImg.naturalHeight)
                : 0;
            if (uploadedImg && want > Math.max(imgW, imgH) * 1.1 && Math.max(imgW, imgH) < available) {
                applyTexture(uploadedImg);
            } else {
                fitFigure(imgW, imgH);
                syncCanvasSize(ctx.gl, canvas, cssW, cssH);
                coverUV = coverUVFor(cssW, cssH, imgW, imgH);
            }
            if (!transitioning) front = restFront();
            scheduleFrame();
        });
    }

    function boot() {
        const grid = document.getElementById('photoFloatingGrid');
        if (!grid) return;

        setupLightbox(grid);

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
