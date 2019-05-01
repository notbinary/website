<?php

$context = get_context();
$parent = get_page_by_template('page-templates/articles.php');
$context['related_content'] = get_related_or_fallback($context['post']);

Timber::render('single/article.html', $context);
