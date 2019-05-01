<?php
/*
    Template name: Stories
 */

$context = get_context();
$context['sidebar_menu'] = get_page_sidebar_menu($context['post'], false);
$context['top_ancestor_id'] = get_post_top_ancestor_id($context['post'], false);
$context['top_ancestor'] = get_the_title(get_post_top_ancestor_id($context['post'], false));

Timber::render('page/stories.html', $context);