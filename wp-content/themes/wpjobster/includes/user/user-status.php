<?php
function wpj_user_status_vars( $u_id='' ){

	$vars = array();

	if( ! isset( $u_id ) ){
		$current_user = wp_get_current_user();
		$u_id = $current_user->ID;
	}

	global $current_user;
	$current_user = wp_get_current_user();
	$username_curent = $current_user->user_login;
	$username_url = isset( $wp_query->query_vars['username']) ? urldecode($wp_query->query_vars['username']):'';

	if ( $username_url ) {
		$username = $username_url;
		$uid = get_user_by( 'login', $username );
		$u_id = $uid->ID;
	} else {
		$username = $username_curent;
	}

	$wpjobster_en_user_online_status = get_option('wpjobster_en_user_online_status');
	if( $wpjobster_en_user_online_status == 'yes_with_text' || $wpjobster_en_user_online_status == 'yes_with_icon' ) {

		$current_time = time();
		$last_login_time = get_user_meta($u_id, 'last_user_login', true);

		$time_difference = ($current_time - $last_login_time) / 60;
		$mins = (int)$time_difference;
		$random_no = wp_rand (1,9999);
	}else{
		$random_no = 0;
		$mins = 0;
	}

	$vars = array(
		'u_id' => $u_id,
		'random_no' => $random_no,
		'mins' => $mins,
		'wpjobster_en_user_online_status' => $wpjobster_en_user_online_status
	);

	return $vars;
}

add_action( 'wp_ajax_update_user_meta_for_user_online_status_action', 'update_user_meta_for_user_online_status' );
add_action('init','update_user_meta_for_user_online_status');
function update_user_meta_for_user_online_status() {
	global $current_user;
	$current_user = wp_get_current_user();
	$login_uid = $current_user->ID;
	$current_time = time();
	update_user_meta ($login_uid,'last_user_login',$current_time);
}

add_action( 'wp_footer', 'user_online_offline_status_script' );
function user_online_offline_status_script() { ?>
	<script type="text/javascript" >
		jQuery(document).ready(function($) {

			var lmt = new Date();
			last_move_time = lmt.getTime();

			$(document).mousemove(function(){
				var cmt = new Date();
				current_move_time = cmt.getTime();

				if( (current_move_time - last_move_time) > 60000) {
					$.ajax({
						method: "POST",
						url: ajaxurl,
						data: { 'action': 'update_user_meta_for_user_online_status_action' },
						success: function (data) {
							//alert("ajax called");
						}
					});
					last_move_time = cmt.getTime();
				}
			});
		});
	</script>
<?php }

add_action('init', 'wpj_set_last_visit');
function wpj_set_last_visit() {
	$user = wp_get_current_user();
	update_user_meta( $user->ID, 'last_login', current_time('mysql') );
}

function wpj_get_last_visit( $user_id ) {
	$last_login = get_user_meta( $user_id, 'last_login', true );
	$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

	if ( wp_is_mobile() ) {
		$the_last_visit = date( "M j, y, g:i a", strtotime( $last_login ) );
	} else {
		$the_last_visit = mysql2date( $date_format, $last_login, false );
	}
	return $the_last_visit;
}
