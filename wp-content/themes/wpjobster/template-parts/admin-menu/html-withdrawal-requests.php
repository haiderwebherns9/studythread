<?php

function wpj_withdrawal_requests_html() {

	global $wpdb;

?>

<div id="usual2" class="usual">

	<ul>
		<li><a href="#tabs1"><?php _e('Unresolved Requests','wpjobster'); ?></a></li>
		<li><a href="#tabs2"><?php _e('Resolved Requests','wpjobster'); ?></a></li>
		<li><a href="#tabs_rejected"><?php _e('Rejected Requests','wpjobster'); ?></a></li>
		<li><a href="#tabs3"><?php _e('Search Unresolved','wpjobster'); ?></a></li>
		<li><a href="#tabs4"><?php _e('Search Solved','wpjobster'); ?></a></li>
		<li><a href="#tabs_search_rejected"><?php _e('Search Rejected','wpjobster'); ?></a></li>
	</ul>
	<div id="tabs1">
		<?php
		$s = "select * from ".$wpdb->prefix."job_withdraw where done='0' and ( activation_key is NULL or activation_key = '' ) order by id desc";
		$r = $wpdb->get_results($s);
		$wpjobster_enable_paypal_withdraw  = get_option('wpjobster_paypal_enable_withdrawal');
		if(count($r) > 0):
		?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<?php if ($wpjobster_enable_paypal_withdraw == 'yes'): ?>
						<th width="3%" >All<input type="checkbox" name="option1" value="mass_pay" onclick="for(c in document.getElementsByName('requests[]')) document.getElementsByName('requests[]').item(c).checked = this.checked"></th>
					<?php endif ?>
					<th width="12%" ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';

					if ($wpjobster_enable_paypal_withdraw=="yes") {
						if ($row->methods == 'PayPal') {
							echo '<form method="post"> <th><input type="checkbox" name="requests[]" value="'.$row->id.'"></th>';
						}else{
							echo '<th></th>';
						}
					}
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
					echo '</tr>';
				}
				if ($wpjobster_enable_paypal_withdraw=="yes") {
					echo "<tfoot><tr> <td colspan = '2'><input type='submit' value='Process PayPal Requests' name='processPayReque'> </form><td></tr></tfoot>";
				}

				?>
			</tbody>

		</table>

		<?php else: ?>

		<div class="padd101">
			<?php _e('There are no unresolved withdrawal requests.','wpjobster'); ?>
		</div>

		<?php endif; ?>
	</div>

	<div id="tabs2">

		<?php

		$s = "select * from ".$wpdb->prefix."job_withdraw where done='1' order by id desc";
		$r = $wpdb->get_results($s);

		if(count($r) > 0):

			?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th><?php _e('Date Released','wpjobster'); ?></th>
					<th><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.($row->datedone == 0 ? "Not yet" : date('d-M-Y H:i:s',$row->datedone)) .'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
					echo '</tr>';
				}

				?>
			</tbody>

		</table>
	<?php else: ?>

		<div class="padd101">
			<?php _e('There are no resolved withdrawal requests.','wpjobster'); ?>
		</div>

	<?php endif; ?>

	</div>

	<div id="tabs_rejected">

		<?php

		$s = "select * from ".$wpdb->prefix."job_withdraw where rejected='1' order by id desc";
		$r = $wpdb->get_results($s);

		if(count($r) > 0):

			?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th><?php _e('Date Released','wpjobster'); ?></th>
					<th><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.($row->datedone == 0 ? "Not yet" : date('d-M-Y H:i:s',$row->datedone)) .'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
					echo '</tr>';
				}

				?>
			</tbody>

		</table>
	<?php else: ?>

		<div class="padd101">
			<?php _e('There are no rejected withdrawal requests.','wpjobster'); ?>
		</div>

	<?php endif; ?>

	</div>

	<div id="tabs3">

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="withdraw-req" name="page" />
			<input type="hidden" value="tabs3" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php if(isset($_GET['search_user'])) echo $_GET['search_user']; ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save3" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

		<?php

		if(isset($_GET['wpjobster_save3'])):

			$search_user = trim($_GET['search_user']);

		$user   = get_user_by('login', $search_user);
		$uid  = $user->ID;

		$s = "select * from ".$wpdb->prefix."job_withdraw where done='0' AND uid='$uid' order by id desc";
		$r = $wpdb->get_results($s);

		if(count($r) > 0):

			?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th width="12%" ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
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

	?>

	</div>

	<div id="tabs4">
		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="withdraw-req" name="page" />
			<input type="hidden" value="tabs4" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php if(isset($_GET['search_user'])) echo $_GET['search_user4']; ?>" name="search_user4" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save4" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

		<?php

		if(isset($_GET['wpjobster_save4'])):

			$search_user = trim($_GET['search_user4']);

		$user   = get_user_by('login', $search_user);
		$uid  = $user->ID;

		$s = "select * from ".$wpdb->prefix."job_withdraw where done='1' AND uid='$uid' order by id desc";
		$r = $wpdb->get_results($s);

		if(count($r) > 0):

			?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th width="12%" ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
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

	?>

	</div>

	<div id="tabs_search_rejected">
		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="withdraw-req" name="page" />
			<input type="hidden" value="tabs_search_rejected" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php if(isset($_GET['search_user'])) echo $_GET['search_user5']; ?>" name="search_user5" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save5" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

		<?php

		if(isset($_GET['wpjobster_save5'])):

			$search_user = trim($_GET['search_user5']);

		$user   = get_user_by('login', $search_user);
		$uid  = $user->ID;

		$s = "select * from ".$wpdb->prefix."job_withdraw where rejected='1' AND uid='$uid' order by id desc";
		$r = $wpdb->get_results($s);

		if(count($r) > 0):

			?>

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th width="12%" ><?php _e('Username','wpjobster'); ?></th>
					<th><?php _e('Method','wpjobster'); ?></th>
					<th width="20%"><?php _e('Details','wpjobster'); ?></th>
					<th><?php _e('Date Requested','wpjobster'); ?></th>
					<th ><?php _e('Amount','wpjobster'); ?></th>
					<th ><?php _e('Currency','wpjobster'); ?></th>
					<th width="25%"><?php _e('Options','wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php

				foreach($r as $row)
				{
					$user = get_userdata($row->uid);

					echo '<tr>';
					echo '<th>'.$user->user_login.'</th>';
					echo '<th>'.$row->methods .'</th>';
					echo '<th>'.apply_filters( 'wpj_sensitive_info_email', $row->payeremail ) .'</th>';
					echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
					echo '<th>'.wpjobster_get_show_price_classic($row->amount) .'</th>';
					echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
					echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
						__('Make Complete','wpjobster').'</a>' . ' | ' . '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=withdraw-req&den_id='.$row->id.'" class="awesome">'.
						__('Deny Request','wpjobster').'</a>' :( $row->done == 1 ? __("Completed",'wpjobster') : __("Rejected",'wpjobster') ) ).'</th>';
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

	?>

	</div>

<?php
}
