<?php
/**
 * Plugin Name: WPC Show Single Variations for WooCommerce
 * Plugin URI: https://wpclever.net/
 * Description: WPC Show Single Variations helps you show all variations as single products on the archive pages.
 * Version: 2.4.6
 * Author: WPClever
 * Author URI: https://wpclever.net
 * Text Domain: wpc-show-single-variations
 * Domain Path: /languages/
 * Requires Plugins: woocommerce
 * Requires at least: 4.0
 * Tested up to: 6.9
 * WC requires at least: 3.0
 * WC tested up to: 10.4
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 **/

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverWoosv' ) && class_exists( 'WC_Product' ) ) {
	class WPCleverWoosv {
		public function __construct() {
			$this->define_constants();
			$this->include_library();
			$this->admin_hooks();
			$this->public_hooks();
		}

		private function define_constants() {
			! defined( 'WOOSV_VERSION' ) && define( 'WOOSV_VERSION', '2.4.6' );
			! defined( 'WOOSV_LITE' ) && define( 'WOOSV_LITE', __FILE__ );
			! defined( 'WOOSV_FILE' ) && define( 'WOOSV_FILE', __FILE__ );
			! defined( 'WOOSV_URI' ) && define( 'WOOSV_URI', plugin_dir_url( __FILE__ ) );
			! defined( 'WOOSV_DIR' ) && define( 'WOOSV_DIR', plugin_dir_path( __FILE__ ) );
			! defined( 'WOOSV_REVIEWS' ) && define( 'WOOSV_REVIEWS', 'https://wordpress.org/support/plugin/wpc-show-single-variations/reviews/' );
			! defined( 'WOOSV_CHANGELOG' ) && define( 'WOOSV_CHANGELOG', 'https://wordpress.org/plugins/wpc-show-single-variations/#developers' );
			! defined( 'WOOSV_DISCUSSION' ) && define( 'WOOSV_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-show-single-variations' );
			! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOSV_URI );
		}

		private function include_library() {
			include 'includes/dashboard/wpc-dashboard.php';
			include 'includes/kit/wpc-kit.php';
			include 'includes/hpos.php';
			require_once 'includes/class-helper.php';
			require_once 'includes/class-admin.php';
			require_once 'includes/class-public.php';
		}

		private function admin_hooks() {
			$woosv_admin = Woosv_Admin::instance();
			add_action( 'init', [ $woosv_admin, 'init' ] );
			add_action( 'admin_enqueue_scripts', [ $woosv_admin, 'admin_enqueue_scripts' ] );
			add_action( 'admin_menu', [ $woosv_admin, 'admin_menu' ] );
			add_action( 'admin_init', [ $woosv_admin, 'register_settings' ] );
			add_filter( 'plugin_action_links', [ $woosv_admin, 'action_links' ], 10, 2 );
			add_filter( 'plugin_row_meta', [ $woosv_admin, 'row_meta' ], 10, 2 );
			add_action( 'woocommerce_product_after_variable_attributes', [ $woosv_admin, 'add_fields' ], 10, 3 );
			add_action( 'woocommerce_save_product_variation', [ $woosv_admin, 'save_fields' ], 10, 2 );

			// WPC Variation Duplicator
			add_action( 'wpcvd_duplicated', [ $woosv_admin, 'duplicate_variation' ], 99, 2 );

			// WPC Variation Bulk Editor
			add_action( 'wpcvb_bulk_update_variation', [ $woosv_admin, 'bulk_update_variation' ], 99, 2 );
		}

		private function public_hooks() {
			$woosv_public = Woosv_Public::instance();
			add_action( 'woocommerce_product_query', [ $woosv_public, 'product_query' ], 999 );
			add_filter( 'woocommerce_shortcode_products_query', [ $woosv_public, 'shortcode_products_query' ], 999 );
			add_filter( 'posts_clauses', [ $woosv_public, 'posts_clauses' ], 999, 2 );
			add_filter( 'woocommerce_product_variation_get_name', [ $woosv_public, 'variation_get_name' ], 999, 2 );
			add_filter( 'the_title', [ $woosv_public, 'the_title' ], 999, 2 );
		}
	}

	new WPCleverWoosv();
}
