<?php
if(!isset($_SESSION)) { session_start(); }

$pid = $_GET['jobid'];
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('url')."/wp-login.php?redirect_to=" . urlencode(get_permalink($pid))); exit; }

global $current_user;
$current_user = wp_get_current_user();

global $wp_query;
$post = get_post($pid);

if ( $post->post_author != $current_user->ID ) {
	wp_redirect(get_bloginfo('url')); exit;
}

$crds = wpjobster_get_credits($current_user->ID);
$uid = $current_user->ID;
$action = isset($_GET['action'])?$_GET['action']:'';
if(isset($_GET['action'])&&$_GET['action']=='success'&&isset($_GET['method'])){
	while (get_user_meta($uid, 'uz_last_order_ok', true) != '1') {
		sleep(1);
	}
	update_user_meta( $uid, 'uz_last_order_ok', '0' );
	wp_redirect(wpjobster_my_account_link());
}

$date_format = get_option( 'date_format' );
$h_start_date = get_featured_start_date('homepage', $pid);
$h_end_date = get_featured_end_date($h_start_date);
$c_start_date = get_featured_start_date('category', $pid);
$c_end_date = get_featured_end_date($c_start_date);
$s_start_date = get_featured_start_date('subcategory', $pid);
$s_end_date = get_featured_end_date($s_start_date);

if(isset($_GET['method'])){
	$feature_pages="";

	if($_GET['feature_pages']){
		foreach($_GET['feature_pages'] as $feat){
			if($feat=='homepage')
			 $feature_pages.="h";
			else if($feat=='category')
			 $feature_pages.="c";
			else if($feat=='subcategory')
			 $feature_pages.="s";
		}
	}

	if(sizeof($_GET['feature_pages'])==0)
		$f_err = __("Please select at least one page.", 'wpjobster');
	else if( 	($h_start_date != $_GET['h_date_start']&&strpos($feature_pages,'h') !== false) ||
				($c_start_date != $_GET['c_date_start']&&strpos($feature_pages,'c') !== false) ||
				($s_start_date != $_GET['s_date_start']&&strpos($feature_pages,'s') !== false)
			)
		$f_err = __("The interval was changed in the meantime.", 'wpjobster');

	$price=0;

	if(sizeof($_GET['feature_pages'])>0){
		foreach($_GET['feature_pages'] as $feat){
			if($feat=='homepage')
			 $price+=get_option('wpjobster_featured_price_homepage');
			else if($feat=='category')
			 $price+=get_option('wpjobster_featured_price_category');
			else if($feat=='subcategory')
			 $price+=get_option('wpjobster_featured_price_subcategory');
		}
		if($_GET['method']=='credits'){
			if($crds<$price){
				$f_err = __("You don't have enough money in your balance. Choose one of the other payment methods.", 'wpjobster');
			}
		}
	}

	// SUCCESS
	if(!isset($f_err)){ ?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				take_to_gateway_feature();
			});
			function take_to_gateway_feature() {
				gateway_name = '<?php echo $_GET['method']; ?>';
				enable_popup = '<?php echo get_option('wpjobster_'.$_GET['method'].'_enablepopup'); ?>';
				base_url = "<?php echo bloginfo('url')?>";
				base_url = base_url + '/?pay_for_item=' + gateway_name;
				base_url = base_url + '&payment_type=' + 'feature';
				base_url = base_url + '&jobid=<?php echo $pid; ?>';
				base_url = base_url + '&h_date_start=<?php echo $_GET['h_date_start']; ?>';
				base_url = base_url + '&c_date_start=<?php echo $_GET['c_date_start']; ?>';
				base_url = base_url + '&s_date_start=<?php echo $_GET['s_date_start']; ?>';
				base_url = base_url + '&feature_pages=<?php echo $feature_pages; ?>';

				if( enable_popup === 'yes' ) {
					jQuery.ajax({
						type: "POST",
						url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
						data: {
							action: 'wpjobster_check_payment_gateway_popup',
							jobid: '<?php echo $pid; ?>',
							payment_type: 'feature',
							h_date_start: '<?php echo $_GET['h_date_start']; ?>',
							c_date_start: '<?php echo $_GET['c_date_start']; ?>',
							s_date_start: '<?php echo $_GET['s_date_start']; ?>',
							feature_pages: '<?php echo $feature_pages; ?>',
							gateway: gateway_name
						},
						success: function (output) {
							jQuery(".payment-gateway-popup").html(output);
						}
					});
				} else {
					window.location = base_url;
				}
			}
		</script>
		<?php
	}
}

