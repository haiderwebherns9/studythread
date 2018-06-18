<?php
// Featured Categories Shortcode [featured_categories_list]
if (!function_exists('featured_categories_s')) {
	function featured_categories_s() {
		$homepage_id = get_option("main_page_url");
		?>
		<div class="ui four cards">
			<?php
			if (have_rows('featured_category', $homepage_id)) {
				while (have_rows('featured_category', $homepage_id)) {
					the_row();
					$featured_category_object = get_sub_field('featured_category_id');
					if ($featured_category_object) {
						$featured_category_id = $featured_category_object->term_id;
						$featured_category_image = get_sub_field('featured_category_image'); ?>

						<div class="ui card">


							<a class="image" href="<?php echo get_term_link($featured_category_id, 'job_cat'); ?>">
								<div class="card-image-helper">
									<?php if( $featured_category_image['sizes']['thumb_picture_size'] ){ ?>
										<img src="<?php echo $featured_category_image['sizes']['thumb_picture_size']; ?>" alt="<?php echo $featured_category_object->name; ?>">
									<?php }else{
										echo '<a href="' . get_permalink() . '">' . '<img class="image_class" src="' . get_template_directory_uri() . '/images/nopic.jpg" width="100" height="100" />' . '</a>';
									} ?>
								</div>
							</a>


							<div class="content card-pusher-cover">
								<a class="header center" href="<?php echo get_term_link($featured_category_id, 'job_cat'); ?>">
									<?php echo $featured_category_object->name; ?>
								</a>
							</div>


						</div>

					<?php } ?>
				<?php } ?>
			<?php } wp_reset_query(); ?>
		</div>
		<?php
	}
}
add_shortcode( 'featured_categories_list', 'featured_categories_s' );

// Job Listing Shortcode for 4 columns [job_listings_4]
if (!function_exists('job_listings_4_s')) {
	function job_listings_4_s() {
		wpj_latest_jobs();
	}
}
add_shortcode( 'job_listings_4', 'job_listings_4_s' );


// Job Listing Shortcode for 3 columns [job_listings_3]
if (!function_exists('job_listings_3_s')) {
	function job_listings_3_s() {
		wpj_latest_jobs( 3 );
	}
}
add_shortcode( 'job_listings_3', 'job_listings_3_s' );



/**
 * List Jobs Shortcode [list_jobs]
 *
 * arguments  | accepts         | default
 * ----------------------------------------------------
 * category   | (id/slug/name)  | all
 * featured   | true/false      | false
 * jobs       | (int)           | 4
 * columns    | 3/4             | 4
 * showtitle  | true/false      | true
 * order      | new/old/random  | false (site settings)
 */

