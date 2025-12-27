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

<style>
    /* Main Content Wrapper - Same as homepage */
    .main-content {
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: var(--main-content-bg);
        margin-top: 0;
        position: relative;
        z-index: 10;
    }

    /* Category Filter Buttons */
    .category-filter {
        padding: 96px 0 64px 0;
        text-align: center;
    }

    .category-filter-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Active state for filter buttons */
    .footer-contact-pill.active {
        background: var(--highlight-color);
        color: white;
    }

    /* Mobile Responsive - See breakpoint reference in plugin base-sections.css */
    @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
        .main-content {
            padding: 0 4vw;
        }

        .category-filter {
            padding: 48px 0 24px 0;
        }
    }
</style>

<main class="main-content">
    <div class="category-filter">
        <div class="category-filter-buttons">
            <!-- "All" button - active on main archive -->
            <a href="<?php echo get_post_type_archive_link('story'); ?>"
               class="footer-contact-pill <?php echo is_post_type_archive('story') && !is_tax() ? 'active' : ''; ?>">
                all
            </a>

            <?php
            // Get all story categories
            $categories = get_terms([
                'taxonomy' => 'story_category',
                'hide_empty' => true,
            ]);

            if (!empty($categories) && !is_wp_error($categories)) :
                foreach ($categories as $category) :
                    $is_active = is_tax('story_category', $category->slug);
            ?>
                    <a href="<?php echo get_term_link($category); ?>"
                       class="footer-contact-pill <?php echo $is_active ? 'active' : ''; ?>">
                        <?php echo esc_html(strtolower($category->name)); ?>
                    </a>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</main>

<?php get_footer('branded'); ?>
