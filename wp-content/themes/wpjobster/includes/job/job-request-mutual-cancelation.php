<?php
add_action( 'wp_ajax_nopriv_wpj_request_mutual_cancelation_vars', 'wpj_request_mutual_cancelation_vars' );
add_action( 'wp_ajax_wpj_request_mutual_cancelation_vars', 'wpj_request_mutual_cancelation_vars' );
function wpj_request_mutual_cancelation_vars(){
	global $wpdb,$wp_rewrite,$wp_query, $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$orderid            = isset( $_POST['orderid'] ) ? $_POST['orderid'] : '';
	$message_to_buyer   = isset( $_POST['message_to_buyer'] ) ? addslashes( $_POST['message_to_buyer'] ) : '';

	if( $orderid ){

		$s          = "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
		$r          = $wpdb->get_results($s);
		$row        = $r[0];
		$pid        = $row->pid;
		$post       = get_post($pid);
		$user_info  = get_userdata($post->post_author);
		$user_name  = $user_info->user_login;
		$buyer      = $row->uid;
		$buyer_name = get_userdata($buyer)->user_login;
		$date_made  = $row->date_made;

		if( isset( $_POST['process_action'] ) && $_POST['process_action'] == 'confirm_cancellation_from_seller' ) {
			if (!is_demo_user()) {

				$tm = current_time('timestamp', 1);

				$query_exist = "select * from {$wpdb->prefix}job_chatbox where content='{$message_to_buyer}' and uid=-8 and oid={$orderid}";
				$datum = $wpdb->get_results($query_exist);
				if($wpdb->num_rows <= 0) {
					$s = "update ".$wpdb->prefix."job_orders set message_to_buyer='$message_to_buyer', request_cancellation_from_seller='1', accept_cancellation_request='0', date_request_cancellation='$tm' where id='$orderid'";
					$wpdb->query($s);

					$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-8','$orderid','$message_to_buyer')"; // -8 means the seller requested cancellation
					$wpdb->query($g1);
					wpj_update_user_notifications( $row->uid, 'notifications', +1 );

					if (get_post_type($pid) == 'offer') {
						wpjobster_send_email_allinone_translated('cancel_offer_seller', $row->uid, false, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('cancel_offer_seller', $row->uid, false, $pid, $orderid);
					} else {
						wpjobster_send_email_allinone_translated('cancel_seller', $row->uid, false, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('cancel_seller', $row->uid, false, $pid, $orderid);
					}
				}
			}
		}

		if( isset( $_POST['process_action'] ) && $_POST['process_action'] == 'confirm_cancellation_from_buyer' ) {

			if (!is_demo_user()) {

				$tm = current_time('timestamp', 1);

				$query_exist = "select * from {$wpdb->prefix}job_chatbox where content='{$message_to_buyer}' and uid=-9 and oid={$orderid}";
				$datum = $wpdb->get_results($query_exist);
				if($wpdb->num_rows <= 0) {
					$s = "update ".$wpdb->prefix."job_orders set message_to_seller='$message_to_buyer', request_cancellation_from_buyer='1', accept_cancellation_request='0', date_request_cancellation='$tm' where id='$orderid'";
					$wpdb->query($s);

					$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-9','$orderid','$message_to_buyer')"; // -9 means the buyer requested cancellation
					$wpdb->query($g1);
					wpj_update_user_notifications( $post->post_author, 'notifications', +1 );

					if (get_post_type($pid) == 'offer') {
						wpjobster_send_email_allinone_translated('cancel_offer_buyer', $post->post_author, false, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('cancel_offer_buyer', $post->post_author, false, $pid, $orderid);
					} else {
						wpjobster_send_email_allinone_translated('cancel_buyer', $post->post_author, false, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('cancel_buyer', $post->post_author, false, $pid, $orderid);
					}
				}
			}
		}

	}
}

