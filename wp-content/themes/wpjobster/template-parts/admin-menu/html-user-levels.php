<?php

function wpj_user_levels_html() { ?>


	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('All Users','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search','wpjobster'); ?></a></li>
			<li><a href="#tabs3"><?php _e('Level Settings','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">

			<?php

			$ul = new UserLevels();
			$vars = $ul->wpj_user_level_vars1();
			$r = $vars['r'];
			$lastpage = $vars['lastpage'];
			$pageno = $vars['pageno'];
			$nr = $vars['nr'];
			$arr = $vars['arr'];

			if($nr > 0) { ?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th width="15%"><?php _e('Username','wpjobster'); ?></th>
						<th width="20%"><?php _e('Email','wpjobster'); ?></th>
						<th width="20%"><?php _e('Date Registered','wpjobster'); ?></th>
						<th ><?php _e('Options','wpjobster'); ?></th>
					</tr>
				</thead>

				<script>
					var $ = jQuery;
					$(document).ready(function() {

						$('.update_btn*').click(function() {

							var id = $(this).attr('alt');

							if($('#user_level_3_' + id).is(':checked')) var user_level_3_ = '1'; else var user_level_3_ = '0';
							if($('#user_level_1_' + id).is(':checked')) var user_level_1_ = '1'; else var user_level_1_ = '0';
							if($('#user_level_2_' + id).is(':checked')) var user_level_2_ = '1'; else var user_level_2_ = '0';
							if($('#user_level_0_' + id).is(':checked')) var user_level_0_ = '1'; else var user_level_0_ = '0';

							$.ajax({
								url: "<?php echo get_admin_url(null, '/admin-ajax.php'); ?>",

								type:'POST',
								data:'action=update_level_user&uid=' + id +'&level3='+ user_level_3_ +'&level1='+ user_level_1_ +'&level2=' + user_level_2_+'&level0=' + user_level_0_ ,
								success: function (text) {
									//alert(text);
									alert("<?php _e('User Level Updated.','wpjobster'); ?>"  );
									return false;
						        }
						    });
						});
					});
				</script>

				<tbody>

					<?php
					foreach($r as $row) {

						$user = get_userdata($row->ID);

						echo '<tr style="">';
						echo '<th>'.$user->user_login.'</th>';
						echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->user_email) .'</th>';
						echo '<th>'.$row->user_registered .'</th>';

						echo '<th>';

						$user_level = wpjobster_get_user_level($row->ID);

						?>

						<?php _e('Level 0:','wpjobster'); ?> <input <?php if($user_level != "1" and $user_level != "2" and $user_level != "3") echo 'checked="checked"'; ?>
						type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_0_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>

						<?php _e('Level 1:','wpjobster'); ?> <input <?php if($user_level == "1") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_1_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
						<?php _e(' Level 2:','wpjobster'); ?> <input <?php if($user_level == "2") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_2_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
						<?php _e(' Level 3:','wpjobster'); ?> <input <?php if($user_level == "3") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_3_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" />

						<input type="button" value="<?php _e('Update','wpjobster'); ?>" class="update_btn" alt="<?php echo $row->ID; ?>" />

						<?php
						echo '</th>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>

			<?php
			for($i=1;$i<=$lastpage;$i++) {

				if($pageno == $i) echo $i." | ";
				else
					echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=user_levels&pj='.$i.'"
				>'.$i.'</a> | ';
			}
		} ?>

		</div>

		<div id="tabs2" >
			<?php
				$ul = new UserLevels();
				$ul->wpj_user_levels_update_user_level();
			?>
			<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
				<input type="hidden" value="user_levels" name="page" />
				<input type="hidden" value="tabs2" name="active_tab" />
				<table width="100%" class="sitemile-table">
					<tr>
						<td><?php _e('Search User','wpjobster'); ?></td>
						<td><input type="text" value="<?php echo WPJ_Form::get( 'search_user', '' ); ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Search','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

			<?php

			if(isset($_GET['wpjobster_save2'])) {

				$ul = new UserLevels();
				$vars = $ul->wpj_user_level_vars2();
				$r = $vars['r'];
				$lastpage = $vars['lastpage'];
				$pageno = $vars['pageno'];
				$rows_per_page = $vars['rows_per_page'];
				$nr = $vars['nr'];

				if($nr > 0) { ?>

				<table class="widefat post fixed" cellspacing="0">
					<thead>
						<tr>
							<th width="15%"><?php _e('Username','wpjobster'); ?></th>
							<th width="20%"><?php _e('Email','wpjobster'); ?></th>
							<th width="20%"><?php _e('Date Registered','wpjobster'); ?></th>

							<th ><?php _e('Options','wpjobster'); ?></th>
						</tr>
					</thead>

					<tbody>

						<?php
						foreach($r as $row) {

							$user = get_userdata($row->ID);
							echo '<tr style="">';
							echo '<th>'.$user->user_login.'</th>';
							echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->user_email) .'</th>';
							echo '<th>'.$row->user_registered .'</th>';

							echo '<th>';

							$user_level = wpjobster_get_user_level($row->ID);
        					//if(empty($user_level)) $user_level = "0";
							?>
							<?php _e('Level 0:','wpjobster'); ?> <input <?php if($user_level != "1" and $user_level != "2" and $user_level != "3") echo 'checked="checked"'; ?>
							type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_0_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e('Level 1:','wpjobster'); ?> <input <?php if($user_level == "1") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_1_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e(' Level 2:','wpjobster'); ?> <input <?php if($user_level == "2") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_2_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e(' Level 3:','wpjobster'); ?> <input <?php if($user_level == "3") echo 'checked="checked"'; ?> type="radio" name="user_level<?php echo $row->ID; ?>"  id="user_level_3_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" />

							<input type="button" value="<?php _e('Update','wpjobster'); ?>" class="update_btn" alt="<?php echo $row->ID; ?>" />

							<?php
							echo '</th>';

							echo '</tr>';
						}}
						?>
					</tbody>
				</table>
			<?php } ?>
		</div>

		<div id="tabs3" >
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=user_levels&active_tab=tabs3">
				<style>
					.user-level tr td input[type=text],
					.user-level tr td input[type=number],
					.user-level tr td select{
						height:28px;
						width: 80px;
					}
				</style>

				<table width="100%" class="sitemile-table user-level">
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('Set a value up to 3 max.'); ?></td>
						<td width="200"><?php _e('Default User Level:','wpjobster'); ?></td>
						<td><input type="text" size="3" name="wpjobster_default_level_nr" value="<?php echo get_option('wpjobster_default_level_nr'); ?>"/></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Do you want to automatically upgrade the levels?', 'wpjobster')); ?></td>
						<td><?php _e('Automatically upgrade levels:', 'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_auto_upgrade_user_level','yes'); ?></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Do you want to automatically downgrade the levels?', 'wpjobster')); ?></td>
						<td><?php _e('Automatically downgrade levels:', 'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_auto_downgrade_user_level','yes'); ?></td>
					</tr>
				</table>
				<br><br>

				<table width="100%" class="sitemile-table user-level">
					<tr>
						<td width="22"></td>
						<td width="200"></td>
						<td width="80"><?php _e('Enabled', 'wpjobster'); ?></td>
						<td width="80"><?php echo sprintf(__('Level %d', 'wpjobster'), 0); ?></td>
						<td width="80"><?php echo sprintf(__('Level %d', 'wpjobster'), 1); ?></td>
						<td width="80"><?php echo sprintf(__('Level %d', 'wpjobster'), 2); ?></td>
						<td width="80"><?php echo sprintf(__('Level %d', 'wpjobster'), 3); ?></td>
						<td width="80"></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Enable job packages', 'wpjobster')); ?></td>
						<td><?php _e('Job packages:', 'wpjobster'); ?></td>
						<td></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_get_level0_packages', 'no');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_get_level1_packages', 'no');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_get_level2_packages', 'no');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_get_level3_packages', 'no');?></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Enable Extra services', 'wpjobster')); ?></td>
						<td><?php _e('Extra services:', 'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_extra');?></td>
						<td><input type="text" size="5" name="wpjobster_get_level0_extras" value="<?php echo get_option('wpjobster_get_level0_extras'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level1_extras" value="<?php echo get_option('wpjobster_get_level1_extras'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level2_extras" value="<?php echo get_option('wpjobster_get_level2_extras'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level3_extras" value="<?php echo get_option('wpjobster_get_level3_extras'); ?>"/></td>
						<td><?php echo ' '.sprintf(__('max %d','wpjobster'), 10); ?></td>
					</tr>

					<?php if ( ! wpj_is_allowed( 'fast_del_multiples' ) ) { ?>
						<tr>
							<td colspan="8">
								<?php wpj_disabled_settings_notice( 'fast_del_multiples' ); ?>
							</td>
						</tr>
					<?php } ?>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Enable Extra fast delivery and <br> Max Fast Delivery Multiples for user level', 'wpjobster')); ?></td>
						<td><?php _e('Extra fast delivery:', 'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_extra_fast_delivery');?></td>
						<td><input class="<?php wpj_disabled_settings_class( 'fast_del_multiples' ); ?>" type="text" size="5" name="wpjobster_get_level0_fast_delivery_multiples" value="<?php echo get_option('wpjobster_get_level0_fast_delivery_multiples'); ?>"/></td>
						<td><input class="<?php wpj_disabled_settings_class( 'fast_del_multiples' ); ?>" type="text" size="5" name="wpjobster_get_level1_fast_delivery_multiples" value="<?php echo get_option('wpjobster_get_level1_fast_delivery_multiples'); ?>"/></td>
						<td><input class="<?php wpj_disabled_settings_class( 'fast_del_multiples' ); ?>" type="text" size="5" name="wpjobster_get_level2_fast_delivery_multiples" value="<?php echo get_option('wpjobster_get_level2_fast_delivery_multiples'); ?>"/></td>
						<td><input class="<?php wpj_disabled_settings_class( 'fast_del_multiples' ); ?>" type="text" size="5" name="wpjobster_get_level3_fast_delivery_multiples" value="<?php echo get_option('wpjobster_get_level3_fast_delivery_multiples'); ?>"/></td>
						<td>&nbsp;</td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet(__('Enable Additional revision and <br> Max Additional revision multiples for user level', 'wpjobster')); ?></td>
						<td><?php _e('Additional revision:', 'wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_extra_additional_revision');?></td>
						<td><input type="text" size="5" name="wpjobster_get_level0_add_rev_multiples" value="<?php echo get_option('wpjobster_get_level0_add_rev_multiples'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level1_add_rev_multiples" value="<?php echo get_option('wpjobster_get_level1_add_rev_multiples'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level2_add_rev_multiples" value="<?php echo get_option('wpjobster_get_level2_add_rev_multiples'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_get_level3_add_rev_multiples" value="<?php echo get_option('wpjobster_get_level3_add_rev_multiples'); ?>"/></td>
						<td>&nbsp;</td>
					</tr>

					<?php if ( ! wpj_is_allowed( 'job_multiples' ) ) { ?>
					<tr>
						<td colspan="8">
							<?php wpj_disabled_settings_notice( 'job_multiples' ); ?>
						</td>
					</tr>
					<?php } ?>

					<tbody class="<?php wpj_disabled_settings_class( 'job_multiples' ); ?>">
						<tr>
							<td valign=top><?php wpjobster_theme_bullet( __( 'Max Job Multiples for user level', 'wpjobster' ) ); ?></td>
							<td><?php echo __('Job Multiples:', 'wpjobster'); ?></td>
							<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_multiples');?></td>
							<td><input type="text" size="5" name="wpjobster_get_level0_jobmultiples" value="<?php echo get_option('wpjobster_get_level0_jobmultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level1_jobmultiples" value="<?php echo get_option('wpjobster_get_level1_jobmultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level2_jobmultiples" value="<?php echo get_option('wpjobster_get_level2_jobmultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level3_jobmultiples" value="<?php echo get_option('wpjobster_get_level3_jobmultiples'); ?>"/></td>
							<td></td>
						</tr>

						<tr>
							<td valign=top><?php wpjobster_theme_bullet( __( 'Job Multiples needs to be enabled.', 'wpjobster' ) ); ?></td>
							<td><?php echo __('Extra Multiples:', 'wpjobster'); ?></td>
							<td></td>

							<td><input type="text" size="5" name="wpjobster_get_level0_extramultiples" value="<?php echo get_option('wpjobster_get_level0_extramultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level1_extramultiples" value="<?php echo get_option('wpjobster_get_level1_extramultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level2_extramultiples" value="<?php echo get_option('wpjobster_get_level2_extramultiples'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level3_extramultiples" value="<?php echo get_option('wpjobster_get_level3_extramultiples'); ?>"/></td>
							<td></td>
						</tr>
					</tbody>

					<?php if ( ! wpj_is_allowed( 'custom_extras' ) ) { ?>
					<tr>
						<td colspan="8">
							<?php wpj_disabled_settings_notice( 'custom_extras' ); ?>
						</td>
					</tr>
					<?php } ?>

					<tbody class="<?php wpj_disabled_settings_class( 'custom_extras' ); ?>">
						<tr>
							<td valign=top><?php wpjobster_theme_bullet( __( 'Max total custom extras amount for user level', 'wpjobster' ) ); ?></td>
							<td><?php echo __('Custom Extras:', 'wpjobster'); ?></td>
							<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_custom_extras');?></td>
							<td><input type="text" size="5" name="wpjobster_get_level0_customextrasamount" value="<?php echo get_option('wpjobster_get_level0_customextrasamount'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level1_customextrasamount" value="<?php echo get_option('wpjobster_get_level1_customextrasamount'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level2_customextrasamount" value="<?php echo get_option('wpjobster_get_level2_customextrasamount'); ?>"/></td>
							<td><input type="text" size="5" name="wpjobster_get_level3_customextrasamount" value="<?php echo get_option('wpjobster_get_level3_customextrasamount'); ?>"/></td>
							<td><?php echo wpjobster_get_currency_classic(); ?></td>
						</tr>
					</tbody>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet( __( 'How often should we check again if user levels need to be updated?', 'wpjobster' ) ); ?></td>
						<td><?php echo __('Recheck period for upgrade:', 'wpjobster'); echo ' '; ?></td>
						<td></td>
						<td><input type="number" size="3" min="1" max="12" name="wpjobster_level0_recheck_interval"  value="<?php echo get_option( 'wpjobster_level0_recheck_interval'); ?>" /></td>
						<td><input type="number" size="3" min="1" max="12" name="wpjobster_level1_recheck_interval"  value="<?php echo get_option( 'wpjobster_level1_recheck_interval'); ?>" /></td>
						<td><input type="number" size="3" min="1" max="12" name="wpjobster_level2_recheck_interval"  value="<?php echo get_option( 'wpjobster_level2_recheck_interval'); ?>" /></td>
						<td></td>
						<td><?php _e("months","wpjobster");?></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet('The minumum amount the user should have sold in the previous month.'); ?></td>
						<td><?php _e('Required amount for upgrade:', 'wpjobster')?></td>
						<td></td>
						<td></td>
						<td><input type="text" size="5" name="wpjobster_level1_min" value="<?php echo get_option('wpjobster_level1_min'); ?>"/></td>
						<td><input type="text" size="5" name="wpjobster_level2_min" value="<?php echo get_option('wpjobster_level2_min'); ?>"/></td>
						<td></td>
						<td><?php echo wpjobster_get_currency_classic(); ?></td>
					</tr>

					<tr>
						<td valign=top><?php wpjobster_theme_bullet('Percentage of rating needed in order to upgrade the level.'); ?></td>
						<td><?php _e('Required ratings for upgrade:', 'wpjobster'); ?></td>
						<td></td>
						<td></td>
						<td><input type="number" size="3" min="0" max="100" name="wpjobster_level1_upgrade_rating"  value="<?php echo get_option( 'wpjobster_level1_upgrade_rating'); ?>" /></td>
						<td><input type="number" size="3" min="0" max="100" name="wpjobster_level2_upgrade_rating"  value="<?php echo get_option( 'wpjobster_level2_upgrade_rating'); ?>" /></td>
						<td></td>
						<td><?php echo '%'; ?></td>
					</tr>

					<tr>
						<td></td>
						<td>
							<input type="submit" class="button-secondary" name="wpjobster_update_user_level_setting" value="<?php _e('Save options','wpjobster'); ?>"/>
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>
	</div>

<?php

}
