<?php
/*
    Template name: Sidebar
 */

$context = get_context();

$context['sidebar_menu'] = get_page_sidebar_menu($context['post'], false);
$context['top_ancestor_id'] = get_post_top_ancestor_id($context['post'], false);
$context['top_ancestor'] = get_the_title(get_post_top_ancestor_id($context['post'], false));
$context['subheading'] = get_field('subheading');
$context['title'] = $post->post_title;
$context['related_content'] = get_related_or_fallback($context['post']);

Timber::render('page/sidebar.html', $context);