if ( ! function_exists( 'wpjobster_list_jobs_s' ) ) {
	function wpjobster_list_jobs_s( $atts ) {

		$a = shortcode_atts( array(
			'category' => 0,
			'jobs' => 4,
			'columns' => 4,
			'showtitle' => true,
			'order' => false,
			'featured' => false,
		), $atts );

		$posts_per_page = $a['jobs'];
		$category = $a['category'];
		$columns = $a['columns'];
		$columns_class = ( $columns == 3 ) ? 'ui three cards' : 'ui four cards';
		$showtitle = filter_var( $a['showtitle'], FILTER_VALIDATE_BOOLEAN );
		$order = $a['order'];
		$featured = filter_var( $a['featured'], FILTER_VALIDATE_BOOLEAN );

		if ( ctype_digit( $category ) || is_int( $category ) ) {
			$this_term = get_term_by( 'id', $category, 'job_cat' );
		} else {
			$this_term = false;
		}

		if ( ! $this_term ) {
			$this_term = get_term_by( 'slug', $category, 'job_cat' );
		}

		if ( ! $this_term ) {
			$this_term = get_term_by( 'name', $category, 'job_cat' );
		}

		if ( isset( $this_term->term_id ) && is_numeric( $this_term->term_id ) ) {
			$category_id = $this_term->term_id;
		} else {
			$category_id = 0;
		}

		if ( isset( $this_term->name ) && ! empty( $this_term->name ) ) {
			$category_link = '<a href="' . get_term_link( $this_term ) . '">' . $this_term->name . '</a>';
		} elseif ( $featured ) {
			$category_link = __( 'Featured Services', 'wpjobster' );
		} else {
			$category_link = __( 'Popular Services', 'wpjobster' );
		}

		if ( $order == 'new' || $order == 'old' || $order == 'random' || $order == 'rand' ) {
			$jobs_order = $order;
		} else {
			$jobs_order = get_option( 'wpjobster_jobs_order' );
		}

		if ( $jobs_order == 'new' ) {
			$orderby_featured = array( 'meta_value' => 'ASC', 'date' => 'DESC' );
			$order_non_featured = 'DESC';
			$orderby_non_featured = 'date';
		}
		elseif ( $jobs_order == 'old' ) {
			$orderby_featured = array( 'meta_value' => 'ASC', 'date' => 'ASC' );
			$order_non_featured = 'ASC';
			$orderby_non_featured = 'date';
		}
		else {
			$orderby_featured = array( 'meta_value' => 'ASC', 'rand' => 'rand' );
			$order_non_featured = 'RAND';
			$orderby_non_featured = 'rand';
		}

		$meta_query = array(
			array(
				'key'     => 'active',
				'value'   => '1',
				'compare' => '='
			)
		);

		$tax_query = $category_id ? array(
			array(
				'taxonomy' => 'job_cat',
				'field'    => 'term_id',
				'terms'    => array( $category_id ),
			),
		) : array();

		$paged = 1;

		$featured_enabled = get_option( 'wpjobster_featured_enable' );
		if ( $featured && $featured_enabled == 'yes' ) {
			$args = array(
				'post_type'      => 'job',
				'tax_query'      => $tax_query,
				'paged'          => $paged,
				'posts_per_page' => $posts_per_page,
				'post_status'    => 'publish',

				'order' => 'DESC',
				'meta_query' => $meta_query,
				'meta_key' => 'home_featured_now',
				'orderby'=> $orderby_featured
			);
		} else {
			$args = array(
				'post_type'      => 'job',
				'tax_query'      => $tax_query,
				'paged'          => $paged,
				'posts_per_page' => $posts_per_page,
				'post_status'    => 'publish',

				'order'          => $order_non_featured,
				'meta_query'     => $meta_query,
				'orderby'        => $orderby_non_featured,
			);
		}


		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {

			if ( $showtitle ) {
				echo '<h2 class="heading-title fancy-underline heading-homepage">' . $category_link . '</h2>';
			}

			echo '<div class="cf ' . $columns_class . '">';

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				wpj_get_user_post_tumb_card();
			}

			echo '</div>';

			wp_reset_postdata();
		}
	}
}
add_shortcode( 'list_jobs', 'wpjobster_list_jobs_s' );

