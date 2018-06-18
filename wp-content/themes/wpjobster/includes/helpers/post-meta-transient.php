<?php
/**
 * Custom Transients for Post Meta based on the original wp transient functions
 */

function delete_post_meta_transient( $post_id, $transient ) {

	$post_id = (int) $post_id;

	do_action( 'delete_post_meta_transient_' . $transient, $post_id, $transient );

	if ( wp_using_ext_object_cache() ) {
		$result = wp_cache_delete( "{$transient}-{$post_id}", "post_meta_transient-{$post_id}" );
	} else {
		$meta_timeout = '_transient_timeout_' . $transient;
		$meta = '_transient_' . $transient;
		$result = delete_post_meta( $post_id, $meta );
		if ( $result )
			delete_post_meta( $post_id, $meta_timeout );
	}

	if ( $result ) {
		do_action( 'deleted_post_meta_transient', $post_id, $transient );
	}

	return $result;
}


function get_post_meta_transient( $post_id, $transient ) {

	$post_id = (int) $post_id;

	if ( has_filter( 'pre_post_meta_transient_' . $transient ) ) {
		$pre = apply_filters( 'pre_post_meta_transient_' . $transient, $post_id, $transient );
		if ( false !== $pre ) {
			return $pre;
		}
	}

	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( "{$transient}-{$post_id}", "post_meta_transient-{$post_id}" );
	} else {
		$meta = '_transient_' . $transient;
		if ( ! wp_installing() ) {
			$meta_timeout = '_transient_timeout_' . $transient;
			$timeout = get_post_meta( $post_id, $meta_timeout, true );
			if ( false !== $timeout && $timeout < time() ) {
				delete_post_meta( $post_id, $meta );
				delete_post_meta( $post_id, $meta_timeout );
				$value = false;
			}
		}

		if ( ! isset( $value ) ) {
			$value = get_post_meta( $post_id, $meta, true );
		}
	}

	return apply_filters( 'post_meta_transient_' . $transient, $value, $post_id, $transient );
}


function set_post_meta_transient( $post_id, $transient, $value, $expiration = 0 ) {

	$post_id = (int) $post_id;
	$expiration = (int) $expiration;

	$value = apply_filters( 'pre_set_post_meta_transient_' . $transient, $value, $expiration, $post_id, $transient );

	$expiration = apply_filters( 'expiration_of_post_meta_transient_' . $transient, $expiration, $value, $post_id, $transient );

	if ( wp_using_ext_object_cache() ) {
		wp_cache_delete( "{$transient}-{$post_id}", "post_meta_transient-{$post_id}" );
		$result = wp_cache_set( "{$transient}-{$post_id}", $value, "post_meta_transient-{$post_id}", $expiration );
	} else {
		$meta_timeout = '_transient_timeout_' . $transient;
		$meta = '_transient_' . $transient;

		delete_post_meta( $post_id, $meta );
		delete_post_meta( $post_id, $meta_timeout );

		if ( $expiration ) {
			update_post_meta( $post_id, $meta_timeout, time() + $expiration, true );
		}

		$result = update_post_meta( $post_id, $meta, $value, true );
	}

	if ( $result ) {
		do_action( 'set_post_meta_transient_' . $transient, $value, $expiration, $post_id, $transient );
		do_action( 'setted_post_meta_transient', $post_id, $transient, $value, $expiration );
	}

	return $result;
}
