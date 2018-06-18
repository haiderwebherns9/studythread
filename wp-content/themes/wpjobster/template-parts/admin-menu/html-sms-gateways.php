<?php

function wpj_sms_gateways_html() {

	$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));

?>

<div id="usual2" class="usual">

	<ul>
		<li><a href="#tabs1"><?php _e("General Settings",'wpjobster'); ?></a></li>
		<li><a href="#tabs2"><?php _e("Twilio",'wpjobster'); ?></a></li>
		<li><a href="#tabs3"><?php _e("Cafe24",'wpjobster'); ?></a></li>

		<?php $wpjobster_sms_gateways = get_wpjobster_sms_gateways();
		$sms_gate_plugin = array();

		if(!empty($wpjobster_sms_gateways)){
			foreach($wpjobster_sms_gateways as $index=>$gateway){
				$wpjobster_sms_gateways_index[$gateway['unique_id']]=$index;
				$sms_gate_plugin[ $gateway['unique_id'] ] = $gateway['label']; ?>

				<li><a href="#tabs<?php echo $gateway['unique_id'];?>"><?php echo $gateway['label'] ?></a></li>
				<?php if(isset($gateway['show_settigs_form']) && $gateway['show_settigs_form']!=''){
					add_action("wpjobster_show_smsgateway_forms",$gateway['show_settigs_form'],$index,4);
				}
			}
		} ?>
	</ul>

	<?php do_action('wpjobster_show_smsgateway_forms',$wpjobster_sms_gateways,$arr); ?>

	<div id="tabs1">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-gateways&active_tab=tabs1">

			<?php if (!wpjobster_sms_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="200"><?php _e('Choose to enable:','wpjobster'); ?></td>
					<td><?php
						$sms_gate_theme = array( "-" => 'Choose',"twilio" => 'Twilio', "cafe24" => "cafe24");
						$sms_gate = array_merge($sms_gate_theme,$sms_gate_plugin);
						echo wpjobster_get_option_drop_down($sms_gate, 'wpjobster_sms_gateways_enable'); ?>
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_gate_settings" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>

	<div id="tabs2">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-gateways&active_tab=tabs2">

			<?php if (!wpjobster_sms_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Account Sid:','wpjobster'); ?></td>
					<td><input type="text" name="wpjobster_theme_accountsid" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_accountsid') ); ?>" size="85" /> </td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Auth Token:','wpjobster'); ?></td>
					<td><input type="text" name="wpjobster_theme_authtoken" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_authtoken') ); ?>" size="55" /> </td>

				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="160"><?php _e("SMS From Twilio Number:",'wpjobster'); ?></td>
					<td><input type="text" size="45" name="wpjobster_sms_numb_twilio_from" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', stripslashes( get_option('wpjobster_sms_numb_twilio_from') ) ); ?>"/></td>

				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_twilio" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

	</div>

	<div id="tabs3">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-gateways&active_tab=tabs3">

			<?php if (!wpjobster_sms_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('User Id:','wpjobster'); ?></td>
					<td><input type="text" name="wpjobster_theme_cafe_userid" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_cafe_userid') ); ?>" size="85" /> </td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Certification Key:','wpjobster'); ?></td>
					<td><input type="text" name="wpjobster_theme_cafe_secure" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_cafe_secure') ); ?>" size="55" /> </td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="160"><?php _e("From Number:",'wpjobster'); ?></td>
					<td><input type="text" size="45" name="wpjobster_sms_numb_cafe_from" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', stripslashes( get_option('wpjobster_sms_numb_cafe_from') ) ); ?>"/></td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_cafe" value="<?php _e('Save Options','wpjobster'); ?>"/></td>

				</tr>

			</table>
		</form>
	</div>
</div>
<?php
}
