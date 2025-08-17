<?php
/*
Template Name: Portfolio Page Debug
*/

// Debug: Template is loading
error_log('Portfolio DEBUG template is loading');
echo '<!-- PORTFOLIO DEBUG TEMPLATE LOADED -->';

try {
    echo '<h1>Portfolio Template Debug Test</h1>';
    echo '<p>Testing shortcodes one by one...</p>';
    
    // Test 1: Basic shortcode
    echo '<h2>Test 1: About Section</h2>';
    echo do_shortcode('[reuben_about]');
    echo '<hr>';
    
    // Test 2: Reviews
    echo '<h2>Test 2: Reviews Section</h2>';
    echo do_shortcode('[reuben_reviews]');
    echo '<hr>';
    
    // Test 3: Dynamic Stories (this might be the problem)
    echo '<h2>Test 3: Dynamic Stories - Profiles</h2>';
    echo do_shortcode('[reuben_dynamic_stories category="profiles" layout="grid" limit="11" show_view_all="true"]');
    echo '<hr>';
    
    echo '<p>If you see this message, all shortcodes are working!</p>';
    
} catch (Exception $e) {
    echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
}

get_footer();
?>