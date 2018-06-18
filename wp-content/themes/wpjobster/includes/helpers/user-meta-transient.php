<?php
/**
 * Custom Transients for User Meta based on the original wp transient functions
 */

function delete_user_meta_transient( $user_id, $transient ) {

	$user_id = (int) $user_id;

	do_action( 'delete_user_meta_transient_' . $transient, $user_id, $transient );

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_delete( "{$transient}-{$user_id}", "user_meta_transient-{$user_id}" );
	} else {
		$meta_timeout = '_transient_timeout_' . $transient;
		$meta = '_transient_' . $transient;
		$result = delete_user_meta( $user_id, $meta );
		if ( $result )
			delete_user_meta( $user_id, $meta_timeout );
	}

	if ( $result ) {
		do_action( 'deleted_user_meta_transient', $user_id, $transient );
	}

	return $result;
}


function get_user_meta_transient( $user_id, $transient ) {

	$user_id = (int) $user_id;

	if ( has_filter( 'pre_user_meta_transient_' . $transient ) ) {
		$pre = apply_filters( 'pre_user_meta_transient_' . $transient, $user_id, $transient );
		if ( false !== $pre ) {
			return $pre;
		}
	}

	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( "{$transient}-{$user_id}", "user_meta_transient-{$user_id}" );
	} else {
		$meta = '_transient_' . $transient;
		if ( ! wp_installing() ) {
			$meta_timeout = '_transient_timeout_' . $transient;
			$timeout = get_user_meta( $user_id, $meta_timeout, true );
			if ( false !== $timeout && $timeout < time() ) {
				delete_user_meta( $user_id, $meta );
				delete_user_meta( $user_id, $meta_timeout );
				$value = false;
			}
		}

		if ( ! isset( $value ) ) {
			$value = get_user_meta( $user_id, $meta, true );
		}
	}

	return apply_filters( 'user_meta_transient_' . $transient, $value, $user_id, $transient );
}


function set_user_meta_transient( $user_id, $transient, $value, $expiration = 0 ) {

	$user_id = (int) $user_id;
	$expiration = (int) $expiration;

	$value = apply_filters( 'pre_set_user_meta_transient_' . $transient, $value, $expiration, $user_id, $transient );

	$expiration = apply_filters( 'expiration_of_user_meta_transient_' . $transient, $expiration, $value, $user_id, $transient );

	if ( wp_using_ext_object_cache() ) {
		wp_cache_delete( "{$transient}-{$user_id}", "user_meta_transient-{$user_id}" );
		$result = wp_cache_set( "{$transient}-{$user_id}", $value, "user_meta_transient-{$user_id}", $expiration );
	} else {
		$meta_timeout = '_transient_timeout_' . $transient;
		$meta = '_transient_' . $transient;

		delete_user_meta( $user_id, $meta );
		delete_user_meta( $user_id, $meta_timeout );

		if ( $expiration ) {
			update_user_meta( $user_id, $meta_timeout, time() + $expiration, true );
		}

		$result = update_user_meta( $user_id, $meta, $value, true );
	}

	if ( $result ) {
		do_action( 'set_user_meta_transient_' . $transient, $value, $expiration, $user_id, $transient );
		do_action( 'setted_user_meta_transient', $user_id, $transient, $value, $expiration );
	}

	return $result;
}
