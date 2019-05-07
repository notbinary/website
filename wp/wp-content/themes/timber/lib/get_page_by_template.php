<?php

function get_page_by_template($template) {
    $args = array(
        'post_type' => 'page',
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    );
    $page = get_first(get_posts($args));
    if ($page) {
        return new TimberPost($page);
    }
    return $page;
}
