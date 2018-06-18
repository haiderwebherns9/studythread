<?php
$wpjobster_subscription_enabled = get_option('wpjobster_subscription_enabled');
if($wpjobster_subscription_enabled=='yes'){
	include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
	$wpjobster_subscription = new wpjobster_subscription();
	$wpjobster_subscription->check_scheduled_subscription();
	$wpjobster_subscription->send_subscription_renewal_reminder();
}
