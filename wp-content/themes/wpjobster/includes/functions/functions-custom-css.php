<?php
function wpj_custom_css(){

	$primary_color = get_theme_mod( 'primary_color' );
	if ( ! $primary_color ) {
		$primary_color = '#83C124';
	}

	$secondary_color =  get_theme_mod( 'secondary_color' );
	if ( ! $secondary_color ) {
		$secondary_color = '#2d5767';
	}

	do_action('custom_colors_panel');
	$primary_color = apply_filters( 'primary_color', $primary_color );
	$secondary_color = apply_filters( 'secondary_color', $secondary_color );

	if ( get_option( 'wpjobster_set_original_colours' ) != 'done' ) {
		$pc_orig = get_field("primary_color", "options");
		$sc_orig = get_field("secondary_color", "options");

		set_theme_mod( 'primary_color', $pc_orig );
		set_theme_mod( 'secondary_color', $sc_orig );

		$primary_color = apply_filters( 'primary_color', $pc_orig );
		$secondary_color = apply_filters( 'secondary_color', $sc_orig );

		update_option( 'wpjobster_set_original_colours', 'done' );
	} ?>

	<style>
	/* GENERAL COLORS */
		/* Background */
		.account-statistics, /* my account graph */
		.wrapper-graph-dropdown a.graph-link .icon, /* my account graph arrow */
		.nh-user-balance, /* top user menu balance */
		.heading-title.fancy-underline:before, .heading-title.fancy-underline-after:after, /* title underline homepage */
		#suggest_job_btn, /* new request button */
		.level-badge-title:after, /* level page line */
		.how-it-works-title:after, .level-comission-title:after, /* line on level and how it wordks page */
		.unread-label, /* messages and notifications circle */
		.green-list li:before, /* single job bullets */
		.uploadifive-queue-item .progress-bar, /* upload progress bar */
		.new-footer-cols ul li a:hover::before, /* footer hover bullets */
		span.balance /* witdraw success message */
		{
			background-color: <?php echo $primary_color; ?> !important;
			border-color: <?php echo $primary_color; ?> !important;
		}

		/* Color */
		.request-cat a, /* request category */
		.nh-submenu a:hover, /* dropdown menu */
		.ui.segments.middle-menu ul li a:hover, ul.menu.top-user li a:hover, /* main menu */
		.ui.card>.content .card-job-price, .ui.cards>.card>.content .card-job-price, /* thumnail job price */
		.ui.card > .content > a.header:hover, .ui.cards > .card > .content > a.header:hover, /* thumnail job title */
		.footer-new a:hover, /* footer hover links */
		.greengreen, /* default color class */
		.uploadifive-queue-item span.filename, /* upload file name */
		.ui.header.wpj-title-icon > .icon,
		.packages-sidebar .pck-sidebar-compare-packages, /* sidebar compare packages */
		.packages-sidebar .pck-price-sidebar /* price sidebar package */
		{
			color: <?php echo $primary_color; ?> !important;
		}

		/* Border */
		.amount_section a:hover,
		.order-extras ul li .amount_section a:hover,
		.cool-message-input.focus,
		.packages-sidebar.selected {
			border: 1px solid <?php echo $primary_color; ?> !important;
			outline-color: <?php echo $primary_color; ?> !important;
			border-color: <?php echo $primary_color; ?> !important;
			-webkit-box-shadow: 0 0 2px <?php echo $primary_color; ?> !important;
			box-shadow: 0 0 2px <?php echo $primary_color; ?> !important;
		}
		.ui.action.input:not([class*="left action"]) > .ui.input > input:focus {
			border-right-color: <?php echo $primary_color; ?> !important;
		}
		:focus{
			outline-color: <?php echo $primary_color; ?> !important;
		}
		input:focus,
		select:focus,
		textarea:focus {
			border: 1px solid <?php echo $primary_color; ?> !important;
			box-shadow: 0 0 0px <?php echo $primary_color; ?> !important;
		}

		/* Icons */
		/* to be removed */
		.buy-badge-title h1:before,
		.my-favorites-title i.icon.heart:before,
		.secure-info-badge:before,
		.single-job-audio-title h2:before,
		.single-job-title-description h2:before,
		.single-job-preview-title h2:before,
		.sigle-job-additional-title h2:before,
		ul.single-job-rate-delivery .queue-order:before,
		.single-job-delivery-time:before,
		i.icon.announcement:before,
		i.icon.users:before,
		i.icon.payment:before,
		i.icon.edit:before,
		i.icon.write:before,
		.seller-notifications-title h1:before,
		.bookmark-icon-smaller:after,
		.level-badge-title:before,
		.single-job-feedback-title h2:before,
		.link-to-pm i,
		.packages-sidebar i.wait.icon,
		.packages-sidebar i.refresh.icon
		{
			color: <?php echo $primary_color; ?> !important;
		}

		/* Customizer */
		.customize-partial-edit-shortcuts-shown .primary {
			background-color: <?php echo $primary_color; ?> !important;
			border-color: <?php echo $primary_color; ?> !important;
		}

		.customize-partial-edit-shortcuts-shown .secondary {
			background-color: <?php echo $secondary_color; ?> !important;
			border-color: <?php echo $secondary_color; ?> !important;
		}

		/* Register button from slider */
		.wpjobster-button.register-link{
			border-color: <?php echo $primary_color; ?> !important;
		}
		.wpjobster-button.register-link:hover{
			background-color: <?php echo $primary_color; ?> !important;
		}

		/* All notifications */
		<?php list($r, $g, $b) = sscanf($primary_color, "#%02x%02x%02x"); ?>
		.nh-notifications-dropdown li:hover,
		.wpj-all-notifications-style li:hover,
		.wpj-all-notifications-style .wpj-notification-unread:hover,
		.box-checked {
			background: <?php echo "rgba( $r, $g, $b, .1 ) !important"; ?>;
		}

		.wpj-all-notifications-style .wpj-notification-unread a,
		.wpj-all-notifications-style li.wpj-notification-read:hover a,
		.pm-holder.pm-unread-message .link-to-pm,
		.pm-holder.pm-unread-message a.link-to-pm {
			border-color: <?php echo $primary_color; ?>;
		}

	/* END GENERAL COLORS */

	/* HEADER OPTIONS */

		/* Logo Height & Spacing */
		<?php $logo_spacing = get_theme_mod( 'logo_spacing' ); ?>
		.logo_holder{
			padding: <?php echo $logo_spacing['top'].' '.$logo_spacing['right'].' '.$logo_spacing['bottom'].' '.$logo_spacing['left']; ?>;
		}
		.logo_holder img{
			height: <?php echo get_theme_mod( 'logo_height' ).'px'; ?>;
		}

	/* END HEADER OPTIONS */

	/* TYPOGRAPHY OPTIONS */

		/* Site font family */
		<?php
		$primary_font = get_theme_mod( 'primary_font_family' );
		$secondary_font = get_theme_mod( 'secondary_font_family' );

		$primary_font_family = $primary_font['font-family'];
		$secondary_font_family = $secondary_font['font-family'];

		$primary_font_style = substr( strtolower( $primary_font['variant'] ), -6 ) == strtolower( 'italic' ) ? 'italic' : '';
		$secondary_font_style = substr( strtolower( $secondary_font['variant'] ), -6 ) == strtolower( 'italic' ) ? 'italic' : '';

		$primary_font_weight = ( 1 === preg_match( '~[0-9]~', $primary_font['variant'] ) ) ? substr( $primary_font['variant'], -3 ) : $primary_font['variant'];
		$secondary_font_weight = ( 1 === preg_match( '~[0-9]~', $secondary_font['variant'] ) ) ? substr( $secondary_font['variant'], -3) : $secondary_font['variant'];

		if ( $primary_font_style ) {
			$primary_font_weight = ( 1 === preg_match( '~[0-9]~', $primary_font['variant'] ) ) ? substr( $primary_font['variant'], -9, -6 ) : $primary_font['variant'];
		} else {
			$primary_font_style = 'normal';
		}

		if ( $secondary_font_style ) {
			$secondary_font_weight = ( 1 === preg_match( '~[0-9]~', $secondary_font['variant'] ) ) ? substr( $secondary_font['variant'], -9, -6 ) : $secondary_font['variant'];
		} else {
			$secondary_font_style = 'normal';
		}

		if( ! $primary_font_weight || $primary_font_weight == 'regular' || $primary_font_weight == 'italic' ) $primary_font_weight = 400;
		if( ! $secondary_font_weight || $secondary_font_weight == 'regular' || $secondary_font_weight == 'italic' ) $secondary_font_weight = 400;
		?>

		body, h1, h2, h3, h4, h5, h6, a,
		.ui.button, .ui.cards > .card > .content > .header, .ui.card > .content > .header,
		input, select, textarea
		{
			font-family: <?php if( $primary_font_family ) echo '"' . $primary_font_family . '", '; if( $secondary_font_family ) echo '"' . $secondary_font_family . '", '; echo "sans-serif"; ?>;
		}

		/* Place Holders */
		::-webkit-input-placeholder {
			font-family: <?php if( $primary_font_family ) echo '"' . $primary_font_family . '", '; if( $secondary_font_family ) echo '"' . $secondary_font_family . '", '; echo "sans-serif"; ?>;
		}
		:-moz-placeholder {
			font-family: <?php if( $primary_font_family ) echo '"' . $primary_font_family . '", '; if( $secondary_font_family ) echo '"' . $secondary_font_family . '", '; echo "sans-serif"; ?>;
		}
		::-moz-placeholder {
			font-family: <?php if( $primary_font_family ) echo '"' . $primary_font_family . '", '; if( $secondary_font_family ) echo '"' . $secondary_font_family . '", '; echo "sans-serif"; ?>;
		}
		:-ms-input-placeholder {
			font-family: <?php if( $primary_font_family ) echo '"' . $primary_font_family . '", '; if( $secondary_font_family ) echo '"' . $secondary_font_family . '", '; echo "sans-serif"; ?>;
		}


		body{
			font-weight: <?php if( $primary_font_weight ) echo $primary_font_weight; else echo $secondary_font_weight; ?>;
			font-style: <?php if( $primary_font_style ) echo $primary_font_style; else echo $secondary_font_style; ?>;
		}

		/* Elements font size */
		h1{ font-size: <?php echo get_theme_mod( 'h1_font_size' ) . 'px' ?>; }
		h2{ font-size: <?php echo get_theme_mod( 'h2_font_size' ) . 'px' ?>; }
		h3{ font-size: <?php echo get_theme_mod( 'h3_font_size' ) . 'px' ?>; }
		h4{ font-size: <?php echo get_theme_mod( 'h4_font_size' ) . 'px' ?>; }
		h5{ font-size: <?php echo get_theme_mod( 'h5_font_size' ) . 'px' ?>; }
		h6{ font-size: <?php echo get_theme_mod( 'h6_font_size' ) . 'px' ?>; }
		p { font-size: <?php echo get_theme_mod( 'p_font_size' )  . 'px' ?>; }

		i.dropdown.icon, a:not(#wpadminbar a){
			font-size: <?php echo get_theme_mod( 'anchor_font_size' ) . 'px' ?>;
		}

	/* END TYPOGRAPHY OPTIONS */

	/* FOOTER */

		/* Background color */
		.footer-new{ background-color: <?php echo get_theme_mod( 'footer_background' ); ?>; }

		/* Scroll to Top */
		.scrollToTop{
			background-color: <?php echo get_theme_mod( 'scroll_to_top_background' ) ?>;
			border: <?php echo get_theme_mod( 'scroll_to_top_border_size' ) . 'px ' . get_theme_mod( 'scroll_to_top_border_style' ) . ' ' . get_theme_mod( 'scroll_to_top_border_color' ); ?>;
			color: <?php echo get_theme_mod( 'scroll_icon_color' )['link']; ?> !important;
		}

		.scrollToTop:hover{
			color: <?php echo get_theme_mod( 'scroll_icon_color' )['hover']; ?> !important;
		}

	/* END FOOTER */

	</style>

	<?php
}

// Remove admin menu bar space
add_action('get_header', 'remove_admin_login_header');
function remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
