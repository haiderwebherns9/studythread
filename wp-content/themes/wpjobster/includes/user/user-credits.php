<?php
add_action('wp_ajax_update_users_balance', 'wpjobster_update_users_balance');
if (!function_exists('wpjobster_update_users_balance')) {
	function wpjobster_update_users_balance() {
		if (current_user_can('manage_options'))
		if ($_POST['action'] == "update_users_balance") {
			$uid = $_POST['uid'];

			if (!empty($_POST['increase_credits'])) {

				if ($_POST['increase_credits'] > 0)
				if (is_numeric($_POST['increase_credits'])) {
					$cr = wpjobster_get_credits($uid);
					wpjobster_update_credits($uid, $cr + $_POST['increase_credits']);
					$reason = __('Payment received from Site Admin', 'wpjobster');

					wpjobster_add_history_log('1', $reason, $_POST['increase_credits'], $uid, '', '', 1, '');

					wpjobster_send_email_allinone_translated('balance_up', $uid, false, false, false, false, false, false, false, $_POST['increase_credits']);
					wpjobster_send_sms_allinone_translated('balance_up', $uid, false, false, false, false, false, false, false, $_POST['increase_credits']);

					wpjobster_send_email_allinone_translated('balance_admin_up', 'admin', $uid, false, false, false, false, false, false, $_POST['increase_credits']);
					wpjobster_send_sms_allinone_translated('balance_admin_up', 'admin', $uid, false, false, false, false, false, false, $_POST['increase_credits']);

				}

			} else {

				if ($_POST['decrease_credits'] > 0)
				if (is_numeric($_POST['decrease_credits'])) {
					$cr = wpjobster_get_credits($uid);
					wpjobster_update_credits($uid, $cr - $_POST['decrease_credits']);
					$reason = __('Payment withdrawn by Site Admin', 'wpjobster');

					wpjobster_add_history_log('0', $reason, $_POST['decrease_credits'], $uid, '', '', 2, '');

					wpjobster_send_email_allinone_translated('balance_down', $uid, false, false, false, false, false, false, false, $_POST['decrease_credits']);
					wpjobster_send_sms_allinone_translated('balance_down', $uid, false, false, false, false, false, false, false, $_POST['decrease_credits']);

					wpjobster_send_email_allinone_translated('balance_admin_down', 'admin', $uid, false, false, false, false, false, false, $_POST['decrease_credits']);
					wpjobster_send_sms_allinone_translated('balance_admin_down', 'admin', $uid, false, false, false, false, false, false, $_POST['decrease_credits']);

				}

			}

			$cr = wpjobster_get_credits($uid);
			if($cr < 0) {
				wpjobster_send_email_allinone_translated('balance_negative', $uid, false, false, false, false, false, false, false, $cr);
				wpjobster_send_sms_allinone_translated('balance_negative', $uid, false, false, false, false, false, false, false, $cr);
			}

			//echo auctionTheme_get_credits($uid);
			if(isset($sign)){
				echo $sign;
			}
			echo wpjobster_get_show_price(wpjobster_get_credits($uid));
		}
	}
}

function wpjobster_get_credits($uid){
	$c = get_user_meta($uid, 'credits', true);

	if (empty($c)) {
		update_user_meta($uid, 'credits', "0");
		return 0;
	}

	return $c;
}

function wpjobster_update_credits($uid, $am){
	update_user_meta($uid, 'credits', $am);
}

function update_user_credits_balance($payment_transaction_id,$uid){
	global $wpdb;
	$balance =wpjobster_get_credits($uid);
	$sql_credit_update = " insert into {$wpdb->prefix}job_credits_balance_log set datemade='".time()."',"
			. " uid = '{$uid}',"
			. " credit_balance='{$balance}',"
			. " job_payment_transaction_id='{$payment_transaction_id}'";
	$wpdb->query($sql_credit_update);
	return $wpdb->insert_id;
}

function get_previous_credit_balance($uid,$from_bal){
	global $wpdb;
	$s = "select credit_balance as sum_amount ,from_unixtime({$from_bal}) as fromdt FROM {$wpdb->prefix}job_credits_balance_log logs where logs.uid={$uid}  and datemade<={$from_bal} order by datemade desc limit 1 ";
	$result_data = $wpdb->get_results($s);
	if($result_data){
		return $result_data[0];
	}else{
		return 0;
	}
}
