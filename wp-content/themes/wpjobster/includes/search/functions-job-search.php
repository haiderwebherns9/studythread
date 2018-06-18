<?php
if (!function_exists('wpjobster_job_posts_where')) {
	function wpjobster_job_posts_where( $where ) {
		global $wpdb, $term1;
		if(!is_array($term1)){
			$terms = trim($term1);
			$term1 = explode(" ",$terms);
		}else{
			$term1 = array();
		}
		$xl = '';
		foreach($term1 as $tt) {
			$xl .= " AND ({$wpdb->posts}.post_title LIKE '%$tt%' OR {$wpdb->posts}.post_content LIKE '%$tt%')";
		}
		$where .= " AND (1=1 $xl )";
		return $where;
	}
}
if (!function_exists('wpjobster_job_posts_fields')) {
	function wpjobster_job_posts_fields( $select ){
		$units = get_option('wpjobster_locations_unit')=='kilometers' ? 6371 : 3959;
		$select = $select . ", {$units} * acos( cos( radians({$_GET['lat']}) ) * cos( radians( wpj_lat.meta_value ) ) * cos( radians ( wpj_long.meta_value ) - radians({$_GET['long']}) ) + sin( radians({$_GET['lat']}) ) * sin( radians ( wpj_lat.meta_value ) ) ) as distance";
		return $select;
	}
}
if (!function_exists('wpjobster_job_posts_join')) {
	function wpjobster_job_posts_join ( $join ){
		global $wpdb;
		$join = $join . "
		LEFT JOIN {$wpdb->prefix}postmeta AS wpj_lat ON ({$wpdb->prefix}posts.ID = wpj_lat.post_id AND wpj_lat.meta_key='lat')
		LEFT JOIN {$wpdb->prefix}postmeta AS wpj_long ON ({$wpdb->prefix}posts.ID = wpj_long.post_id AND wpj_long.meta_key='long')
		";
		return $join;
	}
}
if (!function_exists('wpjobster_job_posts_group_by')) {
	function wpjobster_job_posts_group_by( $groupby ){
		$rad = !empty($_GET['location_rad']) ? $_GET['location_rad'] : 1;
		$groupby = $groupby . "
		HAVING distance < {$rad}
		";
		return $groupby;
	}
}
if (!function_exists('wpjobster_job_posts_where_price')) {
	function wpjobster_job_posts_where_price( $query ) {
		;
	}
}
add_action( 'pre_get_posts', 'wpjobster_job_posts_where_price' );

