<?php

	$wpj_job = new WPJ_Load_More_Posts(
		array(
			'post_type'      => 'news',
			'function_name'  => 'wpjobster_get_news',
			'posts_per_page' => 10,
		)
	);

	if($wpj_job->have_rows()){
		$wpj_job->show_posts_list_func();
	}else{
		echo __('No posts for news!','wpjobster');
	}

?>
