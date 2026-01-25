<?php
/**
 * Template Name: Full Bleed Hero Story
 * Template Post Type: story
 *
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

// Add story templates CSS and JS to wp_head
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/story-templates/story-templates.css?v=' . wp_get_theme()->get('Version') . '">' . "\n";
    echo '<script src="' . get_stylesheet_directory_uri() . '/story-templates/story-templates.js?v=' . wp_get_theme()->get('Version') . '"></script>' . "\n";
}, 999);

get_header('branded'); ?>

<style>
    /* Template-Specific CSS for Full Bleed Hero Story Template */
    /* Common styles are now in story-templates.css */

    /* Full Bleed Hero Section - Template Specific */
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
        margin-bottom: 1rem;
        line-height: 1.1;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    /* Override base-sections.css to ensure story hero h1 stays at 96px */
    .story-hero-full-bleed h1,
    .story-hero-text h1 {
        font-size: 96px !important;
        font-weight: 400 !important;
    }

    .story-hero-text h3 {
        margin-bottom: 16px;
    }

    /* Style paragraph inside h2 (WordPress excerpt wraps content in p tags) */
    .story-hero-text h2 p {
        font-family: var(--serif-font) !important;
        color: white !important;
        font-size: calc(20px * 1.23) !important;
        font-weight: 400 !important;
        margin-bottom: 1rem !important;
        line-height: 1.3 !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
    }

    .story-hero-text .story-meta {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        font-family: var(--primary-font);
        font-size: 16px;
    }

    .story-hero-text .story-meta i {
        color: white;
    }

    /* Hero image credit positioned below hero section */
    .hero-image-credit {
        position: absolute;
        left: 2vw;
        top: calc(100vh + 4px);
        font-family: var(--primary-font);
        font-size: 12px;
        color: var(--text-color-muted);
        z-index: 1000;
    }

    /* Mobile Responsive - Hero Specific - See breakpoint reference in plugin base-sections.css */
    @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
        .story-hero-content {
            width: 96vw;
        }
        
        .story-hero-text {
            width: 96vw;
            max-width: 96vw;
            min-width: 96vw;
        }
        
        .story-hero-text h1 {
            font-size: 80px !important;
            margin-bottom: 1rem;
        }

        .story-hero-text h2,
        .story-hero-text h2 p {
            font-size: 20px !important;
            margin-bottom: 1.5rem;
        }

        .hero-image-credit {
            left: 2vw;
            top: calc(100vh + 12px);
            text-align: left;
        }
    }

    @media (max-width: 480px), ((max-width: 1200px) and (max-height: 480px)) {
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

    /* Body paragraph styling now in story-templates.css */
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
    
    // Update site title to scroll to top of page
    if (siteTitle) {
        siteTitle.href = '#';
        siteTitle.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Create new story navigation
    const storyNav = document.createElement('a');
    storyNav.href = '/';
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
            <?php
            $short_headline = get_field('short_headline');
            $hero_title = !empty($short_headline) ? $short_headline : get_the_title();
            ?>
            <h1><?php echo esc_html($hero_title); ?></h1>

            <?php if (has_excerpt()) : ?>
                <h2><?php the_excerpt(); ?></h2>
            <?php endif; ?>
            
            <?php
            $publication = get_field('publication');
            $publish_date = get_field('publish_date');
            $photo_credit = get_field('photo_credit');
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

<!-- Image credit positioned below hero section -->
<?php if ($photo_credit && has_post_thumbnail()) : ?>
    <div class="hero-image-credit"><?php echo esc_html($photo_credit); ?></div>
<?php endif; ?>

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
                    <p style="margin-top: 2rem; text-align: left; max-width: 900px; width: 900px; margin-left: auto; margin-right: auto;">
                        <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener" style="color: var(--highlight-color); text-decoration: none; font-family: var(--primary-font); font-style: italic; font-weight: 400;">
                            Read the full story →
                        </a>
                    </p>
                <?php endif; ?>
            </article>
        </div>
    </div>

    <!-- More Stories Section -->
    <?php echo do_shortcode('[more_stories limit="6"]'); ?>

</main>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>
