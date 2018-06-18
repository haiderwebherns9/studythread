<div class="ui segment white-cnt padding-cnt" >
	<h2 class="heading-subtitle"><?php _e('Schedule Upgrade', 'wpjobster'); ?></h2>
	<div>
		<form id="subscripton-form" action="<?php echo get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ); ?>" method="GET">
			<input type="hidden" name="jb_action" value="schedule_subscription"/>
			<input type="hidden" name="schedule_only" value ="1" />

			<div class="bs-table-header cf">
				<div class="bs-col-container cf">
					<div class="bs-col8"><?php _e('Subscription', 'wpjobster'); ?></div>
					<div class="bs-col66888fill"><?php _e('Eligibility', 'wpjobster'); ?></div>
					<div class="bs-col66888fill"><?php _e('Features', 'wpjobster'); ?></div>
					<div class="bs-col66888fill"><?php _e('Billing', 'wpjobster'); ?></div>
				</div>
			</div>

			<?php foreach($this->subscription_arr as $key =>$value){
				if(get_option('wpjobster_subscription_level_2_enabled') != 'yes'){
					$level2 = 'level2';
				}else{
					$level2 = '';
				}
				if(get_option('wpjobster_subscription_level_3_enabled') != 'yes'){
					$level3 = 'level3';
				}else{
					$level3 = '';
				}

				if($key != $level2 && $key != $level3){
					$amount_chargable = $this->subscription_arr[$key][$current_type];
					if($this->subscription_eligibility_enabled=='yes'){
						if(wpjobster_formats_special($this->current_user_sales,2) >=wpjobster_formats_special($this->subscription_arr[$key]['eligility'],2)){
							$eligible_for_sub = '';
							$eligible_str= "<br />".__("Current amount of sales", "wpjobster").": ".wpjobster_get_show_price($this->current_user_sales);
									$eligible_str .= "<br />".__("ELIGIBLE", "wpjobster");
							$eligible_class = "subscription-eligible";
						}else{
							$eligible_for_sub = ' disabled="disabled" ';
									$eligible_str= "<br />".__("Current amount of sales", "wpjobster").": ".wpjobster_get_show_price($this->current_user_sales);
									$eligible_str .= "<br />". __("NOT ELIGIBLE", "wpjobster");
							$eligible_class = "subscription-not-eligible";
						}
					}else{
						$eligible_for_sub = '';
						$eligible_str = "";
						$eligible_class = "subscription-eligible";
					} ?>

					<div class="bs-table-row cf hover-break <?php echo $eligible_class;?>">
						<div class="bs-col-container cf">
							<div class="bs-col8"><?php echo translate_subscription_strings($key); ?></div>
							<div class="bs-col66888fill ">
							<?php if($this->subscription_arr[$key]['eligility']!='' && $this->subscription_arr[$key]['eligility']!='0'){ _e('Minimum amount of sales', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['eligility']);}else{echo __("NA", "wpjobster");}
									 echo $eligible_str;
							?>
							</div>
							<div class="bs-col66888fill ">
								<?php
									$param1 = $this;
									do_action('list_user_type', $param1, $key);
								?>
								<?php if($this->subscription_arr[$key]['job_packages']!='' ){ _e('Job packages', 'wpjobster'); echo ": "; echo $this->subscription_arr[$key]['job_packages'];?><br /><?php } ?>
								<?php if($this->subscription_arr[$key]['noof_extras']!='' ){ _e('Number of extras allowed', 'wpjobster'); echo ": "; echo $this->subscription_arr[$key]['noof_extras'];?><br /><?php } ?>
								<?php if($this->subscription_arr[$key]['max_extra_price']!='' ){ _e('Maximum price for extra allowed', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['max_extra_price']); ?><br /><?php } ?>
								<?php if($this->subscription_arr[$key]['fees']!='' ){ _e('Commission charged', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['fees']?>%<br /><?php } ?>
								<?php if($this->subscription_arr[$key]['profile_label']!='' ){ _e('Profile label', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['profile_label']?><br /><?php } ?>
								<?php if($this->subscription_arr[$key]['icon_url']!='' ){ _e('Profile icon', 'wpjobster'); ?>: <img src="<?php echo $this->subscription_arr[$key]['icon_url']?>" width="25" /><br /><?php } ?>
								<?php if( validate_image_file( $this->subscription_arr[$key]['max_job_price'] ) ){ _e('Maximum price for job allowed', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['max_job_price']); ?><br /><?php } ?>
							</div>
							<div class="bs-col66888fill">
							<?php
							foreach($this->subscription_duration as $keyd => $valued){
								if($this->subscription_arr[$key][$valued]>=1){
									$amount_chargable = $this->subscription_arr[$key][$valued];

									echo '<div class="subscription-row">';
										echo '<div class="ui checkbox">';
											echo '<input class="radio-input" '.$eligible_for_sub.' type="radio" id="'.$valued.'-'.$key.'" name="sub_id" value="'.$valued.'-'.$key. '">';

											do_action('send_new_values', $param1, $eligible_for_sub, $valued, $key);

											echo '<label for="'.$valued.'-'.$key.'">';
												echo '<div class="level-label">'.translate_subscription_strings($valued).'</div>';
												echo '<div class="level-amount">'.  wpjobster_get_show_price($amount_chargable)." </div>";
											echo '</label>';
										echo '</div>';
									echo '</div>';
								}
							} ?>
							</div>
						</div>
					</div>
				<?php }
			}//end foreach ?>
			<div class="subs-buttons payment-buttons cf">
				<?php if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
					<button name="method" value="credits" class="ui white button pay_featured_button"><?php _e('Account Balance','wpjobster'); ?></button>
				<?php }
				do_action( 'wpjobster_purchase_subscription_add_payment_method' ); ?>
			</div>
		</form>
	</div>
</div>
