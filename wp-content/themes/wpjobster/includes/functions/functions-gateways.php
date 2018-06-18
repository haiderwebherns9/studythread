<?php
function wpj_old_ajax_admin_delete_handlers() {
	if (!is_demo_admin()) {
		if (isset($_POST['delete_variable_braintree_merchant'])) {
			if (is_user_logged_in() && current_user_can('manage_options')) {
				$ids = $_POST['delete_variable_braintree_merchant'];
				global $wpdb;
				$ss = "delete from ".$wpdb->prefix."job_braintree_merchant_ac_ids where id='$ids'";
				$wpdb->query($ss);
				exit;
			}
		}
	}
}
add_action( 'init', 'wpj_old_ajax_admin_delete_handlers' );

global $payment_type_enable_arr;
$payment_type_enable_arr = array(
	"job_purchase" => array(
		"enable_label" => __( "Enable for job purchase:", "wpjobster" ),
		"hint_label" => __( "Allow your users to purchase jobs through this payment gateway.", "wpjobster" ),
	),
	"topup" => array(
		"enable_label" => __( "Enable for top up:", "wpjobster" ),
		"hint_label" => __( "Allow your users to top up their balance through this payment gateway.", "wpjobster" ),
	),
	"featured" => array(
		"enable_label" => __( "Enable for featured job:", "wpjobster"),
		"hint_label" => __( "Allow your users to pay for featured jobs through this payment gateway.", "wpjobster" ),
	),
	"custom_extra" => array(
		"enable_label" => __( "Enable for custom extra:", "wpjobster"),
		"hint_label" => __( "Allow your users to pay for custom extras through this payment gateway.", "wpjobster" ),
	),
	"subscription" => array(
		"enable_label" => __( "Enable for subscription:", "wpjobster"),
		"hint_label" => __( "Allow your users to pay for subscription through this payment gateway.", "wpjobster" ),
	),
);

