<?php
function wpj_tag_vars(){
	$vars = array();

	global $query_string;
	$my_order = wpjobster_get_current_order_by_thing();
	$closed = array(
			'key' => 'closed',
			'value' => "0",
			'compare' => '='
	);
	$prs_string_qu = wp_parse_args($query_string);
	$prs_string_qu['meta_query'] = array($closed);
	$prs_string_qu['post_type'] = array('job','post');
	$force_no_custom_order = "TRUE";

		if($my_order == "auto")
		{
			$prs_string_qu['meta_key'] = "";
			$prs_string_qu['orderby'] = "date";
			$prs_string_qu['order'] = "ASC";
			$force_no_custom_order = "FALSE";
		}

		if($my_order == "new")
		{
			$prs_string_qu['meta_key'] = "";
			$prs_string_qu['orderby'] = "date";
			$prs_string_qu['order'] = "DESC";
		}

		if($my_order == "rating")
		{
			$prs_string_qu['meta_key'] = "wpj_new_rating";
			$prs_string_qu['orderby'] = "meta_value_num";
			$prs_string_qu['order'] = "DESC";
		}

		$prs_string_qu['force_no_custom_order'] = $force_no_custom_order;

		$prs_string_qu = $prs_string_qu + array('function_name'=>'wpj_get_user_post_tumb_card', 'container_class' => 'ui three cards');

		$wpj_job = new WPJ_Load_More_Posts($prs_string_qu);

	$vars = array(
		'prs_string_qu' => $prs_string_qu,
		'wpj_job'       => $wpj_job,
		'my_order'      => $my_order
	);

	return $vars;
}
