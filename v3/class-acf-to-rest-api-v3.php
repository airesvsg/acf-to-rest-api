<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_V3' ) ) {
	class ACF_To_REST_API_V3 {
		public static function includes() {
			require_once dirname( __FILE__ ) . '/lib/class-acf-to-rest-api-acf-api.php';
			require_once dirname( __FILE__ ) . '/lib/class-acf-to-rest-api-acf-field-settings.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-posts-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-terms-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-comments-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-attachments-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-options-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-users-controller.php';
		}

		public static function create_rest_routes() {
			foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $post_type ) {
				if ( 'attachment' == $post_type->name ) {
					$controller = new ACF_To_REST_API_Attachments_Controller( $post_type );
				} else {
					$controller = new ACF_To_REST_API_Posts_Controller( $post_type );
				}
				$controller->register();
			}

			foreach ( get_taxonomies( array( 'show_in_rest' => true ), 'objects' ) as $taxonomy ) {
				$controller = new ACF_To_REST_API_Terms_Controller( $taxonomy );
				$controller->register();
			}

			$controller = new ACF_To_REST_API_Comments_Controller;
			$controller->register();

			$controller = new ACF_To_REST_API_Options_Controller;
			$controller->register();

			$controller = new ACF_To_REST_API_Users_Controller;
			$controller->register();
		}

		public static function missing_notice() {
			if ( ! ACF_To_REST_API::is_plugin_active( 'rest-api' ) ) {
				include dirname( __FILE__ ) . '/../shared/includes/admin/views/html-notice-missing-rest-api.php';
			}

			if ( ! ACF_To_REST_API::is_plugin_active( 'acf' ) ) {
				include dirname( __FILE__ ) . '/../shared/includes/admin/views/html-notice-missing-acf.php';
			}
		}
	}
}
