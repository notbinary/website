<?php
if ( ! defined( 'LEADIN_PLUGIN_VERSION' ) ) {
	wp_die( '', '', 403 );
}

add_action( 'wp_ajax_leadin_disconnect_ajax', 'leadin_disconnect_ajax' );

/**
 * AJAX handler to disconnect portal id
 */
function leadin_disconnect_ajax() {
	check_ajax_referer( 'hubspot-ajax' );
	leadin_activate_plugins_or_403();
	if ( get_option( 'leadin_portalId' ) ) {
		delete_option( 'leadin_portalId' );
		wp_die( '{"message": "Success!"}' );
	} else {
		wp_die( '{"error": "No leadin_portalId found, cannot disconnect."}', '', 400 );
	}
}
