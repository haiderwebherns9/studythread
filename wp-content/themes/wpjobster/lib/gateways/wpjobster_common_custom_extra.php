<?php
include_once 'wpjobster_common_payment.php';
class WPJ_Common_Custom_Extra extends WPJ_Common_Payment{
	public $_currency,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_order_id,$_custom_extra_id,$_myaccount_lnk,$_payable_amount,$_payment_gateway,$_payment_type;

	public function payment_process($order_id, $payment_gateway_slug, $payment_details, $response, $redirect='yes'){
		$custom_extra_order=$this->get_custom_extra_order_by_id($order_id);
		if(is_array($response)){
			$payment_response = json_encode($response);
		}else{
			$payment_response = $response;
		}
		$pmt_row = $this->get_order_by_type_and_id($order_id,'custom_extra');
		if($pmt_row){
			$response_array['total'] = $pmt_row->final_amount;
		}else{
			$response_array['total'] = $custom_extra_order->payable_amount;
		}
		$response_array['mc_gross'] = $custom_extra_order->payable_amount;
		$response_array['order_id'] = $custom_extra_order->order_id;
		$response_array['custom_extra_id'] = $custom_extra_order->custom_extra_id;
		$response_array['mc_currency'] = $custom_extra_order->currency;
		$response_array['uid'] = $custom_extra_order->user_id;
		$payment_details = !empty($payment_details) ? $payment_details : $custom_extra_order->payment_gateway_transaction_id;
		$payment_response = !empty($payment_response) ? $payment_response : $custom_extra_order->payment_response;
		$this->complete_custom_extra_process($response_array);
		$this->update_custom_extra_order_status($order_id,
			'completed',$payment_details, //-1 stands for failed
			$payment_response);
		if( $redirect=='yes' ) {
			$this->goto_order_page($payment_gateway_slug, $response_array);
		}
	}

	public function payment_failed($order_id,$gateway_slug,$payment_details='',$payment_response='', $redirect='yes'){
		$custom_extra_order=$this->get_custom_extra_order_by_id($order_id);
		$payment_details = !empty($payment_details) ? $payment_details : $custom_extra_order->payment_gateway_transaction_id;
		$payment_response = !empty($payment_response) ? $payment_response : $custom_extra_order->payment_response;
		$this->update_custom_extra_order_status($order_id,
			'cancelled',$payment_details, //-1 stands for failed topup
			$payment_response);

		if( $redirect == 'yes' ) {
			$this->goto_order_fail_page($gateway_slug,$custom_extra_order);
		}
	}

	public function __construct($payment_gateway='') {
		parent::__construct($payment_gateway);
		global $wp_query,$wpdb,$current_user;

		$this->_payment_type = 'custom_extra';
		$this->_payment_gateway=$payment_gateway;
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;
		add_action("wpjobster_custom_extra_payment_success",array($this,"payment_process"),10,5);
		add_action("wpjobster_custom_extra_payment_failed",array($this,"payment_failed"),10,5);
		if(isset($_GET['site_currency'])){
			$this->_currency = strtoupper($_GET['site_currency']);
		}elseif(isset($_COOKIE["site_currency"])){
			$this->_currency=strtoupper($_COOKIE["site_currency"]);
		}else{
			$this->_currency=wpjobster_get_currency();
		}
		$this->_myaccount_lnk = wpjobster_my_account_link();

		if(isset($_GET['oid']) && isset($_GET['custom_extra'])){

			$this->_order_id 	= isset($_GET['oid'])?$_GET['oid']:'0';
			$this->_custom_extra_id 	= isset($_GET['custom_extra'])?$_GET['custom_extra']:false;
			$ord = wpjobster_get_order($this->_order_id);
			$custom_extras = json_decode($ord->custom_extras);
			if ( ! $this->_custom_extra_id ) $this->_custom_extra_id = 0;
			$total = $custom_extras[$this->_custom_extra_id]->price;
			$wpjobster_enable_site_tax   = get_option('wpjobster_enable_site_tax');
			$this->_wpjobster_tax_percent=0;
			if( $wpjobster_enable_site_tax == 'yes'  ):
				$master_total=0;
				$country_code = user($this->_current_user->ID, 'country_code');
				$this->_wpjobster_tax_percent=wpjobster_get_tax($country_code);
			endif;
			$this->_custom_extra_amount = $total;
			$total= wpjobster_formats_special_exchange( $total, '1', $this->_currency );
			$total = wpjobster_formats_special($total, 2);
			$this->_tax = wpjobster_formats_special($total*$this->_wpjobster_tax_percent/100,2);
			$this->_payable_amount = $total+$this->_tax;

		}
		add_filter("wpjobster_insert_custom_extra_order",array($this,'insert_custom_extra_order'),10,2);
	}

