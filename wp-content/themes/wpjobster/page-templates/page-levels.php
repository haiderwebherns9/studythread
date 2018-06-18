<?php get_header();
/*
Template Name: Levels
*/
?>

<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
	<div class="ui hidden divider"></div>
	<div class="ui segment">
		<div class="sixteen wide column">
			<h1 class="level-badge-title">
				<?php the_title(); ?>
			</h1>
		</div>

		<div class="sixteen wide column">
			<div class="ui hidden divider"></div>
			<div class="levels-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

	<div class="ui segment">
		<div class="ui two column stackable grid border-bottom">

			<div class="sixteen wide column">
				<div class="level-number">1</div>
			</div>

			<div class="eight wide column">

				<div class="level-one-rookie">
					<?php if(get_field('level_1_image')){ ?>
						<img src="<?php the_field('level_1_image'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/image1.jpg" alt="server">
					<?php } ?>

					<div class="level-icon">
						<?php if(get_field('user_level_1_icon', 'options')){ ?>
						<img src="<?php the_field('user_level_1_icon', 'options'); ?>" width="255" height="255" />
						<?php } else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/level-1-icon.png" alt="server">
						<?php } ?>
					</div>
				</div>

			</div>

			<div class="eight wide column">
				<div class="level-description">

					<h2><?php the_field('level_1_title'); ?></h2>

					<?php the_field('level_1_description'); ?>

				</div>
			</div>

		</div>

		<div class="ui two column stackable grid border-bottom">

			<div class="sixteen wide column">
				<div class="level-number">2</div>
			</div>

			<div class="eight wide column">
				<div class="level-description level-two">

					<h2><?php the_field('level_2_title'); ?></h2>

					<?php the_field('level_2_description'); ?>

				</div>
			</div>

			<div class="eight wide column">
				<div class="level-two-master">

					<?php if(get_field('level_2_image')){ ?>
						<img src="<?php the_field('level_2_image'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/image2.jpg" alt="server">
					<?php } ?>
					<div class="level-icon">
						<?php if(get_field('user_level_2_icon', 'options')){ ?>
						<img src="<?php the_field('user_level_2_icon', 'options'); ?>" width="255" height="255" />
						<?php } else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/level-2-icon.png" alt="server">
						<?php } ?>
					</div>

				</div>
			</div>

		</div>

		<div class="ui two column stackable grid">

			<div class="sixteen wide column">
				<div class="level-number">3</div>
			</div>

			<div class="eight wide column">

				<div class="level-three-expert">

					<?php if(get_field('level_3_image')){ ?>
						<img src="<?php the_field('level_3_image'); ?>" width="255" height="255" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/image3.jpg" alt="server">
					<?php } ?>
					<div class="level-icon">
						<?php if(get_field('user_level_3_icon', 'options')){ ?>
						<img src="<?php the_field('user_level_3_icon', 'options'); ?>" width="255" height="255" />
						<?php } else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/level-3-icon.png" alt="server">
						<?php } ?>
					</div>
				</div>

			</div>

			<div class="eight wide column">

				<div class="level-description">

					<h2><?php the_field('level_3_title'); ?></h2>
					<?php the_field('level_3_description'); ?>

				</div>

			</div>

		</div>

	</div>

	<div class="ui segment">
		<div class="ui hidden divider"></div>

		<div class="ui two column stackable grid">
			<div class="four wide column">
				<h2 class="level-comission-title"><?php the_field('level_bottom_title'); ?></h2>
			</div>

			<div class="twelve wide column">
				<?php the_field('level_bottom_description'); ?>
			</div>
		</div>

		<div class="ui hidden divider"></div>
	</div>

	<?php if ( get_option( 'wpjobster_enable_site_fee' ) == 'flexible' ) { ?>
		<div class="ui segment">
			<h2 class="heading-subtitle"><?php _e("Commissions per level", "wpjobster"); ?></h2>
			<?php do_shortcode('[show_commissions_table]'); ?>
		</div>
	<?php } ?>

	<?php endwhile; ?>

<?php endif; ?>

<div class="ui hidden divider"></div>

<?php get_footer(); ?>
