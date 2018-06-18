<?php
function wpjobster_get_total_nr_of_listings(){
	$query = new WP_Query("post_type=job&order=DESC&orderby=id&posts_per_page=-1&paged=1");
	return $query->post_count;
}


function wpjobster_get_total_nr_of_open_listings(){
	$query = new WP_Query("meta_key=closed&meta_value=0&post_type=job&order=DESC&orderby=id&posts_per_page=-1&paged=1");
	return $query->post_count;
}


function wpjobster_get_total_nr_of_closed_listings(){
	$query = new WP_Query("meta_key=closed&meta_value=1&post_type=job&order=DESC&orderby=id&posts_per_page=-1&paged=1");
	return $query->post_count;
}

function wpjobster_nr_active_jobs($uid){
	$meta = array(
		'key'     => 'active',
		'value'   => "1",
		'compare' => '='
	);
	$args = array(
		'posts_per_page' => '-1',
		'post_type'      => 'job',
		'author'         => $uid,
		'meta_query'     => array($meta)
	);
	$q = new WP_Query($args);
	return $q->post_count;
}

function wpjobster_nr_inactive_jobs($uid){
	$meta = array(
		'key'     => 'active',
		'value'   => "0",
		'compare' => '='
	);
	$args = array(
		'posts_per_page' => '-1',
		'post_status'    => array('draft','publish'),
		'post_type'      => 'job',
		'author'         => $uid,
		'meta_query'     => array($meta)
	);
	$q = new WP_Query($args);
	return $q->post_count;
}

function wpjobster_nr_in_review_jobs($uid){
	$meta = array(
		'key'     => 'under_review',
		'value'   => "1",
		'compare' => '='
	);
	$args = array(
		'posts_per_page' => '-1',
		'post_status'    => 'draft',
		'post_type'      => 'job',
		'author'         => $uid,
		'meta_query'     => array($meta)
	);
	$q = new WP_Query($args);
	return $q->post_count;
}

function wpjobster_nr_rejected_jobs($uid){

	$args = array(
		'posts_per_page' => '-1',
		'post_status'    => 'pending',
		'post_type'      => 'job',
		'author'         => $uid
	);
	$q = new WP_Query($args);
	return $q->post_count;
}

function wpjobster_get_number_of_active_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select distinct orders.id from " . $prefix . "job_orders orders, " . $prefix . "posts posts
		 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='0' AND
		 orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0'  and payment_status!='pending' order by orders.id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_get_number_of_pending_pmt_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;

	$s = "select distinct orders.id from " . $prefix . "job_orders orders, " . $prefix . "posts posts
		 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='0' AND
		 orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' and (payment_status='pending' or payment_status='processing') order by orders.id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_get_number_of_cencelled_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select distinct * from " . $prefix . "job_orders orders, " . $prefix . "posts posts
		 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.closed='1' order by orders.id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_get_number_of_completed_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select distinct * from " . $prefix . "job_orders orders, " . $prefix . "posts posts
		 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND
		 orders.done_buyer='1' AND orders.closed='0' order by orders.id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_get_number_of_delivered_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select distinct orders.id from " . $prefix . "job_orders orders, " . $prefix . "posts posts
		 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND
		 orders.done_buyer='0' AND orders.closed='0' order by orders.id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_orders_in_queue($uid){
	global $wpdb;
	$pref = $wpdb->prefix;
	$s = "select posts.ID from " . $pref . "posts posts, " . $pref . "job_orders orders where posts.post_author='$uid'
	AND posts.ID=orders.pid AND orders.date_finished='0' AND request_cancellation='0' AND force_cancellation='0' AND accept_cancellation_request='0' AND payment_response!=''";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_job_orders_in_queue($pid){
	global $wpdb;
	$pref = $wpdb->prefix;
	$s = "select posts.ID from " . $pref . "posts posts, " . $pref . "job_orders orders where posts.ID='$pid'
	AND posts.ID=orders.pid AND orders.date_finished='0' AND request_cancellation='0' AND force_cancellation='0' AND accept_cancellation_request='0' AND payment_response!=''";
	$r = $wpdb->get_results($s);
	return count($r);
}
