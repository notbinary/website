<?php
/*
* Get related or fallback
*/
function get_related_or_fallback($post, $post_types = array('articles')) {

    $posts_required = 3;
    $taxonomy = 'category';
    $post_id = $post->ID;
    $post_type = $post->post_type;
    $post_id_array = array($post_id);
    $selected = $post->get_field('related_content');
    $ids = [];

    if (is_array($selected)) {
        $selected = array_diff($selected, $post_id_array);
        $args = array(
            'post_type'         => $post_types,
            'include'           => $selected,
            'post_status'       => 'publish',
            'posts_per_page'    => $posts_required,
            'fields'            => 'ids',
            'orderby'           => 'post__in'
        );
        $ids = get_posts($args);
    }
    $count = count($ids);

    if ($count < $posts_required) {
        // We came up short, lets get posts of the same type with the same taxonomy terms
        $term_ids = wp_get_post_terms($post_id, $taxonomy, array('fields' => 'ids'));
        $args = array(
            'exclude'           => array_merge($ids, $post_id_array),
            'post_status'       => 'publish',
            'post_type'         => $post_type,
            'posts_per_page'    => $posts_required - $count,
            'tax_query'         => array(
                array(
                    'taxonomy'  => $taxonomy,
                    'terms'     => $term_ids
                )
            ),
            'fields'            => 'ids',
            'orderby'           => 'date',
            'order'             => 'DESC'
        );
        $secondary = get_posts($args);
        $ids = array_merge($ids, $secondary);
        $count = count($ids);
    }

    if ($count < $posts_required) {
        // We came up short, let us be less specific
        $args = array(
            'exclude'           => array_merge($ids, $post_id_array),
            'post_status'       => 'publish',
            'post_type'         => $post_type,
            'posts_per_page'    => $posts_required - $count,
            'fields'            => 'ids',
            'orderby'           => 'date',
            'order'             => 'DESC'
        );
        $tertiary = get_posts($args);
        $ids = array_merge($ids, $tertiary);
    }

    return Timber::get_posts($ids);
}
