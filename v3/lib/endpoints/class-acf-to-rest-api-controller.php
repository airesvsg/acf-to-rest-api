<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Controller' ) ) {
	class ACF_To_REST_API_Controller extends WP_REST_Controller {
		protected $acf        = null;
		protected $type       = null;
		protected $controller = null;

		protected static $default_params = array(
			'page'     => 1,
			'per_page' => 10,
			'orderby'  => 'id',
		);

		public function __construct( $type = null ) {
			$this->namespace = 'acf/v3';
			$this->acf = new ACF_To_REST_API_ACF_API( $this->type, get_class( $this ) );
		}

		public function register_hooks() {
			add_action( 'rest_insert_' . $this->type, array( $this, 'rest_insert' ), 10, 3 );
		}

		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/?(?P<field>[\w\-\_]+)?', array(
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

			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			) );
		}

		public function register_field() {
			register_rest_field( $this->type, 'acf', array(
				'get_callback' => array( $this, 'register_field_callback' ),
				'schema' => array(
					'description' => __( 'Expose advanced custom fields.', 'acf-to-rest-api' ),
					'type'        => 'object',
				),
			) );
		}

		public function register_field_callback( $data ) {
			$fields = $this->acf->get_fields( $data );
			return $fields['acf'];
		}

		public function register() {
			$this->register_routes();
			$this->register_hooks();
			$this->register_field();
		}

		public function get_item( $request ) {
			$fields = $this->acf->get_fields( $request );
			return rest_ensure_response( $fields );
		}

		public function get_item_permissions_check( $request ) {
			return apply_filters( 'acf/rest_api/item_permissions/get', true, $request, $this->type );
		}

		public function get_items( $request ) {
			if ( ! method_exists( $this->controller, 'get_items' ) ) {
				return new WP_Error( 'cant_get_items', __( 'Cannot get items', 'acf-to-rest-api' ), array( 'status' => 500 ) );
			}

			$this->set_default_parameters( $request );
			$data = $this->controller->get_items( $request )->get_data();

			$response = array();
			if ( ! empty( $data ) ) {
				foreach ( $data as $v ) {
					if ( isset( $v['acf'] ) ) {
						$response[] = array(
							'id'  => $v['id'],
							'acf' => $v['acf'],
						);
					}
				}
			}

			return apply_filters( 'acf/rest_api/' . $this->type . '/get_items', rest_ensure_response( $response ), rest_ensure_request( $request ) );
		}

		public function get_items_permissions_check( $request ) {
			return apply_filters( 'acf/rest_api/items_permissions/get', true, $request, $this->type );
		}

		public function update_item_permissions_check( $request ) {
			return apply_filters( 'acf/rest_api/item_permissions/update', current_user_can( 'edit_posts' ), $request, $this->type );
		}

		public function update_item( $request ) {
			$item = $this->prepare_item_for_database( $request );
			if ( is_array( $item ) && count( $item ) > 0 ) {
				foreach ( $item['data'] as $key => $value ) {
					if ( isset( $item['fields'][ $key ]['key'] ) ) {
						$field = $item['fields'][ $key ];
						$edit  = $this->acf->edit_in_rest( $field );
						if ( $edit ) {
							if ( function_exists( 'acf_update_value' ) ) {
								acf_update_value( $value, $item['id'], $field );
							} elseif ( function_exists( 'update_field' ) ) {
								update_field( $field['key'], $value, $item['id'] );
							} else {
								do_action( 'acf/update_value', $value, $item['id'], $field );
							}							
						}
					}
				}

				return new WP_REST_Response( $this->acf->get_fields( $request ), 200 );
			}

			return new WP_Error( 'cant_update_item', __( 'Cannot update item', 'acf-to-rest-api' ), array( 'status' => 500 ) );
		}

		public function rest_insert( $object, $request, $creating ) {
			if ( $request instanceof WP_REST_Request ) {
				$id = $this->acf->get_id( $object );				
				if ( ! $id ) {
					$id = $this->acf->get_id( $request );
				}
				$request->set_param( 'id', $id );
			}
			
			return $this->update_item( $request );
		}

		public function prepare_item_for_database( $request ) {
			$item = false;
			if ( $request instanceof WP_REST_Request ) {
				$key = apply_filters( 'acf/rest_api/key', 'fields', $request, $this->type );
				if ( is_string( $key ) && ! empty( $key ) ) {
					$data  = $request->get_param( $key );
					$field = $request->get_param( 'field' );
					$id    = $this->acf->get_id( $request );
					if ( $id && is_array( $data ) ) {
						$fields = $this->acf->get_field_objects( $id );
						if ( is_array( $fields ) && ! empty( $fields ) ) {
							if ( $field && isset( $data[ $field ] ) ) {
								$data = array( $field => $data[ $field ] );
							}
							$item = array(
								'id'     => $id,
								'fields' => $fields,
								'data'   => $data,
							);
						}
					}
				}
			}
			return apply_filters( 'acf/rest_api/' . $this->type . '/prepare_item', $item, $request );
		}

		protected function set_default_parameters( &$request ) {
			if ( $request instanceof WP_REST_Request ) {
				$params = $request->get_params();
				foreach ( self::$default_params as $k => $v ) {
					if ( ! isset( $params[ $k ] ) ) {
						$request->set_param( $k, $v );
					}
				}
			}
		}
	}
}
