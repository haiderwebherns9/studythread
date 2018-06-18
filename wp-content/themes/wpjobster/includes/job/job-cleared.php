<?php
add_action('wp', 'vc_setup_clearing_period');
function vc_setup_clearing_period(){

	if (!wp_next_scheduled('vc_daily_event_clearing_jobs')) {
		wp_schedule_event(time(), 'twicedaily', 'vc_daily_event_clearing_jobs');
	}

}

add_action('vc_daily_event_clearing_jobs', 'increment_clearing_dates');
function increment_clearing_dates(){
	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "job_orders where clearing_period='2'";
	$r = $wpdb->get_results($s);
	$tm = current_time('timestamp', 1);
	foreach ($r as $row) {

		if ($row->date_to_clear <= $tm) {
			$orderid = $row->id;
			$s = "update " . $wpdb->prefix . "job_orders set clearing_period='1' where id='$orderid' ";
			$wpdb->query($s);
			wpjobster_mark_cleared($row->id, 1);
		}

	}
}

function wpjobster_mark_cleared($orderid, $ok_without_uid = ''){
	global $wpdb;
	$s = "select distinct * from " . $wpdb->prefix . "job_orders where id='$orderid'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

	$post = get_post($row->pid);
	$tm = current_time('timestamp', 1);

	$raw_amount = $row->mc_gross;
	$buyer_processing_fees = $row->processing_fees;
	$wpjobster_tax_amount = $row->tax_amount;

	$current_cash = wpjobster_get_credits($post->post_author);
	$payment_gateway = wpjobster_get_order_meta($orderid, 'payment_gateway');

	// check custom extras
	$custom_extras = json_decode( $row->custom_extras );
	if ( $custom_extras ) {
		$i = 0;
		foreach ( $custom_extras as $custom_extra ) {
			if ( $custom_extra->paid ) {
				$custom_extra_order = wpj_get_custom_extra( $row->id, $i );
				$custom_extra_payment = wpj_get_payment( array(
					'payment_type' => 'custom_extra',
					'payment_type_id' => $custom_extra_order->id,
				) );

				$raw_amount += $custom_extra_payment->amount;
				$buyer_processing_fees += $custom_extra_payment->fees;
				$wpjobster_tax_amount += $custom_extra_payment->tax;
			}
			$i++;
		}
	}

	$amount_fee = wpjobster_calculate_fee( $raw_amount, '', $post->post_author );

	if($payment_gateway=='cod'){
		$seller_credit_remain = $current_cash - ( $amount_fee + $wpjobster_tax_amount + $buyer_processing_fees );
		wpjobster_update_credits( $post->post_author, $seller_credit_remain );
	}else{
		wpjobster_update_credits( $post->post_author, $current_cash + ( $raw_amount - $amount_fee ) );
	}

	update_total_earnings($post->post_author, $raw_amount);
	update_total_spendings($row->uid, $raw_amount);

	$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $orderid;

	if($payment_gateway!='cod') {
		$reason = __('Payment cleared for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post->post_title . '</a>';
		wpjobster_add_history_log('1', $reason, $raw_amount, $post->post_author, '', $orderid, 5, '');
	} else {
		$seller_credit_remain = wpjobster_get_credits($seller_id);
			if($seller_credit_remain < 0) {
				wpjobster_send_email_allinone_translated('balance_negative', $seller_id, false, false, false, false, false, false, false, $seller_credit_remain);
				wpjobster_send_sms_allinone_translated('balance_negative', $seller_id, false, false, false, false, false, false, false, $seller_credit_remain);
			}
			if ($buyer_processing_fees > 0) {
			$reason = __('Processing fee for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post->post_title . '</a>';
			wpjobster_add_history_log('0', $reason, $buyer_processing_fees,$post->post_author, '', $orderid, 13, '');
		}
		if ($wpjobster_tax_amount > 0) {
			$reason = __('Tax for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post->post_title . '</a>';
			wpjobster_add_history_log('0', $reason, $wpjobster_tax_amount,$post->post_author, '', $orderid, 14, '');
		}
	}

	$reason = __('Fee charged for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post->post_title . '</a>';
	wpjobster_add_history_log('0', $reason, $amount_fee, $post->post_author, '', $orderid, 6, '');

	$s = "update " . $wpdb->prefix . "job_orders set admin_fee='$amount_fee' where id='$orderid' ";
	$wpdb->query($s);
}
