<?php
global $is_blog_pg;
$is_blog_pg = 1;

get_header(); ?>

<div id="content-full" class="blog_posts">
	<div class="white-cnt heading-cnt">
		<h1 class="heading-title">
			<?php the_archive_title(); ?>
		</h1>
	</div>

	<?php
	global $wp_query;
	$wpj_job = new WPJ_Load_More_Posts(
		array(
			'function_name'  => 'wpjobster_get_archive',
			'posts_per_page' => 12,
			'year' => $wp_query->query_vars['year'],
			'monthnum' => $wp_query->query_vars['monthnum'],
		)
	);
	?>

	<div class="wpjobster-news-wrapper">
		<?php
		if($wpj_job->have_rows()){
			$wpj_job->show_posts_list_func();
		}else{
			echo __('No posts!','wpjobster');
		}
		?>
	</div>
</div>

<?php get_footer(); ?>
