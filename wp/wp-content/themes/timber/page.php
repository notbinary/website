<?php

$context = get_context();

error_log(json_encode($context));

$context['related_content'] = get_related_or_fallback($context['post']);

Timber::render('page/standard-page.html', $context);