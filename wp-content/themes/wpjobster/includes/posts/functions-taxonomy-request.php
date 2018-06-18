<?php
if ( ! function_exists( 'wpj_taxonomy_request_title_filter' ) ) {
	function wpj_taxonomy_request_title_filter() {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$term_title = $term->name;
		$job_cat_term_slug = $term->slug;
		$my_order = wpjobster_get_current_order_by_thing(); ?>

		<div class="ui two column stackable grid">
			<div class="eight wide column">
				<h1 class="ui header wpj-title-icon">
					<i class="users icon"></i>
					<?php
						if ( empty( $term_title ) ) echo __( "All Posted Requests", 'wpjobster' );
						else echo $term_title;
					?>
				</h1>
			</div>

			<div class="eight wide column">
				<div class="stackable-buttons right">

					<?php _e( "Filter requests by:", 'wpjobster' ); ?>

					<a class="ui white button <?php echo ( $my_order == "auto" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'auto' ); ?>"><?php _e( "Auto", "wpjobster" ); ?></a>

					<a class="ui white button <?php echo ( $my_order == "old" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'old'); ?>" ><?php  _e("Old","wpjobster"); ?></a>

					<a class="ui white button <?php echo ( $my_order == "new" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'new'); ?>"><?php _e( "New", "wpjobster" ); ?></a>

				</div>
			</div>
		</div>

	<?php }
}
if ( ! function_exists( 'wpj_taxonomy_request_category_list_sidebar' ) ) {
	function wpj_taxonomy_request_category_list_sidebar() {
		$hide_empty_categories = ( get_option( 'wpjobster_display_request_empty_categories' ) == 'yes' ) ? false : true;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$term_id        = $term->term_id;
		$taxonomy_name  = get_query_var( 'taxonomy' );
		$termchildren = get_terms( $taxonomy_name, array( 'child_of' => $term_id, 'hide_empty' => $hide_empty_categories ) );

		$terms = get_term($term_id, $taxonomy_name);

		if( count($termchildren) > 0 || $terms->parent ){ ?>
			<ul class="xoxo xyxy">
				<div class="new-subcategory-listing">
					<ul class="subcat-list">
						<?php if(count($termchildren) > 0){
							foreach($termchildren as $ch){ ?>
								<li><a href="<?php echo get_term_link( $ch, $taxonomy_name ) ?>"><?php echo $ch->name ?></a></li>
							<?php }
						}else{
							if ( $terms->parent ) {
								$termParent = get_term($terms->parent, $taxonomy_name);
								echo '<li><a href="'.get_term_link($termParent).'">'.__('Back','wpjobster').'</a></li>';
							}
						} ?>
					</ul>
				</div>
			</ul>
		<?php } ?>
	<?php }
}

