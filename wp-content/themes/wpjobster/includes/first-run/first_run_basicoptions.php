<?php

global $wpdb;

// keep this for older installations compatibility
update_option( 'wpjobster_update_380', 'done' );

// insert pages
wpjobster_insert_pages( 'wpjobster_blog_home_id',
	'Blog Posts',
	'[wpjobster_theme_blog_posts]' );
wpjobster_insert_pages( 'wpjobster_post_new_page_id',
	'Post New Job',
	'[wpjobster_theme_post_new]' );
wpjobster_insert_pages( 'wpjobster_my_account_page_id',
	'My Account',
	'[wpjobster_theme_my_account]' );
wpjobster_insert_pages( 'wpjobster_my_requests_page_id',
	'My Requests',
	'[wpjobster_theme_my_requests]' );
wpjobster_insert_pages( 'wpjobster_my_favorites_page_id',
	'My Favorites',
	'[wpjobster_theme_my_favorites]' );
wpjobster_insert_pages( 'wpjobster_all_categories_page_id',
	'All Categories',
	'[wpjobster_theme_all_categories]' );
wpjobster_insert_pages( 'wpjobster_my_account_personal_info_page_id',
	'Personal Information',
	'[wpjobster_theme_my_account_personal_info]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_payments_page_id',
	'Payments',
	'[wpjobster_theme_my_account_payments]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_shopping_page_id',
	'Shopping',
	'[wpjobster_theme_my_account_shopping]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_all_notifications_page_id',
	'All Notifications',
	'[wpjobster_theme_my_account_all_notifications]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_sales_page_id',
	'Sales',
	'[wpjobster_theme_my_account_sales]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_priv_mess_page_id',
	'Private Messages',
	'[wpjobster_theme_my_account_priv_mess]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_my_account_reviews_page_id',
	'Reviews/Feedback',
	'[wpjobster_theme_my_account_reviews]',
	get_option( 'wpjobster_my_account_page_id' ) );
wpjobster_insert_pages( 'wpjobster_new_request_page_id',
	'New Request',
	'[vc_row css=".vc_custom_1481065781073{margin-top: 40px !important;}"][vc_column][vc_column_text][add_edit_request][/vc_column_text][/vc_column][/vc_row]',
	0,
	'page-vc-default.php'
);
wpjobster_insert_pages( 'wpjobster_search_user_page_id',
	'Search Users',
	'',
	0,
	'page-search-by-user.php'
);
wpjobster_insert_pages( 'wpjobster_email_settings_page_id',
	'Email settings',
	'',
	get_option( 'wpjobster_my_account_page_id' ),
	'page-email-settings.php'
);
wpjobster_insert_pages( 'wpjobster_advanced_search_request_page_id',
	'Search Requests',
	'[wpjobster_theme_search_requests]' );

wpjobster_insert_pages( 'wpjobster_user_profile_page_id',
	'User Profile',
	'[vc_row full_width="stretch_row_content_no_spaces"][vc_column][vc_column_text][user_header][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_column_text][user_portfolio_slider][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_column_text][user_profile_jobs][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_column_text][user_profile_reviews][/vc_column_text][/vc_column][/vc_row]' );

