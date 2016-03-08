<?php
/*
Plugin Name:  WP REST API Post and User Meta
Plugin URI:   https://github.com/bostondv/wp-api-meta-data
Description:  Adds post and user meta data to WP REST API endpoints
Version:      1.1.1
Author:       Boston Dell-Vandenberg
Author URI:   https://pomelodesign.com
License:      MIT License
*/

namespace WPAPIUserMeta;

/**
 * Handler for getting post meta data.
 *
 * @since 1.1.0
 *
 * @param array $object The object from the response
 * @param string $field_name Name of field
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 */
function get_post_meta( $data, $field_name, $request ) {
  if ( $data['id'] ) {
    $post_meta = array_map( function( $a ) { return $a[0]; }, \get_post_meta( $data['id'] ) );
  }
  if ( !$post_meta ) {
    return;
  }
  return $post_meta;
}

/**
 * Handler for setting post meta data.
 *
 * @since 1.1.0
 *
 * @param mixed $value The value of the field
 * @param object $object The object from the response
 * @param string $field_name Name of field
 *
 * @return mixed
 */
function set_post_meta( $value, $object, $field_name ) {
  if ( ! $value || ! is_array( $value ) ) {
    return;
  }
  foreach ( $value as $k => $v ) {
    if ( $v === null ) {
      \delete_post_meta( $object->ID, $k, strip_tags( $v ) );
    } else {
      \update_post_meta( $object->ID, $k, strip_tags( $v ) );
    }
  }
}

/**
 * Handler for getting user meta data.
 *
 * @since 1.0.0
 *
 * @param array $object The object from the response
 * @param string $field_name Name of field
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 */
function get_user_meta( $data, $field_name, $request ) {
  if ( $data['id'] ) {
    $user_meta = array_map( function( $a ) { return $a[0]; }, \get_user_meta( $data['id'] ) );
  }
  if ( !$user_meta ) {
    return;
  }
  return $user_meta;
}

/**
 * Handler for setting user meta data.
 *
 * @since 1.0.0
 *
 * @param mixed $value The value of the field
 * @param object $object The object from the response
 * @param string $field_name Name of field
 *
 * @return mixed
 */
function set_user_meta( $value, $object, $field_name ) {
  if ( ! $value || ! is_array( $value ) ) {
    return;
  }
  foreach ( $value as $k => $v ) {
    if ( $v === null ) {
      \delete_user_meta( $object->ID, $k, strip_tags( $v ) );
    } else {
      \update_user_meta( $object->ID, $k, strip_tags( $v ) );
    }
  }
}

/**
 * Add the field "meta" to WP API responses for users read and write
 */
function register_meta_fields() {
  \register_rest_field( 'post',
    'meta',
    array(
      'get_callback'    => __NAMESPACE__ . '\\get_post_meta',
      'update_callback' => __NAMESPACE__ . '\\set_post_meta',
      'schema'          => null,
    )
  );
  
  \register_rest_field( 'page',
    'meta',
    array(
      'get_callback'    => __NAMESPACE__ . '\\get_post_meta',
      'update_callback' => __NAMESPACE__ . '\\set_post_meta',
      'schema'          => null,
    )
  );

  \register_rest_field( 'user',
    'meta',
    array(
      'get_callback'    => __NAMESPACE__ . '\\get_user_meta',
      'update_callback' => __NAMESPACE__ . '\\set_user_meta',
      'schema'          => null,
    )
  );

  $custom_post_types = \get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );

  foreach ( $custom_post_types as $type ) {
    \register_rest_field( $type,
      'meta',
      array(
        'get_callback'    => __NAMESPACE__ . '\\get_post_meta',
        'update_callback' => __NAMESPACE__ . '\\set_post_meta',
        'schema'          => null,
      )
    );
  }
}

add_action( 'rest_api_init', __NAMESPACE__ . '\\register_meta_fields' );
