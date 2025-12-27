<?php
/**
 * Template Name: Split Hero Story
 * Template Post Type: story
 *
 * Single Story Template - Split Hero Design
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

get_header('branded');

// Get custom hero color from post meta
$custom_hero_color = get_post_meta(get_the_ID(), 'story_hero_color', true);
$hero_color = !empty($custom_hero_color) ? $custom_hero_color : '#39e58f';
?>

<style>
    /* Template-Specific CSS for Split Hero Story Template */
    /* Common styles are now in story-templates.css */

    /* Dynamic CSS variable for custom hero color */
    :root {
        --story-hero-color: <?php echo esc_attr($hero_color); ?>;
    }

    /* Split Hero Section - Two Column Layout */
    .story-hero-full-bleed {
        position: relative;
        top: calc(2vw + 60px);
        width: 100vw;
        height: calc(100vh - 60px - 2vw);
        overflow: hidden;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        display: flex;
    }

    /* Left column - Text content with custom color background */
    .story-hero-content {
        position: relative;
        width: 50%;
        height: 100%;
        background: var(--story-hero-color);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 4vw;
        z-index: 2;
    }

    /* Right column - Featured image */
    .story-hero-background {
        position: relative;
        width: 50%;
        height: 100%;
        z-index: 1;
    }

    .story-hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
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
        color: #000 !important;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        text-shadow: none;
    }

    .story-hero-text h2 {
        font-family: var(--serif-font);
        color: #000 !important;
        font-size: calc(20px * 1.23);
        font-weight: 400;
        margin-bottom: 2rem;
        line-height: 1.3;
        text-shadow: none;
    }

    /* Ensure standfirst paragraph stays black in both light and dark mode */
    .story-hero-text h2 p {
        color: #000 !important;
    }

    .story-hero-text .story-meta {
        color: rgba(0, 0, 0, 0.8) !important;
        font-weight: 600;
        text-shadow: none;
        font-family: var(--primary-font);
        font-size: 16px;
    }

    .story-hero-text .story-meta i {
        color: #000 !important;
    }

    /* Tablet Responsive - Stack columns vertically with image on top */
    @media (max-width: 1200px) {
        .story-hero-full-bleed {
            top: 61px;
            height: calc(100vh - 61px);
            flex-direction: column;
        }

        .story-hero-content {
            width: 100%;
            height: 50%;
            padding: 6vw;
            order: 2; /* Move colored area below image */
        }

        .story-hero-background {
            width: 100%;
            height: 50%;
            order: 1; /* Move image above colored area */
        }

        .story-hero-text {
            width: 100%;
            max-width: 100%;
            min-width: 100%;
        }

        .story-hero-text h1 {
            font-size: 80px !important;
            margin-bottom: 1rem;
        }

        .story-hero-text h2 {
            font-size: 20px !important;
            margin-bottom: 1.5rem;
        }
    }

    /* Mobile responsive adjustments for header height changes */
    @media (max-width: 768px), (max-height: 768px) {
        .story-hero-full-bleed {
            top: 56px;
            height: calc(100vh - 56px);
        }
    }

    @media (max-width: 480px), (max-height: 480px) {
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

    /* Split-specific padding and margin adjustment */
    .story-single-container {
        margin-top: calc(2vw + 60px);
        padding: 4rem 2vw 0 2vw;
    }

    /* Tablet responsive adjustments for content margin */
    @media (max-width: 1200px) {
        .story-single-container {
            margin-top: 61px;
        }
    }

    /* Mobile responsive adjustments for content margin */
    @media (max-width: 768px), (max-height: 768px) {
        .story-single-container {
            margin-top: 56px;
        }
    }

    /* Header styling for split template */
    .site-header.over-split-hero {
        background: var(--content-bg) !important;
    }

    /* Light mode: black text when over split hero */
    @media (prefers-color-scheme: light) {
        .site-header.over-split-hero .site-title-name {
            color: #000 !important;
        }

        .site-header.over-split-hero .story-header-nav {
            color: #000 !important;
        }

        .site-header.over-split-hero .story-header-contact {
            background: rgba(0, 0, 0, 0.05) !important;
            border-color: #000 !important;
            color: #000 !important;
        }

        .site-header.over-split-hero .story-header-contact:hover {
            background: #000 !important;
            color: white !important;
        }

        /* Light mode: black text when over content area */
        .site-header.over-full-bleed .site-title-name,
        .site-header:not(.over-split-hero) .site-title-name {
            color: #000 !important;
        }

        .site-header.over-full-bleed .story-header-nav,
        .site-header:not(.over-split-hero) .story-header-nav {
            color: #000 !important;
        }

        .site-header.over-full-bleed .story-header-contact,
        .site-header:not(.over-split-hero) .story-header-contact {
            background: rgba(0, 0, 0, 0.05) !important;
            border-color: #000 !important;
            color: #000 !important;
        }

        .site-header.over-full-bleed .story-header-contact:hover,
        .site-header:not(.over-split-hero) .story-header-contact:hover {
            background: #000 !important;
            color: white !important;
        }
    }

    /* Dark mode: white text when over split hero */
    @media (prefers-color-scheme: dark) {
        .site-header.over-split-hero .site-title-name {
            color: white !important;
        }

        .site-header.over-split-hero .story-header-nav {
            color: white !important;
        }

        .site-header.over-split-hero .story-header-contact {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: white !important;
            color: white !important;
        }

        .site-header.over-split-hero .story-header-contact:hover {
            background: white !important;
            color: #000 !important;
        }

        /* Dark mode: white text when over content area */
        .site-header.over-full-bleed .site-title-name,
        .site-header:not(.over-split-hero) .site-title-name {
            color: white !important;
        }

        .site-header.over-full-bleed .story-header-nav,
        .site-header:not(.over-split-hero) .story-header-nav {
            color: white !important;
        }

        .site-header.over-full-bleed .story-header-contact,
        .site-header:not(.over-split-hero) .story-header-contact {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: white !important;
            color: white !important;
        }

        .site-header:not(.over-full-bleed) .story-header-contact:hover {
            background: white !important;
            color: #000 !important;
        }
    }

    /* Hero image credit positioned below hero section */
    .hero-image-credit {
        position: absolute;
        left: calc(50vw + 12px);
        top: calc(100vh + 4px);
        font-family: var(--primary-font);
        font-size: 12px;
        color: var(--text-color-muted);
        z-index: 1000;
    }

    /* Tablet responsive for hero image credit - image now on top */
    @media (max-width: 1200px) {
        .hero-image-credit {
            left: 2vw;
            top: calc(100vh + 8px);
            text-align: left;
        }
    }
</style>

<script>
// Handle header transparency on scroll for split layout
function handleSplitHeaderScroll() {
    const header = document.querySelector('.site-header');

    if (!header) return;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Calculate header height based on breakpoint - fixed pixel values
    let headerHeight;

    if (window.innerWidth <= 768) {
        headerHeight = 56; // Mobile height
    } else if (window.innerWidth <= 1200) {
        headerHeight = 61; // Tablet height
    } else {
        // Desktop: 2vw + 60px
        const vwValue = window.innerWidth * 0.02;
        headerHeight = vwValue + 60;
    }

    // Make header transparent when scrolled past header height
    if (scrollTop > headerHeight) {
        header.classList.remove('over-split-hero');
        header.classList.add('over-full-bleed');
    } else {
        header.classList.add('over-split-hero');
        header.classList.remove('over-full-bleed');
    }
}

// Listen for scroll events
window.addEventListener('scroll', handleSplitHeaderScroll);
window.addEventListener('load', handleSplitHeaderScroll);

// No scroll-based opacity transitions for split layout since text is fixed in position

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

<!-- Story Hero Split Layout -->
<section class="story-hero-full-bleed">
    <!-- Left column: Text content with green background -->
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

    <!-- Right column: Featured image -->
    <div class="story-hero-background">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php else : ?>
            <!-- Fallback background if no featured image -->
            <div style="background: linear-gradient(135deg, #39e58f 0%, #2dc776 100%); width: 100%; height: 100%;"></div>
        <?php endif; ?>
    </div>
</section>

<!-- Image credit positioned below hero section -->
<?php if ($photo_credit && has_post_thumbnail()) : ?>
    <div class="hero-image-credit">photograph: <?php echo esc_html($photo_credit); ?></div>
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
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Process all images in story content for automatic captions
    const storyImages = document.querySelectorAll('.story-content-inner img');

    storyImages.forEach(function(img) {

        // Get WordPress caption from multiple possible sources
        let captionText = '';
        let credit = '';

        // Method 1: Check if image is inside a WordPress figure with figcaption
        const figure = img.closest('figure');
        if (figure) {
            const figcaption = figure.querySelector('figcaption');
            if (figcaption) {
                captionText = figcaption.textContent.trim();
            }
        }

        // Method 2: Check for wp-caption-text (WordPress standard)
        const wpCaption = img.parentNode.querySelector('.wp-caption-text');
        if (wpCaption && !captionText) {
            captionText = wpCaption.textContent.trim();
        }

        // Method 3: Check parent for wp-caption div
        const wpCaptionDiv = img.closest('.wp-caption');
        if (wpCaptionDiv && !captionText) {
            const captionElement = wpCaptionDiv.querySelector('.wp-caption-text');
            if (captionElement) {
                captionText = captionElement.textContent.trim();
            }
        }

        // Method 4: Check for WordPress blocks caption
        const wpBlockCaption = img.parentNode.querySelector('.wp-element-caption');
        if (wpBlockCaption && !captionText) {
            captionText = wpBlockCaption.textContent.trim();
        }

        // Method 4b: Check for wp-block-image caption
        const wpBlockImage = img.closest('.wp-block-image');
        if (wpBlockImage && !captionText) {
            const blockCaption = wpBlockImage.querySelector('figcaption');
            if (blockCaption) {
                captionText = blockCaption.textContent.trim();
            }
        }

        // Method 4c: Check for UAGB image caption
        const uagbBlock = img.closest('.wp-block-uagb-image');
        if (uagbBlock && !captionText) {
            // Found UAGB block, searching for caption...

            // Check for figcaption within UAGB block
            const uagbCaption = uagbBlock.querySelector('figcaption');
            if (uagbCaption) {
                captionText = uagbCaption.textContent.trim();
            }

            // Check for UAGB-specific caption classes
            const uagbCaptionText = uagbBlock.querySelector('.uagb-image-caption, .wp-block-uagb-image__caption');
            if (uagbCaptionText && !captionText) {
                captionText = uagbCaptionText.textContent.trim();
            }

            // Debug: Log all elements within UAGB block
        }

        // Method 5: Check for caption in surrounding paragraph
        const nextElement = img.parentNode.nextElementSibling;
        if (nextElement && nextElement.tagName === 'P' && nextElement.classList.contains('caption') && !captionText) {
            captionText = nextElement.textContent.trim();
        }

        // Method 6: Check for data-caption attribute
        if (!captionText && img.getAttribute('data-caption')) {
            captionText = img.getAttribute('data-caption').trim();
        }

        // Method 6b: Check for WordPress attachment attributes
        if (!captionText) {
            // Check for data-attachment_id attribute
            const attachmentId = img.getAttribute('data-attachment-id') || img.getAttribute('data-id');
            if (attachmentId) {
            }

            // Check for WordPress-specific attributes that might contain caption
            const wpCaption = img.getAttribute('data-caption');
            const wpTitle = img.getAttribute('data-title');
            const wpDescription = img.getAttribute('data-description');

            if (wpCaption) {
                captionText = wpCaption.trim();
            } else if (wpDescription) {
                captionText = wpDescription.trim();
            }
        }

        // Comprehensive debugging disabled - caption detection working

        // Debug: show what we found

        // If no caption found from DOM, try WordPress REST API
        if (!captionText) {

            // Extract filename to search for attachment
            const filename = img.src.split('/').pop().split('?')[0]; // Remove query params
            const filenameWithoutExt = filename.replace(/\.[^.]+$/, ''); // Remove extension

            // Search for attachment by filename
            fetch('/wp-json/wp/v2/media?search=' + encodeURIComponent(filenameWithoutExt) + '&per_page=1')
                .then(response => response.json())
                .then(mediaArray => {
                    if (mediaArray && mediaArray.length > 0) {
                        const mediaData = mediaArray[0];

                        // Check for caption in WordPress media data
                        let foundCaption = '';
                        if (mediaData.caption && mediaData.caption.rendered) {
                            foundCaption = mediaData.caption.rendered.replace(/<[^>]*>/g, '').trim(); // Strip HTML
                        }

                        // Check for source in meta fields with comprehensive debugging
                        let foundSource = '';
                        // Check for source in meta fields

                        // Check for source in the new REST API field we registered
                        if (mediaData.media_source) {
                            foundSource = mediaData.media_source;
                        } else if (mediaData.meta) {
                            // Fallback: check meta fields
                            foundSource = mediaData.meta._media_source ||
                                         mediaData.meta.media_source ||
                                         mediaData.meta.source ||
                                         '';

                            if (foundSource) {
                            } else {
                            }
                        } else {
                        }

                        // Also check if source is in other locations
                        if (!foundSource && mediaData.source) {
                            foundSource = mediaData.source;
                        }

                        // Check custom fields if available
                        if (!foundSource && mediaData.acf && mediaData.acf.source) {
                            foundSource = mediaData.acf.source;
                        }

                        // Update caption display if we found caption data
                        if (foundCaption || foundSource) {
                            updateCaptionDisplay(img, foundCaption, foundSource);
                        } else {
                        }
                    } else {

                        for (let i = 0; i < img.attributes.length; i++) {
                            const attr = img.attributes[i];
                        }
                    }
                })
                .catch(error => {
                });
        }

        // Try to extract credit from caption text using common patterns
        if (captionText && (captionText.includes('Photo:') || captionText.includes('Credit:') || captionText.includes('Source:'))) {
            const parts = captionText.split(/(?:Photo:|Credit:|Source:)/i);
            if (parts.length > 1) {
                captionText = parts[0].trim();
                credit = parts[1].trim();
            }
        }

        // Get WordPress media library source field
        // We need to make an AJAX call to get the media source since it's not in the DOM
        if (!credit) {
            // Try to get attachment ID from various WordPress attributes
            let attachmentId = null;

            // Check for wp-image-XXXX class (WordPress standard)
            const imageClasses = img.className.split(' ');
            for (let className of imageClasses) {
                if (className.startsWith('wp-image-')) {
                    attachmentId = className.replace('wp-image-', '');
                    break;
                }
            }

            // If we have an attachment ID, fetch the source
            if (attachmentId) {
                // For now, use a placeholder - we'll need to implement AJAX call
                credit = 'Loading source...';

                // Make AJAX call to get media source
                fetch('/wp-json/wp/v2/media/' + attachmentId)
                    .then(response => response.json())
                    .then(data => {

                        // Check multiple possible locations for source field
                        let source = '';

                        // Method 1: Check meta field
                        if (data.meta && data.meta._media_source) {
                            source = data.meta._media_source;
                        }

                        // Method 2: Check meta field without underscore
                        if (!source && data.meta && data.meta.media_source) {
                            source = data.meta.media_source;
                        }

                        // Method 3: Check meta field as 'source'
                        if (!source && data.meta && data.meta.source) {
                            source = data.meta.source;
                        }

                        // Method 4: Check acf fields if using ACF
                        if (!source && data.acf && data.acf.source) {
                            source = data.acf.source;
                        }

                        // Method 5: Check top level source field
                        if (!source && data.source) {
                            source = data.source;
                        }


                        if (source) {
                            // Update the credit text if we found a source
                            const existingCaption = img.nextSibling;
                            if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                                const creditElement = existingCaption.querySelector('.credit-text');
                                if (creditElement) {
                                    creditElement.textContent = source;
                                }
                            }
                        } else {
                            // Remove credit element if no source found
                            const existingCaption = img.nextSibling;
                            if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                                const creditElement = existingCaption.querySelector('.credit-text');
                                if (creditElement) {
                                    creditElement.remove();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        // Remove loading text if API fails
                        const existingCaption = img.nextSibling;
                        if (existingCaption && existingCaption.classList.contains('story-image-caption')) {
                            const creditElement = existingCaption.querySelector('.credit-text');
                            if (creditElement) {
                                creditElement.remove();
                            }
                        }
                    });
            } else {
                // No attachment ID found, no credit to show
                credit = '';
            }
        }

        // Only show caption if we have actual content
        if (captionText || credit) {
            // Create caption div
            const captionDiv = document.createElement('div');
            captionDiv.className = 'story-image-caption';

            // Add caption and credit with pipe separator
            let captionHTML = '';
            if (captionText) {
                captionHTML += `<span class="caption-text">${captionText}</span>`;
            }
            if (credit) {
                if (captionText) {
                    captionHTML += `<span class="pipe-separator"> | </span>`;
                }
                captionHTML += `<span class="credit-text">${credit}</span>`;
            }
            captionDiv.innerHTML = captionHTML;

            // Insert caption inside UAGB block (if exists) or after image
            const uagbBlock = img.closest('.wp-block-uagb-image');
            if (uagbBlock) {
                // For UAGB blocks, append inside the block (not as a sibling)
                uagbBlock.appendChild(captionDiv);
            } else {
                // For regular images, insert after the image
                img.parentNode.insertBefore(captionDiv, img.nextSibling);
            }
        }
    });

    // Helper function to update caption display
    function updateCaptionDisplay(img, captionText, creditText) {
        const uagbBlock = img.closest('.wp-block-uagb-image');

        // Remove any existing caption
        if (uagbBlock) {
            // For UAGB blocks, check inside the block
            const existingCaption = uagbBlock.querySelector('.story-image-caption');
            if (existingCaption) {
                existingCaption.remove();
            }
        } else {
            // For regular images, check next sibling
            let nextSibling = img.parentNode.nextElementSibling;
            if (nextSibling && nextSibling.classList.contains('story-image-caption')) {
                nextSibling.remove();
            }
        }

        // Only create caption if we have caption text or credit
        if (captionText || creditText) {
            const captionDiv = document.createElement('div');
            captionDiv.className = 'story-image-caption';

            let captionHTML = '';
            if (captionText) {
                captionHTML += `<span class="caption-text">${captionText}</span>`;
            }
            if (creditText) {
                if (captionText) {
                    captionHTML += `<span class="pipe-separator"> | </span>`;
                }
                captionHTML += `<span class="credit-text">${creditText}</span>`;
            }

            captionDiv.innerHTML = captionHTML;

            // Insert caption inside UAGB block or after regular image
            if (uagbBlock) {
                uagbBlock.appendChild(captionDiv);
            } else {
                img.parentNode.insertBefore(captionDiv, img.nextSibling);
            }
        }
    }
});
</script>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>
