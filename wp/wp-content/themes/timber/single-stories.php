<?php

$context = get_context();

$context['related_content'] = get_related_or_fallback($context['post'],array('help','stories','articles','page'));

Timber::render('single/story.html', $context);