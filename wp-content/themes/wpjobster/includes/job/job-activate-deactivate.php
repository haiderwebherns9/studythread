<?php
// ACTIVATE ALL USER JOBS
function activate_all_user_jobs( $user_id, $reason = '' ){
	$args = array(
		'post_type'      => 'job',
		'posts_per_page' => -1,
		'post_status'    => array('draft','publish','pending'),
		'author'         => $user_id,
		'meta_query'     => array(
			array(
				'key'     => 'active',
				'value'   => "0",
				'compare' => '=',
			),
		),
	);

	$posts = get_posts( $args );

	foreach ($posts as $key) {
		update_post_meta( $key->ID, 'active', '1' );
		if ( $reason != '' ) {
			update_post_meta( $key->ID, 'activate_reason', $reason );
		}
	}
}

// DEACTIVATE ALL USER JOBS
function deactivate_all_user_jobs( $user_id, $reason = '' ){
	$args = array(
		'post_type'      => 'job',
		'posts_per_page' => -1,
		'post_status'    => array('draft','publish','pending'),
		'author'         => $user_id,
		'meta_query'     => array(
			array(
				'key'     => 'active',
				'value'   => "1",
				'compare' => '=',
			),
		),
	);

	$posts = get_posts( $args );

	foreach ($posts as $key) {
		update_post_meta( $key->ID, 'active', '0' );
		if ( $reason != '' ) {
			update_post_meta( $key->ID, 'deactivation_reason', $reason );
		}
	}
}

add_action( 'wp_ajax_nopriv_wpj_ajax_deactivate_job', 'wpj_ajax_deactivate_job' );
add_action( 'wp_ajax_wpj_ajax_deactivate_job', 'wpj_ajax_deactivate_job' );
function wpj_ajax_deactivate_job() {

	if ( is_user_logged_in() ) {

		$pid = $_POST['var_post_id'];
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;
		$post = get_post($pid);

		if ($post->post_author == $current_user->ID) {

			update_post_meta($pid, 'active', 0);

			echo 'success';

		} else {

			echo 'not_your_post';
		}

	} else {
		echo 'not_logged_in';
	}

	wp_die();
}


add_action( 'wp_ajax_nopriv_wpj_ajax_activate_job', 'wpj_ajax_activate_job' );
add_action( 'wp_ajax_wpj_ajax_activate_job', 'wpj_ajax_activate_job' );
function wpj_ajax_activate_job() {

	if ( is_user_logged_in() ) {

		$pid = $_POST['var_post_id'];
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;
		$post = get_post($pid);

		if ($post->post_author == $current_user->ID) {

			update_post_meta($pid, 'active', 1);
			delete_post_meta( $pid, 'deactivation_reason' );

			echo 'success';

		} else {

			echo 'not_your_post';
		}

	} else {
		echo 'not_logged_in';
	}


	wp_die();

}

function wpjobster_deactivate_all_jobs($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select distinct * from " . $prefix . "posts where post_author=".$uid." order by ID desc";
	$r = $wpdb->get_results($s);

	if (count($r) > 0) {
		foreach ($r as $row) {
			$pid = $row->ID;
			if (get_post_meta($pid, "active", true)) {
				update_post_meta($pid, "active", '0');
			}

		}
	}
}
