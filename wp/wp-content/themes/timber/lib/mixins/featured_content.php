<?php
/**
 * Get the featured content link by link type
 * @param  obj $context
 * @return obj $context
 */
function featured_content_mixin ($context) {

    if ($context['post']->show_featured) {
        $link_type = 'featured_' . $context['post']->featured_link_type;
        $context['featured_link'] = $context['post']->get_field($link_type);
    }

    return $context;
}
