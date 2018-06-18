<?php
/*
	Plugin Name: WPJobster Account Segregation
	Plugin URL: http://wpjobster.com/
	Description: This plugin extends Jobster Theme and allows you to segregate the accounts between buyers and sellers.
	Version: 2.0.1
	Author: WPJobster
	Author URI: http://wpjobster.com/
*/

// INCLUDE CLASS FOR CREATING LICENSE TAB
if( !class_exists( 'WPJ_Plugin_License' ) ) {
	include( plugin_dir_path( __FILE__ ) . 'updater/plugin-updater.php' );
}

$wpj_account_segregation_license = new WPJ_Plugin_License(
	array(
		'file'       => __FILE__,
		'item_name'  => 'Account Segregation',
		'version'    => '2.0.1',
		'author'     => 'WPJobster',
		'api_url'    => 'http://wpjobster.com',
		'short_slug' => 'account_segregation',
		'full_slug'  => 'wpjobster-account-segregation',
		'textdomain' => 'wpjobster-account-segregation',
	)
);

// INCLUDE STYLES AND SCRIPTS
add_action('admin_enqueue_scripts', 'wpjobster_as_load_scripts');
add_action('wp_enqueue_scripts', 'wpjobster_as_load_scripts');
function wpjobster_as_load_scripts() {
	wp_enqueue_style( 'main-styles', plugins_url( 'style.css', __FILE__ ), array() );
}

// ADD SHORTCODES
add_shortcode( 'wpj_as_seller_account_verification' , 'wpjobster_account_confirmation_function' );

// FIRST RUN
include 'wpjobster-as-first-run.php';

// Run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'md_options_install');
function md_options_install() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}
// Helper functions
function wpj_as_slug() {
	// declare this in one place just in case we're gonna change it later
	return 'wpjobster-account-segregation';
}

function wpj_as_menu_url() {
	return menu_page_url( wpj_as_slug(), false );
}

function wpj_as_dir_url() {
	return plugin_dir_url( __FILE__ );
}

