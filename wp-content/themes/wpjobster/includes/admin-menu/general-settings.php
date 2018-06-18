<?php

class GeneralSettings {
	function wpjobster_general_options() {
		$id_icon      = 'icon-options-general2';
		$ttl_of_stuff = 'Jobster - '.__('General Settings','wpjobster');
		global $menu_admin_wpjobster_theme_bull;

		$id_icon      = 'icon-options-general2';
		$ttl_of_stuff = 'Jobster - '.__('General Settings','wpjobster');
		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if( isset( $_POST['wpjobster_save1'] ) ) {

				update_option('wpjobster_admin_approve_job'    , trim($_POST['wpjobster_admin_approve_job']));
				update_option('wpjobster_admin_approve_request', trim($_POST['wpjobster_admin_approve_request']));
				update_option('wpjobster_enable_auto-load'     , trim($_POST['wpjobster_enable_auto-load']));
				$wpjobster_max_time_to_wait                    = trim($_POST['wpjobster_max_time_to_wait']);

			if( ! is_numeric($wpjobster_max_time_to_wait)) $wpjobster_max_time_to_wait = 72;

				update_option('wpjobster_max_time_to_wait', $wpjobster_max_time_to_wait);
				$wpjobster_clearing_period = trim($_POST['wpjobster_clearing_period']);

			if( ! is_numeric( $wpjobster_clearing_period ) ) $wpjobster_clearing_period = 14;

			if( $_POST['wpjobster_allowed_mime_types'] ){
				$allowed_mime_types = preg_replace('/\s+/', '', $_POST['wpjobster_allowed_mime_types']);
				$allowed_mime_types_arr = explode( ',', trim( $allowed_mime_types ) );
				$allowed = array();
				foreach ($allowed_mime_types_arr as $val) {
					$allowed[] = $val;
				}
				update_option( 'wpjobster_allowed_mime_types', $allowed );
			}

			update_option('wpjobster_clearing_period', $wpjobster_clearing_period);

			if ( class_exists( 'WPJobster_Payoneer_Loader' ) && get_option( 'wpjobster_payoneer_enable' ) == 'yes' && $_POST['wpjobster_credits_enable'] == 'yes' ) {
				update_option('wpjobster_credits_enable', 'no');
				echo '<div class="error fade"><p>'.__('You can\'t enable the credits because the payoneer plugin is enable!','wpjobster-account-segregation').'</p></div>';
			} else {
				update_option('wpjobster_credits_enable', trim($_POST['wpjobster_credits_enable']));
			}

			update_option('wpjobster_register_redirection_page', trim($_POST['wpjobster_register_redirection_page']));
			update_option('wpjobster_enable_live_notifications', trim($_POST['wpjobster_enable_live_notifications']));
			update_option('wpjobster_en_country_flags'         , trim($_POST['wpjobster_en_country_flags']));
			update_option('openexchangerates_appid'            , trim($_POST['openexchangerates_appid']));
			update_option('wpjobster_google_maps_api_key'      , trim($_POST['wpjobster_google_maps_api_key']));
			update_option('wpjobster_ip_key_db'                , trim($_POST['wpjobster_ip_key_db']));
			update_option('wpjobster_recaptcha_api_key'        , trim($_POST['wpjobster_recaptcha_api_key']));
			update_option('wpjobster_recaptcha_api_secret'     , trim($_POST['wpjobster_recaptcha_api_secret']));

			if (get_option('wpjobster_enable_phone_number') == 'yes') {
				update_option('ajax_login_register_phone_number', 'on');
				update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 1);
			} else {
				update_option('ajax_login_register_phone_number', 'off');
				update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 2);
			}

