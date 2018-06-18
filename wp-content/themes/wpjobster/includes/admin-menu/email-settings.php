<?php

class EmailSettings {

	function wpjobster_email_settings() {

		$id_icon      = 'icon-options-general-email';
		$ttl_of_stuff = 'Jobster - '.__( 'Email Settings','wpjobster' );
		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if ( isset( $_POST['wpjobster_save1'] ) ) {
				update_option( 'wpjobster_email_name_from', trim( $_POST['wpjobster_email_name_from'] ) );
				update_option( 'wpjobster_email_addr_from', trim( $_POST['wpjobster_email_addr_from'] ) );
				update_option( 'wpjobster_allow_html_emails', trim( $_POST['wpjobster_allow_html_emails'] ) );
				update_option( 'wpjobster_verify_email', trim( $_POST['wpjobster_verify_email'] ) );

				if( $_POST['wpjobster_verify_email'] == 'no' ){
					update_option( 'wpjobster_lock_verify_email_address', 'no' );
					update_option( 'uz_email_user_verification_enable', 'no' );
				} else {
					update_option( 'wpjobster_lock_verify_email_address', trim( $_POST['wpjobster_lock_verify_email_address'] ) );
				}

				if( $_POST['wpjobster_verify_email'] == 'no' && $_POST['wpjobster_lock_verify_email_address'] == 'yes' ){
					echo '<div class="error fade"><p>'.__('You can\'t lock the user to My Account page because the email verification is disabled','wpjobster').'!</p></div>';
				}

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if ( isset( $_POST['wpjobster_saveprefferedlanguages'] ) ) {

				for ( $i = 1; $i <= 10; $i++ ) {
					$language_x = 'wpjobster_language_' . $i;

					$wpjobster_language_x = trim( $_POST[$language_x] );
					update_option( $language_x, $wpjobster_language_x );
				}

				echo '<div class="updated fade"><p>'.__( 'Settings saved!','wpjobster' ).'</p></div>';
			}

			$reasons = notifications_array();
			$email_categories = notifications_array();
			$languages = get_preferred_languages();
			foreach ( $email_categories as $email_category ) {
				foreach ( $email_category["items"] as $reason => $item ) {
					if ( isset( $_POST['uz_save_email_' . $reason] ) ) {
						update_option('uz_email_'.$reason.'_enable', trim($_POST['uz_email_'.$reason.'_enable']));
						foreach ($languages as $lang => $lang_name) {
							update_option( 'uz_email_'.$reason.'_'.$lang.'_subject', trim( $_POST['uz_email_'.$reason.'_'.$lang.'_subject'] ) );
							update_option( 'uz_email_'.$reason.'_'.$lang.'_message', trim( $_POST['uz_email_'.$reason.'_'.$lang.'_message'] ) );
						}
						echo '<div class="updated fade"><p>'.__( 'Settings saved!','wpjobster' ).'</p></div>';
					}
				}
			}

			wpj_email_settings_html();

		echo '</div>';

	}
}

$es = new EmailSettings();
