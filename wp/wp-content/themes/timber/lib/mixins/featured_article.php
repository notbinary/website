<?php
/**
 * Get a featured article if exists and not draft
 * @param  obj $context
 * @return obj $context
 */
function featured_article_mixin ($context) {

    $context['featured_article'] = null;
    $context['exclude_featured'] = array();
    if ($context['post']->featured_article) {
        $article = new TimberPost($context['post']->featured_article);
        if ($article->post_status == 'publish') {
            if (
                !isset($context['category']) ||
                $article->has_term($context['category'], 'category')
            ) {
                $context['featured_article'] = $article;
                $context['exclude_featured'] = array($article->ID);
            }
        }
    }

    return $context;
}