if (!function_exists('wpjobster_insert_order')) {
	function wpjobster_insert_order($custom, $currency = "", $separator = "|", $with_credits = 0,
			$payment_status = 'completed', $payment_gateway = '', $payment_details = '',$buyer_chargable_fees='',$taxable_amount='',$wpjobster_final_payable_amount_original='') {
		global $payment_type_enable_arr;

		if ($custom) {
			$cust = explode($separator, $custom);
			$pid      = $cust[0];
			$uid      = $cust[1];
			$datemade = $cust[2];

			$amount = $cust[3];
			$extras_nb = $cust[4];

			$xtra = array();
			$xtra_amounts = array();
			for($i=1;$i<=$extras_nb;$i++){
				$xtra[$i] = $cust[4+$i];
				$xtra_amounts[$i] = $cust[4+$i+$extras_nb];
			}

			//-----------------------------------------------------

			$my_arr = array();
			for ( $i = 1; $i <= 10; $i++ ) {
				if ( isset( $xtra[$i] ) ) {
					$my_arr['extra'.$xtra[$i]] = 0;
					if ( ! empty( $xtra[$i] ) ) {
						$my_arr['extra' . $xtra[$i]] = $xtra_amounts[$i];
					}
				}
			}

			for ( $i=1; $i <=10; $i++ ) {
				if ( isset( $my_arr['extra'.$i] ) ) {
					$xtra[$i] = $my_arr['extra'.$i];
				} else {
					$xtra[$i] = 0;
				}
			}

			if ( isset( $my_arr['extraf'] ) ) {
				$xtra['f'] = $my_arr['extraf'];
			} else {
				$xtra['f'] = 0;
			}
			if ( isset( $my_arr['extrar'] ) ) {
				$xtra['r'] = $my_arr['extrar'];
			} else {
				$xtra['r'] = 0;
			}

			//-----------------------------------------------------
			// calculate total
			//-----------------------------------------------------

			$post = get_post($pid);

			$sample_price = get_post_meta($pid, 'price', true);
			$sample_price = apply_filters( 'wpjobster_gateway_job_price', $sample_price, $pid );

			$price = $sample_price*$amount;

			$job_price = $sample_price;
			$job_amount = $amount;

			if ( ! is_numeric( $price ) || $price < 0 ) {
				$price = get_option('wpjobster_job_fixed_amount');
			}

			if (empty($currency)) {
				$currency = wpjobster_get_currency();
			}

			//-----------------------------------------------------

			$extr_ttl = 0;
			if ( count( $my_arr ) ) {
				for ( $i = 1; $i <= 10; $i++ ) {
					if ( isset( $my_arr['extra' . $i] ) && $my_arr['extra' . $i] != 0 ) {
						$extra_price = get_post_meta( $pid, 'extra' . $i . '_price', true );
						$extr_ttl += $extra_price * $my_arr['extra' . $i];
					}
				}
				if ( isset( $my_arr['extraf'] ) && $my_arr['extraf'] != 0 ) {
					$extra_price = get_post_meta( $pid, 'extra_fast_price', true );
					$extr_ttl += $extra_price * $my_arr['extraf'];
				}
				if ( isset( $my_arr['extrar'] ) && $my_arr['extrar'] != 0 ) {
					$extra_price = get_post_meta( $pid, 'extra_revision_price', true );
					$extr_ttl += $extra_price * $my_arr['extrar'];
				}
			}

			$shipping   = get_post_meta($pid, 'shipping',       true);
			if(empty($shipping)) $shipping = 0;

			$total = $price + $extr_ttl + $shipping;
			$mc_gross = $total;
			if(empty($shipping)) $shipping = 0;

			if($buyer_chargable_fees==''){
				$buyer_processing_fees = wpjobster_get_site_processing_fee($price, $extr_ttl, $shipping);
			}else{
				$buyer_processing_fees = $buyer_chargable_fees;
			}

			$buyer_site_fees = wpjobster_calculate_fee($mc_gross);

			if($taxable_amount===''){
				$wpjobster_tax_amount =  wpjobster_get_site_tax($price, $extr_ttl, $shipping, $buyer_processing_fees);
			}else{
				$wpjobster_tax_amount = $taxable_amount;//get_user_meta($uid,'wpjobster_taxable_amount', true);
			}
			//-----------------------------------------------------

			$nts      = get_option("purchase_notes_" . $datemade . $uid);
			delete_option("purchase_notes_" . $datemade . $uid);
			$nts = base64_decode($nts);

			//-----------------------------------------------------
			// check if the order was already made
			//-----------------------------------------------------

			global $wpdb;
			$pref = $wpdb->prefix;

			$s1 = "select * from " . $pref . "job_orders where pid='$pid' AND uid='$uid' AND date_made='$datemade'";

			$r1 = $wpdb->get_results($s1);

			$ord_amount = wpjobster_formats_special_exchange($mc_gross, 1, $currency);
			$payedamount = $currency . '|' . $ord_amount;
			$final_payableamount = $currency.'|'.wpjobster_formats_special_exchange($wpjobster_final_payable_amount_original, 1, $currency);


			//-----------------------------------------------------

			$orderid = false;

			//-----------------------------------------------------
			// check if the order was already made
			//-----------------------------------------------------

			if (count($r1) == 0) {

				//-----------------------------------------------------
				// decrease credits if needed
				//-----------------------------------------------------

				if ($with_credits == 1) {

					$crds = wpjobster_get_credits($uid);
					if ($mc_gross+$buyer_processing_fees +$wpjobster_tax_amount > $crds) {  echo __('NO_CREDITS_LEFT','wpjobster'); exit; }
					wpjobster_update_credits($uid, $crds - ($mc_gross+$buyer_processing_fees+$wpjobster_tax_amount));

				}

				//-----------------------------------------------------
				// insert order in db
				//-----------------------------------------------------

				$nts = addslashes($nts);

				$job_title = esc_sql( wpj_encode_emoji( apply_filters( 'wpjobster_insert_order_job_title', $post->post_title, $pid ) ) );
				$job_description = esc_sql( wpj_encode_emoji( $post->post_content ) );
				$job_instructions = esc_sql( wpj_encode_emoji( get_post_meta( $pid, "instruction_box", true ) ) );
				$job_image = wpjobster_get_first_post_image_ID($pid);



				$extra_prices = array();
				$extra_titles = array();
				$extra_amounts = array();
				for ( $i = 1; $i <= 10; $i++ ) {
					$extra_prices[$i] = get_post_meta( $pid, "extra".$i."_price", true );
					$extra_titles[$i] = esc_sql( wpj_encode_emoji( get_post_meta( $pid, "extra".$i."_content", true ) ) );
					$extra_days[$i] = get_post_meta( $pid, "max_days_ex_".$i, true );
					if(!is_numeric($extra_days[$i]))
						$extra_days[$i] = 0;
					$extra_amounts[$i] = $xtra[$i];
				}

				$extra_fast_amount = 0; $extra_fast_price = 0; $extra_fast_days = 0;
				if ( isset( $my_arr['extraf'] ) && $my_arr['extraf'] != 0 ) {
					$extra_fast_amount = $my_arr['extraf'];
					$extra_fast_price = get_post_meta( $pid, 'extra_fast_price', true );
					$extra_fast_days = get_post_meta( $pid, 'extra_fast_days', true );
				}
				$extra_revision_amount = 0; $extra_revision_price = 0; $extra_revision_days = 0;
				if ( isset( $my_arr['extrar'] ) && $my_arr['extrar'] != 0 ) {
					$extra_revision_amount = $my_arr['extrar'];
					$extra_revision_price = get_post_meta( $pid, 'extra_revision_price', true );
					$extra_revision_days = get_post_meta( $pid, 'extra_revision_days', true );
				}

				$payment_status = esc_sql($payment_status);
				$payment_gateway = esc_sql($payment_gateway);
				$payment_details = esc_sql($payment_details);

				$s1  = "insert into " . $pref . "job_orders (payment_status, payment_gateway, payment_details, pid, uid, date_made, mc_gross,processing_fees,site_fees, tax_amount,notes_to_seller,
					extra1, extra2, extra3, extra4, extra5, extra6, extra7, extra8, extra9, extra10,
					payedamount, job_title, job_description, job_instructions, job_image, job_price, job_amount, final_paidamount,
					extra1_price, extra2_price, extra3_price, extra4_price, extra5_price, extra6_price, extra7_price, extra8_price, extra9_price, extra10_price,
					extra1_title, extra2_title, extra3_title, extra4_title, extra5_title, extra6_title, extra7_title, extra8_title, extra9_title, extra10_title, shipping,
					extra1_days, extra2_days, extra3_days, extra4_days, extra5_days, extra6_days, extra7_days, extra8_days, extra9_days, extra10_days,
					extra_fast, extra_fast_price, extra_fast_days,
					extra_revision, extra_revision_price, extra_revision_days)

				values('$payment_status', '$payment_gateway', '$payment_details', '$pid', '$uid', '$datemade', '$mc_gross','$buyer_processing_fees',$buyer_site_fees,'$wpjobster_tax_amount', '$nts',
					'$xtra[1]', '$xtra[2]', '$xtra[3]', '$xtra[4]', '$xtra[5]', '$xtra[6]', '$xtra[7]', '$xtra[8]', '$xtra[9]', '$xtra[10]',
					'$payedamount', '$job_title', '$job_description', '$job_instructions', '$job_image', '$job_price', '$job_amount', '$final_payableamount',
					'$extra_prices[1]', '$extra_prices[2]', '$extra_prices[3]', '$extra_prices[4]', '$extra_prices[5]', '$extra_prices[6]', '$extra_prices[7]', '$extra_prices[8]', '$extra_prices[9]', '$extra_prices[10]',
					'$extra_titles[1]', '$extra_titles[2]', '$extra_titles[3]', '$extra_titles[4]', '$extra_titles[5]', '$extra_titles[6]', '$extra_titles[7]', '$extra_titles[8]', '$extra_titles[9]', '$extra_titles[10]', '$shipping',
					'$extra_days[1]', '$extra_days[2]', '$extra_days[3]', '$extra_days[4]', '$extra_days[5]', '$extra_days[6]', '$extra_days[7]', '$extra_days[8]', '$extra_days[9]', '$extra_days[10]',
					'$extra_fast_amount', '$extra_fast_price', '$extra_fast_days',
					'$extra_revision_amount', '$extra_revision_price', '$extra_revision_days')";

				$wpdb->query($s1);

				//-----------------------------------------------------

				$s1      = "select * from " . $pref . "job_orders where pid='$pid' AND uid='$uid' AND date_made='$datemade'";
				$r1      = $wpdb->get_results($s1);
				$orderid = $r1[0]->id;

				//-----------------------------------------------------
				if(!isset($ccc)){
					$ccc='';
				}
				if($payment_status == 'completed'){

					$g1 = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','0','$orderid','$ccc')";
					$wpdb->query($g1);
					wpj_update_user_notifications( $post->post_author, 'notifications', +1 );
				}

				//-----------------------------------------------------

				$uid_a = get_post($pid);
				$uid_a = $uid_a->post_author;

				$s1 = "insert into " . $pref . "job_ratings (orderid, uid, pid) values('$orderid','$uid_a','$pid')";

				$wpdb->query($s1);

				//-----------------------------------------------------

				$sales = get_post_meta($pid, 'sales', true);
				if (empty($sales))
						$sales = 1;
				else
						$sales = $sales + 1;

				update_post_meta($pid, 'sales', $sales);

				if (get_post_type($pid) == 'offer') {
						update_post_meta($pid, 'offer_accepted', 1);
				}

				//-----------------------------------------------------
				// store logs, send emails
				//-----------------------------------------------------

				if (get_post_type($pid) == 'offer') {

						wpjobster_send_email_allinone_translated('offer_acc_buyer', $uid, $post->post_author, $pid, $orderid);
						wpjobster_send_email_allinone_translated('offer_acc_seller', $post->post_author, $uid, $pid, $orderid);

						wpjobster_send_sms_allinone_translated('offer_acc_buyer', $uid, $post->post_author, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('offer_acc_seller', $post->post_author, $uid, $pid, $orderid);

				} else {

					if ($payment_status == 'pending') {

					} else {

						wpjobster_send_email_allinone_translated('purchased_buyer', $uid, $post->post_author, $pid, $orderid);
						wpjobster_send_email_allinone_translated('purchased_seller', $post->post_author, $uid, $pid, $orderid);

						wpjobster_send_sms_allinone_translated('purchased_buyer', $uid, $post->post_author, $pid, $orderid);
						wpjobster_send_sms_allinone_translated('purchased_seller', $post->post_author, $uid, $pid, $orderid);
					}

				}
				if($payment_status != 'pending' && $payment_status != 'failed' && $payment_status != 'cancelled'){
					wpjobster_maintain_log($orderid, $post->post_title, $mc_gross, $uid, $pid, $post->post_author, $buyer_processing_fees, $wpjobster_tax_amount);
					// this runs for credits
					do_action( 'wpjobster_job_payment_completed', $orderid );
				}

			}

			//-----------------------------------------------------
			// success
			//-----------------------------------------------------
			return $orderid;
		}

		return false;
	}
}


