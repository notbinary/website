<?php
/*
    Template name: Team
 */

$context = get_context();

$context['sidebar_menu'] = get_page_sidebar_menu($context['post'], false);
$context['top_ancestor_id'] = get_post_top_ancestor_id($context['post'], false);
$context['top_ancestor'] = get_the_title(get_post_top_ancestor_id($context['post'], false));
$context['subheading'] = get_field('subheading');
$context['title'] = $post->post_title;
$context['related_content'] = get_related_or_fallback($context['post']);

// $team_mixin = listing_mixin(array(
//     'post_type'      => 'team-member',
//     'posts_per_page' => -1,
//     'orderby'        => 'menu_order',
//     'order'          => 'DESC',
// ));
// $context = $team_mixin($context);

$context['team_members_title'] 	= get_field('team_members_title');
$context['team_members'] = get_field('team_members');
$context['additional_people_title'] = get_field('additional_people_title');
$context['additional_people'] = get_field('additional_people');


Timber::render('page/team.html', $context);