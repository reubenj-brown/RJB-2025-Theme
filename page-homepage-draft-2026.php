<?php
/*
Template Name: Homepage Draft 2026
*/

show_admin_bar(false);

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');

    wp_enqueue_style(
        'homepage-draft-2026',
        get_stylesheet_directory_uri() . '/2026-homepage-draft/homepage-draft.css',
        array('astra-child-theme-css'),
        '1.0.0'
    );
}, 100);

get_header('branded');

// Load the draft content from the 2026-homepage-draft folder
include get_stylesheet_directory() . '/2026-homepage-draft/homepage-draft.php';

get_footer('branded');
