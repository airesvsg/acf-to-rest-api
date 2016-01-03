<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Term_Controller' ) ) {
	class ACF_To_REST_API_Term_Controller extends ACF_To_REST_API_Controller {
		public function register_routes() {
			register_rest_route( 'acf/v2', "/{$this->type}/(?P<taxonomy>[\w\-\_]+)/(?P<id>\d+)", array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
				),
			) );
		}

		public function get_item( $request ) {
			if ( self::show( $request ) ) {
				return parent::get_item( $request );
			}

			return new WP_Error( 'rest_no_route', __( 'No route was found matching the URL and request method', 'acf-to-rest-api' ), array( 'status' => 404 ) );
		}

		protected static function show( $object ) {
			global $wp_taxonomies;
			
			if ( $object instanceof WP_REST_Request ) {
				$taxonomy = $object->get_param( 'taxonomy' );				
			} else {
				$taxonomy = false;
			}
			
			return $taxonomy && isset( $wp_taxonomies[$taxonomy]->show_in_rest ) && $wp_taxonomies[$taxonomy]->show_in_rest;
		}
	}
}
