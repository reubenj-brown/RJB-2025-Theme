<?php
/*
Template Name: Test Page
*/

// Disable WordPress admin bar for this page
show_admin_bar(false);

get_header();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Page - <?php bloginfo('name'); ?></title>
    
    <?php wp_head(); ?>
    
    <!-- Adobe Fonts - Legitima -->
    <link rel="stylesheet" href="https://use.typekit.net/ffl7rra.css">
    
    <style>
        /* Font declarations */
        @font-face {
            font-family: 'Innovator Grotesk';
            src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-Regular.woff2') format('woff2'),
                 url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-Regular.woff') format('woff');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Innovator Grotesk';
            src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-RegularItalic.woff2') format('woff2'),
                 url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-RegularItalic.woff') format('woff');
            font-weight: 400;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: 'Innovator Grotesk';
            src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBold.woff2') format('woff2'),
                 url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBold.woff') format('woff');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Innovator Grotesk';
            src: url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBoldItalic.woff2') format('woff2'),
                 url('<?php echo get_stylesheet_directory_uri(); ?>/fonts/InnovatorGrotesk-SemiBoldItalic.woff') format('woff');
            font-weight: 600;
            font-style: italic;
            font-display: swap;
        }

        /* CSS Variables */
        :root {
            --highlight-color: #39e58f;
            --primary-font: 'Innovator Grotesk', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            --serif-font: 'Legitima', Georgia, 'Times New Roman', serif;
        }

        /* Hide WordPress theme elements for clean testing */
        body.page-template-page-test .site-header,
        body.page-template-page-test .site-footer,
        body.page-template-page-test .main-navigation,
        body.page-template-page-test .entry-header,
        body.page-template-page-test .entry-footer,
        body.page-template-page-test .widget-area,
        body.page-template-page-test .sidebar {
            display: none !important;
        }

        /* Reset styles for test page */
        body.page-template-page-test {
            font-family: var(--primary-font) !important;
            line-height: 1.6;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }

        /* Hide admin bar */
        #wpadminbar {
            display: none !important;
        }

        html {
            margin-top: 0 !important;
        }

        /* Main content container */
        .test-content {
            max-width: 100vw;
            width: 100vw;
            margin: 0;
            padding: 0 2vw;
        }
    </style>
</head>

<body class="page-template-page-test">
    <main class="test-content">
        <?php echo do_shortcode('[reuben_about]'); ?>
        <?php echo do_shortcode('[reuben_writing]'); ?>
        <?php echo do_shortcode('[reuben_photography]'); ?>
        <?php echo do_shortcode('[reuben_strategy]'); ?>
        <?php echo do_shortcode('[reuben_cv]'); ?>
        
    </main>

    <?php wp_footer(); ?>
</body>
</html>
