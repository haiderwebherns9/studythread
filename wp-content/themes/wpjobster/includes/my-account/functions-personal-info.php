<?php

function wpj_personal_info_vars() {

	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	global $wpdb,$wp_rewrite,$wp_query;
	$wpjobster_characters_personalinfo_max = get_option("wpjobster_characters_personalinfo_max");
	$wpjobster_characters_personalinfo_max = (empty($wpjobster_characters_personalinfo_max)|| $wpjobster_characters_personalinfo_max==0)?500:$wpjobster_characters_personalinfo_max;
	$wpjobster_characters_personalinfo_min = get_option("wpjobster_characters_personalinfo_min");
	$wpjobster_characters_personalinfo_min = (empty($wpjobster_characters_personalinfo_min)|| $wpjobster_characters_personalinfo_min==0)?0:$wpjobster_characters_personalinfo_min;

	if(isset($_SESSION['ERROR_ON_ACCOUNT'])&&$_SESSION['ERROR_ON_ACCOUNT']==1){
		echo '<div class="errrs">'.__("You can't proceed to a different page until you fill out the required information","wpjobster").'</div>';
		$_SESSION['ERROR_ON_ACCOUNT']=0;
	}
	if(isset($_POST['save-info'])){
		$ok = 1;
		$post_new_error = '';
           if ( isset( $_POST['country_code'] ) ) {
				if (user($uid, '	country_id') != $_POST['country_code']) {
					update_user_meta($uid, 'country_id',$_POST['country_code']);
				}
			}
			 if ( isset( $_POST['teacher_college'] ) ) {
				if (user($uid, '	teacher_college') != $_POST['teacher_college']) {
					update_user_meta($uid, 'teacher_college',$_POST['teacher_college']);
				}
			}
			if ( isset( $_POST['teacher_education'] ) ) {
				if (user($uid, '	teacher_education') != $_POST['teacher_education']) {
					update_user_meta($uid, 'teacher_education',$_POST['teacher_education']);
				}
			}
			if ( isset( $_POST['teacher_degree'] ) ) {
				if (user($uid, '	teacher_degree') != $_POST['teacher_degree']) {
					update_user_meta($uid, 'teacher_degree',$_POST['teacher_degree']);
				}
			}
			if ( isset( $_POST['Bkash_number'] ) ) {
				if (user($uid, '	Bkash_number') != $_POST['Bkash_number']) {
					update_user_meta($uid, 'Bkash_number',$_POST['Bkash_number']);
				}
			}
		if ( isset( $_POST['personal_info'] ) ) {
			
			if(get_option('wpjobster_wysiwyg_for_profile') != 'yes'){
				$_POST['personal_info'] = strip_tags(nl2br($_POST['personal_info']), '<br />');
				if(mb_strlen($_POST['personal_info'])<$wpjobster_characters_personalinfo_min || mb_strlen($_POST['personal_info'])>$wpjobster_characters_personalinfo_max)
				{
					$ok = 0; $post_new_error['personal_info']= sprintf(__('Personal info needs to have at least %d characters and %d at most!','wpjobster'),$wpjobster_characters_personalinfo_min,$wpjobster_characters_personalinfo_max);
				}

				$_POST['personal_info'] = substr(strip_tags(nl2br($_POST['personal_info']), '<br />'),0,$wpjobster_characters_personalinfo_max);
			}else{
				$_POST['personal_info'] = wpj_description_parser( $_POST['personal_info'] );
				$kses_job_description = wp_kses( $_POST['personal_info'], array() );
				if ( mb_strlen( $kses_job_description ) < $wpjobster_characters_personalinfo_min || mb_strlen ( $kses_job_description ) > $wpjobster_characters_personalinfo_max ){
					$ok = 0; $post_new_error['personal_info']= sprintf(__('Personal info needs to have at least %d characters and %d at most!','wpjobster'),$wpjobster_characters_personalinfo_min,$wpjobster_characters_personalinfo_max);
				}
			}
		}

		if (!is_demo_user()) {
			if ( isset( $_POST['cell_number'] ) ) {
				if (user($uid, 'cell_number') != $_POST['cell_number']) {
					update_user_meta($uid, 'uz_phone_verification', 0);
				}
			}

			$data = array("user_company", "tax_id", "address", "cell_number", "city", "zip", "personal_info", "bank_bank_name", "bank_bank_address", "bank_account_name", "bank_account_number", "bank_account_currency", "bank_additional_info");

			if (get_option("wpjobster_enable_country_select") != "no") { array_push($data, "country_code"); }
			array_push($data, "timezone_select");

			if (count(get_preferred_languages()) > 1) { array_push($data, "preferred_language"); }

			if (!user($uid, 'first_name')) { array_push($data, "first_name"); }
			if (!user($uid, 'last_name')) { array_push($data, "last_name"); }
			if (!user($uid, 'paypal_email')) { array_push($data, "paypal_email"); }
			if (!user($uid, 'payoneer_email')) { array_push($data, "payoneer_email"); }
			if (!user($uid, 'payoneer_card')) { array_push($data, "payoneer_card"); }
                        do_action( 'wpjobster_save_withdraw_personalinfo_gateway', $uid );

			foreach($data as $k){
				if ( isset( $_POST[$k] ) ) {
					update_user_meta($uid, $k, $_POST[$k]);
				}
			}
		}


		$display_portofolio = apply_filters( 'display_or_hide_section_filter', true );
		if ( get_option('wpjobster_enable_user_profile_portfolio') == 'yes' && $display_portofolio == 'true' ) {
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');

			$default_nr = get_option('wpjobster_profile_default_nr_of_pics');
			if(empty($default_nr)) $default_nr = 5;

			for($j=1;$j<=	$default_nr; $j++){
				if(!empty($_FILES['file_' . $j]['name'])):
					$upload_overrides 	= array( 'test_form' => false );
					$uploaded_file 		= wp_handle_upload($_FILES['file_' . $j], $upload_overrides);
					$file_name_and_location = $uploaded_file['file'];
					$file_title_for_media_library = $_FILES['file_' . $j]['name'];
					$arr_file_type 		= wp_check_filetype(basename($_FILES['file_' . $j]['name']));
					$uploaded_file_type = $arr_file_type['type'];

					if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" ){

						$attachment = array(
							'post_mime_type' => $uploaded_file_type,
							'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
							'post_content' => '',
							'post_status' => 'publish',
							'post_author' => $uid,
						);

						if(!is_demo_user()) {
							$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
							$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
							wp_update_attachment_metadata($attach_id,  $attach_data);
							update_post_meta( $attach_id, 'is_portfolio', 1 );
						}
					}
				endif;
			}

			if ( isset( $_POST['images_order'] ) ) {
				$images_order = htmlspecialchars($_POST['images_order']);

				if ($images_order) {
					$images_order = explode(',', $images_order);
					$i = 1;
					foreach ($images_order as $image) {
						if(!is_demo_user()) {
							update_post_meta($image, 'images_order', $i);
						}
						$i++;
					}
				}
			}
		}


		if(isset($_POST['password'])){
			if(  !empty($_POST['password'])):
			$p1 = trim($_POST['password']);
			$p2 = trim($_POST['reppassword']);

			if($p1 == $p2){
				if (!is_demo_user()) {
					global $wpdb;
					$newp = md5($p1);
					$sq = "update ".$wpdb->prefix."users set user_pass='$newp' where ID='$uid'" ;
					$wpdb->query($sq);
				}
			} else echo '<div class="error">'.__('Password was not changed. It does not match the password confirmation.','wpjobster').'</div>';
			endif;
		}


		if(isset($_POST['email'])){
			if(!empty($_POST['email'])){
				$user_info = get_userdata($uid);
				$old_email = $user_info->user_email;
				$new_email = trim($_POST['email']);

				if($old_email != $new_email){
					if ( email_exists( $new_email ) ) {
						echo '<div class="error">'.__('<strong>ERROR</strong>: This email is already registered, please choose another one.','wpjobster').'</div>';
					}else{
						if (!is_demo_user()) {
							wp_update_user( array( 'ID' => $uid, 'user_email' => $new_email ) );
							update_user_meta( $uid, 'uz_email_verification', 0 );
							$email_key = wpjobster_email_verification_init($uid);
							wpjobster_send_email_allinone_translated('user_verification', $uid, false, false, false, false, false, false, $email_key);
						}
					}
				}
			}
		}


		if(!empty($_FILES['avatar']["tmp_name"])){
			$avatar = $_FILES['avatar'];

			$tmp_name 	= $avatar["tmp_name"];
			$name 		= $avatar["name"];

			$upldir = wp_upload_dir();
			$path = $upldir['path'];
			$url  = $upldir['url'];

			$name = str_replace(" ","",$name);

			if(getimagesize($tmp_name) > 0)
			{
				move_uploaded_file($tmp_name, $path."/".$name);
				if (!is_demo_user()) {
					update_user_meta($uid, 'avatar', $url."/".$name);
				}
			}
		}

		do_action( 'wpjobster_user_profile_extra_fields_update', $uid );
		$ok = apply_filters( 'wpj_validation_profile_extra_fields', $ok );
		$post_new_error = apply_filters( 'wpj_validation_profile_extra_fields_error_messages', $post_new_error );

		if($ok){
			echo '<div class="saved_thing">'.__("Information saved!","wpjobster").'</div>';
		}else{
			if(is_array($post_new_error) && $ok == 0)
			{
				echo '<div class="errrs">';

					foreach($post_new_error as $e)
					echo '<div class="newad_error">'.$e. '</div>';

				echo '</div>';

			}
		}
	} // if(isset($_POST['save-info'])){

	do_action( 'wpj_validation_profile_extra_fields_onload' );

}
