<?php /* Template Name: Email settings */ ?>
<?php get_header(); ?>

<?php
$reasons = notifications_array();
$email_categories = notifications_array();
$email_categories_no_admin = $email_categories;
unset( $email_categories_no_admin['admin'] );
unset( $email_categories_no_admin['registration'] );
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$sms_enabled = false;
if ( get_option( 'wpjobster_sms_gateways_enable' ) != '-' && get_option( 'wpjobster_sms_gateways_enable' ) != '' ) {
	$sms_enabled = true;
}

?>

<div id="content-full-ov">
	<div class="ui segment heading-cnt cf adjustments">
		<h1 class="heading-title left"><?php echo get_the_title(); ?></h1>
	</div>

	<?php
	if(isset($_POST['uz_save_notification'])){
		?><div class="ui segment"><?php
			//EMAIL NOTIFICATIONS
			if(isset($_POST['email_notifications'])){
				$email_notifications = $_POST['email_notifications'];
				$all_email_notifications = array();
				//ARRAY ALL EMAIL NOTIFICATIONS
				foreach ( $email_categories_no_admin as $email_category ) {
					foreach ( $email_category["items"] as $reason => $key ) {
						$reason_name = $key["title"];
						$all_email_notifications[] = 'uz_email_'.$reason.'_enable';
					}
				}
				//ARRAY ALL CHECKED NOTIFICATIONS
				$all_email_notifications_checked = array();
				foreach ($all_email_notifications as $reason => $val) {
					if(isset($email_notifications[$reason])){
						$all_email_notifications_checked[]=$email_notifications[$reason];
					}
				}
				//ARRAY ALL UNCHECKED NOTIFICATIONS
				$all_email_notifications_no_checked=array_diff($all_email_notifications, $all_email_notifications_checked);
				//UPDATE CHECK NOTIFICATIONS
				foreach ($all_email_notifications_checked as $chk) {
					update_user_meta($user_id, $chk, 'yes');
				}
				//UPDATE UNCHECKED NOTIFICATION
				foreach ($all_email_notifications_no_checked as $no_chk) {
					update_user_meta($user_id, $no_chk, 'no');
				}
			}else{
				//UPDATE ALL EMAIL NOTIFICATIONS TO NO
				foreach ( $email_categories_no_admin as $email_category ) {
					foreach ( $email_category["items"] as $reason => $key ) {
						$reason_name = $key["title"];
						update_user_meta($user_id, 'uz_email_'.$reason.'_enable', 'no');
					}
				}
			}

			//SMS NOTIFICATIONS
			if ( $sms_enabled ) {
				if(isset($_POST['sms_notifications'])){
					$sms_notifications = $_POST['sms_notifications'];
					$all_sms_notifications = array();
					//ARRAY ALL SMS NOTIFICATIONS
					foreach ( $email_categories_no_admin as $email_category ) {
						foreach ( $email_category["items"] as $reason => $key ) {
							$reason_name = $key["title"];
							$all_sms_notifications[] = 'uz_sms_'.$reason.'_enable';
						}
					}
					//ARRAY ALL CHECKED NOTIFICATIONS
					$all_sms_notifications_checked = array();
					foreach ($all_sms_notifications as $reason => $val) {
						if(isset($sms_notifications[$reason])){
							$all_sms_notifications_checked[]=$sms_notifications[$reason];
						}
					}
					//ARRAY ALL UNCHECKED NOTIFICATIONS
					$all_sms_notifications_no_checked=array_diff($all_sms_notifications, $all_sms_notifications_checked);
					//UPDATE CHECK NOTIFICATIONS
					foreach ($all_sms_notifications_checked as $chk) {
						update_user_meta($user_id, $chk, 'yes');
					}
					//UPDATE UNCHECKED NOTIFICATION
					foreach ($all_sms_notifications_no_checked as $no_chk) {
						update_user_meta($user_id, $no_chk, 'no');
					}
				}else{
					//UPDATE ALL SMS NOTIFICATIONS TO NO
					foreach ( $email_categories_no_admin as $email_category ) {
						foreach ( $email_category["items"] as $reason => $key ) {
							$reason_name = $key["title"];
							update_user_meta($user_id, 'uz_sms_'.$reason.'_enable', 'no');
						}
					}
				}
			}

			echo __('Settings saved!','wpjobster');
		?></div>
	<?php } ?>


	<div class="ui segment heading-cnt cf adjustments">
		<form method="POST" class="ui form">

				<div class="two fields">
					<div class="field">

						<div class="white-cnt heading-cnt cf center adjustments">
							<label><?php echo __('Email','wpjobster'); ?></label>
						</div>

						<div class="white-cnt heading-cnt cf">
							<p class="lighter">
								<ul class="notifications mail_notification">
									<li id="all-mail" class="little-bottom-space">
										<div class="ui checkbox">
											<input id="all-email-notify" type="checkbox" value="mark_all_email_notify" />
											<label><?php _e( 'Check all', 'wpjobster' ); ?></label>
										</div>
									</li>

									<?php
									foreach ( $email_categories_no_admin as $email_category ) {
										if( isset( $email_category["items"] ) && $email_category["items"] ){
											foreach ( $email_category["items"] as $reason => $pair ) {
												$reason_name = $pair["title"];
												$reason_desc = $pair["description"];
												$emailischecked = get_user_meta($user_id, 'uz_email_'.$reason.'_enable', true);

												if ( get_option( 'uz_email_'.$reason.'_enable' ) != 'no' ){ ?>
													<li>
														<div class="ui checkbox">
															<input type="checkbox" class="email_notifications" name="email_notifications[]" <?php if($emailischecked != "no") echo "checked"; ?> value="<?php echo 'uz_email_'.$reason.'_enable'; ?>" />
															<label><?php _e($reason_name, 'wpjobster'); ?></label>
														</div>
													</li>
												<?php }
											}
										}
									} ?>
								</ul>
							</p>
						</div>

					</div>


					<?php if ( $sms_enabled ) { ?>
						<div class="field">
							<div class="white-cnt heading-cnt cf center adjustments">
								<label><?php echo __('SMS','wpjobster'); ?></label>
							</div>

							<div class="white-cnt heading-cnt cf">
								<p class="lighter">
									<ul class="notifications sms_notification">
										<li id="all-sms" class="little-bottom-space">
											<div class="ui checkbox">
												<input id="all-sms-notify" type="checkbox" value="mark_all_sms_notify" />
												<label><?php _e( 'Check all', 'wpjobster' ); ?></label>
											</div>
										</li>

										<?php
										foreach ( $email_categories_no_admin as $email_category ) {
											if( isset( $email_category["items"] ) && $email_category["items"] ){
												foreach ( $email_category["items"] as $reason => $pair ) {
													$reason_name = $pair["title"];
													$reason_desc = $pair["description"];
													$smsischecked = get_user_meta($user_id, 'uz_sms_'.$reason.'_enable', true);

													if ( get_option( 'uz_sms_'.$reason.'_enable' ) != 'no' ) { ?>
														<li>
															<div class="ui checkbox">
																<input type="checkbox" class="sms_notifications" name="sms_notifications[]" <?php if($smsischecked != "no") echo "checked"; ?> value="<?php echo 'uz_sms_'.$reason.'_enable'; ?>" />
																<label><?php _e($reason_name, 'wpjobster'); ?></label>
															</div>
														</li>
													<?php }
												}
											}
										} ?>
									</ul>
								</p>
							</div>
						</div>
					<?php } ?>
				</div>
			<input type="submit" class="btn btn-front ui primary button" name="uz_save_notification" value="<?php _e('Save Options','wpjobster'); ?>"/>
		<div class="ui hidden divider"></div>
		</form>
	</div>
</div>

<div class="ui hidden divider"></div>

<?php get_footer(); ?>
