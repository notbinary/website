<?php

if (!function_exists('get_post_top_ancestor_id')) {
    /**
     * Gets the id of the topmost ancestor of the current page. Returns the current
     * page's id if there is no parent.
     *
     * @param object $post
     * @return int
     */
    function get_post_top_ancestor_id($post) {
        if($post->post_parent){
            $ancestors = array_reverse(get_post_ancestors($post->ID));
            return $ancestors[0];
        }

        return $post->ID;
    }
}
