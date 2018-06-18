<?php
if (!function_exists('wpjobster_adv_src_area_function')) {
	function wpjobster_adv_src_area_function() {
		ob_start();

		$my_order = wpjobster_get_current_order_by_thing();

		function wpj_advanced_left_filter() { ?>
			<div class="tax-request-form">
				<form method="get" class="ui form">
					<div class="field">
						<input class="white lighter" type="text" size="10" placeholder="<?php _e('Search Term','wpjobster') ?>" value="<?php echo WPJ_Form::get( 'term1', '' ); ?>" name="term1" />
					</div>

					<?php
					$wpjobster_location = get_option( 'wpjobster_location' );
					if ( $wpjobster_location == "yes" ) {
						if ( get_option( 'wpjobster_locations_unit' ) == 'miles' ) {
							$radius_placeholder = __( "Radius (miles)", "wpjobster" );
						} else {
							$radius_placeholder = __( "Radius (kilometers)", "wpjobster" );
						}
						?>
						<div class="field">
							<input class="white lighter" type="text" data-replaceplaceholder="<?php _e( 'Select a valid location', 'wpjobster' ) ?>" placeholder="<?php _e( 'Location', 'wpjobster' ) ?>" id="location_input" value="<?php echo isset( $_GET['location'] ) ? $_GET['location'] : ''; ?>" name="location">
							<input id="lat" type="hidden" name="lat" id="lat" value="<?php echo isset( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>">
							<input id="long" type="hidden" name="long" id="long" value="<?php echo isset($_GET['long']) ? $_GET['long'] : ''; ?>">
						</div>

						<div class="field">
							<input class="white lighter" type="text" data-replaceplaceholder="<?php _e( 'Insert a valid number', 'wpjobster' ) ?>" data-replaceplaceholder2="<?php _e( 'Select a location first', 'wpjobster' ) ?>" placeholder="<?php echo $radius_placeholder; ?>" id="location_input_radius" value="<?php echo isset( $_GET['location_rad'] ) ? $_GET['location_rad'] : ''; ?>" name="location_rad">
						</div>
					<?php } ?>

					<div class="field">
						<?php
						if ( !isset( $_GET['job_cat'] ) ) $selected = ''; else $selected = $_GET['job_cat'];
						echo wpjobster_get_categories_name_select( 'job_cat', $selected, __( "Categories", 'wpjobster' ), "grey_input white styledselect", "slug" ); ?>
					</div>

					<?php do_action( 'wpj_advanced_search_sidebar_extra_fields' ); ?>

					<div class="field">
						<div class="two fields">
							<div class="field">
								<?php if ( isset( $_GET['min_price'] ) ) { $selected_min_price = $_GET['min_price']; }else{ $selected_min_price = ''; } ?>
								<input class="white lighter" type="text" name="min_price" placeholder="<?php _e( 'Min Price', 'wpjobster' ) ?>" value="<?php echo $selected_min_price; ?>" />
							</div>

							<div class="field">
								<?php if ( isset( $_GET['max_price'] ) ) { $selected_max_price = $_GET['max_price']; } else { $selected_max_price = ''; } ?>
								<input class="white lighter" type="text" name="max_price" placeholder="<?php _e( 'Max Price', 'wpjobster' ) ?>" value="<?php echo $selected_max_price; ?>" />
							</div>
						</div>
					</div>

					<div class="field">
						<div class="two fields">
							<?php if ( isset( $_GET['max_days'] ) ) { $selected_max_days = $_GET['max_days']; } else { $selected_max_days = get_option( 'wpjobster_job_max_delivery_days' ); } ?>
							<div class="field responsive-left">
								<span class="lighter inline-block"><?php _e( "Delivery", "wpjobster" ); ?></span>
							</div>
							<div class="field days-deliver responsive-right">
								<input type="text" id="amount" name="max_days" readonly class="max-days-input" value="<?php echo $selected_max_days; ?>" />
								<div id="amount-label" class="max-days-label"><?php _e( 'days', 'wpjobster' ); ?></div>
							</div>
						</div>
					</div>

					<div class="field">
						<div id="slider-range-min" data-value="<?php echo isset( $selected_max_days ) ? $selected_max_days : 30;  ?>"></div>
					</div>

					<div class="field">
						<div class="cf advanced-filter-submit-holder"><input class="ui fluid button full-width nomargin bigger" type="submit" value="<?php _e( 'Filter Results', 'wpjobster' ) ?>" name="research_me" /></div>
					</div>
				</form>
			</div>
		<?php } ?>

		<div id="content-full-ov">
			<div class="ui basic notpadded segment">
				<div class="ui two column stackable grid">
					<div class="eight wide column">
						<div class="single-category-title">
							<h1 class="ui header">
								<?php echo sprintf( __("Advanced search for jobs",'wpjobster')); ?>
							</h1>
						</div>
					</div>

					<div class="eight wide column">
						<div class="stackable-buttons right">

							<?php _e("Filter jobs by:",'wpjobster'); ?>

							<a class="ui white button <?php echo ($my_order == "auto" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page('auto'); ?>"><?php _e("Auto","wpjobster"); ?></a>

							<a class="ui white button <?php echo ($my_order == "rating" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'rating'); ?>"><?php _e("Rating","wpjobster"); ?></a>

							<a class="ui white button <?php echo ($my_order == "new" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'new'); ?>"><?php _e("New","wpjobster"); ?></a>

						</div>
					</div>
				</div>
			</div>

			<div class="new-left-sidebar">
				<div class="ui segment">
					<?php wpj_advanced_left_filter(); ?>
				</div>
				<?php dynamic_sidebar( 'other-page-area' ); ?>
				<div class="ui hidden divider"></div>
			</div>

			<div class="right_container">
				<div class="post-result-advanced-search">
					<?php wpj_advanced_search_post_result(); ?>
					<div class="ui hidden divider"></div>
				</div>
			</div>
		</div><!-- #content -->
		
		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}
