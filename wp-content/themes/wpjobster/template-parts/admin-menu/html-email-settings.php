<?php
function wpj_email_settings_html() {
	$arr = array( "yes" => 'Yes', "no" => "No");
	$reasons = notifications_array();
	$languages = get_preferred_languages(); ?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('Email Settings','wpjobster'); ?></a></li>
			<li><a href="#preferred-languages"><?php _e('Preferred Languages','wpjobster'); ?></a></li>
			<li><a href="#email-notifications"><?php _e('Email Notifications','wpjobster'); ?></a></li>
		</ul>

		<div id="email-notifications" class="cf" style="display: flex;">
			<div class="ui vertical accordion text menu acc-nav">
				<?php
				$email_categories = notifications_array();
				$languages = get_preferred_languages();

				$i = 0;
				foreach ( $email_categories as $email_category ) {
					$j = 0; ?>
					<div class="title <?php if ( array_key_exists( WPJ_Form::get( 'active_subtab', '' ), $email_category['items'] ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 ) ) { echo "active"; } ?>">
						<i class="dropdown icon"></i>
						<b><?php echo $email_category["title"]; ?></b>
					</div>
					<div class="content menu <?php if ( array_key_exists( WPJ_Form::get( 'active_subtab', '' ), $email_category['items'] ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 ) ) { echo "active"; } ?>">
						<?php foreach ( $email_category["items"] as $item_index => $item ) { ?>
							<a class="item <?php if ( ( WPJ_Form::get( 'active_subtab', '' ) == $item_index ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 && $j == 0 ) ) { echo "active"; } ?>" href="#" data-item="uz_tabs_email_<?php echo $item_index; ?>"><?php echo $item["title"] ?></a>
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

						<div id="<?php echo 'uz_tabs_email_'.$reason; ?>" class="hidden-tab <?php if ( ( WPJ_Form::get( 'active_subtab', '' ) == $reason ) || ( ! WPJ_Form::get( 'active_subtab', '' ) && $i == 0 && $j == 0 ) ) { echo "active"; } ?>">

							<div class="spntxt_bo"><?php echo $reason_desc; ?></div>
							<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=email-settings&active_tab=email-notifications&active_subtab=<?php echo $reason; ?>">
								<table width="100%" class="sitemile-table">

									<tr>
										<td><?php _e('Enable this email:','wpjobster'); ?><br>
											<?php echo wpjobster_get_option_drop_down($arr, 'uz_email_'.$reason.'_enable'); ?>
										</td>
									</tr>

									<?php foreach ($languages as $lang => $lang_name) { ?>
										<tr>
											<td><?php _e('Email Subject:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?><br>
												<input type="text" size="80" name="<?php echo 'uz_email_'.$reason.'_'.$lang.'_subject'; ?>" value="<?php echo stripslashes(get_option('uz_email_'.$reason.'_'.$lang.'_subject')); ?>"/>
											</td>
										</tr>

										<tr>
											<td valign=top ><?php _e('Email Content:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?><br>
												<textarea cols="80" rows="10" name="<?php echo 'uz_email_'.$reason.'_'.$lang.'_message'; ?>"><?php echo stripslashes(get_option('uz_email_'.$reason.'_'.$lang.'_message')); ?></textarea>
											</td>
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
										<td><input type="submit" class="button-secondary" name="<?php echo 'uz_save_email_'.$reason; ?>" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
									</tr>
								</table>
							</form>
						</div>
					<?php $i++; }
				$j++; } ?>
			</div>
		</div>

		<div id="tabs1">

			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=email-settings&active_tab=tabs1">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e("Email From Name:",'wpjobster'); ?></td>
						<td><input type="text" size="45" name="wpjobster_email_name_from" value="<?php echo stripslashes(get_option('wpjobster_email_name_from')); ?>"/></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e("Sender Email Address:",'wpjobster'); ?></td>
						<td><input type="email" size="45" name="wpjobster_email_addr_from" value="<?php echo apply_filters( 'wpj_sensitive_info_email', stripslashes( get_option('wpjobster_email_addr_from') ) ); ?>"/></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e("Allow HTML in emails:",'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_allow_html_emails'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e("Verify Email:",'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_verify_email', 'yes'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="160"><?php _e("Lock to MyAccount until e-mail verified:",'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_lock_verify_email_address', 'no'); ?></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

		</div>

		<div id="preferred-languages">

			<form method="post" action="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=email-settings&active_tab=preferred-languages">
				<table width="100%" class="sitemile-table">
				  <?php for ($i = 1; $i <= 10; $i++) {

					$language_x = 'wpjobster_language_' . $i;
					?>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('Preferred language:','wpjobster'); ?> <?php echo $i; ?></td>
						<td><?php echo wpjobster_get_option_drop_down(get_all_languages(), $language_x); ?></td>
					</tr>

					<?php } ?>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_saveprefferedlanguages" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

		</div>
	</div>

	<div class="ui hidden divider"></div>

	<?php
}
