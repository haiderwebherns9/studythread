<?php

class SmsGateways{

	function wpjobster_sms_gateways() {

		// GENERAL SECTION OPTIONS #####
		$id_icon    = 'icon-options-general4';
		$ttl_of_stuff   = 'Jobster - SMS Gateways';
		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			// Gral Settings
			if(isset($_POST['wpjobster_save_gate_settings'])) {
				if (wpjobster_sms_allowed()) {

					update_option('wpjobster_sms_gateways_enable',      trim($_POST['wpjobster_sms_gateways_enable']));
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

			// Twilio
			if(isset($_POST['wpjobster_save_twilio'])) {
				if (wpjobster_sms_allowed()) {

					if(isset($_POST['wpjobster_theme_accountsid'])) update_option('wpjobster_theme_accountsid', trim($_POST['wpjobster_theme_accountsid']));
					if(isset($_POST['wpjobster_theme_apipass'])) update_option('wpjobster_theme_apipass'      , trim($_POST['wpjobster_theme_apipass']));
					if(isset($_POST['wpjobster_theme_authtoken'])) update_option('wpjobster_theme_authtoken'  , trim($_POST['wpjobster_theme_authtoken']));

					update_option('wpjobster_sms_numb_twilio_from',  trim($_POST['wpjobster_sms_numb_twilio_from']));

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
			// Cafe24
			if(isset($_POST['wpjobster_save_cafe'])) {
				if (wpjobster_sms_allowed()) {

					update_option('wpjobster_theme_cafe_userid',  trim($_POST['wpjobster_theme_cafe_userid']));
					update_option('wpjobster_theme_cafe_secure',  trim($_POST['wpjobster_theme_cafe_secure']));

					update_option('wpjobster_sms_numb_cafe_from', trim($_POST['wpjobster_sms_numb_cafe_from']));

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

			do_action('wpjobster_sms_methods_action');

			// ##### TABS IN ADMIN FOR EVERY GW #####
			// (the html tabs for the gateways options in admin)

			wpj_sms_gateways_html();

		echo '</div>';

	}

}

$sg = new SmsGateways();
