<?php

function get_related_articles_mixin ($context) {

    $terms = $context['post']->terms;
    $context['related_posts'] = [];
    $posts_required = 3;

    $args_without_author = array(
        'post_type' => 'articles',
        'posts_per_page' => $posts_required,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => array_map(function($r) {
                    return $r->slug;
                }, $terms)
            )
        ),
        'post__not_in' => array($context['post']->ID)
    );

    $args_with_author = array_merge(
        $args_without_author,
        array(
            'author' => $context['post']->post_author
        )
    );

    if (is_array($terms) and count($terms)) {

        $related_posts = Timber::get_posts($args_with_author);
        $num_articles_with_author = count($related_posts);

        if ($num_articles_with_author < $posts_required) {
            $fallbacks_required = $posts_required - $num_articles_with_author;
            $args_without_author['posts_per_page'] = $fallbacks_required;
            $related_posts = array_merge($related_posts, Timber::get_posts($args_without_author));
        }

        $context['related_posts'] = $related_posts;
    }

    return $context;
}
