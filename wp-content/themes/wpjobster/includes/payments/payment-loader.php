<?php
function wpj_loader_page_vars(){
	$vars = array();

	if ( isset( $_GET['payment_type'] ) && $_GET['payment_type'] == 'job_purchase' ) {

		make_trans_completed('wpjobster_common_job_purchase','WPJ_Common_Job_Purchase','paypal');
		$wpjobster_paypal_success_page_id=get_option("wpjobster_paypal_success_page");
		if($wpjobster_paypal_success_page_id) $location = get_permalink($wpjobster_paypal_success_page_id);
		else $location = get_bloginfo('siteurl') . '/?jb_action=chat_box&oid=' . $_GET['oid'];

	}elseif( isset( $_GET['payment_type'] ) && $_GET['payment_type'] == 'topup' ) {

		make_trans_completed('wpjobster_common_topup','WPJ_Common_Topup','paypal');
		$wpjobster_paypal_success_page_id=get_option("wpjobster_paypal_success_page");
		if($wpjobster_paypal_success_page_id) $location = get_permalink($wpjobster_paypal_success_page_id);
		else $location = get_bloginfo('siteurl') . '/?jb_action=show_bank_details&payment_type=topup&oid=' . $_GET['oid'];

	}elseif( isset( $_GET['payment_type'] ) && $_GET['payment_type'] == 'feature' ) {

		$job_id = make_trans_completed('wpjobster_common_featured','WPJ_Common_Featured','paypal');
		$wpjobster_paypal_success_page_id=get_option("wpjobster_paypal_success_page");
		if($wpjobster_paypal_success_page_id) $location = get_permalink($wpjobster_paypal_success_page_id);
		else $location = get_bloginfo( 'siteurl' ) . '/?jb_action=feature_job&status=success&jobid=' . $job_id;

	}elseif( isset( $_GET['payment_type'] ) && $_GET['payment_type'] == 'custom_extra' ) {

		$order_id = make_trans_completed('wpjobster_common_custom_extra','WPJ_Common_Custom_Extra','paypal');
		$wpjobster_paypal_success_page_id=get_option("wpjobster_paypal_success_page");
		if($wpjobster_paypal_success_page_id) $location = get_permalink($wpjobster_paypal_success_page_id);
		else $location = get_bloginfo( 'siteurl' ) . '/?jb_action=chat_box&status=success&oid=' . $order_id;

	} elseif(isset( $_GET['payment_type'] ) && $_GET['payment_type'] == 'subscription'){
		if(isset( $_GET['final_status'] ) && $_GET['final_status'] == 'cancelled'){
			$location = get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) . '?sub_action=details&sub_error=1&message_code=cancelled';
		}else{
			make_trans_completed('wpjobster_subscription','wpjobster_subscription','paypal');
			$wpjobster_paypal_success_page_id=get_option("wpjobster_paypal_success_page");
			if($wpjobster_paypal_success_page_id) $location = get_permalink($wpjobster_paypal_success_page_id);
			else $location = get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) . '?sub_action=details&thankyou=1&message_code=success';
		}

	} else {
		$location = wpjobster_my_account_link();
	}

	$vars = array(
		'location' => $location
	);
	return $vars;
}
