<?php
/**
 * Template Name: Video Hero Story
 * Template Post Type: story
 *
 * Single Story Template - Video Hero Design
 * Uses shared header and footer from portfolio page
 */

// Disable WordPress admin bar for story pages
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

// Add story templates CSS to wp_head
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' .                                                                                         
    get_stylesheet_directory_uri() . '/story-templates.css?v=' . wp_get_theme()->get('Version') . '">' . "\n";
}, 999);

get_header('branded'); ?>

<style>
    /* Template-Specific CSS for Video Hero Story Template */
    /* Common styles are now in story-templates.css */

    /* Full Bleed Hero Section for Video */
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

    /* Fallback still image styling - matches Full Bleed template */
    .story-hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    /* Video background styling */
    .story-hero-background video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    /* Fallback image styling */
    .fallback-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .story-hero-background.video-error .fallback-image {
        opacity: 1;
    }

    .story-hero-background.video-loaded .fallback-image {
        opacity: 0;
    }

    /* Video Hero Content - positioned at bottom */
    .story-hero-content {
        position: fixed;
        bottom: 10vh;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0;
        transition: opacity 0.3s ease, filter 0.3s ease;
    }

    .story-hero-text {
        max-width: 800px;
        text-align: center;
    }

    /* Headline - Legitima font at 36px */
    .story-hero-text h1 {
        font-family: var(--serif-font) !important;
        font-size: 36px !important;
        font-weight: 400 !important;
        font-style: italic !important;
        text-transform: none !important;
        color: white;
        margin-bottom: 1rem;
        line-height: 1.3;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
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
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    .story-hero-text .story-meta {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        font-family: var(--primary-font);
        font-size: 16px;
    }

    .story-hero-text .story-meta i {
        color: white;
    }

    /* Hero video credit positioned below hero section */
    .hero-image-credit {
        position: absolute;
        left: 2vw;
        top: calc(100vh + 4px);
        font-family: var(--primary-font);
        font-size: 12px;
        color: var(--text-color-muted);
        z-index: 1000;
    }

    /* Mobile Responsive - Video Hero Specific */
    @media (max-width: 768px) {
        .story-hero-content {
            width: 96vw;
            bottom: 5vh;
        }

        .story-hero-text {
            width: 96vw;
            max-width: 96vw;
            min-width: 96vw;
        }

        .story-hero-text h1 {
            font-size: 28px !important;
            margin-bottom: 1rem;
        }

        .story-hero-text h2,
        .story-hero-text h2 p {
            font-size: 18px !important;
            margin-bottom: 1.5rem;
        }

        .story-content-inner p {
            font-size: 18px !important;
        }

        .hero-image-credit {
            left: 2vw;
            top: calc(100vh + 12px);
            text-align: left;
        }
    }

    @media (max-width: 480px) {
        .story-hero-content {
            padding: 1.5rem;
            bottom: 3vh;
        }

        .story-hero-text h1 {
            font-size: 24px !important;
            margin-bottom: 0.75rem;
        }

        .story-hero-text h2 {
            font-size: 16px !important;
            margin-bottom: 1rem;
        }
    }

    /* Video-specific content paragraph sizing */
    .story-content-inner p {
        font-family: var(--serif-font) !important;
        font-size: 24px !important;
        margin-bottom: 1.5rem;
    }

    .story-content-inner p.story-meta {
        margin-top: 16px;
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

    // Video error handling
    const video = document.querySelector('.story-hero-full-bleed video');
    const background = document.querySelector('.story-hero-background');

    if (video && background) {
        // Check if video element can play
        if (video.error || !video.canPlayType || !video.canPlayType('video/mp4')) {
            console.log('Video not supported or has error, showing fallback image');
            background.classList.add('video-error');
        }

        // Handle video load errors
        video.addEventListener('error', function() {
            console.log('Video failed to load, showing fallback image');
            background.classList.add('video-error');
        });

        // Handle video stall/timeout
        video.addEventListener('stalled', function() {
            if (video.readyState < 3) {
                console.log('Video stalled during loading, showing fallback image');
                background.classList.add('video-error');
            }
        });

        // Timeout fallback
        const loadTimeout = setTimeout(function() {
            if (video.readyState < 3) {
                console.log('Video load timeout, showing fallback image');
                background.classList.add('video-error');
            }
        }, 3000);

        // Clear timeout if video loads successfully
        video.addEventListener('canplaythrough', function() {
            clearTimeout(loadTimeout);
            background.classList.remove('video-error');
            background.classList.add('video-loaded');
            console.log('Video loaded successfully');
        });

        // Ensure video keeps looping
        video.addEventListener('ended', function() {
            video.currentTime = 0;
            video.play();
        });

        // Handle video pause
        video.addEventListener('pause', function() {
            if (!background.classList.contains('video-error')) {
                video.play();
            }
        });
    }
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

<!-- Story Hero Full Bleed with Video -->
<section class="story-hero-full-bleed">
    <div class="story-hero-background">
        <?php
        // Get video URL from ACF field (you'll need to add this field)
        $video_url = get_field('hero_video_url');
        $fallback_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

        if ($video_url) : ?>
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            </video>
            <div class="fallback-image" style="background-image: url('<?php echo esc_url($fallback_image_url); ?>')"></div>
        <?php elseif (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php else : ?>
            <!-- Fallback background if no featured image -->
            <div style="background: linear-gradient(135deg, #39e58f 0%, #2dc776 100%); width: 100%; height: 100%;"></div>
        <?php endif; ?>
    </div>
    <div class="story-hero-content">
        <div class="story-hero-text">
            <h1><?php echo esc_html(get_the_title()); ?></h1>

            <?php if (has_excerpt()) : ?>
                <h2><?php the_excerpt(); ?></h2>
            <?php endif; ?>

            <?php
            $publication = get_field('publication');
            $medium = get_field('medium');
            $publish_date = get_field('publish_date');
            $photo_credit = get_field('photo_credit');
            ?>

            <?php if ($medium || $publication || $publish_date) : ?>
                <p class="story-meta">
                    <?php if ($medium) : ?>
                        <?php echo esc_html($medium); ?>
                    <?php endif; ?>
                    <?php if ($publication) : ?>
                        <?php echo $medium ? ' for ' : 'For '; ?><i><?php echo esc_html($publication); ?></i>
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

<!-- Video credit positioned below hero section -->
<?php if ($photo_credit) : ?>
    <div class="hero-image-credit">video: <?php echo esc_html($photo_credit); ?></div>
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
