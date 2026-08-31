<?php
/*
Template Name: Photography Draft 2026
*/

show_admin_bar(false);

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');

    wp_enqueue_style(
        'photography-draft-2026',
        get_stylesheet_directory_uri() . '/2026-photography-draft/photography-draft.css',
        array('astra-child-theme-css'),
        '1.0.0'
    );
}, 100);

// Story templates CSS styles the "← Home / Photography" breadcrumb nav
// injected into the header by photography-draft.php's script block.
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/story-templates/story-templates.css?v=' . wp_get_theme()->get('Version') . '">' . "\n";
}, 999);

get_header('branded');

// Load the draft content from the 2026-photography-draft folder
include get_stylesheet_directory() . '/2026-photography-draft/photography-draft.php';

get_footer('branded');
