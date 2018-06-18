<?php
if ( ! function_exists( 'wpj_get_single_job_location' ) ) {
	function wpj_get_single_job_location() {
		global $wp_query;
		$location_display = get_post_meta( get_the_ID(), 'location_input', true );
		$wpjobster_location = get_option('wpjobster_location');
		if ( $wpjobster_location == 'yes' ) { ?>
			<div class="job-location job-geo-location" id="job_address">
				<?php
					if ( ! empty( $location_display ) ) {
						echo '<div class="marker location"><a href="https://google.com/maps/place/' . $location_display . '" target="_blank" >' . $location_display .  '</a></div>';
					}
				?>
			</div>
		<?php }
	}
}

if ( ! function_exists( 'wpj_get_single_job_rating' ) ) {
	function wpj_get_single_job_rating( $job_id = false, $details = true ) {

		// can be used without parameter inside the loop

		if ( $job_id == false ) {
			global $wp_query;
			$job_id = get_the_ID();
		}

		$ratinggrade = wpjobster_get_job_rating( $job_id );
		$ratinggrade = ( $ratinggrade ) / 20; ?>
		<?php if ( $details ) { ?>
			<div class="job-rating-top">
		<?php } ?>
			<?php if ( $ratinggrade != 0 ) {
				if( wpjobster_get_job_ratings_number( $job_id ) >=3 ) {
					echo wpjobster_show_stars_our_of_number( $ratinggrade );
				} else {
					echo __( "Not enough ratings", "wpjobster" );
				}
			} else {
				echo __( 'Not rated yet', 'wpjobster' );
			} ?>
		<?php if ( $details ) { ?>
			</div>
			<div class="job-rating-top-text">
				<?php if ( wpjobster_get_job_ratings_number( $job_id ) >= 3 ) {

					echo wpjobster_get_job_ratings_number( $job_id ) . " " .  _n( "review", "reviews", wpjobster_get_job_ratings_number( $job_id ), "wpjobster" );

				} ?>
			</div>
		<?php } ?>
	<?php
	}
}

if ( ! function_exists( 'wpj_get_single_job_order_queue' ) ) {
	function wpj_get_single_job_order_queue() {
		global $wp_query;
		$pid = get_the_ID();
		if ( ( wpjobster_job_fake_queue( $pid ) > 0 ) || ( wpjobster_job_orders_in_queue( $pid ) > 0 ) ) { ?>
			<?php if ( wpjobster_job_fake_queue( $pid ) > 0) { ?>
				<div class="queue-order">
					<?php echo __("Orders in queue", "wpjobster") . ': ' . wpjobster_job_fake_queue( $pid ); ?>
				</div>
			<?php } elseif ( wpjobster_job_orders_in_queue( $pid ) > 0) { ?>
				<div class="queue-order">
					<?php echo __( "Orders in queue", "wpjobster" ) . ': ' . wpjobster_job_orders_in_queue( $pid ); ?>
				</div>
			<?php }
		}
	}
}

if ( ! function_exists( 'wpj_get_single_job_delivery_time' ) ) {
	function wpj_get_single_job_delivery_time() {
		global $wp_query;
		$instant    = get_post_meta( get_the_ID(), "instant", true );
		$max_days   = get_post_meta( get_the_ID(), "max_days", true ); ?>
		<div class="single-job-delivery-time">
			<?php
				if ( $instant == 1 ) {
					echo __( 'Instant', 'wpjobster' );
				} else {
					echo $max_days . " " . _n( "day", "days", $max_days, "wpjobster" );
				}
			?>
		</div>
	<?php }
}

if ( ! function_exists( 'wpj_get_single_job_thumbnail_carousel' ) ) {
	function wpj_get_single_job_thumbnail_carousel() {
		global $wp_query;
		$arrids = wpjobster_get_post_images_ids( get_the_ID(), get_option( 'wpjobster_default_nr_of_pics' ) );
		$slider_elements = get_post_meta( get_the_ID(), 'youtube_link1', true ) ? count( $arrids ) + 1 : count( $arrids ); ?>


		<div class="cf">
			<?php
			if ( $slider_elements > 0 ) {
				$i = 1;
				echo '<div class="wpj-carousel owl-carousel owl-theme">';

					if( get_post_meta(get_the_ID(), 'youtube_link1', true )!='' ){ ?>

						<a class="owl-video" data-hash="slide<?php echo $i; ?>" href="<?php echo get_post_meta(get_the_ID(), 'youtube_link1', true ); ?>"></a>

					<?php
						$i++;
					}

					$counter = 0;
					foreach( $arrids as $imageid ){
						$image_src = wpj_get_attachment_image_url( $imageid, 'job_slider_image' );

						if ( $counter > 0 ) {
							$lazy_class = 'bx-lazy owl-lazy';
							$lazy_src = get_template_directory_uri() . '/images/blank.gif';
						} else {
							$lazy_class = '';
							$lazy_src = $image_src;
						}

						echo '<div class="bx-lazy-container" data-hash="slide' . $i . '">';
						echo '<img src="' . $lazy_src . '" class="job-blurry-bg ' . $lazy_class . '" data-src="' . $image_src . '" />';
						echo '<img src="' . $lazy_src . '" class="' . $lazy_class . '" data-src="' . $image_src . '" />';
						echo '</div>';

						$counter++;
						$i++;
					}

				echo '</div>';
			}
			?>

			<?php if ( $slider_elements > 1 ) { ?>
			<div class="image-gallery-slider-pager-container">
				<div class="image-gallery-slider-pager">
					<?php
					$i = 1;
					if(get_post_meta(get_the_ID(), 'youtube_link1', true)!=''){
						$protocol = is_ssl() ? "https://" : "http://"; ?>
						<a href="#slide<?php echo $i; ?>" class="video-thumbnail">
							<div class="pager-bg-thumb" style="background-image: url('<?php echo $protocol; ?>img.youtube.com/vi/<?php echo get_youtube_id(get_post_meta(get_the_ID(), 'youtube_link1', true)); ?>/default.jpg');"></div>
						</a>
						<?php
						$i++;
					}
					foreach($arrids as $imageid){ ?>
						<?php $sliderthumb = wpj_get_attachment_image_url( $imageid, array( 42, 42 ) ); ?>
						<a href="#slide<?php echo $i; ?>">
							<div class="pager-bg-thumb" style="background-image: url('<?php echo $sliderthumb; ?>');"></div>
						</a>
						<?php $i++;
					} ?>
				</div>
			</div>
			<?php } ?>
		</div>

	<?php }
}

