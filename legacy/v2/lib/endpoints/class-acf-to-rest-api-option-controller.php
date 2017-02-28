<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Option_Controller' ) ) {
	class ACF_To_REST_API_Option_Controller extends ACF_To_REST_API_Controller {
		public function __construct( $type ) {
			parent::__construct( $type );
			$this->rest_base = 'options';
		}

		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/?(?P<field>[\w\-\_]+)?', array(
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
	}
}
