<?php
$context = get_context();
// Get the related content
$context['related_content'] = get_related_or_fallback($context['post']);
// Get the parent jobs page
$parent = get_page_by_template('page-templates/jobs.php');
// Get the how to apply content
$context['how_to_apply_title'] = get_field('how_to_apply_title',$parent->ID);
$context['how_to_apply_content'] = get_field('how_to_apply_content',$parent->ID);
$context['how_to_apply_link_email'] = get_field('how_to_apply_link_email',$parent->ID);
// If the how to apply content is set at post level use that
if(get_field('how_to_apply_title') != '' && get_field('how_to_apply_content')){
	$context['how_to_apply_title'] = get_field('how_to_apply_title');
	$context['how_to_apply_content'] = get_field('how_to_apply_content');
	$context['how_to_apply_link_email'] = get_field('how_to_apply_link_email');
}
Timber::render('single/job.html', $context);