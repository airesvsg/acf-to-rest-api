<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Comments_Controller' ) ) {
	class ACF_To_REST_API_Comments_Controller extends ACF_To_REST_API_Controller {
		public function __construct() {
			$this->type      = 'comment';
			$this->rest_base = 'comments';
			parent::__construct();
		}

		public function get_items( $request ) {
			$this->controller = new WP_REST_Comments_Controller;
			return parent::get_items( $request );
		}
	}
}
