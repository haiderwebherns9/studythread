<?php

function wpj_pricing_settings_html() {

	$the_symbol = wpjobster_get_currency_symbol(wpjobster_get_currency_classic());
	$arr_currency = array(
		"" => "-",
		"USD" => "United States Dollar (USD)",
		"EUR" => "Euro (EUR)",
		"JPY" => "Japanese Yen (JPY)",
		"GBP" => "British Pound Sterling (GBP)",
		"AUD" => "Australian Dollar (AUD)",
		"CHF" => "Swiss Franc (CHF)",
		"CAD" => "Canadian Dollar (CAD)",
		"AED" => "United Arab Emirates Dirham (AED)",
		"AFN" => "Afghan Afghani (AFN)",
		"ALL" => "Albanian Lek (ALL)",
		"AMD" => "Armenian Dram (AMD)",
		"ANG" => "Netherlands Antillean Guilder (ANG)",
		"AOA" => "Angolan Kwanza (AOA)",
		"ARS" => "Argentine Peso (ARS)",
		"AWG" => "Aruban Florin (AWG)",
		"AZN" => "Azerbaijani Manat (AZN)",
		"BAM" => "Bosnia-Herzegovina Convertible Mark (BAM)",
		"BBD" => "Barbadian Dollar (BBD)",
		"BDT" => "Bangladeshi Taka (BDT)",
		"BGN" => "Bulgarian Lev (BGN)",
		"BHD" => "Bahraini Dinar (BHD)",
		"BIF" => "Burundian Franc (BIF)",
		"BMD" => "Bermudan Dollar (BMD)",
		"BND" => "Brunei Dollar (BND)",
		"BOB" => "Bolivian Boliviano (BOB)",
		"BRL" => "Brazilian Real (BRL)",
		"BSD" => "Bahamian Dollar (BSD)",
		"BTC" => "Bitcoin (BTC)",
		"BTN" => "Bhutanese Ngultrum (BTN)",
		"BWP" => "Botswanan Pula (BWP)",
		"BYR" => "Belarusian Ruble (BYR)",
		"BZD" => "Belize Dollar (BZD)",
		"CDF" => "Congolese Franc (CDF)",
		"CLF" => "Chilean Unit of Account (UF) (CLF)",
		"CLP" => "Chilean Peso (CLP)",
		"CNY" => "Chinese Yuan (CNY)",
		"COP" => "Colombian Peso (COP)",
		"CRC" => "Costa Rican Colón (CRC)",
		"CUC" => "Cuban Convertible Peso (CUC)",
		"CUP" => "Cuban Peso (CUP)",
		"CVE" => "Cape Verdean Escudo (CVE)",
		"CZK" => "Czech Republic Koruna (CZK)",
		"DJF" => "Djiboutian Franc (DJF)",
		"DKK" => "Danish Krone (DKK)",
		"DOP" => "Dominican Peso (DOP)",
		"DZD" => "Algerian Dinar (DZD)",
		"EEK" => "Estonian Kroon (EEK)",
		"EGP" => "Egyptian Pound (EGP)",
		"ERN" => "Eritrean Nakfa (ERN)",
		"ETB" => "Ethiopian Birr (ETB)",
		"FJD" => "Fijian Dollar (FJD)",
		"FKP" => "Falkland Islands Pound (FKP)",
		"GEL" => "Georgian Lari (GEL)",
		"GGP" => "Guernsey Pound (GGP)",
		"GHS" => "Ghanaian Cedi (GHS)",
		"GIP" => "Gibraltar Pound (GIP)",
		"GMD" => "Gambian Dalasi (GMD)",
		"GNF" => "Guinean Franc (GNF)",
		"GTQ" => "Guatemalan Quetzal (GTQ)",
		"GYD" => "Guyanaese Dollar (GYD)",
		"HKD" => "Hong Kong Dollar (HKD)",
		"HNL" => "Honduran Lempira (HNL)",
		"HRK" => "Croatian Kuna (HRK)",
		"HTG" => "Haitian Gourde (HTG)",
		"HUF" => "Hungarian Forint (HUF)",
		"IDR" => "Indonesian Rupiah (IDR)",
		"ILS" => "Israeli New Sheqel (ILS)",
		"IMP" => "Manx pound (IMP)",
		"INR" => "Indian Rupee (INR)",
		"IQD" => "Iraqi Dinar (IQD)",
		"IRR" => "Iranian Rial (IRR)",
		"ISK" => "Icelandic Króna (ISK)",
		"JEP" => "Jersey Pound (JEP)",
		"JMD" => "Jamaican Dollar (JMD)",
		"JOD" => "Jordanian Dinar (JOD)",
		"KES" => "Kenyan Shilling (KES)",
		"KGS" => "Kyrgystani Som (KGS)",
		"KHR" => "Cambodian Riel (KHR)",
		"KMF" => "Comorian Franc (KMF)",
		"KPW" => "North Korean Won (KPW)",
		"KRW" => "South Korean Won (KRW)",
		"KWD" => "Kuwaiti Dinar (KWD)",
		"KYD" => "Cayman Islands Dollar (KYD)",
		"KZT" => "Kazakhstani Tenge (KZT)",
		"LAK" => "Laotian Kip (LAK)",
		"LBP" => "Lebanese Pound (LBP)",
		"LKR" => "Sri Lankan Rupee (LKR)",
		"LRD" => "Liberian Dollar (LRD)",
		"LSL" => "Lesotho Loti (LSL)",
		"LTL" => "Lithuanian Litas (LTL)",
		"LVL" => "Latvian Lats (LVL)",
		"LYD" => "Libyan Dinar (LYD)",
		"MAD" => "Moroccan Dirham (MAD)",
		"MDL" => "Moldovan Leu (MDL)",
		"MGA" => "Malagasy Ariary (MGA)",
		"MKD" => "Macedonian Denar (MKD)",
		"MMK" => "Myanma Kyat (MMK)",
		"MNT" => "Mongolian Tugrik (MNT)",
		"MOP" => "Macanese Pataca (MOP)",
		"MRO" => "Mauritanian Ouguiya (MRO)",
		"MTL" => "Maltese Lira (MTL)",
		"MUR" => "Mauritian Rupee (MUR)",
		"MVR" => "Maldivian Rufiyaa (MVR)",
		"MWK" => "Malawian Kwacha (MWK)",
		"MXN" => "Mexican Peso (MXN)",
		"MYR" => "Malaysian Ringgit (MYR)",
		"MZN" => "Mozambican Metical (MZN)",
		"NAD" => "Namibian Dollar (NAD)",
		"NGN" => "Nigerian Naira (NGN)",
		"NIO" => "Nicaraguan Córdoba (NIO)",
		"NOK" => "Norwegian Krone (NOK)",
		"NPR" => "Nepalese Rupee (NPR)",
		"NZD" => "New Zealand Dollar (NZD)",
		"OMR" => "Omani Rial (OMR)",
		"PAB" => "Panamanian Balboa (PAB)",
		"PEN" => "Peruvian Nuevo Sol (PEN)",
		"PGK" => "Papua New Guinean Kina (PGK)",
		"PHP" => "Philippine Peso (PHP)",
		"PKR" => "Pakistani Rupee (PKR)",
		"PLN" => "Polish Zloty (PLN)",
		"PYG" => "Paraguayan Guarani (PYG)",
		"QAR" => "Qatari Rial (QAR)",
		"RON" => "Romanian Leu (RON)",
		"RSD" => "Serbian Dinar (RSD)",
		"RUB" => "Russian Ruble (RUB)",
		"RWF" => "Rwandan Franc (RWF)",
		"SAR" => "Saudi Riyal (SAR)",
		"SBD" => "Solomon Islands Dollar (SBD)",
		"SCR" => "Seychellois Rupee (SCR)",
		"SDG" => "Sudanese Pound (SDG)",
		"SEK" => "Swedish Krona (SEK)",
		"SGD" => "Singapore Dollar (SGD)",
		"SHP" => "Saint Helena Pound (SHP)",
		"SLL" => "Sierra Leonean Leone (SLL)",
		"SOS" => "Somali Shilling (SOS)",
		"SRD" => "Surinamese Dollar (SRD)",
		"STD" => "São Tomé and Príncipe Dobra (STD)",
		"SVC" => "Salvadoran Colón (SVC)",
		"SYP" => "Syrian Pound (SYP)",
		"SZL" => "Swazi Lilangeni (SZL)",
		"THB" => "Thai Baht (THB)",
		"TJS" => "Tajikistani Somoni (TJS)",
		"TMT" => "Turkmenistani Manat (TMT)",
		"TND" => "Tunisian Dinar (TND)",
		"TOP" => "Tongan Paʻanga (TOP)",
		"TRY" => "Turkish Lira (TRY)",
		"TTD" => "Trinidad and Tobago Dollar (TTD)",
		"TWD" => "New Taiwan Dollar (TWD)",
		"TZS" => "Tanzanian Shilling (TZS)",
		"UAH" => "Ukrainian Hryvnia (UAH)",
		"UGX" => "Ugandan Shilling (UGX)",
		"UYU" => "Uruguayan Peso (UYU)",
		"UZS" => "Uzbekistan Som (UZS)",
		"VEF" => "Venezuelan Bolívar Fuerte (VEF)",
		"VND" => "Vietnamese Dong (VND)",
		"VUV" => "Vanuatu Vatu (VUV)",
		"WST" => "Samoan Tala (WST)",
		"XAF" => "CFA Franc BEAC (XAF)",
		"XAG" => "Silver (troy ounce) (XAG)",
		"XAU" => "Gold (troy ounce) (XAU)",
		"XCD" => "East Caribbean Dollar (XCD)",
		"XDR" => "Special Drawing Rights (XDR)",
		"XOF" => "CFA Franc BCEAO (XOF)",
		"XPD" => "Palladium Ounce (XPD)",
		"XPF" => "CFP Franc (XPF)",
		"XPT" => "Platinum Ounce (XPT)",
		"YER" => "Yemeni Rial (YER)",
		"ZAR" => "South African Rand (ZAR)",
		"ZMK" => "Zambian Kwacha (pre-2013) (ZMK)",
		"ZMW" => "Zambian Kwacha (ZMW)",
		"ZWL" => "Zimbabwean Dollar (ZWL)"
	);

	$arr = array(
		"yes" => __("Yes",'wpjobster'),
		"no"  => __("No",'wpjobster')
	);
	$arr_site_fees = array(
		"disabled" => __("Disabled",'wpjobster'),
		"percent"  => __("Percent",'wpjobster'),
		"fixed"    => __("Fixed",'wpjobster'),
		"flexible" => __("Flexible",'wpjobster')
	);
	$arr_processing_fees = array(
		"disabled" => __("Disabled",'wpjobster'),
		"fixed"  => __("Fixed",'wpjobster'),
		"percent"    => __("Percent",'wpjobster')
	);
	$sep = array(
		"," => __('Comma (,)','wpjobster'),
		"." => __("Point (.)",'wpjobster')
	);
	$frn = array(
		"front" => __('In front of sum', 'wpjobster')." (".$the_symbol."50)",
		"back"  => __('After the sum', 'wpjobster')." (50".$the_symbol.")"
	);
	$spc = array(
		"yes" => __('Yes', 'wpjobster')." (50 ".$the_symbol.")",
		"no"  => __('No', 'wpjobster')." (50".$the_symbol.")"
	);
	$dec = array(
		"default" => __('Default','wpjobster'),
		"ifneeded" => __("If Needed",'wpjobster'),
		"never" => __("Never",'wpjobster')
	);

	?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('Main Details','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Job Fees','wpjobster'); ?></a></li>
			<li><a href="#withdraw-limit"><?php _e('Withdraw Options','wpjobster'); ?></a></li>
			<li><a href="#limits"><?php _e('Price Limits','wpjobster'); ?></a></li>
			<li><a href="#tabs3"><?php _e('Job Values','wpjobster'); ?></a></li>
			<li><a href="#tabs4"><?php _e('Dropdown Job Values','wpjobster'); ?></a></li>
			<li><a href="#tabs5"><?php _e('Rates','wpjobster'); ?></a></li>
			<li><a href="#tabs6"><?php _e('Featured','wpjobster'); ?></a></li>
			<li><a href="#topup"><?php _e('Top Up','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">
			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs1">
				<table width="100%" class="sitemile-table">
				<?php
				$is_allowed_multi_currency = wpj_is_allowed( 'multi_currency' );
				for ( $i = 1; $i <= 10; $i++ ) {
					$currency_x = 'wpjobster_currency_' . $i;
					$currency_symbol_x = 'wpjobster_currency_symbol_' . $i;
					?>

					<?php if ( ! $is_allowed_multi_currency && $i == 2 ) { ?>
					<tr>
						<td colspan="6">
							<?php wpj_disabled_settings_notice( 'multi_currency' ); ?>
						</td>
					</tr>
					<?php } ?>

					<tr class="<?php if ( ! $is_allowed_multi_currency && $i > 1 ) { echo 'wpjobster-disabled-settings'; } ?>">
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Site currency:','wpjobster'); ?> <?php echo $i; ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_currency, $currency_x); ?></td>

						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e('Currency symbol:','wpjobster'); ?></td>
						<td><input type="text" size="6" name="<?php echo $currency_symbol_x; ?>" value="<?php echo get_option($currency_symbol_x); ?>" /> </td>
					</tr>

					<?php } ?>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Currency symbol position:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($frn, 'wpjobster_currency_position'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Space between sum and symbol:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($spc, 'wpjobster_currency_symbol_space'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Decimals:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($dec, 'wpjobster_decimals'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Decimals sum separator:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($sep, 'wpjobster_decimal_sum_separator'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Thousands sum separator:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($sep, 'wpjobster_thousands_sum_separator'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e( 'Replace "0.00 USD" price with "Free": ' , 'wpjobster' ); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_replace_zero_with_free', 'no'); ?></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="tabs2" style="display: none; ">
			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs2">
				<table width="100%" class="sitemile-table">
					<tr>
						<td></td>
						<td><h2><?php _e("For Sellers", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td><?php wpjobster_theme_bullet( __( 'Please choose between the available site fee types then fill below the amounts.', 'wpjobster' ) ); ?></td>
						<td><?php _e("Enable Site Fee:", "wpjobster"); ?></td>
						<td>
							<?php echo wpjobster_get_option_drop_down( $arr_site_fees, 'wpjobster_enable_site_fee', 'flexible' ); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('If the site fee is set to percent, this value will be used.','wpjobster')); ?></td>
						<td ><?php _e('Percent Fee:','wpjobster'); ?></td>
						<td><input type="text" size="3" name="wpjobster_percent_fee_taken" value="<?php echo get_option('wpjobster_percent_fee_taken'); ?>"/> %</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('If the site fee is set to fixed, this value will be used.','wpjobster')); ?></td>
						<td ><?php _e('Fixed Fee:','wpjobster'); ?></td>
						<td><input type="text" size="3" name="wpjobster_solid_fee_taken" value="<?php echo get_option('wpjobster_solid_fee_taken'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<?php if ( ! wpj_is_allowed( 'flexible_fees' ) ) { ?>
					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
					<tr>
						<td colspan="3">
							<?php wpj_disabled_settings_notice( 'flexible_fees' ); ?>
						</td>
					</tr>
					<?php } ?>

					<tbody class="<?php wpj_disabled_settings_class( 'flexible_fees' ); ?>">
						<tr>
							<td><?php wpjobster_theme_bullet(__('If the site fee is set to flexible, the values below value will be used.','wpjobster')); ?></td>
							<td width="220"><?php _e("Flexible Fees:", "wpjobster"); ?></td>
							<td><?php _e('Percent per Level (Level 0, Level 1, Level 2, Level 3)', 'wpjobster'); ?></td>

						</tr>

						<tr>
							<td><?php wpjobster_theme_bullet(__('If the percent for level 1, 2 or 3 is empty, then the percent for level 0 will be used.','wpjobster')); ?></td>
							<td><?php _e('Base Fee','wpjobster'); ?>:</td>
							<td>
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range0_level0" value="<?php echo get_option('wpjobster_percent_fee_taken_range0_level0'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range0_level1" value="<?php echo get_option('wpjobster_percent_fee_taken_range0_level1'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range0_level2" value="<?php echo get_option('wpjobster_percent_fee_taken_range0_level2'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range0_level3" value="<?php echo get_option('wpjobster_percent_fee_taken_range0_level3'); ?>"/>%
							</td>
						</tr>

						<tr>
							<td><?php wpjobster_theme_bullet(__('If the percent for level 1, 2 or 3 is empty, then the percent for level 0 will be used.','wpjobster')); ?></td>
							<td><?php _e('Fee Over','wpjobster'); ?> <input type="text" size="3" name="wpjobster_percent_fee_taken_range1_base" value="<?php echo get_option('wpjobster_percent_fee_taken_range1_base'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?>:</td>
							<td>
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range1_level0" value="<?php echo get_option('wpjobster_percent_fee_taken_range1_level0'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range1_level1" value="<?php echo get_option('wpjobster_percent_fee_taken_range1_level1'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range1_level2" value="<?php echo get_option('wpjobster_percent_fee_taken_range1_level2'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range1_level3" value="<?php echo get_option('wpjobster_percent_fee_taken_range1_level3'); ?>"/>%
							</td>
						</tr>

						<tr>
							<td><?php wpjobster_theme_bullet(__('If the percent for level 1, 2 or 3 is empty, then the percent for level 0 will be used.','wpjobster')); ?></td>
							<td><?php _e('Fee Over','wpjobster'); ?> <input type="text" size="3" name="wpjobster_percent_fee_taken_range2_base" value="<?php echo get_option('wpjobster_percent_fee_taken_range2_base'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?>:</td>
							<td>
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range2_level0" value="<?php echo get_option('wpjobster_percent_fee_taken_range2_level0'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range2_level1" value="<?php echo get_option('wpjobster_percent_fee_taken_range2_level1'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range2_level2" value="<?php echo get_option('wpjobster_percent_fee_taken_range2_level2'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range2_level3" value="<?php echo get_option('wpjobster_percent_fee_taken_range2_level3'); ?>"/>%
							</td>
						</tr>

						<tr>
							<td><?php wpjobster_theme_bullet(__('If the percent for level 1, 2 or 3 is empty, then the percent for level 0 will be used.','wpjobster')); ?></td>
							<td><?php _e('Fee Over','wpjobster'); ?> <input type="text" size="3" name="wpjobster_percent_fee_taken_range3_base" value="<?php echo get_option('wpjobster_percent_fee_taken_range3_base'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?>:</td>
							<td>
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range3_level0" value="<?php echo get_option('wpjobster_percent_fee_taken_range3_level0'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range3_level1" value="<?php echo get_option('wpjobster_percent_fee_taken_range3_level1'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range3_level2" value="<?php echo get_option('wpjobster_percent_fee_taken_range3_level2'); ?>"/>%
								<input type="text" size="3" name="wpjobster_percent_fee_taken_range3_level3" value="<?php echo get_option('wpjobster_percent_fee_taken_range3_level3'); ?>"/>%
							</td>
						</tr>

						<tr>
							<td ></td>
							<td ></td>
							<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
						</tr>
					</tbody>
					<?php
					$css_class = "";
					if (!wpjobster_processing_fee_allowed()) {
						$css_class = "wpjobster-disabled-settings";
						?>
						<tr>
							<td colspan="3">
								<div class="wpjobster-update-nag wpjobster-notice">
									The settings below are only available for the Developer and Entrepreneur licenses. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
								</div>
							</td>
						</tr>
					<?php } ?>
					<tr class="<?php echo $css_class; ?>">
						<td></td>
						<td><h2><?php _e("For Buyers", "wpjobster"); ?></h2></td>
						<td>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Enable/Disable processing fees chargeable to buyer for site admin','wpjobster')); ?></td>
						<td><?php _e('Enable Processing Fee','wpjobster'); ?>:

						<td>
							<?php echo wpjobster_get_option_drop_down($arr_processing_fees, 'wpjobster_enable_buyer_processing_fees', 'no') ?>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Processing fees chargeable to buyer for site admin','wpjobster')); ?></td>
						<td><?php _e('Fixed Processing Fee Amount','wpjobster'); ?>:
						<td>
							<input type="text" size="3" name="wpjobster_buyer_processing_fees" value="<?php echo get_option('wpjobster_buyer_processing_fees'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Processing fees percent chargeable to buyer for site admin','wpjobster')); ?></td>
						<td><?php _e('Processing Fee Percent','wpjobster'); ?>:
						<td>
							<input type="text" size="3" name="wpjobster_buyer_processing_fees_percent" value="<?php echo get_option('wpjobster_buyer_processing_fees_percent'); ?>"/> <?php echo "%"; ?>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Enable/Disable refunding processing fees when the transaction is closed','wpjobster')); ?></td>
						<td><?php _e('Refund The Fee if Job Cancelled','wpjobster'); ?>:
						<td>
							<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_refund_buyer_processing_fees') ?>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Enable/Disable Tax.','wpjobster')); ?></td>
						<td><?php _e('Enable Tax','wpjobster'); ?>:

						<td>
							<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_site_tax', 'no') ?>
						</td>
					</tr>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('','wpjobster')); ?></td>
						<td><?php _e('Tax Percent','wpjobster'); ?>:
						<td>
							<input type="number" step="any" min="0" max="100" size="3" name="wpjobster_tax_percent" value="<?php echo get_option('wpjobster_tax_percent'); ?>"/>%
						</td>
					</tr>

					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('If enabled, the tax applies to the full price including the processing fee.','wpjobster')); ?></td>
						<td><?php _e('Apply Tax Over Processing Fee','wpjobster'); ?>:
						<td>
							<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_processingfee_tax'); ?>
						</td>
					</tr>

					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Enable/Disable refunding tax when the transaction is closed','wpjobster')); ?></td>
						<td><?php _e('Refund The Tax if Job Cancelled','wpjobster'); ?>:
						<td>
							<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_refund_tax','no') ?>
						</td>
					</tr>
					<?php
					$wpjobster_country_taxes = get_option('wpjobster_country_taxes');
					$wpjobster_country_taxes_percentage = get_option('wpjobster_country_taxes_percentage');

					$c = get_country_name();
					if($wpjobster_country_taxes_percentage){
						foreach($wpjobster_country_taxes_percentage as $country_cd=>$percent){
							?>
							<tr class="<?php echo $css_class; ?>">
								<td><?php wpjobster_theme_bullet(__('Select a country and tax for it','wpjobster')); ?></td>
								<td><?php _e('Tax for country','wpjobster'); ?>:
									<select class="grey_input styledselect" style="width:80px;" name="wpjobster_country_taxes[]">
										<?php
										list_options($c,$country_cd);
										?>
									</select>
								</td>
								<td>
									<input  type="number" step="any" min="0" max="100" size="3" name="wpjobster_country_taxes_percentage[]" value="<?php echo $percent?>">%
								</td>
							</tr> <?php
						}
					}
					?>
					<tr class="<?php echo $css_class; ?>">
						<td><?php wpjobster_theme_bullet(__('Select a country and tax for it','wpjobster')); ?></td>
						<td><?php _e('Tax for country','wpjobster'); ?>:
							<?php $country_code = user(get_current_user_id(), 'country_code');
							$c = get_country_name();
						//print_r($c); ?>
						<select class="grey_input styledselect" style="width:80px;" name="wpjobster_country_taxes[]">
							<?php
							$c = get_country_name();
							list_options($c,$country_code);
							?>
						</select> <?php
						//echo wpjobster_get_option_drop_down($cc, 'wpjobster_country_taxes[]',$country_code)  ;
						?>
						</td>

						<td>
							<input name="wpjobster_country_taxes_percentage[]" value=""  type="number" step="any" min="0" max="100" size="3">%
						</td>
					</tr>
					<!--label><?php echo __('Country','wpjobster'); ?>:
					<p class="lighter">
					<select class="grey_input styledselect" name="country_code">
						<?php
						$c = get_country_name();
					//list_options($c,);
						?>
					</select>
					</p>
					</label-->
					<tr class="<?php echo $css_class; ?>">
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>



		<div id="withdraw-limit" style="display: none; ">
			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=withdraw-limit">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Minimum Withdrawal Amount:','wpjobster'); ?></td>
						<td><input type="text" size="10" name="wpjobster_withdraw_limit" value="<?php echo get_option('wpjobster_withdraw_limit'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>


					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Enable Paypal Withdraw:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_paypal_withdraw'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Enable Payoneer Withdraw:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_payoneer_withdraw'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Enable Bank Withdraw:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_bank_withdraw'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('User need to validate his request before the withdrawal appear in the admin section','wpjobster')); ?></td>
						<td ><?php _e('Enable Withdraw E-mail Verification:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_withdraw_email_verification', 'no'); ?></td>
					</tr>

					<?php do_action( 'wpjobster_show_enable_withdraw_admin_gateway', $arr ); ?>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_withdraw" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>
		</div>


		<div id="limits" style="display: none; ">
			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=limits">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 0 max job amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level0_max" value="<?php echo get_option('wpjobster_level0_max'); ?>"/>  <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 0 max extra amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level0_max_extra" value="<?php echo get_option('wpjobster_level0_max_extra'); ?>"/>  <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 1 max job amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level1_max" value="<?php echo get_option('wpjobster_level1_max'); ?>"/>  <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 1 max extra amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level1_max_extra" value="<?php echo get_option('wpjobster_level1_max_extra'); ?>"/>  <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 2 max job amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level2_max" value="<?php echo get_option('wpjobster_level2_max'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 2 max extra amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level2_max_extra" value="<?php echo get_option('wpjobster_level2_max_extra'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 3 max job amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level3_max" value="<?php echo get_option('wpjobster_level3_max'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('leave blank to be unlimited'); ?></td>
						<td width="200"><?php _e('Level 3 max extra amount','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_level3_max_extra" value="<?php echo get_option('wpjobster_level3_max_extra'); ?>"/>   <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td colspan="3"></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__("Minimum price that can be set for a job.", "wpjobster")); ?></td>
						<td width="220"><?php _e('Min Job amount:','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_min_job_amount" value="<?php echo get_option('wpjobster_min_job_amount'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__("Minimum price that can be set for a custom offer.", "wpjobster")); ?></td>
						<td width="220"><?php _e('Min custom offer price:','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_offer_price_min" value="<?php echo get_option('wpjobster_offer_price_min'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__("Maximum price that can be set for a custom offer.", "wpjobster")); ?></td>
						<td ><?php _e('Max custom offer price:','wpjobster'); ?></td>
						<td><input type="text" size="5" name="wpjobster_offer_price_max" value="<?php echo get_option('wpjobster_offer_price_max'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_savelimits" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="topup" style="display: none; ">
			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=topup">

				<?php if (!wpjobster_topup_allowed()) { ?>

				<div class="wpjobster-update-nag wpjobster-notice">
					This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
				</div>

				<?php } ?>

				<table width="100%" class="sitemile-table <?php if (!wpjobster_topup_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

					<tr>
						<td></td>
						<td><h2><?php _e("Top Up Settings", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Allow your users to purchase in-site credits using PayPal.','wpjobster')); ?></td>
						<td width="21%"><?php _e('Enable Top Up','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_topup', 'no'); ?></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_topup" value="<?php _e('Save Settings','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=topup">
				<table width="100%" class="sitemile-table <?php if (!wpjobster_topup_allowed()) { echo "wpjobster-disabled-settings"; } ?>">
					<tr>
						<td></td>
						<td><h2><?php _e("Top Up Packages", "wpjobster"); ?></h2></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><strong><?php _e("Cost", "wpjobster"); ?></strong></td>
						<td><strong><?php _e("Credit", "wpjobster"); ?></strong></td>
						<td><strong><?php _e("Options", "wpjobster"); ?></strong></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Add a top up package. Cost = the amount user will pay for a particular top up package. Credit = the amount the user will be credited with. Difference between the two is what you will be making on those transactions.','wpjobster')); ?></td>
						<td width="21%"><?php _e('Add new package:','wpjobster'); ?></td>
						<td><input name="newcost" type="text" size="10" /> <?php echo wpjobster_get_currency_classic(); ?></td>
						<td><input name="newcredit" type="text" size="10" /> <?php echo wpjobster_get_currency_classic(); ?></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_topup_package" value="<?php _e('Add Package','wpjobster'); ?>"/></td>
					</tr>

					<?php
					global $wpdb;
					$ss = "select * from ".$wpdb->prefix."job_topup_packages order by cost asc";
					$r = $wpdb->get_results($ss);
					if(count($r) > 0):

					foreach($r as $row) {

						echo '<tr id="user_package'.$row->id.'">';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>'.wpjobster_get_show_price_classic($row->cost,2).'</td>';
						echo '<td>'.wpjobster_get_show_price_classic($row->credit, 2).'</td>';
						echo '<td><a href="#" rel="'.$row->id.'" class="delete_user_package">'.__('Delete','wpjobster').'</a></td>';
						echo '</tr>';

					}
					else:
						echo '<tr>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>' . __('No packages added yet.','wpjobster') . '</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '</tr>';

					endif;
					?>
				</table>
			</form>
			<?php if (!is_demo_admin()) { ?>
			<script>
				var $ = jQuery;
				$(document).ready(function() {
					$('.delete_user_package').click(function() {
						var id = $(this).attr('rel');
						$.ajax({
							type: "POST",
							url: "<?php echo get_bloginfo('url'); ?>/",
							data: "delete_user_package="+id,
							success: function(msg){
								$("#user_package" + id).hide();
							}
						});
					});
				});
			</script>
			<?php } ?>
		</div>

		<div id="tabs3" style="display: none;">

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs3">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e('Job fixed amount:','wpjobster'); ?></td>
						<td><input type="text" size="15" name="wpjobster_job_fixed_amount" value="<?php echo get_option('wpjobster_job_fixed_amount'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td colspan="3"><?php _e('OR','wpjobster'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Enable free text input:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_free_input_box'); ?></td>
					</tr>

					<tr>
						<td colspan="3">OR</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Enable dropdown values:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_dropdown_values'); ?></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save3" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>
		</div>

		<div id="tabs4" style="display: none;">

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs4">
				<table width="100%" class="sitemile-table">

					<tr>
						<td width="210"><?php _e('Add new cost:','wpjobster'); ?></td>
						<td><input name="newcost" type="text" size="10" /> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td width="210"></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_addnewcost" value="<?php _e('Add','wpjobster'); ?>" /></td>
					</tr>

				</table>
			</form>

			<?php if (!is_demo_admin()) { ?>
			<script>
				var $ = jQuery;
				$(document).ready(function() {

					$('.delete_job_cost').click(function() {

						var id = $(this).attr('rel');

						$.ajax({
							type: "POST",
							url: "<?php echo get_bloginfo('url'); ?>/",
							data: "delete_variable_job_fee="+id,
							success: function(msg){
								$("#job_cost_" + id).hide();

							}
						});
					});
				});
			</script>
			<?php }

			global $wpdb;

			$ss = "select * from ".$wpdb->prefix."job_var_costs order by cost asc";
			$r = $wpdb->get_results($ss);

			if(count($r) > 0):

				echo '<table width="400">';
			echo '<tr>';
			echo '<td><b>' . __("Cost",'wpjobster') . '</b></td>';
			echo '<td><b>' . __("Options",'wpjobster') . '</b></td>';
			echo '</tr>';

			foreach($r as $row) {

				echo '<tr id="job_cost_'.$row->id.'">';
				echo '<td width="50">'.wpjobster_get_show_price_classic($row->cost,2).'</td>';
				echo '<td width="100"><a href="#" rel="'.$row->id.'" class="delete_job_cost">'.__('Delete','wpjobster').'</a></td>';
				echo '</tr>';

			}

			echo '</table>';

			else:

				echo __('No values added yet.','wpjobster');

			endif;

			?>

		</div>

		<div id="tabs5" style="display: none; ">

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs5">

				<table width="100%" class="sitemile-table">
					<tr>
						<td></td>
						<td colspan="2">
							<p><?php _e("The current rates represent the currency according to USD which is the base currency for OpenExchangeRates.org. If your base currency is different than USD, the prices will be converted in USD first then from USD in your currency.", "wpjobster"); ?></p>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__("If you don't have one, please visit openexchangerates.org and sign up for any of the plans. The smallest plan is good enough as long as it offers at least 100 API Requests per month.", "wpjobster")); ?></td>
						<td><?php _e("OpenExchange App ID:", "wpjobster"); ?></td>
						<td><input type="text" size="15" name="openexchangerates_appid" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('openexchangerates_appid') ); ?>" />
							<?php if (get_option('openexchangerates_appid') == '') { ?>
							&nbsp; <a href="https://openexchangerates.org/signup/free" target="_blank"><?php _e('Sign Up for a free Open Exchange Rates account', 'wpjobster'); ?></a>
							<?php } ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e('USD:','wpjobster'); ?></td>
						<td><?php _e("The base currency to which all the exchange rates are relative. It can't be changed.", "wpjobster"); ?></td>
					</tr>


					<?php

					$json = get_option('exchange_rates');
					$exchangeRates = json_decode($json);

					global $wpjobster_currencies_array;
					foreach ($wpjobster_currencies_array as $wpjobster_currency) {
						if ($wpjobster_currency != "USD") {
							?>
							<tr>
								<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
								<td><?php echo $wpjobster_currency . ':'; ?></td>
								<td><input type="text" size="15" name="wpjobster_<?php echo $wpjobster_currency; ?>_currency" value="<?php echo $exchangeRates->rates->$wpjobster_currency; ?>"/></td>
							</tr>
							<?php
						}
					}
					?>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save5" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>
		</div>

		<!-- Featured tab -->
		<div id="tabs6" style="display: none; ">

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=pricing-settings&active_tab=tabs6">
				<?php wpj_disabled_settings_notice( 'featured_job' ); ?>
				<table width="100%" class="sitemile-table <?php wpj_disabled_settings_class( 'featured_job' ); ?>">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e("Enable featured jobs", "wpjobster"); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_featured_enable'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Featured interval (days):','wpjobster'); ?></td>
						<td><input type="number" size="45" name="wpjobster_featured_interval" value="<?php echo get_option('wpjobster_featured_interval'); ?>"/> <?php _e("days", "wpjobster"); ?></td>
					</tr>

					<tr>
						<td colspan="3">  </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Number of featured jobs on Homepage:','wpjobster'); ?></td>
						<td><input type="number" size="45" name="wpjobster_featured_homepage" value="<?php echo get_option('wpjobster_featured_homepage'); ?>"/> <?php _e("jobs", "wpjobster"); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Price for featured job on Homepage (main currency):','wpjobster'); ?></td>
						<td><input type="number" size="45" step="any" name="wpjobster_featured_price_homepage" value="<?php echo get_option('wpjobster_featured_price_homepage'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td colspan="3">  </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Number of featured jobs on category pages:','wpjobster'); ?></td>
						<td><input type="number" size="45" name="wpjobster_featured_category" value="<?php echo get_option('wpjobster_featured_category'); ?>"/> <?php _e("jobs", "wpjobster"); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Price for featured job on category pages (main currency):','wpjobster'); ?></td>
						<td><input type="number" size="45" step="any" name="wpjobster_featured_price_category" value="<?php echo get_option('wpjobster_featured_price_category'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td colspan="3">  </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Number of featured jobs on subcategory pages:','wpjobster'); ?></td>
						<td><input type="number" size="45" name="wpjobster_featured_subcategory" value="<?php echo get_option('wpjobster_featured_subcategory'); ?>"/> <?php _e("jobs", "wpjobster"); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Price for featured job on subcategory pages (main currency):','wpjobster'); ?></td>
						<td><input type="number" size="45" step="any" name="wpjobster_featured_price_subcategory" value="<?php echo get_option('wpjobster_featured_price_subcategory'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save6" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

			<p><?php _e("*If you change the interval, the current featured jobs are not affected by this change.", "wpjobster"); ?></p>
			<p><?php _e("*If you change the number of featured jobs per page and it is less than previous, the current featured jobs will show until their period expires, even if there will be more jobs than the new value.", "wpjobster"); ?></p>
		</div>
	</div>

<?php

}
