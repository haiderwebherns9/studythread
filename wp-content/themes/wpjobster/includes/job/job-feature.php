<?php
if ( ! function_exists( 'get_featured_start_date' ) ) {
	function get_featured_start_date($page, $pid){
		$taxonomy = 'job_cat';
		$terms = get_the_terms( $pid, $taxonomy );
		$t = $terms[0];
		$term = get_term_by( 'id', $t->term_id, $taxonomy ); // get current term
		$p = get_term($term->parent, $taxonomy);
			if( empty( $p->term_id ) ){
			$t = $terms[0];
			$category_id = $t->term_id;
					$t = isset( $terms[1] ) ? $terms[1] : '';
					$subcategory_id = isset( $t->term_id ) ? $t->term_id : '';
		}
		else{
			$t = $terms[1];
			$category_id = $t->term_id;
			$t = $terms[0];
			$subcategory_id = $t->term_id;
		}

		if($page=='homepage'){
			$current_key = 'current_home_featured_number';
			$max_number_key = 'wpjobster_featured_homepage';
			$until_key = 'home_featured_until';
		}
		elseif($page=='category'){
			$current_key = 'current_category_featured_number_'.$category_id;
			$max_number_key = 'wpjobster_featured_category';
			$until_key = 'category_featured_until';
		}
		elseif($page=='subcategory'){
			$current_key = 'current_subcategory_featured_number_'.$subcategory_id;
			$max_number_key = 'wpjobster_featured_subcategory';
			$until_key = 'subcategory_featured_until';
		}

		if(get_option( $current_key )<get_option( $max_number_key ))
			return get_midnight_date_timestamp(time());
		else{
			$tax_query = array();
			if($page=='category'){
				$tax_query = array(
						array(
								'taxonomy' => $taxonomy,
								'terms' => $category_id,
								'field' => 'term_id',
						)
				);
			}
			elseif($page=='subcategory'){
				$tax_query = array(
						array(
								'taxonomy' => $taxonomy,
								'terms' => $subcategory_id,
								'field' => 'term_id',
						)
				);
			}

			$meta_query = array(
				array(
					'key' => $until_key,
					'value' => "z",
					'compare' => '!='
				)
			);
			$args = array(
				'post_type' => 'job',
				'posts_per_page' => $max_number_key,
				'tax_query' => $tax_query,
				'meta_query' => $meta_query,
				'meta_key' => $until_key,
				'orderby'=> 'meta_value_num',
				'order' => 'DESC',
			);
			$loop = new WP_Query( $args );
			if($loop->have_posts()):
				while ( $loop->have_posts() ) : $loop->the_post();
					$start_date = get_midnight_date_timestamp(strtotime("+1 day", get_post_meta(get_the_ID(), $until_key, true)));
				endwhile;
			endif; wp_reset_query();
			return isset( $start_date ) ? $start_date : strtotime(date('Y-m-d H:i:s'));
		}
	}
}

if ( ! function_exists( 'get_featured_end_date' ) ) {
	function get_featured_end_date($start_date){
		return get_midnight_date_timestamp(strtotime('+'.(get_option('wpjobster_featured_interval') - 1).' day', $start_date));
	}
}

if ( ! function_exists( 'get_featured_start_date_from_end_date' ) ) {
	function get_featured_start_date_from_end_date($end_date){
		return get_midnight_date_timestamp(strtotime('-'.(get_option('wpjobster_featured_interval') - 1).' day', $end_date));
	}
}

if ( ! function_exists( 'update_featured_after_pay' ) ) {
	function update_featured_after_pay($page, $pid){

		$interval = get_option('wpjobster_featured_interval') - 1;

		$taxonomy = 'job_cat';
		$terms = get_the_terms( $pid, $taxonomy );
		$t = $terms[0];
		$term = get_term_by( 'id', $t->term_id, $taxonomy ); // get current term
		$p = get_term($term->parent, $taxonomy);
		if($p->term_id==""){
			$t = $terms[0];
			$category_id = $t->term_id;
			$t = $terms[1];
			$subcategory_id = $t->term_id;
		}
		else{
			$t = $terms[1];
			$category_id = $t->term_id;
			$t = $terms[0];
			$subcategory_id = $t->term_id;
		}

		if($page=='homepage'){
			$current_key = 'current_home_featured_number';
			$max_number_key = 'wpjobster_featured_homepage';
			$until_key = 'home_featured_until';
			$featured_now_key = 'home_featured_now';
		}
		elseif($page=='category'){
			$current_key = 'current_category_featured_number_'.$category_id;
			$max_number_key = 'wpjobster_featured_category';
			$until_key = 'category_featured_until';
			$featured_now_key = 'category_featured_now';
		}
		elseif($page=='subcategory'){
			$current_key = 'current_subcategory_featured_number_'.$subcategory_id;
			$max_number_key = 'wpjobster_featured_subcategory';
			$until_key = 'subcategory_featured_until';
			$featured_now_key = 'subcategory_featured_now';
		}

		$h_start_date = get_featured_start_date($page, $pid);
		$new_date = strtotime('+'.$interval.' day', $h_start_date);
		if ( get_option( $current_key ) === false ) $nb=0;
		else $nb = get_option( $current_key );
		$nb++;
		update_post_meta($pid, $until_key, $new_date);
		if($h_start_date == get_midnight_date_timestamp(time())){
			update_post_meta($pid, $featured_now_key, 'y');
			update_option( $current_key, $nb );
		}
	}
}

if ( ! function_exists( 'wpjobster_get_allfeatured_info_by_postid' ) ) {
	function wpjobster_get_allfeatured_info_by_postid($pid,$via="email"){
		//User Timezone Function
		wpjobster_timezone_change();

		$date_format = get_option( 'date_format' );
		if(get_post_meta($pid, 'home_featured_until', true)!='z' && get_post_meta($pid, 'home_featured_until', true)!=''){
				$dt = get_post_meta($pid, 'home_featured_until', true);
				if($via=='email')$all_featured_info .= "<p>";
				$all_featured_info .= __("Featured on homepage between: ", 'wpjobster');
				if($via=='email')$all_featured_info .= '<strong>';
				$all_featured_info .= date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
				if($via=='email')$all_featured_info .= '</strong>';
				if($via=='email')$all_featured_info .= "</p>";
		}
		if(get_post_meta($pid, 'category_featured_until', true)!='z' && get_post_meta($pid, 'category_featured_until', true)!=''){
				$dt = get_post_meta($pid, 'category_featured_until', true);
				if($via=='email')$all_featured_info .= "<p>";
				$all_featured_info .= __("Featured on category page between: ", 'wpjobster');
				if($via=='email')$all_featured_info .= '<strong>';
				$all_featured_info .= date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
				if($via=='email')$all_featured_info .= '</strong>';
				if($via=='email')$all_featured_info .= "</p>";
		}
		if(get_post_meta($pid, 'subcategory_featured_until', true)!='z' && get_post_meta($pid, 'subcategory_featured_until', true)!=''){
				$dt = get_post_meta($pid, 'subcategory_featured_until', true);
				if($via=='email')$all_featured_info .= "<p>";
				$all_featured_info .= __("Featured on subcategory page between: ", 'wpjobster');
				if($via=='email')$all_featured_info .= '<strong>';
				$all_featured_info .= date_i18n($date_format, get_featured_start_date_from_end_date($dt)) . " - " . date_i18n($date_format, $dt);
				if($via=='email')$all_featured_info .= '</strong>';
				if($via=='email')$all_featured_info .= "</p>";
		}
		return $all_featured_info;
	}
}
?>
