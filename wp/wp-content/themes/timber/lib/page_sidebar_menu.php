<?php

/**
 * Gets a list of pages under this section's top ancestor
 *
 * @uses object $post and $postType
 * @return array
 */
function get_page_sidebar_menu($post, $include_top = true, $postType = null, $depth = 2){

    $top_id = get_post_top_ancestor_id($post);

    $args = array(
        'child_of'      => $top_id,
        'depth'         => $depth, // Only works with wp_list_pages()
        'hierarchical'  => true,
        'post_type'     => 'page',
        'post_status'   => 'publish',
        'sort_column'   => 'menu_order, post_title',
        'echo'          => 0,
        'title_li'      => 0,
        'link_before'   => '<i role="presentation" aria-hidden="true" class="ico ico--16 ico-arrow-lavender"></i> '
    );

    $submenu = wp_list_pages($args);

    $submenu = str_replace('<li class="', '<li class="navigation-tertiary__item ', $submenu);
    $submenu = str_replace('current_page_item', 'current_page_item navigation-tertiary__item--active', $submenu);
    $submenu = str_replace('page_item_has_children', 'page_item_has_children navigation-tertiary__item--parent', $submenu);
    $submenu = str_replace('<ul class=\'children\'', '<ul class="children navigation-tertiary--childlist"', $submenu);

    // if ($include_top) {
    //     $top = array(get_page($top_id));
    //     $all = array_merge($top, get_pages($args));
    // }
    // else {
    //     $all = get_pages($args);
    // }

    // $submenu = array_map(function ($r) {
    //     return array(
    //         'ID' => $r->ID,
    //         'link' => get_permalink($r->ID),
    //         'title' => $r->post_title,
    //         'post_parent' => $r->post_parent,
    //     );
    // }, $all);

    return $submenu;
}
