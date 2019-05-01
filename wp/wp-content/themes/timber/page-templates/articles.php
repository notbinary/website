<?php
/*
    Template name: Blog
 */

$context = get_context();
global $params;

/**
 * See if there is a valid featured article, if so set the exclude array
 * to avoid it appearing twice
 */
$context = featured_article_mixin($context);

/**
 * Get the filtered articles
 */
$articles_mixin = listing_mixin(array(
    'post_type'      => 'articles',
    'posts_per_page' => 12,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'args'           => array(
        'post__not_in' => $context['exclude_featured']
    )
));
$context = $articles_mixin($context);

Timber::render('page/articles.html', $context);