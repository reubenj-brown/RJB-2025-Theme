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
    
    // Enqueue story templates CSS for story post types
    if (is_singular('story') || is_post_type_archive('story') || is_tax('story_category')) {
        wp_enqueue_style(
            'story-templates-css',
            get_stylesheet_directory_uri() . '/story-templates.css',
            array('astra-child-theme-css'),
            wp_get_theme()->get('Version')
        );
    }
    
    // Custom JavaScript will be added as needed
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Helper function to get stories for homepage sections
 * 
 * @param string $category - Story category slug
 * @param int $limit - Number of posts to retrieve
 * @return WP_Query
 */
function get_portfolio_stories($category = '', $limit = 6) {
    $args = [
        'post_type' => 'story',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'story_category',
                'field' => 'slug',
                'terms' => $category
            ]
        ];
    }
    
    return new WP_Query($args);
}

/**
 * Helper function to get featured image URL
 * 
 * @param int $post_id
 * @param string $size
 * @return string
 */
function get_story_featured_image($post_id, $size = 'medium') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    
    // Return a placeholder if no featured image
    return get_stylesheet_directory_uri() . '/images/story-placeholder.jpg';
}

/**
 * Helper function to get story metadata
 * 
 * @param int $post_id
 * @return array
 */
function get_story_metadata($post_id) {
    return [
        'publication' => get_field('publication', $post_id),
        'publish_date' => get_field('publish_date', $post_id),
        'external_url' => get_field('external_url', $post_id),
        'photo_credit' => get_field('photo_credit', $post_id)
    ];
}

/**
 * Custom functions for portfolio site will be added here
 */