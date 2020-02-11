<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Donation' ) ) {

	class ACF_To_REST_API_Donation {

		const DONATION_URL = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=E5M7HDWNPFVF4&lc=BR&item_name=Aires%20Goncalves&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest';

		private static $version_meta = 'acf_to_rest_api_donation_version';

		private static $action_nonce = 'acf-to-rest-api-ajax-nonce';

		public static function init() {
			self::hooks();
		}

		private static function hooks() {
			if ( self::show() ) {
				add_action( 'admin_notices', array( __CLASS__, 'donation_notice' ) );
				add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
				add_action( 'wp_ajax_acf_to_rest_api_dismiss_notice', array( __CLASS__, 'dismiss_notice' ) );
			}
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_styles' ) );
		}

		public static function admin_enqueue_styles() {
			wp_enqueue_style( 'acf_to_rest_api_donation', plugins_url( 'assets/css/acf-to-rest-api-donation.css', dirname( __FILE__ ) . '/../../../../' ), array(), ACF_To_REST_API::VERSION );
		}

		public static function admin_enqueue_scripts() {
			wp_enqueue_script( 'acf_to_rest_api_donation', plugins_url( 'assets/js/acf-to-rest-api-donation.js', dirname( __FILE__ ) . '/../../../../' ), array( 'jquery' ), ACF_To_REST_API::VERSION, true );
			wp_localize_script( 'acf_to_rest_api_donation', 'acf_to_rest_api_donation', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( self::$action_nonce ),
			) );
		}

		public static function donation_notice() {
			include dirname( __FILE__ ) . '/../views/html-notice-donation.php';
		}

		public static function dismiss_notice() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], self::$action_nonce ) ) {
				wp_die();
			}
			
			$user_id = get_current_user_id();
			if ( $user_id ) {
				update_user_meta( $user_id, self::$version_meta, ACF_To_REST_API::VERSION );
			}

			exit;
		}

		private static function show() {
			$version = null;
			$user_id = get_current_user_id();
			
			if ( $user_id ) {
				$version = get_user_meta( $user_id, self::$version_meta, true );
			}
			
			return is_admin() && $version !== ACF_To_REST_API::VERSION;
		}

	}

	add_action( 'admin_init', array( 'ACF_To_REST_API_Donation', 'init' ) );

}
