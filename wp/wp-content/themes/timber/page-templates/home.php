<?php
/*
    Template name: Home
 */

$context = get_context();
global $params;

$context['page_links'] = get_field('page_links');

$context['logos'] = get_field('logos');

// echo '<pre>'; print_r($context['page_links']); echo '</pre>';

Timber::render('page/home.html', $context);