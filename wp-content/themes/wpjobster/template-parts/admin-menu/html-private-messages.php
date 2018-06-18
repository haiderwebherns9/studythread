<?php

function wpj_private_messages_html() {

	global $wpdb;

?>

<div id="usual2" class="usual">
	<ul>
		<li><a href="#tabs1"><?php _e('All Private Messages','wpjobster'); ?></a></li>
		<li><a href="#tabs2"><?php _e('Search User','wpjobster'); ?></a></li>

	</ul>
	<div id="tabs1">

		<?php

			$pm = new PrivateMessages();
			$vars1 = $pm->wpj_private_messages_vars1();
			$r = $vars1['r'];
			$next_pg = $vars1['next_pg'];
			$totalPages = $vars1['totalPages'];
			$previous_pg = $vars1['previous_pg'];
			$start = $vars1['start'];
			$start_me = $vars1['start_me'];
			$my_page = $vars1['my_page'];
			$end_me = $vars1['end_me'];
			$end = $vars1['end'];

			if(count($r) > 0):

		?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th width="25%"><?php _e('Sender','wpjobster'); ?></th>
					<th width="25%"><?php _e('Receiver','wpjobster'); ?></th>
					<th width="25%"><?php _e('Sent On','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				$i = 0;
				foreach($r as $row) {

					$sender                = get_userdata($row->initiator);
					$receiver              = get_userdata($row->user);

					if($i%2) $new_bg_color = '#E7E9F1';
					else $new_bg_color     = '#fff';

					$i++;

					echo '<tr style="background:'.$new_bg_color.'">';
					echo '<th>'.$sender->user_login.'</th>';
					echo '<th>'.$receiver->user_login.'</th>';
					echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';
					echo '<th>';

					if($row->show_to_source == 1 and $row->show_to_destination == 1)
					{
						if(isset($_GET['pj'])) echo '<a href="'.get_admin_url().'admin.php?page=privmess&pj='.$_GET['pj'].'&del_mess='.$row->id.'">'.__('Delete','wpjobster').'</a>';
					} else {

						_e('Message Deleted','wpjobster'); echo '<br/>';
						if(isset($_GET['pj'])) echo '<a href="'.get_admin_url().'admin.php?page=privmess&pj='.$_GET['pj'].'&p_del_mess='.$row->id.'">'.__('Permanent Delete','wpjobster').'</a>';

					}

					echo '</th>';
					echo '</tr>';

					echo '<tr style="background:'.$new_bg_color.'" id="mess_age_'.$row->id.'">';
					echo '<th colspan="5"><strong>' .  __("Message Content:",'wpjobster') . '</strong> '.$row->content.'</th>';
					echo '</tr>';
				}

				?>
			</tbody>
		</table>
		<?php

		if($start > 1)
			echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&pj='.$previous_pg.'"><< '.__('Previous','wpjobster').'</a> ';
		echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&pj='.$start_me.'"><<</a> ';

		for($i = $start; $i <= $end; $i ++) {
			if ($i == $my_page) {
				echo ''.$i.' | ';
			} else {

				echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&pj='.$i.'">'.$i.'</a> | ';

			}
		}

		if($totalPages > $my_page)
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&pj='.$end_me.'">>></a> ';
		echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&pj='.$next_pg.'">'.__('Next','wpjobster').' >></a> ';

		?>

		<?php else: ?>

			<div class="padd101">
				<?php _e('There are no private messages.','wpjobster'); ?>
			</div>

		<?php endif; ?>

	</div>

	<div id="tabs2">

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="privmess" name="page" />
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

			$pm = new PrivateMessages();
			$vars2 = $pm->wpj_private_messages_vars2();
			$r = $vars2['r'];
			$lastpage = $vars2['lastpage'];
			$pageno = $vars2['pageno'];


		if(count($r) > 0): ?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th width="25%"><?php _e('Sender','wpjobster'); ?></th>
					<th width="25%"><?php _e('Receiver','wpjobster'); ?></th>
					<th width="25%"><?php _e('Sent On','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				$i = 0;
				foreach($r as $row) {

					$sender                = ( $row->initiator ) ? get_userdata($row->initiator) : "";
					$receiver              = ( $row->user ) ? get_userdata($row->user) : "";
					$user_sender 		   = ($sender) ? $sender->user_login : "";
					$user_receiver         = ($receiver) ? $receiver->user_login : "";

					if($i%2) $new_bg_color = '#E7E9F1';
					else $new_bg_color     = '#fff';

					$i++;

					echo '<tr style="background:'.$new_bg_color.'">';
					echo '<th>'.$user_sender.'</th>';
					echo '<th>'.$user_receiver.'</th>';
					echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';
					echo '<th>';
					if($row->show_to_source == 1 and $row->show_to_destination == 1)
					{
						if(isset($_GET['pj'])) echo '<a href="'.get_admin_url().'admin.php?page=privmess&pj='.$_GET['pj'].'&del_mess='.$row->id.'">'.__('Delete','wpjobster').'</a>';
					} else {

						_e('Message Deleted','wpjobster'); echo '<br/>';
						if(isset($_GET['pj'])) echo '<a href="'.get_admin_url().'admin.php?page=privmess&pj='.$_GET['pj'].'&p_del_mess='.$row->id.'">'.__('Permanent Delete','wpjobster').'</a>';

					}
					echo '</th>';
					echo '</tr>';


					echo '<tr style="background:'.$new_bg_color.'" id="mess_age_'.$row->id.'">';
					echo '<th colspan="5"><strong>' .  __("Message Content:",'wpjobster') . '</strong> '.$row->content.'</th>';
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

	endif;

	if(isset($_GET['search_user'])){
		for($i=1;$i<=$lastpage;$i++){
			if($lastpage > 1){
				if($pageno == $i){
					echo $i." | ";
				}else{
					echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=privmess&active_tab=tabs2&search_user='.$_GET['search_user'].'&wpjobster_save2=Search&pj='.$i.'">'.$i.'</a> | ';
				}
			}
		}
	}

	?>

	</div>
</div>

<?php

}
