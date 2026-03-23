<?php
/**
 * Template Name: Photography Page
 * Description: Photography portfolio page with multiple gallery sections
 */

// Disable WordPress admin bar for this page
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

// Add story templates CSS for header styling
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/story-templates/story-templates.css?v=' . wp_get_theme()->get('Version') . '">' . "\n";
}, 999);

get_header('branded'); ?>

<style>
    /* Main Content Wrapper - Positioned below header */
    .main-content {
        padding: 0;
        width: 100vw;
        max-width: 100vw;
        background: var(--main-content-bg);
        margin-top: calc(60px + 2vw + env(safe-area-inset-top));
        position: relative;
        z-index: 10;
    }

    /* Tablet Responsive */
    @media (max-width: 1200px) {
        .main-content {
            margin-top: calc(80px + env(safe-area-inset-top));
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
        .main-content {
            padding-left: 0;
            padding-right: 0;
            margin-top: calc(70px + env(safe-area-inset-top));
        }
    }
</style>

<main class="main-content">
    <!-- Intro Section -->
    <div class="photo-page-intro">
        <h3>As a photographer, I've freelanced for <i>The Wall Street Journal</i> and worked as a photo assistant for editorial and TV clients. I'm a member of the <a href="https://lapressphoto.com" target="_blank" rel="noopener">LA Press Photographers Association</a>.</h3>
    </div>

    <!-- Photography Sections -->
    <?php echo do_shortcode('[photo_section type="stories"]'); ?>
    <?php echo do_shortcode('[photo_section type="portraits"]'); ?>
    <?php echo do_shortcode('[photo_section type="infrastructure"]'); ?>
    <?php echo do_shortcode('[photo_section type="events"]'); ?>
    <?php echo do_shortcode('[photo_section type="cities"]'); ?>
    <?php echo do_shortcode('[photo_section type="landscapes"]'); ?>

</main>

<script>
// Replace header navigation for photography page (same as story pages)
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

<?php get_footer('branded'); ?>
