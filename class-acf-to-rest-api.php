<?php
/**
 * Plugin Name: ACF to REST API
 * Description: Exposes Advanced Custom Fields Endpoints in the WordPress REST API
 * Author: Aires Gonçalves
 * Author URI: http://github.com/airesvsg
 * Version: 3.3.3
 * Plugin URI: http://github.com/airesvsg/acf-to-rest-api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API' ) ) {

	class ACF_To_REST_API {

		const VERSION = '3.3.3';

		private static $old_request_version     = 2;
		private static $default_request_version = 3;
		private static $request_version;

		private static $instance = null;

		public static function init() {
			self::includes();
			self::hooks();
		}

		protected static function instance() {
			if ( is_null( self::$instance ) ) {
				$class = 'ACF_To_REST_API_V' . self::handle_request_version();
				if ( class_exists( $class ) ) {
					self::$instance = new $class;
				}
			}
			return self::$instance;
		}

		private static function includes() {
			if ( self::$old_request_version == self::handle_request_version() ) {
				require_once dirname( __FILE__ ) . '/legacy/v2/class-acf-to-rest-api-v2.php';
			} else {
				require_once dirname( __FILE__ ) . '/v3/class-acf-to-rest-api-v3.php';
			}

			if ( self::is_plugin_active( 'all' ) ) {
				if ( is_admin() ) {
					require_once dirname( __FILE__ ) . '/shared/includes/admin/classes/class-acf-to-rest-api-settings.php';
					require_once dirname( __FILE__ ) . '/shared/includes/admin/classes/class-acf-to-rest-api-donation.php';
				}
				self::instance()->includes();
			}
		}

		public static function handle_request_version() {
			if ( is_null( self::$request_version ) ) {
				if ( defined( 'ACF_TO_REST_API_REQUEST_VERSION' ) ) {
					self::$request_version = (int) ACF_TO_REST_API_REQUEST_VERSION;
				} else {
					self::$request_version = (int) get_option( 'acf_to_rest_api_request_version', self::$default_request_version );
				}
			}
			return self::$request_version;
		}

		private static function hooks() {
			$acf_plugin_version = get_option( 'acf_version' );
			$hook_type = $acf_plugin_version >= '5.12' ? 'rest_pre_dispatch' : 'rest_api_init';

			add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ) );
			if ( self::is_plugin_active( 'all' ) ) {
				add_action( $hook_type, array( __CLASS__, 'create_rest_routes' ), 10 );
				if ( self::$default_request_version == self::handle_request_version() ) {
					ACF_To_REST_API_ACF_Field_Settings::hooks();
				}
			} else {
				add_action( 'admin_notices', array( __CLASS__, 'missing_notice' ) );
			}
		}

		public static function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'acf-to-rest-api' );
			load_textdomain( 'acf-to-rest-api', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/languages/' . $locale . '.mo' );
		}

		public static function create_rest_routes() {
			self::instance()->create_rest_routes();
		}

		public static function is_plugin_active( $plugin ) {
			if ( 'rest-api' == $plugin ) {
				return class_exists( 'WP_REST_Controller' );
			} elseif ( 'acf' == $plugin ) {
				return class_exists( 'acf' );
			} elseif ( 'all' == $plugin ) {
				return class_exists( 'WP_REST_Controller' ) && class_exists( 'acf' );
			}

			return false;
		}

		public static function is_plugin_installed( $plugin ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$paths = false;
			if ( 'rest-api' == $plugin ) {
				$paths = array( 'rest-api/plugin.php' );
			} elseif ( 'acf' == $plugin ) {
				$paths = array( 'advanced-custom-fields-pro/acf.php', 'acf-pro/acf.php', 'advanced-custom-fields/acf.php' );
			}

			if ( $paths ) {
				$plugins = get_plugins();
				if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
					foreach ( $paths as $path ) {
						if ( isset( $plugins[ $path ] ) && ! empty( $plugins[ $path ] ) ) {
							return $path;
						}
					}
				}
			}

			return false;
		}

		public static function missing_notice() {
			self::instance()->missing_notice();
		}
	}

	add_action( 'plugins_loaded', array( 'ACF_To_REST_API', 'init' ) );

}
