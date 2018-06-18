<?php

// GENERAL FUNCTIONS
function wpjobster_theme_bullet($rn = '') {
	global $menu_admin_wpjobster_theme_bull;
	$menu_admin_wpjobster_theme_bull = '<a href="#" class="tltp_cls" title="'.$rn.'"><img src="'.get_template_directory_uri() . '/images/qu_icon.png" /></a>';
	echo $menu_admin_wpjobster_theme_bull;
}

function wpjobster_disp_spcl_cst_pic($pic) {
	return '<img src="'.get_template_directory_uri().'/images/'.$pic.'" /> ';
}

add_action('admin_menu', 'wpjobster_admin_menu');
function wpjobster_admin_menu() {

	$icn        = get_template_directory_uri()."/images/settings39.png";
	$capability = 'manage_options';

	global $sr, $ub, $es, $gs, $ls, $pg, $ps, $pm, $sg, $ss, $subscription, $tracking, $tm, $ul, $ur, $wr;

	add_menu_page(__('Jobster Theme'), __('Jobster','wpjobster'), $capability,"PT1_admin_mnu", array( $sr, 'wpjobster_totalsales' ), $icn, 0);
	add_submenu_page("PT1_admin_mnu", __('Summary','wpjobster'), wpjobster_disp_spcl_cst_pic('overview_icon.png'). __('Summary','wpjobster'),$capability, 'PT1_admin_mnu', array( $sr, 'wpjobster_totalsales' ) );
	add_submenu_page("PT1_admin_mnu", __('General Settings','wpjobster'), wpjobster_disp_spcl_cst_pic('setup_icon.png'). __('General Settings','wpjobster'),$capability, "general-options", array ( $gs, 'wpjobster_general_options' ));
	add_submenu_page("PT1_admin_mnu", __('Layout Settings','wpjobster'), wpjobster_disp_spcl_cst_pic('layout_icon.png'). __('Layout Settings','wpjobster'),$capability, "layout-settings", array( $ls, 'wpjobster_layout_settings' ));
	add_submenu_page("PT1_admin_mnu", __('Email Settings','wpjobster'), wpjobster_disp_spcl_cst_pic('email_icon.png').__('Email Settings','wpjobster'),$capability, 'email-settings', array( $es, 'wpjobster_email_settings' ));

	add_submenu_page("PT1_admin_mnu", __('SMS Settings','wpjobster'), wpjobster_disp_spcl_cst_pic('email_icon.png').__('SMS Settings','wpjobster'),$capability, 'sms-settings', array( $ss, 'wpjobster_sms_settings'));
	add_submenu_page("PT1_admin_mnu", __('SMS Gateways','wpjobster'), wpjobster_disp_spcl_cst_pic('email_icon.png').__('SMS Gateways','wpjobster'),$capability, 'sms-gateways', array( $sg, 'wpjobster_sms_gateways'));

	add_submenu_page("PT1_admin_mnu", __('Pricing Settings','wpjobster'), wpjobster_disp_spcl_cst_pic('dollar_icon.png').__('Pricing Settings','wpjobster'),$capability, 'pricing-settings', array( $ps, 'wpjobster_pricing_options' ));
	add_submenu_page("PT1_admin_mnu", __('Payment Gateways','wpjobster'), wpjobster_disp_spcl_cst_pic('gateway_icon.png').__('Payment Gateways','wpjobster'),$capability, 'payment-methods', array( $pg, 'wpjobster_payment_methods' ));
	add_submenu_page('PT1_admin_mnu', __('Withdrawal Requests','wpjobster'), wpjobster_disp_spcl_cst_pic('wallet_icon.png').__('Withdrawal Requests','wpjobster'),$capability, 'withdraw-req', array( $wr, 'wpjobster_withdrawals'));
	add_submenu_page('PT1_admin_mnu', __('User Balances','wpjobster'), wpjobster_disp_spcl_cst_pic('bal_icon.png').__('User Balances','wpjobster'),$capability, 'User-Balances', 'wpjobster_user_balances');
	add_submenu_page('PT1_admin_mnu', __('User Badges','wpjobster'), wpjobster_disp_spcl_cst_pic('badge1.png').__('User Badges','wpjobster'),$capability, 'user_badges',array( $ub, 'wpjobster_user_badges' ));
	add_submenu_page('PT1_admin_mnu', __('User Levels','wpjobster'), wpjobster_disp_spcl_cst_pic('bdg1.png').__('User Levels','wpjobster'),$capability, 'user_levels', array( $ul, 'wpjobster_user_levels' ));
	add_submenu_page("PT1_admin_mnu",__('Subscriptions','wpjobster'),  wpjobster_disp_spcl_cst_pic('bdg1.png').__('Subscriptions','wpjobster'),$capability,'subscriptions', array( $subscription, 'wpjobster_subscription'));
	add_submenu_page("PT1_admin_mnu", __('Transactions','wpjobster'), wpjobster_disp_spcl_cst_pic('list_icon.png'). __('Transactions','wpjobster'),$capability, 'trans-site', 'wpjobster_hist_trans');
	add_submenu_page('PT1_admin_mnu', __('Orders','wpjobster'), wpjobster_disp_spcl_cst_pic('orders_icon.png'). __('Orders','wpjobster'),$capability, 'order-stats', 'wpjobster_orders_m');
	add_submenu_page('PT1_admin_mnu', __('User Reviews','wpjobster'), wpjobster_disp_spcl_cst_pic('review_icon.png'). __('User Reviews','wpjobster'),$capability, 'usrrev', array( $ur, 'wpjobster_user_reviews_scr'));
	add_submenu_page('PT1_admin_mnu', __('Private Messages','wpjobster'), wpjobster_disp_spcl_cst_pic('mess_icon.png'). __('Private Messages','wpjobster'),$capability, 'privmess', array( $pm, 'wpjobster_private_messages_scr'));
	add_submenu_page('PT1_admin_mnu', __('Transaction Messages','wpjobster'), wpjobster_disp_spcl_cst_pic('trans_icon.png'). __('Transaction Messages','wpjobster'),$capability, 'chatmess', array( $tm, 'wpjobster_chat_messages_scr' ));
	add_submenu_page("PT1_admin_mnu", __('Tracking','wpjobster'), wpjobster_disp_spcl_cst_pic('track_icon.png'). __('Tracking','wpjobster'),$capability, 'track-tools', array( $tracking, 'wpjobster_tracking_tools'));
	add_submenu_page("PT1_admin_mnu", __('Information','wpjobster'), wpjobster_disp_spcl_cst_pic('info_icon.png'). __('Information','wpjobster'),$capability, 'info-stuff', 'wpjobster_information');

	do_action('wpjobster_new_admin_options_menu');
}

