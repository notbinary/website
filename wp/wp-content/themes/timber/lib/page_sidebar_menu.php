<?php

/**
 * Gets a list of pages under this section's top ancestor
 *
 * @uses object $post and $postType
 * @return array
 */
function get_page_sidebar_menu($post, $include_top = true, $postType = null, $depth = 2, $active_id = null){

    $top_id = get_post_top_ancestor_id($post);

    $args = array(
        'child_of'      => $top_id,
        'depth'         => $depth,
        'hierarchical'  => true,
        'post_type'     => 'page',
        'post_status'   => 'publish',
        'sort_column'   => 'menu_order, post_title',
        'echo'          => 0,
        'title_li'      => 0,
        'link_before'   => ''
    );

    $submenu = wp_list_pages($args);

    $submenu = str_replace('<li class="', '<li class="navigation-secondary__item ', $submenu);
    $submenu = str_replace('current_page_item', 'current_page_item navigation-secondary__item--active', $submenu);
    $submenu = str_replace('page_item_has_children', 'page_item_has_children navigation-secondary__item--parent', $submenu);
    $submenu = str_replace('<ul class=\'children\'', '<ul class="children navigation-tertiary--childlist"', $submenu);

    // Make team page active if we're on a team member single page
    if ($active_id != null) {
        $submenu = str_replace('page-item-'.$active_id, 'current_page_item navigation-secondary__item--active', $submenu);
    }

    return $submenu;
}
