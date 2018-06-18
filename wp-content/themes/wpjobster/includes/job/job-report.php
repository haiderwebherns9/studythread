<?php
if(!function_exists('wpjobster_report_submit')){
	function wpjobster_report_submit($user) {
		global $current_user;
		$job_id = $_POST['job_id'];
		$post = get_post($job_id);

		$user_id = $current_user->ID;
		$user_arr = get_post_meta( $job_id, "reported_by" );
		if($user_id==$post->post_author){
			$report_arr['msg'] =  __( "You can not report your own job", "wpjobster" );
			$report_arr['err'] = 1;
		}elseif(!in_array($user_id,$user_arr)){
			update_post_meta( $job_id,"reported_by",$user_id );
			wpjobster_send_email_allinone_translated( 'report_job_user', $user_id, false, $job_id );
			wpjobster_send_email_allinone_translated( 'report_job_admin', 'admin', $user_id, $job_id );
			$report_arr['msg'] =  __( "Your message is sent to admin", "wpjobster" );
			$report_arr['err'] = 0;
		}else{
			$report_arr['err'] = 1;
			$report_arr['msg'] = __( "You already reported this job", "wpjobster" );
		}
		echo json_encode( $report_arr );
		die();
	}
}
add_action( 'wp_ajax_report_submit', 'wpjobster_report_submit' );
add_action( 'wp_ajax_nopriv_report_submit', 'wpjobster_report_submit' );
