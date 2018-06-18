<?php
include_once 'wpjobster_common_payment.php';
class WPJ_Common_Featured extends WPJ_Common_Payment{
	public $_currency,$_job_id,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_h_date_start,$_c_date_start,$_s_date_start,$_feature_pages,$_myaccount_lnk,$_payable_amount,$_payment_gateway,$_payment_type;

	public function payment_process($order_id,$payment_gateway_slug,$payment_details,$response,$redirect='yes'){
		update_option('test', 'was_here_fe');
		$featured_order=$this->get_featured_order_by_id($order_id);
		if(is_array($response)){
			$payment_response = json_encode($response);
		}else{
			$payment_response = $response;
		}

		$featured_order_id = $featured_order->id;
		$job = get_post($featured_order->job_id);
		$amn = $credit = $featured_order->payable_amount;
		$uid = $featured_order->user_id;
		$user = get_userdata($uid);//, $default_lang)
		$tm = time();
		$orderid = $order_id;
		$pmt_row = $this->get_order_by_type_and_id($order_id,'feature');
		if($pmt_row){
			$response_array['total'] = $pmt_row->final_amount;
		}else{
			$response_array['total'] = $featured_order->payable_amount;
		}
		$response_array['mc_gross'] = $featured_order->payable_amount;
		$response_array['feature_pages'] = $featured_order->feature_pages;
		$response_array['mc_currency'] = $featured_order->currency;
		$response_array['pid']= $featured_order->job_id;
		$response_array['uid'] = $featured_order->user_id;
				$payment_details = !empty($payment_details) ? $payment_details : $featured_order->payment_gateway_transaction_id;
				$payment_response = !empty($payment_response) ? $payment_response : $featured_order->payment_response;
		$this->complete_feature_process($response_array);
		$this->update_featured_order_status($order_id,
					'completed',$payment_details, //-1 stands for failed topup
					$payment_response);
		do_action( 'wpjobster_featured_payment_completed', $order_id );
				if( $redirect == 'yes' ) {
					$this->goto_featured_page($payment_gateway_slug,$order_id);
				}
	}
	public function payment_failed($order_id,$gateway_slug,$payment_details='',$payment_response='', $redirect='yes'){

				$featured_order=$this->get_featured_order_by_id($order_id);
				$payment_details = !empty($payment_details) ? $payment_details : $featured_order->payment_gateway_transaction_id;
				$payment_response = !empty($payment_response) ? $payment_response : $featured_order->payment_response;

		$this->update_featured_order_status($order_id,
			'cancelled',$payment_details, //-1 stands for failed topup
			$payment_response);
				if( $redirect == 'yes' ) {
					$this->goto_featured_fail_page($gateway_slug,$order_id);
				}


	}
	/*
	 * Function will handle the payment status that is other thna cancel/failure and success
	 */
	public function payment_other( $order_id, $gateway_slug, $payment_details = '', $payment_response = '', $payment_status = 'processing', $redirect='yes' ){

				$featured_order=$this->get_featured_order_by_id($order_id);
				$payment_details = !empty($payment_details) ? $payment_details : $featured_order->payment_gateway_transaction_id;
				$payment_response = !empty($payment_response) ? $payment_response : $featured_order->payment_response;

				$this->update_featured_order_status($order_id,
			$payment_status,$payment_details, //-1 stands for failed topup
			$payment_response);
				if( $redirect == 'yes' ) {
					$this->goto_featured_page($gateway_slug,$order_id);
				}
	}
	public function __construct( $payment_gateway='' ) {
		parent::__construct( $payment_gateway );
		global $wp_query, $wpdb, $current_user;

		$this->_payment_type = 'feature';
		$this->_payment_gateway = $payment_gateway;
		$this->_current_user = wp_get_current_user();
		$this->_wpdb = $wpdb;
		add_action( "wpjobster_feature_payment_success", array( $this, "payment_process" ), 10, 5 );
		add_action( "wpjobster_feature_payment_failed", array( $this, "payment_failed" ), 10, 5 );
		add_action( "wpjobster_feature_payment_other", array( $this, "payment_other" ), 10, 6 );
		if ( isset( $_GET['site_currency'] ) ) {
			$this->_currency = strtoupper( $_GET['site_currency'] );
		} elseif ( isset( $_COOKIE["site_currency"] ) ) {
			$this->_currency = strtoupper( $_COOKIE["site_currency"] );
		}
		$this->_myaccount_lnk = wpjobster_my_account_link();

		if ( isset( $_GET['feature_pages'] ) ) {

			$this->_job_id        = isset( $_GET['jobid'] ) ? $_GET['jobid'] : '0';
			$this->_h_date_start  = isset( $_GET['h_date_start'] ) ? $_GET['h_date_start'] : false;
			$this->_c_date_start  = isset( $_GET['c_date_start'] ) ? $_GET['c_date_start'] : false;
			$this->_s_date_start  = isset( $_GET['s_date_start'] ) ? $_GET['s_date_start'] : false;
			$this->_feature_pages = isset( $_GET['feature_pages'] ) ? $_GET['feature_pages'] : false;

			$total = 0;

			if ( strpos( $this->_feature_pages ,'h' ) !== false )
				$total += get_option( 'wpjobster_featured_price_homepage' );
			if ( strpos( $this->_feature_pages ,'c' ) !== false )
				$total += get_option( 'wpjobster_featured_price_category' );
			if ( strpos( $this->_feature_pages ,'s' ) !== false )
				$total += get_option( 'wpjobster_featured_price_subcategory' );

			$wpjobster_enable_site_tax = get_option( 'wpjobster_enable_site_tax' );
			$this->_wpjobster_tax_percent = 0;
			if ( $wpjobster_enable_site_tax == 'yes' ) {
				$master_total = 0;
				$country_code = user( $this->_current_user->ID, 'country_code' );
				$this->_wpjobster_tax_percent = wpjobster_get_tax( $country_code );
			}
			$this->_featured_amount = $total;
			$total = wpjobster_formats_special_exchange( $total, '1', $this->_currency );
			$total = wpjobster_formats_special( $total, 2 );
			$this->_tax = wpjobster_formats_special( $total * $this->_wpjobster_tax_percent / 100, 2 );
			$this->_payable_amount = $total + $this->_tax;

		}
		add_filter( "wpjobster_insert_featured_order", array( $this,'insert_featured_order' ), 10, 2 );
	}

