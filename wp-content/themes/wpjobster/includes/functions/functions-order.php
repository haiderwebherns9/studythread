<?php
function wpjobster_get_current_order_by_thing(){

	if (empty($_SESSION['current_order']) == "auto")        return "auto"; else        return $_SESSION['current_order'];
}

if (!function_exists('wpjobster_camouflage_order_id')) {
	function wpjobster_camouflage_order_id( $id, $date = '' ) {
		if ( ! $date ) {
			$order = wpjobster_get_order( $id );
			$date = $order->date_made;
		}
		$return='I';
		$return=$return.date("j", $date);
		$return=$return.date("n", $date);
		$return=$return.'D';
		$half=round(strlen($id)/2);
		$end=strlen($id);
		$ids=str_split($id);
		for($i=0;$i<$half;$i++)$return=$return.$ids[$i];
		$return=$return.date("y", $date);
		for($i=$half;$i<$end;$i++)$return=$return.$ids[$i];
		return $return;
	}
}

function wpjobster_get_order_details_by_orderid($oid){
	global $wpdb;
	$s = "SELECT * from ".$wpdb->prefix."job_orders WHERE id='".$oid."'";
	$r = $wpdb->get_results($s);
	$r = $r[0];
	return $r;
}

function wpjobster_get_order_row_obj($oid){
	global $wpdb;
	$s = "select distinct * from " . $wpdb->prefix . "job_orders where id='$oid'";
	$r = $wpdb->get_results($s);
	return $r[0];
}

function get_pending_clearance($uid) {
	global $wpdb, $prefix;
	$prefix = $wpdb->prefix;
	$get_pending_clearance_query = "select SUM(mc_gross) as pending_clearance FROM {$prefix}job_orders orders, {$prefix}posts posts
		 where posts.post_author={$uid} AND posts.ID=orders.pid AND orders.done_seller='1' AND orders.done_buyer='1' AND orders.completed='1' AND orders.closed='0' AND orders.clearing_period='2'";
	$g_pending_clearance = $wpdb->get_results($get_pending_clearance_query , OBJECT);

	if ($g_pending_clearance[0]->pending_clearance > 0) {
		return wpjobster_get_show_price($g_pending_clearance[0]->pending_clearance);
	} else {
		return "0";
	}
}

function get_pending_clearance_buyer($uid) {
	global $wpdb, $prefix;
	$prefix = $wpdb->prefix;
	$get_pending_clearance_query = "select SUM(mc_gross) as pending_clearance FROM {$prefix}job_orders orders
		 where orders.uid={$uid} AND orders.done_seller='0' AND orders.done_buyer='0' AND orders.closed='0'";
	$g_pending_clearance = $wpdb->get_results($get_pending_clearance_query , OBJECT);



	if ($g_pending_clearance[0]->pending_clearance > 0) {
		return wpjobster_get_show_price($g_pending_clearance[0]->pending_clearance);
	} else {
		return "0";
	}
}

