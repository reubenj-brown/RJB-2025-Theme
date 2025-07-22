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

get_header('branded'); ?>

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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .section-heading {
            font-size: 2rem;
            line-height: 2.4rem;
        }

        .main-content {
            padding: 0 4vw;
        }
    }
</style>

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
    </main>

<?php get_footer('branded'); ?>