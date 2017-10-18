<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ACF_To_REST_API_ACF_API' ) ) {
	class ACF_To_REST_API_ACF_API {
		protected $id            = null;
		protected $type          = null;
		protected $controller    = null;
		protected $field_objects = null;

		public function __construct( $type, $controller = null ) {
			$this->type = $type;
			$this->controller = $controller;
		}

		protected function format_id() {
			if ( $this->id ) {
				switch ( $this->type ) {
					case 'comment' :
						$this->id = 'comment_' . $this->id;
						break;
					case 'user' :
						$this->id = 'user_' . $this->id;
						break;
					default :
						if ( 'ACF_To_REST_API_Terms_Controller' == $this->controller ) {
							$this->id = $this->type . '_' . $this->id;
						}
						break;
				}
			}

			$this->id = apply_filters( 'acf/rest_api/id', $this->id, $this->type, $this->controller );

			return $this->id;
		}

		public function get_id( $object ) {
			$this->id = false;

			if ( is_numeric( $object ) ) {
				$this->id = $object;
			} elseif ( is_array( $object ) && isset( $object['id'] ) ) {
				$this->id = $object['id'];
			} elseif ( is_object( $object ) ) {
				if ( $object instanceof WP_REST_Response ) {
					$data = $object->get_data();
					if ( isset( $data['id'] ) ) {
						$this->id = $data['id'];
					}
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

			if ( 'option' == $this->type ) {
				$this->id = sanitize_title( $this->id );
			} else {
				$this->id = absint( $this->id );
			}

			return $this->format_id();
		}

		public function get_fields( $request ) {
			$data  = array();
			$field = null;
			
			if ( $request instanceof WP_REST_Request ) {
				$field = $request->get_param( 'field' );
			}

			if ( $this->get_id( $request ) ) {
				if ( $field ) {
					$data[ $field ] = get_field( $field, $this->id );
				} else {
					$fields = get_fields( $this->id );
					if ( ! $fields ) {
						$this->get_field_objects( $this->id );
						$fields = $this->get_fields_fallback();
					}
					$data['acf'] = $fields;
				}

				if ( apply_filters( 'acf/rest_api/field_settings/show_in_rest', false ) && $this->get_field_objects( $this->id ) ) {
					if ( $field ) {
						$this->show_in_rest( $data, $field, $this->field_objects );
					} else {
						foreach ( array_keys( $data['acf'] ) as $key ) {
							$this->show_in_rest( $data['acf'], $key, $this->field_objects );
						}
					}
				}
			} else {
				$data['acf'] = array();
			}

			return apply_filters( 'acf/rest_api/' . $this->type . '/get_fields', $data, $request );
		}

		protected function get_fields_fallback() {
			$fields = array();

			if ( ! empty( $this->field_objects ) ) {
				foreach ( $this->field_objects as $objects ) {
					if( isset( $objects['name'] ) && ! empty( $objects['name'] ) ) {
						$fields[ $objects['name'] ] = get_field( $objects['name'], $this->id );
					}
				}
			}

			return $fields;
		}

		public function get_field_objects( $id ) {
			if ( empty( $id ) ) {
				return false;
			}

			$this->field_objects = false;
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
				$this->field_objects = array();
				foreach ( $fields_tmp as $field ) {
					if ( is_array( $field ) && isset( $field['name'] ) ) {
						$this->field_objects[ $field['name'] ] = $field;
					}
				}
			}

			return $this->field_objects;
		}

		public function show_in_rest( &$data, $field, $field_objects ) {
			if ( ! array_key_exists( $field, $field_objects ) || ! isset( $field_objects[ $field ]['show_in_rest'] ) || ! $field_objects[ $field ]['show_in_rest'] ) {
				unset( $data[ $field ] );
			}
		}

		public function edit_in_rest( $field ) {
			return ! ( apply_filters( 'acf/rest_api/field_settings/edit_in_rest', false ) && isset( $field['edit_in_rest'] ) && ! $field['edit_in_rest'] );
		}
	}
}
