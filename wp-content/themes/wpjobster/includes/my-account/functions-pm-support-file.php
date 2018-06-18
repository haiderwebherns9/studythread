<?php
function wpj_pm_support_file($otheruid='', $myuid=''){
	// conversation archive
	if (isset($_GET['archive_conversation'] ) && $_GET['archive_conversation'] == 1 && isset($_POST['action_button'] ) && $_POST['action_button'] == 1) {
		if (!is_demo_user()) {
			global $wpdb;
			$s = "UPDATE ".$wpdb->prefix."job_pm "
					."SET archived_to_source = CASE WHEN initiator = '$myuid' then '1' ELSE archived_to_source END,"
					."archived_to_destination = CASE WHEN user = '$myuid' then '1' ELSE archived_to_destination END "
				."WHERE (initiator = '$myuid' AND user = '$otheruid') "
				."OR (initiator = '$otheruid' AND user = '$myuid');";

			$wpdb->query($s);
		}
		die();
	}


	// conversation unarchive
	if (isset($_GET['unarchive_conversation'] ) && $_GET['unarchive_conversation'] == 1 && isset($_POST['action_button']) && $_POST['action_button'] == 1) {

		if (!is_demo_user()) {
			global $wpdb;
			$s = "UPDATE ".$wpdb->prefix."job_pm "
					."SET archived_to_source = CASE WHEN initiator = '$myuid' then '0' ELSE archived_to_source END,"
					."archived_to_destination = CASE WHEN user = '$myuid' then '0' ELSE archived_to_destination END "
				."WHERE (initiator = '$myuid' AND user = '$otheruid') "
				."OR (initiator = '$otheruid' AND user = '$myuid');";
			$wpdb->query($s);
		}
		die();
	}


	// conversation delete
	if (isset($_GET['delete_conversation']) && $_GET['delete_conversation'] == 1 && isset($_POST['action_button'] ) && $_POST['action_button'] == 1) {

		if (!is_demo_user()) {
			global $wpdb;
			$s = "UPDATE ".$wpdb->prefix."job_pm "
					."SET show_to_source = CASE WHEN initiator = '$myuid' then '0' ELSE show_to_source END,"
					."show_to_destination = CASE WHEN user = '$myuid' then '0' ELSE show_to_destination END "
				."WHERE (initiator = '$myuid' AND user = '$otheruid') "
				."OR (initiator = '$otheruid' AND user = '$myuid');";

			$wpdb->query($s);
			wpj_refresh_user_notifications( $myuid, 'messages' );
		}
		die();
	}


	// pm delete
	if(isset($_GET['delete_pm']) && $_GET['delete_pm'] == 1 && isset($_GET['pm_id']) && isset($_POST['delete_pm']) && $_POST['delete_pm'] == 1) {
		if (!is_numeric($_GET['pm_id'])) {
			echo 'ERROR';
			die();
		}
		$pm_id = $_GET['pm_id'];

		if (!is_demo_user()) {
			global $wpdb;
			$s = "SELECT DISTINCT * FROM ".$wpdb->prefix."job_pm WHERE id='$pm_id'";
			$r = $wpdb->get_results($s);

			if(count($r) > 0) {
				$r = $r[0];
				if ($myuid == $r->initiator) {
	//                                echo
					$s = "UPDATE ".$wpdb->prefix."job_pm SET show_to_source='0' WHERE id='$r->id' ";
					$wpdb->query($s);

				} elseif ($myuid == $r->user) {
	//                              echo
					$s = "UPDATE ".$wpdb->prefix."job_pm SET show_to_destination='0' WHERE id='$r->id' ";
					$wpdb->query($s);
					wpj_refresh_user_notifications( $r->user, 'messages' );
				}
			}
		}
		die();
	}

	//the ajax
	if(isset($_GET['ajax']) && $_GET['ajax'] == 'true' && isset($_GET['pg'])) {
		if (!is_numeric($_GET['pg'])) {
			echo 'ERROR';
			die();
		}
		$offset = ($_GET['pg'] * 10) - 10;

		global $wpdb;
		$s = "SELECT * FROM (SELECT * FROM ".$wpdb->prefix."job_pm WHERE (user = '$otheruid' AND initiator = '$myuid' AND show_to_source = '1') OR (initiator = '$otheruid' and user = '$myuid' AND show_to_destination = '1') ORDER BY datemade DESC LIMIT 10 OFFSET ".$offset.") AS t1 ORDER BY datemade ASC";
		$r = $wpdb->get_results($s);

		if(count($r) > 0) {
			echo '<div class="pm-list-ajax">';
			?>
			<?php wpjobster_pm_loop($r, $myuid, $otheruid); ?>

			<?php
			echo '</div>';

		} else {
			_e('No messages here.','wpjobster');
		}
		die();
	}

	$wpjobster_characters_message_max = get_option("wpjobster_characters_message_max");
	$wpjobster_characters_message_max = (empty($wpjobster_characters_message_max)|| $wpjobster_characters_message_max==0)?1200:$wpjobster_characters_message_max;
	$wpjobster_characters_message_min = get_option("wpjobster_characters_message_min");
	$wpjobster_characters_message_min = (empty($wpjobster_characters_message_min))?0:$wpjobster_characters_message_min;


	// pm post
	$isOk=1;
	if (isset($_POST['send'])) {

		if ($_POST['message'] || $_POST['hidden_files_pm_attachments']) {
			$isOk=1;

			$message = trim(nl2br(strip_tags(htmlspecialchars(wpj_encode_emoji($_POST['message'])))));

			$msg2 = get_parsed_countable_string($_POST['message']);

			if(mb_strlen($msg2)<$wpjobster_characters_message_min||mb_strlen($msg2)>$wpjobster_characters_message_max) {
				$isOk = 0;
				$_SESSION['error_message'] = sprintf(__('The message needs to have at least %d characters and %d at most!', 'wpjobster'), $wpjobster_characters_message_min, $wpjobster_characters_message_max);

			} elseif (!is_demo_user()) {

				$tm_tm = current_time('timestamp', 1);
				$pm_files = $_POST['hidden_files_pm_attachments'];

				wpj_insert_private_message( array(
					'content'   => $message,
					'datemade'  => $tm_tm,
					'initiator' => $myuid,
					'user'      => $otheruid,
					'attached'  => $pm_files,
				) );

			}
		}

		// redirect 302 to same page with $_GET
		// to prevent $_POST resubmission on refresh

		header('Location: '.$privurl_m.'?username='.$_GET['username']);
		die;

	}
}
