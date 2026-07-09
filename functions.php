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
    
    // Enqueue child theme style (no dependency on astra-theme-css so it loads
    // even on story templates that deregister the parent stylesheet)
    wp_enqueue_style(
        'astra-child-theme-css',
        get_stylesheet_directory_uri() . '/style.css',
        array(),
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
 * Set posts per page for story archives and exclude photo-* categories
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('story')) {
        $query->set('posts_per_page', 24);

        // Exclude photo-* categories from archive unless filtering by category
        $category_filter = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        if (empty($category_filter) && function_exists('get_photo_category_slugs')) {
            $photo_slugs = get_photo_category_slugs();
            if (!empty($photo_slugs)) {
                $query->set('tax_query', [
                    [
                        'taxonomy' => 'story_category',
                        'field' => 'slug',
                        'terms' => $photo_slugs,
                        'operator' => 'NOT IN'
                    ]
                ]);
            }
        }
    } elseif (!is_admin() && $query->is_main_query() && is_tax('story_category')) {
        $query->set('posts_per_page', 24);
    }
});

/**
 * Helper function to get photo category slugs to exclude from automated story selection
 * Returns all story_category terms that start with "photo-"
 *
 * @return array Array of category slugs to exclude
 */
function get_photo_category_slugs() {
    $photo_slugs = [];
    $terms = get_terms([
        'taxonomy' => 'story_category',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            if (strpos($term->slug, 'photo-') === 0) {
                $photo_slugs[] = $term->slug;
            }
        }
    }

    return $photo_slugs;
}

/**
 * Build WP_Query args for the /stories archive feed (used by both the initial
 * SSR render and the AJAX load-more handler, so they always stay in sync).
 *
 * @param string $category Story category slug to filter by, or '' for all (excludes photo-* categories).
 * @param int $posts_per_page
 * @param int $offset
 * @return array
 */
function get_stories_archive_query_args($category = '', $posts_per_page = 24, $offset = 0) {
    $args = array(
        'post_type' => 'story',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'story_category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    } else {
        // Exclude photo-* categories from "all stories" view
        $photo_slugs = get_photo_category_slugs();
        if (!empty($photo_slugs)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'story_category',
                    'field' => 'slug',
                    'terms' => $photo_slugs,
                    'operator' => 'NOT IN'
                )
            );
        }
    }

    return $args;
}

/**
 * Helper function to get stories for homepage sections
 *
 * @param string $category - Story category slug
 * @param int $limit - Number of posts to retrieve
 * @param bool $exclude_photo_categories - Whether to exclude photo-* categories (default true when no category specified)
 * @return WP_Query
 */
function get_portfolio_stories($category = '', $limit = 6, $exclude_photo_categories = null, $order = 'DESC') {
    $args = [
        'post_type' => 'story',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => $order
    ];

    // Default: exclude photo categories when no specific category is requested
    if ($exclude_photo_categories === null) {
        $exclude_photo_categories = empty($category);
    }

    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'story_category',
                'field' => 'slug',
                'terms' => $category
            ]
        ];
    } elseif ($exclude_photo_categories) {
        $photo_slugs = get_photo_category_slugs();
        if (!empty($photo_slugs)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'story_category',
                    'field' => 'slug',
                    'terms' => $photo_slugs,
                    'operator' => 'NOT IN'
                ]
            ];
        }
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
        return get_the_post_thumbnail_url($post_id, $size);
    }

    // Second priority: ACF Image Picker field (media library)
    $original_image_picker = get_field('original_image_picker', $post_id);
    if (!empty($original_image_picker)) {
        // Handle different ACF image field formats
        if (is_array($original_image_picker)) {
            return $original_image_picker['url'];
        } else {
            return $original_image_picker;
        }
    }

    // Third priority: ACF Original Image URL field
    $original_image_url = get_field('original_image_url', $post_id);
    if (!empty($original_image_url)) {
        // Handle relative URLs and domain slugs
        if (strpos($original_image_url, '/') === 0) {
            // Relative URL starting with / - prepend site URL
            return home_url($original_image_url);
        } elseif (!filter_var($original_image_url, FILTER_VALIDATE_URL)) {
            // Not a valid URL - treat as relative path and prepend site URL
            return home_url('/' . ltrim($original_image_url, '/'));
        } else {
            // Valid full URL - return as is
            return $original_image_url;
        }
    }

    // Fallback: return empty string instead of broken path
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
        'medium' => get_field('medium', $post_id),
        'publish_date' => get_field('publish_date', $post_id),
        'external_url' => get_field('external_url', $post_id),
        'photo_credit' => get_field('photo_credit', $post_id),
        'original_image_url' => get_field('original_image_url', $post_id),
        'short_headline' => get_field('short_headline', $post_id),
        'photo_gallery' => get_field('photo_gallery', $post_id),
        'homepage_thumbnail' => get_field('homepage_thumbnail', $post_id)
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
 * Add meta box for story hero color picker
 */
