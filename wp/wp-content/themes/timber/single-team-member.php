<?php

$context = get_context();

$top_page = get_page_by_template('page-templates/about.php');
$team_page = get_page_by_template('page-templates/team.php');

$context['sidebar_menu'] = get_page_sidebar_menu($top_page, false, null, 2, $team_page->ID);

$context['top_ancestor_id'] = $top_page->ID;
$context['top_ancestor'] = $top_page->post_title;
$context['subheading'] = get_field('team_member_job_title');
$context['title'] = $post->post_title;
$context['related_content'] = get_related_or_fallback($context['post'],array('post','case-studies','page'));

Timber::render('single/team-member.html', $context);