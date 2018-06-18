<?php
add_action( 'init','wpj_purchase_this_widget' );
function wpj_purchase_this_widget(){
	global $no_header_footer;
	$no_header_footer = false;
	if (isset($_GET['jb_action']) && $_GET['jb_action'] == 'purchase_this_widget') {
		$no_header_footer = true;
		add_filter('show_admin_bar', '__return_false');
	}
}

if(!isset($_SESSION)) {
	session_start();
}
	$pid = $_GET['jobid'];
	if (isset($_POST['site_currency'])) {
			$selected = $_POST['site_currency'];
		}
		else {
			$selected=$_COOKIE["site_currency"];
		}
	function wpjobster_filter_ttl($title){return __("Purchase job",'wpjobster')." - ";}
	add_filter( 'wp_title', 'wpjobster_filter_ttl', 10, 3 );


	//-----------------------------------------
	// remember to check when job is inactive
	//-----------------------------------------

	$job_under_review = get_post_meta($pid, "under_review", true);
	$job_active = get_post_meta($pid, 'active', true);

	$widget_job_state = '';

	if (!$job_under_review && $job_active) {
		$widget_job_state = 'active';
	}
	else {
		$widget_job_state = 'inactive';
	}

// pre_print_r($widget_job_state);




	$widget_user_state = '';

	if (!is_user_logged_in()) {

		//------------------------
		// you are not logged in
		//------------------------
		$widget_user_state = 'not_logged_in';

	} else {

		global $current_user;
		$current_user = wp_get_current_user();

		global $wp_query;
		$post = get_post($pid);

		if ($post->post_author == $current_user->ID) {

			//---------------------
			// you are the author
			//---------------------
			$widget_user_state = 'owner_logged_in';

		} else {

			//--------------
			// you can buy
			//--------------
			$widget_user_state = 'buyer_logged_in';

		}
	}

	$uz_ui_last = $_COOKIE["uz-ui-last-" . $pid];









	$crds = wpjobster_get_credits($current_user->ID);
	$price = get_post_meta($pid, 'price', true);
	if(empty($price)) $price = get_option('wpjobster_job_fixed_amount');

	$uid = $current_user->ID;

