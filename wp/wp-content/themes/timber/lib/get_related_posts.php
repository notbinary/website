<?php
/*
* Get related posts
*/
function get_related_posts($post, $num_items, $type, $field, $orderby='date', $order='DESC') {

    $args = array(
        'post_type' => $type,
        'posts_per_page' => $num_items,
        'order' => $order,
        'orderby' => $orderby,
        'meta_query' => array(
            array(
                'key' => $field,
                'value' => '"' . $post->ID . '"',
                'compare' => 'LIKE'
            )
        )
    );

    return Timber::get_posts($args);
}
