<?php
function wpjobster_add_history_log($tp, $reason, $amount, $uid, $uid2 = '', $oid = '', $rid = '', $details = '',$payed_amt='') {

	// $tp == 1     => (+) Money received
	// $tp == 0     => (-) Money paid
	// $tp == 2     => ( ) Money Pending

	// $rid == 1    => Payment received from Site Admin
	// $rid == 2    => Payment withdrawn by Site Admin
	// $rid == 3    => Payment made for:                   #ORDER_URL
	// $rid == 4    => Payment collected for:              #ORDER_URL
	// $rid == 5    => Payment cleared for:                #ORDER_URL
	// $rid == 6    => Fee charged for:                    #ORDER_URL
	// $rid == 7    => Payment refunded for:               #ORDER_URL
	// $rid == 8    => Payment refunded for:               #ORDER_URL
	// $rid == 9    => Withdrawal to                       #METHOD: #DETAILS
	// $rid == 10   => Feature job:                        $details = $pid
	// $rid == 11   => Payment for subscription            weekly-level1-new|change|renew
	// $rid == 12   => Top Up account balance
	// $rid == 13   => Processing fee for:                 #ORDER_URL
	// $rid == 14   => Tax for:                            #ORDER_URL
	// $rid == 15   => Payment received from Affiliate System
	// $rid == 16   => Processing fee for custom extra:    #ORDER_URL
	// $rid == 17   => Tax for custom extra:               #ORDER_URL
	// $rid == 18   => Custom extra:                       #ORDER_URL
	// $rid == 19   => Payment collected for custom extra: #ORDER_URL

	$tm = current_time('timestamp', 1);
	global $wpdb;
	global $wpjobster_currencies_array;

	if (isset($_POST['mc_currency'])) {
		$currency = $_POST['mc_currency'];
	} else {
		$currency = wpjobster_get_currency();
	}

	if ( $payed_amt != '' ) {
		// prioritize provided amount/currency
		$payedamount = $payed_amt;
	} elseif ( $oid > 0 ) {
		// calculate by order currency if exists

		// better do not provide $payed_amt for refunds
		// or where you need original order amount/currency

		$s = "select payedamount, mc_gross from " . $wpdb->prefix . "job_orders where id='$oid'";
		$r = $wpdb->get_results($s);
		$r = $r[0];

		// calculate by original order currency and exch rate
		$pipeseparatedprice = $r->payedamount;

		$pricearray  = explode('|', $pipeseparatedprice);
		$currency    = $pricearray[0];
		$payedamount = $amount * $pricearray[1] / $r->mc_gross;
		$payedamount = $currency . '|' . wpjobster_formats_special($payedamount, 2);

	} else {
		// default currency fallback
		$payedamount = $wpjobster_currencies_array[0] . '|' . wpjobster_formats_special($amount, 2);

	}

	$reason = esc_sql($reason);

	$details = esc_sql($details);

	$s = "insert into " . $wpdb->prefix . "job_payment_transactions (tp, reason, amount, uid, datemade, uid2, payedamount, oid, rid, details)
	values('$tp','$reason','$amount','$uid','$tm','$uid2','$payedamount', '$oid', '$rid', '$details')";
	$wpdb->query($s);
	update_user_credits_balance($wpdb->insert_id,$uid);
}
