<?php
function wpjobster_get_country_code_of_ip($ip){
	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "job_ipcache where ipnr='$ip'";
	$r = $wpdb->get_results($s);

	$ipLiteKey = get_option('wpjobster_ip_key_db');

	if (count($r) == 0) {
		$url = "http://api.ipinfodb.com/v3/ip-city/?key=$ipLiteKey&ip=$ip&format=json";
		$ip2location_data = wpjobster_get_cURL_data($url);
		$decoded_data = json_decode($ip2location_data);

		$ccode = $decoded_data->countryCode;
		$s = "insert into " . $wpdb->prefix . "job_ipcache (ipnr, country, info) values('$ip','$ccode','$ip2location_data')";
		$wpdb->query($s);
		return $ccode;
	}

	return $r[0]->country;
}

if ( ! function_exists( 'display_user_flag' ) ) {
	function display_user_flag( $uid ) {
		// deprecated, calling the new function
		wpjobster_display_user_flag( $uid );
	}
}

if ( ! function_exists( 'wpjobster_display_user_flag' ) ) {
	function wpjobster_display_user_flag( $uid ) {
		echo wpjobster_get_user_flag( $uid );
	}
}

if ( ! function_exists( 'wpjobster_get_user_flag' ) ) {
	function wpjobster_get_user_flag( $uid ) {
		$enable_flags = get_option( 'wpjobster_en_country_flags' );
		$html = '';

		if ( $enable_flags == 'yes' ) {
			$country_code = get_user_meta( $uid, 'country_code', true );
			$country_name = get_country_name( $country_code );

			if ( $country_name ) {
				$flag_code = strtolower( $country_code );

				if ( $flag_code ) {
					$html .= '<div class="user-flag-shadow tooltip"><img class="user-flag" src="' . get_template_directory_uri() . '/images/flags/' . $flag_code . '.png" /><span>' . $country_name . '</span></div>';
				}
			}
		}

		return $html;
	}
}

if ( ! function_exists( 'display_user_flag_and_country' ) ) {
	function display_user_flag_and_country( $uid ) {
		// deprecated, calling the new function
		wpjobster_display_user_flag_and_country( $uid );
	}
}

if ( ! function_exists( 'wpjobster_display_user_flag_and_country' ) ) {
	function wpjobster_display_user_flag_and_country($uid) {
		$enable_flags = get_option('wpjobster_en_country_flags');

		if ($enable_flags == 'yes') {
			$country_code = get_user_meta($uid, 'country_code', true);
			$country_name = get_country_name($country_code);

			if ($country_name) {
				$flag_code = strtolower($country_code);

				if ($flag_code) {
					echo '<div class="user-flag-shadow tooltip"><img class="user-flag" src="' . get_template_directory_uri() . '/images/flags/' . $flag_code . '.png" /><span>' . $country_name . '</span></div>' . $country_name;
				}
			}
		}
	}
}

function wpjobster_get_user_country($uid){
	$opt = get_option('wpjobster_en_country_flags');

	if ($opt == 'yes') {
		$code = 'us';
		$ip = get_user_meta($uid, 'ip_reg', true);
		$code = wpjobster_get_country_code_of_ip($ip);
		$code = strtolower($code);

		if (empty($code))            $code = 'us';
		$code = apply_filters('wpjobster_code_country_ip', $code);
		return $code;
	}
}

function tmp_update_users_country() {
	$user_query = new WP_User_Query( array( 'role' => 'Subscriber' ) );
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			echo '<p>' . $user->display_name . '</p>';

			if (get_user_meta($user->ID, 'country', true)) {
				echo '<p>' . $user->ID . '</p>';
				$country = get_user_meta($user->ID, 'country', true);

				if ($country == 'Srbija' || $country == 'SP' || $country == 'RS') { $country_code = 'RS'; }
				elseif ($country == 'HR') { $country_code = 'HR'; }
				elseif ($country == 'BA') { $country_code = 'BA'; }
				elseif ($country == 'SI') { $country_code = 'SI'; }
				elseif ($country == 'CG') { $country_code = 'CG'; }
				elseif ($country == 'MK') { $country_code = 'MK'; }
				else { $country_code = 'RS'; }


				// THE UPDATE!!!
				update_user_meta($user->ID, 'country_code', $country_code);
				echo '<p>***' . get_user_meta($user->ID, 'country_code', true) . '</p>';
			}
		}
	} else {
		echo 'No users found.';
	}
}
