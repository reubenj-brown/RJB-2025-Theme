<?php
/**
 * Template Name: Story Archive
 * Description: Archive page for all stories
 */

// Disable WordPress admin bar for archive pages
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

get_header('branded'); ?>



<?php get_footer('branded'); ?>