add_action( 'wpjobster_job_payment_completed', 'wpjobster_set_order_delivery_time', 10, 1 );
function wpjobster_set_order_delivery_time( $order ) {
	if ( ! is_object( $order ) ) {
		$order = wpjobster_get_order( $order );
	}

	$date_paid         = time();
	$delivery_days     = get_post_meta( $order->pid, 'max_days', true );

	if ( $order->extra_fast != 0 ) {
		$delivery_days     = $order->extra_fast_days;
	}

	if($order->extra1>0) $delivery_days += $order->extra1_days;
	if($order->extra2>0) $delivery_days += $order->extra2_days;
	if($order->extra3>0) $delivery_days += $order->extra3_days;
	if($order->extra4>0) $delivery_days += $order->extra4_days;
	if($order->extra5>0) $delivery_days += $order->extra5_days;
	if($order->extra6>0) $delivery_days += $order->extra6_days;
	if($order->extra7>0) $delivery_days += $order->extra7_days;
	if($order->extra8>0) $delivery_days += $order->extra8_days;
	if($order->extra9>0) $delivery_days += $order->extra9_days;
	if($order->extra10>0) $delivery_days += $order->extra10_days;

	if($order->extra_revision_days>0) $delivery_days += $order->extra_revision_days;

	$expected_delivery = $date_paid + ( 24 * 3600 * $delivery_days );

	wpj_update_expected_delivery( $order, $expected_delivery );
}