function wpj_advanced_search_post_result() {
	$my_order = wpjobster_get_current_order_by_thing(); ?>

	<div class="post_results">

		<?php
		$meta_querya = array();
		$force_no_custom_order = TRUE;

		global $term1;
		$term1 = trim( strip_tags( WPJ_Form::get( 'term1', '' ) ) );

		if(!empty($_GET['term1'])) {
			add_filter( 'posts_where' , 'wpjobster_job_posts_where' );
		}

		if(!empty($_GET['location'])) {
			add_filter( 'posts_fields', 'wpjobster_job_posts_fields' );
			add_filter( 'posts_join_paged', 'wpjobster_job_posts_join' );
			add_filter( 'posts_groupby', 'wpjobster_job_posts_group_by' );
		}

		if(isset($_GET['order'])) $order = $_GET['order'];
		else $order = "DESC";

		if(isset($_GET['orderby'])) $orderby = $_GET['orderby'];
		else $orderby = "meta_value";

		if(isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
		else $meta_key = "featured";

		$my_order = wpjobster_get_current_order_by_thing();

		if ($my_order == "auto") {
			$force_no_custom_order = FALSE;
		}
		if($my_order == "new") {
			$orderby = "date";
			$meta_key = "";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}
		if($my_order == "views") {
			$orderby = "meta_value";
			$meta_key = "views";
		}
		if($my_order == "rating") {
			$orderby = "meta_value";
                        $meta_key = "wpj_new_rating";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}
		if($my_order == "express") {
			$express = array(
				'key' => 'max_days',
				'value' => "1",
				'compare' => '='
			);
		}
		if($my_order == "instant") {
			$instant = array(
				'key' => 'instant',
				'value' => "1",
				'compare' => '='
			);
		}
		$closed = array(
				'key' => 'closed',
				'value' => "0",
				'compare' => '='
			);
		if(isset($_GET['min_price']) && !empty($_GET['min_price'])) {
			$min_price = array(
				'key' => 'price',
				'value' => $_GET['min_price'],
				'type' => 'numeric',
				'compare' => '>='
			);
		}
		if(isset($_GET['max_price']) && !empty($_GET['max_price'])) {
			$max_price = array(
				'key' => 'price',
				'value' => $_GET['max_price'],
				'type' => 'numeric',
				'compare' => '<='
			);
		}
		if(isset($_GET['max_price']) && $_GET['max_price']=='0' ) {
			$max_price = array(
				'key' => 'price',
				'value' => $_GET['max_price'],
				'type' => 'numeric',
				'compare' => '<='
			);
		}
		if(isset($_GET['max_days']) && !empty($_GET['max_days'])) {
			$max_days = array(
				'key' => 'max_days',
				'value' => $_GET['max_days'],
				'type' => 'numeric',
				'compare' => '<='
			);
		}
		if(!empty($_GET['job_location_cat'])) $loc = array(
				'taxonomy' => 'job_location',
				'field' => 'slug',
				'terms' => $_GET['job_location_cat']
		);
		else $loc = '';
		if(!empty($_GET['job_cat'])) $adsads = array(
				'taxonomy' => 'job_cat',
				'field' => 'slug',
				'terms' => $_GET['job_cat']
		);
		else $adsads = '';
		$active = array(
			'key' => 'active',
			'value' => "1",
			'compare' => '='
		);

		$meta_querya = apply_filters( 'wpj_slider_search_extra_fields', $meta_querya);
		isset($closed)?array_push($meta_querya,$closed):"";
		isset($active)?array_push($meta_querya,$active):"";
		isset($express)?array_push($meta_querya,$express):"";
		isset($instant)?array_push($meta_querya,$instant):"";
		isset($min_price)?array_push($meta_querya,$min_price):"";
		isset($max_price)?array_push($meta_querya,$max_price):"";
		isset($max_days)?array_push($meta_querya,$max_days):"";
		isset($countries)?array_push($meta_querya,$countries):"";

		$args = array(
			'posts_per_page'       => 12,
			'post_type'            => 'job',
			'order'                => $order,
			'meta_query'           => $meta_querya,
			'meta_key'             => $meta_key,
			'orderby'              =>$orderby,
			'force_no_custom_order'=> $force_no_custom_order,
			'tax_query'            => array($loc, $adsads),
			'search_args'          => array(
				'lat'         => ( isset( $_GET['lat'] )          && $_GET['lat'] )          ? $_GET['lat']          : '',
				'long'        => ( isset( $_GET['long'] )         && $_GET['long'] )         ? $_GET['long']         : '',
				'location'    => ( isset( $_GET['location'] )     && $_GET['location'] )     ? $_GET['location']     : '',
				'location_rad'=> ( isset( $_GET['location_rad'] ) && $_GET['location_rad'] ) ? $_GET['location_rad'] : '',
				'term1'       => ( isset( $_GET['term1'] )        && $_GET['term1'] )        ? $_GET['term1']        : '',
			)
		);
		$args = $args + array( 'function_name' => 'wpj_get_user_post_tumb_card', 'container_class' => 'ui three cards', 's'=>$term1 );

		$wpj_job = new WPJ_Load_More_Posts( $args );
		if ( $wpj_job->have_rows() ) {
			$wpj_job->show_posts_list_func();
		} else {
			echo '<div class="sixteen wide column">';
			echo '<div class="no-results">' . __('No results found.','wpjobster') . '</div>';
			echo '</div>';
		}
		?>

	</div>

<?php }