if ( get_option( 'wpjobster_first_run_options1' ) != 'done' ) {
	update_option( 'wpjobster_first_run_options1', 'done' );
	update_option( 'wpjobster_default_level_nr',   '0' );
	update_option( 'wpjobster_level1_extras',      '3' );
	update_option( 'wpjobster_level2_extras',      '3' );
	update_option( 'wpjobster_level3_extras',      '3' );

	update_option( 'wpjobster_level0_vds',         '1' );
	update_option( 'wpjobster_level1_vds',         '1' );
	update_option( 'wpjobster_level2_vds',         '1' );
	update_option( 'wpjobster_level3_vds',         '1' );

	update_option( 'wpjobster_level1_min',         '50' );
	update_option( 'wpjobster_level2_min',         '200' );
	update_option( 'wpjobster_level0_max',         '5000' );
	update_option( 'wpjobster_level0_max_extra',   '5000' );
	update_option( 'wpjobster_level1_max',         '5000' );
	update_option( 'wpjobster_level1_max_extra',   '5000' );
	update_option( 'wpjobster_level2_max',         '5000' );
	update_option( 'wpjobster_level2_max_extra',   '5000' );
	update_option( 'wpjobster_level3_max',         '5000' );
	update_option( 'wpjobster_level3_max_extra',   '5000' );
	update_option( 'wpjobster_min_job_amount',     '0' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_first_run_options2' ) != 'done' ) {
	update_option( 'wpjobster_first_run_options2',              'done' );
	update_option( 'wpjobster_enable_instant_deli',             'no' );
	update_option( 'wpjobster_show_main_menu',                  'yes' );
	update_option( 'wpjobster_admin_approve_job',               'no' );
	update_option( 'wpjobster_enable_extra',                    'yes' );
	update_option( 'wpjobster_job_listing',                     '0' );
	update_option( 'wpjobster_featured_job_listing',            '1' );
	update_option( 'wpjobster_enable_shipping',                 'yes' );
	update_option( 'wpjobster_enable_location_based_shipping',  'no' );
	update_option( 'wpjobster_job_attachments_enabled',         'no' );

	update_option( 'wpjobster_request_max_deliv',  'yes' );
	update_option( 'wpjobster_request_budget',  'no' );
	update_option( 'wpjobster_request_file_upload',  'no' );

	update_option( 'wpjobster_currency',                        'USD' );
	update_option( 'wpjobster_currency_1',                      'USD' );

	update_option( 'wpjobster_currency_symbol_1',               'USD' );
	update_option( 'wpjobster_currency_symbol',                 '$' );
	update_option( 'wpjobster_currency_position',               'back' );
	update_option( 'wpjobster_currency_symbol_space',           'yes' );
	update_option( 'wpjobster_decimal_sum_separator',           '.' );
	update_option( 'wpjobster_thousands_sum_separator',         ',' );

	update_option( 'wpjobster_show_limit_job_cnt',              '12' );
	update_option( 'wpjobster_nrpostsPage_home_page',           '12' );

	update_option( 'wpjobster_withdraw_limit',                  '10' );

	update_option( 'wpjobster_enable_free_input_box',           'yes' );
	update_option( 'wpjobster_enable_dropdown_values',          'no' );
	update_option( 'wpjobster_home_page_layout',                '1' );

	update_option( 'wpjobster_main_how_it_works_line1',         'Welcome to Jobster Theme' );
	update_option( 'wpjobster_main_how_it_works_line2',         'World\'s best microjob marketplace<br/>theme for WordPress CMS' );
	update_option( 'wpjobster_enable_how_it_works',             'yes' );

	update_option( 'wpjobster_allow_html_emails',               'yes' );
	update_option( 'wpjobster_paypal_enable_sdbx',              'no' );
	update_option( 'wpjobster_paypal_enable_secure',            'no' );
	update_option( 'wpjobster_enable_paypal_ad',                'no' );
	update_option( 'wpjobster_paypal_ad_model',                 'parallel' );

	update_option( 'wpjobster_enable_site_fee',                 'flexible' );

	update_option( 'wpjobster_percent_fee_taken_range1_base',   '20' );
	update_option( 'wpjobster_percent_fee_taken_range2_base',   '100' );
	update_option( 'wpjobster_percent_fee_taken_range3_base',   '500' );

	update_option( 'wpjobster_percent_fee_taken_range0_level0', '20' );
	update_option( 'wpjobster_percent_fee_taken_range0_level1', '20' );
	update_option( 'wpjobster_percent_fee_taken_range0_level2', '20' );
	update_option( 'wpjobster_percent_fee_taken_range0_level3', '20' );

	update_option( 'wpjobster_percent_fee_taken_range1_level0', '19' );
	update_option( 'wpjobster_percent_fee_taken_range1_level1', '17' );
	update_option( 'wpjobster_percent_fee_taken_range1_level2', '15' );
	update_option( 'wpjobster_percent_fee_taken_range1_level3', '13' );

	update_option( 'wpjobster_percent_fee_taken_range2_level0', '18' );
	update_option( 'wpjobster_percent_fee_taken_range2_level1', '16' );
	update_option( 'wpjobster_percent_fee_taken_range2_level2', '14' );
	update_option( 'wpjobster_percent_fee_taken_range2_level3', '12' );

	update_option( 'wpjobster_percent_fee_taken_range3_level0', '11' );
	update_option( 'wpjobster_percent_fee_taken_range3_level1', '10' );
	update_option( 'wpjobster_percent_fee_taken_range3_level2', '9' );
	update_option( 'wpjobster_percent_fee_taken_range3_level3', '8' );

	update_option( 'wpjobster_offer_price_min',                 '5' );
	update_option( 'wpjobster_offer_price_max',                 '5000' );

	update_option( 'wpjobster_default_nr_of_pics',              '10' );

	update_option( 'wpjobster_max_time_to_wait',                '72' );
	update_option( 'wpjobster_clearing_period',                 '3' );

	update_option( 'wpjobster_language_1',                      'en' );
	update_option( 'wpjobster_show_pagination_homepage',        'no' );
	update_option( 'wpjobster_taxonomy_page_with_sdbr',         'yes' );
	update_option( 'wpjobster_enable_second_footer',            'yes' );
	update_option( 'wpjobster_en_country_flags',                'no' );
	update_option( 'wpjobster_ip_country_detection',            'no' );
	update_option( 'wpjobster_number_of_cancellations',         '5' );
	update_option( 'wpjobster_number_of_modifications',         '5' );
	update_option( 'wpjobster_get_total_extras',                '3' );
	update_option( 'wpjobster_enable_widget_embed_code',        'no' );
}


if ( get_option( 'wpjobster_first_run_options_224' ) != 'done' ) {
	update_option( 'wpjobster_enable_auto-load',      'no' );
	update_option( 'wpjobster_enable_jobs_title',     'yes' );
	update_option( 'wpjobster_first_run_options_224', 'done' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_upda_req101' ) != 'done' ) {

	$another_special_sql = "select * from ".$wpdb->prefix."job_ratings where awarded='1'";
	$another_special_res = $wpdb->get_results($another_special_sql);

	foreach ( $another_special_res as $row2 ) {
		$id     = $row2->id;
		$grade  = $row2->grade;
		$oid    = $row2->orderid;

		$s1s1   = "select * from " . $wpdb->prefix . "job_orders where id='$oid' ";
		$r1r1   = $wpdb->get_results( $s1s1 );

		$pid    = $r1r1[0]->pid;
		$pstpst = get_post( $pid );
		$uid    = $pstpst->post_author;

		if ( $grade == 0 ) {
			$sss = "update ".$wpdb->prefix."job_ratings set grade='1', uid='$uid', pid='$pid' where id='$id' ";
			$wpdb->query( $sss );
		}

		if ( $grade == 1) {
			$sss = "update " . $wpdb->prefix . "job_ratings set grade='5', uid='$uid', pid='$pid' where id='$id' ";
			$wpdb->query( $sss );
		}
	}
	update_option( "wpjobster_upda_req101", "done" );

}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_up_upd1_amk81j' ) != 'DONe' ) {
	update_option( 'wpjobster_up_upd1_amk81j', "DONe" );
	wpjobster_insert_pages( 'wpjobster_all_categories_page_id',
		'All Categories',
		'[wpjobster_theme_all_categories]' );
	wpjobster_insert_pages( 'wpjobster_advanced_search_id',
		'Search Jobs',
		'[wpjobster_theme_search_jobs]' );
}


if ( get_option( 'wpjobster_up_upd1_640hj' ) != 'DONe' ) {
	query_posts( "post_type=job&order=DESC&orderby=id&posts_per_page=10000" );

	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$ttl = get_the_title();
			$newttl = get_post_meta(get_the_ID(), 'title_variable', true);
			$npost = array();
			$npost['ID'] = get_the_ID();
			$npost['post_title'] = $newttl;
			wp_update_post( $npost );

		}
	}

	wp_reset_query();
	update_option( 'wpjobster_up_upd1_640hj', "DONe" );
}


if ( get_option( 'wpjobster_up_upd1_featured' ) != 'DONe' ) {
	query_posts( "post_type=job&order=DESC&orderby=id&posts_per_page=-1" );
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			update_post_meta( get_the_ID(), 'home_featured_until',        "z" );
			update_post_meta( get_the_ID(), 'category_featured_until',    "z" );
			update_post_meta( get_the_ID(), 'subcategory_featured_until', "z" );
			update_post_meta( get_the_ID(), 'home_featured_now',          "z" );
			update_post_meta( get_the_ID(), 'category_featured_now',      "z" );
			update_post_meta( get_the_ID(), 'subcategory_featured_now',   "z" );
		}
	}
	wp_reset_query();
	update_option( 'wpjobster_up_upd1_featured', "DONe" );
}


if ( get_option( 'wpjobster_update_236' ) != 'done' ) {
	update_option( 'wpjobster_enable_responsive', 'no' );
	update_option( 'wpjobster_update_236', 'done' );
}


if ( get_option( 'wpjobster_update_243' ) != 'done' ) {

	wpjobster_insert_homepage('main_page_url', 'Homepage', '' );
	update_option( 'wpjobster_update_243', 'done' );

}


