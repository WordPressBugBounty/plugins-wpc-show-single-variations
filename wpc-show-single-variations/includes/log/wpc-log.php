<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WOOSV_LITE' ) ? WOOSV_LITE : WOOSV_FILE, 'woosv_activate' );
register_deactivation_hook( defined( 'WOOSV_LITE' ) ? WOOSV_LITE : WOOSV_FILE, 'woosv_deactivate' );
add_action( 'admin_init', 'woosv_check_version' );

function woosv_check_version() {
	if ( ! empty( get_option( 'woosv_version' ) ) && ( get_option( 'woosv_version' ) < WOOSV_VERSION ) ) {
		wpc_log( 'woosv', 'upgraded' );
		update_option( 'woosv_version', WOOSV_VERSION, false );
	}
}

function woosv_activate() {
	wpc_log( 'woosv', 'installed' );
	update_option( 'woosv_version', WOOSV_VERSION, false );
}

function woosv_deactivate() {
	wpc_log( 'woosv', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}