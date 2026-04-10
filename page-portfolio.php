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
                min-height: auto;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                background: transparent !important; /* Override white background from .content-section */
                margin-bottom: 0;
                padding: 24px 0 calc(2vw + 40px + 80px) 0; /* Top 24px, bottom = footer height + 80px */
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

            /* Contact Section - Two Column Layout (Photo | Info) */
            .contact-content {
                display: grid;
                grid-template-columns: 1fr 2fr;
                align-items: center;
                gap: 64px;
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 2vw;
                box-sizing: border-box;
            }

            .contact-image {
                justify-self: start;
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                box-sizing: border-box;
            }

            .contact-image img {
                max-width: 100%;
                height: auto;
                display: block;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
            }

            .contact-info {
                display: flex;
                flex-direction: column;
                gap: 2rem;
            }

            .contact-info h3 {
                margin: 0;
                word-wrap: break-word;
                overflow-wrap: break-word;
                max-width: 100%;
            }

            .contact-links-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .contact-column {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .contact-item {
                display: flex;
                flex-direction: column;
                gap: 0;
            }

            .contact-item p {
                font-family: var(--primary-font) !important;
                font-weight: 400 !important;
                font-size: 1rem !important;
                margin: 0 !important;
            }

            p.contact-headline {
                font-weight: 600 !important;
            }

            .contact-item a {
                color: var(--text-color) !important;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .contact-item a:hover {
                color: var(--link-hover-color) !important;
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
                    grid-template-columns: 1fr 2fr;
                    gap: 48px;
                    padding: 0 4vw;
                }

                .contact-image img {
                    max-width: 100%;
                }

                .contact-info h3 {
                    font-size: 1.5rem;
                }

                .contact-links-grid {
                    gap: 1.5rem;
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
                    min-height: auto;
                    padding: 24px 0 calc(2vw + 35px + 80px) 0; /* Mobile footer height + 80px */
                }

                /* Contact section gradient mobile - constrain to viewport */
                .contact-section::before {
                    left: 0;
                    right: 0;
                    bottom: calc(-2vw - 35px); /* Mobile footer height: 1vw top + logo + contact pill + 1vw bottom */
                }

                /* Contact section - stack as rows on mobile */
                .contact-content {
                    grid-template-columns: 1fr;
                    text-align: center;
                    gap: 32px;
                    width: 100%;
                    max-width: 100%;
                    padding: 0 4vw;
                    box-sizing: border-box;
                    overflow: hidden;
                }

                .contact-info {
                    display: contents; /* Allow children to participate in parent grid */
                }

                .contact-info h3 {
                    text-align: center;
                    font-size: 1.25rem;
                    order: -1; /* Move above image */
                    width: 100%;
                    box-sizing: border-box;
                    padding: 0 4vw;
                }

                .contact-image {
                    order: 0;
                    margin: 0;
                    justify-self: center;
                    display: flex;
                    justify-content: center;
                }

                .contact-image img {
                    width: auto;
                    max-width: 60vw;
                    max-height: 40vh;
                    height: auto;
                    object-fit: contain;
                }

                .contact-links-grid {
                    order: 1;
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                }

                .contact-column {
                    gap: 1rem;
                }

                .contact-item p {
                    font-size: 14px !important;
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
                    <h3 id="cv">Here are some resume highlights. Or download a full <a href="https://reubenjbrown.com/wp-content/uploads/2026/04/Reuben-J-Brown-CV_April26.pdf">PDF version</a></h3>
                </div>
            </div>
        <section class="content-section cv-section">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
        <div class="strategy-intro" style="padding-bottom: 0 !important;">
            <div class="strategy-intro-headline">
                <span class="display-headline" id="contact">Contact</span>
            </div>
            <div class="strategy-intro-body" style="display: none;">
                <h3>My first gig – at two months old – was a starring role in the music video for <a href="https://youtu.be/K1BNOzDnOLI?t=202">“You Were Right”</a> by Badly Drawn Boy. For actually serious things, contact me below:</h3>
            </div>
        </div>
    </main>

    <!-- Contact Section - Outside main wrapper for full width gradient -->
    <section class="content-section contact-section">
        <div class="contact-content">
            <div class="contact-image">
                <img src="<?php echo esc_url(home_url('/wp-content/uploads/2026/03/Reuben_J_Brown_Multimedia-Journalist-photographer-writer-editor-HEADSHOT.avif')); ?>" alt="Headshot of journalist Reuben J. Brown in green soft, filtered light. He wears a black suede jacket." />
            </div>
            <div class="contact-info">
                <h3>My first gig – at two months old – was a starring role in the music video for <a href="https://youtu.be/K1BNOzDnOLI?t=202">“You Were Right”</a> by Badly Drawn Boy. For actually serious things, contact me here:</h3>
                <div class="contact-links-grid">
                    <!-- Left column -->
                    <div class="contact-column">
                        <div class="contact-item">
                            <p class="contact-headline">Email</p>
                            <p><a href="mailto:reubenjbrown@protonmail.com">reubenjbrown[at]protonmail.com</a></p>
                        </div>
                        <div class="contact-item">
                            <p class="contact-headline">Signal</p>
                            <p><a href="https://signal.me/#eu/88vN3zt9qpBApa_mQdOvsnnIEJHj3HXbYNegw65lGjsrEYaS1wdGhq7o9cF3os1X" target="_blank" rel="noopener">@reubenjbrown.01</a></p>
                        </div>
                    </div>
                    <!-- Right column -->
                    <div class="contact-column">
                        <div class="contact-item">
                            <p class="contact-headline">Instagram</p>
                            <p><a href="https://www.instagram.com/reubenj.brown/" target="_blank" rel="noopener">@reubenj.brown</a></p>
                        </div>
                        <div class="contact-item">
                            <p class="contact-headline">LinkedIn</p>
                            <p><a href="https://www.linkedin.com/in/reuben-j-brown/" target="_blank" rel="noopener">/in/reuben-j-brown</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer('branded'); ?>
