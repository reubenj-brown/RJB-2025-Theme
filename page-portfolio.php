
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
        overflow-x: hidden;
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
        font-size: 60px;
        line-height: 60px;
        font-weight: 400;
        text-decoration: none;
        transition: all 0.5s ease;
        color: #000;
        text-shadow: none;
        position: absolute;
        left: 2vw;
        top: 1vw;
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
        top: calc((2vw + 60px) / 2);
        transform: translateY(-50%);
    }

    .main-nav {
        position: absolute;
        right: 2vw;
        top: calc((2vw + 60px) / 2);
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
        padding: 1vw 2vw;
        z-index: 100;
    }

    /* Gradient blur effect for footer - 3 points: 2px, 5px, 10px */
    .site-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(10px);
        mask: linear-gradient(to bottom, transparent 10px, black 30px, black 100%);
        -webkit-mask: linear-gradient(to bottom, transparent 10px, black 30px, black 100%);
        z-index: -1;
    }

    .site-footer::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(2px);
        mask: linear-gradient(to bottom, black 0%, black 20px, transparent 30px);
        -webkit-mask: linear-gradient(to bottom, black 0%, black 20px, transparent 30px);
        z-index: -1;
    }

    /* Third pseudo-element for middle blur point */
    .site-footer .footer-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        backdrop-filter: blur(5px);
        mask: linear-gradient(to bottom, transparent 10px, black 20px, transparent 30px);
        -webkit-mask: linear-gradient(to bottom, transparent 10px, black 20px, transparent 30px);
        z-index: -1;
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
        position: relative;
        z-index: 10;
    }
    
    .content-section {
        background: white;
    }

    /* Section Headings */
    .section-heading {
        font-family: var(--primary-font) !important;
        font-size: 48px;
        line-height: 48px;
        font-weight: 600;
        color: #808080;
        text-align: center;
        margin: 0;
        padding: 4vw 0;
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

    /* Footer overlay styles for full-bleed section */
    .site-footer.over-full-bleed::before,
    .site-footer.over-full-bleed::after,
    .site-footer.over-full-bleed .footer-content::before {
        backdrop-filter: none !important;
        -webkit-mask: none !important;
        mask: none !important;
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
            font-size: 2.46rem;
            line-height: 2.46rem;
            position: static;
            transform: none;
            left: auto;
            top: auto;
        }

        .site-title-role {
            display: none;
        }

        .main-nav {
            position: static;
            transform: none;
            top: auto;
            right: auto;
            left: auto;
        }

        .main-nav a {
            font-size: 17px;
        }

        .main-nav li:not(:last-child)::after {
            margin: 0 0.5rem;
        }

        .section-heading {
            font-size: 2rem;
            line-height: 2.4rem;
        }

        .main-content {
            padding: 0 4vw;
        }

        .site-footer {
            padding: 3vw 4vw 2vw 4vw;
        }

        .social-links {
            gap: 0;
        }


        .footer-logo {
            width: 28px;
            height: 28px;
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
                    <li><a href="#features" class="nav-link">Features</a></li>
                    <li><a href="#reviews" class="nav-link">Reviews</a></li>
                    <li><a href="#profiles" class="nav-link">Profiles</a></li>
                    <li><a href="#interviews" class="nav-link">Interviews</a></li>
                    <li><a href="#photographs" class="nav-link">Photographs</a></li>
                    <li><a href="#strategy" class="nav-link">Strategy</a></li>
                    <li><a href="#cv" class="nav-link">CV</a></li>
                </ul>
            </nav>
            <div class="site-title-role">
            </div>
        </div>
    </header>


    <!-- Full Bleed Hero Section -->
    <?php echo do_shortcode('[featured_story_full_bleed]'); ?>
    
    <!-- Main Content Wrapper -->
    <main class="main-content">
        <section class="content-section about-section" id="about">
            <?php echo do_shortcode('[reuben_about]'); ?>
        </section>
        
        <h1 class="section-heading">Features</h1>
        <section class="content-section features-section" id="features">
            <?php echo do_shortcode('[reuben_features]'); ?>
        </section>
        
        <h1 class="section-heading">Reviews</h1>
        <section class="content-section reviews-section" id="reviews">
            <?php echo do_shortcode('[reuben_reviews]'); ?>
        </section>
        
        <h1 class="section-heading">Profiles</h1>
        <section class="content-section profiles-section" id="profiles">
            <?php echo do_shortcode('[reuben_profiles]'); ?>
        </section>
        
        <h1 class="section-heading">Interviews</h1>
        <section class="content-section interviews-section" id="interviews">
            <?php echo do_shortcode('[reuben_interviews]'); ?>
        </section>
        
        <h1 class="section-heading">Photographs</h1>
        <section class="content-section photographs-section" id="photographs">
            <?php echo do_shortcode('[reuben_photographs]'); ?>
        </section>
        
        <h1 class="section-heading">Strategy</h1>
        <section class="content-section strategy-section" id="strategy">
            <?php echo do_shortcode('[reuben_strategy]'); ?>
        </section>
        
        <h1 class="section-heading">CV</h1>
        <section class="content-section cv-section" id="cv">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
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
                const activeLink = document.querySelector(`.nav-link[href="#${bestMatch}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
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
                // Footer is overlapping hero section - use white logo/text and disable blur
                footer.classList.add('over-full-bleed');
                if (footerLogo) footerLogo.src = '/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
                if (copyright) copyright.style.color = 'white';
                if (socialLinks) socialLinks.style.display = 'none';
            } else {
                // Footer is not overlapping hero section - use black logo/gray text and enable blur
                footer.classList.remove('over-full-bleed');
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