function wpjobster_maintain_log($orderid, $post_title, $mc_gross, $uid, $pid, $post_author, $buyer_processing_fees = 0, $wpjobster_tax_amount = 0) {
	global $wpdb;
	$order_url = get_bloginfo('url') . '/?jb_action=chat_box&oid=' . $orderid;
	$reason = __('Payment made for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_title . '</a>';
	wpjobster_add_history_log('0', $reason, $mc_gross,$uid, '', $orderid, 3, '');
	if ($buyer_processing_fees > 0) {
		$reason = __('Processing fee for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_title . '</a>';
		wpjobster_add_history_log('0', $reason, $buyer_processing_fees,$uid, '', $orderid, 13, '');
	}
	if ($wpjobster_tax_amount > 0) {
		$reason = __('Tax for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_title . '</a>';
		wpjobster_add_history_log('0', $reason, $wpjobster_tax_amount,$uid, '', $orderid, 14, '');
	}

	$reason = __('Payment collected for', 'wpjobster') . ': <a href="' . $order_url . '">' . $post_title . '</a>';
	wpjobster_add_history_log('2', $reason, $mc_gross,$post_author, '', $orderid, 4, '');

	//-----------------------------------------------------

	$instant = get_post_meta($pid, 'instant', true);
	if ($instant == "1") {
		$tm = current_time('timestamp', 1);
		$s  = "update " . $wpdb->prefix . "job_orders set done_seller='1', date_finished='$tm' where id='$orderid' ";
		$wpdb->query($s);
		$ccc = __('Delivered', 'wpjobster');
		$g1 = "insert into " . $wpdb->prefix . "job_chatbox (datemade, uid, oid, content) values('$tm','-1','$orderid','$ccc')";
		$wpdb->query($g1);
		wpj_update_user_notifications( $uid, 'notifications', +1 );

		if (get_post_type($pid) == 'offer') {
				wpjobster_send_email_allinone_translated('order_offer_delivered', $uid, false, $pid, $orderid);
				wpjobster_send_sms_allinone_translated('order_offer_delivered', $uid, false, $pid, $orderid);
		} else {
				wpjobster_send_email_allinone_translated('order_delivered', $uid, false, $pid, $orderid);
				wpjobster_send_sms_allinone_translated('order_delivered', $uid, false, $pid, $orderid);
		}
	}

	//-----------------------------------------------------

	$post_title = htmlspecialchars_decode($post_title);
	$admin_email = get_bloginfo('admin_email');
	$message     = sprintf(__('A new job has been purchased on your site: <a href="%s">%s</a>', 'wpjobster'), get_permalink($pid), $post_title);
	wpjobster_send_email($admin_email, sprintf(__('New Job Purchased on your site - %s', 'wpjobster'), $post_title), $message);
	wpjobster_purchase_completed_functions($uid);

}

if (!function_exists('wpjobster_get_order')) {
	function wpjobster_get_order($oid) {

		$oid = esc_sql($oid);

		if (is_numeric($oid)) {
			global $wpdb;
			$pref = $wpdb->prefix;

			$sql = "SELECT * FROM {$wpdb->prefix}job_orders WHERE  id = %d";
			$result = $wpdb->get_row($wpdb->prepare($sql, $oid));

			return $result;
		}

		return false;
	}
}

if (!function_exists('wpjobster_update_order_meta')) {
	function wpjobster_update_order_meta($oid, $meta_key, $meta_value) {

		$oid = esc_sql($oid);
		$meta_key = esc_sql($meta_key);
		$meta_value = esc_sql( maybe_serialize( wpj_encode_emoji( $meta_value ) ) );

		// define what can be updated with this function
		$allowed_meta_keys = array(
			'payment_status',
			'payment_gateway',
			'payment_response',
			'payment_details',
			'custom_extras'
		);

		if (in_array($meta_key, $allowed_meta_keys)) {
			if (is_numeric($oid)) {
				if (wpjobster_get_order($oid)) {
					global $wpdb;
					$pref = $wpdb->prefix;

					$sql = "UPDATE {$wpdb->prefix}job_orders SET $meta_key = '$meta_value' WHERE id = %d";
					$result = $wpdb->query($wpdb->prepare($sql, $oid));

					// $wpdb->query returns the number of rows updated
					// so it can be 0, which is not an error
					if ($result === false) {
						return false;
					}

					return true;
				}

				// order not exists
				return false;
			}

			// order id not numeric
			return false;
		}

		// meta not allowed
		return false;
	}
}


if (!function_exists('wpjobster_get_order_meta')) {
	function wpjobster_get_order_meta($oid, $meta_key) {
		$oid = esc_sql($oid);
		$meta_key = esc_sql($meta_key);

		if (is_numeric($oid)) {
			$order = wpjobster_get_order($oid);

			if ($order) {
				if (isset($order->$meta_key)) {
					return maybe_unserialize($order->$meta_key);
				}

				// meta not exists
				return false;
			}

			// order not exists
			return false;
		}

		// order id not numeric
		return false;
	}
}

// paypal form
function get_tab_id($wpjobster_payment_gateways){
	global $gateway_index;
	$this_index = 0;
	foreach($wpjobster_payment_gateways as $index=>$gateway){
		if($gateway_index==$this_index){
			$gateway_index++;
			return $gateway['unique_id'];
		}
		$this_index++;
	}
	$gateway_index++;
}
if(!function_exists('show_paypal_form')){
	function show_paypal_form($wpjobster_payment_gateways,$arr,$arr_pages){
		global $payment_type_enable_arr;
		$tab_id = get_tab_id($wpjobster_payment_gateways); ?>

		<div id="tabs<?php echo $tab_id?>">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs<?php echo $tab_id;?>">
				<table width="100%" class="sitemile-table">
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="200"><?php _e('Enable:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_paypal_enable', 'no' ); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Put the Paypal Button caption you want user to see on purchase page "); ?></td>
						<td><?php _e('Paypal Button caption:','wpjobster'); ?></td>
						<td><input type="text" size="45" name="wpjobster_paypal_button_caption" value="<?php echo get_option('wpjobster_paypal_button_caption'); ?>"/></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable testing mode', 'wpjobster')); ?></td>
						<td width="200"><?php _e('Enable Sandbox:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_paypal_enable_sdbx'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="200"><?php _e('Enable Withdrawal:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_paypal_enable_withdrawal'); ?></td>
					</tr>

					<?php foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
						if($payment_type_enable_key != 'job_purchase'){ ?>
							<tr>
								<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
								<td width="200"><?php echo $payment_type_enable['enable_label']; ?></td>
								<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_paypal_enable_'.$payment_type_enable_key); ?></td>
							</tr>
					<?php }//end if
					} // end foreach ?>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set it to yes only if you know what it means and you have the SSL API certificates from PayPal on your server!', 'wpjobster')); ?></td>
						<td width="200"><?php _e('Certificate installed on the server:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_paypal_enable_secure'); ?> <?php _e('(In most of the cases it should be set to No)', 'wpjobster') ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td ><?php _e('PayPal Email Address:','wpjobster'); ?></td>
						<td><input type="text" size="45" name="wpjobster_paypal_email" value="<?php echo apply_filters( 'wpj_sensitive_info_email', get_option('wpjobster_paypal_email') ); ?>"/></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Please select a page to show when paypal payment successful."); ?></td>
						<td><?php _e('Trasaction success page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_paypal_success_page','', ' class="select2" '); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Transaction failure page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_paypal_failure_page','', ' class="select2" '); ?> </td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Automatic Withdrawals", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('PayPal Client ID:','wpjobster'); ?></td>
						<td><input type="text" name="wpjobster_theme_appid" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_appid') ); ?>" size="55" /> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('PayPal Secret:','wpjobster'); ?></td>
						<td><input type="password" name="wpjobster_theme_appsecret" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_appsecret') ); ?>" size="55" /> </td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Subscriptions", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Paypal API Username:','wpjobster'); ?></td>
						<td><input type="text" name="wpjobster_theme_apiusername" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_apiusername') ); ?>" size="55" /> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Paypal API Password:','wpjobster'); ?></td>
						<td><input type="password" name="wpjobster_theme_apipassword" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_apipassword') ); ?>" size="55" /> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Paypal API Signature:','wpjobster'); ?></td>
						<td><input type="text" name="wpjobster_theme_apisignature" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_theme_apisignature') ); ?>" size="55" /> </td>
					</tr>

					<tr>
						<td>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$(".select2").select2();
								});
							</script>
						</td>
						<td></td>
						<td>
							<p><?php _e('Regular job purchases will work with the PayPal Email Address only.', 'wpjobster'); ?></p>
							<p><?php _e('Client ID and Secret are needed for the mass withdrawals from admin.', 'wpjobster'); ?></p>
						</td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><input type="submit" name="wpjobster_save_paypal" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>
		</div>
	<?php }
}// endif


