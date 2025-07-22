<?php
/**
 * Stories Archive Template - Fully Branded  
 * Uses same styling approach as portfolio page, bypassing Astra defaults
 */

// Disable WordPress admin bar
show_admin_bar(false);

// Remove Astra's CSS
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('astra-theme-css');
    wp_deregister_style('astra-theme-css');
}, 100);

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="https://use.typekit.net/ffl7rra.css">

<?php wp_head(); ?>

<style>
    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-Regular.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
    }

    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-RegularItalic.woff2') format('woff2');
    font-weight: 400;
    font-style: italic;
    font-display: swap;
    }

    @font-face {
    font-family: 'Innovator Grotesk';
    src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBold.woff2') format('woff2');
    font-weight: 600;
    font-style: normal;
    font-display: swap;
    }

    /* CSS Variables */
    :root {
        --highlight-color: #39e58f;
        --primary-font: 'Innovator Grotesk', 'Helvetica Neue', Arial, sans-serif;
        --serif-font: 'Legitima', Georgia, serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: var(--primary-font);
        background: white;
        color: #000;
        overflow-x: hidden;
    }

    /* Header Styles */
    .site-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        z-index: 1000;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .header-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .site-title-name {
        font-family: var(--serif-font);
        font-size: 32px;
        font-weight: 400;
        color: #000;
        text-decoration: none;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .main-nav {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .main-nav ul {
        list-style: none;
        display: flex;
        gap: 2rem;
        margin: 0;
        padding: 0;
    }

    .main-nav a {
        color: #808080;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
        font-size: 16px;
    }

    .main-nav a:hover {
        color: var(--highlight-color);
    }

    /* Main Content */
    .main-content {
        margin-top: 100px;
        min-height: calc(100vh - 200px);
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

    /* Footer Styles */
    .site-footer {
        background: #000;
        color: white;
        padding: 2rem 0;
        margin-top: 4rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 2rem;
    }

    .footer-logo img {
        height: 40px;
        width: auto;
    }

    .social-links {
        color: #808080;
    }

    .social-links a {
        color: #808080;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .social-links a:hover {
        color: white;
    }

    .copyright {
        color: #808080;
        font-size: 14px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .site-header {
            height: auto;
            padding: 1rem 0;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            padding: 0 1rem;
        }

        .site-title-name {
            position: static;
            transform: none;
            font-size: 24px;
        }

        .main-nav {
            position: static;
            transform: none;
        }

        .main-nav ul {
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .stories-archive-container {
            padding: 2rem 1rem;
        }

        .archive-title {
            font-size: calc(36px * 1.23);
        }

        .stories-archive-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .story-category-filter ul {
            flex-direction: column;
            align-items: center;
        }

        .footer-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>
</head>

<body>
    <!-- Header -->
    <header class="site-header">
        <div class="header-content">
            <a href="<?php echo home_url('/'); ?>" class="site-title-name">
                Reuben J. Brown
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo home_url('/#about'); ?>">About</a></li>
                    <li><a href="<?php echo home_url('/#features'); ?>">Features</a></li>
                    <li><a href="<?php echo home_url('/#reviews'); ?>">Reviews</a></li>
                    <li><a href="<?php echo home_url('/#profiles'); ?>">Profiles</a></li>
                    <li><a href="<?php echo home_url('/#interviews'); ?>">Interviews</a></li>
                    <li><a href="<?php echo home_url('/#photographs'); ?>">Photographs</a></li>
                    <li><a href="<?php echo home_url('/#strategy'); ?>">Strategy</a></li>
                    <li><a href="<?php echo home_url('/#cv'); ?>">CV</a></li>
                </ul>
            </nav>
        </div>
    </header>

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

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/wp-content/uploads/2025/06/Reuben-J-Brown-logo-favicon-white.png" alt="RJB Logo">
            </div>
            <div class="social-links">
                <p><a href="mailto:reubenjbrown@protonmail.com">email</a> / <a href="https://www.instagram.com/reubenj.brown/">instagram</a> / <a href="https://www.linkedin.com/in/reuben-j-brown/">linkedin</a></p>
            </div>
            <div class="copyright">© Reuben J. Brown 2025</div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>