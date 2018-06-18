<?php

function wpj_all_notifications_vars() {

	$vars = array();

	global $current_user, $wpdb;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$using_perm = wpjobster_using_permalinks();
	if($using_perm) $all_notif_pg_lnk = get_permalink(get_option('wpjobster_my_account_all_notifications_page_id')). "?";
	else $all_notif_pg_lnk = get_bloginfo('url'). "?page_id=". get_option('wpjobster_my_account_all_notifications_page_id'). "&";

	wpjobster_mark_all_notifications_as_read();
	wpjobster_mark_notifications_as_read();

	$vars = array(
		'wpdb' => $wpdb,
		'uid' => $uid
	);

	return $vars;

}

// other necessary functions
function notifications_enqueue_scripts() {

	$dependencies = array(
		'jquery'
	);

	wp_enqueue_script( 'notifications-script', get_template_directory_uri() . '/js/wpjobster/notifications.js', $dependencies  );

	wp_localize_script( 'notifications-script', '_notifications_settings', notifications_localized_js() );
}

add_action( 'wp_enqueue_scripts', 'notifications_enqueue_scripts');

add_action( 'wp_ajax_wpjobster_ajax_notifications', 'wpjobster_ajax_notifications' );
add_action( 'wp_ajax_nopriv_wpjobster_ajax_notifications', 'wpjobster_ajax_notifications' );

function notifications_localized_js() {
	$defaults = array(
		'ajaxurl' => admin_url("admin-ajax.php"),
		'is_user_logged_in' => is_user_logged_in() ? 1 : 0,
		'nonotifications' => __('No older notifications found', 'wpjobster'),
		"check_all" => __( 'Check all', 'wpjobster' ),
		"uncheck_all" => __( 'Uncheck all', 'wpjobster' ),
		);
	return $defaults;
}

function wpjobster_ajax_notifications() {

	$limit = (isset($_POST['limit'])) ? $_POST['limit'] : 10;
	$offset = (isset($_POST['offset'])) ? $_POST['offset'] : 10;

	$args = array(
		'limit' => $limit,
		'offset' => $offset,
	);

	$notifications = wpjobster_get_notifications($args);
	$notifications_count = count($notifications);

	ob_start();
	wpjobster_display_notifications($notifications);
	$content = ob_get_clean();

	$output = array(
		'content' => $content,
		'count' => $notifications_count,
	);

	wp_die(json_encode($output));

}


function wpjobster_get_notifications($args = null) {

	global $wpdb;

	$defaults = array(
		'limit' => 10,
		'offset' => 0,
		'orderby' => 'date',
		'order' => 'desc',
		'uid' => '',
		'status' => '',
	);

	$args = wp_parse_args($args, $defaults);

	$limit = esc_sql($args['limit']);
	$offset = esc_sql($args['offset']);
	$orderby = esc_sql($args['orderby']);
	$order = esc_sql($args['order']);
	$uid = esc_sql($args['uid']);
	$status = $args['status'];

	if ($status == 'unread') {
		$status_sql = "AND chatbox.rd_receiver='0'";
	} elseif ($status == 'read') {
		$status_sql = "AND chatbox.rd_receiver='1'";
	} else {
		$status_sql = "";
	}

	if (!$uid) {
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
	}

	$s = "SELECT chatbox.id id, orders.id oid, posts.ID pid, posts.post_author sid, orders.uid bid, chatbox.uid code, chatbox.datemade tm, chatbox.rd_receiver rd, orders.date_made otm
	FROM ".$wpdb->prefix."job_chatbox chatbox, ".$wpdb->prefix."job_orders orders, $wpdb->posts posts
	WHERE chatbox.oid=orders.id AND posts.ID=orders.pid
	AND chatbox.uid!='$uid' $status_sql
	AND (posts.post_author='$uid' OR orders.uid='$uid')
	AND (chatbox.uid > 0
		OR (posts.post_author = '$uid'
			AND (chatbox.uid = '0'
				OR chatbox.uid = '-2'
				OR chatbox.uid = '-9'
				OR chatbox.uid = '-10'
				OR chatbox.uid = '-11'
				OR chatbox.uid = '-14'
				OR chatbox.uid = '-15'
				OR chatbox.uid = '-17'
				OR chatbox.uid = '-18'
				OR chatbox.uid = '-21'
				OR chatbox.uid = '-33'
				OR chatbox.uid = '-34'
				OR chatbox.uid = '-35'
			)
		)
		OR (orders.uid = '$uid'
			AND (chatbox.uid = '-1'
				OR chatbox.uid = '-8'
				OR chatbox.uid = '-12'
				OR chatbox.uid = '-13'
				OR chatbox.uid = '-16'
				OR chatbox.uid = '-19'
				OR chatbox.uid = '-22'
				OR chatbox.uid = '-31'
				OR chatbox.uid = '-32'
			)
		)
	)


	ORDER BY tm DESC LIMIT $limit OFFSET $offset";


	$notifications = $wpdb->get_results($s);
	return $notifications;
}

