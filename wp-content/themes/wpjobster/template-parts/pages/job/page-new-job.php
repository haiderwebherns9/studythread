<?php
if ( ! function_exists( 'wpjobster_post_new_area_function' ) ) {
	function wpjobster_post_new_area_function() {
		ob_start();
		do_action("wpjobster_check_user_role");

/* VARS */
		global $current_user, $wpdb;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$wpjobster_post_new_page_id = get_option('wpjobster_post_new_page_id');

		// Checking for new subscriptions
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info();
		extract($wpjobster_subscription_info);

		$s = "select * from " . $wpdb->prefix . "posts where post_type='job' AND post_status='auto-draft' AND post_author=" . $uid . " order by ID desc";
		$r = $wpdb->get_results($s);
		$row = $r[0];
		$last_pid = $row->ID;

		$pid = isset( $_GET['jobid'] ) ? $_GET['jobid'] : $last_pid;

		$vc_inline = function_exists('wpj_vc_is_inline') ? wpj_vc_is_inline() : vc_is_inline();

		if( ! $vc_inline ){
			if ($last_pid > $pid) {
				wp_redirect(get_bloginfo('url') . "/post-new-job/?jobid=" . $last_pid);
			}
		}

		global $post_PID, $post_new_error, $adOK;
		$post_PID = $pid;
		$post = get_post($pid);
		$location = wp_get_object_terms($pid, 'job_location');
		$cat_args = array('orderby' => 'term_order', 'order' => 'ASC');
		$cat = wp_get_object_terms($pid, 'job_cat', $cat_args);

		global $current_user;
		$current_user = wp_get_current_user();
		$cid               = $current_user->ID;
		$post              = get_post($pid);

		$shipping          = (isset($_POST['shipping']) && is_numeric($_POST['shipping'])) ? trim($_POST['shipping']) : '';
		$max_days          = isset($_POST['max_days'])?trim($_POST['max_days']):'';
		$max_days_ex[1]    = isset($_POST['max_days_1'])?trim($_POST['max_days_1']):'';
		$max_days_ex[2]    = isset($_POST['max_days_2'])?trim($_POST['max_days_2']):'';
		$max_days_ex[3]    = isset($_POST['max_days_3'])?trim($_POST['max_days_3']):'';
		$max_days_ex[4]    = isset($_POST['max_days_4'])?trim($_POST['max_days_4']):'';
		$max_days_ex[5]    = isset($_POST['max_days_5'])?trim($_POST['max_days_5']):'';
		$max_days_ex[6]    = isset($_POST['max_days_6'])?trim($_POST['max_days_6']):'';
		$max_days_ex[7]    = isset($_POST['max_days_7'])?trim($_POST['max_days_7']):'';
		$max_days_ex[8]    = isset($_POST['max_days_8'])?trim($_POST['max_days_8']):'';
		$max_days_ex[9]    = isset($_POST['max_days_9'])?trim($_POST['max_days_9']):'';
		$max_days_ex[10]   = isset($_POST['max_days_10'])?trim($_POST['max_days_10']):'';
		$max_days_fast     = isset($_POST['max_days_fast'])?trim($_POST['max_days_fast']):'';
		$max_days_revision = isset($_POST['max_days_revision'])?trim($_POST['max_days_revision']):'';
		$ttl               = (empty($_SESSION['i_will']) ? $post->post_title : $_SESSION['i_will']);
		$ttl               = (empty($_SESSION['i_will']) ? ($post->post_title == "Auto Draft" ? "" : get_post_meta($post->ID,'title_variable',true)) : $_SESSION['i_will']);

		$display = isset( $_POST['packages'] ) && $_POST['packages'] == 'yes' ? 'style="display: none;"' : 'style="display: block;"';
		$display_table =  isset( $_POST['packages'] ) && $_POST['packages'] == 'yes' ? 'style="display: block;"' : 'style="display: none;"';
		?>
<!-- END VARS -->

<!-- CONTENT -->
		<div id="content-full-ov" class="page_without_sidebar">
	<!-- TITLE -->
				<div class="ui basic notpadded segment">
					<div class="post-new-job-title">
						<h1 class="ui header wpj-title-icon">
							<i class="edit icon"></i>
							<?php echo __("Post New Job", 'wpjobster'); ?>
						</h1>
						<h3 id="jbpop">(Watch the Video before Posting new Job)</h3>
						<div style="display:none;">
						<?php if ( is_active_sidebar( 'posting_job_video' ) ) : ?>
                                <?php dynamic_sidebar( 'posting_job_video' ); ?>
                       <?php endif; ?>
						</div>
					</div>
				</div>
	<!-- END TITLE -->

	<!-- ACTION -->
				<?php do_action( 'wpj_after_vars_declaration_for_post_new_job' ); ?>
	<!-- END ACTION -->

	<!-- INSTRUCTIONS -->
				<?php if ( get_field( 'general_instructions', 'options' ) || current_user_can( 'manage_options' ) ) { ?>
					<div class="ui segment">
						<?php if ( get_field( 'general_instructions', 'options' ) ) {
								the_field( 'general_instructions', 'options' );
							} elseif ( current_user_can( 'manage_options' ) ) {
								_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
							} ?>
					</div>
				<?php } ?>
	<!-- END INSTRUCTIONS -->

				<div class="ui segment">
	<!-- SHOW ERRORS -->
					<?php if(is_array($post_new_error)){
						if($adOK == 0)
						{
							echo '<div class="errrs">';
								foreach($post_new_error as $e)
								echo '<div class="newad_error">'.$e. '</div>';
							echo '</div>';
						}
					} ?>

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
									//	$('#sub_cats').html(data);
									//	$('#sub_cats select').val(selected);
									}
									$.fn.myFunction();
								});
								jQuery(function(){
									$('.ui.dropdown').dropdown();
								});
							})(jQuery);
						}

						function check_delivery_time(){
							var max_days_val = $("#max_days").val();

							$("#max_days_fast").parent().children("span.styledselect").html($("#max_days_fast option:first-child").html());
							$("#max_days_fast option:first-child").prop("selected", true);
							$("#max_days_fast option").each(function(ind, el){
								if(parseInt(max_days_val) && parseInt($(el).val())>=parseInt(max_days_val))
									$(el).prop("disabled", true);
								else
									$(el).prop("disabled", false);
							});

							$(".max_days_fast.ui.dropdown .menu .item").each(function(ind, el){
								if(parseInt(max_days_val) && parseInt($(el).data('value'))>=parseInt(max_days_val)){
									$(el).addClass("disabled");
								}
								else{
									$(el).removeClass("disabled");
								}
							});

							if(max_days_val=="instant"){
								$("#enable_extra_fast").prop("checked", false);
								$("#enable_extra_fast").prop("disabled", true);
								$("#enable_extra_fast").parent().children(".checkbox").removeClass("checked");
								$("#enable_extra_fast").parent().children("span").append(" (DISABLED)");
								$("#max_days_fast").prop("disabled", true);
								$(".max_days_fast.ui.dropdown").addClass("disabled");
								$("input[name=extra_fast_price]").prop("disabled", true);
							}
							else{
								$("#enable_extra_fast").prop("disabled", false);
								$("#enable_extra_fast").html($("#enable_extra_fast").html().replace(" (DISABLED)",""));
								$("#max_days_fast").prop("disabled", false);
								$(".max_days_fast.ui.dropdown").removeClass("disabled");
								$("input[name=extra_fast_price]").prop("disabled", false);
							}
						}

						jQuery(document).ready(function(e) {
							check_delivery_time();

							$("#max_days").change(function(e){
								check_delivery_time();
							});<?php

							if(isset($_POST['job_cat']) && $_POST['job_cat']!=''){ ?>
								display_subcat(this,'<?php echo $_POST['job_cat']?>','<?php echo isset($_POST['subcat'])?$_POST['subcat']:""; ?>');
							<?php } ?>
						});
					</script>
                     <script>
					  
					     jQuery(document).ready(function($) {
							  var sub_fd = $('#fd_cat').html();
								$('#add_extra_sub').click(function(){
									$(this).parent().before('<div class="two fields">'+sub_fd+'</div>');
								$('.ui.dropdown').dropdown();
								});
						 });
					 </script>
					<script type="text/javascript">
						function delete_this_deliveryfile(id){
							$.ajax({
								method: 'get',
								url : '<?php echo get_bloginfo('url');?>/index.php/?_ad_delete_pid='+id,
								dataType : 'text',
								success: function (text) {
									$('#image_ss'+id).remove();
									$("#hidden_instant_file_uploader").removeClass('hidden');
								}
							});
						}
					</script>
	<!-- END SHOW ERRORS -->

					<?php do_action( 'wpj_add_extra_fields_post_page' ); ?>

	<!-- POST FORM -->
	                 <script>
					 //Form Validation
					   function formValidation(){
						   var subj_cat=$("select[name='job_cat[]']").val();
						   var subj_subcat=$("select[name='subcat[]']").val();
						   var othr=$("input[name='other_subcat[]']").val();
						  // alert(subj_subcat);
						   if((subj_cat=="") || (subj_subcat=="undefined") || (othr=="") || (subj_subcat=="null")){
                                if($('.errrs').length === 0){
									$("form.ui.form").before('<div class="errrs"><div class="newad_error">Error: Enter a Subject!</div></div>')
								} else{                            
							   $(".errrs").append('<div class="newad_error">Error: Enter a Subject!</div>');  
                             }
							   return false;
						   }
					   }
					 </script>
					<form class="ui form" method="post" enctype="multipart/form-data" action="<?php echo wpjobster_post_new_with_pid_stuff_thg($pid);?>" onSubmit="return formValidation();">

						<div class="field">
							<div class="two fields">
		<!-- JOB TITLE -->
								<div class="field instructions-popup">
									<label><?php echo __('Job Title', 'wpjobster'); ?></label>
									<input type="text" class="charlimit-jobtitle uz-listen1" name="job_title" value="<?php echo stripslashes( $ttl); ?>" cols="40" />
									<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
								</div>
								<?php echo wpj_get_popup( 'job_title_instructions' ); ?>
		<!-- END JOB TITLE -->

		<!-- JOB PRICE -->
							
		<!-- END JOB PRICE -->
							</div><!-- END ROW -->
                         <div class="fields two">
						     <div id="job_price_field" class="field instructions-popup" <?php echo $display; ?>>
									<label>
										<?php echo __('Job Price', 'wpjobster'); ?>
										<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
									</label>

									<?php
									$wpjobster_enable_dropdown_values   = get_option('wpjobster_enable_dropdown_values');
									$wpjobster_enable_free_input_box    = get_option('wpjobster_enable_free_input_box');
									$x = (isset($_POST['job_cost']) ? $_POST['job_cost'] : get_post_meta($pid,'price',true));

									global $current_user;
									$user_level = wpjobster_get_user_level( $current_user->ID );
									$min_job_amount = get_option('wpjobster_min_job_amount');

									if ( ! is_numeric( $min_job_amount ) || $min_job_amount == '' || $min_job_amount == '0' || $min_job_amount < 0 ) {
										$min_job_amount = 0;
									}
									$allowed_max_job_cost = get_option('wpjobster_level'.$user_level.'_max');

									if ( $wpjobster_subscription_max_job_price ) {
										$allowed_max_job_cost = $wpjobster_subscription_max_job_price;
									}
									if ( $wpjobster_enable_free_input_box == "yes" ) { ?>
									<script>
									$(document).ready(function(){
										var slect_prc =$('input[name=prc]:checked').val(); 
										  if(slect_prc=="negotiable"){
											   $(".post-new-price").hide();
											   $(".ngp_wrap").show();
										   }else{
											  $(".ngp_wrap").hide(); 
											  $(".post-new-price").show();
										   }
									$('.radio_change').on('change', function() {
										   var price_val=$('input[name=prc]:checked').val(); 
										   if(price_val=="negotiable"){
											   $(".post-new-price").hide();
											   $(".ngp_wrap").show();
										   }else{
											  $(".ngp_wrap").hide(); 
											  $(".post-new-price").show();
										   }
										});
									});
									</script>
                                        <div class="ui checkbox radio">
									      <input type="radio" class="grey_input radio_change" name="prc" id="fix_price" value="fix_price" <?php if(((isset($_POST['prc'])) &&($_POST['prc']=='fix_price')) || (!isset($_POST['prc'])) ){ ?> checked <?php }  ?>>
									         <label><span> Fixed</span></label>
								        </div>
										 <input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" name="job_cost" class="post-new-price uz-listen1" value="<?php echo $x; ?>" />
									   <div class="ui checkbox radio">
									      <input type="radio" class="grey_input radio_change" name="prc" id="nego_price" value="negotiable" <?php if((isset($_POST['prc'])) &&($_POST['prc']=='negotiable') ){ ?> checked <?php } ?>>
									         <label><span> Negotiable Range </span></label>
								        </div>
										<div class="ngp_wrap three fields" style="display:none;">
									        	<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_min_price" value="<?php if(isset($_POST['job_min_price'])){ echo $_POST['job_min_price']; } ?>" cols="40" />
												To
										    <input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_max_price" value="<?php if(isset($_POST['job_max_price'])){echo $_POST['job_max_price'];} ?>" cols="40" />
										</div>
									    
									<?php } elseif ( $wpjobster_enable_dropdown_values == "yes" ){
										echo wpjobster_get_variale_cost_dropdown('grey_input', $x);
									} else {
										echo '<div class="job-fixed-price">';
											echo wpjobster_get_show_price_classic(get_option('wpjobster_job_fixed_amount'));
										echo '</div>';
									}
									?>
								</div>
								<?php echo wpj_get_popup( 'job_price_instructions' ); ?>
                        </div>						 
							<div class="two fields" id="fd_cat">
		<!-- JOB CATEGORY -->
								<div class="field instructions-popup">
									<label><?php //echo __('Category', 'wpjobster'); ?>Subjects</label>
                                        <?php //print_r($cat);?>
									<?php
									if($_POST['job_cat'][0]==3){
										echo wpjobster_get_categories_clck("job_cat[]",
										  $_POST['job_cat'][0]
									, __('Select Category','wpjobster'), "ui dropdown new-post-category styledselect uz-listen2", 'onchange="display_subcat(this,this.value)"' );
									} else { 
									echo wpjobster_get_categories_clck("job_cat[]",
									!isset($_POST['job_cat']) ? (isset($cat)&& is_array($cat)&& isset($cat[0]->term_id) ? $cat[0]->term_id : "") : htmlspecialchars($_POST['job_cat'])
									, __('Select Category','wpjobster'), "ui dropdown new-post-category styledselect uz-listen2", 'onchange="display_subcat(this,this.value)"' );
									}
									?>
								</div>
								<?php echo wpj_get_popup( 'job_category_instructions' ); ?>
		<!-- END JOB CATEGORY -->
		         <?php //print_r($_POST);?>
		<!-- JOB SUBCATEGORY -->
								<div class="field subcat-field instructions-popup">
									<?php echo '<span id="sub_cats" class="post-new-subcat">';
										if(!empty($cat[1]->term_id)){
											$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[0]->term_id;
											$sub_terms2 = get_terms( 'job_cat', $args2 );
											$ret = '<select class="styledselect2 uz-listen2" name="subcat">';
											$ret .= '<option value="">'.__('Select Subcategory','wpjobster'). '</option>';
											$selected1 = $cat[1]->term_id;
											foreach ( $sub_terms2 as $sub_term2 ) {
												$sub_id2 = $sub_term2->term_id;
												$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';
											}
											$ret .= "</select>";
											echo $ret;
										}
										if($_POST['other_subcat']){
											echo '<input type="text" name="other_subcat[]" class="subcat_txt">';
										}
									echo '</span>'; ?>
								</div>
								<?php echo wpj_get_popup( 'job_subcategory_instructions' ); ?>
		<!-- END JOB SUBCATEGORY -->
							</div><!-- END ROW -->
							
						</div><!-- END FIELD(title, price, category, subcategory) -->
                    <div class="field">
									<a href="javascript:void(0);" id="add_extra_sub" class="cursor_pointer ">+ Add New Extra</a>
								</div> 
		<!-- JOB PACKAGES -->
						<?php $wpjobster_packages = get_option('wpjobster_packages_enabled');
						$user_level = wpjobster_get_user_level( $uid );
						$lvl_sts = get_option( 'wpjobster_get_level'.$user_level.'_packages' );

						wpj_get_subscription_info_path();
						$wpjobster_subscription_info = get_wpjobster_subscription_info( $uid );
						extract( $wpjobster_subscription_info );

						if ( $wpjobster_subscription_enabled == 'yes' ) {
							$lvl_sts = get_option( 'wpjobster_subscription_packages_'.$wpjobster_subscription_level );
						}

						if ( $wpjobster_packages == "yes" && $lvl_sts == 'yes' ) { ?>
							<div class="field">
								<div class="two fields">
									<div class="field instructions-popup">
										<label><?php echo __('Packages', 'wpjobster'); ?></label>

										<div class="post-new-job-slide-box">
											<div class="ui toggle checkbox">
												<input type="checkbox" name="packages" id="packages" value="yes" <?php echo isset( $_POST['packages'] ) && $_POST['packages'] == 'yes' ? 'checked' : ''; ?> />
												<label><?php echo __( 'Slide to enable', 'wpjobster' ); ?></label>
											</div>
										</div>
									</div>
									<?php echo wpj_get_popup( 'job_packages_instructions' ); ?>

									<div class="field"></div>
								</div><!-- END TWO FIELDS -->
							</div><!-- END FIELD -->
						<?php } ?>
		<!-- END JOB PACKAGES -->

		<!-- JOB PACKAGES TABLE -->
						<?php if ( $wpjobster_packages == "yes" ) { ?>
							<div class="field packages" <?php echo $display_table; ?>>
								<table class="ui celled definition table">
									<thead>
										<tr>
											<th></th>
											<th><?php echo __( 'BASIC', 'wpjobster'); ?></th>
											<th><?php echo __( 'STANDARD', 'wpjobster'); ?></th>
											<th><?php echo __( 'PREMIUM', 'wpjobster'); ?></th>
										</tr>
									</thead>

									<tbody>
										<tr>
											<td>
												<?php echo __( 'Package name', 'wpjobster'); ?>
											</td>

											<?php for( $i=0; $i<3; $i++ ) { ?>
												<td>
													<div class="ui input instructions-popup">
														<input class="package_name" name="package_name[]" maxlength="35" type="text" placeholder="<?php echo __( 'Name your package', 'wpjobster'); ?>" value="<?php echo isset( $_POST['package_name'][$i] ) ? $_POST['package_name'][$i] : ''; ?>">
													</div>
													<?php echo wpj_get_popup( 'job_package_name_instructions' ); ?>
												</td>
											<?php } ?>
										</tr>
										<tr>
											<td>
												<?php echo __( 'Package description', 'wpjobster'); ?>
											</td>

											<?php for( $i=0; $i<3; $i++ ) { ?>
												<td>
													<div class="instructions-popup">
														<textarea class="package_description" minlength="<?php echo get_option('wpjobster_characters_description_min'); ?>" maxlength="<?php echo get_option('wpjobster_characters_description_max'); ?>" name="package_description[]" type="text" placeholder="<?php echo __( 'Describe the details of your offering', 'wpjobster'); ?>"><?php echo isset( $_POST['package_description'][$i] ) ? $_POST['package_description'][$i] : ''; ?></textarea>
													</div>
													<?php echo wpj_get_popup( 'job_package_description_instructions' ); ?>
												</td>
											<?php } ?>
										</tr>
										<tr>
											<td>
												<?php echo __( 'Package delivery time', 'wpjobster'); ?>
											</td>

											<?php for( $i=0; $i<3; $i++ ) { ?>
												<td>
													<div class="instructions-popup">
														<?php $max_days = isset( $_POST['package_max_days'][$i] ) ? $_POST['package_max_days'][$i] : ''; ?>
														<select id="max_days" name="package_max_days[]" class="ui dropdown max-day-deliver styledselect uz-listen2">
															<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
																<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days ? ' selected="selected=" ' : ""); ?>>
																	<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
																</option>
															<?php } ?>
														</select>
													</div>
													<?php echo wpj_get_popup( 'job_package_delivery_time_instructions' ); ?>
												</td>
											<?php } ?>
										</tr>
										<tr>
											<td>
												<?php echo __( 'Package Revisions', 'wpjobster'); ?>
											</td>

											<?php for( $i=0; $i<3; $i++ ) { ?>
												<td>
													<div class="instructions-popup">
														<?php $revisions = isset( $_POST['package_revisions'][$i] ) ? $_POST['package_revisions'][$i] : ''; ?>
														<select name="package_revisions[]" class="ui dropdown styledselect">
															<?php for( $i_count=1; $i_count<=9; $i_count++){ ?>
																<option value="<?php echo $i_count ?>" <?php echo ($i_count == $revisions ? ' selected="selected=" ' : ""); ?>>
																	<?php echo $i_count; ?>
																</option>
															<?php } ?>
															<option <?php if ( $revisions == 'unlimited' ) echo 'selected'; ?> value="unlimited"><?php echo __( 'Unlimited', 'wpjobster' ); ?></option>
														</select>
													</div>
													<?php echo wpj_get_popup( 'job_package_revision_instructions' ); ?>
												</td>
											<?php } ?>
										</tr>
										<tr>
											<td>
												<?php echo __( 'Package price', 'wpjobster') . '&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')'; ?>
											</td>
											<?php for( $i=0; $i<3; $i++ ) { ?>
												<td class="pck-padd-left">
													<div class="ui labeled input instructions-popup">
														<div class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency_classic() ); ?></div>
														<input class="package_price" step="0.01" name="package_price[]" type="number" placeholder="<?php echo __( 'Insert package price', 'wpjobster'); ?>" value="<?php echo isset( $_POST['package_price'][$i] ) ? $_POST['package_price'][$i] : ''; ?>">
													</div>
													<?php echo wpj_get_popup( 'job_package_price_instructions' ); ?>
												</td>
											<?php } ?>
										</tr>
										<tr class="pck-repeater">
											<td>
												<div class="instructions-popup di">
													<input class="pck-inp-custom-name" name="pck-inp-custom-name[]" placeHolder="<?php echo __( 'Insert field name', 'wpjobster'); ?>" />
												</div>
												<i class="pck-icon-rem remove icon" style="display: inline;"></i>
												<?php echo wpj_get_popup( 'job_package_custom_field_name_instructions' ); ?>
											</td>

											<td class="pck-center">
												<div class="ui checkbox instructions-popup">
													<input type="hidden" name="pck-chk-value[basic][]" value="off" />
													<input type="checkbox" class="basic-checkbox" value="on" />
													<label></label>
												</div>
												<script type="text/javascript">
													jQuery( document ).ready(function($) {
														$(document).on("change", "input.basic-checkbox", function() {
															var value = $(this).is(":checked") ? $(this).val() : 'off';
															$(this).siblings("input[name='pck-chk-value[basic][]']").val(value);
														});
													});
												</script>
												<?php echo wpj_get_popup( 'job_package_custom_field_checklist_instructions' ); ?>
											</td>
											<td class="pck-center">
												<div class="ui checkbox instructions-popup">
													<input type="hidden" name="pck-chk-value[standard][]" value="off" />
													<input type="checkbox" class="standard-checkbox" value="on" />
													<label></label>
												</div>
												<script type="text/javascript">
													jQuery( document ).ready(function($) {
														$(document).on("change", "input.standard-checkbox", function() {
															var value = $(this).is(":checked") ? $(this).val() : 'off';
															$(this).siblings("input[name='pck-chk-value[standard][]']").val(value);
														});
													});
												</script>
												<?php echo wpj_get_popup( 'job_package_custom_field_checklist_instructions' ); ?>
											</td>
											<td class="pck-center">
												<div class="ui checkbox instructions-popup">
													<input type="hidden" name="pck-chk-value[premium][]" value="off" />
													<input type="checkbox" class="premium-checkbox" value="on" />
													<label></label>
												</div>
												<script type="text/javascript">
													jQuery( document ).ready(function($) {
														$(document).on("change", "input.premium-checkbox", function() {
															var value = $(this).is(":checked") ? $(this).val() : 'off';
															$(this).siblings("input[name='pck-chk-value[premium][]']").val(value);
														});
													});
												</script>
												<?php echo wpj_get_popup( 'job_package_custom_field_checklist_instructions' ); ?>
											</td>
										</tr>
									</tbody>
								</table>

								<!-- ADD NEW CUSTOM FIELD - LINK -->
								<div class="field">
									<a href="javascript:void(0);" id="add_custom_field_to_package" class="cursor_pointer>" ><?php _e("+ Add New Custom Field", "wpjobster"); ?></a>
								</div>
								<!-- END ADD NEW CUSTOM FIELD - LINK -->
							</div>
						<?php } ?>
		<!-- END JOB PACKAGES TABLE -->
                 								
		<!-- JOB DESCRIPTION -->
						<div id="job_description_field" class="field post-new-job-wrapper-x instructions-popup">
							<?php if(wpj_bool_option('wpjobster_allow_wysiwyg_job_description')){ ?>
								<!-- WYSIWYG TEXTAREA -->
								<div class="field input-block"><?php
									$max_chr_description = get_option( 'wpjobster_characters_description_max' ) ?: 1000; ?>
									<label><?php echo __('Description', 'wpjobster'); ?>

										<textarea id="job_description" rows="6" cols="45" class="lighter grey_input job-description-wysiwyg job-description-wysiwyg-style uz-listen1" name="job_description"><?php echo empty($_POST['job_description']) ? str_replace("<br />","",stripslashes($post->post_content)) : stripslashes($_POST['job_description']); ?></textarea>

										<div id="job_description_toolbar" class="job-description-wysiwyg-toolbar">
											<a data-wysihtml5-command="bold"><i class="bordered bold icon"></i></a>
											<a data-wysihtml5-command="italic"><i class="bordered italic icon"></i></a>
											<a data-wysihtml5-command="underline"><i class="bordered underline icon"></i></a>
											<a data-wysihtml5-command="insertUnorderedList"><i class="bordered unordered list icon"></i></a>
											<a data-wysihtml5-command="insertOrderedList"><i class="bordered ordered list icon"></i></a>
										</div>

										<div class="char-count lighter"><?php echo ' / ' . $max_chr_description . ' ' . __( 'Characters', 'wpjobster' ); ?></div>

										<script>
											jQuery(document).ready(function($){
												max_chr_description = '<?php echo $max_chr_description; ?>';
												wpj_js_description_args_allowed( max_chr_description );
											});
										</script>
									</label>
								</div>
								<!-- END WYSIWYG TEXTAREA -->
							<?php }else{ ?>
								<!-- SIMPLE TEXTAREA -->
								<div class="field">
									<label><?php echo __('Description', 'wpjobster'); ?></label>
									<textarea rows="6" class="charlimit-jobdescription uz-listen1" name="job_description"><?php echo empty($_POST['job_description']) ? str_replace("<br />","",stripslashes($post->post_content)) : stripslashes($_POST['job_description']); ?></textarea>
									<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
								</div>
								<!-- END SIMPLE TEXTAREA -->
							<?php } ?><!-- END IF TEXTAREA TYPE -->
						</div><!-- END FIELD -->

						<?php echo wpj_get_popup( 'job_description_instructions' ); ?>

		<!-- END JOB DESCRIPTION -->

		<!-- JOB BUYER INSTRUCTIONS -->
						<div class="field instructions-popup">
							<?php $instruction_box = get_post_meta($pid, 'instruction_box', true); ?>
							<label><?php echo __('Instructions to buyer', 'wpjobster'); ?></label>
							<textarea rows="6" class="charlimit-jobinstruction uz-listen1"  name="instruction_box"><?php echo empty($_POST['instruction_box']) ? str_replace("<br />","",trim(stripslashes($instruction_box))) : htmlspecialchars(stripslashes($_POST['instruction_box'])); ?></textarea>
							<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
						</div>
						<?php echo wpj_get_popup( 'job_instructions_instructions' ); ?>
		<!-- END JOB BUYER INSTRUCTIONS -->

						<div class="field">
							<div class="two fields">
		<!-- JOB TAGS -->
								<div class="field input-block instructions-popup">
									<input type="hidden" name="job_location_cat" value="<?php get_user_meta($current_user->ID,'country',true) ?>">
									<?php
									$job_tags = '';
									$t = wp_get_post_tags($pid);
									$i = 0;
									$i_separator = '';
									foreach( $t as $tag ) {
										$job_tags .= $i_separator . $tag->name;
										$i++;
										if ($i > 0) { $i_separator = ', '; }
									}
									?>
									<label><?php echo __('Tags', 'wpjobster'); ?> <span class="lighter">(<?php _e('separate your tags by comma','wpjobster'); ?>)</span></label>
									<input type="text" id="job_tags" class="uz-listen1"  name="job_tags" value="<?php echo $job_tags; ?>" />
								</div>
								<?php echo wpj_get_popup( 'job_tags_instructions' ); ?>
		<!-- END JOB TAGS -->

		<!-- JOB LET'S MEET -->
								<div class="field instructions-popup">
									<?php $wpjobster_lets_meet = get_option('wpjobster_lets_meet');
									if($wpjobster_lets_meet == "yes"){ ?>

										<label><?php echo __('Let\'s Meet', 'wpjobster'); ?></label>

										<div class="post-new-job-slide-box">
											<div class="ui toggle checkbox">
												<input type="checkbox" name="lets_meet" id="lets_meet" value="yes" <?php echo (get_post_meta($pid, 'lets_meet', true) == "yes") ? 'checked' : ''; ?> />
												<label><?php echo __( 'Slide to enable', 'wpjobster' ); ?></label>
											</div>
										</div>
									<?php } ?>
								</div>
								<?php echo wpj_get_popup( 'job_lets_meet_instructions' ); ?>
		<!-- END JOB LET'S MEET -->
							</div><!-- END TWO FIELDS -->
						</div><!-- END FIELD -->

						<div class="field">
							<div class="three fields">
		<!-- JOB LOCATION -->
								<?php $wpjobster_location_display_condition = get_option('wpjobster_location_display_condition');
								if ( $wpjobster_location_display_condition == 'ifchecked' ) { ?>
									<div class="field instructions-popup" id="location-input" style="display:none">
								<?php } else { ?>
									<div class="field instructions-popup">
								<?php }
										if ($wpjobster_location_display_condition == 'always' || $wpjobster_location_display_condition == 'ifchecked') {
											$wpjobster_location = get_option('wpjobster_location');
											if($wpjobster_location == "yes"){ ?>
													<label><?php echo __('Location', 'wpjobster'); ?></label>
													<input class="uz-listen1" type="text" data-replaceplaceholder="<?php _e('Select a valid location','wpjobster') ?>" placeholder="<?php _e('Location','wpjobster') ?>" id="location_input"
															value="<?php echo get_post_meta($pid, 'location_input', true); ?>" name="location_input">
													<!-- SEND LATITUDE AND LONGITUDE -->
													<input id="lat" type="hidden" name="lat" value="<?php echo get_post_meta($pid, 'lat', true); ?>">
													<input id="long" type="hidden" name="long" value="<?php echo get_post_meta($pid, 'long', true); ?>">
											<?php }
										} ?>
								</div>
								<?php echo wpj_get_popup( 'job_location_instructions' ); ?>
		<!-- END JOB LOCATION -->

		<!-- JOB DISTANCE -->
								<?php $wpjobster_distance_display_condition = get_option('wpjobster_distance_display_condition');
								if ( $wpjobster_distance_display_condition == 'ifchecked' ) { ?>
									<div class="field instructions-popup" id="distance-input" style="display:none">
								<?php } else { ?>
									<div class="field instructions-popup">
								<?php }
										if ($wpjobster_distance_display_condition == 'always' || $wpjobster_distance_display_condition == 'ifchecked') { ?>
											<label>
												<?php
												if (get_option('wpjobster_locations_unit') == 'miles') {
													$radius_placeholder = __("miles", "wpjobster");
												} else {
													$radius_placeholder = __("kilometers", "wpjobster");
												}
												echo __('Distance', 'wpjobster') . '<span class="lighter">&nbsp;(' . $radius_placeholder . ')</span>'; ?>
											</label>

											<input class="uz-listen2" type="number" placeholder="<?php _e('Distance','wpjobster') ?>" id="distance_input" value="<?php echo get_post_meta($pid, 'distance_input', true); ?>" name="distance_input">
										<?php } ?>
								</div>
								<?php echo wpj_get_popup( 'job_distance_instructions' ); ?>
		<!-- END JOB DISTANCE -->

		<!-- DISPLAY MAP -->
								<?php if ( $wpjobster_location_display_condition == 'ifchecked' ) { ?>
									<div class="field instructions-popup" id="map-checkbox" style="display:none">
								<?php } else { ?>
									<div class="field instructions-popup">
								<?php }
										$wpjobster_location_display_map_user_choice = get_option('wpjobster_location_display_map_user_choice');
										if ($wpjobster_location_display_map_user_choice == 'yes' && (($wpjobster_location_display_condition == 'ifchecked' && $wpjobster_lets_meet == "yes") || $wpjobster_location_display_condition == 'always') ) { ?>
											<label><?php echo __('Display Map', 'wpjobster'); ?></label>
											<div class="post-new-job-check-map">
												<div class="ui checkbox">
													<input type="checkbox" name="display_map" id="display_map" value="yes" <?php echo (get_post_meta($pid, 'display_map', true) == "yes") ? 'checked' : ''; ?>/>
													<label><?php echo __( 'Check to enable', 'wpjobster' ); ?></label>
												</div>
											</div>
										<?php } ?>
								</div>
								<?php echo wpj_get_popup( 'job_display_map_instructions' ); ?>
		<!-- END DISPLAY MAP -->
							</div><!-- END THREE FIELDS -->
						</div><!-- END FIELD -->

						<div class="field">
							<div class="three fields">
		<!-- JOB MAX DAYS TO DELIVER -->
								<div id="job_delivery_time_field" class="field instructions-popup" <?php echo $display; ?>>
									<?php
									$wpjobster_enable_shipping = get_option('wpjobster_enable_shipping');
									$wpjobster_enable_instant_deli = get_option('wpjobster_enable_instant_deli');
									?>

									<label>
										<?php echo __('Max Days to Deliver', 'wpjobster');
										$max_days = (!isset($max_days) || empty($max_days) ? get_post_meta($pid,'max_days',true) : $max_days );
										$max_days = ( empty($max_days) && isset($_POST['max_days']) ? $_POST['max_days'] : $max_days );
										if($max_days=='instant'){
											$instant_file_class = '';
										}else{
											$instant_file_class ='hidden';
										} ?>
									</label>

									<select id="max_days" name="max_days" class="ui dropdown max-day-deliver styledselect uz-listen2" >
										<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
										<?php if($wpjobster_enable_instant_deli != "no"){ ?>
											<option <?php echo ($max_days=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
										<?php } ?>
										<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
											<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days?' selected="selected=" ':""); ?>>
												<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
											</option>
										<?php } ?>
									</select>
								</div>
								<?php echo wpj_get_popup( 'job_delivery_time_instructions' ); ?>
		<!-- END JOB MAX DAYS TO DELIVER -->

		<!-- JOB INSTANT DELIVERY -->
								<?php if($wpjobster_enable_instant_deli != "no"){ ?>
									<div id="instant-file-section" class="field instructions-popup instant-delivery-file <?php echo $instant_file_class;?>">
										<?php echo "<label>".__('Instant Delivery File', 'wpjobster')."</label>"; ?>
										<?php wpjobster_theme_attachments_uploader_html5($secure=1,"file_upload_instant_job_attachments", "hidden_files_instant_job_attachments", "instant_delivery"); ?>
									</div>
									<?php echo wpj_get_popup( 'job_instant_delivery_time_instructions' ); ?>
								<?php } ?>
		<!-- END JOB INSTANT DELIVERY -->

		<!-- JOB REQUIRES SHIPPING -->
								<div class="field instructions-popup">
									<?php if($wpjobster_enable_shipping == "yes"){ ?>
										<label>
											<?php echo __('Requires shipping?', 'wpjobster'); ?><?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
										</label>

										<input type="number" step="any" min="0" size="5" class="uz-listen1" placeholder="<?php echo __( 'optional', 'wpjobster' ); ?>" name="shipping" value="<?php echo (empty($shipping) ? get_post_meta($pid,'shipping',true) : $shipping ); ?>" />
									<?php } ?>
								</div>
								<?php echo wpj_get_popup( 'job_shipping_instructions' ); ?>
		<!-- END JOB REQUIRES SHIPPING -->
							</div><!-- END THREE FIELDS -->
						</div><!-- END FIELD -->

		<!-- JOB COVER IMAGE -->
						<div class="field instructions-popup">
							<?php if(get_option('wpjobster_enable_job_cover') == 'yes'){ ?>

								<label><?php echo __('Cover Image', 'wpjobster'); ?></label>

								<?php if ( wpjobster_get_preferred_uploader() === 'html5fileupload' ) {
									wpjobster_theme_cover_image_html5( $pid );
								} else {
									wpjobster_dropzone_cover_uploader( $pid );
								}
							} ?>
						</div>
						<?php echo wpj_get_popup( 'job_cover_image_instructions' ); ?>
		<!-- END JOB COVER IMAGE -->

		<!-- JOB IMAGE -->
						<div class="field instructions-popup">
							<label><?php echo __('Images', 'wpjobster'); ?></label>

							<?php if ( wpjobster_get_preferred_uploader() === 'html5fileupload' ) {
								wpjobster_theme_job_images_html5( $pid );
							} else {
								wpjobster_dropzone_image_uploader( $pid );
							} ?>
						</div>
						<?php echo wpj_get_popup( 'job_images_instructions' ); ?>
		<!-- JOB IMAGE -->

		<!-- JOB PREVIEW -->
						<?php $wpjobster_attachments_enable = get_option('wpjobster_job_attachments_enabled');
						if ($wpjobster_attachments_enable == "yes"){ ?>
							<div class="field instructions-popup">
								<label><?php echo __('Job Preview', 'wpjobster'); ?></label>
								<?php wpjobster_theme_attachments_uploader_html5( $secure=1, "file_upload_preview_job_attachments", "hidden_files_preview_job_attachments", "work_preview"); ?>
							</div>
							<?php echo wpj_get_popup( 'job_preview_instructions' );
						} ?>
		<!-- END JOB PREVIEW -->

		<!-- JOB VIDEO -->
						<div class="field instructions-popup">
							<label class="youtube-link">
								<?php do_action('wpjobster_before_youtube_links');

								global $current_user;
								$current_user = wp_get_current_user();
								$uid = $current_user->ID;
								$user_level = wpjobster_get_user_level($uid);
								$sts = get_option('wpjobster_level'.$user_level.'_vds');
								if($sts > 3) $sts = 3;

								for($i=1;$i<=$sts;$i++) {

									_e('Youtube Video Link','wpjobster'); ?>
							</label>

									<input type="text" id="youtube_link" name="youtube_link<?php echo $i; ?>" class="uz-listen1"
									value="<?php echo get_post_meta($pid, 'youtube_link'.$i, true); ?>" /><div id="ytlInfo"></div>

								<?php } ?>
						</div>
						<?php echo wpj_get_popup( 'job_video_instructions' ); ?>
		<!-- END JOB VIDEO -->

						<?php do_action('wpjobster_after_youtube_links'); ?>

		<!-- JOB AUDIO -->
						<div class="field instructions-popup">
							<?php $wpjobster_audio_enable = get_option('wpjobster_audio');
							if ($wpjobster_audio_enable == "yes"){ ?>
								<label><?php echo __('Audios', 'wpjobster'); ?></label>
								<div class="audio-upload-space"></div>
								<?php wpjobster_theme_job_audios_html5($pid);
							} ?>
						</div>
						<?php echo wpj_get_popup( 'job_audio_instructions' ); ?>
		<!-- END JOB AUDIO -->

		<!-- JOB EXTRA FAST DELIVERY -->
						<?php
						$wpjobster_enable_extra = get_option('wpjobster_enable_extra');
						$wpjobster_enable_multiples = get_option('wpjobster_enable_multiples');
						$allowed_max_extra_price = get_option('wpjobster_level'.$user_level.'_max_extra');

						if($wpjobster_subscription_max_extra_price)$allowed_max_extra_price = $wpjobster_subscription_max_extra_price;
						$wpjobster_enable_extra_fast_delivery = get_option('wpjobster_enable_extra_fast_delivery');
						$hidden_extra_fast_delivery = ' hidden ';

						if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
							$wpjobster_enable_extra_fast_delivery = 'yes';
						}

						if( $wpjobster_enable_extra_fast_delivery !='no' ) {
							$extra_fast_delivery = 'yes';
							if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
								$extra_fast_delivery = 'yes'; // override only if subscription extra available
							}
							if( $extra_fast_delivery == 'yes' ) {
								$hidden_extra_fast_delivery = ' ';
							}
						}
						?>

						<div class="cf p20t bot_bord <?php echo $hidden_extra_fast_delivery; ?>" >
							<!-- EXTRA FAST DELIVERY CHECKBOX -->
							<div class="field instructions-popup">
								<div class="ui checkbox">
									<input type="checkbox" class="grey_input" name="enable_extra_fast" id="enable_extra_fast" value="<?php echo stripslashes(get_post_meta($pid, 'extra_fast_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra_fast_enabled', true)) ? 'checked' : ''; ?> />
									<label><?php echo '<span> '.__("Extra fast delivery","wpjobster").'</span>'; ?></label>
								</div>
							</div>
							<?php echo wpj_get_popup( 'job_extra_fast_delivery_enable_instructions' ); ?>

							<div class="two fields">
								<!-- EXTRA FAST DELIVERY PRICE -->
								<div class="field lock-field instructions-popup">
									<label>
										<?php _e('Price','wpjobster'); ?>
										<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
									</label>
									<input class="grey_input uz-listen1 price-input" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra_fast_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra_fast_price', true)); ?>" />
								</div>
								<?php echo wpj_get_popup( 'job_extra_fast_delivery_price_instructions' ); ?>
								<!-- END EXTRA FAST DELIVERY PRICE -->

								<!-- EXTRA FAST DELIVERY MAX DAYS -->
								<div class="field lock-field instructions-popup">
									<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>
									<select id="max_days_fast max-days-input" name="max_days_fast" class="grey_input styledselect uz-listen3 max-day-deliver max_days_fast ui dropdown">
										<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
										<?php if($wpjobster_enable_instant_deli != "no"){ ?>
											<option <?php echo ($max_days_fast=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
										<?php } ?>
										<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
											<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_fast?' selected="selected=" ':""); ?>>
											<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
											</option>
										<?php } ?>
									</select>
								</div>
								<?php echo wpj_get_popup( 'job_extra_fast_delivery_days_to_deliver_instructions' ); ?>
								<!-- END EXTRA FAST DELIVERY MAX DAYS -->
							</div>
						</div>
		<!-- END JOB EXTRA FAST DELIVERY -->

		<!-- JOB ADDITIONAL REVISION -->
						<?php
						$wpjobster_enable_extra_additional_revision = get_option('wpjobster_enable_extra_additional_revision');
						$hidden_extra_additional_revision = ' hidden ';
						if( $wpjobster_subscription_additional_revision == 'yes' ) {
							$wpjobster_enable_extra_additional_revision = 'yes';
						}
						if( $wpjobster_enable_extra_additional_revision !='no' ) {
							$extra_additional_revision = 'yes';
							if( $wpjobster_subscription_additional_revision == 'yes' ) {
								$extra_additional_revision = 'yes'; // override only if subscription extra available
							}
							if( $extra_additional_revision == 'yes' ) {
								$hidden_extra_additional_revision = ' ';
							}
						}
						?>

						<div class="cf p20t bot_bord <?php echo $hidden_extra_additional_revision; ?>">
							<!-- ADDITIONAL REVISION CHECKBOX -->
							<div class="field instructions-popup">
								<div class="ui checkbox">
									<input type="checkbox" class="grey_input" name="enable_extra_revision" id="enable_extra_revision" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_enabled', true)); ?>"
											<?php echo (get_post_meta($pid, 'extra_revision_enabled', true)) ? 'checked' : ''; ?>/>
									<label><?php echo '<span> '.__("Additional revision","wpjobster").'</span>'; ?></label>
								</div>
							</div>
							<?php echo wpj_get_popup( 'job_additional_revision_enable_instructions' ); ?>
							<!-- END ADDITIONAL REVISION CHECKBOX -->

							<div class="three fields">
								<!-- ADDITIONAL REVISION PRICE -->
								<div class="field instructions-popup">
									<label>
										<?php _e('Price','wpjobster'); ?>
										<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
									</label>

									<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra_revision_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_price', true)); ?>" />
								</div>
								<?php echo wpj_get_popup( 'job_additional_revision_price_instructions' ); ?>
								<!-- END ADDITIONAL REVISION PRICE -->

								<!-- ADDITIONAL REVISION MAX DAYS -->
								<div class="field instructions-popup">
									<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>

									<select id="max_days_revision" name="max_days_revision" class="grey_input styledselect max-day-deliver uz-listen3 ui dropdown">
										<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
										<?php if($wpjobster_enable_instant_deli != "no"){ ?>
											<option <?php echo ($max_days_revision=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
										<?php } ?>
										<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
											<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_revision?' selected="selected=" ':""); ?>>
											<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
											</option>
										<?php } ?>
									</select>
								</div>
								<?php echo wpj_get_popup( 'job_additional_revision_days_to_deliver_instructions' ); ?>
								<!-- END ADDITIONAL REVISION MAX DAYS -->

								<!-- ADDITIONAL REVISION MULTIPLE -->
								<?php if( $wpjobster_enable_multiples=='yes' ){ ?>
									<div class="field multiple-box instructions-popup">
										<div class="ui checkbox">
											<input type="checkbox" class="grey_input" name="enable_multiples_revision" id="enable_multiples_revision" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_multiples_enabled', true)); ?>"
														<?php echo (get_post_meta($pid, 'extra_revision_multiples_enabled', true)) ? 'checked' : ''; ?>/>
											<label><?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?></label>
										</div>
									</div>
									<?php echo wpj_get_popup( 'job_additional_revision_multiples_instructions' ); ?>
								<?php } ?>
								<!-- END ADDITIONAL REVISION MULTIPLE -->
							</div><!-- END THREE FIELDS -->
						</div>
		<!-- JOB ADDITIONAL REVISION -->

		<!-- JOB EXTRA -->
						<?php
						if($wpjobster_subscription_noof_extras){
							$wpjobster_enable_extra = 'yes';
						}

						if($wpjobster_enable_extra != "no"){
							$sts = get_option('wpjobster_get_level'.$user_level.'_extras');

							if($wpjobster_subscription_noof_extras) $sts = $wpjobster_subscription_noof_extras;
							$extras_allowed = $sts;
							if ( empty( $sts ) ) $sts = 10;

							if ( $sts > 0 ) { ?>
								<div id="all_extras">
									<?php $total_extra_displayed=0;
									for($i=1; $i<=$sts; $i++){
										if(1<=(int)get_post_meta($pid, 'extra'.$i.'_price', true) || $i==1){
											$total_extra_displayed++;
											$class ='';
										}else{
											$class = 'hidden';
										} ?>

										<div id="extra_<?php echo $i; ?>" class="cf p20t <?php echo $class;?> bot_bord">
											<!-- EXTRA CHECKBOX -->
											<div class="field instructions-popup">
												<div class="ui checkbox">
													<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $i; ?>" id="enable_extra_<?php echo $i; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)); ?>"
														<?php echo (get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
													<label><?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?></label>
												</div>
											</div>
											<?php echo wpj_get_popup( 'job_extra_enable_instructions' ); ?>
											<!-- END EXTRA CHECKBOX -->

											<!-- EXTRA DESCRIPTION -->
											<div class="field instructions-popup">
												<label><?php _e('Description','wpjobster'); ?></label>
												<textarea class="grey_input one_line charlimit-extradescription uz-listen2" name="extra<?php echo $i; ?>_content" cols="40" rows="2"><?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_content', true)); ?></textarea>
												<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
											</div>
											<?php echo wpj_get_popup( 'job_extra_description_instructions' ); ?>
											<!-- END EXTRA DESCRIPTION -->

											<div class="three fields">
												<!-- EXTRA PRICE -->
												<div class="field instructions-popup">
													<label>
														<?php _e('Price','wpjobster'); ?>
														<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
													</label>

													<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $i; ?>_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_price', true)); ?>" />
												</div>
												<?php echo wpj_get_popup( 'job_extra_price_instructions' ); ?>
												<!-- END EXTRA PRICE -->

												<!-- EXTRA MAX DAYS -->
												<div class="field instructions-popup">
													<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>
													<select id="max_days_<?php echo $i; ?>" name="max_days_<?php echo $i; ?>" class="grey_input max-day-deliver styledselect uz-listen3 ui dropdown">
														<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
														<?php if($wpjobster_enable_instant_deli != "no"){ ?>
															<option <?php echo ($max_days_ex[$i]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
														<?php } ?>
														<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
															<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_ex[$i]?' selected="selected=" ':""); ?>>
															<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
															</option>
														<?php } ?>
													</select>
												</div>
												<?php echo wpj_get_popup( 'job_extra_days_to_deliver_instructions' ); ?>
												<!-- END EXTRA MAX DAYS -->

												<!-- EXTRA MULTIPLES -->
												<?php if($wpjobster_enable_multiples=='yes'){ ?>
													<div class="field multiple-box instructions-popup">
														<div class="ui checkbox">
															<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $i; ?>" id="enable_multiples_<?php echo $i; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_enabled', true)); ?>"
															<?php echo (get_post_meta($pid, 'extra'.$i.'_enabled', true)) ? 'checked' : ''; ?>/>
															<label><?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?></label>
														</div>
													</div>
													<?php echo wpj_get_popup( 'job_extra_multiples_instructions' );
												} ?>
												<!-- END EXTRA MULTIPLES -->

												<!-- DELETE EXTRA - LINK -->
												<?php if(get_post_meta($pid, 'extra'.$i.'_price', true) !='') { ?>
													<div class="pdd29t">
														<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $i?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
													</div>
												<?php } ?>
												<!-- END DELETE EXTRA - LINK -->

											</div><!-- END THREE FIELDS -->
										</div><!-- END <div id="extra_<?php //echo $i; ?>" class="cf p20t <?php //echo $class;?> bot_bord"> -->
									<?php } // END for($i=1;$i<=$sts;$i++)

									for($j=$i; $j<=10; $j++){
										if(1<=(int)get_post_meta($pid, 'extra'.$j.'_price', true)){
											$total_extra_displayed++; ?>
											<div class="cf p20t bot_bord" id="extra_<?php echo $j; ?>">
												<!-- EXTRA CHECKBOX -->
												<div class="cf p10b">

													<div class="uz-listenh">
														<p class="">
															<label>
																<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $j; ?>" id="enable_extra_<?php echo $j; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)); ?>"
																	<?php echo (get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
																<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
															</label>
														</p>

														<!-- INTRUCTIONS FOR ENABLE EXTRA -->
														<div class="uz-suggesth">
															<?php if ( get_field( 'job_extra_enable_instructions', 'options' ) ) {
																the_field( 'job_extra_enable_instructions', 'options' );
															} elseif ( current_user_can( 'manage_options' ) ) {
																_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
															} ?>
														</div>
														<!-- END INTRUCTIONS FOR ENABLE EXTRA -->
													</div>
													<!-- END EXTRA CHECKBOX -->

													<!-- EXTRA DESCRIPTION -->
													<div class=""><?php _e('Description','wpjobster'); ?>
														<p class="lighter">
															<textarea class="grey_input one_line charlimit-extradescription uz-listen2" name="extra<?php echo $j; ?>_content" cols="40" rows="2"><?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_content', true)); ?></textarea>
															<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
														</p>
													</div>

													<!-- INSTRUCTIONS FOR EXTRA DESCRIPTION -->
													<div class="uz-suggest2">
														<?php if ( get_field( 'job_extra_description_instructions', 'options' ) ) {
															the_field( 'job_extra_description_instructions', 'options' );
														} elseif ( current_user_can( 'manage_options' ) ) {
															_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
														} ?>
													</div>
													<!-- END INSTRUCTIONS FOR EXTRA DESCRIPTION -->

												</div><!-- END <div class="cf p10b"> -->
												<!-- END EXTRA DESCRIPTION -->

												<div class="row cf">
													<!-- EXTRA PRICE -->
													<div class="one-fifth pdd10r left">
														<?php _e('Price','wpjobster');
														echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
														<p class="lighter">
															<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $j; ?>_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_price', true)); ?>" />
														</p>
													</div>
													<!-- END EXTRA PRICE -->

													<!-- EXTRA MAX DAYS TO DELIVER -->
													<div class="two-fifths pdd10l left"><?php echo __('Max Days to Deliver', 'wpjobster'); ?>
														<div class="grey_select">
															<span class="left w100 relative">
																<select id="max_days_<?php echo $j; ?>" name="max_days_<?php echo $j; ?>" class="grey_input max-day-deliver styledselect uz-listen3" >
																	<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
																	<?php if($wpjobster_enable_instant_deli != "no"){ ?>
																		<option <?php echo ($max_days_ex[$j]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
																	<?php } ?>
																	<?php for($j_count=1;$j_count<=get_option( 'wpjobster_job_max_delivery_days' );$j_count++){ ?>
																		<option value="<?php echo $j_count ?>" <?php echo ($j_count==$max_days_ex[$j]?' selected="selected=" ':""); ?>>
																		<?php echo sprintf( _n( '%d day', '%d days',$j_count, 'wpjobster' ), $j_count);?>
																		</option>
																	<?php } ?>
																</select>
															</span>
														</div>
													</div>
													<!-- END EXTRA MAX DAYS TO DELIVER -->

													<!-- EXTRA MULTIPLES -->
													<?php if($wpjobster_enable_multiples=='yes'){ ?>
														<div class="one-fifth pdd10l pdd29t left uz-listenh">
															<p class="lighter">
																<label>
																	<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $j; ?>" id="enable_multiples_<?php echo $j; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_enabled', true)); ?>"
																		<?php echo (get_post_meta($pid, 'extra'.$j.'_enabled', true)) ? 'checked' : ''; ?>/>
																	<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
																</label>
															</p>
															<div class="uz-suggesth">
																<?php if ( get_field( 'job_extra_multiples_instructions', 'options' ) ) {
																	the_field( 'job_extra_multiples_instructions', 'options' );
																} elseif ( current_user_can( 'manage_options' ) ) {
																	_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
																} ?>
															</div>
														</div>
													<?php } ?>
													<!-- END EXTRA MULTIPLES -->

													<!-- DELETE EXTRA - LINK -->
													<?php if(get_post_meta($pid, 'extra'.$j.'_price', true) !='') { ?>
														<div class="pdd29t">
															<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $j?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
														</div>
													<?php } ?>
													<!-- END DELETE EXTRA - LINK -->

													<!-- INSTRUCTIONS FOR EXTRA PRICE -->
													<div class="uz-suggest1">
														<?php if ( get_field( 'job_extra_price_instructions', 'options' ) ) {
															the_field( 'job_extra_price_instructions', 'options' );
														} elseif ( current_user_can( 'manage_options' ) ) {
															_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
														} ?>
													</div>
													<!-- END INSTRUCTIONS FOR EXTRA PRICE -->

													<!-- INSTRUCTIONS FOR EXTRA MAX DAYS TO DELIVER -->
													<div class="uz-suggest3">
														<?php if ( get_field( 'job_extra_days_to_deliver_instructions', 'options' ) ) {
															the_field( 'job_extra_days_to_deliver_instructions', 'options' );
														} elseif ( current_user_can( 'manage_options' ) ) {
															_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
														} ?>
													</div>
													<!-- END INSTRUCTIONS FOR EXTRA MAX DAYS TO DELIVER -->
												</div><!-- END <div class="row cf"> -->
											</div><!-- END <div class="cf p20t bot_bord" id="extra_<?php //echo $j; ?>"> -->
										<?php } // END if(1<=(int)get_post_meta($pid, 'extra'.$j.'_price', true)){
									} // END for($j=$i;$j<=10  ;$j++){ ?>
								</div><!-- END <div id="all_extras"> -->

								<!-- ADD NEW EXTRA - LINK -->
								<div class="field">
									<a href="javascript:void(0);" id="add_extra" class="cursor_pointer <?php echo ($extras_allowed<=$total_extra_displayed)?'hidden':'';?>" ><?php _e("+ Add New Extra", "wpjobster"); ?></a>
								</div>
								<!-- END ADD NEW EXTRA - LINK -->

								<!-- ADD/DELETE EXTRA - JS -->
								<script>
									total_extra_displayed = parseInt('<?php echo $total_extra_displayed;?>');
									extras_allowed = parseInt('<?php echo $extras_allowed;?>');

									jQuery(document).ready(function($){
										$("#add_extra").click(function(){
											total_extra_displayed = total_extra_displayed+1;
											$("#extra_"+total_extra_displayed).removeClass('hidden');
											if(extras_allowed==total_extra_displayed){
												$("#add_extra").addClass("hidden");
											}
										});
									});

									extras_allowed = '<?php echo $extras_allowed; ?>';

									delete_extras =function(){
										extr_number = $(this).attr('data-rel');

										$.ajax({
											method: 'get',
											url : '<?php echo get_bloginfo('url');?>/index.php/?_extra_delete_pid=<?php echo $pid;?>&extra_no='+extr_number,
											dataType : 'text',
											success: function (text) {
												result_txt = JSON.parse(text);

												if(result_txt.status=='done'){
													jQuery("#all_extras").html(result_txt.html);
													jQuery(".delete_extra").click(delete_extras);
													extradescription_charlimit();
													total_extra_displayed = result_txt.total_extra_displayed;
													if(extras_allowed>total_extra_displayed){
														$("#add_extra").removeClass("hidden");
													}else{
														$("#add_extra").removeClass("hidden");
														$("#add_extra").addClass("hidden");
													}
													if(result_txt.more_extras=='no'){
														more_extras='no';
													}
												}
											} // END success
										}); // END ajax
									}; // END delete_extras function

									jQuery(document).ready(function(){
										jQuery(".delete_extra").click(delete_extras);
									});

								</script>
								<!-- END ADD/DELETE EXTRA - JS -->
							<?php } // END if($sts > 0)
						} // END if($wpjobster_enable_extra != "no") ?>
		<!-- END JOB EXTRA -->

		<!-- JOB TERMS OF SERVICES -->
						<?php
						$wpjobster_tos_page_link = get_option("wpjobster_tos_page_link");
						$wpjobster_tos_type = get_option("wpjobster_tos_type");

						if ( trim( $wpjobster_tos_page_link )!='' && $wpjobster_tos_type != 'disabled' ) { ?>
							<div class="field uz-form">

								<div class="terms-of-services"><?php _e("Terms of Service", 'wpjobster'); ?></div>

								<?php if ( $wpjobster_tos_type=='show_on_page' ) {
									$linkpage_id = url_to_postid( $wpjobster_tos_page_link );
									$post = get_page($linkpage_id);
									$link_page_post = qtrans_use(qtrans_getLanguage(), $post->post_content,false); ?>
									<div class="terms-of-services-box">
										<?php if( $link_page_post == '[wpjobster_theme_post_new]' ){
											echo '';
										} else {
											echo wpautop( $link_page_post );
										} ?>
									</div>
								<?php } // END if($wpjobster_tos_type=='show_on_page') ?>

								<div class="ui checkbox">
									<input type="checkbox" name="i_agree" value="1">
									<label>
										<p><?php _e("I agree to the ", 'wpjobster'); ?><a class="terms-services" href="<?php echo $wpjobster_tos_page_link;?>" class="" target="_blank"><?php _e("Terms of Service", 'wpjobster'); ?></a>.</p>
									</label>
								</div>
							</div><!-- END FIELD -->

						<?php } // END if(trim($wpjobster_tos_page_link)!='' && $wpjobster_tos_type!='disabled') ?>
		<!-- END JOB TERMS OF SERVICES -->

						<?php
						$characters_jobtitle_max = get_option("wpjobster_characters_jobtitle_max");
						$characters_jobtitle_max = (empty($characters_jobtitle_max)|| $characters_jobtitle_max==0)?80:$characters_jobtitle_max;
						$wpjobster_characters_description_max = get_option("wpjobster_characters_description_max");
						$wpjobster_characters_description_max = (empty($wpjobster_characters_description_max)|| $wpjobster_characters_description_max==0)?1000:$wpjobster_characters_description_max;
						$wpjobster_characters_instructions_max = get_option("wpjobster_characters_instructions_max");
						$wpjobster_characters_instructions_max = (empty($wpjobster_characters_instructions_max)|| $wpjobster_characters_instructions_max==0)?350:$wpjobster_characters_instructions_max;
						?>

						<!-- CHARACTER LIMIT -->
						<script>
							jQuery(document).ready(function($) {
								jQuery(".charlimit-jobtitle").counted({count:<?php echo $characters_jobtitle_max;?>});
								jQuery(".charlimit-jobdescription").counted({count:<?php echo $wpjobster_characters_description_max;?>});
								jQuery(".charlimit-jobinstruction").counted({count:<?php echo $wpjobster_characters_instructions_max;?>});
							});

							<?php if($wpjobster_enable_extra != "no"){
								$wpjobster_characters_extradescription_max = get_option( "wpjobster_characters_extradescription_max" );
								$wpjobster_characters_extradescription_max = ( empty( $wpjobster_characters_extradescription_max ) || $wpjobster_characters_extradescription_max==0 ) ? 50 : $wpjobster_characters_extradescription_max; ?>
								function extradescription_charlimit(){
									jQuery(".charlimit-extradescription").counted({count:<?php echo $wpjobster_characters_extradescription_max;?>});
								}
								jQuery(document).ready(function($) {
									extradescription_charlimit();
								});
							<?php } ?>
						</script>
						<!-- END CHARACTER LIMIT -->

		<!-- POST NEW JOB BUTTON -->
						<div class="field">
							<input class="ui primary button" type="submit" name="wpjobster_post_new_job" value="<?php _e("Post Job", 'wpjobster'); ?>" />
						</div>
		<!-- END POST NEW JOB BUTTON -->
					</form>
	<!-- END POST FORM -->
				</div><!-- END <div class="ui segment"> -->
		</div><!-- END <div id="content" class="page_without_sidebar"> -->
<!-- END CONTENT -->

		<div class="ui hidden divider"></div>

		<?php
		$post_new = ob_get_contents();
		ob_clean();

		return $post_new;

	}
} ?>