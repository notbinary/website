<?php

function get_posts_by_menu_order($post_type) {
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    $posts = Timber::get_posts($args);
    return $posts;
}
