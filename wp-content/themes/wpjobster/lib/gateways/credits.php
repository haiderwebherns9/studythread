<?php
if(!is_user_logged_in()) { wp_redirect( get_bloginfo('url')."/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit; }

global $wp_query;
$pid = $wp_query->query_vars['jobid'];

global $current_user;
$current_user = wp_get_current_user();
$uid = $current_user->ID;

// currency
$currency = wpjobster_get_currency();
$selected = $currency;

$post   = get_post($pid);
$crds   = wpjobster_get_credits($uid);

$tm = time();

$amount = $_GET['amount'];

$extr_ttl = 0; $xtra_stuff = ''; $xtra_stuff_amounts = '';
$extras = $_GET['extras'];
$extras = explode("|", $extras);
$extras_amounts = $_GET['extras_amounts'];
$extras_amounts = explode("|", $extras_amounts);
if(count($extras) && count($extras_amounts)) {
	$i=0;
	foreach($extras as $myitem) {
		if(!empty($myitem)) {
			$extra_price  = get_post_meta($pid, 'extra'.$myitem.'_price', true);
			$extr_ttl += $extra_price*$extras_amounts[$i];
			$xtra_stuff .= '|'. $myitem;
			$xtra_stuff_amounts .= '|'. $extras_amounts[$i];
		}
		$i++;
	}
}

$cust = $pid.'|'.$uid.'|'.$tm.'|'.$amount.'|'.(count($extras)-1).$xtra_stuff.$xtra_stuff_amounts;

//---------------------------------

if(!isset($_SESSION)) {
	session_start();
}
if ($_SESSION['confirmationpagevisited'] == $pid) {

	if (!is_demo_user()) {

		$common_details = get_common_details('credits');
		if ( $common_details ) {
			extract( $common_details );
		}

		$orderid = $order_id;

		if ($orderid) {
			wp_redirect(get_bloginfo('url').'/?jb_action=chat_box&oid='.$orderid);

			exit;
		} else {
			echo __('Error while inserting the order. Please contact the site administrator.', 'wpjobster');
		}

	} else {

		global $wpdb;
		$pref = $wpdb->prefix;

		$s = "select * from ".$pref."job_orders where uid='$uid' order by id desc";

		$r = $wpdb->get_results($s);
		$last_row = $r[0];
		$last_row_id = $last_row->id;

		wp_redirect(get_bloginfo('url').'/?jb_action=chat_box&oid='.$last_row_id);

	}

	$_SESSION['confirmationpagevisited'] = '';

} else {

	$_SESSION['confirmationpagevisited'] == '';
	wp_redirect(get_permalink($pid)); exit;
}
?>
