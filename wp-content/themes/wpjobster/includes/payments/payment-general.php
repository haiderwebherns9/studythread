<?php
if( ! function_exists( 'get_wpjobster_payment_gateways' ) ){
	function get_wpjobster_payment_gateways(){
		$wpjobster_payment_gateways = array(
			"100"=>array("label"=>__("PayPal",'wpjobster'),"show_settigs_form"=>"show_paypal_form","action"=>"show_paypal_form","unique_id"=>"paypal","response_action"=>"paypal_response"),
			"800"=>array("label"=>__("COD",'wpjobster'),"show_settigs_form"=>"show_cod_form","action"=>"show_cod_form","unique_id"=>"cod"),
			"811"=>array("label"=>__("Bank Transfer",'wpjobster'),"show_settigs_form"=>"show_banktransfer_form","action"=>"show_banktransfer_form","response_action"=>"banktransfer_response","unique_id"=>"banktransfer")
		);
		$wpjobster_payment_gateways = apply_filters( 'wpjobster_payment_gateways', $wpjobster_payment_gateways );
		ksort( $wpjobster_payment_gateways );
		return $wpjobster_payment_gateways;
	}
}

function wpjobster_purchase_completed_functions($uid) {
	update_user_meta( $uid, 'uz_last_order_not_tracked', '1' );
}