	function get_custom_extra_orders_pagination($gateway='',$limit=array(),$order_by='id', $order='desc'){
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

		$select_package = "select * from ".$this->_wpdb->prefix."job_custom_extra_orders where 1 {$where} {$order_str} {$limit}";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r:0;

	}

	function complete_custom_extra_process($response_array= array()){
		$payed_amt = $response_array['mc_currency']."|".$response_array['mc_gross'];
		if($response_array['order_id']!=false && $response_array['custom_extra_id']!=null){
			$ord = wpjobster_get_order($response_array['order_id']);
			$custom_extras = json_decode($ord->custom_extras);
			$custom_extra = $custom_extras[$response_array['custom_extra_id']];
			$custom_extra->paid = true;

			$buyer_processing_fees_orignal = wpjobster_get_site_processing_fee( $custom_extra->price, 0, 0);
			$tax_orignal = wpjobster_get_site_tax($custom_extra->price,0,0,$buyer_processing_fees_orignal);

			$tax = wpjobster_formats_special_exchange( $tax_orignal, '1', $response_array['mc_currency'] );
			$buyer_processing_fees = wpjobster_formats_special_exchange( $buyer_processing_fees_orignal, '1', $response_array['mc_currency'] );

			if($buyer_processing_fees_orignal)
				$custom_extra->processing_fees = $buyer_processing_fees_orignal;
			if($tax_orignal)
				$custom_extra->tax = $tax_orignal;
			$custom_extra->paid = true;

			wpjobster_update_order_meta($response_array['order_id'], 'custom_extras', json_encode($custom_extras));

			// update expected_delivery
			wpj_update_expected_delivery_add_days( $ord, $custom_extras[$response_array['custom_extra_id']]->delivery );

			notify_after_custom_extra_pay($response_array['order_id'], $response_array['uid'],  $response_array['total'], $response_array['mc_currency'], $response_array['custom_extra_id']);
			$pref = $this->_wpdb->prefix;
			$datemade = current_time('timestamp',0);

			$g1 = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','-34','".$response_array['order_id']."','".$response_array['custom_extra_id']."')";
			$this->_wpdb->get_results($g1);
			wpj_update_user_notifications( wpj_get_seller_id( $ord ), 'notifications', +1 );

			update_user_meta(  $response_array['uid'], 'uz_last_order_ok', '1' );
		}
		return true;

	}

	function update_custom_extra_order_status($order_id,
		$status,$transaction_id=0,
		$payment_response=''){
		$tm = time();
		$sql = " update ".$this->_wpdb->prefix."job_custom_extra_orders set  payment_status='$status', paid_on='$tm',"
			   . " payment_gateway_transaction_id='$transaction_id',"
			   . "payment_response='".$payment_response."' where id='$order_id'";
		$update_result = $this->_wpdb->query($sql);

		$payment['order_id'] = $order_id;
		$payment['status']=$status;
		$payment['payment_type']='custom_extra';
		$payment['payment_response']=$payment_response; //'custom_extra';
		$payment['payment_details']=$transaction_id; //'custom_extra';
		$this->update_payment($payment);

	}

