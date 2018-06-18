<?php

class SmsSettings{

	function wpjobster_sms_settings() {
		$id_icon      = 'icon-options-general-email';
		$ttl_of_stuff = 'Jobster - '.__('SMS Settings','wpjobster');
		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_POST['wpjobster_save1'])) {
				if (wpjobster_sms_allowed()) {

					update_option('wpjobster_sms_gateways_enable', trim($_POST['wpjobster_sms_gateways_enable']));

					update_option('wpjobster_sms_admin_numb_from', trim($_POST['wpjobster_sms_admin_numb_from']));

					update_option('wpjobster_verify_phone_numbers', trim($_POST['wpjobster_verify_phone_numbers']));

					if( $_POST['wpjobster_verify_phone_numbers'] == 'no' ){
						update_option( 'wpjobster_lock_verify_phone_numbers', 'no' );
					} else {
						update_option( 'wpjobster_lock_verify_phone_numbers', trim( $_POST['wpjobster_lock_verify_phone_numbers'] ) );
					}

					if( $_POST['wpjobster_verify_phone_numbers'] == 'no' && $_POST['wpjobster_lock_verify_phone_numbers'] == 'yes' ){
						echo '<div class="error fade"><p>'.__('You can\'t lock the user to My Account page because the phone number verification is disabled','wpjobster').'!</p></div>';
					}

					echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';

				} else {
					update_option('wpjobster_sms_gateways_enable', '-');
					?>
					<div class="error notice">
						<p>Could not save SMS settings. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
					</div>
					<?php
				}
			}

			$reasons = notifications_array();
			$email_categories = notifications_array();
			$languages = get_preferred_languages();

			foreach ( $email_categories as $email_category ) {
				if( isset( $email_category["items"] ) && $email_category["items"] ){
					foreach ( $email_category["items"] as $reason => $item ) {
						if ( isset( $_POST['uz_save_sms_' . $reason] ) ) {
							if ( wpjobster_sms_allowed() ) {
								update_option('uz_sms_'.$reason.'_enable', trim($_POST['uz_sms_'.$reason.'_enable']));
								foreach ($languages as $lang => $lang_name) {
									update_option('uz_sms_'.$reason.'_'.$lang.'_message', trim($_POST['uz_sms_'.$reason.'_'.$lang.'_message']));
								}
								echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
							} else {
								update_option('wpjobster_sms_gateways_enable', '-');
								?>
								<div class="error notice">
									<p>Could not save SMS settings. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to Entrepreneur in order to use this feature.</p>
								</div>
								<?php
							}
						}
					}
				}
			}

			wpj_sms_settings_html();

		echo '</div>';

	}

}

$ss = new SmsSettings();
