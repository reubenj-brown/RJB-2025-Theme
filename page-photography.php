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
    /* Photography page highlight color override */
    :root {
        --highlight-color: #003CFF;
        --link-hover-color: #003CFF;
    }

    /* Main Content Wrapper - No margin, gradient starts at top */
    .main-content {
        padding: 0;
        width: 100vw;
        max-width: 100vw;
        background: var(--main-content-bg);
        margin-top: 0;
        position: relative;
        z-index: 10;
    }
</style>

<main class="main-content">
    <!-- Intro Section with Gradient -->
    <div class="photo-intro-gradient">
        <div class="strategy-intro">
            <div class="strategy-intro-headline" style="opacity: 1; filter: blur(0px);">
                <span class="display-headline">Photography</span>
            </div>
            <div class="strategy-intro-body" style="opacity: 1; filter: blur(0px);">
                <h3>I’ve freelanced for <i>The Wall Street Journal</i> and worked as a photo assistant for editorial and TV clients. I’m a member of the LA Press Photographers Association and winner of a 2026 National Press Photographers Foundation scholarship. Photo editors say I’m good at making boring things look interesting. </h3>
            </div>
        </div>
    </div>

    <!-- Photography Sections -->
    <?php echo do_shortcode('[photo_section type="stories"]'); ?>
    <?php echo do_shortcode('[photo_section type="portraits"]'); ?>
    <?php echo do_shortcode('[photo_section type="infrastructure"]'); ?>
    <?php echo do_shortcode('[photo_section type="events"]'); ?>
    <?php echo do_shortcode('[photo_section type="cities"]'); ?>
    <?php echo do_shortcode('[photo_section type="landscapes"]'); ?>

</main>

<!-- Photo Lightbox -->
<div class="photo-lightbox" id="photoLightbox">
    <button class="photo-lightbox-close" aria-label="Close image">×</button>
    <img class="photo-lightbox-image" src="" alt="">
</div>

<script>
// Disable browser scroll restoration and reset horizontal scrollers
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

// Function to reset all photo scrollers to left position
function resetPhotoScrollers() {
    document.querySelectorAll('.photo-scroll').forEach(function(scroller) {
        scroller.scrollLeft = 0;
        scroller.scrollTo({ left: 0, behavior: 'instant' });
    });
}

// Run on DOMContentLoaded
document.addEventListener('DOMContentLoaded', resetPhotoScrollers);

// Run on window load
window.addEventListener('load', resetPhotoScrollers);

// Run after a short delay to catch late-loading elements
window.addEventListener('load', function() {
    setTimeout(resetPhotoScrollers, 100);
    setTimeout(resetPhotoScrollers, 300);
});

// Photo Lightbox functionality
(function() {
    var lightbox = document.getElementById('photoLightbox');
    var lightboxImg = lightbox.querySelector('.photo-lightbox-image');
    var closeBtn = lightbox.querySelector('.photo-lightbox-close');
    var currentImageElement = null;
    var currentScroller = null;

    document.querySelectorAll('.photo-picture img').forEach(function(img) {
        img.addEventListener('click', function() {
            currentImageElement = this;
            currentScroller = this.closest('.photo-scroll');
            lightboxImg.src = this.src;
            lightboxImg.alt = this.alt;
            lightbox.classList.add('active');
        });
    });

    function closeLightbox() {
        lightbox.classList.remove('active');
        lightboxImg.src = '';
        currentImageElement = null;
        currentScroller = null;
    }

    function navigateImage(direction) {
        if (!currentImageElement || !currentScroller) return;

        var images = Array.from(currentScroller.querySelectorAll('.photo-picture img'));
        var currentIndex = images.indexOf(currentImageElement);

        if (currentIndex === -1) return;

        var newIndex = currentIndex + direction;

        // Loop around if at edges
        if (newIndex < 0) newIndex = images.length - 1;
        if (newIndex >= images.length) newIndex = 0;

        currentImageElement = images[newIndex];
        lightboxImg.src = currentImageElement.src;
        lightboxImg.alt = currentImageElement.alt;
    }

    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            navigateImage(-1);
        } else if (e.key === 'ArrowRight') {
            navigateImage(1);
        }
    });
})();

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

    // Create desktop navigation (left side)
    const storyNavDesktop = document.createElement('span');
    storyNavDesktop.className = 'story-header-nav story-header-nav-desktop';
    storyNavDesktop.innerHTML = '<a href="/">← Home</a> / <strong>Photography</strong>';
    header.appendChild(storyNavDesktop);

    // Create mobile navigation (right side)
    const storyNavMobile = document.createElement('span');
    storyNavMobile.className = 'story-header-nav story-header-nav-mobile';
    storyNavMobile.innerHTML = '<strong>Photography</strong> / <a href="/">Home →</a>';
    header.appendChild(storyNavMobile);

    // Create new contact button (desktop only)
    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
});
</script>

<?php get_footer('branded'); ?>
