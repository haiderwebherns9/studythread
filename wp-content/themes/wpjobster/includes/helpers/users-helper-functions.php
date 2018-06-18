<?php
function user($id="",$k="ID",$v=array()){

	if(!$id){$id=get_current_user_id();}
	$u=array("Full texts","ID","user_login","user_pass","user_nicename","user_email","user_url","user_registered","user_activation_key","user_status","display_name");
	if(!in_array($k,$u)){
		if(isset($v) && is_array($v)){
			return get_user_meta($id,$k,1);
		}else{
			update_user_meta($id,$k,$v);
		}
	}else{
		if(isset($v) &&is_array($v)){
			$user_info = get_userdata($id);
			return isset($user_info)&&  is_object($user_info)?$user_info->$k:"";
		}else{
			wp_update_user(array("ID"=>$id,$k=>$v));
		}
	}
}

function wpj_get_user_profile_link( $user = false ) {

	$user_profile_page_id = get_option( 'wpjobster_user_profile_page_id' );

	if ( $user === false ) {
		return get_permalink( $user_profile_page_id );
	} elseif ( is_object( $user ) ) {
		$userdata = $user;
		$user_login = $userdata->user_login;
	} elseif ( is_int( $user ) ) {
		$userdata = get_userdata( $user );
		$user_login = $userdata->user_login;
	} elseif ( is_string( $user ) ) {
		$user_login = $user;
	} else {
		return false;
	}

	return get_permalink( $user_profile_page_id ) . $user_login . '/';
}

// deprecated
if ( ! function_exists( 'wpjobster_get_user_profile_link' ) ) {
	function wpjobster_get_user_profile_link( $username ) {
		return wpj_get_user_profile_link( $username );
	}
}
