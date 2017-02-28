<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_Controller' ) ) {
	class ACF_To_REST_API_Controller extends WP_REST_Controller {

		protected $type;

		protected $id;

		public function __construct( $type ) {
			$this->type = apply_filters( 'acf/rest_api/type', $type );
			$this->namespace = 'acf/v2';
			$this->rest_base = $this->get_rest_base( $this->type );
		}

		public function register_hooks() {
			if ( $this->type ) {
				add_filter( 'rest_prepare_' . $this->type, array( $this, 'rest_prepare' ), 10, 3 );
				add_action( 'rest_insert_' . $this->type, array( $this, 'rest_insert' ), 10, 3 );
			}
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
		}

		protected function get_rest_base( $type ) {
			global $wp_post_types;

			$default = apply_filters( 'acf/rest_api/default_rest_base', ! in_array( $type, array( 'post', 'page' ) ), $type );

			if ( $default && isset( $wp_post_types[ $type ] ) && isset( $wp_post_types[ $type ]->rest_base ) ) {
				return $wp_post_types[ $type ]->rest_base;
			}

			return $type;
		}

		public function get_item( $request ) {
			return $this->get_fields( $request );
		}

		public function get_item_permissions_check( $request ) {
			return apply_filters( 'acf/rest_api/item_permissions/get', true, $request, $this->type );
		}

		public function rest_prepare( $response, $post, $request ) {
			return $this->get_fields( $request, $response, $post );
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
						if ( function_exists( 'acf_update_value' ) ) {
							acf_update_value( $value, $item['id'], $field );
						} elseif ( function_exists( 'update_field' ) ) {
							update_field( $field['key'], $value, $item['id'] );
						} else {
							do_action( 'acf/update_value', $value, $item['id'], $field );
						}
					}
				}

				return new WP_REST_Response( $this->get_fields( $request ), 200 );
			}

			return new WP_Error( 'cant_update_item', __( 'Cannot update item', 'acf-to-rest-api' ), array( 'status' => 500 ) );
		}

		public function rest_insert( $object, $request, $creating ) {
			if ( $request instanceof WP_REST_Request && ! $this->get_id( $request ) && $this->get_id( $object ) ) {
				$request->set_param( 'id', $this->id );
			}

			return $this->update_item( $request );
		}

		public function prepare_item_for_database( $request ) {
			$item = false;

			if ( $request instanceof WP_REST_Request ) {
				$key  = apply_filters( 'acf/rest_api/key', 'fields', $request, $this->type );

				if ( is_string( $key ) && ! empty( $key ) ) {
					$data  = $request->get_param( $key );
					$field = $request->get_param( 'field' );

					$this->format_id( $request );

					if ( $this->id && is_array( $data ) ) {
						$fields = $this->get_field_objects( $this->id );

						if ( is_array( $fields ) && ! empty( $fields ) ) {
							if ( $field && isset( $data[ $field ] ) ) {
								$data = array( $field => $data[ $field ] );
							}

							$item = array(
								'id'     => $this->id,
								'fields' => $fields,
								'data'   => $data,
							);
						}
					}
				}
			}

			return apply_filters( 'acf/rest_api/' . $this->type . '/prepare_item', $item, $request );
		}

		protected function get_id( $object ) {
			$this->id = false;

			if ( is_numeric( $object ) ) {
				$this->id = $object;
			} elseif ( is_array( $object ) ) {
				$object = array_change_key_case( $object, CASE_UPPER );
				if ( array_key_exists( 'ID', $object ) ) {
					$this->id = $object['ID'];
				}
			} elseif ( is_object( $object ) ) {
				if ( $object instanceof WP_REST_Response ) {
					return $this->get_id( $object->get_data() );
				} elseif ( $object instanceof WP_REST_Request ) {
					$this->id = $object->get_param( 'id' );
				} elseif ( isset( $object->ID ) ) {
					$this->id = $object->ID;
				} elseif ( isset( $object->comment_ID ) ) {
					$this->id = $object->comment_ID;
				} elseif ( isset( $object->term_id ) ) {
					$this->id = $object->term_id;
				}
			}
			
			$this->id = absint( $this->id );

			return $this->id;
		}

		protected function format_id( $object ) {
			$this->get_id( $object );

			switch ( $this->type ) {
				case 'comment' :
					$this->id = 'comment_' . $this->id;
					break;
				case 'user' :
					$this->id = 'user_' . $this->id;
					break;
				case 'term' :
					if ( $object instanceof WP_Term ) {
						$taxonomy = $object->taxonomy;
					} elseif ( $object instanceof WP_REST_Request ) {
						$taxonomy = $object->get_param( 'taxonomy' );
					}
					$this->id = $taxonomy . '_' . $this->id;
					break;
				case 'option' :
					$this->id = 'options';
					break;
			}
			
			$this->id = apply_filters( 'acf/rest_api/id', $this->id );

			return $this->id;
		}

		protected function get_fields( $request, $response = null, $object = null ) {
			$data  = array();
			$field = null;
			$swap  = $response instanceof WP_REST_Response;

			if ( $request instanceof WP_REST_Request ) {
				$field = $request->get_param( 'field' );
			}
			
			if ( $swap ) {
				$data = $response->get_data();
			}

			if ( empty( $object ) ) {
				if ( ! empty( $request ) ) {
					$object = $request;
				} elseif ( ! empty( $data ) ) {
					$object = $response;
				}
			}

			$this->format_id( $object );

			if ( $this->id ) {
				if ( $field ) {
					$data = array( $field => get_field( $field, $this->id ) );
				} else {
					$data['acf'] = get_fields( $this->id );
				}
			} else {
				$data['acf'] = array();
			}

			if ( $swap ) {
				$response->data = $data;
				$data = $response;
			}

			return apply_filters( 'acf/rest_api/' . $this->type . '/get_fields', $data, $request, $response, $object );
		}

		protected function get_field_objects( $id ) {
			if ( empty( $id ) ) {
				return false;
			}

			$fields     = array();
			$fields_tmp = array();

			if ( function_exists( 'acf_get_field_groups' ) && function_exists( 'acf_get_fields' ) && function_exists( 'acf_extract_var' ) ) {
				$field_groups = acf_get_field_groups( array( 'post_id' => $id ) );

				if ( is_array( $field_groups ) && ! empty( $field_groups ) ) {
					foreach ( $field_groups as $field_group ) {
						$field_group_fields = acf_get_fields( $field_group );
						if ( is_array( $field_group_fields ) && ! empty( $field_group_fields ) ) {
							foreach ( array_keys( $field_group_fields ) as $i ) {
								$fields_tmp[] = acf_extract_var( $field_group_fields, $i );
							}
						}
					}
				}
			} else {
				if ( strpos( $id, 'user_' ) !== false ) {
					$filter = array( 'ef_user' => str_replace( 'user_', '', $id ) );
				} elseif ( strpos( $id, 'taxonomy_' ) !== false ) {
					$filter = array( 'ef_taxonomy' => str_replace( 'taxonomy_', '', $id ) );
				} else {
					$filter = array( 'post_id' => $id );
				}

				$field_groups = apply_filters( 'acf/location/match_field_groups', array(), $filter );
				$acfs = apply_filters( 'acf/get_field_groups', array() );

				if ( is_array( $acfs ) && ! empty( $acfs ) && is_array( $field_groups ) && ! empty( $field_groups ) ) {
					foreach ( $acfs as $acf ) {
						if ( in_array( $acf['id'], $field_groups ) ) {
							$fields_tmp = array_merge( $fields_tmp, apply_filters( 'acf/field_group/get_fields', array(), $acf['id'] ) );
						}
					}
				}
			}

			if ( is_array( $fields_tmp ) && ! empty( $fields_tmp ) ) {
				foreach ( $fields_tmp as $field ) {
					if ( is_array( $field ) && isset( $field['name'] ) ) {
						$fields[ $field['name'] ] = $field;
					}
				}
			}

			return $fields;
		}

	}
}
