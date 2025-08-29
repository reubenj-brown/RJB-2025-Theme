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
                font-size: 32px;
                line-height: 32px;
                font-weight: 600;
                color: #808080;
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
                background: linear-gradient(to bottom, white 0%, white 40%, #39e58f 100%);
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
                color: #000;
                margin-bottom: 1.5rem;
                transition: all 0.3s ease;
            }

            .contact-link:last-child {
                margin-bottom: 0;
            }

            .contact-link:hover {
                color: var(--highlight-color);
            }

            /* Features Section */
            .features-section {
                display: flex;
                align-items: flex-start;
                position: relative;
            }

            .features-section::before {
                content: '';
                position: absolute;
                left: 50%;
                top: 0;
                bottom: 0;
                width: 1px;
                background-color: #e0e0e0;
                z-index: 10;
            }

            .features-left {
                width: 50%;
                padding-right: 32px;
            }

            .features-story-main {
                display: flex;
                align-items: flex-start;
                gap: 24px;
                width: 100%; /* Use full available width of parent */
            }

            .features-story-main .story-content {
                flex: 0 0 calc(40% - 9.6px); /* 40% minus 40% of gap */
                text-align: center;
            }

            /* Ensure headlines are black */
            .features-story-main h2 a,
            .features-story-small h2 a {
                color: #000 !important;
                text-decoration: none;
            }

            /* Features section hover effects - highlight color with no transition */
            .features-story-main h2 a:hover,
            .features-story-small h2 a:hover {
                color: var(--highlight-color) !important;
                transition: none;
            }

            /* Primary story spacing removed - now handled below with font styling */

            .features-story-main p:not(.story-meta) {
                color: #808080;
                margin-bottom: 2rem;
                line-height: 1.3;
                font-family: var(--serif-font);
                font-size: 24.5px;
            }

            /* Secondary story headlines - back to original */
            .features-story-small h2 {
                font-size: 29.5px;
                margin-bottom: 12px;
            }

            /* Primary story headline - PP Right Serif at 96px */
            .features-story-main h2 {
                margin-bottom: 1.5rem;
                line-height: 1.1;
                font-family: var(--compressed-font);
                font-size: 96px;
                font-weight: 400;
                font-style: normal;
                text-transform: uppercase;
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

            .features-story-main .story-image {
                flex: 0 0 calc(60% - 14.4px); /* 60% minus 60% of gap */
                aspect-ratio: 4/3;
                overflow: hidden;
                position: relative;
                margin-bottom: 8px;
            }

            .features-story-main .story-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
            }

            /* Features section caption styling */
            .features-story-main .caption {
                font-family: var(--primary-font);
                font-size: 12px;
                font-weight: 400;
                line-height: 1.4;
                color: #808080;
                margin: 8px 0 0 0;
                text-align: right;
                background: white;
                padding: 2px 0;
                width: 100%;
            }

            .features-right {
                width: 50%;
                padding-left: 32px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                gap: 16px;
            }

            .features-story-small {
                display: flex;
                align-items: flex-start;
                gap: 32px;
            }

            .features-story-small .story-content {
                flex: 1;
            }

            .features-story-small .story-image {
                width: 120px;
                height: 120px;
                max-height: 120px;
                aspect-ratio: 1/1;
                overflow: hidden;
                flex-shrink: 0;
                position: relative;
                margin-bottom: 8px;
            }

            .features-story-small .story-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
            }

            /* Tablet responsive */
            @media (max-width: 1200px) {
                .features-section {
                    flex-direction: column;
                }

                .features-section::before {
                    display: none;
                }

                .features-left,
                .features-right {
                    width: 100%;
                    padding: 0;
                }

                .features-left {
                    margin-bottom: 32px;
                }

                /* Keep text left of image on tablet */
                .features-story-main {
                    gap: 24px;
                }

                /* Primary story text stays center-aligned on tablet */
                .features-story-main .story-content {
                    text-align: center;
                }

                /* Features section body text 16px on tablet */
                .features-story-main p:not(.story-meta) {
                    font-family: var(--serif-font);
                    font-size: calc(16px * 1.23); /* 16px * 1.23 = 19.68px - scaled for serif font */
                }
            }

            /* Mobile responsive */
            @media (max-width: 768px) {

                .features-story-main {
                    flex-direction: column;
                }

                /* Primary story text stays center-aligned on mobile */
                .features-story-main .story-content {
                    width: 100%;
                    margin-bottom: 16px;
                    text-align: center;
                }

                .features-story-main .story-image {
                    width: 100%;
                }

                /* Hero section headline on mobile */
                .featured-story-full-bleed h2,
                .featured-story-full-bleed h2 i {
                    font-size: 64px !important;
                }

                /* Features section headline sizes on mobile */
                .features-story-main h2 {
                    font-size: 64px; /* Primary story headline - compressed font */
                }
                
                .features-story-small h2 {
                    font-size: 24px; /* Secondary story headlines */
                }

                /* Features section body text 16px on mobile */
                .features-story-main p:not(.story-meta) {
                    font-family: var(--serif-font);
                    font-size: calc(16px * 1.23); /* 16px * 1.23 = 19.68px - scaled for serif font */
                }

                /* Keep secondary stories horizontal on mobile with square images */
                .features-story-small {
                    flex-direction: row;
                    gap: 24px;
                }

                .features-story-small .story-content {
                    flex: 1;
                }

                .features-story-small .story-image {
                    width: 120px;
                    height: 120px;
                    aspect-ratio: 1/1;
                    max-height: 120px;
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
        
        <h1 class="section-heading" id="stories">Features</h1>
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
                    <!-- Left half - Main featured story -->
                    <div class="features-left">
                        <div class="features-story-main">
                            <div class="story-content">
                                <h2 class="serif-font-scaled">
                                    <a href="<?php echo !empty($first_story['metadata']['external_url']) ? esc_url($first_story['metadata']['external_url']) : $first_story['permalink']; ?>"<?php echo !empty($first_story['metadata']['external_url']) ? ' target="_blank" rel="noopener"' : ''; ?>>
                                        <?php echo !empty($first_story['metadata']['short_headline']) ? $first_story['metadata']['short_headline'] : $first_story['title']; ?>
                                    </a>
                                </h2>
                                <?php if (!empty($first_story['excerpt'])) : ?>
                                    <p><?php echo $first_story['excerpt']; ?></p>
                                <?php endif; ?>
                                <p class="story-meta">
                                    <?php if (!empty($first_story['metadata']['publication'])) : ?>
                                        For <i><?php echo $first_story['metadata']['publication']; ?></i>
                                    <?php endif; ?>
                                    <?php if (!empty($first_story['metadata']['publish_date'])) : ?>
                                        <?php echo !empty($first_story['metadata']['publication']) ? ' in ' : ''; ?>
                                        <?php echo date('F Y', strtotime($first_story['metadata']['publish_date'])); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="story-image">
                                <img src="<?php echo $first_story['image_url']; ?>" alt="<?php echo $first_story['title']; ?>">
                                <?php if (!empty($first_story['metadata']['photo_credit'])) : ?>
                                    <div class="caption">photograph: <?php echo $first_story['metadata']['photo_credit']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right half - Secondary stories -->
                    <div class="features-right">
                        <?php foreach ($remaining_stories as $story) : ?>
                            <div class="features-story-small">
                                <div class="story-content">
                                    <h2 class="serif-font-scaled">
                                        <a href="<?php echo !empty($story['metadata']['external_url']) ? esc_url($story['metadata']['external_url']) : $story['permalink']; ?>"<?php echo !empty($story['metadata']['external_url']) ? ' target="_blank" rel="noopener"' : ''; ?>>
                                            <?php echo $story['title']; ?>
                                        </a>
                                    </h2>
                                    <p class="story-meta">
                                        <?php if (!empty($story['metadata']['publication'])) : ?>
                                            For <i><?php echo $story['metadata']['publication']; ?></i>
                                        <?php endif; ?>
                                        <?php if (!empty($story['metadata']['publish_date'])) : ?>
                                            <?php echo !empty($story['metadata']['publication']) ? ' in ' : ''; ?>
                                            <?php echo date('F Y', strtotime($story['metadata']['publish_date'])); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="story-image">
                                    <img src="<?php echo $story['image_url']; ?>" alt="<?php echo $story['title']; ?>">
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
            <?php echo do_shortcode('[reuben_reviews]'); ?>
        </section>
        
        <h1 class="section-heading">Architecture</h1>
        <section class="content-section profiles-section" id="profiles">
            <?php echo do_shortcode('[reuben_profiles]'); ?>
        </section>
        
        <h1 class="section-heading">Interviews</h1>
        <section class="content-section interviews-section" id="interviews">
            <?php echo do_shortcode('[reuben_dynamic_stories category="interviews" layout="grid" limit="11" show_view_all="true" show_excerpt="false"]'); ?>
        </section>
        
        <h1 class="section-heading">Photographs</h1>
        <section class="content-section photographs-section" id="photographs">
            <?php echo do_shortcode('[reuben_photographs]'); ?>
        </section>
        
        <h1 class="section-heading" id="cv">CV</h1>
        <section class="content-section cv-section">
            <?php echo do_shortcode('[reuben_cv]'); ?>
        </section>
    </main>

    <!-- Contact Section - Outside main wrapper for full width gradient -->
    <h1 class="section-heading" id="contact">Contact</h1>
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
