<?php
get_header();
$vars = wpj_bank_details_vars();
$orderid = $vars['orderid'];
$date_made = $vars['date_made'];
$order_status = $vars['order_status'];
$current_order = $vars['current_order'];
$row = $vars['row'];
?>
<div id="content-full-ov">
	<div class="white-cnt heading-cnt ui segment">
		<?php do_action("wpjobster_before_ordertitle",$orderid);?>
		<h1 class="heading-title"><?php echo sprintf(__("Transaction #%s%s",'wpjobster'), wpjobster_camouflage_order_id($orderid,$date_made),$order_status); ?></h1>
		<?php do_action("wpjobster_after_ordertitle",$orderid);?>
	</div>

	<?php if ($current_order->payment_status == 'pending') {
		do_action("wpjobster_before_bank_details_display",$current_order); ?>

		<div class="job_post white-cnt ui segment">
			<div class="">
				<div class="padding-cnt">
					<strong><?php  _e('Bank Details', 'wpjobster'); ?>:</strong><br>
					<?php echo nl2br(get_option('wpjobster_bank_details')); ?><br>
				</div>
			</div>
		</div>

		<?php
		do_action("wpjobster_after_bank_details_display",$current_order);
		do_action("wpjobster_before_pending_order_details",$current_order);
		?>

		<div class="job_post white-cnt ui segment">
			<div class="">
				<div class="padding-cnt">
					<?php _e('Deposit Waiting!', 'wpjobster'); ?><br>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway_name); ?><br>
					<?php _e('The transaction will start as soon as you complete the payment process.', 'wpjobster'); ?><br>

					<script>
					function pending_order_process(act,order_id,payment_gateway){
						window.location="<?php bloginfo('siteurl'); ?>/?payment_response="+payment_gateway+"&payment_type=topup&order_id="+order_id+"&action=cancel";
						return 1;
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							data: "action=process_pending_order&process="+act+"&order_id=" + order_id,
							success: function(msg){
								window.location.reload();
							}
						});
					}
					</script>
					<br />
					<a class="redlink" href='javascript:void(0)' onclick='pending_order_process("cancel","<?php echo $row->id;?>","<?php echo $row->payment_gateway_name;?>")'><?php _e( 'Cancel', 'wpjobster' ); ?></a>
				</div>
			</div>
		</div>
		<?php do_action("wpjobster_after_pending_order_details",$current_order);

	} elseif ($current_order->payment_status == 'failed') {

		do_action("wpjobster_before_failed_order_details",$current_order); ?>
		<div class="job_post white-cnt ui segment">
			<div class="">
				<div class="padding-cnt">
					<?php _e('Failed!', 'wpjobster'); ?><br>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway_name); ?><br>
				</div>
			</div>
		</div>
		<?php do_action("wpjobster_after_pending_order_details",$current_order);

	} elseif ($current_order->payment_status == 'cancelled') {

		do_action("wpjobster_before_cancelled_order_details",$current_order); ?>
		<div class="job_post white-cnt ui segment">
			<div class="">
				<div class="padding-cnt">
					<?php _e('Cancelled Pending order!', 'wpjobster'); ?><br>
					<?php do_action( 'wpj_gateway_transaction_cancelled_message', $orderid, 'topup' ); ?>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway_name); ?><br>
				</div>
			</div>
		</div>
		<?php do_action("wpjobster_after_cancelled_order_details",$current_order);
	} else {
		do_action("wpjobster_before_completed_order_details",$current_order); ?>
		<div class="job_post white-cnt ui segment">
			<div class="">
				<div class="padding-cnt">
					<?php do_action("wpj_before_print_topup_completed_order",$current_order,$_GET['jb_action']); ?>
					<?php _e('Deposit Completed!', 'wpjobster'); ?><br>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway_name); ?><br>
					<?php _e('Thank you for the payment.', 'wpjobster'); ?><br>

				</div>
			</div>
		</div>
	<?php } ?>
</div><?php

get_footer();