if ( get_option( 'wpjobster_update_254' ) != 'done' ) {
	update_option( 'wpjobster_audio',                   'no' );
	update_option( 'wpjobster_max_uploads_audio',       '1' );
	update_option( 'wpjobster_mandatory_audio_for_jbs', 'no' );
	update_option( 'wpjobster_max_audio_upload_size',   '10' );
	update_option( 'wpjobster_update_254',              'done' );
}

if ( get_option( 'wpjobster_update_259' ) != 'done' ) {
	update_option( 'wpjobster_max_img_upload_size', 10);
	update_option( 'wpjobster_update_259', 'done' );
}

if ( get_option( 'wpjobster_update_260' ) != 'done' ) {
	update_option( 'wpjobster_min_img_upload_width',        720 );
	update_option( 'wpjobster_min_img_upload_height',       405 );
	update_option( 'wpjobster_enable_job_cover',            'no' );
	update_option( 'wpjobster_min_cover_img_upload_width',  980 );
	update_option( 'wpjobster_min_cover_img_upload_height', 180 );
	update_option( 'wpjobster_update_260',                  'done' );

}

// qTranslate-X default options
if ( get_option( 'wpjobster_update_qtranslate' ) != 'done' ) {
	update_option( 'qtranslate_post_type_excluded', array(
		'acf-field-group', 'acf-field', 'job', 'offer', 'request' ) );
	update_option( 'qtranslate_qtrans_compatibility', '1' );
	update_option( 'wpjobster_update_qtranslate', 'done' );
}


if ( get_option( 'wpjobster_update_262_extras' ) != 'done' ) {
	update_option( "wpjobster_get_level0_extras", 3 );
	update_option( "wpjobster_get_level1_extras", 3 );
	update_option( "wpjobster_get_level2_extras", 3 );
	update_option( "wpjobster_get_level3_extras", 3 );
	update_option( 'wpjobster_update_262_extras', 'done' );
}

if ( get_option( 'wpjobster_update_ul_extra_fast_delivery' ) != 'done' ) {
	update_option( "wpjobster_enable_extra_fast_delivery", 'yes' );
	update_option( "wpjobster_extra_fast_devliery_level0", 'yes' );
	update_option( "wpjobster_extra_fast_devliery_level1", 'yes' );
	update_option( "wpjobster_extra_fast_devliery_level2", 'yes' );
	update_option( "wpjobster_extra_fast_devliery_level3", 'yes' );
	update_option( 'wpjobster_update_ul_extra_fast_delivery', 'done' );
}

if ( get_option( 'wpjobster_update_ul_extra_fast_delivery_multiples' ) != 'done' ) {

	update_option( 'wpjobster_get_level0_fast_delivery_multiples', 3 );
	update_option( 'wpjobster_get_level1_fast_delivery_multiples', 5 );
	update_option( 'wpjobster_get_level2_fast_delivery_multiples', 10 );
	update_option( 'wpjobster_get_level3_fast_delivery_multiples', 20 );

	update_option( 'wpjobster_subscription_fast_del_multiples_level0', 5 );
	update_option( 'wpjobster_subscription_fast_del_multiples_level1', 10 );
	update_option( 'wpjobster_subscription_fast_del_multiples_level2', 20 );
	update_option( 'wpjobster_subscription_fast_del_multiples_level3', 30 );

	update_option( 'wpjobster_update_ul_extra_fast_delivery_multiples', 'done' );
}

if ( get_option( 'wpjobster_update_sub_additional_revision' ) != 'done' ) {
	update_option( "wpjobster_enable_extra_additional_revision", 'yes' );
	update_option( "wpjobster_extra_additional_revision_level0", 'yes' );
	update_option( "wpjobster_extra_additional_revision_level1", 'yes' );
	update_option( "wpjobster_extra_additional_revision_level2", 'yes' );
	update_option( "wpjobster_extra_additional_revision_level3", 'yes' );
	update_option( 'wpjobster_update_sub_additional_revision', 'done' );
}

if ( get_option( 'wpjobster_update_sub_additional_revision_multiples' ) != 'done' ) {

	update_option( 'wpjobster_get_level0_add_rev_multiples', 3 );
	update_option( 'wpjobster_get_level1_add_rev_multiples', 5 );
	update_option( 'wpjobster_get_level2_add_rev_multiples', 10 );
	update_option( 'wpjobster_get_level3_add_rev_multiples', 20 );

	update_option( 'wpjobster_subscription_add_rev_multiples_level0', 5 );
	update_option( 'wpjobster_subscription_add_rev_multiples_level1', 10 );
	update_option( 'wpjobster_subscription_add_rev_multiples_level2', 20 );
	update_option( 'wpjobster_subscription_add_rev_multiples_level3', 30 );

	update_option( 'wpjobster_update_sub_additional_revision_multiples', 'done' );
}

if ( get_option( 'wpjobster_update_263_layout' ) != 'done' ) {
	update_option( 'wpjobster_update_263_layout', 'done' );
}


if ( get_option( 'wpjobster_update_263_character_limits' ) != 'done' ) {
	update_option( 'wpjobster_characters_jobtitle_min',         5 );
	update_option( 'wpjobster_characters_jobtitle_max',         80 );
	update_option( 'wpjobster_characters_description_min',      35 );
	update_option( 'wpjobster_characters_description_max',      1000 );
	update_option( 'wpjobster_characters_instructions_min',     35 );
	update_option( 'wpjobster_characters_instructions_max',     500 );
	update_option( 'wpjobster_characters_extradescription_min', 5 );
	update_option( 'wpjobster_characters_extradescription_max', 50 );
	update_option( 'wpjobster_characters_request_min',          35 );
	update_option( 'wpjobster_characters_request_max',          500 );
	update_option( 'wpjobster_characters_message_min',          0 );
	update_option( 'wpjobster_characters_message_max',          1200 );
	update_option( 'wpjobster_characters_personalinfo_min',     35 );
	update_option( 'wpjobster_characters_personalinfo_max',     500 );

	update_option( 'wpjobster_update_263_character_limits',     'done' );
}


if ( get_option( 'wpjobster_update_269' ) != 'done' ) {
	update_option( 'wpjobster_update_269', 'done' );
}


if ( get_option( 'wpjobster_update_270_paypal' ) != 'done' ) {
	update_option( 'wpjobster_theme_signature', '' );
	update_option( 'wpjobster_theme_apipass', '' );
	update_option( 'wpjobster_theme_apiuser', '' );
	update_option( 'wpjobster_update_270_paypal', 'done' );
}


if ( get_option( 'wpjobster_paypal_page_created' ) != 'DONe' ) {
	update_option( 'wpjobster_paypal_page_created', 'DONe' );
}


if ( get_option( 'wpjobster_update_272_earnings' ) != 'done' ) {
	$user_query = get_users();
	foreach ( $user_query as $user ) {
		update_total_earnings( $user->ID );
		update_total_spendings( $user->ID );
	}
	update_option( 'wpjobster_update_272_earnings', 'done' );
}


