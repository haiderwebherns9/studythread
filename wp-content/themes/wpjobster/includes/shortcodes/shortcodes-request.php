<?php
// ADD/EDIT REQUEST

if( ! function_exists( 'wpjobster_add_or_edit_request_form' ) ) {
	function wpjobster_add_or_edit_request_form($display_posts = 0, $class = '') {

		global $site_url_localized, $current_user, $wpdb;
		$current_user = wp_get_current_user();
		$user_type = get_user_meta( $current_user->ID, 'wpjobster_user_type',true ); 
		if ( is_user_logged_in() ) {

			// Get locations vars
			$wpjobster_request_location = get_option('wpjobster_request_location');
			$wpjobster_request_lets_meet = get_option('wpjobster_request_lets_meet');
			$wpjobster_request_location_display_condition = get_option('wpjobster_request_location_display_condition');
			$wpjobster_request_date_display_condition = get_option('wpjobster_request_date_display_condition');
			$wpjobster_request_location_display_map = get_option('wpjobster_request_location_display_map');
			remove_filter( 'the_content', 'wpautop' );

			$req_title = '';

			if(isset($_GET['request_id'])){

				// Get vars if action = edit
				$request_id = $_GET['request_id'];

				if(isset(get_post($request_id)->post_content)){
					$req_title = get_the_title( $request_id );
					$req_desc = str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($request_id)->post_content));
					$req_budget = get_post_meta($request_id, 'budget', true);
					$req_budget_from = get_post_meta($request_id, 'budget_from', true);
					$req_deliv = get_post_meta($request_id, 'job_delivery', true);
					$req_attachments = get_post_meta($request_id, 'req_attachments', true);
					$req_cat = wp_get_post_terms( $request_id, 'request_cat' );

					$req_subj_cat = get_post_meta( $request_id, 'req_subj_cat' );
					$req_subj_subcat = get_post_meta( $request_id, 'req_subj_subcat' );
                    $req_currency = get_post_meta( $request_id, 'req_currency' );			
					
					
					$req_lets_meet = get_option('wpjobster_request_lets_meet');
					$req_lets_meet_checked = get_post_meta($request_id, 'req_lets_meet_checked', true);
					$request_deadline = get_post_meta($request_id, 'request_deadline', true);

					$req_start_date = get_post_meta($request_id, 'request_start_date', true);
					$req_end_date = get_post_meta($request_id, 'request_end_date', true);

					$req_address = get_post_meta($request_id, 'request_location_input', true);
				}else{
					unset($_GET['request_id']);
				}
			} ?>

			<div id="content-full-ov" class="page_without_sidebar request-form-holder">
				<!-- Set title -->
				<div class="ui basic notpadded segment">
					<div class="add-new-request">
					<div class="pst_req">	
						<div id="req_Modal" class="jmodal">
						  <!-- Modal content -->
						  <div class="jmodal-content">
						  <span class="cclose">&times;</span>
							<div class="jmodal-body">
								 <?php if ( is_active_sidebar( 'posting_req_video' ) ) : ?>
														<?php dynamic_sidebar( 'posting_req_video' ); ?>
											   <?php endif; ?>
							</div>
							</div>
						 </div>
						 </div>
					<script>
				 $(document).ready(function() {
					$("#reqpop").click(function(){
						 $("#req_Modal").show();
					});
					$(".cclose").click(function(){
						 $("#req_Modal").hide();
					});
			   });
		</script>
						<h1 class="ui header wpj-title-icon">
							<i class="edit icon"></i>
							<?php
							if(isset($_GET['request_id']))
								$label_name = __("Edit Request","wpjobster");
							else
								$label_name = __("Post New Request","wpjobster");
							echo $label_name;
							?>
						</h1>
						<h3 id="reqpop" style="cursor:pointer;">(Watch this Video before Post new request)</h3>
					</div>
				</div>

				<?php

				// Get rejected vars
				$req_rejected_name = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_name", true) : '';
				$req_rejected_description = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_description", true) : '';
				$req_rejected_tags = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_tags", true) : '';
				$req_rejected_deadline = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_deadline", true) : '';
				$req_rejected_budget_from = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_budget_from", true) : '';
				$req_rejected_budget_to = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_budget_to", true) : '';
				$req_rejected_attachments = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_attachments", true) : '';

				$req_rejected_name_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_name_comment", true) : '';
				$req_rejected_description_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_description_comment", true) : '';
				$req_rejected_tags_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_tags_comment", true) : '';
				$req_rejected_deadline_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_deadline_comment", true) : '';
				$req_rejected_budget_from_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_budget_from_comment", true) : '';
				$req_rejected_budget_to_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_budget_to_comment", true) : '';
				$req_rejected_attachments_comment = (isset($request_id)) ? get_post_meta($request_id, "req_rejected_attachments_comment", true) : '';

				$request_id = isset( $request_id ) ? $request_id : '';
				?>

				<div class="ui segment">
					<div class="<?php echo $class; ?>">
						<div id="request-error-show" class="errrs">
							<div class="newad_error request-error-show"></div>
						</div>

						<form method="post" action="" class="ui form">
							<input type="hidden" value="<?php if(isset($_GET['request_id'])){ echo $_GET['request_id']; } ?>" name="reqidedit" />
							<!-- TITLE -->
							<div class="field instructions-popup">
								<label><?php echo __('Title', 'wpjobster'); ?></label>
								<input type="text" size="40" class="grey_input charlimit-jobtitle uz-listen1 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_name == 1) echo 'rejected-input'; ?>" name="request_title" value="<?php echo $req_title; ?>"><?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
							</div>
							<?php echo wpj_get_popup( 'request_title_instructions', 'edit', 'request', $request_id, 'name' ); ?>

							<!-- DESCRIPTION -->
							<div class="field instructions-popup">
								<label><?php echo __('Description', 'wpjobster'); ?></label>
								<textarea class="charlimit-request big-search-select grey_input uz-listen1 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_description == 1) echo 'rejected-input'; ?>" name="request" id="request_textarea" placeholder=""><?php if(isset($req_desc) && $req_desc) echo $req_desc; ?></textarea><?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
								<input name="action" type="hidden" value="request_action">
								<input name="job_cat" type="hidden" value="">
							</div>
							<?php echo wpj_get_popup( 'request_description_instructions', 'edit', 'request', $request_id, 'description' ); ?>

							<!-- TAG -->
							<?php
								$pid = isset($_GET['request_id']) ? $_GET['request_id'] : '';
								$request_tags = '';
								$t = wp_get_post_tags($pid);
								$i = 0;
								$i_separator = '';

								foreach($t as $tag)
								{
									$request_tags .= $i_separator . $tag->name;
									$i++;
									if ($i > 0) { $i_separator = ', '; }
								}
								
							?>
							<div class="field instructions-popup input-block" style="display:none;">
								<label><?php echo __('Tags', 'wpjobster'); ?> <span class="lighter">(<?php _e('separate your tags by comma','wpjobster'); ?>)</span></label>
								<input type="text" id="<?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_tags == 1) echo 'job_tags_rejected'; else echo 'job_tags'; ?>" size="50" class="grey_input uz-listen1 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_tags == 1) echo 'rejected-input'; ?>" name="request_tags" value="<?php if(isset($request_tags) && $request_tags) echo $request_tags; ?>" /><br/>
							</div>
							<?php echo wpj_get_popup( 'request_tags_instructions', 'edit', 'request', $request_id, 'tags' ); ?>
                       <script>
					   function display_subcat(this_var,vals,selected){
							
							if(typeof(selected)==='undefined'){
								selected=''
							}
                            
							(function($){
								$.post("<?php bloginfo('url'); ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
									if(data.length >0) {
									
									$(this_var).parent().parent().parent().find('.post-new-subcat').html(data);
									$(this_var).parent().parent().parent().find('.post-new-subcat select').val(selected);
										//$('#sub_cats').html(data);
										//$('#sub_cats select').val(selected);
									}
									$.fn.myFunction();
								});
								jQuery(function(){
									$('.ui.dropdown').dropdown();
								});
							})(jQuery);
						}
					   </script>
					   				<script>
					      jQuery(document).ready(function($) {
							  var sub_fd = $('#fd_cat').html();
								$('#add_extra_sub').click(function(){								
									$('.add_ex').before('<div class="two fields">'+sub_fd+'</div>');
							        $('.ui.dropdown').dropdown();
								});
						 });
					 </script>
					 	
				<?php	
				    $exp = explode(',',$req_subj_cat[0]);
					$exp_subcat = explode(',',$req_subj_subcat [0]);
					//print_r($exp);print_r(exp_subcat);
					foreach($exp as $key3=>$cat_val)
					{
					?>
					   <div class="two fields" id="fd_cat">
		                        <!-- JOB CATEGORY -->
								<div class="field instructions-popup">
									<label><?php //echo __('Category', 'wpjobster'); ?>Subjects</label>

									<?php
									echo wpjobster_get_categories_clck("subj_cat[]",
									!isset($_POST['subj_cat']) ? (isset($cat_val)&& isset($cat_val) ? $cat_val : "") : htmlspecialchars($_POST['subj_cat'])
									, __('Select Category','wpjobster'), "ui dropdown new-post-category styledselect uz-listen2", 'onchange="display_subcat(this,this.value)"' );
									?>
								</div>
								<?php echo wpj_get_popup( 'job_category_instructions' ); ?>
		<!-- END JOB CATEGORY -->

		<!-- JOB SUBCATEGORY -->
								<div class="field subcat-field instructions-popup">
									<?php echo '<span  class="post-new-subcat">';
										if(!empty($cat_val)){ 
										    //echo $selected1 = $exp_subcat[$key3];
											$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_val;
											$sub_terms2 = get_terms( 'job_cat', $args2 );
											$ret = '<select class="styledselect2 uz-listen2" name="subcat">';
											$ret .= '<option value="">'.__('Select Subcategory','wpjobster'). '</option>';
											$selected1 = $exp_subcat[$key3];
											foreach ( $sub_terms2 as $sub_term2 ) {
												$sub_id2 = $sub_term2->term_id;
												$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';
											}
											$ret .= "</select>";
											echo $ret;
										}
									echo '</span>'; ?>
								</div>
								<?php echo wpj_get_popup( 'job_subcategory_instructions' ); ?>
		<!-- END JOB SUBCATEGORY -->
							</div><!-- END ROW -->
							<?php 
					                }
							?>
							 <div class="field add_ex" >
									<a href="javascript:void(0);" id="add_extra_sub" class="cursor_pointer ">+ Add New Extra</a>
							 </div> 
							<!-- CATEGORY -->
							<?php
							$selected = isset($_GET['job_cat'])?($_GET['job_cat']):'';
							$selected_slug = '';

							if(!isset($_GET['job_cat'])){ $selected = '';
								$term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

								if(isset($term)&&is_object($term)&&$term->name!=''){
									$selected_slug = $term->slug;
								}
							}
							else{
								$term = get_term_by('name', $selected, get_query_var( 'taxonomy' ) );
								if(isset($term->name) && $term->name!=''){
									$selected_slug = $term->slug;
								}
							}

							if(isset($req_cat) && $req_cat){
								$req_cat = str_replace('-req', '', $req_cat[0]->slug);
								$selected = strtolower($req_cat);
							}
							?>

							<div class="field instructions-popup" style="display:none;">
								<input type="hidden" name="job_location_cat" value="<?php get_user_meta($current_user->ID,'country',true) ?>">
								<label><?php echo __('Category', 'wpjobster'); ?></label>
								<?php echo wpjobster_get_categories_name_select('job_cat', $selected, __("Categories",'wpjobster'), "grey_input new-post-category styledselect uz-listen2", "slug"); ?>
							</div>
							<?php echo wpj_get_popup( 'request_category_instructions' ); ?>
                              
							<div class="two fields">
								<?php if(get_option('wpjobster_request_deadline') == "yes"){ ?>
									<!-- DEADLINE -->
									<div class="field instructions-popup">
										<label><?php echo __('Deadline', 'wpjobster'); ?></label>
										<div class="ui calendar" id="deadline_input_ui">
											<div class="ui input left icon">
												<i class="calendar icon"></i>
												<input class="grey_input lighter w100 request_datepick uz-listen1 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_deadline == 1) echo 'rejected-input'; ?>" type="text" placeholder="<?php _e('Deadline','wpjobster') ?>" id="request_deadline_input" value="<?php if(isset($request_deadline) && $request_deadline){ echo $request_deadline; } ?>" name="request_deadline">
											</div>
										</div>
									</div>
									<?php echo wpj_get_popup( 'request_deadline_instructions', 'edit', 'request', $request_id, 'deadline' );
								} ?>

								<?php if(get_option('wpjobster_request_max_deliv') == "yes"){ ?>
									<!-- MAX DELIVERY DAYS -->
									<div class="field instructions-popup">
										<?php if(isset($req_deliv) && $req_deliv) $maxd = $req_deliv; else $maxd = '';  ?>
										<label><?php echo __('How Many Days You Need The Service For:', 'wpjobster'); ?></label>
										<div id="max_days_to_deliver_select_container">
												<?php echo wpjobster_max_days_to_deliver('job_delivery', $maxd, __("Expected delivery",'wpjobster'), "styledselect new-post-category uz-listen2", "slug"); ?>
										</div>
									</div>
									<?php echo wpj_get_popup( 'request_delivery_time_instructions' );
								} ?>
							</div>

							<div class="two fields">
								<?php// if(get_option('wpjobster_request_budget') == "yes"){ ?>
									<!-- BUDGET FROM -->
									<div class="field instructions-popup">
										<label>
											<?php echo __('Budget', 'wpjobster'); ?>
											<p class="lighter">
												<div id="budget_input_container">
													<input class="decimal grey_input lighter w100 uz-listen1 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_budget_from == 1) echo 'rejected-input'; ?>" type="number" min="0" placeholder="<?php echo sprintf( __( 'Budget', 'wpjobster' ), wpjobster_get_currency_classic() ); ?>" id="request_budget_from_input" value="<?php if(isset($req_budget_from) && $req_budget_from) echo $req_budget_from; ?>" name="budget_from">
												</div>
											</p>
										</label>
									</div>
									<?php echo wpj_get_popup( 'request_budget_from_instructions', 'edit', 'request', $request_id, 'budget_from' );
								// } ?>
                                      <div class="field instructions-popup">
										<label>
											<?php echo __('Currency', 'wpjobster'); ?>
										</label>	
											<?php 
											global $wpdb;
	                                        $query = "SELECT * FROM 	wp_87fsrr_country";
                                            $res= $wpdb->get_results($query); 
											//print_r($req_currency);
											?>
												    <select name="req_curency" id="req_currency" class="ui dropdown">
													  <option value="">Currency</option>
													   <?php foreach($res as $res_val) { ?>
													   <option value="<?php echo $res_val->country_id;?>" <?php if($req_currency[0]==$res_val->country_id){?>selected<?php } ?>><?php echo $res_val->currency;?></option>
													   <?php } ?>
													</select>
									</div>
								<!-- BUDGET TO -->
								<?php if(get_option('wpjobster_request_budget') == "yes"){ ?>
									<div class="field instructions-popup" style="display:none">
										<label>
											<?php echo __('Budget To', 'wpjobster'); ?>
											<p class="lighter">
												<div id="budget_input_container">
													<input class="decimal grey_input lighter w100 uz-listen2 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_budget_to == 1) echo 'rejected-input'; ?>" type="number" min="0" placeholder="<?php echo sprintf( __( 'Budget (%s)', 'wpjobster' ), wpjobster_get_currency_classic() ); ?>" id="request_budget_input" value="<?php if(isset($req_budget) && $req_budget) echo $req_budget; ?>" name="budget">
												</div>
											</p>
										</label>
									</div>
									<?php echo wpj_get_popup( 'request_budget_to_instructions', 'edit', 'request', $request_id, 'budget_to' );
								} ?>
							</div>

							<div class="two fields">
								<?php if(get_option('wpjobster_request_file_upload') == "yes"){ ?>
									<!-- UPLOAD ATTACHMENTS -->
									<div class="field instructions-popup">
										<label><?php echo __('Upload attachments', 'wpjobster'); ?></label>
										<?php
										if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_attachments == 1){
											echo '<div class="rejected-border">';
										}

										wpjobster_theme_attachments_uploader_html5($secure=1,"file_upload_new_request_attachments", "hidden_files_new_request_attachments", "new_request");

										if (isset($request_id) && get_post_status($request_id) == 'pending' && $req_rejected_attachments == 1){
											echo '</div>';
										}
										if (isset($req_attachments) && $req_attachments) {
											echo '<div class="pm-attachments">';
											$attachments = explode(",", $req_attachments);
											foreach ($attachments as $attachment) {
												if($attachment != ""){
													echo '<div class="div_div2" id="image_ss'.$attachment.'"><a class="download-req" target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
													echo substr(get_the_title($attachment), 0, 20).'...</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span>';
													echo '<a href="javascript: void(0)" onclick="delete_this_reqfile('.$attachment.','.$request_id.');"></a></div><br>';
												}
											}
										echo '</div>';
										}
										?>
									</div>
									<?php echo wpj_get_popup( 'request_attachments_instructions', 'edit', 'request', $request_id, 'attachments' );
								} ?>

								<!-- LET'S MEET -->
								<div class="field instructions-popup">
									<?php
									if ($wpjobster_request_location == "yes") {
										if($wpjobster_request_lets_meet == "yes"){ ?>

											<label><?php echo __("Let's meet", "wpjobster"); ?></label>

											<div class="post-new-job-slide-box">
												<div class="ui toggle checkbox">
													<input type="checkbox" <?php if(isset($req_lets_meet_checked) && $req_lets_meet_checked && $req_lets_meet_checked == 'yes'){ echo "checked"; } ?> class="grey_input uz-listen2 <?php if (isset($request_id) && get_post_status($request_id) == 'pending' && $request_lets_meet == 1) echo 'rejected-input'; ?>" name="request_lets_meet" id="request_lets_meet" value="yes" />
													<label><?php echo __(' Slide to enable', 'wpjobster' ); ?></label>
												</div>
											</div>

										<?php }
									} ?>
								</div>
								<?php echo wpj_get_popup( 'request_lets_meet_instructions' ); ?>
							</div>

							<?php if ($wpjobster_request_location == "yes") { ?>
								<script>
								jQuery(document).ready(function(){
									$( '#start_date_input_ui' ).wpjcalendar();
									$( '#end_date_input_ui' ).wpjcalendar();
									$( '#deadline_input_ui' ).wpjcalendar();
								})
								</script>

								<?php
								// LOCATION
								if ($wpjobster_request_location == "yes") {
									if ($wpjobster_request_location_display_condition == "always" || $wpjobster_request_location_display_condition == "ifchecked") {
										if ($wpjobster_request_location_display_condition == "ifchecked") {
											?>
											<script>
											jQuery(document).ready(function(){

												// location toggle
												if (jQuery("#request_lets_meet").is(':checked')) {
													jQuery("#location_input_container").show();
												} else {
													jQuery("#location_input_container").hide();
												}

												jQuery('#request_lets_meet').click(function() {
													jQuery("#location_input_container").slideToggle(300);
												});

											});
											</script>
											<?php
										}
										?>
										<div class="field instructions-popup" id="location_input_container">
											<label><?php echo __("Location", "wpjobster"); ?></label>
											<input class="grey_input lighter w100" type="text" data-replaceplaceholder="<?php _e('Select a valid location','wpjobster') ?>" placeholder="<?php _e('Location','wpjobster') ?>" id="request_location_input" value="<?php if(isset($req_address) && $req_address){ echo $req_address; } ?>" name="request_location_input">
											<input id="request_lat" type="hidden" name="request_lat" value="">
											<input id="request_long" type="hidden" name="request_long" value="">
										</div>
										<?php echo wpj_get_popup( 'request_location_instructions' );
									}
								}
								if ($wpjobster_request_date_display_condition == "always" || $wpjobster_request_date_display_condition == "ifchecked") {
									if ($wpjobster_request_date_display_condition == "ifchecked") {
										?>
										<script>
										jQuery(document).ready(function(){

											// start date toggle
											if (jQuery("#request_lets_meet").is(':checked')) {
												jQuery("#start_date_input_container").show();
											} else {
												jQuery("#start_date_input_container").hide();
											}

											jQuery('#request_lets_meet').click(function() {
												jQuery("#start_date_input_container").slideToggle(300);
											});


											// end date toggle
											if (jQuery("#request_lets_meet").is(':checked')) {
												jQuery("#end_date_input_container").show();
											} else {
												jQuery("#end_date_input_container").hide();
											}

											jQuery('#request_lets_meet').click(function() {
												jQuery("#end_date_input_container").slideToggle(300);
											});
										});
										</script>
									<?php } ?>

									<div class="two fields empty-field">
										<!-- START DATE -->
										<div class="field instructions-popup">
											<div id="start_date_input_container">
												<label><?php echo __("Start Date", "wpjobster"); ?></label>
												<div class="ui calendar" id="start_date_input_ui">
													<div class="ui input left icon">
														<i class="calendar icon"></i>
														<input class="grey_input lighter w100 request_datepick" type="text" placeholder="<?php _e('Start Date','wpjobster') ?>" id="request_start_date_input" value="<?php if(isset($req_start_date) && $req_start_date){ echo $req_start_date; } ?>" name="request_start_date">
													</div>
												</div>
											</div>
										</div>
										<?php echo wpj_get_popup( 'request_start_date_instructions' ); ?>

										<!-- END DATE -->
										<div class="field instructions-popup">
											<div id="end_date_input_container">
												<label><?php echo __("End Date", "wpjobster"); ?></label>
												<div class="ui calendar" id="end_date_input_ui">
													<div class="ui input left icon">
														<i class="calendar icon"></i>
														<input class="grey_input lighter w100 request_datepick" type="text" placeholder="<?php _e('End Date','wpjobster') ?>" id="request_end_date_input" value="<?php if(isset($req_end_date) && $req_end_date){ echo $req_end_date; } ?>" name="request_end_date">
													</div>
												</div>
											</div>
										</div>
										<?php echo wpj_get_popup( 'request_end_date_instructions' ); ?>
									</div>

									<?php
								}
							}
							if (is_user_logged_in()) { ?>
								<script>
									jQuery(document).ready(function(){
										var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)request_suggest_cookie\s*\=\s*([^;]*).*$)|^.*$/, "$1");
										if(cookieValue!='') {
											document.getElementById("request_textarea").value = cookieValue;
										}
										jQuery('#suggest_job_btn').click(function() {
											document.cookie = "request_suggest_cookie=;Expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/";
										});
									});
								</script>
								<?php
									if(isset($_GET['request_id']))
										$button_name = __("Save request","wpjobster");
									else
										$button_name = __("Suggest","wpjobster");
								?>
								<input type="submit" value="<?php echo $button_name; ?>" name="submit_prepare_request" id="suggest_job_btn" class="i_will_continue" />
								<div id="request-error-notify" class="errs-notify">
									<div class="newad_error request-error-notify"></div>
								</div>
							<?php } else { ?>
								<a id="request_suggest_btn" class="btn login-link" href="<?php echo get_bloginfo('url') . '/wp-login.php?redirect_to=' . urlencode( get_permalink() ); ?>"><?php echo __("Suggest","wpjobster"); ?></a>
								<script>
									jQuery(document).ready(function(){
										jQuery('#request_suggest_btn').click(function() {
											var request_textarea_val = document.getElementById("request_textarea").value;
											if( request_textarea_val == "" ){
												alert("Please type down your request");
												return;
											} else {
												var date = new Date();
												var minutes = 5;
												date.setTime(date.getTime() + (minutes * 60 * 1000));
												document.cookie = "request_suggest_cookie="+request_textarea_val+";expires="+date.toUTCString()+";path=/";
											}
										});
									});
								</script>
							<?php } ?>

							<div class="success-response">
								<div class="response-text">
									<?php _e('The request was submitted successfully.','wpjobster'); ?>
								</div>
							</div>
						</form>
					</div>


					<?php
					$characters_request_max = get_option("wpjobster_characters_request_max");
					$wpjobster_characters_request_max = (empty($wpjobster_characters_request_max)|| $wpjobster_characters_request_max==0)?500:$wpjobster_characters_request_max;
					$characters_jobtitle_max = get_option("wpjobster_characters_jobtitle_max");
					$characters_jobtitle_max = (empty($characters_jobtitle_max)|| $characters_jobtitle_max==0)?80:$characters_jobtitle_max;
					?>
					<script>
						jQuery(document).ready(function($) {
							jQuery(".charlimit-request").counted({count:<?php echo $characters_request_max;?>});
							jQuery(".charlimit-jobtitle").counted({count:<?php echo $characters_jobtitle_max;?>});
						});
					</script>

				</div>
			</div>

			<div class="ui hidden divider"></div>

			<script type="text/javascript">
				function delete_this_reqfile(id, reqid){
					$.ajax({
						method: 'get',
							url : '<?php echo get_bloginfo('url');?>/index.php/?_ad_delete_reqid='+id+'&reqid='+reqid,
							dataType : 'text',
							success: function (text) {
								$('#image_ss'+id).remove();
								$("#hidden_instant_file_uploader").removeClass('hidden');
						}
					});
				}
			</script>

			<?php
		}else{
			wp_redirect( get_bloginfo('url')."/wp-login.php?redirect_to=" . urlencode( get_permalink() ) ); exit;
		}
	}
}