// ALL LOCATIONS //
if(!function_exists('wpjobster_all_locs_area_function')){
	function wpjobster_all_locs_area_function(){

		ob_start(); ?>

		<div id="content" class="jobster_special_page">
			<div class="my_box3">
				<div class="padd10">
					<div class="box_title"><?php echo __("All Locations", 'wpjobster'); ?></div>
					<div class="box_content">
						<?php
						$opt = get_option('wpjobster_show_tax_views');

						if($opt == "no") $show_me_count = false;
						else $show_me_count = true;

						$show_me_count = true;
						$opt = get_option('wpjobster_show_subcats_enbl');

						if($opt == 'no')
							$smk_closed = "smk_closed_disp_none";
						else
							$smk_closed = '';

						$terms 		= get_terms("job_location","parent=0&hide_empty=0");

						global $wpdb;
						$arr = array();
						$count = count($terms); $i = 0;
						if ( $count > 0 ){
							$nr = 4;
							$total_count = 0;
							$arr = array();
							global $wpdb;
							$contor = 0;
							$count = count($terms); $i = 0;
							if ( $count > 0 ){
								foreach ( $terms as $term ) {
									$stuffy = '';
									$cnt	= 1;
									$stuffy .= "<ul id='location-stuff'><li>";
										$terms2 = get_terms("job_location","parent=".$term->term_id."&hide_empty=0");
										$mese = '';
										$mese .= '<ul>';
											$link = get_term_link($term->slug,"job_location"); //This line changed by Alka on 18/09/2015
											$mese .= "<h3><a href='".$link."'>" . $term->name;
												$total_ads = wpjobster_get_custom_taxonomy_count('job',$term->slug);

												if($terms2){
													$mese2 = '<ul class="'.$smk_closed.'" id="taxe_project_cat_'.$term->term_id.'">';
														foreach ( $terms2 as $term2 ){
															++$cnt;
															$tt = wpjobster_get_custom_taxonomy_count('job',$term2->slug);
															$total_ads += $tt;
															$link = get_term_link($term2->slug,"job_location");
															$mese2 .= "<li><a href='".$link."'>" . $term2->name." ". ($show_me_count == true ? "(".$tt.")" : "")."</a></li>";

															$terms3 = get_terms("job_location","parent=".$term2->term_id."&hide_empty=0");
															if($terms3){
																$mese2 .= '<ul class="baca_loc">';
																	foreach ( $terms3 as $term3 ){
																		++$cnt;
																		$tt = wpjobster_get_custom_taxonomy_count('job',$term3->slug);
																		$total_ads += $tt;
																		$link = get_term_link($term3->slug,	"job_location");
																		if(!is_wp_error($link))
																		$mese2 .= "<li><a href='".$link."'>" . $term3->name." ". ($show_me_count == true ? "(".$tt.")" : "")."</a></li>";

																		$terms4 = get_terms("job_location","parent=".$term3->term_id."&hide_empty=0");
																		if($terms4){
																			$mese2 .= '<ul class="baca_loc">';
																				foreach ( $terms4 as $term4 ){
																					++$cnt;
																					$tt = wpjobster_get_custom_taxonomy_count('job',$term4->slug);
																					$total_ads += $tt;
																					$link = get_term_link($term4->slug,	"job_location");
																					if(!is_wp_error($link))
																					$mese2 .= "<li><a href='".$link."'>" . $term4->name." ". ($show_me_count == true ? "(".$tt.")" : "")."</a></li>";
																				}
																			$mese2 .= '</ul>';
																		}

																	}
																$mese2 .= '</ul>';
															}
														}
													$mese2 .= '</ul>';
												}
											$stuffy .= $mese.($show_me_count == true ? "(".$total_ads.")" : "") ."</a></h3></li>";
											$stuffy .= $mese2;
											$mese2 = '';
										$stuffy .= '</ul></li>';
									$stuffy .= '</ul>';

									$i++;
									$arr[$contor]['content'] = $stuffy;
									$arr[$contor]['size']    = $cnt;
									$total_count             = $total_count + $cnt;
									$contor++;
								}
							}

							$i = 0; $k = 0;
							$result = array();

							foreach($arr as $category){
								$result[$k] .= $category['content'];
								$k++;

								if($k == $nr) $k=0;
							}

							foreach($result as $res)
								echo "<div class='stuffa4 jobster_special_page_columns'>".$res.'</div>';
						} ?>
					</div>
				</div>
			</div>
		</div>

		<div id="right-sidebar">
			<ul class="xoxo"><?php dynamic_sidebar( 'other-page-area' ); ?></ul>
		</div>

		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}

// END ALL LOCATIONS //

// NEW VERSION FOR POST NEW JOB
function wpjobster_post_new_job_rows( $job_input_width, $col_counter ) {
	if ( $job_input_width == ''
		|| $job_input_width == 'full'
		|| $job_input_width == 'half-solo'
		|| ( $job_input_width == 'half' && $col_counter != 1 ) ) {

		$col_counter = 0;
		if ( $job_input_width == 'half' ) {
			$col_counter++;
		}
$html_output = <<<HTML
							</div>
							<div class="s-row">
HTML;


	} else {
		$col_counter++;
		$html_output = '';
	}

	return array( $html_output, $col_counter );
}

function wpj_the_width_class( $job_input_width ) {
	if ( $job_input_width == 'half' || $job_input_width == 'half-solo' ) {
		echo 'col50';
	} else {
		echo 'col100';
	}
}

function wpjobster_field_has_errors( $job_field ) {
	if ( ! empty( $job_field['errors'] ) ) {
		return true;
	}
	return false;
}

function wpjobster_field_display_errors( $job_field ) {
	if ( ! empty( $job_field['errors'] ) ) {
		foreach ( $job_field['errors'] as $error ) {
			if ( ! empty( $job_field[$error] ) ) {
				$error_text = $job_field[$error];
			} else {
				$error_text = $error;
			}
echo <<<HTML
							<div class="error">
								{$error_text}
							</div>
HTML;
		}
	}
}

function wpjobster_field_display_basic_counter( $element_id, $max_length ) {
	// works only with basic inputs

	$characters_word = __( 'Characters', 'wpjobster' );
echo <<<HTML
							<div class="char-count"><em>0</em> / {$max_length} {$characters_word}</div>
							<script>
								jQuery( document ).ready( function($) {
									add_input_length_listener( $( "#{$element_id}" ), $( "#{$element_id}" ).siblings( ".char-count" ), {$max_length}, "input" );
								});
							</script>
HTML;
}



// Post New Job Shortcode [wpjobster_post_new_job]
if ( ! function_exists( 'wpjobster_post_new_job' ) ) {
	function wpjobster_post_new_job() {

		$wpjobster_post_new_page_id = get_option('wpjobster_post_new_page_id');

// TODO: remove this and update new page option //
		// $wpjobster_post_new_page_id = 3659;
		// >>> TMP TO BE REMOVED <<< //


		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		// init new post
		if ( ! isset( $_GET['jobid'] ) ) {
			$pid = wpjobster_get_auto_draft( $current_user->ID );
			update_post_meta( $pid, 'home_featured_now', "z" );
			update_post_meta( $pid, 'category_featured_now', "z" );
			update_post_meta( $pid, 'subcategory_featured_now', "z" );
			wp_redirect( add_query_arg( 'jobid', $pid, get_permalink( $wpjobster_post_new_page_id ) ) );
			exit;
		}

		// double check that we are on the right page
		global $wpdb;
		$last_draft = $wpdb->get_row(
			$wpdb->prepare(
				"
				SELECT *
				FROM $wpdb->posts
				WHERE post_type = 'job'
					AND post_status = 'auto-draft'
					AND post_author = %d
				ORDER BY ID DESC
				",
				$uid
			)
		);

		$pid = $_GET['jobid'];

		if ( $last_draft->ID > $pid || $uid != get_post_field( 'post_author', $pid ) ) {
			wp_redirect( add_query_arg( 'jobid', $last_draft->ID, get_permalink( $wpjobster_post_new_page_id ) ) );
			exit;
		}

		// done with the redirections
		$post = get_post( $pid );

		// check for subscriptions
		wpj_get_subscription_info_path();
		$subscription = get_wpjobster_subscription_info();

		$user_level = wpjobster_get_user_level( $uid );

		// START ACF
		if ( have_rows( 'job_fields' ) ) { // check if the flexible content field has rows of data

			// create a multidimensional array to structure our data
			$job_fields_array = get_field_object( 'job_fields' );
			$job_fields = array();

			// set up the first element for the fields without tabs
			array_push( $job_fields, array() );
			$job_fields[0]['tab_fields'] = array();

			foreach ( $job_fields_array['value'] as $job_field ) {
				if ( $job_field['acf_fc_layout'] == 'tab_separator' ) {

					// prepare tab with empty array for the fields
					$job_field['tab_fields'] = array();

					// insert tab
					array_push( $job_fields, $job_field );

				} else {

					// prepare field with empty array for the errors
					$job_field['errors'] = array();

					// data submit and errors here to avoid another two foreach loops
					if ( $job_field['acf_fc_layout'] == 'job_title' ) {

						$post_job_title = WPJ_Form::post( 'job_title', ( ( $post->post_title != 'Auto Draft' ) ? $post->post_title : '') );
						$post_job_title = wp_kses( $post_job_title, array() );

						$min_chr_title = get_option( 'wpjobster_characters_jobtitle_min' ) ?: 15;
						$max_chr_title = get_option( 'wpjobster_characters_jobtitle_max' ) ?: 80;

						if ( isset( $_POST['job_submit'] ) ) {
							if ( ! is_demo_user() ) {
								wp_update_post( array(
									'ID' => $pid,
									'post_title' => $post_job_title,
								) );
							}

							if ( empty( $post_job_title ) ) {
								$job_field['errors'][] = 'err_title_empty';
							} elseif ( mb_strlen( $post_job_title ) < $min_chr_title ) {
								$job_field['errors'][] = 'err_title_too_short';
							} elseif ( mb_strlen( $post_job_title ) > $max_chr_title ) {
								$job_field['errors'][] = 'err_title_too_long';
							}
						}

					} elseif ( $job_field['acf_fc_layout'] == 'job_price' ) {

						$post_job_price = WPJ_Form::post( 'job_price', get_post_meta( $pid, 'price', true ) );

						$min_job_price = get_option( 'wpjobster_min_job_amount' );
						$max_job_price = $subscription['wpjobster_subscription_max_job_price'] ?: get_option( 'wpjobster_level' . $user_level . '_max' );

						if ( wpj_bool_option( 'wpjobster_enable_free_input_box' ) ) {
							update_post_meta( $pid, 'variable_cost', '1' );

						} elseif ( wpj_bool_option( 'wpjobster_enable_dropdown_values' ) ) {
							update_post_meta( $pid, 'input_free_cost', '1' );

						} else {
							$post_job_price = get_option( 'wpjobster_job_fixed_amount' );

						}

						if ( ! is_numeric( $post_job_price ) ) {
							$post_job_price = 0;
						}

						if ( isset( $_POST['job_submit'] ) ) {
							if ( ! is_demo_user() ) {
								update_post_meta( $pid, 'price', $post_job_price );
							}

							if ( $post_job_price < 0 ) {
								$job_field['errors'][] = 'err_price_negative';
							} elseif ( $post_job_price < $min_job_price ) {
								$job_field['errors'][] = 'err_price_too_small';
							} elseif ( isset( $max_job_price )
									&& $post_job_price > $max_job_price ) {
								$job_field['errors'][] = 'err_price_too_big';
							}
						}

					} elseif ( $job_field['acf_fc_layout'] == 'job_category' ) {

						$categories = wp_get_object_terms( $pid, 'job_cat', array(
								'orderby' => 'term_order',
								'order' => 'ASC',
							)
						);

						if ( isset( $categories[0] ) && $categories[0]->parent != 0 ) {
							$categories = array_reverse( $categories );
						}

						$default_job_category = isset( $categories[0]->term_id ) ? $categories[0]->term_id : '';
						$default_job_subcategory = isset( $categories[1]->term_id ) ? $categories[1]->term_id : '';

						$post_job_category = WPJ_Form::post( 'job_category', $default_job_category );
						$post_job_subcategory = WPJ_Form::post( 'job_subcategory', $default_job_subcategory );

						if ( isset( $_POST['job_submit'] ) ) {

							$term_job_category = get_term( $post_job_category, 'job_cat' );
							$term_job_subcategory = get_term( $post_job_subcategory, 'job_cat' );

							$categories_to_set = array();
							if ( isset( $term_job_category->slug ) ) {
								$categories_to_set[] = $term_job_category->slug;
							}
							if ( isset( $term_job_subcategory->slug ) ) {
								$categories_to_set[] = $term_job_subcategory->slug;
							}

							if ( ( get_post_meta( $pid, 'subcategory_featured_until', true ) == 'z'
									|| get_post_meta( $pid, 'subcategory_featured_until', true ) == false )
								&& ( get_post_meta( $pid, 'category_featured_until', true ) == 'z'
									|| get_post_meta( $pid, 'category_featured_until', true ) == false ) ) {

								wp_set_object_terms( $pid, $categories_to_set, 'job_cat' );
							} else {
								$job_field['errors'][] = 'err_category_featured';
							}

							if ( empty( $post_job_category ) ) {
								$job_field['errors'][] = 'err_category_not_set';
							}

							if ( empty( $post_job_subcategory ) ) {
								$job_field['errors'][] = 'err_subcategory_not_set';
							}
						}

					} elseif ( $job_field['acf_fc_layout'] == 'job_description' ) {

						$post_job_description = WPJ_Form::post( 'job_description', $post->post_content );
						$post_job_description = wpj_description_parser( $post_job_description );

						$min_chr_description = get_option( 'wpjobster_characters_description_min' ) ?: 0;
						$max_chr_description = get_option( 'wpjobster_characters_description_max' ) ?: 1000;

						if ( isset( $_POST['job_submit'] ) ) {
							if ( ! is_demo_user() ) {
								wp_update_post( array(
									'ID' => $pid,
									'post_content' => $post_job_description,
								) );
							}

							$kses_job_description = wp_kses( $post_job_description, array() );
							if ( mb_strlen( $kses_job_description ) < $min_chr_description ) {
								$job_field['errors'][] = 'err_description_too_short';
							} elseif ( mb_strlen( $kses_job_description ) > $max_chr_description ) {
								$job_field['errors'][] = 'err_description_loo_long';
							}
						}


					} elseif ( $job_field['acf_fc_layout'] == 'job_tags' ) {

						$job_tags_text = wp_strip_all_tags( get_the_tag_list( '', ',', '', $pid ) );
						$post_job_tags = WPJ_Form::post( 'job_tags', $job_tags_text );

						if ( isset( $_POST['job_submit'] ) ) {
							if ( ! is_demo_user() ) {
								wp_set_post_tags( $pid, $post_job_tags );
							}
						}


					} elseif ( $job_field['acf_fc_layout'] == 'job_instructions' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_lets_meet' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_location' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_distance' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_delivery_time' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_shipping' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_cover' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_images' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_youtube_video' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_audio_files' ) {



					} elseif ( $job_field['acf_fc_layout'] == 'job_extras' ) {
						if ( isset( $_POST['job_submit'] ) ) {
							if ( wpj_bool_option( 'wpjobster_enable_extra' ) || $subscription['wpjobster_subscription_noof_extras'] ) {
								$user_allowed_extras = get_option( 'wpjobster_get_level' . $user_level . '_extras' );
								$user_max_extra_price = get_option( 'wpjobster_level' . $user_level . '_max_extra' );

								// check subscriptions
								$user_allowed_extras = $subscription['wpjobster_subscription_noof_extras'] ?: $user_allowed_extras;
								if ( ! is_numeric( $user_allowed_extras ) ) { $user_allowed_extras = 3; }
								$user_max_extra_price = $subscription['wpjobster_subscription_max_extra_price'] ?: $user_max_extra_price;


								for ( $k = 1; $k <= $user_allowed_extras; $k++ ) {
									$extra_price = WPJ_Form::post( 'extra' . $k . '_price' );
									$extra_content = WPJ_Form::post( 'extra' . $k . '_content' );

									if ( is_numeric( $extra_price )
										&& $extra_price > 0
										&& $extra_price <= $user_max_extra_price ) {

										if ( ! is_demo_user() ) {
											update_post_meta( $pid, 'extra' . $k . '_price', 	$extra_price );
										}
									} else {
										$job_field['errors'][] = 'err_extra_price';
									}

									if ( ! empty( $extra_content ) ) {
										if ( ! is_demo_user() ) {
											update_post_meta( $pid, 'extra' . $j_extra_cnt . '_content', $extra_content);
										}
									}

									$min_chr_extra_content = get_option( 'wpjobster_characters_extradescription_min' );
									$max_chr_extra_content = get_option( 'wpjobster_characters_extradescription_max' );

									// set defaults
									$min_chr_extra_content = $min_chr_extra_content ?: 0;
									$max_chr_extra_content = $max_chr_extra_content ?: 50;

									if ( mb_strlen( $extra_content ) < $min_chr_extra_content
										|| mb_strlen( $extra_content ) > $max_chr_extra_content ) {
										$job_field['errors'][] = 'err_extra_content';
									}

								}

// TODO: check later if we need to store extras dynamically
							}
						}

					} elseif ( $job_field['acf_fc_layout'] == 'job_terms_of_service' ) {



					} else {



					}


					// insert fields at the end for display, including all the errors
					end( $job_fields );
					$tab_key = key( $job_fields ); // get last tab's key
					array_push( $job_fields[$tab_key]['tab_fields'], $job_field );
				} // endif tab/input
			} // endforeach



			if ( isset( $_POST['job_submit'] ) ) {
				if ( ! is_demo_user() ) {

					// do last things about the job if everything is ok
// TODO: check if there is no error
					if ( 1 == 2 ) {
						wp_update_post( array(
							'ID' => $pid,
							'post_status' => 'draft',
						) );
						update_post_meta( $pid, 'is_draft',      '0' );
						update_post_meta( $pid, 'active',        '1' );


						if ( wpj_bool_option( 'wpjobster_admin_approve_job' ) ) {

							wp_publish_post( $pid );
							wp_update_post( array(
								'ID' => $pid,
								'post_status' => 'draft',
							) );

							update_post_meta( $pid, 'under_review', '1' );

							wpjobster_send_email_allinone_translated('job_admin_new', 'admin', false, $pid);
							wpjobster_send_sms_allinone_translated('job_admin_new', 'admin', false, $pid);

							wpjobster_send_email_allinone_translated('job_new', false, false, $pid);
							wpjobster_send_sms_allinone_translated('job_new', false, false, $pid);

						} else {

							wp_publish_post( $pid );
							wp_update_post( array(
								'ID' => $pid,
								'post_status' => 'publish',
							) );

							update_post_meta( $pid, 'under_review', '0' );

							wpjobster_send_email_allinone_translated('job_admin_acc', 'admin', false, $pid);
							wpjobster_send_sms_allinone_translated('job_admin_acc', 'admin', false, $pid);

							wpjobster_send_email_allinone_translated('job_acc', false, false, $pid);
							wpjobster_send_sms_allinone_translated('job_acc', false, false, $pid);

						}


						// send Analytics Goal
						update_user_meta( $uid, 'uz_last_job_post_not_tracked', $pid );
						update_user_meta( $uid, 'uz_last_job_post_not_tracked_cpa', $pid );

					}

				}
			}


			// get post again, after updating all the info
			$post = get_post( $pid );

			if ( ! $job_fields[0]['tab_fields'] ) {
				$use_tabs = true;
			} else {
				$use_tabs = false;
			}


			// start displaying below ?>
			<?php end( $job_fields ); $last_tab = key( $job_fields ); // get last tab ?>
<form method="post" enctype="multipart/form-data" action="">
	<div class="post-new-job-wrapper">
		<div class="tabs-wrapper">

			<?php if ( $use_tabs && $last_tab > 0 ) { // display tab navigation ?>
			<div class="navigator">
				<?php
					$tab_number = 0;
					foreach ( $job_fields as $job_tab ) {
						if ( $use_tabs && $tab_number > 0 ) {

							// // which tab is active?
							if ( $tab_number == 1 ) {
								$tab_class = 'active';
							} else {
								$tab_class = '';
							}

				?>
							<div class="tab-selector <?php echo $tab_class; ?>" id="tab<?php echo $tab_number; ?>" style="width: 20%;"><div class="tab-text"><span><?php echo $tab_number; ?></span><?php echo $job_tab['label']; ?></div></div>
				<?php
						}
						$tab_number++;
					}
				?>
			</div>
			<?php } ?>

			<?php $tab_number = 0; $col_counter = 0;
			foreach ( $job_fields as $job_tab ) {

				// display tab header
				if ( $use_tabs && $tab_number > 0 ) {

					// which tab is active?
					if ( $tab_number == 1 ) {
						$tab_class = 'active';
					} else {
						$tab_class = '';
					}
			?>

					<?php if ( $tab_number == 1 ) { ?>
			<div class="white-cnt tab-wrapper">
					<?php } ?>



				<div class="tab <?php echo $tab_class; ?>">
					<div class="s-row">
						<div class="col70">
							<div class="s-row">
						<?php // echo $tab_number; ?>
				<?php } ?>


				<?php if ( $job_tab['tab_fields'] ) { // display tab content ?>
					<?php foreach ( $job_tab['tab_fields'] as $job_input ) { ?>

						<?php if ( $job_input['acf_fc_layout'] == 'job_title' ) { // job_title input ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block job-title <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>
										<textarea name="job_title" id="job_title" class="focus-area" placeholder="<?php echo $job_input['placeholder']; ?>"><?php echo $post_job_title; ?></textarea>
										<?php wpjobster_field_display_basic_counter( 'job_title', $max_chr_title ) ?>
									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_price' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?><span class="lighter"> - <?php echo wpjobster_get_currency_symbol( wpjobster_get_currency_classic() ); ?></span></h2>

										<?php if ( wpj_bool_option( 'wpjobster_enable_free_input_box' ) ) { ?>
											<input name="job_price" id="job_price" type="number" step="any" class="focus-area" placeholder="<?php echo $job_input['placeholder']; ?>" value="<?php echo $post_job_price; ?>">
										<?php } elseif ( wpj_bool_option( 'wpjobster_enable_dropdown_values' ) ) { ?>
											<div class="select-block">
												<?php echo wpjobster_get_variale_cost_dropdown( 'styledselect focus-area', $post_job_price, 'job_price' ); ?>
											</div
										<?php } else { ?>
											<?php echo wpjobster_get_show_price_classic( get_option( 'wpjobster_job_fixed_amount' ) ); ?>
										<?php } ?>
									</label>
									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>

						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_category' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="col100 col-stick-bottom">
								<label for="job_category">
									<?php wpjobster_field_display_errors( $job_input ); ?>
								</label>
							</div>

							<div class="col50 hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label><h2><?php echo $job_input['label']; ?></h2>
									<div class="select-block">
										<?php
											echo wpjobster_get_categories_clck(
												'job_category',
												$post_job_category,
												__( 'Select Category', 'wpjobster' ),
												'styledselect focus-area',
												'onchange="display_subcat(this.value)"'
											);
										?>
									</div>
									</label>
									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>

							<div class="col50 hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label><span class="empty"></span>
									<div class="select-block" id="sub_cats">
										<?php
											echo wpjobster_get_subcategories_clck(
												'job_subcategory',
												$post_job_category,
												$post_job_subcategory,
												__( 'Select Subcategory', 'wpjobster' ),
												'styledselect focus-area'
											);
										?>
									</div>
									</label>
									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>

							<script>
								function display_subcat( vals, selected ) {
									if ( typeof( selected ) === 'undefined' ) { selected = ''; }

									$.post(
										"<?php bloginfo('url'); ?>/?get_subcategories_for_me=1",
										{queryString: "" + vals + ""},
										function( data ) {
											if ( data.length > 0 ) {
												$( '#sub_cats' ).html( data );
												$( '#sub_cats select' ).val( selected );
											}
											$.fn.myFunction();
										}
									);
								}
							</script>

							<?php if ( $post_job_category ) { ?>
							<script>
								jQuery( document ).ready( function( e ) {
										display_subcat( '<?php echo $post_job_category; ?>',
														'<?php echo $post_job_subcategory; ?>' );
								});
							</script>
							<?php } ?>

						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_description' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>



							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<div class="relative">
											<?php wpjobster_field_display_errors( $job_input ); ?>
											<h2><?php echo $job_input['label']; ?></h2>
											<textarea id="job_description" name="job_description" class="job-description-wysiwyg job-description-wysiwyg-style" placeholder="<?php echo $job_input['placeholder']; ?>"><?php echo $post_job_description; ?></textarea>

											<!-- toolbar with suitable buttons and dialogues -->
											<div id="job_description_toolbar" class="job-description-wysiwyg-toolbar">
												<a data-wysihtml5-command="bold"><i class="bordered bold icon"></i></a>
												<a data-wysihtml5-command="italic"><i class="bordered italic icon"></i></a>
												<a data-wysihtml5-command="underline"><i class="bordered paint brush icon"></i></a>
												<a data-wysihtml5-command="insertUnorderedList"><i class="bordered unordered list icon"></i></a>
												<a data-wysihtml5-command="insertOrderedList"><i class="bordered ordered list icon"></i></a>
											</div>

											<div class="char-count"><?php echo ' / ' . $max_chr_description . ' ' . __( 'Characters', 'wpjobster' ); ?></div>

											<script>
												jQuery(document).ready(function($){
													max_chr_description = '<?php echo $max_chr_description; ?>';
													wpj_js_description_args_allowed( max_chr_description );
												});
											</script>
										</div>

									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_tags' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>

										<input id="job_tags" name="job_tags" value="<?php echo $post_job_tags; ?>" class="focus-area" />

										<script>
											jQuery( document ).ready( function($) {
												$('#job_tags').tagsInput({
													'defaultText':'',
													'height': 'auto',
													'width': '100%'
												});

												$("#job_tags_tag").focus(function() {
													$(this).parents(".input-block").eq(0).find('.hidden-tooltip').addClass("visible");
													$(this).parents(".post-new-job-wrapper").eq(0).addClass('has-focus');
													$("#job_tags_tagsinput").addClass('focus');
												}).blur(function(){
													$(this).parents(".input-block").eq(0).find('.hidden-tooltip').removeClass("visible");
													$(this).parents(".post-new-job-wrapper").eq(0).removeClass('has-focus');
													$("#job_tags_tagsinput").removeClass('focus');
												});

											});
										</script>


									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>

						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_instructions' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_lets_meet' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_location' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_distance' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_delivery_time' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_shipping' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_cover' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_images' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_youtube_video' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_audio_files' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_extras' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2><?php echo $job_input['price_label']; ?></h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } elseif ( $job_input['acf_fc_layout'] == 'job_terms_of_service' ) { ?>

							<?php list( $html_output, $col_counter ) = wpjobster_post_new_job_rows( $job_input['width'], $col_counter ); echo $html_output; ?>

							<div class="<?php wpj_the_width_class( $job_input['width'] ); ?> hover-area">
								<div class="input-block <?php if ( wpjobster_field_has_errors( $job_input ) ) { echo ' has-errors '; } ?>">
									<label>
										<?php wpjobster_field_display_errors( $job_input ); ?>
										<h2>job_terms_of_service</h2>



									</label>

									<div class="hidden-tooltip">
										<div class="hidden-tooltip-box">
											<?php echo $job_input['instructions']; ?>
										</div>
									</div>
								</div>
							</div>


						<?php } else { ?>
							<?php //echo $job_input['acf_fc_layout']; echo '<br>'; ?>
						<?php } ?>
					<?php } ?>

				<?php } else { ?>
					<?php // tab empty ?>
				<?php } ?>

				<?php if ( $use_tabs && $tab_number > 0 ) { ?>
							</div>
						</div>
					</div><!-- s-row -->

					<div class="tab-controls">
					<?php if ( $tab_number > 1 ) { ?>
						<a href="#" title="" class="left"><?php echo $job_fields[$tab_number - 1]['label']; ?></a>
					<?php } ?>
					<?php if ( $tab_number < $last_tab ) { ?>
						<a href="#" title="" class="right"><?php echo $job_fields[$tab_number + 1]['label']; ?></a>
					<?php } ?>
					</div><!-- tab-controls -->
				</div><!-- tab -->

					<?php if ( $tab_number == $last_tab ) { ?>
			</div><!-- tab-wrapper -->
					<?php } ?>
				<?php } $tab_number++; ?>
			<?php } // endforeach ?>
			<input type="submit" name="job_submit" value="Submit" style="margin-top: 100px;">
		</div><!-- tabs-wrapper -->
	</div><!-- post-new-job-wrapper -->
</form>

<?php
// pre_print_r( $job_fields );
?>

		<?php } else { // no layouts found ?>



		<?php } // endif have_rows // END ACF
	} // end function
} // endif function_exists
add_shortcode( 'wpjobster_post_new_job', 'wpjobster_post_new_job' );


// Edit Job Shortcode [wpjobster_edit_job]
if ( ! function_exists( 'wpjobster_edit_job' ) ) {
	function wpjobster_edit_job() {
		?>
Edit Job
		<?php
	}
}
add_shortcode( 'wpjobster_edit_job', 'wpjobster_edit_job' );
// END NEW VERSION FOR POST NEW JOB

add_shortcode( 'inipay_error_message', 'inipay_error_message_function' );
function inipay_error_message_function( $atts ){
	global $wpdb;

	$id = $_GET['id'];
	$method = strtolower($_GET['method']);
	if($method==''){
		$method="vbank";
	}
	$sel = " select result_msg from ".$wpdb->prefix."job_inipay_{$method}_payment_details where id='$id'";
	$res = $wpdb->get_results($sel);
	$row = $res[0];

	return __("Error Message:","wpjobster").$row->result_msg;
}
