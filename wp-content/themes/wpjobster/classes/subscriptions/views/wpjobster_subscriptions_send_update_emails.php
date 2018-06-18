<?php
$wpjobster_subscription_enabled = get_option('wpjobster_subscription_enabled');
if($wpjobster_subscription_enabled=='yes'){
	include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
	$wpjobster_subscription = new wpjobster_subscription();
	$period_level_id = $_GET['_update_email_sendto_id'];
	$wpjobster_subscription->send_update_email_subscription($period_level_id);
}
