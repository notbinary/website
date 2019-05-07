<?php

function get_partners() {
    $partners = get_field('featured_partners', 'option');

    if (count($partners)) {
        return array_filter(array_map(function ($i) {
            $p = new TimberPost($i->ID);
            if ($p->post_status == 'publish') {
                return $p;
            }
            return null;
        }, $partners));
    }
    else {
        return get_field('partners', 'option');
    }
}