get_header(); ?>

<div id="content-full-ov" data-currency="<?php echo wpjobster_get_currency();  ?>">
	<div class="feature-job">

		<div class="ui segment">
			<div class="feature-title">
				<h1><i class="money icon"></i><?php echo __("Feature Job", 'wpjobster'); ?></h1>
			</div>
		</div>
		<?php do_action( 'wpj_featured_job_after_title', get_option( 'wpjobster_user_payoneer_partner' ), 'feature' ); ?>

		<?php if ( isset( $_GET['status'] ) ) { ?>
			<?php if ( $_GET['status'] == 'success' ) { ?>
				<div class="ui segment padding-cnt center green-cnt">
					<?php _e( "Your payment was successful!", "wpjobster" ); ?>
				</div>
			<?php } ?>
		<?php } ?>

		<?php if ( isset( $_GET['status'] ) ) { ?>
			<?php if ( $_GET['status'] == 'fail' ) { ?>
				<div class="ui segment center red-cnt">
					<?php _e( "Payment failed! Please try again.", "wpjobster" );
						echo "<br>";
						do_action( 'wpj_gateway_transaction_cancelled_message', $pid, 'feature' );
					?>
				</div>
			<?php } ?>
		<?php } ?>

		<form class="ui form" action="<?php echo get_bloginfo('url'); ?>" method="get">
			<div class="ui segment">

				<div class="invoice-wrapper-chatpage">
					<?php do_action( 'wpj_feature_page_before_job_title', $pid, $_GET['jb_action'] ); ?>
				</div>

				<?php
				if(isset($f_err)){
					echo '<p>' . $f_err . '</p>';
				}

				$currency = wpjobster_get_currency();
				$price_homepage = wpjobster_get_show_price($price_homepage_raw = get_option('wpjobster_featured_price_homepage'));
				$price_category = wpjobster_get_show_price($price_category_raw = get_option('wpjobster_featured_price_category'));
				$price_subcategory = wpjobster_get_show_price($price_subcategory_raw = get_option('wpjobster_featured_price_subcategory'));
				?>

				<div class="ui two column stackable grid">

					<div class="two wide column">
						<div class="feature-job-image">
							<img width="70" height="70" class="round-avatar" src="<?php echo wpjobster_get_first_post_image($pid, 101, 101); ?>" />
						</div>
					</div>

					<div class="fourteen wide column">
						<div class="feature-title-cover-job">
							<h3>
								<?php
								if (get_post_type($pid) == 'offer')
									echo __("Private transaction with", "wpjobster") . ' ' . get_userdata($post->post_author)->user_login;
								else
									echo get_the_title($pid);
								?>
							</h3>
							<div class="payment-job-categories">
								<?php echo wpjobster_display_job_categories(); ?>
							</div>
						</div>
					</div>

					<input type="hidden" name="jb_action" value="feature_job"/>
					<input type="hidden" name="jobid" value="<?php echo $pid; ?>"/>
					<input type="hidden" name="h_date_start" value="<?php echo $h_start_date; ?>"/>
					<input type="hidden" name="c_date_start" value="<?php echo $c_start_date; ?>"/>
					<input type="hidden" name="s_date_start" value="<?php echo $s_start_date; ?>"/>

					<div class="ui fitted divider"></div>

					<div class="sixteen wide column">
						<?php echo __("Select the pages where your job will be featured:", 'wpjobster'); ?>
					</div>

					<div class="ui fitted divider"></div>

					<?php
					$g=0;
					global $wpdb;
					$disply_orders = array();

					if(get_post_meta($pid, 'home_featured_until', true)=='z' || get_post_meta($pid, 'home_featured_until', true)==false){ $g++; // already is featured
						$home_fees_and_tax = false;
						$sql_feature = " select * from ".$wpdb->prefix."job_featured_orders where job_id='{$pid}' and user_id='{$uid}' and payment_status='pending' and feature_pages like '%h%' limit 1 ";
						$rows = $wpdb->get_results($sql_feature);

						if($rows){
							$row= $rows['0'];
							$h_start_date = $row->h_date_start;
							$feature_pages = $row->feature_pages;
							$disply_orders[]='h'; ?>

							<div class="sixteen wide column">

								<?php
								echo __("(Pending) Featured on Home page between:", 'wpjobster');
								echo ' <strong>';
								echo date_i18n($date_format, $h_start_date) . " - " . date_i18n($date_format, get_featured_end_date($h_start_date));
								echo '</strong>';
								if(strpos($feature_pages,'c')!==false){
									$c_start_date = $row->c_date_start;
									$disply_orders[]='c';
									echo '<br>' . __("(Pending) Featured on Category page between:", 'wpjobster');
									echo ' <strong>';
									echo date_i18n($date_format, $c_start_date) . " - " . date_i18n($date_format, get_featured_end_date($c_start_date));
									echo '</strong>';
								}
								if(strpos($feature_pages,'c')!==false){
									$s_start_date = $row->s_date_start;
									$disply_orders[]='s';
									echo '<br>' . __("(Pending) Featured on Subcategory page between:", 'wpjobster');
									echo ' <strong>';
									echo date_i18n($date_format, $s_start_date) . " - " . date_i18n($date_format, get_featured_end_date($s_start_date));
									echo '</strong>';
								}
								?>

								<span class="payment-item-price">
									<?php echo $row->payable_amount ." ".$row->currency; ?>
								</span>

								<?php if ($row->payment_gateway_name == 'banktransfer') {
									do_action("wpjobster_before_bank_details_display",$row); ?>
									<div class="job_post white-cnt">
										<div class="">
											<div class="padding-cnt">
												<strong><?php  _e('Bank Details', 'wpjobster'); ?>:</strong><br>
												<?php echo nl2br(get_option('wpjobster_bank_details')); ?><br>
											</div>
										</div>
									</div>
								<?php
									do_action("wpjobster_after_bank_details_display",$row);
								} ?>

								<span><a class="redlink" href="<?php bloginfo('siteurl'); ?>/?payment_response=<?php echo $row->payment_gateway_name; ?>&payment_type=feature&order_id=<?php echo $row->id; ?>&action=cancel" ><?php _e( 'Cancel', 'wpjobster' ); ?></a></span>

							</div><!-- END <div class="sixteen wide column"> -->

							<div class="ui fitted divider"></div>

						<?php }else{ ?>

							<div class="sixteen wide column">
								<div class="ui checkbox">
									<input type="checkbox" class="styled featured_chk" name="feature_pages[]" data-price="<?php echo get_exchange_value($price_homepage_raw,get_option('wpjobster_currency_1'),  wpjobster_get_currency()); ?>" id="homepage_featured_chk" value="homepage"/>
									<label>
										<?php echo __("Homepage", 'wpjobster'); ?>(<?php echo __("first available", 'wpjobster'); ?>:
										<strong><?php echo date_i18n($date_format, $h_start_date) . " - " . date_i18n($date_format, $h_end_date); ?></strong>)
									</label>
								</div>

								<span class="payment-item-price" >
									<?php echo $price_homepage; ?>
								</span>
							</div>

							<div class="ui fitted divider"></div>
						<?php }
					} else { $dt = get_post_meta($pid, 'home_featured_until', true); $home_fees_and_tax = true; ?>

					<div class="sixteen wide column">
						<?php
						echo __("Featured on homepage between:", 'wpjobster');
						echo ' <strong>';
						echo date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
						echo '</strong>';
						?>
					</div>

					<div class="ui fitted divider"></div>

					<?php }
					if(get_post_meta($pid, 'category_featured_until', true)=='z' || get_post_meta($pid, 'category_featured_until', true)==false){ $g++;
						$category_fees_and_tax = false;
						$sql_feature = " select * from ".$wpdb->prefix."job_featured_orders where job_id='{$pid}' and user_id='{$uid}' and payment_status='pending' and feature_pages like '%c%' limit 1 ";
						$rows = $wpdb->get_results($sql_feature);
						if(!in_array('c', $disply_orders)){
							if($rows ){
								$row= $rows['0'];
								$c_start_date = $row->c_date_start;
								$feature_pages = $row->feature_pages;
								$disply_orders[]='c'; ?>

								<div class="sixteen wide column">

									<?php echo __("(Pending) Featured on category page between:", 'wpjobster');
									echo ' <strong>';
									echo date_i18n($date_format, $c_start_date) . " - " . date_i18n($date_format, get_featured_end_date($c_start_date));
									echo '</strong>';
									if(strpos($feature_pages,'s')!==false){
										$s_start_date = $row->s_date_start;
										$disply_orders[]='s';
										echo '<br />'.__("(Pending) Featured on Subcategory page between:", 'wpjobster');
										echo ' <strong>';
										echo date_i18n($date_format, $s_start_date) . " - " . date_i18n($date_format, get_featured_end_date($s_start_date));
										echo '</strong>';
									} ?>

									<span class="payment-item-price">
										<?php echo $row->payable_amount ." ".$row->currency;//wpjobster_get_show_price($row->payable_amount) ; ?>
									</span>

									<?php if ($row->payment_gateway_name == 'banktransfer') {
										do_action("wpjobster_before_bank_details_display",$row); ?>

										<div class="job_post white-cnt">
											<div class="">
												<div class="padding-cnt">
													<strong><?php  _e('Bank Details', 'wpjobster'); ?>:</strong><br>
													<?php echo nl2br(get_option('wpjobster_bank_details')); ?><br>
												</div>
											</div>

										</div>
										<?php do_action("wpjobster_after_bank_details_display",$row);
									} ?>

									<span><a class="redlink" href="<?php bloginfo('siteurl'); ?>/?payment_response=<?php echo $row->payment_gateway_name; ?>&payment_type=feature&order_id=<?php echo $row->id; ?>&action=cancel" ><?php _e( 'Cancel', 'wpjobster' ); ?></a></span>

								</div>

								<div class="ui fitted divider"></div>

							<?php }else{ ?>

								<div class="sixteen wide column">
									<div class="ui checkbox">
										<input type="checkbox" class="styled featured_chk" data-price="<?php echo get_exchange_value($price_category_raw,get_option('wpjobster_currency_1'),  wpjobster_get_currency()); ?>" name="feature_pages[]" id="category_featured_chk"  value="category"/>
										<label>
											<?php echo __("Category page", 'wpjobster'); ?>(<?php echo __("first available", 'wpjobster'); ?>:
											<strong><?php echo date_i18n($date_format, $c_start_date) . " - " . date_i18n($date_format, $c_end_date); ?></strong>)
										</label>
									</div>

									<span class="payment-item-price" >
										<?php echo $price_category; ?>
									</span>
								</div>

								<div class="ui fitted divider"></div>

							<?php }
						}
					} else { $dt = get_post_meta($pid, 'category_featured_until', true); $category_fees_and_tax = true; ?>

						<div class="sixteen wide column">
							<?php echo __("Featured on category page between:", 'wpjobster');
							echo ' <strong>';
							echo date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
							echo '</strong>'; ?>
						</div>

						<div class="ui fitted divider"></div>

					<?php }

					if(get_post_meta($pid, 'subcategory_featured_until', true)=='z' || get_post_meta($pid, 'subcategory_featured_until', true)==false){ $g++;
						$subcategory_fees_and_tax = false;
						$sql_feature = " select * from ".$wpdb->prefix."job_featured_orders where job_id='{$pid}' and user_id='{$uid}' and payment_status='pending' and feature_pages like '%s%' limit 1 ";
						$rows = $wpdb->get_results($sql_feature);

						if(!in_array('s', $disply_orders)){
							if($rows ){
								$row= $rows['0'];
								$s_start_date = $row->s_date_start;
								$disply_orders[]='s'; ?>

								<div class="sixteen wide column">

									<?php echo __("(Pending) Featured on subcategory page between:", 'wpjobster');
									echo ' <strong>';
										echo date_i18n($date_format, $s_start_date) . " - " . date_i18n($date_format, get_featured_end_date($s_start_date));
									echo '</strong>'; ?>

									<span class="payment-item-price">
										<?php echo $row->payable_amount ." ".$row->currency ; ?>
									</span>
									<?php if ($row->payment_gateway_name == 'banktransfer') {
										do_action("wpjobster_before_bank_details_display",$row); ?>

										<div class="job_post white-cnt">
											<div class="">
												<div class="padding-cnt">
													<strong><?php  _e('Bank Details', 'wpjobster'); ?>:</strong><br>
													<?php echo nl2br(get_option('wpjobster_bank_details')); ?><br>
												</div>
											</div>
										</div>

										<?php do_action("wpjobster_after_bank_details_display",$row);
									} ?>

									<span><a class="redlink" href="<?php bloginfo('siteurl'); ?>/?payment_response=<?php echo $row->payment_gateway_name;?>&payment_type=feature&order_id=<?php echo $row->id; ?>&action=cancel" ><?php _e( 'Cancel', 'wpjobster' ); ?></a></span>

								</div>

								<div class="ui fitted divider"></div>

							<?php }else{ ?>

								<div class="sixteen wide column">
									<div class="ui checkbox">
										<input type="checkbox" class="styled featured_chk" data-price="<?php echo get_exchange_value($price_subcategory_raw,get_option('wpjobster_currency_1'),  wpjobster_get_currency()) ; ?>" name="feature_pages[]"  id="subcategory_featured_chk" value="subcategory"/>
										<label>
											<?php echo __("Subcategory page", 'wpjobster'); ?>(<?php echo __("first available", 'wpjobster'); ?>:
											<strong><?php echo date_i18n($date_format, $s_start_date) . " - " . date_i18n($date_format, $s_end_date); ?></strong>)
										</label>
									</div>

									<span class="payment-item-price">
										<?php echo $price_subcategory; ?>
									</span>
								</div>

								<div class="ui fitted divider"></div>
							<?php }
						}
					} else {
						$dt = get_post_meta($pid, 'subcategory_featured_until', true); $subcategory_fees_and_tax = true; ?>

						<div class="sixteen wide column">
							<?php
							echo __("Featured on subcategory page between:", 'wpjobster');
							echo ' <strong>';
								echo date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
							echo '</strong>'; ?>
						</div>

						<div class="ui fitted divider"></div>
					<?php }

					$wpjobster_thousands_sum_separator = get_option('wpjobster_thousands_sum_separator');

					if (empty($wpjobster_thousands_sum_separator)) $wpjobster_thousands_sum_separator = ',';

					$wpjobster_currency_position = get_option('wpjobster_currency_position');
					$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');
					$wpjobster_decimal_sum_separator = get_option('wpjobster_decimal_sum_separator');

					if (empty($wpjobster_decimal_sum_separator)) $wpjobster_decimal_sum_separator = '.';

					$arr = array( $home_fees_and_tax, $category_fees_and_tax, $subcategory_fees_and_tax );

					if (in_array(false, $arr, true)) {
						$wpjobster_enable_processingfee_tax = get_option('wpjobster_enable_processingfee_tax');
						$buyer_processing_fees_enabled = get_option('wpjobster_enable_buyer_processing_fees');
						if ($buyer_processing_fees_enabled!='disabled') {
							if($buyer_processing_fees_enabled=='percent'){
								$wpjobster_buyer_processing_fees_percent = get_option('wpjobster_buyer_processing_fees_percent');
								$percent_string = "(".$wpjobster_buyer_processing_fees_percent."%)";
								$wpjobster_buyer_processing_fees = 0;
							}else{
								$wpjobster_buyer_processing_fees_percent = 0;
								$percent_string = "";
								$wpjobster_buyer_processing_fees = get_option('wpjobster_buyer_processing_fees');
							}

							if( $wpjobster_buyer_processing_fees ) { ?>
								<div class="sixteen wide column">
									<div class="processing-fees-title">
										<?php echo __("Processing Fees:",'wpjobster'); ?>
										<span id="buyer-processing-fee" class="processingfee-amount extra-price-inside"><?php echo wpjobster_get_show_price($wpjobster_buyer_processing_fees); ?></span>
									</div>
								</div>

								<div class="ui fitted divider"></div>

							<?php }
						}

						$wpjobster_enable_site_tax   = get_option('wpjobster_enable_site_tax');

						if( $wpjobster_enable_site_tax == 'yes' ){
							$master_total=0;
							$country_code = user($uid, 'country_code');
							$wpjobster_tax_percent=wpjobster_get_tax($country_code);
							$wpjobster_tax_percent = (float)$wpjobster_tax_percent; ?>

							<div class="sixteen wide column">

							<?php
							echo sprintf(__('Tax (%s&#37;)', 'wpjobster'), $wpjobster_tax_percent); ?>:

								<span class="tax-amount right" >
									<?php
									if(!isset($wpjobster_tax_amount))$wpjobster_tax_amount=0;
									 echo wpjobster_get_show_price($wpjobster_tax_amount);
									?>
								</span>

							</div>

						<?php
						}else{
							$wpjobster_tax_percent=0;
							$wpjobster_tax_amount=0;
						}

						do_action( 'list_after_tax_price', $price_homepage, 'feature' );
					} ?>

				</div><!-- END <div class="ui two column stackable grid"> -->

				<?php if($g==0){ ?>
					<p><?php echo __("There are no available featured spots at the moment.", 'wpjobster'); ?></p>
				<?php }

				if($g!=0){ ?>
					<div class="ui grid feature-payment">
						<div class="twelve wide column">
							<?php // credits
							$crds = wpjobster_get_credits( $current_user->ID );

							if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) {
								if( $crds > 0 ) { ?>
									<button name="method" value="credits" class="ui white button nomargin pay_featured_button"><?php _e('Pay with Balance','wpjobster'); ?></button>
								<?php }
							}

							$wpjobster_payment_gateways =get_wpjobster_payment_gateways();
							foreach($wpjobster_payment_gateways as $priority=>$button_arr){
								if( isset($button_arr['response_action']) || $button_arr['unique_id']=='paypal'){
									$wpjobster_gateway_enable = get_option('wpjobster_'.$button_arr['unique_id'].'_enable');
									$wpjobster_gateway_enable_featured = get_option('wpjobster_'.$button_arr['unique_id'].'_enable_featured');
									if($wpjobster_gateway_enable == "yes" && $wpjobster_gateway_enable_featured!='no'):
										do_action('wpjobster_before_'.$button_arr['unique_id'].'_featuredbutton_link' ); ?>
										<button name="method" value="<?php echo $button_arr['unique_id']?>" class="withpaypal ui white button nomargin pay_featured_button">
										<?php
											$wpjobster_gateway_button_caption = get_option('wpjobster_'.$button_arr['unique_id'].'_button_caption');
											if($wpjobster_gateway_button_caption!=''){
												echo $wpjobster_gateway_button_caption;
											}
											else { echo $button_arr['unique_id'] ;}
										?>
										</button>
										<?php
										do_action('wpjobster_after_'.$button_arr['unique_id'].'_featuredbutton_link' );
									endif;
								}
							} ?>
						</div>

						<?php do_action( 'wpjobster_purchase_featured_add_payment_method', $pid ); ?>

						<div class="four wide column">
							<div id="total_payable" class="payment-total-price" >
								<?php _e( 'Total: ','wpjobster' ); ?>
								<span id='showtotal'
								data-decimaldisplay="<?php echo get_option('wpjobster_decimals'); ?>" data-thousands="<?php echo $wpjobster_thousands_sum_separator;?>"
								data-decimal="<?php echo $wpjobster_decimal_sum_separator;?>" data-space="<?php echo $wpjobster_currency_symbol_space;?>"
								data-cur="<?php echo get_cur(); ?>"
								data-symbol="<?php echo wpjobster_get_currency_symbol(get_cur()); ?>"
								data-processingfeesenable="<?php echo $buyer_processing_fees_enabled; ?>"
								data-processingfeesfixed="<?php echo isset($wpjobster_buyer_processing_fees)?wpjobster_formats_special_exchange($wpjobster_buyer_processing_fees):0; ?>"
								data-processingfeespercent="<?php echo isset($wpjobster_buyer_processing_fees_percent)?$wpjobster_buyer_processing_fees_percent:0 ; ?>"
								data-processingfeetax="<?php echo isset($wpjobster_enable_processingfee_tax)?$wpjobster_enable_processingfee_tax:0; ?>"
								data-position="<?php  echo $wpjobster_currency_position; ?>"
								data-tax="<?php echo $wpjobster_tax_percent;?>" class="payment-item-price total" id="payment-item-price"> </span>
							</div><!-- .payment-total-price -->
						</div>
					</div>
				<?php } ?>
			</div><!-- END <div class="ui segment"> -->
		</form>
	</div>
</div>

<div class="payment-gateway-popup"></div>

<?php get_footer(); ?>
