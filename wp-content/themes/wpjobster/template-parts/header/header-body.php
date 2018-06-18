<?php
global $wpdb, $wp_rewrite, $wp_query, $current_user, $is_profile_pg, $site_url_localized, $no_header_footer;

update_user_lat_and_long();
wpjobster_timezone_change();
?>

<div class="ui sidebar inverted vertical left menu">
	<?php wpj_semantic_sidebar_left(); ?>
</div>

<div class="ui sidebar vertical right inverted menu">
	<?php wpj_semantic_sidebar_right(); ?>
</div>

<!--[if IE ]><div class="pusher ie"><![endif]-->
<!--[if !IE]>-->
<div class="pusher">
<!--<![endif]-->

	<?php include_once(get_template_directory()."/images/svg/evil-icons-custom.svg"); ?>

	<?php if ($no_header_footer == false) { ?>

		<?php echo wpj_header_mobile_menu(); ?>

		<div class="background-top-menu">
			<?php if ( current_user_can('administrator') ){ ?>
					<div class="wrapper-menu-top user-admin">
						<div class="cf main">
							<div class="ui two column stackable grid">
								<div class="four wide column column-no-padding">
									<div class="logo_holder">
										<a href="<?php echo home_url('/'); ?>"><img id="logo" src="<?php echo wpj_header_logo(); ?>" /></a>
									</div>
								</div>
								<div class="twelve wide column column-no-padding">
									<div class="nh-right">

										<?php echo wpj_header_secondary_menu(); ?>

										<div class="nh-search">
											<div class="nh-search-container">
												<?php wpjobster_display_top_search_form(); ?>
											</div>
										</div>

										<?php
										echo wpj_header_language_selector();
										echo wpj_header_currency_selector();
										echo wpj_header_right_part();
										?>
									</div>
								</div>
							</div>
							<?php echo wpj_header_main_menu(); ?>
						</div>
					</div>
				<?php }else { ?>
					<div class="wrapper-menu-top">
						<div class="cf main">
							<div class="ui two column stackable grid">
								<div class="four wide column column-no-padding">
									<div class="logo_holder">
										<a href="<?php echo home_url('/'); ?>"><img id="logo" src="<?php echo wpj_header_logo(); ?>" /></a>
									</div>
								</div>
								<div class="twelve wide column column-no-padding">
									<div class="nh-right">

										<?php echo wpj_header_secondary_menu(); ?>

										<div class="nh-search">
											<div class="nh-search-container">
												<?php wpjobster_display_top_search_form(); ?>
											</div>
										</div>

										<?php
										echo wpj_header_language_selector();
										echo wpj_header_currency_selector();
										echo wpj_header_right_part();
										?>
									</div>
								</div>
							</div>
							<?php echo wpj_header_main_menu(); ?>
						</div>
					</div>
				<?php } ?>
		</div>
	<?php } ?>

	<div class="cf <?php echo get_field('full_width') ? 'main-full' : 'main'; ?> <?php echo ($no_header_footer == true) ? 'widget-cnt' : '';  ?>">
