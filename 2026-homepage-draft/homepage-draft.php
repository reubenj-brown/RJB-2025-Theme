<?php
/*
Template Name: Homepage Draft 2026
*/

show_admin_bar(false);

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');

    wp_enqueue_style(
        'homepage-draft-2026',
        get_stylesheet_directory_uri() . '/2026-homepage-draft/homepage-draft.css',
        array('astra-child-theme-css'),
        '1.0.0'
    );
}, 100);

get_header('branded');
?>

<main class="homepage-draft" id="top">

    <!-- ============================================================
         HERO — full-bleed featured story
         ============================================================ -->
    <section class="hp-hero" id="hero">

        <div class="hp-hero-bg"
             data-fallback="<?php echo esc_url(home_url('/wp-content/uploads/2025/11/reuben-j-brown-almeria-greenhouses-el-ejido-agriculture.avif')); ?>">
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url(home_url('/wp-content/uploads/2025/07/Eviction-of-Cortijo-El-Uno-Almeria-Spain-Greenhouse-Farms-Invernadero-Reuben-J-Brown.mp4')); ?>" type="video/mp4">
            </video>
            <div class="hp-hero-fallback"
                 style="background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/11/reuben-j-brown-almeria-greenhouses-el-ejido-agriculture.avif')); ?>')">
            </div>
        </div>

        <div class="hp-hero-content">
            <a href="/stories/the-cost-of-a-miracle/" class="hp-hero-story-link">
                <p class="hp-kicker">Featured story</p>
                <h1 class="hp-hero-headline">The Cost of a Miracle</h1>
                <p class="hp-hero-standfirst">In Europe's driest region, a vast plastic sea covers an agricultural system built on intensity and innovation — but the cracks in Almería's miracle are growing, too</p>
                <p class="story-meta">For <i>Panoramic</i> · May 2025</p>
            </a>
        </div>

    </section><!-- .hp-hero -->


    <!-- ============================================================
         INTRO — who I am
         ============================================================ -->
    <section class="hp-intro content-section" id="about">
        <div class="hp-intro-inner">
            <p class="hp-intro-text">I'm Reuben, a multimedia journalist currently in London. I work as a writer, photographer and editor on stories about very big systems, the people shaping them, and the people they shape.</p>
        </div>
    </section><!-- .hp-intro -->


    <!-- ============================================================
         STORIES — recent writing
         ============================================================ -->
    <section class="hp-stories content-section" id="stories">
        <div class="hp-section-header">
            <h2 class="hp-section-label">Writing</h2>
            <a href="<?php echo home_url('/stories/'); ?>" class="hp-section-more">All stories →</a>
        </div>
        <?php echo do_shortcode('[reuben_writing]'); ?>
    </section><!-- .hp-stories -->


    <!-- ============================================================
         PHOTOGRAPHY — teaser
         ============================================================ -->
    <section class="hp-photography content-section" id="photography">
        <div class="hp-section-header">
            <h2 class="hp-section-label">Photography</h2>
            <a href="<?php echo home_url('/photography'); ?>" class="hp-section-more">See all photography →</a>
        </div>
        <?php echo do_shortcode('[reuben_photography]'); ?>
    </section><!-- .hp-photography -->


    <!-- ============================================================
         CONTACT
         ============================================================ -->
    <section class="hp-contact contact-section" id="contact">
        <div class="hp-contact-inner">
            <p class="display-headline">
                <a href="mailto:reubenjub@gmail.com">Get in touch ↗</a>
            </p>
            <div class="hp-contact-links">
                <a href="https://instagram.com/reubenjbrown" target="_blank" rel="noopener noreferrer">Instagram</a>
                <span class="hp-contact-sep">/</span>
                <a href="https://twitter.com/reubenjbrown" target="_blank" rel="noopener noreferrer">Twitter / X</a>
                <span class="hp-contact-sep">/</span>
                <a href="https://www.linkedin.com/in/reubenjbrown/" target="_blank" rel="noopener noreferrer">LinkedIn</a>
            </div>
        </div>
    </section><!-- .hp-contact -->

</main><!-- .homepage-draft -->

<script>
(function() {
    /* ---------------------------------------------------------------
       Hero video — show fallback image until video is confirmed ready
    --------------------------------------------------------------- */
    const heroBg   = document.querySelector('.hp-hero-bg');
    const video    = heroBg ? heroBg.querySelector('video') : null;

    if (!heroBg || !video) return;

    function showFallback() {
        heroBg.classList.add('hp-video-error');
    }

    if (video.error || !video.canPlayType || !video.canPlayType('video/mp4')) {
        showFallback();
        return;
    }

    video.addEventListener('error', showFallback);

    const timeout = setTimeout(function() {
        if (video.readyState < 3) showFallback();
    }, 4000);

    video.addEventListener('canplaythrough', function() {
        clearTimeout(timeout);
        heroBg.classList.add('hp-video-ready');
    });
})();
</script>

<?php get_footer('branded'); ?>
