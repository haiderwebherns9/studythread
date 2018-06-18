<?php
if ( $process_action != '' ) {
	if ( ! class_exists( 'wpjobster_subscription' ) ) {
		include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
		$wcs = new wpjobster_subscription( $sk );
	}

	if ( $sk == 'paypal' ) {
		if ( class_exists( 'WPJobster_PayPal_Loader' ) ) {
			$paypalClass = new WPJobster_PayPal_Loader();
		}
	} else {
		if( ! class_exists( 'wpjobster_' . $sk ) && file_exists( get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php' ) ) {
			include_once get_template_directory(). '/lib/gateways/wpjobster_' . $sk . '.php';
		}
	}

	if ( $action == 'payment' ) {

		$wcs = new wpjobster_subscription( $sk );
		$wcs->_currency = apply_filters( "wpjobster_take_allowed_currency_$sk", $wcs->_currency );
		if ( isset( $_GET['sub_type'] ) ) {
			if ( $_GET['sub_type'] == 'daily' ) {
				$cycle = 'D';
				$period = 1;
			} else if ( $_GET['sub_type'] == 'weekly' ) {
				$cycle = 'W';
				$period = 1;
			} else if ( $_GET['sub_type'] == 'monthly' ) {
				$cycle = 'M';
				$period = 1;
			}else if ( $_GET['sub_type'] == 'quarterly' ) {
				$cycle = 'M';
				$period = 3;
			} else if ( $_GET['sub_type'] == 'yearly' ) {
				$cycle = 'Y';
				$period = 1;
			} else {
				$cycle = 'lifetime';
				$period = 'unlimited';
			}
		}

		if ( isset( $_GET['sub_level'] ) ) {
			if ( $_GET['sub_level'] == 'level1' ) {
				$plan = get_option( 'wpjobster_subscription_name_level1' ) ? get_option( 'wpjobster_subscription_name_level1' ) : __('Starter Plan', 'wpjobster');
			} else if ( $_GET['sub_level'] == 'level2' ) {
				$plan = get_option( 'wpjobster_subscription_name_level2' ) ? get_option( 'wpjobster_subscription_name_level2' ) : __('Business Plan', 'wpjobster');
			} else if ( $_GET['sub_level'] == 'level3' ) {
				$plan = get_option( 'wpjobster_subscription_name_level3' ) ? get_option( 'wpjobster_subscription_name_level3' ) : __('Professional Plan', 'wpjobster');
			} else {
				$plan = get_option( 'wpjobster_subscription_name_level0' ) ? get_option( 'wpjobster_subscription_name_level0' ) : __('Not Subscribed', 'wpjobster');
			}
		}

		$_subscription_amount = $_GET['sub_amount'];
		$prices               = $wcs->calculate_tax_and_fees( $_subscription_amount );

		$buyer_processing_fees_orignal = $prices['buyer_processing_fees_orignal'];
		$tax_orignal                   = $prices['tax_orignal'];
		$total_amount_orignal          = $prices['total_amount_orignal'];

		$_tax                  = $prices['tax'];
		$buyer_processing_fees = $prices['buyer_processing_fees'];
		$_payable_amount       = $prices['payable_amount'];

		$paid    = 'pending';
		$sub_sts = 'inactive';

		$subscription_order= array(
			'user_id'              => $_GET['user_id'],
			'subscription_amount'  => $_subscription_amount,
			'payment_status'       => $paid,
			'subscription_status'  => $sub_sts,
			'payment_gateway_name' => $wcs->_payment_gateway,
			'tax'                  => 0, //$_tax
			'payable_amount'       => $_subscription_amount, // $_payable_amount
			'currency'             => $wcs->_currency,
			'plan'                 => $_GET['sub_type'],
			'level'                => $_GET['sub_level'] . '-' . $_GET['sub_usr_type'],
			'tax_orignal'          => 0, // $tax_orignal
			'fees'                 => 0, // $buyer_processing_fees
			'total_amount_orignal' => 0, // $total_amount_orignal
			'fees_orignal'         => 0  // $buyer_processing_fees_orignal
		);

		$order_id = $wcs->insert_subscription_order( $subscription_order );
		$wcs->insert_subscription_payment_received($order_id,$wcs->_payment_gateway,$paid,'','');

		$details['order_id']                       = $order_id;
		$details['cycle']                          = $cycle;
		$details['period']                         = $period;
		$details['uid']                            = $_GET['user_id'];
		$details['sub_type']                       = $_GET['sub_type'];
		$details['sub_level']                      = $_GET['sub_level'];
		$details['wpjobster_final_payable_amount'] = $_subscription_amount; //for tax & fees: $_payable_amount;
		$details['price']                          = $_subscription_amount;
		$details['selected']                       = $wcs->_currency;
		$details['current_user']                   = $wcs->_current_user;
		$details['pid']                            = $order_id;
		$details['title']                          = 'Subscription ' . $plan . ' - ' . ucfirst( $_GET['sub_type'] );

		do_action( $process_action, 'subscription', $details );
	}
	if ( $action == 'process_payment' ) {
		do_action( $process_action, 'subscription', $wcs );
	}
}
