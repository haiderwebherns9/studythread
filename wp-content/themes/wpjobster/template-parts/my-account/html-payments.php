<?php

if(!function_exists('wpjobster_my_account_payments_area_function')){
	function wpjobster_my_account_payments_area_function(){

		ob_start();

		$vars = wpj_payments_vars();
		$pg = $vars['pg'];
		$uid = $vars['uid'];
		$pay_pg_lnk = $vars['pay_pg_lnk'];
		$wpdb = $vars['wpdb'];

		?>

		<div id="content-full-ov" data-currency="<?php echo strtoupper($selected);  ?>">
			<!-- page content here -->
			<div class="ui basic notpadded segment">
				<div class="ui two column stackable grid">
					<div class="eight wide column">
						<h1 class="ui header wpj-title-icon">
							<i class="credit card icon"></i>
							<?php _e("My Payments",'wpjobster'); ?>   
						</h1>
					</div>
					
					<div class="eight wide column">
						<?php
						if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) {
							$bal = wpjobster_get_credits($uid);
							echo '<span class="ui primary button balance nomargin">'.sprintf(__("Current Balance: <strong>%s</strong>", "wpjobster"), wpjobster_get_show_price($bal))."</span>";
						}
						?>
					</div>
				</div>
			</div>
		<script>
			 $(document).ready(function() {
					 $("#watch_video").click(function(){
					   $("#payment_Modal").show();
					 });
					 $(".cclose").click(function(){
					var video = $("#payment_Modal iframe").attr("src");
				    $("#payment_Modal iframe").attr("src","");
				    $("#payment_Modal iframe").attr("src",video);
					   $("#payment_Modal").hide();
					 });
			  });
       </script>
	     <div class="pst_job">
			<div id="payment_Modal" class="jmodal">
				<!-- Modal content -->
				<div class="jmodal-content">
				<span class="cclose">&times;</span>
				   <div class="jmodal-body">
					 <?php if ( is_active_sidebar( 'payment_video' ) ) : ?>
						  <?php dynamic_sidebar( 'payment_video' ); ?>
						  <?php endif; ?>
				   </div>
			   </div>
		   </div>
		   </div>
            <h3 id="watch_video">(Watch the video before Withdrawing any Fund) </h3>
			<?php if (isset($_GET['success']) && $_GET['success'] == "true") { ?>
				<div class="white-cnt padding-cnt center green-cnt">
					<?php _e("Your transaction was successful. Thank you!", "wpjobster"); ?>
				</div>
			<?php } ?>

			<?php if (isset($_GET['cancel']) && $_GET['cancel'] == "true") { ?>
				<div class="white-cnt padding-cnt center red-cnt">
					<?php _e("Something went wrong or you have cancelled the transaction. Please try again.", "wpjobster"); ?>
				</div>
			<?php } ?>


			<div class="ui basic notpadded segment">
				<div class="stackable-buttons">
					<?php if ( !class_exists( 'WPJobster_Payoneer_Loader' ) || get_option( 'wpjobster_payoneer_enable' ) == 'no' ) { ?>
						<a class="ui white button <?php if ($pg == 'home' ) { echo 'active'; } ?>" href="<?php echo $pay_pg_lnk; ?>"><?php _e('Payments','wpjobster'); ?></a>
					<?php } ?>

					<a class="ui white button <?php if ($pg == 'transactions' ) { echo 'active'; } ?>" href="<?php echo $pay_pg_lnk; ?>transactions"><?php _e('Transactions','wpjobster'); ?></a>

					<?php if ( get_option( 'wpjobster_credits_enable' ) != 'no' && get_option('wpjobster_enable_topup') == 'yes' ) { ?>
						<a class="ui white button <?php if ($pg == 'topup' ) { echo 'active'; } ?>" href="<?php echo $pay_pg_lnk; ?>topup"><?php _e('Top Up','wpjobster'); ?></a>
					<?php }

					if ( !class_exists( 'WPJobster_Payoneer_Loader' ) || get_option( 'wpjobster_payoneer_enable' ) == 'no' ) { ?>
						<a class="ui white button <?php if ($pg == 'withdraw' ) { echo 'active'; } ?>" href="<?php echo $pay_pg_lnk; ?>withdraw"><?php _e('Withdraw Money','wpjobster'); ?></a>
					<?php } ?>

					<?php do_action('after_payment_type_list'); ?>
				</div>
			</div>

			<?php if($pg == 'home'){ ?>
				<div class="ui segment">
					<div class="ui two column stackable grid">

						<div class="sixteen wide column">
							<h2><?php _e('Pending Withdrawals','wpjobster'); ?></h2>
						</div>

						<div class="ui fitted divider"></div>

						<?php wpj_payment_tab_home(); ?>

					</div>
				</div>

				<?php $wpjobster_clearing_period = get_option( 'wpjobster_clearing_period' );

				if ( is_numeric( $wpjobster_clearing_period ) && $wpjobster_clearing_period == 0 ) {
					$using_clearing = false;
				} else {
					$using_clearing = true;
				}

				if ( $using_clearing ) { ?>
					<div class="ui segment">
						<div class="ui two column stackable grid">
							<div class="sixteen wide column">
								<h2><?php _e("Pending Incoming Payments","wpjobster"); ?></h2>
							</div>

							<div class="ui fitted divider"></div>

							<?php wpj_payment_tab_pending_incoming(); ?>

						</div>
					</div>
					<div class="ui hidden divider"></div>
				<?php }
			}elseif($pg == 'withdraw'){
				file_put_contents ( getcwd().'/uz_log_orderid' , __FILE__." - ".time()." - ---------------------------- \n",  FILE_APPEND ); ?>
				<div class="ui segment">
					<div class="ui two column stackable grid">
						<div class="sixteen wide column">
							<h2><?php _e("Request Withdrawal","wpjobster"); ?></h2>
						</div>
						<div class="ui fitted divider"></div>

						<?php
						$vars = wpj_payment_tab_withdraw();
						$wpjobster_currency_position = $vars['wpjobster_currency_position'];
						$wpjobster_currency_symbol_space = $vars['wpjobster_currency_symbol_space'];
						?>

						<div class="eight wide column payment-title-table">
							<?php _e('Method','wpjobster'); ?>
						</div>
						<div class="four wide column payment-title-table">
							<?php _e('Withdraw amount','wpjobster'); ?>
						</div>
						<div class="four wide column payment-title-table">
							<?php _e('Action','wpjobster'); ?>
						</div>

						<?php
						if (get_option('wpjobster_enable_paypal_withdraw') != "no") {
							if (get_user_meta($uid, 'paypal_email',true)!='') {
							?>

							<div class="ui divider"></div>
							<div class="sixteen wide column">
								<form class="ui form" method="post" enctype="application/x-www-form-urlencoded">
									<div class="field">
										<div class="three fields no-bottom-margin">
											<input type="hidden" value="<?php echo current_time('timestamp',0) ?>" name="tm_tm" />

											<div class="eight wide field withdraw-column">
												<div class="withdraw-title">
													<?php _e('PayPal', 'wpjobster'); ?>
												</div>
											</div>

											<div class="four wide field">
												<div class="ui labeled input">
													<label class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency() ); ?></label>
													<input class="" value="<?php if(isset($_POST['amount'])) echo $_POST['amount']; ?>" type="text" size="10" name="amount" />
												</div>
												<input value="<?php echo get_user_meta($uid, 'paypal_email',true); ?>" type="hidden" size="30" name="paypal" />

											</div>

											<div class="four wide field">
												<input class="withpaypal ui button secondary nomargin fluid" data-alert-message="<?php echo __('Error', 'wpjobster')?>" type="submit" name="withdraw" value="<?php _e('Withdraw', 'wpjobster'); ?>" />
											</div>
										</div>
									</div>
								</form>
							</div>

							<?php } else { ?>

								<div class="ui divider"></div>
								<div class="eight wide column"><?php _e('PayPal', 'wpjobster'); ?></div>
								<div class="eight wide column"><?php echo sprintf(__('Please fill your PayPal email <a class="fill-payment-color" href="%s">here</a>.', 'wpjobster'), get_permalink(get_option('wpjobster_my_account_personal_info_page_id')) . '/payments#paypal-payments'); ?></div>

							<?php }
						}

						if (get_option('wpjobster_enable_payoneer_withdraw') != "no") {
						if (get_user_meta($uid, 'payoneer_email',true) != '') {
						?>

						<div class="ui divider"></div>
						<div class="sixteen wide column">
							<form class="ui form" method="post" enctype="application/x-www-form-urlencoded">
								<div class="field">
									<div class="three fields no-bottom-margin">
										<input type="hidden" value="<?php echo current_time('timestamp',0) ?>" name="tm_tm" />

										<div class="eight wide field withdraw-column">
											<div class="withdraw-title">
												<?php _e('Payoneer', 'wpjobster'); ?>
											</div>
										</div>

										<div class="four wide field">
											<div class="ui labeled input">
												<label class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency() ); ?></label>
												<input class="" value="<?php if(isset($_POST['amount2'])) echo $_POST['amount2']; ?>" type="text" size="10" name="amount2" />
											</div>
											<input value="<?php echo get_user_meta($uid, 'payoneer_email',true) ?>" type="hidden" size="30" name="paypal" />

										</div>

										<div class="four wide field">
											<input data-alert-message="<?php echo __('Error', 'wpjobster')?>" class="onlyeur ui button secondary nomargin fluid" type="submit" name="withdraw2" value="<?php _e('Withdraw', 'wpjobster'); ?>" />
										</div>

									</div>
								</div>
							</form>
						</div>

						<?php } else { ?>

							<div class="ui divider"></div>
							<div class="eight wide column"><?php _e('Payoneer', 'wpjobster'); ?></div>
							<div class="eight wide column"><?php echo sprintf(__('Please fill your Payoneer email <a class="fill-payment-color" href="%s">here</a>.', 'wpjobster'), get_permalink(get_option('wpjobster_my_account_personal_info_page_id')) . '/payments#payoneer-payments'); ?></div>
						<?php }
						}

						if (get_option('wpjobster_enable_bank_withdraw') != "no") {
							if (get_user_meta($uid, 'bank_bank_name',true) != ''
								&& get_user_meta($uid, 'bank_account_name',true) != ''
								&& get_user_meta($uid, 'bank_account_number',true) != ''
								) {
							?>

							<div class="ui divider"></div>
							<div class="sixteen wide column">
								<form class="ui form" method="post" enctype="application/x-www-form-urlencoded">
									<div class="field">
										<div class="three fields">
											<input type="hidden" value="<?php echo current_time('timestamp',0) ?>" name="tm_tm" />

											<div class="eight wide field withdraw-column">
												<div class="withdraw-title">
													<?php _e('Bank Account', 'wpjobster'); ?>
												</div>
											</div>

											<div class="four wide field">
												<div class="ui labeled input">
													<label class="ui label"><?php echo wpjobster_get_currency_symbol( wpjobster_get_currency() ); ?></label>
													<input class="" value="<?php if(isset($_POST['amount3'])) echo $_POST['amount3']; ?>" type="text" size="10" name="amount3" />
												</div>
												<input value="<?php echo __('Bank Name','wpjobster') . ': ' . get_user_meta($uid, 'bank_bank_name',true) . '<br>'
												. __('Bank Address','wpjobster') . ': ' . get_user_meta($uid, 'bank_bank_address',true) . '<br>'
												. __('Account Name','wpjobster') . ': ' . get_user_meta($uid, 'bank_account_name',true) . '<br>'
												. __('Account Number','wpjobster') . ': ' . get_user_meta($uid, 'bank_account_number',true) . '<br>'
												. __('Account Currency','wpjobster') . ': ' . get_user_meta($uid, 'bank_account_currency',true) . '<br>'
												. __('Additional Info','wpjobster') . ': ' . get_user_meta($uid, 'bank_additional_info',true); ?>" type="hidden" size="30" name="paypal" />
											</div>

											<div class="four wide field">
												<input class="onlyeur ui button secondary nomargin fluid" data-alert-message="<?php echo __('Error', 'wpjobster')?>" type="submit" name="withdraw3" value="<?php _e('Withdraw', 'wpjobster'); ?>" />
											</div>
										</div>
									</div>
								</form>
							</div>

							<?php } else { ?>

								<div class="ui divider"></div>
								<div class="eight wide column"><?php _e('Bank Account', 'wpjobster'); ?></div>
								<div class="eight wide column"><?php echo sprintf(__('Please fill your Bank details <a class="fill-payment-color" href="%s">here</a>.', 'wpjobster'), get_permalink(get_option('wpjobster_my_account_personal_info_page_id')) . '/payments#bank-payments'); ?></div>

							<?php }
						}
						$current_user_wo = wp_get_current_user();
						$uid_wo = $current_user_wo->ID;
						$wpjobster_currency_position_wo = get_option('wpjobster_currency_position');

						do_action('wpjobster_payments_withdraw_options', $uid_wo, $wpjobster_currency_position_wo );
						?>
					</div>
				</div>
				<div class="ui hidden divider"></div>
			<?php }elseif($pg == 'transactions'){ ?>
				<div class="ui segment">
					<div class="ui two column stackable grid">
						<div class="sixteen wide column">
							<h2><?php _e("Transactions","wpjobster"); ?></h2>
						</div>

						<div class="ui fitted divider"></div>

						<?php wpj_payment_tab_transactions(); ?>

					</div>
				</div>
				<div class="ui hidden divider"></div>
			<?php }elseif($pg=='topup' && get_option('wpjobster_enable_topup') == 'yes'){
				if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
					<div class="ui segment">
						<div class="ui two column stackable grid">

							<div class="sixteen wide column">
								<h2><?php _e("Top Up your account","wpjobster"); ?></h2>
							</div>

							<div class="ui fitted divider"></div>

							<?php
							$ss = "select * from ".$wpdb->prefix."job_topup_packages order by cost asc";
							$r = $wpdb->get_results($ss);

							if(count($r) > 0) { ?>

								<div class="eight wide column desktop-resolution">
									<p><?php _e("Cost",'wpjobster'); ?></p>
								</div>
								<div class="eight wide column desktop-resolution">
									<p><?php _e("Credits",'wpjobster'); ?></p>
								</div>

								<div class="sixteen wide column reponsive-cost-credits">
									<div class="top-up-cost-credits">
										<ul>
											<li><?php _e("Cost",'wpjobster'); ?></li>
											<li><?php _e("Credits",'wpjobster'); ?></li>
										</ul>
									</div>
								</div>

								<?php
								$selected = wpjobster_get_currency();
								$currency       = $selected;

								foreach ($r as $row) { ?>

									<div class="ui fitted divider"></div>

									<div class="eight wide column desktop-resolution">
										<?php
											echo '<div class="ui radio checkbox">';
											echo '<input class="simple-radio" id="credit_amt'.$row->id.'" type="radio" data-price="'.get_exchange_value($row->cost, get_option('wpjobster_currency_1'), $currency).'" name="buy_credit_amount" value="'.$row->id.'">';
											echo '<label for="credit_amt'.$row->id.'">'.wpjobster_get_show_price($row->cost,2).'</label>';
											echo '</div>';
										?>
									</div>

									<div class="eight wide column desktop-resolution">
										<?php echo '<label for="credit_amt'.$row->id.'">'.wpjobster_get_show_price($row->credit,2).'</label>'; ?>
									</div>

									<div class="sixteen wide column reponsive-cost-credits">
										<?php
											echo '<div class="ui checkbox">';
											echo '<input class="simple-radio" id="credit_amt'.$row->id.'" type="radio" data-price="'.get_exchange_value($row->cost, get_option('wpjobster_currency_1'), $currency).'" name="buy_credit_amount" value="'.$row->id.'">';
											echo '<label for="credit_amt'.$row->id.'">'.wpjobster_get_show_price($row->cost,2).'</label>';
											echo '</div>';
											echo '<label class="label-top-up" for="credit_amt'.$row->id.'">'.wpjobster_get_show_price($row->credit,2).'</label>';
										?>
									</div>

								<?php }
							}else{ ?>
								<div class="sixteen wide column">
									<p><?php _e("No packages added yet.","wpjobster"); ?></p>
								</div>
							<?php } ?>

						</div>
						<?php if(count($r) > 0) { ?>
							<div class="payment-buttons ui grid">
								<div class="sixteen wide column">
									<div class="btn-margin-bottom-helper">
									<?php wpj_payments_tab_topup_buttons(); ?>

									</div>
								</div>
								<div class="payment-gateway-popup"></div>
							</div>
						<?php } ?>

					</div>

					<script>
						$(".simple-radio[type=radio]").change(function(){
							var cur = $("#payment-item-price").data("cur");
							var symbol = $("#payment-item-price").data("symbol");
							var position = $("#payment-item-price").data("position");
							var space = $("#payment-item-price").data("space");
							var decimal = $("#payment-item-price").data("decimal");
							var thousands = $("#payment-item-price").data("thousands");
							var decimaldisplay = $("#payment-item-price").data("decimaldisplay");

							s_total = 0;
							master_total = 0;
							master_total = $(".simple-radio[type=radio]:checked").data('price');
							total = parseFloat(master_total);

							var formatted_money = String(total.formatMoney(2, decimal, thousands));
							if (decimaldisplay == "ifneeded") {
								if (isInt(total)) {
									formatted_money = String(total.formatMoney(0, decimal, thousands));
								} else {
									formatted_money = String(total.formatMoney(2, decimal, thousands));
								}
							} else if (decimaldisplay == "never") {
								formatted_money = String(total.formatMoney(0, decimal, thousands));
							}
							//alert("formatted money "+formatted_money);
							var space_str = "";
							if (space == "yes") {space_str = " ";}

							if (position == "front") {
								if(formatted_money > 0) {
									$("#payment-item-price").html(symbol + space_str + formatted_money);
								}
							} else {
								if(formatted_money > 0) {
									$("#payment-item-price").html(formatted_money + space_str + symbol);
								} else {
									$("#payment-item-price").html(formatted_money + space_str + symbol);
								}
							}
							return ;
						});
						$(document).ready(function(){
							if(typeof $(".simple-radio[type=radio]:checked").data('price')!='undefined'){
								$(".simple-radio[type=radio]").change();
							}
						});
					</script>
					<script>
						function credit_function(gateway, enable_popup){
							if(typeof gateway=='undefined'){
								gateway='paypal'
							}
							package_id = $('input[name=buy_credit_amount]:checked').val();
							if(package_id>='1'){
								if( enable_popup === 'yes' ) {

									jQuery.ajax({
										type: "POST",
										url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
										data: {
											action: 'wpjobster_check_payment_gateway_popup',
											package_id: package_id,
											payment_type: 'topup',
											gateway: gateway
										},
										success: function (output) {
											jQuery(".payment-gateway-popup").html(output);
										}
									});
								} else {
									window.location.href='<?php echo get_bloginfo('siteurl'); ?>/?pay_for_item='+gateway+'&payment_type=topup&package_id='+package_id;
								}
							}else{
								alert("<?php _e('Please select the package first', 'wpjobster'); ?>");
							}
						 }
					</script>
				<?php } else { ?>
					<div class="ui segment">
						<?php echo __( 'You need to enable credits to access this page!', 'wpjobster' ); ?>
					</div>
				<?php }
			}
			do_action('new_payment_type_content'); ?>
			<!-- end page content here -->
		</div>
		<?php

		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}

