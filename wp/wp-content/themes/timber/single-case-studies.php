<?php

$context = get_context();

$context['related_content'] = get_related_or_fallback($context['post'],array('post','case-studies','page'));

Timber::render('single/case-study.html', $context);