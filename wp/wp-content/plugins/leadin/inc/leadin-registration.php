<?php
if ( ! defined( 'LEADIN_PLUGIN_VERSION' ) ) {
	wp_die( '', '', 403 );
}

add_action( 'wp_ajax_leadin_registration_ajax', 'leadin_registration_ajax' );

/**
 * AJAX handler to connect portal to WordPress
 */
function leadin_registration_ajax() {
	check_ajax_referer( 'hubspot-ajax' );
	leadin_activate_plugins_or_403();
	$existing_portal_id = get_option( 'leadin_portalId' );

	if ( ! empty( $existing_portal_id ) ) {
		wp_die( '{"error": "Registration is already complete for this portal"}', '', 400 );
	}

	$request_body  = file_get_contents( 'php://input' );
	$data          = json_decode( $request_body, true );
	$new_portal_id = intval( $data['portalId'] );

	if ( empty( $new_portal_id ) ) {
		$error_body = array(
			'error'   => 'Registration missing required fields',
			'request' => $request_body,
		);

		wp_die( json_encode( $error_body ), '', 400 );
	}

	add_option( 'leadin_portalId', $new_portal_id );

	wp_die( '{"message": "Success!"}' );
}