if(!function_exists('show_cod_form')){
	function show_cod_form($wpjobster_payment_gateways,$arr,$arr_pages){
		global $payment_type_enable_arr;
		$tab_id = get_tab_id($wpjobster_payment_gateways); ?>
		<div id="tabs<?php echo $tab_id?>">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs<?php echo $tab_id?>">

				<table width="100%" class="sitemile-table">

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Enable/Disable Cash on delivery"); ?></td>
						<td width="200"><?php _e('Enable Cash On Delivery:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_cod_enable', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Please select a page to show when Cash On Delivery transaction successful."); ?></td>
						<td><?php _e('Trasaction success page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_cod_success_page','', ' class="select2" '); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Transaction failure page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_cod_failure_page','', ' class="select2" '); ?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Put the Cash On Delivery button caption you want user to see on purchase page "); ?></td>
						<td><?php _e('COD Button caption:','wpjobster'); ?></td>
						<td><input type="text" size="45" name="wpjobster_cod_button_caption" value="<?php echo get_option('wpjobster_cod_button_caption'); ?>"/>
						</td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><input type="submit" name="wpjobster_save_cod" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>

			</form>
		</div>
	<?php }// endif function show_cod_form
}//endif show_cod_form

if(!function_exists('show_banktransfer_form')){
	function show_banktransfer_form($wpjobster_payment_gateways,$arr,$arr_pages){
		global $payment_type_enable_arr;
		$tab_id = get_tab_id($wpjobster_payment_gateways);

		// topup processing actions
		add_action("take_to_bank_transfer_credit","taketogateway_bank_transfer_function");
		add_action("topup_bank_transfer_response","processgateway_bank_transfer_function");

		//featured processing actions
		add_action("take_to_bank_transfer_featured","featured_taketogateway_bank_transfer_function");
		add_action("featured_bank_transfer_response","processgateway_bank_transfer_function");
		?>

		<div id="tabs<?php echo $tab_id?>">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs<?php echo $tab_id?>">
				<table width="100%" class="sitemile-table">
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Enable/Disable Bank transfer"); ?></td>
						<td width="200"><?php _e('Enable bank transfer:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_banktransfer_enable', 'no'); ?></td>
					</tr>

					<?php foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
						if($payment_type_enable_key != 'job_purchase' && $payment_type_enable_key != 'subscription'){ ?>
							<tr>
								<td valign=top width="22"><?php wpjobster_theme_bullet($payment_type_enable['hint_label']); ?></td>
								<td width="200"><?php echo $payment_type_enable['enable_label']; ?></td>
								<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_banktransfer_enable_'.$payment_type_enable_key); ?></td>
							</tr>
					<?php }//end if
					} // end foreach ?>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Please select a page to show when bank transfer transaction successful and include your bank details in it."); ?></td>
						<td><?php _e('Trasaction success page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_banktransfer_success_page','', ' class="select2" '); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td><?php _e('Transaction failure page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_banktransfer_failure_page','', ' class="select2" '); ?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Put the bank bank details required."); ?></td>
						<td><?php _e('Bank Details:','wpjobster'); ?></td>
						<td><textarea style='height:100px;width:350px;' name="wpjobster_bank_details" ><?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_bank_details') ); ?></textarea>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet("Put the bank transfer Button caption you want user to see on purchase page "); ?></td>
						<td><?php _e('Bank Trasnfer Button caption:','wpjobster'); ?></td>
						<td><input type="text" size="45" name="wpjobster_banktransfer_button_caption" value="<?php echo get_option('wpjobster_banktransfer_button_caption'); ?>"/></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><input type="submit" name="wpjobster_save_banktransfer" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>
	<?php }// endif function show_banktransfer_form
}//endif show_banktransfer_form

if(!function_exists("wpjobster_payment_methods_action_function")){
	function wpjobster_payment_methods_action_function(){
		global $payment_type_enable_arr;
		// ###### ACTIONS ON SAVE FOR EVERY METHOD #####
		// (the updates made to the gateways options in admin)
		// Cash On Delivery
		if(isset($_POST['wpjobster_save_cod']))
		{
			update_option('wpjobster_cod_enable', trim($_POST['wpjobster_cod_enable']));

			update_option('wpjobster_cod_button_caption', trim($_POST['wpjobster_cod_button_caption']));
			update_option('wpjobster_cod_success_page', trim($_POST['wpjobster_cod_success_page']));
			update_option('wpjobster_cod_failure_page', trim($_POST['wpjobster_cod_failure_page']));

			echo '<div class="updated fade">'.__('Settings saved!','wpjobster').'</div>';
		}

		// Bank transfer
		if(isset($_POST['wpjobster_save_banktransfer']))
		{
			update_option('wpjobster_banktransfer_enable', trim($_POST['wpjobster_banktransfer_enable']));

			foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
				if($payment_type_enable_key != 'job_purchase' && $payment_type_enable_key != 'subscription'){
					if(isset($_POST['wpjobster_banktransfer_enable_'.$payment_type_enable_key]))
						update_option('wpjobster_banktransfer_enable_'.$payment_type_enable_key, trim($_POST['wpjobster_banktransfer_enable_'.$payment_type_enable_key]));
				}
			}

			update_option('wpjobster_banktransfer_button_caption', trim($_POST['wpjobster_banktransfer_button_caption']));
			update_option('wpjobster_bank_details', trim($_POST['wpjobster_bank_details']));
			update_option('wpjobster_banktransfer_success_page', trim($_POST['wpjobster_banktransfer_success_page']));
			update_option('wpjobster_banktransfer_failure_page', trim($_POST['wpjobster_banktransfer_failure_page']));

			echo '<div class="updated fade">'.__('Settings saved!','wpjobster').'</div>';
		}

		// paypal
		if(isset($_POST['wpjobster_save_paypal']))
		{
			update_option('wpjobster_paypal_enable',  trim($_POST['wpjobster_paypal_enable']));
			update_option('wpjobster_paypal_email',     trim($_POST['wpjobster_paypal_email']));
			update_option('wpjobster_paypal_enable_sdbx', trim($_POST['wpjobster_paypal_enable_sdbx']));
			update_option('wpjobster_paypal_button_caption', trim($_POST['wpjobster_paypal_button_caption']));

			update_option('wpjobster_paypal_enable_withdrawal', trim($_POST['wpjobster_paypal_enable_withdrawal']));

			foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
				if($payment_type_enable_key != 'job_purchase'){
					if(isset($_POST['wpjobster_paypal_enable_'.$payment_type_enable_key]))
						update_option('wpjobster_paypal_enable_'.$payment_type_enable_key, trim($_POST['wpjobster_paypal_enable_'.$payment_type_enable_key]));
				}
			}

			update_option('wpjobster_paypal_enable_secure', trim($_POST['wpjobster_paypal_enable_secure']));
			update_option('wpjobster_paypal_success_page', trim($_POST['wpjobster_paypal_success_page']));
			update_option('wpjobster_paypal_failure_page', trim($_POST['wpjobster_paypal_failure_page']));
			update_option('wpjobster_theme_appid',        trim($_POST['wpjobster_theme_appid']));
			update_option('wpjobster_theme_appsecret',        trim($_POST['wpjobster_theme_appsecret']));

			update_option('wpjobster_theme_apiusername',        trim($_POST['wpjobster_theme_apiusername']));
			update_option('wpjobster_theme_apipassword',        trim($_POST['wpjobster_theme_apipassword']));
			update_option('wpjobster_theme_apisignature',        trim($_POST['wpjobster_theme_apisignature']));

			echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
		}

	}// end function
}//endif

