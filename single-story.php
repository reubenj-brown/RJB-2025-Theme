<?php
/**
 * Single Story Template
 * Template for displaying individual story posts
 */

get_header(); ?>

<div class="story-single-container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <!-- Story Header -->
        <header class="story-header">
            <div class="story-header-content">
                <h1 class="story-title"><?php the_title(); ?></h1>
                
                <?php if (has_excerpt()) : ?>
                    <h2 class="story-standfirst"><?php the_excerpt(); ?></h2>
                <?php endif; ?>
                
                <div class="story-meta">
                    <?php
                    // Get custom fields
                    $publication = get_field('publication');
                    $publish_date = get_field('publish_date');
                    $external_url = get_field('external_url');
                    $photo_credit = get_field('photo_credit');
                    ?>
                    
                    <?php if ($publication || $publish_date) : ?>
                        <p class="story-publication-info">
                            <?php if ($publication) : ?>
                                For <em><?php echo esc_html($publication); ?></em>
                            <?php endif; ?>
                            <?php if ($publish_date) : ?>
                                <?php echo $publication ? ' in ' : ''; ?>
                                <?php echo esc_html($publish_date); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($external_url) : ?>
                        <p class="story-external-link">
                            <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener">
                                Read the full story →
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        
        <!-- Featured Image -->
        <?php if (has_post_thumbnail()) : ?>
            <div class="story-featured-image">
                <?php the_post_thumbnail('full'); ?>
                <?php if ($photo_credit) : ?>
                    <div class="story-image-credit"><?php echo esc_html($photo_credit); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Story Content -->
        <article class="story-content">
            <div class="story-content-inner">
                <?php the_content(); ?>
            </div>
        </article>
        
        <!-- Story Categories -->
        <?php
        $categories = wp_get_post_terms(get_the_ID(), 'story_category');
        if (!empty($categories) && !is_wp_error($categories)) :
        ?>
            <div class="story-categories">
                <h3>Categories</h3>
                <ul>
                    <?php foreach ($categories as $category) : ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($category)); ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <nav class="story-navigation">
            <div class="nav-previous">
                <?php previous_post_link('%link', '← Previous Story', true, '', 'story_category'); ?>
            </div>
            <div class="nav-next">
                <?php next_post_link('%link', 'Next Story →', true, '', 'story_category'); ?>
            </div>
        </nav>
        
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>