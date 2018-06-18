<?php
$vars = wpj_tag_vars();
function wpj_get_tag_search_filter() { ?>
	<form class="ui form" method="get" action="<?php echo get_permalink( get_option( 'wpjobster_advanced_search_id' ) ); ?>">
		<div class="field">
			<input class="grey_input white" type="text" size="10" placeholder="<?php _e( 'Search Term', 'wpjobster' ) ?>" value="<?php echo isset( $_GET['term1']) ? $_GET['term1'] : ''; ?>" name="term1" />
		</div>

		<?php
		if ( get_option( 'wpjobster_location' ) == "yes" ) { ?>
			<div class="field">
				<input class="grey_input white lighter" type="text" data-replaceplaceholder="<?php _e( 'Select a valid location', 'wpjobster' ) ?>" placeholder="<?php _e( 'Location', 'wpjobster' ) ?>" id="location_input" value="<?php echo isset( $_GET['location'] ) ? $_GET['location'] : ''; ?>" name="location">
				<input id="lat" type="hidden" name="lat" id="lat" value="<?php echo isset( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>">
				<input id="long" type="hidden" name="long" id="long" value="<?php echo isset( $_GET['long'] ) ? $_GET['long'] : ''; ?>">
			</div>
		<?php } ?>

		<div class="field">
			<?php $selected = isset( $_GET['job_cat'] ) ? $_GET['job_cat'] : '';
				if ( !isset( $_GET['job_cat'] ) ) $selected = '';
				echo wpjobster_get_categories_slug_2_top_header( 'job_cat', $selected, __( "Categories", 'wpjobster' ), "grey_input white styledselect") ?>
		</div>

		<?php do_action( 'wpj_advanced_search_sidebar_extra_fields' ); ?>

		<div class="field">
			<div class="two fields">
				<div class="field">
					<?php if ( isset( $_GET['min_price'] ) ) { $selected_min_price = $_GET['min_price']; } else { $selected_min_price = ''; } ?>
					<input class="grey_input white" type="text" name="min_price" placeholder="<?php _e( 'Min Price', 'wpjobster' ) ?>" value="<?php echo $selected_min_price ?>" />
				</div>
				<div class="field">
					<?php if ( isset( $_GET['max_price'] ) ) { $selected_max_price = $_GET['max_price']; } else { $selected_max_price = ''; } ?>
					<input class="grey_input white" type="text" name="max_price" placeholder="<?php _e('Max Price','wpjobster') ?>" value="<?php echo $selected_max_price ?>" />
				</div>
			</div>
		</div>

		<div class="field">
			<?php if (isset($_GET['max_days'])) { $selected_max_days = $_GET['max_days']; } else { $selected_max_days = get_option( 'wpjobster_job_max_delivery_days' ); } ?>
			<div class="main-margin">
				<span class="lighter inline-block"><?php _e( "Delivery", "wpjobster" ); ?></span>
				<div class="right">
					<input type="text" id="amount" name="max_days" readonly class="max-days-input" value="<?php echo $selected_max_days ?>" />
					<div id="amount-label" class="max-days-label"><?php _e( 'days', 'wpjobster' ); ?></div>
				</div>
			</div>
			<div id="slider-range-min" data-value="<?php echo isset( $selected_max_days ) ? $selected_max_days : 30;  ?>"></div>
		</div>

		<div class="field">
			<div class="cf">
				<input class="ui fluid button full-width nomargin bigger" type="submit" value="<?php _e( 'Filter Results', 'wpjobster' ) ?>" name="research_me" />
			</div>
		</div>
	</form>


<?php } ?>

<div id="content-full-ov" class="">

	<div class="ui basic notpadded segment">
		<div class="ui two column stackable grid">
			<div class="eight wide column">
				<h1 class="ui header">
					<?php echo sprintf( __("Tag: %s",'wpjobster'), urldecode( $vars['prs_string_qu']['tag'] ) ); ?>
				</h1>
			</div>
			<div class="eight wide column">
				<div class="stackable-buttons right">

					<?php _e( "Filter jobs by:", 'wpjobster' ); ?>

					<a class="ui white button <?php echo ($vars['my_order'] == "auto" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page('auto'); ?>"><?php
					_e("Auto","wpjobster"); ?></a>
					<a class="ui white button <?php echo ($vars['my_order'] == "rating" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'rating'); ?>" >
					<?php  _e("Rating","wpjobster"); ?></a>
					<a class="ui white button <?php echo ($vars['my_order'] == "new" ? 'active' : ""); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'new'); ?>"><?php
					_e("New","wpjobster"); ?></a>

				</div>
			</div>
		</div>
	</div>

	<div class="new-left-sidebar">
		<div class="ui segment">
			<?php wpj_get_tag_search_filter() ?>
			<?php dynamic_sidebar( 'other-page-area' ); ?>
		</div>

		<div class="ui hidden divider"></div>
	</div>

	<div class="right_container">
		<?php if($vars['wpj_job']->have_rows()){ ?>
			<?php $vars['wpj_job']->show_posts_list_func(); ?>
		<?php }else{
			echo __("Sorry, there are no posted jobs yet.", "wpjobster");
		} ?>
		<div class="ui hidden divider"></div>
	</div>

</div>