if ( ! function_exists( 'wpj_taxonomy_request_search_form_filter' ) ) {
	function wpj_taxonomy_request_search_form_filter() { ?>
		<form method="get" class="ui form" action="<?php echo get_permalink( get_option( 'wpjobster_advanced_search_request_page_id' ) ); ?>">

			<div class="field">
				<input class="grey_input white lighter" type="text" size="10" placeholder="<?php _e( 'Search Term', 'wpjobster' ) ?>" value="<?php echo isset( $_GET['term1'] ) ? $_GET['term1']:''; ?>" name="term1" />
			</div>
			<?php
			$wpjobster_location = get_option( 'wpjobster_request_location' );
			if ( $wpjobster_location == "yes" ) {
				if ( get_option( 'wpjobster_locations_unit' ) == 'miles' ) {
					$radius_placeholder = __( "Radius (miles)", "wpjobster" );
				} else {
					$radius_placeholder = __( "Radius (kilometers)", "wpjobster" );
				}
				?>
				<div class="field">
					<input class="white lighter" type="text" data-replaceplaceholder="<?php _e('Select a valid location','wpjobster') ?>" placeholder="<?php _e('Location','wpjobster') ?>" id="location_input" value="<?php echo isset( $_GET['location'] ) ? $_GET['location'] : ''; ?>" name="location">
					<input id="lat" type="hidden" name="lat" id="lat" value="<?php echo isset( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>">
					<input id="long" type="hidden" name="long" id="long" value="<?php echo isset($_GET['long']) ? $_GET['long'] : ''; ?>">
				</div>

				<div class="field">
					<input class="white lighter" type="text" data-replaceplaceholder="<?php _e('Insert a valid number','wpjobster') ?>" data-replaceplaceholder2="<?php _e('Select a location first','wpjobster') ?>" placeholder="<?php echo $radius_placeholder; ?>" id="location_input_radius" value="<?php echo isset( $_GET['location_rad'] ) ? $_GET['location_rad'] : ''; ?>" name="location_rad">
				</div>

			<?php } ?>

			<div class="field">
				<?php if(!isset($_GET['job_cat'])) $selected = '';else $selected = $_GET['job_cat'];
				echo wpjobster_get_categories_name_select('job_cat', $selected, __("Categories",'wpjobster'), "grey_input white styledselect","slug"); ?>
			</div>

			<?php do_action( 'wpj_advanced_search_sidebar_extra_fields' ); ?>

			<?php if(get_option('wpjobster_request_budget') == "yes"){ ?>
				<div class="field">
					<div class="two fields">
						<div class="field">
							<?php if (isset($_GET['budget_from'])) { $selected_budget_from = $_GET['budget_from']; }else{ $selected_budget_from=''; } ?>
							<input class="grey_input white lighter" type="text" name="budget_from" placeholder="<?php _e('Min Budget','wpjobster') ?>" value="<?php echo $selected_budget_from; ?>" />
						</div>

						<div class="field">
							<?php if ( isset($_GET['budget_to']) ) { $selected_budget_to = $_GET['budget_to']; }else{$selected_budget_to='';} ?>
							<input class="grey_input white lighter" type="text" name="budget_to" placeholder="<?php _e('Max Budget','wpjobster') ?>" value="<?php echo $selected_budget_to; ?>" />
						</div>
					</div>
				</div>
			<?php } ?>

			<script>
			jQuery(document).ready(function($){
				$( '#deadline_tax_req_input_ui' ).wpjcalendar();
			})
			</script>

			<?php if(get_option('wpjobster_request_deadline') == "yes"){ ?>
				<div class="field">
					<div class="ui calendar" id="deadline_tax_req_input_ui">
						<div class="ui input left icon">
							<i class="calendar icon"></i>
							<input class="grey_input white lighter request_datepick" type="text" placeholder="<?php _e('Deadline','wpjobster') ?>" id="request_deadline_input" value="<?php echo WPJ_Form::get( 'request_deadline', '' ); ?>" name="request_deadline">
						</div>
					</div>
				</div>
			<?php }

			if(get_option('wpjobster_request_max_deliv') == "yes"){ ?>
				<div class="field pt">
					<div class="two fields">
						<?php if ( isset( $_GET['max_days'] ) ) { $selected_max_days = $_GET['max_days']; } else { $selected_max_days = get_option( 'wpjobster_request_max_delivery_days' ); } ?>
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
			<?php } ?>

			<div class="field">
				<div class="cf advanced-filter-submit-holder"><input class="ui fluid button full-width nomargin bigger" type="submit" value="<?php _e('Filter Results','wpjobster') ?>" name="research_me" /></div>
			</div>
		</form>
	<?php }
}

function wpj_taxonomy_request_posts(){

	$vars = array();

	global $query_string;
	$my_order = wpjobster_get_current_order_by_thing();

	$force_no_custom_order = TRUE;

	if ($my_order == "auto") {
		$force_no_custom_order = FALSE;
		$orderby = '';
		$order = '';
	}

	if($my_order == "new") {
		$orderby = "date";;
		$order = "DESC";
	}

	if($my_order == "old") {
		$orderby = "date";;
		$order = "ASC";
	}

	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$term_title = $term->name;
	$wpj_request = new WPJ_Load_More_Posts(
		array(
			'post_type'      => 'request',
			'function_name'  => 'wpjobster_get_req_cat',
			'posts_per_page' => 12,
			'orderby'        => $orderby,
			'order'          => $order,
			'tax_query'      => array(
				array(
					'taxonomy' => get_query_var( 'taxonomy' ),
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				)
			)
		)
	);

	if($wpj_request->have_rows()){
		echo '<div id="suggest_jobs">';
			$wpj_request->show_posts_list_func();
		echo '</div>';
	}else{
		echo '<div class="ui segment">';
			_e('No results found.','wpjobster');
		echo '</div>';
	}
}
