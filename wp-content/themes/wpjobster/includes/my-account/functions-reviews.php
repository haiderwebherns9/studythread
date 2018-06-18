<?php

function wpj_reviews_vars() {

	$vars = array();

	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;

	global $wpdb,$wp_rewrite,$wp_query;
	$third_page = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'home';
	$pages = array( 'home', 'pending-ratings', 'my-ratings' );
	if( ! in_array($third_page, $pages) ){ $third_page = 'home'; }

	//User Timezone Function
	wpjobster_timezone_change();

	$vars = array(
		'uid' => $uid,
		'third_page' => $third_page
	);

	return $vars;

}


function wpj_feedback_tabs( $third_page ) {

	if($third_page == "home"):

		$wpj_job = new WPJ_Load_More_Queries(
			array(
				'query_type'     => 'reviews',
				'query_status'   => 'to_award',
				'function_name'  => 'wpjobster_get_to_award_ratings',
				'posts_per_page' => '10',
				'new_class_row' => 'my-account-review'
			)
		);

		if($wpj_job->have_rows()){
			$wpj_job->show_queries_list_func();
		}else{
			echo '<div class="ui segment">';
			_e("There are no reviews to be awarded.","wpjobster");
			echo '</div>';
		} ?>
	<?php elseif($third_page == "pending-ratings"): ?>
		<div class="box_content">
			<?php
			$wpj_job = new WPJ_Load_More_Queries(
				array(
					'query_type'     => 'reviews',
					'query_status'   => 'to_receive',
					'function_name'  => 'wpjobster_get_pending_ratings',
					'posts_per_page' => '10',
					'new_class_row'  => 'review-row-listing'
				)
			);

			if($wpj_job->have_rows()){
				$wpj_job->show_queries_list_func();
				echo '<div class="ui hidden divider"></div>';
			}else{
				echo '<div class="ui segment">';
					_e("You have no pending reviews.","wpjobster");
				echo '</div>';
			} ?>
		</div>
	<?php elseif($third_page == "my-ratings"): ?>
		<?php
		$wpj_job = new WPJ_Load_More_Queries(
			array(
			'query_type'     => 'reviews',
			'query_status'   => 'received',
			'function_name'  => 'wpjobster_get_my_ratings',
			'posts_per_page' => '10',
			'new_class_row'  => 'review-row-listing'
			)
		);

		if($wpj_job->have_rows()){
			$wpj_job->show_queries_list_func();
			echo '<div class="ui hidden divider"></div>';
		}else{
			echo '<div class="ui segment">';
				_e("You have no reviews.","wpjobster");
			echo '</div>';
		} ?>
	<?php endif;

}
