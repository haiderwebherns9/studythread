<?php
add_action('wp_ajax_nopriv_request_action', 'request_action');
add_action('wp_ajax_request_action', 'request_action');
if ( ! function_exists( 'request_action' ) ) {
	function request_action() {
        //print_r($_POST);
		if(!is_user_logged_in()) {
			$error['error']=__('You need to be logged in to be able to post requests', 'wpjobster');
		} else {
			//print_r($_POST);
			//exit;
			global $current_user;
			$current_user = wp_get_current_user();
			$wpjobster_admin_approve_request = get_option('wpjobster_admin_approve_request');
			$rqq = trim($_POST['request']);
			$req_title = ( isset( $_POST['request_title'] ) && $_POST['request_title'] != '' ) ? trim( $_POST['request_title'] ) : mb_substr( $rqq, 0, 65 ) . '...';
			$request_cat = $_POST['job_cat'];

			if(isset($_POST['job_delivery'])) $max_deliv = $_POST['job_delivery']; else $max_deliv = '';
			if(isset($_POST['budget'])) $budget = $_POST['budget']; else $budget = '';
			if(isset($_POST['budget_from'])) $budget_from = $_POST['budget_from']; else $budget_from = '';
			if(isset($_POST['request_deadline'])) $request_deadline = $_POST['request_deadline']; else $request_deadline = '';

			if(isset($_POST['request_start_date'])) $request_start_date = $_POST['request_start_date']; else $request_start_date = '';
			if(isset($_POST['request_end_date'])) $request_end_date = $_POST['request_end_date']; else $request_end_date = '';
			if(isset($_POST['request_location_input'])) $request_location_input = $_POST['request_location_input']; else $request_location_input = '';
			if(isset($_POST['request_lat'])) $request_lat = $_POST['request_lat']; else $request_lat = '';
			if(isset($_POST['request_long'])) $request_long = $_POST['request_long']; else $request_long = '';

			if(isset($_POST['subj_cat'])) $req_subj_cat = $_POST['subj_cat']; else $req_subj_cat = '';
			if(isset($_POST['subcat'])) $req_subj_subcat = $_POST['subcat']; else $req_subj_subcat = '';
			if(isset($_POST['req_curency'])) $req_currency = $_POST['req_curency']; else $req_currency = '';
			
			if(isset($_POST['hidden_files_new_request_attachments'])) $req_attachments = $_POST['hidden_files_new_request_attachments']; else $req_attachments = '';

			$request_tags = isset($_POST['request_tags']) ? trim( strip_tags( htmlspecialchars( $_POST['request_tags'] ) ) ) : '';

			if(isset($_POST['reqidedit']) && $_POST['reqidedit'] != ''){
// EDIT REQUEST
				$reqid = $_POST['reqidedit'];

				if (empty($rqq)) {
					$error['error']=__('You cannot leave the request empty', 'wpjobster');

				} /*elseif (empty($request_cat)) {
					$error['error']=__('Please select a category', 'wpjobster');                 
				}*/ elseif(empty($req_currency)){ 
				  $error['error']=__('Please select a currency', 'wpjobster'); 
			    } elseif(empty($req_title)){
					$error['error']=__('You cannot leave the title request empty', 'wpjobster');
				} elseif(empty($budget_from)){
					$error['error']=__('You cannot leave the Budget empty', 'wpjobster');
				} 
				/*elseif($budget < $budget_from){
					$error['error']=__('Budget from is higher than budget to', 'wpjobster');
                
				}*/ elseif($request_end_date < $request_start_date){
					$error['error']=__('End date is higher than start date', 'wpjobster');
   
				} else {

					wp_set_post_tags( $reqid, $request_tags);

					update_post_meta($reqid, 'budget', $budget);
					update_post_meta($reqid, 'budget_from', $budget_from);
					update_post_meta($reqid, 'job_delivery', $max_deliv);
					update_option('wpjobster_request_lets_meet', 'yes');
					
					//print_r($req_subj_cat);
							$subj_cat = '';
							foreach($req_subj_cat as $key3=>$req_val){
								  $subj_cat .= $req_val;
								if($key3 < (count($req_subj_cat) -1))
								{
									 $subj_cat .=',';
								}
							}
							$subj_subcat = '';
							foreach($req_subj_subcat as $key4=>$req_subj_val){
								  $subj_subcat .= $req_subj_val;
								if($key4 < (count($req_subj_subcat) -1))
								{
									 $subj_subcat .=',';
								}
							}
							if($_GET['request_id']){
							 $rqid=$_GET['request_id'];	
							}
							
							update_post_meta( $reqid, 'req_subj_cat', $subj_cat);
							update_post_meta($reqid, 'req_subj_subcat', $subj_subcat);
					    if(isset($req_currency)){
							update_post_meta( $reqid, 'req_currency', $req_currency);
						}
					if(isset($_POST['request_lets_meet']))
						update_post_meta($reqid, 'req_lets_meet_checked', 'yes');
					else
						update_post_meta($reqid, 'req_lets_meet_checked', 'no');

					update_post_meta($reqid, 'request_deadline', trim(htmlspecialchars($request_deadline)));

					update_post_meta($reqid, 'request_start_date', trim(htmlspecialchars($request_start_date)));
					update_post_meta($reqid, 'request_end_date', trim(htmlspecialchars($request_end_date)));
					update_post_meta($reqid, 'request_location_input', $request_location_input);
					update_post_meta($reqid, 'request_location_input', trim(htmlspecialchars($request_location_input)));
					update_post_meta($reqid, 'request_lat', trim(htmlspecialchars($request_lat)));
					update_post_meta($reqid, 'request_long', trim(htmlspecialchars($request_long)));

					$request_post = array(
						'ID'           => $reqid,
						'post_title'   => $req_title,
						'post_content' => $rqq,
						'post_status'  => ( $wpjobster_admin_approve_request == "yes" ? 'draft' : 'publish' )
					);

					wp_update_post( $request_post );

					if($wpjobster_admin_approve_request == "yes"){
						wpjobster_send_email_allinone_translated('request_edit', 'admin', 'admin', $reqid);
					}

					$job_term = get_term_by('slug', $request_cat , 'job_cat' );

					if( $job_term ){

						$request_term = get_term_by('slug', $request_cat."-req" , 'request_cat' );

						if( $request_term ){
							wp_set_post_terms( $reqid, array($request_term->term_id), 'request_cat' );
						}else{
							if( $job_term->parent ){
								$job_category = get_term_by( 'id', $job_term->parent, 'job_cat' );
								$request_category = get_term_by('slug', $job_category->slug.'-req','request_cat');
							} else {
								$job_category = '';
								$request_category = get_term_by('slug', $request_cat.'-req','request_cat');
							}

							$request_category_id = '';

							if( ! $request_category ){
								if( $job_category ){
									$new_category = array(
										'cat_name' => $job_category->name,
										'category_description' => '',
										'category_nicename' => $job_category->slug."-req",
										'category-slug' => $job_category->slug."-req",
										'taxonomy' => 'request_cat'
									);
								} else {
									$new_category = array(
										'cat_name' => $job_term->name,
										'category_description' => '',
										'category_nicename' => $job_term->slug."-req",
										'category-slug' => $job_term->slug."-req",
										'taxonomy' => 'request_cat'
									);
								}
								$request_category_id = wp_insert_category($new_category);
								wp_set_post_terms( $reqid, array($request_category_id), 'request_cat' );
							}else{
								$request_category_id = $request_category->term_id;
							}

							if( $job_term->parent ){
								$new_subcategory = array(
									'cat_name' => $job_term->name,
									'category_description' => '',
									'category_nicename' => $job_term->slug."-req",
									'category-slug' => $job_term->slug."-req",
									'category_parent' => $request_category_id,
									'taxonomy' => 'request_cat'
								);
								$request_subcategory_id = wp_insert_category($new_subcategory);
								wp_set_post_terms( $reqid, array($request_subcategory_id), 'request_cat' );
							}
						}
					}

					$error['success']='yes';

					if(isset($_POST['hidden_files_new_request_attachments'])){
						$upload_job_any_attachments = $_POST['hidden_files_new_request_attachments'];
						$upload_job_any_attachments_arr = explode(",", $upload_job_any_attachments);
						$existing_job_any_attachments = get_post_meta($reqid, 'req_attachments', true);
						$existing_job_any_attachments_arr = explode(",", $existing_job_any_attachments);
						$new_job_any_attachments_arr = array_merge($upload_job_any_attachments_arr, $existing_job_any_attachments_arr);
						$new_job_any_attachments = implode(",",$new_job_any_attachments_arr);
						update_post_meta( $reqid, 'req_attachments', $new_job_any_attachments );

						foreach ( $new_job_any_attachments_arr as $job_any_attachment ) {
							update_post_meta( $job_any_attachment, 'req_id', $reqid );
						}
					}

					do_action( 'wpjobster_after_request_inserted', $reqid, $job_term->term_id );
				}
			}else{
// ADD NEW REQUEST
				if (empty($rqq)) {
					$error['error']=__('You cannot leave the request empty', 'wpjobster');

				} /*elseif (empty($request_cat)) {
					$error['error']=__('Please select a category', 'wpjobster');

				}*/ elseif($request_end_date < $request_start_date){
					$error['error']=__('End date is higher than start date', 'wpjobster');
               } elseif(empty($budget_from)){
					$error['error']=__('You cannot leave the Budget empty', 'wpjobster');				 
				} /*elseif($budget < $budget_from){
					$error['error']=__('Budget from is higher than budget to', 'wpjobster');
                }*/ elseif(empty($req_currency)){
					$error['error']=__('Please select your currency', 'wpjobster');                
				} else {
					$wpjobster_characters_request_max = get_option("wpjobster_characters_request_max");
					$wpjobster_characters_request_min = get_option("wpjobster_characters_request_min");
					$wpjobster_characters_request_max = (empty($wpjobster_characters_request_max)|| $wpjobster_characters_request_max==0)?500:$wpjobster_characters_request_max;
					$wpjobster_characters_request_min = (empty($wpjobster_characters_request_min)|| $wpjobster_characters_request_min==0)?35:$wpjobster_characters_request_min;
					if(mb_strlen(count_newline_as_one_char($rqq))>$wpjobster_characters_request_max || mb_strlen(count_newline_as_one_char($rqq))<$wpjobster_characters_request_min){
						$error['error']=sprintf(__('Request needs to have at least %d characters and %d at most!', 'wpjobster'), $wpjobster_characters_request_min, $wpjobster_characters_request_max);//__('Error. Please try again.', 'wpjobster');
					} else {
						$my_post = array();
						$my_post['post_title'] = $req_title;
						$my_post['post_content'] = $rqq;
						$my_post['post_type'] = 'request';
						$my_post['post_status'] = ( $wpjobster_admin_approve_request == "yes" ? 'draft' : 'publish' );
						$my_post['post_author'] = $current_user->ID;

						if (!is_demo_user()) {
							$pid = wp_insert_post( $my_post, true );

							$job_term = get_term_by('slug', $request_cat , 'job_cat' );

							if( $job_term ){

								$request_term = get_term_by('slug', $request_cat."-req" , 'request_cat' );

								if( $request_term ){
									wp_set_post_terms( $pid, array($request_term->term_id), 'request_cat' );
								}else{
									if( $job_term->parent ){
										$job_category = get_term_by( 'id', $job_term->parent, 'job_cat' );
										$request_category = get_term_by('slug', $job_category->slug.'-req','request_cat');
									} else {
										$job_category = '';
										$request_category = get_term_by('slug', $request_cat.'-req','request_cat');
									}

									$request_category_id = '';

									if( ! $request_category ){
										if( $job_category ){
											$new_category = array(
												'cat_name' => $job_category->name,
												'category_description' => '',
												'category_nicename' => $job_category->slug."-req",
												'category-slug' => $job_category->slug."-req",
												'taxonomy' => 'request_cat'
											);
										} else {
											$new_category = array(
												'cat_name' => $job_term->name,
												'category_description' => '',
												'category_nicename' => $job_term->slug."-req",
												'category-slug' => $job_term->slug."-req",
												'taxonomy' => 'request_cat'
											);
										}
										$request_category_id = wp_insert_category($new_category);
										wp_set_post_terms( $pid, array($request_category_id), 'request_cat' );
									}else{
										$request_category_id = $request_category->term_id;
									}

									if( $job_term->parent ){
										$new_subcategory = array(
											'cat_name' => $job_term->name,
											'category_description' => '',
											'category_nicename' => $job_term->slug."-req",
											'category-slug' => $job_term->slug."-req",
											'category_parent' => $request_category_id,
											'taxonomy' => 'request_cat'
										);
										$request_subcategory_id = wp_insert_category($new_subcategory);
										wp_set_post_terms( $pid, array($request_subcategory_id), 'request_cat' );
									}
								}
							}

							wp_set_post_tags( $pid, $request_tags);

							add_post_meta( $pid, 'job_delivery', $max_deliv, true );
							add_post_meta( $pid, 'budget', $budget, true );
							add_post_meta( $pid, 'budget_from', $budget_from, true );
							add_post_meta( $pid, 'req_attachments', $req_attachments, true );
                           if(isset($req_currency)){
							 add_post_meta($pid, 'req_currency', $req_currency,true);
						    }
						    //print_r($req_currency);
							$subj_cat = '';
							foreach($req_subj_cat as $key3=>$req_val){
								  $subj_cat .= $req_val;
								if($key3 < (count($req_subj_cat) -1))
								{
									 $subj_cat .=',';
								}
							}
							$subj_subcat = '';
							foreach($req_subj_subcat as $key4=>$req_subj_val){
								  $subj_subcat .= $req_subj_val;
								if($key4 < (count($req_subj_subcat) -1))
								{
									 $subj_subcat .=',';
								}
							}
							add_post_meta( $pid, 'req_subj_cat', $subj_cat, true );
							add_post_meta( $pid, 'req_subj_subcat', $subj_subcat, true );
							
							if(isset($_POST['request_lets_meet']))
								update_post_meta($pid, 'req_lets_meet_checked', 'yes');
							else
								update_post_meta($pid, 'req_lets_meet_checked', 'no');

							$wpjobster_admin_approve_request = get_option("wpjobster_admin_approve_request");
							if($wpjobster_admin_approve_request == "yes"):
								wpjobster_send_email_allinone_translated('request_admin_new', 'admin', false, $pid);
								wpjobster_send_sms_allinone_translated('request_admin_new', 'admin', false, $pid);
								wpjobster_send_email_allinone_translated('request_new', false, false, $pid);
								wpjobster_send_sms_allinone_translated('request_new', false, false, $pid);

								update_post_meta($pid, 'under_review', "1");

							else:
								update_post_meta($pid, 'under_review', "0");
								wpjobster_send_email_allinone_translated('request_admin_acc', 'admin', false, $pid);
								wpjobster_send_sms_allinone_translated('request_admin_acc', 'admin', false, $pid);
								wpjobster_send_email_allinone_translated('request_new_acc', false, false, $pid);
								wpjobster_send_sms_allinone_translated('request_new_acc', false, false, $pid);
							endif;

							// insert location, date
							$wpjobster_request_location = get_option('wpjobster_request_location');
							if ($wpjobster_request_location == "yes") {

								$wpjobster_request_lets_meet = get_option('wpjobster_request_lets_meet');
								if ($wpjobster_request_lets_meet == "yes") {

									if ( isset( $_POST['request_lets_meet'] ) ) {
										$request_lets_meet = trim(htmlspecialchars($_POST['request_lets_meet']));
										update_post_meta($pid, "request_lets_meet", $request_lets_meet);
									}
								}

								$wpjobster_request_location_display_condition = get_option('wpjobster_request_location_display_condition');
								if ($wpjobster_request_location_display_condition == "always"
									|| $wpjobster_request_location_display_condition == "ifchecked") {

									// insert location
									update_post_meta($pid, "request_location_input", trim(htmlspecialchars($_POST['request_location_input'])));
									update_post_meta($pid, "request_lat", trim(htmlspecialchars($_POST['request_lat'])));
									update_post_meta($pid, "request_long", trim(htmlspecialchars($_POST['request_long'])));
								}

								$wpjobster_request_date_display_condition = get_option('wpjobster_request_date_display_condition');
								if ($wpjobster_request_date_display_condition == "always"
									|| $wpjobster_request_date_display_condition == "ifchecked") {

									// insert date
									update_post_meta($pid, "request_deadline", trim(htmlspecialchars($_POST['request_deadline'])));
									update_post_meta($pid, "request_start_date", trim(htmlspecialchars($_POST['request_start_date'])));
									update_post_meta($pid, "request_end_date", trim(htmlspecialchars($_POST['request_end_date'])));
								}
							}

							do_action( 'wpjobster_after_request_inserted', $pid, $job_term->term_id );
						}
						$error['success']='yes';
					}
				}
			}

		}

		echo json_encode($error);
		die();
	}
}

add_action( 'wp_ajax_request_error_content', 'request_error_content' );
add_action( 'wp_ajax_nopriv_request_error_content', 'request_error_content' );
function request_error_content(){ ?>
	<div class="content-full-ov">
		<h4 class="center">
			<?php _e('You need to have at least one active job in order to send custom offers.<br /><br />If you want to post a job, click ', 'wpjobster'); ?>
			<a href="<?php echo get_permalink( get_option( 'wpjobster_post_new_page_id' ) ); ?>"><?php echo __( 'here', 'wpjobster' ); ?>.</a>
		</h4>
	</div><?php
	die();
}
