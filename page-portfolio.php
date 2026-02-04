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
            /* CSS Variables loaded from style.css */

            /* Main Content Wrapper */
            .main-content {
                background: var(--main-content-bg);
            }

            /* Section Headings */
            .section-heading {
                font-family: var(--primary-font) !important;
                font-size: 2rem;
                line-height: 32px;
                font-weight: 600;
                color: var(--section-heading-color);
                text-align: center;
                margin: 0;
                padding: 96px 0 64px 0;
            }

            /* Contact Section */
            .contact-section {
                min-height: calc(100vh - 100px - 4vw);
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

            /* Contact Section - Three Column Layout */
            .contact-content {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                max-width: 100vw;
                margin: 0;
                padding: 0 2vw;
                box-sizing: border-box;
            }

            .contact-image {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
            }

            .contact-image img {
                width: 400px;
                height: auto;
                display: block;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
                transform: rotate(-10deg);
            }

            .contact-links-wrapper {
                flex: 2;
                display: flex;
            }

            .contact-links {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 1rem;
                box-sizing: border-box;
            }

            .contact-link {
                /* Font properties from .display-headline in base-sections.css */
                text-decoration: none;
                transition: all 0.3s ease;
                color: #000 !important;
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
                font-size: 6rem !important;
                font-weight: 400 !important;
                font-style: normal !important;
                text-transform: uppercase !important;
                margin-bottom: .5rem !important;
            }


            /* Mobile responsive - See breakpoint reference in plugin base-sections.css */
            @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
                /* Hero section headline on mobile */
                .featured-story-full-bleed h2,
                .featured-story-full-bleed h2 i {
                    font-size: 4rem !important;
                }
            }

            /* Tablet Responsive */
            @media (max-width: 1200px) {
                .contact-content {
                    flex-direction: column;
                    text-align: left;
                    width: 100%;
                    padding: 2rem 3rem;
                }

                .contact-image {
                    flex: none;
                    margin: 0 auto;
                    padding: 0;
                }

                .contact-image img {
                    width: 350px;
                    max-width: 90vw;
                }

                .contact-links-wrapper {
                    flex: none;
                    width: 100%;
                    justify-content: space-around;
                    gap: 3rem;
                }

                .contact-links {
                    flex: none;
                }

            }

            /* General Mobile Responsive - See breakpoint reference in plugin base-sections.css */
            @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)), (max-width: 480px) {
                .section-heading {
                    font-size: 1.5rem;
                    line-height: 28px;
                    padding: 48px 0 24px 0;
                }

                .main-content {
                    padding: 0 4vw;
                }

                .contact-section {
                    min-height: calc(100vh - 94px - 4vw - env(safe-area-inset-top));
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
                    text-align: left;
                    column-gap: 92px;
                    width: 100%;
                    padding: 0 4vw;
                }

                .contact-image {
                    margin: 0;
                }

                .contact-image img {
                    width: 60vw;
                    max-width: 85vw;
                }

                .contact-links-wrapper {
                    gap: 3rem;
                }

                .contact-links {
                    gap: 0.25rem;
                }

                .contact-link {
                    font-weight: 400;
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

        // About section large intro text fade effect
        const aboutText = document.querySelector('.large-intro-text');

        if (aboutText) {
            function updateAboutTextOpacity() {
                const scrollY = window.scrollY;
                const aboutTextRect = aboutText.getBoundingClientRect();
                const aboutTextTop = scrollY + aboutTextRect.top;
                const aboutTextHeight = aboutTextRect.height;
                const viewportHeight = window.innerHeight;

                // Start fading when text reaches 18% from top of viewport, fully faded at 0% from top
                const fadeStart = aboutTextTop - (viewportHeight * 0.18);
                const fadeEnd = aboutTextTop;

                if (scrollY <= fadeStart) {
                    aboutText.style.opacity = '1';
                    aboutText.style.filter = 'blur(0px)';
                } else if (scrollY >= fadeEnd) {
                    aboutText.style.opacity = '0';
                    aboutText.style.filter = 'blur(10px)';
                } else {
                    // Calculate opacity and blur between fadeStart and fadeEnd
                    const fadeProgress = (scrollY - fadeStart) / (fadeEnd - fadeStart);
                    const opacity = 1 - fadeProgress;
                    const blurAmount = fadeProgress * 10; // 0px to 10px blur
                    aboutText.style.opacity = opacity.toString();
                    aboutText.style.filter = `blur(${blurAmount}px)`;
                }
            }

            // Initial call and scroll listener for about text
            updateAboutTextOpacity();
            window.addEventListener('scroll', updateAboutTextOpacity);
        }

        // Strategy intro text fade effect
        const strategyChildren = document.querySelectorAll('.strategy-intro-headline, .strategy-intro-body');

        if (strategyChildren.length > 0) {
            function updateStrategyTextOpacity() {
                const scrollY = window.scrollY;
                const viewportHeight = window.innerHeight;

                strategyChildren.forEach(el => {
                    const elRect = el.getBoundingClientRect();
                    const elTop = scrollY + elRect.top;

                    // Start fading when element reaches 20% from top of viewport, fully faded when it would leave viewport
                    const fadeStart = elTop - (viewportHeight * 0.20);
                    const fadeEnd = elTop;

                    if (scrollY <= fadeStart) {
                        el.style.opacity = '1';
                        el.style.filter = 'blur(0px)';
                    } else if (scrollY >= fadeEnd) {
                        el.style.opacity = '0';
                        el.style.filter = 'blur(10px)';
                    } else {
                        // Calculate opacity and blur between fadeStart and fadeEnd
                        const fadeProgress = (scrollY - fadeStart) / (fadeEnd - fadeStart);
                        const opacity = 1 - fadeProgress;
                        const blurAmount = fadeProgress * 10; // 0px to 10px blur
                        el.style.opacity = opacity.toString();
                        el.style.filter = `blur(${blurAmount}px)`;
                    }
                });
            }

            // Initial call and scroll listener for strategy intro sections
            updateStrategyTextOpacity();
            window.addEventListener('scroll', updateStrategyTextOpacity);
        }
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

        <?php echo do_shortcode('[reuben_cronkite]'); ?>

        <section class="content-section photographs-section" id="photographs">
            <?php echo do_shortcode('[reuben_photographs]'); ?>
        </section>

        <section class="content-section video-section" id="video">
            <?php echo do_shortcode('[reuben_video_projects]'); ?>
        </section>
        
        <section class="content-section reviews-section" id="reviews">
            <?php echo do_shortcode('[reuben_reviews]'); ?>
        </section>

        <section class="content-section architecture-section" id="architecture">
            <?php echo do_shortcode('[reuben_profiles]'); ?>
        </section>

            <div class="strategy-intro">
                <div class="strategy-intro-headline">
                    <span class="display-headline">CV</span>
                </div>
                <div class="strategy-intro-body">
                    <h3 id="cv">Here are some resume highlights. Or download a full <a href="https://reubenjbrown.com/wp-content/uploads/2025/11/Reuben_J_Brown_Investigative_Journalist_CV_November-2025.pdf">PDF version</a></h3>
                </div>
            </div>
        <section class="content-section cv-section">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
        <div class="strategy-intro" style="padding-bottom: 0 !important;">
            <div class="strategy-intro-headline">
                <span class="display-headline">Contact</span>
            </div>
            <div class="strategy-intro-body">
                <h3 id="contact">My first gig – at two months old – was a starring role in the music video for <a href="https://youtu.be/K1BNOzDnOLI?t=202">“You Were Right”</a> by Badly Drawn Boy. For actually serious things, contact me below:</h3>
            </div>
        </div>
    </main>

    <!-- Contact Section - Outside main wrapper for full width gradient -->
    <section class="content-section contact-section">
        <div class="contact-content">
            <div class="contact-image">
                <img src="<?php echo esc_url(home_url('/wp-content/uploads/2026/01/reuben-j-brown-journalist-writer-photographer-editor-2026-headshot-1.avif')); ?>" alt="Headshot of journalist Reuben J. Brown in green soft, filtered light. He wears a black suede jacket." />
            </div>
            <div class="contact-links-wrapper">
                <div class="contact-links">
                    <a href="mailto:reubenjbrown@protonmail.com" class="contact-link display-headline">email</a>
                    <a href="https://www.instagram.com/reubenj.brown/" class="contact-link display-headline" target="_blank" rel="noopener">instagram</a>
                </div>
                <div class="contact-links">
                    <a href="https://www.linkedin.com/in/reuben-j-brown/" class="contact-link display-headline" target="_blank" rel="noopener">linkedin</a>
                    <a href="https://signal.me/#eu/88vN3zt9qpBApa_mQdOvsnnIEJHj3HXbYNegw65lGjsrEYaS1wdGhq7o9cF3os1X" class="contact-link display-headline" target="_blank" rel="noopener">signal</a>
                </div>
            </div>
        </div>
    </section>

<?php get_footer('branded'); ?>
