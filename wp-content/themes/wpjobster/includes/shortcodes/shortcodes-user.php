<?php
// SHORTCODES FOR USER PROFILE //

//SHORTCODE USER HEADER
wpj_add_shortcode('user_header','wpj_get_user_profile_banner_description', 1);
function wpj_get_user_profile_banner_description() {
	ob_start();

	global $wpdb, $wp_rewrite, $wp_query;
	global $current_user;
	$current_user = wp_get_current_user();
	$username_curent = $current_user->user_login;
	$username_url = isset( $wp_query->query_vars['username']) ? urldecode($wp_query->query_vars['username']):'';

	if ( $username_url ) {
		$username = $username_url;
	} else {
		$username = $username_curent;
	}

	$uid = get_user_by( 'login', $username );
	$reg = $uid->user_registered;


	$uid = get_user_by( 'login', $username );

	$banner_id = get_user_meta( $uid->ID, 'banner_id', true );
	$banner = get_user_meta( $uid->ID, 'banner', true );
	$attachmentid = get_user_meta( $uid->ID, 'attachmentid', true );

	if ( !username_exists( $username ) ) wp_redirect( site_url() );

	$joined = strtotime( $reg ) > 0 ? wpjobster_seconds_to_words_joined(time() - strtotime($reg)) : __( 'There is no record of the date', 'wpjobster' ) . '!';
	$rtg = wpjobster_get_seller_rating( $uid->ID );
	$ratinggrade = $rtg / 20;

	$last = get_user_meta( $uid->ID, 'last_user_login', true );

	if ( empty( $last ) ) $act = __( 'no activity', 'wpjobster' );
	else $act = wpjobster_prepare_seconds_to_words( current_time( 'timestamp', 1 ) - $last )." ago";

	$using_perm = wpjobster_using_permalinks();

	if ( $using_perm ) $privurl_m = get_permalink( get_option( 'wpjobster_my_account_priv_mess_page_id' ) ) . "?";
	else $privurl_m = get_bloginfo( 'url' ) . "/?page_id=" . get_option( 'wpjobster_my_account_priv_mess_page_id' ) . "&";

	if (is_user_logged_in() ) {
		$pers_id = get_option( 'wpjobster_my_account_personal_info_page_id' );
	}

	global $is_profile_pg, $usrusr;
	$usrusr = $username;
	$is_profile_pg = 1;


	if ( $banner ) { ?>

		<div class="ub-white-full ub-cover-photo" id="banner" data-attach_id="<?php echo $banner_id; ?>" style="background-image:url( '<?php echo $banner; ?>' );">
		<div class="overlay"></div>

	<?php
	} else { ?>
		<div class="ub-white-full" id="banner">
	<?php }; ?>
			<div class="ui container">
				<div class="ui two column grid user-details-wrapper">
					<div class="four wide column user-profile-responsive-name-rating">

						<div class="ub-picture">
							<?php if ( $uid->ID == $current_user->ID ) { ?>
								<?php wpjobster_avatar_upload_html5( 180, 180 ); ?>

							<?php } else { ?>
								<img width="180" height="180" border="0" src="<?php echo wpjobster_get_avatar( $uid->ID, 180, 180 ); ?>" class="user_img round-avatar" />
							<?php } ?>
								<?php if ( wpjobster_get_user_level( $uid->ID ) != 0 ) { ?>
							<div class="sidebar-user-level-profile">
								<?php wpjobster_display_user_level_badge( $uid->ID ); ?>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="twelve wide column">

						<div class="wrapper-user-profile-hide-name-rating">
							<div id="descriptionbackgroud" class="cf left w100">
								<h1 class="heading-title left"><?php echo $uid->user_login; ?>
									<?php
									if( get_option( 'wpjobster_enable_user_company' ) == 'yes' ){
										$user_company = get_user_meta( $uid->ID, 'user_company', true );
										if( $user_company ) { echo ' (' . get_user_meta( $uid->ID, 'user_company', true ) . ')'; }
									}
									$u_id = $uid->ID;
									include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );
									?>
									<?php wpjobster_display_user_subscription_icon( $uid->ID ); ?>
									<?php wpjobster_display_user_flag( $uid->ID ); ?>
									<?php wpjobster_display_user_badges( $uid->ID ); ?>
								</h1>


								<div class="ub-links" style="display: block;">
									<?php if ( $uid->ID == $current_user->ID ) { ?>
										<span class="uppercase"><a href="<?php echo get_permalink( $pers_id ); ?>"><?php _e( "Edit Profile", "wpjobster" ); ?></a></span> |
										<!-- Banner upload-->
										<?php wpjobster_banner_upload_html5( 0, 0, $banner );
									} ?>
								</div>
							</div>

							<div class="ub-rating user-profile">
								<?php
									if ( $ratinggrade != 0 ) {
										echo wpjobster_show_big_stars_our_of_number( $ratinggrade );
									} else {
										echo __( "Not rated yet", "wpjobster" );
									}
								?>
							</div>
						</div>
						<div class="ub-description">
							<?php
								$desc_content = get_user_meta( $uid->ID, 'personal_info', true );
								list( $desc_content, $validation_errors ) = filterMessagePlusErrors( $desc_content, true );

								if( get_option( 'wpjobster_wysiwyg_for_profile' ) != 'yes' ) {
									echo stripslashes( $desc_content );
								} else {
									echo wpj_description_parser( $desc_content );
								}
							?>
							<div class="user-info-desc">
								<small class="uppercase"><?php _e( "Registered", "wpjobster" ) ?>: <?php echo $joined; ?></small>
								<?php if ( get_option( 'wpjobster_enable_last_seen' ) != 'no' ) { ?>
									&nbsp;|&nbsp;
									<small class="uppercase"><?php _e( "Last seen", "wpjobster" ) ?>: <?php echo wpj_get_last_visit( $uid->ID ); ?></small>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if ( is_user_logged_in() ) {
			if ( $uid->ID != $current_user->ID ) {
				wpjobster_add_uploadifive_scripts(); ?>
				<div id="user-actions">
					<div class="ui container">
						<div id="chart-div-container" class="two ui buttons user-buttons" style="display: none;">
							<button class="ui primary button no-radius open-modal-single-request-offer">
								<?php echo __( "REQUEST CUSTOM OFFER", "wpjobster" );
								wpj_single_job_request_custom_offer( $uid->ID, get_the_ID() ); ?>
							</button>
							<button class="ui primary button no-radius <?php echo !is_user_logged_in() ? 'login-link ' : ''; ?>" onclick="window.location.href='<?php echo $privurl_m; ?>username=<?php echo $uid->user_nicename; ?>'">
								<?php _e( "CONTACT ME", "wpjobster" ); ?>
							</button>
						</div>
						<button id="user-buttons" class="ui primary button right floated graph-link no-radius user-info-arrow"><i class="angle double icon down no-margin"></i></button>
					</div>
				</div>
			<?php }
		}

	$user_banner = ob_get_contents();
	ob_clean();

	return $user_banner;

}

// SHORTCODE USER PORTFOLIO SLIDER
wpj_add_shortcode('user_portfolio_slider','wpj_get_user_profile_portfolio_slider');
function wpj_get_user_profile_portfolio_slider() {

	global $wp_query;
	global $current_user;
	$current_user = wp_get_current_user();
	$username_curent = $current_user->user_login;
	$username_url = isset( $wp_query->query_vars['username']) ? urldecode($wp_query->query_vars['username']):'';

	if ( $username_url ) {
		$username = $username_url;
	} else {
		$username = $username_curent;
	}
	$uid = get_user_by( 'login', $username );
	$slider_enabled = get_option('wpjobster_enable_user_profile_portfolio');

	$display_my_portofolio = apply_filters( 'display_or_hide_section_filter', true );
	if( $display_my_portofolio == 'true' || $uid->ID != $current_user->ID ){
		if ( $slider_enabled == 'yes' ) {

			$wpjobster_default_nr_of_pics = get_option( 'wpjobster_profile_default_nr_of_pics' );

			if ( function_exists( 'wpjobster_get_portfolio_images' ) ) {
				$portfolio_images = wpjobster_get_portfolio_images( $uid->ID, $wpjobster_default_nr_of_pics );
			} else {
				$portfolio_images = array();
			}

			$slider_elements = count( $portfolio_images );

			$user_portfolio = '';

			if ( $slider_elements > 0 ) {

				$user_portfolio .= '<div class="cf main">';
					$user_portfolio .= '<div class="ui grid">';
						$user_portfolio .= '<div class="sixteen wide column">';
							$user_portfolio .= '<h2 class="heading-title fancy-underline heading-bigger-nomargin left">';
								if($uid->ID == $current_user->ID) {
									$user_portfolio .= __("My Portfolio", "wpjobster");
								} else {
									$user_portfolio .= sprintf(__("%s's Portfolio", "wpjobster"), $uid->user_login);
								}
							$user_portfolio .= '</h2>';
						$user_portfolio .= '</div>';

						$no_slider_pager = $slider_elements < 2 ? 'no-slider-pager' : '';

						$user_portfolio .= '<div class="sixteen wide column ' . $no_slider_pager . '">';
							$user_portfolio .= '<div class="jb-page-image-holder-user-profile">';
								if ($slider_elements > 0) {
									$user_portfolio .= '<div class="wpj-carousel owl-carousel owl-theme">';
										$counter = 0;
										foreach ( $portfolio_images as $portfolio_image ) {
											$imageid = $portfolio_image->ID;
											$image_src = wpj_get_attachment_image_url( $imageid, array( 980, 405 ) );
											if ( $counter > 0 ) {
												$lazy_class = 'bx-lazy owl-lazy';
												$lazy_src = get_template_directory_uri() . '/images/blank.gif';
											} else {
												$lazy_class = '';
												$lazy_src = $image_src;
											}

											$user_portfolio .= '<div class="bx-lazy-container" data-hash="slide' . $counter . '">';
												$user_portfolio .= '<img src="' . $lazy_src . '" class="job-blurry-bg ' . $lazy_class . '" data-src="' . $image_src . '" />';
												$user_portfolio .= '<img src="' . $lazy_src . '" class="' . $lazy_class . '" data-src="' . $image_src . '" />';
											$user_portfolio .= '</div>';

											$counter++;
										}
									$user_portfolio .= '</div>';
								}
							$user_portfolio .= '</div>';

							if ( $slider_elements > 1 ) {
								$user_portfolio .= '<div class="image-gallery-slider-pager-container user-profile">';
									$user_portfolio .= '<div class="image-gallery-slider-pager">';
										$i = 1;
										foreach ( $portfolio_images as $portfolio_image ) {
											$imageid = $portfolio_image->ID;
											$sliderthumb = wpj_get_attachment_image_url( $imageid, array( 42, 42 ) );
											$user_portfolio .= '<a href="#slide'.$i.'">';
												$user_portfolio .= '<div class="pager-bg-thumb" style="background-image: url(\'' . $sliderthumb . '\');"></div>';
											$user_portfolio .= '</a>';
											$i++;
										}
									$user_portfolio .= '</div>';
								$user_portfolio .= '</div>';
							}
						$user_portfolio .= '</div>';
					$user_portfolio .= '</div>';
				$user_portfolio .= '</div>';

			}

			return $user_portfolio;
		}
	}
}

// SHORTCODE USER PROFILE JOBS
wpj_add_shortcode('user_profile_jobs','wpj_user_profile_my_jobs');
function wpj_user_profile_my_jobs() {

	ob_start();

	global $wp_query;
	global $current_user;
	$current_user = wp_get_current_user();
	$username_curent = $current_user->user_login;
	$username_url = isset( $wp_query->query_vars['username']) ? urldecode($wp_query->query_vars['username']):'';
	if ( $username_url ) {
		$username = $username_url;
	} else {
		$username = $username_curent;
	}
	$uid = get_user_by( 'login', $username );

	$display_my_jobs = apply_filters( 'display_or_hide_section_filter', true );
	if( $display_my_jobs == 'true' || $uid->ID != $current_user->ID ){
		if ( get_option( 'wpjobster_enable_jobs_section_on_user_profile' ) != 'no' ) { ?>
			<div class="cf p15b">
				<?php if ( get_option( 'wpjobster_enable_jobs_title' ) == "yes" ) { ?>
				<h2 class="heading-title fancy-underline heading-bigger-nomargin left">
					<?php
					if ( $uid->ID == $current_user->ID ) {
						echo __( "My Jobs", "wpjobster" );
					} else {
						echo sprintf( __( "Jobs by %s", "wpjobster" ), $uid->user_login );
					}
					?>
				</h2>

				<div class="ui hidden fitted divider"></div>

				<?php } ?>
			</div>

			<?php
			$meta_query = array(
				array(
					'key' => 'active',
					'value' => "1",
					'compare' => '='
				),
				array(
					'key' => 'closed',
					'value' => "0",
					'compare' => 'LIKE'
				)
			);

			$wpj_job = new WPJ_Load_More_Posts(
				array(
					'post_type'      => 'job',
					'function_name'  => 'wpj_get_user_post_tumb_card',
					'posts_per_page' => 12,
					'author' => $uid->ID,
					'meta_query' => $meta_query,
					'container_class' => 'ui four cards'
				)
			);
			?>



			<?php
			if ( $wpj_job->have_rows() ) {
				$wpj_job->show_posts_list_func();
			} else {
				echo __( 'Sorry, there are no posted jobs yet.', 'wpjobster' );
			}
		}
	}

	$user_jobs = ob_get_contents();
	ob_clean();

	return $user_jobs;

}


wpj_add_shortcode('user_profile_reviews','wpj_get_user_profile_reviews');
function wpj_get_user_profile_reviews() {

	ob_start();

	global $wp_query;
	global $current_user;
	$current_user = wp_get_current_user();
	$username_curent = $current_user->user_login;
	$username_url = isset( $wp_query->query_vars['username']) ? urldecode($wp_query->query_vars['username']):'';

	if ( $username_url ) {
		$username = $username_url;
	} else {
		$username = $username_curent;
	}
	$uid = get_user_by( 'login', $username );

	$total_per_load=3;
	$user_id =$uid->ID;

	global $wpdb;
	$query_feedback_total = "
		SELECT DISTINCT *, ratings.datemade datemade
		FROM {$wpdb->prefix}job_ratings ratings, {$wpdb->prefix}job_orders orders, {$wpdb->prefix}posts posts
		WHERE posts.ID=orders.pid
		AND ratings.awarded='1'
		AND orders.id=ratings.orderid
		AND posts.post_author={$user_id}
		ORDER BY datemade DESC
	";
	$r_feedback_total = $wpdb->get_results( $query_feedback_total );
	$r_seller_total = count( $r_feedback_total );

	$query = "
		SELECT DISTINCT *, ratings.datemade datemade
		FROM {$wpdb->prefix}job_ratings ratings, {$wpdb->prefix}job_orders orders, {$wpdb->prefix}posts posts
		WHERE posts.ID=orders.pid
		AND ratings.awarded='1'
		AND orders.id=ratings.orderid
		AND posts.post_author={$user_id}
		ORDER BY datemade DESC
		LIMIT $total_per_load
	";
	$r = $wpdb->get_results( $query );

	if ( count( $r ) > 0) { ?>

		<input type="hidden" id="uid" value="<?php echo $user_id?>">
		<input type="hidden" id="total_per_load" value="<?php echo $total_per_load?>">

		<div class="ui segment feedback overflow-hidden">
			<div class="ui grid">
				<div class="sixteen wide column">
					<div class="review-title">
						<h2><?php _e( "Reviews", "wpjobster" ); ?></h2>
					</div>
				</div>
			</div>

			<div style="" class="feedbacks_box_all ui grid">
				<div class="feedbacks_box visible_feedbacks_box first_feedbacks_box" id="all-feedback-box" style="display:block">

					<?php
					// loop

					$cnt = 0; // counter/incrementor
					foreach ( $r as $row ) {
						$cnt++;
						$post = $row->pid;
						$post = get_post( $post );
						$user2 = get_userdata( $row->uid );
						// first two are visible
						if ( $cnt==3 ) { /* ?>
						</div><div class="feedbacks_box">
						<?php */ }

						//after that put 4 in a block
						else if ( $cnt%4 == 3 && $cnt > 3 ) { /*
						?>
						</div><div class="feedbacks_box">
						<?php */
						}
						//get_seller_feedback_to_buyer();

						?>
					<div class="feed cf">
						<div>
							<a href="" class="left p10r job-feedback-picture"><img width="45" height="45" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar( $user2->ID, 46, 46 ); ?>" /></a>
							<div class="left job-feedback-content">
								<div class="left cb p5b w100">
									<a class="left" href="<?php echo wpj_get_user_profile_link( $user2->user_login ); ?>">
										<?php
										$custom_offer_query = "SELECT * FROM {$wpdb->prefix}job_pm WHERE initiator = {$user_id} AND custom_offer={$row->pid}";
										$custom_offer_result = $wpdb->get_results($custom_offer_query);

										if(count($custom_offer_result) > 0){
											$username = $user2->user_login . " <span class='lighter light_grey'>(custom job)</span>";
										}else{
											$username = $user2->user_login;
										}
										echo $username;
										?>
									</a>
									<div class="left p10l user-profile-rating">
										<?php echo wpjobster_show_stars_our_of_number( $row->grade ); ?>
									</div>
									<span class="grey-time p10l right">
										<?php echo date_i18n( get_option( 'date_format' ), $row->datemade ); ?>
									</span>
								</div>

								<div class="cb">
									<p><?php echo stripslashes( $row->reason ); ?></p>
								</div>
							</div>
						</div>
					</div>
					<?php
					$query_seller = "select distinct *, ratings.datemade,orders.uid as buyer_id, datemade from " . $wpdb->prefix . "job_ratings_by_seller ratings,"
								. " " . $wpdb->prefix . "job_orders orders,
					" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND posts.ID= '$row->pid' AND
					 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$user_id' and orders.id=" . $row->orderid . " order by datemade desc limit 3";
					$r_seller = $wpdb->get_results( $query_seller );

					if ( $r_seller ) {
						$row_seller = $r_seller[0];
						$user3 = get_userdata( $user_id ); ?>

					<!-- Feedback by seller -->
					<div class="feed cf feedback-answer">
						<div>
							<a href="" class="left p10r job-feedback-picture"><img width="35" height="35" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar( $user3->ID, 46, 46 ); ?>" /></a>
							<div class="left job-feedback-content">
								<div class="left cb p5b w100">
									<a class="left" href="<?php echo wpj_get_user_profile_link( $user3->user_login ); ?>">
										<a  class="left" href="<?php echo wpj_get_user_profile_link( $user3->user_login ); ?>">
											<?php
											$custom_offer_query = "SELECT * FROM {$wpdb->prefix}job_pm WHERE initiator = {$user_id} AND custom_offer={$row->pid}";
											$custom_offer_result = $wpdb->get_results($custom_offer_query);

											if(count($custom_offer_result) > 0){
												$username = $user3->user_login . " <span class='lighter light_grey'>(custom job)</span>";
											}else{
												$username = $user3->user_login;
											}
											echo $username;
											?>
										</a>
									</a>
									<div class="left p10l user-profile-rating">
										<?php echo wpjobster_show_stars_our_of_number( $row_seller->grade ); ?>
									</div>
									<span class="grey-time p10l right">
									</span>
								</div>


								<div class="cb">
									<p><?php echo stripslashes( $row_seller->reason ); ?></p>
								</div>
							</div>
						</div>
					</div>
					<?php
					}// if seller rating
				} ?>

				</div>
			</div>

			<?php if( $r_seller_total > $total_per_load ) { ?>
				<a data-rel='3' href="javascript:void(0)" id="load-more-feedback" class="btn w100 show_more-notneeeded bigger grey_btn"><?php _e( "Load More Reviews", 'wpjobster' ); ?></a>
			<?php } ?>

		</div>

		<div class="ui hidden divider"></div>

	<?php }

	wp_reset_query();
	wp_reset_postdata();

	$user_reviews = ob_get_contents();
	ob_clean();

	return $user_reviews;

}

// SHORTCODES FOR USER LEVEL //

// Level 1 shortcode [show_level_one]
wpj_add_shortcode( 'show_level_one', 'show_level_one_s' );
if (!function_exists('show_level_one_s')) {
	function show_level_one_s() {
		?>
		<div class="level-container">

			<div class="level-photo">
				<?php if(get_field('level_1_image')){ ?>
					<img src="<?php the_field('level_1_image'); ?>" width="255" height="255" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/image1.jpg" alt="server">
				<?php } ?>

				<div class="level-icon">
					<?php if(get_field('user_level_1_icon', 'options')){ ?>
					<img src="<?php the_field('user_level_1_icon', 'options'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/level-1-icon.png" alt="server">
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
}

// Level 2 shortcode [show_level_two]
wpj_add_shortcode( 'show_level_two', 'show_level_two_s' );
if (!function_exists('show_level_two_s')) {
	function show_level_two_s(){
		?>
		<div class="level-container">

			<div class="level-photo">

				<?php if(get_field('level_2_image')){ ?>
					<img src="<?php the_field('level_2_image'); ?>" width="255" height="255" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/image2.jpg" alt="server">
				<?php } ?>
				<div class="level-icon">
					<?php if(get_field('user_level_2_icon', 'options')){ ?>
					<img src="<?php the_field('user_level_2_icon', 'options'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/level-2-icon.png" alt="server">
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
}

// Level 3 shortcode [show_level_three]
wpj_add_shortcode( 'show_level_three', 'show_level_three_s' );
if (!function_exists('show_level_three_s')) {
	function show_level_three_s() {
		?>
		<div class="level-container">

			<div class="level-photo">

				<?php if(get_field('level_3_image')){ ?>
					<img src="<?php the_field('level_3_image'); ?>" width="255" height="255" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/image3.jpg" alt="server">
				<?php } ?>
				<div class="level-icon">
					<?php if(get_field('user_level_3_icon', 'options')){ ?>
					<img src="<?php the_field('user_level_3_icon', 'options'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/level-3-icon.png" alt="server">
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
}


// Commissions Table shortcode [show_commissions_table]
wpj_add_shortcode( 'show_commissions_table', 'show_commissions_table_s' );
if (!function_exists('show_commissions_table_s')) {
	function show_commissions_table_s() {
		if ( get_option( 'wpjobster_enable_site_fee' ) == 'flexible' ) {
			?>
			<div class="commissions-table">
					<div class="bs-table-header cf">
						<div class="bs-col-container cf">
							<div class="bs-col2"><?php _e("Percent per level", "wpjobster"); ?></div>
							<div class="bs-col8 greengreen"><?php _e("Level 0", "wpjobster"); ?></div>
							<div class="bs-col8 greengreen"><?php _e("Level 1", "wpjobster"); ?></div>
							<div class="bs-col8 greengreen"><?php _e("Level 2", "wpjobster"); ?></div>
							<div class="bs-col8 greengreen"><?php _e("Level 3", "wpjobster"); ?></div>
						</div>
					</div>

					<div class="bs-table-row cf">
						<div class="bs-col-container cf">
							<div class="bs-col2"><?php _e("Commission for values", "wpjobster"); ?>: <?php echo wpjobster_get_show_price(0) . " - " . wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range1_base')); ?></div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 0", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range0_level0"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 1", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range0_level1"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 2", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range0_level2"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 3", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range0_level3"); ?>%</div>
						</div>
					</div>

					<div class="bs-table-row cf">
						<div class="bs-col-container cf">
							<div class="bs-col2"><?php _e("Commission for values", "wpjobster"); ?>: <?php echo wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range1_base')) . " - " . wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range2_base')); ?></div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 0", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range1_level0"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 1", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range1_level1"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 2", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range1_level2"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 3", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range1_level3"); ?>%</div>
						</div>
					</div>

					<div class="bs-table-row cf">
						<div class="bs-col-container cf">
							<div class="bs-col2"><?php _e("Commission for values", "wpjobster"); ?>: <?php echo wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range2_base')) . " - " . wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range3_base')); ?></div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 0", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range2_level0"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 1", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range2_level1"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 2", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range2_level2"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 3", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range2_level3"); ?>%</div>
						</div>
					</div>

					<div class="bs-table-row cf">
						<div class="bs-col-container cf">
							<div class="bs-col2"><?php _e("Commission for values", "wpjobster"); ?>: <?php echo wpjobster_get_show_price(get_option('wpjobster_percent_fee_taken_range3_base')) . " +"; ?></div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 0", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range3_level0"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 1", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range3_level1"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 2", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range3_level2"); ?>%</div>
							<div class="bs-col8"><span class="responsive_titles greengreen"><?php _e("Level 3", "wpjobster"); ?></span><?php echo get_option("wpjobster_percent_fee_taken_range3_level3"); ?>%</div>
						</div>
					</div>
			</div>
			<?php
		}
	}
}