if(!function_exists('wpjobster_show_gateway_demouser')){
	function wpjobster_show_gateway_demouser($wpjobster_success_page_id=0){

		global $wpdb;
		$pref = $wpdb->prefix;
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;

		$s = "select * from ".$pref."job_orders where uid='$uid' order by id desc";
		$r = $wpdb->get_results($s);
		$last_row = $r[0];
		$last_row_id = $last_row->id;
		wp_redirect(get_bloginfo('url').'/?jb_action=chat_box&oid='.$last_row_id);
		exit();
	}//end function
}// endif


if ( ! function_exists( 'wpjobster_take_to_payment_gateway' ) ) {
	function wpjobster_take_to_payment_gateway( $pid, $main_amount, $extrs2 = '', $extrs_amounts2 = '', $total_amount = '' ) {
		?>
		<script type="text/javascript">
		function take_to_gateway( gateway_name, enable_popup ) {

			base_url = "<?php echo bloginfo('url')?>";
			base_url = base_url + '/?pay_for_item=' + gateway_name;
			base_url = base_url + '&jobid=<?php echo $pid; ?>';
			base_url = base_url + '&amount=<?php echo $main_amount; ?>';
			base_url = base_url + '&extras=<?php echo $extrs2; ?>';
			base_url = base_url + '&extras_amounts=<?php echo $extrs_amounts2; ?>';

			if( enable_popup === 'yes' ) {

				jQuery.ajax({
					type: "POST",
					url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
					data: {
						action: 'wpjobster_check_payment_gateway_popup',
						jobid: '<?php echo $pid; ?>',
						amount: '<?php echo $main_amount; ?>',
						extras: '<?php echo $extrs2; ?>',
						extras_amounts: '<?php echo $extrs_amounts2; ?>',
						gateway: gateway_name,
						total_amount: '<?php echo $total_amount; ?>',
					},
					success: function (output) {
						jQuery(".payment-gateway-popup").html(output);
					}
				});

				return false;

			} else {
				window.location = base_url;
			}
		}
		</script>
		<?php
	}
}
add_action( 'wpjobster_check_payment_gateway', 'wpjobster_take_to_payment_gateway', 10, 5 );


if ( ! function_exists( 'wpjobster_take_to_payment_gateway2' ) ) {
	function wpjobster_take_to_payment_gateway2( $oid, $main_amount, $custom_extra_id ) {
		?>
		<script type="text/javascript">
			function take_to_gateway( gateway_name, enable_popup ) {

				base_url = "<?php echo bloginfo('url')?>";
				base_url = base_url + '/?pay_for_item=' + gateway_name;
				base_url = base_url + '&payment_type=custom_extra';
				base_url = base_url + '&oid=<?php echo $oid; ?>';
				base_url = base_url + '&amount=<?php echo $main_amount; ?>';
				base_url = base_url + '&custom_extra=<?php echo $custom_extra_id; ?>';

				if( enable_popup === 'yes' ) {

					jQuery.ajax({
						type: "POST",
						url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
						data: {
							action: 'wpjobster_check_payment_gateway_popup',
							payment_type: 'custom_extra',
							oid: '<?php echo $oid; ?>',
							amount: '<?php echo $main_amount; ?>',
							custom_extra: '<?php echo $custom_extra_id; ?>',
							gateway: gateway_name
						},
						success: function (output) {
							jQuery(".payment-gateway-popup").html(output);
						}
					});

					return false;

				} else {
					window.location = base_url;
				}
			}
		</script>
		<?php
	}
}
add_action( 'wpjobster_check_payment_gateway2', 'wpjobster_take_to_payment_gateway2', 10, 4 );


