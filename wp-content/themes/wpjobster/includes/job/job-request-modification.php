<?php
add_action( 'wp_ajax_nopriv_wpj_request_modification_vars', 'wpj_request_modification_vars' );
add_action( 'wp_ajax_wpj_request_modification_vars', 'wpj_request_modification_vars' );
function wpj_request_modification_vars(){

	global $wpdb,$wp_rewrite,$wp_query, $current_user;

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$orderid = ( isset( $_POST['oid'] ) && $_POST['oid'] ) ? $_POST['oid'] : '';
	$message_request_modification = ( isset( $_POST['message_request_modification'] ) && $_POST['message_request_modification'] ) ? addslashes($_POST['message_request_modification']) : '';

	if( $orderid ){
		$s = "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
		$r = $wpdb->get_results($s);
		$row = $r[0];
		$pid = $row->pid;
		$post = get_post($pid);
		$user_info = get_userdata($post->post_author);
		$user_name = $user_info->user_login;
		$buyer      = $row->uid;
		$buyer_name = get_userdata($buyer)->user_login;
		$date_made = $row->date_made;


		if (!is_demo_user()) {

			$tm = current_time('timestamp', 1);

			$query_exist = "select * from {$wpdb->prefix}job_chatbox where content='{$message_request_modification}' and uid=-15 and oid={$orderid}";
			$datum = $wpdb->get_results($query_exist);
			if($wpdb->num_rows <= 0) {
				$s = "update ".$wpdb->prefix."job_orders set message_request_modification='$message_request_modification', request_modification='1', date_request_modification='$tm', done_seller='0', date_finished='0' where id='$orderid'";
				$wpdb->query($s);

				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-15','$orderid','$message_request_modification')";
				$wpdb->query($g1);
				wpj_update_user_notifications( $post->post_author, 'notifications', +1 );
			}

			if (get_post_type($pid) == 'offer') {
				wpjobster_send_email_allinone_translated('mod_offer_buyer', $post->post_author, false, $pid, $orderid);
				wpjobster_send_sms_allinone_translated('mod_offer_buyer', $post->post_author, false, $pid, $orderid);
			} else {
				wpjobster_send_email_allinone_translated('mod_buyer', $post->post_author, false, $pid, $orderid);
				wpjobster_send_sms_allinone_translated('mod_buyer', $post->post_author, false, $pid, $orderid);
			}
		}
	}

}
