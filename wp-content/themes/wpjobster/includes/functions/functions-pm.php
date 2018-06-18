<?php
function wpjobster_get_unread_number_messages($uid){
	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "job_pm where user='$uid' and show_to_destination='1' and rd='0'";
	$r = $wpdb->get_results($s);
	return count($r);
}

add_action( 'init', 'wpj_old_ajax_frontend_delete_message' );
function wpj_old_ajax_frontend_delete_message() {
	//-=================== delete PMs ============================
	if(!is_demo_admin()){
		global $wpdb;

		if (isset($_GET['confirm_message_deletion'])) {
			$return = $_GET['return'];
			$messid = $_GET['id'];
			global $wpdb, $current_user;
			$current_user = wp_get_current_user();
			$uid = $current_user->ID;
			$s = "select * from " . $wpdb->prefix . "job_pm where id='$messid' AND (user='$uid' OR initiator='$uid')";
			$r = $wpdb->get_results($s);

			if (count($r) > 0) {
				$row = $r[0];

				if ($row->initiator == $uid) {
					$s = "update " . $wpdb->prefix . "job_pm set show_to_source='0' where id='$messid'";
					$wpdb->query($s);
				} else {
					$s = "update " . $wpdb->prefix . "job_pm set show_to_destination='0' where id='$messid'";
					$wpdb->query($s);
					wpj_refresh_user_notifications( $row->user, 'messages' );
				}

				$using_perm = wpjobster_using_permalinks();

				if ($using_perm)            $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')) . "/?"; else            $privurl_m = get_bloginfo('url') . "/?page_id=" . get_option('wpjobster_my_account_priv_mess_page_id') . "&";

				if ($return == "inbox")            wp_redirect($privurl_m . "priv_act=inbox"); else
				if ($return == "outbox")            wp_redirect($privurl_m . "priv_act=sent-items"); else
				if ($return == "home")            wp_redirect($privurl_m); else            wp_redirect(get_permalink(get_option('wpjobster_my_account_page_id')));
			} else        wp_redirect(get_permalink(get_option('wpjobster_my_account_page_id')));
		}
	}
}