add_action('admin_bar_menu', 'wpjobster_admin_bar_menu', 35);
function wpjobster_admin_bar_menu( $admin_bar ) {
	$admin_bar->add_menu( array(
		'id'    => 'PT1_admin_mnu',
		'title' => __( 'Jobster', 'wpjobster' ),
		'href'  => get_admin_url () . 'admin.php?page=PT1_admin_mnu',
		'meta'  => array(
			'title' => __( 'Jobster', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'general-options',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'General Settings', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=general-options',
		'meta'   => array(
			'title' => __( 'General Settings', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'layout-settings',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Layout Settings', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=layout-settings',
		'meta'   => array(
			'title' => __( 'Layout Settings', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'email-settings',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Email Settings', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=email-settings',
		'meta'   => array(
			'title' => __( 'Email Settings', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'sms-settings',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'SMS Settings', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=sms-settings',
		'meta'   => array(
			'title' => __( 'SMS Settings', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'sms-gateways',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'SMS Gateways', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=sms-gateways',
		'meta'   => array(
			'title' => __( 'SMS Gateways', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'pricing-settings',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Pricing Settings', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=pricing-settings',
		'meta'   => array(
			'title' => __( 'Pricing Settings', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'payment-methods',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Payment Gateways', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=payment-methods',
		'meta'   => array(
			'title' => __( 'Payment Gateways', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'withdraw-req',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Withdrawal Requests', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=withdraw-req',
		'meta'   => array(
			'title' => __( 'Withdrawal Requests', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'User-Balances',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'User Balances', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=User-Balances',
		'meta'   => array(
			'title' => __( 'User Balances', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'user_badges',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'User Badges', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=user_badges',
		'meta'   => array(
			'title' => __( 'User Badges', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'user_levels',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'User Levels', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=user_levels',
		'meta'   => array(
			'title' => __( 'User Levels', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'subscriptions',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Subscriptions', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=subscriptions',
		'meta'   => array(
			'title' => __( 'Subscriptions', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'trans-site',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Transactions', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=trans-site',
		'meta'   => array(
			'title' => __( 'Transactions', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'order-stats',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Orders', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=order-stats',
		'meta'   => array(
			'title' => __( 'Orders', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'usrrev',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'User Reviews', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=usrrev',
		'meta'   => array(
			'title' => __( 'User Reviews', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'privmess',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Private Messages', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=privmess',
		'meta'   => array(
			'title' => __( 'Private Messages', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'chatmess',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Transaction Messages', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=chatmess',
		'meta'   => array(
			'title' => __( 'Transaction Messages', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'track-tools',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Tracking', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=track-tools',
		'meta'   => array(
			'title' => __( 'Tracking', 'wpjobster' )
		),
	));
	$admin_bar->add_menu( array(
		'id'     => 'info-stuff',
		'parent' => 'PT1_admin_mnu',
		'title'  => __( 'Information', 'wpjobster' ),
		'href'   => get_admin_url () . 'admin.php?page=info-stuff',
		'meta'   => array(
			'title' => __( 'Information', 'wpjobster' )
		),
	));
}


global $menu_admin_wpjobster_theme_bull;
$menu_admin_wpjobster_theme_bull = '<img src="'.get_template_directory_uri() . '/images/qu_icon.png" />';

// FUNCTIONS
include_once get_template_directory() . '/includes/admin-menu/admin-settings.php';
include_once get_template_directory() . '/includes/admin-menu/general-settings.php';
include_once get_template_directory() . '/includes/admin-menu/summary-settings.php';
include_once get_template_directory() . '/includes/admin-menu/layout-settings.php';
include_once get_template_directory() . '/includes/admin-menu/email-settings.php';
include_once get_template_directory() . '/includes/admin-menu/sms-settings.php';
include_once get_template_directory() . '/includes/admin-menu/sms-gateways.php';
include_once get_template_directory() . '/includes/admin-menu/pricing-settings.php';
include_once get_template_directory() . '/includes/admin-menu/withdrawal-requests.php';
include_once get_template_directory() . '/includes/admin-menu/user-badges.php';
include_once get_template_directory() . '/includes/admin-menu/user-levels.php';
include_once get_template_directory() . '/includes/admin-menu/subscription.php';
include_once get_template_directory() . '/includes/admin-menu/user-reviews.php';
include_once get_template_directory() . '/includes/admin-menu/transaction-messages.php';
include_once get_template_directory() . '/includes/admin-menu/private-messages.php';
include_once get_template_directory() . '/includes/admin-menu/tracking.php';
include_once get_template_directory() . '/includes/admin-menu/payment-gateways.php';


// VIEWS
get_template_part('template-parts/admin-menu/html', 'general-settings');
get_template_part('template-parts/admin-menu/html', 'layout-settings');
get_template_part('template-parts/admin-menu/html', 'email-settings');
get_template_part('template-parts/admin-menu/html', 'sms-settings');
get_template_part('template-parts/admin-menu/html', 'sms-gateways');
get_template_part('template-parts/admin-menu/html', 'pricing-settings');
get_template_part('template-parts/admin-menu/html', 'withdrawal-requests');
get_template_part('template-parts/admin-menu/html', 'user-balances');
get_template_part('template-parts/admin-menu/html', 'user-badges');
get_template_part('template-parts/admin-menu/html', 'user-levels');
get_template_part('template-parts/admin-menu/html', 'subscription');
get_template_part('template-parts/admin-menu/html', 'transactions');
get_template_part('template-parts/admin-menu/html', 'reviews');
get_template_part('template-parts/admin-menu/html', 'private-messages');
get_template_part('template-parts/admin-menu/html', 'transaction-messages');
get_template_part('template-parts/admin-menu/html', 'tracking');
get_template_part('template-parts/admin-menu/html', 'information');
get_template_part('template-parts/admin-menu/html', 'orders');
