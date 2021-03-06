<?php get_header(); ?>
<?php $wpjobster_packages = get_option('wpjobster_packages_enabled');
$user_level = wpjobster_get_user_level( $uid );
$lvl_sts = get_option( 'wpjobster_get_level'.$user_level.'_packages' );

wpj_get_subscription_info_path();
$wpjobster_subscription_info = get_wpjobster_subscription_info( $uid );
extract( $wpjobster_subscription_info );

if ( $wpjobster_subscription_enabled == 'yes' ) {
	$lvl_sts = get_option( 'wpjobster_subscription_packages_'.$wpjobster_subscription_level );
}
?>

<!-- EDIT JOB PAGE -->
<div id="content-full-ov" class="page_without_sidebar">

<!-- TITLE -->
	<div class="ui basic notpadded segment">
		<div class="edit-job-title">
			<h1 class="ui header wpj-title-icon">
				<i class="edit icon"></i>
				<?php echo sprintf(__("Edit Job - %s", 'wpjobster'), $posta->post_title); ?>
			</h1>
		</div>
	</div>
<!-- END TITLE -->

<?php do_action( 'wpj_after_vars_declaration_for_edit_job' ); ?>

<!-- CONTENT -->
	<div class="post-new-job-wrapper-x ui segment">

<!-- ERRORS -->
		<?php
		if($more_extras=='yes'){
			$adOK=0;
			$post_new_error['more_extras'] = sprintf(__('Only %s extras are allowed for your user level. Please delete the remaining.', 'wpjobster'), $extras_allowed);
		}
		if(isset($post_new_error) && is_array($post_new_error)){
			if(isset($adOK) && $adOK == 0){
				echo '<div class="errrs">';
					foreach($post_new_error as $e)
					echo '<div class="newad_error">'.$e. '</div>';
				echo '</div>';
			}
		} ?>
<!-- END ERRORS -->

<!-- JOB SAVED -->
		<?php if(isset($adOK) && $adOK == 1){
			if($job_saved == 1):
				echo '<div class="edit-job-ok"><div class="padd10">'.__('Your job has been saved.','wpjobster').'</div></div>';
			endif;
		} ?>
<!-- END JOB SAVED -->

<!-- SCRIPTS -->
		<script>
			function display_subcat(this_var,vals,selected){
				if(typeof(selected)==='undefined'){
					selected='';
				}
				
				(function($){
					$.post("<?php bloginfo('url'); ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
						if(data.length >0) {
							//console.log(data);
							$(this_var).parent().parent().parent().find('.sub_cat_design').html(data);
							$(this_var).parent().parent().parent().find('.sub_cat_design select').val(selected);
							//$('#sub_cats').html(data);
							//$('#sub_cats select').val(selected);
						}
						$.fn.myFunction();
					});
				})(jQuery);
			}

			function check_delivery_time(initial_val, current){
				var max_days_val = $("#max_days").val();

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
				var jq_job_cat = $("#job_cat").val();
				var jq_sub_cat = $('select[name="subcat"] option:selected').val();
				display_subcat(jq_job_cat,jq_sub_cat);
				var initial_val = $("#max_days").val();
				check_delivery_time(initial_val, initial_val);
				$("#max_days").change(function(e){
					check_delivery_time(initial_val, this.value);
				});

				<?php
				if(isset($_POST['job_cat']) && $_POST['job_cat']!=''){ ?>
					display_subcat(this,'<?php echo $_POST['job_cat']?>','<?php echo isset($_POST['subcat']) ? $_POST['subcat']:""; ?>');
				<?php } ?>
			});

			more_extras = '<?php echo $more_extras?>';

			function delete_this_deliveryfile(id, jobid){
				$.ajax({
					method: 'get',
						url : '<?php echo get_bloginfo('url');?>/index.php/?_ad_delete_pid='+id+'&jobid='+jobid,
						dataType : 'text',
						success: function (text) {
							//alert(text);
							$('#image_ss'+id).remove();
							$("#hidden_instant_file_uploader").removeClass('hidden');
					}
				});
			}
		</script>
		   <script>
				jQuery(document).ready(function($) {
					$('.ui.dropdown').dropdown();
					var sub_fd = $('#fd_cat').html();
					$('#add_extra_sub').click(function(){
					$(this).parent().before('<div class="field"><div class="two fields">'+sub_fd+'</div></div>');
					 display_subcat();
					});
				 });
			</script>
<!-- END SCRIPTS -->

