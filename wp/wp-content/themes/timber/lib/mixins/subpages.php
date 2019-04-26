<?php

function get_subpages_mixin($context) {
    $context['children'] = Timber::get_posts(array(
        'post_parent' => $context['post']->ID,
        'order' => 'ASC',
        'orderby' => 'menu_order',
        'post_status' => 'publish',
        'post_type' => 'page',
    ));
    return $context;
}
