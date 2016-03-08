<?php
/**
 * Plugin Name: ACF to REST API
 * Description: Edit, Get and Puts ACF fields in WordPress REST API.
 * Author: Aires Gonçalves
 * Author URI: http://github.com/airesvsg
 * Version: 2.0.7
 * Plugin URI: http://github.com/airesvsg/acf-to-rest-api
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API' ) ) {

	class ACF_To_REST_API {

		const VERSION = '2.0.7';

		public static function init() {
			self::includes();
			self::hooks();
		}

		private static function includes() {
			if ( self::is_plugin_active( 'all' ) ) {
				require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-controller.php';
				require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-option-controller.php';
				require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-term-controller.php';
				require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-attachment-controller.php';
			}
		}

		private static function hooks() {
			add_action( 'init', array( __CLASS__, 'load_plugin_textdomain' ) );
			if ( self::is_plugin_active( 'all' ) ) {
				add_action( 'rest_api_init', array( __CLASS__, 'create_rest_routes' ), 10 );
			} else {
				add_action( 'admin_notices', array( __CLASS__, 'missing_notice' ) );
			}
		}

		public static function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'acf-to-rest-api' );
			load_textdomain( 'acf-to-rest-api', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/languages/' . $locale . '.mo' );
		}

		public static function create_rest_routes() {
			$default = array( 'user', 'comment', 'term', 'option' );
			$types   = get_post_types( array( 'show_in_rest' => true ) );
			
			if ( $types && isset( $types['attachment'] ) ) {
				unset( $types['attachment'] );
				$default[] = 'media';
			}

			$types = apply_filters( 'acf/rest_api/types', array_merge( $types, array_combine( $default, $default ) ) );
			
			if ( is_array( $types ) && count( $types ) > 0 ) {
				foreach( $types as $type ) {
					if ( 'term' == $type ) {
						$controller = new ACF_To_REST_API_Term_Controller( $type );						
					} elseif ( 'media' == $type ) {
						$controller = new ACF_To_REST_API_Attachment_Controller( $type );
					} elseif ( 'option' == $type ) {
						$controller = new ACF_To_REST_API_Option_Controller( $type );
					} else {
						$controller = new ACF_To_REST_API_Controller( $type );
					}

					$controller->register_routes();
					$controller->register_hooks();	
				}
			}
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
						if ( isset( $plugins[$path] ) && ! empty( $plugins[$path] ) ) {
							return $path;
						}
					}					
				}
			}

			return false;
		}

		public static function missing_notice() {
			if ( ! self::is_plugin_active( 'rest-api' ) ) {
				include dirname( __FILE__ ) . '/includes/admin/views/html-notice-missing-rest-api.php';
			}

			if ( ! self::is_plugin_active( 'acf' ) ) {
				include dirname( __FILE__ ) . '/includes/admin/views/html-notice-missing-acf.php';				
			}
		}
	}

	add_action( 'plugins_loaded', array( 'ACF_To_REST_API', 'init' ) );

}