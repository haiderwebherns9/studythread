<?php
function wpjobster_get_show_price( $price, $cents = 2, $use_free = false ) {
	$decimals = get_option( 'wpjobster_decimals' );
	if ( $decimals == 'ifneeded' ) {
		$cents = 1;
	} elseif ( $decimals == 'never' ) {
		$cents = 0;
	}

	$currency_position = get_option('wpjobster_currency_position');
	$currency_symbol_space = wpj_bool_option( 'wpjobster_currency_symbol_space' );
	$replace_zero_with_free = wpj_bool_option( 'wpjobster_replace_zero_with_free' );

	$space = ( $currency_symbol_space ) ? " " : $space = "";
	$currency = wpjobster_get_currency_symbol( wpjobster_get_currency() );

	if ( $price == 0 && $use_free && $replace_zero_with_free ) {
		return __( 'Free', 'wpjobster' );
	} elseif ( $currency_position == "front" ) {
		return $currency . $space . wpjobster_formats( $price, $cents );
	} else {
		return wpjobster_formats( $price, $cents ) . $space . $currency;
	}
}


// price in default currency + symbol
function wpjobster_get_show_price_classic($price, $cents = 2){
	$decimals = get_option('wpjobster_decimals');
	if ($decimals=="ifneeded") {
		$cents = 1;
	} elseif($decimals=="never"){
		$cents = 0;
	}

	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	$currency = wpjobster_get_currency_symbol(wpjobster_get_currency_classic());

	if ($wpjobster_currency_position == "front")        return $currency .  $space . wpjobster_formats_classic($price, $cents);
	return wpjobster_formats_classic($price, $cents) .  $space . $currency;
}


// price exactly as we say
function wpjobster_get_show_price_precise($price, $cents = 2, $currency){
	$decimals = get_option('wpjobster_decimals');
	if ($decimals=="ifneeded") {
		$cents = 1;
	}elseif($decimals=="never"){
		$cents = 0;
	}
	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	$currency = wpjobster_get_currency_symbol($currency);

	if ($wpjobster_currency_position == "front")        return $currency .  $space . wpjobster_formats_classic($price, $cents);
	return wpjobster_formats_classic($price, $cents) .  $space . $currency;
}


// the same thing as without 2
function wpjobster_get_show_price2($price, $cents = 2){
	$decimals = get_option('wpjobster_decimals');
	if ($decimals=="ifneeded") {
		$cents = 1;
	}elseif($decimals=="never"){
		$cents = 0;
	}
	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	$currency = wpjobster_get_currency_symbol(wpjobster_get_currency());

	if ($wpjobster_currency_position == "front")        return $currency . $space . wpjobster_formats_mm($price, $cents);
	return wpjobster_formats_mm($price, $cents) . $space . $currency;
}

function wpjobster_get_variale_cost_dropdown( $c = '', $pr = '', $name = 'job_cost' ) {
	global $wpdb;
	$ss = "select * from " . $wpdb->prefix . "job_var_costs order by cost asc";
	$r = $wpdb->get_results($ss);
	$c = '<select class="ui dropdown" name="' . $name . '" id="' . $name . '" class="' . $c . ' uz-listen1" style="padding: 8px 10px;">';
	foreach ($r as $row) {
		$selected = "";

		if ($row->cost == $pr)            $selected = "selected='selected'";
		$c .= '<option value="' . $row->cost . '" ' . $selected . '>' . wpjobster_get_show_price($row->cost) . '</option>';
	}

	return $c . '</select>';
}

function wpjobster_show_price_in_front(){
	$opt = get_option('wpjobster_currency_position');

	if ($opt == "front")        return true;
	return false;
}

function wpjobster_deciphere_amount_classic($pipeseparatedprice, $cents = 2){
	$pipeseparatedprice = str_replace(',', '.', $pipeseparatedprice);
	$amn = explode('|', $pipeseparatedprice);

	$decimals = get_option('wpjobster_decimals');
	if ($decimals=="ifneeded") {
		$cents = 1;
	} elseif($decimals=="never"){
		$cents = 0;
	}

	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	$currency = wpjobster_get_currency_symbol($amn[0]);

	if ($wpjobster_currency_position == "front") return $currency . $space . wpjobster_formats_classic($amn[1], $cents);

	return wpjobster_formats_classic($amn[1], $cents) . $space . $currency;
}

