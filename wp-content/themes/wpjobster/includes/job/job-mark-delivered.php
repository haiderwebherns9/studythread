<?php
function wpj_mark_delivered( $orderid = '' ){
	if( ! is_user_logged_in() ) { wp_redirect( get_bloginfo( 'url' ) . "/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit; }

	global $current_user;
	$current_user = wp_get_current_user();

	global $wp_query, $wpdb;

	if( ! $orderid ) { $orderid = $_GET['oid']; }

	$s = "select distinct * from ".$wpdb->prefix."job_orders where id='$orderid'";
	$r = $wpdb->get_results( $s );
	$row = $r[0];
	$post = get_post( $row->pid );
	$uid_to_send = $row->uid;

	do_action( 'wpj_before_order_marked_as_delivered', $orderid );

	if( $post->post_author == $current_user->ID && $row->delivered == 0 && $row->closed != 1 && $row->request_cancellation_from_seller != 1 && $row->request_cancellation_from_buyer != 1 ) {
		if ( ! is_demo_user() ) {
			$tm = current_time( 'timestamp', 1 );
			$s = "update " . $wpdb->prefix . "job_orders set done_seller='1', date_finished='$tm' where id='$orderid' ";
			$wpdb->query( $s );

			$g1 = "insert into " . $wpdb->prefix . "job_chatbox ( datemade, uid, oid, content ) values( '$tm','-1','$orderid','$ccc' )";
			$wpdb->query( $g1 );
			wpj_update_user_notifications( $uid_to_send, 'notifications', +1 );

			if ( get_post_type( $pid ) == 'offer' ) {
				wpjobster_send_email_allinone_translated( 'order_offer_delivered', $uid_to_send, false, $row->pid, $orderid );
				wpjobster_send_sms_allinone_translated( 'order_offer_delivered', $uid_to_send, false, $row->pid, $orderid );
			} else {
				wpjobster_send_email_allinone_translated( 'order_delivered', $uid_to_send, false, $row->pid, $orderid );
				wpjobster_send_sms_allinone_translated( 'order_delivered', $uid_to_send, false, $row->pid, $orderid );
			}
		}

		do_action( 'wpj_after_order_marked_as_delivered', $orderid );

		wp_redirect( get_bloginfo( 'url' ) . '?jb_action=chat_box&oid=' . $orderid ); exit;
	}

	wp_redirect( get_bloginfo( 'url' ) ); exit;
}
?>