function add_story_hero_color_meta_box() {
    add_meta_box(
        'story_hero_color',
        'Hero Background Color',
        'render_story_hero_color_meta_box',
        'story',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_story_hero_color_meta_box');

/**
 * Render the hero color meta box
 */
function render_story_hero_color_meta_box($post) {
    wp_nonce_field('story_hero_color_nonce', 'story_hero_color_nonce');
    $value = get_post_meta($post->ID, 'story_hero_color', true);
    $value = !empty($value) ? $value : '#39e58f';
    ?>
    <p>
        <label for="story_hero_color">Choose a background color for the split hero template:</label>
        <input type="color" id="story_hero_color" name="story_hero_color" value="<?php echo esc_attr($value); ?>" style="width: 100%; height: 40px;">
    </p>
    <p class="description">This color will be used as the background for the left side of the split hero layout. Default: #39e58f (green)</p>
    <?php
}

/**
 * Save the hero color meta box value
 */
function save_story_hero_color_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['story_hero_color_nonce']) || !wp_verify_nonce($_POST['story_hero_color_nonce'], 'story_hero_color_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the color
    if (isset($_POST['story_hero_color'])) {
        update_post_meta($post_id, 'story_hero_color', sanitize_hex_color($_POST['story_hero_color']));
    }
}
add_action('save_post_story', 'save_story_hero_color_meta_box');

/**
 * Add meta box for flagging the lead (large, left) features story
 */
function add_story_lead_feature_meta_box() {
    add_meta_box(
        'story_lead_feature',
        'Features Section',
        'render_story_lead_feature_meta_box',
        'story',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_story_lead_feature_meta_box');

/**
 * Render the lead-feature checkbox meta box
 */
function render_story_lead_feature_meta_box($post) {
    wp_nonce_field('story_lead_feature_nonce', 'story_lead_feature_nonce');
    $value = get_post_meta($post->ID, 'story_lead_feature', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="story_lead_feature" value="1" <?php checked($value, '1'); ?>>
            Show as the lead (large, left) feature story
        </label>
    </p>
    <p class="description">The story must also be in the "features" category. Only check one story; if several are checked, the most recent one wins.</p>
    <?php
}

/**
 * Save the lead-feature checkbox value
 */
function save_story_lead_feature_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['story_lead_feature_nonce']) || !wp_verify_nonce($_POST['story_lead_feature_nonce'], 'story_lead_feature_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save (or clear) the flag
    if (!empty($_POST['story_lead_feature'])) {
        update_post_meta($post_id, 'story_lead_feature', '1');
    } else {
        delete_post_meta($post_id, 'story_lead_feature');
    }
}
add_action('save_post_story', 'save_story_lead_feature_meta_box');

/**
 * AJAX handler for lazy loading more stories.
 *
 * Uses an explicit offset/limit sent by the client rather than a "page number",
 * so the batch requested always lines up with how many stories are actually
 * on screen — previously this assumed the first batch was always exactly 24
 * stories, and any mismatch (e.g. the initial render coming up short) meant
 * everything after that guessed cutoff got skipped forever.
 */
function ajax_load_more_stories() {
    $offset = isset($_POST['offset']) ? max(0, intval($_POST['offset'])) : 0;
    $limit = isset($_POST['limit']) ? max(1, intval($_POST['limit'])) : 12;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    $args = get_stories_archive_query_args($category, $limit, $offset);

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $metadata = function_exists('get_story_metadata') ? get_story_metadata(get_the_ID()) : [];
            $story_url = !empty($metadata['external_url']) ? esc_url($metadata['external_url']) : get_permalink();
            $is_external = !empty($metadata['external_url']);
            ?>
            <article class="story-item">
                <a href="<?php echo $story_url; ?>" class="story-link"<?php echo $is_external ? ' target="_blank" rel="noopener"' : ''; ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="story-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="story-content">
                        <h2><?php the_title(); ?></h2>

                        <?php if (has_excerpt()) : ?>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($metadata['medium']) || !empty($metadata['publication']) || !empty($metadata['publish_date'])) : ?>
                            <p class="story-meta">
                                <?php if (!empty($metadata['medium'])) : ?>
                                    <?php echo esc_html($metadata['medium']); ?>
                                <?php endif; ?>
                                <?php if (!empty($metadata['publication'])) : ?>
                                    <?php echo !empty($metadata['medium']) ? ' for ' : 'For '; ?><i><?php echo esc_html($metadata['publication']); ?></i>
                                <?php endif; ?>
                                <?php if (!empty($metadata['publish_date'])) : ?>
                                    <?php echo !empty($metadata['publication']) ? ' in ' : ''; ?>
                                    <?php echo date('F Y', strtotime($metadata['publish_date'])); ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            </article>
            <?php
        }
        $html = ob_get_clean();
    }

    wp_reset_postdata();

    // Calculate if there are more posts
    $has_more = $query->found_posts > ($offset + $limit);

    wp_send_json_success(array(
        'html' => $html,
        'count' => $query->post_count,
        'has_more' => $has_more
    ));
}
add_action('wp_ajax_load_more_stories', 'ajax_load_more_stories');
add_action('wp_ajax_nopriv_load_more_stories', 'ajax_load_more_stories');

/**
 * Custom functions for portfolio site will be added here
 */