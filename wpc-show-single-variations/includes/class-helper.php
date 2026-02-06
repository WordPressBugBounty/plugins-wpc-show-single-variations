<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woosv_Helper' ) ) {
	class Woosv_Helper {
		protected static $instance = null;
		protected static $settings = [];

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {
			self::$settings = (array) get_option( 'woosv_settings', [] );
		}

		public static function get_settings() {
			return apply_filters( 'woosv_get_settings', self::$settings );
		}

		public static function get_setting( $name, $default = false ) {
			if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
				$setting = self::$settings[ $name ];
			} else {
				$setting = get_option( 'woosv_' . $name, $default );
			}

			return apply_filters( 'woosv_get_setting', $setting, $name, $default );
		}

		public static function sanitize_array( $arr ) {
			foreach ( (array) $arr as $k => $v ) {
				if ( is_array( $v ) ) {
					$arr[ $k ] = self::sanitize_array( $v );
				} else {
					$arr[ $k ] = sanitize_post_field( 'post_content', $v, 0, 'db' );
				}
			}

			return $arr;
		}
	}

	return Woosv_Helper::instance();
}
