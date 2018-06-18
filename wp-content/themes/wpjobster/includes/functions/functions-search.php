<?php
// GENERAL FUNCTIONS
function get_default_search(){
	$default_search = get_option( 'wpjobster_default_advanced_search' );
	if( $default_search == 'users' ){
		$url_for_search = get_permalink( get_option( 'wpjobster_search_user_page_id' ) );
	} elseif( $default_search == 'requests' ){
		$url_for_search = get_permalink( get_option( 'wpjobster_advanced_search_request_page_id' ) );
	} else {
		$url_for_search = get_permalink(get_option('wpjobster_advanced_search_id'));
	}
	return $url_for_search;
}

// FUNCTIONS
include_once get_template_directory() . '/includes/search/functions-job-search.php';
include_once get_template_directory() . '/includes/search/functions-request-search.php';
include_once get_template_directory() . '/includes/search/functions-user-search.php';
include_once get_template_directory() . '/includes/search/functions-live-search.php';

// VIEWS
get_template_part('template-parts/pages/search/page', 'advanced-job-search');
get_template_part('template-parts/pages/search/page', 'advanced-request-search');
get_template_part('template-parts/pages/search/page', 'advanced-user-search');
?>
