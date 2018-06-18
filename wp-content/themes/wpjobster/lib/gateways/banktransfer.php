<?php
global $wp_query;
$pid = $wp_query->query_vars['jobid'];
$currency = wpjobster_get_currency();

$wpjobster_banktransfer_enable = get_option('wpjobster_banktransfer_enable');

if($wpjobster_banktransfer_enable == 'yes') {

	if (!is_demo_user()) {

		$common_details = get_common_details('banktransfer');
		extract($common_details);

		if(!$orderid ){
			$orderid = $order_id;
		}
		if ($orderid) {
			$wpjobster_paypal_success_page_id=get_option("wpjobster_banktransfer_success_page");

			if($wpjobster_paypal_success_page_id){
				wp_redirect(get_permalink($wpjobster_paypal_success_page_id));
			}else{
				wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$orderid);
			}
			exit;
		} else {
			echo __('Error while inserting the order. Please contact the site administrator.', 'wpjobster');
		}

	} else { //end not demo user
		wpjobster_show_gateway_demouser();
	}
}
?>
