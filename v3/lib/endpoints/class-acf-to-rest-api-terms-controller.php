<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Terms_Controller' ) ) {
	class ACF_To_REST_API_Terms_Controller extends ACF_To_REST_API_Controller {
		public function __construct( $type ) {
			$this->type      = $type->name;
			$this->rest_base = ! empty( $type->rest_base ) ? $type->rest_base : $type->name;
			parent::__construct( $type );
		}

		public function get_items( $request ) {
			$this->controller = new WP_REST_Terms_Controller( $this->type );
			return parent::get_items( $request );
		}
	}
}
