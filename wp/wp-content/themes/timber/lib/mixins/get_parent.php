<?php

include_once __DIR__ . '/../top_ancestor_id.php';

function get_parent_page_mixin ($ctx) {
    $topid = get_post_top_ancestor_id($ctx['page']);
    $ctx['parent'] = new TimberPost($topid);
    return $ctx;
}
