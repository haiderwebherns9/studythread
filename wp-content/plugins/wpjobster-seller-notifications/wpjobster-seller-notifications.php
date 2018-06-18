<?php
/*
 Plugin Name: WPJobster Seller Notifications
 Plugin URL: http://wpjobster.com/
 Description: Send notifications to sellers about the new requests posted in the categories they follow.
 Version: 2.0.1
 Author: WPJobster
 Author URI: http://wpjobster.com/
*/

// INCLUDE CLASS FOR CREATING LICENSE TAB
if ( ! class_exists( 'WPJ_Plugin_License' ) ) {
	include( plugin_dir_path( __FILE__ ) . 'updater/plugin-updater.php' );
}

add_action( 'init', 'wpjobster_sn_is_account_segregation');
add_action( 'admin_init', 'wpjobster_sn_is_account_segregation');
function wpjobster_sn_is_account_segregation() {
	if ( ! function_exists( 'wpjobster_user_type' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}

$wpj_seller_notifications_license = new WPJ_Plugin_License(
	array(
		'file'       => __FILE__,
		'item_name'  => 'Seller Notifications',
		'version'    => '2.0.1',
		'author'     => 'WPJobster',
		'api_url'    => 'http://wpjobster.com',
		'short_slug' => 'seller_notifications',
		'full_slug'  => 'wpjobster-seller-notifications',
		'textdomain' => 'wpjobster-seller-notifications',
	)
);

add_action('wp_enqueue_scripts', 'wpjobster_sn_load_scripts');
function wpjobster_sn_load_scripts() {
	wp_enqueue_style( 'sn-main-styles', plugins_url( 'style.css', __FILE__ ), array() );

	// Register the script
	wp_register_script( 'sn-main-scripts', plugins_url( 'script.js', __FILE__ ) );

	// Localize the script with new data
	$translation_array = array(
		'location_on' => __( 'Enable location', 'wpjobster-seller-notifications' ),
		'location_off' => __( 'Disable location', 'wpjobster-seller-notifications' ),
	);
	wp_localize_script( 'sn-main-scripts', 'sn', $translation_array );

	// Enqueued script with localized data.
	wp_enqueue_script( 'sn-main-scripts' );
}

function wpj_sn_slug() {
	// declare this in one place just in case we're gonna change it later
	return 'wpjobster-seller-notifications';
}

function wpj_sn_dir_url() {
	return plugin_dir_url( __FILE__ );
}

// Load translation files if exists
add_action( 'plugins_loaded', 'wpj_sn_load_textdomain' );
function wpj_sn_load_textdomain() {
	load_plugin_textdomain( 'wpjobster-seller-notifications', false, plugin_dir_url(__FILE__) . '/languages' );
}

// First run
add_action( 'admin_init', 'wpj_sn_first_run' );
function wpj_sn_first_run() {
	if ( get_option( 'wpj_sn_first_run_emails' ) != 'done' ) {
		update_option( 'wpj_sn_first_run_emails', 'done' );

		update_option( 'uz_email_request_notification_en_subject',
			'A new request was posted'
		);

		update_option( 'uz_email_request_notification_en_message',
			'Hello ##receiver_username##,' . PHP_EOL . PHP_EOL .

			'A new request was posted in one of the categories you follow.' . PHP_EOL .
			'Please find the link below:' . PHP_EOL .
			'##request_link##' . PHP_EOL . PHP_EOL .

			'Thank you,' . PHP_EOL .
			'##your_site_name## Team'
		);

		update_option( 'uz_sms_request_notification_en_message',
			'Hello ##receiver_username##,' . PHP_EOL . PHP_EOL .

			'A new request was posted in one of the categories you follow.' . PHP_EOL .
			'Please find the link below:' . PHP_EOL .
			'##request_link##' . PHP_EOL . PHP_EOL .

			'Thank you,' . PHP_EOL .
			'##your_site_name## Team'
		);
	}
}

// Add settings link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpjobster_seller_notifications_action_links' );
function wpjobster_seller_notifications_action_links ( $links ) {
	$mylinks = array(
		'<a href="' . menu_page_url( wpj_sn_slug(), false ) . '">' . __('Settings', 'wpjobster-seller-notifications') . '</a>',
	);
return array_merge( $links, $mylinks );
}

// Create custom plugin settings menu
add_action('admin_menu', 'wpjobster_seller_notifications_create_menu', 12);
function wpjobster_seller_notifications_create_menu() {
	add_submenu_page( 'PT1_admin_mnu', __('WPJobster Seller Notifications','wpjobster-seller-notifications'), '<img style="width: 20px; height: 20px;" src="' . wpj_sn_dir_url() . 'images/notifications.png"> ' . __('Seller Notifications','wpjobster-seller-notifications'), 'manage_options', wpj_sn_slug(), 'wpjobster_seller_notifications_plugin_settings_page');
}

add_action('admin_bar_menu', 'wpjobster_seller_notifications_in_admin_bar', 999);
function wpjobster_seller_notifications_in_admin_bar( $wp_admin_bar ) {
	$wp_admin_bar->add_node( array(
		'id'     => 'wpjobster-seller-notifications',
		'parent' => 'PT1_admin_mnu',
		'title' => __( 'Seller notifications', 'wpjobster-seller-notifications' ),
		'href'  => get_admin_url () . 'admin.php?page=wpjobster-seller-notifications',
		'meta'  => array(
			'title' => __( 'Seller Notifications', 'wpjobster-seller-notifications' )
		),
	));
}

function wpjobster_seller_notifications_plugin_settings_page($user_id) {
	$email_categories = notifications_array();
	$languages = get_preferred_languages();
	$arr = array(
		'yes' => __( 'Yes', 'wpjobster-seller-notifications' ),
		'no' => __( 'No', 'wpjobster-seller-notifications' ),
	);

	if(isset($_POST['wpjobster_save_plugin_settings'])) {
		update_option( 'wpj_sn_location' , trim( $_POST['wpj_sn_location'] ) );
	}

	$wpj_sn_location = get_option( 'wpj_sn_location' ); ?>

	<div class="wrap">
		<h2 class="my_title_class_sitemile"><?php _e( 'Jobster - Seller Notifications', 'wpjobster-seller-notifications' ); ?></h2>
		<div id="usual2" class="usual">
			<ul>
				<li><a href="#tabs1"><?php _e( "General Settings", 'wpjobster-seller-notifications' ); ?></a></li>
				<li><a href="#tabs2"><?php _e( "Email", 'wpjobster-seller-notifications' ); ?></a></li>
				<li><a href="#tabs3"><?php _e( "SMS", 'wpjobster-seller-notifications' ); ?></a></li>
				<?php do_action('wpj_seller_notifications_add_tab_name'); ?>
			</ul>
			<div id="tabs1">
				<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=wpjobster-seller-notifications">
					<table width="100%" class="sitemile-table">
						<tr>
							<td></td>
							<td><h2><?php _e("General Settings", "wpjobster"); ?></h2></td>
							<td></td>
						</tr>

						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet( __("Select no if you want to hide location from seller notifications page.", "wpjobster") ); ?></td>
							<td width="20%"><?php _e('Location:','wpjobster'); ?></td>
							<td><?php echo wpjobster_get_option_drop_down($arr, 'wpj_sn_location', 'no'); ?></td>
						</tr>
						<tr>
							<td valign=top width="22"></td>
							<td width="20%"></td>
							<td><input type="submit" class="button-secondary" name="wpjobster_save_plugin_settings" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
						</tr>
					</table>
				</form>
			</div>
			<div id="tabs2">
				<?php
				// E-MAIL
				foreach ( $email_categories as $email_category ) {
					foreach ( $email_category["items"] as $reason => $item ) {
						$reason_name = $item["title"];
						$reason_desc = $item["description"];

						if( $reason == "request_notification" ){ ?>

							<table width="100%" class="sitemile-table">
								<tr>
									<td valign="top" width="22"></td>
									<td><h2><?php echo $reason_name; ?></h2></td>
									<td></td>
								</tr>
								<tr>
									<td valign="top" width="22"></td>
									<td><?php echo $reason_desc; ?></td>
									<td></td>
								</tr>
							</table>

							<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=email-settings&active_tab=<?php echo 'uz_tabs_email_'.$reason; ?>">
								<table width="100%" class="sitemile-table">

									<tr>
										<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
										<td ><?php _e('Enable this email:','wpjobster'); ?></td>
										<td><?php echo wpjobster_get_option_drop_down($arr, 'uz_email_'.$reason.'_enable'); ?></td>
									</tr>

									<?php foreach ($languages as $lang => $lang_name) { ?>
									<tr>
										<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
										<td width="160"><?php _e('Email Subject:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?></td>
										<td><input type="text" size="90" name="<?php echo 'uz_email_'.$reason.'_'.$lang.'_subject'; ?>" value="<?php echo stripslashes(get_option('uz_email_'.$reason.'_'.$lang.'_subject')); ?>"/></td>
									</tr>

									<tr>
										<td valign=top><?php wpjobster_theme_bullet(); ?></td>
										<td valign=top ><?php _e('Email Content:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?></td>
										<td><textarea cols="92" rows="10" name="<?php echo 'uz_email_'.$reason.'_'.$lang.'_message'; ?>"><?php echo stripslashes(get_option('uz_email_'.$reason.'_'.$lang.'_message')); ?></textarea></td>
									</tr>
									<?php } ?>

									<tr>
										<td valign=top></td>
										<td valign=top ></td>
										<td><div class="spntxt_bo2">
										All tags legend: (You can NOT use all of them in each email, please see the available shortcodes list from top!)<br/><br/>


										<strong>##receiver_username##</strong> --- <?php _e("the person that will receive the emails.", "wpjobster"); ?><br/>
										<strong>##sender_username##</strong> --- <?php _e("the other person involved in the transaction.", "wpjobster"); ?><br/>
										<strong>##site_login_url##</strong> --- <?php _e("the link to the login page (the static one).", "wpjobster"); ?><br/>
										<strong>##your_site_name##</strong> --- <?php _e("your website's name", "wpjobster"); ?><br/>
										<strong>##your_site_url##</strong> --- <?php _e("your website's homepage url",'wpjobster'); ?><br/>
										<strong>##my_account_url##</strong> --- <?php _e("your website's my account link",'wpjobster'); ?><br/>
										<strong>##job_name##</strong> --- <?php _e("new job's title",'wpjobster'); ?><br/>
										<strong>##job_link##</strong> --- <?php _e('link for the new job','wpjobster'); ?><br/>
										<strong>##transaction_number##</strong> --- <?php _e('transaction number','wpjobster'); ?><br/>
										<strong>##transaction_page_link##</strong> --- <?php _e('transaction page link','wpjobster'); ?><br/>
										<strong>##amount_withdrawn##</strong> --- <?php _e('amount withdrawn, including ','wpjobster'); ?><br/>
										<strong>##withdraw_method##</strong> --- <?php _e('withdraw method','wpjobster'); ?><br/>
										<strong>##current_level##</strong> --- <?php _e('current level of the receiver','wpjobster'); ?><br/>
										<strong>##receiver_email##</strong> --- <?php _e('the email address of the user','wpjobster'); ?><br/>
										<strong>##private_message_link##</strong> --- <?php _e('the link for the conversation with a particular user','wpjobster'); ?><br/>
										<strong>##username##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_username woudn't make sense", "wpjobster"); ?><br/>
										<strong>##user_email##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_email woudn't make sense", "wpjobster"); ?><br/>
										<strong>##private_message_link##</strong> --- <?php _e("The link to a private conversation with another user", "wpjobster"); ?><br/>
										<strong>##password##</strong> --- <?php _e("The auto generated password for old nonajax registration", "wpjobster"); ?><br/>
										<strong>##email_verification##</strong> --- <?php _e("The email verification link", "wpjobster"); ?><br/>

										<strong>##all_featured_info##</strong> --- <?php _e("The periods and pages of the job that will be featured", "wpjobster"); ?><br/>

										</div></td>
									</tr>

									<tr>
										<td ></td>
										<td ></td>
										<td><input type="submit" class="button-secondary" name="<?php echo 'uz_save_email_'.$reason; ?>" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
									</tr>

								</table>
							</form>
						<?php } // end if
					} // end foreach
				} // end foreach
				// END E-MAIL ?>
			</div>
			<div id="tabs3">
				<?php //SMS
				$sms_categories = notifications_array();
				foreach ($sms_categories as $sms_category) {
					foreach ( $sms_category["items"] as $reason => $item ) {
						$reason_name = $item["title"];
						$reason_desc = $item["description"];

						if( $reason == "request_notification" ){ ?>

							<table width="100%" class="sitemile-table">
								<tr>
									<td valign="top" width="22"></td>
									<td><h2><?php echo $reason_name; ?></h2></td>
									<td></td>
								</tr>
								<tr>
									<td valign="top" width="22"></td>
									<td><?php echo $reason_desc; ?></td>
									<td></td>
								</tr>
							</table>

							<div id="<?php echo 'uz_tabs_sms_'.$reason; ?>">
								<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=sms-settings&active_tab=<?php echo 'uz_tabs_sms_'.$reason; ?>">

									<?php if (!wpjobster_sms_allowed()) { ?>

										<div class="wpjobster-update-nag wpjobster-notice">
											This feature is Entrepreneur license exclusive. <a href="http://wpjobster.com/buy/" target="_blank">Buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
										</div>

									<?php } ?>

									<table width="100%" class="sitemile-table <?php if (!wpjobster_sms_allowed()) { echo "wpjobster-disabled-settings"; } ?>">

										<tr>
											<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
											<td ><?php _e('Enable this sms:','wpjobster'); ?></td>
											<td><?php echo wpjobster_get_option_drop_down($arr, 'uz_sms_'.$reason.'_enable'); ?></td>
										</tr>

										<?php foreach ($languages as $lang => $lang_name) { ?>
										<tr>
											<td valign=top><?php wpjobster_theme_bullet(); ?></td>
											<td valign=top ><?php _e('SMS Content:','wpjobster'); ?><?php echo ' ('.$lang_name.')'; ?></td>
											<td><textarea cols="92" rows="10" name="<?php echo 'uz_sms_'.$reason.'_'.$lang.'_message'; ?>"><?php echo stripslashes(get_option('uz_sms_'.$reason.'_'.$lang.'_message')); ?></textarea></td>
										</tr>
										<?php } ?>

										<tr>
											<td valign=top></td>
											<td valign=top ></td>
											<td><div class="spntxt_bo2">
											All tags legend: (You can NOT use all of them in each email, please see the available shortcodes list from top!)<br/><br/>


											<strong>##receiver_username##</strong> --- <?php _e("the person that will receive the emails.", "wpjobster"); ?><br/>
											<strong>##sender_username##</strong> --- <?php _e("the other person involved in the transaction.", "wpjobster"); ?><br/>
											<strong>##site_login_url##</strong> --- <?php _e("the link to the login page (the static one).", "wpjobster"); ?><br/>
											<strong>##your_site_name##</strong> --- <?php _e("your website's name", "wpjobster"); ?><br/>
											<strong>##your_site_url##</strong> --- <?php _e("your website's homepage url",'wpjobster'); ?><br/>
											<strong>##my_account_url##</strong> --- <?php _e("your website's my account link",'wpjobster'); ?><br/>
											<strong>##job_name##</strong> --- <?php _e("new job's title",'wpjobster'); ?><br/>
											<strong>##job_link##</strong> --- <?php _e('link for the new job','wpjobster'); ?><br/>
											<strong>##transaction_number##</strong> --- <?php _e('transaction number','wpjobster'); ?><br/>
											<strong>##transaction_page_link##</strong> --- <?php _e('transaction page link','wpjobster'); ?><br/>
											<strong>##amount_withdrawn##</strong> --- <?php _e('amount withdrawn, including ','wpjobster'); ?><br/>
											<strong>##withdraw_method##</strong> --- <?php _e('withdraw method','wpjobster'); ?><br/>
											<strong>##current_level##</strong> --- <?php _e('current level of the receiver','wpjobster'); ?><br/>
											<strong>##receiver_email##</strong> --- <?php _e('the email address of the user','wpjobster'); ?><br/>
											<strong>##private_message_link##</strong> --- <?php _e('the link for the conversation with a particular user','wpjobster'); ?><br/>
											<strong>##username##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_username woudn't make sense", "wpjobster"); ?><br/>
											<strong>##user_email##</strong> --- <?php _e("this is used mostly for emails sent to admin, because receiver_email woudn't make sense", "wpjobster"); ?><br/>
											<strong>##private_message_link##</strong> --- <?php _e("The link to a private conversation with another user", "wpjobster"); ?><br/>
											<strong>##password##</strong> --- <?php _e("The auto generated password for old nonajax registration", "wpjobster"); ?><br/>
											<strong>##email_verification##</strong> --- <?php _e("The email verification link", "wpjobster"); ?><br/>

											<strong>##all_featured_info##</strong> --- <?php _e("The periods and pages of the job that will be featured", "wpjobster"); ?><br/>

											</div></td>
										</tr>

										<tr>
											<td ></td>
											<td ></td>
											<td><input type="submit" class="button-secondary" name="<?php echo 'uz_save_sms_'.$reason; ?>" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
										</tr>

									</table>
								</form>

							</div>
						<?php } //end if
					} //end foreach
				} //end foreach
				// END SMS ?>
			</div>
			<?php do_action('wpj_seller_notifications_add_tab_content');  ?>
		</div>
	</div>
	<?php
}

//activate this plugin if account segregtion plugin is activated
register_activation_hook( __FILE__, 'wpjobster_seller_notifications_activate' );
function wpjobster_seller_notifications_activate(){
	// Require parent plugin
	if ( ! is_plugin_active( 'wpjobster-account-segregation/wpjobster-account-segregation.php' ) && current_user_can( 'activate_plugins' ) ) {
		// Stop activation redirect and show error
		wp_die( 'WPJobster Account Segregation plugin needs to be active before activating WPJobster Seller Notifications. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>' );
	}

	if ( ! function_exists( 'wpjobster_insert_pages' ) ) {
		wp_die( 'Jobster Theme is required!' );
	} else {
		wpjobster_insert_pages( 'wpjobster_seller_notifications_categories_page_id',
			'Requests Notifications',
			'[wpjobster_seller_notifications_categories]',
			0,
			'wpjobster-special-page-template.php'
		);
	}
}

//adding user meta for seller and category
if (!function_exists('notification_save_user_categories')) {
	function notification_save_user_categories() {

		global $current_user;
		$current_uid = $current_user->ID ;

		if( isset( $_POST['seller_notifications_post'] ) ){

			$sum_of_terms = intval( $_POST['sum_of_terms'] );

			if( isset( $_POST['user_category'] ) ){
				$user_category = $_POST['user_category'];
				delete_user_meta( $current_uid, "user_category" );

				foreach( $user_category as $category_id ){
					add_user_meta( $current_uid, "user_category", $category_id );
				}

				if( $sum_of_terms == count( $_POST['user_category'] ) ){
					update_user_meta( $current_uid, 'user_all_categories', 'on' );
				} else {
					update_user_meta( $current_uid, 'user_all_categories', 'off' );
				}
			} else {
				delete_user_meta( $current_uid, "user_category" );
			}

			if( wpjobster_sn_location_is_enabled() ){

				if( isset( $_POST['enable_location'] ) ){
					$enable_location = htmlspecialchars($_POST['enable_location']);
					update_user_meta( $current_uid, "user_enable_location", $enable_location );
				} else {
					update_user_meta( $current_uid, "user_enable_location", "off" );
				}

				if( isset( $_POST['location_input'] ) ){
					$location_input = htmlspecialchars($_POST['location_input']);
					update_user_meta( $current_uid, "user_request_location", $location_input );
				}

				if( isset( $_POST['radius_input'] ) ){
					$radius_input = htmlspecialchars($_POST['radius_input']);
					update_user_meta( $current_uid, "user_request_radius", $radius_input );
				}

				if( isset( $_POST['lat'] ) ){
					$lat = htmlspecialchars($_POST['lat']);
					update_user_meta( $current_uid, "user_request_location_latitude", $lat );
				}

				if( isset( $_POST['long'] ) ){
					$long = htmlspecialchars($_POST['long']);
					update_user_meta( $current_uid, "user_request_location_longitude", $long );
				}
			}
		}
	}
}

function wpjobster_sn_location_is_enabled(){
	$wpj_sn_location = get_option( 'wpj_sn_location' );
	if( $wpj_sn_location == 'yes' ){
		return true;
	} else {
		return false;
	}
}

//display notification page with multiple check box
if (!function_exists('wpjobster_get_categories_name_checkbox')) {
	function wpjobster_get_categories_name_checkbox() { ?>
		<div id="content-full-ov" class="requests-notifications uz-form">
			<div class="ui segment">
				<div class="seller-notifications-title">
					<h1><?php _e( "Requests Notifications", 'wpjobster-seller-notifications' ); ?></h1>
				</div>
			</div>

			<div><?php

				// Save post data
				notification_save_user_categories();

				global $current_user;
				$current_user = wp_get_current_user();
				$current_uid = $current_user->ID ;
				$current_categories = get_user_meta($current_uid,"user_category");
				$wpjobsterusertype = get_user_meta($current_uid, 'wpjobster_user_type', true);

				if (strtolower($wpjobsterusertype) == 'seller'){
					$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
					$taxo = 'job_cat';
					$ccc = 'category-checkbox';
					$terms = get_terms($taxo, $args);

					$ret = '<form method="post" action="" class="cf">';
						if ( empty( $selected ) ) $selected = -1;
						$radius_unit = get_option( 'wpjobster_locations_unit' ) ? ' (' . get_option( 'wpjobster_locations_unit' ) . ')' : '';
						$all_cat_checked = get_user_meta( $current_uid, 'user_all_categories', true ) == 'on' ? 'checked' : '';

						$ret .= '
						<div class="ui segment">
							<div class="ui form">';
								if( wpjobster_sn_location_is_enabled() ){
									if( get_user_meta( $current_uid, 'user_enable_location', true ) == 'on' ) {
										$checked = 'checked';
										$disabled = '';
										$lbl_location = __( 'Enable location','wpjobster-seller-notifications' );
									} else {
										$checked = '';
										$disabled = 'disabled';
										$lbl_location = __( 'Disable location','wpjobster-seller-notifications' );
									}

									$ret .='
									<div class="field location-wrapper">
										<div class="two fields">
											<div class="field">
												<label>' . __( 'Location', 'wpjobster-seller-notifications' ) . '</label>
												<input
													class="location_input"
													type="text"
													data-replaceplaceholder="' . __( 'Select a valid location','wpjobster' ) . '"
													placeholder="' . __( 'Location','wpjobster-seller-notifications' ) . '"
													id="location_input"
													name="location_input"
													value="' . get_user_meta( $current_uid, 'user_request_location', true ) . '"
													'.$disabled.'
												/>
											</div>

											<input id="lat" type="hidden" name="lat" value="' . get_user_meta( $current_uid, 'user_request_location_latitude', true ) . '">
											<input id="long" type="hidden" name="long" value="' . get_user_meta( $current_uid, 'user_request_location_longitude', true ) . '">

											<div class="field">
												<label>' . __( 'Radius', 'wpjobster-seller-notifications' ) . __( $radius_unit, 'wpjobster-seller-notifications' )  . '</label>
												<input
													type="text"
													placeholder="' . __( 'Radius','wpjobster-seller-notifications' ) . '"
													id="radius_input"
													name="radius_input"
													value="' . get_user_meta( $current_uid, 'user_request_radius', true ) . '"
													'.$disabled.'
												/>
											</div>
										</div>
									</div>

									<div class="field">
										<div class="ui toggle checkbox enable_location ' . $checked . '">
											<input ' . $checked . ' name="enable_location" type="checkbox" id="enable_location" />
											<label class="lbl_location">'.$lbl_location.'</label>
										</div>
									</div>';
								}

								$sum_all = 0;
								$ret .= '<div class="field">';
									$ret .= '<label>' . __( 'Categories', 'wpjobster-seller-notifications' ) . '</label>';
									$ret .= '<select id="user_category" name="user_category[]" class="ui fluid search dropdown" multiple="">';
										$ret .= '<option value="">Categories</option>';

										$i = 1;
										foreach ($terms as $term) {
											$sum_all += $i;

											$ide = $term->term_id;
											$selected_ide = in_array($ide,$current_categories) ? " selected " : "";

											$ret .= '<option '.$selected_ide.' value="'.$ide.'">'.$term->name.'</option>';

											$args = "orderby=name&order=ASC&hide_empty=0&parent=" . $ide;
											$sub_terms = get_terms($taxo, $args);

											$j = 1;
											foreach ($sub_terms as $sub_term) {
												$sum_all += $j;

												$sub_id = $sub_term->term_id;
												$selected_sub_id = in_array($sub_id,$current_categories) ? " selected " : "";
												$ret .= '<option '.$selected_sub_id.' value="'.$sub_id.'">&nbsp;&nbsp;|&nbsp;'.$sub_term->name.'</option>';

												$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $sub_id;
												$sub_terms2 = get_terms($taxo, $args2);

												$k = 1;
												foreach ($sub_terms2 as $sub_term2) {
													$sum_all += $k;

													$sub_id2 = $sub_term2->term_id;
													$selected_sub_id2 = in_array($sub_id2,$current_categories) ? " selected " : "";
													$ret .= '<option '.$selected_sub_id2.' value="'.$sub_id2.'">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;'.$sub_term2->name.'</option>';

												}
											}
										}
									$ret .= '</select>';
								$ret .= '</div>

								<div class="field">
									<div class="ui toggle checkbox select_all_user_categories">
										<input ' . $all_cat_checked . ' name="select_all_user_categories" type="checkbox" />
										<input type="hidden" name="sum_of_terms" value="'.$sum_all.'" />
										<label>' . __( 'All categories', 'wpjobster-seller-notifications' ) . '</label>
									</div>
								</div>

								<div class="field">
									<input type="submit" name="seller_notifications_post" value="'. __('Save', 'wpjobster-seller-notifications') . '" class="ui primary button">
								</div>
							</div>
						</div>';
					$ret .= '</form>';

					echo $ret;

				} else {
					// echo "Sorry, your are not a seller.";
					wp_redirect( get_permalink( get_option( 'wpjobster_my_account_shopping_page_id' ) ) );
				} ?>
			</div>

			<div class="ui hidden divider"></div>

		</div></div>
		<?php
	}
}
add_shortcode('wpjobster_seller_notifications_categories', 'wpjobster_get_categories_name_checkbox');

if( isset( $_GET['user_categories'] ) ){
	add_action( 'wp_init','notification_save_user_categories' );
}

function wpjobster_get_two_point_distance( $lat1, $lon1, $lat2, $lon2, $unit ) {
	$theta = $lon1 - $lon2;
	$dist = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) +  cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
	$dist = acos( $dist );
	$dist = rad2deg( $dist );
	$miles = $dist * 60 * 1.1515;

	if ( $unit == "kilometers" ) {
		return ( $miles * 1.609344 );
	} else {
		return $miles;
	}
}

//send mail to sellers subscribed
if ( ! function_exists( 'wpjobster_send_email_to_all_subscribers' ) ) {
	function wpjobster_send_email_to_all_subscribers( $request_id, $category_id ) {

		$request_lat = get_post_meta( $request_id, 'request_lat', true );
		$request_long = get_post_meta( $request_id, 'request_long', true );

		$users = get_users( array( 'meta_key'=>'user_category','meta_value'=>$category_id ) );

		foreach( $users as $user ){
			$user_id = $user->ID;

			$user_location = get_user_meta( $user_id, 'user_request_location', true );
			$user_lat = get_user_meta( $user_id, 'user_request_location_latitude', true );
			$user_long = get_user_meta( $user_id, 'user_request_location_longitude', true );

			$user_radius = get_user_meta( $user_id, 'user_request_radius', true );
			$request_radius = $user_radius ? $user_radius : 35;

			$current_location_categories = get_user_meta($user_id,"user_location_category");

			$distance = wpjobster_get_two_point_distance( doubleval( $request_lat ), doubleval( $request_long ), doubleval( $user_lat ), doubleval( $user_long ), get_option( 'wpjobster_locations_unit' ) );

			$request_distance = $distance ? $distance : 0;

			$enable_location = get_user_meta( $user_id, 'user_enable_location', true );

			if ( wpjobster_user_type( $user_id ) == 'seller' ) {
				if( wpjobster_sn_location_is_enabled() && $enable_location == 'on' && $user_location ){
					if( $request_distance <= $request_radius ){
						wpjobster_send_email_allinone_translated('request_notification', $user_id, false, $request_id, false, false, false, false, false);
					}
				} else {
					wpjobster_send_email_allinone_translated('request_notification', $user_id, false, $request_id, false, false, false, false, false);
				}
			}
		}
	}
}
add_action("wpjobster_after_request_inserted", "wpjobster_send_email_to_all_subscribers",10,2);

// email template for admin
if ( ! function_exists( 'wpjobster_admin_menu_email_templates_filter' ) ) {
	function wpjobster_admin_menu_email_templates_filter( $reasons ) {
		$reasons['seller_notifications'] = array(
			"title" => "Seller Notifications",
			"items" => array(
				"request_notification" => array(
					"title"       => __( "New Request Notification", "wpjobster-seller-notifications" ),
					"description" =>
						"This notification will be received by sellers when there is a new request in one of their followed categories.
						<br /><br /> Available shortcodes:
						<br /><br /> <strong>##receiver_username##, <br> ##your_site_name##, <br> ##your_site_url##, <br> ##my_account_url##, <br> ##job_link##, <br> ##job_name##, <br> ##request_link##</strong>",
				),
			),
		);

		return $reasons;
	}
}
add_filter("wpjobster_admin_menu_email_templates", "wpjobster_admin_menu_email_templates_filter",10,1);


if (!function_exists('wpjobster_sn_dropdown_menu_list_filter')) {
	function wpjobster_sn_dropdown_menu_list_filter($drop_down_user_menu) {
		$is_seller = 1;
		if ( is_plugin_active( 'wpjobster-account-segregation/wpjobster-account-segregation.php' ) ) {
			if ( wpjobster_user_type() != 'seller' ) {
				$is_seller = 0;
			}
		}

		if( $is_seller == 1 ){
			$seller_notification_menu = array(
				'label' => __( 'Notifications', 'wpjobster-seller-notifications' ),
				'url' => get_option( 'wpjobster_seller_notifications_categories_page_id' ),
				'childs' => array(),
				'order' => '4',
			);

			$drop_down_user_menu['requests']['childs']['seller_notification'] = $seller_notification_menu;
		}

		return $drop_down_user_menu;
	}
}
add_filter("wpjobster_dropdown_menu_list", "wpjobster_sn_dropdown_menu_list_filter", 10, 1);

if (!function_exists('wpjobster_sn_page_list_filter')) {
	function wpjobster_sn_page_list_filter( $sn_page_assignment ) {
		if ( is_plugin_active( 'wpjobster-account-segregation/wpjobster-account-segregation.php' ) ) {
			$sn_page_assignment[ __( 'Seller Notification','wpjobster' ) ] = 'wpjobster_seller_notifications_categories_page_id';
		}

		return $sn_page_assignment;
	}
}
add_filter("wpjobster_page_assignments_list", "wpjobster_sn_page_list_filter", 10, 1);
