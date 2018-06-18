<div class="ui segment white-cnt padding-cnt" >
	<h2><?php _e("You have opted to cancel the subscription. Please Confirm.", "wpjobster"); ?></h2>
	<div class='filter-jobs-responsive-helper cf filter-jobs-cnt'>
		<p>
		<?php
		echo sprintf(__('You are currently subscribed to %1$s with a %2$s billing period.', 'wpjobster'), translate_subscription_strings($current_level), translate_subscription_strings($current_type));
		if( translate_subscription_strings( $current_type ) != 'lifetime' ) {
			echo " ";
			echo sprintf(__('Your next biling due date is %s.', 'wpjobster'), $this->current_subscription->next_billing_date);
		} ?>
		</p>
		<p>
		<a class="ui negative button" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>?sub_action=process_cancellation"><?php _e("Yes", 'wpjobster');?></a>
		<a class="ui positive button" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>"><?php _e("No", 'wpjobster'); ?></a>
		</p>
	</div>
</div>

<div class="ui hidden divider"></div>
