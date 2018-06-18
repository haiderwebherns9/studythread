<?php

// Create Public Homepage if doesn't exists
$wpj_check_public_homepage = get_option('wpj_check_public_homepage');
if (empty($wpj_check_public_homepage)) {
	$main_page_url = get_option('main_page_url');
	$new_page_template = 'page-templates/page-homepage-public.php';

	$main_page_object = get_post($main_page_url);
	if (!isset($main_page_object->ID)) {
		wpjobster_insert_homepage('main_page_url', 'Homepage', '' );
		$main_page_url = get_option('main_page_url');
	}

	update_post_meta( $main_page_url, '_wp_page_template', $new_page_template );

$main_page_content_public = <<<EOT
[vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1460396291658{margin-bottom: 0px;}"][vc_column][rev_slider_vc alias="home" el_class="rev-slider-container"][vc_column_text][advanced-search-slider search-style = white][/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row"][vc_column][vc_custom_heading text="Featured Categories" font_container="tag:h2|font_size:30|text_align:left" use_theme_fonts="yes" el_class="heading-title fancy-underline"][vc_column_text][featured_categories_list][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_custom_heading text="Popular Services" font_container="tag:h2|font_size:30|text_align:left" use_theme_fonts="yes" el_class="heading-title fancy-underline"][vc_column_text][job_listings_4][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_custom_heading text="News & Stories" font_container="tag:h2|font_size:30|text_align:left" use_theme_fonts="yes" el_class="heading-title fancy-underline"][vc_column_text][news_listing articles_no = 10][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][/vc_column][/vc_row]
EOT;

	$update_user_homepage_content = array(
		'ID'           => $main_page_url,
		'post_content' => $main_page_content_public,
	);
	wp_update_post($update_user_homepage_content);

	update_option('show_on_front', 'page');    // show on front a static page
	update_option('page_on_front', $main_page_url);  //set it as front page

	update_option('wpj_check_public_homepage', 'done');
}


// Create User Homepage if doesn't exists
$wpj_check_user_homepage = get_option('wpj_check_user_homepage');
if (empty($wpj_check_user_homepage)) {
	$main_page_url_user = get_option('main_page_url_user');
	$main_page_object_user = get_post($main_page_url_user);

	if(!isset($page_check->ID)){
		$new_page_title = 'Homepage - Logged In';
		$new_page_template = 'page-templates/page-homepage-user.php';
		$new_page = array(
			'post_type' => 'page',
			'post_name' => 'home',
			'post_title' => $new_page_title,
			'post_status' => 'publish',
			'post_author' => 1,
		);

		$main_page_url_user = wp_insert_post($new_page);
		update_option('main_page_url_user', $main_page_url_user);

	}

	update_post_meta( $main_page_url_user, '_wp_page_template', $new_page_template );

$main_page_content_user = <<<EOT
[vc_row el_class="wpj-position-container"][vc_column width="1/4" el_class="wpj-position-2"][vc_column_text][user_welcome_box][/vc_column_text][vc_column_text][popular_categories][/vc_column_text][vc_column_text][recently_viewed_jobs][/vc_column_text][vc_column_text][recently_bought_job][/vc_column_text][/vc_column][vc_column width="3/4" el_class="wpj-position-1"][rev_slider alias="logged-in-home"][vc_row_inner][vc_column_inner][vc_column_text][services_searchbar][/vc_column_text][/vc_column_inner][/vc_row_inner][vc_custom_heading text="Recommended for you" font_container="tag:h2|font_size:30|text_align:left" use_theme_fonts="yes" el_class="heading-title fancy-underline"][vc_column_text][job_listings_3][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column][vc_custom_heading text="News & Stories" font_container="tag:h2|font_size:30|text_align:left" use_theme_fonts="yes" el_class="heading-title fancy-underline"][vc_column_text][news_listing articles_no = 10][/vc_column_text][/vc_column][/vc_row]
EOT;

	$update_user_homepage_content = array(
		'ID'           => $main_page_url_user,
		'post_content' => $main_page_content_user,
	);
	wp_update_post($update_user_homepage_content);

	update_option('wpj_check_user_homepage', 'done');
}


// Set default Visual Composer Settings

$wpj_check_visual_composer_defaults = get_option('wpj_check_visual_composer_defaults');
if (empty($wpj_check_visual_composer_defaults)) {

	update_option('wpb_js_use_custom', '1');
	update_option('wpb_js_margin', '10px');
	update_option('wpb_js_gutter', '10');
	update_option('wpb_js_responsive_max', '991');

	update_option('wpj_check_visual_composer_defaults', 'done');
}

$wpj_check_visual_composer_defaults_update1 = get_option( 'wpj_check_visual_composer_defaults_update1' );
if ( empty( $wpj_check_visual_composer_defaults_update1 )
	&& get_option( 'wpb_js_margin' ) == '10' ) {

	update_option( 'wpb_js_margin', '10px' );
	update_option( 'wpj_check_visual_composer_defaults_update1', 'done' );
}
