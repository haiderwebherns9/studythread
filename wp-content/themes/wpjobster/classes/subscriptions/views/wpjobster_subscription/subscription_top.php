<?php
global $above_top;
echo $above_top //showing any message for success/failure ;?>
<div class="ui segment white-cnt padding-cnt" >
	<div class='filter-jobs-responsive-helper cf filter-jobs-cnt'>
		<?php
		$is_subscribed='0';
		if( is_object( $this->current_subscription) ){
			$is_subscribed='1';
			$current_level = $this->current_subscription->subscription_level;
			$current_type = $this->current_subscription->subscription_type;
		}
		$sub_action = isset( $_GET['sub_action'] ) ? $_GET['sub_action'] : 'details';

		if($is_subscribed==1){ ?>
			<p><?php echo sprintf(__('You are currently subscribed to %1$s with a %2$s billing period.', 'wpjobster'), translate_subscription_strings($current_level), translate_subscription_strings($current_type));
				if( translate_subscription_strings( $current_type ) != 'lifetime' ) {
					echo " ";
					echo sprintf(__('Your next biling due date is %s.', 'wpjobster'), $this->current_subscription->next_billing_date);
				}
				?>
			</p>

			<a class="ui white button <?php echo $sub_action == 'details' ? 'active' : ''; ?>" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>?sub_action=details"><?php _e("Current Subscription", 'wpjobster'); ?></a>
			<a class="ui white button <?php echo $sub_action == 'change' ? 'active' : ''; ?>" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>?sub_action=change"><?php _e("Upgrade Subscription", 'wpjobster'); ?></a>
			<?php if( translate_subscription_strings( $current_type ) != 'lifetime' ) { ?>
				<a class="ui white button <?php echo $sub_action == 'schedule' ? 'active' : ''; ?>" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>?sub_action=schedule"><?php _e("Schedule Upgrade", 'wpjobster'); ?></a>
			<?php } ?>
			<a class="ui white button <?php echo $sub_action == 'cancel' ? 'active' : ''; ?>" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>?sub_action=cancel"><?php _e("Cancel Subscription", 'wpjobster'); ?></a>
		<?php }else{ ?>
			<a class="ui white button" href="<?php echo get_permalink(get_option('wpjobster_subscriptions_page_id')); ?>"><?php _e("Subscribe Now", 'wpjobster'); ?></a>
		<?php } ?>
	</div>
</div>
