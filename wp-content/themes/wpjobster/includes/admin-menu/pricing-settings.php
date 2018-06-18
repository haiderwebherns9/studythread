<?php

class PricingSettings {

	function __construct() {
		add_action( 'init',array( $this, 'catch_ajax_posts' ) );
	}

	function catch_ajax_posts(){
		if (!is_demo_admin()) {
			if (isset($_POST['delete_variable_job_fee'])) {
				if (is_user_logged_in() && current_user_can('manage_options')) {
					$ids = $_POST['delete_variable_job_fee'];
					global $wpdb;
					$ss = "delete from ".$wpdb->prefix."job_var_costs where id='$ids'";
					$wpdb->query($ss);
					exit;
				}
			}

			if (isset($_POST['delete_user_package'])) {
				if (is_user_logged_in() && current_user_can('manage_options')) {
					$ids = $_POST['delete_user_package'];
					global $wpdb;
					$ss = "delete from ".$wpdb->prefix."job_topup_packages where id='$ids'";
					$wpdb->query($ss);
					exit;
				}
			}
		}
	}

	function wpjobster_pricing_options() {

		$id_icon      = 'icon-options-general4';
		$ttl_of_stuff = 'Jobster - '.__('Pricing Settings','wpjobster');

		global $menu_admin_wpjobster_theme_bull, $wpdb;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_POST['wpjobster_save1'])){
				$wpjobster_currency_position      = trim($_POST['wpjobster_currency_position']);
				$wpjobster_currency_symbol_space      = trim($_POST['wpjobster_currency_symbol_space']);
				$wpjobster_decimal_sum_separator    = trim($_POST['wpjobster_decimal_sum_separator']);
				$wpjobster_thousands_sum_separator    = trim($_POST['wpjobster_thousands_sum_separator']);
				$wpjobster_replace_zero_with_free   =   trim($_POST['wpjobster_replace_zero_with_free']);
				$wpjobster_decimals   = trim($_POST['wpjobster_decimals']);

				update_option('wpjobster_currency_position',    $wpjobster_currency_position);
				update_option('wpjobster_currency_symbol_space',    $wpjobster_currency_symbol_space);
				update_option('wpjobster_decimal_sum_separator',  $wpjobster_decimal_sum_separator);
				update_option('wpjobster_thousands_sum_separator',  $wpjobster_thousands_sum_separator);
				update_option('wpjobster_replace_zero_with_free',  $wpjobster_replace_zero_with_free);
				update_option('wpjobster_decimals',  $wpjobster_decimals);

				$is_allowed_multi_currency = wpj_is_allowed( 'multi_currency' );
				$allowed_currency_error_flag = 0;

				for ( $i = 1; $i <= 10; $i++ ) {
					$currency_x = 'wpjobster_currency_' . $i;
					$currency_symbol_x = 'wpjobster_currency_symbol_' . $i;

					$wpjobster_currency_x = trim( $_POST[$currency_x] );
					$wpjobster_currency_symbol_x = trim( $_POST[$currency_symbol_x] );

					if ( ! $is_allowed_multi_currency && $i > 1
						&& ( $wpjobster_currency_x != '' || $wpjobster_currency_symbol_x != '' ) ) {

						$wpjobster_currency_x = '';
						$wpjobster_currency_symbol_x = '';
						$allowed_currency_error_flag = 1;
					}

					update_option( $currency_x, $wpjobster_currency_x );
					update_option( $currency_symbol_x, $wpjobster_currency_symbol_x );
				}

				if ( ! $is_allowed_multi_currency && $allowed_currency_error_flag ) {
					wpj_disabled_settings_error( 'multi_currency' );
				}

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}


			if(isset($_POST['wpjobster_save2'])){
				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';

				$wpjobster_enable_site_fee   = trim($_POST['wpjobster_enable_site_fee']);
				$wpjobster_percent_fee_taken = trim($_POST['wpjobster_percent_fee_taken']);
				$wpjobster_solid_fee_taken   = trim($_POST['wpjobster_solid_fee_taken']);

				update_option('wpjobster_solid_fee_taken', $wpjobster_solid_fee_taken);
				update_option('wpjobster_percent_fee_taken', $wpjobster_percent_fee_taken);

				if ( $wpjobster_enable_site_fee == "flexible" &&
					! wpj_is_allowed( 'flexible_fees' ) ) {

					update_option( 'wpjobster_enable_site_fee', 'percent' );
					wpj_disabled_settings_error( 'flexible_fees' );

				} else {
					update_option( 'wpjobster_enable_site_fee', $wpjobster_enable_site_fee );
				}

				$wpjobster_percent_fee_taken_range0_level0 = trim($_POST['wpjobster_percent_fee_taken_range0_level0']);
				$wpjobster_percent_fee_taken_range0_level1 = trim($_POST['wpjobster_percent_fee_taken_range0_level1']);
				$wpjobster_percent_fee_taken_range0_level2 = trim($_POST['wpjobster_percent_fee_taken_range0_level2']);
				$wpjobster_percent_fee_taken_range0_level3 = trim($_POST['wpjobster_percent_fee_taken_range0_level3']);
				$wpjobster_percent_fee_taken_range1_level0 = trim($_POST['wpjobster_percent_fee_taken_range1_level0']);
				$wpjobster_percent_fee_taken_range1_level1 = trim($_POST['wpjobster_percent_fee_taken_range1_level1']);
				$wpjobster_percent_fee_taken_range1_level2 = trim($_POST['wpjobster_percent_fee_taken_range1_level2']);
				$wpjobster_percent_fee_taken_range1_level3 = trim($_POST['wpjobster_percent_fee_taken_range1_level3']);
				$wpjobster_percent_fee_taken_range2_level0 = trim($_POST['wpjobster_percent_fee_taken_range2_level0']);
				$wpjobster_percent_fee_taken_range2_level1 = trim($_POST['wpjobster_percent_fee_taken_range2_level1']);
				$wpjobster_percent_fee_taken_range2_level2 = trim($_POST['wpjobster_percent_fee_taken_range2_level2']);
				$wpjobster_percent_fee_taken_range2_level3 = trim($_POST['wpjobster_percent_fee_taken_range2_level3']);
				$wpjobster_percent_fee_taken_range3_level0 = trim($_POST['wpjobster_percent_fee_taken_range3_level0']);
				$wpjobster_percent_fee_taken_range3_level1 = trim($_POST['wpjobster_percent_fee_taken_range3_level1']);
				$wpjobster_percent_fee_taken_range3_level2 = trim($_POST['wpjobster_percent_fee_taken_range3_level2']);
				$wpjobster_percent_fee_taken_range3_level3 = trim($_POST['wpjobster_percent_fee_taken_range3_level3']);
				$wpjobster_enable_refund_buyer_processing_fees = trim($_POST['wpjobster_enable_refund_buyer_processing_fees']);
				$wpjobster_buyer_processing_fees = trim($_POST['wpjobster_buyer_processing_fees']);
				$wpjobster_buyer_processing_fees_percent = trim($_POST['wpjobster_buyer_processing_fees_percent']);


				// let's play safe.
				if (!is_numeric($wpjobster_buyer_processing_fees)) {
					$wpjobster_buyer_processing_fees = 0;
				}

				if (!is_numeric($wpjobster_buyer_processing_fees_percent)) {
					$wpjobster_buyer_processing_fees_percent = 0;
				}

				$wpjobster_enable_buyer_processing_fees = trim($_POST['wpjobster_enable_buyer_processing_fees']);

				if (($wpjobster_enable_buyer_processing_fees == 'fixed' || $wpjobster_enable_buyer_processing_fees == 'percent') && !wpjobster_processing_fee_allowed()) {
					update_option('wpjobster_enable_buyer_processing_fees', 'disabled');
					?>
					<div class="error notice">
						<p>Could not enable Buyer Processing Fees. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Developer or Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				} else {
					update_option('wpjobster_enable_buyer_processing_fees', $wpjobster_enable_buyer_processing_fees);
				}

				update_option('wpjobster_enable_refund_buyer_processing_fees', $wpjobster_enable_refund_buyer_processing_fees);
				update_option('wpjobster_buyer_processing_fees', $wpjobster_buyer_processing_fees);
				update_option('wpjobster_buyer_processing_fees_percent', $wpjobster_buyer_processing_fees_percent);

				$wpjobster_enable_site_tax = trim($_POST['wpjobster_enable_site_tax']);
				$wpjobster_enable_refund_tax = trim($_POST['wpjobster_enable_refund_tax']);
				$wpjobster_country_taxes = $_POST['wpjobster_country_taxes'];
				$wpjobster_country_taxes_percentage = $_POST['wpjobster_country_taxes_percentage'];

				$wpjobster_enable_processingfee_tax = trim($_POST['wpjobster_enable_processingfee_tax']);
				$wpjobster_tax_percent = trim($_POST['wpjobster_tax_percent']);
				if (!is_numeric($wpjobster_tax_percent)) {
					$wpjobster_tax_percent = 0;
				}
				update_option("wpjobster_enable_refund_tax", $wpjobster_enable_refund_tax);
				update_option("wpjobster_enable_processingfee_tax", $wpjobster_enable_processingfee_tax);
				update_option("wpjobster_tax_percent", $wpjobster_tax_percent);
				$last_index = count($wpjobster_country_taxes_percentage)-1;
				$wpjobster_country_taxes_percent_arr=array();
				foreach($wpjobster_country_taxes_percentage as $index=>$percent){
					if($percent!='' ||(int)$percent!='0'){
						$wpjobster_country_taxes_percent_arr[$wpjobster_country_taxes[$index]] =$percent;
					}
				}
				update_option("wpjobster_country_taxes_percentage", $wpjobster_country_taxes_percent_arr);

				if ($wpjobster_enable_site_tax == 'yes' && !wpjobster_processing_fee_allowed()) {
					update_option('wpjobster_enable_site_tax', 'no'); ?>
					<div class="error notice">
						<p>Could not enable Tax. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Developer or Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				} else {
					update_option('wpjobster_enable_site_tax', $wpjobster_enable_site_tax);
				}

				update_option('wpjobster_percent_fee_taken_range0_level0', $wpjobster_percent_fee_taken_range0_level0);
				update_option('wpjobster_percent_fee_taken_range0_level1', $wpjobster_percent_fee_taken_range0_level1);
				update_option('wpjobster_percent_fee_taken_range0_level2', $wpjobster_percent_fee_taken_range0_level2);
				update_option('wpjobster_percent_fee_taken_range0_level3', $wpjobster_percent_fee_taken_range0_level3);
				update_option('wpjobster_percent_fee_taken_range1_level0', $wpjobster_percent_fee_taken_range1_level0);
				update_option('wpjobster_percent_fee_taken_range1_level1', $wpjobster_percent_fee_taken_range1_level1);
				update_option('wpjobster_percent_fee_taken_range1_level2', $wpjobster_percent_fee_taken_range1_level2);
				update_option('wpjobster_percent_fee_taken_range1_level3', $wpjobster_percent_fee_taken_range1_level3);
				update_option('wpjobster_percent_fee_taken_range2_level0', $wpjobster_percent_fee_taken_range2_level0);
				update_option('wpjobster_percent_fee_taken_range2_level1', $wpjobster_percent_fee_taken_range2_level1);
				update_option('wpjobster_percent_fee_taken_range2_level2', $wpjobster_percent_fee_taken_range2_level2);
				update_option('wpjobster_percent_fee_taken_range2_level3', $wpjobster_percent_fee_taken_range2_level3);
				update_option('wpjobster_percent_fee_taken_range3_level0', $wpjobster_percent_fee_taken_range3_level0);
				update_option('wpjobster_percent_fee_taken_range3_level1', $wpjobster_percent_fee_taken_range3_level1);
				update_option('wpjobster_percent_fee_taken_range3_level2', $wpjobster_percent_fee_taken_range3_level2);
				update_option('wpjobster_percent_fee_taken_range3_level3', $wpjobster_percent_fee_taken_range3_level3);

				update_option('wpjobster_percent_fee_taken_range1_base', trim($_POST['wpjobster_percent_fee_taken_range1_base']));
				update_option('wpjobster_percent_fee_taken_range2_base', trim($_POST['wpjobster_percent_fee_taken_range2_base']));
				update_option('wpjobster_percent_fee_taken_range3_base', trim($_POST['wpjobster_percent_fee_taken_range3_base']));
			}

			if(isset($_POST['wpjobster_save_withdraw'])) {

				$wpjobster_withdraw_limit       = trim($_POST['wpjobster_withdraw_limit']);
				update_option('wpjobster_withdraw_limit'                    , $wpjobster_withdraw_limit);
				update_option('wpjobster_enable_paypal_withdraw'            , trim($_POST['wpjobster_enable_paypal_withdraw']));
				update_option('wpjobster_enable_payoneer_withdraw'          , trim($_POST['wpjobster_enable_payoneer_withdraw']));
				update_option('wpjobster_enable_bank_withdraw'              , trim($_POST['wpjobster_enable_bank_withdraw']));
				update_option('wpjobster_enable_withdraw_email_verification', trim($_POST['wpjobster_enable_withdraw_email_verification']));

				if ( $_POST['wpjobster_enable_withdraw_email_verification'] == 'yes' ) {
					// E-mail
					update_option('uz_email_withdraw_req_en_subject', 'You have requested a withdrawal');
					update_option('uz_email_withdraw_req_en_message',
						'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

						'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.
						'Please click the following link in order to confirm your request: '.PHP_EOL.
						'##withdrawal_email_verification##'.PHP_EOL.PHP_EOL.

						'Withdraw details:'.PHP_EOL.
						'Amount: ##amount_withdrawn##'.PHP_EOL.
						'Method: ##withdraw_method##'.PHP_EOL.PHP_EOL.

						'Thank you,'.PHP_EOL.
						'##your_site_name## Team');

					// SMS
					update_option('uz_sms_withdraw_req_en_message',

						'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

						'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.
						'Please click the following link in order to confirm your request: '.PHP_EOL.
						'##withdrawal_email_verification##'.PHP_EOL.PHP_EOL.

						'Amt: ##amount_withdrawn##'.PHP_EOL.
						'Method: ##withdraw_method##');
				} else {
					// E-mail
					update_option('uz_email_withdraw_req_en_subject', 'You have requested a withdrawal');
					update_option('uz_email_withdraw_req_en_message',
						'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

						'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.

						'Withdraw details:'.PHP_EOL.
						'Amount: ##amount_withdrawn##'.PHP_EOL.
						'Method: ##withdraw_method##'.PHP_EOL.PHP_EOL.

						'Thank you,'.PHP_EOL.
						'##your_site_name## Team');

					// SMS
					update_option('uz_sms_withdraw_req_en_message',

						'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

						'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.

						'Amt: ##amount_withdrawn##'.PHP_EOL.
						'Method: ##withdraw_method##');
				}

				do_action( 'wpjobster_save_enable_withdraw_admin_gateway' );

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_savelimits'])) {

				update_option('wpjobster_level0_max', trim($_POST['wpjobster_level0_max']));
				update_option('wpjobster_level1_max', trim($_POST['wpjobster_level1_max']));
				update_option('wpjobster_level2_max', trim($_POST['wpjobster_level2_max']));
				update_option('wpjobster_level3_max', trim($_POST['wpjobster_level3_max']));
				update_option('wpjobster_level0_max_extra', trim($_POST['wpjobster_level0_max_extra']));
				update_option('wpjobster_level1_max_extra', trim($_POST['wpjobster_level1_max_extra']));
				update_option('wpjobster_level2_max_extra', trim($_POST['wpjobster_level2_max_extra']));
				update_option('wpjobster_level3_max_extra', trim($_POST['wpjobster_level3_max_extra']));

				update_option('wpjobster_offer_price_min', trim($_POST['wpjobster_offer_price_min']));
				update_option('wpjobster_min_job_amount' , trim($_POST['wpjobster_min_job_amount']));
				update_option('wpjobster_offer_price_max', trim($_POST['wpjobster_offer_price_max']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if (isset($_POST['wpjobster_save_topup'])) {
				if (wpjobster_topup_allowed()) {
					update_option('wpjobster_enable_topup', trim($_POST['wpjobster_enable_topup']));

					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
				} else {
					update_option('wpjobster_enable_topup', 'no');
					?>
					<div class="error notice">
						<p>Could not enable Top Up. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}
			}

			if(isset($_POST['wpjobster_save_topup_package'])) {
				if (wpjobster_topup_allowed()) {
					$cost = trim($_POST['newcost']);
					$credit = trim($_POST['newcredit']);
					$ss = "insert into ".$wpdb->prefix."job_topup_packages (cost,credit) values('$cost','$credit')";
					$wpdb->query($ss);

					echo '<div class="updated fade"><p>'.__('Package added!','wpjobster').'</p></div>';
				} else {
					update_option('wpjobster_enable_topup', 'no');
					?>
					<div class="error notice">
						<p>Could not add package. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}

			}

			if(isset($_POST['wpjobster_save3'])){

				$wpjobster_job_fixed_amount         = trim($_POST['wpjobster_job_fixed_amount']);
				$wpjobster_enable_free_input_box    = trim($_POST['wpjobster_enable_free_input_box']);
				$wpjobster_enable_dropdown_values   = trim($_POST['wpjobster_enable_dropdown_values']);

				update_option('wpjobster_job_fixed_amount',       $wpjobster_job_fixed_amount);
				update_option('wpjobster_enable_free_input_box',  $wpjobster_enable_free_input_box);
				update_option('wpjobster_enable_dropdown_values',   $wpjobster_enable_dropdown_values);

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_save5'])){
				$json = get_option('exchange_rates');
				$exchangeRates = json_decode($json);

				global $wpjobster_currencies_array;
				foreach ($wpjobster_currencies_array as $wpjobster_currency) {
					if ($wpjobster_currency != "USD") {
						$exchangeRates->rates->$wpjobster_currency = $_POST['wpjobster_' . $wpjobster_currency . '_currency'];
					}
				}

				$json = json_encode($exchangeRates);
				update_option('exchange_rates', $json);

				update_option('openexchangerates_appid', trim($_POST['openexchangerates_appid']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_addnewcost'])){
				$cost = trim($_POST['newcost']);
				$ss = "insert into ".$wpdb->prefix."job_var_costs (cost) values('$cost')";
				$wpdb->query($ss);

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_save6'])) {
				if ( wpj_is_allowed( 'featured_job' ) ) {
					$wpjobster_featured_enable = trim($_POST['wpjobster_featured_enable']);
					$wpjobster_featured_interval = trim($_POST['wpjobster_featured_interval']);
					if(!is_numeric($wpjobster_featured_interval)) $wpjobster_featured_interval = 7;
					$wpjobster_featured_homepage = trim($_POST['wpjobster_featured_homepage']);
					if(!is_numeric($wpjobster_featured_homepage)) $wpjobster_featured_homepage = 5;
					$wpjobster_featured_category = trim($_POST['wpjobster_featured_category']);
					if(!is_numeric($wpjobster_featured_category)) $wpjobster_featured_category = 5;
					$wpjobster_featured_subcategory = trim($_POST['wpjobster_featured_subcategory']);
					if(!is_numeric($wpjobster_featured_subcategory)) $wpjobster_featured_subcategory = 5;
					$wpjobster_featured_price_homepage = trim($_POST['wpjobster_featured_price_homepage']);
					if(!is_numeric($wpjobster_featured_price_homepage)) $wpjobster_featured_price_homepage = 5;
					$wpjobster_featured_price_category = trim($_POST['wpjobster_featured_price_category']);
					if(!is_numeric($wpjobster_featured_price_category)) $wpjobster_featured_price_category = 5;
					$wpjobster_featured_price_subcategory = trim($_POST['wpjobster_featured_price_subcategory']);
					if(!is_numeric($wpjobster_featured_price_subcategory)) $wpjobster_featured_price_subcategory = 5;

					update_option('wpjobster_featured_enable', $wpjobster_featured_enable);
					update_option('wpjobster_featured_interval', $wpjobster_featured_interval);
					update_option('wpjobster_featured_homepage', $wpjobster_featured_homepage);
					update_option('wpjobster_featured_category', $wpjobster_featured_category);
					update_option('wpjobster_featured_subcategory', $wpjobster_featured_subcategory);
					update_option('wpjobster_featured_price_homepage', $wpjobster_featured_price_homepage);
					update_option('wpjobster_featured_price_category', $wpjobster_featured_price_category);
					update_option('wpjobster_featured_price_subcategory', $wpjobster_featured_price_subcategory);

					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
				} else {
					update_option( 'wpjobster_featured_enable', 'no' );
					wpj_disabled_settings_error( 'featured_job' );
				}
			}

			wpj_pricing_settings_html();

		echo '</div>';

	}

}

$ps = new PricingSettings();
