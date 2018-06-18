<?php
/**
 * Jobster helper functions
 */

add_action( 'wp_head', 'wpj_refresh_user_notifications_cron' );
function wpj_refresh_user_notifications_cron() {
	global $current_user;
	$current_user = wp_get_current_user();
	if ( $current_user->ID ) {
		$current_time = current_time( 'timestamp', 1 );
		$timeout = get_user_meta( $current_user->ID, 'notifications_cron_timeout', true );
		if ( $current_time > $timeout || 0==0) {
			wpj_refresh_user_notifications( $current_user->ID );
			$new_timeout = $current_time + 3600; // + 1h * 60m * 60s
			update_user_meta( $current_user->ID, 'notifications_cron_timeout', $new_timeout );
		}
	}
}


function wpj_insert_transaction_message( $args ) {
	// TODO
}


function wpj_insert_private_message( $args ) {
    //print_r($args);
	//exit;
	global $current_user;
	$current_user  = wp_get_current_user();

	$defaults = array(
		'content'              => '',
		'datemade'             => current_time( 'timestamp', 1 ),
		'initiator'            => $current_user->ID,
		'user'                 => 0,
		'attached'             => '',
		'custom_offer'         => 0,
		'associate_request_id' => 0,
		'associate_job_id'     => 0,
	);

	$args = wp_parse_args( $args, $defaults );

	global $wpdb;
	$wpdb->query( $wpdb->prepare(
		"
		INSERT INTO {$wpdb->prefix}job_pm
			( content,offer_price,offer_days, datemade, initiator, user, attached, custom_offer, associate_request_id, associate_job_id )
		VALUES
			( %s,%d,%d, %d, %d, %d, %s, %d, %d, %d )
		",
		$args['content'],
		$args['offer_price'],
		$args['offer_day'],
		$args['datemade'],
		$args['initiator'],
		$args['user'],
		$args['attached'],
		$args['custom_offer'],
		$args['associate_request_id'],
		$args['associate_job_id']
	) );

	$this_pm = $wpdb->insert_id;

	if ( $args['attached'] ) {
		$pm_files_array = explode( ',', $args['attached'] );
		foreach ( $pm_files_array as $attachment ) {
			add_post_meta( $attachment, 'pm_id', $this_pm );
		}
	}

	if ( $args['custom_offer'] == -1 ) {
		$reason = 'new_request';
	} elseif ( $args['custom_offer'] > 0 ) {
		$reason = 'new_offer';
	} else {
		$reason = 'new_message';
	}

	wpjobster_send_email_allinone_translated( $reason, $args['user'], $args['initiator'] );
	wpjobster_send_sms_allinone_translated( $reason, $args['user'], $args['initiator'] );

	$messages = get_user_meta( $args['user'], 'messages_number', true );
	if ( is_numeric( $messages ) ) {
		$messages = $messages + 1;
		update_user_meta( $args['user'], 'messages_number', $messages );
	} else {
		wpj_refresh_user_notifications( $args['user'], 'messages' );
	}

	return $this_pm;
}


function wpj_get_private_message( $id ) {

	global $wpdb;
	$message = $wpdb->get_row( $wpdb->prepare(
		"
		SELECT DISTINCT *
		FROM {$wpdb->prefix}job_pm
		WHERE id = %d
		",
		$id
	) );

	return $message;
}


function wpj_get_chatbox_message( $id ) {

	global $wpdb;
	$message = $wpdb->get_row( $wpdb->prepare(
		"
		SELECT DISTINCT *
		FROM {$wpdb->prefix}job_chatbox
		WHERE id = %d
		",
		$id
	) );

	return $message;
}


function wpj_update_user_notifications( $uid, $type, $number ) {
	if ( ! $uid ) {
		global $current_user;
		$current_user  = wp_get_current_user();
		$uid = $current_user->ID;
	}

	if ( $uid ) {
		if ( $type == 'messages' || $type == 'notifications' ) {
			$type_number = get_user_meta( $uid, $type . '_number', true );
			if ( is_numeric( $type_number ) && is_numeric( $number ) ) {
				$type_number = $type_number + $number;
				update_user_meta( $uid, $type . '_number', $type_number );
			} else {
				$type_number = wpj_refresh_user_notifications( $uid, $type );
			}
			return $type_number;

		} else {
			wpj_refresh_user_notifications( $uid );
			return true;
		}
	}

	return false;
}


function wpj_refresh_user_notifications( $uid = 0, $type = '' ) {
	if ( ! $uid ) {
		global $current_user;
		$current_user  = wp_get_current_user();
		$uid = $current_user->ID;
	}

	if ( $uid ) {
		if ( ! $type || $type == 'messages' ) {
			global $wpdb;
			$messages_number = $wpdb->get_var( $wpdb->prepare(
				"
				SELECT COUNT(*)
				FROM {$wpdb->prefix}job_pm
				WHERE user = %d
					AND show_to_destination = %d
					AND rd = %d
				",
				$uid, 1, 0
			) );
			update_user_meta( $uid, 'messages_number', $messages_number );
		}
		if ( ! $type || $type == 'notifications' ) {
			$args = array(
				'limit'  => 100,
				'offset' => 0,
				'uid'    => $uid,
				'status' => 'unread',
			);
			$notifications_number = count( wpjobster_get_notifications( $args ) );
			update_user_meta( $uid, 'notifications_number', $notifications_number );
		}

		if ( ! $type ) {
			return true;
		} elseif ( $type == 'messages' ) {
			return $messages_number;
		} elseif ( $type == 'notifications' ) {
			return $notifications_number;
		}
	}

	return false;
}


function wpj_read_private_message( $message ) {
	if ( ! is_object( $message ) ) {
		$message = wpj_get_private_message( $message );
	}

	$tm = current_time( 'timestamp', 1 );

	global $wpdb;
	$wpdb->query( $wpdb->prepare(
		"
		UPDATE {$wpdb->prefix}job_pm
		SET rd = %d, readdate = %d
		WHERE id = %d
		",
		1, $tm, $message->id
	) );

	$messages_number = get_user_meta( $message->user, 'messages_number', true );
	if ( is_numeric( $messages_number ) ) {
		$messages_number = $messages_number - 1;
		update_user_meta( $message->user, 'messages_number', $messages_number );
	} else {
		wpj_refresh_user_notifications( $message->user, 'messages' );
	}

	return;
}
