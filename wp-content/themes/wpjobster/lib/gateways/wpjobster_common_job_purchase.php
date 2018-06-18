<?php
include_once 'wpjobster_common_payment.php';
class WPJ_Common_Job_Purchase extends WPJ_Common_Payment{
	public $_currency,$_wpjobster_tax_percent,$_wpjobster_tax_amount,$_package_id,$_current_user,
			$_package_cost,$_wpdb,$_wp_query,$_pay_pg_lnk,$_payment_type;

	public function __construct($payment_gateway='') {
		global $wp_query,$wpdb,$current_user;
		parent::__construct($payment_gateway);
		$this->_payment_type = 'job_purchase';
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;
		$this->_wp_query=$wp_query;
		add_action("wpjobster_job_purchase_payment_success",array($this,"payment_process"),10,5);
		add_action("wpjobster_job_purchase_payment_failed",array($this,"payment_failed"),10,5);
		add_action("wpjobster_job_purchase_payment_other",array($this,"payment_other"),10,6);
		if(isset($_GET['site_currency']))
		{
			$this->_currency = strtoupper($_GET['site_currency']);
		}
		else
		{
			$this->_currency = isset( $_COOKIE["site_currency"] ) ? strtoupper( $_COOKIE["site_currency"] ) : 'USD';
		}
		$using_perm = wpjobster_using_permalinks();
		if ($using_perm) {
			$this->pay_pg_lnk = get_permalink(get_option('wpjobster_my_account_payments_page_id')). "?";
		} else {
			$this->pay_pg_lnk = get_bloginfo('siteurl'). "?page_id=". get_option('wpjobster_my_account_payments_page_id'). "&";
		}
	}

	function insert_job_prchase($payment_gateway, $params = array()){
		$to_currency = '';
		$to_currency = apply_filters("wpjobster_take_allowed_currency_$payment_gateway",$to_currency);
		$common_details = get_common_details($payment_gateway,0,$to_currency,$params);
		$payment_info['payment_status'] = 0;
		$payment_info['payment_gateway'] = $payment_gateway;
		$payment_info['payment_type'] = $this->_payment_type;
		$payment_info['payment_type_id'] = $common_details['order_id'];
		$payment_info['fees'] = $common_details['wpjobster_job_buyer_processing_fees'];
		$payment_info['amount'] = $common_details['wpjobster_job_price'];
		$payment_info['tax'] = $common_details['wpjobster_job_tax'];

		$payment_info['currency'] = get_option('wpjobster_currency_1');

		$payment_info['final_amount'] = $common_details['wpjobster_final_payable_amount_original'];
		$payment_info['final_amount_exchanged'] = $common_details['wpjobster_final_payable_amount'];
		$payment_info['final_amount_currency']=$common_details['currency'];

		$order = $this->insert_payment($payment_info);
		return $common_details;
	}


	public function payment_process($orderid,$payment_gateway_slug,$payment_details,$response, $redirect='yes'){

		$old_order_info = wpjobster_get_order_details_by_orderid( $orderid );

		$payment_status = "completed";
		wpjobster_update_order_meta( $orderid, 'payment_status', $payment_status );
				$response = !empty($response) ? $response : $old_order_info->payment_response;
		wpjobster_update_order_meta( $orderid, 'payment_response', $response );

		if ( $payment_details != '' ) {
			wpjobster_update_order_meta( $orderid, 'payment_details', $payment_details );
		}

		// get order info from database
		$order_info = wpjobster_get_order_details_by_orderid( $orderid );

		$post_title            = $order_info->job_title;
		$mc_gross              = $order_info->mc_gross;
		$uid                   = $order_info->uid;
		$pid                   = $order_info->pid;
		$buyer_processing_fees = $order_info->processing_fees;
		$wpjobster_tax_amount  = $order_info->tax_amount;
		$post                  = get_post( $pid );
		$post_author_id        = $post->post_author;

		if ( $old_order_info->payment_status != $order_info->payment_status ) {
			$datemade = time();

			$g1 = "insert into " . $this->_wpdb->prefix . "job_chatbox (datemade, uid, oid, content) values('$datemade','0','$orderid','')";
			$this->_wpdb->query($g1);
			wpj_update_user_notifications( $post_author_id, 'notifications', +1 );

			wpjobster_maintain_log( $orderid, $post_title, $mc_gross, $uid, $pid, $post_author_id, $buyer_processing_fees, $wpjobster_tax_amount );
			wpjobster_send_sms_allinone_translated( 'purchased_buyer', $uid, $post_author_id, $pid, $orderid );
			wpjobster_send_sms_allinone_translated( 'purchased_seller', $post_author_id, $uid, $pid, $orderid );
			wpjobster_send_email_allinone_translated( 'purchased_buyer', $uid, $post_author_id, $pid, $orderid );
			wpjobster_send_email_allinone_translated( 'purchased_seller', $post_author_id, $uid, $pid, $orderid );

			// this runs for regular gateways
			do_action( 'wpjobster_job_payment_completed', $orderid );
		}
		$payment['order_id']=$orderid ;
		$payment['status']='1';
		$payment['payment_type']='job_purchase';
		$payment['payment_response']=$response;
		$payment['payment_details']=$payment_details;
		$this->update_payment($payment);
				if( $redirect == 'yes' ) {
					$this->job_purchase_success($payment_gateway_slug,$orderid);
				}
	}

