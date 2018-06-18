<?php

wpj_purchase_this_start_session();

wpj_purchase_this_redirects();

$extra = wpj_purchase_this_job_extra();
$main_amount = $extra['main_amount'];

$vars = wpj_purchase_this_get_vars();
foreach ($vars as $key => $value) {
	$$key = $value;
}

if ( isset( $_POST['pck_price_val'] ) && $_POST['pck_deliv_val'] ) {
	update_post_meta( $pid, 'price', $_POST['pck_price_val'] );
	update_post_meta( $pid, 'max_days', $_POST['pck_deliv_val'] );
}

$selected = wpj_purchase_this_get_currency();

get_header();
?>

<div id="content-full-ov" data-currency="<?php echo strtoupper( $selected ); ?>">
	<div class="ui segment ">
		<div class="ui two column stackable grid">
			<div class="twelve wide column">
				<div class="purchase-title">
					<h1><i class="payment icon buy"></i><?php echo __("Review and Choose Payment Method", 'wpjobster'); ?></h1>
				</div>
			</div>

			<div class="four wide column right aligned">
				<div class="currency-switch-purchase-job">
					<?php global $wpjobster_currencies_array; if (count($wpjobster_currencies_array) > 1) { ?>

						<?php display_currency_select_secondary(); ?>

					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<?php
		$payment_instructions_title = get_field("payment_instructions_title", "options");
		$payment_instructions_text = get_field("payment_instructions_text", "options");
		if ($payment_instructions_title && $payment_instructions_text) {
	?>
		<div class="ui segment">
			<div class="notice">
				<h2 class="heading-subtitle heading-icon noborder info-icon"><?php echo $payment_instructions_title; ?></h2>
				<br>
				<?php echo $payment_instructions_text; ?>
			</div>
		</div>
	<?php } ?>

	<?php do_action( 'wpj_purchase_this_after_title', $post->post_author, $payment_type ); ?>

	<div class="ui segment bottom-margin">
		<div class="ui two column stackable grid">

			<?php do_action( 'wpjobster_before_message_purchase_gig_job' ); ?>

			<div class="three wide column">
				<div class="job-avatar">
					<img width="100" height="100" class="round-avatar" src="<?php echo wpjobster_get_first_post_image($pid, 101, 101); ?>" />
				</div>
			</div>


			<div class="nine wide column">
				<div class="job-title-purchase">
					<h3><?php wpj_purchase_this_job_title(); ?></h3>
				</div>
				<div class="payment-job-categories">
				<?php wpj_purchase_this_job_categories(); ?>
				</div>
			</div>
			<div class="four wide column">
				<div class="job-price-purchase payment-main-item" data-mainamount="<?php echo $main_amount; ?>">
				<?php wpj_purchase_this_job_price(); ?>
				</div>
			</div>
		</div>

		<?php do_action( "wpjobster_check_custom_input", $pid ); ?>
		<?php wpj_purchase_this_extra_fast(); ?>

		<div class="ui two column stackable grid">

			<?php wpj_purchase_this_extra_job_add();

			if( wpj_purchase_this_shipping() ){
				if ( get_option('wpjobster_enable_shipping') !='no' ) { ?>
					<div class="ui fitted divider"></div>
					<div class="sixteen wide column">
						<?php _e( 'Shipping:', 'wpjobster' ); ?>
						<span class="payment-item-price"><?php echo wpjobster_get_show_price( wpj_purchase_this_shipping() ); ?></span>
					</div>
				<?php }
			}

			if( wpj_purchase_this_processings_fees() ){
				if ( get_option('wpjobster_enable_buyer_processing_fees') != 'disabled' ) { ?>
					<div class="ui fitted divider"></div>
					<div class="sixteen wide column">
						<?php if ( get_option( 'wpjobster_enable_buyer_processing_fees' ) == 'percent' ) {
							echo sprintf( __( 'Processing Fees (%s&#37;):', 'wpjobster' ), get_option( 'wpjobster_buyer_processing_fees_percent' ) );
						} else {
							echo __( 'Processing Fees:', 'wpjobster' );
						} ?>
						<span class="payment-item-price"><?php echo wpjobster_get_show_price( wpj_purchase_this_processings_fees() ); ?></span>
					</div>
				<?php }
			}

			if( wpj_purchase_this_tax_job() ){ ?>
				<div class="ui fitted divider"></div>
				<div class="sixteen wide column">
					<?php
					$country_code = user( $uid, 'country_code' );
					$wpjobster_tax_percent = wpjobster_get_tax( $country_code );
					echo sprintf( __( 'Tax (%s&#37;)', 'wpjobster' ), $wpjobster_tax_percent ) . ':'; ?>
					<span class="payment-item-price" id="showtax"><?php echo wpjobster_get_show_price( wpj_purchase_this_tax_job() ); ?></span>
				</div>
			<?php }

			$wpj_purchase_this_total_price = wpj_purchase_this_total_price();

			do_action( 'list_after_tax_price', $wpj_purchase_this_total_price['total_orig'], 'purchase_this' ); ?>

			<div class="twelve wide column payment-gateways">

				<?php wpj_purchase_this_payment_methods(); ?>

			</div>

			<div class="four wide column payment-total">
				<div class="total-payment-purchase">
					<?php
					echo __( 'Total:','wpjobster' );
					echo " <span id='showtotal'> " . wpjobster_get_show_price( $wpj_purchase_this_total_price['total_filtered'], 2, true ) . "</span>";
					?>
				</div>
			</div>

		</div>

		<div class="payment-gateway-popup"></div>

		<div class="ui two column stackable grid secure-shop">
			<div class="three wide column secure-shop icon">
				<img src="<?php echo get_template_directory_uri() . '/images/ssl-secure.png'; ?>" alt="SSL Icon" width="111" height="142">
			</div>
			<div class="twthirteen wide column secure-shop">
				<div class="secure-encryption">
					<p class="ssl-title"><?php _e( "Secure Shopping", "wpjobster" ); ?></p>
					<p class="ssl-description"><?php _e( "128 bit data encryption", "wpjobster" ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
