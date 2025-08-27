<?php
/**
 * Single Story Template - Full Bleed Hero Design
 * Uses shared header and footer from portfolio page
 */

// Disable WordPress admin bar for story pages
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

get_header('branded'); ?>

<style>
    /* Full Bleed Hero Section for Stories */
    .story-hero-full-bleed {
        position: relative;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
    }

    .story-hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .story-hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .story-hero-content {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0;
        transition: opacity 0.3s ease, filter 0.3s ease;
    }

    .story-hero-text {
        max-width: 600px;
        text-align: center;
    }

    .story-hero-text h1 {
        font-family: var(--compressed-font) !important;
        font-size: 96px !important;
        font-weight: 400 !important;
        font-style: normal !important;
        text-transform: uppercase !important;
        color: white;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .story-hero-text h2 {
        font-family: var(--serif-font);
        color: white;
        font-size: calc(20px * 1.23); /* 20px * 1.23 = 24.6px - scaled for serif font */
        margin-bottom: 2rem;
        line-height: 1.3;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .story-hero-text .story-meta {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        font-family: var(--primary-font);
        font-size: 16px;
    }

    .story-hero-text .story-meta::after {
        content: " →";
    }

    .story-hero-text .story-meta i {
        color: white;
    }

    /* Main Content */
    .main-content {
        margin-top: 0;
        min-height: calc(100vh - 200px);
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: white;
        position: relative;
        z-index: 10;
    }

    /* Story Content Styles */
    .story-single-container {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        padding: 3rem 2rem 0 2rem;
    }

    /* Content wrapper for proper text centering within full-width container */
    .story-content-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .story-content-inner {
        font-family: var(--serif-font);
        font-size: 20px;
        line-height: 1.6;
        color: #000;
    }

    .story-content-inner p {
        margin-bottom: 1.5rem;
    }

    /* Full-width images in content */
    .story-content-inner img {
        width: calc(100vw - 4rem); /* Account for container padding */
        margin: 2rem -2rem; /* Break out of content wrapper padding */
        display: block;
    }

    .story-content-inner h2 {
        font-family: var(--serif-font);
        font-size: calc(32px * 1.23);
        font-weight: 400;
        margin: 2rem 0 1rem 0;
        color: #000;
    }

    .story-content-inner h3 {
        font-family: var(--serif-font);
        font-size: calc(24px * 1.23);
        font-weight: 400;
        margin: 1.5rem 0 1rem 0;
        color: #000;
    }

    .story-image-credit {
        font-size: 12px;
        color: #808080;
        text-align: right;
        margin-top: 4px;
        padding: 2px 0;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .story-hero-content {
            padding: 2rem;
        }
        
        .story-hero-text {
            max-width: 100%;
        }
        
        .story-hero-text h1 {
            font-size: 64px !important;
            margin-bottom: 1rem;
        }
        
        .story-hero-text h2 {
            margin-bottom: 1.5rem;
        }

        .main-content {
            padding: 0 4vw;
        }

        .story-single-container {
            padding: 2rem 1rem;
        }

        .story-content-inner {
            font-size: 18px;
        }
    }

    @media (max-width: 480px) {
        .story-hero-content {
            padding: 1.5rem;
        }
        
        .story-hero-text h1 {
            margin-bottom: 0.75rem;
        }
        
        .story-hero-text h2 {
            margin-bottom: 1rem;
        }
    }

    /* Story-specific header modifications */
    .site-header .main-nav {
        display: none !important;
    }

    .site-header::after {
        content: "← Stories";
        position: absolute;
        right: 2vw;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-family: var(--primary-font);
        font-size: 16px;
        font-weight: 400;
        text-decoration: none;
        cursor: pointer;
        z-index: 1002;
        transition: all 0.3s ease;
    }

    .site-header:not(.over-full-bleed)::after {
        color: #000 !important;
    }

    .site-header .contact-pill {
        display: none !important;
    }

    .site-header::before {
        content: "contact →";
        position: absolute;
        right: calc(2vw + 100px);
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid white;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-family: var(--primary-font);
        font-size: 14px;
        font-weight: 400;
        text-decoration: none;
        cursor: pointer;
        z-index: 1002;
        transition: all 0.3s ease;
    }

    .site-header:not(.over-full-bleed)::before {
        background: rgba(0, 0, 0, 0.05) !important;
        border-color: #000 !important;
        color: #000 !important;
    }

    .site-header::before:hover {
        background: white !important;
        color: #000 !important;
    }

    .site-header:not(.over-full-bleed)::before:hover {
        background: #000 !important;
        color: white !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroContent = document.querySelector('.story-hero-content');
    const heroSection = document.querySelector('.story-hero-full-bleed');
    
    if (!heroContent || !heroSection) return;
    
    function updateHeroTextOpacity() {
        const scrollY = window.scrollY;
        const heroHeight = heroSection.offsetHeight;
        
        // Start fading when scrolled 5% of hero height, fully faded at 30%
        const fadeStart = heroHeight * 0.05;
        const fadeEnd = heroHeight * 0.3;
        
        if (scrollY <= fadeStart) {
            heroContent.style.opacity = '1';
            heroContent.style.filter = 'blur(0px)';
        } else if (scrollY >= fadeEnd) {
            heroContent.style.opacity = '0';
            heroContent.style.filter = 'blur(10px)';
        } else {
            // Calculate opacity and blur between fadeStart and fadeEnd
            const fadeProgress = (scrollY - fadeStart) / (fadeEnd - fadeStart);
            const opacity = 1 - fadeProgress;
            const blurAmount = fadeProgress * 10; // 0px to 10px blur
            heroContent.style.opacity = opacity.toString();
            heroContent.style.filter = `blur(${blurAmount}px)`;
        }
    }
    
    // Initial call and scroll listener
    updateHeroTextOpacity();
    window.addEventListener('scroll', updateHeroTextOpacity);
});

// Handle story header navigation clicks
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    
    // Navigate to stories section on "← Stories" click
    header.addEventListener('click', function(e) {
        if (e.target === header && e.target.matches('.site-header::after')) {
            window.location.href = '/#stories';
        }
    });
    
    // Simulate click area for pseudo-elements
    const storiesLink = document.createElement('a');
    storiesLink.href = '/#stories';
    storiesLink.style.cssText = 'position: absolute; right: 2vw; top: 50%; transform: translateY(-50%); width: 80px; height: 40px; z-index: 1003; opacity: 0;';
    header.appendChild(storiesLink);
    
    const contactLink = document.createElement('a');
    contactLink.href = '/#contact';
    contactLink.style.cssText = 'position: absolute; right: calc(2vw + 100px); top: 50%; transform: translateY(-50%); width: 100px; height: 40px; z-index: 1003; opacity: 0;';
    header.appendChild(contactLink);
});
</script>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Story Hero Full Bleed -->
<section class="story-hero-full-bleed">
    <div class="story-hero-background">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php else : ?>
            <!-- Fallback background if no featured image -->
            <div style="background: linear-gradient(135deg, #39e58f 0%, #2dc776 100%); width: 100%; height: 100%;"></div>
        <?php endif; ?>
    </div>
    <div class="story-hero-content">
        <div class="story-hero-text">
            <h1><?php the_title(); ?></h1>
            
            <?php if (has_excerpt()) : ?>
                <h2><?php the_excerpt(); ?></h2>
            <?php endif; ?>
            
            <?php
            $publication = get_field('publication');
            $publish_date = get_field('publish_date');
            ?>
            
            <?php if ($publication || $publish_date) : ?>
                <p class="story-meta">
                    <?php if ($publication) : ?>
                        For <i><?php echo esc_html($publication); ?></i>
                    <?php endif; ?>
                    <?php if ($publish_date) : ?>
                        <?php echo $publication ? ' in ' : ''; ?>
                        <?php echo date('F Y', strtotime($publish_date)); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="main-content">
    <div class="story-single-container">
        <div class="story-content-wrapper">
            <!-- Story Content -->
            <article class="story-content">
                <div class="story-content-inner">
                    <?php the_content(); ?>
                </div>
                
                <?php 
                $photo_credit = get_field('photo_credit');
                if ($photo_credit && has_post_thumbnail()) : 
                ?>
                    <div class="story-image-credit"><?php echo esc_html($photo_credit); ?></div>
                <?php endif; ?>
                
                <?php 
                $external_url = get_field('external_url');
                if ($external_url) : 
                ?>
                    <p style="margin-top: 2rem; text-align: center;">
                        <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener" style="color: var(--highlight-color); text-decoration: none; font-weight: 500;">
                            Read the full story →
                        </a>
                    </p>
                <?php endif; ?>
            </article>
        </div>
    </div>
</main>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>