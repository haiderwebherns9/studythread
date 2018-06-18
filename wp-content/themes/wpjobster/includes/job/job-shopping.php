<?php
function wpjobster_shooping_active_nr($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select id from " . $prefix . "job_orders where uid='$uid' AND done_seller='0' AND done_buyer='0' AND date_finished='0' AND closed='0' and payment_status !='pending' ";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_shooping_pending_nr($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select id from " . $prefix . "job_orders where uid='$uid' AND done_seller='0' AND done_buyer='0' AND date_finished='0' AND closed='0' and (payment_status='pending' or payment_status='processing') ";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_shooping_review_nr($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select id from " . $prefix . "job_orders where uid='$uid' AND done_seller='1' AND done_buyer='0' AND closed='0'";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_shooping_cancelled_nr($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select id from " . $prefix . "job_orders where uid='$uid' AND closed='1' order by id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_shooping_completed_nr($uid){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$s = "select id from " . $prefix . "job_orders where uid='$uid' AND completed='1' order by id desc";
	$r = $wpdb->get_results($s);
	return count($r);
}

function wpjobster_shopping_statuses_info() { ?>
	<a class="button-modal-open" href="#"><?php _e('Status Messages Legend', 'wpjobster'); ?></a>
	<div class="ui small modal legend">
		<i class="close icon"></i>
		<div class="header">
			<?php _e('Status Messages Legend', 'wpjobster'); ?>
		</div>
		<div class="content sales-modal">
			<div class="position-relative">
				<span class="oe-status-btn oe-green oe-full"><?php _e('active', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The job is active and you wait for the seller to deliver.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green"><?php _e('active', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The job is active and the seller is waiting for your answer.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-orange oe-full"><?php _e('modification', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You requested a modification of the current job.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red oe-full"><?php _e('problem', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You requested a cancellation for the transaction.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red"><?php _e('problem', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The seller requested a cancellation and you need to respond.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green oe-full"><?php _e('delivered', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The job was delivered and you have to review it.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green-txt"><?php _e('completed', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The transaction was completed and accepted by you.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red-txt"><?php _e('cancelled', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You and the seller agreed to cancel the transaction.', 'wpjobster'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
