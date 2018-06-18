<?php
if(!function_exists('wpjobster_my_account_pers_info_area_function')) {
	function wpjobster_my_account_pers_info_area_function() {

		ob_start();

		wpjobster_init_uploader_scripts();
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		$wpjobster_characters_personalinfo_max = get_option("wpjobster_characters_personalinfo_max");
		$wpjobster_characters_personalinfo_max = (empty($wpjobster_characters_personalinfo_max)|| $wpjobster_characters_personalinfo_max==0)?500:$wpjobster_characters_personalinfo_max;
		?>

 <script>
	$(document).ready(function (){
		 	$(".form.edit-profile").submit(function(e){
		    if(($('#tcollege').val()=="")) {
            $('#tcollege').focus();
			$('#tcollege' ).next( ".err_msg" ).html("Error:  college or university attending required");
			$('#tcollege' ).next( ".err_msg" ).css( {"display":"block","color":"red","margin":"5%"} ).fadeOut( 3000 );
		    e.preventDefault();
			return false;
            }
			if(($('#tedu').val()=="")) {
            $('#tedu').focus();
			$('#tedu' ).next( ".err_msg" ).html("Error:  level of education required");
			$('#tedu' ).next( ".err_msg" ).css( {"display":"block","color":"red","margin":"5%"} ).fadeOut( 3000 );
	        e.preventDefault();
			return false;
            }
			if(($('#tdegre').val()=="")) {
            $('#tdegre').focus();
			$('#tdegre' ).next( ".err_msg" ).html("Error:  studying or earned degree required");
			$('#tdegre' ).next( ".err_msg" ).css( {"display":"block","color":"red","margin":"5%"} ).fadeOut( 3000 );
            e.preventDefault();
			return false;
            }
         });
	 });
    </script>   

		<div id="content-full-ov">
			<!-- page content here -->
			<div class="ui two column stackable grid">
				<div class="ui row">
				<div class="thirteen wide column stackable-buttons">
					<h1><?php _e("Personal Info",'wpjobster'); ?></h1>

					<?php
					global $wp_query;
					$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'home';
					$pages = array( 'home', 'profile', 'security', /*'billing',*/ 'payments' );
					if( ! in_array($pg, $pages) ){ $pg = 'home'; }

					$personal_info_pg_lnk = get_permalink( get_option( 'wpjobster_my_account_personal_info_page_id' ) );
					?>
					<a class="ui white button <?php if ( $pg == 'home' ) { echo 'active'; } ?>" href="<?php echo $personal_info_pg_lnk; ?>"><?php _e( 'Account', 'wpjobster' ); ?></a>
					<a class="ui white button <?php if ( $pg == 'profile' ) { echo 'active'; } ?>" href="<?php echo $personal_info_pg_lnk; ?>profile"><?php _e( 'Profile', 'wpjobster' ); ?></a>
					<a class="ui white button <?php if ( $pg == 'security' ) { echo 'active'; } ?>" href="<?php echo $personal_info_pg_lnk; ?>security"><?php _e( 'Security', 'wpjobster' ); ?></a>

					<?php /* // to be added later //
					<a class="ui white button <?php if ( $pg == 'billing' ) { echo 'active'; } ?>" href="<?php echo $personal_info_pg_lnk; ?>billing"><?php _e( 'Billing', 'wpjobster' ); ?></a>
					// to be added later // */ ?>

					<a class="ui white button <?php if ( $pg == 'payments' ) { echo 'active'; } ?>" href="<?php echo $personal_info_pg_lnk; ?>payments"><?php _e( 'Payments', 'wpjobster' ); ?></a>

				</div>
				<div class="three wide column">
					<div class="personal-info-avatar right">
						<?php wpjobster_avatar_upload_html5(75, 75); ?>
					</div>
				</div>
				</div>
			</div>

			<div class="ui segment">

				<?php wpj_personal_info_vars(); ?>

				<form class="ui form edit-profile" method="post" enctype="multipart/form-data">
					<div class="uz-form full-width-inputs">
                
						<?php if ( $pg == 'home' ) { ?>
                               <?php $type=user($uid, 'wpjobster_user_type');?>
							<div class="field">
								<div class="two fields navin">
									<div class="field">
										<label><?php echo __('First name','wpjobster'); ?></label>
										<div class="input-relative">
											<input type="text" name="first_name" value="<?php echo empty($_POST['first_name']) ? stripslashes(user($uid, 'first_name')) : stripslashes($_POST['first_name']); ?>" placeholder="<?php _e( 'First Name', 'wpjobster' ); ?>" <?php if(user($uid, 'first_name')){echo 'readonly';} ?> />
											<?php if(user($uid, 'first_name')){echo '<i class="lock icon"></i>';} ?>
										</div>

									</div>
									<div class="field">
										<label><?php echo __('Last name','wpjobster'); ?></label>
										<div class="input-relative">
											<input type="text" name="last_name" value="<?php echo empty($_POST['last_name']) ? stripslashes(user($uid, 'last_name')) : stripslashes($_POST['last_name']); ?>" placeholder="<?php _e( 'Last Name', 'wpjobster' ); ?>" <?php if(user($uid, 'last_name')){echo 'readonly';} ?> />
											<?php if(user($uid, 'first_name')){echo '<i class="lock icon"></i>';} ?>
										</div>

									</div>
								</div>
							</div>

							<?php
								$user_company = get_user_meta( $uid, 'user_company', true );
								$company = empty( $_POST['company'] ) ? $user_company : stripslashes( $_POST['company'] );
                                $tax_id = get_user_meta( $uid, 'tax_id', true );
								$tax_id = empty( $_POST['tax_id'] ) ? $tax_id : stripslashes( $_POST['tax_id'] );
							?>
							<?php if ( get_option( 'wpjobster_enable_user_company' ) == 'yes' ) { ?>
								<div class="field">
									<div class="two fields">
										<div class="field">
											<label><?php echo __('Company', "wpjobster"); ?></label>
											<input type="text" value="<?php echo $company; ?>" name="user_company" size="40" placeholder="<?php echo _x( 'My Company LLC', 'Placeholder for: Company', 'wpjobster' ); ?>" />
										</div>
										<div class="field">
											<label><?php echo __('Tax ID', "wpjobster"); ?></label>
											<input type="text" value="<?php echo $tax_id; ?>" name="tax_id" size="40" placeholder="<?php echo _x( 'XX-XXXXXXX', 'Placeholder for: Tax ID', 'wpjobster' ); ?>" />
										</div>
									</div>
								</div>
							<?php } ?>

							<div class="field">
								<div class="two fields">
									<div class="field">
										<label><?php echo __('Address','wpjobster'); ?></label>
										<input type="text" name="address" placeholder="<?php echo _x( '123 Street Name, Province', 'Placeholder for: Address', 'wpjobster' ); ?>" value="<?php echo user($uid, 'address'); ?>" />
									</div>

									<div class="field">
										<label><?php echo __('City','wpjobster'); ?></label>
										<input type="text" name="city" placeholder="<?php _e( 'City', 'wpjobster' ); ?>" value="<?php echo empty($_POST['city']) ? stripslashes(user($uid, 'city')) : stripslashes($_POST['city']); ?>" />
									</div>
								</div>
							</div>

							<div class="field">
								<div class="two fields">
									<div class="field">
										<?php
                                            $cid=user($uid, 'country_id');	
											global $wpdb;
											$query = "SELECT * FROM 	wp_87fsrr_country";
											$res= $wpdb->get_results($query);
											?>
										<label><?php echo __('Country','wpjobster'); ?></label>
										<select class="ui search dropdown new-post-category" name="country_code">
											<?php foreach($res as $res_val){ ?>
							                  <option value="<?php echo $res_val->country_id;?>" <?php if($res_val->country_id==$cid){ ?>selected<?php } ?> ><?php echo $res_val->country_name;?></option>
						                    <?php }?>
											<?php
												//$c = get_country_name();
                                                 //list_options($c,user($uid, 'country_code'));
											?>
										</select>
									</div>

									<div class="field">
										<label><?php echo __('ZIP Code','wpjobster'); ?></label>
										<input type="text" name="zip" value="<?php echo empty($_POST['zip']) ? stripslashes(user($uid, 'zip')) : stripslashes($_POST['zip']); ?>" placeholder="<?php echo _x( '123456', 'Placeholder for: ZIP Code', 'wpjobster' ); ?>" />
									</div>
								</div>
							</div>
							<?php if($type=='seller') { ?>
                            <div class="field">
								<div class="two fields">
									<div class="field">
										  <label>College or University Attending(ed):</label>
										   <input type="text" id="tcollege" value="<?php echo empty($_POST['teacher_college']) ? stripslashes(user($uid, 'teacher_college')) : stripslashes($_POST['teacher_college']); ?>"name="teacher_college" placeholder="College or University Attending">
									        <label class="err_msg" style="display:none;"></label>
									</div>
									<div class="field">
										  <label>Level of Education:</label>
										   <input type="text" id="tedu" value="<?php echo empty($_POST['teacher_education']) ? stripslashes(user($uid, 'teacher_education')) : stripslashes($_POST['teacher_education']); ?>"name="teacher_education" placeholder="Level of Education">
										  <label class="err_msg" style="display:none;"></label>
									</div>
								</div>	
							</div>	
							 <div class="field">
								<div class="two fields">
									<div class="field">
										  <label>Studying or earned Degree:</label>
										   <input type="text" id="tdegre" value="<?php echo empty($_POST['teacher_degree']) ? stripslashes(user($uid, 'teacher_degree')) : stripslashes($_POST['teacher_degree']); ?>"name="teacher_degree" placeholder="Studying or earned Degree">
										  <label class="err_msg" style="display:none;"> </label>
									</div>
									<div class="field">
										  <label>Enter Bkash Number(Only for Bangladesh):</label>
										   <input type="text" id="tbkash" value="<?php echo empty($_POST['Bkash_number']) ? stripslashes(user($uid, 'Bkash_number')) : stripslashes($_POST['Bkash_number']); ?>"name="Bkash_number" placeholder="Enter Bkash Number(Only for Bangladesh)">
                                     </div>
								</div>	
							</div>	
							<?php } ?>
							<div class="field">
								<div class="two fields">
									<div class="field grey_select">
										<label><?php echo __('Timezone','wpjobster'); ?></label>
										<select id="timezone_select" class="ui search dropdown new-post-category" name="timezone_select">
										<?php
											$tm = get_timezone_name();
											list_options($tm,user($uid, 'timezone_select'));
										?>
										</select>
									</div>

									<div class="field">
										<?php if (count(get_preferred_languages()) > 1) { ?>
											<label><?php echo __('Preffered language','wpjobster'); ?></label>
											<select class="ui dropdown styledselect new-post-category" name="preferred_language">
											<?php
												$pl = get_preferred_languages();
												list_options($pl,user($uid, 'preferred_language'));
											?>
											</select>
										<?php } ?>
									</div>


								</div>
							</div>



							<?php
							$user_info = get_userdata($uid);
							$user_mail = $user_info->user_email;
							$email = empty($_POST['email']) ? $user_mail : stripslashes($_POST['email']);
							?>
							<div class="field">
								<div class="two fields">
									<div class="field">
										<label><?php echo __('Email', "wpjobster"); ?></label>
										<input type="email" value="<?php echo $email; ?>" name="email" placeholder="<?php echo _x( 'email@example.com', 'Placeholder for: Email', 'wpjobster' ); ?>" size="40" />
									</div>

									<div class="field">
										<label class="cb"><?php echo __('Phone Number','wpjobster'); ?></label>
										<?php
										$phone_number = empty($_POST['cell_number']) ? user($uid, 'cell_number') : stripslashes($_POST['cell_number']);
										$phone_number = wpjobster_phone_number_format($uid, $phone_number);
										?>
										<p class="lighter"><input class="cell_number" type="text" name="cell_number" data-default-country="<?php echo get_option('wpjobster_phone_country_select'); ?>" value="<?php echo $phone_number; ?>" size="40" data-country="<?php echo get_user_meta( $uid, 'country_code', true ); ?>" placeholder="<?php echo _x( '+123456789000', 'Placeholder for: Phone Number', 'wpjobster' ); ?>"" /></p>

									</div>
								</div>
							</div>

							<?php do_action( 'wpjobster_user_profile_extra_fields_display', $uid ); ?>

						<?php } elseif ( $pg == 'profile' ) { ?>

							<div class="field">

								<?php if(get_option('wpjobster_wysiwyg_for_profile') != 'yes'){ ?>
									<label class="cb"><?php echo __('Profile Description','wpjobster'); ?>
									<p class="lighter"><textarea class="charlimit-personalinfo" type="textarea" cols="30" rows="5" name="personal_info"><?php echo strip_tags(user($uid, 'personal_info')); ?></textarea>
													<span> <?php _e("Characters left","wpjobster");?></span>
													</p>
									</label>
								<?php } else {
									$max_chr_description = get_option( 'wpjobster_characters_personalinfo_max' ) ?: 1000; ?>
									<label><?php echo __('Profile Description','wpjobster'); ?>
										<textarea id="job_description" class="lighter job-description-wysiwyg job-description-wysiwyg-style" type="textarea" cols="30" rows="5" name="personal_info"><?php echo user($uid, 'personal_info'); ?></textarea>

										<div id="job_description_toolbar" class="job-description-wysiwyg-toolbar">
											<a data-wysihtml5-command="bold"><i class="bordered bold icon"></i></a>
											<a data-wysihtml5-command="italic"><i class="bordered italic icon"></i></a>
											<a data-wysihtml5-command="underline"><i class="bordered underline icon"></i></a>
											<a data-wysihtml5-command="insertUnorderedList"><i class="bordered unordered list icon"></i></a>
											<a data-wysihtml5-command="insertOrderedList"><i class="bordered ordered list icon"></i></a>
										</div>

										<div class="char-count lighter"><?php echo ' / ' . $max_chr_description . ' ' . __( 'Characters', 'wpjobster' ); ?></div>
									</label>
								<?php } ?>


								<?php if ( get_option( 'wpjobster_wysiwyg_for_profile' ) == 'yes' ) { ?>
									<script>
										jQuery(document).ready(function($){
											max_chr_description = '<?php echo $max_chr_description; ?>';
											wpj_js_description_args_allowed( max_chr_description );
										});
									</script>
								<?php } ?>

							</div>

							<?php
							$display_portofolio = apply_filters( 'display_or_hide_section_filter', true );
							if ( get_option('wpjobster_enable_user_profile_portfolio') == 'yes' && $display_portofolio == 'true' ) { ?>
								<div class="field">
									<?php
									echo __('Profile Portfolio', 'wpjobster');
									wpjobster_dropzone_image_uploader( $uid, 'portfolio' );
									?>
								</div>
							<?php } ?>

						<?php } elseif ( $pg == 'security' ) { ?>

							<div class="field">
								<div class="two fields">
									<div class="field">
										<label><?php _e( "New Password", "wpjobster" ); ?></label>
										<input  type="password" value="" placeholder="<?php _e( "New Password", "wpjobster" ); ?>" name="password" />
									</div>
									<div class="field">
										<label><?php _e( "Repeat Password", "wpjobster" ); ?></label>
										<input type="password" value="" placeholder="<?php _e( "Repeat Password", "wpjobster" ); ?>" name="reppassword" />
									</div>
								</div>
							</div>

						<?php } elseif ( $pg == 'billing' ) { ?>

						<?php } elseif ( $pg == 'payments' ) { ?>

							<?php if (get_option('wpjobster_enable_paypal_withdraw') != "no" || get_option('wpjobster_enable_payoneer_withdraw') != "no" || get_option('wpjobster_enable_bank_withdraw') != "no") { ?>

							<?php }

							if ( !class_exists( 'WPJobster_Payoneer_Loader' ) || get_option( 'wpjobster_payoneer_enable' ) == 'no' ) {
								if (get_option('wpjobster_enable_paypal_withdraw') != "no") { ?>

									<div id="paypal-payments" class="field paypal-payment-wrapper">
										<label><?php _e('PayPal Payment', 'wpjobster'); ?></label>
										<div class="two fields">
											<div class="field">
												<input type="text" name="paypal_email" placeholder="<?php _e( 'PayPal Email', 'wpjobster' ); ?>" value="<?php echo user($uid, 'paypal_email'); ?>" <?php if(user($uid, 'paypal_email')){echo 'readonly';} ?> />
												<?php if(user($uid, 'paypal_email')){echo '<i class="lock icon"></i>';} ?>
											</div>
										</div>
									</div>

								<?php }

								if (get_option('wpjobster_enable_payoneer_withdraw') != "no") { ?>

									<div id="payoneer-payments" class="field">
										<label><?php _e('Payoneer Payment', 'wpjobster'); ?></label>
										<div class="two fields">
											<div class="field">
												<input type="text" name="payoneer_email" placeholder="<?php _e( 'Payoneer Email', 'wpjobster' ); ?>" value="<?php echo user($uid, 'payoneer_email'); ?>" <?php if(user($uid, 'payoneer_email')){echo 'readonly';} ?> />
												<?php if(user($uid, 'payoneer_email')){echo '<i class="lock icon"></i>';} ?>

											</div>

											<div class="field">

												<input type="text" name="payoneer_card" placeholder="<?php _e( 'Payoneer Card', 'wpjobster' ); ?>" value="<?php echo user($uid, 'payoneer_card'); ?>" <?php if(user($uid, 'payoneer_card')){echo 'readonly';} ?> />
												<?php if(user($uid, 'payoneer_card')){echo '<i class="lock icon"></i>';} ?>

											</div>
										</div>
									</div>

								<?php }

								if (get_option('wpjobster_enable_bank_withdraw') != "no") { ?>

									<div id="bank-payments" class="field">

										<label><?php _e('Bank Payment', 'wpjobster'); ?></label>

										<div class="two fields">
											<div class="field">
												<input type="text" name="bank_bank_name" placeholder="<?php _e( 'Bank Name', 'wpjobster' ); ?>" value="<?php echo user($uid, 'bank_bank_name'); ?>" <?php if(user($uid, 'bank_bank_name')){echo 'readonly';} ?> />
												<?php if(user($uid, 'bank_bank_name')){echo '<i class="lock icon"></i>';} ?>
											</div>
											<div class="field">
												<input type="text" name="bank_bank_address" placeholder="<?php _e( 'Bank Address', 'wpjobster' ); ?>" value="<?php echo user($uid, 'bank_bank_address'); ?>" <?php if(user($uid, 'bank_bank_address')){echo 'readonly';} ?> />
												<?php if(user($uid, 'bank_bank_address')){echo '<i class="lock icon"></i>';} ?>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="two fields">
											<div class="field">
											<input type="text" name="bank_account_name" placeholder="<?php _e( 'Bank Account Name', 'wpjobster' ); ?>" value="<?php echo user($uid, 'bank_account_name'); ?>" <?php if(user($uid, 'bank_account_name')){echo 'readonly';} ?> />
											<?php if(user($uid, 'bank_account_name')){echo '<i class="lock icon"></i>';} ?>
											</div>
											<div class="field">
											<input type="text" name="bank_account_number" placeholder="<?php _e( 'Bank Account Number', 'wpjobster' ); ?>" value="<?php echo user($uid, 'bank_account_number'); ?>" <?php if(user($uid, 'bank_account_number')){echo 'readonly';} ?> />
											<?php if(user($uid, 'bank_account_number')){echo '<i class="lock icon"></i>';} ?>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="two fields">
											<div class="field">
												<input type="text" name="bank_account_currency" placeholder="<?php _e( 'Bank Account Currency', 'wpjobster' ); ?>" value="<?php echo user($uid, 'bank_account_currency'); ?>" <?php if(user($uid, 'bank_account_currency')){echo 'readonly';} ?> />
												<?php if(user($uid, 'bank_account_currency')){echo '<i class="lock icon"></i>';} ?>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="two fields">
											<div class="field">
												<textarea row="4" type="textarea" placeholder="<?php _e( 'Bank Additional Info', 'wpjobster' ); ?>" name="bank_additional_info"><?php echo user($uid, 'bank_additional_info'); ?></textarea>
											</div>
										</div>
									</div>

								<?php }
								do_action( 'wpjobster_show_withdraw_personalinfo_gateway', $uid );
							}
							do_action( 'wpjobster_show_payoneer_personalinfo_gateway', $uid );
						} ?>

						<div class="field">
							<input class="ui primary button" type="submit" name="save-info" value="<?php _e("Save Changes" ,'wpjobster'); ?>" />
						</div>
					</div>
				</form>

				<script>
					jQuery(document).ready(function($) {
						jQuery(".charlimit-personalinfo").counted({count:<?php echo $wpjobster_characters_personalinfo_max;?>});
					});
				</script>

			</div>
			<div class="ui hidden divider"></div>
		</div>

		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
} ?>