//===============================================================================================

			$extra_job_add = array(); $h = 0;
			$partial_ttl = 0;
			$extra_job_arr = array();

			for($k=1;$k<=10;$k++)
			{
				$extra_price    = get_post_meta($pid, 'extra'.$k.'_price',      true);
				$extra_content  = get_post_meta($pid, 'extra'.$k.'_content',    true);


				if(!empty($extra_price) && !empty($extra_content)):
					// if(!empty($_POST['extra' . $k])):

						$extra_job_add[$h]['content']   = $extra_content;
						$extra_job_add[$h]['price']     = $extra_price;
						$extra_job_add[$h]['extra_nr']  = $k;
						$h++;

						$extra_job_arr['extra_job' . $pid][$h]['extra_nr']  = $k;
						$extra_job_arr['extra_job' . $pid][$h]['price']     = $extra_price;

						$partial_ttl += $extra_price;

					// endif;
				endif;
			}
			$prc        = get_post_meta($pid, "price", true);
			$shipping   = get_post_meta($pid, 'shipping',       true);

			if(!empty($shipping)):

			else:

				$shipping = 0;

			endif;

			get_header();
			$selected=get_cur();
			?>

		<div id="content" data-currency="<?php echo strtoupper($selected);  ?>">





			<div class="widget-container uz-ui-first" <?php if ($widget_job_state == 'active' && $uz_ui_last == 'second') { echo 'style="display: none;"'; } ?>>
				<div class="cf white-cnt box_content no-border-top">
				<?php do_action('wpjobster_before_message_purchase_gig_job'); ?>
					<div class="padding-cnt payment-cnt">
					<?php if ($widget_job_state == 'active') { ?>
						<?php
						$extra_job_add = array(); $h = 0;
						$partial_ttl = 0;
						$extra_job_arr = array();
						for($k=1;$k<=3;$k++)
						{
							$extra_price    = get_post_meta($pid, 'extra'.$k.'_price',      true);
							$extra_content  = get_post_meta($pid, 'extra'.$k.'_content',    true);
							if(!empty($extra_price) && !empty($extra_content)):
								// if(!empty($_POST['extra' . $k])):
									$extra_job_add[$h]['content']   = $extra_content;
									$extra_job_add[$h]['price']     = $extra_price;
									$extra_job_add[$h]['extra_nr']  = $k;
									$h++;
									$extra_job_arr['extra_job' . $pid][$h]['extra_nr']  = $k;
									$extra_job_arr['extra_job' . $pid][$h]['price']     = $extra_price;
									$partial_ttl += $extra_price;
								// endif;
							endif;
						}
						?>


						<ul class="payment-items-list widget-payment-items-list">
							<li class="payment-main-item">
								<img width="60" height="60" class="round-avatar" src="<?php echo wpjobster_get_first_post_image($pid, 61, 61); ?>" />



								<div class="payment-main-item-content cf">
									<div class="payment-title-categories cf">
										<h3>
										<?php if (get_post_type($pid) == 'offer') {
											echo __("Private transaction with", "wpjobster") . ' ' . get_userdata($post->post_author)->user_login;

										} else {
											echo get_the_title($pid);

										} ?>
										</h3>
										<div class="payment-job-categories">
											<?php echo wpjobster_display_job_categories_text(); ?>
										</div>
									</div>


								</div>
							</li>

							<li>
								<?php _e('Job base price', 'wpjobster'); ?>
								<span class="payment-item-price">
									<?php echo wpjobster_get_show_price($prc); ?>
								</span>
							</li>

									<?php
										if(count($extra_job_add) > 0) {

										$checked_extras = explode("_", $_COOKIE["uz-ui-extras-".$pid]);
										// pre_print_r($checked_extras);
										foreach($extra_job_add as $extra_job_add_item):

											$checked_html = "";
											if (in_array($extra_job_add_item['extra_nr'], $checked_extras)) {
												$checked_html = 'checked="checked" ';
											}
									?>
									<li class="cf extra-item" data-extranr="<?php echo $extra_job_add_item['extra_nr']; ?>">
										<label>



										<?php echo '<input class="widgetextracheck" type="checkbox" data-extranr="'.$extra_job_add_item['extra_nr'].'" data-price="'.wpjobster_formats_special_exchange($extra_job_add_item['price']).'" name="extra'.$extra_job_add_item['extra_nr'].'" id="extra'.$extra_job_add_item['extra_nr'].'" value="1" '.$checked_html.'/>'; ?>





									<?php echo $extra_job_add_item['content']; ?>
										<span class="payment-item-price">
										<?php echo wpjobster_get_show_price($extra_job_add_item['price']); ?>
										</span>
										</label>
									</li>
									<?php endforeach; ?>

									<?php } ?>







									<?php
										$shipping   = get_post_meta($pid, 'shipping',       true);
										if(!empty($shipping)):
									?>
							<li>
									<?php _e('Shipping', 'wpjobster'); ?>
								<span class="payment-item-price">
									<?php echo wpjobster_get_show_price($shipping); ?>
									<?php
									else:
										$shipping = 0 ;?>

							   </span>
							</li>
									<?php
									endif;
									//--------------------------------------------------------------
									?>












							<?php
								$shipping   = get_post_meta($pid, 'shipping', true);
							?>


						</ul>



						<div class="payment-total-price-holder">

							<?php global $wpjobster_currencies_array;
							if (count($wpjobster_currencies_array) > 1) { ?>
							<div class="right">

								<?php display_currency_select_secondary(); ?>

							</div>
							<?php } ?>







							<div class="payment-total-price">
								<?php _e('Total:','wpjobster'); echo " "; ?>
								<span class="total" data-credits="<?php echo wpjobster_formats_special_exchange($crds); ?>" data-price="<?php echo wpjobster_formats_special_exchange($prc + $shipping); ?>" data-cur="<?php echo get_cur(); ?>">
								<?php echo wpjobster_get_show_price(wpjobster_formats_special_exchange($prc + $shipping)); ?>
								</span>
							</div>
						</div>
					<?php } else { ?>
						<div class="center">
						<?php echo sprintf(__('This job is inactive. Please contact the author for more details or visit <a href="%s" target="_blank" class="green">wpjobster</a> for other similar jobs.', 'wpjobster'), home_url('/')); ?>
						</div>
					<?php } ?>






					</div>
				</div>




