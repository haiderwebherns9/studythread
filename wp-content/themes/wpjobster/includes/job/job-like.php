<?php
function wpjobster_get_likes_nr($pid){
	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "job_likes where pid='$pid'";
	$r = $wpdb->get_results($s);
	return count($r);
}

add_action( 'init', 'wpj_unlike_job' );
function wpj_unlike_job(){
	global $wpdb;

	if (isset($_POST['unlike_this_job'])) {

		if (!is_user_logged_in())        exit;
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$tm = time();
		$pid = $_POST['unlike_this_job'];
		$likes = get_post_meta($pid, 'likes', true);

		if (empty($likes))        $likes = 0; else        $likes = $likes - 1;
		update_post_meta($pid, 'likes', $likes);
		global $wpdb;
		$s = "delete from " . $wpdb->prefix . "job_likes where pid='$pid' AND uid='$uid'";
		$wpdb->query($s);
		exit;
	}
}

add_action( 'init', 'wpj_like_job' );
function wpj_like_job(){
	if (isset($_POST['like_this_job'])) {

		if (!is_user_logged_in())        exit;
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$tm = time();
		$pid = $_POST['like_this_job'];
		$likes = get_post_meta($pid, 'likes', true);

		if (empty($likes))        $likes = 1; else        $likes = $likes + 1;
		update_post_meta($pid, 'likes', $likes);
		global $wpdb;
		$s = "insert into " . $wpdb->prefix . "job_likes (pid,uid,date_made) values('$pid','$uid','$tm')";
		$wpdb->query($s);
		exit;
	}
}