if (!function_exists('wpjobster_pm_loop')) {
	function wpjobster_pm_loop($r, $myuid, $otheruid) {

		//User Timezone Function
		wpjobster_timezone_change();

		$otheruid_slug = get_user_by('id', $otheruid);
		if(is_object($otheruid_slug)&& isset($otheruid_slug->user_nicename)){
			$otheruid_slug = $otheruid_slug->user_nicename;
		}else{
			$otheruid_slug = false;//$otheruid_slug->user_nicename;
		}
		$page_url = get_permalink( get_option( 'wpjobster_my_account_priv_mess_page_id' ) );
		global $wpjobster_currencies_array;

		foreach($r as $row) {
			if ($myuid != $row->initiator && $row->rd == 0) {
				wpj_read_private_message( $row );
			} // marked as read

			if ($row->custom_offer == -1 ) {
				if ($myuid == $row->initiator) {
					$bgcolor = 'pm-request-holder lightgreybg';

				} else {
					$bgcolor = 'pm-request-holder';
				}

			} elseif ($row->custom_offer > 0 ) {
				if ($myuid == $row->initiator) {
					$bgcolor = 'pm-offer-holder lightgreybg';

				} else {
					$bgcolor = 'pm-offer-holder';
				}

			} elseif ($myuid == $row->initiator) {
				$bgcolor = 'lightgreybg';

			} else {
				$bgcolor = '';
			}

			$user = get_userdata($row->initiator);
			if(is_object($user) && isset($user->ID)){
				$user_avatar = '<img width="45" height="45" border="0" src="'.wpjobster_get_avatar($user->ID,46,46).'" class="round-avatar" />';
			}else{
				$user_avatar = '<img width="45" height="45" border="0" src="'.wpjobster_get_avatar($row->initiator,46,46).'" class="round-avatar" />';
			}

			$custom_offer_class = "";
			$title_icon = "";

			if ($row->custom_offer == -1) {
				if ($myuid == $row->user) {
					$custom_offer_class = "pm-offer-blue";
					$title_icon = "title-tag-icon";

				} else {
					$custom_offer_class = "pm-offer-gray";
					$title_icon = "title-tag-icon";

				}

			} elseif ($row->custom_offer > 0) {

				// expired offer?
				if (get_post_meta($row->custom_offer, "offer_expired", true) == 0) {
					if (current_time('timestamp', 1) > get_post_meta($row->custom_offer, "offer_date_expire", true)) {

						update_post_meta($row->custom_offer, 'offer_expired', 1);
						wpjobster_send_email_allinone_translated('offer_exp', $row->user, $row->initiator);
						wpjobster_send_sms_allinone_translated('offer_exp', $row->user, $row->initiator);
					}
				}


				if (get_post_meta($row->custom_offer, "offer_accepted", true) == 1) {
					$custom_offer_class = "pm-offer-green";
					$title_icon = "title-check-icon";

				} elseif (get_post_meta($row->custom_offer, "offer_declined", true) == 1) {
					$custom_offer_class = "pm-offer-red";
					$title_icon = "title-close-icon";

				} elseif (get_post_meta($row->custom_offer, "offer_withdrawn", true) == 1) {
					$custom_offer_class = "pm-offer-gray";
					$title_icon = "title-minus-icon";

				} elseif (get_post_meta($row->custom_offer, "offer_expired", true) == 1) {
					$custom_offer_class = "pm-offer-gray";
					$title_icon = "title-clock-icon";

				} elseif ($myuid == $row->user) {
					$custom_offer_class = "pm-offer-blue";
					$title_icon = "title-tag-icon";

				} else {
					$custom_offer_class = "pm-offer-gray";
					$title_icon = "title-tag-icon";

				}
			}


			echo '<div id="pm_'.$row->id.'" class="pm-holder cf '.$bgcolor.' '.$custom_offer_class.' '.$title_icon.'"><div class="padding-cnt relative cf">';


			if ($row->custom_offer == -1) {
				echo '<div class="pm-custom-offer-request-header">';
				if ($myuid == $row->initiator) {
					echo __("Custom Offer Request Sent", "wpjobster");
				} elseif ($myuid == $row->user) {
					echo __("Custom Offer Request Received", "wpjobster");
				}

				echo '</div>';

			} elseif ($row->custom_offer > 0) {
				echo '<div class="pm-custom-offer-offer-header">';
				if ($myuid == $row->initiator) {
					echo __("Custom Price Offer Sent", "wpjobster");
				} elseif ($myuid == $row->user) {
					echo __("Custom Price Offer Received", "wpjobster");
				}

				echo '</div>';
			}

			echo '<div class="pm-avatar"><a href="'.(is_object($user)&&isset($user->user_login)?wpjobster_get_user_profile_link($user->user_login):"javascript:void(0)").'">'.$user_avatar;
			echo '</a></div>';

			echo '<div class="pm-actions">';

			echo '<div class="pm-delete-confirm"><a class="pm-delete-ok pm-action-green" href="';
			echo isset($privurl_m)?$privurl_m:"";
			if($otheruid_slug){
				echo '?username='.$otheruid_slug.'&delete_pm=1&pm_id='.$row->id.'">'.__("Delete", "wpjobster").'</a> / <span class="pm-action-deny-delete">'.__("Cancel", "wpjobster").'</span></div>';
			}else{
				echo '?user_id='.$otheruid.'&delete_pm=1&pm_id='.$row->id.'">'.__("Delete", "wpjobster").'</a> / <span class="pm-action-deny-delete">'.__("Cancel", "wpjobster").'</span></div>';
			}

			echo '<a class="pm-delete action-tooltip" href="#"><span>'.__("Delete Message", "wpjobster").'</span><i class="trash icon"></i></a>';
			echo '</div>';


			echo '<div class="pm-content cf">';
			if(is_object($user)&&isset($user->user_login)){
				echo '<div class="pm-user"><a href="'.wpjobster_get_user_profile_link($user->user_login).'">'.$user->user_login.'</a></div>';
			}else{
				echo '<div class="pm-user"><a href="javascript:void(0)">'.__("Deleted User",'wpjobster').'</a></div>';
			}


			// Filtered Message + Errors
			echo '<div>';
			$message = stripslashes( $row->content );
			list($message, $validation_errors) = filterMessagePlusErrors($message);

			echo wpj_make_links_clickable( $message );
			echo displayValidationErrors($validation_errors);
			echo '</div>';

			// Display Attachments
			if ($row->attached) {
				echo '<div class="pm-attachments"><div class="pm-attachments-title">';
				_e("Attachments", "wpjobster");
				echo '</div>';
				$attachments = explode(",", $row->attached);
				foreach ($attachments as $attachment) {
					echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
					echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div><br>';
				}
				echo '</div>';
			}

			echo '<div class="pm-date">'.date_i18n(get_option( 'date_format' ),$row->datemade).'</div>';


			echo '</div>';


			if ($row->custom_offer > 0) {


			}


			// custom offer buttons and messages
			if ($myuid == $row->user && $row->custom_offer == -1) {
				if($otheruid_slug){

					echo '<div class="pm-custom-offer-bottom open-custom-request-received-modal cf"><div class="pm-custom-offer-actions cf"><a href="" data-jid="'.$row->associate_job_id.'" class="offer-link2 custom-offer-pm btn green">' . __("Create Custom Offer", "wpjobster") . '</a></div></div>';

				}else{
					// echo '<div class="pm-custom-offer-bottom open-custom-request-received-modal cf"><div class="pm-custom-offer-actions cf"><a href="javascript:void(0)" class="offer-link custom-offer-pm btn green">' . __("Create Custom Offer", "wpjobster") . '</a></div></div>';
				}
			} elseif ($row->custom_offer > 0) {


				$delivery_days = get_post_meta($row->custom_offer, "max_days", true);
				$days_word = _n("day", "days", $delivery_days, "wpjobster");

				echo '<div class="pm-custom-offer-bottom cf"><div class="pm-custom-offer-meta cf">';
					echo '<table>';
					echo '<tr><td>';
					echo __("Price", "wpjobster") . ':';
					echo '</td><td>';
					echo wpjobster_get_show_price_classic(get_post_meta($row->custom_offer, "price", true), 1);
					echo '</td></tr>';
					echo '<tr><td>';
					echo __("Duration", "wpjobster") . ':';
					echo '</td><td>';
					echo $delivery_days . ' ' . $days_word ;
					echo '</td></tr>';
					echo '</table>';
				echo '</div>';


				// the view
				echo '<div class="pm-custom-offer-actions cf"><div class="btn-margin-bottom-helper">';
				if ($myuid == $row->initiator) {
					if (get_post_meta($row->custom_offer, "offer_accepted", true) == 1) {
						echo '<span class="offer-green">';
						_e("Your offer was accepted!", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_declined", true) == 1) {
						echo '<span class="offer-red">';
						_e("Your offer was declined.", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_withdrawn", true) == 1) {
						echo '<span class="offer-red">';
						_e("You have withdrawn your offer.", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_expired", true) == 1) {
						echo '<span class="offer-red">';
						_e("Your offer expired.", "wpjobster");
						echo '</span>';
					} else {

						if( is_singular( 'request' ) ){
							$data_withdraw = is_user_logged_in() ? 'data-submit="[name=withdraw'.$row->custom_offer.']"' : '';
						}else{
							$data_withdraw = '';
						} ?>

						<a <?php echo $data_withdraw; ?> data-id="<?php echo $row->id; ?>" class="withdraw_custom_offer custom-offer-pm btn lightgrey_btn"><?php _e("Withdraw Custom Offer", "wpjobster"); ?></a>

						<form id="withdraw_custom_offer" method="post" name="withdraw<?php echo $row->custom_offer; ?>" action="" style="display: none;">
							<input type="hidden" value="1" name="withdraw_offer" />
							<input type="hidden" value="pm_buttons_action" name="action" />
							<input type="hidden" value="<?php echo $row->custom_offer; ?>" name="custom_offer" />
						</form>

						<?php
					}

				} elseif ($myuid == $row->user) {
					if (get_post_meta($row->custom_offer, "offer_accepted", true) == 1) {
						echo '<span class="offer-green">';
						_e("Your have accepted the offer!", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_declined", true) == 1) {
						echo '<span class="offer-red">';
						_e("You have declined the offer.", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_withdrawn", true) == 1) {
						echo '<span class="offer-red">';
						_e("The offer was withdrawn by the seller.", "wpjobster");
						echo '</span>';
					} elseif (get_post_meta($row->custom_offer, "offer_expired", true) == 1) {
						echo '<span class="offer-red">';
						_e("The offer expired.", "wpjobster");
						echo '</span>';
					} else {
						$prc = get_post_meta($row->custom_offer, "price", true);
						?>

						<form method="post" name="purchase<?php echo $row->custom_offer; ?>" action="<?php echo get_bloginfo('url') . '/?jb_action=purchase_this&jobid=' . $row->custom_offer; ?>">
							<button data-id="<?php echo $row->id; ?>" type="submit" class="custom-offer-pm btn green"><?php _e("Accept Custom Offer", "wpjobster"); ?>
								<strong class="total"
									data-price="<?php echo wpjobster_formats_special_exchange( $prc ); ?>"
									data-cur="<?php echo get_cur(); ?>"
									><?php echo wpjobster_get_show_price($prc); ?>
								</strong>
							</button>
							<input type="hidden" name="purchaseformvalidation" value="ok" />
						</form>

						<span>
							<?php
							if( is_singular( 'request' ) ){
								$data_decline = is_user_logged_in() ? 'data-submit="[name=decline'.$row->custom_offer.']"' : '';
							}else{
								$data_decline = '';
							} ?>
							<a <?php echo $data_decline; ?> data-id="<?php echo $row->id; ?>" class="decline_custom_offer custom-offer-pm btn lightgrey_btn"><?php _e("Decline Custom Offer", "wpjobster"); ?></a>

							<form id="decline_custom_offer" method="post" name="decline<?php echo $row->custom_offer; ?>" action="" style="display: none;">
								<input type="hidden" value="1" name="decline_offer" />
								<input type="hidden" value="pm_buttons_action" name="action" />
								<input type="hidden" value="<?php echo $row->custom_offer; ?>" name="custom_offer" />
							</form>
						</span>

						<?php
					}
				}
				echo '</div></div></div>';
			} // end custom offer


			echo '</div>';

			echo '</div>';

		}
	}
}

add_action( 'wp_ajax_nopriv_wpj_ajax_send_message_users', 'wpj_ajax_send_message_users' );
add_action( 'wp_ajax_wpj_ajax_send_message_users', 'wpj_ajax_send_message_users' );
function wpj_ajax_send_message_users() {
	global $current_user;
	$current_user = wp_get_current_user();
	$myuid=$current_user->ID;
	$otheruid = $_POST['otheruid'];
	$message = $_POST['message'];
	$upload = $_POST['upload'];

	$wpjobster_characters_message_max = get_option("wpjobster_characters_message_max");
	$wpjobster_characters_message_max = ( empty( $wpjobster_characters_message_max )|| $wpjobster_characters_message_max ==0 )?1200:$wpjobster_characters_message_max;
	$wpjobster_characters_message_min = get_option( "wpjobster_characters_message_min" );
	$wpjobster_characters_message_min = ( empty( $wpjobster_characters_message_min ) )?0:$wpjobster_characters_message_min;

	$isOk=1;


		if ( $_POST['message'] || $_POST['upload'] ) {

			$isOk=1;

			 $message = trim( nl2br( strip_tags( htmlspecialchars( wpj_encode_emoji( $_POST['message'] ) ) ) ) );

			 $msg2 = get_parsed_countable_string($_POST['message']);

			if(mb_strlen($msg2)<$wpjobster_characters_message_min||mb_strlen($msg2)>$wpjobster_characters_message_max) {
				$isOk = 0;
				$_SESSION['error_message'] = sprintf(__('The message needs to have at least %d characters and %d at most!', 'wpjobster'), $wpjobster_characters_message_min, $wpjobster_characters_message_max);

			} elseif (!is_demo_user()) {

				$tm_tm = current_time('timestamp', 1);
				$pm_files = $_POST['upload'];

				global $wpdb;
				$s = "insert into ".$wpdb->prefix."job_pm (content, datemade, initiator, user, attached) values('$message','$tm_tm','$myuid','$otheruid','$pm_files')";
				$wpdb->query($s);
				$this_pm = $wpdb->insert_id; // !!! saved this first, because it changes after the first add_post_meta in the loop below !!!
				if ( $pm_files ) {
					$pm_files_array = explode( ',', $pm_files );
					foreach ( $pm_files_array as $attachment ) {

						add_post_meta( $attachment, 'pm_id', $this_pm );

					}
				}

				$messages = get_user_meta( $otheruid, 'messages_number', true );
				if ( is_numeric( $messages ) ) {
					$messages = $messages + 1;
					update_user_meta( $otheruid, 'messages_number', $messages );
				} else {
					wpj_refresh_user_notifications( $otheruid, 'messages' );
				}

				wpjobster_send_email_allinone_translated( 'new_message', $otheruid, $myuid );
				wpjobster_send_sms_allinone_translated( 'new_message', $otheruid, $myuid );

			}
		}

	$query_msg = "SELECT * FROM " . $wpdb->prefix . "job_pm WHERE id =".$this_pm;
	$r = $wpdb -> get_results( $query_msg );

	wpjobster_pm_loop( $r, $myuid, $otheruid );

	wp_die();
}

add_action( 'init', 'wpj_update_all_cumstom_offer' );
function wpj_update_all_cumstom_offer(){
	global $wpdb;

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$updated = get_user_meta( $uid, "wpj_custom_offers_id_updated", true );

	if( $updated != 'done' ){
		$query = "select * from " . $wpdb->prefix . "job_pm where ( user='" . $uid . "' or initiator='" . $uid . "' ) and custom_offer != '0'";
		$result = $wpdb->get_results($query);

		if( count( $result ) > 0 ){
			foreach ($result as $row) {
				$id = $uid.$row->id.$row->custom_offer.$row->datemade;
				update_post_meta( $id, 'custom_offer_declined', 'done' );
				update_post_meta( $id, 'custom_offer_withdrawn', 'done' );
				update_post_meta( $id, 'custom_offer_accepted', 'done' );
			}
		}

		update_user_meta( $uid, "wpj_custom_offers_id_updated", "done" );
	}
}
