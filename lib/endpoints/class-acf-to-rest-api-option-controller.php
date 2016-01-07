<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Option_Controller' ) ) {
	class ACF_To_REST_API_Option_Controller extends ACF_To_REST_API_Controller {
		public function register_routes() {
			register_rest_route( 'acf/v2', "/options/?", array(
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

			register_rest_route( 'acf/v2', "/options/(?P<name>[\w\-\_]+)/?", array(
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

		public function prepare_item_for_database( $request ) {
			$item = parent::prepare_item_for_database( $request );
			
			if ( $item && $request instanceof WP_REST_Request ) {
				$name = $request->get_param( 'name' );
				if ( $name && array_key_exists( $name, $item['data'] ) ) {
					$item['data'] = array( $name => $item['data'][$name] );
				}
			}
			
			return $item;
		}

		public function get_fields( $request, $response = null, $object = null ) {
			if ( $request instanceof WP_REST_Request ) {
				$name = $request->get_param( 'name' );
				if ( $name ) {
					$value = get_field( $name, $this->type );
					$data  = array( $name => $value );
					return apply_filters( "acf/rest_api/{$this->type}/get_fields", $data, $request, $response, $object );					
				}
			}

			return parent::get_fields( $request, $response, $object );
		}
	}
}
