<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Settings' ) ) {

	class ACF_To_REST_API_Settings {

		private static $donation_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=airesvsg%40gmail%2ecom&lc=BR&item_name=Aires%20Goncalves&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest';
		private static $github_url   = 'http://github.com/airesvsg/acf-to-rest-api';

		public static function init() {
			self::hooks();
			self::save();
		}

		private static function hooks() {
			if ( ACF_To_REST_API::is_plugin_active( 'all' ) ) {
				if ( current_user_can( 'manage_options' ) && ! defined( 'ACF_TO_REST_API_REQUEST_VERSION' ) ) {
					add_action( 'admin_init', array( __CLASS__, 'acf_admin_setting' ) );
					add_filter( 'plugin_action_links_acf-to-rest-api/class-acf-to-rest-api.php', array( __CLASS__, 'plugin_action_links' ) );
				}

				add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 3 );
			}
		}

		public static function add_settings_section() {
			include_once dirname( __FILE__ ) . '/../includes/admin/views/html-settings-section.php';
		}

		public static function add_settings_field( $args ) {
			$request_version = ACF_To_REST_API::handle_request_version();

			include_once dirname( __FILE__ ) . '/../includes/admin/views/html-settings-field.php';
		}

		public static function plugin_action_links( $actions ) {
			if ( ! empty( $actions ) ) {
				$new_actions = array(
					'acf_to_rest_api_settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'options-permalink.php#acf-to-rest-api-settings' ), esc_html__( 'Settings', 'acf-to-rest-api' ) ),
				);

				$new_actions += $actions;

				return $new_actions;
			}

			return $actions;
		}

		public static function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data ) {
			if ( isset( $plugin_data['slug'] ) && 'acf-to-rest-api' == $plugin_data['slug'] ) {
				$plugin_meta['acf-to-rest-api-github'] = sprintf( '<a href="%s" target="_blank">%s</a>', self::$github_url, esc_html__( 'Fork me on GitHub' ) );
				$plugin_meta['acf_to_rest_api_donation'] = sprintf( '<a href="%s" target="_blank">%s</a>', self::$donation_url, esc_html__( 'Make a donation', 'acf-to-rest-api' ) );
			}

			return $plugin_meta;
		}

		public static function acf_admin_setting() {
			add_settings_section(
				'acf_to_rest_api_settings_section',
				__( 'ACF to REST API', 'acf-to-rest-api' ),
				array( __CLASS__, 'add_settings_section' ),
				'permalink'
			);

			add_settings_field(
				'acf_to_rest_api_request_version',
				__( 'Request Version', 'acf-to-rest-api' ),
				array( __CLASS__, 'add_settings_field' ),
				'permalink',
				'acf_to_rest_api_settings_section'
			);
		}

		private static function save() {
			if ( ! is_admin() ) {
				return;
			}

			if ( isset( $_POST['acf_to_rest_api_settings'] ) ) {
				$settings = $_POST['acf_to_rest_api_settings'];
				if ( array_key_exists( 'request_version', $settings ) ) {
					update_option( 'acf_to_rest_api_request_version', absint( $settings['request_version'] ) );
				}
			}
		}

	}

	ACF_To_REST_API_Settings::init();

}
