<?php

if(!function_exists('wpjobster_my_favorites_area_function')) {
	function wpjobster_my_favorites_area_function() {

		ob_start();

		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		if (!get_option('wpjobster_my_favorites_page_id')) {
			update_option('wpjobster_my_favorites_page_id', get_the_ID());
		}
		?>

		<div id="content-full-ov">

			<div class="ui basic notpadded segment">
				<h1 class="ui header wpj-title-icon">
					<i class="heart icon"></i>
					<?php _e("My Favorites",'wpjobster'); ?>
				</h1>
			</div>

			<div class="ui segment">
				<div class="ui two column stackable grid my-fav-titles">
					<?php

					if(is_user_logged_in()) {

						$display = '<div class="upb-bookmarks-list">';

							$bookmarks = upb_get_user_meta(upb_get_user_id());
							if($bookmarks) {
								?>

								<div class="eight wide column favorite-title-table">
									<?php _e('Job Title', 'wpjobster'); ?>
								</div>
								<div class="three wide column favorite-title-table">
									<?php _e("Delivery time", "wpjobster"); ?>
								</div>
								<div class="three wide column favorite-title-table">
									<?php _e('Job Price', 'wpjobster'); ?>
								</div>
								<div class="two wide column favorite-title-table">
									<?php _e('Remove', 'wpjobster'); ?>
								</div>
				</div>
								<?php

								$display = wpj_my_favorites($display, $bookmarks);

							} else {
								$display .= '<div class="bookmark-link no-bookmarks">' . __("You do not have any bookmarked posts.", "wpjobster") . '</div>';
								$display .= '</div>';
							}

					}
					else {
						$display .= __("You must be logged in to view your bookmarks.", "wpjobster");
					}
					echo $display;
					?>
			</div>
		</div>
	</div>

	<div class="ui hidden divider"></div>

		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
} ?>
