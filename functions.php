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
    // First priority: WordPress featured image
    if (has_post_thumbnail($post_id)) {
        $wp_image_url = get_the_post_thumbnail_url($post_id, $size);
        error_log("WordPress featured image for post $post_id: '$wp_image_url'");
        return $wp_image_url;
    }

    // Second priority: ACF Image Picker field (media library)
    $original_image_picker = get_field('original_image_picker', $post_id);
    if (!empty($original_image_picker)) {
        // Handle different ACF image field formats
        if (is_array($original_image_picker)) {
            $image_url = $original_image_picker['url'];
            error_log("ACF Image Picker (array) for post $post_id: '$image_url'");
            return $image_url;
        } else {
            error_log("ACF Image Picker (string) for post $post_id: '$original_image_picker'");
            return $original_image_picker;
        }
    }

    // Third priority: ACF Original Image URL field
    $original_image_url = get_field('original_image_url', $post_id);
    if (!empty($original_image_url)) {
        // Handle relative URLs and domain slugs
        if (strpos($original_image_url, '/') === 0) {
            // Relative URL starting with / - prepend site URL
            $final_url = home_url($original_image_url);
            error_log("ACF Image URL conversion for post $post_id: '$original_image_url' -> '$final_url'");
            return $final_url;
        } elseif (!filter_var($original_image_url, FILTER_VALIDATE_URL)) {
            // Not a valid URL - treat as relative path and prepend site URL
            $final_url = home_url('/' . ltrim($original_image_url, '/'));
            error_log("ACF Image URL conversion (relative) for post $post_id: '$original_image_url' -> '$final_url'");
            return $final_url;
        } else {
            // Valid full URL - return as is
            error_log("ACF Image URL (full URL) for post $post_id: '$original_image_url'");
            return $original_image_url;
        }
    }

    // Fallback: return empty string instead of broken path
    error_log("No image found for post $post_id, returning empty string");
    return '';
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
        'photo_credit' => get_field('photo_credit', $post_id),
        'original_image_url' => get_field('original_image_url', $post_id),
        'short_headline' => get_field('short_headline', $post_id),
        'photo_gallery' => get_field('photo_gallery', $post_id),
        'homepage_thumbnail' => get_post_meta($post_id, 'homepage_thumbnail', true)
    ];
}

/**
 * Add theme-color meta tag to all pages
 */
function add_theme_color_meta() {
    echo '<meta name="theme-color" content="#39e58f">' . "\n";
}
add_action('wp_head', 'add_theme_color_meta');

/**
 * Custom functions for portfolio site will be added here
 */