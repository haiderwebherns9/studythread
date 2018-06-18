<?php

function wpjobster_user_balances() {

	$id_icon      = 'icon-options-general-bal';
	$ttl_of_stuff = 'Jobster - '.__('User Balances','wpjobster');

//------------------------------------------------------

	echo '<div class="wrap">';
	echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
	echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

	?>

		<div id="usual2" class="usual">

			<ul>
				<li><a href="#tabs1"><?php _e('All Users Balances','wpjobster'); ?></a></li>
				<li><a href="#tabs2"><?php _e('Search','wpjobster'); ?></a></li>
			</ul>

			<div id="tabs1">

				<?php

				$rows_per_page = 10;

				if(isset($_GET['pj'])) $pageno = $_GET['pj'];
				else $pageno = 1;

				global $wpdb;

				$s1    = "select ID from ".$wpdb->users." order by user_login asc ";
				$s     = "select * from ".$wpdb->users." order by user_login asc ";
				$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

				$r        = $wpdb->get_results($s1); $nr = count($r);
				$lastpage = ceil($nr/$rows_per_page);

				$r = $wpdb->get_results($s.$limit);

				if($nr > 0) {

				?>

				<table class="widefat post fixed" cellspacing="0">
					<thead>
						<tr>
							<th width="15%"><?php _e('Username','wpjobster'); ?></th>
							<th width="20%"><?php _e('Email','wpjobster'); ?></th>
							<th width="20%"><?php _e('Date Registered','wpjobster'); ?></th>
							<th width="13%" ><?php _e('Cash Balance','wpjobster'); ?></th>
							<th ><?php _e('Options','wpjobster'); ?></th>
						</tr>
					</thead>

					<?php if (!is_demo_admin()) { ?>
					<script>
						var $ = jQuery;
						$(document).ready(function() {

							$('.update_btn*').click(function() {

								var id               = $(this).attr('alt');
								var increase_credits = $('#increase_credits' + id).val();
								var decrease_credits = $('#decrease_credits' + id).val();

								$.ajax({
									url: "<?php echo get_admin_url(null, '/admin-ajax.php'); ?>",
									type:'POST',
									data:'action=update_users_balance&increase_credits='+ increase_credits +'&uid='+ id +'&decrease_credits=' + decrease_credits ,
									success: function (text) {

										alert("<?php _e('User balance updated.','wpjobster'); ?>");

										text = text.slice(0, -1);
										$("#money" + id).html(text);
										$('#increase_credits' + id).val("");
										$('#decrease_credits' + id).val("");

										return false;
									}
								});

							});

							$('.update_btn2*').click(function() {

								var id               = $(this).attr('alt');
								var increase_credits = $('#increase_credits2' + id).val();
								var decrease_credits = $('#decrease_credits2' + id).val();

								$.ajax({
									url: "<?php echo get_admin_url(null, '/admin-ajax.php'); ?>",
									type:'POST',
									data:'action=update_users_balance&increase_credits='+ increase_credits +'&uid='+ id +'&decrease_credits=' + decrease_credits ,
									success: function (text) {

										alert("<?php _e('User balance updated.','wpjobster'); ?>");

										text = text.slice(0, -1);
										$("#money2" + id).html(text);
										$('#increase_credits2' + id).val("");
										$('#decrease_credits2' + id).val("");

										return false;
									}
								});

							});

						});

					</script>
					<?php } ?>

					<tbody>

						<?php

						foreach($r as $row) {
							$user = get_userdata($row->ID);

							echo '<tr style="">';
							echo '<th>'.$user->user_login.'</th>';
							echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->user_email) .'</th>';
							echo '<th>'.$row->user_registered .'</th>';
							echo '<th class=""><span id="money'.$row->ID.'">'.wpjobster_get_show_price_classic(wpjobster_get_credits($row->ID),2).'</span></th>';
							echo '<th>';
							?>

							<?php _e('Increase Cash:','wpjobster'); ?> &nbsp; <input type="text" size="4" id="increase_credits<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /> <?php echo wpjobster_get_currency_classic(); ?><br/>
							<?php _e(' Decrease Cash:','wpjobster'); ?> <input type="text" size="4" id="decrease_credits<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /> <?php echo wpjobster_get_currency_classic(); ?>

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
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=User-Balances&pj='.$i.'"
					>'.$i.'</a> | ';

				} }
				?>

			</div>

			<div id="tabs2" >

				<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
					<input type="hidden" value="User-Balances" name="page" />
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

					global $wpdb;
					$usr = trim($_GET['search_user']);
					$rows_per_page = 10;

					if(isset($_GET['pj'])) $pageno = $_GET['pj'];
					else $pageno = 1;

					global $wpdb;

					$s1 = "select ID from ".$wpdb->users." where user_login like '%$usr%' order by user_login asc ";
					$s = "select * from ".$wpdb->users." where user_login like '%$usr%' order by user_login asc ";
					$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

					$r = $wpdb->get_results($s1); $nr = count($r);
					$lastpage      = ceil($nr/$rows_per_page);

					$r = $wpdb->get_results($s.$limit);

					if($nr > 0) { ?>

						<table class="widefat post fixed" cellspacing="0">
							<thead>
								<tr>
									<th width="15%"><?php _e('Username','wpjobster'); ?></th>
									<th width="20%"><?php _e('Email','wpjobster'); ?></th>
									<th width="20%"><?php _e('Date Registered','wpjobster'); ?></th>
									<th width="13%" ><?php _e('Cash Balance','wpjobster'); ?></th>
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
									echo '<th class="'.$cl.'"><span id="money2'.$row->ID.'">'.$sign.wpjobster_get_show_price_classic(wpjobster_get_credits($row->ID),2).'</span></th>';
									echo '<th>';
									?>

									<?php _e('Increase Cash:'); ?> <input type="text" size="4" id="increase_credits2<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /> <?php echo wpjobster_get_currency_classic(); ?><br/>
									<?php _e('Decrease Cash:'); ?> <input type="text" size="4" id="decrease_credits2<?php echo $row->ID; ?>" rel="<?php echo $row->ID; ?>" /> <?php echo wpjobster_get_currency_classic(); ?>

									<input type="button" value="<?php _e('Update','wpjobster'); ?>" class="update_btn2" alt="<?php echo $row->ID; ?>" />

									<?php
									echo '</th>';

									echo '</tr>';
								}
					} ?>

							</tbody>
						</table>

						<?php
						for ( $i=1; $i<=$lastpage; $i++ ) {
							if ( $pageno == $i ) {
								echo $i." | ";
							} else {
								echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=User-Balances&active_tab=tabs2&search_user='.$_GET['search_user'].'&wpjobster_save2=Search&pj='.$i.'">'.$i.'</a> | ';
							}
						}
				} ?>
			</div>
		</div>
	<?php
	echo '</div>';

}
