<?php
//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//------------------------------------------------

global $pagenow;

if ((is_admin() && 'themes.php' == $pagenow && isset($_GET['activated']))
	|| (is_admin() && 'admin.php' == $pagenow && isset($_GET['dbupdate']))) {

	include 'first_run_sql.php';
	include 'first_run_basicoptions.php';
	include 'first_run_visualcomposer.php';
	include 'first_run_emails.php';
	include 'first_run_sms.php';

	update_option( 'wpjobster_update_510', 'done' );
	add_action( 'admin_notices', 'wpjobster_database_updated_notice', 9 );

} // endif


// check if the database was updated
if ( get_option( 'wpjobster_update_510' ) != 'done' ) {
	add_action( 'admin_notices', 'wpjobster_database_update_required_notice', 9 );
}


// check if license key was filled
if ( get_option( 'wpjobster_license_key' ) == '' ) {
	add_action( 'admin_notices', 'wpjobster_license_key_missing_notice' );
}


if ( get_option( 'wpjobster_beginner_defaults_404' ) != 'done' ) {
	$the_loop_check = the_loop_check();
	if ( $the_loop_check == 0.5 ) {
		include 'first_run_beginner.php';
	}

	if ( $the_loop_check == 0.5 || $the_loop_check == 1 || $the_loop_check == 2 || $the_loop_check == 3 ) {
		update_option( 'wpjobster_beginner_defaults_404', 'done' );
	}
}


function wpjobster_database_update_required_notice() {
	?>
	<div class="error notice">
		<h2><?php _e('Jobster Database update required!', 'wpjobster'); ?>
		<a class="page-title-action" href="<?php echo get_bloginfo( 'url' ).'/wp-admin/admin.php?page=PT1_admin_mnu&dbupdate=true' ?>"><?php _e('Update Now', 'wpjobster'); ?></a></h2>
	</div>
	<?php
}


function wpjobster_database_updated_notice() {
	?>
	<div class="updated notice is-dismissible">
		<p><?php _e('Jobster Database was successfully updated!', 'wpjobster'); ?></p>
	</div>
	<?php
}


function wpjobster_license_key_missing_notice() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p>
		<?php _e('Please fill your WPJobster License Key!', 'wpjobster'); ?>
		&nbsp; <a class="" href="<?php echo get_bloginfo( 'url' ).'/wp-admin/admin.php?page=general-options'; ?>"><?php _e('General Settings', 'wpjobster'); ?></a>
		</p>
	</div>
	<?php
}


// check if openexchangerates api key was filled
if (get_option('openexchangerates_appid') == '') {
	add_action('admin_notices', 'wpjobster_openexchangerates_appid_required_notice');
}

function wpjobster_openexchangerates_appid_required_notice() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p>
		<?php _e('Please fill your Open Exchange Rates App ID!', 'wpjobster'); ?>
		&nbsp; <a class="" href="<?php echo get_bloginfo( 'url' ).'/wp-admin/admin.php?page=pricing-settings&active_tab=tabs5'; ?>"><?php _e('Pricing Settings', 'wpjobster'); ?></a>
		</p>
	</div>
	<?php
}


// check if required plugins were activated
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! is_plugin_active( 'js_composer/js_composer.php' )
	|| ! is_plugin_active( 'revslider/revslider.php' )
	|| ! is_plugin_active( 'easy-social-share-buttons3/easy-social-share-buttons3.php' ) ) {

	add_action( 'admin_notices', 'wpjobster_required_plugins' );
}

