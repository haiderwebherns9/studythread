<?php
if ( $process_action != '' ) {
	if ( ! class_exists( 'wpjobster_common_custom_extra' ) ) {
		include_once get_template_directory() . "/lib/gateways/wpjobster_common_custom_extra.php";
		$wcf = new WPJ_Common_Custom_Extra( $sk );
	}

	if ( $sk == 'paypal' ) {
		if ( class_exists( 'WPJobster_PayPal_Loader' ) ) {
			$paypalClass = new WPJobster_PayPal_Loader();
		}
	} else {
		if ( ! class_exists( 'wpjobster_' . $sk ) && file_exists( get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php' ) ) {
			include_once get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php';
		}
	}

	if ( $action == 'payment' ) {
		$wcf = new WPJ_Common_Custom_Extra( $sk );

		$_order_id        = isset( $_GET['oid'] ) ? $_GET['oid'] : false;
		$_custom_extra_id = isset( $_GET['custom_extra'] ) && $_GET['custom_extra'] ? $_GET['custom_extra'] : 0;
		$ord              = wpjobster_get_order( $_order_id );
		$custom_extras    = json_decode( $ord->custom_extras );
		$total            = $custom_extras[$_custom_extra_id]->price;

		$_custom_extra_amount          = $total;
		$buyer_processing_fees_orignal = wpjobster_get_site_processing_fee( $_custom_extra_amount, 0, 0);
		$tax_orignal                   = wpjobster_get_site_tax( $_custom_extra_amount, 0, 0, $buyer_processing_fees_orignal );
		$total_amount_orignal          = $_custom_extra_amount+$tax_orignal+$buyer_processing_fees_orignal;
		$_tax                          = wpjobster_formats_special_exchange( $tax_orignal, '1', $wcf->_currency );
		$buyer_processing_fees         = wpjobster_formats_special_exchange( $buyer_processing_fees_orignal, '1', $wcf->_currency );
		$total                         = wpjobster_formats_special_exchange( $_custom_extra_amount, '1', $wcf->_currency );
		$_payable_amount               = $total+$buyer_processing_fees+$_tax;

		$paid= 'pending';
		if ( $sk == 'credits' ) {
			$uid = $wcf->_current_user->ID;
			$crds = wpjobster_get_credits( $uid );
			if ($total_amount_orignal > $crds) { echo __('NO_CREDITS_LEFT','wpjobster'); exit; }
			wpjobster_update_credits( $uid, $crds - ( $total_amount_orignal ) );
			$paid = 'completed';
		}

		$custom_extra_order = array(
			'order_id'             => $_order_id,
			'custom_extra_id'      => $_custom_extra_id,
			'user_id'              => $wcf->_current_user->ID,
			'custom_extra_amount'  => $_custom_extra_amount,
			'payment_status'       => $paid,
			'payment_gateway_name' => $wcf->_payment_gateway,
			'tax'                  => $_tax,
			'payable_amount'       => $_payable_amount,
			'currency'             => $wcf->_currency,
			'tax_orignal'          => $tax_orignal,
			'fees'                 => $buyer_processing_fees,
			'total_amount_orignal' => $total_amount_orignal,
			'fees_orignal'         => $buyer_processing_fees_orignal
		);

		$order_id = $wcf->insert_custom_extra_order($custom_extra_order);

		$custom_extra_order_gateway['order_id']                       = $order_id;
		$custom_extra_order_gateway['price']                          = $_custom_extra_amount;
		$custom_extra_order_gateway['uid']                            = $wcf->_current_user->ID;
		$custom_extra_order_gateway['pid']                            = $_order_id;
		$custom_extra_order_gateway['selected']                       = $wcf->_currency;
		$custom_extra_order_gateway['job_title']                      = $custom_extra_order_gateway['title'] = $custom_extras[$_custom_extra_id]->description;
		$custom_extra_order_gateway['wpjobster_final_payable_amount'] = $_payable_amount;
		$custom_extra_order_gateway['current_user']                   = $wcf->_current_user;
		$custom_extra_order_gateway['currency']                       = $wcf->_currency;

		do_action($process_action,'custom_extra',$custom_extra_order_gateway);
	}

	if( $action == 'process_payment' ){
		do_action( $process_action, 'custom_extra', $wcf );
	}
}
