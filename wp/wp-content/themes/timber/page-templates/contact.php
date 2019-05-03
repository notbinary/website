<?php
/**
 * Template Name: Contact
 */

$context = get_context();

$context['title'] = $post->post_title;
$context['subheading'] = get_field('subheading');
$context['related_content'] = get_related_or_fallback($context['post']);

Timber::render('page/contact.html', $context);