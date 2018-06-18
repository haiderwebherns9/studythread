<?php
function wpjobster_myrequests_statuses_info() { ?>
	<a class="button-modal-open" href="#"><?php _e('Status Messages Legend', 'wpjobster'); ?></a>
	<div class="ui small modal legend">
		<i class="close icon"></i>
		<div class="header">
			<?php _e('Status Messages Legend', 'wpjobster'); ?>
		</div>
		<div class="content my-requests">
			<div class="position-relative">
				<span class="oe-status-btn oe-green oe-full"><?php _e('published', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your request is visible and can be taken by all users.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-orange oe-full"><?php _e('pending', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your request is pending review and is not yet available.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red oe-full"><?php _e('rejected', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your request failed to pass our review and is not visible to anyone.', 'wpjobster'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
