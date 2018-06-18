<?php
if ( $process_action != '' ) {
	if ( ! class_exists( 'wpjobster_common_featured' ) ) {
		include_once get_template_directory() . "/lib/gateways/wpjobster_common_featured.php";
		$wcf = new WPJ_Common_Featured( $sk );
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

		$wcf = new WPJ_Common_Featured( $sk );
		$wcf->_currency = apply_filters( "wpjobster_take_allowed_currency_$sk", $wcf->_currency );

		$_job_id        = isset( $_GET['jobid'] )         ? $_GET['jobid']         : '0';
		$_h_date_start  = isset( $_GET['h_date_start'] )  ? $_GET['h_date_start']  : false;
		$_c_date_start  = isset( $_GET['c_date_start'] )  ? $_GET['c_date_start']  : false;
		$_s_date_start  = isset( $_GET['s_date_start'] )  ? $_GET['s_date_start']  : false;
		$_feature_pages = isset( $_GET['feature_pages'] ) ? $_GET['feature_pages'] : false;

		$total = 0;
		if ( strpos( $_feature_pages ,'h' ) !== false )
			$total+=get_option( 'wpjobster_featured_price_homepage' );
		if ( strpos( $_feature_pages ,'c' ) !== false )
			$total+=get_option( 'wpjobster_featured_price_category' );
		if ( strpos( $_feature_pages ,'s' ) !== false )
			$total+=get_option( 'wpjobster_featured_price_subcategory' );

		$_featured_amount              = $total;
		$buyer_processing_fees_orignal = wpjobster_get_site_processing_fee( $_featured_amount, 0, 0);
		$tax_orignal                   = wpjobster_get_site_tax($_featured_amount,0,0,$buyer_processing_fees_orignal);
		$total_amount_orignal          = $_featured_amount+$tax_orignal+$buyer_processing_fees_orignal;
		$_tax                          = wpjobster_formats_special_exchange( $tax_orignal, '1', $wcf->_currency );
		$buyer_processing_fees         = wpjobster_formats_special_exchange( $buyer_processing_fees_orignal, '1', $wcf->_currency );
		$total                         = wpjobster_formats_special_exchange( $_featured_amount, '1', $wcf->_currency );
		$_payable_amount               = $total+$buyer_processing_fees+$_tax;

		$paid= 'pending';
		if ( $sk=='credits' ) {
			$uid  = $wcf->_current_user->ID;
			$crds = wpjobster_get_credits($uid);
			if ($total_amount_orignal > $crds) { echo __('NO_CREDITS_LEFT','wpjobster'); exit; }
			wpjobster_update_credits($uid, $crds - ($total_amount_orignal));
			$paid = 'completed';
		}

		$featured_order = array(
			'feature_pages'        => $_feature_pages,
			'job_id'               => $_job_id,
			'user_id'              => $wcf->_current_user->ID,
			'featured_amount'      => $_featured_amount,
			'payment_status'       => $paid,
			'payment_gateway_name' => $wcf->_payment_gateway,
			'h_date_start'         => $_h_date_start,
			'c_date_start'         => $_c_date_start,
			's_date_start'         => $_s_date_start,
			'tax'                  => $_tax,
			'payable_amount'       => $_payable_amount,
			'currency'             => $wcf->_currency,
			'tax_orignal'          => $tax_orignal,
			'fees'                 => $buyer_processing_fees,
			'total_amount_orignal' => $total_amount_orignal,
			'fees_orignal'         => $buyer_processing_fees_orignal
		);

		$order_id     = $wcf ->insert_featured_order($featured_order);
		$post_feature = get_post($_job_id);

		$featured_order_gateway['order_id']                       = $order_id;
		$featured_order_gateway['price']                          = $_featured_amount;
		$featured_order_gateway['uid']                            = $wcf->_current_user->ID;
		$featured_order_gateway['pid']                            = $_job_id;
		$featured_order_gateway['selected']                       = $wcf->_currency;
		$featured_order_gateway['job_title']                      = $featured_order_gateway['title'] = $post_feature->post_title;
		$featured_order_gateway['wpjobster_final_payable_amount'] = $_payable_amount;
		$featured_order_gateway['current_user']                   = $wcf->_current_user;
		$featured_order_gateway['currency']                       = $wcf->_currency;

		do_action( $process_action, 'feature', $featured_order_gateway, $_job_id );
	}
	if ( $action=='process_payment' ) {
		do_action( $process_action, 'feature', $wcf );
	}
}
