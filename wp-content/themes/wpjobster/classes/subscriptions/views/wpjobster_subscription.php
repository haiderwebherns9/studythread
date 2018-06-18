<?php
$wpjobster_subscription_enabled = get_option('wpjobster_subscription_enabled');

if (!is_user_logged_in()) {
	wp_redirect(get_bloginfo('url'));
}

if($wpjobster_subscription_enabled=='yes'){
	if(isset($_GET['method']) && $_GET['method']){
		$payment_method = $_GET['method'];
	}else{
		$payment_method = '';
	}
	if(isset($_GET['jb_action']) && $_GET['jb_action']){
		$jb_action = $_GET['jb_action'];
	}else{
		$jb_action = '';
	}
	include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
	$wpjobster_subscription = new wpjobster_subscription($payment_method);
	global $wp_query;
	global $above_top ;

	$above_top = '';
	if(isset($_GET['sub_error']) && $_GET['sub_error']=='1'||isset($_GET['thankyou']) && $_GET['thankyou']=='1' ){

		if(isset($_GET['sub_error']) && $_GET['sub_error']=='1'){
			$error='1';
		}else{
			$error='0';
		}

		$message_code = $_GET['message_code'];
		$above_top = $wpjobster_subscription->show_messages($error,$message_code );

	}

	if( get_option( 'wpjobster_'.$payment_method.'_enablepopup' ) == 'yes' ){
		echo '<div class="payment-gateway-popup"></div>';
	}

	if($payment_method != 'credits' && $payment_method != ''){
		if( ! isset( $_GET['sub_id'] ) ){
			wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id'))."?sub_action=details&sub_error=1&message_code='no_plan_selected'");
			exit;
		}

		$sub_result = $wpjobster_subscription->send_payment_details($payment_method, $jb_action);
	}elseif($payment_method == 'credits'){
		if( ! isset( $_GET['sub_id'] ) ){
			wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id'))."?sub_action=details&sub_error=1&message_code='no_plan_selected'");
			exit;
		}

		$cr = wpjobster_get_credits(get_current_user_id());
		$nm = explode("-",$_GET['sub_id']);
		$sub_type =$nm[0];
		$sub_level =$nm[1];
		$sub_id = 'wpjobster_subscription_'.$sub_type.'_amount_'.$sub_level;
		$sub_amount = get_option($sub_id);

		if($cr >= $sub_amount){
			$sub_result = $wpjobster_subscription->do_subscription('','',$payment_method);

			if(!isset($sub_result['error'])){
				wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id'))."?sub_action=details&thankyou=1&message_code=".$sub_result);
			}
			else{
				wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id'))."?sub_action=schedule&sub_error=1&message_code=".$sub_result['error']);
			}
		}else{
			wp_redirect(get_permalink(get_option('wpjobster_my_account_payments_page_id')).'topup');
		}
	}else{
		if(isset($_GET['sub_action']) && $_GET['sub_action']=='process_cancellation'){
			$wpjobster_subscription->process_cancellation();
			wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id')));
		}else{
			$wpjobster_subscription->chose_subscriptions();
		}
	}
}
