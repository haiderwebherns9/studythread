<?php
/*
Template Name: Blog Posts
*/

get_header(); ?>

<div id="content" class="blog_posts">
	<h2 class="page_title"><?php _e("Blog Posts",'wpjobster'); ?></h2>
	<?php $wpj_job = new WPJ_Load_More_Posts(
		array(
			'post_type'      => 'post',
			'function_name'  => 'wpjobster_get_archive_posts',
			'posts_per_page' => 2,
		)
	);
	if($wpj_job->have_rows()){
		$wpj_job->show_posts_list_func();
	}else{
		echo __('No posts!','wpjobster');
	} ?>
</div>

<div class="the_sidebar">
	<div class="ul">
		<h2 class="small first_sidebar_title"><?php _e("Archive",'wpjobster'); ?></h2>
		<ul><?php wp_get_archives(); ?></ul>
	</div>
</div>

<?php get_footer();
