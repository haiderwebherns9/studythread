<?php
function wpj_user_email_vars(){
	$vars = array();

	global $wp_query, $wpdb, $site_url_localized;
	$my_account_url = get_permalink(get_option('wpjobster_my_account_page_id'));

	$status = "";
	$title = "";
	$message = "";

	// check if logged in
	if(is_user_logged_in()) {
		global $current_user;
		$current_user = wp_get_current_user();
	}

	//check GET data & verify
	if (isset($_GET['username']) && !empty($_GET['username']) AND isset($_GET['key']) && !empty($_GET['key'])) {
		$user = get_user_by('slug', $_GET['username']);

		if (!$user) {
			wp_redirect(get_bloginfo('url'));
		}
		elseif (is_user_logged_in() && $current_user->ID != $user->ID) {
			wp_redirect(get_bloginfo('url'));
		}

		if ( isset( $_GET['action'] ) && $_GET['action'] == 'withdrawal' ) {
			global $wpdb;

			$key = isset( $_GET['key'] ) && $_GET['key'] ? $_GET['key'] : '';
			if ( !is_user_logged_in() ) {
				wp_redirect( $my_account_url );
			}
			if ( $key ) {
				$s = "select * from ".$wpdb->prefix."job_withdraw where uid='$user->ID' and activation_key='$key'";
				$row = $wpdb->get_results($s);
				$id = isset($row[0]->id) ? $row[0]->id : '';
				if ( $id ) {
					$ss = "update ".$wpdb->prefix."job_withdraw set activation_key=NULL where id='$id'";
					$wpdb->query($ss);

					$title = __("Withdrawal Confirmed", "wpjobster");
					$message = __("Your withdrawal request was successfully confirmed.", "wpjobster");
				} else {
					$status = "wrongkey";
					$title = __("Wrong Link", "wpjobster");
					$message = __("Oops. It seems that the withdrawal verification link is wrong or the request was already verified.", "wpjobster");
				}
			}
		} else {
			if (get_user_meta($user->ID, 'uz_email_verification', true) == 1 ) {
				if (is_user_logged_in()) {
					wp_redirect($my_account_url);
				}
				if (hash("sha256", $user->user_nicename . get_user_meta($user->ID, 'uz_email_verification_key', true), false) == $_GET['key']) {
					$status = "alreadyok";
					$title = __("Email Verified", "wpjobster");
					$message = __("Your email was already verified.", "wpjobster");
				}
				else {
					$status = "wrongkey";
					$title = __("Wrong Link", "wpjobster");
					$message = __("Oops. It seems that the email verification link is wrong. Please try again or login to your account and generate a new one.", "wpjobster");
				}

			}
			elseif (hash("sha256", $user->user_nicename . get_user_meta($user->ID, 'uz_email_verification_key', true), false) == $_GET['key']) {
				update_user_meta( $user->ID, 'uz_email_verification', 1 );
				$status = "ok";
				$title = __("Email Verified", "wpjobster");
				$message = __("Your email was successfully verified.", "wpjobster");

				//to_do send email successfully activated here
			}
			else {
				$status = "wrongkey";
				$title = __("Wrong Link", "wpjobster");
				$message = __("Oops. It seems that the email verification link is wrong. Please try again or login to your account and generate a new one.", "wpjobster");
			}
		}
	}

	elseif (is_user_logged_in() && isset($_GET['resend']) && $_GET['resend'] == "true" && get_user_meta($current_user->ID, 'uz_email_verification', true) != 1) {
		$email_key = wpjobster_email_verification_init($current_user->ID);
		wpjobster_send_email_allinone_translated('user_verification', $current_user->ID, false, false, false, false, false, false, $email_key);
		wpjobster_send_sms_allinone_translated('user_verification', $current_user->ID, false, false, false, false, false, false, $email_key);

		$status = "resent";
		$title = __("Verification Email Sent", "wpjobster");
		$message = __("The verification email was sent to your email address.", "wpjobster");
	}

	else {
		wp_redirect(get_bloginfo('url'));
	}

	$vars = array(
		'site_url_localized' => $site_url_localized,
		'my_account_url'     => $my_account_url,
		'title'              => $title,
		'message'            => $message
	);

	return $vars;
}
