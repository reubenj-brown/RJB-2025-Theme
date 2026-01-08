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
    /* CSS Variables for Dark Mode - Match Portfolio Page */
    :root {
        --section-heading-color: #808080;
        --main-content-bg: white;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --section-heading-color: #808080; /* Keep headings same gray */
            --main-content-bg: #050505;
        }
    }

    /* Main Content Wrapper - Positioned below header */
    .main-content {
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: var(--main-content-bg);
        margin-top: calc(60px + 2vw + env(safe-area-inset-top));
        position: relative;
        z-index: 10;
    }

    /* Section Headings - Match Portfolio Page */
    .section-heading {
        font-family: var(--primary-font) !important;
        font-size: 32px;
        line-height: 32px;
        font-weight: 600;
        color: var(--section-heading-color);
        text-align: center;
        margin: 0;
        padding: 96px 0 64px 0;
    }

    /* Category Filter Buttons */
    .category-filter {
        padding: 3rem 0;
        text-align: center;
    }

    .category-filter-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 10px; /* Space for scrollbar */
        margin-bottom: -10px; /* Compensate for padding */
    }

    /* Optional: Hide scrollbar for cleaner look */
    .category-filter-buttons::-webkit-scrollbar {
        height: 6px;
    }

    .category-filter-buttons::-webkit-scrollbar-track {
        background: transparent;
    }

    .category-filter-buttons::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }

    .category-filter-buttons::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    /* Active state for filter buttons */
    .footer-contact-pill.active {
        background: var(--highlight-color);
        color: white;
    }

    /* Tablet Responsive */
    @media (max-width: 1200px) {
        .main-content {
            margin-top: calc(80px + env(safe-area-inset-top));
        }
    }

    /* Mobile Responsive - See breakpoint reference in plugin base-sections.css */
    @media (max-width: 768px), ((max-width: 1200px) and (max-height: 768px)) {
        .main-content {
            padding: 0 4vw;
            margin-top: calc(70px + env(safe-area-inset-top));
        }

        .category-filter {
            padding: 2rem 0;
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

    <!-- Stories Grid -->
    <div class="stories-content">
        <div class="stories-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="story-item">
                        <a href="<?php the_permalink(); ?>" class="story-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="story-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                                <?php
                                $photo_credit = get_post_meta(get_the_ID(), 'photo_credit', true);
                                if ($photo_credit) :
                                ?>
                                    <div class="caption"><?php echo esc_html($photo_credit); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="story-content">
                                <h2><?php the_title(); ?></h2>

                                <?php if (has_excerpt()) : ?>
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <?php endif; ?>

                                <?php
                                $metadata = function_exists('get_story_metadata') ? get_story_metadata(get_the_ID()) : [];
                                if (!empty($metadata['medium']) || !empty($metadata['publication']) || !empty($metadata['publish_date'])) :
                                ?>
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
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-stories-message">No stories found.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer('branded'); ?>
