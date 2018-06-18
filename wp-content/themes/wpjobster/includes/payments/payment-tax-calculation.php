<?php
function wpjobster_get_tax($country_code){
	$wpjobster_country_taxes_percentage = get_option('wpjobster_country_taxes_percentage');
	if(isset($wpjobster_country_taxes_percentage[$country_code])){
		$wpjobster_tax_percent=$wpjobster_country_taxes_percentage[$country_code];
	}else{
		$wpjobster_tax_percent = get_option('wpjobster_tax_percent');
	}
	return $wpjobster_tax_percent;
}

if ( ! function_exists( 'wpjobster_get_site_tax' ) ) {
	function wpjobster_get_site_tax( $price, $extr_ttl=0, $shipping=0, $buyer_processing_fees=0 ) {

		if ( wpj_bool_option( 'wpjobster_enable_site_tax' ) ) {
			$cur_uid = get_current_user_id();
			$country_code = get_user_meta( $cur_uid, 'country_code', true );
			$wpjobster_tax_percent = wpjobster_get_tax( $country_code );

			if ( wpj_bool_option( 'wpjobster_enable_processingfee_tax' ) && $buyer_processing_fees > 0 ) {
				$total_taxable_amount = $price + $extr_ttl + $shipping + $buyer_processing_fees;
			} else {
				$total_taxable_amount = $price + $extr_ttl + $shipping;
			}

			$tax_amount = $total_taxable_amount * $wpjobster_tax_percent / 100;
		} else {
			$tax_amount = 0;
		}

		return wpjobster_formats_special( $tax_amount );
	}
}