if ( get_option( 'wpjobster_update_282_homepagenews' ) != 'done' ) {
	update_post_meta( get_option( "main_page_url" ), "show_news", array(1) );
	update_post_meta( get_option( "main_page_url" ), "show_news_logged_in", array(1) );
	update_option( 'wpjobster_update_282_homepagenews', 'done' );
}


if ( get_option( 'wpjobster_update_283_insert_pages' ) != 'done' ) {
	wpjobster_insert_pages('wpjobster_subscriptions_page_id',
		'Subscriptions',
		'[wpjobstersubscriptions]' );
	update_option( 'wpjobster_update_283_insert_pages', 'done' );
}


if ( get_option( 'wpjobster_update_283_topup' ) != 'done' ) {
	update_option( 'wpjobster_enable_topup', 'no' );
	update_option( 'wpjobster_update_283_topup', 'done' );
}


if ( get_option( "wpjobster_update_300_tos" ) != 'done' ) {
	update_option( "wpjobster_update_300_tos", "done" );

	if ( get_option( "wpjobster_tos_page_link" ) != '' ) {
		update_option( "wpjobster_tos_type", 'link' );
	} else {
		update_option( "wpjobster_tos_type", 'disabled' );
	}
}


if ( get_option( 'wpjobster_update_300_locations') != 'done' ) {
	update_option( 'wpjobster_update_300_locations', 'done' );

	if ( get_option( 'wpjobster_location') == 'yes') {
		update_option( 'wpjobster_location_display_condition', 'always' );
	} else {
		update_option( 'wpjobster_location_display_condition', 'never' );
	}

	update_option( 'wpjobster_distance_display_condition', 'never' );
	update_option( 'wpjobster_location_display_map', 'no' );
}


if ( ! get_option( 'wpjobster_safe_date_format' ) ) {
	update_option( 'wpjobster_safe_date_format', 'Y-m-d' );
}


