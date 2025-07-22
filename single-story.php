<?php
/**
 * Single Story Template - Fully Branded
 * Uses shared header and footer from portfolio page
 */

// Disable WordPress admin bar for story pages
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

    /* Story Content Styles */
    .story-single-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    .story-header {
        text-align: left;
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .story-title {
        font-family: var(--serif-font);
        font-size: calc(40px * 1.23);
        font-weight: 400;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        color: #000;
    }

    .story-standfirst {
        font-family: var(--primary-font);
        font-size: 24px;
        font-weight: 400;
        line-height: 1.4;
        color: #333;
        margin-bottom: 2rem;
    }

    .story-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .story-publication-info {
        color: #808080;
        font-size: 16px;
    }

    .story-publication-info em {
        color: #000;
        font-style: italic;
    }

    .story-external-link a {
        color: var(--highlight-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .story-external-link a:hover {
        color: #2dc776;
    }

    .story-featured-image {
        position: relative;
        margin-bottom: 3rem;
        width: 100%;
    }

    .story-featured-image img {
        width: 100%;
        height: auto;
        display: block;
    }

    .story-image-credit {
        font-size: 12px;
        color: #808080;
        text-align: right;
        margin-top: 4px;
        padding: 2px 0;
    }

    .story-content-inner {
        font-family: var(--primary-font);
        font-size: 20px;
        line-height: 1.6;
        color: #000;
    }

    .story-content-inner p {
        margin-bottom: 1.5rem;
    }

    .story-content-inner h2 {
        font-family: var(--serif-font);
        font-size: calc(32px * 1.23);
        font-weight: 400;
        margin: 2rem 0 1rem 0;
        color: #000;
    }

    .story-content-inner h3 {
        font-family: var(--serif-font);
        font-size: calc(24px * 1.23);
        font-weight: 400;
        margin: 1.5rem 0 1rem 0;
        color: #000;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding: 0 4vw;
        }

        .story-single-container {
            padding: 2rem 1rem;
        }

        .story-title {
            font-size: calc(28px * 1.23);
        }

        .story-standfirst {
            font-size: 20px;
        }

        .story-content-inner {
            font-size: 18px;
        }
    }
</style>

<!-- Main Content -->
<main class="main-content">
    <div class="story-single-container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <!-- Back to Portfolio Link -->
            <div class="back-to-portfolio">
                <a href="<?php echo home_url('/'); ?>">← Back to Portfolio</a>
            </div>
            
            <!-- Story Header -->
            <header class="story-header">
                <h1 class="story-title"><?php the_title(); ?></h1>
                
                <?php if (has_excerpt()) : ?>
                    <h2 class="story-standfirst"><?php the_excerpt(); ?></h2>
                <?php endif; ?>
                
                <div class="story-meta">
                    <?php
                    $publication = get_field('publication');
                    $publish_date = get_field('publish_date');
                    $external_url = get_field('external_url');
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
            </header>
            
            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="story-featured-image">
                    <?php the_post_thumbnail('full'); ?>
                    <?php 
                    $photo_credit = get_field('photo_credit');
                    if ($photo_credit) : 
                    ?>
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
            
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer('branded'); ?>