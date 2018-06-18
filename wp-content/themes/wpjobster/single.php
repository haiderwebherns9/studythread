<?php
get_header();

$wpjobster_adv_code_single_page_above_content = stripslashes(get_option('wpjobster_adv_code_single_page_above_content'));

if(!empty($wpjobster_adv_code_single_page_above_content)):
	echo '<div class="full_width_a_div">';
	echo $wpjobster_adv_code_single_page_above_content;
	echo '</div>';
endif;
?>

<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
	<div class="ui divider hidden"></div>

	<div id="content-full" class="">
		<div class="blog_post ui segment">
			<h1 class="heading-title"><?php the_title() ?></h1>
			<div class="blog_thumbnail"><?php the_post_thumbnail('blog_thumbnail_big'); ?></div>
			<div class="blog_post_content ui segment overflow-visible">
				<div class="padding-cnt">
					<?php the_content(); ?>
				</div>
				<div class="extra content center">
					<i class="calendar icon"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="ui divider hidden"></div>

<?php endwhile; endif;

get_footer(); ?>
