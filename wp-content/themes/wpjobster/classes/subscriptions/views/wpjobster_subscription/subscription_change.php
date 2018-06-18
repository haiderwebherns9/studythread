<div class="ui segment white-cnt padding-cnt" >
	<h2 class="heading-subtitle"><?php _e('Upgrade Subscription', 'wpjobster'); ?></h2>
	<div>
		<form id="subscripton-form" action="<?php echo get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ); ?>" method="GET">
			<input type="hidden" name="jb_action" value="change_subscription"/>

			<?php
			if($is_subscribed==1){echo "<div class='bs-table-header' style='margin-bottom: 15px;'>";
			_e("Note: You will have to pay for the price difference for the current billing cycle, while for the next month you will be charged according to your chosen subscription plan.", 'wpjobster');
			echo "</div>";
			}
			?>

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
					$show_this = 1;
					if($is_subscribed==1){
						if($key < $current_level ){
							$show_this = 0;
						}else{
							$amount_chargable = $this->subscription_arr[$key][$current_type] - $this->subscription_arr[$current_level][$current_type];
							if ($amount_chargable < 0) {
								$amount_chargable = 0;
							}
						}
					}else{
						$amount_chargable = 0;
					}
					if($show_this == 1){

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
							$eligible_for_sub = ' ';
							$eligible_str= "";
							$eligible_str .= "";
							$eligible_class = "subscription-eligible";
						}
						?>
						<div class="bs-table-row cf hover-break <?php echo $eligible_class;?>">
							<div class="bs-col-container cf">
								<div class="bs-col8"><?php echo translate_subscription_strings($key); ?></div>
								<div class="bs-col66888fill ">
								<?php if($this->subscription_arr[$key]['eligility']!='' && $this->subscription_arr[$key]['eligility']!='0'){ _e('Minimum amount of sales', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['eligility']); } else { echo __("NA", "wpjobster"); }
								echo "<br />" . $eligible_str;
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
									<?php if($this->subscription_arr[$key]['fees']!='' ){ _e('Commission charged', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['fees']; ?>%<br /><?php } ?>
									<?php if($this->subscription_arr[$key]['profile_label']!='' ){ _e('Profile label', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['profile_label']?><br /><?php } ?>
									<?php if( validate_image_file( $this->subscription_arr[$key]['icon_url'] ) ){ _e('Profile icon', 'wpjobster'); ?>: <img src="<?php echo $this->subscription_arr[$key]['icon_url']?>" width="25" /><br /><?php } ?>
									<?php if($this->subscription_arr[$key]['max_job_price']!='' ){ _e('Maximum price for job allowed', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['max_job_price']); ?><br /><?php } ?>
								</div>
								<div class="bs-col66888fill">
								<?php foreach($this->subscription_duration as $keyd => $valued){

									$amount_chargable = $this->subscription_arr[$key][$valued] - $this->subscription_arr[$current_level][$current_type];

									if($this->subscription_arr[$key][$valued]>=1){
										if($is_subscribed==0){
											$amount_chargable = $this->subscription_arr[$key][$valued];
										}

										echo '<div class="subscription-row">';
											echo '<div class="ui checkbox">';
												echo '<input class="radio-input" '.$eligible_for_sub.' type="radio" id="'.$valued.'-'.$key.'" name="sub_id" value="'.$valued.'-'.$key.'-'.$this->subscription_arr[$current_level][$current_type]. '">';
												echo '<input type="hidden" name="sub_amt" value="'.wpjobster_formats_mm($this->subscription_arr[$key][$valued]).'">';

												do_action('send_new_values', $param1, $eligible_for_sub, $valued, $key);

												echo '<label for="'.$valued.'-'.$key.'">';
													echo '<span class="level-label">'.translate_subscription_strings($valued).'</span>';
													echo '<span class="level-amount">'. wpjobster_get_show_price($this->subscription_arr[$key][$valued])." </span>";
												echo '</label>';
											echo '</div>';

											if($is_subscribed==1) { echo "<div>" . sprintf(__("Difference charged now: %s", "wpjobster"), wpjobster_get_show_price($amount_chargable)) . "</div>"; }
										echo '</div>';
									}
								} ?>
								</div>
							</div>
						</div>
					<?php }//end if($show_this == 1){
				} //end if($key != $level2 && $key != $level3){
			}//end foreach ?>

			<div class="subs-buttons payment-buttons payment-buttons-subscription cf">
				<?php if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
					<button name="method" value="credits" class="ui white button"><?php _e('Account Balance','wpjobster'); ?></button>
				<?php }

				$wpjobster_paypal_enable = get_option('wpjobster_paypal_enable');
				$wpjobster_paypal_enable_subs = get_option('wpjobster_paypal_enable_subscription');
				if($wpjobster_paypal_enable == "yes" && $wpjobster_paypal_enable_subs == "yes"): ?>
					<button name="method" value="paypal" class="ui white button"><?php _e('PayPal','wpjobster'); ?></button>
				<?php endif;

				do_action( 'wpjobster_purchase_subscription_add_payment_method' ); ?>
			</div>
		</form>
	</div>
</div>

<?php
//Subscription Popup
$ajax_url = admin_url( 'admin-ajax.php' ); ?>
<div id="payment-gateway-popup"></div>
<script>
	function take_to_gateway_subscription_upgrade_popup( gateway_unique_name ) {
		sub_details = jQuery('input[name="sub_id"]:checked').val();
		jQuery.ajax({
			type: "GET",
			url: "<?php echo get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ); ?>?jb_action=change_subscription&method="+gateway_unique_name+"&sub_id="+sub_details,
			success: function(msg){

			}
		});

		sub_details = jQuery('input[name="sub_id"]:checked').val();
		if (sub_details === undefined) {
			 alert("Please select a plan");
			 return false;
		}
		sd_arr = sub_details.split('-');
		sub_typ = sd_arr[0];
		sub_lvl = sd_arr[1];
		sub_typ_lvl = sub_typ+"-"+sub_lvl;

		sub_amt = jQuery("#"+sub_typ_lvl).next().val();
		uid = "<?php echo $this->_current_user->ID; ?>";

		jQuery.ajax({
			type: "POST",
			url: "<?php echo $ajax_url; ?>",
			data: {
				action: 'wpjobster_check_payment_gateway_popup',
				user_id: uid,
				payment_type: 'subscription',
				sub_amount: sub_amt,
				sub_type: sub_typ,
				sub_level: sub_lvl,
				gateway: gateway_unique_name
			},
			success: function (output) {
				jQuery("#payment-gateway-popup").html(output);
			}
		});
		return false;
	}
</script>
