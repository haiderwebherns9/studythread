<?php
if(!is_user_logged_in()) { wp_redirect( get_bloginfo('url')."/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit; }
global $wp_query;
$pid = $wp_query->query_vars['jobid'];

global $current_user;
$current_user = wp_get_current_user();
$uid = $current_user->ID;

$post	= get_post($pid);
$crds	= wpjobster_get_credits($uid);

$feature_pages = $_GET['feature_pages'];
$total=0;
if(strpos($feature_pages,'h') !== false)
	$total+=get_option('wpjobster_featured_price_homepage');
if(strpos($feature_pages,'c') !== false)
	$total+=get_option('wpjobster_featured_price_category');
if(strpos($feature_pages,'s') !== false)
	$total+=get_option('wpjobster_featured_price_subcategory');

		include 'wpjobster_common_featured.php';
		$wcf = new WPJ_Common_Featured();
		$featured_order= array('feature_pages'=>$wcf->_feature_pages,'job_id'=>$wcf->_job_id,'user_id'=>$wcf->_current_user->ID,
			'featured_amount'=>$wcf->_featured_amount,'paid'=>0,'payment_gateway_name'=>'credits',
			'h_date_start'=>$wcf->_h_date_start,'c_date_start'=>$wcf->_c_date_start,'s_date_start'=>$wcf->_s_date_start
				,'tax'=>$wcf->_tax,'payable_amount'=>$wcf->_payable_amount,'currency'=>$wcf->_currency
				);
		$order_id = $featured_order_id = $wcf->insert_featured_order($featured_order);
		$price = $total=$wcf->_payable_amount;

if($total > $crds) { echo 'NO_CREDITS_LEFT'; exit; }

get_header();

if(!is_demo_user()) {
	wpjobster_update_credits($uid , $crds - $total );
					global $wpdb;
				$tm = time();
					$sql = " update ".$wpdb->prefix."job_featured_orders set  paid='1', paid_on='{$tm}',"
				. " payment_gateway_transaction_id='paid-with-credits',"
				. "payment_response='paid-with-credits' where id='$order_id'";

	$update_result = $wpdb->query($sql);

	if(strpos($feature_pages,'h') !== false){
		update_featured_after_pay('homepage', $pid);
	}
	if(strpos($feature_pages,'c') !== false){
		update_featured_after_pay('category', $pid);
	}
	if(strpos($feature_pages,'s') !== false){
		update_featured_after_pay('subcategory', $pid);
	}
	$payed_amt = $_COOKIE["site_currency"]."|".wpjobster_formats($total);
	notify_after_featured_pay($pid, $uid, $total, $payed_amt);
	wp_redirect(wpjobster_my_account_link());

}

get_footer(); ?>
