<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Term_Controller' ) ) {
	class ACF_To_REST_API_Term_Controller extends ACF_To_REST_API_Controller {
		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->type . '/(?P<taxonomy>[\w\-\_]+)/(?P<id>[\d]+)/?(?P<field>[\w\-\_]+)?', array(
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
			if ( $this->show( $request ) ) {
				return parent::get_item( $request );
			}

			return new WP_Error( 'rest_no_route', __( 'No route was found matching the URL and request method', 'acf-to-rest-api' ), array( 'status' => 404 ) );
		}

		protected function get_rest_base( $request ) {
			global $wp_taxonomies;

			$taxonomy = false;
			if ( $request instanceof WP_REST_Request ) {
				$taxonomy = $request->get_param( 'taxonomy' );
			}

			if ( $taxonomy && ! array_key_exists( $taxonomy, $wp_taxonomies ) ) {
				foreach ( $wp_taxonomies as $tax_key => $tax_value ) {
					if ( isset( $tax_value->rest_base ) && $taxonomy == $tax_value->rest_base ) {
						$request->set_param( 'taxonomy', $tax_key );
						return $tax_key;
					}
				}
			}

			return $taxonomy;
		}

		protected function show( $object ) {
			global $wp_taxonomies;	
			$taxonomy = $this->get_rest_base( $object );
			return $taxonomy && isset( $wp_taxonomies[ $taxonomy ]->show_in_rest ) && $wp_taxonomies[ $taxonomy ]->show_in_rest;
		}
	}
}