if ( get_option( 'wpjobster_update_default_charts_settings' ) != 'done' ) {
	update_option( 'wpjobster_update_default_charts_settings', 'done' );

	update_option( 'wpjobster_enable_phone_number', 'no' );
	update_option( 'wpjobster_enable_user_stats', 'yes' );
	update_option( 'wpjobster_enable_user_charts', 'yes' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( "DGCPTT_deactivated" ) != 'yes' ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( "gd-taxonomies-tools/gd-taxonomies-tools.php" );
	update_option( "DGCPTT_deactivated", "yes" );
}

// ---------- ---------- ---------- ---------- ---------- ----------

// this update is only for new installations
if ( get_option( 'wpjobster_update_380_default_options' ) != 'done'
	&& get_option( 'wpjobster_sql_10extras' ) != 'done' ) {

	// WP Settings
	update_option( 'users_can_register', 1 );
	update_option( 'posts_per_page', 12 );
	update_option( 'posts_per_rss', 12 );
	update_option( 'permalink_structure', '/%postname%/' );

	// WPJobster Settings // only if wpjobster_sql_10extras not done
	update_option( 'wpjobster_email_addr_from', get_option( 'admin_email', '' ) );
	update_option( 'wpjobster_email_name_from', get_option( 'blogname', '' ) );
	update_option( 'wpjobster_featured_enable', 'no' );
	update_option( 'wpjobster_featured_interval', 7 );
	update_option( 'wpjobster_featured_homepage', 5 );
	update_option( 'wpjobster_featured_price_homepage', 5 );
	update_option( 'wpjobster_featured_category', 5 );
	update_option( 'wpjobster_featured_price_category', 5 );
	update_option( 'wpjobster_featured_subcategory', 5 );
	update_option( 'wpjobster_featured_price_subcategory', 5 );
	update_option( 'wpjobster_enable_job_cover', 'yes' );
	update_option( 'wpjobster_allow_wysiwyg_job_description', 'yes' );
	update_option( 'wpjobster_enable_responsive', 'yes' );
	update_option( 'wpjobster_enable_lazy_loading', 'yes' );
	update_option( 'wpjobster_video_thumbnails', 'yes' );
	update_option( 'wpjobster_blacklisted_words_pm', 'email' . PHP_EOL . 'telephone' . PHP_EOL . 'skype' );
	update_option( 'wpjobster_blacklisted_words_pm_err', 'Providing your contact details in messages is against our terms of service!' );
	update_option( 'wpjobster_blacklisted_words2_pm', 'paypal' . PHP_EOL . 'stripe' );
	update_option( 'wpjobster_blacklisted_words2_pm_err', 'You cannot offer to pay directly to users.' );
	update_option( 'wpjobster_blacklisted_email', 'Providing your contact details in messages is against our terms of service!' );
	update_option( 'wpjobster_blacklisted_phone', 'Providing your contact details in messages is against our terms of service!' );

	// zM Ajax Login & Register
	update_option( 'ajax_login_register_advanced_usage_login', '.login-link' );
	update_option( 'ajax_login_register_advanced_usage_register', '.register-link' );
	update_option( 'ajax_login_register_advanced_usage_forgot', '.forgot-password-handle' );

	// Remove Dashboard Access
	update_option( 'rda_enable_profile', '' );

	// Easy Social Share Buttons 3
	$essb_defaults = get_option( 'easy-social-share-buttons3', array() );
	$essb_args = wp_parse_args( array(
		'style' => 6,
		'button_style' => 'icon',
		'hide_social_name' => 1,
		'target_link' => 1,
		'display_where' => 'nowhere',
		'buttons_pos' => 'right',
		'force_hide_buttons_on_mobile' => 'true',
		'display_in_types' => array(
			'post',
			'news',
		),
		'networks' => array(
			'facebook',
			'twitter',
			'google',
			'pinterest',
			'linkedin',
			'whatsapp',
		),
	), $essb_defaults );
	update_option( 'easy-social-share-buttons3', $essb_args );

	// WP Social Login
	update_option( 'wsl_settings_Facebook_enabled', 1 );
	update_option( 'wsl_settings_Google_enabled',   1 );
	update_option( 'wsl_settings_Twitter_enabled',  1 );
	update_option( 'wsl_settings_social_icon_set',  'none' );
	update_option( 'wsl_settings_users_avatars',    0 );
	update_option( 'wsl_settings_use_popup',        1 );
	update_option( 'wsl_settings_widget_display',   4 );
	update_option( 'wsl_settings_bouncer_profile_completion_require_email', 1 );
	update_option( 'wsl_settings_bouncer_profile_completion_change_username', 1 );

	update_option( 'wpjobster_update_380_default_options', 'done' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_update_381_fix_options' ) != 'done' ) {
	// fix the bug with empty min job amount
	if ( get_option( 'wpjobster_min_job_amount' ) == '' ) {
		update_option( 'wpjobster_min_job_amount', '0' );
	}
	update_option( 'wpjobster_update_381_fix_options', 'done' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( "wpjobster_update_383_multiples") != 'done' ) {
	update_option( "wpjobster_update_383_multiples","done" );

	update_option( 'wpjobster_enable_multiples',          'yes' );
	update_option( 'wpjobster_get_level0_jobmultiples',   3 );
	update_option( 'wpjobster_get_level1_jobmultiples',   5);
	update_option( 'wpjobster_get_level2_jobmultiples',   10);
	update_option( 'wpjobster_get_level3_jobmultiples',   20);
	update_option( 'wpjobster_get_level0_extramultiples', 3);
	update_option( 'wpjobster_get_level1_extramultiples', 5);
	update_option( 'wpjobster_get_level2_extramultiples', 10);
	update_option( 'wpjobster_get_level3_extramultiples', 20);

	update_option( 'wpjobster_subscription_job_multiples_enabled',   'yes' );
	update_option( 'wpjobster_subscription_extra_multiples_enabled', 'yes' );
	update_option( 'wpjobster_subscription_job_multiples_level0',    5);
	update_option( 'wpjobster_subscription_job_multiples_level1',    10);
	update_option( 'wpjobster_subscription_job_multiples_level2',    20);
	update_option( 'wpjobster_subscription_job_multiples_level3',    30);
	update_option( 'wpjobster_subscription_extra_multiples_level0',  5);
	update_option( 'wpjobster_subscription_extra_multiples_level1',  10);
	update_option( 'wpjobster_subscription_extra_multiples_level2',  20);
	update_option( 'wpjobster_subscription_extra_multiples_level3',  30);
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_update_383_defaults' ) != 'done' ) {
	update_option( 'wpjobster_enable_live_notifications', 'yes' );
	update_option( 'wpjobster_update_383_defaults', 'done' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

if ( get_option( 'wpjobster_update_390_defaults' ) != 'done' ) {
	$default_message = 'Edit this message from Admin > Options';

	if ( ! get_option( 'options_job_title_instructions' )
		|| get_option( 'options_job_title_instructions' ) == $default_message ) {
		update_option( 'options_job_title_instructions', 'This is your job title. Choose it wisely, it is the first piece of information buyers will see.' );
	}
	if ( ! get_option( 'options_job_price_instructions' )
		|| get_option( 'options_job_price_instructions' ) == $default_message ) {
		update_option( 'options_job_price_instructions', 'Choose a price for the basic job. You can add extra services below.' );
	}
	if ( ! get_option( 'options_job_category_instructions' )
		|| get_option( 'options_job_category_instructions' ) == $default_message ) {
		update_option( 'options_job_category_instructions', 'Please select the category and subcategory most suitable for your job.' );
	}
	if ( ! get_option( 'options_job_description_instructions' )
		|| get_option( 'options_job_description_instructions' ) == $default_message ) {
		update_option( 'options_job_description_instructions', 'The description should be as detailed as possible so buyers will be able to understand if this meets their needs.' );
	}
	if ( ! get_option( 'options_job_instructions_instructions' )
		|| get_option( 'options_job_instructions_instructions' ) == $default_message ) {
		update_option( 'options_job_instructions_instructions', 'Here you can tell your buyer what information you need in order to get started. This message will be displayed on the transaction page right after the job purchase.' );
	}
	if ( ! get_option( 'options_job_tags_instructions' )
		|| get_option( 'options_job_tags_instructions' ) == $default_message ) {
		update_option( 'options_job_tags_instructions', 'The tags are some keywords related to your job. Buyers will be able to find your job easier if you choose them properly.' );
	}
	if ( ! get_option( 'options_job_lets_meet_instructions' )
		|| get_option( 'options_job_lets_meet_instructions' ) == $default_message ) {
		update_option( 'options_job_lets_meet_instructions', 'Check this if you need to meet the buyer in order to provide your service.' );
	}
	if ( ! get_option( 'options_job_location_instructions' )
		|| get_option( 'options_job_location_instructions' ) == $default_message ) {
		update_option( 'options_job_location_instructions', 'Here you can fill the location where you provide this service.' );
	}
	if ( ! get_option( 'options_job_distance_instructions' )
		|| get_option( 'options_job_distance_instructions' ) == $default_message ) {
		update_option( 'options_job_distance_instructions', 'Here you can fill the distance that you can travel in order to provide this service.' );
	}
	if ( ! get_option( 'options_job_display_map_instructions' )
		|| get_option( 'options_job_display_map_instructions' ) == $default_message ) {
		update_option( 'options_job_display_map_instructions', 'Check this if you want to display a map with the location on your job page.' );
	}
	if ( ! get_option( 'options_job_delivery_time_instructions' )
		|| get_option( 'options_job_delivery_time_instructions' ) == $default_message ) {
		update_option( 'options_job_delivery_time_instructions', 'Delivery time is your deadline for delivering an order. Be careful when you choose this, because late deliveries can result in cancellations or affect your reputation.' );
	}
	if ( ! get_option( 'options_job_shipping_instructions' )
		|| get_option( 'options_job_shipping_instructions' ) == $default_message ) {
		update_option( 'options_job_shipping_instructions', 'Does this require physical shipping? If you want the buyer to pay for it, please add the shipping cost here.' );
	}
	if ( ! get_option( 'options_job_cover_image_instructions' )
		|| get_option( 'options_job_cover_image_instructions' ) == $default_message ) {
		update_option( 'options_job_cover_image_instructions', 'Sometimes a photo can say more than 1000 words. Please select the best cover image related to your job.' );
	}
	if ( ! get_option( 'options_job_images_instructions' )
		|| get_option( 'options_job_images_instructions' ) == $default_message ) {
		update_option( 'options_job_images_instructions', 'Sometimes a photo can say more than 1000 words. Please select the best photos related to your job.' );
	}
	if ( ! get_option( 'options_job_video_instructions' )
		|| get_option( 'options_job_video_instructions' ) == $default_message ) {
		update_option( 'options_job_video_instructions', 'If you want to describe your job even better, you can add the link to an YouTube video here.' );
	}
	if ( ! get_option( 'options_job_audio_instructions' )
		|| get_option( 'options_job_audio_instructions' ) == $default_message ) {
		update_option( 'options_job_audio_instructions', 'Here you can provide audio samples if your job is related to this.' );
	}
	if ( ! get_option( 'options_job_extra_price_instructions' )
		|| get_option( 'options_job_extra_price_instructions' ) == $default_message ) {
		update_option( 'options_job_extra_price_instructions', 'Choose a price for your additional service.' );
	}
	if ( ! get_option( 'options_job_extra_description_instructions' )
		|| get_option( 'options_job_extra_description_instructions' ) == $default_message ) {
		update_option( 'options_job_extra_description_instructions', 'Describe your additional service in one short line.' );
	}
	if ( ! get_option( 'options_job_extra_multiples_instructions' )
		|| get_option( 'options_job_extra_multiples_instructions' ) == $default_message ) {
		update_option( 'options_job_extra_multiples_instructions', 'Check this box in order to enable multiple quantity purchase for this additional service.' );
	}

	update_option( 'wpjobster_update_390_defaults', 'done' );
}

// ---------- ---------- ---------- ---------- ---------- ----------

// check if the database was updated with subscription default values
$wpjobster_subscription_job_multiples_level0 = get_option( 'wpjobster_subscription_job_multiples_level0' );
if ( empty( $wpjobster_subscription_job_multiples_level0 ) ) {
	update_option( 'wpjobster_subscription_job_multiples_level0', '1' );
}
$wpjobster_subscription_job_multiples_level1 = get_option( 'wpjobster_subscription_job_multiples_level1' );
if ( empty( $wpjobster_subscription_job_multiples_level1 ) ) {
	update_option( 'wpjobster_subscription_job_multiples_level1', '1' );
}
$wpjobster_subscription_job_multiples_level2 = get_option( 'wpjobster_subscription_job_multiples_level2' );
if ( empty( $wpjobster_subscription_job_multiples_level2 ) ) {
	update_option( 'wpjobster_subscription_job_multiples_level2', '1' );
}
$wpjobster_subscription_job_multiples_level3 = get_option( 'wpjobster_subscription_job_multiples_level3' );
if ( empty( $wpjobster_subscription_job_multiples_level3 ) ) {
	update_option( 'wpjobster_subscription_job_multiples_level3', '1' );
}
$wpjobster_subscription_extra_multiples_level0 = get_option( 'wpjobster_subscription_extra_multiples_level0' );
if ( empty( $wpjobster_subscription_extra_multiples_level0 ) ) {
	update_option( 'wpjobster_subscription_extra_multiples_level0', '1' );
}
$wpjobster_subscription_extra_multiples_level1 = get_option( 'wpjobster_subscription_extra_multiples_level1' );
if ( empty( $wpjobster_subscription_extra_multiples_level1 ) ) {
	update_option( 'wpjobster_subscription_extra_multiples_level1', '1' );
}
$wpjobster_subscription_extra_multiples_level2 = get_option( 'wpjobster_subscription_extra_multiples_level2' );
if ( empty( $wpjobster_subscription_extra_multiples_level2 ) ) {
	update_option( 'wpjobster_subscription_extra_multiples_level2', '1' );
}
$wpjobster_subscription_extra_multiples_level3 = get_option( 'wpjobster_subscription_extra_multiples_level3' );
if ( empty( $wpjobster_subscription_extra_multiples_level3 ) ) {
	update_option( 'wpjobster_subscription_extra_multiples_level3', '1' );
}

if ( get_option( 'wpjobster_update_401_defaults' ) != 'done' ) {
	update_option( 'wpjobster_update_401_defaults', 'done' );
	update_option( 'wpjobster_active_job_cutom_offer', 'yes' );
}

if ( get_option( 'wpjobster_update_402_defaults' ) != 'done' ) {
	update_option( 'wpjobster_update_402_defaults', 'done' );

	if ( get_option( 'wpjobster_mandatory_pics_for_jbs' ) == '' ) {
		update_option( 'wpjobster_mandatory_pics_for_jbs', 'yes' );
	}
}

if ( get_option( 'wpjobster_update_403_customextras' ) != 'done' ) {
	update_option( 'wpjobster_update_403_customextras', 'done' );

	update_option( 'wpjobster_enable_custom_extras', 'yes' );
	update_option( 'wpjobster_get_level0_customextrasamount', 500 );
	update_option( 'wpjobster_get_level1_customextrasamount', 1000 );
	update_option( 'wpjobster_get_level2_customextrasamount', 2000 );
	update_option( 'wpjobster_get_level3_customextrasamount', 3000 );

	update_option( 'wpjobster_subscription_custom_extras_enabled', 'yes' );
	update_option( 'wpjobster_subscription_max_customextrasamount_level0', 500 );
	update_option( 'wpjobster_subscription_max_customextrasamount_level1', 1000 );
	update_option( 'wpjobster_subscription_max_customextrasamount_level2', 2000 );
	update_option( 'wpjobster_subscription_max_customextrasamount_level3', 3000 );
}

if ( get_option( 'wpjobster_update_403_defaults' ) != 'done' ) {
	update_option( 'wpjobster_update_403_defaults', 'done' );

	$wpjobster_enable_site_fee = get_option( 'wpjobster_enable_site_fee' );
	$wpjobster_percent_fee_taken = get_option( 'wpjobster_percent_fee_taken' );
	$wpjobster_solid_fee_taken = get_option( 'wpjobster_solid_fee_taken' );

	if ( $wpjobster_enable_site_fee == '' ) {
		if ( is_numeric( $wpjobster_percent_fee_taken ) ) {
			update_option( 'wpjobster_enable_site_fee', 'percent' );
		} elseif ( is_numeric( $wpjobster_solid_fee_taken ) ) {
			update_option( 'wpjobster_enable_site_fee', 'fixed' );
		} else {
			update_option( 'wpjobster_enable_site_fee', 'flexible' );
		}
	}

	update_option( 'wpjobster_enable_custom_offers', 'yes' );
}

if ( get_option( 'wpjobster_update_404_default_instructions' ) != 'done' ) {
	$default_message = 'Edit this message from Admin > Options';

	if ( ! get_option( 'job_extra_days_to_deliver_instructions' )
		|| get_option( 'job_extra_days_to_deliver_instructions' ) == $default_message ) {
		update_option( 'job_extra_days_to_deliver_instructions', 'Delivery time for an extra.' );
	}

	if ( ! get_option( 'job_extra_enable_instructions' )
		|| get_option( 'job_extra_enable_instructions' ) == $default_message ) {
		update_option( 'job_extra_enable_instructions', 'If this is enabled, the extra is visible for buyers.' );
	}

	update_option( 'wpjobster_update_404_default_instructions', 'done' );
}

if ( get_option( 'wpjobster_update_404_defaults' ) != 'done') {
	update_option( 'wpjobster_update_404_defaults', 'done' );

	update_option( 'wpjobster_subscription_level_2_enabled', 'yes' );
	update_option( 'wpjobster_subscription_level_3_enabled', 'yes' );

	$my_post = array(
		'ID'           => get_option( 'wpjobster_new_request_page_id' ),
		'post_content' => '[vc_row css=".vc_custom_1481065781073{margin-top: 40px !important;}"][vc_column][vc_column_text][add_edit_request][/vc_column_text][/vc_column][/vc_row]',
	);
	wp_update_post( $my_post );
}

if ( get_option( 'wpjobster_profile_slider_defaults' ) != 'done' ) {
	update_option( 'wpjobster_profile_slider_defaults', 'done' );

	update_option( 'wpjobster_enable_user_profile_portfolio', 'no' );
	update_option( 'wpjobster_profile_default_nr_of_pics', 10 );
	update_option( 'wpjobster_profile_max_img_upload_size', 10 );
	update_option( 'wpjobster_profile_min_img_upload_width', 720 );
	update_option( 'wpjobster_profile_min_img_upload_height', 405 );

	update_option( 'wpjobster_wysiwyg_for_profile', 'yes' );
	update_option( 'wpjobster_enable_jobs_section_on_user_profile', 'yes' );
}

if ( get_option( 'wpjobster_rating_new_values' ) != 'done' ) {
	update_option( 'wpjobster_rating_new_values', 'done' );

	$args = array(
		'post_type'             => 'job',
		'posts_per_page'        => -1
	);
	$all_posts = get_posts( $args );
	foreach ($all_posts as $key) {
		$pid = $key->ID;
		wpjobster_get_job_rating_new($pid);
	}
}

if ( get_option( 'wpjobster_update_410_search_defaults' ) != 'done' ) {
	update_option( 'wpjobster_update_410_search_defaults', 'done' );

	update_option( 'wpjobster_default_advanced_search', 'jobs' );
	update_option( 'wpjobster_enable_jobs_for_advanced_search', 'yes' );
	update_option( 'wpjobster_enable_requests_for_advanced_search', 'no' );
	update_option( 'wpjobster_enable_users_for_advanced_search', 'yes' );
}

if ( get_option( 'wpjobster_update_411_search_defaults' ) != 'done' ) {
	update_option( 'wpjobster_update_411_search_defaults', 'done' );

	update_option( 'wpjobster_enable_search_user_location', 'yes' );
}

if ( get_option( 'wpjobster_sales_reports_update' ) != 'done' ) {
	update_option( 'wpjobster_sales_reports_update', 'done' );

	$wpdb->query(
		"
		UPDATE {$wpdb->prefix}job_orders wjo
		SET site_fees = (
			SELECT amount FROM {$wpdb->prefix}job_payment_transactions wjpt
			WHERE rid=6 AND wjpt.oid = wjo.id
		);

		UPDATE {$wpdb->prefix}job_orders wjo
		SET shipping = (
			SELECT wpm.meta_value FROM {$wpdb->prefix}postmeta wpm
			WHERE wpm.post_id = wjo.pid AND wpm.meta_key='shipping'
		);
		"
	);
}

if ( get_option( 'wpjobster_update_412_max_delivery' ) != 'done' ) {
	update_option( 'wpjobster_update_412_max_delivery', 'done' );

	update_option( 'wpjobster_job_max_delivery_days', 30 );
	update_option( 'wpjobster_request_max_delivery_days', 30 );
}

if( get_option( 'wpjobster_rename_pm_associate_columns' ) != 'done' ){
	update_option( 'wpjobster_rename_pm_associate_columns', 'done' );

	$job_pm_pid_exists = $wpdb->get_results(
		"
		SELECT COLUMN_NAME
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE table_name = {$wpdb->prefix}job_pm
			AND column_name = pid
		"
	);

	if ( ! empty( $job_pm_pid_exists ) ) {
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}job_pm DROP associate_job_id" );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}job_pm CHANGE pid associate_job_id INT(11) NOT NULL DEFAULT '0';" );
	}

	$job_pm_associate_post_id_exists = $wpdb->get_results(
		"
		SELECT COLUMN_NAME
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE table_name = {$wpdb->prefix}job_pm
			AND column_name = associate_post_id
		"
	);

	if ( ! empty( $job_pm_associate_post_id_exists ) ) {
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}job_pm DROP associate_request_id" );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}job_pm CHANGE associate_post_id associate_request_id INT(11) NOT NULL DEFAULT '0';" );
	}
}

if( get_option( 'wpjobster_set_page_templates' ) != 'done' ){

	update_option( 'wpjobster_set_page_templates', 'done' );

	$all_page_templates = array(
		'blog-posts',
		'page-email-settings',
		'page-full-width',
		'page-homepage-public',
		'page-homepage-user',
		'page-how-it-works',
		'page-levels',
		'page-post-edit-job',
		'page-post-new-job',
		'page-search-by-user',
		'page-support',
		'page-vc-default',
		'template-meta-data-filter',
		'wpjobster-special-page-template'
	);

	foreach ($all_page_templates as $template) {
		$pages = get_posts(
			array(
				'post_type' => 'page',
				'fields' => 'ids',
				'nopaging' => true,
				'meta_key' => '_wp_page_template',
				'meta_value' => $template . '.php'
			)
		);

		foreach ($pages as $page) {
			update_post_meta( $page, '_wp_page_template', 'page-templates/' . $template . '.php' );
		}
	}
}

add_action( 'admin_init','wpjobster_change_public_homepage_title' );
function  wpjobster_change_public_homepage_title(){
	if( get_option( 'wpjobster_change_public_homepage_title' ) != 'done' ){

		update_option( 'wpjobster_change_public_homepage_title', 'done' );

		$public_homepage_query = new WP_Query( array( 'page_id' => get_option( 'main_page_url' ) ) );
		while ( $public_homepage_query->have_posts() ) : $public_homepage_query->the_post();
			$the_content = stripslashes( get_the_content() );
			$last_replace = str_replace( 'margin-bottom: -65px !important;', 'margin-bottom: 1.33em;', $the_content );
			$last_replace = str_replace( '[requestbox]', '', $last_replace );
			$content = $last_replace;

			$update_public_homepage_content = array(
				'ID'           => get_option( 'main_page_url' ),
				'post_content' => $content,
			);
			wp_update_post($update_public_homepage_content);
		endwhile;
		wp_reset_postdata();
	}
}

add_action( 'admin_init','wpjobster_change_user_homepage_title' );
function wpjobster_change_user_homepage_title(){
	if( get_option( 'wpjobster_change_user_homepage_title' ) != 'done' ){

		update_option( 'wpjobster_change_user_homepage_title', 'done' );

		$user_homepage_query = new WP_Query( array( 'page_id' => get_option( 'main_page_url_user' ) ) );
		while ( $user_homepage_query->have_posts() ) : $user_homepage_query->the_post();
			$the_content = stripslashes( get_the_content() );
			$first_replace = str_replace( 'h3', 'h2|font_size:30', $the_content );
			$second_replace = str_replace( 'use_theme_fonts="yes"', 'use_theme_fonts="yes" el_class="heading-title fancy-underline"', $first_replace );
			$third_replace = str_replace( 'margin-bottom: -65px !important;', 'margin-bottom: 1.33em;', $second_replace );
			$last_replace = str_replace( 'el_class="heading-title fancy-underline" el_class="heading-title fancy-underline"', 'el_class="heading-title fancy-underline"', $third_replace );
			$last_replace = str_replace( '[requestbox]', '', $last_replace );
			$content = $last_replace;

			$update_user_homepage_content = array(
				'ID'           => get_option( 'main_page_url_user' ),
				'post_content' => $content,
			);
			wp_update_post($update_user_homepage_content);
		endwhile;
		wp_reset_postdata();
	}
}

if( get_option( 'wpjobster_set_footer_content' ) != 'done' ){
	update_option( 'wpjobster_set_footer_content', 'done' );

	$files = array(
		get_template_directory() . '/images/carts/card-visa.png',
		get_template_directory() . '/images/carts/card-mastercard.png',
		get_template_directory() . '/images/carts/card-americanexpress.png',
		get_template_directory() . '/images/carts/card-discover.png',
		get_template_directory() . '/images/carts/card-jcb.png',
		get_template_directory() . '/images/carts/card-dinersclub.png',
		get_template_directory() . '/images/carts/card-paypal.png'
	);
	foreach ($files as $file) {

		// Insert images to media
		$parent_post_id = 0;
		$filename = basename($file);
		$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => $parent_post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}
		}

		$arr[] = array( 'link_url' => $attachment_id );
	}
	set_theme_mod( 'image_demo', $arr );

	set_theme_mod( 'copyright_text', '&copy; 2017 <a href="http://wpjobster.com">wpjobster.com</a>' );
}

