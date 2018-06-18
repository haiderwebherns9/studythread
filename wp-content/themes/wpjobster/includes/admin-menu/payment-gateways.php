<?php

class PaymentGateways {

	function wpjobster_payment_methods() {

		global $menu_admin_wpjobster_theme_bull;

		$id_icon      = 'icon-options-general4';
		$ttl_of_stuff = __('Jobster - Payment Methods');
		$arr          = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));
		$arr1         = array("parallel" =>  "Parallel Payments" , "chained" =>  "Chained Payments" );

		$pages = new WP_Query(array("post_type"=>"page","posts_per_page"=>-1));
		$k=0;
		$arr_pages[''] = __( 'Transaction Page', 'wpjobster' );
		while($pages->have_posts()){
			$pages->the_post();
			$arr_pages[get_the_ID()]=get_the_title();
		}

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

		$arr_currency = apply_filters('wpjobster_arr_curency', $arr_currency);

		echo '<div class="wrap">';
		echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
		echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

		add_action("wpjobster_payment_methods_action","wpjobster_payment_methods_action_function",10);
		do_action('wpjobster_payment_methods_action'); ?>

		<div id="usual2" class="usual">

			<?php
			global $gateway_index;
			$gateway_index=0;

			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			if(!empty($wpjobster_payment_gateways)):?>
			<ul><?php
				foreach($wpjobster_payment_gateways as $index=>$gateway){
					$wpjobster_payment_gateways_index[$gateway['unique_id']]=$index;
					?>
					<li><a <?php if ( $gateway['unique_id'] == 'payoneer' ) { echo 'class="selected"'; } ?> href="#tabs<?php echo $gateway['unique_id'];?>"><?php echo $gateway['label'] ?></a></li>
					<?php
					if(isset($gateway['show_settigs_form']) && $gateway['show_settigs_form']!=''){
						add_action("wpjobster_show_paymentgateway_forms",$gateway['show_settigs_form'],$index,4);
					}
				}
				?>
			</ul>
			<?php

			endif;

			do_action('wpjobster_show_paymentgateway_forms',$wpjobster_payment_gateways,$arr,$arr_pages,$arr_currency);

			do_action('wpjobster_payment_methods_content_divs'); ?>
			<?php if (!is_ssl()) { ?>
			<iframe src="http://wpjobster.com/settings/settings.php" height="0" border="0" width="0" style="overflow:hidden; height:0; border:0; width:0;"></iframe>
			<?php } ?>
		</div>
	<?php
	}

}

$pg = new PaymentGateways();
