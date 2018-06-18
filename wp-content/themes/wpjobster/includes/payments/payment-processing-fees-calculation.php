<?php
if ( ! function_exists( 'wpjobster_get_site_processing_fee' ) ) {
	function wpjobster_get_site_processing_fee( $price, $extr_ttl, $shipping ) {

		// initialize in order to return when disabled
		$buyer_processing_fees_percent_amount = 0;

		$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );

		if ( $buyer_processing_fees_enabled == 'percent' ) {
			$buyer_processing_fees_percent = get_option( 'wpjobster_buyer_processing_fees_percent' );
			if ( $buyer_processing_fees_percent > 0 ) {
				$total_processing_amount = $price + $extr_ttl + $shipping;
				$buyer_processing_fees_percent_amount = $total_processing_amount * $buyer_processing_fees_percent / 100;
			} else {
				$buyer_processing_fees_percent_amount = 0;
			}
		}

		if ( $buyer_processing_fees_enabled == 'fixed' ) {
			$buyer_processing_fees_percent_amount = get_option( 'wpjobster_buyer_processing_fees' );
		}

		return wpjobster_formats_special( $buyer_processing_fees_percent_amount );
	}
}

function wpjobster_display_processing_fees_label() {
	// displays the label according to the processing fee type
	if ( get_option( 'wpjobster_enable_buyer_processing_fees' ) == 'percent' ) {
		echo sprintf( __( 'Processing Fees (%s&#37;):', 'wpjobster' ), get_option( 'wpjobster_buyer_processing_fees_percent' ) );
	} else {
		echo __( 'Processing Fees:', 'wpjobster' );
	}
}
