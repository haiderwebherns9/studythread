<?php

function wpj_payments_vars() {

	$vars = array();

	global $current_user, $wpdb;
	$prefix = $wpdb->prefix;

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$using_perm = wpjobster_using_permalinks();
	if($using_perm) $pay_pg_lnk = get_permalink(get_option('wpjobster_my_account_payments_page_id'));
	else $pay_pg_lnk = get_bloginfo('url'). "?page_id=". get_option('wpjobster_my_account_payments_page_id'). "&";

	$selected = wpjobster_get_currency();
	global $wp_query;
	$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'home';
	$pages = array( 'home', 'transactions', 'topup', 'withdraw', 'affiliate' );
	if( ! in_array($pg, $pages) ){ $pg = 'home'; }

	$bal = wpjobster_get_credits($uid);

	$vars = array(

		'uid'        => $uid,
		'pay_pg_lnk' => $pay_pg_lnk,
		'pg'         => $pg,
		'wpdb'       => $wpdb,
		'bal'        => $bal

	);

	return $vars;

}

function wpj_payment_tab_home() {

	$wpj_job = new WPJ_Load_More_Queries(
		array(
			'query_type'     => 'withdrawals',
			'query_status'   => 'pending',
			'function_name'  => 'wpjobster_get_pending_withdrawals_queries',
			'posts_per_page' => '10',
			'new_class_row' => 'my-account-shopping-list'
		)
	);
	$no_jobs_text = __('No withdrawals pending yet.','wpjobster');

	if($wpj_job->have_rows()){
		echo '<div class="five wide column payment-title-table">';
		echo __('Date','wpjobster');
		echo '</div>';
		echo '<div class="four wide column payment-title-table">';
		echo __('Amount','wpjobster');
		echo '</div>';
		echo '<div class="four wide column payment-title-table">';
		echo __('Type of payment','wpjobster');
		echo '</div>';
		echo '<div class="three wide column payment-title-table">';
		echo __('Status','wpjobster');
		echo '</div>';
		$wpj_job->show_queries_list_func();
	}else{
		echo "<div class='sixteen wide column'>".$no_jobs_text."</div>";
	}

}

function wpj_payment_tab_pending_incoming() {

	$wpj_job = new WPJ_Load_More_Queries(
		array(
			'query_type'     => 'payments',
			'query_status'   => 'pending',
			'function_name'  => 'wpjobster_get_pending_payments_queries',
			'posts_per_page' => '10',
		)
	);
	$no_jobs_text = __('No payments pending yet.','wpjobster');

	if( $wpj_job->have_rows() ) {
		echo '<div class="two wide column payment-title-table">'.__('Buyer',"wpjobster").'</div>';
		echo '<div class="two wide column payment-title-table">'.__('Job',"wpjobster").'</div>';
		echo '<div class="three wide column payment-title-table">'.__('Date Purchased',"wpjobster").'</div>';
		echo '<div class="three wide column payment-title-table">'.__('Date Completed',"wpjobster").'</div>';
		echo '<div class="three wide column payment-title-table">'.__('Date Clearing',"wpjobster").'</div>';
		echo '<div class="three wide column payment-title-table">'.__('Amount',"wpjobster").'</div>';

		$wpj_job->show_queries_list_func();
	} else {
		echo "<div class='sixteen wide column'>".$no_jobs_text."</div>";
	}

}

function wpj_payment_tab_withdraw() {

	$vars = wpj_payments_vars();
	foreach ($vars as $key => $value) {
			$$key = $value;
	}

	$vars = array();

	if(isset($_POST['withdraw']) or isset($_POST['withdraw2']) or isset($_POST['withdraw3']) or isset($_POST['withdraw4']) or isset($_POST['withdraw5']) or isset($_POST['withdraw6']) or isset($_POST['withdraw7'])) {
		$amount = trim($_POST['amount']);
		$paypal = trim($_POST['paypal']);

		$selected = wpjobster_get_currency();
		$currency = $selected;

		if(isset($_POST['withdraw2'])) $amount = trim($_POST['amount2']);
		if(isset($_POST['withdraw3'])) $amount = trim($_POST['amount3']);
		if(isset($_POST['withdraw4'])) $amount = trim($_POST['amount4']);
		if(isset($_POST['withdraw5'])) $amount = trim($_POST['amount5']);
		if(isset($_POST['withdraw6'])) $amount = trim($_POST['amount6']);
		if(isset($_POST['withdraw7'])) $amount = trim($_POST['amount7']);

		global $wpjobster_currencies_array;
		$amount_default = get_exchange_value($amount, $currency, $wpjobster_currencies_array[0]);

		$wpjobster_withdraw_limit = get_option('wpjobster_withdraw_limit');
		if(empty($wpjobster_withdraw_limit) or !is_numeric($wpjobster_withdraw_limit)) $wpjobster_withdraw_limit = 10;

	$tm = current_time('timestamp',0); global $wpdb;
	if(!empty($_POST['tm_tm'])) $tm = $_POST['tm_tm'];

	$s = "select * from ".$wpdb->prefix."job_withdraw where uid='$uid' AND datemade='$tm'";
	$r = $wpdb->get_results($s);
	if(count($r) == 0) {

		if(!is_numeric($amount) || $amount < 0) {
			echo '<div class="error">'.__('ERROR: Provide a well formated amount.','wpjobster').'</div>';
		}

		elseif( wpjobster_isValidEmail($paypal) == false&&!isset($_POST['withdraw3']) &&!isset($_POST['withdraw4']) &&!isset($_POST['withdraw5']) ) {
			echo '<div class="error">'.__('ERROR: Invalid email provided.','wpjobster').'</div>';
		}

		elseif($amount_default < $wpjobster_withdraw_limit) {
			echo '<div class="error">'.sprintf(__('ERROR: The amount must be higher than: %s.','wpjobster'), wpjobster_get_show_price($wpjobster_withdraw_limit) ).'</div>';
		}

		elseif ($bal < $amount_default) {
			file_put_contents ( getcwd().'/uz_log_orderid' , __FILE__." - ".time()." - no \n",  FILE_APPEND );

			echo '<div class="error">'.__('ERROR: Your balance is smaller than the amount requested.','wpjobster').'</div>';
		}

		else {
			file_put_contents ( getcwd().'/uz_log_orderid' , __FILE__." - ".time()." - db \n",  FILE_APPEND );

			$method = __("PayPal", 'wpjobster');
			if(isset($_POST['withdraw2'])) $method = __("Payoneer", 'wpjobster');

			if(isset($_POST['withdraw3'])) $method = __("Bank", 'wpjobster');

			$method = apply_filters('wpjobster_withdraw_method', $method);
			$tm = current_time('timestamp',0); global $wpdb;
			if(!empty($_POST['tm_tm'])) $tm = $_POST['tm_tm'];

			$s = "select * from ".$wpdb->prefix."job_withdraw where uid='$uid' AND datemade='$tm'";
			$r = $wpdb->get_results($s);
			if(count($r) == 0)
			{
				$selected = wpjobster_get_currency();
				$currency       = $selected;
				$payedamount = $currency. '|' . wpjobster_formats_special($amount, 1);
				if ( get_option( 'wpjobster_enable_withdraw_email_verification' ) == 'yes' ) {
					$act_key = md5(uniqid(mt_rand(), true));
				} else {
					$act_key = '';
				}
				$s = "insert into " . $wpdb->prefix . "job_withdraw ( payeremail, methods, amount, datemade, uid, payedamount, activation_key ) values( '$paypal', '$method', '$amount_default', '$tm', '$uid', '$payedamount', '$act_key' )";
				$wpdb->query($s);
				$cr = wpjobster_get_credits($uid);
				wpjobster_update_credits($uid, $cr - $amount_default);
				//-----------------------
				wpjobster_send_email_allinone_translated('withdraw_req', $uid, false, false, false, $method, wpjobster_get_show_price_precise($amount, 2, $currency), false, false, false, false, false, false, false, $act_key);
				wpjobster_send_sms_allinone_translated('withdraw_req', $uid, false, false, false, $method, wpjobster_get_show_price_precise($amount, 2, $currency), false, false, false, false, false, false, $act_key);


				// Email the admin
				$admin_email = get_bloginfo('admin_email');
				$uid_data = get_userdata($uid);
				$subject = __("New withdrawal request", "wpjobster");
				$message = __("Hello admin", "wpjobster") . ',<br><br>' . __("You have a new withdrawal request:", "wpjobster") . '<br><br>' . __("User", "wpjobster") . ': ' . $uid_data->user_login . '<br>' . __("Amount", "wpjobster") . ': ' . wpjobster_get_show_price_precise($amount, 2, $currency) . '<br>' . __("Payment Method", "wpjobster") . ': ' . $method;
				wp_mail( $admin_email, $subject, $message);
				//-----------------------
			}
			echo '<span class="balance">'. __('Your request has been queued. Redirecting...','wpjobster'). '</span>';
			$url_redir = $pay_pg_lnk;
			echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
		}
	} else {
		echo '<span class="balance">'. __('Your request has been queued. Redirecting...','wpjobster'). '</span>';
		$url_redir = $pay_pg_lnk;
		echo '<meta http-equiv="refresh" content="2;url='.$url_redir.'" />';
	}
	}
	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	$wpjobster_currency_position = get_option('wpjobster_currency_position');
	$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
	$space = " ";
	if ($wpjobster_currency_symbol_space == 'no') $space = "";

	$vars = array(

		'wpjobster_currency_position' => $wpjobster_currency_position,
		'wpjobster_currency_symbol_space' => $wpjobster_currency_symbol_space

	);

	return $vars;

}

function wpj_payment_tab_transactions() {

	 $wpj_job = new WPJ_Load_More_Queries(
		array(
			'query_type'     => 'transactions',
			'query_status'   => 'all',
			'function_name'  => 'wpjobster_get_transactions_queries',
			'posts_per_page' => '10',
			'new_class_row'  => 'payment-row'
		)
	);
	$no_jobs_text = __('No activity yet.','wpjobster');

	if($wpj_job->have_rows()){
		global $wpjobster_currencies_array;
		if (count($wpjobster_currencies_array) > 1) { $multiple_currencies = 1; } else { $multiple_currencies = 0; }
		if ($multiple_currencies) { $descr_col_class = "bs-col466fill"; } else { $descr_col_class = "bs-col46fill"; }

		echo '<div class="eight wide column payment-title-table ' .$descr_col_class.'">'.__('Description',"wpjobster").'</div>';
		echo '<div class="four wide column payment-title-table">'.__('Date',"wpjobster").'</div>';
		echo '<div class="two wide column payment-title-table">'.__('Amount',"wpjobster").'</div>';
		if ($multiple_currencies) {
			echo '<div class="two wide column payment-title-table">'.__('Currency',"wpjobster").'</div>';
		}
		$wpj_job->show_queries_list_func();
	}else{
		echo "<div class='sixteen wide column'>".$no_jobs_text."</div>";
	}

}

function wpj_payments_tab_topup_buttons() {

	$wpjobster_payment_gateways =get_wpjobster_payment_gateways();
	foreach($wpjobster_payment_gateways as $priority=>$button_arr){
		if((isset($button_arr['action']) && isset($button_arr['response_action']))){
			$wpjobster_gateway_enable = get_option('wpjobster_'.$button_arr['unique_id'].'_enable');
			$wpjobster_gateway_enable_topup = get_option('wpjobster_'.$button_arr['unique_id'].'_enable_topup');
			$wpjobster_gateway_enablepopup = get_option('wpjobster_'.$button_arr['unique_id'].'_enablepopup');
			if($wpjobster_gateway_enable == "yes" && $wpjobster_gateway_enable_topup!='no'):
				do_action('wpjobster_before_'.$button_arr['unique_id'].'_creditbutton_link' ); ?>
				<a  href="javascript:void(0);" onclick="credit_function('<?php echo $button_arr['unique_id']; ?>','<?php echo $wpjobster_gateway_enablepopup; ?>');"  class="ui white button">
				<?php
					$wpjobster_gateway_button_caption = get_option('wpjobster_'.$button_arr['unique_id'].'_button_caption');
					if($wpjobster_gateway_button_caption!=''){
						echo $wpjobster_gateway_button_caption;
					}
					else { echo $button_arr['unique_id'] ;}
				?>
				</a>
				<?php
				do_action('wpjobster_after_'.$button_arr['unique_id'].'_creditbutton_link' );
			endif;
		}
	}

}
