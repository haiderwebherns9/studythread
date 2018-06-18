<?php
function wpj_reviews_html() {
	global $wpdb;
?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('All User Reviews','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search User','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">

			<?php
			$ur = new UserReviews();
			$vars = $ur->wpj_user_reviews_vars();
			$r = $vars['r'];
			$lastpage = $vars['lastpage'];
			$pageno = $vars['pageno'];
			if(count($r) > 0):
				?>
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('User','wpjobster'); ?></th>
						<th><?php _e('Price','wpjobster'); ?></th>
						<th><?php _e('Job','wpjobster'); ?></th>
						<th><?php _e('Rating Type','wpjobster'); ?></th>
						<th><?php _e('Description','wpjobster'); ?></th>
						<th><?php _e('Awarded On','wpjobster'); ?></th>
						<th><?php _e('Options','wpjobster'); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$reviews = new UserReviews();
					$reviews->wpj_get_reviews_wpdb();
					?>
				</tbody>

			</table>
			<?php for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i) echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=usrrev&active_tab=tabs1&pj='.$i.'"
					>'.$i.'</a> | ';
				} }else: ?>

				<div class="padd101">
					<?php _e('There are no user feedback.','wpjobster'); ?>
				</div>

			<?php endif; ?>

		</div>

		<div id="tabs2">

			<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
				<input type="hidden" value="usrrev" name="page" />
				<input type="hidden" value="tabs2" name="active_tab" />
				<table width="100%" class="sitemile-table">
					<tr>
						<td><?php _e('Search User','wpjobster'); ?></td>
						<td><input type="text" value="<?php if(isset($_GET['search_user'])) echo $_GET['search_user']; ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Search','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

			<?php
			if(isset($_GET['search_user'])){
				$user = trim($_GET['search_user']);
				$user = get_user_by('login', $user);
				$uid = $user->ID;
			}else{
				$uid = "";
			}
			$s = "select ratings.orderid, ratings.datemade, ratings.reason, ratings.grade from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders, ".$wpdb->prefix."posts posts where orders.pid=posts.ID AND posts.post_author='$uid' AND orders.id=ratings.orderid AND ratings.awarded>0 order by ratings.id desc";
			$r = $wpdb->get_results($s);
			if(count($r) > 0): ?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('User','wpjobster'); ?></th>
						<th><?php _e('Price','wpjobster'); ?></th>
						<th><?php _e('Job','wpjobster'); ?></th>
						<th><?php _e('Rating Type','wpjobster'); ?></th>
						<th><?php _e('Description','wpjobster'); ?></th>
						<th><?php _e('Awarded On','wpjobster'); ?></th>
						<th><?php _e('Options','wpjobster'); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach($r as $row)
					{
						$s_ql = "select * from ".$wpdb->prefix."job_orders where id='".$row->orderid."'";
						$r_ql = $wpdb->get_results($s_ql);
						$post = get_post($r_ql[0]->pid);
						$userdata = get_userdata($post->post_author);
						$pid = $r_ql[0]->pid;
						echo '<tr>';
						echo '<th>'.$userdata->user_login.'</th>';
						echo '<th>'.wpjobster_get_show_price_classic($r_ql[0]->mc_gross).'</th>';
						echo '<th><a href="'.get_permalink($pid).'">'.wpjobster_wrap_the_title($r_ql[0]->job_title, $pid).'</a></th>';
						echo '<th>'.wpjobster_show_stars_our_of_number($row->grade).'</th>';
						echo '<th>'.$row->reason.'</th>';
						echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';
						echo '<th><form method="POST" action=""><input type="hidden" name="action" value="delete_review"><input type="hidden" name="idofreview" value="'.isset($row->id).'"><input type="submit" class="button-secondary" value="' . __("Delete",'wpjobster') . '"></form></th>';
						echo '</tr>';
					}
					?>
				</tbody>

			</table>
			<?php else: ?>

			<div class="padd101">
				<?php _e('There are no user feedback.','wpjobster'); ?>
			</div>

			<?php endif; ?>

		</div>

		<div id="tabs3"></div>
	</div>
<?php
}
