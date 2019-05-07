<?php

// Used for blog posts

$context = get_context();
$context['related_content'] = get_related_or_fallback($context['post']);
$context['categories'] = get_categories();

Timber::render('single/blog.html', $context);
