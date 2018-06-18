<?php
function wpjobster_get_job_completed_sales( $post_id ) {

	$post_id = (int) $post_id;

	if ( false === get_post_meta_transient( $post_id, 'completed_sales' ) ) {
		global $wpdb;
		$results = $wpdb->get_row(
			"
			SELECT COUNT(*) AS count
			FROM {$wpdb->prefix}job_orders
			WHERE pid = {$post_id}
				AND done_seller='1'
				AND done_buyer='1'
				AND closed='0'
			"
		);

		if ( isset( $results->count )
			&& ( ctype_digit( $results->count ) || is_int( $results->count ) ) ) {

			set_post_meta_transient( $post_id, 'completed_sales', $results->count, 86400 );
			$count = $results->count;
		} else {
			$count = 0;
		}
	} else {
		$count = get_post_meta_transient( $post_id, 'completed_sales' );
	}

	return $count;
}

function wpjobster_sales_statuses_info() {
	?>

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
				<?php _e('The job is active and you wait for an answer from the buyer.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green"><?php _e('active', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The job is active and the buyer is waiting for you to deliver.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-orange"><?php _e('modification', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The buyer requested a modification and you need to deliver the job again.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red oe-full"><?php _e('problem', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You requested a cancellation for the current job.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red"><?php _e('problem', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The buyer requested a cancellation and you need to respond.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green oe-full"><?php _e('delivered', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You delivered the job and wait for the buyer to accept.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-green-txt"><?php _e('completed', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('The transaction was completed and accepted by the buyer.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red-txt"><?php _e('cancelled', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('You and the buyer agreed to cancel the transaction.', 'wpjobster'); ?>
				</div>
			</div>
		</div>
	</div>

	<?php
}

function get_sales_by_userid($user_id=0){

	if ($user_id != 0 && get_user_meta($user_id, 'user_total_earnings', true)) {
		return (get_user_meta($user_id, 'user_total_earnings', true));
	} else {
		return 0;
	}
}
