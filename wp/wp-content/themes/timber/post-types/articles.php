<?php

function articles_init() {
	register_post_type( 'articles', array(
		'labels'            => array(
			'name'                => __( 'Articles', 'timber' ),
			'singular_name'       => __( 'Article', 'timber' ),
			'all_items'           => __( 'All Articles', 'timber' ),
			'new_item'            => __( 'New Article', 'timber' ),
			'add_new'             => __( 'Add New', 'timber' ),
			'add_new_item'        => __( 'Add New Article', 'timber' ),
			'edit_item'           => __( 'Edit Article', 'timber' ),
			'view_item'           => __( 'View Article', 'timber' ),
			'search_items'        => __( 'Search Articles', 'timber' ),
			'not_found'           => __( 'No Articles found', 'timber' ),
			'not_found_in_trash'  => __( 'No Articles found in trash', 'timber' ),
			'parent_item_colon'   => __( 'Parent Article', 'timber' ),
			'menu_name'           => __( 'Articles', 'timber' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array('title', 'revisions', 'excerpt', 'author', 'comments'),
		'has_archive'       => false,
		'rewrite'           => array('slug' => 'news'),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-media-text',
		'show_in_rest'      => true,
		'rest_base'         => 'news',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'articles_init' );

function articles_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['articles'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Article updated. <a target="_blank" href="%s">View Article</a>', 'timber'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'timber'),
		3 => __('Custom field deleted.', 'timber'),
		4 => __('Article updated.', 'timber'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Article restored to revision from %s', 'timber'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Article published. <a href="%s">View Article</a>', 'timber'), esc_url( $permalink ) ),
		7 => __('Article saved.', 'timber'),
		8 => sprintf( __('Article submitted. <a target="_blank" href="%s">Preview Article</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Article scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Article</a>', 'timber'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Article draft updated. <a target="_blank" href="%s">Preview Article</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'articles_updated_messages' );
