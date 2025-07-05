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
    
    // Manually enqueue our plugin CSS here to ensure it loads
    wp_enqueue_style(
        'reuben-portfolio-sections',
        plugin_dir_url(__FILE__) . '../plugins/reuben-portfolio-sections/assets/portfolio-sections.css',
        [],
        '1.0.1'
    );
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
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 2vw;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        gap: 1vw;
        align-items: center;
        max-width: 100%;
        padding: 0 0 1vw 0;
    }

    .site-title {
        font-family: var(--primary-font) !important;
        font-size: min(3vw, 54px);
        line-height: min(3.6vw, 64px);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .site-title .subtitle {
        font-size: 20px;
        font-weight: 400;
        color: #808080;
        transition: opacity 0.3s ease;
        line-height: 1.2;
    }

    /* Hide subtitle on mobile when not in splash area */
    @media (max-width: 768px) {
        .site-header.visible .site-title .subtitle {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }
    }

    .site-title {
        color: #000;
        text-shadow: none;
    }

    .site-title .subtitle {
        color: #808080;
        text-shadow: none;
    }

    .main-nav ul {
        display: flex;
        list-style: none;
        gap: 0;
        align-items: center;
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
        transition: all 0.3s ease;
        padding: 0.5rem 0;
        position: relative;
    }

    .main-nav a {
        color: #000;
        text-shadow: none;
    }

    .main-nav a:hover,
    .main-nav a.active {
        color: #39e58f;
        font-weight: 600;
    }

    .main-nav a.active::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #39e58f;
        border-radius: 1px;
    }

    /* Footer Styles */
    .site-footer {
        position: sticky;
        bottom: 0;
        background: white;
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
        transition: color 0.3s ease;
    }

    .social-links a:not(:last-child)::after {
        content: " / ";
        color: #000;
    }

    .social-links a:hover {
        color: #39e58f;
    }

    .social-links a:hover::after {
        color: #000;
    }

    /* Main Content Wrapper */
    .main-content {
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: white;
        margin-top: 0;
        padding-top: 8rem;
        position: relative;
        z-index: 10;
    }
    
    .content-section {
        background: white;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-content {
            gap: 0.8rem;
            padding: 1rem 0;
        }

        .site-title {
            font-size: 2rem;
            line-height: 2.4rem;
        }

        .site-title .subtitle {
            font-size: 18px;
        }

        .main-nav ul {
            flex-wrap: wrap;
        }

        .main-nav a {
            font-size: 18px;
        }

        .main-nav li:not(:last-child)::after {
            margin: 0 0.5rem;
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
            <a href="#" class="site-title">
                Reuben J. Brown
                <span class="subtitle">Multimedia Journalist</span>
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#stories" class="nav-link">Stories</a></li>
                    <li><a href="#strategy" class="nav-link">Strategy</a></li>
                    <li><a href="#cv" class="nav-link">CV</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <!-- Main Content Wrapper -->
    <main class="main-content">
        <?php echo do_shortcode('[reuben_about]'); ?>
        <?php echo do_shortcode('[reuben_stories]'); ?>
        <?php echo do_shortcode('[reuben_strategy]'); ?>
        <?php echo do_shortcode('[reuben_cv]'); ?>
    </main>

    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="https://skyblue-mongoose-220265.hostingersite.com/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png" alt="RJB Logo" id="footer-logo-img">
            </div>
            <div class="social-links">
                <a href="#" class="caption">email</a>
                <a href="#" class="caption">instagram</a>
                <a href="#" class="caption">linkedin</a>
            </div>
            <div class="copyright caption">© Reuben J. Brown 2025</div>
        </div>
    </footer>

    <script>
        // Set footer logo to black since no splash area
        const footerLogo = document.getElementById('footer-logo-img');
        if (footerLogo) {
            footerLogo.src = 'https://skyblue-mongoose-220265.hostingersite.com/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
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
    </script>

    <?php wp_footer(); ?>
</body>
</html>
