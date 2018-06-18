<?php
// POST LISTINGS [posts_listing]
if (!function_exists('posts_listings_s')) {
	function posts_listings_s( $atts ) {
		$a = shortcode_atts( array(
			'articles_no' => 10
		), $atts );

		$articles_no = $a['articles_no'];
		?>

		<div class="cf relative right p30t"">
			<a href="<?php echo get_permalink( get_option( 'wpjobster_blog_home_id' ) ); ?>" class="ui button"><?php _e("View all posts",'wpjobster'); ?></a>
		</div>

		<div class="cf cb relative">
		<div class="news-carousel owl-carousel owl-theme">
			<?php
			query_posts('post_type=post&posts_per_page=' . $articles_no);
			while(have_posts()){
				the_post();
				?>

				<div class="ui card">
					<a class="image" href="<?php the_permalink() ?>">
						<div class="card-image-helper">
							<?php if( get_featured_image("news_slider") ){
								echo get_featured_image("news_slider");
							}else{
								echo '<a href="' . get_permalink() . '">' . '<img class="image_class" src="' . get_template_directory_uri() . '/images/nopic.jpg" width="100" height="100" />' . '</a>';
							} ?>
						</div>
					</a>

					<div class="content card-pusher-cover">
						<a class="header center" href="<?php the_permalink(); ?>">
							<?php the_title() ?>
						</a>
						<div class="description">
							<?php echo wpjobster_better_trim( wp_strip_all_tags( get_the_excerpt(), true ), 240 ); ?>
						</div>
					</div>

					<div class="extra content center">
						<i class="calendar icon"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
					</div>
				</div>

			<?php } wp_reset_query(); ?>
		</div>
	</div>
		<?php
	}
}
add_shortcode( 'posts_listing', 'posts_listings_s' );
// END POST LISTINGS [posts_listing]

// BLOG POSTS
function wpjobster_blog_posts_area_function() {
	ob_start(); ?>

	<div id="content-full" class="blog_posts">
		<div class="ui segment">
			<div class="ui grid">
				<div class="sixteen wide column">
					<h1 class="heading-title">
						<?php the_title(); ?>
					</h1>
				</div>
			</div>
		</div>

		<div class="load-more-container">
			<?php
			$wpj_job = new WPJ_Load_More_Posts(
				array(
					'post_type'       => 'post',
					'function_name'   => 'wpjobster_get_post_blog',
					'posts_per_page'  => 12,
					'container_class' => 'blog-posts'
				)
			);

			if($wpj_job->have_rows()){
				$wpj_job->show_posts_list_func();

				echo '<div class="ui hidden divider"></div>';

			}else{
				echo __('There are no posts!','wpjobster');
			}
			?>
		</div>
	</div>

	<?php
	$ret = ob_get_contents();
	ob_clean();

	return $ret;
}
// END BLOG POSTS //
