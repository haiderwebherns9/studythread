<?php

function wpj_transaction_messages_html() {

	global $wpdb;

?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('All Transaction Messages','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search User','wpjobster'); ?></a></li>

		</ul>
		<div id="tabs1">

			<?php

			$tm = new TransactionMessage();
			$vars = $tm->transaction_messages_vars();
			$r = $vars['r'];
			$totalPages = $vars['totalPages'];
			$start = $vars['start'];
			$my_page = $vars['my_page'];
			$end = $vars['end'];
			$previous_pg = $vars['previous_pg'];
			$next_pg = $vars['next_pg'];
			$page = $vars['page'];
			$start_me = $vars['start_me'];
			$end_me = $vars['end_me'];

			if(count($r) > 0): ?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Sender','wpjobster'); ?></th>
						<th><?php _e('Receiver','wpjobster'); ?></th>
						<th><?php _e('Job Owner','wpjobster'); ?></th>
						<th width="20%"><?php _e('Job Title','wpjobster'); ?></th>
						<th><?php _e('Sent On','wpjobster'); ?></th>
						<th width="25%"><?php _e('Options','wpjobster'); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$i = 0;
					foreach($r as $row) {

						$wpjobster_get_order_row_obj = wpjobster_get_order_row_obj($row->oid);

						$pst                         = get_post($wpjobster_get_order_row_obj->pid);
						if($pst) $post_author        = get_userdata($pst->post_author);
						$sender                      = get_userdata($row->uid);
						if($wpjobster_get_order_row_obj->uid == $row->uid){
							$receiver                = ( isset( $post_author ) && $post_author ) ? $post_author : "";
						}else{
							$receiver                =   get_userdata($wpjobster_get_order_row_obj->uid);
						}

						if($i%2) $new_bg_color = '#E7E9F1';
						else $new_bg_color = '#fff';

						$i++;

						echo '<tr style="background:'.$new_bg_color.'">';
							echo '<th>';
								if( isset( $sender ) && $sender ) echo $sender->user_login;
							echo '</th>';

							echo '<th>';
								if( isset( $receiver ) && $receiver ) echo $receiver->user_login;
							echo '</th>';

							echo '<th>';
								if( isset( $post_author ) && $post_author ) echo $post_author->user_login;
							echo '</th>';

							echo '<th>';
								if( isset( $pst ) && $pst ) echo '<a href="'.get_permalink( $wpjobster_get_order_row_obj->pid ).'">'.$pst->post_title.'</a>';
							echo '</th>';

							echo '<th>'.date( 'd-M-Y H:i:s', $row->datemade ).'</th>';

							echo '<th>';
							echo '<a href="'.get_admin_url().'admin.php?page=chatmess&pj='.$page.'&del_mess='.$row->id.'">'.__('Delete','wpjobster').'</a>';
							echo '</th>';
						echo '</tr>';

						echo '<tr style="background:'.$new_bg_color.'" id="mess_age_'.$row->id.'">';
							echo '<th colspan="6"><strong>' . __("Message Content:",'wpjobster') . ' </strong> '.$row->content.'</th>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>
			<?php

			if($start > 1)
				echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=chatmess&pj='.$previous_pg.'"><< '.__('Previous','wpjobster').'</a> ';
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=chatmess&pj='.$start_me.'"><<</a> ';

			for($i = $start; $i <= $end; $i ++) {
				if ($i == $my_page) {
					echo ''.$i.' | ';
				} else {

					echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=chatmess&pj='.$i.'">'.$i.'</a> | ';

				}
			}

			if($totalPages > $my_page)
				echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=chatmess&pj='.$end_me.'">>></a> ';
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=chatmess&pj='.$next_pg.'">'.__('Next','wpjobster').' >></a> ';

			?>

			<?php else: ?>

				<div class="padd101">
					<?php _e('There are no private messages.','wpjobster'); ?>
				</div>

			<?php endif; ?>

		</div>

		<div id="tabs2">

			<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
				<input type="hidden" value="chatmess" name="page" />
				<input type="hidden" value="tabs2" name="active_tab" />
				<table width="100%" class="sitemile-table">
					<tr>
						<td><?php _e('Search User','wpjobster'); ?></td>
						<td><input type="text" value="<?php if(isset($_GET['search_user'])) echo $_GET['search_user']; ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Search','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

			<?php

			if(isset($_GET['wpjobster_save2'])):

				$search_user = trim($_GET['search_user']);

				$user        = get_user_by('login', $search_user);
				$uid         = isset( $user->ID ) ? $user->ID : '';

				$s           = "select * from ".$wpdb->prefix."job_chatbox where uid='$uid' order by id desc";
				$r           = $wpdb->get_results($s);

			if(count($r) > 0): ?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Sender','wpjobster'); ?></th>
						<th><?php _e('Job Owner','wpjobster'); ?></th>
						<th width="20%"><?php _e('Job Title','wpjobster'); ?></th>
						<th><?php _e('Sent On','wpjobster'); ?></th>
						<th width="25%"><?php _e('Options','wpjobster'); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$i = 0;

					foreach( $r as $row ) {

						$wpjobster_get_order_row_obj = wpjobster_get_order_row_obj( $row->oid );

						$pst         = isset( $wpjobster_get_order_row_obj->pid ) ? get_post( $wpjobster_get_order_row_obj->pid ) : '';
						$post_author = isset( $pst->post_author ) ? get_userdata( $pst->post_author ) : '';
						$sender      = isset( $row->uid ) ? get_userdata( $row->uid ) : '';
						$pj          = isset( $_GET['pj'] ) ? $_GET['pj'] : '';


						if( $i%2 ) $new_bg_color = '#E7E9F1';
						else $new_bg_color       = '#fff';

						$i++;

						echo '<tr style="background:'.$new_bg_color.'">';
							echo '<th>';
								if( $sender ) echo $sender->user_login;
							echo '</th>';

							echo '<th>';
								if( $post_author ) echo $post_author->user_login;
							echo '</th>';

							echo '<th>';
								if( $pst ) echo '<a href="'.get_permalink($wpjobster_get_order_row_obj->pid).'">'.$pst->post_title.'</a>';
							echo '</th>';

							echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';

							echo '<th>';
								echo '<a href="'.get_admin_url().'admin.php?page=chatmess&pj='.$pj.'&del_mess='.$row->id.'">'.__('Delete','wpjobster').'</a>';
							echo '</th>';
						echo '</tr>';

						echo '<tr style="background:'.$new_bg_color.'" id="mess_age_'.$row->id.'">';
							echo '<th colspan="5"><strong>' . __(" Message Content:",'wpjobster') . '</strong> '.$row->content.'</th>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>

		<?php else: ?>

			<div class="padd101">
				<?php _e('There are no results for your search.','wpjobster'); ?>
			</div>

		<?php endif;
		endif; ?>
	</div>
</div>
<?php
}
