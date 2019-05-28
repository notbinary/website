<?php

// Set up tinymce
require_once __DIR__ . '/tinymce.php';


/**
 * Remove default taxonomies
 */
function unregister_default_taxonomies(){
    global $wp_taxonomies;
    $taxonomies = array('category', 'post_tag');
    foreach($taxonomies as $taxonomy) {
        if (taxonomy_exists($taxonomy)) {
            unset($wp_taxonomies[$taxonomy]);
        }
    }
}
add_action('init', 'unregister_default_taxonomies');


/**
 * Register post type definitions
 */
$post_type_files = glob(get_stylesheet_directory() . '/post-types/*.php');
if (!empty($post_type_files)) {
    foreach($post_type_files as $post_type) {
        require_once($post_type);
    }
}


/**
 * Register taxonomy definitions
 */
// $taxonomy_files = glob(get_stylesheet_directory() . '/taxonomies/*.php');
// if (!empty($taxonomy_files)) {
//     foreach($taxonomy_files as $taxonomy) {
//         require_once($taxonomy);
//     }
// }

/**
 * Remove support for pages
 */
add_action('admin_init', 'remove_page_support');
function remove_page_support() {
    // remove_post_type_support('page', 'editor');     // content editor
    // remove_post_type_support('page', 'thumbnail');  // thumbnail
}


/**
 * Register Options page
 */
if( function_exists('acf_add_options_page') ) {

    // add parent
    $parent = acf_add_options_page(array(
        'page_title'    => 'Common',
        'menu_title'    => 'Common',
        'position'      => '40',
        'capability'    => 'delete_others_pages',
        'redirect'      => true
    ));

    // add sub pages
    acf_add_options_sub_page(array(
        'page_title'    => 'Footer',
        'parent_slug'   => $parent['menu_slug'],
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Social accounts',
        'parent_slug'   => $parent['menu_slug'],
    ));
    acf_add_options_sub_page(array(
        'page_title'    => '404',
        'parent_slug'   => $parent['menu_slug'],
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Metatags and API Keys',
        'parent_slug'   => $parent['menu_slug'],
    ));

}


/*
*   Remove Menu Items
*/
// function fff_remove_menu_items() {
    // remove_menu_page('edit.php'); // Posts
    // remove_menu_page('edit-comments.php'); // Comments
// }
// add_action('admin_menu', 'fff_remove_menu_items');

/**
 * Register and create menus if they don't exist
 */
function fff_wp_menus() {
    register_nav_menus(
        array(
            'main-nav'   => 'Main navigation',
            'footer-nav' => 'Footer navigation',
            'footer-links' => 'Footer links',
        )
    );
    if (!wp_get_nav_menu_object('Main navigation')) {
        $menu_id = wp_create_nav_menu('Main navigation');       //create the menu
        $locations = get_theme_mod('nav_menu_locations');       //get the menu locations
        $locations['main-nav'] = $menu_id;                      //set our new menu to be the main nav
        set_theme_mod('nav_menu_locations', $locations);        //update
    }
    if (!wp_get_nav_menu_object('Footer navigation')) {
        $menu_id = wp_create_nav_menu('Footer navigation');     //create the menu
        $locations = get_theme_mod('nav_menu_locations');       //get the menu locations
        $locations['footer-nav'] = $menu_id;                    //set our new menu to be the main nav
        set_theme_mod('nav_menu_locations', $locations);        //update
    }
    if (!wp_get_nav_menu_object('Footer links')) {
        $menu_id = wp_create_nav_menu('Footer links');     //create the menu
        $locations = get_theme_mod('nav_menu_locations');       //get the menu locations
        $locations['footer-link'] = $menu_id;                    //set our new menu to be the main nav
        set_theme_mod('nav_menu_locations', $locations);        //update
    }
}
add_action('init', 'fff_wp_menus');

/**
 * Amend oembed with extra props.
 */
function amend_embed_html($code){

    if (strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false) {
        $code = str_replace('?feature=oembed', '?rel=0&autohide=1&showinfo=0&cc_load_policy=1', $code);
    }
    elseif (strpos($code, 'vimeo.com') !== false) {
        $query_start = strpos($code,'?');
        $code = substr_replace($code,'title=0&byline=0&portrait=0&',$query_start+1,0);
    }
    return $code;
}
add_filter('embed_oembed_html', 'amend_embed_html');

/**
 * Get the first item from an array or false
 * @param  array $array
 * @return any|bool The first item or false
 */
function get_first($array) {
    if (count($array)) {
        return $array[0];
    }
    return false;
}
