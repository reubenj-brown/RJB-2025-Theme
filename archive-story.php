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

// Add story templates CSS for header styling
add_action('wp_head', function() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/story-templates/story-templates.css?v=' . wp_get_theme()->get('Version') . '">' . "\n";
}, 999);

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
        padding: 28px 0 3rem 0;
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

    /* Button reset for filter pills */
    .category-filter-buttons button.footer-contact-pill {
        border: 1px solid var(--highlight-color);
        cursor: pointer;
        font-family: inherit;
        font-size: inherit;
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
            <!-- "All" button - active by default -->
            <button type="button" class="footer-contact-pill active" data-category="">
                all
            </button>

            <?php
            // Get all story categories
            $categories = get_terms([
                'taxonomy' => 'story_category',
                'hide_empty' => true,
            ]);

            if (!empty($categories) && !is_wp_error($categories)) :
                foreach ($categories as $category) :
            ?>
                    <button type="button" class="footer-contact-pill" data-category="<?php echo esc_attr($category->slug); ?>">
                        <?php echo esc_html(strtolower($category->name)); ?>
                    </button>
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
// Replace header navigation for archive page (same as story pages)
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    const mainNav = header.querySelector('.main-nav');
    const contactPill = header.querySelector('.contact-pill');
    const siteTitle = header.querySelector('.site-title-name');

    // Remove existing navigation elements
    if (mainNav) mainNav.remove();
    if (contactPill) contactPill.remove();

    // Update site title to scroll to top of page
    if (siteTitle) {
        siteTitle.href = '#';
        siteTitle.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Create new story navigation
    const storyNav = document.createElement('a');
    storyNav.href = '/';
    storyNav.className = 'story-header-nav';
    storyNav.textContent = '← Home';
    header.appendChild(storyNav);

    // Create new contact button
    const contactButton = document.createElement('a');
    contactButton.href = '/#contact';
    contactButton.className = 'story-header-contact';
    contactButton.textContent = 'contact →';
    header.appendChild(contactButton);
});
</script>

<script>
(function() {
    let currentPage = 1;
    let currentCategory = '';
    let isLoading = false;
    let hasMorePosts = <?php echo $GLOBALS['wp_query']->found_posts > 24 ? 'true' : 'false'; ?>;

    const grid = document.getElementById('stories-grid');
    const trigger = document.getElementById('load-more-trigger');
    const loadingIndicator = document.getElementById('loading-indicator');
    const filterButtons = document.querySelectorAll('.category-filter-buttons button');

    if (!grid || !trigger) return;

    // Intersection Observer for lazy loading
    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMorePosts) {
                loadMorePosts();
            }
        });
    }, {
        rootMargin: '200px'
    });

    if (hasMorePosts) {
        observer.observe(trigger);
    }

    // Check URL for category parameter on page load
    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = urlParams.get('category');
    if (initialCategory) {
        // Find and click the matching filter button
        const matchingButton = document.querySelector(`.category-filter-buttons button[data-category="${initialCategory}"]`);
        if (matchingButton) {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            matchingButton.classList.add('active');
            filterStories(initialCategory);
        }
    }

    // Filter button click handlers
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Don't reload if same category
            if (category === currentCategory) return;

            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Load stories for this category
            filterStories(category);
        });
    });

    function updateURL(category) {
        const url = new URL(window.location);
        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        history.replaceState({}, '', url);
    }

    function filterStories(category) {
        currentCategory = category;
        currentPage = 1;
        isLoading = true;
        hasMorePosts = true;

        // Update URL for sharing
        updateURL(category);

        // Show loading, clear grid
        loadingIndicator.style.display = 'block';
        grid.innerHTML = '';
        trigger.style.display = 'block';

        // Disconnect and reconnect observer
        observer.disconnect();

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'load_more_stories',
                page: 1,
                category: category
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html) {
                grid.innerHTML = data.data.html;
                hasMorePosts = data.data.has_more;

                if (hasMorePosts) {
                    observer.observe(trigger);
                } else {
                    trigger.style.display = 'none';
                }
            } else {
                grid.innerHTML = '<p class="no-stories-message">No stories found.</p>';
                hasMorePosts = false;
                trigger.style.display = 'none';
            }

            loadingIndicator.style.display = 'none';
            isLoading = false;
        })
        .catch(error => {
            console.error('Error filtering stories:', error);
            loadingIndicator.style.display = 'none';
            isLoading = false;
        });
    }

    function loadMorePosts() {
        isLoading = true;
        loadingIndicator.style.display = 'block';
        currentPage++;

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'load_more_stories',
                page: currentPage,
                category: currentCategory
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
