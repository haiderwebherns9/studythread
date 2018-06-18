<?php get_header();

//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//------------------------------------------------

$wpjobster_adv_code_single_page_above_content = stripslashes(get_option('wpjobster_adv_code_single_page_above_content'));

if(!empty($wpjobster_adv_code_single_page_above_content)):
	echo '<div class="full_width_a_div">';
	echo $wpjobster_adv_code_single_page_above_content;
	echo '</div>';
endif;

if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
	<div id="content">
		<div class="ui segment">
			<h1 class="page_title"><?php the_title() ?></h1>
			<?php the_content(); ?>
		</div>
	</div>

	<?php endwhile; ?>
<?php endif; ?>

<div class="the_sidebar">
	<?php if ( is_active_sidebar( 'page-widgets-area' ) ) : ?>
		<div class="ui segment smallpadding-cnt">
			<div id="page-widgets-area" class="primary-sidebar widget-area" role="complementary">
				<ul>
				<?php dynamic_sidebar( 'page-widgets-area' ); ?>
				</ul>
			</div>
		</div>
		<div class="ui hidden divider"></div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
