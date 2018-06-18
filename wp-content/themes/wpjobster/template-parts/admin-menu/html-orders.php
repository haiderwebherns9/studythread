<?php

function wpjobster_orders_m() {
	global $wpdb;
	$id_icon      = 'icon-options-general-orders';
	$ttl_of_stuff = 'Jobster - '.__('Orders','wpjobster');
	$prefix = $wpdb->prefix;
	//------------------------------------------------------

	echo '<div class="wrap">';
	echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
	echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';
	if(isset($_GET['status'])){
		if($_GET['status']=='success' || $_GET['status']=='fail'){
			$message = '';
			if( $_GET['status']=='success' ) $message = __( "Order status changed to Completed","wpjobster" );
			if( $_GET['status']=='fail' ) $message = __( "Order status changed to Cancelled","wpjobster" );
			?>
			<div class="updated" ><p><?php echo $message;?> </p><button type="button" class="notice-dismiss vc-notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php }
	} ?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('Active','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Search Active','wpjobster'); ?></a></li>			

			<li><a href="#tabs3"><?php _e('Delivered','wpjobster'); ?></a></li>
			<li><a href="#tabs4"><?php _e('Search Delivered','wpjobster'); ?></a></li>

			<li><a href="#tabs5"><?php _e('Completed','wpjobster'); ?></a></li>
			<li><a href="#tabs6"><?php _e('Search Completed','wpjobster'); ?></a></li>

			<li><a href="#tabs7"><?php _e('Closed','wpjobster'); ?></a></li>
			<li><a href="#tabs8"><?php _e('Search Closed','wpjobster'); ?></a></li>

			<li><a href="#tabs-10"><?php _e('Bank transfer top up','wpjobster'); ?></a></li>
			<li><a href="#tabs-11"><?php _e('Bank transfer featured','wpjobster'); ?></a></li>
			<li><a href="#tabs-12"><?php _e('Bank transfer custom extra','wpjobster'); ?></a></li>
			<li><a href="#tabs-13"><?php _e('Bkash Payement','wpjobster'); ?></a></li>
		</ul>

		<div id="tabs1">
			<?php
			if (!is_demo_admin()) {
				if (isset($_GET['idclose'])) {
					$tm = current_time('timestamp', 1);
					$idclose = $_GET['idclose'];

					if (!is_numeric($idclose)) { echo "ERROR!"; die; }

					$s      = "select * from ".$wpdb->prefix."job_orders orders, ".$wpdb->prefix."posts posts where orders.pid=posts.ID AND orders.id='$idclose'";
					$r      = $wpdb->get_results($s);
					$row    = $r[0];
					$oid    = $row->id;
					$pid    = $row->pid;
					$buyer  = $row->uid;
					$seller = $row->post_author;

					if ($row->closed != 1 && $row->completed != 1) {
						$s1  = "update ".$wpdb->prefix."job_orders set closed='1', force_cancellation='1', payment_status='cancelled', date_closed='$tm' where id='$idclose'";
						$wpdb -> query($s1);

						$ccc = '';
						$g1  = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-14','$idclose','$ccc')"; // -14 means the admin closed the job
						$wpdb->query($g1);
						wpj_update_user_notifications( $seller, 'notifications', +1 );

						$current_cash      = wpjobster_get_credits($row->uid);
						$refundable_amount = wpjobster_get_refundable_amount($row);
						wpjobster_update_credits($row->uid, $current_cash + $refundable_amount);

						$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $idclose;
						$reason    = __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $row->post_title . '</a>';
						wpjobster_add_history_log('1', $reason, $refundable_amount, $row->uid, '', $idclose, 7, '');

						if (get_post_type($pid) == 'offer') {
							wpjobster_send_email_allinone_translated('cancel_offer_admin', $buyer, false, $pid, $oid);
							wpjobster_send_email_allinone_translated('cancel_offer_admin', $seller, false, $pid, $oid);
						} else {
							wpjobster_send_email_allinone_translated('cancel_admin', $buyer, false, $pid, $oid);
							wpjobster_send_email_allinone_translated('cancel_admin', $seller, false, $pid, $oid);
						}
					}
				}

				if (isset($_GET['idcomplete'])) {
					$tm         = current_time('timestamp', 1);
					$idcomplete = $_GET['idcomplete'];

					if (!is_numeric($idcomplete)) { echo "ERROR!"; die; }

					$s          = "select * from ".$wpdb->prefix."job_orders orders, ".$wpdb->prefix."posts posts where orders.pid=posts.ID AND orders.id='$idcomplete'";
					$r          = $wpdb->get_results($s);
					$row        = $r[0];
					$oid        = $row->id;
					$pid        = $row->pid;
					$post_title = $row->job_title;

					$buyer      = $row->uid;
					$seller     = $row->post_author;

					$mc_gross = $row->mc_gross;
					$buyer_processing_fees = $row->processing_fees;
					$wpjobster_tax_amount = $row->tax_amount;

					if ($row->closed != 1 && $row->completed != 1 && $row->payment_status != 'completed' ) {
						$s1       = "update ".$wpdb->prefix."job_orders set  payment_status='completed' where id='$idcomplete'";
						$wpdb->query($s1);
						$s1       = "update ".$wpdb->prefix."job_payment_received set  payment_status='1'  and payment_type='feature' where id='$idcomplete'";
						$wpdb->query($s1);

						$ccc      = '';
						$datemade = time();

						$g1 = "insert into " . $wpdb->prefix . "job_chatbox (datemade, uid, oid, content) values('$datemade','0','$oid','')";
						$wpdb->query($g1);
						wpj_update_user_notifications( $seller, 'notifications', +1 );

						wpjobster_send_email_allinone_translated('admin_payment_completed_by_admin', 'admin', false, $pid, $oid);
						wpjobster_send_email_allinone_translated('payment_completed_by_admin', $buyer, false, $pid, $oid);

						wpjobster_send_sms_allinone_translated('purchased_buyer', $buyer, false, $pid, $oid);
						wpjobster_send_sms_allinone_translated('purchased_seller', $seller, false, $pid, $oid);
						wpjobster_send_email_allinone_translated('purchased_buyer', $buyer, false, $pid, $oid);
						wpjobster_send_email_allinone_translated('purchased_seller', $seller, false, $pid, $oid);

						wpjobster_maintain_log($oid, $post_title, $mc_gross, $buyer, $pid, $seller, $buyer_processing_fees, $wpjobster_tax_amount);

						// this runs when marked as completed by admin or banktransfer
						do_action( 'wpjobster_job_payment_completed', $oid );
					}
				}
			}

	$rows_per_page = 10;
	if(isset($_GET['pj_order_t1'])) $pageno = $_GET['pj_order_t1'];
	else $pageno = 1;

	$s1 = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
	where  posts.ID=orders.pid AND orders.done_seller='0' AND
	orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc";

	$s  = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
	where  posts.ID=orders.pid AND orders.done_seller='0' AND
	orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

	$r = $wpdb->get_results($s1);$nr = count($r);
	$lastpage      = ceil($nr/$rows_per_page);
	if(!isset($limit)){

		$limit='';
	}
	$r = $wpdb->get_results($s.$limit);

	if(count($r) > 0) {

	echo '<table width="100%" class="wp-list-table widefat fixed posts">';
	echo '<thead><tr>';
	echo '<th width="12%">'.__('ID','wpjobster').'</th>';
	echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
	echo '<th>'.__('Payment gateway','wpjobster').'</th>';
	echo '<th>'.__('Payment status','wpjobster').'</th>';
	echo '<th>'.__('Job Price','wpjobster').'</th>';
	echo '<th>'.__('Order Total','wpjobster').'</th>';
	echo '<th>'.__('Seller','wpjobster').'</th>';
	echo '<th>'.__('Buyer','wpjobster').'</th>';
	echo '<th>'.__('Ordered on','wpjobster').'</th>';
	echo '<th>'.__('Expected delivery','wpjobster').'</th>';
	echo '<th>'.__('Close Job','wpjobster').'</th>';
	do_action('wpjobster_invoice_link_heading');
	echo '</tr></thead><tbody>';

	foreach($r as $row) {

		$post      = get_post($row->pid);
		$price     = get_post_meta($row->pid, 'price', true);
		$expected  = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
		$price     = wpjobster_get_show_price_classic($price);
		$buyer     = get_userdata($row->uid);
		$seller    = get_userdata($post->post_author);
		$date_made = date("d-m-Y H:i:s", $row->date_made);

		echo '<tr>';
		echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
		echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
		echo '<th>'.$row->payment_gateway.'</th>';
		echo '<th>'.$row->payment_status;
		if($row->payment_status=='pending'){
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idcomplete='.$row->id.'" class="awesome">'.__('Mark Complete','wpjobster').'</a>';
		}elseif($row->payment_status=='failed' || $row->payment_status=='cancelled'){
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idcomplete='.$row->id.'&prev_status='.$row->payment_status.'" class="awesome">'.__('Mark Complete','wpjobster').'</a>';
		}
		echo '</th>';
		echo '<th>'.$price.'</th>';
		echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
		echo '<th>'.$seller->user_login.'</th>';
		echo '<th>'.$buyer->user_login.'</th>';
		echo '<th>'.$date_made.'</th>';
		echo '<th>'.$expected.'</th>';
		echo '<th><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idclose='.$row->id.'" class="awesome">'.__('Close','wpjobster').'</a></th>';
		do_action('wpjobster_invoice_link_url', $row->id );
		echo '</tr>';

	}

	echo '</tbody></table>';
	for($i=1;$i<=$lastpage;$i++) {

		if($lastpage > 1){
			if($pageno == $i) echo $i." | ";
			else
				echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs1&pj_order_t1='.$i.'"
			>'.$i.'</a> | ';
		}
	}
}
else { echo '<div style="padding:15px">'.__('No open orders yet.','wpjobster').'</div>'; }
?>

</div>

	<div id="tabs2">
		<?php       $search_user = isset($_GET['search_user'])?trim($_GET['search_user']):'';
		?>          <form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
		<input type="hidden" value="order-stats" name="page" />
		<input type="hidden" value="tabs2" name="active_tab" />
		<table width="100%" class="sitemile-table">
			<tr>
				<td><?php _e('Search User','wpjobster'); ?></td>
				<td><input type="text" value="<?php echo $search_user; ?>" name="search_user" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Search','wpjobster'); ?>"/></td>
			</tr>

		</table>
	</form>

	<?php

	$user = get_user_by('login', $search_user);
	if($user)
	{
		$uid = $user->ID;
	}else{
		$uid ='0' ;
	}

	$s                       = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
	where  posts.ID=orders.pid AND orders.done_seller='0' AND (orders.uid='$uid' OR posts.post_author='$uid') AND
	orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc";

	$r                       = $wpdb->get_results($s);

	if(count($r) > 0)
	{

		echo '<table width="100%" class="wp-list-table widefat fixed posts">';
		echo '<thead><tr>';
		echo '<th width="12%">'.__('ID','wpjobster').'</th>';
		echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
		echo '<th>'.__('Payment gateway','wpjobster').'</th>';
		echo '<th>'.__('Payment status','wpjobster').'</th>';
		// echo '<th>'.__('Order Price','wpjobster').'</th>';
		echo '<th>'.__('Job Price','wpjobster').'</th>';
		// echo '<th>'.__('Order Currency','wpjobster').'</th>';
		echo '<th>'.__('Order Total','wpjobster').'</th>';
		echo '<th>'.__('Seller','wpjobster').'</th>';
		echo '<th>'.__('Buyer','wpjobster').'</th>';
		echo '<th>'.__('Ordered on','wpjobster').'</th>';
		echo '<th>'.__('Expected delivery','wpjobster').'</th>';
		//_PRIVATE_CHANGES_ADD
		echo '<th>'.__('Close Job','wpjobster').'</th>';
		do_action('wpjobster_invoice_link_heading');
		//_PRIVATE_CHANGES_END
		echo '</tr></thead><tbody>';

		foreach($r as $row)
		{

			$post                    = get_post($row->pid);
			$price                   = get_post_meta($row->pid, 'price', true);
			$expected                = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
			$price                   = wpjobster_get_show_price_classic($price);
			$buyer                   = get_userdata($row->uid);
			$seller                  = get_userdata($post->post_author);
			$date_made               = date("d-m-Y H:i:s", $row->date_made);

			echo '<tr>';
			echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
			echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
			echo '<th>'.$row->payment_gateway.'</th>';
			echo '<th>'.$row->payment_status.'</th>';
			echo '<th>'.$price.'</th>';
			echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
			echo '<th>'.$seller->user_login.'</th>';
			echo '<th>'.$buyer->user_login.'</th>';
			echo '<th>'.$date_made.'</th>';
			echo '<th>'.$expected.'</th>';
		//_PRIVATE_CHANGES_ADD
			echo '<th><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idclose='.$row->id.'" class="awesome">'.__('Close','wpjobster').'</a></th>';
			do_action('wpjobster_invoice_link_url', $row->id );
		//_PRIVATE_CHANGES_END
			echo '</tr>';

		}

		echo '</tbody></table>';
	}
	else { echo '<div style="padding:15px">'.__('No results for your search.','wpjobster').'</div>'; }

	?>
	</div>

	<div id="tabs3">

		<?php

		$rows_per_page = 10;
		if(isset($_GET['pj_order_t3'])) $pageno = $_GET['pj_order_t3'];
		else $pageno = 1;

		$s1 = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where   posts.ID=orders.pid AND orders.done_seller='1' AND
		orders.done_buyer='0' AND orders.closed='0' order by orders.id desc";

		$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where   posts.ID=orders.pid AND orders.done_seller='1' AND
		orders.done_buyer='0' AND orders.closed='0' order by orders.id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

		$r = $wpdb->get_results($s1);$nr = count($r);
		$lastpage      = ceil($nr/$rows_per_page);

		$r = $wpdb->get_results($s.$limit);

		if(count($r) > 0)
		{

			echo '<table width="100%" class="wp-list-table widefat fixed posts">';
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
					//  echo '<th>'.__('Order Price','wpjobster').'</th>';
			echo '<th>'.__('Job Price','wpjobster').'</th>';
					//  echo '<th>'.__('Order Currency','wpjobster').'</th>';
			echo '<th>'.__('Order Total','wpjobster').'</th>';
			echo '<th>'.__('Seller','wpjobster').'</th>';
			echo '<th>'.__('Buyer','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '<th>'.__('Expected delivery','wpjobster').'</th>';
	//_PRIVATE_CHANGES_ADD
			echo '<th>'.__('Close Job','wpjobster').'</th>';
			do_action('wpjobster_invoice_link_heading');
	//_PRIVATE_CHANGES_END
			echo '</tr></thead><tbody>';

			foreach($r as $row)
			{

				$post   = get_post($row->pid);
				$price  = get_post_meta($row->pid, 'price', true);
				$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
				$price  = wpjobster_get_show_price_classic($price);
				$buyer  = get_userdata($row->uid);
				$seller = get_userdata($post->post_author);
				$date_made = date("d-m-Y H:i:s", $row->date_made);

				echo '<tr>';
				echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
				echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
				echo '<th>'.$row->payment_gateway.'</th>';
				echo '<th>'.$row->payment_status.'</th>';
				echo '<th>'.$price.'</th>';
				echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
				echo '<th>'.$seller->user_login.'</th>';
				echo '<th>'.$buyer->user_login.'</th>';
				echo '<th>'.$date_made.'</th>';
				echo '<th>'.$expected.'</th>';
	//_PRIVATE_CHANGES_ADD
				echo '<th><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idclose='.$row->id.'" class="awesome">'.__('Close','wpjobster').'</a></th>';
				do_action('wpjobster_invoice_link_url', $row->id );
	//_PRIVATE_CHANGES_END
				echo '</tr>';

			}

			echo '</tbody></table>';
			for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i) echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs3&pj_order_t3='.$i.'"
					>'.$i.'</a> | ';
				}
			}
		}
		else { echo '<div style="padding:15px">'.__('No delivered orders yet.','wpjobster').'</div>'; }

		?>

	</div>

	<div id="tabs4">

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="order-stats" name="page" />
			<input type="hidden" value="tabs4" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php echo WPJ_Form::get( 'search_user4', '' ); ?>" name="search_user4" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save4" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>

	<div id="tabs5">
		<?php
		$rows_per_page = 10;
		if(isset($_GET['pj_order_t5'])) $pageno = $_GET['pj_order_t5'];
		else $pageno = 1;

		$s1 = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where posts.ID=orders.pid AND orders.done_seller='1' AND
		orders.done_buyer='1' AND orders.closed='0' order by orders.id desc";

		$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where posts.ID=orders.pid AND orders.done_seller='1' AND
		orders.done_buyer='1' AND orders.closed='0' order by orders.id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

		$r = $wpdb->get_results($s1); $nr = count($r);
		$lastpage      = ceil($nr/$rows_per_page);

		$r = $wpdb->get_results($s.$limit);

		if(count($r) > 0)
		{

			echo '<table width="100%" class="wp-list-table widefat fixed posts">';
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
					//  echo '<th>'.__('Order Price','wpjobster').'</th>';
			echo '<th>'.__('Job Price','wpjobster').'</th>';
					//  echo '<th>'.__('Order Currency','wpjobster').'</th>';
			echo '<th>'.__('Order Total','wpjobster').'</th>';
			echo '<th>'.__('Seller','wpjobster').'</th>';
			echo '<th>'.__('Buyer','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '<th>'.__('Expected delivery','wpjobster').'</th>';
			do_action('wpjobster_invoice_link_heading');
						//echo '<th></th>';
			echo '</tr></thead><tbody>';

			foreach($r as $row)
			{

				$post   = get_post($row->pid);
				$price  = get_post_meta($row->pid, 'price', true);
				$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
				$price  = wpjobster_get_show_price_classic($price);
				$buyer  = get_userdata($row->uid);
				$seller = get_userdata($post->post_author);
				$date_made = date("d-m-Y H:i:s", $row->date_made);

				echo '<tr>';
				echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
				echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
				echo '<th>'.$row->payment_gateway.'</th>';
				echo '<th>'.$row->payment_status.'</th>';
				echo '<th>'.$price.'</th>';
				echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
				echo '<th>'.$seller->user_login.'</th>';
				echo '<th>'.$buyer->user_login.'</th>';
				echo '<th>'.$date_made.'</th>';
				echo '<th>'.$expected.'</th>';
				do_action('wpjobster_invoice_link_url', $row->id );
						//echo '<th></th>';
				echo '</tr>';

			}

			echo '</tbody></table>';


			for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i) echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs5&pj_order_t5='.$i.'"
					>'.$i.'</a> | ';
				}
			}
		}
		else { echo '<div style="padding:15px">'.__('No completed orders yet.','wpjobster').'</div>'; }

		?>

	</div>

	<div id="tabs6">

		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="order-stats" name="page" />
			<input type="hidden" value="tabs6" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php echo WPJ_Form::get( 'search_user6', '' ); ?>" name="search_user6" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save6" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

		<?php

		$search_user = trim( WPJ_Form::get( 'search_user6', '' ) );
		if ( $search_user ) {
			$user = get_user_by( 'login', $search_user );
			$uid = $user->ID;
		} else {
			$uid = '';
		}

		$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where posts.ID=orders.pid AND orders.done_seller='1' AND  (orders.uid='$uid' OR posts.post_author='$uid') AND
		orders.done_buyer='1' AND orders.closed='0' order by orders.id desc";

		$r = $wpdb->get_results($s);

		if(count($r) > 0)
		{

			echo '<table width="100%" class="wp-list-table widefat fixed posts">';
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
				 //   echo '<th>'.__('Order Price','wpjobster').'</th>';
			echo '<th>'.__('Job Price','wpjobster').'</th>';
			echo '<th>'.__('Order Total','wpjobster').'</th>';
			echo '<th>'.__('Seller','wpjobster').'</th>';
			echo '<th>'.__('Buyer','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '<th>'.__('Expected delivery','wpjobster').'</th>';
			do_action('wpjobster_invoice_link_heading');
						//echo '<th></th>';
			echo '</tr></thead><tbody>';

			foreach($r as $row)
			{

				$post   = get_post($row->pid);
				$price  = get_post_meta($row->pid, 'price', true);
				$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
				$price  = wpjobster_get_show_price_classic($price);
				$buyer  = get_userdata($row->uid);
				$seller = get_userdata($post->post_author);
				$date_made = date("d-m-Y H:i:s", $row->date_made);

				echo '<tr>';
				echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
				echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
				echo '<th>'.$row->payment_gateway.'</th>';
				echo '<th>'.$row->payment_status.'</th>';
				echo '<th>'.$price.'</th>';
				echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
				echo '<th>'.$seller->user_login.'</th>';
				echo '<th>'.$buyer->user_login.'</th>';
				echo '<th>'.$date_made.'</th>';
				echo '<th>'.$expected.'</th>';
				do_action('wpjobster_invoice_link_url', $row->id );
						//echo '<th></th>';
				echo '</tr>';

			}

			echo '</tbody></table>';
		}
		else { echo '<div style="padding:15px">'.__('No completed orders yet.','wpjobster').'</div>'; } ?>

	</div>

	<div id="tabs7">

		<?php
		$rows_per_page = 10;
		if(isset($_GET['pj_order_t7'])) $pageno = $_GET['pj_order_t7'];
		else $pageno = 1;

		$s1 = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where  posts.ID=orders.pid AND orders.closed='1' order by orders.id desc";

		$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where  posts.ID=orders.pid AND orders.closed='1' order by orders.id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;


		$r = $wpdb->get_results($s1); $nr = count($r);
		$lastpage      = ceil($nr/$rows_per_page);

		$r = $wpdb->get_results($s.$limit);

		if(count($r) > 0)
		{

			echo '<table width="100%" class="wp-list-table widefat fixed posts">';
				echo '<thead><tr>';
					echo '<th width="12%">'.__('ID','wpjobster').'</th>';
					echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
					echo '<th>'.__('Payment gateway','wpjobster').'</th>';
					echo '<th>'.__('Payment status','wpjobster').'</th>';
					echo '<th>'.__('Job Price','wpjobster').'</th>';
					echo '<th>'.__('Order Total','wpjobster').'</th>';
					echo '<th>'.__('Seller','wpjobster').'</th>';
					echo '<th>'.__('Buyer','wpjobster').'</th>';
					echo '<th>'.__('Ordered on','wpjobster').'</th>';
					echo '<th>'.__('Expected delivery','wpjobster').'</th>';
					echo '<th>'.__('Reason','wpjobster').'</th>';
					do_action('wpjobster_invoice_link_heading');
				echo '</tr></thead>';
			echo '<tbody>';

			foreach($r as $row)
			{

				$post   = get_post($row->pid);
				$price  = get_post_meta($row->pid, 'price', true);
				$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
				$price  = wpjobster_get_show_price_classic($price);
				$buyer  = get_userdata($row->uid);
				$seller = get_userdata($post->post_author);
				$date_made = date("d-m-Y H:i:s", $row->date_made);

				if( $row->force_cancellation == 2 ){
					$reason = 'Expired';
				}elseif( $row->force_cancellation == 1 ){
					$reason = 'Admin';
				}else{
					$reason = 'Mutual';
				}

				echo '<tr>';
				echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
				echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
				echo '<th>'.$row->payment_gateway.'</th>';
				echo '<th>'.$row->payment_status.'</th>';
				echo '<th>'.$price.'</th>';
				echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
				echo '<th>'.$seller->user_login.'</th>';
				echo '<th>'.$buyer->user_login.'</th>';
				echo '<th>'.$date_made.'</th>';
				echo '<th>'.$expected.'</th>';
				echo '<th>'.$reason.'</th>';
				do_action('wpjobster_invoice_link_url', $row->id );
						//echo '<th></th>';
				echo '</tr>';

			}

			echo '</tbody></table>';
			for($i=1;$i<=$lastpage;$i++)
			{
				if($lastpage > 1){
					if($pageno == $i) echo $i." | ";
					else
						echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs7&pj_order_t7='.$i.'"
					>'.$i.'</a> | ';
				}
			}
		}
		else { echo '<div style="padding:15px">'.__('No closed orders yet.','wpjobster').'</div>'; }

		?>

	</div>

	<div id="tabs8">
		<form method="get" action="<?php bloginfo('url'); ?>/wp-admin/admin.php">
			<input type="hidden" value="order-stats" name="page" />
			<input type="hidden" value="tabs8" name="active_tab" />
			<table width="100%" class="sitemile-table">
				<tr>
					<td><?php _e('Search User','wpjobster'); ?></td>
					<td><input type="text" value="<?php echo WPJ_Form::get( 'search_user8', '' ); ?>" name="search_user8" size="20" /> <input type="submit" class="button-secondary" name="wpjobster_save8" value="<?php _e('Search','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>

		<?php

		$search_user = trim( WPJ_Form::get( 'search_user8', '' ) );
		if ( $search_user ) {
			$user = get_user_by('login', $search_user);
			$uid = $user->ID;
		} else {
			$uid = '';
		}

		$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
		where  posts.ID=orders.pid  AND  (orders.uid='$uid' OR posts.post_author='$uid') AND orders.closed='1' order by orders.id desc";

		$r = $wpdb->get_results($s);

		if(count($r) > 0)
		{

			echo '<table width="100%" class="wp-list-table widefat fixed posts">';
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
					//  echo '<th>'.__('Order Price','wpjobster').'</th>';
			echo '<th>'.__('Job Price','wpjobster').'</th>';
			echo '<th>'.__('Order Total','wpjobster').'</th>';
			echo '<th>'.__('Seller','wpjobster').'</th>';
			echo '<th>'.__('Buyer','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '<th>'.__('Expected delivery','wpjobster').'</th>';
			do_action('wpjobster_invoice_link_heading');
						//echo '<th></th>';
			echo '</tr></thead><tbody>';

			foreach($r as $row)
			{

				$post   = get_post($row->pid);
				$price  = get_post_meta($row->pid, 'price', true);
				$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
				$price  = wpjobster_get_show_price_classic($price);
				$buyer  = get_userdata($row->uid);
				$seller = get_userdata($post->post_author);
				$date_made = date("d-m-Y H:i:s", $row->date_made);

				echo '<tr>';
				echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
				echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
				echo '<th>'.$row->payment_gateway.'</th>';
				echo '<th>'.$row->payment_status.'</th>';
				echo '<th>'.$price.'</th>';
				echo '<th>'.wpjobster_deciphere_amount_classic($row->payedamount).'</th>';
				echo '<th>'.$seller->user_login.'</th>';
				echo '<th>'.$buyer->user_login.'</th>';
				echo '<th>'.$date_made.'</th>';
				echo '<th>'.$expected.'</th>';
				do_action('wpjobster_invoice_link_url', $row->id );
						//echo '<th></th>';
				echo '</tr>';

			}

			echo '</tbody></table>';
		}
		else { echo '<div style="padding:15px">'.__('No closed orders yet.','wpjobster').'</div>'; }

		?>

	</div>

	<div id="tabs-10">
		<table width="100%" class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
				<?php
					echo '<th width="12%">'.__('ID','wpjobster').'</th>';
					echo '<th>'.__('User','wpjobster').'</th>';
					echo '<th>'.__('Payment gateway','wpjobster').'</th>';
					echo '<th>'.__('Payment status','wpjobster').'</th>';
					echo '<th>'.__('Order Price','wpjobster').'</th>';
					echo '<th>'.__('Order Date','wpjobster').'</th>';?>
				</tr>
			</thead>
			<tbody>
			<?php
			include_once get_template_directory()."/lib/gateways/wpjobster_common_topup.php";
			$wct = new WPJ_Common_Topup('banktransfer');
			$topup_orders = $wct->get_topup_orders_pagination('banktransfer',array(0,10));
			if($topup_orders){
				foreach( $topup_orders as $topup_order ){
					if($topup_order->payment_status=='pending'){
						$payment_status='pending <a href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=topup&action=complete&order_id='.$topup_order->id.'">Complete</a>|<a href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=topup&action=cancel&order_id='.$topup_order->id.'">Cancel</a>';
					}elseif($topup_order->payment_status=='completed'){
						$payment_status='completed';
					}else{
						$payment_status='cancelled';

					}
					?>
					<tr>
						<td ><?php echo $topup_order->id;?></td>

						<td ><?php $usr= get_user_by("ID",$topup_order->user_id);echo $usr->user_login; ?></td>
						<td ><?php echo $topup_order->payment_gateway_name;?></td>
						<td ><?php echo $payment_status;?></td>
						<td ><?php echo $topup_order->package_cost_without_tax." ".$topup_order->currency;?></td>
						<td ><?php echo $topup_order->created_on;?></td></tr>
						<?php } } //endforeach //endif ?>
			</tbody>
		</table>
	</div>
	<div id="tabs-11">
		<table width="100%" class="wp-list-table widefat fixed posts">
			<?php
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
			echo '<th>'.__('Feature Price','wpjobster').'</th>';
			echo '<th>'.__('User','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '</tr></thead>';
			include_once get_template_directory()."/lib/gateways/wpjobster_common_featured.php";
			$wcf = new WPJ_Common_Featured();
							//$topup_orders = $wct->get_topup_orders_pagination('banktransfer');
			$featured_orders = $wcf->get_featured_orders_pagination('banktransfer',array(0,10));
							//        print_r($featured_orders );
			?>
			<tbody>
					<?php if($featured_orders){ foreach( $featured_orders as $featured_order ){
						if($featured_order->payment_status=='pending'){
							$payment_status='pending <a href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=feature&action=complete&order_id='.$featured_order->id.'">Complete</a>|<a  href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=feature&action=cancel&order_id='.$featured_order->id.'">Cancel</a>';
						}elseif($featured_order->payment_status=='completed'){
							$payment_status='completed';
						}else{
							$payment_status='cancelled';
						}
						?>
					<tr>
						<td ><?php echo $featured_order->id;?></td>
						<td ><?php $job= get_post($featured_order->job_id); echo $job->post_title; ?></td>
						<td ><?php echo $featured_order->payment_gateway_name;?></td>
						<td ><?php echo $payment_status;?></td>
						<td ><?php echo $featured_order->payable_amount." ".$featured_order->currency;?></td>
						<td ><?php $usr= get_user_by("ID",$featured_order->user_id);echo $usr->user_login; ?></td>
						<td ><?php echo $featured_order->created_on;?></td>
					</tr>
					<?php } } //endforeach //endif ?>
			</tbody>
		</table>
	</div>

	<div id="tabs-12">
		<table width="100%" class="wp-list-table widefat fixed posts">
			<?php
			echo '<thead><tr>';
			echo '<th width="12%">'.__('ID','wpjobster').'</th>';
			echo '<th width="20%">'.__('Description','wpjobster').'</th>';
			echo '<th>'.__('Payment gateway','wpjobster').'</th>';
			echo '<th>'.__('Payment status','wpjobster').'</th>';
			echo '<th>'.__('Custom Extra Price','wpjobster').'</th>';
			echo '<th>'.__('User','wpjobster').'</th>';
			echo '<th>'.__('Ordered on','wpjobster').'</th>';
			echo '</tr></thead>';
			include_once get_template_directory()."/lib/gateways/wpjobster_common_custom_extra.php";
			$wcf = new WPJ_Common_Custom_Extra();
			$cusom_extra_orders = $wcf->get_custom_extra_orders_pagination('banktransfer',array(0,10));
			?>
			<tbody>
				<?php if($cusom_extra_orders){ foreach( $cusom_extra_orders as $cusom_extra_order ){
					if($cusom_extra_order->payment_status=='pending'){
						$payment_status='pending <a href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=custom_extra&action=complete&order_id='.$cusom_extra_order->id.'">Complete</a>|<a  href="' . get_bloginfo( 'url' ) . '/?payment_response=banktransfer&payment_type=custom_extra&action=cancel&order_id='.$cusom_extra_order->id.'">Cancel</a>';
					}elseif($cusom_extra_order->payment_status=='completed'){
						$payment_status='completed';
					}else{
						$payment_status='cancelled';
					}
					?>
				<tr>
					<td ><?php echo $cusom_extra_order->id;?></td>
					<td ><?php $ord = wpjobster_get_order($cusom_extra_order->order_id);
						$custom_extras = json_decode($ord->custom_extras);
						echo $custom_extras[$cusom_extra_order->custom_extra_id]->description; ?>
					</td>
					<td ><?php echo $cusom_extra_order->payment_gateway_name;?></td>
					<td ><?php echo $payment_status;?></td>
					<td ><?php echo $cusom_extra_order->payable_amount." ".$cusom_extra_order->currency;?></td>
					<td ><?php $usr= get_user_by("ID",$cusom_extra_order->user_id);echo $usr->user_login; ?></td>
					<td ><?php echo $cusom_extra_order->created_on;?></td>
				</tr>
				<?php } } //endforeach //endif ?>
			</tbody>
		</table>
	</div>
	<div id="tabs-13">
			<?php
			if (!is_demo_admin()) {
				if (isset($_GET['idclose'])) {
					$tm = current_time('timestamp', 1);
					$idclose = $_GET['idclose'];

					if (!is_numeric($idclose)) { echo "ERROR!"; die; }

					$s      = "select * from ".$wpdb->prefix."job_orders orders, ".$wpdb->prefix."posts posts where payment_gateway = 'Bkash' and orders.pid=posts.ID AND orders.id='$idclose'";
					$r      = $wpdb->get_results($s);
					$row    = $r[0];
					$oid    = $row->id;
					$pid    = $row->pid;
					$buyer  = $row->uid;
					$seller = $row->post_author;

					if ($row->closed != 1 && $row->completed != 1) {
						$s1  = "update ".$wpdb->prefix."job_orders set closed='1', force_cancellation='1', payment_status='cancelled', date_closed='$tm' where id='$idclose'";
						$wpdb -> query($s1);

						$ccc = '';
						$g1  = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-14','$idclose','$ccc')"; // -14 means the admin closed the job
						$wpdb->query($g1);
						wpj_update_user_notifications( $seller, 'notifications', +1 );

						$current_cash      = wpjobster_get_credits($row->uid);
						$refundable_amount = wpjobster_get_refundable_amount($row);
						wpjobster_update_credits($row->uid, $current_cash + $refundable_amount);

						$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $idclose;
						$reason    = __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $row->post_title . '</a>';
						wpjobster_add_history_log('1', $reason, $refundable_amount, $row->uid, '', $idclose, 7, '');

						if (get_post_type($pid) == 'offer') {
							wpjobster_send_email_allinone_translated('cancel_offer_admin', $buyer, false, $pid, $oid);
							wpjobster_send_email_allinone_translated('cancel_offer_admin', $seller, false, $pid, $oid);
						} else {
							wpjobster_send_email_allinone_translated('cancel_admin', $buyer, false, $pid, $oid);
							wpjobster_send_email_allinone_translated('cancel_admin', $seller, false, $pid, $oid);
						}
					}
				}

				if (isset($_GET['idcomplete'])) {
					$tm         = current_time('timestamp', 1);
					$idcomplete = $_GET['idcomplete'];

					if (!is_numeric($idcomplete)) { echo "ERROR!"; die; }

					$s          = "select * from ".$wpdb->prefix."job_orders orders, ".$wpdb->prefix."posts posts where  payment_gateway = 'Bkash' and orders.pid=posts.ID AND orders.id='$idcomplete'";
					$r          = $wpdb->get_results($s);
					$row        = $r[0];
					$oid        = $row->id;
					$pid        = $row->pid;
					$post_title = $row->job_title;

					$buyer      = $row->uid;
					$seller     = $row->post_author;

					$mc_gross = $row->mc_gross;
					$buyer_processing_fees = $row->processing_fees;
					$wpjobster_tax_amount = $row->tax_amount;

					if ($row->closed != 1 && $row->completed != 1 && $row->payment_status != 'completed' ) {
						$s1       = "update ".$wpdb->prefix."job_orders set  payment_status='completed' where id='$idcomplete'";
						$wpdb->query($s1);
						$s1       = "update ".$wpdb->prefix."job_payment_received set  payment_status='1'  and payment_type='feature' where id='$idcomplete'";
						$wpdb->query($s1);

						$ccc      = '';
						$datemade = time();

						$g1 = "insert into " . $wpdb->prefix . "job_chatbox (datemade, uid, oid, content) values('$datemade','0','$oid','')";
						$wpdb->query($g1);
						wpj_update_user_notifications( $seller, 'notifications', +1 );

						wpjobster_send_email_allinone_translated('admin_payment_completed_by_admin', 'admin', false, $pid, $oid);
						wpjobster_send_email_allinone_translated('payment_completed_by_admin', $buyer, false, $pid, $oid);

						wpjobster_send_sms_allinone_translated('purchased_buyer', $buyer, false, $pid, $oid);
						wpjobster_send_sms_allinone_translated('purchased_seller', $seller, false, $pid, $oid);
						wpjobster_send_email_allinone_translated('purchased_buyer', $buyer, false, $pid, $oid);
						wpjobster_send_email_allinone_translated('purchased_seller', $seller, false, $pid, $oid);

						wpjobster_maintain_log($oid, $post_title, $mc_gross, $buyer, $pid, $seller, $buyer_processing_fees, $wpjobster_tax_amount);

						// this runs when marked as completed by admin or banktransfer
						do_action( 'wpjobster_job_payment_completed', $oid );
					}
				}
			}
    ?>
		<script>
		  jQuery( function($) {
			var dateFormat = "mm/dd/yy",
			  from = $( "#from" )
				.datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  numberOfMonths: 1
				})
				.on( "change", function() {
				  to.datepicker( "option", "minDate", getDate( this ) );
				}),
			  to = $( "#to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1
			  })
			  .on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			  });
		 
			function getDate( element ) {
			  var date;
			  try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			  } catch( error ) {
				date = null;
			  }
		 
			  return date;
			}
			
           $( "#datepicker" ).datepicker();
		  } );
		  </script>
		<form action='<?php echo site_url();?>/wp-admin/admin.php'>
		<input type="hidden" name="page" value="order-stats">
		<input type="hidden" name="active_tab" value="tabs-13">
		<label for="date">Date</label>
		<input type="text" id="datepicker" name="date" value="<?php echo $_GET['date']?$_GET['date']:'';?>">		
		<input type="submit" name="submit" value="Filter">
		</form>
		<form action='<?php echo site_url();?>/wp-admin/admin.php'>
		<input type="hidden" name="page" value="order-stats">
		<input type="hidden" name="active_tab" value="tabs-13">
		<label for="from">From</label>
		<input type="text" id="from" name="from" value="<?php echo $_GET['from']?$_GET['from']:'';?>">
		<label for="to">to</label>
		<input type="text" id="to" name="to" value="<?php echo $_GET['to']?$_GET['to']:'';?>">
		<input type="submit" name="submit" value="Filter" >
		</form>
	<?php
	$rows_per_page = 10;	
	if($_GET['date']){	
        $fildat = strtotime('-3 Days',strtotime($_GET['date']));
		$fildat1 = strtotime('-2 Days',strtotime($_GET['date']));
		$filter_date='expected_delivery >"'.$fildat.'" and expected_delivery <"'.$fildat1.'" and ';
	}else if($_GET['from']){	
        $fildat = strtotime('-3 Days',strtotime($_GET['from']));
		$fildat1 = strtotime('-3 Days',strtotime($_GET['to']));
		$filter_date='expected_delivery >"'.$fildat.'" and expected_delivery <"'.$fildat1.'" and ';
	}else{
		$filter_date="";
	}
	
	if(isset($_GET['pj_order_t1'])) $pageno = $_GET['pj_order_t1'];
	else $pageno = 1;
  
	$s1 = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
	where ".$filter_date." payment_gateway = 'Bkash' and  posts.ID=orders.pid AND orders.done_seller='0' AND
	orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc";
     
	$s  = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
	where ".$filter_date."  payment_gateway = 'Bkash' and posts.ID=orders.pid AND orders.done_seller='0' AND
	orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

	$r = $wpdb->get_results($s1);$nr = count($r);
	$lastpage      = ceil($nr/$rows_per_page);
	if(!isset($limit)){

		$limit='';
	}
	$r = $wpdb->get_results($s.$limit);

	if(count($r) > 0) {

	echo '<table width="100%" class="wp-list-table widefat fixed posts">';
	echo '<thead><tr>';
	echo '<th width="12%">'.__('ID','wpjobster').'</th>';
	echo '<th width="20%">'.__('Job Title','wpjobster').'</th>';
	echo '<th>'.__('Payment gateway','wpjobster').'</th>';
	echo '<th>'.__('Payment status','wpjobster').'</th>';
	echo '<th>'.__('Job Price','wpjobster').'</th>';
	echo '<th>'.__('Our Payment','wpjobster').'</th>';
	echo '<th>'.__('Seller','wpjobster').'</th>';
	echo '<th>'.__('Buyer','wpjobster').'</th>';
	echo '<th>'.__('Ordered on','wpjobster').'</th>';
	echo '<th>'.__('Expected delivery','wpjobster').'</th>';
	echo '<th>'.__('Expected Pay Date','wpjobster').'</th>';
	echo '<th>'.__('Close Job','wpjobster').'</th>';
	do_action('wpjobster_invoice_link_heading');
	echo '</tr></thead><tbody>';

	foreach($r as $row) {

		$post      = get_post($row->pid);
		$price     = get_post_meta($row->pid, 'price', true);
		$expected  = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
		$price     = wpjobster_get_show_price_classic($price);
		$buyer     = get_userdata($row->uid);
		$seller    = get_userdata($post->post_author);
		$date_made = date("d-m-Y H:i:s", $row->date_made);

		echo '<tr>';
		echo '<th>#'.wpjobster_camouflage_order_id($row->id, $row->date_made).'</th>';
		echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
		echo '<th>'.$row->payment_gateway.'</th>';
		echo '<th>'.$row->payment_status;
		if($row->payment_status=='pending'){
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idcomplete='.$row->id.'" class="awesome">'.__('Mark Complete','wpjobster').'</a>';
		}elseif($row->payment_status=='failed' || $row->payment_status=='cancelled'){
			echo ' <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&idcomplete='.$row->id.'&prev_status='.$row->payment_status.'" class="awesome">'.__('Mark Complete','wpjobster').'</a>';
		}
		echo '</th>';
		echo '<th>'.$price.'</th>';
		if($price)
		{
			$our_order = ($price/100)*10;
		}
	  //  echo '<th>'.wpjobster_deciphere_amount_classic($our_order).'</th>';
	    echo '<th>'.wpjobster_get_show_price_classic($our_order).'</th>';
		echo '<th>'.$seller->user_login.'</th>';
		echo '<th>'.$buyer->user_login.'</th>';
		echo '<th>'.$date_made.'</th>';
		echo '<th>'.$expected.'</th>';
		echo '<th>'.date_i18n( get_option( 'date_format' ),strtotime('+3 day', wpj_get_expected_delivery( $row->id ) )) .'</th>';
		echo '<th>';
		?>
		<script>
		 function function_close(){
		    location.href="<?php echo get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs-13&idclose='.$row->id; ?>";
		  }
		</script>
		  <select onchange="function_close();" style="width:54px">
			<option value="pending" <?php if(!$row->closed){echo 'selected="selected"';} ?>>Pending</option>
			<option value="close">Close</option>
		  </select>
		<?php
		echo '</th>';
		do_action('wpjobster_invoice_link_url', $row->id );
		echo '</tr>';

	}

	echo '</tbody></table>';
	for($i=1;$i<=$lastpage;$i++) {

		if($lastpage > 1){
			if($pageno == $i) echo $i." | ";
			else
				echo '<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=order-stats&active_tab=tabs-13&pj_order_t1='.$i.'"
			>'.$i.'</a> | ';
		}
	}
}
else { echo '<div style="padding:15px">'.__('No open orders yet.','wpjobster').'</div>'; }
?>

</div>
	<?php
	echo '</div>';
}