	function get_featured_orders_pagination($gateway='',$limit=array(),$order_by='id', $order='desc'){
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

		$select_package = "select * from ".$this->_wpdb->prefix."job_featured_orders where 1 {$where} {$order_str} {$limit}";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r:0;

	}

	function complete_feature_process($response_array= array()){
		$payed_amt = $response_array['mc_currency']."|".$response_array['mc_gross'];
		// update post featured fields
		if(strpos($response_array['feature_pages'],'h') !== false){
			update_featured_after_pay('homepage', $response_array['pid']);
		}
		if(strpos($response_array['feature_pages'],'c') !== false){
			update_featured_after_pay('category', $response_array['pid']);
		}
		if(strpos($response_array['feature_pages'],'s') !== false){
			update_featured_after_pay('subcategory', $response_array['pid']);
		}
		notify_after_featured_pay($response_array['pid'], $response_array['uid'],  $response_array['total'], $payed_amt);
		update_user_meta(  $response_array['uid'], 'uz_last_order_ok', '1' );
		return true;
	}

	function get_job($job_id=0){
		if($job_id==0){
			$job_id = $this->_job_id ;
		}
		$post = get_post($package_id);

	}

	function update_featured_order_status($order_id, $status,$transaction_id=0, $payment_response=''){
		$tm = time();
		$sql = " update ".$this->_wpdb->prefix."job_featured_orders set  payment_status='$status', paid_on='$tm',"
				. " payment_gateway_transaction_id='$transaction_id',"
				. "payment_response='".$payment_response."' where id='$order_id'";
		$update_result = $this->_wpdb->query($sql);

		$payment['order_id'] = $order_id;
		$payment['status']=$status;
		$payment['payment_type']='feature';
		$payment['payment_response']=$payment_response;//'feature';
		$payment['payment_details']=$transaction_id;//'feature';
		$this->update_payment($payment);

	}