<script>
jQuery(document).ready(function(){

	function getUrlParameter(sParam)
	{
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++)
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam)
			{
				return sParameterName[1];
			}
		}
	}

	var jobid = getUrlParameter('jobid');

	var effect = 'slide';
	var options = { direction: "left" };
	var duration = 300;

	jQuery(".uz-ui-next").click(function(){
		jQuery(".uz-ui-second").toggle('slide', {direction: 'right'}, duration);
		jQuery(".uz-ui-first").toggle('slide', {direction: 'left'}, duration);
		jQuery.cookie("uz-ui-last-" + jobid, "second", {expires : 1, path: '/'});
	});
	jQuery(".uz-ui-prev").click(function(){
		jQuery(".uz-ui-first").toggle('slide', {direction: 'left'}, duration);
		jQuery(".uz-ui-second").toggle('slide', {direction: 'right'}, duration);
		jQuery.cookie("uz-ui-last-" + jobid, "first", {expires : 1, path: '/'});
	});





	function widget_check() {
		var total = Number($(".total").data("price"));
		var credits = Number($(".total").data("credits"));
		var cur = $(".total").data("cur");
		var extrs = "";
		var extrs2 = "";

		var jobid = getUrlParameter('jobid');

		$(".widget-payment-items-list [type=checkbox]").each(function(){
			if($(this).is(":checked")){
				total = total + Number($(this).data("price"));
				extrs = extrs + $(this).data("extranr") + "|";
				extrs2 = extrs2 + $(this).data("extranr") + "_";
			}
		});

		$.cookie("uz-ui-extras-" + jobid, extrs2, {expires : 1, path: '/'});

		$(".total").html(String(total.toFixed(2)).replace(".",",")+" "+cur);

		$.each($(".widget-payment-buttons-list a.widget-payment-button"), function(){
			var _href = $(this).attr("href");
			var _href_new = _href.replace(/extras=[^&]*/, 'extras=' + extrs);
			$(this).attr("href", _href_new);
		});

		$.each($(".widget-payment-buttons-list a.widget-payment-button-special"), function(){
			var _href = $(this).attr("href");
			var _href_new = _href.replace(/extras=[^&]*/, 'extras=' + extrs2);
			$(this).attr("href", _href_new);
		});

		$.each($(".widget-payment-buttons-list a.widget-payment-button-credits"), function(){
			if (total > credits) {
				$(this).css("display", "none");
			} else {
				$(this).css("display", "block");
			}

		});
	}

	widget_check();


	$(".widgetextracheck[type=checkbox]").change(function(){

		var ob=$("[name="+$(this).attr("name")+"]");
		if($(this).is(":checked")){
			ob.attr("checked","checked");
		}else{
			ob.removeAttr("checked");
		}

		widget_check();

	});





});
</script>




			<?php if ($widget_job_state == 'active') { ?>
				<div class="uz-ui-next"><?php _e("Continue", "wpjobster"); ?></div>
			<?php } ?>






			</div>



			<?php
			//----------------
			// Second Step
			//----------------
			if ($widget_job_state == 'active') {
			?>

			<div class="widget-container uz-ui-second" <?php if ($widget_job_state == 'active' && $uz_ui_last != 'second') { echo 'style="display: none;"'; } ?>>


				<div class="cf white-cnt box_content no-border-top">
					<?php do_action('wpjobster_before_message_purchase_gig_job'); ?>
						<div class="padding-cnt payment-cnt">
							<ul class="payment-items-list">
								<li class="payment-main-item">
									<img width="60" height="60" class="round-avatar" src="<?php echo wpjobster_get_first_post_image($pid, 61, 61); ?>" />



									<div class="payment-main-item-content cf">
										<div class="payment-title-categories cf">
											<h3>
											<?php if (get_post_type($pid) == 'offer') {
												echo __("Private transaction with", "wpjobster") . ' ' . get_userdata($post->post_author)->user_login;

											} else {
												echo get_the_title($pid);

											} ?>
											</h3>
											<div class="payment-job-categories">
												<?php echo wpjobster_display_job_categories_text(); ?>
											</div>
										</div>

									</div>
								</li>



								<?php
									$shipping   = get_post_meta($pid, 'shipping', true);
								?>


							</ul>



							<div class="payment-total-price-holder">

								<?php global $wpjobster_currencies_array;
								if (count($wpjobster_currencies_array) > 1) { ?>
								<div class="right">

									<?php display_currency_select_tertiary(); ?>

								</div>
								<?php } ?>

								<div class="payment-total-price">
									<?php _e('Total:','wpjobster'); echo " "; ?>
									<span class="total" data-credits="<?php echo wpjobster_formats_special_exchange($crds); ?>" data-price="<?php echo wpjobster_formats_special_exchange($prc + $shipping); ?>" data-cur="<?php echo get_cur(); ?>">
									<?php echo wpjobster_get_show_price(wpjobster_formats_special_exchange($prc + $shipping)); ?>
									</span>
								</div>
							</div>
						</div>
















					<?php if ($widget_job_state == 'active') { ?>

					<div class="uz-ui-cnt-separator"></div>

					<div class="payment-buttons cf widget-payment-buttons-list">
						<?php
						$extrs = '';
						$extrs2 = '';
						//----------------
						// not_logged_in
						//----------------

						if ($widget_user_state == 'not_logged_in') { ?>

						<div class="center">
							<div class="bs-col-container">
								<div class="bs-col2">
									<a class="login-link btn lightgrey_btn" href="<?php echo $site_url_localized; ?>/wp-login.php"><?php echo __("Login","wpjobster"); ?></a>
								</div>
								<div class="bs-col2">
									<a class="register-link btn lightgrey_btn" href="<?php echo $site_url_localized; ?>/wp-login.php?action=register"><?php echo __("Register","wpjobster"); ?></a>
								</div>
							</div>

							<div class="divider">
								<span><?php _e("or", "ajax_login_register"); ?></span>
							</div>

							<?php do_action( 'wordpress_social_login' ); ?>
						</div>

						<?php } elseif ($widget_user_state == 'buyer_logged_in') { ?>

							<?php if($crds >= ($prc + $shipping)): ?>
							<a href="<?php bloginfo('url'); ?>/?pay_for_item=credits&jobid=<?php echo $pid; ?>&confirm=1&extras=<?php echo $extrs2; ?>" class="widget-payment-button-special widget-payment-button-credits btn lightgrey_btn btn_logo_wpjobster" target="_blank"><?php _e('Pay with Balance','wpjobster'); ?></a>
							<?php endif; ?>


							<?php
								$wpjobster_paypal_enable = get_option('wpjobster_paypal_enable');
								if($wpjobster_paypal_enable == "yes"):
								do_action('wpjobster_before_paypal_link' , $pid, $extrs);
							?>
								<a data-alert-message=""  href="<?php bloginfo('url'); ?>/?pay_for_item=paypal&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="widget-payment-button withpaypal btn lightgrey_btn btn_logo_paypal" target="_blank"><?php _e('Pay with PayPal','wpjobster'); ?></a>

							<?php
								do_action('wpjobster_after_paypal_link' , $pid, $extrs);
								endif;
							?>


							<!-- #################################### -->
							 <?php /*
								 $wpjobster_moneybookers_enable = get_option('wpjobster_moneybookers_enable');
								 if($wpjobster_moneybookers_enable == "yes"):
							 ?>
								 <a href="<?php bloginfo('url'); ?>/?pay_for_item=moneybookers&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by Moneybookers','wpjobster'); ?></a> &nbsp;
							 <?php
								 endif;
							 */ ?>

							<!-- #################################### -->

							<!-- #################################### -->

							 <?php /*
								 $wpjobster_bank_enable = get_option('wpjobster_bank_enable');
								 if($wpjobster_bank_enable == "yes"):
							 ?>
								 <a href="<?php bloginfo('url'); ?>/?pay_for_item=bank&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by Bank(Offline)','wpjobster'); ?></a> &nbsp;
							 <?php
								 endif;
							 */ ?>

							<!-- #################################### -->

							<?php do_action('wpjobster_purchase_job_add_payment_method', $pid, $extrs); ?>



		<!--                 <div class="ssl-secure-payment cf">
							<img src="<?php echo get_template_directory_uri() . '/images/ssl-secure.png'; ?>" alt="SSL Icon" width="111" height="142">
							<div>
								<span class="ssl-title"><?php _e("Secure Shopping", "wpjobster"); ?></span>
								<span class="ssl-description"><?php _e("128 bit data encryption", "wpjobster"); ?></span>
							</div>
						</div> -->



						<?php } // $widget_user_state == 'buyer_logged_in'


						//----------------
						// owner_logged_in
						//----------------

						elseif ($widget_user_state == 'owner_logged_in') { ?>
							<div class="center">
							<?php _e("This is your own job."); ?>
							</div>
						<?php } ?>


					</div>

					<?php } ?>

				</div>





				<?php if ($widget_user_state == 'not_logged_in') { ?>
					<div class="uz-widget-user-bottom">
						<div class="uz-widget-user-secure uz-widget-user-grey">
							<img src="<?php echo get_template_directory_uri() . '/images/lock-white.png'; ?>" alt="SSL Lock Icon" width="17" height="20">
							<?php _e("Please login in order to buy.", "wpjobster"); ?>
						</div>
					</div>

					<div class="uz-ui-prev uz-ui-bottom-margin"><?php _e("Back", "wpjobster"); ?></div>

				<?php } elseif ($widget_user_state == 'buyer_logged_in' || $widget_user_state == 'owner_logged_in') { ?>
					<div class="uz-widget-user-bottom">
						<div class="uz-widget-user-secure">
							<img src="<?php echo get_template_directory_uri() . '/images/lock-white.png'; ?>" alt="SSL Lock Icon" width="17" height="20">

							<?php echo __("Logged in as", "wpjobster") . " " . $current_user->user_login . " (" . wpjobster_get_show_price(wpjobster_get_credits($current_user->ID)) . "). "; ?> <a href="<?php echo wp_logout_url( 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ); ?>"><?php _e("Not you?", "wpjobster"); ?></a>
						</div>
					</div>

					<div class="uz-ui-prev uz-ui-bottom-margin"><?php _e("Back", "wpjobster"); ?></div>

				<?php } else { ?>

					<div class="uz-ui-prev"><?php _e("Back", "wpjobster"); ?></div>

				<?php } ?>

			</div>

			<?php } ?>

		</div>


<?php get_footer(); ?>
