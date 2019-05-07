<?php

function jobs_init() {
	register_post_type( 'jobs', array(
		'labels'            => array(
			'name'                => __( 'Jobs', 'timber' ),
			'singular_name'       => __( 'Job', 'timber' ),
			'all_items'           => __( 'All Jobs', 'timber' ),
			'new_item'            => __( 'New Job', 'timber' ),
			'add_new'             => __( 'Add New', 'timber' ),
			'add_new_item'        => __( 'Add New Job', 'timber' ),
			'edit_item'           => __( 'Edit Job', 'timber' ),
			'view_item'           => __( 'View Job', 'timber' ),
			'search_items'        => __( 'Search Jobs', 'timber' ),
			'not_found'           => __( 'No Jobs found', 'timber' ),
			'not_found_in_trash'  => __( 'No Jobs found in trash', 'timber' ),
			'parent_item_colon'   => __( 'Parent Job', 'timber' ),
			'menu_name'           => __( 'Jobs', 'timber' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array('title', 'editor', 'revisions', 'excerpt'),
		'has_archive'       => false,
		'rewrite'           => array('slug' => 'jobs'),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-media-text',
		'show_in_rest'      => true,
		'rest_base'         => 'jobs',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'jobs_init' );

function jobs_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['jobs'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Job updated. <a target="_blank" href="%s">View Job</a>', 'timber'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'timber'),
		3 => __('Custom field deleted.', 'timber'),
		4 => __('Job updated.', 'timber'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Job restored to revision from %s', 'timber'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Job published. <a href="%s">View Job</a>', 'timber'), esc_url( $permalink ) ),
		7 => __('Job saved.', 'timber'),
		8 => sprintf( __('Job submitted. <a target="_blank" href="%s">Preview Job</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Job scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Job</a>', 'timber'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Job draft updated. <a target="_blank" href="%s">Preview Job</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'jobs_updated_messages' );
