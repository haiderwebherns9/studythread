<?php
if ( ! function_exists( 'get_wpjobster_subscription_info' ) ) {

	function get_wpjobster_subscription_info( $uid = 0 ) {

		$wpjobster_subscription_info['wpjobster_subscription_enabled'] = get_option('wpjobster_subscription_enabled');
		if($wpjobster_subscription_info['wpjobster_subscription_enabled']=='yes'){
			if($uid==0){
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
			}
			include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
			$wpjobster_subscription_new_post = new wpjobster_subscription();


			$current_subscription = $wpjobster_subscription_new_post->get_current_subscription($uid);
			$wpjobster_subscription_info['wpjobster_subscription_eligibility_enabled'] = get_option('wpjobster_subscription_eligibility_enabled');
			if($current_subscription){
				if($current_subscription->subscription_status=='cancelled' ){
					$wpjobster_subscription_info['wpjobster_subscription_type'] = '';
					$wpjobster_subscription_info['wpjobster_subscription_level'] = 'level0';
					$wpjobster_subscription_info['wpjobster_subscription_amount'] = '0';
					$wpjobster_subscription_info['wpjobster_subscription_eligibility'] = false;
					$wpjobster_subscription_info['wpjobster_subscription_status']='1cancelled';
					$wpjobster_subscription_info['wpjobster_subscription_type_old'] = $current_subscription->subscription_type;
					$wpjobster_subscription_info['wpjobster_subscription_level_old'] = $current_subscription->subscription_level;
					$wpjobster_subscription_info['wpjobster_subscription_old'] = $current_subscription;
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_status']='2active';
					$wpjobster_subscription_info['wpjobster_subscription_type'] = $current_subscription->subscription_type;
					$wpjobster_subscription_info['wpjobster_subscription_level'] = $current_subscription->subscription_level;
					$wpjobster_subscription_info['wpjobster_subscription_amount'] = get_option('wpjobster_subscription_'.$wpjobster_subscription_info['wpjobster_subscription_type'].'_amount_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
					if($wpjobster_subscription_info['wpjobster_subscription_eligibility_enabled']=='yes'){
						$wpjobster_subscription_info['wpjobster_subscription_eligibility'] = get_option('wpjobster_subscription_eligibility_amount_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
					}else{
						$wpjobster_subscription_info['wpjobster_subscription_eligibility'] = false;
					}
				}
			}else{
				$wpjobster_subscription_info['wpjobster_subscription_status']='3new';
				$wpjobster_subscription_info['wpjobster_subscription_type'] = '';
				$wpjobster_subscription_info['wpjobster_subscription_level'] = 'level0';
				$wpjobster_subscription_info['wpjobster_subscription_amount'] = '0';
				$wpjobster_subscription_info['wpjobster_subscription_eligibility'] = false;
			}
				$wpjobster_subscription_info['wpjobster_subscription_profile_label_enabled'] = get_option('wpjobster_subscription_profile_label_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_profile_label_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_profile_label'] = get_option('wpjobster_subscription_profile_label_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_profile_label'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_icon_url_enabled']= get_option('wpjobster_subscription_icon_url_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_icon_url_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_icon_url'] = get_option('wpjobster_subscription_icon_url_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_icon_url'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_max_extra_price_enabled']= get_option('wpjobster_subscription_max_extra_price_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_max_extra_price_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_max_extra_price'] = get_option('wpjobster_subscription_max_extra_price_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_max_extra_price'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_max_job_price_enabled']= get_option('wpjobster_subscription_max_job_price_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_max_job_price_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_max_job_price'] = get_option('wpjobster_subscription_max_job_price_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_max_job_price'] = false;
				}


			$wpjobster_subscription_info['wpjobster_subscription_custom_extras_enabled']= get_option('wpjobster_subscription_custom_extras_enabled');
			if($wpjobster_subscription_info['wpjobster_subscription_custom_extras_enabled']=='yes'){
				$wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'] = get_option('wpjobster_subscription_max_customextrasamount_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
			}else{
				$wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'] = get_option('wpjobster_get_level'.wpjobster_get_user_level($uid).'_customextrasamount');
			}

				$wpjobster_subscription_info['wpjobster_fees_for_subscriber_enabled']= get_option('wpjobster_fees_for_subscriber_enabled');
				if($wpjobster_subscription_info['wpjobster_fees_for_subscriber_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_fees'] = get_option('wpjobster_subscription_fees_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_fees'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_noof_extras_enabled']= get_option('wpjobster_subscription_noof_extras_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_noof_extras_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_noof_extras'] = get_option('wpjobster_subscription_noof_extras_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_noof_extras'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_ex_fast_delivery_enabled'] = get_option('wpjobster_subscription_ex_fast_delivery_enabled');
				if( $wpjobster_subscription_info['wpjobster_subscription_ex_fast_delivery_enabled'] == 'yes' ) {
					$wpjobster_subscription_info['wpjobster_subscription_ex_fast_delivery'] = 'yes';
				} else {
					$wpjobster_subscription_info['wpjobster_subscription_ex_fast_delivery'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_additional_revision_enabled'] = get_option('wpjobster_subscription_additional_revision_enabled');
				if( $wpjobster_subscription_info['wpjobster_subscription_additional_revision_enabled'] == 'yes' ) {
					$wpjobster_subscription_info['wpjobster_subscription_additional_revision'] = 'yes';
				} else {
					$wpjobster_subscription_info['wpjobster_subscription_additional_revision'] = false;
				}


				$wpjobster_subscription_info['wpjobster_subscription_job_multiples_enabled']= get_option('wpjobster_subscription_job_multiples_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_job_multiples_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_job_multiples'] = get_option('wpjobster_subscription_job_multiples_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_job_multiples'] = false;
				}

				$wpjobster_subscription_info['wpjobster_subscription_extra_multiples_enabled']= get_option('wpjobster_subscription_extra_multiples_enabled');
				if($wpjobster_subscription_info['wpjobster_subscription_extra_multiples_enabled']=='yes'){
					$wpjobster_subscription_info['wpjobster_subscription_extra_multiples'] = get_option('wpjobster_subscription_extra_multiples_'.$wpjobster_subscription_info['wpjobster_subscription_level']);
				}else{
					$wpjobster_subscription_info['wpjobster_subscription_extra_multiples'] = false;
				}
		}else{
			$wpjobster_subscription_info['wpjobster_subscription_type'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_level'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_amount'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_eligibility'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_noof_extras'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_ex_fast_delivery'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_additional_revision'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_job_multiples'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_extra_multiples'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_max_extra_price'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_fees'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_profile_label'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_icon_url'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_max_job_price'] = false;
			$wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'] = get_option('wpjobster_get_level'.wpjobster_get_user_level($uid).'_customextrasamount');
		}
		return $wpjobster_subscription_info;
	}
}
?>
