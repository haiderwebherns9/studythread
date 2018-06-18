<?php

$scriptPath = dirname(__FILE__);
$path = realpath($scriptPath . '/./');
$filepath = explode("wp-content", $path);

define('WP_USE_THEMES', false);
require(''.$filepath[0].'/wp-load.php');

global $wpdb;
$uid = $current_user->ID;

//User Timezone Function
wpjobster_timezone_change();

$s = "select orders.id oid, posts.ID pid, posts.post_author sid, orders.uid bid, chatbox.uid code, chatbox.datemade tm, orders.date_made otm
from ".$wpdb->prefix."job_chatbox chatbox, ".$wpdb->prefix."job_orders orders, $wpdb->posts posts
where chatbox.rd_receiver='0' AND chatbox.oid=orders.id AND chatbox.uid!='$uid' AND posts.ID=orders.pid AND (posts.post_author='$uid' OR orders.uid='$uid') order by tm DESC ";
$r = $wpdb->get_results($s); ?>

<div class="antiscroll-wrap">
	<div class="antiscroll-inner"><?php
		if(count($r) > 0) {
			$i = 0;
			foreach($r as $row) {
				$transaction_number = wpjobster_camouflage_order_id($row->oid, $row->otm);
				$notification_date = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->tm);
				$buyer = get_userdata($row->bid);
				$seller = get_userdata($row->sid);
				if ($row->code > 0
					|| ($uid == $row->sid
						&& ($row->code == 0
							|| $row->code == -2
							|| $row->code == -9
							|| $row->code == -10
							|| $row->code == -11
							|| $row->code == -14
							|| $row->code == -15
							|| $row->code == -17
							|| $row->code == -18
							|| $row->code == -21
							|| $row->code == -33
							|| $row->code == -34
							|| $row->code == -35))
					|| ($uid == $row->bid
						&& ($row->code == -1
							|| $row->code == -8
							|| $row->code == -12
							|| $row->code == -13
							|| $row->code == -16
							|| $row->code == -19
							|| $row->code == -22
							|| $row->code == -31
							|| $row->code == -32))) {
					$i++;

					if ($i == 1) { echo "<ul>"; } ?>
					<li>
						<a href="<?php echo get_bloginfo('url')."/?jb_action=chat_box&oid=".$row->oid; ?>">
							<?php
							if ($uid == $row->sid) {
								$displayname = $buyer->user_login;
							} elseif ($uid == $row->bid) {
								$displayname = $seller->user_login;
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
									// modification request
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
									if($uid==$row->bid){
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
							} ?>

							<div class="nh-notification-icon <?php echo $icon_class; ?>"></div>
							<div class="nh-notification-right">
								<?php echo sprintf($format, $transaction_number, $displayname); ?>
								<div class="nh-notification-date"><?php echo $notification_date; ?></div>
							</div>
						</a>
					</li>
				<?php } // endif
			} // endforeach

			if ($i >= 1) {
				echo "<ul>";
			} else {
				echo '<p>'.__("You don't have any unread notifications.", "wpjobster").'</p>';
			}
		} else {
			echo '<p>'.__("You don't have any unread notifications.", "wpjobster").'</p>';
		} ?>

	</div>

	<?php $all_notifications_page_id = get_option('wpjobster_my_account_all_notifications_page_id'); ?>

	<div class="wpj-view-all-cnt">
		<a href="<?php echo get_permalink($all_notifications_page_id); ?>"><?php _e('View All', 'wpjobster'); ?></a>
	</div>

</div>
