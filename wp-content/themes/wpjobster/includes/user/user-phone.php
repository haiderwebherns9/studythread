<?php
function wpj_user_phone_vars(){
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
	if (isset($_POST['phone_key']) && !empty($_POST['phone_key']) && get_option('wpjobster_verify_phone_numbers') == 'yes') {

		if (get_user_meta($current_user->ID, 'uz_phone_verification', true) == 1) {
			if (is_user_logged_in('wpjobster_verify_phone_numbers')) {
				wp_redirect($my_account_url);
			}
		}
		elseif (get_user_meta($current_user->ID, 'uz_phone_verification', true) != 1
			&& get_user_meta($current_user->ID, 'uz_phone_verification_key', true) == $_POST['phone_key']) {
			update_user_meta( $current_user->ID, 'uz_phone_verification', 1 );
			$status = "ok";
			$title = __("Phone Verified", "wpjobster");
			$message = __("Your phone number was successfully verified.", "wpjobster");

		}
		else {
			$status = "wrongkey";
			$title = __("Wrong Code", "wpjobster");
			$message = __("Oops. It seems that the phone verification code is wrong. Please try again or login to your account and generate a new one.", "wpjobster");
		}
	}elseif (is_user_logged_in() && isset($_GET['resend']) && $_GET['resend'] == "true" && get_user_meta($current_user->ID, 'uz_phone_verification', true) != 1 && get_option('wpjobster_verify_phone_numbers') == 'yes') {

		$verification_key = get_rand_alphanumeric(6);
		update_user_meta($current_user->ID, 'uz_phone_verification_key', $verification_key);
		wpjobster_send_sms($current_user->ID, $verification_key);
		$status = "resent";
		$title = __("SMS Code Sent", "wpjobster");
		$message = __("The verification code was sent to your phone number. Please fill it below and click submit.", "wpjobster");
	}else {
		wp_redirect(get_bloginfo('url'));
	}

	$vars = array(
		'site_url_localized' => $site_url_localized,
		'my_account_url'     => $my_account_url,
		'status'             => $status,
		'title'              => $title,
		'message'            => $message
	);

	return $vars;
}
