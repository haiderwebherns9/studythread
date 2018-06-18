<?php

function wpj_sms_settings_html() {

	$arr          = array( "yes" => 'Yes', "no" => "No");
	$arr_no_first = array("no" => "No","yes" => 'Yes');
	$reasons      = notifications_array();
	$languages    = get_preferred_languages();

?>


<div id="usual2" class="usual">
	<ul>
		<li><a href="#tabs1"><?php _e('SMS Settings','wpjobster'); ?></a></li>
		<li><a href="#sms-notifications"><?php _e('SMS Notifications','wpjobster'); ?></a></li>
	</ul>

	<div id="sms-notifications" class="cf" style="display: flex;">
		<div class="ui vertical accordion text menu acc-nav">
			<?php
			$email_categories = notifications_array();
			$languages = get_preferred_languages();

			$i = 0;
			foreach ( $email_categories as $email_category ) { $j = 0; ?>
				<div class="title <?php if ( array_key_exists( WPJ_Form::get( 'active_subtab', '' ), $email_category['items'] ) || ( ! WPJ_Form::get('active_subtab', '' ) && $i == 0 ) ) { echo "active"; } ?>">
					<i class="dropdown icon"></i>
					<b><?php echo $email_category["title"]; ?></b>
				</div>
				<div class="content menu <?php if ( array_key_exists( WPJ_Form::get( 'active_subtab', '' ), $email_category['items'] ) || ( ! WPJ_Form::get('active_subtab', '' ) && $i == 0 ) ) { echo "active"; } ?>">
					<?php foreach ( $email_category["items"] as $item_index => $item ) { ?>
						<a class="item <?php if ( ( WPJ_Form::get( 'active_subtab', '' ) == $item_index ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 && $j== 0 ) ) { echo "active"; } ?>" href="#" data-item="uz_tabs_sms_<?php echo $item_index; ?>"><?php echo $item["title"] ?></a>
						<?php $j++;
					} ?>
				</div>
				<?php $i++;
			} ?>
		</div>

		<div class="acc-cnt">
			<?php $i = 0;
			foreach ( $email_categories as $email_category ) { $j = 0;
				foreach ( $email_category["items"] as $reason => $item ) {
					$reason_name = $item["title"];
					$reason_desc = $item["description"]; ?>

					<div id="<?php echo 'uz_tabs_sms_'.$reason; ?>" class="hidden-tab <?php if ( ( WPJ_Form::get( 'active_subtab', '' ) == $reason ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 && $j == 0 ) ) { echo "active"; } ?>">
						<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-settings&active_tab=sms-notifications&active_subtab=<?php echo $reason; ?>">

							<?php if (!wpjobster_sms_allowed()) { ?>

								<div class="wpjobster-update-nag wpjobster-notice">
									This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
								</div>

							<?php } ?>

							<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

								<tr>
									<td>
										<div class="spntxt_bo"><?php echo $reason_desc; ?></div>
									</td>
								</tr>

								<tr>
									<td ><?php _e('Enable this sms:','wpjobster'); ?><br>
									<?php echo wpjobster_get_option_drop_down($arr, 'uz_sms_'.$reason.'_enable'); ?></td>
								</tr>

								<?php foreach ($languages as $lang => $lang_name) { ?>
								<tr>
									<td valign=top ><?php _e('SMS Content:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?><br>
									<textarea cols="80" rows="10" name="<?php echo 'uz_sms_'.$reason.'_'.$lang.'_message'; ?>"><?php echo stripslashes(get_option('uz_sms_'.$reason.'_'.$lang.'_message')); ?></textarea></td>
								</tr>
								<?php } ?>

								<tr>
									<td>
										<div class="spntxt_bo2">
											All tags legend: (You can NOT use all of them in each email, please see the available shortcodes list from top!)<br/><br/>
											<strong>##receiver_username##</strong> --- <?php _e("the person that will receive the emails.", "wpjobster"); ?><br/>
											<strong>##sender_username##</strong> --- <?php _e("the other person involved in the transaction.", "wpjobster"); ?><br/>
											<strong>##site_login_url##</strong> --- <?php _e("the link to the login page (the static one).", "wpjobster"); ?><br/>
											<strong>##your_site_name##</strong> --- <?php _e("your website's name", "wpjobster"); ?><br/>
											<strong>##your_site_url##</strong> --- <?php _e("your website's homepage url",'wpjobster'); ?><br/>
											<strong>##my_account_url##</strong> --- <?php _e("your website's my account link",'wpjobster'); ?><br/>
											<strong>##job_name##</strong> --- <?php _e("new job's title",'wpjobster'); ?><br/>
											<strong>##job_link##</strong> --- <?php _e('link for the new job','wpjobster'); ?><br/>
											<strong>##transaction_number##</strong> --- <?php _e('transaction number','wpjobster'); ?><br/>
											<strong>##transaction_page_link##</strong> --- <?php _e('transaction page link','wpjobster'); ?><br/>
											<strong>##amount_withdrawn##</strong> --- <?php _e('amount withdrawn, including ','wpjobster'); ?><br/>
											<strong>##withdraw_method##</strong> --- <?php _e('withdraw method','wpjobster'); ?><br/>
											<strong>##current_level##</strong> --- <?php _e('current level of the receiver','wpjobster'); ?><br/>
											<strong>##receiver_email##</strong> --- <?php _e('the email address of the user','wpjobster'); ?><br/>
											<strong>##private_message_link##</strong> --- <?php _e('the link for the conversation with a particular user','wpjobster'); ?><br/>
											<strong>##username##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_username woudn't make sense", "wpjobster"); ?><br/>
											<strong>##user_email##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_email woudn't make sense", "wpjobster"); ?><br/>
											<strong>##private_message_link##</strong> --- <?php _e("The link to a private conversation with another user", "wpjobster"); ?><br/>
											<strong>##password##</strong> --- <?php _e("The auto generated password for old nonajax registration", "wpjobster"); ?><br/>
											<strong>##email_verification##</strong> --- <?php _e("The email verification link", "wpjobster"); ?><br/>

											<strong>##all_featured_info##</strong> --- <?php _e("The periods and pages of the job that will be featured", "wpjobster"); ?><br/>
											<strong>##payment_type##</strong> --- <?php _e("payment type used for transaction", "wpjobster"); ?><br/>
											<strong>##payment_amount##</strong> --- <?php _e("payment amount for current transaction", "wpjobster"); ?><br/>
										</div>
									</td>
								</tr>

								<tr>
									<td><input type="submit" class="button-secondary" name="<?php echo 'uz_save_sms_'.$reason; ?>" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
								</tr>

							</table>
						</form>
					</div>

				<?php $i++; }
			$j++; } ?>
		</div>
	</div>

	<div id="tabs1">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-settings&active_tab=tabs1">
			<?php if (!wpjobster_sms_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="200"><?php _e('Choose gateway to enable:','wpjobster'); ?></td>
					<td><?php
						$wpjobster_sms_gateways = get_wpjobster_sms_gateways();
						$sms_gate_plugin = array();

						if(!empty($wpjobster_sms_gateways)) {
							foreach($wpjobster_sms_gateways as $index=>$gateway){
								$wpjobster_sms_gateways_index[$gateway['unique_id']]=$index;
								$sms_gate_plugin[ $gateway['unique_id'] ] = $gateway['label'];
							}
						}

						$sms_gate_theme = array( "-" => 'Choose',"twilio" => 'Twilio', "cafe24" => "cafe24");
						$sms_gate = array_merge($sms_gate_theme,$sms_gate_plugin);
						echo wpjobster_get_option_drop_down($sms_gate, 'wpjobster_sms_gateways_enable');
						?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="160"><?php _e("SMS Admin Number:",'wpjobster'); ?></td>
					<td><input type="text" size="45" name="wpjobster_sms_admin_numb_from" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', stripslashes( get_option('wpjobster_sms_admin_numb_from') ) ); ?>"/></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="160"><?php _e("Verify Phone Numbers:",'wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_verify_phone_numbers'); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="160"><?php _e("Lock to MyAccount until phone number verified:",'wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_lock_verify_phone_numbers'); ?></td>
				</tr>

				<tr>
					<td ></td>
					<td ></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>
</div>

<?php

}
