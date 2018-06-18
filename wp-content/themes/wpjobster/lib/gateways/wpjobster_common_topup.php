<?php
include_once 'wpjobster_common_payment.php';
class WPJ_Common_Topup extends WPJ_Common_Payment{
	public $_currency,$_wpjobster_tax_percent,$_wpjobster_tax_amount,$_package_id,$_current_user,$_payment_gateway, $_package_cost_original,$_package_cost,$_package_credit_original,$_package_credit,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public function __construct($payment_gateway){
		global $wp_query,$wpdb,$current_user;
		$this->_payment_gateway = $payment_gateway;
		parent::__construct($payment_gateway);
		$this->_payment_type = 'topup';
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;
		$this->_wp_query=$wp_query;
		add_action("wpjobster_topup_payment_success",array($this,"payment_process"),10,5);
		add_action("wpjobster_topup_payment_failed",array($this,"payment_failed"),10,5);
		add_action("wpjobster_topup_payment_other",array($this,"payment_other"),10,6);
		if ( isset( $_GET['site_currency'] ) ) {
			$this->_currency = strtoupper( $_GET['site_currency'] );
		} elseif ( isset( $_COOKIE["site_currency"] ) ) {
			$this->_currency = strtoupper( $_COOKIE["site_currency"] );
		} else {
			$this->_currency = wpjobster_get_currency_classic();
		}
		$this->_package_id 	= isset($_GET['package_id'])?$_GET['package_id']:'0';

		$using_perm = wpjobster_using_permalinks();
		if ($using_perm) {
			$this->pay_pg_lnk = get_permalink(get_option('wpjobster_my_account_payments_page_id')). "?";
		} else {
			$this->pay_pg_lnk = get_bloginfo('siteurl'). "?page_id=". get_option('wpjobster_my_account_payments_page_id'). "&";
		}
	}
	public function payment_process($order_id,$payment_gateway_slug,$payment_details,$response, $redirect='yes'){

		$package_order=$this->get_topup_order_by_id($order_id);

        if( $payment_gateway_slug == 'pesapal' ) {
            $pay_order_status = 'completed';
        } else {
            $pay_order_status = 'pending';
        }

		// prevent updating credits multiple times
		if ( isset( $package_order->payment_status )
			&& $package_order->payment_status == $pay_order_status ) {

			$currency = $package_order->currency;
			$package_id = $package_order->package_id;
			$package = $this->get_package($package_id);
			$credit = $this->_package_credit;
			$amn = $package_order->package_amount;
			$uid = $package_order->user_id;
			$user = get_userdata($uid);
			$email = $user->user_email;
			$hashTotal = $this->_package_cost;

			$cr = wpjobster_get_credits($uid);
			wpjobster_update_credits($uid, $cr + $credit);
			$price=$credit;
			$reason = __('Top Up account balance', 'wpjobster');
			$payed_amt = $currency."|".$amn;
			$amount_without_tax = $currency."|".$package_order->package_cost_without_tax;
			$tax_paid = $currency."|".$package_order->tax;

			wpjobster_add_history_log('1', $reason, $price, $uid, '', '', 12, '',$amount_without_tax);

			$payed_amt_mail = $amn." ".$currency;
			wpjobster_send_email_allinone_translated('balance_up_topup', $uid, false, false, false, false, false, false, false, $credit,$payed_amt_mail,$order_id);
			wpjobster_send_sms_allinone_translated('balance_up_topup', $uid, false, false, false, false, false, false, false, $credit,$payed_amt_mail,$order_id);

			wpjobster_send_email_allinone_translated('balance_admin_up_topup', 'admin', $uid, false, false, false, false, false, false, $credit,$payed_amt_mail,$order_id);
			wpjobster_send_sms_allinone_translated('balance_admin_up_topup', 'admin', $uid, false, false, false, false, false, false, $credit,$payed_amt_mail,$order_id);

			do_action( 'wpjobster_top_up_payment_completed', $order_id );
			$this->update_topup_prchase_status($order_id, "completed", $payment_details, $response, $payment_gateway_slug, $redirect);
		} else {
			$payment_details = !empty($payment_details) ? $payment_details : $package_order->payment_gateway_transaction_id;
			$response = !empty($response) ? $response : $package_order->payment_response;
			$this->update_topup_prchase_status($order_id, "completed", $payment_details, $response, $payment_gateway_slug, $redirect);
			wp_die(
				__( 'Order status already changed.', 'wpjobster' ),
				__( 'Error', 'wpjobster' ),
				array( 'response' => 400 )
			);
		}

	}
	public function payment_other( $orderid,$gateway_slug,$payment_details='',$payment_response='',$payment_status='processing', $redirect='yes' ){
		$package_order=$this->get_topup_order_by_id($orderid);
		$payment_details = !empty($payment_details) ? $payment_details : $package_order->payment_gateway_transaction_id;
		$payment_response = !empty($payment_response) ? $payment_response : $package_order->payment_response;

		$this->update_topup_prchase_status($orderid, $payment_status, $payment_details, $payment_response, $gateway_slug, $redirect);
	}
	public function payment_failed($orderid,$gateway_slug,$payment_details='',$payment_response='', $redirect='yes'){
		$package_order=$this->get_topup_order_by_id($orderid);
		$payment_details = !empty($payment_details) ? $payment_details : $package_order->payment_gateway_transaction_id;
		$payment_response = !empty($payment_response) ? $payment_response : $package_order->payment_response;

		$this->update_topup_prchase_status($orderid, "cancelled", $payment_details, $payment_response, $gateway_slug, $redirect);
	}

