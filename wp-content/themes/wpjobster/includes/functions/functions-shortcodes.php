<?php
function wpj_add_shortcode( $tag, $func ) {
	if ( ! is_admin() ) {
		add_shortcode( $tag, $func );
	} else {
		return;
	}
}

// FUNCTIONS
include_once get_template_directory() . '/includes/shortcodes/shortcodes-boxes.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-category.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-job.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-news.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-posts.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-request.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-search.php';
include_once get_template_directory() . '/includes/shortcodes/shortcodes-user.php';

// SHORTCODES
add_shortcode( 'wpjobster_theme_post_new', 'wpjobster_post_new_area_function' );
add_shortcode( 'wpjobster_theme_pay_for_job_page', 'wpjobster_pay_for_job_area_function' );
add_shortcode( 'wpjobster_theme_blog_posts', 'wpjobster_blog_posts_area_function' );
add_shortcode( 'wpjobster_theme_my_account_reviews', 'wpjobster_my_account_reviews_area_function' );
add_shortcode( 'wpjobster_theme_my_account_shopping', 'wpjobster_my_account_shopping_area_function' );
add_shortcode( 'wpjobster_theme_my_account_all_notifications', 'wpjobster_my_account_all_notifications_area_function' );
add_shortcode( 'wpjobster_theme_my_account_personal_info', 'wpjobster_my_account_pers_info_area_function' );
add_shortcode( 'wpjobster_theme_my_account_sales', 'wpjobster_my_account_sales_area_function' );
add_shortcode( 'wpjobster_theme_all_categories', 'wpjobster_all_cats_area_function' );
add_shortcode( 'wpjobster_theme_search_jobs', 'wpjobster_adv_src_area_function' );
add_shortcode( 'wpjobster_theme_search_requests', 'wpjobster_adv_req_src_area_function' );
add_shortcode( 'wpjobster_theme_all_locations', 'wpjobster_all_locs_area_function' );
add_shortcode( 'wpjobster_theme_my_account_payments', 'wpjobster_my_account_payments_area_function' );
add_shortcode( 'wpjobster_theme_my_account', 'wpjobster_my_account_area_function' );
add_shortcode( 'wpjobster_theme_my_favorites', 'wpjobster_my_favorites_area_function' );
add_shortcode( 'wpjobster_theme_my_account_priv_mess', 'wpjobster_my_account_priv_mess_area_function' );
add_shortcode( 'add_edit_request','wpjobster_add_or_edit_request_form' );
