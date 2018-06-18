<?php
if( !function_exists( 'wpj_user_badges_vars' ) ){
	function wpj_user_badges_vars(){
		$vars = array();

		if(!isset($_SESSION)) {
			session_start();
		}
		if(!is_user_logged_in()) { wp_redirect(get_bloginfo('url')); exit; }

		global $current_user;
		$current_user = wp_get_current_user();

		global $wp_query;

		$price_first_badge = get_option('wpjobster_first_badge_price');
		$price_second_badge = get_option('wpjobster_second_badge_price');

		$currency = wpjobster_get_currency_classic();

		$crds = wpjobster_get_credits($current_user->ID);
		$uid = $current_user->ID;

		if(isset($_GET['action'])&&$_GET['action']=='success'&&isset($_GET['method'])&&$_GET['method']=='paypal'){
			while (get_user_meta($uid, 'uz_last_order_ok', true) != '1') {
				sleep(1);
			}

			update_user_meta( $uid, 'uz_last_order_ok', '0' );

			wp_redirect(wpjobster_my_account_link());
		}

		$date_format = get_option( 'date_format' );

		if(isset($_GET['method'])){

			// calculate total price
			$price=0;

			if (wpjobster_user_eligible_for_first_badge($uid)) {
				$price = get_option('wpjobster_first_badge_price');
			} elseif (wpjobster_user_eligible_for_second_badge($uid)) {
				$price = get_option('wpjobster_second_badge_price');
			}


			if($_GET['method']=='balance'){
				if($crds<$price){
					$f_err = __("You don't have enough money in your balance. Choose one of the other payment methods.", 'wpjobster');
					wp_redirect(get_permalink(get_option('wpjobster_my_account_payments_page_id')).'topup');
				}
			}

			if (!wpjobster_user_eligible_for_first_badge($uid)
				&& !wpjobster_user_eligible_for_second_badge($uid)) {
				$f_err = __("You are not eligible to buy the badge.", 'wpjobster');
			}


			// succes
			if(!isset($f_err)){
				// go to pay page
				wp_redirect(get_bloginfo('url')."?pay_badges=".$_GET['method']);
			}

		}

		if(!isset($f_err)){
			$f_err = '';
		}

		$vars = array(
			'f_err' => $f_err,
			'uid' => $uid
		);

		return $vars;
	}
}
if (!function_exists('wpjobster_show_badge_user')) {
	function wpjobster_show_badge_user($uid)    {
		$user_level = wpjobster_get_user_level($uid);

		if ($user_level == "1")            return '<div class="user_level1"></div>';
		if ($user_level == "2")            return '<div class="user_level2"></div>';
		if ($user_level == "3")            return '<div class="user_level3"></div>';
	}
}

if ( ! function_exists( 'wpjobster_display_user_badges' ) ) {
	function wpjobster_display_user_badges( $uid ) {
		echo wpjobster_get_user_badges( $uid );
	}
}

if ( ! function_exists( 'wpjobster_get_user_badges' ) ) {
	function wpjobster_get_user_badges( $uid ) {
		$html = '';
		$user_badge = get_user_meta( $uid, 'user_badge', true );

		$html .= '<div class="ub-badges-cnt">';
			if ( $user_badge == 1 ) {

				$html .= '<div class="ub-badge tooltip ub-verified"><span>' . __( "Verified User", "wpjobster" ) . '</span></div>';

			} elseif ( $user_badge == 2 ) {
				$second_badge_style = '';
				$wpjobster_second_badge_icon = get_option( 'wpjobster_second_badge_icon' );
				if ( $wpjobster_second_badge_icon ) {
					$second_badge_style = 'background-image: url(' . $wpjobster_second_badge_icon . '); background-position: 0px 0px; background-size: 100%;';
				}

				$html .= '<div class="ub-badge tooltip ub-verified" style="' . $second_badge_style . '">';
					$html .= '<span>' . __("This user has passed our verification process", "wpjobster") . '</span>';
				$html .= '</div>';
			}

			$user_email_verification = get_user_meta( $uid, 'uz_email_verification', true );
			if ( $user_email_verification == 1 ) {
				$html .= '<div class="ub-badge tooltip ub-verified-email">';
					$html .= '<span>' . __( "This user verified his email address", "wpjobster" ) . '</span>';
				$html .= '</div>';
			}
		$html .= '</div>';
		return $html;
	}
}

if (!function_exists('wpjobster_show_badge_user2')) {
	function wpjobster_show_badge_user2($uid)    {
		$user_badge = get_user_meta($uid, 'user_badge', true);

		if ($user_badge == "1") return '<div class="user_badge1"></div>';
		if ($user_badge == "2") return '<div class="user_badge2"></div>';
	}
}

if (!function_exists('wpjobster_show_badge_user_account_panel')) {
	function wpjobster_show_badge_user_account_panel($uid)    {
		$user_level = wpjobster_get_user_level($uid);

		if ($user_level == "1") return '<div class="user_level1_u"></div>';
		if ($user_level == "2") return '<div class="user_level2_u"></div>';
		if ($user_level == "3") return '<div class="user_level3_u"></div>';
	}
}

if (!function_exists('wpjobster_show_badge_user_account_panel2')) {
	function wpjobster_show_badge_user_account_panel2($uid)    {
		$user_badge = get_user_meta($uid, 'user_badge', true);

		if ($user_badge == "1") return '<div class="user_badge1u"></div>';
		if ($user_badge == "2") return '<div class="user_badge2u"></div>';
	}
}

add_action('wp_ajax_update_badge_user', 'wpjobster_update_badge_user');
function wpjobster_update_badge_user(){
	if (current_user_can('manage_options'))
	if ($_POST['action'] == "update_badge_user") {
		$uid = $_POST['uid'];
		$level0 = $_POST['level0'];
		$level1 = $_POST['level1'];
		$level2 = $_POST['level2'];

		if ($level1 == "1"){
			update_user_meta($uid, 'user_badge', "1");
			update_option('wpjobster_second_badge_active', "false");
		}

		if ($level2 == "1")                update_user_meta($uid, 'user_badge', "2");

		if ($level0 == "1"){
			update_user_meta($uid, 'user_badge', "0");
			update_option('wpjobster_second_badge_active', "false");
		}
	}
}

function wpjobster_user_eligible_for_first_badge($uid) {
	$enable_badges_sale = get_option('wpjobster_enable_badges_sale');

	$user_badge = get_user_meta($uid,'user_badge',true);

	if ($enable_badges_sale == 'yes'
		&& $user_badge == 0) {
		return true;
	}

	return false;
}

function wpjobster_user_eligible_for_second_badge($uid) {
	$enable_badges_sale = get_option('wpjobster_enable_badges_sale');

	$rating_for_badge = get_option('wpjobster_min_rating_badge_2');
	$rating_number_for_badge = get_option('wpjobster_min_rating_number_badge_2');

	$rating_user = wpjobster_get_avg_rating($uid);
	$rating_number_user = wpjobster_get_seller_ratings_number($uid);

	$user_badge = get_user_meta($uid,'user_badge',true);

	if ($enable_badges_sale == 'yes'
		&& $user_badge == 1
		&& $rating_user >= $rating_for_badge
		&& $rating_number_user >= $rating_number_for_badge) {
		return true;
	}

	return false;
}
