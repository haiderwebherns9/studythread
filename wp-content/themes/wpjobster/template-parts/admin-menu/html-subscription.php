<?php

function wpj_subscription_html() {

	$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));

?>


<style>
	.toplabel span{width:87px;float:left;margin-left:0px;}
	.subscription-form-settings select{ width:80px;vertical-align:top;}
	.subscription-form-settings td input[type=text]{height:28px;width:80px;vertical-align:top;}
	img.icon-url-img {max-width:23px;max-height:23px;}
</style>
<div id="usual2" class="usual">

	<ul>
		<li><a href="#tabs1"><?php _e('Subscriptions Settings', 'wpjobster'); ?></a></li>
		<li><a href="#tabs2"><?php _e('Eligibility Settings', 'wpjobster'); ?></a></li>
		<li><a href="#tabs3"><?php _e('Features Settings', 'wpjobster'); ?></a></li>
		<li><a href="#tabs4"><?php _e('Active subscriptions', 'wpjobster'); ?></a></li>
	</ul>

	<div id="tabs1" style="display: block; ">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=subscriptions&active_tab=tabs1">
			<?php if (!wpjobster_subscriptions_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_subscriptions_allowed()) { echo "wpjobster-disabled-settings"; } ?>">
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Check this option to enable the subscription.', 'wpjobster')); ?></td>
					<td valign="top"><?php _e('Enabled','wpjobster'); ?>:</td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_enabled', 'no'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Free user level.', 'wpjobster')); ?></td>
					<td valign="top"><?php echo sprintf(__('Subscription Level %d', 'wpjobster'), 0); ?>:</td>
					<td>
						<?php $level0_name = get_option('wpjobster_subscription_name_level0') ? get_option('wpjobster_subscription_name_level0') : __( 'Not Subscribed', 'wpjobster' ); ?>
						<label class='wpjobster-label-80' for='wpjobster_subscription_name_level0'><?php _e('Name', 'wpjobster'); ?>:</label>
						<input class='sbc_level_name' type='text' size="10" name="wpjobster_subscription_name_level0" id="wpjobster_subscription_name_level0" value='<?php echo $level0_name; ?>' /><br />
						<?php _e('Free', 'wpjobster'); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Fill the subscription amount for level 1.', 'wpjobster')); ?></td>
					<td width="150" valign="top"><?php echo sprintf(__('Subscription Level %d', 'wpjobster'), 1); ?>:</td>
					<td>
						<?php $level1_name = get_option('wpjobster_subscription_name_level1') ? get_option('wpjobster_subscription_name_level1') : __( 'Starter Plan', 'wpjobster' ); ?>
						<label class='wpjobster-label-80' for='wpjobster_subscription_name_level1'><?php _e('Name', 'wpjobster'); ?>:</label>
						<input class='sbc_level_name' type='text' size="10" name="wpjobster_subscription_name_level1" id="wpjobster_subscription_name_level1" value='<?php echo $level1_name; ?>' /><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_weekly_amount_level1'><?php _e('Weekly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_weekly_amount_level1"  id="wpjobster_subscription_weekly_amount_level1" value='<?php echo get_option('wpjobster_subscription_weekly_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="weekly_level1" name="weekly_level1" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_monthly_amount_level1'><?php _e('Monthly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_monthly_amount_level1" id="wpjobster_subscription_monthly_amount_level1" value='<?php echo get_option('wpjobster_subscription_monthly_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="monthly_level1" name="monthly_level1" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_quarterly_amount_level1'><?php _e('Quarterly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_quarterly_amount_level1" id="wpjobster_subscription_quarterly_amount_level1" value='<?php echo get_option('wpjobster_subscription_quarterly_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="quarterly_level1" name="quarterly_level1" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_yearly_amount_level1'><?php _e('Yearly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_yearly_amount_level1" id="wpjobster_subscription_yearly_amount_level1" value='<?php echo get_option('wpjobster_subscription_yearly_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="yearly_level1" name="yearly_level1" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_lifetime_amount_level1'><?php _e('Lifetime', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_lifetime_amount_level1" id="wpjobster_subscription_lifetime_amount_level1" value='<?php echo get_option('wpjobster_subscription_lifetime_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="lifetime_level1" name="lifetime_level1" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Check this option to enable the subscription.', 'wpjobster')); ?></td>
					<td valign="top"><?php echo sprintf(__('Enable Subscription Level %d', 'wpjobster'), 2); ?>:</td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_level_2_enabled', 'yes'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Fill the subscription amount for level 2.', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php echo sprintf(__('Subscription Level %d', 'wpjobster'), 2); ?>:</td>
					<td>
						<?php $level2_name = get_option('wpjobster_subscription_name_level2') ? get_option('wpjobster_subscription_name_level2') : __( 'Business Plan', 'wpjobster' ); ?>
						<label class='wpjobster-label-80' for='wpjobster_subscription_name_level2'><?php _e('Name', 'wpjobster'); ?>:</label>
						<input class='sbc_level_name' type='text' size="10" name="wpjobster_subscription_name_level2" id="wpjobster_subscription_name_level2" value='<?php echo $level2_name; ?>' /><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_weekly_amount_level2'><?php _e('Weekly', 'wpjobster'); ?>:</label>  <input type='text' size="10" name="wpjobster_subscription_weekly_amount_level2"  id="wpjobster_subscription_weekly_amount_level2" value='<?php echo get_option('wpjobster_subscription_weekly_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="weekly_level2" name="weekly_level2" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_monthly_amount_level2'><?php _e('Monthly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_monthly_amount_level2" id="wpjobster_subscription_monthly_amount_level2" value='<?php echo get_option('wpjobster_subscription_monthly_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="monthly_level2" name="monthly_level2" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_quarterly_amount_level2'><?php _e('Quarterly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_quarterly_amount_level2" id="wpjobster_subscription_quarterly_amount_level2" value='<?php echo get_option('wpjobster_subscription_quarterly_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="quarterly_level2" name="quarterly_level2" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_yearly_amount_level2'><?php _e('Yearly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_yearly_amount_level2" id="wpjobster_subscription_yearly_amount_level2" value='<?php echo get_option('wpjobster_subscription_yearly_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="yearly_level2" name="yearly_level2" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_lifetime_amount_level2'><?php _e('Lifetime', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_lifetime_amount_level2" id="wpjobster_subscription_lifetime_amount_level2" value='<?php echo get_option('wpjobster_subscription_lifetime_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="lifetime_level2" name="lifetime_level2" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Check this option to enable the subscription.', 'wpjobster')); ?></td>
					<td valign="top"><?php echo sprintf(__('Enable Subscription Level %d', 'wpjobster'), 3); ?>:</td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_level_3_enabled', 'yes'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Fill the subscription amount for level 3.', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php echo sprintf(__('Subscription Level %d', 'wpjobster'), 3); ?>:</td>
					<td>
						<?php $level3_name = get_option('wpjobster_subscription_name_level3') ? get_option('wpjobster_subscription_name_level3') : __( 'Professional Plan', 'wpjobster' ); ?>
						<label class='wpjobster-label-80' for='wpjobster_subscription_name_level3'><?php _e('Name', 'wpjobster'); ?>:</label>
						<input class='sbc_level_name' type='text' size="10" name="wpjobster_subscription_name_level3" id="wpjobster_subscription_name_level3" value='<?php echo $level3_name; ?>' /><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_weekly_amount_level3'><?php _e('Weekly', 'wpjobster'); ?>:</label>  <input type='text' size="10" name="wpjobster_subscription_weekly_amount_level3"  id="wpjobster_subscription_weekly_amount_level3" value='<?php echo get_option('wpjobster_subscription_weekly_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="weekly_level3" name="weekly_level3" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_monthly_amount_level3'><?php _e('Monthly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_monthly_amount_level3" id="wpjobster_subscription_monthly_amount_level3" value='<?php echo get_option('wpjobster_subscription_monthly_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="monthly_level3" name="monthly_level3" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_quarterly_amount_level3'><?php _e('Quarterly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_quarterly_amount_level3"  id="wpjobster_subscription_quarterly_amount_level3" value='<?php echo get_option('wpjobster_subscription_quarterly_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="quarterly_level3" name="quarterly_level3" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_yearly_amount_level3'><?php _e('Yearly', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_yearly_amount_level3" name="wpjobster_subscription_yearly_amount_level3" value='<?php echo get_option('wpjobster_subscription_yearly_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="yearly_level3" name="yearly_level3" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

						<label class='wpjobster-label-80' for='wpjobster_subscription_lifetime_amount_level3'><?php _e('Lifetime', 'wpjobster'); ?>:</label> <input type='text' size="10" name="wpjobster_subscription_lifetime_amount_level3" name="wpjobster_subscription_lifetime_amount_level3" value='<?php echo get_option('wpjobster_subscription_lifetime_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
						<input type="button" class="price_update" id="lifetime_level3" name="lifetime_level3" value="<?php _e('Send email', 'wpjobster'); ?>"><br />

					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><?php _e("The Send Email buttons from above let you manually send a notification about the price change only to the users affected by it.", "wpjobster"); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('How many days in advance to notify the user about the expiration of his subscription.', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Email notification before expiring subscription.', 'wpjobster'); ?>:</td>
					<td>
						<?php $arr_days=array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6);
						echo wpjobster_get_option_drop_down($arr_days, 'wpjobster_subscription_prior_notification');
						_e("days", "wpjobster");
						?>
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_subs1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
		<script type="text/javascript">
			var $ = jQuery;
			$(document).ready(function(){
				$(".price_update").on("click",function(){
					var id=this.id;
					//alert(id);
					$.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('url');?>/?_update_email_sendto_id='+id,
						dataType : 'text',
						success: function (text) {   //window.location.reload();
							json_data = JSON.parse(text);
							if(json_data.msg=='done'){
								alert("<?php _e("Email sent.");?>");
							}
						}
					});
				});
			});

			function checkImage (src, good, bad) {
				var img = new Image();
				img.onload = good;
				img.onerror = bad;
				img. src = src;
			}
			jQuery(document).ready(function(){
				jQuery(".icon-url").blur(function(){
					// alert("URL"+this.value);
					input_id = this.id;
					img_id = input_id + "_img";
					checkImage(this.value, function(){ jQuery("#"+img_id).attr("src",this.src );     }, function(){ console.log("The url you have provided is not valid image."); });
				});
			});
		</script>

	</div>

	<div id="tabs2" style="display: none; ">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=subscriptions&active_tab=tabs2">

			<?php if (!wpjobster_subscriptions_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table <?php if (!wpjobster_subscriptions_allowed()) { echo "wpjobster-disabled-settings"; } ?>">
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Select yes for this option to enable the eligibility.', 'wpjobster')); ?></td>
					<td valign="top"><?php _e('Enable Eligibility','wpjobster'); ?>:</td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_eligibility_enabled', 'no'); ?></td>
				</tr>

				<!--tr>
				<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('Commision fees for subscription level %d', 'wpjobster'), 1)); ?></td>
				<td width="150" valign="top"><?php _e('Fixed % for every level','wpjobster'); ?>:</td>
				<td>
				<input type='text' name="wpjobster_fees_for_subscriber_all_level"  id="wpjobster_fees_for_subscriber_all_level" value='<?php echo get_option('wpjobster_fees_for_subscriber_all_level') ?>' /><br />
				</td>
				</tr-->
				<!--tr>
				<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('User needs to have total sales more than eligibility amount for level %d', 'wpjobster'), 1)); ?></td>
				<td width="150"  valign="top"><?php echo sprintf(__('Level %d sales amount needed', 'wpjobster'), 0); ?>:</td>
				<td>
				<input type='text' name="wpjobster_subscription_eligibility_amount_level0"  id="wpjobster_subscription_eligibility_amount_level0" value='<?php echo get_option('wpjobster_subscription_eligibility_amount_level0') ?>' />
				</td>
				</tr-->
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('User needs to have total sales more than eligibility amount in order to reach level %d', 'wpjobster'), 1)); ?></td>
					<td width="150"  valign="top"><?php echo sprintf(__('Level %d sales amount needed', 'wpjobster'), 1); ?>:</td>
					<td>
						<input type='text' name="wpjobster_subscription_eligibility_amount_level1"  id="wpjobster_subscription_eligibility_amount_level1" value='<?php echo get_option('wpjobster_subscription_eligibility_amount_level1') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('User needs to have total sales more than eligibility amount in order to reach level %d', 'wpjobster'), 2)); ?></td>
					<td width="150"  valign="top"><?php echo sprintf(__('Level %d sales amount needed', 'wpjobster'), 2); ?>:</td>
					<td>
						<input type='text' name="wpjobster_subscription_eligibility_amount_level2"  id="wpjobster_subscription_eligibility_amount_level2" value='<?php echo get_option('wpjobster_subscription_eligibility_amount_level2') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('User needs to have total sales more than eligibility amount in order to reach level %d', 'wpjobster'), 3)); ?></td>
					<td width="150"  valign="top"><?php echo sprintf(__('Level %d sales amount needed', 'wpjobster'), 3); ?>:</td>
					<td>
						<input type='text' name="wpjobster_subscription_eligibility_amount_level3"  id="wpjobster_subscription_eligibility_amount_level3" value='<?php echo get_option('wpjobster_subscription_eligibility_amount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<!--tr>
				<td valign=top width="22"><?php wpjobster_theme_bullet(sprintf(__('Commision fees for subscription level %d', 'wpjobster'), 3)); ?></td>
				<td width="150"  valign="top"><?php echo sprintf(__('Subscription Level %d', 'wpjobster'), 3); ?>:</td>
				<td>
				<input type='text' name="wpjobster_fees_for_subscriber_level3"  id="wpjobster_fees_for_subscriber_level3" value='<?php echo get_option('wpjobster_fees_for_subscriber_level3') ?>' />
				</td></tr-->

				<tr>
					<td ></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_subs2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>

	<div id="tabs3" style="display:none; ">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=subscriptions&active_tab=tabs3">

			<?php if (!wpjobster_subscriptions_allowed()) { ?>

			<div class="wpjobster-update-nag wpjobster-notice">
				This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
			</div>

			<?php } ?>

			<table width="100%" class="sitemile-table subscription-form-settings <?php if (!wpjobster_subscriptions_allowed()) { echo "wpjobster-disabled-settings"; } ?>">
				<tr>
					<td ></td><td ></td>
					<td class='toplabel'>
						<span><?php _e('Enabled', 'wpjobster'); ?></span>
						<span><?php echo sprintf(__('Level %d', 'wpjobster'), 0); ?></span>
						<span><?php echo sprintf(__('Level %d', 'wpjobster'), 1); ?></span>
						<span><?php echo sprintf(__('Level %d', 'wpjobster'), 2); ?></span>
						<span><?php echo sprintf(__('Level %d', 'wpjobster'), 3); ?></span>
					</td>
				</tr>

				<?php do_action('add_features_options_for_subscription', $arr); ?>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Percentage fees will be charged from the seller for any sold job. This will override the default fees.', 'wpjobster')); ?></td>
					<td width="150" valign="top"><?php _e('Fees to be charged', 'wpjobster'); ?>:</td>
					<td nowrap >
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_fees_for_subscriber_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_fees_level0" id="wpjobster_subscription_fees_level0" value='<?php echo get_option('wpjobster_subscription_fees_level0') ?>' />
						<input type='text' name="wpjobster_subscription_fees_level1" id="wpjobster_subscription_fees_level1" value='<?php echo get_option('wpjobster_subscription_fees_level1') ?>' />
						<input type='text' name="wpjobster_subscription_fees_level2" id="wpjobster_subscription_fees_level2" value='<?php echo get_option('wpjobster_subscription_fees_level2') ?>' />
						<input type='text' name="wpjobster_subscription_fees_level3" id="wpjobster_subscription_fees_level3" value='<?php echo get_option('wpjobster_subscription_fees_level3') ?>' />
						%
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable or disable job packages', 'wpjobster')); ?></td>
					<td width="150" valign="top"><?php _e('Job Packages', 'wpjobster'); ?>:</td>
					<td>
						<input type='text' disabled>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_packages_level0', 'no'); ?>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_packages_level1', 'no'); ?>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_packages_level2', 'no'); ?>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_packages_level3', 'no'); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('No. of extras allowed for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Number of extras', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_noof_extras_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_noof_extras_level0" id="wpjobster_subscription_noof_extras_level0" value='<?php echo get_option('wpjobster_subscription_noof_extras_level0') ?>' />
						<input type='text' name="wpjobster_subscription_noof_extras_level1" id="wpjobster_subscription_noof_extras_level1" value='<?php echo get_option('wpjobster_subscription_noof_extras_level1') ?>' />
						<input type='text' name="wpjobster_subscription_noof_extras_level2" id="wpjobster_subscription_noof_extras_level2" value='<?php echo get_option('wpjobster_subscription_noof_extras_level2') ?>' />
						<input type='text' name="wpjobster_subscription_noof_extras_level3" id="wpjobster_subscription_noof_extras_level3" value='<?php echo get_option('wpjobster_subscription_noof_extras_level3') ?>' />

					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable extra fast delivery subscription and <br> No. of multiples allowed for each level of subscription ', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Extra fast delivery', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_ex_fast_delivery_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_fast_del_multiples_level0" id="wpjobster_subscription_fast_del_multiples_level0" value='<?php echo get_option('wpjobster_subscription_fast_del_multiples_level0') ?>' />
						<input type='text' name="wpjobster_subscription_fast_del_multiples_level1" id="wpjobster_subscription_fast_del_multiples_level1" value='<?php echo get_option('wpjobster_subscription_fast_del_multiples_level1') ?>' />
						<input type='text' name="wpjobster_subscription_fast_del_multiples_level2" id="wpjobster_subscription_fast_del_multiples_level2" value='<?php echo get_option('wpjobster_subscription_fast_del_multiples_level2') ?>' />
						<input type='text' name="wpjobster_subscription_fast_del_multiples_level3" id="wpjobster_subscription_fast_del_multiples_level3" value='<?php echo get_option('wpjobster_subscription_fast_del_multiples_level3') ?>' />
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable Additional revision subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Additional Revision', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_additional_revision_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_add_rev_multiples_level0" id="wpjobster_subscription_add_rev_multiples_level0" value='<?php echo get_option('wpjobster_subscription_add_rev_multiples_level0') ?>' />
						<input type='text' name="wpjobster_subscription_add_rev_multiples_level1" id="wpjobster_subscription_add_rev_multiples_level1" value='<?php echo get_option('wpjobster_subscription_add_rev_multiples_level1') ?>' />
						<input type='text' name="wpjobster_subscription_add_rev_multiples_level2" id="wpjobster_subscription_add_rev_multiples_level2" value='<?php echo get_option('wpjobster_subscription_add_rev_multiples_level2') ?>' />
						<input type='text' name="wpjobster_subscription_add_rev_multiples_level3" id="wpjobster_subscription_add_rev_multiples_level3" value='<?php echo get_option('wpjobster_subscription_add_rev_multiples_level3') ?>' />
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('No. of multiples allowed for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Max Job Multiples', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_job_multiples_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_job_multiples_level0" id="wpjobster_subscription_job_multiples_level0" value='<?php echo get_option('wpjobster_subscription_job_multiples_level0') ?>' />
						<input type='text' name="wpjobster_subscription_job_multiples_level1" id="wpjobster_subscription_job_multiples_level1" value='<?php echo get_option('wpjobster_subscription_job_multiples_level1') ?>' />
						<input type='text' name="wpjobster_subscription_job_multiples_level2" id="wpjobster_subscription_job_multiples_level2" value='<?php echo get_option('wpjobster_subscription_job_multiples_level2') ?>' />
						<input type='text' name="wpjobster_subscription_job_multiples_level3" id="wpjobster_subscription_job_multiples_level3" value='<?php echo get_option('wpjobster_subscription_job_multiples_level3') ?>' />

					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('No. of multiples allowed for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Max Extra Multiples', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_extra_multiples_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_extra_multiples_level0" id="wpjobster_subscription_extra_multiples_level0" value='<?php echo get_option('wpjobster_subscription_extra_multiples_level0') ?>' />
						<input type='text' name="wpjobster_subscription_extra_multiples_level1" id="wpjobster_subscription_extra_multiples_level1" value='<?php echo get_option('wpjobster_subscription_extra_multiples_level1') ?>' />
						<input type='text' name="wpjobster_subscription_extra_multiples_level2" id="wpjobster_subscription_extra_multiples_level2" value='<?php echo get_option('wpjobster_subscription_extra_multiples_level2') ?>' />
						<input type='text' name="wpjobster_subscription_extra_multiples_level3" id="wpjobster_subscription_extra_multiples_level3" value='<?php echo get_option('wpjobster_subscription_extra_multiples_level3') ?>' />

					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Max total custom extras amount for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Max total custom extras amount', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_custom_extras_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_max_customextrasamount_level0" id="wpjobster_subscription_max_customextrasamount_level0" value='<?php echo get_option('wpjobster_subscription_max_customextrasamount_level0') ?>' />
						<input type='text' name="wpjobster_subscription_max_customextrasamount_level1" id="wpjobster_subscription_max_customextrasamount_level1" value='<?php echo get_option('wpjobster_subscription_max_customextrasamount_level1') ?>' />
						<input type='text' name="wpjobster_subscription_max_customextrasamount_level2" id="wpjobster_subscription_max_customextrasamount_level2" value='<?php echo get_option('wpjobster_subscription_max_customextrasamount_level2') ?>' />
						<input type='text' name="wpjobster_subscription_max_customextrasamount_level3" id="wpjobster_subscription_max_customextrasamount_level3" value='<?php echo get_option('wpjobster_subscription_max_customextrasamount_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Max job price for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Max job price', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_max_job_price_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_max_job_price_level0" id="wpjobster_subscription_max_job_price_level0" value='<?php echo get_option('wpjobster_subscription_max_job_price_level0') ?>' />
						<input type='text' name="wpjobster_subscription_max_job_price_level1" id="wpjobster_subscription_max_job_price_level1" value='<?php echo get_option('wpjobster_subscription_max_job_price_level1') ?>' />
						<input type='text' name="wpjobster_subscription_max_job_price_level2" id="wpjobster_subscription_max_job_price_level2" value='<?php echo get_option('wpjobster_subscription_max_job_price_level2') ?>' />
						<input type='text' name="wpjobster_subscription_max_job_price_level3" id="wpjobster_subscription_max_job_price_level3" value='<?php echo get_option('wpjobster_subscription_max_job_price_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Maximum price for extra allowed for each level of subscription', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Max extra price', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_max_extra_price_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_max_extra_price_level0" id="wpjobster_subscription_max_extra_price_level0" value='<?php echo get_option('wpjobster_subscription_max_extra_price_level0') ?>' />
						<input type='text' name="wpjobster_subscription_max_extra_price_level1" id="wpjobster_subscription_max_extra_price_level1" value='<?php echo get_option('wpjobster_subscription_max_extra_price_level1') ?>' />
						<input type='text' name="wpjobster_subscription_max_extra_price_level2" id="wpjobster_subscription_max_extra_price_level2" value='<?php echo get_option('wpjobster_subscription_max_extra_price_level2') ?>' />
						<input type='text' name="wpjobster_subscription_max_extra_price_level3" id="wpjobster_subscription_max_extra_price_level3" value='<?php echo get_option('wpjobster_subscription_max_extra_price_level3') ?>' />
						<?php echo wpjobster_get_currency_classic(); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Label displayed on hover for each level icon', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Profile label', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_profile_label_enabled', 'no'); ?>
						<input type='text' name="wpjobster_subscription_profile_label_level0" id="wpjobster_subscription_profile_label_level0" value='<?php echo get_option('wpjobster_subscription_profile_label_level0') ?>' />
						<input type='text' name="wpjobster_subscription_profile_label_level1" id="wpjobster_subscription_profile_label_level1" value='<?php echo get_option('wpjobster_subscription_profile_label_level1') ?>' />
						<input type='text' name="wpjobster_subscription_profile_label_level2" id="wpjobster_subscription_profile_label_level2" value='<?php echo get_option('wpjobster_subscription_profile_label_level2') ?>' />
						<input type='text' name="wpjobster_subscription_profile_label_level3" id="wpjobster_subscription_profile_label_level3" value='<?php echo get_option('wpjobster_subscription_profile_label_level3') ?>' />
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Icon URL for each level', 'wpjobster')); ?></td>
					<td width="150"  valign="top"><?php _e('Icon URL', 'wpjobster'); ?>:</td>
					<td>
						<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_subscription_icon_url_enabled', 'no'); ?>
						<input type='text' class="icon-url" name="wpjobster_subscription_icon_url_level0" id="wpjobster_subscription_icon_url_level0"value='<?php echo get_option('wpjobster_subscription_icon_url_level0') ?>' />
						<input type='text' class="icon-url" name="wpjobster_subscription_icon_url_level1" id="wpjobster_subscription_icon_url_level1"value='<?php echo get_option('wpjobster_subscription_icon_url_level1') ?>' />
						<input type='text' class="icon-url" name="wpjobster_subscription_icon_url_level2" id="wpjobster_subscription_icon_url_level2" value='<?php echo get_option('wpjobster_subscription_icon_url_level2') ?>' />
						<input type='text' class="icon-url" name="wpjobster_subscription_icon_url_level3" id="wpjobster_subscription_icon_url_level3" value='<?php echo get_option('wpjobster_subscription_icon_url_level3') ?>' />
						<img src="<?php echo get_option('wpjobster_subscription_icon_url_level0') ?>" class="icon-url-img" id="wpjobster_subscription_icon_url_level0_img" />
						<img src="<?php echo get_option('wpjobster_subscription_icon_url_level1') ?>" class="icon-url-img" id="wpjobster_subscription_icon_url_level1_img" />
						<img src="<?php echo get_option('wpjobster_subscription_icon_url_level2') ?>" class="icon-url-img" id="wpjobster_subscription_icon_url_level2_img" />
						<img src="<?php echo get_option('wpjobster_subscription_icon_url_level3') ?>" class="icon-url-img" id="wpjobster_subscription_icon_url_level3_img" />
					</td>
				</tr>
				<tr>
					<td ></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save_subs3" value="<?php _e('Save Options', 'wpjobster'); ?>"/></td>
				</tr>
			</table>
		</form>
	</div>

	<div id="tabs4">
		<?php
		global $wpdb; $prefix = $wpdb->prefix;
		$subscription_query = "
			SELECT DISTINCT *
			FROM ".$prefix."job_subscriptions
			ORDER BY sub_start_date DESC
		";
		$query_result = $wpdb->get_results($subscription_query);
		?>

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="subscriptions" name="page" />
			<input type="hidden" value="tabs4" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search by username or user ID','wpjobster'); ?></td>
					<td>
						<input type="text" value="<?php echo (isset($_GET['name'])) ? $_GET['name'] : WPJ_Form::get( 'search_user', '' ); ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_subs_search" value="<?php _e('Search','wpjobster'); ?>"/>
					</td>
				</tr>
			</table>
		</form>

		<?php
		if( isset($_GET['wpjobster_subs_search']) && isset($_GET['search_user']) && $_GET['search_user'] != "" ){
			global $wpdb;
			$rows_per_page = 10;
			if(isset($_GET['pj'])) $pageno = $_GET['pj'];
			else $pageno = 1;
			$usrlg = trim($_GET['search_user']);
			if ( ctype_digit( $usrlg ) || is_int( $usrlg ) ) {
				$usrid = $_GET['search_user'];
			}else {
				$usrid = false;
			}
			if ( ! $usrid ) {
				$sql  = "select ID from $wpdb->users where user_login='$usrlg'";
				$rqrq   = $wpdb->get_results($sql);
				if(count($rqrq) > 0) $usrid = $rqrq[0]->ID;
				else $usrid = 0;
			}
			$s1     = "select user_id from ".$wpdb->prefix."job_subscriptions where user_id='$usrid' order by sub_start_date desc ";
			$s      = "select * from ".$wpdb->prefix."job_subscriptions where user_id='$usrid' order by sub_start_date desc ";
			$limit    = ' LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
			$r        = $wpdb->get_results($s1);
			$nr       = count($r);
			$lastpage = ceil($nr/$rows_per_page);
			$r        = $wpdb->get_results($s.$limit);
			if(count($r) > 0){ ?>

				<table class="widefat post fixed" cellspacing="0">
					<thead>
						<tr>
							<th><?php _e('No.','wpjobster'); ?></th>
							<th><?php _e('Username','wpjobster'); ?></th>
							<th><?php _e('Level','wpjobster'); ?></th>
							<th><?php _e('Type','wpjobster'); ?></th>
							<th><?php _e('Amount','wpjobster'); ?></th>
							<th><?php _e('Date','wpjobster'); ?></th>
							<th><?php _e('Next billing date','wpjobster'); ?></th>
							<th><?php _e('Next subscription level','wpjobster'); ?></th>
							<th><?php _e('Next subscription type','wpjobster'); ?></th>
							<th><?php _e('Next subscription amount','wpjobster'); ?></th>
							<th><?php _e('Cancel subscription','wpjobster'); ?></th>
						</tr>
					</thead>

				<tbody>
				<?php
				$i = 1;
				foreach($r as $sub){
					$user_id                  = $sub->user_id;
					$subscription_level       = $sub->subscription_level;
					$subscription_type        = $sub->subscription_type;
					$subscription_amount      = $sub->subscription_amount;
					$sub_start_date           = $sub->sub_start_date;
					$next_billing_date        = $sub->next_billing_date;
					$next_subscription_level  = $sub->next_subscription_level;
					$next_subscription_type   = $sub->next_subscription_type;
					$next_subscription_amount = $sub->next_subscription_amount;

					if( $next_billing_date == 0 ){
						$next_billing_date = date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $next_billing_date);
					}else{
						$next_billing_date = 'N/A';
					}

					$user = get_user_by( 'id', $user_id );
					echo '<tr>';
						echo '<th>'.$i.'</th>';
						echo '<th>'.$user->user_login.'</th>';
						echo '<th>'.$subscription_level.'</th>';
						echo '<th>'.$subscription_type.'</th>';
						echo '<th>'.$subscription_amount.'</th>';
						echo '<th>'.date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $sub_start_date).'</th>';
						echo '<th>'.$next_billing_date.'</th>';
						echo '<th>'.$next_subscription_level.'</th>';
						echo '<th>'.$next_subscription_type.'</th>';
						echo '<th>'.$next_subscription_amount.'</th>';
						echo '<th><a href="" onclick="return cancel_this_subscription('.$user_id.')"><img src="'.esc_url(get_template_directory_uri()).'/images/delete_icon.png" border="0" /></a></th>';
					echo '</tr>';
				$i++; } ?>
				</tbody>
				</table>
			<?php }else{ ?>
				<div class="all-users"><?php _e('There are no subscription yet for this user.','wpjobster'); ?></div>
			<?php }
			for($i=1;$i<=$lastpage;$i++){
				if($lastpage > 1){
					if($pageno == $i)
						echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=subscriptions&active_tab=tabs4&search_user='.$_GET['search_user'].'&wpjobster_subs_search=Search&name='.$user->user_login.'&search_id&pj='.$i.'">'.$i.'</a> | ';
				}
			}
			?>

		<?php }else{
			$page = ! empty( $_GET['pj'] ) ? (int) $_GET['pj'] : 1;
			$total = count( $query_result );
			$limit = 10;
			$totalPages = ceil( $total/ $limit );
			$page = max($page, 1);
			$page = min($page, $totalPages);
			$offset = ($page - 1) * $limit;
			if( $offset < 0 ) $offset = 0;
			$query_result = array_slice( $query_result, $offset, $limit );
			?>
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('No.','wpjobster'); ?></th>
						<th><?php _e('Username','wpjobster'); ?></th>
						<th><?php _e('Level','wpjobster'); ?></th>
						<th><?php _e('Type','wpjobster'); ?></th>
						<th><?php _e('Amount','wpjobster'); ?></th>
						<th><?php _e('Date','wpjobster'); ?></th>
						<th><?php _e('Next billing date','wpjobster'); ?></th>
						<th><?php _e('Next subscription level','wpjobster'); ?></th>
						<th><?php _e('Next subscription type','wpjobster'); ?></th>
						<th><?php _e('Next subscription amount','wpjobster'); ?></th>
						<th><?php _e('Cancel subscription','wpjobster'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach($query_result as $sub){
						$user_id                  = $sub->user_id;
						$subscription_level       = $sub->subscription_level;
						$subscription_type        = $sub->subscription_type;
						$subscription_amount      = $sub->subscription_amount;
						$sub_start_date           = $sub->sub_start_date;
						$next_billing_date        = $sub->next_billing_date;
						$next_subscription_level  = $sub->next_subscription_level;
						$next_subscription_type   = $sub->next_subscription_type;
						$next_subscription_amount = $sub->next_subscription_amount;

						if( $next_billing_date == 0 ){
							$next_billing_date = date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $next_billing_date);
						}else{
							$next_billing_date = 'N/A';
						}

						$user = get_user_by( 'id', $user_id );
						echo '<tr>';
							echo '<th>'.$i.'</th>';
							echo '<th>'.$user->user_login.'</th>';
							echo '<th>'.$subscription_level.'</th>';
							echo '<th>'.$subscription_type.'</th>';
							echo '<th>'.$subscription_amount.'</th>';
							echo '<th>'.date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $sub_start_date).'</th>';
							echo '<th>'.$next_billing_date.'</th>';
							echo '<th>'.$next_subscription_level.'</th>';
							echo '<th>'.$next_subscription_type.'</th>';
							echo '<th>'.$next_subscription_amount.'</th>';
							echo '<th><a href="" onclick="return cancel_this_subscription('.$user_id.')"><img src="'.esc_url(get_template_directory_uri()).'/images/delete_icon.png" border="0" /></a></th>';
						echo '</tr>';
					$i++; } ?>
				</tbody>
			</table>

			<script type="text/javascript">
				var $ = jQuery;
				function cancel_this_subscription(id){
					$.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('url');?>/index.php?_ad_cancel_subscription='+id,
						dataType : 'text',
						success: function (text) { window.location.href = '/wp-admin/admin.php?page=subscriptions&active_tab=tabs4';
						return false;
						}
					});
					return false;
				}
			</script>

			<?php
			for($i=1;$i<=$totalPages;$i++){
				if($totalPages > 1){
					if($page == $i){
						echo $i." | ";
					}else{
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=subscriptions&active_tab=tabs4&pj='.$i.'">'.$i.'</a> | ';
					}
				}
			}
			?>

		<?php } ?>

	</div>

<?php

}
