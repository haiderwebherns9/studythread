<?php
global $wpdb;

$chat_box = new WPJobsterChatBox( $_GET['oid'], 'job_purchase' );

$oid = isset( $_GET['oid'] ) ? $_GET['oid'] : '';

if( $chat_box->chatbox_is_time_up() == 1 ){

	$s = "select * from ".$wpdb->prefix."job_orders where id='$oid'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

	$dt = current_time('timestamp', 1);
	$post_au = get_post($row->pid);
	$pid = $row->pid;
	$ccc = __( 'Cancelled', 'wpjobster' );

	$query = "update ".$wpdb->prefix."job_orders set request_cancellation_from_seller='0', request_cancellation_from_buyer='0', accept_cancellation_request='1', closed='1', force_cancellation='2', date_closed='$dt' where id='{$row->id}'";
	$wpdb->query($query);

	$query1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$dt','-35','$oid','$ccc')";
	$wpdb->query($query1);

	wpj_update_user_notifications( $row->uid, 'notifications', +1 );

	$current_cash = wpjobster_get_credits($row->uid);
	$refundable_amount = wpjobster_get_refundable_amount($row);
	wpjobster_update_credits($row->uid, $current_cash+$refundable_amount );

	$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $oid;
	$reason = __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_au->post_title . '</a>';

	wpjobster_add_history_log('1', $reason, $refundable_amount, $row->uid, '', $oid, 7, '');

	if (get_post_type($pid) == 'offer') {
		wpjobster_send_email_allinone_translated('cancel_offer_acc_seller', $row->uid, false, $row->pid, $oid);
		wpjobster_send_sms_allinone_translated('cancel_offer_acc_seller', $row->uid, false, $row->pid, $oid);
	} else {
		wpjobster_send_email_allinone_translated('cancel_acc_seller', $row->uid, false, $row->pid, $oid);
		wpjobster_send_sms_allinone_translated('cancel_acc_seller', $row->uid, false, $row->pid, $oid);
	}

}

do_action( 'wpj_after_order_is_cancelled', $oid, 'job_purchase' );

$url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $oid;
header( "Location: " .  $url );
?>