	function insert_featured_order($featured_order=array()){
		if(empty($featured_order)){
			$job = get_post($this->_job_id);
			$job_title = $job->post_title;
			$featured_order= array('feature_pages'=>$this->_feature_pages,'job_id'=>$this->_job_id,'user_id'=>$this->_current_user->ID,
			'featured_amount'=>$this->_featured_amount,'payment_status'=>'pending','payment_gateway_name'=>$this->payment_gateway,
			'h_date_start'=>$this->_h_date_start,'c_date_start'=>$this->_c_date_start,'s_date_start'=>$this->_s_date_start
				,'tax'=>$this->_tax,'payable_amount'=>$this->_payable_amount,'currency'=>$this->_currency
				);
			if($featured_order['feature_pages']==''){
				return 0;
			}
		}
		if($featured_order['payment_status']==0 || $featured_order['paid']==0){
			$featured_order['payment_status'] = 'pending';
		}
		if(!isset($featured_order['payment_gateway_name']) || $featured_order['payment_gateway_name']==''){
			$featured_order['payment_gateway_name']=$this->_payment_gateway;
		}
		$tm = time();
		$sql = " insert into ".$this->_wpdb->prefix."job_featured_orders set feature_pages='".$featured_order['feature_pages']."',"
				. " job_id= '".$featured_order['job_id']."' ,"
		. " user_id='".$featured_order['user_id']."', featured_amount='".$featured_order['featured_amount']."', payable_amount='".$featured_order['payable_amount']."', added_on='".$tm."',"
					. " payment_status='".$featured_order['payment_status']."',currency='{$featured_order['currency']}',h_date_start='{$featured_order['h_date_start']}',s_date_start='{$featured_order['s_date_start']}',"
				. "c_date_start='{$featured_order['c_date_start']}',tax='{$featured_order['tax']}',payment_gateway_name ='{$featured_order['payment_gateway_name']}'";
		$insert_result = $this->_wpdb->query($sql);

		$order_id = $this->_wpdb->insert_id;

		$payment_info['payment_status'] = 'pending';
		$payment_info['payment_gateway'] = $featured_order['payment_gateway_name'];
		$payment_info['payment_type'] = $this->_payment_type;
		$payment_info['payment_type_id'] = $order_id;
		$payment_info['fees'] = $featured_order['fees_orignal'];
		$payment_info['amount'] = $featured_order['featured_amount'];
		$payment_info['tax'] = $featured_order['tax_orignal'];

		$payment_info['currency'] = get_option('wpjobster_currency_1');

		$payment_info['final_amount'] = $featured_order['total_amount_orignal'];
		$payment_info['final_amount_exchanged'] = $featured_order['payable_amount'];
		$payment_info['final_amount_currency']=$featured_order['currency'];
		$this->insert_payment($payment_info);
		return $order_id ;
	}

	function get_featured_order_by_id($order_id=0){
		$select_package = "select * from ".$this->_wpdb->prefix."job_featured_orders where id='$order_id'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}

	function goto_featured_page( $gateway, $order_id ){
		if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
			wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-11");
		}else{
			$job_id = $this->get_jobid_from_orderid($order_id);
			wp_redirect(get_bloginfo('siteurl')."/?jb_action=feature_job&status=success&jobid=".$job_id);
		}
		die();
	}
	function get_jobid_from_orderid($order_id){
		$sql = " select job_id from ".$this->_wpdb->prefix."job_featured_orders where id='$order_id'";
		$select_result = $this->_wpdb->get_results($sql);
		$select_row = $select_result[0];
		$job_id = $select_row -> job_id;
		return $job_id ;
	}
	function goto_featured_fail_page( $gateway, $order_id ){
		if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
			wp_redirect($_SERVER['HTTP_REFERER']."&status=fail");
		}else{
			$job_id = $this->get_jobid_from_orderid($order_id);
			wp_redirect(get_bloginfo('siteurl')."/?jb_action=feature_job&status=fail&jobid=".$job_id);
		}
		exit;
	}
}