if ( get_option( 'wpjobster_set_copyright_text' ) != 'done' ) {

	update_option( 'wpjobster_set_copyright_text', 'done' );

	$old_copyright_text = get_option( 'wpjobster_footer_copyright_text' );
	$current_copyright_text = get_theme_mod( 'copyright_text' );

	if ( $old_copyright_text ) {
		if ( preg_match( '/wpjobster.com/', $current_copyright_text ) || ! $current_copyright_text ) {
			set_theme_mod( 'copyright_text', $old_copyright_text );
		}
	}
}

if( get_option( 'wpjobster_set_user_account_menu' ) != 'done' ){

	update_option( 'wpjobster_set_user_account_menu', 'done' );

	$menu_name = 'Header User Account Menu';
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu_exists ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		$pages = array(
			get_option( 'wpjobster_my_account_page_id', false ),
			get_option( 'wpjobster_my_account_shopping_page_id', false ),
			get_option( 'wpjobster_my_account_sales_page_id', false ),
			get_option( 'wpjobster_my_account_payments_page_id', false ),
			get_option( 'wpjobster_my_account_priv_mess_page_id', false ),
			get_option( 'wpjobster_my_account_personal_info_page_id', false ),
			get_option( 'wpjobster_my_account_reviews_page_id', false )
		);

		if ( $pages ) {
			foreach ($pages as $page) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-title' => get_the_title( $page ),
						'menu-item-status' => 'publish'
					)
				);
			}
		}
	}

	$menu_header = get_term_by('name', 'Header User Account Menu', 'nav_menu');
	$menu_header_id = $menu_header->term_id;
	$locations = get_theme_mod('nav_menu_locations');
	$locations['wpjobster_header_user_account_menu'] = $menu_header_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

