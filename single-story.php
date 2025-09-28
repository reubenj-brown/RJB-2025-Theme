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
    /* CSS Variables for Story Template */
    :root {
        --content-bg: white;
        --text-color: #000;
        --text-color-muted: #808080;
        --link-color: #808080;
        --link-hover-color: #39e58f;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --content-bg: #050505;
            --text-color: white;
            --text-color-muted: #a8a8a8;
            --link-color: white;
            --link-hover-color: #39e58f;
        }
    }

    /* Global background for story pages */
    html, body {
        background: var(--content-bg);
        min-height: 100vh;
    }

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
        background: var(--content-bg);
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
        max-width: none;
        margin: 0 auto;
        width: 100%;
    }

    .story-content-inner {
        font-family: var(--serif-font);
        font-size: calc(16px * 1.23);
        line-height: 1.6;
        color: var(--text-color);
    }

    /* Text elements limited to 900px width */
    .story-content-inner p,
    .story-content-inner h1,
    .story-content-inner h2,
    .story-content-inner h3,
    .story-content-inner h4,
    .story-content-inner h5,
    .story-content-inner h6,
    .story-content-inner ul,
    .story-content-inner ol,
    .story-content-inner blockquote {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .story-content-inner p {
        margin-bottom: 1.5rem;
    }

    /* Full-width images with 2vw padding from screen edges */
    .story-content-inner img {
        width: calc(100vw - 4vw); /* Full width minus 2vw padding on each side */
        max-width: calc(100vw - 4vw);
        margin: 2rem auto;
        display: block;
    }

    .story-content-inner h2 {
        font-family: var(--serif-font);
        font-size: calc(32px * 1.23);
        font-weight: 400;
        margin: 2rem 0 1rem 0;
        color: var(--text-color);
    }

    .story-content-inner h3 {
        font-family: var(--serif-font);
        font-size: calc(24px * 1.23);
        font-weight: 400;
        margin: 1.5rem 0 1rem 0;
        color: var(--text-color);
    }

    /* Story content links */
    .story-content-inner a {
        color: var(--text-color-muted);
        text-decoration: none;
    }

    .story-content-inner a:hover {
        color: var(--link-hover-color);
    }

    .story-image-credit {
        font-size: 12px;
        color: var(--text-color-muted);
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
            width: 90%;
            min-width: 90%;
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
            font-size: calc(16px * 1.23);
        }
    }

    @media (max-width: 1200px) {
        .story-header-nav {
            font-size: 17px;
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

        .story-header-contact {
            font-size: 14px;
        }
    }

    /* Story-specific header modifications */
    .story-header-nav {
        position: absolute;
        left: 2vw;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-family: var(--primary-font);
        font-size: 20px;
        font-weight: 400;
        text-decoration: none;
        cursor: pointer;
        z-index: 1002;
        transition: all 0.3s ease;
    }

    .site-header:not(.over-full-bleed) .story-header-nav {
        color: #000 !important;
    }

    .story-header-contact {
        position: absolute;
        right: 2vw;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid white;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-family: var(--primary-font);
        font-size: 16px;
        font-weight: 400;
        text-decoration: none;
        cursor: pointer;
        z-index: 1002;
        transition: all 0.3s ease;
    }

    .site-header:not(.over-full-bleed) .story-header-contact {
        background: rgba(0, 0, 0, 0.05) !important;
        border-color: #000 !important;
        color: #000 !important;
    }

    .story-header-contact:hover {
        background: white !important;
        color: #000 !important;
    }

    .site-header:not(.over-full-bleed) .story-header-contact:hover {
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

// Replace header navigation for story pages
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    const mainNav = header.querySelector('.main-nav');
    const contactPill = header.querySelector('.contact-pill');
    const siteTitle = header.querySelector('.site-title-name');
    
    // Remove existing navigation elements
    if (mainNav) mainNav.remove();
    if (contactPill) contactPill.remove();
    
    // Update site title to link to homepage instead of #top
    if (siteTitle) {
        siteTitle.href = '/';
    }
    
    // Create new story navigation
    const storyNav = document.createElement('a');
    storyNav.href = '/#stories';
    storyNav.className = 'story-header-nav';
    storyNav.textContent = '← Home';
    header.appendChild(storyNav);
    
    // Create new contact button
    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
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