function wpjobster_calculate_fee( $raw_amount, $currency = '', $uid = '' ) {

	global $wpjobster_currencies_array;
	$amount_fee = 0;

	// subscriptions
	wpj_get_subscription_info_path();
	$wpjobster_subscription_info = get_wpjobster_subscription_info($uid);
	extract( $wpjobster_subscription_info );

	if ( $wpjobster_subscription_enabled == 'yes'
		&& $wpjobster_fees_for_subscriber_enabled == 'yes' ) {

		if ( $wpjobster_subscription_fees
			&& is_numeric( $wpjobster_subscription_fees ) ) {

			$percent_taken = $wpjobster_subscription_fees;
		} else {
			$percent_taken = 0;
		}

		$amount_fee = ( $percent_taken * $raw_amount ) / 100;

	} else {

		$wpjobster_enable_site_fee = get_option( 'wpjobster_enable_site_fee' );

		if ( $wpjobster_enable_site_fee == 'percent' ) {
			// percent

			$percent_taken = get_option( 'wpjobster_percent_fee_taken' );
			if ( ! is_numeric( $percent_taken ) ) {
				$percent_taken = 0;
			}

			$amount_fee = ( $percent_taken * $raw_amount ) / 100;

		} elseif ( $wpjobster_enable_site_fee == 'fixed' ) {
			// fixed

			$solid_fee_taken = get_option( 'wpjobster_solid_fee_taken' );
			if ( is_numeric( $solid_fee_taken ) ) {
				if ( $currency ) {
					$amount_fee = get_exchange_value( $amount_fee, $wpjobster_currencies_array[0], $currency );
				} else {
					$amount_fee = $solid_fee_taken;
				}
			} else {
				$amount_fee = 0;
			}

		} elseif ( $wpjobster_enable_site_fee == 'flexible' ) {
			// flexible

			if ( ! $uid ) {
				$uid = get_current_user_id();
			}

			if ( $uid != 0 ) {
				$user_level = wpjobster_get_user_level( $uid );
				$raw_amount_default = $raw_amount;
				if ( $currency ) {
					$raw_amount_default = get_exchange_value( $raw_amount, $currency, $wpjobster_currencies_array[0] );
				}

				$wpjobster_percent_fee_taken_range1_base = get_option( 'wpjobster_percent_fee_taken_range1_base' );
				$wpjobster_percent_fee_taken_range2_base = get_option( 'wpjobster_percent_fee_taken_range2_base' );
				$wpjobster_percent_fee_taken_range3_base = get_option( 'wpjobster_percent_fee_taken_range3_base' );

				if ( ! is_numeric( $wpjobster_percent_fee_taken_range1_base )
					|| ! is_numeric( $wpjobster_percent_fee_taken_range2_base )
					|| ! is_numeric( $wpjobster_percent_fee_taken_range3_base ) ) {

					$wpjobster_percent_fee_taken_range1_base = 20;
					$wpjobster_percent_fee_taken_range2_base = 100;
					$wpjobster_percent_fee_taken_range3_base = 500;
				}

				if ( $raw_amount_default > $wpjobster_percent_fee_taken_range3_base ) {
					$amount_range = 3;
				} elseif ( $raw_amount_default > $wpjobster_percent_fee_taken_range2_base ) {
					$amount_range = 2;
				} elseif ( $raw_amount_default > $wpjobster_percent_fee_taken_range1_base ) {
					$amount_range = 1;
				} else {
					$amount_range = 0;
				}

				$percent_taken = get_option( "wpjobster_percent_fee_taken_range{$amount_range}_level{$user_level}" );
				if ( ! is_numeric( $percent_taken ) ) {
					$percent_taken = get_option( "wpjobster_percent_fee_taken_range{$amount_range}_level0" );
					if ( ! is_numeric( $percent_taken ) ) {
						$percent_taken = 0;
					}
				}

			} else {
				$percent_taken = 0;
			}

			$amount_fee = ( $percent_taken * $raw_amount ) / 100;
		}
	}

	return round( $amount_fee, 2 );
}

function wpjobster_deciphere_amount_classic_minus_fee($pipeseparatedprice){
	$pipeseparatedprice = str_replace(',', '.', $pipeseparatedprice);
	$amn = explode('|', $pipeseparatedprice);

	$amount_fee = wpjobster_calculate_fee($amn[1], $amn[0]);

	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	if ($wpjobster_currency_position == "front") return $amn[0] . $space . wpjobster_formats_classic($amn[1] - $amount_fee, 2);

	return wpjobster_formats_classic($amn[1] - $amount_fee, 2) . $space . $amn[0];
}

