<?php
/*
 * Photography Draft 2026 — content partial
 * Loaded by page-photography-draft-2026.php (theme root)
 * Do not use as a standalone template.
 *
 * Card behaviour (the watercolor reveal, and the deferred video loading)
 * lives in watercolor-reveal.js, enqueued by the root wrapper. What's left
 * inline here is page chrome only.
 */
?>

<main class="photography-draft">

    <!-- Intro. Same shared structure as the live photography page, so the
         type comes from base-sections.css rather than being restated here. -->
    <div class="photo-draft-intro">
        <div class="strategy-intro">
            <div class="strategy-intro-body">
                <h3>I’ve freelanced for <i>The Wall Street Journal</i> and worked as a photo assistant for editorial and TV clients. I’m a member of the LA Press Photographers Association and winner of a 2026 National Press Photographers Foundation scholarship. Photo editors say I’m good at making boring things look interesting.</h3>
            </div>
        </div>
    </div>

    <!-- Watercolor reveal cards -->
    <?php echo do_shortcode('[photo_floating_gallery]'); ?>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ---------------------------------------------------------------
       Scroll fade — elements blur and dissolve as they leave the top of
       the viewport.

       The ramp is a straight line between two positions of the element's
       top edge, both given as fractions of viewport height: positive is
       below the top of the screen, negative is above it. Two callers with
       deliberately different shapes:

       INTRO  0.20 -> 0     the original behaviour. Fades over the last
                            fifth of a screen of approach and is gone as
                            it reaches the top.

       CARDS  0 -> -0.35    starts the instant a card's top edge touches
                            the top of the screen, then dissolves over the
                            next third of a screen of travel.

       Cards were keyed off their bottom edge for a while, which read as
       far too soft: a card tall enough that its bottom only entered the
       band once it had all but gone would barely blur at all. Starting
       at zero fires for every card at the same moment regardless of the
       height --card-scale rolled for it, which is the point.
    --------------------------------------------------------------- */
    const MAX_BLUR = 10;

    function fadeOnApproach(elements, fromVh, toVh) {
        let queued = false;
        function onScroll() {
            // rAF-throttled: this reads getBoundingClientRect() for every card,
            // which forces layout. Once per frame is plenty; once per scroll
            // event is a lot of forced reflow across a ~30-card grid.
            if (queued) return;
            queued = true;
            requestAnimationFrame(function() { queued = false; update(); });
        }
        // Reused between frames so a scroll doesn't allocate per element.
        const tops = new Array(elements.length);

        function update() {
            const vh = window.innerHeight;
            const from = fromVh * vh;
            const to = toVh * vh;
            const span = (from - to) || 1;

            // Read every position BEFORE writing any style. Interleaving them
            // means each write invalidates style and the next
            // getBoundingClientRect() has to flush it again — one forced
            // recalc per card per frame instead of one for the whole batch.
            for (let i = 0; i < elements.length; i++) {
                tops[i] = elements[i].getBoundingClientRect().top;
            }

            for (let i = 0; i < elements.length; i++) {
                const el = elements[i];
                // 0 = untouched, 1 = fully faded
                let p = (from - tops[i]) / span;
                if (p < 0) p = 0; else if (p > 1) p = 1;
                const opacity = 1 - p;
                const blur = p * MAX_BLUR;
                // Only touch the DOM when a value actually changed, and drop
                // `filter` entirely at blur 0 rather than writing blur(0px).
                // Any non-none filter forces the card onto its own compositing
                // layer and a filter pass over its contents every repaint —
                // and its contents are a live WebGL canvas. Most cards on
                // screen are not fading at all, so this was pure cost.
                if (el._wcOpacity !== opacity) {
                    el.style.opacity = opacity;
                    el._wcOpacity = opacity;
                }
                if (el._wcBlur !== blur) {
                    el.style.filter = blur > 0 ? 'blur(' + blur + 'px)' : '';
                    el._wcBlur = blur;
                }
            }
        }
        update();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
    }

    const intro = document.querySelector('.photo-draft-intro h3');
    if (intro) fadeOnApproach([intro], 0.20, 0);

    const cards = document.querySelectorAll('.photo-card');
    if (cards.length) fadeOnApproach(Array.prototype.slice.call(cards), 0, -0.35);

    /* ---------------------------------------------------------------
       Replace header navigation with the "← Home / Photography"
       breadcrumb (same script as the live photography page).
    --------------------------------------------------------------- */
    const header = document.querySelector('.site-header');
    if (!header) return;

    const mainNav = header.querySelector('.main-nav');
    const contactPill = header.querySelector('.contact-pill');
    const siteTitle = header.querySelector('.site-title-name');

    if (mainNav) mainNav.remove();
    if (contactPill) contactPill.remove();

    if (siteTitle) {
        siteTitle.href = '#';
        siteTitle.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const storyNavDesktop = document.createElement('span');
    storyNavDesktop.className = 'story-header-nav story-header-nav-desktop';
    storyNavDesktop.innerHTML = '<a href="/">← Home</a> / <strong>Photography</strong>';
    header.appendChild(storyNavDesktop);

    const storyNavMobile = document.createElement('span');
    storyNavMobile.className = 'story-header-nav story-header-nav-mobile';
    storyNavMobile.innerHTML = '<strong>Photography</strong> / <a href="/">Home →</a>';
    header.appendChild(storyNavMobile);

    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
});
</script>

<?php /* Footer is called by the root wrapper page-photography-draft-2026.php */ ?>
