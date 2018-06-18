<?php
// News Listings [news_listing]
if (!function_exists('news_listings_s')) {
	function news_listings_s( $atts ) {
		$a = shortcode_atts( array(
			'articles_no' => 10
		), $atts );

		$articles_no = $a['articles_no'];
		?>

		<div class="cf relative right p30t">
			<a href="<?php echo get_site_url(); ?>/news/" class="ui fluid button"><?php _e("View all news",'wpjobster'); ?></a>
		</div>

		<div class="cf cb relative">
			<div class="news-carousel owl-carousel owl-theme">
				<?php
				query_posts('post_type=news&posts_per_page=' . $articles_no);
				while(have_posts()){
					the_post();
					?>

					<div class="ui card">

						<a class="image" href="<?php the_permalink() ?>">
							<div class="card-image-helper">
								<?php
								if( get_featured_image( "news_slider" ) ){
									echo get_featured_image( "news_slider" );
								}else{
									echo '<img class="image_class no_news_pic" src="' . get_template_directory_uri() . '/images/nopic-big.jpg" width="100" height="100" />';
								}
								?>
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
add_shortcode( 'news_listing', 'news_listings_s' );
