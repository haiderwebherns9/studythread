<?php

if ( get_option( 'wpjobster_update_404_defaults_beginner' ) != 'done' ) {
	update_option( 'wpjobster_update_404_defaults_beginner', 'done' );

	update_option( 'wpjobster_enable_site_fee', 'percent' );
	update_option( 'wpjobster_percent_fee_taken', '20' );

	update_option( 'wpjobster_featured_enable', 'no' );
	update_option( 'wpjobster_enable_multiples', 'no' );
	update_option( 'wpjobster_enable_custom_offers', 'no' );
	update_option( 'wpjobster_enable_custom_extras', 'no' );
}