function wpjobster_display_notifications($notifications, $uid = '') {

	global $current_user;
	$current_user = wp_get_current_user();
	$current_uid = $current_user->ID;

	//User Timezone Function
	wpjobster_timezone_change();

	if (!$uid) {
		$uid = $current_uid;
	}

	if (count($notifications) > 0) {
		$i = 0;
		foreach($notifications as $row) {

			$transaction_number = wpjobster_camouflage_order_id($row->oid, $row->otm);
			$notification_date = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->tm);
			$buyer = get_userdata($row->bid);
			$seller = get_userdata($row->sid);

			$i++;
			?>

			<li class="cf <?php echo 'wpj-notification-' . $row->id; echo $row->rd ? ' wpj-notification-read' : ' wpj-notification-unread'; ?>">

				<a href="<?php echo get_bloginfo('url')."/?jb_action=chat_box&oid=".$row->oid; ?>" class="cf">
					<?php

					if ($uid == $row->sid) {
						$displayname = $buyer->user_login;
					} elseif ($uid == $row->bid) {
						$displayname = $seller->user_login;
					} else {
						$displayname = __('User', 'wpjobster');
					}

					if ($row->code > 0) {
						$format = __('Order %1$s was updated by %2$s', 'wpjobster');
						$icon_class = 'sent green';
					} else {
						if($row->code == 0) {
							// new order
							$format = __('You have a new order from %2$s. Order ID: %1$s.', 'wpjobster');
							$icon_class = 'sent green';
						}

						elseif($row->code == -1) {
							// marked as delivered
							$format = __('%2$s has delivered the order %1$s', 'wpjobster');
							$icon_class = 'delivered green';
						}

						elseif($row->code == -2) {
							// order complete
							$format = __('Order %1$s was marked complete by %2$s', 'wpjobster');
							$icon_class = 'accepted green';
						}

						elseif($row->code == -8 || $row->code == -9) {
							// mutual cancellation requested
							$format = __('%2$s has requested to cancel order %1$s', 'wpjobster');
							$icon_class = 'declined red';
						}

						elseif($row->code == -10 || $row->code == -12) {
							// mutual cancellation accepted
							$format = __('%2$s has accepted cancellation request for order %1$s', 'wpjobster');
							$icon_class = 'accepted red';
						}

						elseif ($row->code == -11 || $row->code == -13) {
							// mutual cancellation declined
							$format = __('%2$s has declined cancellation request for order %1$s', 'wpjobster');
							$icon_class = 'declined2 red';
						}

						elseif ($row->code == -14) {
							// cancelled by admin
							$format = __('Order %1$s was cancelled by admin', 'wpjobster');
							$icon_class = 'admin-action red';
						}

						elseif ($row->code == -15) {
							// modification request
							$format = __('%2$s has requested modification for order %1$s', 'wpjobster');
							$icon_class = 'modification orange';
						}

						elseif($row->code == -16 || $row->code == -17) {
							// mutual cancellation requested
							$format = __('%2$s has aborted cancellation for order %1$s', 'wpjobster');
							$icon_class = 'declined2 red';
						}

						elseif ($row->code == -18) {
							// feedback by buyer
							$format = __('%2$s has rated your order %1$s', 'wpjobster');
							$icon_class = 'star orange';
						}

						elseif ($row->code == -19) {
							// feedback by seller
							$format = __('%2$s has replied to your feedback for order %1$s', 'wpjobster');
							$icon_class = 'star orange';
						}
						elseif ($row->code == -21 || $row->code == -22) {
							// feedback by seller
							if($current_uid==$row->bid){
								$format = __('You have Cancelled the pending order ID: %1$s', 'wpjobster');
							}else{
								$format = __('%2$s has Cancelled the pending order ID: %1$s', 'wpjobster');
							}
							$icon_class = 'declined2 red';
						}
						elseif ($row->code == -31) {
							// new extra added
							$format = __('%2$s added new custom extra for order %1$s', 'wpjobster');
							$icon_class = 'sent green';
						}
						elseif ($row->code == -32) {
							// extra cancelled
							$format = __('%2$s cancelled custom extra for order %1$s', 'wpjobster');
							$icon_class = 'declined red';
						}
						elseif ($row->code == -33) {
							// extra declined
							$format = __('%2$s declined custom extra for order %1$s', 'wpjobster');
							$icon_class = 'declined red';
						}
						elseif ($row->code == -34) {
							// extra accepted
							$format = __('%2$s accepted custom extra for order %1$s', 'wpjobster');
							$icon_class = 'accepted green';
						}
						elseif ($row->code == -35) {
							// order cancelled
							$format = __('%2$s cancelled the order %1$s', 'wpjobster');
							$icon_class = 'declined red';
						}

						else {
							$format = __('Order %1$s was updated', 'wpjobster');
							$icon_class = 'sent';
						}
					}
					?>


					<div class="wpj-notification-icon nh-notification-icon <?php echo $icon_class; ?>"></div>

					<div class="wpj-notification-right">
						<?php echo sprintf($format, $transaction_number, $displayname); ?>
						<div class="wpj-notification-date"><?php echo $notification_date; ?></div>
					</div>

				</a>

				<div class="ui checkbox read-me right">
					<input name="chk-notify[]" id="chk-notify" class="chk-notify" value="<?php echo $row->id; ?>" type="checkbox" />
					<label></label>
				</div>

			</li>

			<?php
		} // end foreach
	} // end count

	return count($notifications);
}
