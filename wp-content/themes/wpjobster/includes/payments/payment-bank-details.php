<?php
function wpj_bank_details_vars(){
	$vars = array();

	global $current_user, $wp_query, $wpdb;
	get_currentuserinfo();
	$cid          = $current_user->ID;
	$orderid      = $_GET['oid'];
	$payment_type = $_GET['payment_type'];

	if(!is_user_logged_in()) { wp_redirect(wp_login_url(get_current_page_url())); exit; }
	$current_user = wp_get_current_user();

	if($payment_type=='feature'){
		$s             = "select * from ".$wpdb->prefix."job_featured_orders where id='$orderid'";
		$r             = $wpdb->get_results($s);
		$current_order = $row = $r[0];
		$pid           = $row->job_id;
		$post          = get_post($pid);
	}else{
		$s             = "select * from ".$wpdb->prefix."job_topup_orders where id='$orderid'";
		$r             = $wpdb->get_results($s);
		$current_order = $row = $r[0];
	}

	$uid  = $row->user_id;
	$date_made = $row->added_on;

	$user_info = get_userdata($uid);
	$user_name = $user_info->user_login;
	$user_link = wpj_get_user_profile_link( $user_name );

	if(($current_user->ID != $uid ) ) wp_redirect(get_bloginfo('url'));

	if($current_order->payment_status=='pending'){
		$order_status=' <span class="title-status pending">('.__('Pending','wpjobster').')</span>';
	}elseif($current_order->payment_status=='failed'){
		$order_status=' <span class="title-status failed">('.__('Failed','wpjobster').')</span>';
	}elseif($current_order->payment_status=='cancelled'){
		$order_status=' <span class="title-status">('.__('Cancelled','wpjobster').')</span>';
	}elseif($current_order->payment_status!='completed'){
		$order_status=' <span class="title-status">('.$current_order->payment_status.')</span>';
	}else{
		$order_status='';
	}

	$vars = array(
		'orderid' => $orderid,
		'date_made' => $date_made,
		'order_status' => $order_status,
		'current_order' => $current_order,
		'row' => $row
	);

	return $vars;
}
