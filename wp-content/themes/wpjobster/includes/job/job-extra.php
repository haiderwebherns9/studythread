<?php
if ( ! function_exists( 'wpj_extra_delete_pid' ) ) {
	function wpj_extra_delete_pid() {
		if ( ! is_demo_admin() ) {
			if (isset($_GET['_extra_delete_pid'])) {
				if (is_user_logged_in()) {
					$pid = $_GET['_extra_delete_pid'];
					$extra_number = $_GET['extra_no'];
					$pstpst = get_post($pid);

					global $current_user;
					$current_user = wp_get_current_user();

					$wpjobster_enable_instant_deli = get_option('wpjobster_enable_instant_deli');
					$max_days_ex[1]   = get_post_meta($pid, "max_days_ex_1", true);
					$max_days_ex[2]   = get_post_meta($pid, "max_days_ex_2", true);
					$max_days_ex[3]   = get_post_meta($pid, "max_days_ex_3", true);
					$max_days_ex[4]   = get_post_meta($pid, "max_days_ex_4", true);
					$max_days_ex[5]   = get_post_meta($pid, "max_days_ex_5", true);
					$max_days_ex[6]   = get_post_meta($pid, "max_days_ex_6", true);
					$max_days_ex[7]   = get_post_meta($pid, "max_days_ex_7", true);
					$max_days_ex[8]   = get_post_meta($pid, "max_days_ex_8", true);
					$max_days_ex[9]   = get_post_meta($pid, "max_days_ex_9", true);
					$max_days_ex[10]   = get_post_meta($pid, "max_days_ex_10", true);

					if ($pstpst->post_author == $current_user->ID) {
						$extra_available = get_number_of_extras_by_job($pid);

						for($i=$extra_number;$i<$extra_available;$i++){
							$j=$i+1;
							update_post_meta($pid,'extra'.$i.'_price',get_post_meta($pid,'extra'.$j.'_price',true));
							update_post_meta($pid,'extra'.$i.'_content',get_post_meta($pid,'extra'.$j.'_content',true));
							update_post_meta($pid, 'extra'.$i.'_enabled',get_post_meta($pid,'extra'.$j.'_enabled',true));
							$debug[]=array("updating $i price with ".get_post_meta($pid,'extra'.$j.'_price',true)." to $j ");
						}

						$debug[]=array("deleteingno"=>$i);
						$result_delete = delete_post_meta($pid,'extra'.$i.'_price');
						$result_delete = delete_post_meta($pid,'extra'.$i.'_content');
						$result_delete = delete_post_meta($pid,'extra'.$i.'_enabled');

						ob_start();
						$user_level = wpjobster_get_user_level($current_user->ID);

						///// level subscription code /////
						wpj_get_subscription_info_path();
						$wpjobster_subscription_info = get_wpjobster_subscription_info();
						extract($wpjobster_subscription_info);

						///// level subscription code end /////
						$sts = get_option('wpjobster_get_level'.$user_level.'_extras');
						if($wpjobster_subscription_noof_extras)$sts = $wpjobster_subscription_noof_extras;// override only if subscription extra available
						$debug[]=$wpjobster_subscription_info;

						if(empty($sts)) $sts = 10;
						$extras_allowed = $sts;

						$extra_available = get_number_of_extras_by_job($pid);
						if($extra_available<=$sts){
							$debug[]=array("poststatus_updated"=>wp_update_post(array("ID"=>$pid,"post_status"=>"publish")));
							$debug[]=array("posstatus_current"=>get_post_status($pid));
							$debug[]=array("postmeta_deleted"=>delete_post_meta($pid,"more_extras"));
							$more_extras="no";
						}else{
							$more_extras="yes";
						}
						$total_extra_displayed=0;
						$wpjobster_enable_multiples = get_option('wpjobster_enable_multiples');
						for($i=1;$i<=$sts;$i++):
							if(1<=(int)get_post_meta($pid, 'extra'.$i.'_price', true) || $i==1){
								$total_extra_displayed++; ?>
								<div class="row cf p20t bot_bord"  id="extra_<?php echo $i; ?>">
									<div class="row cf p10b">
										<div class="uz-listenh">
											<p class="">
												<label>
													<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $i; ?>" id="enable_extra_<?php echo $i; ?>"
														   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)); ?>"
														<?php echo (get_post_meta($pid, 'extra'.$i.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
													<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
												</label>
											</p>
											<div class="uz-suggesth">
												<?php
												if ( get_field( 'job_extra_enable_instructions', 'options' ) ) {
													the_field( 'job_extra_enable_instructions', 'options' );
												} elseif ( current_user_can( 'manage_options' ) ) {
													_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
												}
												?>
											</div>
										</div>

										<div class=""><?php _e('Description','wpjobster'); ?>
											<p class="lighter">
											<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
											if (get_post_status($pid) == 'pending' && ${"rejected_extra".$i} == 1) echo 'rejected-input'; ?>"
													  name="extra<?php echo $i; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$i.'_content', true); ?></textarea>
												<?php echo '<span> '.__("characters left.","wpjobster").'</span>'; ?>
											</p>
										</div>
										<div class="uz-suggest2 <?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$i} == 1) echo 'uz-visible'; ?>">
											<?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$i} == 1) {
												echo ${"rejected_extra".$i."_comment"};
											} else { ?>
												<?php
												if ( get_field( 'job_extra_description_instructions', 'options' ) ) {
													the_field( 'job_extra_description_instructions', 'options' );
												} elseif ( current_user_can( 'manage_options' ) ) {
													_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
												}
												?>
											<?php } ?>
										</div>
									</div>
									<div class="row cf">
										<div class="one-fifth pdd10r left">
											<?php _e('Price','wpjobster'); ?>
											<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
											<p class="lighter">
												<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $i; ?>_price"
													   value="<?php echo get_post_meta($pid, 'extra'.$i.'_price', true); ?>" />
											</p>
										</div>
										<div class="two-fifths pdd10l left"><?php echo __('Max Days to Deliver', 'wpjobster'); ?>
											<div class="grey_select">
															<span class="left w100 relative">
																<select id="max_days_<?php echo $i; ?>" name="max_days_<?php echo $i; ?>" class="grey_input styledselect uz-listen3" >
																	<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
																	<?php if($wpjobster_enable_instant_deli != "no"): ?>
																		<option <?php echo ($max_days_ex[$i]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
																	<?php endif; ?>
																	<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
																		<option value="<?php echo $i_count ?>" <?php echo ($i_count==$max_days_ex[$i]?' selected="selected=" ':""); ?>>
																		<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
																		</option>
																	<?php } ?>
																</select>
															</span>
											</div>
										</div>
										<?php if($wpjobster_enable_multiples=='yes'){ ?>
											<div class="one-fifth pdd10l pdd29t left uz-listenh">
												<p class="lighter">
													<label>
														<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $i; ?>" id="enable_multiples_<?php echo $i; ?>"
															   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$i.'_enabled', true)); ?>"
															<?php echo (get_post_meta($pid, 'extra'.$i.'_enabled', true)) ? 'checked' : ''; ?>/>
														<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
													</label>
												</p>
												<div class="uz-suggesth">
													<?php
													if ( get_field( 'job_extra_multiples_instructions', 'options' ) ) {
														the_field( 'job_extra_multiples_instructions', 'options' );
													} elseif ( current_user_can( 'manage_options' ) ) {
														_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
													}
													?>
												</div>
											</div>
										<?php } ?>
										<div class="pdd29t">
											<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $i?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
										</div>
										<div class="uz-suggest1">
											<?php
											if ( get_field( 'job_extra_price_instructions', 'options' ) ) {
												the_field( 'job_extra_price_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										</div>
										<div class="uz-suggest3">
											<?php
											if ( get_field( 'job_extra_days_to_deliver_instructions', 'options' ) ) {
												the_field( 'job_extra_days_to_deliver_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										</div>
									</div>

								</div>
							<?php }//endif
						endfor;

						for($j=$i;$j<=10 ;$j++):
							if(1<=(int)get_post_meta($pid, 'extra'.$j.'_price', true)){
								$total_extra_displayed++; ?>
								<div class="row cf p20t bot_bord" id="extra_<?php echo $j; ?>">
									<div class="row cf">
										<div class="uz-listenh">
											<p class="">
												<label>
													<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $j; ?>" id="enable_extra_<?php echo $j; ?>"
														   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)); ?>"
														<?php echo (get_post_meta($pid, 'extra'.$j.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
													<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
												</label>
											</p>
											<div class="uz-suggesth">
												<?php
												if ( get_field( 'job_extra_enable_instructions', 'options' ) ) {
													the_field( 'job_extra_enable_instructions', 'options' );
												} elseif ( current_user_can( 'manage_options' ) ) {
													_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
												}
												?>
											</div>
										</div>

										<div class=""><?php _e('Description','wpjobster'); ?>
											<p class="lighter">
												<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
												if (get_post_status($pid) == 'pending' && ${"rejected_extra".$j} == 1) echo 'rejected-input'; ?>"
														  name="extra<?php echo $j; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$j.'_content', true); ?></textarea>
												<?php echo '<span> '.__("characters left.","wpjobster").'</span>'; ?>
											</p>
										</div>
										<div class="uz-suggest2 <?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$j} == 1) echo 'uz-visible'; ?>">
											<?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$j} == 1) {
												echo ${"rejected_extra".$j."_comment"};
											} else { ?>
												<?php
												if ( get_field( 'job_extra_description_instructions', 'options' ) ) {
													the_field( 'job_extra_description_instructions', 'options' );
												} elseif ( current_user_can( 'manage_options' ) ) {
													_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
												}
												?>
											<?php } ?>
										</div>
									</div>
									<div class="row cf">
										<div class="one-fifth pdd10r left">
											<?php _e('Price','wpjobster'); ?>
											<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
											<p class="lighter">
												<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $j; ?>_price"
													   value="<?php echo get_post_meta($pid, 'extra'.$j.'_price', true); ?>" />
											</p>
										</div>
										<div class="two-fifths pdd10l left"><?php echo __('Max Days to Deliver', 'wpjobster'); ?>
											<div class="grey_select">
																<span class="left w100 relative">
																	<select id="max_days_<?php echo $j; ?>" name="max_days_<?php echo $j; ?>" class="grey_input styledselect uz-listen3" >
																		<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
																		<?php if($wpjobster_enable_instant_deli != "no"): ?>
																			<option <?php echo ($max_days_ex[$j]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
																		<?php endif; ?>
																		<?php for($j_count=1;$j_count<=get_option( 'wpjobster_job_max_delivery_days' );$j_count++){ ?>
																			<option value="<?php echo $j_count ?>" <?php echo ($j_count==$max_days_ex[$j]?' selected="selected=" ':""); ?>>
																			<?php echo sprintf( _n( '%d day', '%d days',$j_count, 'wpjobster' ), $j_count);?>
																			</option>
																		<?php } ?>
																	</select>
																</span>
											</div>
										</div>
										<?php if($wpjobster_enable_multiples=='yes'){ ?>
											<div class="one-fifth pdd10l pdd29t left uz-listenh">
												<p class="lighter">
													<label>
														<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $j; ?>" id="enable_multiples_<?php echo $j; ?>"
															   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$j.'_enabled', true)); ?>"
															<?php echo (get_post_meta($pid, 'extra'.$j.'_enabled', true)) ? 'checked' : ''; ?>/>
														<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
													</label>
												</p>
												<div class="uz-suggesth">
													<?php
													if ( get_field( 'job_extra_multiples_instructions', 'options' ) ) {
														the_field( 'job_extra_multiples_instructions', 'options' );
													} elseif ( current_user_can( 'manage_options' ) ) {
														_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
													}
													?>
												</div>
											</div>
										<?php } ?>
										<div class="pdd29t">
											<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $j?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
										</div>
										<div class="uz-suggest1">
											<?php
											if ( get_field( 'job_extra_price_instructions', 'options' ) ) {
												the_field( 'job_extra_price_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										</div>
										<div class="uz-suggest3">
											<?php
											if ( get_field( 'job_extra_days_to_deliver_instructions', 'options' ) ) {
												the_field( 'job_extra_days_to_deliver_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										</div>
									</div>
								</div>
							<?php }
						endfor;

						for($k=$total_extra_displayed+1;$k<=$extras_allowed;$k++): ?>
							<div class="row cf p20t bot_bord hidden" id="extra_<?php echo $k; ?>">
								<div class="row cf p10b">
									<div class="uz-listenh">
										<p class="">
											<label>
												<input type="checkbox" class="grey_input" name="enable_extra_<?php echo $k; ?>" id="enable_extra_<?php echo $k; ?>"
													   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$k.'_extra_enabled', true)); ?>"
													<?php echo (get_post_meta($pid, 'extra'.$k.'_extra_enabled', true)) ? 'checked' : ''; ?>/>
												<?php echo '<span> '.__("Extra","wpjobster").'</span>'; ?>
											</label>
										</p>
										<div class="uz-suggesth">
											<?php
											if ( get_field( 'job_extra_enable_instructions', 'options' ) ) {
												the_field( 'job_extra_enable_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										</div>
									</div>

									<div class=""><?php _e('Description','wpjobster'); ?>
										<p class="lighter">
										<textarea class="grey_input one_line charlimit-extradescription uz-listen2 <?php
										if (get_post_status($pid) == 'pending' && ${"rejected_extra".$k} == 1) echo 'rejected-input'; ?>"
												  name="extra<?php echo $k; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$k.'_content', true); ?></textarea>
											<?php echo '<span> '.__("characters left.","wpjobster").'</span>'; ?>
										</p>
									</div>
									<div class="uz-suggest2 <?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$k} == 1) echo 'uz-visible'; ?>">
										<?php if (get_post_status($pid) == 'pending' && ${"rejected_extra".$k} == 1) {
											echo ${"rejected_extra".$k."_comment"};
										} else { ?>
											<?php
											if ( get_field( 'job_extra_description_instructions', 'options' ) ) {
												the_field( 'job_extra_description_instructions', 'options' );
											} elseif ( current_user_can( 'manage_options' ) ) {
												_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
											}
											?>
										<?php } ?>
									</div>
								</div>
								<div class="row cf">
									<div class="one-fifth pdd10r left">
										<?php _e('Price','wpjobster'); ?>
										<?php echo '<span class="lighter">&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')</span>'; ?>
										<p class="lighter">
											<input class="grey_input uz-listen1" type="number" step="any" min="1" max="<?php echo $allowed_max_extra_price; ?>" size="3" name="extra<?php echo $k; ?>_price"
												   value="<?php echo get_post_meta($pid, 'extra'.$k.'_price', true); ?>" />
										</p>
									</div>
									<div class="two-fifths pdd10l left"><?php echo __('Max Days to Deliver', 'wpjobster'); ?>
										<div class="grey_select">
														<span class="left w100 relative">
															<select id="max_days_<?php echo $k; ?>" name="max_days_<?php echo $k; ?>" class="grey_input styledselect uz-listen3" >
																<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
																<?php if($wpjobster_enable_instant_deli != "no"): ?>
																	<option <?php echo ($max_days_ex[$k]=='instant'?' selected="selected" ' :'');?> value="instant"><?php _e('Instant', 'wpjobster'); ?></option>
																<?php endif; ?>
																<?php for($k_count=1;$k_count<=get_option( 'wpjobster_job_max_delivery_days' );$k_count++){ ?>
																	<option value="<?php echo $k_count ?>" <?php echo ($k_count==$max_days_ex[$k]?' selected="selected=" ':""); ?>>
																	<?php echo sprintf( _n( '%d day', '%d days',$k_count, 'wpjobster' ), $k_count);?>
																	</option>
																<?php } ?>
															</select>
														</span>
										</div>
									</div>
									<?php if($wpjobster_enable_multiples=='yes'){ ?>
										<div class="one-fifth pdd10l pdd29t left uz-listenh">
											<p class="lighter">
												<label>
													<input type="checkbox" class="grey_input" name="enable_multiples_<?php echo $k; ?>" id="enable_multiples_<?php echo $k; ?>"
														   value="<?php echo stripslashes(get_post_meta($pid, 'extra'.$k.'_enabled', true)); ?>"
														<?php echo (get_post_meta($pid, 'extra'.$k.'_enabled', true)) ? 'checked' : ''; ?>/>
													<?php echo '<span> '.__("Multiple","wpjobster").'</span>'; ?>
												</label>
											</p>
											<div class="uz-suggesth">
												<?php
												if ( get_field( 'job_extra_multiples_instructions', 'options' ) ) {
													the_field( 'job_extra_multiples_instructions', 'options' );
												} elseif ( current_user_can( 'manage_options' ) ) {
													_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
												}
												?>
											</div>
										</div>
									<?php } ?>
									<div class="pdd29t">
										<a href="javascript:void(0);" class="delete_extra" data-rel="<?php echo $k?>"><?php _e("- Delete Extra", "wpjobster"); ?></a>
									</div>
									<div class="uz-suggest1">
										<?php
										if ( get_field( 'job_extra_price_instructions', 'options' ) ) {
											the_field( 'job_extra_price_instructions', 'options' );
										} elseif ( current_user_can( 'manage_options' ) ) {
											_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
										}
										?>
									</div>
									<div class="uz-suggest3">
										<?php
										if ( get_field( 'job_extra_days_to_deliver_instructions', 'options' ) ) {
											the_field( 'job_extra_days_to_deliver_instructions', 'options' );
										} elseif ( current_user_can( 'manage_options' ) ) {
											_e( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
										}
										?>
									</div>
								</div>
							</div>
						<?php endfor;

						$debug[]=array("extras_allowed"=>$extras_allowed);
						$html = ob_get_contents();
						ob_end_clean();
						echo json_encode(array("status"=>"done","extra_available"=>$extra_available,"html"=>$html,"more_extras"=>$more_extras,"debug"=>$debug,"total_extra_displayed"=>$total_extra_displayed));
					}
				}
				exit;
			}
		}
	}
}
add_action( 'init', 'wpj_extra_delete_pid' );

function get_number_of_extras_by_job($post_id){
	$all_extras = 0;
	for($k_extras= 1;$k_extras<=10;$k_extras++){
		$extra_price = get_post_meta($post_id, 'extra'.$k_extras.'_price', true);
		if(trim($extra_price)!='' && trim($extra_price)!='0' ){
			$all_extras++;
		}
	}
	return $all_extras;
}
