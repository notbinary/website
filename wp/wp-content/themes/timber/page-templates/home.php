<?php
/*
    Template name: Home
 */

$context = get_context();
global $params;

$context['page_links'] = get_field('page_links');

$context['logos'] = get_field('logos');

Timber::render('page/home.html', $context);