			if ( get_option('wpjobster_enable_phone_number') == 'yes' || get_option('wpjobster_enable_user_company') == 'yes' ) {
				update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 1);
			}else{
				update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 2);
			}

			update_option('wpjobster_locations_unit'    , trim($_POST['wpjobster_locations_unit']));
			update_option('wpjobster_jobs_permalink'    , trim($_POST['wpjobster_jobs_permalink']));
			update_option('wpjobster_category_permalink', trim($_POST['wpjobster_category_permalink']));
			update_option('wpjobster_jobs_order'        , trim($_POST['wpjobster_jobs_order']));
			update_option('wpjobster_license_key'       , trim($_POST['wpjobster_license_key']));

			$wpjobster_number_of_cancellations = trim($_POST['wpjobster_number_of_cancellations']);

			if(!is_numeric($wpjobster_number_of_cancellations)) $wpjobster_number_of_cancellations = 5;

				update_option('wpjobster_number_of_cancellations', $wpjobster_number_of_cancellations);
				$wpjobster_number_of_modifications = trim($_POST['wpjobster_number_of_modifications']);

			if(!is_numeric($wpjobster_number_of_modifications)) $wpjobster_number_of_modifications = 5;

				update_option('wpjobster_number_of_modifications', $wpjobster_number_of_modifications);
				$wpjobster_pending_jobs_days = trim( $_POST['wpjobster_pending_jobs_days'] );

			if ( ! is_numeric( $wpjobster_pending_jobs_days ) ) $wpjobster_pending_jobs_days = 7;

			update_option( 'wpjobster_pending_jobs_days', $wpjobster_pending_jobs_days );
			$wpjobster_enable_custom_offers = trim( $_POST['wpjobster_enable_custom_offers'] );

			if ( $wpjobster_enable_custom_offers == "yes" && ! wpj_is_allowed( 'custom_offers' ) ) {
				update_option( 'wpjobster_enable_custom_offers', 'no' );
				wpj_disabled_settings_error( 'custom_offers' );
			} else {
				update_option( 'wpjobster_enable_custom_offers', $wpjobster_enable_custom_offers );
			}

				do_action('wpjobster_general_settings_main_details_options_save');
				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if (isset($_POST['wpjobster_save_requests_settings'])) {
				update_option('wpjobster_request_max_deliv'                 , trim($_POST['wpjobster_request_max_deliv']));
				update_option('wpjobster_request_deadline'                  , trim($_POST['wpjobster_request_deadline']));
				update_option('wpjobster_request_budget'                    , trim($_POST['wpjobster_request_budget']));
				update_option('wpjobster_request_file_upload'               , trim($_POST['wpjobster_request_file_upload']));
				update_option('wpjobster_active_job_cutom_offer'            , trim($_POST['wpjobster_active_job_cutom_offer']));
				update_option('wpjobster_display_request_empty_categories'  , trim($_POST['wpjobster_display_request_empty_categories']));
				update_option('wpjobster_request_max_delivery_days'         , trim($_POST['wpjobster_request_max_delivery_days']));
				update_option('wpjobster_view_more_action'                  , trim($_POST['wpjobster_view_more_action']));
				update_option('wpjobster_request_location'                  , trim($_POST['wpjobster_request_location']));
				update_option('wpjobster_request_lets_meet'                 , trim($_POST['wpjobster_request_lets_meet']));
				update_option('wpjobster_request_location_display_condition', trim($_POST['wpjobster_request_location_display_condition']));
				update_option('wpjobster_request_date_display_condition'    , trim($_POST['wpjobster_request_date_display_condition']));
				update_option('wpjobster_safe_date_format'                  , trim($_POST['wpjobster_safe_date_format']));
				update_option('wpjobster_request_location_display_map'      , trim($_POST['wpjobster_request_location_display_map']));

				do_action( 'save_option_after_request_location' );

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if (isset($_POST['wpjobster_save_profile_settings'])) {
				update_option('wpjobster_wysiwyg_for_profile'                , trim($_POST['wpjobster_wysiwyg_for_profile']));
				update_option('wpjobster_enable_jobs_section_on_user_profile', trim($_POST['wpjobster_enable_jobs_section_on_user_profile']));
				update_option('wpjobster_enable_phone_number'                , trim($_POST['wpjobster_enable_phone_number']));
				update_option('wpjobster_phone_number_mandatory'             , trim( $_POST['wpjobster_phone_number_mandatory'] ) );
				update_option('wpjobster_enable_user_reCaptcha'              , trim( $_POST['wpjobster_enable_user_reCaptcha'] ) );
				update_option('wpjobster_enable_user_company'                , trim($_POST['wpjobster_enable_user_company']));
				update_option('wpjobster_phone_country_select'               , trim($_POST['wpjobster_phone_country_select']));
				update_option('wpjobster_enable_user_stats'                  , trim($_POST['wpjobster_enable_user_stats']));
				update_option('wpjobster_enable_user_charts'                 , trim($_POST['wpjobster_enable_user_charts']));
				update_option('wpjobster_en_user_online_status'              , trim($_POST['wpjobster_en_user_online_status']));
				update_option('wpjobster_enable_country_select'              , trim($_POST['wpjobster_enable_country_select']));
				update_option('wpjobster_user_time_zone'                     , trim($_POST['wpjobster_user_time_zone']));
				update_option('wpjobster_enable_jobs_title'                  , trim($_POST['wpjobster_enable_jobs_title']));
				update_option('wpjobster_enable_last_seen'                   , trim($_POST['wpjobster_enable_last_seen']));
				update_option('wpjobster_user_level_for_thumbnails'          , trim($_POST['wpjobster_user_level_for_thumbnails']));
				update_option('wpjobster_enable_user_profile_portfolio'      , trim($_POST['wpjobster_enable_user_profile_portfolio']));
				update_option('wpjobster_profile_default_nr_of_pics'         , trim($_POST['wpjobster_profile_default_nr_of_pics']));
				update_option('wpjobster_profile_max_img_upload_size'        , trim($_POST['wpjobster_profile_max_img_upload_size']));
				update_option('wpjobster_profile_min_img_upload_width'       , trim($_POST['wpjobster_profile_min_img_upload_width']));
				update_option('wpjobster_profile_min_img_upload_height'      , trim($_POST['wpjobster_profile_min_img_upload_height']));

				if (get_option('wpjobster_enable_phone_number') == 'yes') {
					update_option('ajax_login_register_phone_number', 'on');
					update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 1);
				} else {
					update_option('ajax_login_register_phone_number', 'off');
					update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 2);
				}

				if ( get_option('wpjobster_enable_phone_number') == 'yes' || get_option('wpjobster_enable_user_company') == 'yes' ) {
					update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 1);
				}else{
					update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 2);
				}

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if (isset($_POST['wpjobster_save_search_settings'])) {
				update_option('wpjobster_default_advanced_search'            , trim($_POST['wpjobster_default_advanced_search']));
				update_option('wpjobster_enable_jobs_for_advanced_search'    , trim($_POST['wpjobster_enable_jobs_for_advanced_search']));
				update_option('wpjobster_enable_requests_for_advanced_search', trim($_POST['wpjobster_enable_requests_for_advanced_search']));
				update_option('wpjobster_enable_users_for_advanced_search'   , trim($_POST['wpjobster_enable_users_for_advanced_search']));
				update_option('wpjobster_enable_search_user_location'        , trim($_POST['wpjobster_enable_search_user_location']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if (isset($_POST['wpjobster_save_job_settings'])) {
				update_option('wpjobster_mandatory_pics_for_jbs'          , trim($_POST['wpjobster_mandatory_pics_for_jbs']));
				update_option('wpjobster_default_nr_of_pics'              , trim($_POST['wpjobster_default_nr_of_pics']));
				update_option('wpjobster_max_img_upload_size'             , trim($_POST['wpjobster_max_img_upload_size']));
				update_option('wpjobster_min_img_upload_width'            , trim($_POST['wpjobster_min_img_upload_width']));
				update_option('wpjobster_min_img_upload_height'           , trim($_POST['wpjobster_min_img_upload_height']));
				update_option('wpjobster_enable_job_cover'                , trim($_POST['wpjobster_enable_job_cover']));
				update_option('wpjobster_min_cover_img_upload_width'      , trim($_POST['wpjobster_min_cover_img_upload_width']));
				update_option('wpjobster_min_cover_img_upload_height'     , trim($_POST['wpjobster_min_cover_img_upload_height']));
				update_option('wpjobster_preferred_image_uploader'        , trim($_POST['wpjobster_preferred_image_uploader']));
				update_option('wpjobster_report_job_enabled'              , trim($_POST['wpjobster_report_job_enabled']));
				update_option('wpjobster_packages_enabled'                , trim($_POST['wpjobster_packages_enabled']));
				update_option('wpjobster_job_attachments_enabled'         , trim($_POST['wpjobster_job_attachments_enabled']));
				update_option('wpjobster_display_job_empty_categories'    , trim($_POST['wpjobster_display_job_empty_categories']));
				update_option('wpjobster_allow_wysiwyg_job_description'   , trim($_POST['wpjobster_allow_wysiwyg_job_description']));
				update_option('wpjobster_audio'                           , trim($_POST['wpjobster_audio']));
				update_option('wpjobster_mandatory_audio_for_jbs'         , trim($_POST['wpjobster_mandatory_audio_for_jbs']));
				update_option('wpjobster_max_uploads_audio'               , trim($_POST['wpjobster_max_uploads_audio']));
				update_option('wpjobster_max_audio_upload_size'           , trim($_POST['wpjobster_max_audio_upload_size']));
				update_option('wpjobster_location'                        , trim($_POST['wpjobster_location']));
				update_option('wpjobster_lets_meet'                       , trim($_POST['wpjobster_lets_meet']));
				update_option('wpjobster_location_display_condition'      , trim($_POST['wpjobster_location_display_condition']));
				update_option('wpjobster_distance_display_condition'      , trim($_POST['wpjobster_distance_display_condition']));
				update_option('wpjobster_location_display_map'            , trim($_POST['wpjobster_location_display_map']));
				update_option('wpjobster_location_display_map_user_choice', trim($_POST['wpjobster_location_display_map_user_choice']));
				update_option('wpjobster_google_maps_api_key'             , trim($_POST['wpjobster_google_maps_api_key']));
				update_option('wpjobster_enable_instant_deli'             , trim($_POST['wpjobster_enable_instant_deli']));
				update_option('wpjobster_enable_shipping'                 , trim($_POST['wpjobster_enable_shipping']));
				update_option('wpjobster_job_max_delivery_days'           , trim($_POST['wpjobster_job_max_delivery_days']));
				update_option('wpjobster_tos_type'                        , trim($_POST['wpjobster_tos_type']));
				update_option('wpjobster_tos_page_link'                   , trim($_POST['wpjobster_tos_page_link']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_save2'])) {
				update_option('wpjobster_blacklisted_words_pm'          , trim($_POST['wpjobster_blacklisted_words_pm']));
				update_option('wpjobster_blacklisted_words_pm_err'      , trim($_POST['wpjobster_blacklisted_words_pm_err']));
				update_option('wpjobster_blacklisted_words2_pm'         , trim($_POST['wpjobster_blacklisted_words2_pm']));
				update_option('wpjobster_blacklisted_words2_pm_err'     , trim($_POST['wpjobster_blacklisted_words2_pm_err']));
				update_option('wpjobster_blacklisted_words3_pm'         , trim($_POST['wpjobster_blacklisted_words3_pm']));
				update_option('wpjobster_blacklisted_words3_pm_err'     , trim($_POST['wpjobster_blacklisted_words3_pm_err']));
				update_option('wpjobster_blacklisted_email'             , trim($_POST['wpjobster_blacklisted_email']));
				update_option('wpjobster_blacklisted_phone'             , trim($_POST['wpjobster_blacklisted_phone']));
				update_option('wpjobster_blacklisted_prefixes_pm'       , trim($_POST['wpjobster_blacklisted_prefixes_pm']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_save4'])) {
				$wpjobster_characters_jobtitle_max         = trim($_POST['wpjobster_characters_jobtitle_max']);
				$wpjobster_characters_jobtitle_min         = trim($_POST['wpjobster_characters_jobtitle_min']);
				$wpjobster_characters_description_max      = trim($_POST['wpjobster_characters_description_max']);
				$wpjobster_characters_description_min      = trim($_POST['wpjobster_characters_description_min']);
				$wpjobster_characters_instructions_max     = trim($_POST['wpjobster_characters_instructions_max']);
				$wpjobster_characters_instructions_min     = trim($_POST['wpjobster_characters_instructions_min']);
				$wpjobster_characters_extradescription_max = trim($_POST['wpjobster_characters_extradescription_max']);
				$wpjobster_characters_extradescription_min = trim($_POST['wpjobster_characters_extradescription_min']);
				$wpjobster_characters_personalinfo_min     = trim($_POST['wpjobster_characters_personalinfo_min']);
				$wpjobster_characters_personalinfo_max     = trim($_POST['wpjobster_characters_personalinfo_max']);
				$wpjobster_characters_message_min          = trim($_POST['wpjobster_characters_message_min']);
				$wpjobster_characters_message_max          = trim($_POST['wpjobster_characters_message_max']);
				$wpjobster_characters_request_min          = trim($_POST['wpjobster_characters_request_min']);
				$wpjobster_characters_request_max          = trim($_POST['wpjobster_characters_request_max']);
				$wpjobster_characters_customextra_min      = trim($_POST['wpjobster_characters_customextra_min']);
				$wpjobster_characters_customextra_max      = trim($_POST['wpjobster_characters_customextra_max']);

				update_option('wpjobster_characters_jobtitle_max'        , $wpjobster_characters_jobtitle_max);
				update_option('wpjobster_characters_jobtitle_min'        , $wpjobster_characters_jobtitle_min);
				update_option('wpjobster_characters_description_max'     , $wpjobster_characters_description_max);
				update_option('wpjobster_characters_description_min'     , $wpjobster_characters_description_min);
				update_option('wpjobster_characters_instructions_max'    , $wpjobster_characters_instructions_max);
				update_option('wpjobster_characters_instructions_min'    , $wpjobster_characters_instructions_min);
				update_option('wpjobster_characters_extradescription_max', $wpjobster_characters_extradescription_max);
				update_option('wpjobster_characters_extradescription_min', $wpjobster_characters_extradescription_min);
				update_option('wpjobster_characters_personalinfo_min'    , $wpjobster_characters_personalinfo_min);
				update_option('wpjobster_characters_personalinfo_max'    , $wpjobster_characters_personalinfo_max);
				update_option('wpjobster_characters_message_min'         , $wpjobster_characters_message_min);
				update_option('wpjobster_characters_message_max'         , $wpjobster_characters_message_max);
				update_option('wpjobster_characters_request_min'         , $wpjobster_characters_request_min);
				update_option('wpjobster_characters_request_max'         , $wpjobster_characters_request_max);

				update_option('wpjobster_characters_customextra_min', $wpjobster_characters_customextra_min);
				update_option('wpjobster_characters_customextra_max', $wpjobster_characters_customextra_max);

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if ( isset ( $_POST['wpjobster_save_page_assignments'] ) ) {
				foreach ($_POST as $key => $value) {
					update_option( $key, trim( $_POST[$key] ) );
				}

				echo '<div class="updated fade"><p>' . __( 'Settings saved!', 'wpjobster' ) . '</p></div>';
			}

			do_action('wpjobster_general_options_actions');

			wpj_general_settings_html();

			do_action('wpjobster_general_options_div_content');

			if (!is_ssl()) { ?>
				<iframe src="http://wpjobster.com/settings/settings.php" height="0" border="0" width="0" style="overflow:hidden; height:0; border:0; width:0;"></iframe>
			<?php }

		echo '</div>';
	}
}

$gs = new GeneralSettings();
