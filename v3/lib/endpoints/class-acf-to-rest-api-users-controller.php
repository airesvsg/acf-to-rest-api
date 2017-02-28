<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Users_Controller' ) ) {
	class ACF_To_REST_API_Users_Controller extends ACF_To_REST_API_Controller {
		public function __construct() {
			$this->type      = 'user';
			$this->rest_base = 'users';
			parent::__construct();
		}

		public function get_items( $request ) {
			$this->controller = new WP_REST_Users_Controller;
			return parent::get_items( $request );
		}
	}
}
