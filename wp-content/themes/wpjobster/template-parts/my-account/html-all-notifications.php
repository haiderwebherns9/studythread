<?php
if(!function_exists('wpjobster_my_account_all_notifications_area_function')) {
	function wpjobster_my_account_all_notifications_area_function(){

		ob_start();

		$vars = wpj_all_notifications_vars();
		$uid = $vars['uid'];
		$wpdb =  $vars['wpdb'];

		?>
		<div id="content-full-ov" >
			<!-- page content here -->
			<form method="POST" class="ui form">
				<div class="ui segment">
					<div class="notification-title">
						<h1><i class="announcement icon"></i><?php _e("All Notifications",'wpjobster'); ?></h1>
					</div>
					<div class="wpj-mark-all-as-read">
						<input type="submit" id="wpj-read-cnt" name="wpj-read-cnt" class="ui primary button little-right-space" value="<?php _e( 'Mark as read', 'wpjobster' ); ?>" />
						<input type="submit" id="wpj-read-all-cnt" name="wpj-read-all-cnt" class="ui secondary button" value="<?php _e( 'Mark all as read', 'wpjobster' ); ?>" />
					</div>
				</div>

				<div class="ui segment">
					<div class="cf">
						<span class="left"><a href="<?php echo get_permalink( get_option( 'wpjobster_email_settings_page_id' ) ); ?>"><?php echo get_the_title( get_option( 'wpjobster_email_settings_page_id' ) ); ?></a></span>
						<div class="ui checkbox chk-all-notify right">
							<input name="chk-all-notify" id="chk-all-notify" type="checkbox" />
							<label><?php _e( 'Check all', 'wpjobster' ); ?></label>
						</div>
					</div>
					<ul class="wpj-all-notifications-style" id="more_notifications_target">
						<?php
							$args = array(
								'limit' => 10,
								'offset' => 0,
							);
							$notifications = wpjobster_get_notifications($args);

							$not_count = wpjobster_display_notifications($notifications);
						?>
					</ul>
					<?php if( $not_count >= 10 ){ ?>
						<div class="center cf">
							<div class="ui white button wpj-ajax-button" id="more_notifications_handler" data-limit="10" data-offset="10" data-more-text="<?php _e('Load More', 'wpjobster'); ?>" data-loading-text="<?php _e('Loading...', 'wpjobster'); ?>"><?php _e('Load More', 'wpjobster'); ?></div>
						</div>
					<?php }

					if( $not_count <= 0 ){ ?>
						<div class="ui segment"><?php echo __( 'There are no notifications yet.', 'wpjobster' ); ?></div>
					<?php } ?>
				</div>
			</form>

			<div class="ui hidden divider"></div>

		</div>
		
		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}