if ( ! function_exists( 'is_account_segregation' ) ) {
	function is_account_segregation() {
		if ( is_plugin_active( 'wpjobster-account-segregation/wpjobster-account-segregation.php' ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'wpjobster_user_type' ) ) {
	function wpjobster_user_type( $uid = false ) {
		if ( is_account_segregation() ) {
			if ( ! $uid ) {
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
			}
			$wpjobster_user_type = get_user_meta( $uid, 'wpjobster_user_type', true );
			return $wpjobster_user_type;
		} else {
			return false;
		}
	}
}

// Load translation files if exists
add_action( 'plugins_loaded', 'wpjobster_load_plugin_textdomain' );
function wpjobster_load_plugin_textdomain() {
	load_plugin_textdomain( 'wpjobster-account-segregation', false, plugin_dir_url(__FILE__) . '/languages' );
}

// Update options when the plugin is activated
register_activation_hook( __FILE__, 'wpjobster_update_account_segregation_options' );
function wpjobster_update_account_segregation_options() {
	$check_update = get_option('wpj_as_update_110');

	if ( $check_update != 'done' ) {
		update_option('wpj_as_update_110', 'done');
		update_option('wpj_as_display_on_register', 'yes');
		update_option('wpj_as_auto_approve_seller', 'yes');
		update_option('wpj_as_hide_menu', 'yes');
		update_option('wpj_as_default_user_type', 'buyer');
	}
}

function wpj_as_get_approval_users(){
	$approvalUsers = array();

	$users = get_users( array( 'fields' => array( 'display_name', 'ID' ) ) );

	foreach($users as $user_id){
		$user_type_temp = get_user_meta ( $user_id->ID, 'wpjobster_temp_user_type', true);
		$user_rejected = get_user_meta ( $user_id->ID, 'wpj_as_rejected', true);

		if( get_option( 'wpj_as_enable_account_verification' ) == 'yes' ){
			$linkedin = get_user_meta( $user_id->ID, 'linkedin_profile_url', true );
			$cv_attachments = get_user_meta( $user_id->ID, 'cv_file', true );
			if($user_type_temp && !$user_rejected && ( $linkedin || $cv_attachments ) ){
				$approvalUsers[] = array(
					'ID' => $user_id->ID,
					'name' => $user_id->display_name,
					'type' => $user_type_temp
				);
			}
		} else {
			if( $user_type_temp && !$user_rejected ){
				$approvalUsers[] = array(
					'ID' => $user_id->ID,
					'name' => $user_id->display_name,
					'type' => $user_type_temp
				);
			}
		}
	}

	return $approvalUsers;
}

// Admin notifications about new users approval
add_action( 'admin_notices', 'new_user_type_success' );
function new_user_type_success() {

	$approvalUsers = wpj_as_get_approval_users();

	if($approvalUsers){
		$approval_number = count($approvalUsers);
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo sprintf( _n( '%d new user to aprove.', '%d new users to aprove.', $approval_number, 'wpjobster-account-segregation'), $approval_number ) . ' <a href="' . wpj_as_menu_url() . '#users-approval">' . __( 'Go', 'wpjobster-account-segregation' ) . '</a>'; ?></p>
		</div>
		<?php
	}
}

// Add settings link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpjobster_account_segregation_action_links' );
function wpjobster_account_segregation_action_links ( $links ) {
	$mylinks = array(
		'<a href="' . menu_page_url( wpj_as_slug(), false ) . '">' . __('Settings', 'wpjobster-account-segregation') . '</a>',
	);
return array_merge( $links, $mylinks );
}

// Create custom plugin settings menu
add_action('admin_menu', 'wpjobster_account_segregation_create_menu', 11);
function wpjobster_account_segregation_create_menu() {
	add_submenu_page( 'PT1_admin_mnu', __('WPJobster Account Segregation','wpjobster-account-segregation'), '<img style="width: 20px; height: 20px;" src="' . wpj_as_dir_url() . 'images/fa-user.png"> ' . __('Account Segregation','wpjobster-account-segregation'), 'manage_options', wpj_as_slug(), 'wpjobster_account_segregation_plugin_settings_page');
}

add_action('admin_bar_menu', 'wpjobster_account_segregation_in_admin_bar', 999);
function wpjobster_account_segregation_in_admin_bar( $wp_admin_bar ) {
	$wp_admin_bar->add_node( array(
		'id'     => 'wpjobster-account-segregation',
		'parent' => 'PT1_admin_mnu',
		'title' => __( 'Account Segregation', 'wpjobster-account-segregation' ),
		'href'  => get_admin_url () . 'admin.php?page=wpjobster-account-segregation',
		'meta'  => array(
			'title' => __( 'Account Segregation', 'wpjobster-account-segregation' )
		),
	));
}

// Add email templates
add_filter( 'wpjobster_admin_menu_email_templates', 'wpjobster_account_segregation_email_templates', 10, 1 );
function wpjobster_account_segregation_email_templates( $reasons ) {
	$reasons['account_segregation'] = array(
		"title" => "Account Segregation",
		"items" => array(
			"as_new_buyer" => array(
				"title"       => __( "New Buyer", "wpjobster" ),
				"description" =>
					"Create a new template for newly registered buyers when account seg. is installed/enabled.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_buyer_admin" => array(
				"title"       => __( "New Buyer (Admin)", "wpjobster" ),
				"description" =>
					"
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##username##, <br /> ##user_email##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_not_approved" => array(
				"title"       => __( "New Seller Not Approved", "wpjobster" ),
				"description" =>
					"User gets this when he registers as a seller but is pending admin review of linkedin & resume.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_not_approved_admin" => array(
				"title"       => __( "New Seller Not Approved (Admin)", "wpjobster" ),
				"description" =>
					"Admin gets this to advise a new seller has registered and admin must now review Linkedin and Resume details and either approve or reject.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##username##, <br /> ##user_email##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_approved" => array(
				"title"       => __( "New Seller Approved", "wpjobster" ),
				"description" =>
					"User gets this when he has been approved by admin as a seller.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_approved_admin" => array(
				"title"       => __( "New Seller Approved (Admin)", "wpjobster" ),
				"description" =>
					"Admin gets this to advise that a new seller has been approved on the platform.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##username##, <br /> ##user_email##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_rejected" => array(
				"title"       => __( "New Seller Rejected", "wpjobster" ),
				"description" =>
					"User gets this to advise they have been rejected seller privileges on the platform but will still be able to access the platform as a buyer.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
			"as_new_seller_rejected_admin" => array(
				"title"       => __( "New Seller Rejected (Admin)", "wpjobster" ),
				"description" =>
					"Admin gets this to advise that a seller has been rejected but will still be able to access the platform as a buyer.
					<br /><br /> Available shortcodes:
					<br /><br /> <strong>##username##, <br /> ##user_email##, <br /> ##your_site_name##, <br /> ##your_site_url##</strong>",
			),
		),
	);
	return $reasons;
}

function wpjobster_account_segregation_plugin_settings_page($user_id) {
	if(isset($_GET['approved'])){
		if(isset($_GET['userID']) && isset($_GET['type'])){
			$user_id = $_GET['userID'];
			$type = $_GET['type'];
			update_user_meta($user_id, 'wpjobster_user_type', $type);
			delete_user_meta($user_id, 'wpjobster_temp_user_type');
			update_user_meta( $user_id, 'account_confirmation', 1 );
			delete_user_meta( $user_id, 'wpj_as_rejected' );

			if ( $type == "seller" ) {
				wpjobster_send_email_allinone_translated( 'as_new_seller_approved', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_approved', $user_id );
				wpjobster_send_email_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
			}

			if(isset($_SERVER['HTTP_REFERER'])) {
				wp_redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}
	if(isset($_GET['rejected'])){
		if(isset($_GET['userID'])){
			$user_id = $_GET['userID'];
			$temp_type = get_user_meta( $user_id, 'wpjobster_temp_user_type', true );

			update_user_meta( $user_id, 'wpj_as_rejected', 1 );
			delete_user_meta( $user_id, 'account_confirmation' );
			delete_user_meta( $user_id, 'wpj_as_become_seller_click' );

			if ( $temp_type == "seller" ) {
				wpjobster_send_email_allinone_translated( 'as_new_seller_rejected', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_rejected', $user_id );
				wpjobster_send_email_allinone_translated( 'as_new_seller_rejected_admin', 'admin', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_rejected_admin', 'admin', $user_id );
			}

			if(isset($_SERVER['HTTP_REFERER'])) {
				wp_redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}
	if(isset($_POST['wpjobster_save_plugin_settings'])) {
		update_option( 'wpj_as_display_on_register'  , trim( $_POST['wpj_as_display_on_register'] ) );
		update_option( 'wpj_as_auto_approve_seller'  , trim( $_POST['wpj_as_auto_approve_seller'] ) );
		update_option( 'wpj_as_hide_menu'            , trim( $_POST['wpj_as_hide_menu'] ) );
		update_option( 'wpj_as_subscriptions'        , trim( $_POST['wpj_as_subscriptions'] ) );
		update_option( 'wpj_as_filter_search_results', trim( $_POST['wpj_as_filter_search_results'] ) );
		update_option( 'wpj_as_default_user_type'    , trim( $_POST['wpj_as_default_user_type'] ) );

		update_option( 'wpj_as_seller_register_redirection',  trim( $_POST['wpj_as_seller_register_redirection'] ) );
		update_option( 'wpj_as_buyer_register_redirection' ,  trim( $_POST['wpj_as_buyer_register_redirection'] ) );
		update_option( 'wpj_as_seller_logged_in_homepage'  ,  trim( $_POST['wpj_as_seller_logged_in_homepage'] ) );
		update_option( 'wpj_as_buyer_logged_in_homepage'   ,  trim( $_POST['wpj_as_buyer_logged_in_homepage'] ) );

		if( $_POST['wpj_as_display_on_register'] == 'yes' && $_POST['wpj_as_subscriptions'] == 'yes' ){
			echo '<div class="error fade"><p>'.__('You can\'t show the registration form because the subscription has the user type feature!','wpjobster-account-segregation').'</p></div>';
		}

		echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster-account-segregation').'</p></div>';
	}

	if(isset($_POST['wpjobster_save_plugin_verification_settings'])) {
		update_option('wpj_as_enable_account_verification', trim( $_POST['wpj_as_enable_account_verification'] ));
		update_option('wpj_as_linkedin_client_id', trim( $_POST['wpj_as_linkedin_client_id'] ));
		update_option('wpj_as_linkedin_client_secret', trim( $_POST['wpj_as_linkedin_client_secret'] ));
		update_option('wpj_as_account_verification_page', trim( $_POST['wpj_as_account_verification_page'] ));

		echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster-account-segregation').'</p></div>';
	}

	if(isset($_POST['wpjobster_save_plugin_tools_settings'])) {
		$user_type = trim($_POST['wpj_no_user_type']);
		$site_users = get_users( array( 'fields' => array( 'ID' ) ) );
		foreach ( $site_users as $user ) {
			$uid=$user->ID;
			$wpjobsterusertype = get_user_meta($uid, 'wpjobster_user_type', true);
			if( !( $wpjobsterusertype ) && $wpjobsterusertype == '' ){
				update_user_meta($uid,'wpjobster_user_type',$user_type);

				if( $user_type != 'seller' ){
					deactivate_all_user_jobs( $uid );
				}
			}
		}
		echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster-account-segregation').'</p></div>';
	}

	$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
	if($wpj_as_subscriptions == 'yes'){
		update_option('wpj_as_display_on_register', 'no');
		update_option('wpj_as_default_user_type', 'buyer');
	}

	$wpj_as_filter_search_results = get_option('wpj_as_filter_search_results') ? get_option('wpj_as_filter_search_results') : 'no';

	$wpj_as_display_on_register = get_option('wpj_as_display_on_register');
	$wpj_as_auto_approve_seller = get_option('wpj_as_auto_approve_seller');
	$wpj_as_hide_menu = get_option('wpj_as_hide_menu');
	$wpj_as_default_user_type = get_option('wpj_as_default_user_type');

	$pages = new WP_Query(array("post_type"=>"page","posts_per_page"=>-1));
	$arr_pages[''] = __( 'Default', 'wpjobster' );
	while($pages->have_posts()){
		$pages->the_post();
		$arr_pages[get_the_ID()]=get_the_title();;
	}
	?>

	<div class="wrap">
	<h2 class="my_title_class_sitemile"><?php _e( 'Jobster - Account Segregation', 'wpjobster-account-segregation' ); ?></h2>
	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e("General Settings",'wpjobster-account-segregation'); ?></a></li>
			<li><a href="#sellerverification"><?php _e("Seller Verification Settings",'wpjobster-account-segregation'); ?></a></li>
			<li><a href="#tools"><?php _e("Tools",'wpjobster-account-segregation'); ?></a></li>
			<?php do_action('wpj_account_segregation_add_tab_name'); ?>
		</ul>
		<div id="tabs1">
			<form method="post" action="<?php echo wpj_as_menu_url(); ?>">
				<table width="100%" class="sitemile-table">
					<tr>
						<td></td>
						<td><h2><?php _e("General Settings", "wpjobster-account-segregation"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Choose if you want to add an input to the registration form in order to let the users select their preferred user type.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Buyer/seller options on registration form:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_display_on_register" id="wpj_as_display_on_register">
								<option <?php if(isset($wpj_as_display_on_register) && $wpj_as_display_on_register == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Show', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_display_on_register) && $wpj_as_display_on_register == "no") echo "selected=selected"; ?> value="no"><?php _e('Hide', 'wpjobster-account-segregation'); ?></option>
							</select>
							<i class="add to calendar icon"></i>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("If no user type is selected, or if the form is not displayed for the user to choose, this will be the default user type.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Default user type:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_default_user_type" id="wpj_as_default_user_type">
								<option <?php if(isset($wpj_as_default_user_type) && $wpj_as_default_user_type == "") echo "selected=selected"; ?> value=""><?php _e('None', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_default_user_type) && $wpj_as_default_user_type == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_default_user_type) && $wpj_as_default_user_type == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select no if you want to manually approve each seller user type.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Auto approve seller:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_auto_approve_seller" id="wpj_as_auto_approve_seller">
								<option <?php if(isset($wpj_as_auto_approve_seller) && $wpj_as_auto_approve_seller == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Yes', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_auto_approve_seller) && $wpj_as_auto_approve_seller == "no") echo "selected=selected"; ?> value="no"><?php _e('No', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select yes if you want to hide pages intended for sellers, like sales, my jobs or post new job.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Hide menu entries for buyers:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_hide_menu" id="wpj_as_hide_menu">
								<option <?php if(isset($wpj_as_hide_menu) && $wpj_as_hide_menu == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Yes', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_hide_menu) && $wpj_as_hide_menu == "no") echo "selected=selected"; ?> value="no"><?php _e('No', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select yes if you want to add user type feature to subscriptions page.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Add user type feature to subscriptions:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_subscriptions" id="wpj_as_subscriptions">
								<option <?php if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Yes', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "no") echo "selected=selected"; ?> value="no"><?php _e('No', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select yes if you want to include buyers in search results.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Include buyers in search results:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_filter_search_results" id="wpj_as_filter_search_results">
								<option <?php if(isset($wpj_as_filter_search_results) && $wpj_as_filter_search_results == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Yes', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_filter_search_results) && $wpj_as_filter_search_results == "no") echo "selected=selected"; ?> value="no"><?php _e('No', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><h2><?php _e("Redirect Settings", "wpjobster-account-segregation"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('To redirect the seller to a custom page after registration.', 'wpjobster-account-segregation')); ?></td>
						<td width="240"><?php _e('Seller register redirection:','wpjobster-account-segregation'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpj_as_seller_register_redirection','', ' class="select2" '); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('To redirect the buyer to a custom page after registration.', 'wpjobster-account-segregation')); ?></td>
						<td width="240"><?php _e('Buyer register redirection:','wpjobster-account-segregation'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpj_as_buyer_register_redirection','', ' class="select2" '); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('', 'wpjobster-account-segregation')); ?></td>
						<td width="240"><?php _e('Logged-in homepage for seller:','wpjobster-account-segregation'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpj_as_seller_logged_in_homepage','', ' class="select2" '); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('', 'wpjobster-account-segregation')); ?></td>
						<td width="240"><?php _e('Logged-in homepage for buyer:','wpjobster-account-segregation'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpj_as_buyer_logged_in_homepage','', ' class="select2" '); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"></td>
						<td width="20%"></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_plugin_settings" value="<?php _e('Save Options','wpjobster-account-segregation'); ?>"/></td>
					</tr>
				</table>
				<?php $approvalUsers = wpj_as_get_approval_users();
				if($approvalUsers){ ?>
					<table width="100%" class="sitemile-table" id="users-approval">
						<tr>
							<td></td>
							<td><h2><?php _e("Users Approval", "wpjobster-account-segregation"); ?></h2></td>
							<td></td>
							<td></td>
						</tr>
						<?php foreach($approvalUsers as $user){ ?>
							<tr>
								<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
								<td width="20%"><?php echo $user["name"] . " - <strong>" . strtoupper($user["type"]) . "</strong>"; ?></td>
								<td>
									<a href="<?php echo wpj_as_menu_url() . '&approved&userID='.$user["ID"].'&type='.$user["type"]; ?>"><input type="button" name="print" value="<?php _e('Approve','wpjobster-account-segregation'); ?>" class="btn btn-large btn-primary" /></a>
									<a href="<?php echo wpj_as_menu_url() . '&rejected&userID='.$user["ID"].'&type='.$user["type"]; ?>"><input type="button" name="print" value="<?php _e('Reject','wpjobster-account-segregation'); ?>" class="btn btn-large btn-primary" /></a>
								</td>
								<td>
									<?php $cv_attachments = get_user_meta( $user['ID'], 'cv_file', true );
									$attachments = explode(",", $cv_attachments);
									$cv_attachments = array_filter($attachments, function($value) { return $value !== ''; });
									if( $cv_attachments ){
										$cv_attachments = implode( ",", $cv_attachments );
									}

									if (isset($cv_attachments) && $cv_attachments && !empty($cv_attachments)) {
										$attachments = explode(",", $cv_attachments);
										foreach ($attachments as $attachment) {
											if($attachment != ""){
												echo '<a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . '" download>' . get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span><br>';
											}
										}
									}

									if ( get_user_meta( $user['ID'], 'linkedin_profile_url', true ) ) {
										$linkedin = get_user_meta( $user['ID'], 'linkedin_profile_url', true );
										echo '<a href="' . $linkedin->publicProfileUrl . '" target="_blank">' . __( 'Linkedin', 'wpjobster-account-segregation' ) . '</a> - ' . $linkedin->formattedName . ' (' . $linkedin->location->name . ', ' . strtoupper( $linkedin->location->country->code ) . ')';
									} ?>
								</td>
							</tr>
						<?php } ?>
					</table>
				<?php } ?>
			</form>
		</div>
		<div id="sellerverification">
			<form method="post" action="<?php echo wpj_as_menu_url(); ?>&active_tab=sellerverification">
				<table width="100%" class="sitemile-table">
					<tr>
						<td></td>
						<td><h2><?php _e("Seller Verification Settings", "wpjobster-account-segregation"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select yes if you want to add confirmation page after user register as seller.", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Require CV or Linkedin:','wpjobster-account-segregation'); ?></td>
						<td>
						<?php $wpj_as_enable_account_verification = get_option( 'wpj_as_enable_account_verification' );
						$wpj_as_account_verification_page = get_option( 'wpj_as_account_verification_page' ); ?>
							<select name="wpj_as_enable_account_verification" id="wpj_as_enable_account_verification">
								<option <?php if(isset($wpj_as_enable_account_verification) && $wpj_as_enable_account_verification == "yes") echo "selected=selected"; ?> value="yes"><?php _e('Yes', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_as_enable_account_verification) && $wpj_as_enable_account_verification == "no") echo "selected=selected"; ?> value="no"><?php _e('No', 'wpjobster-account-segregation'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Insert your linkedin client ID", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Linkedin Client ID:','wpjobster-account-segregation'); ?></td>
						<td><input type="text" name="wpj_as_linkedin_client_id" id="wpj_as_linkedin_client_id" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpj_as_linkedin_client_id')); ?>" /></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Insert your linkedin client Secret", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Linkedin Client Secret:','wpjobster-account-segregation'); ?></td>
						<td><input type="text" name="wpj_as_linkedin_client_secret" id="wpj_as_linkedin_client_secret" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpj_as_linkedin_client_secret')); ?>" /></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select a page where shorcode ['wpjobster_account_confirmation'] is insered", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Seller verification page:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_as_account_verification_page" id="wpj_as_account_verification_page">
								<?php $pages = get_pages();
								foreach ($pages as $page) { ?>
									<option <?php if(isset($wpj_as_account_verification_page) && $wpj_as_account_verification_page == $page->ID) echo "selected=selected"; ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"></td>
						<td width="20%"></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_plugin_verification_settings" value="<?php _e('Save Options','wpjobster-account-segregation'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>
		<div id="tools">
			<form method="post" action="<?php echo wpj_as_menu_url(); ?>&active_tab=tools">
				<table width="100%" class="sitemile-table">
					<tr>
						<td></td>
						<td><h2><?php _e("Tools", "wpjobster-account-segregation"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __("", "wpjobster-account-segregation") ); ?></td>
						<td width="20%"><?php _e('Users with empty user type:','wpjobster-account-segregation'); ?></td>
						<td>
							<select name="wpj_no_user_type" id="wpj_no_user_type">
								<option <?php if(isset($wpj_no_user_type) && $wpj_no_user_type == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
								<option <?php if(isset($wpj_no_user_type) && $wpj_no_user_type == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
							</select>
							<input type="submit" class="button-secondary" name="wpjobster_save_plugin_tools_settings" value="<?php _e('Sync','wpjobster-account-segregation'); ?>"/>
						</td>
					</tr>
				</table>
			</form>
			<?php

			?>
		</div>
		<?php do_action('wpj_account_segregation_add_tab_content');  ?>
	</div>
	</div>
	<?php
}

// Admin user page input
add_action('show_user_profile', 'wpjboster_user_type_add_custom_user_profile_fields');
add_action('edit_user_profile', 'wpjboster_user_type_add_custom_user_profile_fields');
add_action('user_new_form', "wpjboster_user_type_add_custom_user_profile_fields");
function wpjboster_user_type_add_custom_user_profile_fields($user) {
	?>
	<h3><?php _e('WPJobster Account Segregation', 'wpjobster-account-segregation'); ?></h3>

	<table class="form-table">
		<tr>
			<th>
				<label for="wpjobster_user_type"><?php _e('WPJobster User Type', 'wpjobster-account-segregation'); ?>
				</label></th>
			<td>
				<select name="wpjobster_user_type" id="wpjobster_user_type">
					<option <?php if(is_object($user)){ echo (esc_attr(get_the_author_meta('wpjobster_user_type', $user->ID)) == '') ? "selected=selected" : ""; } ?> value=""><?php _e( 'None', 'wpjobster-account-segregation' ); ?></option>
					<option <?php if(is_object($user)){ echo (esc_attr(get_the_author_meta('wpjobster_user_type', $user->ID)) == 'buyer') ? "selected=selected" : ""; } ?> value="buyer"><?php _e( 'Buyer', 'wpjobster-account-segregation' ); ?></option>
					<option <?php if(is_object($user)){ echo (esc_attr(get_the_author_meta('wpjobster_user_type', $user->ID)) == 'seller') ? "selected=selected" : ""; } ?> value="seller"><?php _e( 'Seller', 'wpjobster-account-segregation' ); ?></option>
				</select>
				<span class="description"><?php _e('Please select buyer or seller.', 'wpjobster-account-segregation'); ?></span>
			</td>
		</tr>
	</table>
	<?php
}

//Hide custom offer for buyers
add_filter('display_or_hide_section_filter', 'hide_section_for_buyers', 10, 2);
function hide_section_for_buyers($oldVal, $uid = ''){
	if ( ! $uid ) {
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
	}
	
	$wpjobsterusertype = get_user_meta($uid, 'wpjobster_user_type', true);
	if($wpjobsterusertype == 'seller'){
		$display_custom_offer_button = true;
	}else{
		$display_custom_offer_button = false;
	}

	return $display_custom_offer_button;
}

//Subscription features settings
add_action('save_features_options_for_subscription', "wpjobster_user_type_save_subscription_page");
function wpjobster_user_type_save_subscription_page(){
	$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
	if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "yes"){
		update_option('wpjobster_user_type_for_subscriber_enabled',$_POST['wpjobster_user_type_for_subscriber_enabled']);

		update_option('wpjobster_subscription_user_type_level0',$_POST['wpjobster_subscription_user_type_level0']);
		update_option('wpjobster_subscription_user_type_level1',$_POST['wpjobster_subscription_user_type_level1']);
		update_option('wpjobster_subscription_user_type_level2',$_POST['wpjobster_subscription_user_type_level2']);
		update_option('wpjobster_subscription_user_type_level3',$_POST['wpjobster_subscription_user_type_level3']);
	}
}

add_action('add_features_options_for_subscription', "wpjobster_user_type_subscription_page");
function wpjobster_user_type_subscription_page($arr) {
	$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
	if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "yes"){
		$wpjobster_subscription_user_type_level0 = get_option('wpjobster_subscription_user_type_level0');
		$wpjobster_subscription_user_type_level1 = get_option('wpjobster_subscription_user_type_level1');
		$wpjobster_subscription_user_type_level2 = get_option('wpjobster_subscription_user_type_level2');
		$wpjobster_subscription_user_type_level3 = get_option('wpjobster_subscription_user_type_level3');
		?>
		<tr>
			<td valign=top width="22"><?php wpjobster_theme_bullet(__('User type for each level.', 'wpjobster-account-segregation')); ?></td>
			<td width="150" valign="top"><?php _e('User type', 'wpjobster-account-segregation'); ?>:</td>
			<td nowrap >
				<?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_user_type_for_subscriber_enabled', 'no'); ?>
				<select name="wpjobster_subscription_user_type_level0" id="wpjobster_subscription_user_type_level0">
					<option <?php if(isset($wpjobster_subscription_user_type_level0) && $wpjobster_subscription_user_type_level0 == "") echo "selected=selected"; ?> value=""><?php _e('None', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level0) && $wpjobster_subscription_user_type_level0 == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level0) && $wpjobster_subscription_user_type_level0 == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
				</select>
				<select name="wpjobster_subscription_user_type_level1" id="wpjobster_subscription_user_type_level1">
					<option <?php if(isset($wpjobster_subscription_user_type_level1) && $wpjobster_subscription_user_type_level1 == "") echo "selected=selected"; ?> value=""><?php _e('None', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level1) && $wpjobster_subscription_user_type_level1 == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level1) && $wpjobster_subscription_user_type_level1 == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
				</select>
				<select name="wpjobster_subscription_user_type_level2" id="wpjobster_subscription_user_type_level2">
					<option <?php if(isset($wpjobster_subscription_user_type_level2) && $wpjobster_subscription_user_type_level2 == "") echo "selected=selected"; ?> value=""><?php _e('None', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level2) && $wpjobster_subscription_user_type_level2 == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level2) && $wpjobster_subscription_user_type_level2 == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
				</select>
				<select name="wpjobster_subscription_user_type_level3" id="wpjobster_subscription_user_type_level3">
					<option <?php if(isset($wpjobster_subscription_user_type_level3) && $wpjobster_subscription_user_type_level3 == "") echo "selected=selected"; ?> value=""><?php _e('None', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level3) && $wpjobster_subscription_user_type_level3 == "buyer") echo "selected=selected"; ?> value="buyer"><?php _e('Buyer', 'wpjobster-account-segregation'); ?></option>
					<option <?php if(isset($wpjobster_subscription_user_type_level3) && $wpjobster_subscription_user_type_level3 == "seller") echo "selected=selected"; ?> value="seller"><?php _e('Seller', 'wpjobster-account-segregation'); ?></option>
				</select>
			</td>
		</tr><?php
	}
}

add_action('before_save_subscription','update_user_type_subscription', 10, 1);
function update_user_type_subscription($nm){
	if ( wpj_bool_option( 'wpj_as_subscriptions' ) && wpj_bool_option( 'wpjobster_user_type_for_subscriber_enabled' ) ) {
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$sub_user_type = $nm[2];
		update_user_meta($uid,'wpjobster_user_type',$sub_user_type);

		if( $sub_user_type != 'seller' ){
			deactivate_all_user_jobs( $uid, 'subscription_changed' );
		}
	}
}

add_action('cancel_subscription','cancel_subscription_fnc');
function cancel_subscription_fnc(){
	if ( wpj_bool_option( 'wpj_as_subscriptions' ) && wpj_bool_option( 'wpjobster_user_type_for_subscriber_enabled' ) ) {
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$wpjobster_subscription_user_type_level0 = get_option('wpjobster_subscription_user_type_level0');
		update_user_meta($uid,'wpjobster_user_type',$wpjobster_subscription_user_type_level0);

		if( $wpjobster_subscription_user_type_level0 != 'seller' ){
			deactivate_all_user_jobs( $uid, 'subscription_cancelled' );
		}
	}
}

add_action('add_item_in_array','add_item_in_array_fnc', 10, 2);
function add_item_in_array_fnc($param1, $key){
	$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
	if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "yes"){
		$param1->subscription_arr[$key]['user_type'] = get_option('wpjobster_subscription_user_type_'.$key);
	}
}

add_action('list_user_type','list_user_type_fnc', 10, 2);
function list_user_type_fnc($param1, $key){
	if ( wpj_bool_option( 'wpj_as_subscriptions' ) && wpj_bool_option( 'wpjobster_user_type_for_subscriber_enabled' ) ) {
		if($param1->subscription_arr[$key]['user_type']!='' ){ _e('User type', 'wpjobster-account-segregation'); ?>: <?php echo ucfirst($param1->subscription_arr[$key]['user_type']); ?><br />
		<?php }
	}
}

add_action('send_new_values','send_user_type_fnc', 10, 4);
function send_user_type_fnc($param1, $eligible_for_sub, $valued, $key){
	$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
	if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions == "yes"){ ?>

		<script>
		$( document ).ready(function() {
			jQuery('.radio-input').remove();
		});
		</script>

		<?php
		$user_type_key = $param1->subscription_arr[$key]['user_type'];
		echo '<input class="new-radio-input" '.$eligible_for_sub.' type="radio" id="'.$valued.'-'.$key.'" name="sub_id" value="'.$valued.'-'.$key.'-'.$user_type_key. '">';
	}
}

// Admin user page input update
add_action('personal_options_update', 'wpjboster_user_type_save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'wpjboster_user_type_save_custom_user_profile_fields');
function wpjboster_user_type_save_custom_user_profile_fields($user_id) {

	if (!current_user_can('edit_user', $user_id))
		return FALSE;

	if( $_POST['wpjobster_user_type'] != 'seller' ){
		deactivate_all_user_jobs( $user_id, 'downgraded_by_admin' );
	}

	update_user_meta($user_id, 'wpjobster_user_type', $_POST['wpjobster_user_type']);
}

// Add user notification if jobs deactivated
add_action( 'wpjobster_shopping_after_title', 'wpj_as_pending_approval_notice' );
add_action( 'wpjobster_my_account_after_title', 'wpj_as_pending_approval_notice' );
function wpj_as_pending_approval_notice( $uid ) {

	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$user_type_temp = get_user_meta( $uid, 'wpjobster_temp_user_type', true);

	if ( $user_type_temp == 'seller' ) {
	?>
		<div class="white-cnt padding-cnt center">
			<?php echo __( "You're almost done! Your seller application is under review.", 'wpjobster-account-segregation' ); ?>
		</div>
	<?php
	}
}

// Add user notification if jobs deactivated
add_action( 'wpjobster_shopping_after_title', 'wpj_as_subscriptions_notices' );
add_action( 'wpjobster_my_account_after_title', 'wpj_as_subscriptions_notices' );
function wpj_as_subscriptions_notices( $uid ) {

	if ( get_option( 'wpj_as_subscriptions' ) == 'yes' ) {
		$posts = get_posts( array(
			'post_type'      => 'job',
			'posts_per_page' => -1,
			'post_status'    => array( 'draft', 'publish', 'pending' ),
			'author'         => $uid,
			'meta_query'     => array(
				array(
					'key'     => 'active',
					'value'   => "0",
					'compare' => '=',
				),
				array(
					'key'     => 'deactivation_reason',
					'value'   => "",
					'compare' => '!=',
				),
			),
		) );

		$deactivation_reasons = array();
		foreach ( $posts as $post ) {
			array_push( $deactivation_reasons, get_post_meta( $post->ID, 'deactivation_reason', true ) );
		}

		if ( in_array( 'subscription_cancelled', $deactivation_reasons ) ) {
			?>
			<div class="white-cnt padding-cnt center red-cnt">
				<?php echo sprintf( __( 'Your jobs have been paused because your subscription was cancelled or failed to renew and you lost your seller status. Please <a href="%s">click here</a> in order to renew your subscription.', 'wpjobster-account-segregation' ), get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) ); ?>
			</div>
			<?php
			// to-do: opt out for this notification

		} elseif ( in_array( 'subscription_changed', $deactivation_reasons ) ) {
			?>
			<div class="white-cnt padding-cnt center red-cnt">
				<?php echo sprintf( __( 'Your jobs have been paused because your subscription was changed and you lost your seller status. Please <a href="%s">click here</a> in order to renew your subscription.', 'wpjobster-account-segregation' ), get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) ); ?>
			</div>
			<?php
			// to-do: opt out for this notification

		} elseif ( in_array( 'downgraded_by_admin', $deactivation_reasons ) ) {
			?>
			<div class="white-cnt padding-cnt center red-cnt">
				<?php _e( 'Your jobs have been paused because your account was downgraded to buyer.', 'wpjobster-account-segregation' ); ?>
			</div>
			<?php
			// to-do: opt out for this notification
		}
	} // if wpj_as_subscriptions
}

// Admin users table head
add_filter('manage_users_columns', 'new_wpjobstermodify_user_table');
function new_wpjobstermodify_user_table($column) {
	$column['wpjobster_user_type'] = 'WPJobster User Type';
	return $column;
}

// Admin users table cells
add_filter('manage_users_custom_column', 'new_wpjobstermodify_user_table_row', 10, 3);
function new_wpjobstermodify_user_table_row($val, $column_name, $user_id) {
	switch ($column_name) {
		case 'wpjobster_user_type' :
		      $type= get_the_author_meta('wpjobster_user_type', $user_id);
			  if($type=="seller"){
				  $type="Teacher";
			  } elseif($type=="buyer"){
				  $type="Parents/Students";
			  }
			return $type;
			break;
		default:
	}
	return $val;
}

// Add input to frontend register forms
add_action('register_form_below_email_field', 'wpjobster_user_type_add_custom_registration_form');
add_action('zm_ajax_login_register_below_email_field', 'wpjobster_user_type_add_custom_registration_form' );
if(get_option('wsl_settings_bouncer_profile_completion_hook_extra_fields') != 1){
	add_action('add_new_fields', 'wpjobster_user_type_add_custom_registration_form' );
}
function wpjobster_user_type_add_custom_registration_form(){
	$wpj_as_display_on_register = get_option('wpj_as_display_on_register');
	if(isset($wpj_as_display_on_register) && $wpj_as_display_on_register == "yes"){ ?>
		<div class="field">
			<select name="wpjobster_user_type" class="ui fluid selection dropdown register-segregate">
				<option value="" disabled selected><?php _e( 'User Type', 'wpjobster-account-segregation' ); ?></option>
				<option value="buyer"><?php _e( 'Parrent/Student', 'wpjobster-account-segregation' ); ?></option>
				<option value="seller"><?php _e( 'Teacher', 'wpjobster-account-segregation' ); ?></option>
			</select>
		</div>
<?php }
}

// Save input from frontend register forms
add_action('zm_ajax_login_after_successfull_registration', 'wpjboster_user_type_save_registration_form');
add_action('user_register', 'wpjboster_user_type_save_registration_form', 10, 1 );
function wpjboster_user_type_save_registration_form( $user_id ){
	$wpj_as_display_on_register = get_option('wpj_as_display_on_register');
	$wpj_as_default_user_type = get_option('wpj_as_default_user_type');
	$wpj_as_auto_approve_seller = get_option('wpj_as_auto_approve_seller');

	if(!isset($_POST['wpjobster_user_type']) || $_POST['wpjobster_user_type'] == ''){
		update_user_meta($user_id, 'wpjobster_user_type', $wpj_as_default_user_type);
	}
	elseif(isset($_POST['wpjobster_user_type']) && $_POST['wpjobster_user_type']=="buyer"){
		update_user_meta($user_id, 'wpjobster_user_type', $_POST['wpjobster_user_type']);
	}
	elseif(isset($_POST['wpjobster_user_type']) && $_POST['wpjobster_user_type']=="seller"){
		if(isset($wpj_as_auto_approve_seller) && $wpj_as_auto_approve_seller == "no"){
			update_user_meta($user_id, 'wpjobster_temp_user_type', $_POST['wpjobster_user_type']);
		}else{
			update_user_meta($user_id, 'wpjobster_user_type', $_POST['wpjobster_user_type']);
		}
	}
}

add_action( 'user_register', 'wpjboster_user_type_send_registration_emails' );
function wpjboster_user_type_send_registration_emails( $user_id ){
	$wpj_as_display_on_register = get_option('wpj_as_display_on_register');
	$wpj_as_default_user_type = get_option('wpj_as_default_user_type');
	$wpj_as_auto_approve_seller = get_option('wpj_as_auto_approve_seller');

	if ( ! isset( $_POST['wpjobster_user_type'] ) || $_POST['wpjobster_user_type'] == '' ) {
		if ( $wpj_as_default_user_type == 'seller' ) {
			wpjobster_send_email_allinone_translated( 'as_new_seller_approved', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_seller_approved', $user_id );
			wpjobster_send_email_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
		} else {
			wpjobster_send_email_allinone_translated( 'as_new_buyer', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_buyer', $user_id );
			wpjobster_send_email_allinone_translated( 'as_new_buyer_admin', 'admin', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_buyer_admin', 'admin', $user_id );
		}
	}

	elseif ( isset( $_POST['wpjobster_user_type'] ) && $_POST['wpjobster_user_type'] == "buyer" ) {
		wpjobster_send_email_allinone_translated( 'as_new_buyer', $user_id );
		wpjobster_send_sms_allinone_translated( 'as_new_buyer', $user_id );
		wpjobster_send_email_allinone_translated( 'as_new_buyer_admin', 'admin', $user_id );
		wpjobster_send_sms_allinone_translated( 'as_new_buyer_admin', 'admin', $user_id );
	}

	elseif ( isset( $_POST['wpjobster_user_type'] ) && $_POST['wpjobster_user_type'] == "seller" ) {
		if ( $wpj_as_auto_approve_seller == "no" ) {
			if ( get_option( 'wpj_as_enable_account_verification' ) != 'yes' ) {
				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved', $user_id );
				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $user_id );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $user_id );
			}
		} else {
			wpjobster_send_email_allinone_translated( 'as_new_seller_approved', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_seller_approved', $user_id );
			wpjobster_send_email_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
			wpjobster_send_sms_allinone_translated( 'as_new_seller_approved_admin', 'admin', $user_id );
		}
	}
}

// Restrict buyers from sellers pages
add_filter( 'template_redirect', 'wpj_as_restrict_buyers_from_sellers_pages' );
if ( ! function_exists( 'wpj_as_restrict_buyers_from_sellers_pages' ) ) {
	function wpj_as_restrict_buyers_from_sellers_pages() {
		if ( wpj_bool_option( 'wpj_as_hide_menu' ) ) {
			$current_user = wp_get_current_user();
			$uid = $current_user->ID;
			$wpjobsterusertype = get_user_meta($uid, 'wpjobster_user_type', true);

			global $post;
			$current_page = isset( $post->ID ) ? $post->ID : '';

			$not_allowed_pages = array(
				get_option( 'wpjobster_post_new_page_id' ),
				get_option( 'wpjobster_my_account_page_id' ),
			);

			if ( strtolower( $wpjobsterusertype ) != 'seller'
				&& in_array( $current_page, $not_allowed_pages )
			) {
				$shopping_id = get_option( 'wpjobster_my_account_shopping_page_id' );
				wp_redirect(get_permalink($shopping_id));
			}
		}
	}
}

// Hide subscription page for buyers
if (!function_exists('wpjobster_hide_subscription_page')) {
	function wpjobster_hide_subscription_page() {
		$wpj_as_hide_menu = get_option('wpj_as_hide_menu');
		if($wpj_as_hide_menu=="yes"){
			$wpj_as_subscriptions = get_option('wpj_as_subscriptions');
			if(isset($wpj_as_subscriptions) && $wpj_as_subscriptions != "yes"){
				if( is_page( get_option('wpjobster_subscriptions_page_id') )){
					if ( wpjobster_user_type() != 'seller' ) {
						wp_redirect(get_permalink(get_option('wpjobster_my_account_shopping_page_id')));
						exit();
					}
				}
			}
		}
	}
}
add_action( 'template_redirect', 'wpjobster_hide_subscription_page' );

if (!function_exists('wpjobster_dropdown_menu_list_filter')) {
	function wpjobster_dropdown_menu_list_filter($drop_down_user_menu) {
		if ( wpjobster_user_type() != 'seller' ) {
			unset( $drop_down_user_menu['jobs'] );
			unset( $drop_down_user_menu['sales'] );
			unset( $drop_down_user_menu['class_availability'] );
		}
		if ( wpjobster_user_type() != 'buyer' ) {
			unset( $drop_down_user_menu['my_schedule'] );
		}
		return $drop_down_user_menu;
	}
}
add_filter("wpjobster_dropdown_menu_list", "wpjobster_dropdown_menu_list_filter", 10, 1);

if ( ! function_exists( 'wpj_header_main_menu' ) ) {
	function wpj_header_main_menu(){ ?>
		<div class="ui segments middle-menu">
			<div class="ui segment">
				<?php

					if( isset ( $_GET['jb_action'] ) && (
						$_GET['jb_action'] == 'chat_box'
						|| $_GET['jb_action'] == 'purchase_this'
						|| $_GET['jb_action'] == 'feature_job'
						|| $_GET['jb_action'] == 'badges'
					) ){
						$jb_action_set = 1;
					}else{
						$jb_action_set = 0;
					}

					$pages = array(
						get_option( 'wpjobster_my_account_page_id', false ),
						get_option( 'wpjobster_my_account_shopping_page_id', false ),
						get_option( 'wpjobster_my_account_sales_page_id', false ),
						get_option( 'wpjobster_my_account_payments_page_id', false ),
						get_option( 'wpjobster_my_account_priv_mess_page_id', false ),
						get_option( 'wpjobster_my_account_personal_info_page_id', false ),
						get_option( 'wpjobster_my_account_reviews_page_id', false ),
						get_option( 'wpjobster_my_requests_page_id', false ),
						get_option( 'wpjobster_my_account_all_notifications_page_id', false ),
						get_option( 'wpjobster_my_favorites_page_id', false ),
					);

					if( is_page( $pages ) || $jb_action_set == 1 ) {
						if ( wpjobster_user_type() != 'seller' ) {
							wp_nav_menu(array(
								'theme_location' => 'wpjobster_header_buyer_account_menu',
								'container'      => '',
								'menu_class'     => 'menu categories-here auto_cols',
								'fallback_cb'    => 'link_to_menu_editor' )
							);
						}else{
							wp_nav_menu(array(
								'theme_location' => 'wpjobster_header_user_account_menu',
								'container'      => '',
								'menu_class'     => 'menu categories-here auto_cols',
								'fallback_cb'    => 'link_to_menu_editor' )
							);
						}
					} else {
						wp_nav_menu(array(
							'theme_location' => 'wpjobster_header_main_menu',
							'container'      => '',
							'menu_class'     => 'menu categories-here auto_cols',
							'fallback_cb'    => 'link_to_menu_editor' )
						);
					}
				?>
			</div>
		</div>
	<?php }
}

function wpjobster_account_confirmation_function(){
	$current_user = wp_get_current_user();

	$vc_inline = function_exists('wpj_vc_is_inline') ? wpj_vc_is_inline() : vc_is_inline();

	if ( ( get_option( 'wpj_as_enable_account_verification' ) == 'yes' && get_user_meta( $current_user->ID, 'account_confirmation', 'true' ) != 1 ) || $vc_inline ){ ?>

		<!-- CV UPLOAD !-->
		<div id="account-confirmation">
			<form method="POST">
				<div class="ui segment white-cnt heading-cnt">
					<h1 class="heading-title"><?php echo sprintf( __( 'Welcome %s!', 'wpjobster-account-segregation' ), $current_user->user_login ); ?></h1>
				</div>
				<div class="ui segment white-cnt padding-cnt">
					<p class="as_ac_2emb"><?php echo __( "You're almost done! As a last step before becoming a seller, we need some information about your experience.","wpjobster-account-segregation" ); ?></p>

					<p><strong><?php echo __( 'Please upload your CV or connect with Linkedin', 'wpjobster-account-segregation' ); ?></strong></p>

					<div class="as_ac_uploader_wrapper">
						<?php wpjobster_theme_attachments_uploader_html5($secure=1,"file_upload_cv", "hidden_files_cv", "cv"); ?>
					</div>

					<p>
						<strong><?php echo __( 'OR','wpjobster-account-segregation' ); ?></strong>
					</p>

					<p class="as_ac_2emb">
						<a class="as_ac_btn btn bigger as_ac_linkedin" href="index.php?wpj_as_provider=linkedin"><?php _e( 'Connect with Linkedin', 'wpjobster-account-segregation' ); ?></a>
					</p>
					<p>
						<input type="submit" name="save_cv" class="as_ac_btn btn lightgrey_btn" value="<?php _e( 'Continue', 'wpjobster-account-segregation' ); ?>" />
					</p>
				</div>
			</form>
		</div>
		<?php
		// LINKEDIN LOGIN
		if( ( isset( $_GET["wpj_as_provider"] ) && $_GET["wpj_as_provider"]=="linkedin" )
			|| ( isset( $_GET["oauth_init"] ) && $_GET["oauth_init"] == 1 )
			|| ( isset( $_GET["oauth_token"] ) && isset( $_GET["oauth_verifier"] ) )
		){
			include( plugin_dir_path( __FILE__ ) . 'linkedin/http.php' );
			include( plugin_dir_path( __FILE__ ) . 'linkedin/oauth_client.php' );

			$callbackURL = get_permalink( get_option( 'wpjobster_account_verification_page_id' ) );
			$linkedinApiKey = get_option( 'wpj_as_linkedin_client_id' );
			$linkedinApiSecret = get_option( 'wpj_as_linkedin_client_secret' );
			$linkedinScope = 'r_basicprofile r_emailaddress';

			$client = new oauth_client_class;

			$client->debug = false;
			$client->debug_http = true;
			$client->redirect_uri = $callbackURL;

			$client->client_id = $linkedinApiKey;
			$application_line = __LINE__;
			$client->client_secret = $linkedinApiSecret;

			// API permissions
			if($success = $client->Initialize()){
				if(($success = $client->Process())){
					if(strlen($client->authorization_error)){
						$client->error = $client->authorization_error;
						$success = false;
					}elseif(strlen($client->access_token)){
						$success = $client->CallAPI('http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name)',
						'GET',
						array('format'=>'json'),
						array('FailOnAccessError'=>true), $user);
					}
				}
				$success = $client->Finalize($success);
			}

			if($client->exit) exit;

			if($success){
				update_user_meta( $current_user->ID, 'linkedin_profile_url', $user );
				update_user_meta( $current_user->ID, 'account_confirmation', 1 );
				delete_user_meta( $current_user->ID, 'wpj_as_rejected' );

				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved', $current_user->ID );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved', $current_user->ID );
				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $current_user->ID );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $current_user->ID );

				wp_redirect(home_url());
			}
		}

		// CV
		if( isset( $_POST['save_cv'] ) ){
			if ( isset($_POST['hidden_files_cv']) && $_POST['hidden_files_cv'] != "" ) {
				update_user_meta( $current_user->ID, 'cv_file', $_POST['hidden_files_cv'] );
				update_user_meta( $current_user->ID, 'account_confirmation', 1 );
				delete_user_meta( $current_user->ID, 'wpj_as_rejected' );

				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved', $current_user->ID );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved', $current_user->ID );
				wpjobster_send_email_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $current_user->ID );
				wpjobster_send_sms_allinone_translated( 'as_new_seller_not_approved_admin', 'admin', $current_user->ID );

				wp_redirect(home_url());
			}
		}
	}else{
		wp_redirect(home_url());
	}
}

add_action( 'template_redirect', 'seller_account_confirmation_function', 1 );
function seller_account_confirmation_function(){
	$current_user = wp_get_current_user();
	if( get_option( 'wpj_as_enable_account_verification' ) == 'yes' ){
		if( get_user_meta( $current_user->ID, 'wpjobster_temp_user_type', 'true' ) == 'seller' ){
			if ( get_user_meta( $current_user->ID, 'account_confirmation', 'true' ) != 1 ){
				if( ! isset( $_GET['wpj_as_provider'] ) ){
					$url = get_permalink( get_option( 'wpjobster_my_account_sales_page_id' ) );
					global $post;
					if( $post->ID == get_option( 'wpjobster_my_account_sales_page_id' ) || $post->ID == get_option( 'wpjobster_account_verification_page_id' ) ){
						remove_action( 'template_redirect', 'seller_account_confirmation_function' );
					}else{
						wp_redirect( $url );
						exit();
					}
				}
			}
		}
	}
}

add_action( 'wpjobster_user_search_left_join', 'wpj_as_filter_users_in_search_results_left_join', 10, 1 );
function wpj_as_filter_users_in_search_results_left_join($searchInp){
	global $wpdb;
	$wpj_as_filter_search_results = get_option('wpj_as_filter_search_results') ? get_option('wpj_as_filter_search_results') : 'no';
	if( $wpj_as_filter_search_results == 'no' ){
		echo"LEFT JOIN {$wpdb->prefix}usermeta AS wpj_as ON ( u.ID = wpj_as.user_ID AND wpj_as.meta_key='wpjobster_user_type' )";
	}
}

add_action( 'wpjobster_user_search_where', 'wpj_as_filter_users_in_search_results_where', 10, 1 );
function wpj_as_filter_users_in_search_results_where($searchInp){
	global $wpdb;
	$wpj_as_filter_search_results = get_option('wpj_as_filter_search_results') ? get_option('wpj_as_filter_search_results') : 'no';
	if( $wpj_as_filter_search_results == 'no' ){
		echo "AND wpj_as.meta_value LIKE 'seller'";
	}
}

add_action( 'user_register', 'wpj_as_user_registration_redirect', 10, 1 );
function wpj_as_user_registration_redirect( $user_id ){
	if ( wpjobster_user_type($user_id) == 'seller' || get_user_meta( $user_id, 'wpjobster_temp_user_type', 'true' ) == 'seller' ){
		$redirect = get_option('wpj_as_seller_register_redirection');
	} else {
		$redirect = get_option('wpj_as_buyer_register_redirection');
	}
	update_option('wpjobster_register_redirection_page', $redirect);
}

add_filter( 'template_include', 'member_home' );
if (!function_exists('member_home')) {
	function member_home($template) {
		$vc_inline = function_exists('wpj_vc_is_inline') ? wpj_vc_is_inline() : vc_is_inline();

		if ( ! $vc_inline ) {
			if (is_home() || is_front_page()) {
				if (is_user_logged_in()) {
					$current_user = wp_get_current_user();
					$user_id = $current_user->ID;
					if ( wpjobster_user_type($user_id) == 'seller' ){
						wp_redirect(get_permalink(get_option('wpj_as_seller_logged_in_homepage')));
						exit;
					}else{
						wp_redirect(get_permalink(get_option('wpj_as_buyer_logged_in_homepage')));
						exit;
					}
				} else {
					// not needed
					// return locate_template('page-homepage-public.php');
				}
			}
		}

		return $template;
	}
}
function wpj_as_choose_sale_page(){
	if(!function_exists('wp_get_current_user')) {
		include_once(ABSPATH . "wp-includes/pluggable.php");
	}

	$current_user = wp_get_current_user();
	$user_id =  $current_user->ID;
	$account_confirmation = get_user_meta( $user_id, 'wpjobster_user_type', 'true' );

	if( get_option('wpj_as_auto_approve_seller') == 'no' && $account_confirmation != 'seller' ){
		return true;
	}else{
		return false;
	}
}

if( wpj_as_choose_sale_page() ){
	if( ! function_exists('wpjobster_my_account_sales_area_function' ) ){
		function wpjobster_my_account_sales_area_function(){

			$ajax_url = admin_url( 'admin-ajax.php' );
			$current_user = wp_get_current_user(); ?>

			<script type="text/javascript">
				function wps_as_increment_become_seller(){
					var $ = jQuery;
					$.ajax({
						type: "POST",
						url: '<?php echo $ajax_url; ?>',
						data: { action: 'wpj_as_become_seller_action' },
						success: function() {}
					});
				}
			</script>

			<?php if( ( get_option( 'wpj_as_enable_account_verification' ) != 'yes' && get_user_meta( $current_user->ID, 'wpjobster_temp_user_type', true ) == 'seller' )
			|| ( get_option( 'wpj_as_enable_account_verification' ) == 'no' && get_user_meta( $current_user->ID, 'wpj_as_become_seller_click', true ) == 1 ) ) { ?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('.wpj-as-become-seller').click(function(){
							$('.wpj-as-description').css('display','block');
							wps_as_increment_become_seller();
						});
					});
				</script>
			<?php } else {
				$href = get_permalink( get_option( 'wpjobster_account_verification_page_id' ) );
			} ?>

			<div class="wpj-as-pending-seller center">
				<div class="ui segment padding">
					<div class="wpj-as-title"><?php echo __( 'Work Smarter', 'wpjobster-account-segregation' ); ?></div>
					<div class="wpj-as-subtitle"><?php echo __( 'Your own schedule. Your own terms', 'wpjobster-account-segregation' ); ?>.</div>
					<a class="ui huge primary button wpj-as-become-seller" onclick="wps_as_increment_become_seller();" href="<?php echo isset( $href ) ? $href : '#'; ?>"><?php echo __( 'Apply to Become a Seller', 'wpjobster-account-segregation' ); ?></a>
					<div class="wpj-as-description" style="display: none;">
						<?php
						$current_user = wp_get_current_user();
						$clicked = get_user_meta( $current_user->ID, 'wpj_as_become_seller_click', true );
						if( $clicked ){
							echo __( 'You have already applied to become a seller','wpjobster-account-segregation') . '.';
							echo '<br />';
							echo __( 'You should get an email notification once your application has been reviewed','wpjobster-account-segregation') . '.';
						}else{
							echo __( 'You application has been submitted successfully','wpjobster-account-segregation') . '.';
							echo '<br />';
							echo __( 'You should get an email notification once your application has been reviewed','wpjobster-account-segregation') . '.';
						}
						?>
					</div>
				</div>
			</div>
		<?php }
	}
}

add_action( 'wp_ajax_nopriv_wpj_as_become_seller_action', 'wpj_as_become_seller_action' );
add_action( 'wp_ajax_wpj_as_become_seller_action', 'wpj_as_become_seller_action' );
function wpj_as_become_seller_action(){
	$current_user = wp_get_current_user();
	update_user_meta( $current_user->ID, 'wpj_as_become_seller_click', 1 );
	die();
}

if (!function_exists('wpjobster_as_page_list_filter')) {
	function wpjobster_as_page_list_filter( $as_page_assignment ) {
		$as_page_assignment[ __( 'Account Verification','wpjobster' ) ] = 'wpjobster_account_verification_page_id';
		return $as_page_assignment;
	}
}
add_filter("wpjobster_page_assignments_list", "wpjobster_as_page_list_filter", 10, 1);
