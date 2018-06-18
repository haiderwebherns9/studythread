<?php
add_action( 'wp_ajax_nopriv_wpj_ajax_delete_job', 'wpj_ajax_delete_job' );
add_action( 'wp_ajax_wpj_ajax_delete_job', 'wpj_ajax_delete_job' );
function wpj_ajax_delete_job() {
	if ( is_user_logged_in() ) {


		$pid = $_POST['var_post_id'];
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;

		$post = get_post($pid);

		if ($post->post_author == $current_user->ID) {

			wp_trash_post( $post->ID );

			echo 'success';

		} else {
			echo 'not_your_post';
		}


	}else{
		echo 'not_logged_in';
	}

	wp_die();
}

add_action( 'init', 'wpj_delete_job' );
function wpj_delete_job(){
	if (isset($_GET['_ad_delete_pid'])) {

		if (is_user_logged_in() && !is_demo_admin()) {
			$pid = $_GET['_ad_delete_pid'];
			$pstpst = get_post($pid);
			global $current_user;
			$current_user = wp_get_current_user();
			if ($pstpst->post_author == $current_user->ID || user_can($current_user, "manage_options")) {
				wp_delete_post($_GET['_ad_delete_pid']);

				if(isset($_GET['cover_parent']) && $_GET['cover_parent']!=''){
					delete_post_meta($_GET['cover_parent'],"cover-image");
				}

				if ( isset( $_GET['jobid'] ) ) {
					$jobid = $_GET['jobid'];
					$job_any_attachments = get_post_meta($jobid, 'job_any_attachments', true);
					$attachments = explode(",", $job_any_attachments);
					$new_arr = array_diff($attachments, array($pid));
					if(isset($new_arr) && $new_arr != ""){
						$new_val = implode(",",$new_arr);
					}else{
						$new_val = "";
					}
					update_post_meta($jobid, 'job_any_attachments', $new_val);

					$job_prev_attachments = get_post_meta($jobid, 'preview_job_attchments', true);
					$attachments_prev = explode(",", $job_prev_attachments);
					$new_arr_prev = array_diff($attachments_prev, array($pid));
					if(isset($new_arr_prev) && $new_arr_prev != ""){
						$new_val_prev = implode(",",$new_arr_prev);
					}else{
						$new_val_prev = "";
					}
					update_post_meta($jobid, 'preview_job_attchments', $new_val_prev);
				}

				echo "done";
			}

		}

		exit;
	}
}
