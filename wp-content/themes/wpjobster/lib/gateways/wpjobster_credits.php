<?php
class WPJ_Credits {
	public $_currency,$_package_id,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_unique_id;
	public function __construct($gateway='credit') {
		$this->_payment_gateway=$this->_unique_id=$gateway;
		add_action("show_credit_form",array($this,"show_credit_form"),10,2);
	}

	function show_credit_form($payment_type,$common_details){
		//echo "<pre>";
		$order_id = $common_details['order_id'];

		do_action("wpjobster_".$payment_type."_payment_success",$common_details['order_id'],$this->_unique_id,$transaction_id,json_encode($common_details));

		$wpjobster_success_page_id=get_option("wpjobster_credit_success_page");
		if($wpjobster_success_page_id!=''){
			wp_redirect(get_permalink($wpjobster_success_page_id));
		}else{
			wp_redirect(get_bloginfo('site_url') . "/?jb_action=chat_box&oid={$order_id}" );
		}
	}
}

$cl = new WPJ_Credits();
