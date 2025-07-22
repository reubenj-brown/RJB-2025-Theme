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

// Add portfolio-specific styles to header
add_action('wp_head', function() {
    if (is_page_template('page-portfolio.php')) {
        ?>
        <style>
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

            /* Contact Section */
            .contact-section {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .contact-links {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4rem;
            }

            .contact-link {
                font-family: var(--primary-font) !important;
                font-size: 6rem;
                line-height: 1.2;
                font-weight: 600; /* Semi-bold */
                text-decoration: none;
                color: #000;
                transition: all 0.3s ease;
            }

            .contact-link:hover {
                color: var(--highlight-color);
                transform: translateY(-5px);
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .section-heading {
                    font-size: 2rem;
                    line-height: 2.4rem;
                }

                .main-content {
                    padding: 0 4vw;
                }

                .contact-section {
                    height: 100vh;
                }

                .contact-links {
                    gap: 3rem;
                }

                .contact-link {
                    font-size: 3rem;
                    font-weight: 600;
                }
            }
        </style>
        <?php
    }
});

get_header('branded'); ?>

    <!-- Full Bleed Hero Section -->
    <?php echo do_shortcode('[featured_story_full_bleed]'); ?>
    
    <!-- Main Content Wrapper -->
    <main class="main-content">
        <section class="content-section about-section" id="about">
            <?php echo do_shortcode('[reuben_about]'); ?>
        </section>
        
        <h1 class="section-heading">Features</h1>
        <section class="content-section features-section" id="features">
            <?php echo do_shortcode('[reuben_dynamic_stories category="features" layout="grid" limit="6"]'); ?>
        </section>
        
        <h1 class="section-heading">Reviews</h1>
        <section class="content-section reviews-section" id="reviews">
            <?php echo do_shortcode('[reuben_dynamic_stories category="reviews" layout="grid" limit="6"]'); ?>
        </section>
        
        <h1 class="section-heading">Profiles</h1>
        <section class="content-section profiles-section" id="profiles">
            <?php echo do_shortcode('[reuben_dynamic_stories category="profiles" layout="grid" limit="6"]'); ?>
        </section>
        
        <h1 class="section-heading">Interviews</h1>
        <section class="content-section interviews-section" id="interviews">
            <?php echo do_shortcode('[reuben_dynamic_stories category="interviews" layout="grid" limit="6"]'); ?>
        </section>
        
        <h1 class="section-heading">Photographs</h1>
        <section class="content-section photographs-section" id="photographs">
            <?php echo do_shortcode('[reuben_dynamic_stories category="photographs" layout="grid" limit="6"]'); ?>
        </section>
        
        <h1 class="section-heading">Strategy</h1>
        <section class="content-section strategy-section" id="strategy">
            <?php echo do_shortcode('[reuben_strategy]'); ?>
        </section>
        
        <h1 class="section-heading">CV</h1>
        <section class="content-section cv-section" id="cv">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
        
        <h1 class="section-heading">Contact</h1>
        <section class="content-section contact-section" id="contact">
            <div class="contact-links">
                <a href="mailto:reubenjbrown@protonmail.com" class="contact-link">email</a>
                <a href="https://www.instagram.com/reubenj.brown/" class="contact-link" target="_blank" rel="noopener">instagram</a>
                <a href="https://www.linkedin.com/in/reuben-j-brown/" class="contact-link" target="_blank" rel="noopener">linkedin</a>
            </div>
        </section>
    </main>

<?php get_footer('branded'); ?>