<?php
function show_posts($def='No jobs posted.', $nav=1, $the_query="", $max=0, $func="", $class="post_results") {
	if ($the_query) {
		query_posts($the_query->query_vars);
	}
	global $wp_query;
	if ($max) {
		$wp_query->query_vars["posts_per_page"]=$max;
	}
	$nr = $wp_query->query_vars["posts_per_page"];
	query_posts($wp_query->query_vars);

	if (have_posts()) {
		?><div class="<?php echo $class; ?> cf"><?php
		while ( have_posts() ) : the_post();

		if (!$func) {
			wpj_get_user_post_tumb_card();
		} else {
			$func();
		}
		endwhile;

		?></div><?php
	} else {
		echo "<br><h3 class='center'>".__($def,"wpjobster")."</h3>";
	}
	wp_reset_postdata();
	wp_reset_query();
}

function is_subcategory( $post_type = 'job_cat' ){
	$t = get_query_var('term');
	$term = get_term_by('slug', $t, $post_type);
	return ( $term->parent != '0' ) ? true : false;
}
