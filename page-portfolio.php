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
                max-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden; /* Ensure section doesn't exceed 100vh */
                background: transparent !important; /* Override white background from .content-section */
            }

            /* Contact section gradient - mirrors About section but reversed */
            .contact-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0; /* Full viewport width since section is outside main wrapper */
                right: 0; /* Full viewport width since section is outside main wrapper */
                bottom: -5vw; /* Extend to fully cover footer area and eliminate white gap */
                background: linear-gradient(to bottom, white 0%, white 40%, #39e58f 100%);
                z-index: -1;
            }

            /* Ensure Contact heading has proper styling outside main wrapper */
            .contact-section ~ .section-heading,
            body .section-heading + .contact-section {
                position: relative;
                z-index: 1;
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

            /* Features Section - Custom Layout based on mockup */
            .features-section {
                padding: 4vw 2vw !important;
                margin: 0 !important;
                display: flex;
                flex-direction: column;
                gap: 4rem;
                position: relative;
            }

            /* Main story - full width at top */
            .features-story-main {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                max-width: 800px;
                margin: 0 auto;
            }

            .features-story-main .story-content {
                width: 100%;
                margin-bottom: 2rem;
            }

            .features-story-main .story-title {
                font-family: var(--serif-font) !important;
                font-size: 3rem;
                line-height: 1.2;
                font-weight: 400;
                font-style: italic;
                margin-bottom: 1.5rem;
                color: #000;
            }

            .features-story-main .story-standfirst {
                font-size: 1.2rem;
                line-height: 1.6;
                color: #808080;
                margin-bottom: 1.5rem;
                max-width: 600px;
            }

            .features-story-main .story-meta {
                font-size: 16px;
                color: #808080;
                font-weight: 400;
            }

            .features-story-main .story-image-wrapper {
                width: 100%;
                position: relative;
            }

            .features-story-main .story-image {
                width: 100%;
                height: 60vh;
                object-fit: cover;
                border-radius: 8px;
            }

            .features-story-main .image-credit {
                position: absolute;
                bottom: -1.5rem;
                right: 0;
                font-size: 12px;
                color: #808080;
                font-style: italic;
            }

            /* Secondary stories grid */
            .features-secondary {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 3rem;
                margin-top: 2rem;
            }

            .features-story-small {
                display: flex;
                flex-direction: column;
                text-align: left;
            }

            .features-story-small .story-image-wrapper {
                width: 100%;
                height: 200px;
                margin-bottom: 1rem;
            }

            .features-story-small .story-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
            }

            .features-story-small .story-title {
                font-family: var(--serif-font) !important;
                font-size: 1.4rem;
                line-height: 1.3;
                font-weight: 400;
                font-style: italic;
                margin-bottom: 0.75rem;
                color: #000;
            }

            .features-story-small .story-meta {
                font-size: 16px;
                color: #808080;
                font-weight: 400;
            }

            /* Tablet responsive */
            @media (max-width: 1024px) {
                .features-secondary {
                    grid-template-columns: 1fr 1fr;
                    gap: 2rem;
                }

                .features-story-main .story-title {
                    font-size: 2.5rem;
                }

                .features-story-main .story-image {
                    height: 50vh;
                }
            }

            /* Mobile responsive */
            @media (max-width: 768px) {
                .features-section {
                    padding: 2vw 4vw !important;
                    gap: 3rem;
                }

                .features-secondary {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                }

                .features-story-main .story-title {
                    font-size: 2rem;
                }

                .features-story-main .story-image {
                    height: 40vh;
                }

                .features-story-small .story-image-wrapper {
                    height: 150px;
                }

                .features-story-small .story-title {
                    font-size: 1.2rem;
                }
            }

            /* General Mobile Responsive */
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

                /* Contact section gradient mobile - extend to screen edges */
                .contact-section::before {
                    left: -4vw;
                    right: -4vw;
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
            <?php
            // Get features stories
            $features_query = get_portfolio_stories('features', 6);
            
            if ($features_query->have_posts()) {
                $story_count = 0;
                $stories = [];
                
                // Collect stories into array
                while ($features_query->have_posts()) {
                    $features_query->the_post();
                    $stories[] = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'excerpt' => get_the_excerpt(),
                        'image_url' => get_story_featured_image(get_the_ID(), 'large'),
                        'metadata' => get_story_metadata(get_the_ID()),
                        'permalink' => get_permalink()
                    ];
                }
                wp_reset_postdata();
                
                if (!empty($stories)) {
                    $first_story = $stories[0];
                    $remaining_stories = array_slice($stories, 1);
            ?>
                    <!-- Main featured story -->
                    <div class="features-story-main">
                        <div class="story-content">
                            <h2 class="story-title">
                                <a href="<?php echo $first_story['permalink']; ?>" style="text-decoration: none; color: inherit;">
                                    <?php echo $first_story['title']; ?>
                                </a>
                            </h2>
                            <?php if (!empty($first_story['excerpt'])) : ?>
                                <p class="story-standfirst"><?php echo $first_story['excerpt']; ?></p>
                            <?php endif; ?>
                            <div class="story-meta">
                                <?php if (!empty($first_story['metadata']['publication'])) : ?>
                                    For <em><?php echo $first_story['metadata']['publication']; ?></em>
                                <?php endif; ?>
                                <?php if (!empty($first_story['metadata']['publish_date'])) : ?>
                                    <?php if (!empty($first_story['metadata']['publication'])) echo ' '; ?>
                                    in <?php echo date('F Y', strtotime($first_story['metadata']['publish_date'])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="story-image-wrapper">
                            <img src="<?php echo $first_story['image_url']; ?>" alt="<?php echo $first_story['title']; ?>" class="story-image">
                            <?php if (!empty($first_story['metadata']['photo_credit'])) : ?>
                                <div class="image-credit">photograph: <?php echo $first_story['metadata']['photo_credit']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Secondary stories grid -->
                    <div class="features-secondary">
                        <?php foreach ($remaining_stories as $story) : ?>
                            <div class="features-story-small">
                                <div class="story-image-wrapper">
                                    <img src="<?php echo $story['image_url']; ?>" alt="<?php echo $story['title']; ?>" class="story-image">
                                </div>
                                <div class="story-content">
                                    <h3 class="story-title">
                                        <a href="<?php echo $story['permalink']; ?>" style="text-decoration: none; color: inherit;">
                                            <?php echo $story['title']; ?>
                                        </a>
                                    </h3>
                                    <div class="story-meta">
                                        <?php if (!empty($story['metadata']['publication'])) : ?>
                                            For <em><?php echo $story['metadata']['publication']; ?></em>
                                        <?php endif; ?>
                                        <?php if (!empty($story['metadata']['publish_date'])) : ?>
                                            <?php if (!empty($story['metadata']['publication'])) echo ' '; ?>
                                            in <?php echo date('F Y', strtotime($story['metadata']['publish_date'])); ?> →
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
            <?php
                }
            } else {
                echo '<p style="text-align: center; color: #808080; padding: 4rem;">No features stories found.</p>';
            }
            ?>
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
    </main>

    <!-- Contact Section - Outside main wrapper for full width gradient -->
    <h1 class="section-heading">Contact</h1>
    <section class="content-section contact-section" id="contact">
        <div class="contact-links">
            <a href="mailto:reubenjbrown@protonmail.com" class="contact-link">email</a>
            <a href="https://www.instagram.com/reubenj.brown/" class="contact-link" target="_blank" rel="noopener">instagram</a>
            <a href="https://www.linkedin.com/in/reuben-j-brown/" class="contact-link" target="_blank" rel="noopener">linkedin</a>
        </div>
    </section>

<?php get_footer('branded'); ?>