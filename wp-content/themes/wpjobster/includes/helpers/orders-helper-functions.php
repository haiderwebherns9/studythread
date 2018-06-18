<?php
/**
 * Jobster helper functions
 */


/**
 * Retrieve seller ID for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return int
 */
function wpj_get_seller_id( $order ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}
	$post_id = isset( $order->pid ) ? $order->pid : 0;
	$seller_id = $post_id ? get_post_field( 'post_author', $post_id ) : 0;

	return $seller_id;
}


/**
 * Retrieve buyer ID for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return int
 */
function wpj_get_buyer_id( $order ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}
	$seller_id = isset( $order->uid ) ? $order->uid : 0;

	return $seller_id;
}


/**
 * Retrieve expected_delivery for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return int
 */
function wpj_get_expected_delivery( $order ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}

	// insert expected delivery if not exist
	if ( ! $order->expected_delivery ) {
		$date_made = $order->date_made;
		$max_days = get_post_meta( $order->pid, 'max_days', true );

        if ( $order->extra_fast != 0 ) {
            $max_days     = $order->extra_fast_days;
        }

        if($order->extra1>0) $max_days += $order->extra1_days;
        if($order->extra2>0) $max_days += $order->extra2_days;
        if($order->extra3>0) $max_days += $order->extra3_days;
        if($order->extra4>0) $max_days += $order->extra4_days;
        if($order->extra5>0) $max_days += $order->extra5_days;
        if($order->extra6>0) $max_days += $order->extra6_days;
        if($order->extra7>0) $max_days += $order->extra7_days;
        if($order->extra8>0) $max_days += $order->extra8_days;
        if($order->extra9>0) $max_days += $order->extra9_days;
        if($order->extra10>0) $max_days += $order->extra10_days;

        if($order->extra_revision_days>0) $max_days += $order->extra_revision_days;

        $expected = $date_made + ( 24 * 3600 * $max_days );

		global $wpdb;
		$wpdb->query(
			"
			UPDATE {$wpdb->prefix}job_orders
			SET expected_delivery='$expected'
			WHERE id='$order->id'
			"
		);
		$order->expected_delivery = $expected;
	}

	return $order->expected_delivery;
}


/**
 * Update expected_delivery for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return int
 */
function wpj_update_expected_delivery( $order, $new_date ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}

	global $wpdb;
	$wpdb->query(
		"
		UPDATE {$wpdb->prefix}job_orders
		SET expected_delivery='$new_date'
		WHERE id='$order->id'
		"
	);

	return true;
}


/**
 * Add number of days to expected_delivery for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return int
 */
function wpj_update_expected_delivery_add_days( $order, $days ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}

	$expected = wpj_get_expected_delivery( $order );
	$expected = $expected + ( 24 * 3600 * $days );

	global $wpdb;
	$wpdb->query(
		"
		UPDATE {$wpdb->prefix}job_orders
		SET expected_delivery='$expected'
		WHERE id='$order->id'
		"
	 );

	return $expected;
}


/**
 * Retrieve payment object for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param array
 * @return object
 */
function wpj_get_payment( $args ) {

	$defaults = array(
		'id' => false,
		'payment_type' => 'job_purchase',
		'payment_type_id' => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$id = $args['id'];
	$payment_type = $args['payment_type'];
	$payment_type_id = $args['payment_type_id'];

	global $wpdb;

	if ( $id ) {
		$payment = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT * FROM {$wpdb->prefix}job_payment_received
			WHERE id = %d
			",
			$id
		) );
		return $payment;

	} elseif ( $payment_type_id ) {
		$payment = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT * FROM {$wpdb->prefix}job_payment_received
			WHERE payment_type = %s
				AND payment_type_id = %d
			",
			$payment_type, $payment_type_id
		) );
		return $payment;
	}

	return false;
}


/**
 * Retrieve custom extras object for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @return object
 */
function wpj_get_custom_extras( $order ) {
	if ( is_object( $order ) ) {
		$order_id = $order->id;
	} else {
		$order_id = $order;
	}

	global $wpdb;
	$custom_extras = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT * FROM {$wpdb->prefix}job_custom_extra_orders
		WHERE order_id = %d
		ORDER BY custom_extra_id ASC
		",
		$order_id
	) );

	return $custom_extras;
}


/**
 * Retrieve single custom extra for a particular order
 *
 * @since Jobster v4.0.0
 *
 * @param object|int
 * @param int
 * @return object
 */
function wpj_get_custom_extra( $order, $custom_extra_id ) {
	if ( is_object( $order ) ) {
		$order_id = $order->id;
	} else {
		$order_id = $order;
	}

	global $wpdb;
	$custom_extra = $wpdb->get_row( $wpdb->prepare(
		"
		SELECT * FROM {$wpdb->prefix}job_custom_extra_orders
		WHERE order_id = %d
			AND custom_extra_id = %d
		",
		$order_id, $custom_extra_id
	) );

	return $custom_extra;
}

// GET FEATURED ORDER
if ( ! function_exists( 'wpjobster_get_featured_order_by' ) ) {
	function wpjobster_get_featured_order_by( $column_name='', $val='' ) {

		$val = esc_sql( $val );

		if ( is_numeric( $val ) ) {
			global $wpdb;
			$pref = $wpdb->prefix;

			$sql = "SELECT * FROM {$wpdb->prefix}job_featured_orders WHERE ".$column_name." = ".$val;
			$result = $wpdb->get_results( $sql );

			return $result;
		}

		return false;
	}
}

// GET TOPUP ORDER
if ( ! function_exists( 'wpjobster_get_topup_order_by' ) ) {
	function wpjobster_get_topup_order_by( $column_name='', $val='' ) {

		$val = esc_sql( $val );

		if ( is_numeric( $val ) ) {
			global $wpdb;
			$pref = $wpdb->prefix;

			$sql = "SELECT * FROM {$wpdb->prefix}job_topup_orders WHERE ".$column_name." = ".$val;
			$result = $wpdb->get_row( $sql );

			return $result;
		}

		return false;
	}
}

// GET SUBSCRIPTION ORDER
if ( ! function_exists( 'wpjobster_get_subscription_order_by' ) ) {
	function wpjobster_get_subscription_order_by( $column_name='id', $val='', $status='active', $type_of_return='single' ) {

		$val = esc_sql( $val );

		if ( is_numeric( $val ) ) {
			global $wpdb;
			$pref = $wpdb->prefix;

			$sql = "SELECT * FROM {$wpdb->prefix}job_subscription_orders WHERE " . $column_name . " = '" . $val . "' AND subscription_status='" . $status . "' LIMIT 1";

			if( $type_of_return == 'multiple' ){
				$result = $wpdb->get_results( $sql );
			}else{
				$result = $wpdb->get_row( $sql );
			}

			return $result;
		}

		return false;
	}
}
