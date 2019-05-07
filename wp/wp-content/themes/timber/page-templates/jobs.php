<?php
/*
    Template name: Jobs
 */

$context = get_context();

/**
 * Get the filtered jobs
 */
$jobs_mixin = listing_mixin(array(
    'post_type'      => 'jobs',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
));
$context = $jobs_mixin($context);

$context['title'] = $post->post_title;
$context['content'] = $post->post_content;
$context['subheading'] = get_field('subheading');
$context['related_content'] = get_related_or_fallback($context['post']);

Timber::render('page/jobs.html', $context);