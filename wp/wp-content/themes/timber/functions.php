<?php

// Autoload composer deps
require_once __DIR__ . '/composer/autoload.php';
require_once __DIR__ . '/lib/resize.php';
// Apply common WP fixes
require_once __DIR__ . '/lib/wp-fixes.php';
// Apply contact form fixes
require_once __DIR__ . '/lib/contact_forms.php';
// Apply fffunction theme specific stuff
require_once __DIR__ . '/lib/theme.php';
// SEO helpers
require_once __DIR__ . '/lib/seo.php';

// Set up timber
require_once __DIR__ . '/lib/timber.php';
// Grab $context functions
require_once __DIR__ . '/lib/context.php';
// Add custom routes
require_once __DIR__ . '/lib/routes.php';

// Helper functions
require_once __DIR__ . '/lib/get_page_by_template.php';
require_once __DIR__ . '/lib/get_pages_by_template.php';
require_once __DIR__ . '/lib/get_posts_by_menu_order.php';
require_once __DIR__ . '/lib/get_selected_or_fallback.php';
require_once __DIR__ . '/lib/get_related_or_fallback.php';
require_once __DIR__ . '/lib/page_sidebar_menu.php';

/**
 *   Global Variables
 *   Set up global variables for the site url and the assets folder url to reduce calls
 */
$GLOBALS['home'] = home_url() . '/';
$GLOBALS['assets'] = '/assets/';
$GLOBALS['current_user'] = wp_get_current_user();
$GLOBALS['responsive_sizes'] = array(300, 500, 780, 1060, 1200);

/**
 * Add Google Maps API key to ACF
 */
function acf_google_maps_key() {
    acf_update_setting('google_api_key', 'AIzaSyAZAIjZtkBlsF0ZqvrlkvyLfVn6Bju6bJ4');
}
add_action('acf/init', 'acf_google_maps_key');

/**
 * Set a custom WP Admin menu order
 */
function custom_menu_order($menu_ord) {
    return array(
        'index.php', // Dashboard
        'upload.php', // Media
        'edit.php?post_type=page',
        'separator1',
        'edit.php?post_type=articles',
        'edit.php?post_type=team-member',
        'separator2',
        'admin.php?page=common',
    );
}
// add_filter('custom_menu_order', '__return_true');
// add_filter('menu_order', 'custom_menu_order');

/**
 * Enable thumbnails/featured images
 */
add_theme_support('post-thumbnails');

add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction( $content ) {
    return $content .= '<p>Used as representation of this page in visual lists. Defaults to hero image if not set. Optimum image size 800x800px</p>';
}

// Adding excerpt for page
add_post_type_support( 'page', 'excerpt' );

// Disable Gutenberg Block Styles CSS
function remove_block_css(){
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );