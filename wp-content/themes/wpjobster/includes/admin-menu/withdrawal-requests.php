<?php

class WithdrawalRequest {

	function wpjobster_withdrawals() {

		$id_icon      = 'icon-options-general-withdr';
		$ttl_of_stuff = 'Jobster - '.__('Withdrawal Requests','wpjobster');
		global $wpdb;
		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if (!is_demo_admin()) {
				if(!empty($_POST['processPayReque'])){

					$tm = current_time('timestamp', 1);
					$ids = $_POST['requests'];
					$currency = wpjobster_get_currency_classic();

					$paypal_appid 		= get_option('wpjobster_theme_appid');
					$paypal_appsecret 		= get_option('wpjobster_theme_appsecret');
					$wpjobster_paypal_enable_sdbx 		= get_option('wpjobster_paypal_enable_sdbx');

					if(isset($paypal_appid) && isset($paypal_appsecret) && $paypal_appsecret!='' && $paypal_appid!=''){
						$emails = array();
						$mounts = array();
						$paypal_payout_requests_info=array();

						foreach ($ids as $id) {
							$s = "select * from ".$wpdb->prefix."job_withdraw where id='$id'";
							$row = $wpdb->get_results($s);
							$row = $row[0];

							if($row->done == 0){
								$paypal_email = user($row->uid, 'paypal_email');
								$emails[] = $paypal_email;
								$mounts[] = $row->amount;
								$paypal_payout_requests_info[$id]['email']=$paypal_email;
								$paypal_payout_requests_info[$id]['amount']=$row->amount;
								$paypal_payout_requests_info[$id]['userid']=$row->uid;
								$paypal_payout_requests_info[$id]['uniqueid']=$id;

							}
						}

						if (!empty($emails)) {
							include_once get_template_directory() . '/vendor/PayPal-PHP-SDK/vendor/paypal/rest-api-sdk-php/sample/payouts/CreateSinglePayout_wpjobster.php';
							foreach($output_arr as $item_id =>$output_msg){
								if ($output_msg->batch_header->batch_status == "SUCCESS") {
									$id=$item_id;
									$s = "select * from ".$wpdb->prefix."job_withdraw where id='$id'";
									$row = $wpdb->get_results($s);
									$row = $row[0];
									if($row->done == 0){
										echo '<div class="updated fade"><div class="padd10">'.sprintf(__('Payment completed for %s!','wpjobster'),$row->payeremail).'</div></div>';
										$ss = "update ".$wpdb->prefix."job_withdraw set done='1', datedone='$tm' where id='$id'";
										$wpdb->query($ss);
										wpjobster_send_email_allinone_translated('withdraw_compl', $row->uid, false, false, false, $row->methods, wpjobster_get_show_price_classic($row->amount));

										$details = $row->methods . ': ' . $row->payeremail;
										$reason = __('Withdrawal to', 'wpjobster') . ' ' . $details;
										wpjobster_add_history_log('0', $reason, $row->amount, $row->uid, '', '', 9, $details);
									}
								}else{
									echo '<div class="error"><div class="padd10">'.__(sprintf("Error: Transaction %s, Status is %s and error message from paypal is %s - %s - For more information please visit %s",$output_msg->batch_header->batch_status,$output_msg->items[0]->transaction_status,$output_msg->items[0]->errors->name,$output_msg->items[0]->errors->message,$output_msg->items[0]->errors->information_link),'wpjobster').'</div></div>';
								}
							}
							if(isset($paypal_error)){
								foreach($paypal_error as $item_id => $paypal_errormessage){
									$id=$item_id;
									$s = "select * from ".$wpdb->prefix."job_withdraw where id='$id'";
									$row = $wpdb->get_results($s);

									$row = $row[0];
									echo '<div class="error"><div class="padd10">'.sprintf(__('Error for paypal email %s: %s','wpjobster'),$row->payeremail,$paypal_errormessage).'</div></div>';
								}
							}
						}
					}else{
						echo '<div class="error"><div class="padd10">'.__('PayPal Client ID and PayPal Secret are blank!','wpjobster').'</div></div>';
					}
				}

				if(isset($_GET['den_id'])){
					$tm = current_time('timestamp', 1);
					$ids = $_GET['den_id'];

					$s = "select * from ".$wpdb->prefix."job_withdraw where id='$ids'";
					$row = $wpdb->get_results($s);
					$row = $row[0];

					if($row->done == 0){
						echo '<div class="updated fade"><div class="padd10">'.__('Payment rejected!','wpjobster').'</div></div>';
						$ss = "update ".$wpdb->prefix."job_withdraw set done='-1', rejected_on='$tm', rejected='1', datedone='$tm' where id='$ids'";
						$wpdb->query($ss);

						$ucr = wpjobster_get_credits($row->uid);
						wpjobster_send_email_allinone_translated('withdraw_decl', $row->uid, false, false, false, $row->methods, wpjobster_get_show_price_classic($row->amount));

						wpjobster_update_credits($row->uid, $ucr + $row->amount);
					}
				}

				if(isset($_GET['tid'])){
					$tm = current_time('timestamp', 1);
					$ids = $_GET['tid'];

					$s = "select * from ".$wpdb->prefix."job_withdraw where id='$ids'";
					$row = $wpdb->get_results($s);
					$row = $row[0];

					if($row->done == 0){
						echo '<div class="updated fade"><div class="padd10">'.__('Payment completed!','wpjobster').'</div></div>';
						$ss = "update ".$wpdb->prefix."job_withdraw set done='1', datedone='$tm' where id='$ids'";
						$wpdb->query($ss);
						wpjobster_send_email_allinone_translated('withdraw_compl', $row->uid, false, false, false, $row->methods, wpjobster_get_show_price_classic($row->amount));

						$details = $row->methods . ': ' . $row->payeremail;
						$reason = __('Withdrawal to', 'wpjobster') . ' ' . $details;

						wpjobster_add_history_log('0', $reason, $row->amount, $row->uid, '', '', 9, $details);
					}
				}
			}

			wpj_withdrawal_requests_html();

		echo '</div>';
	}
}

$wr = new WithdrawalRequest();
