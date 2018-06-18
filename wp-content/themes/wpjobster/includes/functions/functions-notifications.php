<?php
if ( ! function_exists( 'wpjobster_get_unread_number_notify' ) ) {
	function wpjobster_get_unread_number_notify( $uid = '' ) {
		$args = array(
			'limit' => 100,
			'offset' => 0,
			'uid' => $uid,
			'status' => 'unread',
		);
		$notifications = wpjobster_get_notifications( $args );
		return count( $notifications );
	}
}

if ( ! function_exists( 'notify_after_featured_pay' ) ) {
	function notify_after_featured_pay($pid, $uid, $total,$payed_amt){

		$email_1 = get_bloginfo('admin_email');
		$post 	= get_post($pid);
		$auth = $post->post_author;
		$user_info = get_userdata($auth);
		$email_2 = $user_info->user_email;
		wpjobster_send_email_allinone_translated('featured_new', 			$uid,    false,   $pid);
		wpjobster_send_email_allinone_translated('featured_admin_new', 'admin', $uid,		 $pid);

		wpjobster_send_sms_allinone_translated('featured_new', 			$uid,    false,   $pid);
		wpjobster_send_sms_allinone_translated('featured_admin_new', 'admin', $uid,		 $pid);

		$details = $pid;
		$reason = __('Feature job','wpjobster').': <a href="'.get_permalink($pid).'">'.$post->post_title.'</a>';
		wpjobster_add_history_log('0', $reason, $total, $uid, '', '', 10, $details,$payed_amt);

		//fix_this^
	}
}

if ( ! function_exists( 'notify_after_custom_extra_pay' ) ) {
	function notify_after_custom_extra_pay($order_id, $uid, $total,$mc_currency,$custom_extra_id){

		$order 	= wpjobster_get_order($order_id);
		$post_a         = get_post($order->pid);
		$custom_extras = json_decode($order->custom_extras);
		$custom_extra = $custom_extras[$custom_extra_id];
		wpjobster_send_email_allinone_translated('custom_extra_paid_new', 		 $uid,                  false, false, $order_id);
		wpjobster_send_email_allinone_translated('custom_extra_paid_new_seller', $post_a->post_author,  $uid,  false, $order_id);
		wpjobster_send_email_allinone_translated('custom_extra_paid_admin_new',  'admin',               $uid,  false, $order_id);

		wpjobster_send_sms_allinone_translated('custom_extra_paid_new', 		 $uid,                  false, false, $order_id);
		wpjobster_send_sms_allinone_translated('custom_extra_paid_new_seller',   $post_a->post_author,  $uid,  false, $order_id);
		wpjobster_send_sms_allinone_translated('custom_extra_paid_admin_new',    'admin',               $uid,  false, $order_id);


		$buyer_processing_fees_orignal = wpjobster_get_site_processing_fee( $custom_extra->price, 0, 0);
		$tax_orignal                   = wpjobster_get_site_tax( $custom_extra->price,0,0,$buyer_processing_fees_orignal);

		$tax                           = wpjobster_formats_special_exchange( $tax_orignal, 1, $mc_currency );
		$buyer_processing_fees         = wpjobster_formats_special_exchange( $buyer_processing_fees_orignal, 1, $mc_currency );
		$price_exchanged               = wpjobster_formats_special_exchange( $custom_extra->price, 1, $mc_currency );

		$order_url = get_bloginfo( 'url' ) . '/?jb_action=chat_box&oid=' . $order_id;
		$custom_extra_title = $custom_extra->description;

		// insert main log for buyer
		$reason = __('Custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $custom_extra_title . '</a>';
		wpjobster_add_history_log('0', $reason, $custom_extra->price,   $uid, '', $order_id, 18, $custom_extra_id, $mc_currency."|". $price_exchanged );

		// insert fee log for buyer
		if($buyer_processing_fees_orignal){
			$reason = __('Processing fee for custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $custom_extra_title . '</a>';
			wpjobster_add_history_log('0', $reason, $buyer_processing_fees_orignal, $uid, '', $order_id, 16, $custom_extra_id, $mc_currency."|".$buyer_processing_fees);
		}

		// insert tax log for buyer
		if($tax_orignal) {
			$reason = __('Tax for custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $custom_extra_title . '</a>';
			wpjobster_add_history_log( '0', $reason, $tax_orignal, $uid, '', $order_id, 17, $custom_extra_id, $mc_currency . "|" . $tax );
		}

		// insert collected log for seller
		$reason = __( 'Payment collected for custom extra', 'wpjobster' ) . ': <a href="' . $order_url . '">' . $custom_extra_title . '</a>';
		wpjobster_add_history_log( '2', $reason, $custom_extra->price, $post_a->post_author, '', $order_id, 19, $custom_extra_id, $mc_currency . '|' . $price_exchanged );
	}
}

//LIVE NOTIFICATIONS ENABLED
if ( ! function_exists( 'wpjobster_live_notifications_enabled' ) ) {
	function wpjobster_live_notifications_enabled() {
		if ( get_option( 'wpjobster_enable_live_notifications' ) == 'yes'
			&& is_user_logged_in() ) {
			return true;
		}
		return false;
	}
}

//MARK SELECTED NOTIFICATION AS READ
if ( ! function_exists( 'wpjobster_mark_notifications_as_read' ) ) {
	function wpjobster_mark_notifications_as_read(){
		global $current_user, $wpdb;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		if ( isset( $_POST['wpj-read-cnt'] ) ) {

			// get only unread notifications belonging to this user
			$notifications = wpjobster_get_notifications( array(
				'limit' => 10000, //reasonable limit
				'uid' => $uid,
				'status' => 'unread',
			) );

			// build a list of IDs rather than querying in loop, for performance
			$allowed_to_read = array();
			foreach ( $notifications as $notification ) {
				$allowed_to_read[] = $notification->id;
			}

			$i = 0;
			$sql_in = '';
			foreach( $_POST['chk-notify'] as $notify ) {
				if ( in_array( $notify, $allowed_to_read ) ) {
					if ( ++$i > 1 ) {
						$sql_in = $sql_in . ', ';
					}
					$sql_in = $sql_in . $notify;
				}
			}

			// update read status
			$wpdb->query(
				"
				UPDATE " . $wpdb->prefix . "job_chatbox
				SET rd_receiver='1'
				WHERE id IN (" . $sql_in . ")
				"
			);

			wpj_refresh_user_notifications( $uid, 'notifications' );
		}
	}
}

// MARK ALL NOTIFICATIONS AS READ
if ( ! function_exists( 'wpjobster_mark_all_notifications_as_read' ) ) {
	function wpjobster_mark_all_notifications_as_read(){
		global $current_user, $wpdb;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		if ( isset( $_POST['wpj-read-all-cnt'] ) ) {

			// get only unread notifications belonging to this user
			$notifications = wpjobster_get_notifications( array(
				'limit' => 10000, //reasonable limit
				'uid' => $uid,
				'status' => 'unread',
			) );

			// build a list of IDs rather than querying in loop, for performance
			$i = 0;
			$sql_in = '';
			foreach ( $notifications as $notification ) {
				if ( ++$i > 1 ) {
					$sql_in = $sql_in . ', ';
				}
				$sql_in = $sql_in . $notification->id;
			}

			// update read status
			$wpdb->query(
				"
				UPDATE " . $wpdb->prefix . "job_chatbox
				SET rd_receiver='1'
				WHERE id IN (" . $sql_in . ")
				"
			);

			wpj_refresh_user_notifications( $uid, 'notifications' );
		}
	}
}
