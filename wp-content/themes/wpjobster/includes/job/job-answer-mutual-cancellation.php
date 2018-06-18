<?php
function wpj_answer_mutual_cancellation() {

	$oid = $_GET['oid'];
	global $wpdb, $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$s = "select * from ".$wpdb->prefix."job_orders where id='$oid'";
	$r = $wpdb->get_results($s);
	$row = $r[0];

	$pid = $row->pid;
	$post_au = get_post($row->pid);
	$date_made = $row->date_made;
	$dt = current_time('timestamp', 1);
	$ccc = __( 'Cancelled', 'wpjobster' );

	if (($row->uid != $uid) && ($post_au->post_author != $uid)) {
		wp_redirect(get_bloginfo('url')); exit;
	}

	if ( $row->closed == 1 || $row->completed == 1 || $row->accept_cancellation_request == -1 ) {
		wp_redirect(get_bloginfo('url')); exit;
	}

	if($row->uid == $uid) {
		if($row->request_cancellation_from_seller == 1) {
			//i am the buyer here
			$s_message = __('The seller of this job has requested a mutual cancellation for this order. Please accept or deny it using the controls below:','wpjobster');
			$return_me = get_permalink(get_option('wpjobster_my_account_shopping_page_id'));

			if($_GET['accept'] == "yes") {
				$s1 = "update ".$wpdb->prefix."job_orders set request_cancellation_from_seller='0', request_cancellation_from_buyer='0', accept_cancellation_request='1', closed='1', date_accept_cancellation='$dt' where id='{$row->id}'";
				$wpdb->query($s1);

				$orderid = $_GET['oid'];
				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$dt','-10','$oid','$ccc')"; // -10 means the cancellation was accepted by buyer
				$wpdb->query($g1);
				wpj_update_user_notifications( $post_au->post_author, 'notifications', +1 );
				if($row->payment_status=='' || $row->payment_status=='completed'){
					$current_cash = wpjobster_get_credits($row->uid);
					$refundable_amount = wpjobster_get_refundable_amount($row);
					wpjobster_update_credits($row->uid, $current_cash + $refundable_amount);
					$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $orderid;
					$reason = __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_au->post_title . '</a>';

					wpjobster_add_history_log('1', $reason, $refundable_amount, $row->uid, '', $orderid, 7, '');
				}

				if (get_post_type($pid) == 'offer') {
					wpjobster_send_email_allinone_translated('cancel_offer_acc_buyer', $post_au->post_author, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_offer_acc_buyer', $post_au->post_author, false, $row->pid, $oid);
				} else {
					wpjobster_send_email_allinone_translated('cancel_acc_buyer', $post_au->post_author, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_acc_buyer', $post_au->post_author, false, $row->pid, $oid);
				}

				do_action( 'wpj_after_order_is_cancelled', $orderid, 'job_purchase' );
			}

			if($_GET['accept'] == "no") {
				$s1 = "update ".$wpdb->prefix."job_orders set request_cancellation_from_seller='0', request_cancellation_from_buyer='0', accept_cancellation_request='-1' where id='{$row->id}'";
				$wpdb->query($s1);

				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$dt','-11','$oid','$ccc')"; // -11 means the cancellation was declined by buyer
				$wpdb->query($g1);

				wpj_update_user_notifications( $post_au->post_author, 'notifications', +1 );

				if (get_post_type($pid) == 'offer') {
					wpjobster_send_email_allinone_translated('cancel_offer_decl_buyer', $post_au->post_author, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_offer_decl_buyer', $post_au->post_author, false, $row->pid, $oid);
				} else {
					wpjobster_send_email_allinone_translated('cancel_decl_buyer', $post_au->post_author, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_decl_buyer', $post_au->post_author, false, $row->pid, $oid);
				}
			}
		} else {
			wp_redirect(get_bloginfo('url').'?jb_action=chat_box&oid='.$oid); exit;
			_e('You don\'t have permission to access this page! Error #3211.','wpjobster'); exit;
		}
	}

	if($post_au->post_author == $uid) {
		if($row->request_cancellation_from_buyer == 1) {
			// i am the seller here
			$s_message = __('The buyer of this job has requested a mutual cancellation for this order. Please accept or deny it using the controls below:','wpjobster');
			$return_me = get_permalink(get_option('wpjobster_my_account_sales_page_id'));

			if($_GET['accept'] == "yes") {
				$s1 = "update ".$wpdb->prefix."job_orders set request_cancellation_from_seller='0', request_cancellation_from_buyer='0', accept_cancellation_request='1', closed='1', date_accept_cancellation='$dt' where id='{$row->id}'";
				$wpdb->query($s1);

				$orderid = $_GET['oid'];
				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$dt','-12','$oid','$ccc')"; // -12 means the cancellation was accepted by seller

				$wpdb->query($g1);
				wpj_update_user_notifications( $row->uid, 'notifications', +1 );

				$current_cash = wpjobster_get_credits($row->uid);
				$refundable_amount = wpjobster_get_refundable_amount($row);
				wpjobster_update_credits($row->uid, $current_cash+$refundable_amount );

				$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $orderid;
				$reason = __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_au->post_title . '</a>';

				wpjobster_add_history_log('1', $reason, $refundable_amount, $row->uid, '', $orderid, 7, '');

				if (get_post_type($pid) == 'offer') {
					wpjobster_send_email_allinone_translated('cancel_offer_acc_seller', $row->uid, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_offer_acc_seller', $row->uid, false, $row->pid, $oid);
				} else {
					wpjobster_send_email_allinone_translated('cancel_acc_seller', $row->uid, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_acc_seller', $row->uid, false, $row->pid, $oid);
				}

				do_action( 'wpj_after_order_is_cancelled', $orderid, 'job_purchase' );
			}


			if($_GET['accept'] == "no") {
				$s1 = "update ".$wpdb->prefix."job_orders set request_cancellation_from_seller='0', request_cancellation_from_buyer='0', accept_cancellation_request='-1' where id='{$row->id}'";
				$wpdb->query($s1);

				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$dt','-13','$oid','$ccc')"; // -13 means the cancellation was declined by seller
				$wpdb->query($g1);

				wpj_update_user_notifications( $row->uid, 'notifications', +1 );

				if (get_post_type($pid) == 'offer') {
					wpjobster_send_email_allinone_translated('cancel_offer_decl_seller', $row->uid, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_offer_decl_seller', $row->uid, false, $row->pid, $oid);
				} else {
					wpjobster_send_email_allinone_translated('cancel_decl_seller', $row->uid, false, $row->pid, $oid);
					wpjobster_send_sms_allinone_translated('cancel_decl_seller', $row->uid, false, $row->pid, $oid);
				}
			}
		} else {
			wp_redirect(get_bloginfo('url').'?jb_action=chat_box&oid='.$oid); exit;
			_e('You don\'t have permission to access this page! Error #3219.','wpjobster'); exit;
		}
	}
}
