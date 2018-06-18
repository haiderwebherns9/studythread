<?php

function wpj_tracking_html() {

	$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));

?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1" class="selected"><?php _e('Google Analytics','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Other Tracking','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">

			<form method="post" action="">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Enable Google Analytics:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_google_analytics'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td valign="top"><?php _e('Analytics Code:','wpjobster'); ?></td>
						<td><textarea rows="6" cols="80" name="wpjobster_analytics_code"><?php echo stripslashes(get_option('wpjobster_analytics_code')); ?></textarea></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

		</div>

		<div id="tabs2">

			<form method="post" action="">
				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Enable Other Tracking:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_other_tracking'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td valign="top"><?php _e('Other Tracking Code:','wpjobster'); ?></td>
						<td><textarea rows="6" cols="80" name="wpjobster_other_tracking_code"><?php echo stripslashes(get_option('wpjobster_other_tracking_code')); ?></textarea></td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>
		</div>
	</div>
<?php
}
