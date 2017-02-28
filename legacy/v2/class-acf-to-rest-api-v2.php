<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_V2' ) ) {

	class ACF_To_REST_API_V2 {

		public static function includes() {
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-option-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-term-controller.php';
			require_once dirname( __FILE__ ) . '/lib/endpoints/class-acf-to-rest-api-attachment-controller.php';
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
				foreach ( $types as $type ) {
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

		public static function missing_notice() {
			if ( ! ACF_To_REST_API::is_plugin_active( 'rest-api' ) ) {
				include dirname( __FILE__ ) . '/../../shared/includes/admin/views/html-notice-missing-rest-api.php';
			}
			if ( ! ACF_To_REST_API::is_plugin_active( 'acf' ) ) {
				include dirname( __FILE__ ) . '/../../shared/includes/admin/views/html-notice-missing-acf.php';
			}
		}

	}
}
