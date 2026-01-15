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

// Set posts per page to 24 initially
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('story') || is_tax('story_category'))) {
        $query->set('posts_per_page', 24);
    }
});

get_header('branded'); ?>

<style>
    /* Typography and base styles loaded from plugin base-sections.css */

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
        <div class="stories-grid" id="stories-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
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
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-stories-message">No stories found.</p>
            <?php endif; ?>
        </div>

        <?php if (have_posts()) : ?>
            <div id="load-more-trigger" style="height: 1px; margin: 100px 0;"></div>
            <div id="loading-indicator" style="display: none; text-align: center; padding: 2rem; color: var(--text-color-muted); font-family: var(--primary-font);">
                Loading more stories...
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
(function() {
    let currentPage = 1;
    let isLoading = false;
    // Check if we have more than 24 posts total (initial load is 24)
    let hasMorePosts = <?php echo $GLOBALS['wp_query']->found_posts > 24 ? 'true' : 'false'; ?>;

    const grid = document.getElementById('stories-grid');
    const trigger = document.getElementById('load-more-trigger');
    const loadingIndicator = document.getElementById('loading-indicator');

    if (!grid || !trigger || !hasMorePosts) return;

    // Intersection Observer for lazy loading
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMorePosts) {
                loadMorePosts();
            }
        });
    }, {
        rootMargin: '200px' // Start loading 200px before trigger is visible
    });

    observer.observe(trigger);

    function loadMorePosts() {
        isLoading = true;
        loadingIndicator.style.display = 'block';
        currentPage++;

        const currentTax = '<?php echo is_tax('story_category') ? get_queried_object()->slug : ''; ?>';

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'load_more_stories',
                page: currentPage,
                category: currentTax
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                grid.insertAdjacentHTML('beforeend', data.data.html);

                if (!data.data.has_more) {
                    hasMorePosts = false;
                    observer.disconnect();
                    trigger.style.display = 'none';
                }
            } else {
                hasMorePosts = false;
                observer.disconnect();
                trigger.style.display = 'none';
            }

            loadingIndicator.style.display = 'none';
            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading more posts:', error);
            loadingIndicator.style.display = 'none';
            isLoading = false;
        });
    }
})();
</script>

<?php get_footer('branded'); ?>
