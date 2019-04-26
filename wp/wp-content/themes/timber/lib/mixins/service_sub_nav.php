<?php

function service_sub_nav($context) {

    $posts = get_posts_by_menu_order('services');
    $context['sub_nav'] = get_circular_nav($posts, $context['post']);

    return $context;
}
