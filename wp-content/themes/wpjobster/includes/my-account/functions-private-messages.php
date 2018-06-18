<?php

function wpj_private_messages_vars() {

	$vars = array();
	global $wpdb, $current_user;
	$current_user = wp_get_current_user();
	$myuid = $current_user->ID;

	//User Timezone Function
	wpjobster_timezone_change();

	$third_page = isset($_GET['priv_act']) ? $_GET['priv_act']:"";

	if(empty($third_page)) $third_page = 'home';

	$using_perm = wpjobster_using_permalinks();
	if($using_perm) $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id'));
	else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_priv_mess_page_id'). "&";

	$vars = array(
		'myuid' => $myuid,
		'wpdb' => $wpdb,
		'privurl_m' => $privurl_m
	);

	return $vars;

}

add_action( 'wp_ajax_pm_buttons_action', 'pm_buttons_action' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_pm_buttons_action', 'pm_buttons_action' ); // ajax for not logged in users
function pm_buttons_action(){
	$vars = wpj_private_messages_vars();
	foreach ($vars as $key => $value) {
		$$key = $value;
	}

	if (isset($_POST['withdraw_offer'] ) && $_POST['withdraw_offer'] == 1 && isset($_POST['custom_offer'])) {
		$custom_offer_object = get_post($_POST['custom_offer']);

		if ($custom_offer_object->post_author == $myuid) {
			if (!is_demo_user()) {
				update_post_meta($_POST['custom_offer'], 'offer_withdrawn', 1);
				wpjobster_send_email_allinone_translated('offer_withdr', get_post_meta($_POST['custom_offer'], "offer_buyer", true), $custom_offer_object->post_author);
				wpjobster_send_sms_allinone_translated('offer_withdr', get_post_meta($_POST['custom_offer'], "offer_buyer", true), $custom_offer_object->post_author);
			}
		}
	}

	if (isset($_POST['decline_offer']) && $_POST['decline_offer'] == 1 && isset($_POST['custom_offer'])) {
		if (get_post_meta($_POST['custom_offer'], "offer_buyer", true) == $myuid) {
			$custom_offer_object = get_post($_POST['custom_offer']);

			if (!is_demo_user()) {
				update_post_meta($_POST['custom_offer'], 'offer_declined', 1);
				wpjobster_send_email_allinone_translated('offer_decl', $custom_offer_object->post_author, $myuid);
				wpjobster_send_sms_allinone_translated('offer_decl', $custom_offer_object->post_author, $myuid);
			}
		}
	}
}


function wpj_private_messages_avatar_username($myuid, $wpdb) {

	$vars = array();

	if(isset($_GET['username'])){
		$otheruid = get_user_by('slug', $_GET['username']);
		$otheruid = $otheruid->ID;
	}else{
		$otheruid=$_GET['user_id'];
	}

	if ($otheruid == $myuid || $otheruid == false) {
		wp_redirect(get_permalink());
		die();
	}

	include_once get_template_directory() . '/includes/my-account/functions-pm-support-file.php';

	//display first messages
	$s = "SELECT * FROM (SELECT * FROM ".$wpdb->prefix."job_pm WHERE (user = '$otheruid' AND initiator = '$myuid' AND show_to_source = '1') OR (initiator = '$otheruid' and user = '$myuid' AND show_to_destination = '1') ORDER BY datemade DESC LIMIT 10) AS t1 ORDER BY datemade ASC";

	$r = $wpdb->get_results($s);

	$archive_class = '';
	$unarchive_class = '';

	if (isset($r[0]) &&( ($r[0]->initiator == $myuid && $r[0]->archived_to_source == 1) || ($r[0]->user == $myuid && $r[0]->archived_to_destination == 1)) ) {
		$archive_class = 'pm-action-inactive';

	} else {
		$unarchive_class = 'pm-action-inactive';
	}

	$user = get_userdata($otheruid);
	$user_avatar = '<img width="45" height="45" border="0" src="'.wpjobster_get_avatar($otheruid,46,46).'" class="round-avatar" />';
	$shortened_title = __("Conversation with", "wpjobster") . ' ' . (is_object($user) && isset($user->user_login)?$user->user_login:__("Deleted User",'wpjobster'));
	$shortened_title = mb_strlen( $shortened_title ) > 27 ? mb_substr($shortened_title, 0, 25) . '...' : $shortened_title;

	$vars = array(

		'otheruid' => $otheruid,
		'r' => $r,
		'user' => $user,
		'user_avatar' => $user_avatar,
		'archive_class' => $archive_class,
		'unarchive_class' => $unarchive_class

	);

	return $vars;

}

function wpj_private_messages_select_db1() {

	global $wpdb;

	global $current_user;
	$current_user = wp_get_current_user();
	$myuid = $current_user->ID;

	$s =
	"SELECT id, other, content, datemade, author, rd, max_custom_offer, min_custom_offer "
	."FROM ("
		."SELECT id, other, content, MAX(datemade) AS datemade, author, rd, MAX(custom_offer) AS max_custom_offer, MIN(custom_offer) AS min_custom_offer, archived "
		."FROM ("
			."SELECT id, user AS other, initiator AS author, content, datemade, rd, custom_offer, archived_to_source AS archived "
			."FROM ".$wpdb->prefix."job_pm "
			."WHERE initiator = ".$myuid." "
			."AND show_to_source = '1' "

			."UNION "
			."SELECT id, initiator AS other, initiator AS author, content, datemade, rd, custom_offer, archived_to_destination AS archived "
			."FROM ".$wpdb->prefix."job_pm "
			."WHERE user = ".$myuid." "
			."AND show_to_destination = '1' "

			."ORDER BY datemade DESC"
		.") tmp "
		."GROUP BY other "
		."ORDER BY datemade DESC) tmp2 "
	."WHERE archived = '1' ";

	$r = $wpdb->get_results($s);

	return $r;

}

function wpj_private_messages_select_db2() {

	global $wpdb;
	global $current_user;
	$current_user = wp_get_current_user();
	$myuid = $current_user->ID;

	$s =
	"SELECT id, other, content, datemade, author, rd, max_custom_offer, min_custom_offer "
	."FROM ("
		."SELECT id, other, content, MAX(datemade) AS datemade, author, rd, MAX(custom_offer) AS max_custom_offer, MIN(custom_offer) AS min_custom_offer, archived "
		."FROM ("
			."SELECT id, user AS other, initiator AS author, content, datemade, rd, custom_offer, archived_to_source AS archived "
			."FROM ".$wpdb->prefix."job_pm "
			."WHERE initiator = ".$myuid." "
			."AND show_to_source = '1' "

			."UNION "
			."SELECT id, initiator AS other, initiator AS author, content, datemade, rd, custom_offer, archived_to_destination AS archived "
			."FROM ".$wpdb->prefix."job_pm "
			."WHERE user = ".$myuid." "
			."AND show_to_destination = '1' "

			."ORDER BY datemade DESC"
		.") tmp "
		."GROUP BY other "
		."ORDER BY datemade DESC) tmp2 "
	."WHERE archived = '0' ";

	$r = $wpdb->get_results($s);

	return $r;

}


function wpj_private_messages_return_messages($r, $privurl_m, $live='') {

	if(count($r) > 0) {

		foreach($r as $row) {

			if( $live && $live != '' ){
				$row->other = $row->initiator;
				$row->author = $row->initiator;
				$row->max_custom_offer = $row->custom_offer;
				$row->min_custom_offer = $row->custom_offer;
			}

			// pre_print_r($row);
			if($row->rd == 0) $cls = '';
			else $cls = '';

			$user = get_userdata($row->other);
			if(is_object($user) && isset($user->ID)){
				$userid_data = $user->ID;
				$user_name = $user->user_login;
				$user_found = 1;
			}else{
				$userid_data = $row->other;
				$user_found = 0;
				$user_name = __("Deleted User",'wpjobster');
			}
			$user_avatar = '<img width="45" height="45" border="0" src="'.wpjobster_get_avatar($userid_data,46,46).'" class="round-avatar" />';
			if (mb_strlen($user_name)>17) {
				$user_name = mb_substr($user_name, 0, 15) . '...';
			}
			$pm_content = $row->content;
			if ($row->author == $row->other && $row->rd == 0) {
				$read_class = 'pm-unread-message';
			} else {
				$read_class = '';
			}
			if ($row->max_custom_offer != 0 && $row->min_custom_offer != 0) {
				$custom_offer_class = 'pm-custom-offer';
			} else {
				$custom_offer_class = '';
			}

			list($pm_content, $validation_errors) = filterMessagePlusErrors($pm_content, true);

			$pm_content = str_replace("\n", "", $pm_content);
			$pm_content = str_replace("\r", "", $pm_content);
			$pm_content = str_replace("\r\n", "", $pm_content);
			$pm_content = str_replace("<br />", " ", $pm_content);

			if (mb_strlen($pm_content)>110) {
				$pm_content = mb_substr($pm_content, 0, 108) . '...';
			}

			echo '<div class="ui notpadded segment pm-holder cf '.$read_class.' '.$custom_offer_class.'">';
			if(isset($user) && is_object($user)){
				echo '<a class="link-to-pm cf" href="?username='.$user->user_nicename.'">';
			}else{
				echo '<a class="link-to-pm cf" href="?user_id='.$userid_data.'" title="'.__("This user has been deleted",'wpjobster').'">';
			}

				echo '<div class="pm-avatar">'.$user_avatar . '</div>';

				echo '<div class="pm-content cf">';
					echo '<div class="">';
						echo '<h3>' . $user_name;
							if ( isset( $user->ID ) ) {
								$u_id = $user->ID;
							} else {
								$u_id = '';
							}
							include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );
						echo '</h3>';
						echo '<div class="pm-right">'.date_i18n(get_option( 'date_format' ),$row->datemade).'</div>';
					echo '</div>';

					echo '<div class="">';
					if ($custom_offer_class) {
						?>
						<i class="tags icon"></i>
						<?php
					}
					echo stripslashes( $pm_content );
					echo '</div>';

				echo '</div>';
				echo '<div class="pm-actions-list">';
					if (isset($_GET['type']) && $_GET['type'] == 'archived') {
						if(is_object($user)){
							echo '<a class="action-button action-listener action-archive action-tooltip ei-unarchive-icon" href="'.$privurl_m.'?username='.$user->user_nicename.'&unarchive_conversation=1"><span>'.__("Unarchive Conversation", "wpjobster").'</span><i class="upload icon"></i></a>';
						}
					} else {
						if(is_object($user)){
							echo '<a class="action-button action-listener action-archive action-tooltip" href="'.$privurl_m.'?username='.$user->user_nicename.'&archive_conversation=1"><span>'.__("Archive Conversation", "wpjobster").'</span><i class="download icon"></i></a>';
						}else{
							echo '<a class="action-button action-listener action-archive action-tooltip" href="'.$privurl_m.'?user_id='.$userid_data.'&archive_conversation=1"><span>'.__("Archive Conversation", "wpjobster").'</span><i class="download icon"></i></a>';
						}
					}
					if(is_object($user)){
						echo '<div class="action-confirm"><a class="action-listener pm-action-green" href="'.$privurl_m.'?username='.$user->user_nicename.'&delete_conversation=1">'.__("Delete", "wpjobster").'</a> / <span class="pm-action-deny-delete">'.__("Cancel", "wpjobster").'</span></div>';
					}else{
						echo '<div class="action-confirm"><a class="action-listener pm-action-green" href="'.$privurl_m.'?user_id='.$row->other.'&delete_conversation=1">'.__("Delete", "wpjobster").'</a> / <span class="pm-action-deny-delete">'.__("Cancel", "wpjobster").'</span></div>';
					}

					echo '<a class="action-button action-confirm-request action-delete action-tooltip" href="#"><span>'.__("Delete Conversation", "wpjobster").'</span><i class="trash icon"></i></a>';
				echo '</div>';
			echo '</a></div>';
		}

	}

	if( $live == '' ){
		if(count($r) <= 0) {
			echo '<div id="no_messages" class="ui segment">';
			_e('No messages here.','wpjobster');
			echo '</div>';
		}
	}

}
