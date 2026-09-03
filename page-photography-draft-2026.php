<?php
/*
Template Name: Photography Draft 2026
*/

show_admin_bar(false);

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');

    $dir = get_stylesheet_directory() . '/2026-photography-draft/';
    $uri = get_stylesheet_directory_uri() . '/2026-photography-draft/';

    // filemtime() rather than a hand-bumped version string — this page is
    // under active iteration and a stale number means a cache clear per edit.
    wp_enqueue_style(
        'photography-draft-2026',
        $uri . 'photography-draft.css',
        array('astra-child-theme-css'),
        file_exists($dir . 'photography-draft.css') ? filemtime($dir . 'photography-draft.css') : null
    );

    // The watercolor reveal controller. Deferred: the pre-paint card
    // randomizer is inline in the gallery template, so nothing here needs
    // to run before first paint.
    wp_enqueue_script(
        'watercolor-reveal',
        $uri . 'watercolor-reveal.js',
        array(),
        file_exists($dir . 'watercolor-reveal.js') ? filemtime($dir . 'watercolor-reveal.js') : null,
        true
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
