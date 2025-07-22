<?php
/**
 * Stories Archive Template
 * Template for displaying the stories archive page
 */

get_header(); ?>

<div class="stories-archive-container">
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
    
    <!-- Pagination -->
    <?php if (function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi(); ?>
    <?php else : ?>
        <div class="stories-pagination">
            <?php
            echo paginate_links([
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
                'type' => 'list'
            ]);
            ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>