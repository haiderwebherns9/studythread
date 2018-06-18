<?php

function wpjobster_hist_trans() {
	$id_icon      = 'icon-options-general-list';
	$ttl_of_stuff = 'Jobster - '.__('Transactions','wpjobster');

	//------------------------------------------------------

	echo '<div class="wrap">';
	echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
	echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

	?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('All Transactions','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search','wpjobster'); ?></a></li>
		</ul>
		<div id="tabs1">
			<?php

			global $wpdb;

			$rows_per_page = 10;

			if(isset($_GET['pj'])) $pageno = $_GET['pj'];
			else $pageno = 1;

			$s1     = "select id from ".$wpdb->prefix."job_payment_transactions order by id desc ";
			$s      = "select *  from ".$wpdb->prefix."job_payment_transactions order by id desc ";
			$limit    = ' LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
			$r      = $wpdb->get_results($s1);
			$nr     = count($r);
			$lastpage   = ceil($nr/$rows_per_page);
			$r      = $wpdb->get_results($s.$limit);

			if(count($r) > 0):

				?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th width="10%"><?php _e('Transaction ID','wpjobster'); ?></th>
						<th width="10%"><?php _e('Username','wpjobster'); ?></th>
						<th width="40%"><?php _e('Comment/Description','wpjobster'); ?></th>
						<th><?php _e('Date Made','wpjobster'); ?></th>
						<th ><?php _e('Amount','wpjobster'); ?></th>

					</tr>
				</thead>

				<tbody>
					<?php

					foreach($r as $row)
					{
						$user = ( $row->uid ) ? get_userdata($row->uid) : '';
						$username = ( $user ) ? $user->user_login : '';

						if($row->tp == 0) { $sign = '-'; $cl = 'redred'; }
						else
							{ $sign = '+'; $cl = 'greengreen'; }

						echo '<tr>';
						echo '<th>'.$row->id.'</th>';
						echo '<th>'.$username.'</th>';
						echo '<th>'.$row->reason .'</th>';
						echo '<th>'.date_i18n('d-M-Y H:i:s',$row->datemade) .'</th>';
						echo '<th class="'.$cl.'">'.$sign.wpjobster_get_show_price_classic($row->amount,2).'</th>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>

			<?php
			for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i) echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=trans-site&active_tab=tabs1&pj='.$i.'"
					>'.$i.'</a> | ';
				}
			}

			else:
				?>

			<?php _e('There are no transactions yet.','wpjobster'); ?>

		<?php endif; ?>

	</div>

	<div id="tabs2">

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="trans-site" name="page" />
			<input type="hidden" value="tabs2" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php echo WPJ_Form::get( 'search_user', '' ); ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

				<tr>
					<td><?php _e('Search ID','wpjobster'); ?></td>
					<td><input type="text" value="<?php echo WPJ_Form::get( 'search_id', '' ); ?>" name="search_id" size="20" />
						<input type="submit" class="button-secondary" name="wpjobster_search_id" value="<?php _e('Search','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

			<!-- ############## -->

			<?php

			if(isset($_GET['wpjobster_save2']) || isset($_GET['wpjobster_search_id'])):

				global $wpdb;

			$rows_per_page = 10;

			if(isset($_GET['pj'])) $pageno = $_GET['pj'];
			else $pageno = 1;

					//-----
			$usrlg = trim($_GET['search_user']);
			$transid = trim($_GET['search_id']);

			if ($transid) {

				$s      = "select *  from ".$wpdb->prefix."job_payment_transactions where id='$transid' ";

			} else {

				$sql  = "select ID from $wpdb->users where user_login='$usrlg'";
				$rqrq   = $wpdb->get_results($sql);

				if(count($rqrq) > 0) $usrid = $rqrq[0]->ID;
				else $usrid = 0;

						//-----

				$s1     = "select id from ".$wpdb->prefix."job_payment_transactions where uid='$usrid' order by id desc ";
				$s      = "select *  from ".$wpdb->prefix."job_payment_transactions where uid='$usrid' order by id desc ";
			}



			$limit    = ' LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
			$r      = $wpdb->get_results($s1);
			$nr     = count($r);
			$lastpage   = ceil($nr/$rows_per_page);
			$r      = $wpdb->get_results($s.$limit);
			if(count($r) > 0):

				?>

			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th width="10%"><?php _e('Transaction ID','wpjobster'); ?></th>
						<th width="10%"><?php _e('Username','wpjobster'); ?></th>
						<th width="40%"><?php _e('Comment/Description','wpjobster'); ?></th>
						<th><?php _e('Date Made','wpjobster'); ?></th>
						<th ><?php _e('Amount','wpjobster'); ?></th>

					</tr>
				</thead>

				<tbody>
					<?php

					foreach($r as $row)
					{
						$user = get_userdata($row->uid);

						if($row->tp == 0) { $sign = '-'; $cl = 'redred'; }
						else
							{ $sign = '+'; $cl = 'greengreen'; }

						echo '<tr>';
						echo '<th>'.$row->id.'</th>';
						echo '<th>'.$user->user_login.'</th>';
						echo '<th>'.$row->reason .'</th>';
						echo '<th>'.date_i18n('d-M-Y H:i:s',$row->datemade) .'</th>';
						echo '<th class="'.$cl.'">'.$sign.wpjobster_get_show_price($row->amount,2).'</th>';
						echo '</tr>';
					}

					?>
				</tbody>
			</table>

		<?php else: ?>

			<?php _e('There are no transactions yet.','wpjobster'); ?>

		<?php endif; endif; ?>

		<?php
		if(isset($_GET['search_user']) && $_GET['search_id'] == ''){
			for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i)
						echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=trans-site&active_tab=tabs2&search_user='.$_GET['search_user'].'&wpjobster_save2=Search&search_id&pj='.$i.'"
					>'.$i.'</a> | ';
				}
			}
		}
		?>

		<!-- ############### -->

	</div>

	<?php
	echo '</div>';

}
