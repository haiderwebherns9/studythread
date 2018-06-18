<?php
function wpj_mark_completed( $orderid = '' ){
	if( ! is_user_logged_in() ) { wp_redirect( get_bloginfo( 'url' ) . "/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit; }

	global $wp_query, $wpdb;

	if( ! $orderid ) { $orderid = $_GET['oid']; }

	if ( ! is_demo_user() ) {
		$mc = wpjobster_mark_completed( $orderid );
		if($mc == 1) {
			$s   = "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
			$r   = $wpdb->get_results( $s );
			$row = $r[0];

			$payment_gateway = $row->payment_gateway;
			if ( $payment_gateway=='cod' ) {

			}

			wp_redirect( get_bloginfo( 'url' ) . '?jb_action=chat_box&oid=' . $orderid );
			exit;
		}
		echo "oops.error";
	} else {
		wp_redirect( get_bloginfo( 'url' ) . '?jb_action=chat_box&oid=' . $orderid );
		exit;
	}
}

if ( ! function_exists( 'wpjobster_mark_completed' ) ) {
	function wpjobster_mark_completed( $orderid, $ok_without_uid = '' ) {
		global $current_user, $wpdb;
		$current_user = wp_get_current_user();
		$s = "select distinct * from " . $wpdb->prefix . "job_orders where id='$orderid'";
		$r = $wpdb->get_results($s);
		$row = $r[0];
		$post = get_post( $row->pid );
		$pid_d = $row->pid;
		$tm = current_time( 'timestamp', 1 );
		$timestamp14 = strtotime( '+14 days', $tm );

		do_action( 'wpj_before_order_marked_as_completed', $orderid );

		$orderid_payment_gateway = wpjobster_get_order_meta( $orderid, 'payment_gateway' );
		$wpjobster_clearing_period = get_option( 'wpjobster_clearing_period' );
		$clear_now = 0;
		if ( is_numeric( $wpjobster_clearing_period ) ) {
			if ( $wpjobster_clearing_period == 0 || $orderid_payment_gateway=='cod' ) {
				$timestamp14 = $tm;
				$clear_now = 1;
			} else {
				$timestamp14 = strtotime( '+' . $wpjobster_clearing_period . ' days', $tm );
			}
		}

		if ( $row->clearing_period == 0 && $row->completed == 0 && $row->done_seller == 1 && $row->closed != 1 ) {
			$s = "update " . $wpdb->prefix . "job_orders set clearing_period='2', date_to_clear='$timestamp14' where id='$orderid' ";
			$wpdb->query( $s );
			do_action( 'wpjobster_do_when_in_clearing', $row );
			do_action( 'wpjobster_do_when_completed', $row );
			if ( $ok_without_uid == 1 ) {
				$ok_gen = 1;
			} elseif ( $row->uid == $current_user->ID ) {
				$ok_gen = 1;
			} else {
				$ok_gen = 0;
			}
			if ( $ok_gen == 1 ) {
				$tm = current_time( 'timestamp', 1 );
				$s = "update " . $wpdb->prefix . "job_orders set done_buyer='1', completed='1', date_completed='$tm' where id='$orderid' ";
				$wpdb->query($s);

				if ( get_post_type( $pid_d ) == 'offer' ) {
					wpjobster_send_email_allinone_translated( 'order_offer_complete', $post->post_author, false, $pid_d, $orderid );
					wpjobster_send_sms_allinone_translated( 'order_offer_complete', $post->post_author, false, $pid_d, $orderid );
				} else {
					wpjobster_send_email_allinone_translated( 'order_complete', $post->post_author, false, $pid_d, $orderid );
					wpjobster_send_sms_allinone_translated( 'order_complete', $post->post_author, false, $pid_d, $orderid );
				}

				$g2 = "insert into " . $wpdb->prefix . "job_admin_earnings ( orderid, pid, admin_fee, datemade ) values( '$orderid','$pid_d','$amount_fee','$tm' )";
				$wpdb->query( $g2 );

				$g1 = "insert into " . $wpdb->prefix . "job_chatbox ( datemade, uid, oid, content ) values( '$tm','-2','$orderid','$ccc' )";
				$wpdb->query( $g1 );
				wpj_update_user_notifications( $post->post_author, 'notifications', +1 );

				if ( $clear_now ) {
					$s = "update " . $wpdb->prefix . "job_orders set clearing_period='1' where id='$orderid' ";
					$wpdb->query( $s );
					wpjobster_mark_cleared( $row->id, 1 );
				}

				do_action( 'wpj_after_order_marked_as_completed', $orderid );

				return 1;
			}
			return 0;
		}
		return 0;
	}
}
