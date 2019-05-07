<?php

function get_related_as_timber_posts($context, $repeater = 'related') {

    if (isset($context[$repeater])) {
        $related = $context[$repeater];
        if (is_array($related) && count($related)) {
            $context[$repeater] = array_map(function ($r) {
                return new TimberPost($r->ID);
            }, $related);
        }
    }

    return $context;
}