if ( ! function_exists( 'wpj_get_single_job_audio' ) ) {
	function wpj_get_single_job_audio() {
		global $wp_query;
		$pid_audio = get_the_ID();
		$args = array(
			'post_type' => 'attachment',
			'post_parent' => $pid_audio,
			'post_mime_type' => 'audio',
			'numberposts' => -1,
			'orderby' => 'meta_value_num date',
			'order' => 'ASC'
		);
		$attachments_audio = get_posts( $args );

		if ( !empty( $attachments_audio ) && get_option('wpjobster_audio') == "yes" ) { ?>
			<div class="ui segment">
				<div class="the_content cf">
					<div class="single-job-audio-title">
						<h2><?php _e( "Audio",'wpjobster' ); ?></h2>
					</div>
					<div class="ui divider"></div>
					<div class="audio-margin-bottom-helper">
						<?php foreach ( $attachments_audio as $attachment_audio ) { ?>
							<div id="jquery_jplayer_<?php echo $attachment_audio->ID; ?>" class="jp-jplayer"></div>

							<div id="jp_container_<?php echo $attachment_audio->ID; ?>" class="jp-flat-audio" role="application" aria-label="media player">
								<div class="jp-play-control jp-control">
									<button class="jp-play jp-button" role="button" aria-label="play" tabindex="0"></button>
								</div>
								<div class="jp-bar">
									<div class="jp-seek-bar jp-seek-bar-display"></div>
									<div class="jp-seek-bar">
										<div class="jp-play-bar"></div>
										<div class="jp-details"><span class="jp-title" aria-label="title"></span></div>
										<div class="jp-timing"><span class="jp-duration" role="timer" aria-label="duration"></span></div>
									</div>
								</div>
								<div class="jp-no-solution">
									<?php _e( 'Media Player Error', 'wpjobster' ); ?><br />
									<?php _e( 'Update your browser or Flash plugin', 'wpjobster' ); ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
		} else {
			return 0;
		}
	}
}

if ( ! function_exists( 'wpj_get_single_job_report' ) ) {
	function wpj_get_single_job_report() {

		global $wp_query, $post;
		$pid = get_the_ID();
		$job_title = get_the_title();

		if( get_option( 'wpjobster_report_job_enabled' )=='yes' ){
			$class_new='right red font-11pt';
			if(!is_user_logged_in()){
				$class_new.=' login-link';
			}else{
				$class_new.=' report-job-new';
			}
			echo "<br><a class='$class_new wpj-modal-report' id='user-report-job' href='javascript:void(0)' data-title = '". $job_title . "' data-jobid='".$pid."'>";
			echo __("Report this","wpjobster");
			echo "</a>";
		}

		?>
		<div class="ui modal report-job smaller">
			<i class="close icon"></i>
			<div class="header report">
				<?php _e('Report this job', 'wpjobster'); ?>
			</div>
			<div class="content report-form-modal"></div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'wpj_get_single_job_preview' ) ) {
	function wpj_get_single_job_preview() {
		global $wp_query;
		$job_any_attachments = get_post_meta( get_the_ID(), 'preview_job_attchments', true );
		$attachments = explode(",", $job_any_attachments);
		$job_any_attachments = array_filter($attachments, function($value) { return $value !== ''; });
		if( $job_any_attachments ){
			$job_any_attachments = implode( ",", $job_any_attachments );
		}
		if (isset($job_any_attachments) && $job_any_attachments && !empty($job_any_attachments)) { ?>

			<div class="ui segment">
				<div class="single-job-preview-title">
					<h2><?php _e( "Job Preview",'wpjobster' ); ?></h2>
				</div>

				<div class="ui divider"></div>

				<div class="file-to-download">
					<?php
						foreach ( $attachments as $attachment ) {
							if( $attachment != "" ){
								echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
								echo get_the_title( $attachment ).'</a> <span class="pm-filesize">( ' .size_format( filesize( get_attached_file( $attachment ) ) ) . ' )</span></div><br>';
							}
						}
					?>
				</div>
			</div>
			<div class="ui hidden divider fitted"></div>
		<?php
		}
	}
}

if ( ! function_exists( 'wpj_get_single_job_packages' ) ) {
	function wpj_get_single_job_packages() {
		$pid = get_the_ID();
		$wpjobster_packages = get_option('wpjobster_packages_enabled');
		$packages = get_post_meta( $pid, 'job_packages', true );

		if ( $wpjobster_packages == 'yes' && $packages == 'yes' ) { ?>
			<div id="sn-packages" class="sn-packages">
				<?php if ( $wpjobster_packages == "yes" ) {
					$package_name = get_post_meta( $pid, 'package_name', true );
					$package_description = get_post_meta( $pid, 'package_description', true );
					$package_max_days = get_post_meta( $pid, 'package_max_days', true );
					$package_price = get_post_meta( $pid, 'package_price', true );
					$package_revisions = get_post_meta( $pid, 'package_revisions', true );
					$package_custom_fields = get_post_meta( $pid, 'package_custom_fields', true ); ?>

					<table class="ui celled definition table">
						<thead>
							<tr>
								<th></th>
								<th id="pck-basic"><?php echo __( 'BASIC', 'wpjobster'); ?></th>
								<th id="pck-standard"><?php echo __( 'STANDARD', 'wpjobster'); ?></th>
								<th id="pck-premium"><?php echo __( 'PREMIUM', 'wpjobster'); ?></th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td><?php echo __( 'Name', 'wpjobster'); ?>
								</td>
								<?php if ( $package_name ) {
									foreach ( $package_name as $p_n_key => $p_name ) { ?>
										<td class="pck-name"><?php echo $p_name; ?></td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td><?php echo __( 'Description', 'wpjobster'); ?>
								</td>
								<?php if ( $package_description ) {
									foreach ( $package_description as $p_d_key => $p_desc ) { ?>
										<td><?php echo $p_desc; ?></td>
									<?php }
								} ?>
							</tr>
							<?php if( $package_custom_fields ) {
								foreach ( $package_custom_fields as $key => $value ) { ?>
									<tr class="sn-custom-fields">
										<td><?php echo $value['name']; ?>
										</td>
										<td class="pck-center">
											<?php if ( $value['basic'] == 'on' ) {
												echo '<i class="checkmark icon"></i>';
											} else {
												echo '<i class="remove icon"></i>';
											} ?>
										</td>
										<td class="pck-center">
											<?php if ( $value['standard'] == 'on' ) {
												echo '<i class="checkmark icon"></i>';
											} else {
												echo '<i class="remove icon"></i>';
											} ?>
										</td>
										<td class="pck-center">
											<?php if ( $value['premium'] == 'on' ) {
												echo '<i class="checkmark icon"></i>';
											} else {
												echo '<i class="remove icon"></i>';
											} ?>
										</td>
									</tr>
								<?php }
							} ?>
							<tr>
								<td><?php echo __( 'Delivery time', 'wpjobster'); ?>
								</td>
								<?php if ( $package_max_days ) {
									foreach ( $package_max_days as $p_md_key => $p_max_days ) { ?>
										<td class="pck-center"><?php echo sprintf( _n( '%d day', '%d days',$p_max_days, 'wpjobster' ), $p_max_days); ?></td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td><?php echo __( 'Revisions', 'wpjobster'); ?>
								</td>
								<?php if ( $package_revisions ) {
									foreach ( $package_revisions as $p_r_key => $p_rev ) { ?>
										<td class="pck-center"><?php if ( $p_rev == 'unlimited' ) { echo __( 'Unlimited', 'wpjobster' ); } else { echo $p_rev; } ?></td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td><?php echo __( 'Price', 'wpjobster'); ?>
								</td>
								<?php if ( $package_max_days ) {
									foreach ( $package_price as $p_p_key => $p_price ) { ?>
										<td data-price="<?php echo wpjobster_formats_special_exchange( $p_price ); ?>" class="pck-center pck-price"><?php echo wpjobster_get_show_price( $p_price ); ?></td>
									<?php }
								} ?>
							</tr>
							<tr>
								<td><?php echo __( 'Order', 'wpjobster'); ?>
								</td>
								<?php if ( $package_max_days ) {
									foreach ( $package_price as $p_p_key => $p_price ) { ?>
										<td class="pck-center pck-order">
											<div class="ui radio checkbox">
												<input type="radio" name="package_price" class="package_price">
												<label></label>
											</div>
										</td>
									<?php }
								} ?>
							</tr>
						</tbody>
					</table>
				<?php } ?>
			</div>
		<?php }
	}
}

if ( ! function_exists( 'wpj_get_job_packages_sidebar' ) ) {
	function wpj_get_job_packages_sidebar() {
		$pid = get_the_ID();
		$wpjobster_packages = get_option('wpjobster_packages_enabled');
		$packages = get_post_meta( $pid, 'job_packages', true );

		if ( $wpjobster_packages == 'yes' && $packages == 'yes' ) {
			function wpj_get_sidebar_package_content( $name, $arr_key ) {
				$pid = get_the_ID();

				$package_name = get_post_meta( $pid, 'package_name', true );
				$package_description = get_post_meta( $pid, 'package_description', true );
				$package_max_days = get_post_meta( $pid, 'package_max_days', true );
				$package_price = get_post_meta( $pid, 'package_price', true );
				$package_revisions = get_post_meta( $pid, 'package_revisions', true );
				$package_custom_fields = get_post_meta( $pid, 'package_custom_fields', true );

				$act = ( $arr_key == 1 ) ? 'active' : '';
				$sel = ( $arr_key == 1 ) ? 'selected' : '';
				$class = ( $arr_key == 1 ) ? 'right labeled' : '';
				$display = ( $arr_key == 1 ) ? 'block' : 'none'; ?>

				<div class="ui segment packages-sidebar <?php echo $sel; ?>">
					<div class="ui accordion">

						<div class="title <?php echo $act; ?>">
							<div class="pck-sidebar-title">
								<span class="pck-price-sidebar"><?php echo wpjobster_get_show_price( $package_price[$arr_key] ); ?></span>
								<span class="pck-title-sidebar"><?php echo $name; ?></span>
								<div class="right"><i class="dropdown icon mt-5"></i></div>
							</div>
						</div>

						<div class="content <?php echo $act; ?>">
							<p class="transition hidden">
								<div class="bold">
									<i class="wait icon"></i>
									<?php echo sprintf( _n( '%d Day Delivery', '%d Days Delivery',$package_max_days[$arr_key], 'wpjobster' ), $package_max_days[$arr_key]); ?>

									<br>

									<i class="refresh icon"></i>
									<?php if ( $package_revisions[$arr_key] == 'unlimited' ) {
										echo __( 'Unlimited Revisions', 'wpjobster' );
									} else {
										echo sprintf( _n( '%d Revision', '%d Revisions',$package_revisions[$arr_key], 'wpjobster' ), $package_revisions[$arr_key]);
									} ?>
								</div>

								<div class="pck-sidebar-name"><?php echo strtoupper( $package_name[$arr_key] ); ?></div>
								<div class="pck-sidebar-desc"><?php echo $package_description[$arr_key]; ?></div>
								<div class="pck-sidebar-fields">
									<?php if ( $package_custom_fields ) {
										foreach ($package_custom_fields as $value) {
											if ( $value[strtolower($name)] == 'on' ) {
												echo '<p>';
													echo '<i class="checkmark icon"></i>';
													echo $value['name'];
												echo '</p>';

											}
										}
									} ?>
								</div>

								<button class="ui large fluid icon button uppercase pck-sidebar-select-package <?php echo $arr_key . ' ' . $class; ?>">
									<?php echo __( 'Select this package', 'wpjobster' ); ?>
									<i class="checkmark icon" style="display: <?php echo $display; ?>"></i>
								</button>

								<a class="pck-sidebar-compare-packages" href="#sn-packages"><?php echo __( 'Compare Packages', 'wpjobster' ); ?></a>
							</p>
						</div>

					</div>
				</div>
			<?php }

			wpj_get_sidebar_package_content( __( 'Premium', 'wpjobster' ), 2 );
			wpj_get_sidebar_package_content( __( 'Standard', 'wpjobster' ), 1 );
			wpj_get_sidebar_package_content( __( 'Basic', 'wpjobster' ), 0 );
		}
	}
}

if ( ! function_exists( 'wpj_get_single_job_buy_btn_sidebar' ) ) {
	function wpj_get_single_job_buy_btn_sidebar() {
		global $wp_query;
		global $post;
		global $current_user;
		$pid = get_the_ID();
		$current_user = wp_get_current_user();
		$uid = $post->post_author;

		wpjobster_add_uploadifive_scripts();

		$author_vacation = get_user_vacation( $uid );
		if ( $author_vacation ) {
			$author_vacation_reason = $author_vacation['reason'];
		}

		$prc = get_post_meta( get_the_ID(), "price", true );
		$prc = apply_filters( 'wpjobster_single_job_price', $prc, get_the_ID() );

		$user_level = wpjobster_get_user_level( $uid );
		$wpjobster_enable_extra = get_option( 'wpjobster_enable_extra' );
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info( $uid );
		extract( $wpjobster_subscription_info );
		if ( $wpjobster_subscription_noof_extras ) {
			$wpjobster_enable_extra = 'yes';
		}

		$extra_job_add = array();
		$h = 0;
		$sts=0;

		if ( $wpjobster_enable_extra != 'no' || $wpjobster_subscription_noof_extras ) {
			$sts = get_option( 'wpjobster_get_level'.$user_level.'_extras' );
			if ( $wpjobster_subscription_noof_extras ) {
				$sts = $wpjobster_subscription_noof_extras;
			}
			if ( empty( $sts ) ) $sts = 0;

		}

		for ( $k = 1; $k <= $sts; $k++ ) {
			$extra_price    = get_post_meta( get_the_ID(), 'extra'.$k.'_price',      true );
			$extra_content  = get_post_meta( get_the_ID(), 'extra'.$k.'_content',    true );
			$extra_enabled  = get_post_meta( get_the_ID(), 'extra'.$k.'_enabled',    true );


			if ( !empty( $extra_price ) && !empty( $extra_content ) ) {

				$extra_job_add[$h]['content']   = $extra_content;
				$extra_job_add[$h]['price']     = $extra_price;
				$extra_job_add[$h]['extra_nr']  = $k;
				$extra_job_add[$h]['enabled']   = $extra_enabled;
				$h++;

			}
		}

		if ( ( $post->post_author != $current_user->ID ) && ! $author_vacation ) {

			$wpjobster_enable_site_tax   = get_option( 'wpjobster_enable_site_tax' );
			if ( get_post_meta($pid, "active", true ) == 1 ) {

				$wpjobster_currency_position = get_option( 'wpjobster_currency_position' );
				$wpjobster_currency_symbol_space = get_option( 'wpjobster_currency_symbol_space' );
				$wpjobster_decimal_sum_separator = get_option( 'wpjobster_decimal_sum_separator' );
				if ( empty( $wpjobster_decimal_sum_separator ) ) $wpjobster_decimal_sum_separator = '.';
				$wpjobster_thousands_sum_separator = get_option( 'wpjobster_thousands_sum_separator' );
				if ( empty( $wpjobster_thousands_sum_separator ) ) $wpjobster_thousands_sum_separator = ',';
				$shipping = get_post_meta( get_the_ID(), 'shipping', true );
				if ( !isset( $shipping ) ) $shipping = 0;

				if ( $wpjobster_enable_site_tax == 'yes' ) {

					$cur_uid = get_current_user_id();
					$country_code = get_user_meta( $cur_uid,"country_code",true );
					$wpjobster_tax_percent = wpjobster_get_tax( $country_code );
					$wpjobster_enable_processingfee_tax = get_option( 'wpjobster_enable_processingfee_tax' );
					$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
					if ( $wpjobster_enable_processingfee_tax == 'yes' && $buyer_processing_fees_enabled != 'disabled' ) {
						$wpjobster_enable_processingfee_tax = 'yes';
					} else {
						$wpjobster_enable_processingfee_tax = 'no';
					}

				} else {
					$wpjobster_tax_percent = 0;
					$wpjobster_enable_processingfee_tax = 'no';
				}

				$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
				if ( $buyer_processing_fees_enabled != 'disabled' ) {
					$buyer_processing_fees = wpjobster_get_site_processing_fee( $prc, $extra_price, $shipping );
					update_user_meta( $uid, 'wpjobster_buyer_chargable_fees', $buyer_processing_fees );
				} else {
					$buyer_processing_fees = 0;
				}


				$wpjobster_subscription_enabled = get_option( 'wpjobster_subscription_enabled' );
				$wpjobster_enable_multiples = get_option( 'wpjobster_enable_multiples' );
				$wpjobster_subscription_job_multiples_enabled = get_option( 'wpjobster_subscription_job_multiples_enabled' );
				$wpjobster_subscription_extra_multiples_enabled = get_option( 'wpjobster_subscription_extra_multiples_enabled' );
				if ( $wpjobster_enable_multiples == 'yes' ) {
					$wpjobster_subscription_level_j = $wpjobster_subscription_level;
					$job_amount_max = get_option( 'wpjobster_subscription_job_multiples_'.$wpjobster_subscription_level_j );
				} else {
					$wpjobster_subscription_level_j = 'level'. $user_level;
					$job_amount_max = get_option( 'wpjobster_get_'.$wpjobster_subscription_level_j.'_jobmultiples' );
				}

				if ( $wpjobster_subscription_enabled == 'yes' && $wpjobster_subscription_extra_multiples_enabled == 'yes' ){
					$wpjobster_subscription_level_e = $wpjobster_subscription_level;
					$extra_amount_max = get_option( 'wpjobster_subscription_extra_multiples_' . $wpjobster_subscription_level_e );
				} else {
					$wpjobster_subscription_level_e = 'level'. $user_level;
					$extra_amount_max = get_option( 'wpjobster_get_' . $wpjobster_subscription_level_e . '_extramultiples' );
				}

			} else {
				$job_amount_max = 1;
				$extra_amount_max = 1;
			}

		} ?>

		<?php if ( ( $post->post_author != $current_user->ID ) && ! $author_vacation ) { ?>


				<?php
				$wpjobster_enable_site_tax = get_option( 'wpjobster_enable_site_tax' );
				if ( get_post_meta( $pid, "active", true ) == 1 ) {

					$wpjobster_currency_position = get_option( 'wpjobster_currency_position' );
					$wpjobster_currency_symbol_space = get_option( 'wpjobster_currency_symbol_space' );
					$wpjobster_decimal_sum_separator = get_option( 'wpjobster_decimal_sum_separator' );
					if ( empty( $wpjobster_decimal_sum_separator ) ) $wpjobster_decimal_sum_separator = '.';
					$wpjobster_thousands_sum_separator = get_option( 'wpjobster_thousands_sum_separator' );
					if ( empty( $wpjobster_thousands_sum_separator ) ) $wpjobster_thousands_sum_separator = ',';
					$shipping = get_post_meta( get_the_ID(), 'shipping', true );
					if ( !isset( $shipping ) ) $shipping = 0;

					if ( $wpjobster_enable_site_tax == 'yes' ) {

						$cur_uid = get_current_user_id();
						$country_code = get_user_meta( $cur_uid, "country_code", true );
						$wpjobster_tax_percent = wpjobster_get_tax( $country_code );
						$wpjobster_enable_processingfee_tax = get_option( 'wpjobster_enable_processingfee_tax' );
						$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
						if ( $wpjobster_enable_processingfee_tax == 'yes' && $buyer_processing_fees_enabled != 'disabled' ) {
							$wpjobster_enable_processingfee_tax = 'yes';
						} else {
							$wpjobster_enable_processingfee_tax = 'no';
						}

					} else {
						$wpjobster_tax_percent = 0;
						$wpjobster_enable_processingfee_tax = 'no';
					}

					$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
					if ( $buyer_processing_fees_enabled!='disabled' ) {
						$buyer_processing_fees = wpjobster_get_site_processing_fee( $prc, $extra_price, $shipping );
						update_user_meta( $uid, 'wpjobster_buyer_chargable_fees', $buyer_processing_fees );
					} else {
						$buyer_processing_fees = 0;
					}


				$wpjobster_subscription_enabled = get_option( 'wpjobster_subscription_enabled' );
				$wpjobster_enable_multiples = get_option( 'wpjobster_enable_multiples' );
				$wpjobster_subscription_job_multiples_enabled = get_option( 'wpjobster_subscription_job_multiples_enabled' );
				$wpjobster_subscription_extra_multiples_enabled = get_option( 'wpjobster_subscription_extra_multiples_enabled' );
				if ( $wpjobster_enable_multiples == 'yes' ) {

					if ( $wpjobster_subscription_enabled == 'yes' && $wpjobster_subscription_job_multiples_enabled == 'yes' ) {
						$wpjobster_subscription_level_j = $wpjobster_subscription_level;
						$job_amount_max = get_option( 'wpjobster_subscription_job_multiples_' . $wpjobster_subscription_level_j );
					} else {
						$wpjobster_subscription_level_j = 'level' . $user_level;
						$job_amount_max = get_option( 'wpjobster_get_' . $wpjobster_subscription_level_j . '_jobmultiples' );
					}

					if ($wpjobster_subscription_enabled == 'yes' && $wpjobster_subscription_extra_multiples_enabled == 'yes' ) {
						$wpjobster_subscription_level_e = $wpjobster_subscription_level;
						$extra_amount_max = get_option( 'wpjobster_subscription_extra_multiples_' . $wpjobster_subscription_level_e );
					} else {
						$wpjobster_subscription_level_e = 'level' . $user_level;
						$extra_amount_max = get_option( 'wpjobster_get_' . $wpjobster_subscription_level_e . '_extramultiples' );
					}

				} else {
					$job_amount_max = 1;
					$extra_amount_max = 1;
				}

				//extra fast
				$wpjobster_enable_extra_fast_delivery = get_option('wpjobster_enable_extra_fast_delivery');
				$extra_fast_enabled = '';
				$fast_delivery_amount_max = 1;
				if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
					$wpjobster_enable_extra_fast_delivery = 'yes';
				}
				if( $wpjobster_enable_extra_fast_delivery !='no' ) {
					$extra_fast_delivery = 'yes';
					$fast_delivery_amount_max = get_option('wpjobster_get_level'.$user_level.'_fast_delivery_multiples');
					if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
						$extra_fast_delivery = 'yes'; // override only if subscription extra available
						$fast_delivery_amount_max = get_option('wpjobster_subscription_fast_del_multiples_'.$wpjobster_subscription_level);
					}
					if( $extra_fast_delivery == 'yes' ) {
						$extra_fast_enabled = get_post_meta($pid, 'extra_fast_enabled', true);
					}
				}
				//extra revision
				$wpjobster_enable_extra_additional_revision = get_option('wpjobster_enable_extra_additional_revision');
				$extra_revision_enabled = '';
				$add_rev_amount_max = 1;
				if( $wpjobster_subscription_additional_revision == 'yes' ) {
					$wpjobster_enable_extra_additional_revision = 'yes';
				}
				if( $wpjobster_enable_extra_additional_revision !='no' ) {
					$extra_additional_revision = 'yes';
					$add_rev_amount_max = get_option('wpjobster_get_level'.$user_level.'_add_rev_multiples');
					if( $wpjobster_subscription_additional_revision == 'yes' ) {
						$extra_additional_revision = 'yes'; // override only if subscription extra available
						$add_rev_amount_max = get_option('wpjobster_subscription_add_rev_multiples_'.$wpjobster_subscription_level);
					}
					if( $extra_additional_revision == 'yes' ) {
						$extra_revision_enabled = get_post_meta($pid, 'extra_revision_enabled', true);
					}
				}

				wpj_get_job_packages_sidebar();
				?>

				<div class="sidebar-buy-job">
					<a href="" class="ui primary huge fluid icon right labeled button login-link uppercase nomargin <?php if ( !$extra_job_add && !$shipping ) echo ' no-arrow'; ?>" <?php echo is_user_logged_in() ? 'data-submit="[name=myFormPurchase]"' : ''; ?>>

						<?php _e( "Buy for","wpjobster" ); ?>

						<strong class="total"

							data-price                 = "<?php echo wpjobster_formats_special_exchange( $prc ); ?>"
							data-shipping              = "<?php echo wpjobster_formats_special_exchange( $shipping ); ?>"
							data-cur                   = "<?php echo get_cur(); ?>"

							data-symbol                = "<?php echo wpjobster_get_currency_symbol( get_cur() ); ?>"
							data-position              = "<?php echo $wpjobster_currency_position; ?>"
							data-space                 = "<?php echo $wpjobster_currency_symbol_space; ?>"
							data-decimal               = "<?php echo $wpjobster_decimal_sum_separator; ?>"
							data-thousands             = "<?php echo $wpjobster_thousands_sum_separator; ?>"
							data-decimaldisplay        = "<?php echo get_option( 'wpjobster_decimals' ); ?>"

							data-processingfeesenable  = "<?php echo get_option( 'wpjobster_enable_buyer_processing_fees' ); ?>"
							data-processingfeesfixed   = "<?php echo wpjobster_formats_special_exchange( get_option( 'wpjobster_buyer_processing_fees' ) ); ?>"
							data-processingfeespercent = "<?php echo get_option( 'wpjobster_buyer_processing_fees_percent' ); ?>"

							data-processingfeetax      = "<?php echo $wpjobster_enable_processingfee_tax; ?>"
							data-tax                   = "<?php echo $wpjobster_tax_percent; ?>"
							data-zerowithfree          = "<?php echo get_option( 'wpjobster_replace_zero_with_free' ); ?>"
							data-freestr               = "<?php echo __( "Free", "wpjobster" ); ?>"

						>
							<?php
								if( $prc > 0 ) {
									echo wpjobster_get_show_price($prc);
								} else {
									if ( wpj_bool_option( 'wpjobster_replace_zero_with_free' ) ) {
										_e( 'Free','wpjobster' );
									} else {
										echo wpjobster_get_show_price( $prc );
									}
								}
							?>
						</strong>
						<i class="caret down icon"></i>
					</a>
					<div class="sidebar-buy-job-form">
						<form class="ui form" method="post" name="myFormPurchase" action="<?php echo get_bloginfo( 'url' ) . '/?jb_action=purchase_this&jobid=' . get_the_ID(); ?>">
							<input type="hidden" name="purchaseformvalidation" value="ok" />
							<input type="hidden" name="main_value_inp" class="main_value_inp" value="1">
							<input type="hidden" name="pck_price_val" class="pck_price_val" />
							<input type="hidden" name="pck_deliv_val" class="pck_deliv_val" />

							<input type="hidden" value="<?php echo wpjobster_formats_special_exchange( $prc ); ?>" id="my_total_total" />
							<?php

							//extra fast
							$wpjobster_enable_extra_fast_delivery = get_option('wpjobster_enable_extra_fast_delivery');
							$extra_fast_enabled = '';

							if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
								$wpjobster_enable_extra_fast_delivery = 'yes';
							}

							if( $wpjobster_enable_extra_fast_delivery !='no' ) {

								$extra_fast_delivery = get_option('wpjobster_extra_fast_devliery_level'.$user_level);

								if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
									$extra_fast_delivery = $wpjobster_subscription_ex_fast_delivery; // override only if subscription extra available
								}

								if( $extra_fast_delivery == 'yes' ) {
									$extra_fast_enabled = get_post_meta($pid, 'extra_fast_enabled', true);
								}
							}

							//extra revision
							$wpjobster_enable_extra_additional_revision = get_option('wpjobster_enable_extra_additional_revision');
							$extra_revision_enabled = '';

							if( $wpjobster_subscription_additional_revision == 'yes' ) {
								$wpjobster_enable_extra_additional_revision = 'yes';
							}

							if( $wpjobster_enable_extra_additional_revision !='no' ) {

								$extra_additional_revision = get_option('wpjobster_extra_additional_revision_level'.$user_level);

								if( $wpjobster_subscription_additional_revision == 'yes' ) {
									$extra_additional_revision = $wpjobster_subscription_additional_revision; // override only if subscription extra available
								}

								if( $extra_additional_revision == 'yes' ) {
									$extra_revision_enabled = get_post_meta($pid, 'extra_revision_enabled', true);
								}
							}

							if ( $extra_fast_enabled || $extra_revision_enabled || $extra_job_add || $shipping || $buyer_processing_fees || $wpjobster_tax_percent || $wpjobster_enable_multiples == 'yes' ) { ?>
							<ul class="ui segment right_button_buy">
								<?php
								$quantity = 'hide';
								if( $wpjobster_subscription_enabled=='yes' ){
									if( $wpjobster_subscription_job_multiples_enabled=='yes' ){
										$quantity = 'show';
									}else{
										$quantity = 'hide';
									}
								}else{
									if( $wpjobster_enable_multiples=='yes' ){
										$quantity = 'show';
									}else{
										$quantity = 'hide';
									}
								}
								if( $quantity == 'show' ){
								?>
									<li class="main_li_nopad">
										<label class="main_amount_box">
											<span><?php _e( 'Job Quantity', 'wpjobster' ); ?></span>

											<span class="extra-price-inside"><?php echo wpjobster_get_show_price($prc); ?></span>

											<?php if($wpjobster_enable_multiples=='yes'){ ?>
												<span class="amount_section">
													<a href="#" class="amount_rmv">-</a>
													<input type="text" name="main_value_inp" class="main_value_inp current_amount main_amount" data-max="<?php echo $job_amount_max; ?>" value="1">
													<a href="#" class="amount_add">+</a>
												</span>
											<?php } ?>
										</label>
									</li>
								<?php }
								if($extra_fast_enabled){
									$extra_fast_price = get_post_meta($pid, 'extra_fast_price', true);
									$max_deliv = get_post_meta($pid, 'extra_fast_days', true);
									if( $extra_fast_price && $max_deliv ){
										?>
										<li class="cf extra_nb_fast">
											<label>
												<div class="ui checkbox">
													<input class="uzextracheck chk_extrafast" type="checkbox" name="extrafast" id="extrafast" value="1" data-price="<?php echo wpjobster_formats_special_exchange($extra_fast_price); ?>"/>
													<label></label>
												</div>

												<div class="wpj-buy-extra-details">
													<span><?php echo __( 'Extra fast delivery', 'wpjobster' ); ?></span>

													<span class="extra-price-inside"><?php echo wpjobster_get_show_price($extra_fast_price); ?></span>
													<br><span class="description"><?php echo sprintf( _n( '(%d day)', '(%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>

													<?php if($fast_delivery_amount_max > 1): ?>
														<span class="amount_section" data-amountnb="fast">
															<a href="#" class="amount_rmv">-</a>
															<input type="text" class="hid_extrafast current_amount main_amount" name="hid_extrafast" data-max="<?php echo $fast_delivery_amount_max; ?>" value="1" />
															<a href="#" class="amount_add">+</a>
														</span>
													<?php endif; ?>
												</div>
											</label>
										</li>
									<?php }
								}

								if($extra_revision_enabled){
									$extra_revision_multiples_enabled = get_post_meta($pid, 'extra_revision_multiples_enabled', true);
									$extra_revision_price = get_post_meta($pid, 'extra_revision_price', true);
									$max_deliv = get_post_meta($pid, 'extra_revision_days', true);
									if( $extra_revision_price && $max_deliv ){
										?>
										<li class="cf extra_nb_revision">
											<label>
												<div class="ui checkbox">
													<input class="uzextracheck chk_extrarevision" type="checkbox" name="extrarevision" id="extrarevision" value="1" data-price="<?php echo wpjobster_formats_special_exchange($extra_revision_price); ?>"/>
													<label></label>
												</div>

												<div class="wpj-buy-extra-details">
													<span><?php echo __( 'Extra revision', 'wpjobster' ); ?></span>

													<span class="extra-price-inside"><?php echo wpjobster_get_show_price($extra_revision_price); ?></span>
													<br><span class="description"><?php echo sprintf( _n( '(+%d day)', '(+%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>

													<?php if ( $add_rev_amount_max > 1 && get_post_meta( $pid, 'extra_revision_multiples_enabled', true ) ): ?>
														<span class="amount_section" style="display: block;" data-amountnb="revision">
															<a href="#" class="amount_rmv">-</a>
															<input type="text" class="hid_extrarevision current_amount"
															name="hid_extrarevision" data-max="<?php echo $add_rev_amount_max; ?>" value="1" />
															<a href="#" class="amount_add">+</a>
														</span>
													<?php endif; ?>
												</div>
											</label>
										</li>
									<?php }
								}

								$i=0;
								if ( $extra_job_add ) {
									foreach ( $extra_job_add as $extra_job_add_item ) { $i++;
										$enabled = get_post_meta($pid, 'extra'.$i.'_extra_enabled', false);
										if(isset($enabled[0]))
											$enabled = $enabled[0];
										else{
											update_post_meta($pid, 'extra'.$i.'_extra_enabled', true);
											$enabled = 1;
										}
										if($enabled){ ?>
											<li class="cf extrali extra_nb_<?php echo $extra_job_add_item['extra_nr']; ?>">
												<label>
													<div class="ui checkbox">
														<input class="uzextracheck chk_extra<?php echo $extra_job_add_item['extra_nr']; ?>" type="checkbox"
															name="extra<?php echo $extra_job_add_item['extra_nr']; ?>"
															id="extra<?php echo $extra_job_add_item['extra_nr']; ?>" value="1"
															data-price="<?php echo wpjobster_formats_special_exchange($extra_job_add_item['price']); ?>"/>
														<label></label>
													</div>

													<div class="wpj-buy-extra-details">
														<span><?php echo $extra_job_add_item['content']; ?></span>

														<span class="extra-price-inside"><?php echo wpjobster_get_show_price($extra_job_add_item['price']); ?></span>
														<br><span class="description"><?php $max_deliv = get_post_meta($pid, 'max_days_ex_'.$i, true); echo sprintf( _n( '(+%d day)', '(+%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>

														<?php if($wpjobster_enable_multiples=='yes' && $extra_job_add_item['enabled']): ?>
															<span class="amount_section" style="display: block;" data-amountnb="<?php echo $extra_job_add_item['extra_nr']; ?>">
																<a href="#" class="amount_rmv">-</a>
																<input type="text" class="current_amount hid_extra<?php echo $extra_job_add_item['extra_nr']; ?>" name="hid_extra<?php echo $extra_job_add_item['extra_nr']; ?>" data-max="<?php echo $extra_amount_max; ?>" value="1" />
																<a href="#" class="amount_add">+</a>
															</span>
														<?php endif; ?>
													</div>
												</label>
											</li>
										<?php }
									}
								}

								$shipping = get_post_meta( get_the_ID(), 'shipping', true );
								if( $shipping ){
									if ( get_option( 'wpjobster_enable_shipping' ) != 'no' ) { ?>
										<li class="shipping-cost cf">
											<?php _e( "Shipping", 'wpjobster' ); ?>
											<span class="extra-price-inside"><?php echo wpjobster_get_show_price( $shipping ) ?></span>
										</li>
									<?php }
								}

								if( $buyer_processing_fees ){
									if ( get_option('wpjobster_enable_buyer_processing_fees') != 'disabled' ) { ?>
										<li class="shipping-cost cf">
										<?php
											if ( get_option( 'wpjobster_enable_buyer_processing_fees' ) == 'percent' ) {
												$percent_fee = get_option( 'wpjobster_buyer_processing_fees_percent' );
											} else {
												$percent_fee = "";
											}

											if ( get_option( 'wpjobster_enable_buyer_processing_fees' ) == 'percent' ) {
												echo sprintf( __( 'Processing Fees (%s&#37;)', 'wpjobster' ), $percent_fee );
											} else {
												echo __( 'Processing Fees', 'wpjobster' );
											}
										?>
											<span class="processingfee-amount extra-price-inside"><?php echo wpjobster_get_show_price($buyer_processing_fees); ?></span>
										</li>

									<?php }
								}

								$tax_amount = wpjobster_get_site_tax( $prc, $extra_price, $shipping, $buyer_processing_fees );
								if( $tax_amount ){
									if ( get_option('wpjobster_enable_site_tax') != 'no' ) { ?>
										<li class="shipping-cost cf">

											<?php  echo sprintf( __( 'Tax (%s&#37;)', 'wpjobster' ), $wpjobster_tax_percent ); ?>
											<span class="tax-amount extra-price-inside"><?php echo wpjobster_get_show_price( $tax_amount ); ?></span>

										</li>
									<?php }
								}

								do_action( 'list_after_tax_price', $prc, 'buy_for_popup' ); ?>

							</ul>
							<?php } ?>
						</form>
					</div>
					
				</div>

			<?php } else { ?>

					<span class="ui huge fluid uppercase disabled button"><?php _e( 'Job deactivated', 'wpjobster' ); ?></span>

			<?php } ?>



		<?php } elseif ( $author_vacation ) { ?>
				<span class="ui huge fluid uppercase disabled button"><?php echo $author_vacation_reason; ?></span>
		<?php } else { ?>

				<span class="ui huge fluid uppercase disabled button"><?php _e( 'This is your own job', 'wpjobster' ); ?>
				</strong>
				</span>

		<?php }
	}
}

if ( ! function_exists( 'get_single_job_order_additional' ) ) {
	function get_single_job_order_additional() {

		global $wp_query;
		global $post;
		global $current_user;
		$pid = get_the_ID();
		$current_user = wp_get_current_user();
		$uid = $post->post_author;
		$wpjobster_enable_multiples = get_option('wpjobster_enable_multiples');

		$author_vacation = get_user_vacation( $uid );
		if ( $author_vacation ) {
			$author_vacation_reason = $author_vacation['reason'];
		}

		$prc = get_post_meta( get_the_ID(), "price", true );
		$prc = apply_filters( 'wpjobster_single_job_price', $prc, get_the_ID() );

		$user_level = wpjobster_get_user_level( $uid );
		$wpjobster_enable_extra = get_option( 'wpjobster_enable_extra' );
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info( $uid );
		extract( $wpjobster_subscription_info );
		if ( $wpjobster_subscription_noof_extras ) {
			$wpjobster_enable_extra = 'yes';
		}

		$extra_job_add = array();
		$h = 0;
		$sts=0;

		if ( $wpjobster_enable_extra != 'no' || $wpjobster_subscription_noof_extras ) {
			$sts = get_option( 'wpjobster_get_level'.$user_level.'_extras' );
			if ( $wpjobster_subscription_noof_extras ) {
				$sts = $wpjobster_subscription_noof_extras;
			}
			if ( empty( $sts ) ) $sts = 0;

		}

		for ( $k = 1; $k <= $sts; $k++ ) {
			$extra_price    = get_post_meta( get_the_ID(), 'extra'.$k.'_price',      true );
			$extra_content  = get_post_meta( get_the_ID(), 'extra'.$k.'_content',    true );
			$extra_enabled  = get_post_meta( get_the_ID(), 'extra'.$k.'_enabled',    true );


			if ( !empty( $extra_price ) && !empty( $extra_content ) ) {

				$extra_job_add[$h]['content']   = $extra_content;
				$extra_job_add[$h]['price']     = $extra_price;
				$extra_job_add[$h]['extra_nr']  = $k;
				$extra_job_add[$h]['enabled']   = $extra_enabled;
				$h++;

			}
		}

		if ( ( $post->post_author != $current_user->ID ) && ! $author_vacation ) {

			$wpjobster_enable_site_tax   = get_option( 'wpjobster_enable_site_tax' );
			if ( get_post_meta($pid, "active", true ) == 1 ) {

				$wpjobster_currency_position = get_option( 'wpjobster_currency_position' );
				$wpjobster_currency_symbol_space = get_option( 'wpjobster_currency_symbol_space' );
				$wpjobster_decimal_sum_separator = get_option( 'wpjobster_decimal_sum_separator' );
				if ( empty( $wpjobster_decimal_sum_separator ) ) $wpjobster_decimal_sum_separator = '.';
				$wpjobster_thousands_sum_separator = get_option( 'wpjobster_thousands_sum_separator' );
				if ( empty( $wpjobster_thousands_sum_separator ) ) $wpjobster_thousands_sum_separator = ',';
				$shipping = get_post_meta( get_the_ID(), 'shipping', true );
				if ( !isset( $shipping ) ) $shipping = 0;

				if ( $wpjobster_enable_site_tax == 'yes' ) {

					$cur_uid = get_current_user_id();
					$country_code = get_user_meta( $cur_uid,"country_code",true );
					$wpjobster_tax_percent = wpjobster_get_tax( $country_code );
					$wpjobster_enable_processingfee_tax = get_option( 'wpjobster_enable_processingfee_tax' );
					$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
					if ( $wpjobster_enable_processingfee_tax == 'yes' && $buyer_processing_fees_enabled != 'disabled' ) {
						$wpjobster_enable_processingfee_tax = 'yes';
					} else {
						$wpjobster_enable_processingfee_tax = 'no';
					}

				} else {
					$wpjobster_tax_percent = 0;
					$wpjobster_enable_processingfee_tax = 'no';
				}

				$buyer_processing_fees_enabled = get_option( 'wpjobster_enable_buyer_processing_fees' );
				if ( $buyer_processing_fees_enabled != 'disabled' ) {
					$buyer_processing_fees = wpjobster_get_site_processing_fee( $prc, $extra_price, $shipping );
					update_user_meta( $uid, 'wpjobster_buyer_chargable_fees', $buyer_processing_fees );
				} else {
					$buyer_processing_fees = 0;
				}

				$tax_amount = wpjobster_get_site_tax( $prc, $extra_price, $shipping, $buyer_processing_fees );

				$wpjobster_subscription_enabled = get_option( 'wpjobster_subscription_enabled' );
				$wpjobster_enable_multiples = get_option( 'wpjobster_enable_multiples' );
				$wpjobster_subscription_job_multiples_enabled = get_option( 'wpjobster_subscription_job_multiples_enabled' );
				$wpjobster_subscription_extra_multiples_enabled = get_option( 'wpjobster_subscription_extra_multiples_enabled' );
				if ( $wpjobster_enable_multiples == 'yes' ) {
					$wpjobster_subscription_level_j = $wpjobster_subscription_level;
					$job_amount_max = get_option( 'wpjobster_subscription_job_multiples_'.$wpjobster_subscription_level_j );
				} else {
					$wpjobster_subscription_level_j = 'level'. $user_level;
					$job_amount_max = get_option( 'wpjobster_get_'.$wpjobster_subscription_level_j.'_jobmultiples' );
				}

				if ( $wpjobster_subscription_enabled == 'yes' && $wpjobster_subscription_extra_multiples_enabled == 'yes' ){
					$wpjobster_subscription_level_e = $wpjobster_subscription_level;
					$extra_amount_max = get_option( 'wpjobster_subscription_extra_multiples_' . $wpjobster_subscription_level_e );
				} else {
					$wpjobster_subscription_level_e = 'level'. $user_level;
					$extra_amount_max = get_option( 'wpjobster_get_' . $wpjobster_subscription_level_e . '_extramultiples' );
				}

			} else {
				$job_amount_max = 1;
				$extra_amount_max = 1;
			}

			//extra fast
			$wpjobster_enable_extra_fast_delivery = get_option('wpjobster_enable_extra_fast_delivery');
			$extra_fast_enabled = '';
			$fast_delivery_amount_max = 1;
			if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
				$wpjobster_enable_extra_fast_delivery = 'yes';
			}
			if( $wpjobster_enable_extra_fast_delivery !='no' ) {
				$extra_fast_delivery = 'yes';
				$fast_delivery_amount_max = get_option('wpjobster_get_level'.$user_level.'_fast_delivery_multiples');
				if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
					$extra_fast_delivery = 'yes'; // override only if subscription extra available
					$fast_delivery_amount_max = get_option('wpjobster_subscription_fast_del_multiples_'.$wpjobster_subscription_level);
				}
				if( $extra_fast_delivery == 'yes' ) {
					$extra_fast_enabled = get_post_meta($pid, 'extra_fast_enabled', true);
				}
			}
			//extra revision
			$wpjobster_enable_extra_additional_revision = get_option('wpjobster_enable_extra_additional_revision');
			$extra_revision_enabled = '';
			$add_rev_amount_max = 1;
			if( $wpjobster_subscription_additional_revision == 'yes' ) {
				$wpjobster_enable_extra_additional_revision = 'yes';
			}
			if( $wpjobster_enable_extra_additional_revision !='no' ) {
				$extra_additional_revision = 'yes';
				$add_rev_amount_max = get_option('wpjobster_get_level'.$user_level.'_add_rev_multiples');
				if( $wpjobster_subscription_additional_revision == 'yes' ) {
					$extra_additional_revision = 'yes'; // override only if subscription extra available
					$add_rev_amount_max = get_option('wpjobster_subscription_add_rev_multiples_'.$wpjobster_subscription_level);
				}
				if( $extra_additional_revision == 'yes' ) {
					$extra_revision_enabled = get_post_meta($pid, 'extra_revision_enabled', true);
				}
			}

		}

		if ( ( get_post_meta( $pid, "active", true ) == 1 ) && ! $author_vacation ) {

			if ( !isset( $shipping ) ) $shipping=0;

		?>

		<div class="order-extras cf">
			<?php

			//extra fast
			$wpjobster_enable_extra_fast_delivery = get_option('wpjobster_enable_extra_fast_delivery');
			$extra_fast_enabled = '';
				if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
					$wpjobster_enable_extra_fast_delivery = 'yes';
				}
				if( $wpjobster_enable_extra_fast_delivery !='no' ) {
					$extra_fast_delivery = get_option('wpjobster_extra_fast_devliery_level'.$user_level);
					if( $wpjobster_subscription_ex_fast_delivery == 'yes' ) {
						$extra_fast_delivery = $wpjobster_subscription_ex_fast_delivery; // override only if subscription extra available
					}
					if( $extra_fast_delivery == 'yes' ) {
						$extra_fast_enabled = get_post_meta($pid, 'extra_fast_enabled', true);
					}
				}
				//extra revision
				$wpjobster_enable_extra_additional_revision = get_option('wpjobster_enable_extra_additional_revision');
				$extra_revision_enabled = '';
				if( $wpjobster_subscription_additional_revision == 'yes' ) {
					$wpjobster_enable_extra_additional_revision = 'yes';
				}
				if( $wpjobster_enable_extra_additional_revision !='no' ) {
					$extra_additional_revision = get_option('wpjobster_extra_additional_revision_level'.$user_level);
					if( $wpjobster_subscription_additional_revision == 'yes' ) {
						$extra_additional_revision = $wpjobster_subscription_additional_revision; // override only if subscription extra available
					}
					if( $extra_additional_revision == 'yes' ) {
						$extra_revision_enabled = get_post_meta($pid, 'extra_revision_enabled', true);
					}
				}

			if ($extra_fast_enabled || $extra_revision_enabled || isset($extra_job_add) || isset($shipping) || isset($buyer_processing_fees) || (isset($wpjobster_enable_site_tax) && $wpjobster_enable_site_tax=='yes')) { ?>

			<div class="sigle-job-additional-title">
				<h2><?php _e( "Order Additional",'wpjobster' ); ?></h2>
			</div>

			<div class="ui divider"></div>

			<?php }

			if ( $post->post_author != $current_user->ID ) { ?>

				<form method="post" name="myFormPurchase2" action="<?php echo get_bloginfo('url') . '/?jb_action=purchase_this&jobid=' . get_the_ID(); ?>" class="cf">
					<input type="hidden" name="purchaseformvalidation" value="ok" />
					<input type="hidden" name="pck_price_val" class="pck_price_val" />
					<input type="hidden" name="pck_deliv_val" class="pck_deliv_val" />

					<input type="hidden" value="<?php echo wpjobster_formats_special_exchange( $prc ); ?>" id="my_total_total" />
					<div class="order-extras-inside">
						<ul>
							<?php
							$quantity = 'hide';
							if( $wpjobster_subscription_enabled=='yes' ){
								if( $wpjobster_subscription_job_multiples_enabled=='yes' ){
									$quantity = 'show';
								}else{
									$quantity = 'hide';
								}
							}else{
								if( $wpjobster_enable_multiples=='yes' ){
									$quantity = 'show';
								}else{
									$quantity = 'hide';
								}
							}
							if( $quantity == 'show' ){ ?>
								<li class="cf">
									<label class="main_amount_box">
										<span><?php _e( 'Job Quantity', 'wpjobster' ); ?></span>

										<span class="extra-price-inside extra-price-resp"><?php echo wpjobster_get_show_price( $prc ); ?></span>
										<br />

										<?php if ( isset( $wpjobster_enable_multiples ) && $wpjobster_enable_multiples == 'yes'){ ?>
											<span class="amount_section">
												<a href="#" class="amount_rmv">-</a>
												<input type="text" name="main_value_inp" class="main_value_inp current_amount main_amount" data-max="<?php echo $job_amount_max; ?>" value="1">
												<a href="#" class="amount_add">+</a>
											</span>
										<?php } ?>
									</label>
								</li>
							<?php } ?>

							<?php if($extra_fast_enabled){
								$extra_fast_price = get_post_meta($pid, 'extra_fast_price', true);
								?>
								<li class="cf extra_nb_fast">
									<label>
										<div class="ui checkbox">
											<input class="uzextracheck chk_extrafast" type="checkbox" name="extrafast" id="extrafast" value="1" data-price="<?php echo wpjobster_formats_special_exchange($extra_fast_price); ?>"/>
											<label></label>
										</div>

										<div class="wpj-buy-extra-details">
											<span><?php echo __( 'Extra fast delivery', 'wpjobster' ); ?></span>

											<span class="extra-price-inside extra-price-resp"><?php echo wpjobster_get_show_price($extra_fast_price); ?></span>
											<br>
											<span class="description"><?php $max_deliv = get_post_meta($pid, 'extra_fast_days', true); echo sprintf( _n( ' (%d day)', '(%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>

											<?php if($fast_delivery_amount_max > 1): ?>
												<span class="amount_section" data-amountnb="fast">
													<a href="#" class="amount_rmv">-</a>
													<input type="text" data-max="<?php echo $fast_delivery_amount_max; ?>" class="current_amount hid_extrafast" name="hid_extrafast" value="1"/>
													<a href="#" class="amount_add">+</a>
												</span>
											<?php endif; ?>
										</div>
									</label>
								</li>
							<?php } ?>

							<?php if($extra_revision_enabled){
								$extra_revision_multiples_enabled = get_post_meta($pid, 'extra_revision_multiples_enabled', true);
								$extra_revision_price = get_post_meta($pid, 'extra_revision_price', true);
								?>
								<li class="cf extra_nb_revision">
									<label>
										<div class="ui checkbox">
											<input class="uzextracheck chk_extrarevision" type="checkbox" name="extrarevision" id="extrarevision" value="1" data-price="<?php echo wpjobster_formats_special_exchange($extra_revision_price); ?>"/>
											<label></label>
										</div>

										<div class="wpj-buy-extra-details">
											<span><?php echo __( 'Extra revision', 'wpjobster' ); ?></span>

											<span class="extra-price-inside extra-price-resp"><?php echo wpjobster_get_show_price($extra_revision_price); ?></span>
											<br>
											<span class="description"><?php $max_deliv = get_post_meta($pid, 'extra_revision_days', true); echo sprintf( _n( '(+%d day)', '(+%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>
											<?php if ( $add_rev_amount_max > 1 && get_post_meta( $pid, 'extra_revision_multiples_enabled', true ) ): ?>
												<span class="amount_section" style="display: block;" data-amountnb="revision">
													<a href="#" class="amount_rmv">-</a>
													<input type="text" class="current_amount hid_extrarevision" data-max="<?php echo $add_rev_amount_max; ?>" name="hid_extrarevision" value="1" />
													<a href="#" class="amount_add">+</a>
												</span>
											<?php endif; ?>
										</div>
									</label>
								</li>
							<?php } ?>

							<?php
							$i = 0;
							if ( $extra_job_add ) {
								foreach( $extra_job_add as $extra_job_add_item ) { $i++;
									// update values for older existing jobs
									if(!get_post_meta($pid, "max_days_ex_".$i, true))
										update_post_meta($pid, 'max_days_ex_'.$i, "instant");
									$enabled = get_post_meta($pid, 'extra'.$i.'_extra_enabled', false);
									if(isset($enabled[0]))
										$enabled = $enabled[0];
									else{
										update_post_meta($pid, 'extra'.$i.'_extra_enabled', true);
										$enabled = 1;
									}
									if($enabled){ ?>
										<li class="cf extralibot extra_nb_<?php echo $extra_job_add_item['extra_nr']; ?>">
											<label>
												<div class="ui checkbox">
													<input class="uzextracheck chk_extra<?php echo $extra_job_add_item['extra_nr']; ?>" type="checkbox" name="extra<?php echo $extra_job_add_item['extra_nr']; ?>" id="extra<?php echo $extra_job_add_item['extra_nr']; ?>" value="1" data-price="<?php echo wpjobster_formats_special_exchange( $extra_job_add_item['price'] ); ?>" />
													<label></label>
												</div>

												<div class="wpj-buy-extra-details">
													<span><?php echo $extra_job_add_item['content']; ?></span>

													<span class="extra-price-inside extra-price-resp"><?php echo wpjobster_get_show_price( $extra_job_add_item['price'] ); ?></span>
													<br>
													<span class="description"><?php $max_deliv = get_post_meta($pid, 'max_days_ex_'.$i, true); echo sprintf( _n( '(+%d day)', '(+%d days)', $max_deliv, 'wpjobster' ), $max_deliv ); ?></span>
													<?php if ( $wpjobster_enable_multiples == 'yes' && $extra_job_add_item['enabled'] ) { ?>
														<span class="amount_section" style="display: block;" data-amountnb="<?php echo $extra_job_add_item['extra_nr']; ?>">
															<a href="#" class="amount_rmv">-</a>
															<input type="text" data-amountnb="<?php echo $extra_job_add_item['extra_nr']; ?>" class="current_amount hid_extra<?php echo $extra_job_add_item['extra_nr']; ?>" name="hid_extra<?php echo $extra_job_add_item['extra_nr']; ?>" value="1" />
															<a href="#" class="amount_add">+</a>
														</span>
													<?php } ?>
												</div>
											</label>
										</li>
									<?php }
								}
							} ?>
						</ul>

					<?php if( $shipping ){
						if( get_option( 'wpjobster_enable_shipping' ) != 'no' ) { ?>
							<div class="shipping">
								<?php _e( "Shipping:", 'wpjobster' ); ?>
								<span class="extra-price-inside"><?php echo wpjobster_get_show_price( $shipping ); ?></span>
							</div>
						<?php }
					}

					if( $buyer_processing_fees ){
						if( get_option( 'wpjobster_enable_buyer_processing_fees' ) != 'disabled' ) {
							if ( isset( $buyer_processing_fees ) ) { ?>
								<div class="shipping">
									<?php wpjobster_display_processing_fees_label(); ?>
									<span class="processingfee-amount extra-price-inside"><?php echo wpjobster_get_show_price( $buyer_processing_fees ); ?></span>
								</div>
							<?php }
						}
					}

					if( $tax_amount ){
						if ( isset( $wpjobster_enable_site_tax ) && $wpjobster_enable_site_tax == 'yes' ) {
							if( !isset( $wpjobster_tax_percent ) ) {
								$cur_uid = get_current_user_id();
								$country_code = get_user_meta( $cur_uid, "country_code", true );
								$wpjobster_tax_percent = wpjobster_get_tax( $country_code );
							}
							$wpjobster_enable_processingfee_tax = get_option( 'wpjobster_enable_processingfee_tax' );
							if ( $wpjobster_enable_processingfee_tax == 'yes' && $buyer_processing_fees_enabled != 'disabled' ) {
								$wpjobster_enable_processingfee_tax = 'yes';
							}
							?>
							<div class="shipping"><?php echo sprintf( __( 'Tax (%s&#37;)', 'wpjobster' ), $wpjobster_tax_percent ); ?>: <span class="tax-amount extra-price-inside"><?php echo wpjobster_get_show_price( $tax_amount ); ?></span></div>
							<?php
						} else {
							$wpjobster_tax_percent = 0;
							$wpjobster_enable_processingfee_tax = "no";
						}
					}

					do_action( 'list_after_tax_price', $prc, 'order_additional' ); ?>

					</div>

					<div class="cf">
						<a href="" <?php echo is_user_logged_in() ? 'data-submit=""' : ''; ?> class="ui huge fluid uppercase primary button submit-button login-link">
							<?php _e( "Buy Now For", 'wpjobster' ); ?>
							<strong class="total"

								data-price                 = "<?php echo wpjobster_formats_special_exchange( $prc ); ?>"
								data-shipping              = "<?php echo wpjobster_formats_special_exchange( $shipping ); ?>"
								data-cur                   = "<?php echo get_cur(); ?>"

								data-symbol                = "<?php echo wpjobster_get_currency_symbol( get_cur() ); ?>"
								data-position              = "<?php echo $wpjobster_currency_position; ?>"
								data-space                 = "<?php echo $wpjobster_currency_symbol_space; ?>"
								data-decimal               = "<?php echo $wpjobster_decimal_sum_separator; ?>"
								data-thousands             = "<?php echo $wpjobster_thousands_sum_separator; ?>"
								data-decimaldisplay        = "<?php echo get_option( 'wpjobster_decimals' ); ?>"

								data-processingfeesenable  = "<?php echo get_option( 'wpjobster_enable_buyer_processing_fees' ); ?>"
								data-processingfeesfixed   = "<?php echo wpjobster_formats_special_exchange( get_option( 'wpjobster_buyer_processing_fees' ) ); ?>"
								data-processingfeespercent = "<?php echo get_option( 'wpjobster_buyer_processing_fees_percent' ); ?>"

								data-processingfeetax      = "<?php echo $wpjobster_enable_processingfee_tax; ?>"
								data-tax                   = "<?php echo $wpjobster_tax_percent; ?>"
								data-zerowithfree          = "<?php echo get_option( 'wpjobster_replace_zero_with_free' ); ?>"
								data-freestr               = "<?php echo __( "Free", "wpjobster" ); ?>"

							>
								<?php
								if( $prc > 0 ) {
									echo wpjobster_get_show_price( $prc );
								} else {
									if ( wpj_bool_option( 'wpjobster_replace_zero_with_free' ) ) {
										_e( 'Free', 'wpjobster' );
									} else {
										echo wpjobster_get_show_price( $prc );
									}
								}
								?>
							</strong>
						</a>
					</div>
				</form>

			<?php } else { ?>
				<span class="ui huge fluid uppercase disabled button"><?php _e( 'This is your own job', 'wpjobster' ); ?>
					<strong><?php echo wpjobster_get_show_price( $prc ); ?></strong>
				</span>
			<?php } ?>

		</div>
		<?php } elseif ( $author_vacation ) { ?>
			<span class="ui huge fluid uppercase disabled button"><?php echo $author_vacation_reason; ?></span>
		<?php } else { ?>
		<div class="order-extras cf">
			<span class="ui huge fluid uppercase disabled button"><?php _e( 'Job deactivated', 'wpjobster' ); ?></span>
		</div>

		<?php }
	}
}

if ( ! function_exists( 'wpj_get_single_job_map_display' ) ) {
	function wpj_get_single_job_map_display() {
		global $wp_query;
		$wpjobster_location = get_option( 'wpjobster_location' );
		$location_input = get_post_meta( get_the_ID(), 'location_input', true );

		if ( $wpjobster_location == 'yes'
			&& get_option( 'wpjobster_location_display_map' ) == 'yes'
			&& $location_input ) {
			if ( get_option( 'wpjobster_location_display_map_user_choice' ) != "yes"
				|| ( get_option( 'wpjobster_location_display_map_user_choice' ) == "yes" && get_post_meta( get_the_ID(), "display_map", true ) == "yes" ) ) {
				?>
				<div class="ui segment">
					<div id="job_map"></div>
				</div>
			<?php }
		}
	}
}

if ( ! function_exists( 'wpj_get_single_job_feedback' ) ) {
	function wpj_get_single_job_feedback() {
		$total_per_load = 3;
		global $wpdb, $wp_query, $post, $current_user;
		$pid = get_the_ID();
		$current_user = wp_get_current_user();
		$uid = $post->post_author;


		$query_feedback_total = "
			SELECT DISTINCT *, ratings.datemade datemade
			FROM {$wpdb->prefix}job_ratings ratings, {$wpdb->prefix}job_orders orders, {$wpdb->prefix}posts posts
			WHERE posts.ID=orders.pid
			AND ( posts.ID={$pid}
				OR ratings.pid IN (
					SELECT custom_offer
					FROM {$wpdb->prefix}job_pm pm, {$wpdb->prefix}job_ratings jr
					WHERE associate_job_id = {$pid}
					AND custom_offer = jr.pid
				)
			)
			AND ratings.awarded='1'
			AND orders.id=ratings.orderid
			AND posts.post_author={$uid}
			ORDER BY datemade DESC
		";
		$r_feedback_total = $wpdb->get_results( $query_feedback_total );
		$r_seller_total = count( $r_feedback_total );

		$query = "
			SELECT DISTINCT *, ratings.datemade datemade
			FROM {$wpdb->prefix}job_ratings ratings, {$wpdb->prefix}job_orders orders, {$wpdb->prefix}posts posts
			WHERE posts.ID=orders.pid
			AND ( posts.ID={$pid}
				OR ratings.pid IN (
					SELECT custom_offer
					FROM {$wpdb->prefix}job_pm pm, {$wpdb->prefix}job_ratings jr
					WHERE associate_job_id = {$pid}
					AND custom_offer = jr.pid
				)
			)
			AND ratings.awarded='1'
			AND orders.id=ratings.orderid
			AND posts.post_author={$uid}
			ORDER BY datemade DESC
			LIMIT {$total_per_load}
		";
		$r = $wpdb->get_results ( $query );


		if ( count( $r ) > 0 ) { ?>
		<input type="hidden" id="uid" value="<?php echo $uid ?>">
		<input type="hidden" id="pid" value="<?php echo $pid ?>">
		<input type="hidden" id="total_per_load" value="<?php echo $total_per_load ?>">
		<div class="ui segment">
			<div class="ui grid">
				<div class="sixteen wide column">
					<div class="single-job-feedback-title">
						<h2><?php _e( "Feedback","wpjobster" ); ?></h2>
					</div>
				</div>
			</div>

			<div class="ui divider"></div>

			<div style="" class="feedbacks_box_all">
				<div class="feedbacks_box visible_feedbacks_box first_feedbacks_box" id="all-feedback-box" style="display:block">
					<?php
						$cnt = 0; // counter/incrementor
						foreach( $r as $row ) {
							$cnt++;
							$post = $row->pid;
							$post = get_post( $post );
							$user2 = get_userdata( $row->uid );
							// first two are visible
							?>
							<div class="feed cf">
								<div>
									<a href="" class="left p10r job-feedback-picture"><img width="45" height="45" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar( $user2->ID, 46, 46 ); ?>" /></a>
									<div class="left job-feedback-content">
										<div class="left cb p5b w100">
											<a class="left" href="<?php echo wpj_get_user_profile_link( $user2->user_login ); ?>"><?php echo $user2->user_login; if( $row->pid != $pid ){ echo " <span class='lighter light_grey'>(custom job)</span> "; } ?></a>
											<div class="left p10l">
												<div class="single-job-rating-stars">
													<?php echo wpjobster_show_stars_our_of_number( $row->grade ); ?>
												</div>
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
							$query_seller = "
								SELECT DISTINCT *, ratings.datemade, orders.uid AS buyer_id, datemade
								FROM {$wpdb->prefix}job_ratings_by_seller ratings, {$wpdb->prefix}job_orders orders, {$wpdb->prefix}posts posts
								WHERE posts.ID=orders.pid
								AND ( posts.ID={$pid}
									OR ratings.pid IN (
										SELECT custom_offer
										FROM {$wpdb->prefix}job_pm pm, {$wpdb->prefix}job_ratings jr
										WHERE associate_job_id = {$pid}
										AND custom_offer = jr.pid
									)
								)
								AND ratings.awarded='1'
								AND orders.id=ratings.orderid
								AND posts.post_author={$uid}
								AND orders.id={$row->orderid}
								ORDER BY datemade DESC
								LIMIT 3
							";
							$r_seller = $wpdb->get_results( $query_seller );
							if( $r_seller ) {
								$row_seller = $r_seller[0];
								$user3 = get_userdata( $uid );
							?>

			<!-- Feedback by seller -->
								<div class="feed cf feedback-answer">
									<div>
										<a href="" class="left p10r job-feedback-picture"><img width="35" height="35" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar($user3->ID,46,46); ?>" /></a>
										<div class="left job-feedback-content">
											<div class="left cb p5b w100">
												<a  class="left" href="<?php echo wpj_get_user_profile_link( $user3->user_login ); ?>"><?php echo $user3->user_login; if( $row_seller->pid != $pid ){ echo " <span class='lighter light_grey'>(custom job)</span> "; } ?></a>
												<div class="left p10l">
													<div class="single-job-rating-stars">
														<?php echo wpjobster_show_stars_our_of_number($row_seller->grade); ?>
													</div>
												</div>
												<span class="grey-time p10l right"></span>
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
				</div><!-- close feedbacks_box -->

			</div><!-- close feedbacks_box_all -->

			<!-- show load more button only if exists more than 2 feedbacks -->
			<?php if( $r_seller_total > $total_per_load ) { ?>
				<a data-rel='3' href="javascript:void(0)" id="load-more-feedback" class="btn w100 show_more-notneeeded bigger grey_btn"><?php _e( "Load More Feedback", 'wpjobster' ); ?></a>
			<?php } ?>
		</div>

		<?php }
		wp_reset_query();
		wp_reset_postdata();
	}
}

if ( ! function_exists( 'wpj_get_single_job_other_jobs' ) ) {
	function wpj_get_single_job_other_jobs() {
		global $wp_query;
		global $post;
		$the_id = get_the_ID();
		$uid = $post->post_author;
		$author = get_userdata( $uid );

		$active = array(
			'key' => 'active',
			'value' => "1",
			'compare' => '='
		);

		$closed = array(
			'key' => 'closed',
			'value' => "0",
			'compare' => '='
		);

		$args = array( 'author' => $uid ,'meta_query' => array( $closed, $active ) ,'posts_per_page' => 3,
		'paged' => 1, 'post_type' => 'job', 'order' => "DESC" , 'orderby'=>"date", 'post__not_in' => array( $the_id ) );
		$the_query = new WP_Query( $args );

		if( $the_query->have_posts() ) { ?>

			<div class="ui segment">
				<h2><?php echo sprintf( __( "Other jobs by %s", 'wpjobster' ), $author->user_login  ); ?></h2>
				<div class="ui divider"></div>
				<?php show_posts( "", 0, $the_query, 3, 'wpj_get_user_post_tumb_card', 'ui three cards' ); ?>
			</div>

		<div class="ui hidden divider"></div>

		<?php }
		wp_reset_postdata();
		wp_reset_query();
	}
}

if ( ! function_exists( 'wpj_get_single_job_user_level' ) ) {
	function wpj_get_single_job_user_level() {
		$using_perm = wpjobster_using_permalinks();
		if($using_perm) $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')). "?";
		else $privurl_m = get_bloginfo('url'). "?page_id=". get_option('wpjobster_my_account_priv_mess_page_id'). "&";
		global $wp_query;
		global $post;
		$pid = get_the_ID();
		$uid = $post->post_author;
		$user = get_userdata( $post->post_author );
		$user_level = wpjobster_get_user_level( $uid );
		global $current_user;
		$current_user = wp_get_current_user();  ?>

		<div class="center">
			<div class="ui basic notpadded segment">
				<div class="user-img-container ">
				<?php if ( wpjobster_get_user_level( $user->ID) != 0 ) { ?>
					<div class="sidebar-user-level">
						<?php $user_level_no = wpjobster_get_user_level( $user->ID );
							$level_flag = 0;
							if( $user_level_no == 1 ) {
								if ( get_field( 'user_level_1_icon', "options" ) ) {
									$icon_url = get_field('user_level_1_icon', "options");
									$level_flag = 1;
								}
							} elseif ( $user_level_no == 2 ) {
								if ( get_field( 'user_level_2_icon', "options" ) ) {
									$icon_url = get_field( 'user_level_2_icon', "options" );
									$level_flag = 1;
								}
							} else {
								if ( get_field( 'user_level_3_icon', "options" ) ) {
									$icon_url = get_field( 'user_level_3_icon', "options" );
									$level_flag = 1;
								}
							}
						?>

						<div class="user-badge user-level-<?php echo $user_level_no; ?>" <?php if($level_flag){?>style="background-image: url(<?php echo $icon_url; ?>);"<?php } ?>></div>
						<div class="nh-tooltip">

							<?php if( $user_level_no == 1 ) {
								_e( "Level 1 Seller", "wpjobster" );
							} elseif ( $user_level_no == 2 ) {
								_e("Level 2 Seller", "wpjobster" );
							} else {
								_e( "Top Rated Seller", "wpjobster" );
							} ?>

						</div>
					</div>
					<?php } ?>
					<?php if ( $post->post_author == $current_user->ID ) { ?>
						<?php wpjobster_avatar_upload_html5( 130, 130 ); ?>

					<?php } else { ?>
						<img class="round-avatar" width="130" height="130" border="0" src="<?php echo wpjobster_get_avatar( $post->post_author, 130, 130 ); ?>" />

					<?php } ?>
				</div>

				<div class="user-info cf">
					<h3 class="overflow-ellipsis">
						<a class="" style="vertical-align: middle" href="<?php echo wpjobster_get_user_profile_link( $user->user_login );?>"><?php echo $user->user_login; ?>
						</a>
						<?php
							$u_id = $user->ID;

							include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );

							wpj_get_subscription_info_path();
							$wpjobster_subscription_info = get_wpjobster_subscription_info( $user->ID );

							extract( $wpjobster_subscription_info );
							if ( $wpjobster_subscription_icon_url && validate_image_file( $wpjobster_subscription_icon_url ) ) { ?>
								<img src="<?php echo $wpjobster_subscription_icon_url?>" class="subscription-user-icon">
							<?php } ?>

						<div class="user-info-subtitle">
						<?php if ( wpjobster_get_seller_rating( $post->post_author ) != 0 ) {

							echo $rtg = wpjobster_get_seller_rating( $post->post_author ) . "% " . __( 'Reputation', 'wpjobster' );
							$ratinggrade = $rtg / 20;

							echo '<div class="user-profile">';
								if ( $ratinggrade != 0 ) {
									echo wpjobster_show_big_stars_our_of_number( $ratinggrade );
								} else {
									echo __( "Not rated yet", "wpjobster" );
								}
								$r_count = wpjobster_get_avg_rating( $post->post_author );
								echo '<span class="small-rtg">' . number_format( $r_count, 1 ) ;
									echo ' (' . wpjobster_get_seller_ratings_number( $post->post_author ) . " " .  _n( "review", "reviews", wpjobster_get_seller_ratings_number( $post->post_author ), "wpjobster" ) . ')';
								echo '</span>';
							echo '</div>';
						} else {
							echo __( "New user", "wpjobster" );

						} ?>
						</div>
					</h3>
				</div>
			</div>

			<?php if ( get_user_meta( $post->post_author, 'personal_info', true ) ) { ?>
			<div class="cf user-bio">
				<?php
				$desc_content = get_user_meta( $post->post_author, 'personal_info', true );
				list( $desc_content, $validation_errors ) = filterMessagePlusErrors( $desc_content, true );

				if( get_option( 'wpjobster_wysiwyg_for_profile' ) != 'yes' ) {
					echo stripslashes( $desc_content );
				} else {
					echo $desc_content;
				} ?>
			</div>
			<?php } else { ?>
			<div class="main-margin cf"></div>
			<?php } ?>

			<?php do_action( 'wpj_profile_extra_fields_display_on_sidebar', $pid );?>
			<div class="cf">

				<div class="flag-and-country">
					<?php display_user_flag_and_country( $post->post_author ); ?>
				</div>


				<?php if( $uid != $current_user->ID ) { ?>


					<a class="ui fluid button <?php echo !is_user_logged_in() ? 'login-link' : ''; ?>" href="<?php echo $privurl_m; ?>username=<?php echo $user->user_nicename; ?>"><?php _e( "Contact","wpjobster" ); ?></a>

				<?php } ?>
			</div>

		</div>
	<?php
	}
}

if ( ! function_exists( 'wpj_get_social_icons' ) ) {
	function wpj_get_social_icons(){ ?>
		<div class="social-icons-floating cf">
			<div class="white-cnt padding-cnt cf">
				<?php echo uz_upb_bookmark_controls(); ?>
			</div>
			<div class="white-cnt padding-cnt cf">
				<div class="">
					<?php echo do_shortcode('[easy-social-share]'); ?>
				</div>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'wpj_update_last_viewed' ) ) {
	function wpj_update_last_viewed(){
		global $post, $current_user;
		setup_postdata($post);
		if (get_post_type($post->ID) == 'job') {
			update_post_meta($post->ID, '_last_viewed', current_time('mysql'));

			if (is_user_logged_in()) {
				$last_viewed = get_user_meta($current_user->ID, 'last_viewed', true);

				if (!is_array($last_viewed)) {
					$last_viewed = array();
				}

				$last_viewed = array_diff($last_viewed, array(get_the_ID()));
				array_unshift($last_viewed, get_the_ID());
				array_splice($last_viewed, 10);

				update_user_meta($current_user->ID, 'last_viewed', $last_viewed);
			}
		}
	}
}

if ( ! function_exists( 'wpj_js_audio_display' ) ) {
	function wpj_js_audio_display(){
		//audio
		$pid_audio = get_the_ID();
		$args = array(
			'post_type' => 'attachment',
			'post_parent' => $pid_audio,
			'post_mime_type' => 'audio',
			'numberposts' => -1,
			'orderby' => 'meta_value_num date',
			'order' => 'ASC'
		);
		$attachments_audio = get_posts($args);
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".uzextracheck[type=checkbox]").change();

			jQuery("#load-more-feedback").click(function(){
				var current = jQuery("#load-more-feedback").attr("data-rel");
				var pid = jQuery("#pid").val();
				var uid = jQuery("#uid").val();
				var total_per_load = jQuery("#total_per_load").val();
				var action = "show_more_feedbacks";
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					dataType: 'json',
					data: {current:current,pid:pid,uid:uid,total_per_load:total_per_load,action:action  },
					success: function (data) {
						if(data.ok=='1'){
							jQuery("#all-feedback-box").append(data.html);
						}else if(typeof data.error !='undefined'){
							alert(data.error);
						}
						if(data.current == '0'){
							jQuery("#load-more-feedback").hide('slow');
						}else{
							jQuery("#load-more-feedback").attr("data-rel",data.current);
						}
					}
				});
			});
			var top = parseFloat(jQuery('.social-icons-floating').css('top'));
			var footerHeight = jQuery('.footer-new').height();
			var lastScrollTop = 0;
			function checkOffset() {
				var windowPositionTop = jQuery(document).height() - jQuery(window).scrollTop();
				var footerPositionBottom = jQuery('.footer-new').offset().top - jQuery(window).scrollTop() - 34;
				var socialPositionTop = jQuery('.social-icons-floating').offset().top - jQuery(window).scrollTop();
				var socialPositionBottom = jQuery('.social-icons-floating').offset().top - jQuery(window).scrollTop() + jQuery('.social-icons-floating').height();
				//scroll direction
				var st = jQuery(window).scrollTop();
				if (jQuery(window).scrollTop() < top) {
					jQuery('.social-icons-floating').css({
						bottom: 'auto',
						top: top
					});
				} else {
					if (st > lastScrollTop) {
					//downscroll
						if (socialPositionBottom >= footerPositionBottom) {
							jQuery('.social-icons-floating').css({
								bottom: (jQuery(window).height() - footerPositionBottom) + 'px',
								top: 'auto'
							});
						}
					} else {
					//upscroll
						if (socialPositionTop <= top) {
							jQuery('.social-icons-floating').css({
								bottom: (jQuery(window).height() - footerPositionBottom) + 'px',
								top: 'auto'
							});
						}
					}
				}
				lastScrollTop = st;
			}
			checkOffset();
			jQuery(document).scroll(function() {
				checkOffset();
			});
			<?php
					foreach ($attachments_audio as $attachment_audio) {
						$filename = basename( get_attached_file( $attachment_audio->ID ) );
						?>
							jQuery("#jquery_jplayer_<?php echo $attachment_audio->ID; ?>").jPlayer({
								ready: function () {
									jQuery(this).jPlayer("setMedia", {
										title: "<?php echo $filename; ?>",
										mp3: "<?php echo wp_get_attachment_url( $attachment_audio->ID ); ?>",
										wav: "<?php echo wp_get_attachment_url( $attachment_audio->ID ); ?>"
									});
								},
								play: function() { // To avoid multiple jPlayers playing together.
									jQuery(this).jPlayer("pauseOthers");
								},
								swfPath: "../../js",
								supplied: "mp3, wav",
								cssSelectorAncestor: "#jp_container_<?php echo $attachment_audio->ID; ?>",
								wmode: "window",
								globalVolume: true,
								useStateClassSkin: true,
								autoBlur: false,
								smoothPlayBar: true,
								keyEnabled: true
							});
						<?php
					}
			?>
		});
		</script>
	<?php }
}

if ( ! function_exists( 'wpj_js_map_display' ) ) {
	function wpj_js_map_display(){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				<?php
				if (get_option('wpjobster_location_display_map_user_choice') != "yes"
					|| (get_option('wpjobster_location_display_map_user_choice') == "yes" && get_post_meta(get_the_ID(), "display_map", true) == "yes")) { ?>
				jQuery("#job_address").each(function(){
					var embed ="<iframe class='job_google_map' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent(jQuery(this).text()) +"&amp;output=embed'></iframe>";
					jQuery('#job_map').html(embed);

				});
				<?php } ?>
			});
		</script>
	<?php }
}

if ( ! function_exists( 'wpj_display_cover_image' ) ) {
	function wpj_display_cover_image() {
		global $wp_query;
		$cover_image_id = get_post_meta( get_the_ID(), 'cover-image', true );
		if ( $cover_image_id && !empty($cover_image_id ) ) {
			echo '<div class="ui gird">';
				echo '<div class="sixteen wide column">';
					echo '<div class="single-job-cover-top">';
						$cover_image_url = wpj_get_attachment_image_url( $cover_image_id , 'job_cover_image' );
						echo '<img src="' . $cover_image_url . '" />';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}
}
?>
