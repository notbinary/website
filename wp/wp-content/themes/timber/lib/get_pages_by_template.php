<?php

function get_pages_by_template($template, $orderby = 'date', $order = 'DESC') {
    $args = array(
        'post_type' => 'page',
        'orderby' => $orderby,
        'order' => $order,
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    );
    $pages = Timber::get_posts($args);
    return $pages;
}
