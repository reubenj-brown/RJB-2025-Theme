<?php
/*
Template Name: Portfolio Page
*/

// Debug: Template is loading
error_log('Portfolio template is loading');
echo '<!-- PORTFOLIO TEMPLATE LOADED -->';

// Disable WordPress admin bar for this page
show_admin_bar(false);

// Remove theme's CSS and JS but keep essential WordPress functions
add_action('wp_enqueue_scripts', function() {
    // Remove theme styles but keep essential WordPress ones
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
    
    // Remove the manual CSS enqueue - the plugin handles this
}, 100);

// Add portfolio-specific styles to header
add_action('wp_head', function() {
    // Always load CSS when this template is used
    ?>
        <style>
            /* Main Content Wrapper */
            .main-content {
                padding: 0 2vw;
                width: 100vw;
                max-width: 100vw;
                background: var(--main-content-bg);
                margin-top: 0;
                position: relative;
                z-index: 10;
            }
            
            /* Content sections background handled by base-sections.css */

            /* CSS Variables for Dark Mode */
            :root {
                --section-heading-color: #808080;
                --main-content-bg: white;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --section-heading-color: #808080; /* Keep headings same gray */
                    --main-content-bg: #050505;
                }
            }

            /* Main Content Wrapper Dark Mode */
            .main-content {
                background: var(--main-content-bg);
            }

            /* Section Headings */
            .section-heading {
                font-family: var(--primary-font) !important;
                font-size: 32px;
                line-height: 32px;
                font-weight: 600;
                color: var(--section-heading-color);
                text-align: center;
                margin: 0;
                padding: 96px 0 64px 0;
            }

            /* Contact Section */
            .contact-section {
                min-height: 75vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                background: transparent !important; /* Override white background from .content-section */
                margin-bottom: 0;
                padding-bottom: 0;
            }

            /* Contact section gradient - mirrors About section but reversed */
            .contact-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0; /* Full viewport width since section is outside main wrapper */
                right: 0; /* Full viewport width since section is outside main wrapper */
                bottom: calc(-2vw - 40px); /* Extend by footer height: 1vw top + 40px logo + 1vw bottom */
                background: linear-gradient(to bottom, var(--main-content-bg) 0%, var(--main-content-bg) 40%, #39e58f 100%);
                z-index: -1;
            }

            /* Ensure Contact heading has proper styling outside main wrapper */
            .contact-section ~ .section-heading,
            body .section-heading + .contact-section {
                position: relative;
                z-index: 1;
            }

            /* Contact Section - Two Column Layout */
            .contact-content {
                display: flex;
                align-items: center;
                gap: 2rem;
                width: 100%;
                max-width: 100vw;
                margin: 0;
                padding: 0 2vw;
                box-sizing: border-box;
            }

            .contact-image {
                flex: 0 0 calc(50% - 1rem);
                display: flex;
                justify-content: flex-end;
                align-items: center;
                box-sizing: border-box;
                padding: 45px; /* Space for shadow - tripled from 15px */
            }

            .contact-image img {
                width: 400px;
                height: auto;
                display: block;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
                transform: rotate(-10deg);
            }

            .contact-links {
                flex: 0 0 calc(50% - 1rem);
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
                box-sizing: border-box;
            }

            .contact-link {
                font-family: var(--serif-font) !important; /* Same as About section first paragraph */
                font-size: 3.8vw; /* Same as About section first paragraph */
                line-height: 1.2 !important; /* Same as About section first paragraph */
                font-weight: 400 !important;
                text-decoration: none;
                color: var(--text-color);
                margin-bottom: 1.5rem;
                transition: all 0.3s ease;
            }

            .contact-link:last-child {
                margin-bottom: 0;
            }

            .contact-link:hover {
                color: var(--highlight-color);
            }


            /* Hero section headline - PP Right Serif at 96px, block caps, not italic */
            .featured-story-full-bleed h2,
            .featured-story-full-bleed h2 i {
                font-family: var(--compressed-font) !important;
                font-size: 96px !important;
                font-weight: 400 !important;
                font-style: normal !important;
                text-transform: uppercase !important;
            }


            /* Mobile responsive */
            @media (max-width: 768px) {
                /* Hero section headline on mobile */
                .featured-story-full-bleed h2,
                .featured-story-full-bleed h2 i {
                    font-size: 64px !important;
                }
            }

            /* Tablet Responsive */
            @media (max-width: 1200px) {
                .contact-content {
                    flex-direction: column;
                    text-align: center;
                    gap: 2rem;
                    width: 100%;
                    padding: 0 3rem;
                }

                .contact-image {
                    flex: none;
                    margin: 0 auto;
                    padding-left: 0;
                }

                .contact-image img {
                    width: 350px;
                    max-width: 90vw;
                }

                .contact-links {
                    flex: none;
                    padding-right: 0;
                }

                .contact-link {
                    font-size: 60px !important; /* Match About section tablet size */
                }
            }

            /* General Mobile Responsive */
            @media (max-width: 768px) {
                .section-heading {
                    font-size: 24px;
                    line-height: 28px;
                    padding: 48px 0 24px 0;
                }

                .main-content {
                    padding: 0 4vw;
                }

                .contact-section {
                    min-height: 75vh;
                }

                /* Contact section gradient mobile - constrain to viewport */
                .contact-section::before {
                    left: 0;
                    right: 0;
                    bottom: calc(-2vw - 35px); /* Mobile footer height: 1vw top + logo + contact pill + 1vw bottom */
                }

                /* Contact section - stack columns on mobile */
                .contact-content {
                    flex-direction: column;
                    text-align: center;
                    gap: 1.5rem;
                    width: 100%;
                    padding: 0 4vw;
                }

                .contact-image img {
                    width: 300px;
                    max-width: 85vw;
                }

                .contact-links {
                    gap: 1.5rem;
                }

                .contact-link {
                    font-size: 55px !important; /* Match About section mobile size */
                    font-weight: 400;
                }
            }

            /* Small Mobile Responsive */
            @media (max-width: 480px) {
                .contact-link {
                    font-size: 45px !important; /* Match About section small mobile size */
                }
            }
        </style>
        <?php
});

