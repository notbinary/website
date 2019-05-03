<?php

function case_studies_init() {
	register_post_type( 'case-studies', array(
		'labels'            => array(
			'name'                => __( 'Case studies', 'timber' ),
			'singular_name'       => __( 'Case study', 'timber' ),
			'all_items'           => __( 'All Case studies', 'timber' ),
			'new_item'            => __( 'New Case study', 'timber' ),
			'add_new'             => __( 'Add New', 'timber' ),
			'add_new_item'        => __( 'Add New Case study', 'timber' ),
			'edit_item'           => __( 'Edit Case study', 'timber' ),
			'view_item'           => __( 'View Case study', 'timber' ),
			'search_items'        => __( 'Search Case studies', 'timber' ),
			'not_found'           => __( 'No Case studies found', 'timber' ),
			'not_found_in_trash'  => __( 'No Case studies found in trash', 'timber' ),
			'parent_item_colon'   => __( 'Parent Case study', 'timber' ),
			'menu_name'           => __( 'Case studies', 'timber' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array('title', 'revisions', 'excerpt'),
		'has_archive'       => false,
		'rewrite'           => array('slug' => 'what-we-do/case-studies'),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-media-text',
		'show_in_rest'      => true,
		'rest_base'         => 'what-we-do/case-studies',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'case_studies_init' );

function case_studies_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['case-studies'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Case study updated. <a target="_blank" href="%s">View Case study</a>', 'timber'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'timber'),
		3 => __('Custom field deleted.', 'timber'),
		4 => __('Case study updated.', 'timber'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Case study restored to revision from %s', 'timber'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Case study published. <a href="%s">View Case study</a>', 'timber'), esc_url( $permalink ) ),
		7 => __('Case study saved.', 'timber'),
		8 => sprintf( __('Case study submitted. <a target="_blank" href="%s">Preview Case study</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Case study scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Case study</a>', 'timber'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Case study draft updated. <a target="_blank" href="%s">Preview Case study</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'case_studies_updated_messages' );
