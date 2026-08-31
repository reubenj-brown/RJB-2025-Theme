<?php
/*
 * Photography Draft 2026 — content partial
 * Loaded by page-photography-draft-2026.php (theme root)
 * Do not use as a standalone template.
 */
?>

<main class="photography-draft">

    <!-- Intro -->
    <div class="photo-draft-intro">
        <h3>I’ve freelanced for <i>The Wall Street Journal</i> and worked as a photo assistant for editorial and TV clients. I’m a member of the LA Press Photographers Association and winner of a 2026 National Press Photographers Foundation scholarship. Photo editors say I’m good at making boring things look interesting.</h3>
    </div>

    <!-- Floating photo/video cards -->
    <?php echo do_shortcode('[photo_floating_gallery]'); ?>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ---------------------------------------------------------------
       Scroll fade — same technique as the homepage/about intro text
       (window.scrollY + getBoundingClientRect, linear opacity/blur).
    --------------------------------------------------------------- */
    function fadeOnApproach(elements, thresholdPct) {
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
                el.style.opacity = opacity;
                el.style.filter = 'blur(' + blur + 'px)';
            });
        }
        update();
        window.addEventListener('scroll', update);
    }

    const intro = document.querySelector('.photo-draft-intro h3');
    if (intro) fadeOnApproach([intro], 0.20);

    const cards = document.querySelectorAll('.flip-card');
    if (cards.length) fadeOnApproach(Array.prototype.slice.call(cards), 0.18);

    /* ---------------------------------------------------------------
       Flip / reveal toggle — one delegated listener for every card.
       CSS decides whether .flipped means "rotate" (mobile/tablet) or
       "slide off and reveal" (desktop) — see photography-draft.css.
    --------------------------------------------------------------- */
    const grid = document.getElementById('photoFloatingGrid');
    if (grid) {
        grid.addEventListener('click', function(e) {
            if (e.target.closest('.flip-card-see-more')) return;
            const card = e.target.closest('.flip-card');
            if (card) card.classList.toggle('flipped');
        });
    }

    /* ---------------------------------------------------------------
       Deferred video loading — same mechanic as the homepage hero
       (preload="none" + data-src, save-data guard), but gated per
       card by IntersectionObserver instead of a single page-load
       timer, since a grid can hold many video cards at once.
    --------------------------------------------------------------- */
    function loadCardVideo(card) {
        const video = card.querySelector('video[data-src]');
        if (!video) return;
        if (!video.canPlayType || !video.canPlayType('video/mp4')) return;
        const conn = navigator.connection;
        if (conn && (conn.saveData || /^(slow-2g|2g)$/.test(conn.effectiveType || ''))) return;

        video.src = video.dataset.src;
        video.addEventListener('canplaythrough', function() {
            card.classList.add('video-loaded'); // fades the poster out via CSS
        });
        video.addEventListener('ended', function() { video.currentTime = 0; video.play(); });
        video.addEventListener('pause', function() {
            if (card.classList.contains('video-loaded')) video.play();
        });
        video.addEventListener('error', function() { card.classList.remove('video-loaded'); });

        video.load();
        const p = video.play();
        if (p && p.catch) p.catch(function() {});
    }

    if (grid) {
        const videoCards = grid.querySelectorAll('.flip-card[data-media="video"]');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        loadCardVideo(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '200px 0px' });
            videoCards.forEach(function(card) { observer.observe(card); });
        } else {
            videoCards.forEach(loadCardVideo);
        }
    }

    /* ---------------------------------------------------------------
       Replace header navigation with the "← Home / Photography"
       breadcrumb (same script as the live photography page).
    --------------------------------------------------------------- */
    const header = document.querySelector('.site-header');
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
