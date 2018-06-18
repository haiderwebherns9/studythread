<?php
if ( ! function_exists( 'wpj_taxonomy_title_filter' ) ) {
	function wpj_taxonomy_title_filter() {
		global $query_string;
		$my_order = wpjobster_get_current_order_by_thing();
		$instant = array();
		$express = array();
		if( $my_order == "instant" ) {
			$instant = array(
				'key' => 'instant',
				'value' => "0",
				'compare' => '='
			);
		}
		if ( $my_order == "express" ) {
			$express = array(
				'key' => 'max_days',
				'value' => "1",
				'compare' => '='
			);
		}

		$closed = array(
			'key' => 'closed',
			'value' => "0",
			'compare' => '='
		);
		$active = array(
			'key' => 'active',
			'value' => "1",
			'compare' => '='
		);
		$prs_string_qu = wp_parse_args( $query_string );
		$prs_string_qu['meta_query'] = array( $closed, $active, $instant, $express );
		$force_no_custom_order = "TRUE";
		if ( $my_order == "auto" ) {
			if ( is_subcategory() )
				$meta_option = "subcategory_featured_now";
			else
				$meta_option = "category_featured_now";
			$jobs_order = get_option( 'wpjobster_jobs_order' );
			if ( $jobs_order == 'new' ){
				$orderby_featured = array( 'meta_value' => 'ASC', 'date' => 'DESC' );
				$order_non_featured = 'DESC';
			}
			elseif ( $jobs_order == 'old' ) {
				$orderby_featured = array( 'meta_value' => 'ASC', 'date' => 'ASC' );
				$order_non_featured = 'ASC';
			} else {
				$orderby_featured = array( 'meta_value' => 'ASC', 'rand' => 'rand' );
				$order_non_featured = 'RAND';
			}
			$feature_enabled = get_option( 'wpjobster_featured_enable' );
			if ( $feature_enabled == 'yes' ) {
				$prs_string_qu['meta_key'] = $meta_option;
				$prs_string_qu['orderby'] = $orderby_featured;
				$prs_string_qu['order'] = "ASC";
				$force_no_custom_order = "FALSE";
			} else {
				$prs_string_qu['meta_key'] = "";
				$prs_string_qu['order'] = $order_non_featured;
				$force_no_custom_order = "FALSE";
			}
		}
		if ( $my_order == "new" ) {
			$prs_string_qu['meta_key'] = "";
			$prs_string_qu['orderby'] = "date";
			$prs_string_qu['order'] = "DESC";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}
		if ( $my_order == "rating" ) {
			$prs_string_qu['meta_key'] = "wpj_new_rating";
			$prs_string_qu['orderby'] = "meta_value_num";
			$prs_string_qu['order'] = "DESC";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}
		if ( $my_order == "views" ) {
			$prs_string_qu['meta_key'] = "views";
			$prs_string_qu['orderby'] = "meta_value_num";
			$prs_string_qu['order'] = "DESC";
		}
		if ( $my_order == "popularity" ) {
			$prs_string_qu['meta_key'] = "likes";
			$prs_string_qu['orderby'] = "meta_value_num";
			$prs_string_qu['order'] = "DESC";
		}
		$prs_string_qu['force_no_custom_order'] = $force_no_custom_order;

		query_posts( $prs_string_qu );
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$term_title = $term->name;
		$job_cat_term_slug = $term->slug; ?>

		<div class="ui two column stackable grid">
			<div class="eight wide column">
				<h1 class="ui header">
					<?php
						if ( empty( $term_title ) ) echo __( "All Posted Jobs", 'wpjobster' );
						else echo $term_title;
					?>
				</h1>
			</div>

			<div class="eight wide column">
				<div class="stackable-buttons right">

					<?php _e( "Filter jobs by:", 'wpjobster' ); ?>

					<a class="ui white button <?php echo ( $my_order == "auto" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'auto' ); ?>"><?php _e( "Auto", "wpjobster" ); ?></a>

					<a class="ui white button <?php echo ( $my_order == "rating" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'rating'); ?>" ><?php  _e("Rating","wpjobster"); ?></a>

					<a class="ui white button <?php echo ( $my_order == "new" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'new'); ?>"><?php _e( "New", "wpjobster" ); ?></a>

				</div>
			</div>
		</div>

	<?php }
}