	function get_topup_order_by_id($order_id=0){
		$select_package = "select * from ".$this->_wpdb->prefix."job_topup_orders where id='$order_id'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}


	function get_topup_orders_pagination($gateway='',$limit=array(),$order_by='id',$order = 'desc'){
		if($gateway!=''){
			$where = " and payment_gateway_name='{$gateway}' ";
		}else{
			$where ='';
		}
		if(!empty($limit) && $limit[0]>=0 && $limit[1]>0){
			$limit = "limit {$limit['0']},{$limit['1']}";
		}else{
			$limit = "";
		}
		$order_str = " order by $order_by ".$order;
		$select_package = "select * from ".$this->_wpdb->prefix."job_topup_orders where 1 {$where} {$order_str} {$limit} ";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r:0;
	}

	function get_package($package_id=0){
		if($package_id==0){
			$package_id = $this->_package_id ;
		}
				$ss = "select * from ".$this->_wpdb->prefix."job_topup_packages where id = ".$package_id." order by cost asc";
		$r = $this->_wpdb->get_results($ss);

		foreach ($r as $row) {
			$this->_package_cost_original = $row->cost;
						$this->_package_cost = wpjobster_formats_special_exchange( $row->cost , '1', $this->_currency );
			$this->_package_credit_original = $row->credit;
						$this->_package_credit = wpjobster_formats_special_exchange( $row->credit , '1', $this->_currency );
			$this->_package_description_cost = sprintf(__(" package buying for %s", "wpjobster"), wpjobster_get_show_price_classic($this->_package_cost,2));
			$this->_package_description_credit = sprintf(__("%s Top Up Package", "wpjobster"), wpjobster_get_show_price($row->credit, 2));
		}
	}

	function update_topup_prchase_status($order_id, $status,$transaction_id=0, $payment_response='', $payment_gateway_slug, $redirect){
		$tm = time();
		$sql = " update ".$this->_wpdb->prefix."job_topup_orders set  payment_status='$status', paid_on='$tm',payment_gateway_transaction_id='$transaction_id' where id='$order_id'";
		$update_result = $this->_wpdb->query($sql);
		$payment['order_id'] =$order_id ;
		$payment['status'] = $status;
		$payment['payment_type']='topup';
		$payment['payment_response']=$payment_response;
		$payment['payment_details']=$transaction_id;
		$this->update_payment($payment);
		if($status=='1' || $status=='completed'){
			if( $redirect == 'yes' ) {
				$this->goto_topup_page($order_id);
			}
		}else{
			if( $redirect == 'yes' ) {
				$this->goto_topup_fail_page($order_id);
			}
		}
	}

	function insert_topup_prchase($topup_order=array()){
		if(empty($topup_order)){return 0;}
		$tm = time();
		$package_topup = $this->get_package($topup_order['package_id']);
		$tax_amount = 0;
		$sql = " insert into ".$this->_wpdb->prefix."job_topup_orders set package_id= '".$topup_order['package_id']."' ,"
			. " user_id='".$topup_order['user_id']."', package_amount='".$topup_order['price_original']."', added_on='".$tm."',"
					. " payment_status='pending',payment_gateway_name ='{$topup_order['payment_gateway_name']}',tax='{$tax_amount}',"
		. " package_cost_without_tax=".$this->_package_cost.",package_credit_without_tax=".$this->_package_credit.",currency = '".$this->_currency."'  ";
		$insert_result = $this->_wpdb->query($sql);
		$order_id = $this->_wpdb->insert_id;
		$payment_info['payment_status'] = 0;
		$payment_info['payment_gateway'] = $topup_order['payment_gateway_name'];
		$payment_info['payment_type'] = 'topup';
		$payment_info['payment_type_id'] = $order_id;
		$payment_info['fees'] = 0;
		$payment_info['amount'] = $topup_order['price_original'];
		$payment_info['tax'] = 0;

		$payment_info['currency'] = get_option('wpjobster_currency_1');
		$payment_info['final_amount'] = $topup_order['price_original'];
		$payment_info['final_amount_exchanged'] = $topup_order['package_amount'];
		$payment_info['final_amount_currency']=$topup_order['currency'];
		$order = $this->insert_payment($payment_info);
		return $order_id ;
	}


	function goto_topup_page( $order_id, $gateway='' ){
		if($gateway==''){
			$gateway = $this->_payment_gateway;
		}
		if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
			wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-10");
		}else{
			wp_redirect(get_bloginfo('siteurl')."/?jb_action=show_bank_details&oid={$order_id}&payment_type=topup");
		}
		exit;
	}
	function goto_topup_fail_page( $order_id, $gateway='' ){
		if($gateway==''){
			$gateway = $this->_payment_gateway;
		}
		if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
			wp_redirect($_SERVER['HTTP_REFERER']."&status=fail&active_tab=tabs-10");
		}else{
			wp_redirect(get_bloginfo('siteurl')."/?jb_action=show_bank_details&oid=$order_id&payment_type=topup");
		}
		exit;
	}
}
