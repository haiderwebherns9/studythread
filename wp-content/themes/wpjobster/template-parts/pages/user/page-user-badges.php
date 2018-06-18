<?php get_header();
$vars = wpj_user_badges_vars();
$uid = $vars['uid']; ?>

<div id="content-full-ov" data-currency="<?php echo wpjobster_get_currency();  ?>">

	<div class="ui segment white-cnt heading-cnt cf">
		<div class="buy-badge-title">
			<h1 class="heading-title"><?php echo __("Buy Badge", 'wpjobster'); ?></h1>
		</div>
	</div>

	<?php if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
		<form class="" action="<?php echo get_bloginfo('url'); ?>" method="get">
			<div class="ui segment cf white-cnt box_content">
			<div class="ui two column stackable grid">
			<div class="sixteen wide column">
				<div class="payment-cnt">
					<?php
					if($vars['f_err']){
						echo '<p>' . $vars['f_err'] . '</p>';
					}
					?>

					<ul class="payment-items-list">
						<li class="">

							<div class="payment-main-item-content cf">
								<div class="payment-title-categories cf">
									<?php if (wpjobster_user_eligible_for_first_badge($uid)) { ?>
										<h3>
											<?php _e("First Badge", "wpjobster"); ?>
										</h3>
										<div class="payment-job-categories">
											<?php _e("You can buy your first badge!", "wpjobster"); ?>
										</div>
									<?php } elseif (wpjobster_user_eligible_for_second_badge($uid)) { ?>
										<h3>
											<?php _e("Second Badge", "wpjobster"); ?>
										</h3>
										<div class="payment-job-categories">
											<?php _e("You are now eligible to buy your second badge!", "wpjobster"); ?>
										</div>
									<?php } ?>

								</div>
							</div>
						</li>
						<input type="hidden" name="jb_action" value="badges" />

						<li class="cf extra-item">
							<?php _e("Price", "wpjobster"); ?>

							<span class="payment-item-price">
							<?php if (wpjobster_user_eligible_for_first_badge($uid)) {
								echo wpjobster_get_show_price(get_option('wpjobster_first_badge_price'));
							} elseif (wpjobster_user_eligible_for_second_badge($uid)) {
								echo wpjobster_get_show_price(get_option('wpjobster_second_badge_price'));
							} ?>
							</span>

						</li>
					</ul> <!-- payment-items-list -->
				</div>
				</div>
				</div>

				<?php if ( get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
					<div class="ui two column stackable grid payment-buttons cf">
						<div class="sixteen wide column payment-buttons">
							<button name="method" value="balance" class="ui white button pay_featured_button"><?php _e('Pay with Balance','wpjobster'); ?></button>
						</div>
					</div>
				<?php } ?>
			</div>
		</form>
	<?php } else { ?>
		<div class="ui segment">
			<?php echo __( 'You need to enable credits to access this page!', 'wpjobster' ); ?>
		</div>
	<?php } ?>

</div>

<?php get_footer(); ?>
