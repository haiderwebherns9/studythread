<?php
global $wp_query;
$pid = $wp_query->query_vars['jobid'];
$currency = wpjobster_get_currency();

$price = get_post_meta( $pid, 'price', true );
$shipping = get_post_meta( $pid, 'shipping', true );
$extras = explode( '|', $_GET['extras'] );
if ( count( $extras ) <= 1 ) {
	$extras = explode( '_', $_GET['extras'] );
}
$extras_no = 0;
if ( count( $extras ) ) {
	foreach ( $extras as $myitem ) {
		if ( ! empty( $myitem ) ) {
			$extras_no++;
		}
	}
}

if ( is_numeric( $price ) && $price == 0
	&& empty( $shipping )
	&& $extras_no == 0 ) {

	$common_details = get_common_details('get_for_free',0,$currency);

	if($common_details){
		extract($common_details);
	}

	$uid                            = $common_details['uid'];
	$wpjobster_final_payable_amount = 0;
	$currency                       = $common_details['currency'];
	$order_id                       = $common_details['order_id'];

	$payment_response = '';
	$payment_details  = '';
	$processing_fees  = 0;
	$tax_amount       = 0;

	if( !empty( $order_id ) ) {
		$payment_status = 'completed';

		global $wpdb;

		$s = "update " . $wpdb->prefix . "job_orders set processing_fees='$processing_fees', tax_amount='$tax_amount', final_paidamount='$currency|0'  where id='$order_id' limit 1 ";
		$wpdb->query($s);

		$datemade = time();

		$g1 = "insert into " . $wpdb->prefix . "job_chatbox (datemade, uid, oid, content) values('$datemade','0','$order_id','')";
		$wpdb->query($g1);

		wpj_update_user_notifications( wpj_get_seller_id( $order_id ), 'notifications', +1 );

		wpjobster_mark_job_prchase_completed( $order_id, $payment_status, $payment_response, $payment_details );

		// this runs for free jobs
		do_action( 'wpjobster_job_payment_completed', $order_id );
		wp_redirect( get_bloginfo( 'url' ) . '/?jb_action=chat_box&oid=' . $order_id );

		exit;

	}

} else {
	echo exit('err_job_not_free');
}
?>
