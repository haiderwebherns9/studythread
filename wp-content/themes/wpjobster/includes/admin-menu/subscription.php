<?php

class Subscription {

	function wpjobster_subscription() {
		$id_icon      = 'icon-options-general-info';
		$ttl_of_stuff = 'Jobster - '.__('Subscriptions Settings','wpjobster');

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_POST['wpjobster_save_subs1'])) {
				if (wpjobster_subscriptions_allowed()) {
					update_option('wpjobster_subscription_enabled', trim($_POST['wpjobster_subscription_enabled']));
					update_option('wpjobster_subscription_level_2_enabled', trim($_POST['wpjobster_subscription_level_2_enabled']));
					update_option('wpjobster_subscription_level_3_enabled', trim($_POST['wpjobster_subscription_level_3_enabled']));

					update_option('wpjobster_subscription_name_level0', trim($_POST['wpjobster_subscription_name_level0']));
					update_option('wpjobster_subscription_name_level1', trim($_POST['wpjobster_subscription_name_level1']));
					update_option('wpjobster_subscription_name_level2', trim($_POST['wpjobster_subscription_name_level2']));
					update_option('wpjobster_subscription_name_level3', trim($_POST['wpjobster_subscription_name_level3']));

					update_option('wpjobster_subscription_weekly_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_weekly_amount_level1'],2));
					update_option('wpjobster_subscription_monthly_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_monthly_amount_level1'],2));
					update_option('wpjobster_subscription_quarterly_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_quarterly_amount_level1'],2));
					update_option('wpjobster_subscription_yearly_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_yearly_amount_level1'],2));
					update_option('wpjobster_subscription_lifetime_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_lifetime_amount_level1'],2));

					update_option('wpjobster_subscription_weekly_amount_level2',wpjobster_formats_special($_POST['wpjobster_subscription_weekly_amount_level2'],2));
					update_option('wpjobster_subscription_monthly_amount_level2',  wpjobster_formats_special($_POST['wpjobster_subscription_monthly_amount_level2'],2));
					update_option('wpjobster_subscription_quarterly_amount_level2',wpjobster_formats_special($_POST['wpjobster_subscription_quarterly_amount_level2'],2));
					update_option('wpjobster_subscription_yearly_amount_level2',wpjobster_formats_special($_POST['wpjobster_subscription_yearly_amount_level2'],2));
					update_option('wpjobster_subscription_lifetime_amount_level2',wpjobster_formats_special($_POST['wpjobster_subscription_lifetime_amount_level2'],2));

					update_option('wpjobster_subscription_weekly_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_weekly_amount_level3'],2));
					update_option('wpjobster_subscription_monthly_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_monthly_amount_level3'],2));
					update_option('wpjobster_subscription_quarterly_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_quarterly_amount_level3'],2));
					update_option('wpjobster_subscription_yearly_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_yearly_amount_level3'],2));
					update_option('wpjobster_subscription_lifetime_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_lifetime_amount_level3'],2));

					update_option('wpjobster_subscription_prior_notification',$_POST['wpjobster_subscription_prior_notification']);
					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';

				} else {
					update_option('wpjobster_subscription_enabled', 'no');
					?>
					<div class="error notice">
						<p>Could not save subscriptions settings. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}
			}

			if(isset($_POST['wpjobster_save_subs2'])) {
				if (wpjobster_subscriptions_allowed()) {

					update_option('wpjobster_subscription_eligibility_enabled',        trim($_POST['wpjobster_subscription_eligibility_enabled']));

					update_option('wpjobster_subscription_eligibility_amount_level1',wpjobster_formats_special($_POST['wpjobster_subscription_eligibility_amount_level1'],2));
					update_option('wpjobster_subscription_eligibility_amount_level2',wpjobster_formats_special($_POST['wpjobster_subscription_eligibility_amount_level2'],2));
					update_option('wpjobster_subscription_eligibility_amount_level3',wpjobster_formats_special($_POST['wpjobster_subscription_eligibility_amount_level3'],2));

					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';

				} else {
					update_option('wpjobster_subscription_enabled', 'no');
					?>
					<div class="error notice">
						<p>Could not save subscriptions settings. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}
			}

			if(isset($_POST['wpjobster_save_subs3'])) {
				if (wpjobster_subscriptions_allowed()) {

					update_option('wpjobster_subscription_profile_label_enabled',$_POST['wpjobster_subscription_profile_label_enabled']);
					update_option('wpjobster_subscription_icon_url_enabled',$_POST['wpjobster_subscription_icon_url_enabled']);
					update_option('wpjobster_subscription_max_extra_price_enabled',$_POST['wpjobster_subscription_max_extra_price_enabled']);
					update_option('wpjobster_subscription_max_job_price_enabled',$_POST['wpjobster_subscription_max_job_price_enabled']);
					update_option('wpjobster_subscription_noof_extras_enabled',$_POST['wpjobster_subscription_noof_extras_enabled']);
					update_option('wpjobster_subscription_ex_fast_delivery_enabled',$_POST['wpjobster_subscription_ex_fast_delivery_enabled']);
					update_option('wpjobster_subscription_additional_revision_enabled',$_POST['wpjobster_subscription_additional_revision_enabled']);
					update_option('wpjobster_subscription_job_multiples_enabled',$_POST['wpjobster_subscription_job_multiples_enabled']);
					update_option('wpjobster_subscription_extra_multiples_enabled',$_POST['wpjobster_subscription_extra_multiples_enabled']);
					update_option('wpjobster_fees_for_subscriber_enabled',$_POST['wpjobster_fees_for_subscriber_enabled']);

					update_option('wpjobster_subscription_profile_label_level0',$_POST['wpjobster_subscription_profile_label_level0']);
					update_option('wpjobster_subscription_profile_label_level1',$_POST['wpjobster_subscription_profile_label_level1']);
					update_option('wpjobster_subscription_profile_label_level2',$_POST['wpjobster_subscription_profile_label_level2']);
					update_option('wpjobster_subscription_profile_label_level3',$_POST['wpjobster_subscription_profile_label_level3']);

					update_option('wpjobster_subscription_icon_url_level0',$_POST['wpjobster_subscription_icon_url_level0']);
					update_option('wpjobster_subscription_icon_url_level1',$_POST['wpjobster_subscription_icon_url_level1']);
					update_option('wpjobster_subscription_icon_url_level2',$_POST['wpjobster_subscription_icon_url_level2']);
					update_option('wpjobster_subscription_icon_url_level3',$_POST['wpjobster_subscription_icon_url_level3']);

					update_option('wpjobster_subscription_max_extra_price_level0',$_POST['wpjobster_subscription_max_extra_price_level0']);
					update_option('wpjobster_subscription_max_extra_price_level1',$_POST['wpjobster_subscription_max_extra_price_level1']);
					update_option('wpjobster_subscription_max_extra_price_level2',$_POST['wpjobster_subscription_max_extra_price_level2']);
					update_option('wpjobster_subscription_max_extra_price_level3',$_POST['wpjobster_subscription_max_extra_price_level3']);

					update_option('wpjobster_subscription_max_job_price_level0',$_POST['wpjobster_subscription_max_job_price_level0']);
					update_option('wpjobster_subscription_max_job_price_level1',$_POST['wpjobster_subscription_max_job_price_level1']);
					update_option('wpjobster_subscription_max_job_price_level2',$_POST['wpjobster_subscription_max_job_price_level2']);
					update_option('wpjobster_subscription_max_job_price_level3',$_POST['wpjobster_subscription_max_job_price_level3']);

					update_option('wpjobster_subscription_noof_extras_level0',$_POST['wpjobster_subscription_noof_extras_level0']);
					update_option('wpjobster_subscription_noof_extras_level1',$_POST['wpjobster_subscription_noof_extras_level1']);
					update_option('wpjobster_subscription_noof_extras_level2',$_POST['wpjobster_subscription_noof_extras_level2']);
					update_option('wpjobster_subscription_noof_extras_level3',$_POST['wpjobster_subscription_noof_extras_level3']);

					update_option('wpjobster_subscription_fast_del_multiples_level0',$_POST['wpjobster_subscription_fast_del_multiples_level0']);
					update_option('wpjobster_subscription_fast_del_multiples_level1',$_POST['wpjobster_subscription_fast_del_multiples_level1']);
					update_option('wpjobster_subscription_fast_del_multiples_level2',$_POST['wpjobster_subscription_fast_del_multiples_level2']);
					update_option('wpjobster_subscription_fast_del_multiples_level3',$_POST['wpjobster_subscription_fast_del_multiples_level3']);

					update_option('wpjobster_subscription_add_rev_multiples_level0',$_POST['wpjobster_subscription_add_rev_multiples_level0']);
					update_option('wpjobster_subscription_add_rev_multiples_level1',$_POST['wpjobster_subscription_add_rev_multiples_level1']);
					update_option('wpjobster_subscription_add_rev_multiples_level2',$_POST['wpjobster_subscription_add_rev_multiples_level2']);
					update_option('wpjobster_subscription_add_rev_multiples_level3',$_POST['wpjobster_subscription_add_rev_multiples_level3']);

					update_option('wpjobster_subscription_job_multiples_level0',empty( $_POST['wpjobster_subscription_job_multiples_level0'] )?'1':$_POST['wpjobster_subscription_job_multiples_level0'] );
					update_option('wpjobster_subscription_job_multiples_level1',empty( $_POST['wpjobster_subscription_job_multiples_level1'] )?'1':$_POST['wpjobster_subscription_job_multiples_level1'] );
					update_option('wpjobster_subscription_job_multiples_level2',empty( $_POST['wpjobster_subscription_job_multiples_level2'] )?'1':$_POST['wpjobster_subscription_job_multiples_level2'] );
					update_option('wpjobster_subscription_job_multiples_level3',empty( $_POST['wpjobster_subscription_job_multiples_level3'] )?'1':$_POST['wpjobster_subscription_job_multiples_level3'] );

					update_option('wpjobster_subscription_extra_multiples_level0',empty( $_POST['wpjobster_subscription_extra_multiples_level0'] )?'1':$_POST['wpjobster_subscription_extra_multiples_level0'] );
					update_option('wpjobster_subscription_extra_multiples_level1',empty( $_POST['wpjobster_subscription_extra_multiples_level1'] )?'1':$_POST['wpjobster_subscription_extra_multiples_level1'] );
					update_option('wpjobster_subscription_extra_multiples_level2',empty( $_POST['wpjobster_subscription_extra_multiples_level2'] )?'1':$_POST['wpjobster_subscription_extra_multiples_level2'] );
					update_option('wpjobster_subscription_extra_multiples_level3',empty( $_POST['wpjobster_subscription_extra_multiples_level3'] )?'1':$_POST['wpjobster_subscription_extra_multiples_level3'] );

					update_option('wpjobster_subscription_fees_level0',$_POST['wpjobster_subscription_fees_level0']);
					update_option('wpjobster_subscription_fees_level1',$_POST['wpjobster_subscription_fees_level1']);
					update_option('wpjobster_subscription_fees_level2',$_POST['wpjobster_subscription_fees_level2']);
					update_option('wpjobster_subscription_fees_level3',$_POST['wpjobster_subscription_fees_level3']);

					update_option('wpjobster_subscription_packages_level0',$_POST['wpjobster_subscription_packages_level0']);
					update_option('wpjobster_subscription_packages_level1',$_POST['wpjobster_subscription_packages_level1']);
					update_option('wpjobster_subscription_packages_level2',$_POST['wpjobster_subscription_packages_level2']);
					update_option('wpjobster_subscription_packages_level3',$_POST['wpjobster_subscription_packages_level3']);

					update_option('wpjobster_subscription_custom_extras_enabled',trim($_POST['wpjobster_subscription_custom_extras_enabled']));

					$wpjobster_sub_multiples_err_flag = 0;

					$wpjobster_subscription_job_multiples_level0 = $_POST['wpjobster_subscription_job_multiples_level0'];
					if($wpjobster_subscription_job_multiples_level0 < 2 or !is_numeric($wpjobster_subscription_job_multiples_level0)) {
						$wpjobster_subscription_job_multiples_level0 = 3; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_job_multiples_level0', trim($wpjobster_subscription_job_multiples_level0));
					$wpjobster_subscription_extra_multiples_level0 = $_POST['wpjobster_subscription_extra_multiples_level0'];
					if($wpjobster_subscription_extra_multiples_level0 < 2 or !is_numeric($wpjobster_subscription_extra_multiples_level0)) {
						$wpjobster_subscription_extra_multiples_level0 = 3; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_extra_multiples_level0', trim($wpjobster_subscription_extra_multiples_level0));

					$wpjobster_subscription_job_multiples_level1 = $_POST['wpjobster_subscription_job_multiples_level1'];
					if($wpjobster_subscription_job_multiples_level1 < 2 or !is_numeric($wpjobster_subscription_job_multiples_level1)) {
						$wpjobster_subscription_job_multiples_level1 = 5; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_job_multiples_level1', trim($wpjobster_subscription_job_multiples_level1));
					$wpjobster_subscription_extra_multiples_level1 = $_POST['wpjobster_subscription_extra_multiples_level1'];
					if($wpjobster_subscription_extra_multiples_level1 < 2 or !is_numeric($wpjobster_subscription_extra_multiples_level1)) {
						$wpjobster_subscription_extra_multiples_level1 = 5; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_extra_multiples_level1', trim($wpjobster_subscription_extra_multiples_level1));

					$wpjobster_subscription_job_multiples_level2 = $_POST['wpjobster_subscription_job_multiples_level2'];
					if($wpjobster_subscription_job_multiples_level2 < 2 or !is_numeric($wpjobster_subscription_job_multiples_level2)) {
						$wpjobster_subscription_job_multiples_level2 = 10; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_job_multiples_level2', trim($wpjobster_subscription_job_multiples_level2));
					$wpjobster_subscription_extra_multiples_level2 = $_POST['wpjobster_subscription_extra_multiples_level2'];
					if($wpjobster_subscription_extra_multiples_level2 < 2 or !is_numeric($wpjobster_subscription_extra_multiples_level2)) {
						$wpjobster_subscription_extra_multiples_level2 = 10; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_extra_multiples_level2', trim($wpjobster_subscription_extra_multiples_level2));

					$wpjobster_subscription_job_multiples_level3 = $_POST['wpjobster_subscription_job_multiples_level3'];
					if($wpjobster_subscription_job_multiples_level3 < 2 or !is_numeric($wpjobster_subscription_job_multiples_level3)) {
						$wpjobster_subscription_job_multiples_level3 = 20; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_job_multiples_level3', trim($wpjobster_subscription_job_multiples_level3));
					$wpjobster_subscription_extra_multiples_level3 = $_POST['wpjobster_subscription_extra_multiples_level3'];
					if($wpjobster_subscription_extra_multiples_level3 < 2 or !is_numeric($wpjobster_subscription_extra_multiples_level3)) {
						$wpjobster_subscription_extra_multiples_level3 = 20; $wpjobster_sub_multiples_err_flag = 1;
					}
					update_option('wpjobster_subscription_extra_multiples_level3', trim($wpjobster_subscription_extra_multiples_level3));

					$wpjobster_sub_customextras_err_flag = 0;

					$wpjobster_subscription_max_customextrasamount_level0 = $_POST['wpjobster_subscription_max_customextrasamount_level0'];
					if($wpjobster_subscription_max_customextrasamount_level0 < 1 or !is_numeric($wpjobster_subscription_max_customextrasamount_level0)) {
						$wpjobster_subscription_max_customextrasamount_level0 = 1; $wpjobster_sub_customextras_err_flag = 1;
					}
					update_option('wpjobster_subscription_max_customextrasamount_level0', trim($wpjobster_subscription_max_customextrasamount_level0));

					$wpjobster_subscription_max_customextrasamount_level1 = $_POST['wpjobster_subscription_max_customextrasamount_level1'];
					if($wpjobster_subscription_max_customextrasamount_level1 < 1 or !is_numeric($wpjobster_subscription_max_customextrasamount_level1)) {
						$wpjobster_subscription_max_customextrasamount_level1 = 1; $wpjobster_sub_customextras_err_flag = 1;
					}
					update_option('wpjobster_subscription_max_customextrasamount_level1', trim($wpjobster_subscription_max_customextrasamount_level1));

					$wpjobster_subscription_max_customextrasamount_level2 = $_POST['wpjobster_subscription_max_customextrasamount_level2'];
					if($wpjobster_subscription_max_customextrasamount_level2 < 1 or !is_numeric($wpjobster_subscription_max_customextrasamount_level2)) {
						$wpjobster_subscription_max_customextrasamount_level2 = 1; $wpjobster_sub_customextras_err_flag = 1;
					}
					update_option('wpjobster_subscription_max_customextrasamount_level2', trim($wpjobster_subscription_max_customextrasamount_level2));

					$wpjobster_subscription_max_customextrasamount_level3 = $_POST['wpjobster_subscription_max_customextrasamount_level3'];
					if($wpjobster_subscription_max_customextrasamount_level3 < 1 or !is_numeric($wpjobster_subscription_max_customextrasamount_level3)) {
						$wpjobster_subscription_max_customextrasamount_level3 = 1; $wpjobster_sub_customextras_err_flag = 1;
					}
					update_option('wpjobster_subscription_max_customextrasamount_level3', trim($wpjobster_subscription_max_customextrasamount_level3));

					do_action('save_features_options_for_subscription');


					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
					if ( $wpjobster_sub_multiples_err_flag ) {
						echo '<div class="error notice is-dismissible"><p>'.__('Job multiples need to be greater than or equal to 2! Default values have been saved!','wpjobster').'</p></div>';
					}
					if ( $wpjobster_sub_customextras_err_flag ) {
						echo '<div class="error notice is-dismissible"><p>'.__('Max total custom extras amount need to be greater than or equal to 1! Default values have been saved!','wpjobster').'</p></div>';
					}

				} else {
					update_option('wpjobster_subscription_enabled', 'no');
					?>
					<div class="error notice">
						<p>Could not save subscriptions settings. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}
			}

			wpj_subscription_html();

		echo '</div>';
	}
}

$subscription = new Subscription();
