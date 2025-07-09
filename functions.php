<?php
/**
 * Astra Child Theme functions and definitions
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function astra_child_enqueue_styles() {
    // Enqueue parent theme style
    wp_enqueue_style( 
        'astra-theme-css', 
        get_template_directory_uri() . '/style.css', 
        array(), 
        wp_get_theme()->get('Version')
    );
    
    // Enqueue child theme style
    wp_enqueue_style( 
        'astra-child-theme-css', 
        get_stylesheet_directory_uri() . '/style.css', 
        array('astra-theme-css'), 
        wp_get_theme()->get('Version')
    );
    
    // Custom JavaScript will be added as needed
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Custom functions for portfolio site will be added here
 */