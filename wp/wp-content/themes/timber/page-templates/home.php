<?php
/*
    Template name: Home
 */

$context = get_context();
global $params;

Timber::render('page/home.html', $context);