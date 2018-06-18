<?php
if ( ! function_exists( 'wpjobster_display_subscription_icon' ) ) {
	function wpjobster_display_subscription_icon( $uid ) {
		// deprecated, calling the new function
		wpjobster_display_user_subscription_icon( $uid );
	}
}

if ( ! function_exists( 'wpjobster_display_user_subscription_icon' ) ) {
	function wpjobster_display_user_subscription_icon( $uid ) {
		echo wpjobster_get_user_subscription_icon( $uid );
	}
}

if ( ! function_exists( 'wpjobster_get_user_subscription_icon' ) ) {
	function wpjobster_get_user_subscription_icon( $uid ) {
		$html = '';
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info( $uid );
		extract( $wpjobster_subscription_info );

		if ( $wpjobster_subscription_icon_url && validate_image_file( $wpjobster_subscription_icon_url ) ) {
			$html .= '<img src="' . $wpjobster_subscription_icon_url . '" class="subscription-user-icon">';
		}
		return $html;
	}
}