// Add homepage hero fade effect
add_action('wp_footer', function() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const heroContent = document.querySelector('.full-bleed-content');
        const heroSection = document.querySelector('.featured-story-full-bleed');
        
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
    </script>
    <?php
});

get_header('branded'); ?>

    <!-- Full Bleed Hero Section -->
    <?php echo do_shortcode('[featured_story_full_bleed]'); ?>
    
    <!-- Main Content Wrapper -->
    <main class="main-content">
        <section class="content-section about-section" id="about">
            <?php echo do_shortcode('[reuben_about]'); ?>
        </section>
        
        <?php echo do_shortcode('[reuben_features]'); ?>

        <h1 class="section-heading">Cronkite</h1>
        <section class="content-section cronkite-section" id="cronkite">
            <?php echo do_shortcode('[reuben_dynamic_stories category="cronkite" layout="grid" limit="11" show_view_all="true" show_excerpt="false"]'); ?>
        </section>

        <section class="content-section reviews-section" id="reviews">
            <?php echo do_shortcode('[reuben_reviews]'); ?>
        </section>

        <h1 class="section-heading">Interviews</h1>
        <section class="content-section interviews-section" id="interviews">
            <?php echo do_shortcode('[reuben_dynamic_stories category="interviews" layout="grid" limit="11" show_view_all="true" show_excerpt="false"]'); ?>
        </section>

        <section class="content-section photographs-section" id="photographs">
            <?php echo do_shortcode('[reuben_photographs]'); ?>
        </section>

            <div class="strategy-intro">
                <h3 class="serif-font-scaled" id="contact">Here are some resume highlights. Or download a full <a href="https://reubenjbrown.com/wp-content/uploads/2025/08/Reuben-J-Brown_Investigative-Journalist_CV_September-2025.pdf">PDF version</a>.</h3>
            </div> 
        <section class="content-section cv-section">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
        <div class="strategy-intro">
                <h3 class="serif-font-scaled">My first gig – at two months old – was a starring role in the for “You Were Right” by Badly Drawn Boy. Watch that <a href="https://youtu.be/K1BNOzDnOLI?t=202">here</a>, or contact me for actually serious things below:</h3>
        </div> 
    </main>

    <!-- Contact Section - Outside main wrapper for full width gradient -->
    <section class="content-section contact-section">
        <div class="contact-content">
            <div class="contact-image">
                <img src="<?php echo esc_url(home_url('/wp-content/uploads/2025/08/Reuben-J.-Brown-Multimedia-Journalist-Headshot.jpg')); ?>" alt="Headshot ofReuben J. Brown against blue background, hard flash, polaroid" />
            </div>
            <div class="contact-links">
                <a href="mailto:reubenjbrown@protonmail.com" class="contact-link">email</a>
                <a href="https://www.instagram.com/reubenj.brown/" class="contact-link" target="_blank" rel="noopener">instagram</a>
                <a href="https://www.linkedin.com/in/reuben-j-brown/" class="contact-link" target="_blank" rel="noopener">linkedin</a>
            </div>
        </div>
    </section>

<?php get_footer('branded'); ?>
