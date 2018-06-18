<?php
function update_total_earnings($uid, $current_order_price = 0, $force_recheck = false) {
	global $wpdb, $prefix;
	$prefix = $wpdb->prefix;
	$user_total_earnings = get_user_meta($uid, 'user_total_earnings', true);
	if ($user_total_earnings && $current_order_price && !$force_recheck){
		update_user_meta($uid, 'user_total_earnings', $user_total_earnings + $current_order_price);

	} else {
		$get_total_earnings_query = "select SUM(mc_gross) as total_earnings FROM {$prefix}job_orders orders, {$prefix}posts posts
		 where posts.post_author={$uid} AND posts.ID=orders.pid AND orders.done_seller='1' AND
		 orders.done_buyer='1' AND orders.completed='1' AND orders.closed='0' AND orders.clearing_period='1'";
		$g_total_earnings = $wpdb->get_results($get_total_earnings_query , OBJECT);
		update_user_meta($uid, 'user_total_earnings', $g_total_earnings[0]->total_earnings);
	}
}

function get_total_withdrawals($uid) {
	global $wpdb, $prefix;
	$prefix = $wpdb->prefix;
	$get_total_withdrawals_query = "select SUM(amount) as total_withdrawals FROM {$prefix}job_withdraw withdrawals
		 where withdrawals.uid={$uid} AND withdrawals.done='1'";
	$g_total_withdrawals = $wpdb->get_results($get_total_withdrawals_query , OBJECT);

	if ($g_total_withdrawals[0]->total_withdrawals > 0) {
		return wpjobster_get_show_price($g_total_withdrawals[0]->total_withdrawals);
	} else {
		return "0";
	}
}

function update_total_spendings($uid, $current_order_price = 0, $force_recheck = false) {
	global $wpdb, $prefix;
	$prefix = $wpdb->prefix;
	$user_total_spendings = get_user_meta($uid, 'user_total_spendings', true);

	if ($user_total_spendings && $current_order_price && !$force_recheck){
		update_user_meta($uid, 'user_total_spendings', $user_total_spendings+$current_order_price);

	} else {
		$get_total_spendings_query = "select SUM(mc_gross) as total_spendings FROM {$prefix}job_orders orders
			 where orders.uid={$uid} AND orders.completed='1' AND orders.done_buyer='1' AND orders.done_seller='1' AND orders.closed='0'";
		$g_total_spendings = $wpdb->get_results($get_total_spendings_query , OBJECT);
		update_user_meta($uid, 'user_total_spendings', $g_total_spendings[0]->total_spendings);
	}
}

// GET USER TOTAL SPENT
function get_total_spent( $uid ){
	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	global $wpdb;

	$query = "
	SELECT SUM(sum_amount) as sum_amount FROM
	(
		SELECT SUM(SUBSTRING_INDEX(final_paidamount, '|', -1)) as sum_amount FROM {$wpdb->prefix}job_orders WHERE uid = {$uid} AND done_seller = '0' AND done_buyer = '0' AND date_finished = '0' AND closed = '0' AND payment_status != 'pending'
		UNION ALL
		SELECT SUM(SUBSTRING_INDEX(final_paidamount, '|', -1)) as sum_amount FROM {$wpdb->prefix}job_orders WHERE uid = {$uid} AND completed = '1'
	)sum_amount
	";

	$total_spent_results = $wpdb->get_results( $query );
	if ( $total_spent_results[0]->sum_amount > 0 ) {
		return wpjobster_get_show_price($total_spent_results[0]->sum_amount);
	} else {
		return "0";
	}
}