	function insert_custom_extra_order($custom_extra_order=array()){
		if(empty($custom_extra_order)){
			$custom_extra_order= array('order_id'=>$this->_order_id,'custom_extra_id'=>$this->_custom_extra_id,'user_id'=>$this->_current_user->ID,
						'custom_extra_amount'=>$this->_custom_extra_amount,'payment_status'=>'pending','payment_gateway_name'=>$this->payment_gateway,
						'tax'=>$this->_tax,'payable_amount'=>$this->_payable_amount,'currency'=>$this->_currency
			);
			if($custom_extra_order['order_id']=='' || $custom_extra_order['custom_extra_id']==''){
				return 0;
			}
		}
		if($custom_extra_order['payment_status']==0 || $custom_extra_order['paid']==0){
			$custom_extra_order['payment_status'] = 'pending';
		}
		if(!isset($custom_extra_order['payment_gateway_name']) || $custom_extra_order['payment_gateway_name']==''){
			$custom_extra_order['payment_gateway_name']=$this->_payment_gateway;
		}
		$tm = time();
		$sql = " insert into ".$this->_wpdb->prefix."job_custom_extra_orders set order_id='".$custom_extra_order['order_id']."',"
			   . " custom_extra_id= '".$custom_extra_order['custom_extra_id']."' ,"
			   . " user_id='".$custom_extra_order['user_id']."', custom_extra_amount='".$custom_extra_order['custom_extra_amount']."', payable_amount='".$custom_extra_order['payable_amount']."', added_on='".$tm."',"
			   . " payment_status='".$custom_extra_order['payment_status']."',currency='{$custom_extra_order['currency']}',"
			   . " tax='{$custom_extra_order['tax']}',payment_gateway_name ='{$custom_extra_order['payment_gateway_name']}'";
		$insert_result = $this->_wpdb->query($sql);

		$order_id = $this->_wpdb->insert_id;

		$payment_info['payment_status'] = 'pending';
		$payment_info['payment_gateway'] = $custom_extra_order['payment_gateway_name'];
		$payment_info['payment_type'] = $this->_payment_type;
		$payment_info['payment_type_id'] = $order_id;
		$payment_info['fees'] = $custom_extra_order['fees_orignal'];
		$payment_info['amount'] = $custom_extra_order['custom_extra_amount'];
		$payment_info['tax'] = $custom_extra_order['tax_orignal'];

		$payment_info['currency'] = get_option('wpjobster_currency_1');

		$payment_info['final_amount'] = $custom_extra_order['total_amount_orignal'];
		$payment_info['final_amount_exchanged'] = $custom_extra_order['payable_amount'];
		$payment_info['final_amount_currency']=$custom_extra_order['currency'];
		$this->insert_payment($payment_info);
		return $order_id ;
	}

	function get_custom_extra_order_by_id($order_id=0){
		$select_package = "select * from ".$this->_wpdb->prefix."job_custom_extra_orders where id='$order_id'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}

	function goto_order_page( $gateway, $response_array ){
		do_action('wpjobster_custom_extra_payment_completed',$gateway,$response_array);

			if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
				wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-12");
			}else{
				wp_redirect(get_bloginfo('siteurl')."/?jb_action=chat_box&oid=".$response_array['order_id']);
			}

		die();
	}

	function goto_order_fail_page( $gateway_slug, $response_array ){
		if($gateway_slug=='banktransfer'){
			$ord = wpjobster_get_order($response_array->order_id);
			$post_a         = get_post($ord->pid);
			$custom_extras = json_decode($ord->custom_extras);
			if(!$custom_extras[$response_array->custom_extra_id]->cancelled){
				$custom_extras[$response_array->custom_extra_id]->cancelled = true;
				wpjobster_update_order_meta($response_array->order_id, 'custom_extras', json_encode($custom_extras));

				if(strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
					wpjobster_send_email_allinone_translated('custom_extra_cancelled_by_admin',          $ord->uid,              false, false, $response_array->order_id);
					wpjobster_send_email_allinone_translated('custom_extra_cancelled_by_admin_seller',   $post_a->post_author,   false, false, $response_array->order_id);

					wpjobster_send_sms_allinone_translated('custom_extra_cancelled_by_admin',            $ord->uid,              false, false, $response_array->order_id);
					wpjobster_send_sms_allinone_translated('custom_extra_cancelled_by_admin_seller',     $post_a->post_author,   false, false, $response_array->order_id);
				}
				else{
					wpjobster_send_email_allinone_translated('cancel_custom_extra', $ord->uid, $this->_current_user, false, $response_array->order_id);
					wpjobster_send_sms_allinone_translated('cancel_custom_extra', $ord->uid, $this->_current_user, false, $response_array->order_id);

					$pref = $this->_wpdb->prefix;
					$datemade = current_time('timestamp',0);

					$g1 = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','-33','$response_array->order_id','$response_array->custom_extra_id')";
					$this->_wpdb->get_results($g1);
					wpj_update_user_notifications( wpj_get_seller_id( $ord ), 'notifications', +1 );
				}
			}
		}

			if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
				wp_redirect($_SERVER['HTTP_REFERER']."&status=fail");
			}else{
				wp_redirect(get_bloginfo('siteurl')."/?jb_action=chat_box&oid=".$response_array->order_id."&status=fail");
			}

		exit;
	}

	function get_main_order_id_from_orderid($order_id){
		// main order id from from main orders table
		$sql = " select order_id from ".$this->_wpdb->prefix."job_custom_extra_orders
				 where id='$order_id'";
		$select_result = $this->_wpdb->get_results($sql);
		$select_row = $select_result[0];
		return $select_row -> order_id;
	}
}
