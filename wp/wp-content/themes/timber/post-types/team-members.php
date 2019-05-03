<?php

function team_member_init() {
	register_post_type( 'team-member', array(
		'labels'            => array(
			'name'                => __( 'Team Members', 'timber' ),
			'singular_name'       => __( 'Team Member', 'timber' ),
			'all_items'           => __( 'All Team Members', 'timber' ),
			'new_item'            => __( 'New Team Member', 'timber' ),
			'add_new'             => __( 'Add New', 'timber' ),
			'add_new_item'        => __( 'Add New Team Member', 'timber' ),
			'edit_item'           => __( 'Edit Team Member', 'timber' ),
			'view_item'           => __( 'View Team Member', 'timber' ),
			'search_items'        => __( 'Search Team Members', 'timber' ),
			'not_found'           => __( 'No Team Members found', 'timber' ),
			'not_found_in_trash'  => __( 'No Team Members found in trash', 'timber' ),
			'parent_item_colon'   => __( 'Parent Team Member', 'timber' ),
			'menu_name'           => __( 'Team Members', 'timber' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array('title', 'revisions', 'excerpt'),
		'has_archive'       => false,
		'rewrite'           => array('slug' => 'about-us/team'),
		'query_var'         => true,
		'menu_icon'         => 'dashicons-id-alt',
		'show_in_rest'      => true,
		'rest_base'         => 'about-us/team',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'team_member_init' );

function team_member_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['stories'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Team Member updated. <a target="_blank" href="%s">View Team Member</a>', 'timber'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'timber'),
		3 => __('Custom field deleted.', 'timber'),
		4 => __('Team Member updated.', 'timber'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Team Member restored to revision from %s', 'timber'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Team Member published. <a href="%s">View Team Member</a>', 'timber'), esc_url( $permalink ) ),
		7 => __('Team Member saved.', 'timber'),
		8 => sprintf( __('Team Member submitted. <a target="_blank" href="%s">Preview Team Member</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Team Member scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Team Member</a>', 'timber'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Team Member draft updated. <a target="_blank" href="%s">Preview Team Member</a>', 'timber'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'team_member_updated_messages' );
