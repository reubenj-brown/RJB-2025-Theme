s
<?php
/*
Template Name: Portfolio Page
*/

// Disable WordPress admin bar for this page
show_admin_bar(false);

// Remove default WordPress styles and scripts that might interfere
remove_action('wp_head', 'wp_print_styles', 8);
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_footer', 'wp_print_footer_scripts');

// Remove theme's CSS and JS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('theme-style');
    wp_deregister_style('theme-style');
}, 100);

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

        <?php 
    // Keep essential WordPress head elements
    wp_head(); 
    ?>
    
<link rel="stylesheet" href="https://use.typekit.net/ffl7rra.css">
<link rel="stylesheet" href="/wp-content/plugins/reuben-portfolio-sections/assets/portfolio-sections.css">

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

        /* Fix WordPress body classes interference */
        body.page-template-page-portfolio .splash-section {
            position: relative !important;
            width: 100vw !important;
            height: 100vh !important;
            overflow: hidden !important;
        }

        body {
            font-family: var(--primary-font) !important;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Splash Section */
        .splash-section {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* Image Carousel Background */
        .carousel-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .carousel-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        .carousel-image.active {
            opacity: 1;
        }

            .carousel-image:nth-child(1) { background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/06/Reuben-j-brown-multimedia-journalist-homepage-images-draft5.webp')); ?>'); }
            .carousel-image:nth-child(2) { background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/06/Reuben-j-brown-multimedia-journalist-homepage-images-draft2.webp')); ?>'); }
            .carousel-image:nth-child(3) { background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/06/Reuben-j-brown-multimedia-journalist-homepage-images-draft9.webp')); ?>'); }
            .carousel-image:nth-child(4) { background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/06/Reuben-j-brown-multimedia-journalist-homepage-images-draft13.webp')); ?>'); }
            .carousel-image:nth-child(5) { background-image: url('<?php echo esc_url(home_url('/wp-content/uploads/2025/06/Reuben-j-brown-multimedia-journalist-homepage-images-draft22.webp')); ?>'); }
      
        /* Header Styles */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: transparent;
            transform: translateY(-100%);
            transition: all 0.3s ease;
            padding: 0 3vw 0 3vw;
        }

        .site-header.visible {
            transform: translateY(0);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.5);
        }

        .site-header.splash-overlay {
            transform: translateY(0);
            background: transparent;
            border-bottom: none;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            max-width: 100%;
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
            font-weight: 400;
            color: #808080;
            transition: opacity 0.3s ease;
        }

        /* Hide subtitle on mobile when not in splash area */
        @media (max-width: 768px) {
            .site-header.visible .site-title .subtitle {
                opacity: 0;
                height: 0;
                overflow: hidden;
            }
        }

        /* White text for splash overlay */
        .site-header.splash-overlay .site-title {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .site-header.splash-overlay .site-title .subtitle {
            color: #808080;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Normal dark text for scrolled header */
        .site-header.visible .site-title {
            color: #000;
            text-shadow: none;
        }

        .site-header.visible .site-title .subtitle {
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
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
            position: relative;
        }

        /* White text for splash overlay */
        .site-header.splash-overlay .main-nav a {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .site-header.splash-overlay .main-nav a:hover,
        .site-header.splash-overlay .main-nav a.active {
            color: #39e58f;
            font-weight: 600;
        }

        /* Normal dark text for scrolled header */
        .site-header.visible .main-nav a {
            color: #000;
            text-shadow: none;
        }

        .site-header.visible .main-nav a:hover,
        .site-header.visible .main-nav a.active {
            color: #39e58f;
            font-weight: 600;
        }

        /* Footer Styles */
        .site-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: transparent;
            padding: 0 3vw 0 3vw;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
        }

        .social-links a {
            text-decoration: none;
            font-weight: 400;
            transition: color 0.3s ease;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .social-links a:hover {
            color: #39e58f;
        }

        /* Footer styling - always 50% grey in splash area */
        .site-footer .social-links a {
            color: #808080;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .site-footer .social-links a:hover {
            color: #39e58f;
        }

        /* Footer styling when not in splash area */
        .site-footer.dark-mode .social-links a {
            color: #000;
            text-shadow: none;
        }

        .site-footer.dark-mode .social-links a:hover {
            color: #39e58f;
        }

        .copyright {
            color: #808080;
            font-size: 0.9rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Copyright when not in splash area */
        .site-footer.dark-mode .copyright {
            color: #000;
            text-shadow: none;
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

            .main-nav ul {
                flex-wrap: wrap;
            }

            .main-nav li:not(:last-child)::after {
                margin: 0 0.5rem;
            }

            .footer-content {
                padding: 1rem 0;
            }

            .social-links {
                gap: 1rem;
                position: static;
                transform: none;
            }

            .footer-logo {
                width: 35px;
                height: 35px;
            }

            .copyright {
                display: none; /* Always hidden on mobile */
            }
        }

        /* Placeholder content for testing scroll */
        .content-section {
            height: 100vh;
            padding: 2rem 3%;
            background: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header splash-overlay" id="site-header">
        <div class="header-content">
            <a href="#" class="site-title">
                Reuben J. Brown
                <span class="subtitle">Multimedia Journalist</span>
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#writing" class="nav-link">Writing</a></li>
                    <li><a href="#photography" class="nav-link">Photography</a></li>
                    <li><a href="#strategy" class="nav-link">Strategy</a></li>
                    <li><a href="#cv" class="nav-link">CV</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Splash Section -->
    <section class="splash-section" id="splash">
        <div class="carousel-container">
            <div class="carousel-image active"></div>
            <div class="carousel-image"></div>
            <div class="carousel-image"></div>
            <div class="carousel-image"></div>
            <div class="carousel-image"></div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="https://skyblue-mongoose-220265.hostingersite.com/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png" alt="RJB Logo" id="footer-logo-img">
            </div>
            <div class="social-links">
                <a href="#">email</a>
                <a href="#">instagram</a>
                <a href="#">linkedin</a>
            </div>
            <div class="copyright">© Reuben J. Brown 2025</div>
        </div>
    </footer>
    
    <?php echo do_shortcode('[reuben_about]'); ?>
    
    <!-- Temporary placeholder sections for other areas -->
    <section class="content-section" id="writing">
        <h2>Writing Section</h2>
        <p>This is placeholder content to test the scroll behavior...</p>
    </section>

    <section class="content-section" id="photography">
        <h2>Photography Section</h2>
        <p>This is placeholder content to test the scroll behavior...</p>
    </section>

    <section class="content-section" id="strategy">
        <h2>Strategy Section</h2>
        <p>This is placeholder content to test the scroll behavior...</p>
    </section>

    <section class="content-section" id="cv">
        <h2>CV Section</h2>
        <p>This is placeholder content to test the scroll behavior...</p>
    </section>

    <script>
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-image');
        const totalSlides = slides.length;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % totalSlides;
            slides[currentSlide].classList.add('active');
        }

        // Change slide every 4 seconds
        setInterval(nextSlide, 4000);

        // Header scroll behavior - simplified to just show/hide splash overlay
        const header = document.getElementById('site-header');
        const footer = document.getElementById('site-footer');
        const footerLogo = document.getElementById('footer-logo-img');

        function updateHeader() {
            const scrollY = window.scrollY;
            
            if (scrollY <= 100) {
                // In splash area - show transparent overlay with white text
                header.classList.add('splash-overlay');
                header.classList.remove('visible');
                footer.classList.remove('dark-mode');
                footerLogo.src = 'https://skyblue-mongoose-220265.hostingersite.com/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png';
            } else {
                // Past splash area - show solid header with dark text
                header.classList.remove('splash-overlay');
                header.classList.add('visible');
                footer.classList.add('dark-mode');
                footerLogo.src = 'https://skyblue-mongoose-220265.hostingersite.com/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-black.png';
            }
        }

        function requestTick() {
            requestAnimationFrame(updateHeader);
        }

        window.addEventListener('scroll', requestTick);

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
</body>
</html>