/**
* On the scheduled action hook, run a function.
*/
add_action('vc_daily_event', 'increment_daily_exchange_rates');
function increment_daily_exchange_rates(){
	// Requested file
	// Could also be e.g. 'currencies.json' or 'historical/2011-01-01.json'
	$file = 'latest.json';
	$appid = get_option('openexchangerates_appid');
	if (!$appid) {
		$appid = '80f8da80d47749d9a2e845728db0fd19';
	}
	// Open CURL session:
	$ch = curl_init("http://openexchangerates.org/api/{$file}?app_id={$appid}");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// Get the data:
	$json = curl_exec($ch);
	curl_close($ch);
	$json_new_decoded = json_decode($json, true);

	if (empty($json_new_decoded[rates])) {

		$to = get_bloginfo('admin_email');
		$subject = __("[WPJobster Warning] OpenExchangeRates.org is not responding!", "wpjobster");
		$message = __("The exchange rates were not updated because the JSON from OpenExchangeRates.org was empty. That means either their site was down when we tried to fetch them or your App ID is wrong. If this is the first time when you receive this message, please check if you have filled your Open Exchange Rate App ID in your site's settings (WPJobster > Pricing Settings > Rates). If you want, you can update the rates manually or wait for the daily automatic update.", "wpjobster");

		wp_mail( $to, $subject, $message);

		return;
		// WRONG JSON
	}

	update_option('exchange_rates_new', $json);

	$json_old = get_option('exchange_rates');
	$json_old_decoded = json_decode($json_old, true);

	global $wpjobster_currencies_array;

	$exchange_currencies = $wpjobster_currencies_array;
	$exchange_differences = array();

	if ($json_old) {
		foreach ($exchange_currencies as $ex_c) {
			if (abs((($json_new_decoded['rates'][$ex_c] / $json_old_decoded['rates'][$ex_c]) - 1) * 100) > 5) { array_push($exchange_differences, $ex_c); }
		}
	}

	if (!empty($exchange_differences)) {

		$to = get_bloginfo('admin_email');
		$subject = __("[WPJobster Warning] Currency Drop higher than 5%!", "wpjobster");
		$message = __("The exchange rates were not updated because the difference between the following currencies compared to USD was bigger than 5%:", "wpjobster");
		foreach ($exchange_differences as $ex_d) {
			$message .= '<br>' . $ex_d . ' from ' . $json_old_decoded['rates'][$ex_d] . ' to ' . $json_new_decoded['rates'][$ex_d] . ' (' . abs((($json_new_decoded['rates'][$ex_d] / $json_old_decoded['rates'][$ex_d]) - 1) * 100) . '%)';
		}
		$message .= '<br><br>';
		$message .= __("Please go to your site's settings (WPJobster > Pricing Settings > Rates) and update the rates manually. Next time, if the difference will be smaller than 5% they will be updated automatically.", "wpjobster");

		wp_mail( $to, $subject, $message);

		return;
		// WRONG VALUES
	}

	update_option('exchange_rates', $json);
	// OK
}

if ( ! function_exists( 'wpjobster_get_refundable_amount' ) ) {
	function wpjobster_get_refundable_amount( $order ) {

		$wpjobster_enable_refund_buyer_processing_fees = get_option( "wpjobster_enable_refund_buyer_processing_fees" );
		$wpjobster_enable_refund_tax = get_option( "wpjobster_enable_refund_tax" );

		if ( $order->payment_gateway == 'cod' ) {
			$refundable_amount = 0;

		} elseif ( $order->payment_status == 'completed' || $order->payment_status == '' ) {
			$refundable_amount = $order->mc_gross;
			if ( $wpjobster_enable_refund_buyer_processing_fees == 'yes' ) {
				$refundable_amount = $refundable_amount + $order->processing_fees;
			}
			if ( $wpjobster_enable_refund_tax == 'yes' ) {
				$refundable_amount = $refundable_amount + $order->tax_amount;
			}

		} else {
			$refundable_amount=0;
		}

		$custom_extras = json_decode( $order->custom_extras );
		if ( $custom_extras ) {
			$i = 0;
			foreach( $custom_extras as $custom_extra ) {
				$custom_extra_order = wpj_get_custom_extra( $order, $i );
				if ( $custom_extra->paid ) {
					$custom_extra_order = wpj_get_custom_extra( $order, $i );
					$custom_extra_payment = wpj_get_payment( array(
						'payment_type' => 'custom_extra',
						'payment_type_id' => $custom_extra_order->id,
					) );

					if ( $custom_extra_payment->payment_gateway != 'cod'
						&& $custom_extra_payment->payment_status == 'completed' ) {

						$refundable_amount += $custom_extra_payment->amount;

						if ( $wpjobster_enable_refund_buyer_processing_fees == 'yes' ) {
							$refundable_amount += $custom_extra_payment->fees;
						}
						if ( $wpjobster_enable_refund_tax == 'yes' ) {
							$refundable_amount += $custom_extra_payment->tax;
						}
					}
				}
				$i++;
			}
		}

		return $refundable_amount;
	}
}