if(!function_exists('gettrasaction_amt')){
	function gettrasaction_amt($fromtimestamp,$totimestamp,$uid,$action='earning'){
		global $wpdb;
		$prefix = $wpdb->prefix;
		if($action=='earning'){
				$s = "select SUM(sum_amount) as sum_amount from
				(
					select SUM(SUBSTRING_INDEX(final_paidamount, '|', -1)) as sum_amount from {$wpdb->prefix}job_orders WHERE uid = {$uid} AND done_seller = '0' AND done_buyer = '0' AND date_finished = '0' AND closed = '0' AND payment_status != 'pending' AND date_made>={$fromtimestamp} AND date_made<{$totimestamp}
					UNION ALL
					select SUM(SUBSTRING_INDEX(final_paidamount, '|', -1)) as sum_amount from {$wpdb->prefix}job_orders WHERE uid = {$uid} AND completed = '1' AND date_made>={$fromtimestamp} AND date_made<{$totimestamp}
				)sum_amount";
		}elseif($action=='active'){
			$s = "select SUM(mc_gross) as sum_amount,from_unixtime({$fromtimestamp}) as fromdt,from_unixtime({$totimestamp})  FROM {$wpdb->prefix}job_orders orders where orders.uid={$uid} AND orders.done_seller='0' AND orders.done_buyer='0' AND orders.closed='0'"
			. " and date_made>={$fromtimestamp} and date_made<{$totimestamp}";
		}elseif($action=='completed'){
					$s = "select SUM(mc_gross)as sum_amount ,from_unixtime({$fromtimestamp}) as fromdt,from_unixtime({$totimestamp}) as todt FROM {$wpdb->prefix}job_orders orders
		 where orders.uid={$uid} AND orders.completed='1' AND orders.done_buyer='1' AND orders.done_seller='1' AND orders.closed='0'"
			. " and date_made>={$fromtimestamp} and date_made<{$totimestamp}";
		}elseif($action=='credit_balance'){
					$s = "select credit_balance as sum_amount ,from_unixtime({$fromtimestamp}) as fromdt,from_unixtime({$totimestamp}) as todt FROM {$wpdb->prefix}job_credits_balance_log logs
		 where logs.uid={$uid}  and datemade>={$fromtimestamp} and datemade<{$totimestamp} order by id desc limit 1 ";

		}elseif($action=='withdraw'){
					$s = "select SUM(amount) as sum_amount,from_unixtime({$fromtimestamp}) as fromdt,from_unixtime({$totimestamp}) as todt  FROM {$wpdb->prefix}job_withdraw withdrawals where withdrawals.uid={$uid} AND withdrawals.done='1' and datemade>={$fromtimestamp} and datemade<{$totimestamp} order by id desc limit 1 ";

		}elseif($action=='pending_clearance'){
			$s = "select SUM(mc_gross) as sum_amount,from_unixtime({$fromtimestamp}) as fromdt,from_unixtime({$totimestamp}) as todt   FROM {$prefix}job_orders orders where orders.uid={$uid} AND orders.done_seller='0' AND orders.done_buyer='0' AND orders.closed='0'and date_made>={$fromtimestamp} and date_made<{$totimestamp} order by id ";
		}
		$result_data = $wpdb->get_results($s);
		$last_balance = 0;
		if($action=='credit_balance'){
			foreach($result_data as $key=>$result_row){
                                if($result_row->sum_amount >0){
					$last_balance=$result_row->sum_amount ;
				}else{
					$result_data[$key]->sum_amount=$last_balance ;
				}
			}
		}
		if($result_data)
			return $result_data[0] ;
		else return array(0);
	}
}

add_action( 'wp_ajax_nopriv_process_pending_order', 'process_pending_order' );
add_action( 'wp_ajax_process_pending_order', 'process_pending_order' );
function process_pending_order(){
	$order_id = $_REQUEST['order_id'];
	$process = $_REQUEST['process'];

	global $wpdb;

	// some basic security checks
	global $current_user;
	$current_user = wp_get_current_user();
	$order = wpjobster_get_order( $order_id );

	if ( $process == 'cancel'
		&& $order->uid == $current_user->ID
		&& $order->payment_status == 'pending'
	) {
		$tm = current_time('timestamp', 1);
		$ccc = '';
		$g1 = " insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-21','$order_id','$ccc')"; // -21 means the buyer cancelled the pending order
		$wpdb->query($g1);
		wpj_update_user_notifications( wpj_get_seller_id( $order ), 'notifications', +1 );

		$g1 = " insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-22','$order_id','$ccc')"; // -21 means the buyer cancelled the pending order
		$wpdb->query($g1);
		wpj_update_user_notifications( wpj_get_buyer_id( $order ), 'notifications', +1 );

		$sql =  " update ".$wpdb->prefix."job_orders set payment_status='cancelled', closed='1' , date_closed='$tm' where id= $order_id ";
		$wpdb->query($sql);

		do_action( 'wpj_after_order_is_cancelled', $order_id, 'job_purchase' );
	}
}
