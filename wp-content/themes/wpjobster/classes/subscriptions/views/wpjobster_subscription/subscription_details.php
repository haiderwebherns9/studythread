<?php
$cancelled =0;
$next_billing = $this->current_subscription->next_billing_date;
if( $next_billing== '0000-00-00'){
	$cancelled =1;
}
?>

<div class="ui segment white-cnt padding-cnt" >
	<?php do_action('wpj_before_current_subscription_title', $this->_current_user->ID); ?>
	<h2 class="heading-subtitle"><?php _e("Current Subscription", "wpjobster");
	if($cancelled ==1){
		echo " (".__("Cancelled", "wpjobster").")";
	}else{
		echo " (".__("Active", "wpjobster").")";
	}
	?></h2>
	<div>
		<?php $key = $current_level ; ?>
			<div class="bs-table-row cf hover-break">
				<div class="bs-col-container cf">
					<div class="bs-col6"><?php _e("Subscription", "wpjobster"); ?></div><div class="bs-col2"><?php echo translate_subscription_strings($key); ?></div>
				</div>
			</div>
			<div class="bs-table-row cf hover-break">
				<div class="bs-col-container cf">
					<div class="bs-col6"><?php _e("Eligibility", "wpjobster"); ?></div>
					<div class="bs-col2 ">
					<?php if($this->subscription_eligibility_enabled=='yes' && $this->subscription_arr[$key]['eligility']!='' && $this->subscription_arr[$key]['eligility']!='0'){ _e('Minimum amount of sales', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['eligility']); }else{echo __("NA", "wpjobster");}
					?>
					</div>
				</div>
			</div>
			<div class="bs-table-row cf hover-break">
				<div class="bs-col-container cf">
					<div class="bs-col6"><?php _e("Features", "wpjobster"); ?></div>
					<div class="bs-col2">
						<?php if($this->subscription_arr[$key]['job_packages']!='' ){ _e('Job packages', 'wpjobster'); echo ": "; echo $this->subscription_arr[$key]['job_packages'];?><br /><?php } ?>
						<?php if($this->subscription_arr[$key]['noof_extras']!='' ){ _e('Number of extras allowed', 'wpjobster'); echo ": "; echo $this->subscription_arr[$key]['noof_extras'];?><br /><?php } ?>
						<?php if($this->subscription_arr[$key]['max_extra_price']!='' ){ _e('Maximum price for extra allowed', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['max_extra_price']); ?><br /><?php } ?>
						<?php if($this->subscription_arr[$key]['fees']!='' ){ _e('Commission charged', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['fees'] . "%"; ?><br /><?php } ?>
						<?php if($this->subscription_arr[$key]['profile_label']!='' ){ _e('Profile label', 'wpjobster'); ?>: <?php echo $this->subscription_arr[$key]['profile_label']?><br /><?php } ?>
						<?php if( validate_image_file( $this->subscription_arr[$key]['icon_url'] ) ){ _e('Profile icon', 'wpjobster'); ?>: <img src="<?php echo $this->subscription_arr[$key]['icon_url']?>" width="25" /><br /><?php } ?>
						<?php if($this->subscription_arr[$key]['max_job_price']!='' ){ _e('Maximum price for job allowed', 'wpjobster'); ?>: <?php echo wpjobster_get_show_price($this->subscription_arr[$key]['max_job_price']); ?><br /><?php } ?>
					</div>
				</div>
			</div>
			<div class="bs-table-row cf hover-break">
				<div class="bs-col-container cf">
				<div class="bs-col6"><?php _e("Billing Period", "wpjobster"); ?></div>
				<div class="bs-col2"><?php echo translate_subscription_strings($current_type); ?></div>

				</div>
			</div>
			<div class="bs-table-row cf hover-break">
				<div class="bs-col-container cf">
				<div class="bs-col6"><?php _e("Next Billing Date", "wpjobster"); ?></div>
				<div class="bs-col2"><?php if( translate_subscription_strings( $current_type ) == 'lifetime' ) { echo "NA"; } else { echo $this->current_subscription->next_billing_date; } ?></div>

				</div>
			</div>
			<?php

			if(($this->current_subscription->next_subscription_level != $this->current_subscription->subscription_level ||
			$this->current_subscription->subscription_type != $this->current_subscription->next_subscription_type      ) && $cancelled !='1')
			{ ?>
				<div class="bs-table-row cf hover-break">
					<div class="bs-col-container cf">
						<div class="bs-col1"><strong><?php echo __('You have scheduled a change in the subscription as below', 'wpjobster'); ?></strong></div>
					</div>
				</div>
				<div class="bs-table-row cf hover-break">
					<div class="bs-col-container cf">
							<div class="bs-col6"><?php echo __("Next Subscription level", "wpjobster"); ?></div>
							<div class="bs-col2"><?php echo  $this->current_subscription->next_subscription_level ; ?></div>
					</div>
				</div>
				<div class="bs-table-row cf hover-break">
					<div class="bs-col-container cf">
							<div class="bs-col6"><?php echo __("Next Subscription period", "wpjobster"); ?></div>
							<div class="bs-col2"><?php echo  $this->current_subscription->next_subscription_type ; ?></div>
					</div>
				</div>
				<div class="bs-table-row cf hover-break">
					<div class="bs-col-container cf">
							<div class="bs-col6"><?php echo __("Next Subscription amount", "wpjobster"); ?></div>
							<div class="bs-col2"><?php echo $this->current_subscription->next_subscription_amount ; ?></div>
					</div>
				</div>
			<?php
			}
			?>
	</div>
</div>

<div class="ui hidden divider"></div>
