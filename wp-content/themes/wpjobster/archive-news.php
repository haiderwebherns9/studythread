<?php
global $is_blog_pg;
$is_blog_pg = 1;

get_header(); ?>

<div id="content-full" class="blog_posts">
	<div class="ui segment">
		<h1 class="heading-title">
			<?php _e("News & Stories",'wpjobster'); ?>
		</h1>
	</div>

	<?php get_template_part('template-parts/posts/content', 'archive-news'); ?>

	<div class="ui hidden divider"></div>

</div>

<?php get_footer(); ?>
