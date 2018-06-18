<?php

function wpj_my_account_vars() {

	$vars = array();

	global $wpdb;
	$prefix = $wpdb->prefix;
	do_action("wpjobster_check_user_role");

	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$last = get_user_meta( $uid, 'last_user_login', true );
	if(empty($last)) {
		add_action( 'wp_head' , 'wpjobster_needs_jquery' );
	}

	$ip_reg = get_user_meta( $uid, 'ip_reg', true );
	if (empty($ip_reg)) {
		$ip = $_SERVER['REMOTE_ADDR'];
		update_user_meta($uid, 'ip_reg', $ip);
		$ip_reg = $ip;
	}

	if(!is_demo_user()) {
		update_user_meta($uid,'last_user_login', current_time('timestamp', 1));
	}

	$bal = wpjobster_get_credits($uid);

	$vars = array(

		'uid' => $uid,
		'bal' => $bal

	);

	return $vars;

}

function wpj_load_more_my_account($pg) {

	$vars = array();
	$user = wpj_my_account_vars();
	$uid = $user['uid'];
	$post_type      = 'job';
	$function_name  = 'wpjobster_get_post_small_new';
	$posts_per_page = '12';
	$post_status    = array('draft','publish','pending');
	$order_by       = 'date';
	$order          = 'DESC';
	$author         = $uid;
	$meta_query     = array(
		'key'     => 'closed',
		'value'   => '0',
		'compare' => '='
	);
	$no_jobs_text = __('There are no jobs yet.','wpjobster');

	if($pg == "active"){
		$meta_query = array(
			'key'     => 'active',
			'value'   => "1",
			'compare' => '='
		);
		$post_status = array('publish');
		$no_jobs_text = __('No active jobs.','wpjobster');
	}else if($pg == "inactive"){
		$meta_query = array(
			'key'     => 'active',
			'value'   => "0",
			'compare' => '='
		);
		$post_status = array('draft','publish');
		$no_jobs_text = __('No inactive jobs.','wpjobster');
	}else if($pg == "under-review"){
		$meta_query = array(
			'key'     => 'under_review',
			'value'   => "1",
			'compare' => '='
		);
		$post_status = 'draft';
		$no_jobs_text = __('No pending review jobs.','wpjobster');
	}else if($pg == "rejected"){
		$post_status = 'pending';
		$meta_query = array();
		$no_jobs_text = __('No rejected jobs.','wpjobster');
	}

	$wpj_job = new WPJ_Load_More_Posts(
		array(
			'post_type'      => $post_type,
			'function_name'  => $function_name,
			'posts_per_page' => $posts_per_page,
			'post_status'    => $post_status,
			'order_by'       => $order_by,
			'order'          => $order,
			'author'         => $author,
			'meta_query'     => array($meta_query),
			'container_class' => 'my-account-job-listing'
		)
	);

	$vars = array(

		'pg' => $pg,
		'wpj_job' => $wpj_job,
		'no_jobs_text' => $no_jobs_text

	);

	return $vars;

}
