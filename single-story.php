<?php
/**
 * Single Story Template - Fully Branded
 * Uses same styling approach as portfolio page, bypassing Astra defaults
 */

// Disable WordPress admin bar for story pages
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