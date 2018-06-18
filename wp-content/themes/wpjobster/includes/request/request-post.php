<?php
if(is_admin()){
	add_action('publish_request', 'wpjobster_publish_request_byadmin', 10, 2);
	add_action('draft_request', 'wpjobster_draft_request_byadmin', 10, 2);
	add_action('pending_request', 'wpjobster_pending_request_byadmin', 10, 2);

	if (!function_exists('wpjobster_publish_request_byadmin')) {
		function wpjobster_publish_request_byadmin($ID, $post) {
			$pid = $ID;
			wpjobster_send_email_allinone_translated('request_acc', false, false, $pid);
			wpjobster_send_sms_allinone_translated('request_acc', false, false, $pid);
			update_post_meta($pid, 'under_review', '0');
			$wpjobster_admin_approve_request = get_option("wpjobster_admin_approve_request");
			if ( $wpjobster_admin_approve_request == 'yes' ) {
				$request_term = wp_get_post_terms($pid, 'request_cat', array("fields" => "all"));
				$request_term_name = $request_term[0]->name;
				$job_categories = get_term_by('name', $request_term_name, 'job_cat');
				$job_cat_term_id = $job_categories->term_id;
				do_action( 'wpjobster_after_request_inserted', $pid, $job_cat_term_id );
			}
		}
	}
	if (!function_exists('wpjobster_pending_request_byadmin')) {
		function wpjobster_pending_request_byadmin($ID, $post) {
			$pid = $ID;
			wpjobster_send_email_allinone_translated('request_decl', false, false, $pid);
			wpjobster_send_sms_allinone_translated('request_decl', false, false, $pid);
			update_post_meta($pid, 'under_review', '1');
		}
	}

	if (!function_exists('wpjobster_draft_request_byadmin')) {
		function wpjobster_draft_request_byadmin($ID, $post)    {
			$pid = $ID;
			update_post_meta($pid, 'under_review', '1');
		}
	}
}

add_action( 'wp_ajax_nopriv_wpj_ajax_request_delete', 'wpj_ajax_request_delete' );
add_action( 'wp_ajax_wpj_ajax_request_delete', 'wpj_ajax_request_delete' );
function wpj_ajax_request_delete() {
	if ( is_user_logged_in() ) {
		$rid = $_POST['var_post_id'];
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;
		$post = get_post( $rid );

		if ( $post->post_author == $current_user->ID ) {

			wp_trash_post( $post->ID );

			echo 'success';

		} else {

			echo 'not_your_post';

		}
	} else {
		echo 'not_logged_in';
	}
	wp_die();
}

add_filter('request', 'wpjobster__myfeed_request');
function wpjobster__myfeed_request($qv){

	if (isset($qv['feed']))        $qv['post_type'] = get_post_types(array(            'name' => 'job'        ));
	return $qv;
}
