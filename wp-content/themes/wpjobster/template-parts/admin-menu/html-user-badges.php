<?php

function wpj_user_badges_html() {
	$user_badges = new UserBadges();
	$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));
?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('All Users','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search','wpjobster'); ?></a></li>
			<li><a href="#tabs3"><?php _e('Settings','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">

			<?php

			$vars = $user_badges->wpj_user_badges_pj();
			$nr = $vars['nr'];
			$r = $vars['results'];
			$lastpage = $vars['lastpage'];
			$pageno = $vars['pageno'];

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

								if($('#badge_level_0_' + id).is(':checked')) var badge_level_0_ = '1'; else var badge_level_0_ = '0';
								if($('#badge_level_1_' + id).is(':checked')) var badge_level_1_ = '1'; else var badge_level_1_ = '0';
								if($('#badge_level_2_' + id).is(':checked')) var badge_level_2_ = '1'; else var badge_level_2_ = '0';

								$.ajax({
									url: "<?php echo get_admin_url(null, '/admin-ajax.php'); ?>",
									type:'POST',
									data:'action=update_badge_user&uid=' + id +'&level0='+ badge_level_0_ +'&level1='+ badge_level_1_ +'&level2=' + badge_level_2_ ,
									success: function (text) {

										alert("<?php _e('User Badge Updated.','wpjobster'); ?>"  );
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

							$user_badge = get_user_meta($row->ID,'user_badge',true);
							if(empty($user_badge)) $user_badge = "0";

							?>

							<?php _e('No Badge:','wpjobster'); ?> &nbsp; &nbsp; &nbsp;&nbsp; <input <?php if($user_badge == "0") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_0_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e('Badge Level 1:','wpjobster'); ?> <input <?php if($user_badge == "1") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_1_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e(' Badge Level 2:','wpjobster'); ?> <input <?php if($user_badge == "2") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_2_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" />

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
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=user_badges&pj='.$i.'"
					>'.$i.'</a> | ';

				}
			}
			?>

		</div>

		<div id="tabs2" >

			<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
				<input type="hidden" value="user_badges" name="page" />
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

				$vars = $user_badges->wpj_user_badges_wpjobster_save2();
				$nr = $vars['nr'];
				$r = $vars['results'];

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

						foreach($r as $row)
						{
							$user = get_userdata($row->ID);

							echo '<tr style="">';
							echo '<th>'.$user->user_login.'</th>';
							echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->user_email) .'</th>';
							echo '<th>'.$row->user_registered .'</th>';

							echo '<th>';

							$user_badge = get_user_meta($row->ID,'user_badge',true);
							if(empty($user_badge)) $user_badge = "0";

							?>

							<?php _e('No Badge:','wpjobster'); ?> &nbsp; &nbsp; &nbsp; &nbsp; <input <?php if($user_badge == "0") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_0_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e('Badge Level 1:','wpjobster'); ?> <input <?php if($user_badge == "1") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_1_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /><br/>
							<?php _e(' Badge Level 2:','wpjobster'); ?> <input <?php if($user_badge == "2") echo 'checked="checked"'; ?> type="radio" name="badge_level<?php echo $row->ID; ?>"  id="badge_level_2_<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" />

							<input type="button" value="<?php _e('Update','wpjobster'); ?>" class="update_btn" alt="<?php echo $row->ID; ?>" />

							<?php
							echo '</th>';

							echo '</tr>';
						}
					}

					?>

				</tbody>
			</table>
		<?php } ?>
		</div>

		<div id="tabs3">

			<form class="rating" id="rating"  action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=user_badges&active_tab=tabs3" method="POST">
				<table class="sitemile-table" width="100%">
					<tbody>

						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(__('Select Yes if you want to let the users buy badges.', 'wpjobster')); ?></td>
							<td width="21%"><?php _e('Enable badges sale:', 'wpjobster'); ?></td>
							<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_badges_sale', 'no' ); ?></td>
						</tr>


						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
							<td ><?php _e('Price for first badge:','wpjobster'); ?></td>
							<td><input type="number" size="45" name="wpjobster_first_badge_price" value="<?php echo get_option('wpjobster_first_badge_price'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
						</tr>
						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
							<td ><?php _e('Price for second badge:','wpjobster'); ?></td>
							<td><input type="number" size="45" name="wpjobster_second_badge_price" value="<?php echo get_option('wpjobster_second_badge_price'); ?>"/> <?php echo wpjobster_get_currency_classic(); ?></td>
						</tr>

						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>

							<td ><?php _e('Min rating for second badge (min 0, max 5):','wpjobster'); ?></td>

							<td><input type="text" size="45" name="wpjobster_min_rating_badge_2" value="<?php echo get_option('wpjobster_min_rating_badge_2'); ?>"/></td>
						</tr>

						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>

							<td ><?php _e('Min number of ratings:','wpjobster'); ?></td>

							<td><input type="number" size="45" name="wpjobster_min_rating_number_badge_2" value="<?php echo get_option('wpjobster_min_rating_number_badge_2'); ?>"/></td>
						</tr>

						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(__('20px x 20px PNG', 'wpjobster')); ?></td>
							<td ><?php _e('Custom icon for second badge:','wpjobster'); ?></td>
							<td><input type="text" size="45" name="wpjobster_second_badge_icon" value="<?php echo get_option('wpjobster_second_badge_icon'); ?>"/></td>
						</tr>

						<tr>
							<td></td>
							<td><input name="wpjobster_save_badge" value="Save Options" type="submit"></td>
							<td></td>
						</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>
<?php

}
