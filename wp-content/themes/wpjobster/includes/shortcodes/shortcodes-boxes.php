<?php
// User Welcome Box [user_welcome_box]
if (!function_exists('welcomebox_s')) {
	function welcomebox_s() {
		global $current_user;
		global $site_url_localized;
		?>
		<div class="ui segment">
			<div class="floating-objects">
				<h3><?php echo sprintf(__("Hi, %s", "wpjobster"), $current_user->user_login); ?></h3>
				<p><?php _e("Request the service you are looking for.", "wpjobster"); ?></p>
				<div class="sidebar-request-btn">
					<a href="<?php echo wpjobster_new_request_link(); ?>" class="ui fluid button" title=""><?php _e("Post a Request", "wpjobster"); ?></a>
				</div>
			</div>
			<?php 
			$uid = $current_user->ID;
	        $type=user($uid, 'wpjobster_user_type');
			?>
			<div class="ui segment">
				<div class="field no-padding">
				    <input  id="shw_techer" type="checkbox" value="show_teacher" <?php if (isset($_GET['teacher'])) { ?>checked<?php } ?>>   Show All Teachers 
				 </div>
			 </div>
		</div>
		<?php
	}
}
add_shortcode( 'user_welcome_box', 'welcomebox_s' );
// Services Search Bar [services_searchbar]
if (!function_exists('searchbar_s')) {
	function searchbar_s() {
		?>
		<div class="ui segment">
			<form method="get" action="<?php echo get_default_search(); ?>" class="ui form autocomplete-search">
				<div class="field no-padding">
					<div class="fields no-padding">
						<div class="thirteen wide field">
							<input type="text" name="term1" autocomplete="off" onkeyup="<?php /*suggest(this.value); */ ?>"   value="<?php if(!empty($term_search)) echo htmlspecialchars($term_search); ?>" placeholder="<?php _e('What are you looking for?','wpjobster'); ?>" />
						</div>
						<div class="three wide field">
							<div class="submit-home-loggedin">
								<input type="submit" class="ui button" value='<?php _e("Find Services",'wpjobster'); ?>' />
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}
add_shortcode( 'services_searchbar', 'searchbar_s' );


// Popular Categories Shortcode [popular_categories]
if (!function_exists('popular_categories_s')) {
	function popular_categories_s() {
		show_popular_terms('job_cat', 6);
	}
}
add_shortcode( 'popular_categories', 'popular_categories_s' );


// Recently Viewed Jobs Shortcode [recently_viewed_jobs]
if (!function_exists('recently_viewed_jobs_s')) {
	function recently_viewed_jobs_s() {
		global $current_user;
		$last_viewed = get_user_meta($current_user->ID, 'last_viewed', true);
		if ($last_viewed) {
			$args = array(
				'post_type' => 'job',
				'posts_per_page' => 5,
				'post__in' => $last_viewed,
				'ignore_sticky_posts' => true,
				'orderby' => 'post__in'
			);
			query_posts( $args );
			if ( have_posts() ) :
				?>
				<div class="ui segment">
					<div class="last-viewed-posts">
					<h4><?php _e("Recently Viewed", "wpjobster"); ?></h4>
					<?php while( have_posts() ) : the_post(); ?>
						<div class="recently-v-box">
							<div class="image">
								<a href="<?php the_permalink(); ?>">
								<?php
									$img = wpjobster_get_job_image();
								?>
									<img src="<?php echo $img; ?>" alt="">
								</a>
							</div>
							<h5><a href="<?php the_permalink(); ?>"><?php echo wpjobster_better_trim(get_the_title(), 55, '...'); ?></a></h5>
						</div>

					<?php endwhile; ?>
					</div>
					<?php wp_reset_query(); ?>

				</div>
				<?php
			endif;
		}
	}
}
add_shortcode( 'recently_viewed_jobs', 'recently_viewed_jobs_s' );

// Recently Bought Job Shortcode [recently_bought_job]
if (!function_exists('recently_bought_job_s')) {
	function recently_bought_job_s() {
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		global $wpdb; $prefix = $wpdb->prefix;
		$get_last_bought_job_query = "select pid as last_bought FROM ".$prefix."job_orders orders
						where orders.uid='$uid' AND orders.completed='1' AND orders.done_buyer='1' AND orders.done_seller='1' AND orders.closed='0' order by orders.date_made desc";

		$get_bought_jobs = $wpdb->get_results( $get_last_bought_job_query );

		$last_bought_job_only_id = '';
		foreach( $get_bought_jobs as $row_get_bought_jobs ) {
			$last_bought_job_only = get_post( $row_get_bought_jobs->last_bought );
			if ( $last_bought_job_only && $last_bought_job_only->post_type == 'job' && $last_bought_job_only->post_status == 'publish' ) {
				$last_bought_job_only_id = $last_bought_job_only->ID;
				$last_bought_job_only_title = $last_bought_job_only->post_title;
				break;
			}
		}

		if ( $last_bought_job_only_id ) {
			?>
			<div class="ui segment">
				<div class="last-viewed-posts recently-bought">
				<h4><?php _e( "Recently Bought", "wpjobster" ); ?></h4>
						<div class="recently-v-box">
						<div class="image"><a href="<?php echo get_post_permalink( $last_bought_job_only_id ); ?>">
							<?php
								$pic_id = wpjobster_get_first_post_image_ID( $last_bought_job_only_id );

								if ($pic_id != false) {
									$img = wpj_get_attachment_image_url( $pic_id, 'thumb_picture_size' );
								} else {
									$img = get_template_directory_uri() . '/images/nopic.jpg';
								}
							?>
							<img src="<?php echo $img; ?>" alt="">
						</a></div>
						<h5><a href="<?php echo get_post_permalink( $last_bought_job_only_id ); ?>"><?php echo $last_bought_job_only_title; ?></a></h5>
					</div>

					<a class="ui fluid button" href="<?php echo get_post_permalink( $last_bought_job_only_id ); ?>" class="green btn"><?php _e( "Buy it Again", "wpjobster" ); ?></a>
				</div>
			</div>
			<?php
		}
	}
}
add_shortcode( 'recently_bought_job', 'recently_bought_job_s' );
