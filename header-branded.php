<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#39e58f">
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="https://use.typekit.net/grj8tmk.css">

<?php wp_head(); ?>

<style>
    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-Regular.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-Regular.woff') format('woff');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
    }

    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-RegularItalic.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-RegularItalic.woff') format('woff');
    font-weight: 400;
    font-style: italic;
    font-display: swap;
    }

    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBold.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBold.woff') format('woff');
    font-weight: 600;
    font-style: normal;
    font-display: swap;
    }

    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBoldItalic.woff2') format('woff2'),
        url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBoldItalic.woff') format('woff');
    font-weight: 600;
    font-style: italic;
    font-display: swap;
    }

    /* CSS Variables */
    :root {
    --highlight-color: #39e58f;
    --primary-font: 'Innovator Grotesk', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    --serif-font: 'Legitima', 'SF Pro Display', ui-serif, Georgia, 'Times New Roman', serif;
    --compressed-font: 'span-compressed', serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Remove all theme styles for branded pages */
    body.page-template-page-portfolio *,
    body.single-story *,
    body.post-type-archive-story * {
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    /* Hide WordPress theme elements */
    body.page-template-page-portfolio #header,
    body.page-template-page-portfolio .site-header:not(#site-header),
    body.page-template-page-portfolio .site-footer:not(#site-footer),
    body.page-template-page-portfolio .main-navigation,
    body.page-template-page-portfolio .entry-header,
    body.page-template-page-portfolio .entry-content,
    body.page-template-page-portfolio .entry-footer,
    body.page-template-page-portfolio .widget-area,
    body.page-template-page-portfolio .sidebar,
    body.page-template-page-portfolio #primary,
    body.page-template-page-portfolio #secondary,
    body.page-template-page-portfolio #content,
    body.page-template-page-portfolio .container,
    body.page-template-page-portfolio .content-area,
    body.single-story #header,
    body.single-story .site-header:not(#site-header),
    body.single-story .site-footer:not(#site-footer),
    body.single-story .main-navigation,
    body.single-story .entry-header,
    body.single-story .entry-content,
    body.single-story .entry-footer,
    body.single-story .widget-area,
    body.single-story .sidebar,
    body.single-story #primary,
    body.single-story #secondary,
    body.single-story #content,
    body.single-story .container,
    body.single-story .content-area,
    body.post-type-archive-story #header,
    body.post-type-archive-story .site-header:not(#site-header),
    body.post-type-archive-story .site-footer:not(#site-footer),
    body.post-type-archive-story .main-navigation,
    body.post-type-archive-story .entry-header,
    body.post-type-archive-story .entry-content,
    body.post-type-archive-story .entry-footer,
    body.post-type-archive-story .widget-area,
    body.post-type-archive-story .sidebar,
    body.post-type-archive-story #primary,
    body.post-type-archive-story #secondary,
    body.post-type-archive-story #content,
    body.post-type-archive-story .container,
    body.post-type-archive-story .content-area {
        display: none !important;
    }

    /* Remove WordPress default margins/padding */
    body.page-template-page-portfolio,
    body.single-story,
    body.post-type-archive-story {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        overflow-x: hidden !important;
        font-family: var(--primary-font) !important;
    }

    /* Hide admin bar completely */
    #wpadminbar {
        display: none !important;
    }

    html {
        margin-top: 0 !important;
    }

    /* Header Styles */
    .site-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: transparent;
        transition: all 0.5s ease;
        padding: 0;
        height: calc(60px + 2vw);
    }

    /* Gradient blur effect for header - 3 points: 10px, 5px, 2px */
    .site-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(10px);
        mask: linear-gradient(to bottom, black 0%, black calc(100% - 30px), transparent calc(100% - 10px));
        -webkit-mask: linear-gradient(to bottom, black 0%, black calc(100% - 30px), transparent calc(100% - 10px));
        z-index: -1;
    }

    .site-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(2px);
        mask: linear-gradient(to bottom, transparent calc(100% - 20px), black calc(100% - 10px), black 100%);
        -webkit-mask: linear-gradient(to bottom, transparent calc(100% - 20px), black calc(100% - 10px), black 100%);
        z-index: -1;
    }

    /* Third pseudo-element for middle blur point */
    .site-header .header-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(5px);
        mask: linear-gradient(to bottom, transparent calc(100% - 30px), black calc(100% - 20px), transparent calc(100% - 10px));
        -webkit-mask: linear-gradient(to bottom, transparent calc(100% - 30px), black calc(100% - 20px), transparent calc(100% - 10px));
        z-index: -1;
    }

    .header-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .site-title-name {
        font-family: var(--serif-font) !important;
        font-size: 48px;
        font-style: italic !important;
        line-height: 48px;
        font-weight: 400;
        text-decoration: none;
        transition: all 0.5s ease;
        color: white; /* Start white for hero section */
        text-shadow: none;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .main-nav {
        position: absolute;
        left: 2vw;
        top: calc((2vw + 60px) / 2);
        transform: translateY(-50%);
    }

    .contact-button {
        position: absolute;
        right: 2vw;
        top: calc((2vw + 60px) / 2);
        transform: translateY(-50%);
    }

    .contact-pill {
        display: inline-block;
        padding: 8px 20px;
        background: white;
        border: 1px solid var(--highlight-color);
        border-radius: 25px;
        text-decoration: none;
        font-family: var(--primary-font) !important;
        font-size: 16px;
        font-weight: 500;
        color: #000;
        transition: all 0.3s ease;
    }

    .contact-pill:hover {
        background: var(--highlight-color);
        color: white;
    }

    .main-nav ul {
        display: flex;
        list-style: none;
        gap: 0;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .main-nav li:not(:last-child)::after {
        content: " / ";
        color: inherit;
        margin: 0 0.8rem;
    }

    .main-nav a {
        font-family: var(--primary-font) !important;
        font-size: 20px;
        text-decoration: none;
        font-weight: 400;
        transition: all 0.5s ease;
        padding: 0.5rem 0;
        position: relative;
        color: white; /* Start white for hero section */
        text-shadow: none;
    }

    .main-nav a:hover,
    .main-nav a.active {
        color: #39e58f;
        font-weight: 600;
    }

    /* Header overlay styles for full-bleed section */
    .site-header.over-full-bleed::before,
    .site-header.over-full-bleed::after,
    .site-header.over-full-bleed .header-content::before {
        backdrop-filter: none !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .site-title-name {
        color: white !important;
        text-shadow: none !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .site-title-role {
        color: rgba(255, 255, 255, 0.8) !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .main-nav a {
        color: white !important;
        text-shadow: none !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .main-nav a:hover,
    .site-header.over-full-bleed .main-nav a.active {
        color: #39e58f !important;
    }

    .site-header.over-full-bleed .contact-pill {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: white !important;
        color: white !important;
    }

    .site-header.over-full-bleed .contact-pill:hover {
        background: white !important;
        color: #000 !important;
    }

    /* Header NOT over full-bleed (normal sections) - black text */
    .site-header:not(.over-full-bleed) .site-title-name {
        color: #000 !important;
    }

    .site-header:not(.over-full-bleed) .main-nav a {
        color: #000 !important;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .site-header {
            height: auto;
            padding: 1rem 0;
        }

        .header-content {
            flex-direction: column;
            gap: 0.5rem;
        }

        .site-title-name {
            font-size: 24px;
            line-height: 24px;
            position: static;
            transform: none;
            order: 1;
        }

        .main-nav {
            position: static;
            transform: none;
            order: 2;
        }

        .contact-button {
            position: static;
            transform: none;
            order: 3;
        }

        .contact-pill {
            font-size: 14px;
            padding: 6px 16px;
        }

        .main-nav a {
            font-size: 17px;
        }

        .main-nav li:not(:last-child)::after {
            margin: 0 0.5rem;
        }
    }
</style>
</head>
<body>
    <!-- Header -->
    <header class="site-header" id="site-header">
        <div class="header-content">
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo home_url('/#about'); ?>" class="nav-link">About</a></li>
                    <li><a href="<?php echo get_post_type_archive_link('story'); ?>" class="nav-link">Stories</a></li>
                    <li><a href="<?php echo home_url('/#strategy'); ?>" class="nav-link">Strategy</a></li>
                    <li><a href="<?php echo home_url('/#cv'); ?>" class="nav-link">CV</a></li>
                </ul>
            </nav>
            
            <a href="<?php echo home_url('/'); ?>" class="site-title-name">
                Reuben J. Brown
            </a>
            
            <div class="contact-button">
                <a href="<?php echo home_url('/#contact'); ?>" class="contact-pill">contact ↓</a>
            </div>
        </div>
    </header>

<script>
    // Smooth scroll navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            
            // Handle both full URLs and hash-only hrefs
            let hash;
            if (targetId.includes('#')) {
                hash = targetId.split('#')[1];
            } else {
                hash = targetId;
            }
            
            const targetElement = document.querySelector('#' + hash);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Update active navigation - only highlight when section fills majority of screen
    function updateActiveNav() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        const viewportHeight = window.innerHeight;
        const scrollTop = window.scrollY;
        
        let bestMatch = '';
        let bestMatchScore = 0;
        
        // Remove all active classes first
        navLinks.forEach(link => link.classList.remove('active'));
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.clientHeight;
            const viewportTop = scrollTop;
            const viewportBottom = scrollTop + viewportHeight;
            
            // Calculate how much of the viewport this section occupies
            const visibleTop = Math.max(sectionTop, viewportTop);
            const visibleBottom = Math.min(sectionBottom, viewportBottom);
            const visibleHeight = Math.max(0, visibleBottom - visibleTop);
            const sectionScore = visibleHeight / viewportHeight;
            
            // Only consider this section if it occupies more than 50% of viewport
            if (sectionScore > 0.5 && sectionScore > bestMatchScore) {
                bestMatchScore = sectionScore;
                bestMatch = section.getAttribute('id');
            }
        });
        
        // Highlight the section that best fills the viewport
        if (bestMatch) {
            const activeLink = document.querySelector(`.nav-link[href*="#${bestMatch}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    }

    window.addEventListener('scroll', updateActiveNav);
    updateActiveNav(); // Run on page load

    // Header transparency and footer visibility for full-bleed sections (portfolio page only)
    function updateHeaderAndFooterForFullBleed() {
        const header = document.querySelector('.site-header');
        const footer = document.querySelector('.site-footer');
        const fullBleedSection = document.querySelector('.featured-story-full-bleed');
        
        if (!header || !footer || !fullBleedSection) {
            return;
        }
        
        const headerHeight = header.offsetHeight;
        const fullBleedRect = fullBleedSection.getBoundingClientRect();
        const footerRect = footer.getBoundingClientRect();
        
        // Check if header overlaps with full-bleed section
        const headerOverlaps = fullBleedRect.top <= headerHeight && fullBleedRect.bottom >= 0;
        
        if (headerOverlaps) {
            header.classList.add('over-full-bleed');
        } else {
            header.classList.remove('over-full-bleed');
        }
        
        // Check if footer overlaps with full-bleed section
        const footerLogo = footer.querySelector('.footer-logo img');
        const copyright = footer.querySelector('.copyright');
        
        // Footer overlaps with hero section if hero section bottom is below footer top
        const footerOverlaps = fullBleedRect.bottom > footerRect.top && fullBleedRect.top < footerRect.bottom;
        
        if (footerOverlaps) {
            // Footer is overlapping hero section - use white logo/text and disable blur
            footer.classList.add('over-full-bleed');
            if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
            if (copyright) copyright.style.color = 'white';
        } else {
            // Footer is not overlapping hero section - use black logo/gray text and enable blur
            footer.classList.remove('over-full-bleed');
            if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
            if (copyright) copyright.style.color = '#808080';
        }
    }

    
    // Function to initialize effects once everything is loaded
    function initializeFullBleedEffects() {
        const header = document.querySelector('.site-header');
        const footer = document.querySelector('.site-footer');
        const fullBleedSection = document.querySelector('.featured-story-full-bleed');
        
        if (header && footer) {
            window.addEventListener('scroll', updateHeaderAndFooterForFullBleed);
            window.addEventListener('resize', updateHeaderAndFooterForFullBleed);
            updateHeaderAndFooterForFullBleed(); // Run initial check
        }
    }
    
    // Always run the initialization (not just on portfolio pages)
    // Try multiple timing approaches
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFullBleedEffects);
    } else {
        initializeFullBleedEffects();
    }
    
    // Also try after window load for shortcode content
    window.addEventListener('load', initializeFullBleedEffects);
    
    // And after a short delay to ensure shortcodes are rendered
    setTimeout(initializeFullBleedEffects, 1000);
    setTimeout(initializeFullBleedEffects, 3000); // Even longer delay
</script>
