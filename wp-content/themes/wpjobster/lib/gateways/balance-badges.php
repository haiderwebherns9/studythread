<?php
if(!is_user_logged_in()) { wp_redirect( get_bloginfo('url')."/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit; }
global $wp_query;
$pid = $wp_query->query_vars['jobid'];

global $current_user;
$current_user = wp_get_current_user();
$uid = $current_user->ID;

$crds 	= wpjobster_get_credits($uid);

if (wpjobster_user_eligible_for_first_badge($uid)) {
	$total = get_option('wpjobster_first_badge_price');
} elseif (wpjobster_user_eligible_for_second_badge($uid)) {
	$total = get_option('wpjobster_second_badge_price');
}

if (!wpjobster_user_eligible_for_first_badge($uid)
	&& !wpjobster_user_eligible_for_second_badge($uid)) {
	_e("ERROR",'wpjobster');
	exit;
}


if($total > $crds) { echo 'NO_CREDITS_LEFT'; exit; }

get_header();

if(!is_demo_user()) {

	if (wpjobster_user_eligible_for_first_badge($uid)) {
		update_user_meta($uid, 'user_badge', "1");
	} elseif (wpjobster_user_eligible_for_second_badge($uid)) {
		update_user_meta($uid, 'user_badge', "2");
	}
	wpjobster_update_credits($uid , $crds - $total );

	wp_redirect(wpjobster_my_account_link());

}

get_footer(); ?>
