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
        margin: 2rem auto 0 auto; /* Remove bottom margin */
        display: block;
    }

    /* Simple image caption below image */
    .story-image-caption {
        font-family: var(--primary-font);
        font-size: 12px;
        line-height: 1.4;
        text-align: left; /* Bottom-left alignment */
        margin-top: 8px;
        margin-bottom: 32px; /* Add 32px bottom padding */
        width: calc(100vw - 4vw);
        margin-left: auto;
        margin-right: auto;
    }

    .story-image-caption .caption-text {
        font-weight: 600; /* Semi-bold for caption */
        color: var(--text-color-muted); /* Use grey color variable */
        display: inline; /* Same line */
    }

    .story-image-caption .credit-text {
        font-weight: 400; /* Regular for credit */
        color: var(--text-color-muted);
        display: inline; /* Same line */
    }

    .story-image-caption .credit-text::before {
        content: " "; /* Space before credit */
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Process all images in story content for automatic captions
    const storyImages = document.querySelectorAll('.story-content-inner img');

    storyImages.forEach(function(img) {
        console.log('Processing image:', img.src);

        // Get WordPress caption from multiple possible sources
        let captionText = '';
        let credit = '';

        // Method 1: Check if image is inside a WordPress figure with figcaption
        const figure = img.closest('figure');
        if (figure) {
            const figcaption = figure.querySelector('figcaption');
            if (figcaption) {
                captionText = figcaption.textContent.trim();
                console.log('Found figcaption:', captionText);
            }
        }

        // Method 2: Check for wp-caption-text (WordPress standard)
        const wpCaption = img.parentNode.querySelector('.wp-caption-text');
        if (wpCaption && !captionText) {
            captionText = wpCaption.textContent.trim();
            console.log('Found wp-caption-text:', captionText);
        }

        // Method 3: Check parent for wp-caption div
        const wpCaptionDiv = img.closest('.wp-caption');
        if (wpCaptionDiv && !captionText) {
            const captionElement = wpCaptionDiv.querySelector('.wp-caption-text');
            if (captionElement) {
                captionText = captionElement.textContent.trim();
                console.log('Found wp-caption div:', captionText);
            }
        }

        // Method 4: Check for WordPress blocks caption
        const wpBlockCaption = img.parentNode.querySelector('.wp-element-caption');
        if (wpBlockCaption && !captionText) {
            captionText = wpBlockCaption.textContent.trim();
            console.log('Found wp-element-caption:', captionText);
        }

        // Method 4b: Check for wp-block-image caption
        const wpBlockImage = img.closest('.wp-block-image');
        if (wpBlockImage && !captionText) {
            const blockCaption = wpBlockImage.querySelector('figcaption');
            if (blockCaption) {
                captionText = blockCaption.textContent.trim();
                console.log('Found wp-block-image figcaption:', captionText);
            }
        }

        // Method 4c: Check for UAGB image caption
        const uagbBlock = img.closest('.wp-block-uagb-image');
        if (uagbBlock && !captionText) {
            console.log('Found UAGB block, searching for caption...');

            // Check for figcaption within UAGB block
            const uagbCaption = uagbBlock.querySelector('figcaption');
            if (uagbCaption) {
                captionText = uagbCaption.textContent.trim();
                console.log('Found UAGB figcaption:', captionText);
            }

            // Check for UAGB-specific caption classes
            const uagbCaptionText = uagbBlock.querySelector('.uagb-image-caption, .wp-block-uagb-image__caption');
            if (uagbCaptionText && !captionText) {
                captionText = uagbCaptionText.textContent.trim();
                console.log('Found UAGB caption text:', captionText);
            }

            // Debug: Log all elements within UAGB block
            console.log('UAGB block contents:', uagbBlock.innerHTML.substring(0, 200));
        }

        // Method 5: Check for caption in surrounding paragraph
        const nextElement = img.parentNode.nextElementSibling;
        if (nextElement && nextElement.tagName === 'P' && nextElement.classList.contains('caption') && !captionText) {
            captionText = nextElement.textContent.trim();
            console.log('Found caption paragraph:', captionText);
        }

        // Method 6: Check for data-caption attribute
        if (!captionText && img.getAttribute('data-caption')) {
            captionText = img.getAttribute('data-caption').trim();
            console.log('Found data-caption:', captionText);
        }

        // Method 6b: Check for WordPress attachment attributes
        if (!captionText) {
            // Check for data-attachment_id attribute
            const attachmentId = img.getAttribute('data-attachment-id') || img.getAttribute('data-id');
            if (attachmentId) {
                console.log('Found data-attachment-id:', attachmentId);
            }

            // Check for WordPress-specific attributes that might contain caption
            const wpCaption = img.getAttribute('data-caption');
            const wpTitle = img.getAttribute('data-title');
            const wpDescription = img.getAttribute('data-description');

            if (wpCaption) {
                captionText = wpCaption.trim();
                console.log('Found WordPress data-caption attribute:', captionText);
            } else if (wpDescription) {
                captionText = wpDescription.trim();
                console.log('Found WordPress data-description attribute:', captionText);
            }
        }

        // Method 7: Comprehensive debugging - analyze complete image structure
        console.log('=== COMPREHENSIVE IMAGE DEBUGGING ===');
        console.log('Image src:', img.src);
        console.log('Image classes:', img.className);
        console.log('Image attributes:', img.attributes);

        // Check all possible image containers and siblings
        console.log('--- Parent Structure ---');
        console.log('Direct parent:', img.parentNode.tagName, img.parentNode.className);
        console.log('Grandparent:', img.parentNode.parentNode ? img.parentNode.parentNode.tagName + ' ' + img.parentNode.parentNode.className : 'none');
        console.log('Great-grandparent:', img.parentNode.parentNode && img.parentNode.parentNode.parentNode ? img.parentNode.parentNode.parentNode.tagName + ' ' + img.parentNode.parentNode.parentNode.className : 'none');

        // Check siblings at different levels
        console.log('--- Sibling Structure ---');
        console.log('Next sibling:', img.nextElementSibling ? img.nextElementSibling.tagName + ' ' + img.nextElementSibling.className + ' content: "' + img.nextElementSibling.textContent.trim().substring(0, 50) + '"' : 'none');
        console.log('Previous sibling:', img.previousElementSibling ? img.previousElementSibling.tagName + ' ' + img.previousElementSibling.className : 'none');
        console.log('Parent next sibling:', img.parentNode.nextElementSibling ? img.parentNode.nextElementSibling.tagName + ' ' + img.parentNode.nextElementSibling.className + ' content: "' + img.parentNode.nextElementSibling.textContent.trim().substring(0, 50) + '"' : 'none');
        console.log('Parent previous sibling:', img.parentNode.previousElementSibling ? img.parentNode.previousElementSibling.tagName + ' ' + img.parentNode.previousElementSibling.className : 'none');

        // Look for any caption-related elements in vicinity
        console.log('--- Caption Element Search ---');
        const possibleCaptionElements = img.parentNode.parentNode ? img.parentNode.parentNode.querySelectorAll('figcaption, .wp-caption-text, .wp-element-caption, .caption, p[class*="caption"], div[class*="caption"]') : [];
        console.log('Found caption-related elements:', possibleCaptionElements.length);
        possibleCaptionElements.forEach((el, index) => {
            console.log(`Caption element ${index}:`, el.tagName, el.className, 'content:', el.textContent.trim().substring(0, 100));
        });

        // Check for any text in immediate vicinity that might be captions
        console.log('--- Nearby Text Content ---');
        const nearbyElements = [img.nextElementSibling, img.parentNode.nextElementSibling, img.parentNode.parentNode ? img.parentNode.parentNode.nextElementSibling : null];
        nearbyElements.forEach((el, index) => {
            if (el && el.textContent && el.textContent.trim()) {
                console.log(`Nearby text ${index}:`, el.tagName, el.className, 'content:', el.textContent.trim().substring(0, 100));
            }
        });

        // Debug: show what we found
        console.log('Caption search results for image:', img.src);
        console.log('- figcaption text:', captionText);

        // If no caption found from DOM, try WordPress REST API
        if (!captionText) {
            console.log('- No caption found in DOM, searching WordPress media library...');

            // Extract filename to search for attachment
            const filename = img.src.split('/').pop().split('?')[0]; // Remove query params
            const filenameWithoutExt = filename.replace(/\.[^.]+$/, ''); // Remove extension
            console.log('- Searching for media with filename:', filenameWithoutExt);

            // Search for attachment by filename
            fetch('/wp-json/wp/v2/media?search=' + encodeURIComponent(filenameWithoutExt) + '&per_page=1')
                .then(response => response.json())
                .then(mediaArray => {
                    if (mediaArray && mediaArray.length > 0) {
                        const mediaData = mediaArray[0];
                        console.log('- Found WordPress media data:', mediaData);

                        // Check for caption in WordPress media data
                        let foundCaption = '';
                        if (mediaData.caption && mediaData.caption.rendered) {
                            foundCaption = mediaData.caption.rendered.replace(/<[^>]*>/g, '').trim(); // Strip HTML
                            console.log('- Found WordPress media library caption:', foundCaption);
                        }

                        // Check for source in meta fields
                        let foundSource = '';
                        if (mediaData.meta) {
                            foundSource = mediaData.meta._media_source || mediaData.meta.media_source || mediaData.meta.source || '';
                            if (foundSource) {
                                console.log('- Found WordPress media source:', foundSource);
                            }
                        }

                        // Update caption display if we found caption data
                        if (foundCaption || foundSource) {
                            updateCaptionDisplay(img, foundCaption, foundSource);
                        } else {
                            console.log('- No caption or source found in WordPress media library');
                        }
                    } else {
                        console.log('- No WordPress media found for filename:', filenameWithoutExt);
                        console.log('- Alt text available:', img.getAttribute('alt'));
                        console.log('- Title available:', img.getAttribute('title'));

                        console.log('=== ALL IMAGE ATTRIBUTES ===');
                        for (let i = 0; i < img.attributes.length; i++) {
                            const attr = img.attributes[i];
                            console.log(`${attr.name}: ${attr.value}`);
                        }
                    }
                })
                .catch(error => {
                    console.log('- Error searching WordPress media:', error);
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
                console.log('Found attachment ID:', attachmentId);

                // Make AJAX call to get media source
                fetch('/wp-json/wp/v2/media/' + attachmentId)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Full media data:', data);

                        // Check multiple possible locations for source field
                        let source = '';

                        // Method 1: Check meta field
                        if (data.meta && data.meta._media_source) {
                            source = data.meta._media_source;
                            console.log('Found source in meta._media_source:', source);
                        }

                        // Method 2: Check meta field without underscore
                        if (!source && data.meta && data.meta.media_source) {
                            source = data.meta.media_source;
                            console.log('Found source in meta.media_source:', source);
                        }

                        // Method 3: Check meta field as 'source'
                        if (!source && data.meta && data.meta.source) {
                            source = data.meta.source;
                            console.log('Found source in meta.source:', source);
                        }

                        // Method 4: Check acf fields if using ACF
                        if (!source && data.acf && data.acf.source) {
                            source = data.acf.source;
                            console.log('Found source in acf.source:', source);
                        }

                        // Method 5: Check top level source field
                        if (!source && data.source) {
                            source = data.source;
                            console.log('Found source in data.source:', source);
                        }

                        console.log('Final source value:', source);

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
                        console.log('Could not fetch media source:', error);
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

        // Ensure we have something to show for debugging
        if (!captionText && !credit) {
            captionText = 'DEBUG: Caption not found';
        }

        // Always show caption area for debugging (change this later)
        if (captionText || credit || true) {
            // Create caption div
            const captionDiv = document.createElement('div');
            captionDiv.className = 'story-image-caption';

            // Add caption text if available
            if (captionText) {
                const captionSpan = document.createElement('div');
                captionSpan.className = 'caption-text';
                captionSpan.textContent = captionText;
                captionDiv.appendChild(captionSpan);
            }

            // Add credit if available
            if (credit) {
                const creditSpan = document.createElement('div');
                creditSpan.className = 'credit-text';
                creditSpan.textContent = credit;
                captionDiv.appendChild(creditSpan);
            }

            // Insert caption after the image
            img.parentNode.insertBefore(captionDiv, img.nextSibling);
            console.log('Caption added for image');
        }
    });

    // Helper function to update caption display
    function updateCaptionDisplay(img, captionText, creditText) {
        // For UAGB images, we need to insert after the UAGB block container, not just the figure
        const uagbBlock = img.closest('.wp-block-uagb-image');
        const insertionPoint = uagbBlock || img.parentNode;

        // Remove any existing caption after this insertion point
        let nextSibling = insertionPoint.nextElementSibling;
        if (nextSibling && nextSibling.classList.contains('story-image-caption')) {
            nextSibling.remove();
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
                captionHTML += `<span class="credit-text">${creditText}</span>`;
            }

            captionDiv.innerHTML = captionHTML;
            insertionPoint.insertAdjacentElement('afterend', captionDiv);
            console.log('Updated caption display with:', captionText, creditText);
            console.log('Inserted caption after:', insertionPoint.className);
        }
    }
});
</script>

<?php endwhile; endif; ?>

<?php get_footer('branded'); ?>