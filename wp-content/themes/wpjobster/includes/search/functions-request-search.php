<?php
if (!function_exists('wpjobster_request_posts_where')) {
	function wpjobster_request_posts_where( $where ) {
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

if (!function_exists('wpjobster_request_posts_fields')) {
	function wpjobster_request_posts_fields( $select ){
		$units = get_option('wpjobster_locations_unit')=='kilometers' ? 6371 : 3959;
		$select = $select . ", {$units} * acos( cos( radians({$_GET['lat']}) ) * cos( radians( wpj_lat.meta_value ) ) * cos( radians ( wpj_long.meta_value ) - radians({$_GET['long']}) ) + sin( radians({$_GET['lat']}) ) * sin( radians ( wpj_lat.meta_value ) ) ) as distance";
		return $select;
	}
}

if (!function_exists('wpjobster_request_posts_join')) {
	function wpjobster_request_posts_join ( $join ){
		global $wpdb;
		$join = $join . "
		LEFT JOIN {$wpdb->prefix}postmeta AS wpj_lat ON ({$wpdb->prefix}posts.ID = wpj_lat.post_id AND wpj_lat.meta_key='request_lat')
		LEFT JOIN {$wpdb->prefix}postmeta AS wpj_long ON ({$wpdb->prefix}posts.ID = wpj_long.post_id AND wpj_long.meta_key='request_long')
		";
		return $join;
	}
}

if (!function_exists('wpjobster_request_posts_group_by')) {
	function wpjobster_request_posts_group_by( $groupby ){
		$rad = !empty($_GET['location_rad']) ? $_GET['location_rad'] : 1;
		$groupby = $groupby . "
		HAVING distance < {$rad}
		";
		return $groupby;
	}
}

if (!function_exists('wpjobster_request_posts_where_price')) {
	function wpjobster_request_posts_where_price( $query ) {
		;
	}
}
add_action( 'pre_get_posts', 'wpjobster_request_posts_where_price' );

function wpj_advanced_search_request_post_result() {
	$my_order = wpjobster_get_current_order_by_thing(); ?>

	<div class="post_results">
		<?php
		$meta_querya = array();
		$force_no_custom_order = TRUE;

		global $term1;
		$term1 = trim( strip_tags( WPJ_Form::get( 'term1', '' ) ) );

		if(!empty($_GET['term1'])) {
			add_filter( 'posts_where' , 'wpjobster_request_posts_where' );
		}

		if(!empty($_GET['location'])) {
			add_filter( 'posts_fields', 'wpjobster_request_posts_fields' );
			add_filter( 'posts_join_paged', 'wpjobster_request_posts_join' );
			add_filter( 'posts_groupby', 'wpjobster_request_posts_group_by' );
		}


		if(isset($_GET['order'])) $order = $_GET['order'];
		else $order = "DESC";


		if(isset($_GET['orderby'])) $orderby = $_GET['orderby'];
		else $orderby = "meta_value";


		if(isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
		else $meta_key = "";


		if ($my_order == "auto") {
			$force_no_custom_order = FALSE;
		}


		if($my_order == "new") {
			$orderby = "date";
			$order = "DESC";
			$meta_key = "";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}

		if($my_order == "old") {
			$orderby = "date";
			$order = "ASC";
			$meta_key = "";
			add_filter('apto_get_orderby', 'theme_apto_get_orderby', 10, 3);
		}

		if(isset($_GET['budget_from']) && !empty($_GET['budget_from'])) {
			$budget_from = array(
				'relation' => 'AND',
				array(
					'key' => 'budget_from',
					'value' => $_GET['budget_from'],
					'type' => 'numeric',
					'compare' => '>='
				),
				array(
					'key' => 'budget',
					'value' => 0,
					'type' => 'numeric',
					'compare' => '!='
				),
				array(
					'key' => 'budget',
					'value' => null,
					'type' => 'numeric',
					'compare' => '!='
				),
			);
		}


		if(isset($_GET['budget_to']) && !empty($_GET['budget_to'])) {
			$budget_to = array(
				'relation' => 'AND',
				array(
					'key' => 'budget',
					'value' => $_GET['budget_to'],
					'type' => 'numeric',
					'compare' => '<='
				),
				array(
					'key' => 'budget',
					'value' => 0,
					'type' => 'numeric',
					'compare' => '!='
				),
				array(
					'key' => 'budget',
					'value' => null,
					'type' => 'numeric',
					'compare' => '!='
				),
			);
		}

		if(isset($_GET['budget_to']) && $_GET['budget_to']=='0' ) {
			$budget_to = array(
				'relation' => 'AND',
				array(
					'key' => 'budget',
					'value' => $_GET['budget_to'],
					'type' => 'numeric',
					'compare' => '<='
				),
				array(
					'key' => 'budget',
					'value' => 0,
					'type' => 'numeric',
					'compare' => '!='
				),
				array(
					'key' => 'budget',
					'value' => null,
					'type' => 'numeric',
					'compare' => '!='
				),
			);
		}

		if(isset($_GET['max_days']) && !empty($_GET['max_days'])) {
			$max_days = array(
				'relation' => 'AND',
				array(
					'key' => 'job_delivery',
					'value' => $_GET['max_days'],
					'type' => 'numeric',
					'compare' => '<='
				),
				array(
					'key' => 'job_delivery',
					'value' => 0,
					'type' => 'numeric',
					'compare' => '!='
				),
				array(
					'key' => 'job_delivery',
					'value' => null,
					'type' => 'numeric',
					'compare' => '!='
				),
			);
		}

		if(isset($_GET['request_deadline']) && !empty($_GET['request_deadline'])) {
			$request_deadline = array(
				'relation' => 'AND',
				array(
					'key' => 'request_deadline',
					'value' => trim( htmlspecialchars( $_GET['request_deadline'] ) ),
					'type' => 'numeric',
					'compare' => '<='
				),
				array(
					'key' => 'request_deadline',
					'value' => 0,
					'type' => 'numeric',
					'compare' => '!='
				),
				array(
					'key' => 'request_deadline',
					'value' => null,
					'type' => 'numeric',
					'compare' => '!='
				),
			);
		}

		if( ! empty( $_GET['job_cat'] ) ){
			$adsads = array(
				'taxonomy' => 'request_cat',
				'field' => 'slug',
				'terms' => $_GET['job_cat'] . '-req'
			);
		} else {
			$adsads = '';
		}

		$meta_querya = apply_filters( 'wpj_slider_search_extra_fields', $meta_querya);

		isset($budget_from) && $budget_from ? array_push( $meta_querya,$budget_from ) : "";
		isset($budget_to) && $budget_to ? array_push( $meta_querya,$budget_to ) : "";
		isset($max_days) && $max_days ? array_push( $meta_querya,$max_days ) : "";
		isset($request_deadline) && $request_deadline ? array_push( $meta_querya,$request_deadline ) : "";

		$args = array(
			'posts_per_page' => 12,
			'post_type' => 'request',
			'order' => $order,
			'meta_query' => $meta_querya,
			'meta_key' => $meta_key,
			'orderby' => $orderby,
			'force_no_custom_order'  => $force_no_custom_order,
			'tax_query' => array($adsads),
			'search_args'          => array(
				'lat'         => ( isset( $_GET['lat'] )          && $_GET['lat'] )          ? $_GET['lat']          : '',
				'long'        => ( isset( $_GET['long'] )         && $_GET['long'] )         ? $_GET['long']         : '',
				'location'    => ( isset( $_GET['location'] )     && $_GET['location'] )     ? $_GET['location']     : '',
				'location_rad'=> ( isset( $_GET['location_rad'] ) && $_GET['location_rad'] ) ? $_GET['location_rad'] : '',
				'term1'       => ( isset( $_GET['term1'] )        && $_GET['term1'] )        ? $_GET['term1']        : '',
			)
		);

		$args = $args + array('function_name'=>'wpjobster_get_req_cat', 's'=>$term1);

		$wpj_job = new WPJ_Load_More_Posts($args);

		if($wpj_job->have_rows()){
			$wpj_job->show_posts_list_func();
		}else{
			echo '<div class="no-results">';
				_e('No results found.','wpjobster');
			echo '</div>';
		}
		?>

	</div>

<?php }
