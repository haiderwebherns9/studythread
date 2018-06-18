<?php
function wpj_latest_jobs( $cols = 4 ) {
	$nrpostsPage_home_page = get_option('wpjobster_nrpostsPage_home_page');
	if(!empty($nrpostsPage_home_page)) $nrpostsPage = $nrpostsPage_home_page;
	$pj = 1;
	$meta_querya = array(array(
			'key' => 'active',
			'value' => "1",
			'compare' => '='
		));
	$jobs_order = get_option('wpjobster_jobs_order');
	if ($jobs_order == 'new') {
		$orderby_featured = array('meta_value' => 'ASC', 'date' => 'DESC');
		$orderby_non_featured = array('date' => 'DESC');
	}
	elseif ($jobs_order == 'old') {
		$orderby_featured = array('meta_value' => 'ASC', 'date' => 'ASC');
		$orderby_non_featured = array('date' => 'ASC');
	}
	else {
		$seed = '';
		if ( isset( $_SESSION['homepage_random_seed'] ) ) {
			$seed = $_SESSION['homepage_random_seed'];
		}
		if ( empty( $seed ) ) {
			$seed = rand();
			$_SESSION['homepage_random_seed'] = $seed;
		}
		$orderby_featured = array('meta_value' => 'ASC', "RAND($seed)" => '');
		$orderby_non_featured = array("RAND($seed)" => '');
	}
	$feature_enabled = get_option('wpjobster_featured_enable');
	if ($feature_enabled=='yes') {
		$args = array(
			'post_status'=>'publish',
			'paged' => $pj,
			'post_type' => 'job',
			'meta_query' => $meta_querya ,
			'meta_key' => 'home_featured_now',
			'orderby'=> $orderby_featured
		);
	}
	else {
		$args = array(
			'post_status'=>'publish',
			'paged' => $pj,
			'post_type' => 'job',
			'meta_query' => $meta_querya ,
			'orderby'=> $orderby_non_featured
		);
	}

		if ( $cols == 3 ) {
			$wpj_job = new WPJ_Load_More_Posts( $args + array ( 'function_name' => 'wpj_get_user_post_tumb_card', 'container_class' => 'ui three cards ' ) );
		} else {
			$wpj_job = new WPJ_Load_More_Posts( $args + array ( 'function_name' => 'wpj_get_user_post_tumb_card', 'container_class' => 'ui four cards' ) );
		}

	?>
	<div class="cf relative">
		<?php echo listing_buttons_jobs(); ?>
		<div class="cf relative" style="width:100%">
			<?php if($wpj_job->have_rows()){ ?>
				<?php $wpj_job->show_posts_list_func(); ?>
			<?php }else{
				echo '<div class="no-results">' . __("Sorry, there are no posted jobs yet.","wpjobster") . '</div>';
			} ?>
		</div>
	</div>
<?php }
