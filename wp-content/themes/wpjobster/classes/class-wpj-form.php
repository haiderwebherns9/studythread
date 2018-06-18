<?php

/**
 * Check if $_GET or $_POST isset in order to assign it to a variable
 *
 * @package WPJobster
 * @subpackage Jobster
 * @since Jobster v3.5.0
 */
if ( ! class_exists( 'WPJ_Form' ) ) {
	class WPJ_Form {
		static function get( $key,  $default_value = '' ) {
			return isset( $_GET[$key] ) ? trim( $_GET[$key] ) : $default_value;
		}
		static function post( $key,  $default_value = '' ) {
			return isset( $_POST[$key] ) ? trim( $_POST[$key] ) : $default_value;
		}
	}
}
