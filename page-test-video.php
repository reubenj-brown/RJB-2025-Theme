<?php
/*
Template Name: Test Video Projects
*/

// Disable WordPress admin bar
show_admin_bar(false);

// Remove Astra theme CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

// Minimal inline styles for proper layout
add_action('wp_head', function() {
    ?>
    <style>
        .main-content {
            padding: 0 2vw;
            width: 100vw;
            max-width: 100vw;
            background: var(--main-content-bg);
            margin-top: 0;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
            .main-content {
                padding: 0 4vw;
            }
        }
    </style>
    <?php
});

get_header('branded'); ?>

    <main class="main-content">
        <?php echo do_shortcode('[reuben_video_projects]'); ?>
    </main>

<?php get_footer('branded'); ?>