if( get_option( 'wpjobster_set_footer_widgets_content_504' ) != 'done' ){
	update_option( 'wpjobster_set_footer_widgets_content_504', 'done' );

	if ( ! is_active_sidebar( 'footer-widget-1' )
		&& ! is_active_sidebar( 'footer-widget-1-logged-in' )
		&& ! is_active_sidebar( 'footer-widget-2' )
		&& ! is_active_sidebar( 'footer-widget-2-logged-in' )
		&& ! is_active_sidebar( 'footer-widget-3' )
		&& ! is_active_sidebar( 'footer-widget-3-logged-in' )
		&& ! is_active_sidebar( 'footer-widget-4' )
		&& ! is_active_sidebar( 'footer-widget-4-logged-in' )
		&& ! is_active_sidebar( 'footer-widget-5' )
		&& ! is_active_sidebar( 'footer-widget-5-logged-in' )
	) {
		// run the widget update code

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'User Menu', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c1_footer_menu' ) ), 'footer-widget-1' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'User Menu', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c1_footer_menu_logged_in' ) ), 'footer-widget-1-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c2_footer_menu' ) ), 'footer-widget-2' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c2_footer_menu_logged_in' ) ), 'footer-widget-2-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Useful Links', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c3_footer_useful_links' ) ), 'footer-widget-3' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Useful Links', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c3_footer_useful_links' ) ), 'footer-widget-3-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c4_footer_useful_links' ) ), 'footer-widget-4' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c4_footer_useful_links' ) ), 'footer-widget-4-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Follow us on', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c5_footer_social' ) ), 'footer-widget-5' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Follow us on', 'nav_menu' => wpjobster_get_menu_id_by_location( 'wpjobster_c5_footer_social' ) ), 'footer-widget-5-logged-in' );
	}
}

