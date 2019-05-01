<?php
/**
 * Template Name: Blog
 */

$templates = array('page/blog.html');

$context = Timber::context();

$context['title'] = 'Blog';
if ( is_day() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'D M Y' );
} else if ( is_month() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'M Y' );
} else if ( is_year() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'Y' );
} else if ( is_tag() ) {
	$context['title'] = single_tag_title( '', false );
} else if ( is_category() ) {
	$context['title'] = single_cat_title( '', false );
	$context['description'] = category_description();
	// array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.html' );
} else if ( is_post_type_archive() ) {
	$context['title'] = post_type_archive_title( '', false );
	// array_unshift( $templates, 'archive-' . get_post_type() . '.html' );
}

$blog_posts_mixin = listing_mixin(array(
    'post_type'      => 'post',
    'posts_per_page' => 12,
    'orderby'        => 'date',
    'order'          => 'DESC',
));
$context = $blog_posts_mixin($context);

$context['categories'] = get_categories();
$context['subheading'] = get_field('subheading');

Timber::render( $templates, $context );