	/*
	 * Function will handle the payment status that is other thna cancel/failure and success
	 */
	public function payment_other( $orderid, $payment_gateway_slug, $payment_details, $response, $payment_status = 'processing', $redirect='yes' ){
		// get order info from database
		$order_info = wpjobster_get_order_details_by_orderid( $orderid );

		wpjobster_update_order_meta( $orderid, 'payment_status', $payment_status );
				$response = !empty($response) ? $response : $order_info->payment_response;
		wpjobster_update_order_meta( $orderid, 'payment_response', $response );

		if ( $payment_details != '' ) {
			wpjobster_update_order_meta( $orderid, 'payment_details', $payment_details );
		}

		$post_title            = $order_info->job_title;
		$mc_gross              = $order_info->mc_gross;
		$uid                   = $order_info->uid;
		$pid                   = $order_info->pid;
		$buyer_processing_fees = $order_info->processing_fees;
		$wpjobster_tax_amount  = $order_info->tax_amount;
		$post                  = get_post( $pid );
		$post_author_id        = $post->post_author;

		$payment['order_id']=$orderid ;
		$payment['status']=$payment_status;
		$payment['payment_type']='job_purchase';
		$payment['payment_response']=$response;
		$payment['payment_details']=$payment_details;
		$this->update_payment($payment);

		if( $redirect == 'yes' ) {
			$this->job_purchase_success($payment_gateway_slug,$orderid);
		}
	}

	public function job_purchase_success($gateway,$orderid){
		$wpjobster_success_page_id=get_option("wpjobster_{$gateway}_success_page");
		if( $wpjobster_success_page_id!='' && $wpjobster_success_page_id!='0' ){
			wp_redirect(get_permalink($wpjobster_success_page_id));
		}else{
			wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$orderid);
		}
	}
	public function job_purchase_failed($gateway,$orderid){
		$wpjobster_failure_page_id=get_option("wpjobster_{$gateway}_failure_page");
		if( $wpjobster_failure_page_id!='' && $wpjobster_failure_page_id!='0' ){
			wp_redirect(get_permalink($wpjobster_failure_page_id));
		}else{
			wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$orderid);
		}
	}
	public function payment_failed($orderid,$gateway_slug,$payment_details='',$payment_response='', $redirect='yes'){
		$old_order_info = wpjobster_get_order_details_by_orderid( $orderid );
		$payment_status = "cancelled";
		wpjobster_update_order_meta( $orderid, 'payment_status', $payment_status );
				$payment_response = !empty($payment_response) ? $payment_response : $old_order_info->payment_response;
		wpjobster_update_order_meta( $orderid, 'payment_response', $payment_response );

		if ( $payment_details != '' ) {
			wpjobster_update_order_meta( $orderid, 'payment_details', $payment_details );
		}

		$payment['order_id']=$orderid ;
		$payment['status']='-1';
		$payment['payment_type']='job_purchase';
		$payment['payment_response']=$payment_response;
		$payment['payment_details']=$payment_details;
		$this->update_payment($payment);
		$tm = current_time('timestamp', 1);
		$s = "update " . $this->_wpdb->prefix . "job_orders set  closed='1' , date_closed='$tm'  where id = '{$orderid}' limit 1  ";
		$r = $this->_wpdb->query($s);

		if ( $redirect == 'yes' ) {
			$this->job_purchase_failed($gateway_slug,$orderid);
		}
	}

	function get_pending_jobs_by_no_of_days(){
		$no_of_days = get_option('wpjobster_pending_jobs_days');
		if($no_of_days==0 || $no_of_days==''){
			$no_of_days = 7; // default values for no of days
		}
		$x_days_before = strtotime("- $no_of_days days");
		$prefix = $this->_wpdb->prefix;
		$s = "select id from " . $prefix . "job_orders where done_seller='0' AND done_buyer='0' AND date_finished='0' AND closed='0' and payment_status='pending' and date_made<$x_days_before ";
		$r = $this->_wpdb->get_results($s);

		foreach ($r as $row){
			$payment_details = "Expired on ".time() ;
			wpjobster_update_order_meta( $row->id, 'payment_status', 'expired' );
			wpjobster_update_order_meta( $row->id, 'payment_details',$payment_details  );
			$tm = current_time('timestamp', 1);
			$s = "update " . $prefix . "job_orders set  closed='1' , date_closed='$tm'  where id = '{$row->id}' limit 1  ";
			$r = $this->_wpdb->query($s);
			$payment['order_id']=$row->id ;
			$payment['status']='expired';
			$payment['payment_type']='job_purchase';
			$payment['payment_response']='Transaction Cancelled by Cron job on '.date("Y-d-m");
			$payment['payment_details']=$payment_details;
			$this->update_payment($payment);
		}
	}

	function close_failed_jobs_cron() {
		// Fix for some old failed payments which didn't properly update
		$prefix = $this->_wpdb->prefix;
		$this->_wpdb->query(
			"
			UPDATE {$prefix}job_orders
			SET closed = '1',
				date_closed = UNIX_TIMESTAMP(),
				payment_details = 'Failed payment, closed by cron.'
			WHERE done_seller='0'
				AND done_buyer='0'
				AND date_finished='0'
				AND closed='0'
				AND payment_status='failed'
			"
		);
	}
}