add_action( 'wp_ajax_wpjobster_check_payment_gateway_popup', 'wpjobster_take_to_payment_gateway_popup' );
function wpjobster_take_to_payment_gateway_popup() {

	$payment_type = isset( $_POST['payment_type'] ) ? $_POST['payment_type'] : 'job_purchase';
	$gateway = $_POST['gateway'];

	if( $payment_type == 'topup' ) {
		$package_id = $_POST['package_id'];
	} elseif( $payment_type == 'feature' ) {
		$pid_feature = $_REQUEST['jobid'];
		$h_start_date = $_REQUEST['h_date_start'];
		$c_start_date = $_REQUEST['c_date_start'];
		$s_start_date = $_REQUEST['s_date_start'];
		$feature_pages = $_REQUEST['feature_pages'];
	} elseif( $payment_type == 'subscription' ){
		$sub_amount = $_REQUEST['sub_amount'];
		$sub_type = $_REQUEST['sub_type'];
		$sub_level = $_REQUEST['sub_level'];
		$user_id = $_REQUEST['user_id'];
	} elseif( $payment_type =='custom_extra' ) {
		$oid_custom_extra = $_REQUEST['oid'];
		$amount = $_REQUEST['amount'];
		$custom_extra = $_REQUEST['custom_extra'];
	} else {
		$pid = $_POST['jobid'];
		$main_amount = $_POST['amount'];
		$extrs2 = $_POST['extras'];
		$extrs_amounts2 = $_POST['extras_amounts'];
	}

	$gateway_developed_in_theme = get_option("wpjobster_{$gateway}_developed_in_theme");

	if( $gateway_developed_in_theme == "yes" ) {
		include get_template_directory() . "/lib/gateways/wpjobster_{$gateway}.php";
	} else {
		$plugin_path = WP_PLUGIN_DIR;
		include $plugin_path . "/wpjobster-{$gateway}/wpjobster-{$gateway}.php";
	}
	exit;
}

//If required, call in payment gateway plugins e.g. Midtrans
if ( ! function_exists( 'wpjobster_all_common_payment_type_classes' ) ) {
	function wpjobster_all_common_payment_type_classes( $payment_type, $payment_gateway_unique_slug ) {
		if($payment_type=='job_purchase'){
			if(!class_exists('wpjobster_common_job_purchase')){
					include_once get_template_directory()."/lib/gateways/wpjobster_common_job_purchase.php";
					$wcjp = new WPJ_Common_Job_Purchase($payment_gateway_unique_slug);
			}
		}elseif($payment_type=='feature'){
			if(!class_exists('wpjobster_common_featured')){
					include_once get_template_directory()."/lib/gateways/wpjobster_common_featured.php";
					$wcf = new WPJ_Common_Featured($payment_gateway_unique_slug);
			}
		}elseif($payment_type=='topup'){
			if(!class_exists('wpjobster_common_topup')){
					include_once get_template_directory()."/lib/gateways/".'wpjobster_common_topup.php';
					$wct = new WPJ_Common_Topup($payment_gateway_unique_slug);
			}
		}elseif($payment_type=='custom_extra') {
			if(!class_exists('wpjobster_common_custom_extra')){
					include_once get_template_directory()."/lib/gateways/".'wpjobster_common_custom_extra.php';
					$wcce = new WPJ_Common_Custom_Extra($payment_gateway_unique_slug);
			}
		}elseif( $payment_type == 'subscription' ) {
			include_once get_template_directory()."/lib/".'wpjobster_subscription.php';
		}else{
			echo __("Something went wrong. No payment type defined","wpjobster");
			die();
		}
	}
}
add_action( 'wpjobster_include_all_common_payment_type_files', 'wpjobster_all_common_payment_type_classes', 10,2 );

//Get payment status by order id and payment type and redirect
//If required, call in payment gateway plugins e.g. Mollie
if( ! function_exists( 'wpjobster_redirect_after_success_failed_transaction_fun' ) ) {
	function wpjobster_redirect_after_success_failed_transaction_fun( $order_id, $payment_type, $gateway_unique_slug, $payment_details, $payment_response ) {

			global $wpdb;
			$pref = $wpdb->prefix;

			if( $payment_type == 'feature' ) {

				$select_package = "select * from ".$pref."job_featured_orders where id='$order_id'";
				$r = $wpdb->get_results($select_package);
				$order_info = isset($r['0'])?$r['0']:0;
				$payment_status             = $order_info->payment_status;

			} elseif( $payment_type == 'topup' ) {

				$select_package = "select * from ".$pref."job_topup_orders where id='$order_id'";
				$r = $wpdb->get_results($select_package);
				$order_info = isset($r['0'])?$r['0']:0;
				$payment_status             = $order_info->payment_status;

			} elseif( $payment_type == 'job_purchase' ) {
				$order_info = wpjobster_get_order_details_by_orderid( $order_id );
				$payment_status             = $order_info->payment_status;


			} elseif( $payment_type == 'custom_extra' ) {

				$select_custom_extra = "select * from ".$pref."job_custom_extra_orders where id='$order_id'";
				$r = $wpdb->get_results($select_custom_extra);
				$order_info = isset($r['0'])?$r['0']:0;
				$payment_status = $order_info->payment_status;

			}

			if( $payment_status == 'completed' ) {
					do_action("wpjobster_".$payment_type."_payment_success",$order_id,$gateway_unique_slug,$payment_details,$payment_response);
			} elseif ( $payment_status == 'cancelled' ) {
					do_action("wpjobster_".$payment_type."_payment_failed",$order_id,$gateway_unique_slug,$payment_details,$payment_response);
			}

	}
}
add_action( 'wpjobster_redirect_after_success_failed_transaction', 'wpjobster_redirect_after_success_failed_transaction_fun', 10, 5 );

//Payment successful redirection only
//If payment status already updated and only need redirection e.g. Payfast
if( ! function_exists( 'wpjobster_payment_success_redirection_fun' ) ) {
	function wpjobster_payment_success_redirection_fun( $order_id, $payment_type, $gateway ) {

		global $wpdb;
		$pref = $wpdb->prefix;

		if( $payment_type == 'feature' ) {

			if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
				wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-11");
			}else{
				$sql = " select job_id from ".$pref."job_featured_orders
						 where id='$order_id'";
				$select_result = $wpdb->get_results($sql);
				$select_row = $select_result[0];
				$job_id = $select_row -> job_id;

				wp_redirect(get_bloginfo('siteurl')."/?jb_action=feature_job&status=success&jobid=".$job_id);
			}
			die();

		} elseif( $payment_type == 'topup' ) {

			if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
				wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-10");
			}else{
				wp_redirect(get_bloginfo('siteurl')."/?jb_action=show_bank_details&oid={$order_id}&payment_type=topup");
			}
			exit;

		} elseif( $payment_type == 'job_purchase' ) {

			$wpjobster_success_page_id=get_option("wpjobster_{$gateway}_success_page");
			if( $wpjobster_success_page_id!='' && $wpjobster_success_page_id!='0' ){
					wp_redirect(get_permalink($wpjobster_success_page_id));
			}else{
					wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$order_id);
			}
			exit;

		} elseif( $payment_type == 'custom_extra' ) {

			if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
				wp_redirect($_SERVER['HTTP_REFERER']."&status=success&active_tab=tabs-12");
			}else{

				$sql = " select order_id from ".$pref."job_custom_extra_orders
						 where id='$order_id'";
				$select_result = $wpdb->get_results($sql);
				$select_row = $select_result[0];
				$order_id = $select_row -> order_id;

				wp_redirect(get_bloginfo('siteurl')."/?jb_action=chat_box&oid=".$order_id);
			}
			die();

		} elseif( $payment_type == 'subscription' ) {
			wp_redirect( get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) . '?sub_action=details&thankyou=1&message_code=success' );
			exit;
		}
	}
}
add_action( 'wpjobster_payment_success_redirection', 'wpjobster_payment_success_redirection_fun', 10, 3 );


