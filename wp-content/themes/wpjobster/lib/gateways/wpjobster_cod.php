<?php
class WPJ_Cod {
	public $_currency,$_package_id,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_unique_id;
	public function __construct($gateway='banktransfer') {
		$this->_payment_gateway=$this->_unique_id=$gateway;
		add_action("show_cod_form",array($this,"show_cod_form"),10,2);
		add_action("cod_response",array($this,"process_cod"),10,2);
	}

	function  process_cod($payment_type){
		//-----------------------------------------------------
		// Calculate Total
		//-----------------------------------------------------
	}
	function show_cod_form($payment_type,$common_details){
		$orderid = $common_details['order_id'];
		if ($orderid) {
			$wpjobster_paypal_success_page_id=get_option("wpjobster_banktransfer_success_page");
			if($wpjobster_success_page_id){
				wp_redirect(get_permalink($wpjobster_success_page_id));
			}else{
				wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$orderid);
			}
			exit;
		} else {
			echo __('Error while inserting the order. Please contact the site administrator.', 'wpjobster');
		}
	}
}

$cl = new WPJ_Cod();
