<?php
function make_trans_completed( $file_name, $class_name, $sk ) {

	if ( isset( $_POST['tx'] ) ) {
		$txn_id = $_POST['tx'];
	} elseif ( isset( $_POST['txn_id'] ) ) {
		$txn_id = $_POST['txn_id'];
	} elseif( isset( $_POST['subscr_id'] ) ) {
		$txn_id = $_POST['subscr_id'];
	} else {
		$txn_id = 'Unknown txn';
	}

	if ( isset( $_POST['st'] ) ) {
		$payment_status = $_POST['st'];
	} elseif ( isset( $_POST['payment_status'] ) ) {
		$payment_status = $_POST['payment_status'];
	} else {
		$payment_status = 'Unknown status';
	}

	if ( isset( $_POST['cm'] ) ) {
		$custom = $_POST['cm'];
	} elseif ( isset( $_POST['custom'] ) ) {
		$custom = $_POST['custom'];
	} else {
		$custom = 'Unknown custom';
	}

	$txn_type = isset( $_POST['txn_type'] ) ? $_POST['txn_type'] : 'Unknown txn type';
	$item_name = isset( $_POST['item_name'] ) ? $_POST['item_name'] : 'Unknown item name';

	if ( ( $txn_id && $payment_status && $custom ) || ( $txn_type && $txn_id && $custom ) ) {

		$next = 0;

		if ( $class_name != 'wpjobster_subscription' ) {
			if ( $payment_status == "Completed" ) $next = 1; else $next = 0;
			if ( ! class_exists( $file_name ) ) {
				include_once get_template_directory()."/lib/gateways/".$file_name.".php";
				$wcjp = new $class_name( $sk );
			}
		} else {
			if ( strpos( strtolower( $item_name ), strtolower( 'Lifetime' ) ) !== false ) {
				if ( $payment_status == "Completed" ) $next = 1; else $next = 0;
			} else {
				if ( $txn_type == "subscr_signup" ) $next = 1; else $next = 0;
			}
			if ( !class_exists( $file_name ) ) {
				include_once get_template_directory()."/classes/subscriptions/".$file_name.".php";
				$wcjp = new $class_name( $sk );
			}
		}

		$payment_response = json_encode( $_REQUEST );

		$payment = wpj_get_payment( array(
			'id' => $custom,
		) );
		$order_id = $payment->payment_type_id;

		if ( ! $order_id ) { $order_id = isset( $_GET['oid'] ) ? $_GET['oid'] : "Unknown Order"; }

		if( $next == 1 ){
			$wcjp->payment_process( $order_id, $sk, $txn_id, $payment_response );
		}

		if( $class_name == 'WPJ_Common_Featured' ){
			$job_id = $wcjp->get_jobid_from_orderid( $order_id );
			return $job_id;
		}

		if( $class_name == 'WPJ_Common_Custom_Extra' ){
			$custom_id = $wcjp->get_main_order_id_from_orderid( $order_id );
			return $custom_id;
		}
	}
}
