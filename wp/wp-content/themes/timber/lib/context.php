<?php

/**
 * Require all the files from lib/mixins
 */
$mixins = glob(__DIR__ . '/mixins/*.php');
if (!empty($mixins)) {
    foreach($mixins as $mixin) {
        require_once($mixin);
    }
}


/**
 * Gets the current context from the global $post
 * @return array a context object with global data and current post fields
 */
function current_context () {
    $context = Timber::get_context();
    $post = new TimberPost();
    if ($post->ID == 0) {
        $seo_array = do_seo_to_post($context, $post);
        $context = $seo_array['context'];
        $post = $seo_array['post'];
    }
    add_yoast_image_filter($context, $post);
    $context['post'] = $post;
    $f = get_fields($post->ID);
    $context = array_merge_recursive($context, (is_array($f)) ? $f : array());
    $context['context'] = $context;

    return $context;
}

/**
 * Gets the context for a specific post
 * @param  array|int $post a WP_Post or an ID, whatever TimberPost accepts
 * @return array           a context object with the post fields
 */
function specific_context($post) {
    $context = [];
    $fields = get_fields($post->ID);
    $context = array_merge_recursive((array) $context, (is_array($fields)) ? $fields : array());
    return $context;
}

/**
 * Conditionally calls either current_context or specific_context
 * based on whether you pass in a post or not.
 * @param  array|int|boolean $post false or a post identifier
 * @return array        a context object. See the above functions
 */
function get_context ($post = false) {
    if (!$post) {
        return current_context();
    } else {
        return specific_context($post);
    }
}
