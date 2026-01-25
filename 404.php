<?php
/**
 * 404 Page Template
 *
 * Custom 404 error page for RJB Portfolio
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
    /* Prevent page scroll */
    html, body {
        height: 100vh;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    /* Light mode defaults */
    .error-404-container {
        --404-bg: white;
        --404-text: #000;
        --404-text-muted: #808080;
        --404-button-hover-text: white;
    }

    /* Dark mode overrides */
    @media (prefers-color-scheme: dark) {
        .error-404-container {
            --404-bg: #050505;
            --404-text: white;
            --404-text-muted: #a8a8a8;
            --404-button-hover-text: #050505;
        }
    }

    .error-404-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        text-align: center;
        background-color: var(--404-bg);
        box-sizing: border-box;
    }

    .error-404-code {
        font-family: var(--compressed-font) !important;
        font-size: clamp(120px, 20vw, 200px);
        font-weight: 400;
        line-height: 1;
        color: var(--highlight-color);
        margin-bottom: 1rem;
    }

    .error-404-title {
        font-family: var(--primary-font) !important;
        font-size: clamp(24px, 4vw, 36px);
        font-weight: 600;
        color: var(--404-text);
        margin-bottom: 1rem;
    }

    .error-404-message {
        font-family: var(--serif-font) !important;
        font-size: clamp(16px, 2vw, 20px);
        color: var(--404-text-muted);
        max-width: 500px;
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    .error-404-link {
        display: inline-block;
        font-family: var(--primary-font) !important;
        font-size: 16px;
        font-weight: 600;
        color: var(--highlight-color);
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        border: 2px solid var(--highlight-color);
        border-radius: 30px;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .error-404-link:hover {
        background-color: var(--highlight-color);
        color: var(--404-button-hover-text);
    }

    @media (max-width: 768px) {
        .error-404-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="error-404-container">
    <div class="error-404-code">404</div>
    <h1 class="error-404-title">Page Not Found</h1>
    <p class="error-404-message">The page you're looking for doesn't exist or has been moved.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404-link">Back to Home</a>
</div>

<?php get_footer('branded'); ?>
