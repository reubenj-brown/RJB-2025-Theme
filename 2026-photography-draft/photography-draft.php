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
       Scroll fade — same technique as the homepage/about intro text
       (window.scrollY + getBoundingClientRect, linear opacity/blur).
    --------------------------------------------------------------- */
    function fadeOnApproach(elements, thresholdPct) {
        let queued = false;
        function onScroll() {
            // rAF-throttled: this reads getBoundingClientRect() for every card,
            // which forces layout. Once per frame is plenty; once per scroll
            // event is a lot of forced reflow across a ~30-card grid.
            if (queued) return;
            queued = true;
            requestAnimationFrame(function() { queued = false; update(); });
        }
        function update() {
            const scrollY = window.scrollY;
            const vh = window.innerHeight;
            elements.forEach(function(el) {
                const rect = el.getBoundingClientRect();
                const elTop = scrollY + rect.top;
                const fadeStart = elTop - (vh * thresholdPct);
                const fadeEnd = elTop;
                let opacity = 1, blur = 0;
                if (scrollY >= fadeEnd) {
                    opacity = 0; blur = 10;
                } else if (scrollY > fadeStart) {
                    const p = (scrollY - fadeStart) / (fadeEnd - fadeStart);
                    opacity = 1 - p; blur = p * 10;
                }
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
            });
        }
        update();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
    }

    const intro = document.querySelector('.photo-draft-intro h3');
    if (intro) fadeOnApproach([intro], 0.20);

    const cards = document.querySelectorAll('.photo-card');
    if (cards.length) fadeOnApproach(Array.prototype.slice.call(cards), 0.18);

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
