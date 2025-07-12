<?php
/*
Template Name: Portfolio Page
*/

// Disable WordPress admin bar for this page
show_admin_bar(false);

// Remove theme's CSS and JS but keep essential WordPress functions
add_action('wp_enqueue_scripts', function() {
    // Remove theme styles but keep essential WordPress ones
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
    
    // Remove the manual CSS enqueue - the plugin handles this
}, 100);

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="https://use.typekit.net/ffl7rra.css">

<?php 
// Keep essential WordPress head elements and our custom styles
wp_head(); 
?>

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
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Remove all theme styles for this page */
    body.page-template-page-portfolio * {
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
    body.page-template-page-portfolio .content-area {
        display: none !important;
    }

    /* Remove WordPress default margins/padding */
    body.page-template-page-portfolio {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        overflow-x: hidden !important;
    }

    /* Hide admin bar completely */
    #wpadminbar {
        display: none !important;
    }

    html {
        margin-top: 0 !important;
    }


    body {
        font-family: var(--primary-font) !important;
        line-height: 1.6;
        overflow-x: hidden;
    }

  
    /* Header Styles */
    .site-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, 1);
        transition: all 0.5s ease;
        padding: 0;
        height: calc(min(3vw, 40px) + 2vw);
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
        font-size: min(3vw, 40px);
        line-height: min(3.6vw, 48px);
        font-weight: 400;
        text-decoration: none;
        transition: all 0.5s ease;
        color: #000;
        text-shadow: none;
        position: absolute;
        left: 50%;
        top: 1vw;
        transform: translateX(-50%);
    }

    .site-title-role {
        font-family: var(--primary-font) !important;
        font-size: 20px;
        line-height: 20px;
        font-weight: 400;
        color: #808080;
        text-shadow: none;
        transition: all 0.5s ease;
        position: absolute;
        left: 2vw;
        top: calc(1vw + min(3.6vw, 48px) / 2);
        transform: translateY(-50%);
    }

    .main-nav {
        position: absolute;
        right: 2vw;
        top: calc(1vw + min(3.6vw, 48px) / 2);
        transform: translateY(-50%);
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
        color: #000;
        text-shadow: none;
    }

    .main-nav a:hover,
    .main-nav a.active {
        color: #39e58f;
        font-weight: 600;
    }



    /* Footer Styles */
    .site-footer {
        position: sticky;
        bottom: 0;
        background: transparent;
        padding: 2vw;
        margin-top: 2rem;
        z-index: 100;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .social-links {
        display: flex;
        gap: 0;
        align-items: center;
    }

    .social-links a {
        text-decoration: none;
        transition: all 0.5s ease;
        color: #808080;
        font-size: 14px;
    }

    .social-links a:hover {
        color: #39e58f;
    }

    .copyright {
        color: #808080;
        font-size: 14px;
        transition: all 0.5s ease;
    }

    /* Main Content Wrapper */
    .main-content {
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: white;
        margin-top: 0;
        padding-top: calc(min(3vw, 40px) + 2vw);
        position: relative;
        z-index: 10;
    }
    
    .content-section {
        background: white;
    }

    /* Section Headings */
    .section-heading {
        font-family: var(--primary-font) !important;
        font-size: min(3vw, 54px);
        line-height: min(3.6vw, 64px);
        font-weight: 600;
        color: #808080;
        text-align: center;
        margin: 4rem 0;
    }

    .footer-logo {
        width: 40px;
        height: 40px;
    }

    .footer-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Header overlay styles for full-bleed section */
    .site-header.over-full-bleed {
        background: transparent !important;
        backdrop-filter: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 1) !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .site-title-name {
        color: white !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .site-title-role {
        color: rgba(255, 255, 255, 0.8) !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .main-nav a {
        color: white !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
        transition: all 0.3s ease !important;
    }

    .site-header.over-full-bleed .main-nav a:hover,
    .site-header.over-full-bleed .main-nav a.active {
        color: #39e58f !important;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .site-title-name {
            font-size: 2rem;
            line-height: 2.4rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .site-title-role {
            display: none;
        }

        .main-nav {
            position: absolute;
            top: 50%;
            right: 2vw;
            left: auto;
            transform: translateY(-50%);
        }

        .main-nav a {
            font-size: 20px;
        }

        .main-nav li:not(:last-child)::after {
            margin: 0 0.5rem;
        }

        .section-heading {
            font-size: 2rem;
            line-height: 2.4rem;
            margin: 3rem 0 1.5rem 0;
        }

        .main-content {
            padding-top: calc(2.4rem + 4rem);
        }

        .footer-content {
            padding: 1rem 0;
        }

        .social-links {
            gap: 0;
        }


        .footer-logo {
            width: 35px;
            height: 35px;
        }

        .copyright {
            display: none; /* Always hidden on mobile */
        }
    }
</style>
</head>
<body>
    <!-- Header -->
    <header class="site-header" id="site-header">
        <div class="header-content">
            <a href="#" class="site-title-name">
                Reuben J. Brown
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#stories" class="nav-link">Stories</a></li>
                    <li><a href="#strategy" class="nav-link">Strategy</a></li>
                    <li><a href="#cv" class="nav-link">CV</a></li>
                </ul>
            </nav>
            <div class="site-title-role">
                Multimedia Journalist
            </div>
        </div>
    </header>


    <!-- Full Bleed Hero Section -->
    <?php echo do_shortcode('[featured_story_full_bleed]'); ?>
    
    <!-- Main Content Wrapper -->
    <main class="main-content">
        <?php echo do_shortcode('[reuben_about]'); ?>
        
        <h1 class="section-heading">Stories</h1>
        <section class="content-section stories-section" id="stories">
            <div class="section-container">
                <div class="stories-content">
                    <?php echo do_shortcode('[story_grid]'); ?>
                    <?php echo do_shortcode('[story_list]'); ?>
                </div>
            </div>
        </section>
        
        <h1 class="section-heading">Strategy</h1>
        <?php echo do_shortcode('[reuben_strategy]'); ?>
        
        <h1 class="section-heading">CV</h1>
        <?php echo do_shortcode('[reuben_cv]'); ?>
    </main>

    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png" alt="RJB Logo" id="footer-logo-img">
            </div>
            <div class="social-links" style="color: #808080">
                <p class="caption"><a href="mailto:reubenjbrown@protonmail.com">email</a> / <a href="https://www.instagram.com/reubenj.brown/">instagram</a> / <a href="https://www.linkedin.com/in/reuben-j-brown/">linkedin</a>
            </div>
            <div class="copyright caption">© Reuben J. Brown 2025</div>
        </div>
    </footer>

    <script>
        // Set footer logo to black since no splash area
        const footerLogo = document.getElementById('footer-logo-img');
        if (footerLogo) {
            footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
        }

        // Smooth scroll navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Update active navigation
        function updateActiveNav() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveNav);
        updateActiveNav(); // Run on page load

        // Header transparency and footer visibility for full-bleed sections
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
            if (fullBleedRect.top <= headerHeight && fullBleedRect.bottom >= 0) {
                header.classList.add('over-full-bleed');
            } else {
                header.classList.remove('over-full-bleed');
            }
            
            // Check if footer overlaps with full-bleed section
            const socialLinks = footer.querySelector('.social-links');
            const footerLogo = footer.querySelector('.footer-logo img');
            const copyright = footer.querySelector('.copyright');
            
            // Footer overlaps with hero section if hero section bottom is below footer top
            if (fullBleedRect.bottom > footerRect.top && fullBleedRect.top < footerRect.bottom) {
                // Footer is overlapping hero section - use white logo/text
                if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
                if (copyright) copyright.style.color = 'white';
                if (socialLinks) socialLinks.style.display = 'none';
            } else {
                // Footer is not overlapping hero section - use black logo/gray text
                if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
                if (copyright) copyright.style.color = '#808080';
                if (socialLinks) socialLinks.style.display = 'block';
            }
        }

        window.addEventListener('scroll', updateHeaderAndFooterForFullBleed);
        window.addEventListener('resize', updateHeaderAndFooterForFullBleed);
        updateHeaderAndFooterForFullBleed(); // Run on page load
    </script>

    <?php wp_footer(); ?>
</body>
</html>
