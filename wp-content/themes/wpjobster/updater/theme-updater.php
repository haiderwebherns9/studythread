<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package WPJobster
 */

// Check once the license status and store the type if exists
// Returns Object or 'unknown'
function wpjobster_c_license_key_type_check( $force = false, $license_key = '' ) {
	if ( $force ) {
		delete_transient( 'wpjobster_c_license_key_status' );
	}

	if ( ! get_transient( 'wpjobster_c_license_key_status', false ) ) {
		// delete_option( 'wpjobster_license_key_status' );
		// delete_transient( 'wpjobster_license_message' );

		$types = array(
			'Beginner License',
			'Webmaster License',
			'Developer License',
			'Entrepreneur License',
			'Entrepreneur Installment Plan',
		);

		if ( get_option( 'wpjobster_c_license_type' ) ) {
			// if we already know the license type, move it to position 0 in the array
			// in an attempt to exit the loop with only one request
			$res = array_search( get_option( 'wpjobster_c_license_type' ), $types, TRUE );
			if ( $res !== FALSE ) {
				wpjobster_array_move( $types, $res, 0 );
			}
		}

		$store_url = 'http://wpjobster.com';
		if ( $license_key ) {
			$license = $license_key;
		} else {
			$license = get_option( 'wpjobster_license_key', false );
		}
		if ( ! $license ) {
			return;
		}

		foreach ( $types as $type ) {

			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $license,
				'item_name' => urlencode( $type ),
				'url' => home_url()
			);
			$response = wp_remote_post( $store_url, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );
			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( $license_data && isset( $license_data->license ) ) {

				if ( $license_data->license != 'item_name_mismatch' ) {
					// all good
					set_transient( 'wpjobster_c_license_key_status', $license_data, ( 60 * 60 * 24 ) );
					update_option( 'wpjobster_c_license_type', $type );
					break;
				} else {
					// unknown, set for 30m
					set_transient( 'wpjobster_c_license_key_status', 'unknown', ( 60 * 60 * 0.5 ) );
				}

			} else {
				// unknown, set for 30m
				set_transient( 'wpjobster_c_license_key_status', 'unknown', ( 60 * 60 * 0.5 ) );
				break;
			}
		}
	}

	return get_transient( 'wpjobster_c_license_key_status', false );
}

function wpjobster_c_license_key_type_force_check() {
	wpjobster_c_license_key_type_check( true );
}

function wpjobster_c_license_key_type_force_check_add( $option, $value ) {
	wpjobster_c_license_key_type_check( true, $value );
}

function wpjobster_c_license_key_type_force_check_update( $option, $old_value, $value ) {
	wpjobster_c_license_key_type_check( true, $value );
}

function wpjobster_c_license_key_status_check( $license_status ) {
	if ( ! $license_status ) {
		$license_status = get_option( 'wpjobster_license_key_status', false );
	}

	$type = get_option( 'wpjobster_c_license_type', false );
	$license = get_option( 'wpjobster_license_key', false );
	$ln = $type == 'Beginner License' ? 0.5 : ( $type == 'Webmaster License' ? 1 : ( $type == 'Developer License' ? 2 : ( $type == 'Entrepreneur License' ? 3 : ( $type == 'Entrepreneur Installment Plan' ? 3 : '' ) ) ) );

	if ( $license_status == 'valid' ) {
		update_option( 'wpjobster_c_license_key_ever_active', $license );
		update_option( 'emos_laer_euqinu_eman', hash( 'sha256', get_host_no_www() . 'd945jfht' . $ln , false ) );
	} elseif ( $license_status == 'expired' || $license_status == 'item_name_mismatch' ) {
		if ( get_option( 'wpjobster_c_license_key_ever_active' ) != $license ) {
			delete_option( 'emos_laer_euqinu_eman' );
		} elseif ( ! get_option( 'emos_laer_euqinu_eman' ) ) {
			update_option( 'emos_laer_euqinu_eman', hash( 'sha256', get_host_no_www() . 'd945jfht' . $ln , false ) );
		}
	} else {
		delete_option( 'emos_laer_euqinu_eman' );
	}
}

