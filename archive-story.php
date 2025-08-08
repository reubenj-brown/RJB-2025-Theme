<?php
/**
 * Stories Archive Template - Fully Branded  
 * Uses shared header and footer from portfolio page
 */

// Disable WordPress admin bar
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

get_header('branded'); ?>

<style>
    /* Main Content */
    .main-content {
        margin-top: calc(60px + 2vw);
        min-height: calc(100vh - 200px);
        padding: 0 2vw;
        width: 100vw;
        max-width: 100vw;
        background: white;
        position: relative;
        z-index: 10;
    }

    /* Archive Styles */
    .stories-archive-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    .back-to-portfolio {
        margin-bottom: 2rem;
    }

    .back-to-portfolio a {
        color: var(--highlight-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .back-to-portfolio a:hover {
        color: #2dc776;
    }

    .archive-header {
        text-align: center;
        margin-bottom: 3rem;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 2rem;
    }

    .archive-title {
        font-family: var(--serif-font);
        font-size: calc(48px * 1.23);
        font-weight: 400;
        margin-bottom: 1rem;
        color: #000;
    }

    .archive-description {
        font-size: 20px;
        color: #666;
        margin-bottom: 2rem;
    }

    .story-category-filter ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .story-category-filter a {
        color: #808080;
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .story-category-filter a:hover,
    .story-category-filter a.active {
        color: var(--highlight-color);
        border-color: var(--highlight-color);
    }

    .stories-archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .story-archive-item {
        position: relative;
    }

    .story-archive-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.3s ease;
    }

    .story-archive-link:hover {
        transform: translateY(-5px);
    }

    .story-archive-image {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .story-archive-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .story-archive-credit {
        font-size: 12px;
        color: #808080;
        text-align: right;
        margin-top: 4px;
        margin-bottom: 1rem;
    }

    .story-archive-title {
        font-family: var(--serif-font);
        font-size: calc(24px * 1.23);
        font-weight: 400;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        color: #000;
    }

    .story-archive-excerpt {
        font-size: 16px;
        line-height: 1.5;
        color: #666;
        margin-bottom: 1rem;
    }

    .story-archive-publication {
        font-size: 14px;
        color: #808080;
    }

    .story-archive-publication em {
        color: #000;
        font-style: italic;
    }

    .no-stories {
        text-align: center;
        font-size: 18px;
        color: #666;
        margin: 3rem 0;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding: 0 4vw;
        }

        .stories-archive-container {
            padding: 2rem 1rem;
        }

        .archive-title {
            font-size: 24px;
        }

        .stories-archive-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .story-category-filter ul {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<!-- Main Content -->
<main class="main-content">
    <div class="stories-archive-container">
        <!-- Back to Portfolio Link -->
        <div class="back-to-portfolio">
            <a href="<?php echo home_url('/'); ?>">← Back to Portfolio</a>
        </div>

        <header class="archive-header">
            <h1 class="archive-title">All Stories</h1>
            <p class="archive-description">A collection of journalism, essays, and multimedia stories by Reuben J. Brown</p>
            
            <!-- Category Filter -->
            <?php
            $categories = get_terms([
                'taxonomy' => 'story_category',
                'hide_empty' => true,
            ]);
            if (!empty($categories) && !is_wp_error($categories)) :
            ?>
                <div class="story-category-filter">
                    <ul>
                        <li><a href="<?php echo get_post_type_archive_link('story'); ?>" class="<?php echo !is_tax('story_category') ? 'active' : ''; ?>">All</a></li>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="<?php echo get_term_link($category); ?>" class="<?php echo is_tax('story_category', $category->term_id) ? 'active' : ''; ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </header>
        
        <!-- Stories Grid -->
        <div class="stories-archive-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="story-archive-item">
                        <a href="<?php the_permalink(); ?>" class="story-archive-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="story-archive-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php
                                $photo_credit = get_field('photo_credit');
                                if ($photo_credit) :
                                ?>
                                    <div class="story-archive-credit"><?php echo esc_html($photo_credit); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <div class="story-archive-content">
                                <h2 class="story-archive-title"><?php the_title(); ?></h2>
                                
                                <?php if (has_excerpt()) : ?>
                                    <p class="story-archive-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <?php endif; ?>
                                
                                <div class="story-archive-meta">
                                    <?php
                                    $publication = get_field('publication');
                                    $publish_date = get_field('publish_date');
                                    ?>
                                    <?php if ($publication || $publish_date) : ?>
                                        <span class="story-archive-publication">
                                            <?php if ($publication) : ?>
                                                For <em><?php echo esc_html($publication); ?></em>
                                            <?php endif; ?>
                                            <?php if ($publish_date) : ?>
                                                <?php echo $publication ? ' in ' : ''; ?>
                                                <?php echo esc_html($publish_date); ?>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="no-stories">No stories found.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer('branded'); ?>