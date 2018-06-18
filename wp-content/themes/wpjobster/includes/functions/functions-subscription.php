<?php
add_action( 'init', 'wpj_update_email_sendto_id' );
function wpj_update_email_sendto_id(){
	if (isset($_GET['_update_email_sendto_id'])) {
		if (is_user_logged_in()) {
			global $current_user;
			$current_user = wp_get_current_user();
			include_once get_template_directory() . '/classes/subscriptions/views/wpjobster_subscriptions_send_update_emails.php';
		}

		exit;
	}
}
add_action( 'init', 'wpj_cancel_subscription' );
function wpj_cancel_subscription(){
	if (isset($_GET['_ad_cancel_subscription'])) {
		if (is_user_logged_in() && current_user_can( 'manage_options' )/* && !is_demo_admin()*/) {
			$uid = $_GET['_ad_cancel_subscription'];
			include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
			$subs = new wpjobster_subscription();
			$subs->process_cancellation( $uid );
		}
		exit;
	}
}

if(!function_exists("wpjobstersubscriptions_function")){
	function wpjobstersubscriptions_function(){
		include_once( get_template_directory() . '/classes/subscriptions/views/wpjobster_subscription.php');
	}
}
add_shortcode("wpjobstersubscriptions","wpjobstersubscriptions_function");

function translate_subscription_strings($key) {
	$strings = array(
		'weekly' => __('weekly', 'wpjobster'),
		'quarterly' => __('quarterly', 'wpjobster'),
		'monthly' => __('monthly', 'wpjobster'),
		'yearly' => __('yearly', 'wpjobster'),
		'level0' => get_option( 'wpjobster_subscription_name_level0' ) ? get_option( 'wpjobster_subscription_name_level0' ) : __('Not Subscribed', 'wpjobster'),
		'level1' => get_option( 'wpjobster_subscription_name_level1' ) ? get_option( 'wpjobster_subscription_name_level1' ) : __('Starter Plan', 'wpjobster'),
		'level2' => get_option( 'wpjobster_subscription_name_level2' ) ? get_option( 'wpjobster_subscription_name_level2' ) : __('Business Plan', 'wpjobster'),
		'level3' => get_option( 'wpjobster_subscription_name_level3' ) ? get_option( 'wpjobster_subscription_name_level3' ) : __('Professional Plan', 'wpjobster')
		);

	if (isset($strings[$key])) {
		return $strings[$key];
	}

	return $key;
}
