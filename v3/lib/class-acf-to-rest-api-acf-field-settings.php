<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_ACF_Field_Settings' ) ) {
	class ACF_To_REST_API_ACF_Field_Settings {
		private function __construct() {}

		public static function hooks() {
			if ( function_exists( 'acf_render_field_setting' ) ) {
				add_action( 'acf/render_field_settings', array( __CLASS__, 'render_field_settings' ) );
			} else {
				add_action( 'acf/create_field_options', array( __CLASS__, 'render_field_settings' ) );
			}
		}

		public static function render_field_settings( $field ) {
			if ( apply_filters( 'acf/rest_api/field_settings/show_in_rest', false ) ) {
				self::show_in_rest( $field );
			}

			if ( apply_filters( 'acf/rest_api/field_settings/edit_in_rest', false ) ) {
				self::edit_in_rest( $field );
			}
		}

		private static function edit_in_rest( $field ) {
			if ( function_exists( 'acf_render_field_setting' ) ) {
				acf_render_field_setting( $field, array(
					'label'         => __( 'Edit in REST API?', 'acf-to-rest-api' ),
					'instructions'  => '',
					'type'          => 'true_false',
					'name'          => 'edit_in_rest',
					'ui'            => 1,
					'class'         => 'field-edit_in_rest',
					'default_value' => 0,
				), true );
			} else { ?>
				<tr>
					<td class="label">
						<label><?php esc_html_e( 'Edit in REST API?', 'acf-to-rest-api' ); ?></label>
					</td>
					<td>
					<?php
					if ( ! isset( $field['edit_in_rest'] ) ) {
						$field['edit_in_rest'] = 0;
					}
					do_action( 'acf/create_field', array(
						'type'          => 'radio',
						'name'          => 'fields[' . $field['name'] . '][edit_in_rest]',
						'value'         => $field['edit_in_rest'],
						'layout'        => 'horizontal',
						'choices'       => array(
							1 => __( 'Yes', 'acf-to-rest-api' ),
							0 => __( 'No', 'acf-to-rest-api' ),
						),
					) ); ?>
					</td>
				</tr>
			<?php
			}
		}

		private static function show_in_rest( $field ) {
			if ( function_exists( 'acf_render_field_setting' ) ) {
				acf_render_field_setting( $field, array(
					'label'         => __( 'Show in REST API?', 'acf-to-rest-api' ),
					'instructions'  => '',
					'type'          => 'true_false',
					'name'          => 'show_in_rest',
					'ui'            => 1,
					'class'         => 'field-show_in_rest',
					'default_value' => 0,
				), true );
			} else { ?>
				<tr>
					<td class="label">
						<label><?php esc_html_e( 'Show in REST API?', 'acf-to-rest-api' ); ?></label>
					</td>
					<td>
					<?php
					if ( ! isset( $field['show_in_rest'] ) ) {
						$field['show_in_rest'] = 0;
					}
					do_action( 'acf/create_field', array(
						'type'          => 'radio',
						'name'          => 'fields[' . $field['name'] . '][show_in_rest]',
						'value'         => $field['show_in_rest'],
						'layout'        => 'horizontal',
						'choices'       => array(
							1 => __( 'Yes', 'acf-to-rest-api' ),
							0 => __( 'No', 'acf-to-rest-api' ),
						),
					) ); ?>
					</td>
				</tr>
			<?php
			}
		}
	}
}
