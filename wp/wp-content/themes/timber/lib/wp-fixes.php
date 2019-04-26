<?php

/*
*   Raise upload limit on uploads
*/
function fff_increase_upload($bytes)
{
    return 100663296; // 96 megabytes
}
add_filter( 'upload_size_limit', 'fff_increase_upload' );


/*
*   Remove builtin scripts
*/
function fff_remove_scripts() {
    // Remove comment-reply.js
    wp_dequeue_script('comment-reply');
    if (!is_admin()) {
        // Remove builtin jquey
        wp_dequeue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'fff_remove_scripts', 1);


/**
 * Remove junk from wp_head output
 */
remove_action('wp_head', 'rsd_link'); // discover mechanism used by XML-RPC clients like Flickr etc
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_generator'); // WordPress version number
remove_action('wp_head', 'start_post_rel_link'); // Post relational links
remove_action('wp_head', 'index_rel_link'); // Post relational links
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head'); // Post relational links
remove_action('wp_head', 'feed_links_extra', 3); // Removes the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Removes links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'print_emoji_detection_script', 7); // Remove emoji
remove_action('wp_print_styles', 'print_emoji_styles'); // Remove emoji
add_filter('emoji_svg_url', '__return_false'); // Remove emoji


/**
 * Remove WP Gallery inlines styles
 */
add_filter('use_default_gallery_style', '__return_false');


/*
* Move Yoast to the bottom of CMS
*/
add_filter( 'wpseo_metabox_prio', function() { return 'low';});


/**
 * Forces WP to never give attachments unique slugs,
 * e.g. vmware.jpg would be vmware-2 rather than vmware
 */
add_filter( 'wp_unique_post_slug_is_bad_attachment_slug', '__return_true' );


/**
 * Hide Wordpress Views advert from Custom Types plugin
 */
define('WPV_VERSION', '9999');


/**
 * Extend get terms with post type parameter.
 *
 * @global $wpdb
 * @param string $clauses
 * @param string $taxonomy
 * @param array $args
 * @return string
 */
function df_terms_clauses($clauses, $taxonomy, $args) {
    if ( isset( $args['post_type'] ) && ! empty( $args['post_type'] ) && $args['fields'] !== 'count' ) {
        global $wpdb;

        $post_types = array();

        if ( is_array( $args['post_type'] ) ) {
            foreach ( $args['post_type'] as $cpt ) {
                $post_types[] = "'" . $cpt . "'";
            }
        } else {
            $post_types[] = "'" . $args['post_type'] . "'";
        }

        if ( ! empty( $post_types ) ) {
            $clauses['fields'] = 'DISTINCT ' . str_replace( 'tt.*', 'tt.term_taxonomy_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields'] ) . ', COUNT(p.post_type) AS count';
            $clauses['join'] .= ' LEFT JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id';
            $clauses['where'] .= ' AND (p.post_type IN (' . implode( ',', $post_types ) . ') OR p.post_type IS NULL)';
            $clauses['orderby'] = 'GROUP BY t.term_id ' . $clauses['orderby'];
        }
    }
    return $clauses;
}
add_filter('terms_clauses', 'df_terms_clauses', 10, 3);


/**
 * Redirects empty search page to search.php rather than index.php
 */
function my_request_filter($query_vars) {
    if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
        $query_vars['s'] = " ";
    }
    return $query_vars;
}
add_filter( 'request', 'my_request_filter' );


/**
 * Remove Dashboard Widgets
 */
function remove_dashboard_widgets () {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');


/**
 * Set default values for the upload media box
 */
function default_post_images() {
    update_option('image_default_align', 'none' );
    update_option('image_default_link_type', 'none' );
    update_option('image_default_size', 'Large' ); // edit if needs be
}
add_action('after_setup_theme', 'default_post_images');


/**
 * Adds RSS feed links to <head> for posts and comments.
 */
add_theme_support('automatic-feed-links');