//Payment failed redirection only
//If payment status already updated and only need redirection e.g. Midtrans
if( ! function_exists( 'wpjobster_payment_failed_redirection_fun' ) ) {
	function wpjobster_payment_failed_redirection_fun( $order_id, $payment_type, $gateway ) {

			global $wpdb;
			$pref = $wpdb->prefix;

			if( $payment_type == 'feature' ) {

				if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
					wp_redirect($_SERVER['HTTP_REFERER']."&status=fail");
				}else{
						$sql = " select job_id from ".$pref."job_featured_orders
							where id='$order_id'";
					$select_result = $wpdb->get_results($sql);
					$select_row = $select_result[0];
					$job_id = $select_row -> job_id;
					wp_redirect(get_bloginfo('siteurl')."/?jb_action=feature_job&status=fail&jobid=".$job_id);
				}
				exit;

			} elseif( $payment_type == 'topup' ) {

				if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
					wp_redirect($_SERVER['HTTP_REFERER']."&status=fail&active_tab=tabs-10");
				}else{
					wp_redirect(get_bloginfo('siteurl')."/?jb_action=show_bank_details&oid=$order_id&payment_type=topup");
				}
				exit;

			} elseif( $payment_type == 'job_purchase' ) {
				$wpjobster_failure_page_id=get_option("wpjobster_{$gateway}_failure_page");
				if( $wpjobster_failure_page_id!='' && $wpjobster_failure_page_id!='0' ){
						wp_redirect(get_permalink($wpjobster_failure_page_id));
				}else{
						wp_redirect(get_bloginfo('siteurl').'/?jb_action=chat_box&oid='.$order_id);
				}
				exit;

			} elseif( $payment_type == 'custom_extra' ) {

				if(strpos($_SERVER['HTTP_REFERER'], 'page=order-stats') && strpos($_SERVER['HTTP_REFERER'], 'admin.php')){
					wp_redirect($_SERVER['HTTP_REFERER']."&status=fail");
				}else{

					$sql = " select order_id from ".$pref."job_custom_extra_orders where id='$order_id'";
					$select_result = $wpdb->get_results($sql);
					$select_row = $select_result[0];
					$order_id = $select_row -> order_id;

					wp_redirect(get_bloginfo('siteurl')."/?jb_action=chat_box&oid=".$order_id."&status=fail");
				}
				exit;

			}

	}
}
add_action( 'wpjobster_payment_fail_redirection', 'wpjobster_payment_failed_redirection_fun', 10, 3 );

function wpj_plugins_enabled_error() {

	if ( ! PAnD::is_admin_notice_active( 'disable-gateway-done-notice-forever' ) ) {
		return;
	}

	$gateways = array( 'Stripe', '2Checkout', 'Payza', 'Braintree', 'Authorize' );

	$is_gateway_ok = 0;
	$message_gat = '';
	foreach ( $gateways as $gateway ) {
		$$gateway = get_option( 'wpjobster_' . strtolower( $gateway ) . '_enable' );

		if ( $$gateway == 'yes' ) {
			if ( ! class_exists( "WPJobster_" . $gateway . "_Loader" ) ) {
				$message_gat .= '- ' . $gateway . ' Gateway' . '<br>';

				$is_gateway_ok = 1;
			}
		}
	}

	$plugins = array( 'wpjobster-account-segregation', 'wpjobster-seller-notifications', 'wpjobster-affiliate', 'wpjobster-invoices' );

	$is_plugin_ok = 0;
	$message_plg = '';
	foreach ( $plugins as $plugin ) {
		if ( is_plugin_active( $plugin . '/' . $plugin . '.php' ) ) {
			$plugin_underscore = str_replace('-', '_', $plugin);
			$$plugin_underscore = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin . '/' . $plugin . '.php' );

			if( ${$plugin_underscore}['Version'] ){
				if( ${$plugin_underscore}['Version'] < '2.0.0' ) {
					$message_plg .= '- ' . ucwords( str_replace('-', ' ', $plugin) ) . '<br>';

					$is_plugin_ok = 1;
				}
			}
		}
	}

	if ( $is_gateway_ok == 1 || $is_plugin_ok == 1 ) {
		echo '<div data-dismissible=disable-gateway-done-notice-forever" class="is-dismissible notice notice-warning padd10">';
			if( $is_gateway_ok == 1 ){
				echo '<h2>' . __( 'Gateways', 'wpjobster' ) . '</h2>';

				$lbl_gat = __( 'Some of the gateways you used before need to be installed as plugins if you want to keep using them.', 'wpjobster' );
				$lbl_gat .= '<br>';
				$lbl_gat .= __( "Don't worry, you don't have to pay extra. If your license included them before, you will receive them for free. Please head to our support center for instructions.", 'wpjobster' );
				$lbl_gat .= '<br>';

				echo $lbl_gat;
				echo $message_gat;
			}

			if( $is_plugin_ok == 1 ){
				echo '<h2>' . __( 'Other Plugin Updates', 'wpjobster' ) . '</h2>';

				$lbl_plg = __( 'The following plugins need to be updated to the latest version in order to be compatible with Jobster v5.0.0+' );
				$lbl_plg .= '<br>';

				echo $lbl_plg;
				echo $message_plg;
			}


			?>
			<br>
			<a class="button action" href="<?php echo admin_url( 'plugins.php' ); ?>"><?php _e( 'Plugins', 'wpjobster' ); ?></a>
			<?php
		echo '</div>';
	}
}
add_action( 'admin_notices', 'wpj_plugins_enabled_error' );
