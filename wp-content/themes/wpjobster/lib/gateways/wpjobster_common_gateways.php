<?php
class WPJ_Common_Gateways{
	public $_currency,$_job_id,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_h_date_start,$_c_date_start,$_s_date_start,$_feature_pages,$_myaccount_lnk,$_payable_amount,$_payment_gateway;
	public function __construct() {
		global $wp_query,$wpdb,$current_user;
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;

		if(isset($_GET['site_currency']))
		{
			$this->_currency = strtoupper($_GET['site_currency']);
		}
		else
		{
			$this->_currency=strtoupper($_COOKIE["site_currency"]);
		}
		$this->_myaccount_lnk = wpjobster_my_account_link();
	}

	function get_featured_orders_pagination($gateway='',$limit=array(0,10)){
		if($gateway!=''){
			$where = " and payment_gateway_name='{$gateway}' ";
		}else{
			$where ='';
		}
		if(!empty($limit) && $limit[0]>0 && $limit[1]>0){
			$limit = "limit {$limit['0']},{$limit['1']}";
		}else{
			$limit = "";
		}
		$select_package = "select * from ".$this->_wpdb->prefix."job_featured_orders where 1 {$where} {$limit}";
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
		$sql = " update ".$this->_wpdb->prefix."job_featured_orders set  paid='$status', paid_on='$tm',"
				. " payment_gateway_transaction_id='$transaction_id',"
				. "payment_response='".$payment_response."' where id='$order_id'";
		$update_result = $this->_wpdb->query($sql);
	}

	function insert_featured_order($featured_order=array()){
		if(empty($featured_order)){
			if(isset($_GET['feature_pages'])){

				$this->_job_id 	= isset($_GET['jobid'])?$_GET['jobid']:'0';
				$this->_h_date_start 	= isset($_GET['h_date_start'])?$_GET['h_date_start']:false;
				$this->_c_date_start 	= isset($_GET['c_date_start'])?$_GET['c_date_start']:false;
				$this->_s_date_start 	= isset($_GET['s_date_start'])?$_GET['s_date_start']:false;
				$this->_feature_pages 	= isset($_GET['feature_pages'])?$_GET['feature_pages']:false;
				$total = 0;
				if(strpos($this->_feature_pages ,'h') !== false)
					$total+=get_option('wpjobster_featured_price_homepage');
				if(strpos($this->_feature_pages ,'c') !== false)
					$total+=get_option('wpjobster_featured_price_category');
				if(strpos($this->_feature_pages ,'s') !== false)
					$total+=get_option('wpjobster_featured_price_subcategory');

				$wpjobster_enable_site_tax   = get_option('wpjobster_enable_site_tax');
				$this->_wpjobster_tax_percent=0;
				if( $wpjobster_enable_site_tax == 'yes'  ):
					$master_total=0;
					$country_code = user($this->_current_user->ID, 'country_code');
					$this->_wpjobster_tax_percent=wpjobster_get_tax($country_code);
				endif;
				$this->_featured_amount = $total;
				$total= wpjobster_formats_special_exchange( $total, '1', $this->_currency );
				$total = wpjobster_formats_special($total, 2);
				$this->_tax = wpjobster_formats_special($total*$this->_wpjobster_tax_percent/100,2);
				$this->_payable_amount = $total+$this->_tax;
			}
			$job = get_post($this->_job_id);
			$job_title = $job->post_title;
			$featured_order= array('feature_pages'=>$this->_feature_pages,'job_id'=>$this->_job_id,'user_id'=>$this->_current_user->ID,
			'featured_amount'=>$this->_featured_amount,'paid'=>0,'payment_gateway_name'=>$this->payment_gateway,
			'h_date_start'=>$this->_h_date_start,'c_date_start'=>$this->_c_date_start,'s_date_start'=>$this->_s_date_start
				,'tax'=>$this->_tax,'payable_amount'=>$this->_payable_amount,'currency'=>$this->_currency
				);
			if($featured_order['feature_pages']==''){
				return 0;
			}
		}
		$tm = time();
		$sql = " insert into ".$this->_wpdb->prefix."job_featured_orders set feature_pages='".$featured_order['feature_pages']."',"
				. " job_id= '".$featured_order['job_id']."' ,"
		. " user_id='".$featured_order['user_id']."', featured_amount='".$featured_order['featured_amount']."', payable_amount='".$featured_order['payable_amount']."', added_on='".$tm."',"
					. " paid='0',currency='{$featured_order['currency']}',h_date_start='{$featured_order['h_date_start']}',s_date_start='{$featured_order['s_date_start']}',"
				. "c_date_start='{$featured_order['c_date_start']}',tax='{$featured_order['tax']}',payment_gateway_name ='{$featured_order['payment_gateway_name']}'";
		$insert_result = $this->_wpdb->query($sql);
		$order_id = $this->_wpdb->insert_id;
		return $order_id ;
	}

	function get_featured_order_by_id($order_id=0){
		$select_package = "select * from ".$this->_wpdb->prefix."job_featured_orders where id='$order_id'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}

	function goto_featured_page(){
		wp_redirect(wpjobster_my_account_link());
		die();
	}
	function goto_featured_fail_page(){
		$this->goto_featured_page();
	}

	// custom_extra functions
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
			$custom_extras[$response_array['custom_extra_id']]->paid = true;
			wpjobster_update_order_meta($response_array['order_id'], 'custom_extras', json_encode($custom_extras));
			notify_after_custom_extra_pay($response_array['order_id'], $response_array['uid'],  $response_array['total'], $payed_amt, $response_array['custom_extra_id']);
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
			$custom_extra_order = array(
				'order_id'=>$this->_order_id,
				'custom_extra_id'=>$this->_custom_extra_id,
				'user_id'=>$this->_current_user->ID,
				'custom_extra_amount'=>$this->_custom_extra_amount,
				'payment_status'=>'pending',
				'payment_gateway_name'=>$this->payment_gateway,
				'tax'=>$this->_tax,
				'payable_amount'=>$this->_payable_amount,
				'currency'=>$this->_currency
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
		wp_redirect(wpjobster_my_account_link());
		die();
	}

	function goto_order_fail_page( $gateway_slug, $response_array ){
		$this->goto_order_page();
	}
}
