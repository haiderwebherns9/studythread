<?php
add_action('wp', 'uz_setup_twicedaily_complete_jobs');
function uz_setup_twicedaily_complete_jobs() {
	if (!wp_next_scheduled('uz_twicedaily_complete_jobs')) {
		wp_schedule_event(time(), 'twicedaily', 'uz_twicedaily_complete_jobs');
	}
}

add_action('uz_twicedaily_complete_jobs', 'wpjobster_close_jobs_jobs');
function wpjobster_close_jobs_jobs()
{
	global $wpdb;

	$ending = array(
		'relation' => 'AND',
		array (
			'key' => 'home_featured_until',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '<'
		),
		array (
			'key' => 'home_featured_now',
			'value' => 'y',
			'type' => 'meta_value',
			'compare' => '='
		)
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($ending));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			update_post_meta(get_the_ID(), 'home_featured_until',"z");
			update_post_meta(get_the_ID(), 'home_featured_now',"z");
			update_option( 'current_home_featured_number', get_option( 'current_home_featured_number' )-1 );
		endwhile;
	endif;

	$ending = array(
		'relation' => 'AND',
		array (
			'key' => 'category_featured_until',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '<'
		),
		array (
			'key' => 'category_featured_now',
			'value' => 'y',
			'type' => 'meta_value',
			'compare' => '='
		)
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($ending));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$pid = get_the_ID();
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
			update_post_meta(get_the_ID(), 'category_featured_until',"z");
			update_post_meta(get_the_ID(), 'category_featured_now',"z");
			update_option( 'current_category_featured_number_'.$category_id, get_option( 'current_category_featured_number_'.$category_id )-1 );
		endwhile;
	endif;

	$ending = array(
		'relation' => 'AND',
		array (
			'key' => 'subcategory_featured_until',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '<'
		),
		array (
			'key' => 'subcategory_featured_now',
			'value' => 'y',
			'type' => 'meta_value',
			'compare' => '='
		)
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($ending));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$pid = get_the_ID();
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
			update_post_meta(get_the_ID(), 'subcategory_featured_until',"z");
			update_post_meta(get_the_ID(), 'subcategory_featured_now',"z");
			update_option( 'current_subcategory_featured_number_'.$category_id, get_option( 'current_subcategory_featured_number_'.$category_id )-1 );
		endwhile;
	endif;

	$starting = array(
		'relation' => 'AND',
		array (
			'key' => 'home_featured_until',
			'value' => strtotime('+'.(get_option('wpjobster_featured_interval') - 1).' day', time()),
			'type' => 'meta_value_num',
			'compare' => '<='
		),
		array (
			'key' => 'home_featured_now',
			'value' => 'z',
			'type' => 'meta_value',
			'compare' => '='
		),
		array (
			'key' => 'home_featured_until',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '>'
		),
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($starting));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			update_post_meta(get_the_ID(), 'home_featured_now',"y");
			update_option( 'current_home_featured_number', get_option( 'current_home_featured_number' )+1 );
		endwhile;
	endif;

	$starting = array(
		'relation' => 'AND',
		array (
			'key' => 'category_featured_until',
			'value' => strtotime('+'.(get_option('wpjobster_featured_interval') - 1).' day', time()),
			'type' => 'meta_value_num',
			'compare' => '<='
		),
		array (
			'key' => 'category_featured_now',
			'value' => 'z',
			'type' => 'meta_value',
			'compare' => '='
		),
		array (
			'key' => 'category_featured_now',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '>'
		),
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($starting));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$pid = get_the_ID();
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
			update_post_meta(get_the_ID(), 'category_featured_now',"y");
			update_option( 'current_category_featured_number_'.$category_id, get_option( 'current_category_featured_number_'.$category_id )+1 );
		endwhile;
	endif;

	$starting = array(
		'relation' => 'AND',
		array (
			'key' => 'subcategory_featured_until',
			'value' => strtotime('+'.(get_option('wpjobster_featured_interval') - 1).' day', time()),
			'type' => 'meta_value_num',
			'compare' => '<='
		),
		array (
			'key' => 'subcategory_featured_now',
			'value' => 'z',
			'type' => 'meta_value',
			'compare' => '='
		),
		array (
			'key' => 'subcategory_featured_now',
			'value' => time(),
			'type' => 'meta_value_num',
			'compare' => '>'
		),
	);
	$args = array( 'posts_per_page' =>'-1', 'post_type' => 'job', 'post_status' => 'publish', 'meta_query' => array($starting));
	$the_query = new WP_Query( $args );
	if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$pid = get_the_ID();
			$taxonomy = 'job_cat';
			$terms = get_the_terms( $pid, $taxonomy );
			//pre_print_r($terms );
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
			update_post_meta(get_the_ID(), 'subcategory_featured_now',"y");
			update_option( 'current_subcategory_featured_number_'.$category_id, get_option( 'current_subcategory_featured_number_'.$category_id )+1 );
		endwhile;
	endif;

	$wpjobster_max_time_to_wait = get_option('wpjobster_max_time_to_wait');
	if(empty($wpjobster_max_time_to_wait)) $wpjobster_max_time_to_wait = 72;

	$scm = current_time('timestamp', 1) - $wpjobster_max_time_to_wait*3600;
	$s = "select * from ".$wpdb->prefix."job_orders where done_seller='1' AND completed='0' AND closed='0' AND date_finished<'$scm'";
	$r = $wpdb->get_results($s);

	foreach($r as $row)
	{
		 wpjobster_mark_completed($row->id, 1);
	}
} ?>
