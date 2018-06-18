<?php
/**
* On an early action hook, check if the hook is scheduled - if not, schedule it.
*/
add_action('wp', 'vc_setup_schedule');
function vc_setup_schedule(){

	if (!wp_next_scheduled('vc_daily_event')) {
		wp_schedule_event(time(), 'daily', 'vc_daily_event');
	}

}

/**
* On an early action hook, check if the hook is scheduled - if not, schedule it.
*/
add_action('wp', 'wpjobster_setup_schedule_daily_levelcheck');
$schedule[]= "before function ";
function wpjobster_setup_schedule_daily_levelcheck(){
	if (!wp_next_scheduled('wpjobster_setup_schedule_daily_levelcheck_event')) {
		wp_schedule_event(time(), 'hourly', 'wpjobster_setup_schedule_daily_levelcheck_event');

	}
}

/**
* On an early action hook, check if the hook is scheduled - if not, schedule it.
*/
add_action('wp', 'wpjobster_setup_schedule_daily');
function wpjobster_setup_schedule_daily(){
	if (!wp_next_scheduled('wpjobster_setup_schedule_daily_event')) {
		wp_schedule_event(time(), 'daily', 'wpjobster_setup_schedule_daily_event');
	}
}

/**
* On the scheduled action hook, run a function.
*/
add_action('wpjobster_setup_schedule_daily_event', 'wpjobster_schedule_daily_function');
function wpjobster_schedule_daily_function(){
	ob_start();
	include_once( get_template_directory() . '/classes/subscriptions/views/wpjobster_check_schedule_subscriptions.php');
	include_once( get_template_directory() . '/lib/gateways/wpjobster_common_job_purchase.php' );

	$wjp = new WPJ_Common_Job_Purchase() ;
	$wjp->get_pending_jobs_by_no_of_days();
	$wjp->close_failed_jobs_cron();

	$contents = ob_get_contents();
	ob_end_clean();
}
