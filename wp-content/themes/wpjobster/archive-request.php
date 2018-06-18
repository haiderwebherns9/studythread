<?php get_header(); ?>

<div id="content-full-ov" class="">
	<div class="ui basic notpadded segment">
		<?php
		$my_order = wpjobster_get_current_order_by_thing();
		?>

		<div class="ui two column stackable grid">
			<div class="eight wide column">
				<h1 class="ui header wpj-title-icon">
					<i class="users icon"></i>
					<?php _e('Recent Requests', 'wpjobster'); ?>
				</h1>
			</div>

			<div class="eight wide column">
				<div class="stackable-buttons right">

						<?php _e( "Filter requests by:", 'wpjobster' ); ?>

						<a class="ui white button <?php echo ( $my_order == "auto" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'auto' ); ?>"><?php _e( "Auto", "wpjobster" ); ?></a>

						<a class="ui white button <?php echo ( $my_order == "old" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'old'); ?>" ><?php  _e("Old","wpjobster"); ?></a>

						<a class="ui white button <?php echo ( $my_order == "new" ? 'active' : "" ); ?>" href="<?php echo wpjobster_filter_switch_link_from_home_page( 'new'); ?>"><?php _e( "New", "wpjobster" ); ?></a>

				</div>
			</div>
		</div>
	</div>

	<?php get_template_part('template-parts/posts/content', 'archive-request'); ?>
</div>

<?php get_footer(); ?>
