<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Attachment_Controller' ) ) {
	class ACF_To_REST_API_Attachment_Controller extends ACF_To_REST_API_Controller {
		public function register_hooks() {
			$this->type = 'attachment';
			parent::register_hooks();
		}
	}
}