function wpjobster_required_plugins() {
	?>
	<div class="error notice">
		<p><?php echo sprintf( __('The following plugins are required in order to ensure proper theme functionality. Please %s and activate:', 'wpjobster'), '<a class="" href="' . get_bloginfo( 'url' ) . '/wp-admin/plugins.php"><strong>' . __( 'go to plugins', 'wpjobster' ) . '</strong></a>' ); ?></p>
		<?php
			if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
				echo '<p>+ WPBakery Page Builder</p>';
			}
			if ( ! is_plugin_active( 'revslider/revslider.php' ) ) {
				echo '<p>+ Slider Revolution</p>';
			}
			if ( ! is_plugin_active( 'easy-social-share-buttons3/easy-social-share-buttons3.php' ) ) {
				echo '<p>+ Easy Social Share Buttons for WordPress</p>';
			}
		?>
		<?php global $pagenow;
		if ( is_admin() && 'plugins.php' == $pagenow ) { ?>
			<p><strong><a href="#" id="wpjobster-select-plugins-asdasd"><?php _e( 'Select All Required Plugins', 'wpjobster' ); ?></strong></a>, <?php _e( 'then use Bulk Actions to activate them.') ?></p>
			<script>
				jQuery( document ).ready( function($) {
					$( "#wpjobster-select-plugins-asdasd" ).click( function(event) {
						event.preventDefault();
						$( "input:checkbox[value='js_composer/js_composer.php']" ).attr( "checked", true );
						$( "input:checkbox[value='revslider/revslider.php']" ).attr( "checked", true );
						$( "input:checkbox[value='easy-social-share-buttons3/easy-social-share-buttons3.php']" ).attr( "checked", true );
					});
				});
			</script>
		<?php } ?>
	</div>
	<?php
}


// check if recommended plugins were activated
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! has_action( 'admin_notices', 'wpjobster_required_plugins' )
	&& get_option( 'wpjobster_sql_10extras' ) != 'done' ) {

	add_action( 'admin_notices', 'wpjobster_recommended_plugins' );
}

function wpjobster_recommended_plugins() {
	if ( ! PAnD::is_admin_notice_active( 'notice-recommended-plugins-forever' ) ) {
		return;
	}
	?>
	<div data-dismissible="notice-recommended-plugins-forever" class="updated notice is-dismissible">
		<p><?php echo sprintf( __('The following plugins are not required, but they can extend the functionality. You will find more about them %s:', 'wpjobster'), '<a class="" href="' . get_bloginfo( 'url' ) . '/wp-admin/plugins.php"><strong>' . __( 'on the plugins page', 'wpjobster' ) . '</strong></a>' ); ?></p>
		<?php
			if ( ! is_plugin_active( 'loco-translate/loco.php' ) ) {
				echo '<p>+ Loco Translate</p>';
			}
			if ( ! is_plugin_active( 'menu-icons/menu-icons.php' ) ) {
				echo '<p>+ Menu Icons</p>';
			}
			if ( ! is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {
				echo '<p>+ WordPress Social Login</p>';
			}
			if ( ! is_plugin_active( 'wp-better-emails/wpbe.php' ) ) {
				echo '<p>+ WP Better Emails</p>';
			}
			if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
				echo '<p>+ Yoast SEO</p>';
			}
		?>
	</div>
	<?php
}


// remove the plugins which are already included in the theme
add_action( 'admin_notices', 'wpjobster_remove_plugins' );
function wpjobster_remove_plugins() {
	if ( ! PAnD::is_admin_notice_active( 'notice-remove-plugins-forever' )
		|| ! wpj_exists_theme_included_plugins() ) {
		return;
	}
	?>
	<div data-dismissible="notice-remove-plugins-forever" class="notice notice-warning is-dismissible">
		<p><?php echo __( 'Since v4.1.1, Jobster theme includes the functionality of the following plugins, so they have been automatically deactivated.', 'wpjobster' ); ?>
		<br>
		<?php echo __( 'You can delete them automatically using the button below or dismiss this notice.', 'wpjobster' ); ?></p>
		<?php
			foreach ( wpj_get_theme_included_plugins() as $plugin_slug ) {
				if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug );
					echo '<p>- ' . $plugin_data['Name'] . '</p>';
				}
			}
		?>
		<p><a class="button action" href="<?php echo get_bloginfo( 'url' ).'/wp-admin/admin.php?page=PT1_admin_mnu&deleteincludedplugins=true' ?>"><?php _e('Delete Now', 'wpjobster'); ?></a></p>
	</div>
	<?php
}

if ( is_admin() && 'admin.php' == $pagenow && isset( $_GET['deleteincludedplugins'] ) ) {
	add_action( 'admin_init', 'wpj_delete_theme_included_plugins' );
	add_action( 'admin_notices', 'wpjobster_plugins_deleted_notice' );
}

function wpjobster_plugins_deleted_notice() {
	?>
	<div class="updated notice is-dismissible">
		<p><?php _e( 'The unnecessary plugins have been successfully deleted!', 'wpjobster' ); ?></p>
	</div>
	<?php
}