<!-- FORM -->
		<form class="ui form" method="post" enctype="multipart/form-data" action="<?php bloginfo('url'); ?>/?jb_action=edit_job&jobid=<?php echo $pid; ?>">
			<div class="field">
				<div class="two fields">
	<!-- JOB TITLE -->
					<div class="field instructions-popup">
						<label><?php echo __( 'Job Title', 'wpjobster' ); ?></label>
						<input type="text" class="charlimit-jobtitle uz-listen1 <?php if (get_post_status($pid) == 'pending' && $rejected_name == 1) echo 'rejected-input'; ?>" name="job_title" value="<?php echo $posta->post_title; ?>" />
						<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
					</div>
					<?php echo wpj_get_popup( 'job_title_instructions', 'edit', 'job', $pid, 'name' ); ?>
	<!-- END JOB TITLE -->

	<!-- JOB PRICE -->
			
	<!-- END JOB PRICE -->
				</div><!-- END TWO FIELDS -->
			</div><!-- END FIELD -->
                  <!-----JOB PRICE---->
				  <div class="two fields">
				  		<div id="job_price_field" class="field instructions-popup" <?php if ( $wpjobster_packages == 'yes' && $lvl_sts == 'yes' && get_post_meta( $pid, 'job_packages', true ) == "yes" ) { echo 'style="display:none;"'; } ?>>
						<label>
							<?php _e( "Job Price", "wpjobster" ); ?>
							<?php echo '<span>&nbsp;(' . wpjobster_get_currency_symbol(wpjobster_get_currency_classic()) . ')</span>'; ?>
						</label>
						<?php
							$wpjobster_enable_dropdown_values = get_option( 'wpjobster_enable_dropdown_values' );
							$wpjobster_enable_free_input_box  = get_option( 'wpjobster_enable_free_input_box' );
							global $current_user;
							$user_level = wpjobster_get_user_level( $current_user->ID );
							$min_job_amount = get_option( 'wpjobster_min_job_amount' );
							if ( ! is_numeric( $min_job_amount ) || $min_job_amount == '' || $min_job_amount == '0' || $min_job_amount < 0 ) {
								$min_job_amount = 0;
							}
							$allowed_max_job_cost = get_option( 'wpjobster_level' . $user_level . '_max' );
							if ( $wpjobster_subscription_max_job_price ) {
								$allowed_max_job_cost = $wpjobster_subscription_max_job_price;
							}
							if( $wpjobster_enable_free_input_box == "yes" ) { ?>
							<script>
									$(document).ready(function(){
									$('.radio_change').on('change', function() {
										   var price_val=$('input[name=prc]:checked').val(); 
                                          if(price_val=="negotiable"){
											   $("#fixprc").hide();
											   $(".ngp_wrap").show();
										   }else{
											  $(".ngp_wrap").hide(); 
											  $("#fixprc").show();
										   }
										});
									});
									</script>
					  <?php 
						$key_price_value = get_post_meta($pid, 'job_price_select', true );	
						$key_max_price = get_post_meta($pid,'job_max_price', true );
						$key_min_price = get_post_meta($pid,'job_min_price', true );
						?>
                                 <div class="ui checkbox radio">
									    <input type="radio" class="grey_input radio_change" name="prc" id="fix_price" value="fix_price" <?php if($key_price_value=="fix_price"){ ?>checked <?php } ?>>
									     <label><span> Fixed</span></label>
								    </div>
								<?php
														    
							if (! empty($key_price_value) && ($key_price_value=="fix_price")) {
								?>	
								<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost ?>" name="job_cost" class="uz-listen1" value="<?php if ( $price != 0 ) echo $price; ?>" size="5" />
								  <?php 		
										}else{?>
								<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost ?>" name="job_cost" class="uz-listen1" value="" size="5"  id="fixprc" style="display:none;">
										<?php }
									?>
							 	 <div class="ui checkbox radio">
									    <input type="radio" class="grey_input radio_change" name="prc" id="nego_price" value="negotiable" <?php if($key_price_value=="negotiable"){ ?>checked<?php } ?>>
									    <label><span> Negotiable Range </span></label>
								</div>
								<?php 								
						   if(! empty($key_price_value) && ($key_price_value=="negotiable")){
									?>
								<div class="ngp_wrap three fields">
									<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_min_price" value="<?php echo stripslashes($key_min_price); ?>" cols="40" />
									To
									<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_max_price" value="<?php echo stripslashes($key_max_price); ?>" cols="40" />
								</div>
								<?php } else { ?>
								<div class="ngp_wrap three fields" id="ngwrap" style="display:none;">
									<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_min_price" value="" cols="40" />
									To
									<input type="number" step="any" min="<?php echo $min_job_amount; ?>" max="<?php echo $allowed_max_job_cost; ?>" class="field" name="job_max_price" value="" cols="40" />
								</div>	
							<?php	
							  } 
								
								} elseif( $wpjobster_enable_dropdown_values == "yes" ) {
								echo wpjobster_get_variale_cost_dropdown( 'grey_input', $price );
							} else {
								echo '<div class="job-fixed-price">';
									echo wpjobster_get_show_price_classic( get_option( 'wpjobster_job_fixed_amount' ) );
								echo '</div>';
							}
						?>
					</div>
					<?php echo wpj_get_popup( 'job_price_instructions' ); ?>
					</div>
				  <!-----End------>
				
	<!-- JOB CATEGORY && SUBCATEGORY -->
	       <div class="two fields" id="fd_cat" style="display:none;">
		<!-- JOB CATEGORY -->
								<div class="field instructions-popup">
									<label><?php //echo __('Category', 'wpjobster'); ?>Subjects</label>

									<?php
									echo wpjobster_get_categories_clck("job_cat[]",
									!isset($_POST['job_cat']) ? (isset($cat)&& is_array($cat)&& isset($cat[0]->term_id) ? $cat[0]->term_id : "") : htmlspecialchars($_POST['job_cat'])
									, __('Select Category','wpjobster'), "ui dropdown new-post-category styledselect uz-listen2", 'onchange="display_subcat(this,this.value)"' );
									?>
								</div>
								<?php echo wpj_get_popup( 'job_category_instructions' ); ?>
		<!-- END JOB CATEGORY -->

		<!-- JOB SUBCATEGORY -->
								<div class="field subcat-field instructions-popup">
								 <div class="sub_cat_design">
									<?php //echo '<span id="sub_cats" class="post-new-subcat">';
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
									//echo '</span>'; ?>
									</div>
								</div>
								<?php echo wpj_get_popup( 'job_subcategory_instructions' ); ?>
		<!-- END JOB SUBCATEGORY -->
							</div><!-- END ROW -->
			<?php 
			   $partial_cat = $cat;
			   foreach($partial_cat  as $key3=>$cat_val) {
				   if($cat_val->term_id == 3)
				   {
					  array_splice($cat, $key3+1,0,array('blank'));
				   }
			   }
			
			   $serdata=get_post_meta($pid, 'subcat_text_field', true);
			   $unsdata=unserialize($serdata); 
			   $cou = 0;
			 
			?>				
	       <?php foreach($cat as $key3=>$cat_val) { //print_r($cat_val);?>
		   <?php if($key3%2 == 0){  ?>
			<div class="field">
				<div class="two fields">	
				<?php
					if((get_post_meta($pid, 'subcategory_featured_until', true)=='z' || get_post_meta($pid, 'subcategory_featured_until', true)==false) && (get_post_meta($pid, 'category_featured_until', true)=='z' || get_post_meta($pid, 'category_featured_until', true)==false)){ ?>		  
					<div class="field instructions-popup">
						<label><?php //echo __('Category', 'wpjobster'); ?>Subjects</label>					
                       <?php echo wpjobster_get_categories_clck("job_cat[]", (isset($cat_val)&&is_array($cat) && isset($cat_val->term_id ) ? $cat_val->term_id : "")  , __('Select Category','wpjobster'), "ui dropdown edit-post-category styledselect uz-listen2", 'onchange="display_subcat(this,this.value)"' ); ?>
					</div>
					<?php echo wpj_get_popup( 'job_category_instructions' ); ?>
					
					<div class="field instructions-popup">
					    <div class="sub_cat_design">
						<?php 
						    // echo '<span id="sub_cats" class="post-new-subcat">';
							if($cat_val->term_id!=3){
							$args3 = "orderby=name&order=ASC&hide_empty=0&parent=".isset($cat_val)&&isset($cat_val->term_id)?$cat_val->term_id:"";
						   $sub_terms3 = get_terms( 'job_cat', $args3 );		
			   
							if(count($sub_terms3)> 0) {
								if(!empty($cat[$key3+1]->term_id)) {
									$selected1 = $cat[$key3+1]->term_id;
								} else {
									$selected1 = -1;
								}
								$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".isset($cat_val)&&isset($cat_val->term_id)?$cat_val->term_id:"";
							    	
								$sub_terms2 = get_terms( 'job_cat', $args2 );
							
								$ret = '<select class="ui dropdown styledselect styledselect2 uz-listen2" name="subcat[]">';
								$ret .= '<option value="">'.__('Select Subcategory','wpjobster'). '</option>';
								$terms = get_the_terms( $_GET['jobid'], 'job_cat' );								
							/*	if( isset( $terms[$key3] ) && $terms[$key3]->parent != 0 && $terms[$key3]->parent != '' ){
									$selected1 = $terms[$key3]->term_id;
								}elseif( isset( $terms[$key3+1] ) && $terms[$key3+1]->parent != 0 && $terms[$key3+1]->parent != '' ){
									$selected1 = $terms[$key3+1]->term_id;
								}*/
							
								foreach ( $sub_terms2 as $sub_term2 )
								{
									$sub_id2 = $sub_term2->term_id;
									$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';
								}
								$ret .= "</select>";
								echo $ret;
							} 
							} else{?>
							<input type="text" name="other_subcat[]" class="subcat_txt" value="<?php echo $unsdata[$cou];?>">
						<?php  $cou++;	}					
							//echo '</span>';
						?>
					</div><!-- END FIELD -->
					</div>
					<?php echo wpj_get_popup( 'job_subcategory_instructions' ); ?>
				 <?php } ?>
				
				</div><!-- END TWO FIELDS -->
			</div><!-- END FIELD -->
			<?php } ?>
		   <?php } ?>
	<!-- END JOB CATEGORY && SUBCATEGORY -->
                 <div class="field">
									<a href="javascript:void(0);" id="add_extra_sub" class="cursor_pointer ">+ Add New Extra</a>
								</div> 
	<!-- JOB PACKAGES -->
			<?php if ( $wpjobster_packages == "yes" && $lvl_sts == 'yes' ) { ?>
				<div class="field">
					<div class="two fields">
						<div class="field instructions-popup">
							<label><?php echo __('Packages', 'wpjobster'); ?></label>

							<div class="post-new-job-slide-box">
								<div class="ui toggle checkbox">
									<input type="checkbox" name="packages" id="packages" value="yes" <?php echo ( get_post_meta( $pid, 'job_packages', true ) == "yes" ) ? 'checked' : ''; ?> />
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
			<?php if ( $wpjobster_packages == "yes" && $lvl_sts == 'yes' ) {
				$package_name = get_post_meta( $pid, 'package_name', true );
				$package_description = get_post_meta( $pid, 'package_description', true );
				$package_max_days = get_post_meta( $pid, 'package_max_days', true );
				$package_price = get_post_meta( $pid, 'package_price', true );
				$package_revisions = get_post_meta( $pid, 'package_revisions', true );
				$package_custom_fields = get_post_meta( $pid, 'package_custom_fields', true );

				$packages = get_post_meta( $pid, 'job_packages', true ); ?>

				<div class="field packages" style="<?php if ( $packages != 'yes' ) { echo 'display: none;'; } ?>">
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
								<?php if ( $package_name ) {
									foreach ( $package_name as $p_n_key => $p_name ) { ?>
										<td>
											<div class="ui input instructions-popup">
												<input class="package_name" name="package_name[]" maxlength="35" type="text" placeholder="<?php echo __( 'Name your package', 'wpjobster'); ?>" value="<?php echo $p_name; ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>>
											</div>
											<?php echo wpj_get_popup( 'job_package_name_instructions' ); ?>
										</td>
									<?php }
								} else {
									for( $i=0; $i<3; $i++ ) { ?>
										<td>
											<div class="ui input instructions-popup">
												<input class="package_name" name="package_name[]" maxlength="35" type="text" placeholder="<?php echo __( 'Name your package', 'wpjobster'); ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>>
											</div>
											<?php echo wpj_get_popup( 'job_package_name_instructions' ); ?>
										</td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td>
									<?php echo __( 'Package description', 'wpjobster'); ?>
								</td>
								<?php if ( $package_description ) {
									foreach ( $package_description as $p_d_key => $p_desc ) { ?>
										<td>
											<div class="instructions-popup">
												<textarea class="package_description" minlength="<?php echo get_option('wpjobster_characters_description_min'); ?>" maxlength="<?php echo get_option('wpjobster_characters_description_max'); ?>" name="package_description[]" type="text" placeholder="<?php echo __( 'Describe the details of your offering', 'wpjobster'); ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>><?php echo $p_desc; ?></textarea>
											</div>
											<?php echo wpj_get_popup( 'job_package_description_instructions' ); ?>
										</td>
									<?php }
								} else {
									for( $i=0; $i<3; $i++ ) { ?>
										<td>
											<div class="instructions-popup">
												<textarea class="package_description" minlength="<?php echo get_option('wpjobster_characters_description_min'); ?>" maxlength="<?php echo get_option('wpjobster_characters_description_max'); ?>" name="package_description[]" type="text" placeholder="<?php echo __( 'Describe the details of your offering', 'wpjobster'); ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>></textarea>
											</div>
											<?php echo wpj_get_popup( 'job_package_description_instructions' ); ?>
										</td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td>
									<?php echo __( 'Package delivery time', 'wpjobster'); ?>
								</td>
								<?php if ( $package_max_days ) {
									foreach ( $package_max_days as $p_md_key => $p_max_days ) { ?>
										<td>
											<div class="instructions-popup">
												<select id="max_days" name="package_max_days[]" class="ui dropdown max-day-deliver styledselect uz-listen2" >
													<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
														<option value="<?php echo $i_count ?>" <?php echo ($i_count==$p_max_days?' selected="selected=" ':""); ?>>
															<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
														</option>
													<?php } ?>
												</select>
											</div>
											<?php echo wpj_get_popup( 'job_package_delivery_time_instructions' ); ?>
										</td>
									<?php }
								} else {
									for( $i=0; $i<3; $i++ ) { ?>
										<td>
											<div class="instructions-popup">
												<?php
												$max_days = (!isset($max_days) || empty($max_days) ? get_post_meta($pid,'max_days',true) : $max_days );
												$max_days = ( empty($max_days) && isset($_POST['max_days']) ? $_POST['max_days'] : $max_days );
												?>
												<select id="max_days" name="package_max_days[]" class="ui dropdown max-day-deliver styledselect uz-listen2" >
													<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
														<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days?' selected="selected=" ':""); ?>>
															<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
														</option>
													<?php } ?>
												</select>
											</div>
											<?php echo wpj_get_popup( 'job_package_delivery_time_instructions' ); ?>
										</td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td>
									<?php echo __( 'Package revisions', 'wpjobster'); ?>
								</td>
								<?php if ( $package_revisions ) {
									foreach ( $package_revisions as $p_r_key => $p_rev ) { ?>
										<td>
											<div class="instructions-popup">
												<select name="package_revisions[]" class="ui dropdown styledselect">
													<?php for($i_count=1;$i_count<=9;$i_count++){ ?>
														<option value="<?php echo $i_count ?>" <?php echo ($i_count==$p_rev?' selected="selected=" ':""); ?>>
															<?php echo $i_count; ?>
														</option>
													<?php } ?>
													<option <?php if ( $p_rev == 'unlimited' ) echo 'selected'; ?> value="unlimited"><?php echo __( 'Unlimited', 'wpjobster' ); ?></option>
												</select>
											</div>
											<?php echo wpj_get_popup( 'job_package_revision_instructions' ); ?>
										</td>
									<?php }
								} else {
									for( $i=0; $i<3; $i++ ) { ?>
										<td>
											<div class="instructions-popup">
												<?php $revisions = isset( $_POST['package_revisions'][$i] ) ? $_POST['package_revisions'][$i] : ''; ?>
												<select name="package_revisions[]" class="ui dropdown styledselect" >
													<?php for($i_count=1;$i_count<=9;$i_count++){ ?>
														<option value="<?php echo $i_count ?>" <?php echo ( $i_count==$revisions ? ' selected="selected=" ' : ""); ?>>
															<?php echo $i_count; ?>
														</option>
													<?php } ?>
													<option <?php if ( $revisions == 'unlimited' ) echo 'selected'; ?> value="unlimited"><?php echo __( 'Unlimited', 'wpjobster' ); ?></option>
												</select>
											</div>
											<?php echo wpj_get_popup( 'job_package_revision_instructions' ); ?>
										</td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td>
									<?php echo __( 'Package price', 'wpjobster') . '&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')'; ?>
								</td>
								<?php if ( $package_price ) {
									foreach ( $package_price as $p_p_key => $p_price ) { ?>
										<td class="pck-padd-left">
											<div class="ui labeled input instructions-popup">
												<div class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency_classic() ); ?></div>
												<input class="package_price" step="0.01" name="package_price[]" type="number" placeholder="<?php echo __( 'Insert package price', 'wpjobster'); ?>" value="<?php echo $p_price; ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>>
											</div>
											<?php echo wpj_get_popup( 'job_package_price_instructions' ); ?>
										</td>
									<?php }
								} else {
									for( $i=0; $i<3; $i++ ) { ?>
										<td class="pck-padd-left">
											<div class="ui labeled input instructions-popup">
												<div class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency_classic() ); ?></div>
												<input class="package_price" step="0.01" name="package_price[]" type="number" placeholder="<?php echo __( 'Insert package price', 'wpjobster'); ?>" value="<?php echo $p_price; ?>" <?php if ( $packages == 'yes' ) { echo 'required'; } ?>>
											</div>
											<?php echo wpj_get_popup( 'job_package_price_instructions' ); ?>
										</td>
									<?php }
								} ?>
							</tr>

							<?php if ( $package_custom_fields ) {
								foreach ($package_custom_fields as $key => $value) { ?>
									<tr class="pck-repeater">
										<td>
											<div class="instructions-popup di">
												<input class="pck-inp-custom-name" placeHolder="<?php echo __( 'Insert field name', 'wpjobster'); ?>" name="pck-inp-custom-name[]" value="<?php echo $value['name']; ?>" />
											</div>
											<i class="pck-icon-rem remove icon" style="display: inline;"></i>
											<?php echo wpj_get_popup( 'job_package_custom_field_name_instructions' ); ?>
										</td>
										<td class="pck-center">
											<div class="ui checkbox instructions-popup">
												<input type="hidden" name="pck-chk-value[basic][]" value="off" />
												<input type="checkbox" class="basic-checkbox" value="on" <?php if ( $value['basic'] == 'on' ) { echo 'checked'; } ?> />
												<label></label>
											</div>
											<script type="text/javascript">
												jQuery( document ).ready(function($) {
													$(document).on("change", "input.basic-checkbox", function() {
														var value = $(this).is(":checked") ? $(this).val() : 'off';
														$(this).siblings("input[name='pck-chk-value[basic][]']").val(value);
													});
													$('input.basic-checkbox').each(function(){
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
												<input type="checkbox" class="standard-checkbox" value="on" <?php if ( $value['standard'] == 'on' ) { echo 'checked'; } ?> />
												<label></label>
											</div>
											<script type="text/javascript">
												jQuery( document ).ready(function($) {
													$(document).on("change", "input.standard-checkbox", function() {
														var value = $(this).is(":checked") ? $(this).val() : 'off';
														$(this).siblings("input[name='pck-chk-value[standard][]']").val(value);
													});
													$('input.standard-checkbox').each(function(){
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
												<input type="checkbox" class="premium-checkbox" value="on" <?php if ( $value['premium'] == 'on' ) { echo 'checked'; } ?> />
												<label></label>
											</div>
											<script type="text/javascript">
												jQuery( document ).ready(function($) {
													$(document).on("change", "input.premium-checkbox", function() {
														var value = $(this).is(":checked") ? $(this).val() : 'off';
														$(this).siblings("input[name='pck-chk-value[premium][]']").val(value);
													});
													$('input.premium-checkbox').each(function(){
														var value = $(this).is(":checked") ? $(this).val() : 'off';
														$(this).siblings("input[name='pck-chk-value[premium][]']").val(value);
													});
												});
											</script>
											<?php echo wpj_get_popup( 'job_package_custom_field_checklist_instructions' ); ?>
										</td>
									</tr>
								<?php }
							} ?>

							<!-- ADD NEW FIELDS -->
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
							<!-- END ADD NEW FIELDS -->
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

			<?php do_action( 'wpj_edit_extra_fields_post_page_display', $pid ); ?>

	<!-- JOB DESCRIPTION -->
			<div id="job_description_field" class="field post-new-job-wrapper-x instructions-popup">
				<div class="input-block">
		<!-- WYSIWYG TEXTAREA -->
					<?php if(wpj_bool_option('wpjobster_allow_wysiwyg_job_description')){
						$max_chr_description = get_option( 'wpjobster_characters_description_max' ) ?: 1000; ?>
						<label><?php echo __('Description', 'wpjobster'); ?>
							<textarea id="job_description" class="lighter grey_input job-description-wysiwyg job-description-wysiwyg-style uz-listen1 <?php if (get_post_status($pid) == 'pending' && $rejected_description == 1) echo 'rejected-input'; ?>"  name="job_description"><?php $pst = $posta->post_content; echo empty($_POST['job_description']) ? $pst : $_POST['job_description']; ?></textarea>

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
									post_status = '<?php echo get_post_status($pid); ?>';
									rejected_description = '<?php echo $rejected_description; ?>';
									wpj_js_description_args_allowed(max_chr_description, post_status , rejected_description);
								});
							</script>
						</label>
		<!-- END WYSIWYG TEXTAREA -->
					<?php }else{ ?>
		<!-- SIMPLE TEXTAREA -->
						<label><?php echo __('Description', 'wpjobster'); ?></label>
						<textarea class="charlimit-jobdescription uz-listen1 <?php if (get_post_status($pid) == 'pending' && $rejected_description == 1) echo 'rejected-input'; ?>" name="job_description"><?php
						$pst = strip_tags($posta->post_content, "<br>");
						echo empty($_POST['job_description']) ? str_replace("<br />","",$pst) : htmlspecialchars($_POST['job_description']); ?></textarea>

						<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
		<!-- END SIMPLE TEXTAREA -->
					<?php } ?><!-- END IF TEXTAREA TYPE -->
				</div><!-- END DIV "input-block" -->
			</div><!-- END FIELD -->
			<?php echo wpj_get_popup( 'job_description_instructions', 'edit', 'job', $pid, 'description' ); ?>
	<!-- END JOB DESCRIPTION -->

	<!-- JOB INSTRUCTIONS FOR BUYER -->
			<div class="field instructions-popup">
				<?php $instruction_box = get_post_meta($posta->ID, 'instruction_box', true); ?>
				<label><?php echo __('Instructions to buyer', 'wpjobster'); ?></label>
				<textarea class="grey_input charlimit-jobinstruction uz-listen1 <?php if (get_post_status($pid) == 'pending' && $rejected_instructions == 1) echo 'rejected-input'; ?>" name="instruction_box"><?php
				$instruction_box = stripslashes($instruction_box);
				echo empty($_POST['instruction_box']) ?  str_replace("<br />","",$instruction_box) : htmlspecialchars($_POST['instruction_box']); ?></textarea>
				<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
			</div>
			<?php echo wpj_get_popup( 'job_instructions_instructions', 'edit', 'job', $pid, 'instructions' ); ?>
	<!-- END JOB INSTRUCTIONS FOR BUYER -->

			<div class="field">
				<div class="two fields">
	<!-- JOB TAGS -->
					<div class="field input-block instructions-popup">
						<?php
							$job_tags = '';
							$t = wp_get_post_tags($pid);
							$i = 0;
							$i_separator = '';
							foreach($t as $tag)
							{
								$job_tags .= $i_separator . $tag->name;
								$i++;
								if ($i > 0) { $i_separator = ', '; }
							}
						?>
						<label><?php echo __('Tags', 'wpjobster'); ?> <span class="lighter">(<?php _e('separate your tags by comma','wpjobster'); ?>)</span></label>
						<input type="text" id="<?php if ( get_post_status( $pid ) == 'pending' && $rejected_tags == 1 ) echo 'job_tags_rejected'; else echo 'job_tags'; ?>" class="uz-listen1 <?php if (get_post_status($pid) == 'pending' && $rejected_tags == 1) echo 'rejected-input'; ?>"  name="job_tags" value="<?php echo $job_tags; ?>" />
					</div>
					<?php echo wpj_get_popup( 'job_tags_instructions', 'edit', 'job', $pid, 'tags' ); ?>
	<!-- END JOB TAGS -->

	<!-- JOB LET's MEET -->
					<?php
					$wpjobster_lets_meet = get_option('wpjobster_lets_meet');
					if($wpjobster_lets_meet == "yes") { ?>
						<div class="field instructions-popup">
							<label><?php echo __('Let\'s Meet', 'wpjobster'); ?></label>

							<div class="post-new-job-slide-box">
								<div class="ui toggle checkbox">
									<input type="checkbox" name="lets_meet" id="lets_meet" value="yes" <?php echo (get_post_meta($pid, 'lets_meet', true) == "yes") ? 'checked' : ''; ?>/>
									<label><?php echo __( 'Slide to enable', 'wpjobster' ); ?></label>
								</div>
							</div>
						</div>
						<?php echo wpj_get_popup( 'job_lets_meet_instructions' );
					} ?>
	<!-- END JOB LET's MEET -->
				</div><!-- END TWO FIELDS -->
			</div><!-- END FIELD -->

			<div class="field">
				<div class="three fields">
	<!-- JOB LOCATION -->
					<?php
					$wpjobster_location_display_condition = get_option('wpjobster_location_display_condition');
				if ($wpjobster_location_display_condition == 'ifchecked') { ?>
					<div class="field instructions-popup" id="edit-location-input" style="display:none">
				<?php }else{ ?>
					<div class="field instructions-popup">
				<?php }
						if ($wpjobster_location_display_condition == 'always' || $wpjobster_location_display_condition == 'ifchecked') {
							$wpjobster_location = get_option('wpjobster_location');
							if ( $wpjobster_location == "yes" ) { ?>

									<label><?php echo __('Location', 'wpjobster'); ?></label>
									<input class="uz-listen1" type="text" data-replaceplaceholder="<?php _e('Select a valid location','wpjobster') ?>" placeholder="<?php _e('Location','wpjobster') ?>" id="location_input" value="<?php echo get_post_meta($pid, 'location_input', true); ?>" name="location_input">
									<input id="lat" type="hidden" name="lat" value="<?php echo get_post_meta($pid, 'lat', true); ?>">
									<input id="long" type="hidden" name="long" value="<?php echo get_post_meta($pid, 'long', true); ?>">

					</div>
					<?php echo wpj_get_popup( 'job_location_instructions' );
							}
						} ?>
	<!-- END JOB LOCATION -->


	<!-- JOB DISTANCE -->
					<?php $wpjobster_distance_display_condition = get_option('wpjobster_distance_display_condition');
				if ($wpjobster_distance_display_condition == 'ifchecked') { ?>
					<div class="field instructions-popup" id="edit-distance-input" style="display:none">
				<?php }else{ ?>
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

	<!-- JOB DISPLAY MAP -->
					<?php
					$wpjobster_location_display_map_user_choice = get_option('wpjobster_location_display_map_user_choice');
				if ($wpjobster_location_display_condition == 'ifchecked') { ?>
					<div class="field instructions-popup" id="edit-map-checkbox" style="display:none">
				<?php }else{ ?>
					<div class="field instructions-popup">
				<?php }

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
				</div>
			</div>
	<!-- END JOB DISPLAY MAP -->

			<div class="field">
				<div class="three fields">
					<div id="job_delivery_time_field" class="field instructions-popup" <?php if ( $wpjobster_packages == 'yes' && $lvl_sts == 'yes' && get_post_meta( $pid, 'job_packages', true ) == "yes" ) { echo 'style="display:none;"'; } ?>">
	<!-- JOB MAX DAYS TO DELIVER -->
						<?php
						$wpjobster_enable_shipping = get_option('wpjobster_enable_shipping');
						$wpjobster_enable_instant_deli = get_option('wpjobster_enable_instant_deli');
						?>
						<label>
							<?php echo __('Max Days to Deliver', 'wpjobster');
							$max_days = (!isset($max_days) || empty($max_days) ? get_post_meta($pid,'max_days',true) : $max_days );
							$max_days = ( isset($_POST['max_days']) ? $_POST['max_days'] : $max_days );

							$instant_delivery = get_post_meta($pid,'instant',true);
							if($instant_delivery==1){
								$max_days='instant';
							}
							if($max_days=='instant'){
								$instant_file_class = '';
							}else{
								$instant_file_class ='hidden';
							}
							?>
						</label>
						<select id="max_days" name="max_days" class="ui dropdown max-day-deliver styledselect uz-listen2" >
							<option value=""><?php echo __("Please Select","wpjobster");?></option>
							<?php if($wpjobster_enable_instant_deli != "no"): ?>
								<option <?php echo ($max_days=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
							<?php endif; ?>
							<?php for($i_count=1;$i_count<=30;$i_count++){ ?>
								<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days?' selected="selected=" ':""); ?>>
									<?php echo sprintf(_n("%d day", "%d days", $i_count, "wpjobster"), $i_count);?>
								</option>
							<?php } ?>
						</select>
					</div><!-- END FIELD -->
					<?php echo wpj_get_popup( 'job_delivery_time_instructions' ); ?>
	<!-- END JOB MAX DAYS TO DELIVER -->

	<!-- JOB INSTANT DELIVERY -->
					<?php
					if($wpjobster_enable_instant_deli != "no"): ?>

						<div id="instant-file-section" class="field instructions-popup instant-delivery-file <?php echo $instant_file_class;?>">

							<?php
							echo "<label>" . __('Instant Delivery File', 'wpjobster') . "</label>";

							if (get_post_status($pid) == 'pending' && $rejected_instant_delivery == 1){
								echo '<div class="rejected-border">';
							}

							wpjobster_theme_attachments_uploader_html5($secure=1,"file_upload_instant_job_attachments", "hidden_files_instant_job_attachments", "instant_delivery");

							if (get_post_status($pid) == 'pending' && $rejected_instant_delivery == 1){
								echo '</div>';
							}

							$job_any_attachments = get_post_meta($pid, 'job_any_attachments', true);
							if (isset($job_any_attachments) && $job_any_attachments != "") {
								echo '<div class="pm-attachments">';
									$attachments = explode(",", $job_any_attachments);
									foreach ($attachments as $attachment) {
										if($attachment != ""){
											echo '<div class="div_div2" id="image_ss'.$attachment.'"><a class="download-req" target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
											echo substr(get_the_title($attachment), 0, 20).'...</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span>';
											echo '<a class="remove-img-req" href="javascript: void(0)" onclick="delete_this_deliveryfile('.$attachment.','.$pid.');"></a></div><br>';
										}
									}
								echo '</div>';
							} ?>
						</div>
						<?php echo wpj_get_popup( 'job_instant_delivery_time_instructions', 'edit', 'job', $pid, 'instant_delivery' );
					endif; ?>
	<!-- END JOB INSTANT DELIVERY -->

	<!-- JOB REQUIRES SHIPPING -->
					<div class="field instructions-popup">
						<?php if($wpjobster_enable_shipping == "yes"): ?>
						<label>
							<?php echo __('Requires shipping?', 'wpjobster'); ?> <?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
						</label>
						<input type="number" step="any" min="0" class="uz-listen1" placeholder="<?php echo __('optional', 'wpjobster'); ?>" name="shipping" value="<?php echo get_post_meta($pid, 'shipping', true); ?>" />
						<?php endif; ?>
					</div>
					<?php echo wpj_get_popup( 'job_shipping_instructions', 'edit', 'job', $pid, 'instant_delivery' ); ?>
	<!-- JOB REQUIRES SHIPPING -->
				</div><!-- END TWO FIELDS -->
			</div><!-- END FIELD -->

	<!-- JOB COVER IMAGE -->
			<div class="field instructions-popup">
				<?php if ( get_option('wpjobster_enable_job_cover' ) == 'yes' ) { ?>
					<label><?php echo __('Cover Image', 'wpjobster'); ?></label>
					<?php
					if ( wpjobster_get_preferred_uploader() === 'html5fileupload' ) {
						wpjobster_theme_cover_image_html5( $pid );
					} else {
						wpjobster_dropzone_cover_uploader( $pid );
					}
				} ?>
			</div>
			<?php echo wpj_get_popup( 'job_cover_image_instructions' ); ?>
	<!-- JOB COVER IMAGE -->

	<!-- JOB IMAGES -->
			<div class="field instructions-popup">

				<label><?php echo __('Images', 'wpjobster'); ?></label>

				<?php if (get_post_status($pid) == 'pending' && $rejected_images == 1) { ?>
					<div class="rejected-border">
				<?php }

					if ( wpjobster_get_preferred_uploader() === 'html5fileupload' ) {
						wpjobster_theme_job_images_html5( $pid );
					} else {
						wpjobster_dropzone_image_uploader( $pid );
					}

				if (get_post_status($pid) == 'pending' && $rejected_images == 1) { ?>
					</div>
				<?php } ?>
			</div>
			<?php echo wpj_get_popup( 'job_images_instructions', 'edit', 'job', $pid, 'images' ); ?>
	<!-- END JOB IMAGES -->

	<!-- JOB PREVIEW -->
			<div class="field instructions-popup">
				<?php
				$wpjobster_attachments_enable = get_option( 'wpjobster_job_attachments_enabled' );
				if ( $wpjobster_attachments_enable == "yes" ) { ?>
					<?php
					echo "<label>" . __( 'Job preview', 'wpjobster' ) . "</label>";
					if (get_post_status($pid) == 'pending' && $rejected_job_preview == 1){
						echo '<div class="rejected-border">';
					}

					wpjobster_theme_attachments_uploader_html5( $secure=1, "file_upload_preview_job_attachments", "hidden_files_preview_job_attachments", "work_preview" );

					if (get_post_status($pid) == 'pending' && $rejected_job_preview == 1){
						echo '</div>';
					} ?>

					<!-- LIST JOB PREVIEW ATTACHMENTS -->
					<?php $job_any_attachments = get_post_meta( $pid, 'preview_job_attchments', true );
					if ( isset( $job_any_attachments ) && $job_any_attachments != "" ) {
						echo '<div class="pm-attachments">';
							$attachments = explode( ",", $job_any_attachments );
							foreach ( $attachments as $attachment ) {
								if ( $attachment != "" ) {
									echo '<div class="div_div2" id="image_ss'.$attachment.'"><a class="download-req" target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
									echo substr( get_the_title( $attachment ), 0, 20 ) . '...</a> <span class="pm-filesize">(' . size_format( filesize(get_attached_file ( $attachment ) ) ) . ')</span>';
									echo '<a class="remove-img-req" href="javascript: void(0)" onclick="delete_this_deliveryfile(' . $attachment . ', ' . $pid . ');"></a></div><br>';
								}
							}
						echo '</div>';
					} ?>
					<!-- END LIST JOB PREVIEW ATTACHMENTS -->
				<?php } ?>
			</div>
			<?php echo wpj_get_popup( 'job_preview_instructions', 'edit', 'job', $pid, 'job_preview' ); ?>
	<!-- END JOB PREVIEW -->

	<!-- JOB VIDEO -->
			<div class="field instructions-popup">
				<?php
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
				$user_level = wpjobster_get_user_level( $uid );
				$sts = get_option( 'wpjobster_level' . $user_level . '_vds' );
				if( $sts > 3 ) $sts = 3;
				for( $i=1; $i<=$sts; $i++ ) {?>

					<label class="youtube-link"><?php _e( 'Youtube Video Link', 'wpjobster' ); ?></label>

					<input type="text" name="youtube_link<?php echo $i ?>" class="uz-listen1 <?php if ( get_post_status( $pid ) == 'pending' && $rejected_video == 1 ) echo 'rejected-input'; ?>" id="youtube_link" value="<?php echo get_post_meta( $pid, 'youtube_link' . $i, true ); ?>" /><div id="ytlInfo"></div>
				<?php } ?>
			</div>
			<?php echo wpj_get_popup( 'job_video_instructions', 'edit', 'job', $pid, 'video' ); ?>
	<!-- END JOB VIDEO -->

	<!-- JOB AUDIO -->
			<div class="field instructions-popup">
				<?php
				$wpjobster_audio_enable = get_option( 'wpjobster_audio' );
				if ( $wpjobster_audio_enable == "yes" ) { ?>

					<label><?php echo __( 'Audio', 'wpjobster' ); ?></label>
					<?php if ( get_post_status( $pid ) == 'pending' && $rejected_audio == 1 ) { ?>
					<div class="rejected-border">
					<?php } ?>
					<?php wpjobster_theme_job_audios_html5( $pid ); ?>
					<?php if ( get_post_status( $pid ) == 'pending' && $rejected_audio == 1 ) { ?>
					</div>
					<?php }
				} ?>
			</div>
			<?php echo wpj_get_popup( 'job_audio_instructions', 'edit', 'job', $pid, 'audio' ); ?>
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
			} ?>

			<div class="cf p20t bot_bord <?php echo $hidden_extra_fast_delivery; ?>">

				<div class="field instructions-popup">
					<div class="ui checkbox">
						<input type="checkbox" class="grey_input" name="enable_extra_fast" id="enable_extra_fast" value="<?php echo stripslashes(get_post_meta($pid, 'extra_fast_enabled', true)); ?>"
							<?php echo (get_post_meta($pid, 'extra_fast_enabled', true)) ? 'checked' : ''; ?>/>
						<label>
							<?php echo '<span> '.__("Extra fast delivery","wpjobster").'</span>'; ?>
						</label>
					</div>
				</div>
				<?php echo wpj_get_popup( 'job_extra_fast_delivery_enable_instructions' ); ?>

				<div class="two fields">

					<div class="field lock-field instructions-popup">
						<label>
							<?php _e('Price','wpjobster'); ?>
							<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
						</label>
						<input class="grey_input uz-listen1 price-input" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra_fast_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra_fast_price', true)); ?>" />
					</div>
					<?php echo wpj_get_popup( 'job_extra_fast_delivery_price_instructions' ); ?>

					<div class="field lock-field instructions-popup">
						<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>
						<select id="max_days_fast" name="max_days_fast" class="grey_input styledselect max-day-deliver uz-listen3 max_days_fast ui dropdown">
							<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
							<?php if($wpjobster_enable_instant_deli != "no"): ?>
								<option <?php echo ($max_days_fast=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
							<?php endif; ?>
							<?php for($i_count=1;$i_count<=30;$i_count++){ ?>
								<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_fast?' selected="selected=" ':""); ?>>
								<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
								</option>
							<?php } ?>
						</select>
					</div>
					<?php echo wpj_get_popup( 'job_extra_fast_delivery_days_to_deliver_instructions' ); ?>
				</div>
			</div>
	<!-- JOB EXTRA FAST DELIVERY -->

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
					$extra_additional_revision = 'yes';
				}
				if( $extra_additional_revision == 'yes' ) {
					$hidden_extra_additional_revision = ' ';
				}
			}
			?>

			<div class="cf p20t bot_bord <?php echo $hidden_extra_additional_revision; ?>">

				<div class="field instructions-popup">
					<div class="ui checkbox">
						<input type="checkbox" class="grey_input" name="enable_extra_revision" id="enable_extra_revision" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_enabled', true)); ?>"
							<?php echo (get_post_meta($pid, 'extra_revision_enabled', true)) ? 'checked' : ''; ?>/>
						<label>
							<?php echo '<span> '.__("Additional revision","wpjobster").'</span>'; ?>
						</label>
					</div>
				</div>
				<?php echo wpj_get_popup( 'job_additional_revision_enable_instructions' ); ?>

				<div class="three fields">
					<div class="field instructions-popup">
						<label>
							<?php _e('Price','wpjobster'); ?>
							<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
						</label>
						<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra_revision_price" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_price', true)); ?>" />
					</div>
					<?php echo wpj_get_popup( 'job_additional_revision_price_instructions' ); ?>

					<div class="field instructions-popup">
						<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>

						<select id="max_days_revision" name="max_days_revision" class="grey_input max-day-deliver styledselect uz-listen3 ui dropdown">
							<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
							<?php if($wpjobster_enable_instant_deli != "no"): ?>
								<option <?php echo ($max_days_revision=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
							<?php endif; ?>
							<?php for($i_count=1;$i_count<=30;$i_count++){ ?>
								<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_revision?' selected="selected=" ':""); ?>>
								<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
								</option>
							<?php } ?>
						</select>
					</div>
					<?php echo wpj_get_popup( 'job_additional_revision_days_to_deliver_instructions' );

					if($wpjobster_enable_multiples=='yes'){ ?>
						<div class="field multiple-box instructions-popup">
							<div class="ui checkbox">
								<input type="checkbox" class="grey_input" name="enable_multiples_revision" id="enable_multiples_revision" value="<?php echo stripslashes(get_post_meta($pid, 'extra_revision_multiples_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra_revision_multiples_enabled', true)) ? 'checked' : ''; ?>/>
								<label>
									<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
								</label>
							</div>
						</div>
					<?php }
					echo wpj_get_popup( 'job_additional_revision_multiples_instructions' ); ?>
				</div>
			</div>
	<!-- END JOB ADDITIONAL REVISION -->

	<!-- JOB EXTRA -->
			<?php
			if($wpjobster_subscription_noof_extras){
				$wpjobster_enable_extra = 'yes';
			}
			if($wpjobster_enable_extra != "no"):

				$sts = get_option('wpjobster_get_level'.$user_level.'_extras');
				if($wpjobster_subscription_noof_extras)$sts = $wpjobster_subscription_noof_extras;// override only if subscription extra available
				if(empty($sts)) $sts = 10;

				if($sts > 0): ?>
					<div id="all_extras">
						<?php $total_extra_displayed=0;
						for ( $i=1; $i<=$sts; $i++ ):
							$cur_extra_price=(int)get_post_meta( $pid, 'extra' . $i . '_price', true );
							$cur_extra_desc=(int)get_post_meta( $pid, 'extra' . $i . '_content', true );

							if(1<=$cur_extra_price || $i==1){
								$class ='';
							}else{
								$class = 'hidden';
							}

							if ( 1<=$cur_extra_price|| $i==1 ) {
								// make sure that the enabled gets checked if needed
								$enabled = get_post_meta( $pid, 'extra' . $i . '_extra_enabled', false );
								if ( ! isset( $enabled[0] ) && $cur_extra_price && $cur_extra_desc ) {
									update_post_meta( $pid, 'extra' . $i . '_extra_enabled', true );
								}
								$total_extra_displayed++; ?>
								<div id="extra_<?php echo $i; ?>" class="cf p20t bot_bord <?php echo $class;?>">
									<div class="field instructions-popup">

										<div class="ui checkbox">
											<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $i; ?>" id="enable_extra_<?php echo $i; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
											<label>
												<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
											</label>
										</div>

									</div>
									<?php echo wpj_get_popup( 'job_extra_enable_instructions' ); ?>

									<div class="field instructions-popup">
										<label><?php _e('Description','wpjobster'); ?></label>
										<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
										if (get_post_status($pid) == 'pending' && ${"rejected_extra".$i} == 1) echo 'rejected-input'; ?>" name="extra<?php echo $i; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$i.'_content', true); ?></textarea>
											<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
									</div>
									<?php echo wpj_get_popup( 'job_extra_description_instructions', 'edit', 'job', $pid, 'rejected_extra' . $i ); ?>

									<div class="three fields">

										<div class="field instructions-popup">
											<label>
												<?php _e('Price','wpjobster'); ?>
												<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
											</label>

											<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $i; ?>_price" value="<?php echo get_post_meta($pid, 'extra'.$i.'_price', true); ?>" />
										</div>
										<?php echo wpj_get_popup( 'job_extra_price_instructions' ); ?>

										<div class="field instructions-popup">
											<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>
											<select id="max_days_<?php echo $i; ?>" name="max_days_<?php echo $i; ?>" class="grey_input styledselect max-day-deliver uz-listen3 ui dropdown">
												<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
												<?php if($wpjobster_enable_instant_deli != "no"): ?>
													<option <?php echo ($max_days_ex[$i]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
												<?php endif; ?>
												<?php for($i_count=1;$i_count<=30;$i_count++){ ?>
													<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_ex[$i]?' selected="selected=" ':""); ?>>
													<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
													</option>
												<?php } ?>
											</select>
										</div>
										<?php echo wpj_get_popup( 'job_extra_days_to_deliver_instructions' );

										if($wpjobster_enable_multiples=='yes'){ ?>
											<div class="field multiple-box instructions-popup">
												<div class="ui checkbox">
													<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $i; ?>" id="enable_multiples_<?php echo $i; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$i.'_enabled', true)) ? 'checked' : ''; ?>/>
													<label>
														<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
													</label>
												</div>
											</div>
										<?php }
										echo wpj_get_popup( 'job_extra_multiples_instructions' ); ?>
									</div>

									<div class="field delete_extra_wrapper">
										<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $i?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
									</div>
								</div>
							<?php } //endif
						endfor;

						for($j=$i;$j<=10  ;$j++):
							$cur_extra_price=(int)get_post_meta( $pid, 'extra' . $j . '_price', true );

							if( 1<=$cur_extra_price ){

								if(1<=$cur_extra_price || $j==1){
									$class ='';
								}else{
									$class = 'hidden';
								}
								$total_extra_displayed++; ?>

								<div id="extra_<?php echo $j; ?>" class="cf p20t bot_bord <?php echo $class;?>">

									<div class="field instructions-popup">
										<div class="ui checkbox">
											<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $j; ?>" id="enable_extra_<?php echo $j; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
											<label>
												<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
											</label>
										</div>
									</div>
									<?php echo wpj_get_popup( 'job_extra_enable_instructions' ); ?>

									<div class="field instructions-popup">
										<label><?php _e('Description','wpjobster'); ?></label>
										<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
										if (get_post_status($pid) == 'pending' && ${"rejected_extra".$j} == 1) echo 'rejected-input'; ?>" name="extra<?php echo $j; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$j.'_content', true); ?></textarea>
											<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
									</div>
									<?php echo wpj_get_popup( 'job_extra_description_instructions', 'edit', 'job', $pid, 'rejected_extra' . $j ); ?>

									<div class="three fields">

										<div class="field instructions-popup">
											<label>
												<?php _e('Price','wpjobster'); ?>
												<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
											</label>

											<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $j; ?>_price" value="<?php echo get_post_meta($pid, 'extra'.$j.'_price', true); ?>" />
										</div>
										<?php echo wpj_get_popup( 'job_extra_price_instructions' ); ?>

										<div class="field instructions-popup">
											<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>

											<select id="max_days_<?php echo $j; ?>" name="max_days_<?php echo $j; ?>" class="grey_input max-day-deliver styledselect uz-listen3 ui dropdown">
												<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
												<?php if($wpjobster_enable_instant_deli != "no"): ?>
													<option <?php echo ($max_days_ex[$j]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
												<?php endif; ?>
												<?php for($j_count=1;$j_count<=30;$j_count++){ ?>
													<option value="<?php echo $j_count ?>" <?php echo ($j_count==$max_days_ex[$j]?' selected="selected=" ':""); ?>>
													<?php echo sprintf( _n( '%d day', '%d days',$j_count, 'wpjobster' ), $j_count);?>
													</option>
												<?php } ?>
											</select>
										</div>
										<?php echo wpj_get_popup( 'job_extra_days_to_deliver_instructions' );

										if($wpjobster_enable_multiples=='yes'){ ?>
											<div class="field multiple-box instructions-popup">
												<div class="ui checkbox">
													<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $j; ?>" id="enable_multiples_<?php echo $j; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$j.'_enabled', true)) ? 'checked' : ''; ?>/>

													<label>
														<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
													</label>
												</div>
											</div>
											<?php echo wpj_get_popup( 'job_extra_multiples_instructions' );
										} ?>

									</div>

									<div class="field delete_extra_wrapper">
										<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $j?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
									</div>
								</div>
							<?php }
						endfor;

						for($k=$total_extra_displayed+1;$k<=$extras_allowed;$k++):
							$cur_extra_price=(int)get_post_meta( $pid, 'extra' . $k . '_price', true );

							if(1<=$cur_extra_price || $k==1){
								$class ='';
							}else{
								$class = 'hidden';
							} ?>

							<div id="extra_<?php echo $k; ?>" class="cf p20t bot_bord <?php echo $class;?>">

								<div class="field instructions-popup">
									<div class="ui checkbox">
										<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $k; ?>" id="enable_extra_<?php echo $k; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$k.'_extra_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$k.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
										<label>
											<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
										</label>
									</div>
								</div>
								<?php echo wpj_get_popup( 'job_extra_enable_instructions' ); ?>

								<div class="field instructions-popup">
									<label><?php _e('Description','wpjobster'); ?></label>

									<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
									if (get_post_status($pid) == 'pending' && ${"rejected_extra".$k} == 1) echo 'rejected-input'; ?>" name="extra<?php echo $k; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$k.'_content', true); ?></textarea>
										<?php echo '<span class="charscounter"> '.__("characters left.","wpjobster").'</span>'; ?>
								</div>
								<?php echo wpj_get_popup( 'job_extra_description_instructions', 'edit', 'job', $pid, 'rejected_extra' . $k ); ?>

								<div class="three fields">

									<div class="field instructions-popup">
										<label>
											<?php _e('Price','wpjobster'); ?>
											<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
										</label>

										<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $k; ?>_price" value="<?php echo get_post_meta($pid, 'extra'.$k.'_price', true); ?>" />
									</div>
									<?php echo wpj_get_popup( 'job_extra_price_instructions' ); ?>

									<div class="field instructions-popup">
										<label><?php echo __('Max Days to Deliver', 'wpjobster'); ?></label>

										<select id="max_days_<?php echo $k; ?>" name="max_days_<?php echo $k; ?>" class="grey_input styledselect max-day-deliver uz-listen3 ui dropdown">
											<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
											<?php if($wpjobster_enable_instant_deli != "no"): ?>
												<option <?php echo ($max_days_ex[$k]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
											<?php endif; ?>
											<?php for($k_count=1;$k_count<=30;$k_count++){ ?>
												<option value="<?php echo $k_count ?>" <?php echo ($k_count==$max_days_ex[$k]?' selected="selected=" ':""); ?>>
												<?php echo sprintf( _n( '%d day', '%d days',$k_count, 'wpjobster' ), $k_count);?>
												</option>
											<?php } ?>
										</select>
									</div>
									<?php echo wpj_get_popup( 'job_extra_days_to_deliver_instructions' );

									if($wpjobster_enable_multiples=='yes'){ ?>
										<div class="field multiple-box instructions-popup">
											<div class="ui checkbox">
												<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $k; ?>" id="enable_multiples_<?php echo $k; ?>" value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$k.'_enabled', true)); ?>" <?php echo (get_post_meta($pid, 'extra'.$k.'_enabled', true)) ? 'checked' : ''; ?>/>
												<label>
													<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
												</label>
											</div>
										</div>
										<?php echo wpj_get_popup( 'job_extra_multiples_instructions' );
									} ?>
								</div>

								<div class="field delete_extra_wrapper">
									<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $k?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
								</div>
							</div>
						<?php endfor; ?>
					</div><!--#all extras-->

					<div class="field">
						<a href="javascript:void(0);" id="add_extra" class="cursor_pointer <?php echo ($extras_allowed<=$total_extra_displayed)?'hidden':'';?>" ><?php _e("+ Add New Extra", "wpjobster"); ?></a>
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
						</script>
					</div>

				<?php endif;
			endif; ?>
	<!-- END JOB EXTRA -->

			<?php
			$characters_jobtitle_max = get_option("wpjobster_characters_jobtitle_max");
			$characters_jobtitle_max = (empty($characters_jobtitle_max)|| $characters_jobtitle_max==0)?80:$characters_jobtitle_max;
			$wpjobster_characters_description_max = get_option("wpjobster_characters_description_max");
			$wpjobster_characters_description_max = (empty($wpjobster_characters_description_max)|| $wpjobster_characters_description_max==0)?1000:$wpjobster_characters_description_max;
			$wpjobster_characters_instructions_max = get_option("wpjobster_characters_instructions_max");
			$wpjobster_characters_instructions_max = (empty($wpjobster_characters_instructions_max)|| $wpjobster_characters_instructions_max==0)?350:$wpjobster_characters_instructions_max; ?>

	<!-- SCRIPTS -->
			<script>
				extras_allowed = '<?php echo $extras_allowed;?>';
				delete_extras =function(){
					extr_number = $(this).attr('data-rel');
					$.ajax({
							method: 'get',
							url : '<?php echo get_bloginfo('url');?>/index.php/?_extra_delete_pid=<?php echo $pid;?>&extra_no='+extr_number,
							dataType : 'text',
							success: function (text) {
								result_txt = JSON.parse(text);
								if(result_txt.status=='done'){
									jQuery(".delete_extra").click(delete_extras);
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
									$.fn.myFunction();

									$("#extra_"+extr_number).addClass('hidden');
									$("input[name='extra"+extr_number+"_price']").val('');

								}
							}
					 });
				};

				jQuery(document).ready(function(){
					jQuery(".delete_extra").click(delete_extras);
				});

				jQuery(document).ready(function($) {
					jQuery(".charlimit-jobtitle").counted({count:<?php echo $characters_jobtitle_max;?>});
					jQuery(".charlimit-jobdescription").counted({count:<?php echo $wpjobster_characters_description_max;?>});
					jQuery(".charlimit-jobinstruction").counted({count:<?php echo $wpjobster_characters_instructions_max;?>});
				});

				<?php if($wpjobster_enable_extra != "no"){
					$wpjobster_characters_extradescription_max = get_option("wpjobster_characters_extradescription_max");
					$wpjobster_characters_extradescription_max = (empty($wpjobster_characters_extradescription_max)|| $wpjobster_characters_extradescription_max==0)?50:$wpjobster_characters_extradescription_max;
					?>
					function extradescription_charlimit(){
						jQuery(".charlimit-extradescription").counted({count:<?php echo $wpjobster_characters_extradescription_max;?>});
					}
					jQuery(document).ready(function($) {
						extradescription_charlimit();
					});
				<?php } ?>
			</script>
	<!-- END SCRIPTS -->

	<!-- SAVE BUTTON -->
			<div class="field">
				<input class="ui primary button edit-job" type="submit" name="save-job" value="<?php _e("Save Job", 'wpjobster'); ?>" />
			</div>
	<!-- END SAVE BUTTON -->

		</form>
<!-- END FORM -->
	</div><!-- END DIV <div class="post-new-job-wrapper-x ui segment"> -->
<!-- END CONTENT -->

</div><!-- END DIV <div id="content-full-ov" class="page_without_sidebar"> -->
<!-- END EDIT JOB PAGE -->

<div class="ui hidden divider"></div>

<?php get_footer(); ?>