if ( ! function_exists( 'wpj_taxonomy_search_form_filter' ) ) {
	function wpj_taxonomy_search_form_filter() { ?>
		<form method="get" class="ui form" action="<?php echo get_permalink( get_option( 'wpjobster_advanced_search_id' ) ); ?>">

			<div class="field">
				<input class="grey_input white lighter" type="text" size="10" placeholder="<?php _e( 'Search Term', 'wpjobster' ) ?>" value="<?php echo isset( $_GET['term1'] )?$_GET['term1']:''; ?>" name="term1" />
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
					<input class="grey_input white lighter" type="text" data-replaceplaceholder="<?php _e( 'Select a valid location', 'wpjobster' ) ?>" placeholder="<?php _e( 'Location', 'wpjobster' ) ?>" id="location_input" value="<?php if ( isset( $_GET['location'] ) ) echo $_GET['location']; ?>" name="location">
					<input id="lat" type="hidden" name="lat" id="lat" value="<?php if ( isset ( $_GET['lat'] ) ) echo $_GET['lat']; ?>">
					<input id="long" type="hidden" name="long" id="long" value="<?php if ( isset ( $_GET['long'] ) ) echo $_GET['long']; ?>">
				</div>
				<div class="field">
					<input class="grey_input white lighter" type="text" data-replaceplaceholder="<?php _e( 'Insert a valid number', 'wpjobster' ) ?>" data-replaceplaceholder2="<?php _e( 'Select a location first', 'wpjobster' ) ?>" placeholder="<?php if ( isset( $radius_placeholder ) ) echo $radius_placeholder; ?>" id="location_input_radius" value="<?php if ( isset ( $_GET['location_rad'] ) ) echo $_GET['location_rad']; ?>" name="location_rad">
				</div>

			<?php } ?>

			<div class="field">
				<div class="select-cat-tax overflow-ellipsis-dropdown">
					<?php
					if ( !isset ( $job_cat_term_slug ) ) $selected = ''; else $selected = $job_cat_term_slug;
					echo wpjobster_get_categories_name_select( 'job_cat', $selected, __( "Categories", 'wpjobster' ), "grey_input white lighter styledselect", "slug" );
					?>
				</div>
			</div>

			<?php do_action( 'wpj_advanced_search_sidebar_extra_fields' ); ?>
			<div class="field">
				<div class="two fields">
					<div class="field">

					<?php if ( isset( $_GET['min_price'] ) ) {
						$selected_min_price = $_GET['min_price'];
					} else {
						$selected_min_price='';
					} ?>

					<input class="grey_input white lighter" type="text" name="min_price" placeholder="<?php _e( 'Min Price', 'wpjobster' ) ?>" value="<?php echo $selected_min_price ?>" />
					</div>

					<div class="field days-field">
						<?php if ( isset($_GET['max_price'] ) ) {
							$selected_max_price = $_GET['max_price'];
						} else {
							$selected_max_price ='';
						} ?>

						<input class="grey_input white lighter" type="text" name="max_price" placeholder="<?php _e( 'Max Price', 'wpjobster' ) ?>" value="<?php echo $selected_max_price ?>" />
					</div>
				</div>
			</div>

			<div class="field">
				<div class="two fields wrapper-delivery-time-search">

					<?php if ( isset( $_GET['max_days'] ) ) {
						$selected_max_days = $_GET['max_days'];
					} else {
						$selected_max_days = get_option( 'wpjobster_job_max_delivery_days' );
					} ?>

					<div class="field">
						<span class="lighter inline-block"><?php _e( "Delivery", "wpjobster" ); ?></span>
					</div>

					<div class="field days-field">

							<input type="text" id="amount" name="max_days" readonly class="max-days-input" value="<?php echo $selected_max_days ?>" />
							<div id="amount-label" class="max-days-label"><?php _e( 'days', 'wpjobster' ); ?></div>

					</div>

				</div>
			</div>

			<div class="field">
				<div id="slider-range-min" data-value="<?php echo isset( $selected_max_days ) ? $selected_max_days : 30;  ?>"></div>
			</div>

			<div class="field">
				<input class="ui fluid button full-width nomargin bigger" type="submit" value="<?php _e( 'Filter Results', 'wpjobster' ) ?>" name="research_me" />
			</div>
		</form>
	<?php }
}

if ( ! function_exists( 'wpj_taxonomy_category_list_sidebar' ) ) {
	function wpj_taxonomy_category_list_sidebar() {
		$hide_empty_categories = ( get_option( 'wpjobster_display_job_empty_categories' ) == 'yes' ) ? false : true;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$term_id        = $term->term_id;
		$taxonomy_name  = get_query_var( 'taxonomy' );
		$termchildren = get_terms( $taxonomy_name, array( 'child_of' => $term_id, 'hide_empty' => $hide_empty_categories ) );
		?>

		<ul class="xoxo xyxy">
			<?php if ( count ( $termchildren ) > 0 ) { ?>
				<div class="new-subcategory-listing">
					<ul class="subcat-list">
					<?php foreach($termchildren as $ch) {  ?>
						<li><a href="<?php echo get_term_link( $ch, $taxonomy_name ) ?>"><?php echo $ch->name ?></a></li>
					<?php } ?>
					</ul>
				</div>
			<?php }else{
				echo __( 'There are no categories yet.', 'wpjobster' );
			} ?>
		</ul>
	<?php }
}

if ( ! function_exists( 'wpj_display_thumbs_taxonomy' ) ) {
	function wpj_display_thumbs_taxonomy() {
		$category_description = category_description();
		if ( ! empty( $category_description ) ){
			echo apply_filters( 'category_archive_meta', '<div class="cat-description">' . $category_description . '</div>' );
		}
		global $wp_query;
		$args = $wp_query->query_vars;
		$wpj_job = new WPJ_Load_More_Posts( $args + array( 'function_name' => 'wpj_get_user_post_tumb_card', 'container_class' => 'ui three cards' ) ); ?>
		<div class="cf" style="width:100%">
			<?php if($wpj_job->have_rows()){
				$wpj_job->show_posts_list_func();
			}else{
				echo '<div class="ui segment">' . __("Sorry, there are no posted jobs yet.","wpjobster") . '</div>';
			} ?>
		</div>
		<?php
	}
}