function wpjobster_c_license_key_status_check_add( $option, $value ) {
	wpjobster_c_license_key_status_check( $value );
}

function wpjobster_c_license_key_status_check_update( $option, $old_value, $value ) {
	wpjobster_c_license_key_status_check( $value );
}

add_action( 'admin_init', 'wpjobster_c_license_key_type_check', 1 );
add_action( 'add_option_' . 'wpjobster_license_key', 'wpjobster_c_license_key_type_force_check_add', 1, 2 );
add_action( 'update_option_' . 'wpjobster_license_key', 'wpjobster_c_license_key_type_force_check_update', 1, 3 );
add_action( 'wpjobster_edd_activate_license_before', 'wpjobster_c_license_key_type_force_check', 1 );
add_action( 'wpjobster_edd_deactivate_license_before', 'wpjobster_c_license_key_type_force_check', 1 );

add_action( 'add_option_' . 'wpjobster_license_key_status', 'wpjobster_c_license_key_status_check_add', 10, 2 );
add_action( 'update_option_' . 'wpjobster_license_key_status', 'wpjobster_c_license_key_status_check_update', 10, 3 );
add_action( 'wpjobster_edd_activate_license_after', 'wpjobster_c_license_key_status_check' );
add_action( 'wpjobster_edd_deactivate_license_after', 'wpjobster_c_license_key_status_check' );

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'http://wpjobster.com', // Site where EDD is hosted
		'item_name'      => get_option( 'wpjobster_c_license_type', false ), // Name of theme
		'theme_slug'     => 'wpjobster', // Theme slug
		'version'        => wpjobster_VERSION, // The current version of this theme
		'author'         => 'WPJobster', // The author of this theme
		'download_id'    => '', // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'wpjobster' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'wpjobster' ),
		'license-key'               => __( 'License Key', 'wpjobster' ),
		'license-action'            => __( 'License Action', 'wpjobster' ),
		'deactivate-license'        => __( 'Deactivate License', 'wpjobster' ),
		'activate-license'          => __( 'Activate License', 'wpjobster' ),
		'status-unknown'            => __( 'License status is unknown.', 'wpjobster' ),
		'renew'                     => __( 'Renew?', 'wpjobster' ),
		'unlimited'                 => __( 'unlimited', 'wpjobster' ),
		'license-key-is-active'     => __( 'License key is active.', 'wpjobster' ),
		'expires%s'                 => __( 'Expires %s.', 'wpjobster' ),
		'expires-never'             => __( 'Lifetime License.', 'wpjobster' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'wpjobster' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'wpjobster' ),
		'license-key-expired'       => __( 'License key has expired.', 'wpjobster' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'wpjobster' ),
		'license-is-inactive'       => __( 'License is inactive.', 'wpjobster' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'wpjobster' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'wpjobster' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'wpjobster' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wpjobster' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wpjobster' ),
	)

);

add_action( 'wpj_before_update_nag', 'wpj_major_update_5', 10, 1 );
function wpj_major_update_5( $version ){
	if( $version == '5.0' ){
		echo '
			<style>
				.new-update-notify{
					border-left: 4px solid #ff0000 !important;
					margin-bottom: -20px !important;
					display: block !important;
				}
				.attention{
					color: #ff0000;
					font-weight: 700;
				}
			</style>
		';

		echo '<div id="update-nag" class="new-update-notify">';
			echo '<span class="attention">' . strtoupper( __( 'Attention', 'wpjobster' ) ) . '! </span>';
			echo __( '5.0 is a major update. Make a full backup (site and database). After theme upgrading, update the wpjobster plugins.', 'wpjobster' );
		echo '</div>';
	}
}

