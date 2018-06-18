<?php
/**
 * Functions with very small requirements, to be used with shortinit
 */

add_action( 'light_ajax_check_live_notifications', 'wpj_check_live_notifications' );
add_action( 'light_ajax_nopriv_check_live_notifications', 'wpj_check_live_notifications' );
function wpj_check_live_notifications() {
	global $current_user;
	$current_user  = wp_get_current_user();

	$enabled       = get_option( 'wpjobster_enable_live_notifications' );
	$notifications = get_user_meta( $current_user->ID, 'notifications_number', true );
	$messages      = get_user_meta( $current_user->ID, 'messages_number', true );

	$response = array(
		'enabled' => $enabled,
		'notifications' => $notifications,
		'messages' => $messages,
		'current_time' => time(),
		'timeout' => 2000,
		'max_timeout' => 32000,
	);

	wp_send_json( $response );
	wp_die();
}

add_action( 'wp_ajax_show_new_messages', 'wpjobster_show_new_messages' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_show_new_messages', 'wpjobster_show_new_messages' ); // ajax for not logged in users
function wpjobster_show_new_messages(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	if( isset( $_POST['msg_username'] ) ){
		$msg_username = get_user_by( 'login', $_POST['msg_username'] );
		$msg_username_id = $msg_username->ID;
	}else{
		$msg_username_id = '';
	}

	$s = "select * from " . $wpdb->prefix . "job_pm where user='$uid' and rd='0'";
	$r = $wpdb->get_results($s);

	if( count( $r ) > 0 ){
		foreach ($r as $row) {
			if($row->initiator != $msg_username_id){
				$query_msg = "SELECT * FROM " . $wpdb->prefix . "job_pm WHERE show_to_source='1' and id =".$row->id;
			}else{
				$query_msg = "SELECT * FROM " . $wpdb->prefix . "job_pm WHERE show_to_destination='1' and id =".$row->id;
			}

			$results = $wpdb->get_results( $query_msg );

			if($row->initiator == $msg_username_id){
				wpjobster_pm_loop( $results, $uid, $msg_username_id );
			}
		}
	};

	$s1 = "select * from " . $wpdb->prefix . "job_pm where ( user='" . $uid . "' or initiator='" . $uid . "' ) and custom_offer != '0'";
	$r1 = $wpdb->get_results($s1);

	if( count( $r1 ) > 0 ){
		foreach ($r1 as $row1) {
			if($row1->initiator != $msg_username_id){
				$query_msg = "SELECT * FROM " . $wpdb->prefix . "job_pm WHERE show_to_source='1' and id =".$row1->id;
			}else{
				$query_msg = "SELECT * FROM " . $wpdb->prefix . "job_pm WHERE show_to_destination='1' and id =".$row1->id;
			}
			$results1 = $wpdb->get_results( $query_msg );

			$id = $uid.$row1->id.$row1->custom_offer.$row1->datemade;

			$offer_declined = get_post_meta( $row1->custom_offer, 'offer_declined', true );
			if( $offer_declined == 1 ){
				$msg_read_dec = get_post_meta( $id, 'custom_offer_declined', true );
				if( $msg_read_dec != 'done' ){
					wpjobster_pm_loop( $results1, $uid, $msg_username_id );
				}
				update_post_meta( $id, 'custom_offer_declined', 'done' );
			}

			$offer_withdrawn = get_post_meta( $row1->custom_offer, 'offer_withdrawn', true );
			if( $offer_withdrawn == 1 ){
				$msg_read_withdrawn = get_post_meta( $id, 'custom_offer_withdrawn', true );
				if( $msg_read_withdrawn != 'done' ){
					wpjobster_pm_loop( $results1, $uid, $msg_username_id );
				}
				update_post_meta( $id, 'custom_offer_withdrawn', 'done' );
			}

			$offer_accepted = get_post_meta( $row1->custom_offer, 'offer_accepted', true );
			if( $offer_accepted == 1 ){
				$msg_read_acc = get_post_meta( $id, 'custom_offer_accepted', true );
				if( $msg_read_acc != 'done' ){
					wpjobster_pm_loop( $results1, $uid, $msg_username_id );
				}
				update_post_meta( $id, 'custom_offer_accepted', 'done' );
			}
		}
	};

	wp_die();
}

add_action( 'wp_ajax_show_new_messages_for_archive', 'wpjobster_show_new_messages_for_archive' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_show_new_messages_for_archive', 'wpjobster_show_new_messages_for_archive' ); // ajax for not logged in users
function wpjobster_show_new_messages_for_archive(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$s = "SELECT * FROM ( SELECT * FROM {$wpdb->prefix}job_pm ORDER BY id DESC ) AS initiators WHERE user='$uid' AND rd='0' GROUP BY initiator ORDER BY id DESC";
	$r = $wpdb->get_results($s);

	$using_perm = wpjobster_using_permalinks();
	if($using_perm) $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id'));
	else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_priv_mess_page_id'). "&";

	echo wpj_private_messages_return_messages( $r, $privurl_m, 'yes' );

	wp_die();
}

add_action( 'wp_ajax_notifications_for_chatbox', 'wpjobster_notifications_for_chatbox' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_notifications_for_chatbox', 'wpjobster_notifications_for_chatbox' ); // ajax for not logged in users
function wpjobster_notifications_for_chatbox(){
	$chat_box = new WPJobsterChatBox($_POST['oid'], $_POST['jb_action']);
	$chat_box->chat_box_current_order_status_details();

	wp_die();
}

add_action( 'wp_ajax_messages_for_chatbox', 'wpjobster_messages_for_chatbox' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_messages_for_chatbox', 'wpjobster_messages_for_chatbox' ); // ajax for not logged in users
function wpjobster_messages_for_chatbox(){
	$orderid = $_POST['oid'];
	$chat_box = new WPJobsterChatBox( $orderid, $_POST['jb_action'] );

	$current_order = wpjobster_get_order( $orderid );

	ob_start();
	echo $chat_box->chat_box_send_message_form();
	$output = ob_get_contents();
	ob_end_clean();

	echo json_encode( array(
		'content'            => $output,
		'payment_status'     => $current_order->payment_status,
		'order_status'       => $current_order->done_buyer,
		'times_up'           => $current_order->force_cancellation,
		'order_cancellation' => $current_order->accept_cancellation_request
	) );

	wp_die();
}
