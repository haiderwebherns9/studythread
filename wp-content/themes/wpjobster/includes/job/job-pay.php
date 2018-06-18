<?php
function wpjobster_pay_for_job_area_function(){

	ob_start();

	if(isset($_GET['site_currency'])){
		$selected = $_GET['site_currency'];
	}else{
		$selected=$_COOKIE["site_currency"];
	}

	global $current_user, $wpdb;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$pid = trim($_GET['jobid']);

	if(isset($_GET['mkf'])){
		update_post_meta($pid, 'featured', 1);
		$post = array();
		$post['ID'] = $pid;
		$post['post_status'] = 'draft';
		wp_update_post( $post);
	}

	$post = get_post($_GET['jobid']);
	$jbnm = wpjobster_wrap_the_title($post->post_title, $pid);
	$prc = get_option('wpjobster_new_job_feat_listing_fee');

	if(get_post_meta($pid,'featured',true) != "1") $prc = 0;

	$wpjobster_new_job_listing_fee = get_option('wpjobster_new_job_listing_fee');
	$prc += $wpjobster_new_job_listing_fee;
	?>

	<div id="content-full-ov" data-currency="<?php echo strtoupper($selected);  ?>" class="class680">
		<h2 class="small blue first_content_title"><?php echo sprintf(__('Pay for job: %s','wpjobster'), $jbnm); ?></h2>
		<div class="p30b">
			<div class="rounded_white_container">
				<span class="skl_pay_feat">
					<?php echo sprintf(__('You are about to pay for the listing fees for your new job. <br/>The fee is <b>%s</b>. Please use the following payment methods.','wpjobster'), wpjobster_get_show_price_classic($prc)); ?>
				</span>

				<br/><br/>

				<div class="cf"><?php
					$wpjobster_paypal_enable = get_option('wpjobster_paypal_enable');
					if($wpjobster_paypal_enable == "yes")
						echo '<a data-alert-message="'.__('Paypal is only available in the following curencies: USD, EUR . Please change the currency accordingly. ','wpjobster').'" class="btn green smaller lighter payment_feat withpaypal" href="'.get_bloginfo('url').'/?jb_action=pay_featured&method=paypal&jobid='.$pid.'">'. __('Pay with PayPal','wpjobster').'</a> ';

					$wpjobster_get_credits = wpjobster_get_credits($uid);
					if($wpjobster_get_credits >= $prc)
						echo '<a class="btn green smaller lighter payment_feat" href="'.get_bloginfo('url').'/?jb_action=pay_featured_credits&jobid='.$pid.'">'. __('Pay by Virtual Currency','wpjobster').'</a> ';

					do_action('wpjobster_pay_for_featured_job', $pid); ?>
				</div>
			</div>
			<div class="bottom-border-simulator"></div>
		</div>
	</div>

	<?php
	$ret = ob_get_contents();
	ob_clean();

	return $ret;

}