if( get_option( 'wpjobster_set_allowed_mime_types' ) != 'done' ){

	update_option( 'wpjobster_set_allowed_mime_types', 'done' );

	$default_allowed = array('zip', 'rar', 'jpg', 'png', 'psd', 'gif', 'jpeg', 'ai', 'cdr', 'eps', 'txt', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', 'mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'avi', 'mpg', 'ogv', 'dwg', 'svg' );

	update_option( 'wpjobster_allowed_mime_types', $default_allowed );
}

if ( get_option( 'wpjobster_update_site_identity' ) != 'done' ) {
	update_option( 'wpjobster_update_site_identity', 'done' );

	$wpjobster_logo_url = get_option( 'wpjobster_logo_url' );
	if ( $wpjobster_logo_url ) {
		set_theme_mod( 'site_logo', $wpjobster_logo_url );
	}

	$wpjobster_favicon_url = get_option( 'wpjobster_favicon_url' );
	if ( $wpjobster_favicon_url ) {
		set_theme_mod( 'site_icon', $wpjobster_favicon_url );
	}
}

if ( get_option( 'wpjobster_withdrawal_update' ) != 'done' ) {
	update_option( 'wpjobster_withdrawal_update', 'done' );

	$wpdb->query( "ALTER TABLE $wpdb->prefix}job_withdraw ADD activation_key VARCHAR(500) NULL AFTER payedamount;" );
}

if ( get_option( 'wpjobster_510_basic_options' ) != 'done' ) {
	update_option( 'wpjobster_510_basic_options', 'done' );

	update_option( 'wpjobster_packages_enabled' , 'no' );
	update_option( 'wpjobster_en_user_online_status' , 'yes_with_icon' );
	update_option( 'wpjobster_enable_last_seen' , 'no' );
	update_option( 'wpjobster_enable_user_reCaptcha' , 'no' );
}
