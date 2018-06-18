<?php
class WPJ_Common_Payment{
	public $table_name,$payment_gateway;

	public function __construct($payment_gateway='') {
		global $wp_query,$wpdb,$current_user;
		$this->payment_gateway = $payment_gateway;
		$this->table_name=$wpdb->prefix."job_payment_received";
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;
		$this->dbprefix = $wpdb->prefix;
		remove_all_actions("wpjobster_store_payment_gateway_log",7);
		add_action("wpjobster_store_payment_gateway_log",array($this,"wpjobster_store_payment_gateway_log"),7,1);
	}

	public function wpjobster_store_payment_gateway_log($payment){
		$tm = time();
		$order_id = $payment['order_id'];
		$payment_type = $payment['payment_type'];
		$transaction_id=$payment['transaction_id'];
		$payment_response=$payment['payment_response'];
		$payment_status=$payment['payment_status'];
		$gateway =$payment['gateway'];
		$sql = " insert into ".$this->dbprefix."job_payment_gateway_log set order_id='{$order_id}',"
		. " payment_gateway= '".$gateway."' ,"
		. " payment_type= '".$payment_type."' ,"
		. " transaction_id= '".$transaction_id."' ,"
		. " payment_status='{$payment_status}', "
		. " response_received = '{$payment_response}', "
		. " datemade='".$tm."' ";
		$insert_result = $this->_wpdb->query($sql);
		$query = htmlspecialchars( $this->_wpdb->last_query, ENT_QUOTES );

		return $this->_wpdb->insert_id;
	}

	public function insert_payment($payment=array()){
		$tm = time();
		if(isset($payment['payment_made_on'])){

			$payment_made_on = " ,payment_made_on='{$payment['payment_made_on']}'" ;

		}else{
			$payment_made_on = "";
		}
		$sql = " insert into ".$this->table_name." set payment_status='".$payment['payment_status']."',"
		. " payment_gateway= '".$payment['payment_gateway']."' ,payment_type= '".$payment['payment_type']."' ,"
		. " payment_type_id= '".$payment['payment_type_id']."' , fees='".$payment['fees']."', amount='".$payment['amount']."', datemade='".$tm."',"
		. " tax='{$payment['tax']}',currency='{$payment['currency']}',final_amount='{$payment['final_amount']}',final_amount_exchanged='{$payment['final_amount_exchanged']}',"
		. " final_amount_currency='{$payment['final_amount_currency']}' {$payment_made_on}";
		$insert_result = $this->_wpdb->query($sql);
		return $this->_wpdb->insert_id;
	}

	public function update_payment($payment){
		$order_id = $payment['order_id'] ;
		if($payment['status']=='1'){
			$status = 'completed';
		}elseif($payment['status']=='-1'){
			$status = 'cancelled';
		}elseif($payment['status']=='0'){
			$status = 'pending';
		}else{
			$status = $payment['status'];
		}
		$payment_type = $payment['payment_type'];
		$payment_response = $payment['payment_response'];
		$payment_details = $payment['payment_details'];

		if($status=='completed'){
			$payment_made_on = " ,payment_made_on='".  time()."'" ;
		}else{
			$payment_made_on = "";
		}
		$sql = " update ".$this->table_name." set payment_status='{$status}',payment_response='{$payment_response}',payment_details='{$payment_details}' {$payment_made_on} "
		. " where payment_type_id={$order_id} and payment_type='{$payment_type}' ";
		$insert_result = $this->_wpdb->query($sql);
	}

	public function get_order_by_type_and_id($order_id,$payment_type){
		$sql = " select * from  ".$this->table_name."  "
		. " where payment_type_id={$order_id} and payment_type='{$payment_type}' ";
		$result = $this->_wpdb->get_results($sql);
		if($result[0]) return $result[0]; else 0;
	}

}
