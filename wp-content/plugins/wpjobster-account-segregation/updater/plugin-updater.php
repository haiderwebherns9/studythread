<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPJobster update API.
 *
 * @author WPJobster
 * @version 1.0.1
 */

if ( ! class_exists( 'WPJ_Plugin_License' ) ) :
	class WPJ_Plugin_License {
		private $file;
		private $license;
		private $item_name;
		private $version;
		private $author = 'WPJobster';
		private $api_url;
		private $short_slug;
		private $full_slug;
		private $textdomain;

		function __construct( $plugin_data ) {

			$this->file       = $plugin_data['file'];
			$this->item_name  = $plugin_data['item_name'];
			$this->version    = $plugin_data['version'];
			$this->license    = isset($_POST['wpjobster_'.$this->short_slug.'_license_key']) ? $_POST['wpjobster_'.$this->short_slug.'_license_key'] : get_option( 'wpjobster_'.$this->short_slug.'_license_key' );
			$this->author     = $plugin_data['author'];
			$this->api_url    = is_null( $plugin_data['api_url'] ) ? $this->api_url : $plugin_data['api_url'];
			$this->short_slug = $plugin_data['short_slug'];
			$this->full_slug  = $plugin_data['full_slug'];
			$this->textdomain = $plugin_data['textdomain'];

			// Setup hooks
			$this->includes();
			$this->hooks();
			$this->auto_updater();
		}

		private function includes() {
			if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				// load our custom updater
				include( plugin_dir_path( __FILE__ ) . 'plugin-updater-class.php' );
			}
		}

		private function hooks() {
			// Register settings
			add_action('admin_init', array( $this, 'wpjobster_register_option') );
			// Activate license key on settings save
			add_action( 'admin_init', array( $this, 'activate_license' ) );
			// Deactivate license key
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );
			// Display errors
			add_action( 'admin_notices', array( $this, 'wpjobster_admin_notices' ) );
			//Display tab name
			add_action( 'wpj_'.$this->short_slug.'_add_tab_name', array( $this, 'wpjobster_license_tab_name' ) );
			// Display page
			add_action( 'wpj_'.$this->short_slug.'_add_tab_content', array( $this, 'wpjobster_license_page' ) );
		}

		private function auto_updater() {
			// Setup the updater
			$license_key = trim( get_option( 'wpjobster_'.$this->short_slug.'_license_key' ) );

			$edd_updater = new EDD_SL_Plugin_Updater(
				$this->api_url,
				$this->file,
				array(
					'version'   => $this->version,
					'license'   => $license_key,
					'item_name' => $this->item_name,
					'author'    => $this->author,
					'beta'      => false
				)
			);
		}

		public function wpjobster_license_tab_name(){
			echo '<li><a href="#license">' . __( 'License', $this->textdomain ) . '</a></li>';
		}

		public function wpjobster_license_page() {
			$license = get_option( 'wpjobster_'.$this->short_slug.'_license_key' );
			$status  = get_option( 'wpjobster_'.$this->short_slug.'_license_status' );
			?>
			<div id="license">
				<form method="post" action="options.php">

					<?php settings_fields('wpjobster_'.$this->short_slug.'_license'); ?>

					<table width="100%" class="sitemile-table">
						<tbody>
							<tr valign="top">
								<td valign=top width="22"></td>
								<td><h2><?php _e( 'License Options', $this->textdomain ); ?></h2></td>
								<td></td>
							</tr>
							<tr valign="top">
								<td valign=top width="22">
									<?php wpjobster_theme_bullet(); ?>
								</td>
								<td>
									<?php _e( 'License Key', $this->textdomain ); ?>
								</td>
								<td>
									<input id="wpjobster_<?php echo $this->short_slug; ?>_license_key" name="wpjobster_<?php echo $this->short_slug; ?>_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
									<?php if( $status !== false && $status == 'valid' ) { ?>
										<?php wp_nonce_field( 'wpjobster_'.$this->short_slug.'_nonce', 'wpjobster_'.$this->short_slug.'_nonce' ); ?>
										<input type="submit" class="button-secondary" name="wpjobster_<?php echo $this->short_slug; ?>_license_deactivate" value="<?php _e( 'Deactivate License', $this->textdomain ); ?>"/>
									<?php } else { ?>
										<?php wp_nonce_field( 'wpjobster_'.$this->short_slug.'_nonce', 'wpjobster_'.$this->short_slug.'_nonce' ); ?>
										<input type="submit" class="button-secondary" name="wpjobster_<?php echo $this->short_slug; ?>_license_activate" value="<?php _e( 'Activate License', $this->textdomain ); ?>"/>
									<?php } ?>
								</td>
							</tr>
							<tr valign="top">
								<td valign=top width="22">
									<?php wpjobster_theme_bullet(); ?>
								</td>
								<td>
									<?php _e( 'License Status', $this->textdomain ); ?>
								</td>
								<td>
									<?php if( $status !== false && $status == 'valid' ) { ?>
										<span style="color:green;"><?php _e( 'active', $this->textdomain ); ?></span>
										<?php wp_nonce_field( 'wpjobster_'.$this->short_slug.'_nonce', 'wpjobster_'.$this->short_slug.'_nonce' ); ?>

									<?php } else { ?>

									<?php } ?>
								</td>
							</tr>
							<tr valign="top">
								<td valign=top width="22"></td>
								<td></td>
								<td><?php submit_button( __( 'Save Options', $this->textdomain ), 'secondary' ); ?></td>
							</tr>
						</tbody>
					</table>

				</form>
			</div>
			<?php
		}

		public function wpjobster_register_option() {
			// creates our settings in the options table
			register_setting('wpjobster_'.$this->short_slug.'_license', 'wpjobster_'.$this->short_slug.'_license_key', 'wpjobster_sanitize_license' );
		}

		public function wpjobster_sanitize_license( $new ) {
			$old = get_option( 'wpjobster_'.$this->short_slug.'_license_key' );
			if( $old && $old != $new ) {
				delete_option( 'wpjobster_'.$this->short_slug.'_license_status' ); // new license has been entered, so must reactivate
			}
			return $new;
		}

		public function activate_license() {
			if ( isset( $_POST['wpjobster_'.$this->short_slug.'_license_activate'] ) ) {
				// run a quick security check
			 	if ( ! check_admin_referer( 'wpjobster_'.$this->short_slug.'_nonce', 'wpjobster_'.$this->short_slug.'_nonce' ) )
					return; // get out if we didn't click the Activate button
				// retrieve the license from the database
				if ( isset( $_POST['wpjobster_'.$this->short_slug.'_license_key'] ) ) {
					$license = $_POST['wpjobster_'.$this->short_slug.'_license_key'];
					update_option( 'wpjobster_'.$this->short_slug.'_license_key', $license );
				} else {
					$license = trim( get_option( 'wpjobster_'.$this->short_slug.'_license_key' ) );
					if ( $license == '' && isset( $this->license ) ) {
						$license = trim( $this->license );
						update_option( 'wpjobster_'.$this->short_slug.'_license_key', $license );
					}
				}
				// data to send in our API request
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $license,
					'item_name'  => urlencode( $this->item_name ), // the name of our product in EDD
					'url'        => home_url()
				);
				// Call the custom API.
				$response = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
				// make sure the response came back okay
				if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
					if ( is_wp_error( $response ) ) {
						$message = $response->get_error_message();
					} else {
						$message = __( 'An error occurred, please try again.', $this->textdomain );
					}
				} else {
					$license_data = json_decode( wp_remote_retrieve_body( $response ) );
					if ( false === $license_data->success ) {
						switch( $license_data->error ) {
							case 'expired' :
								$message = sprintf(
									__( 'Your license key expired on %s.', $this->textdomain ),
									date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
								);
								break;
							case 'revoked' :
								$message = __( 'Your license key has been disabled.', $this->textdomain );
								break;
							case 'missing' :
								$message = __( 'Invalid license.', $this->textdomain );
								break;
							case 'invalid' :
							case 'site_inactive' :
								$message = __( 'Your license is not active for this URL.', $this->textdomain );
								break;
							case 'item_name_mismatch' :
								$message = sprintf( __( 'This appears to be an invalid license key for %s.', $this->textdomain ), $this->item_name );
								break;
							case 'no_activations_left':
								$message = __( 'Your license key has reached its activation limit.', $this->textdomain );
								break;
							default :
								$message = __( 'An error occurred, please try again.', $this->textdomain );
								break;
						}
					}
				}
				// Check if anything passed on a message constituting a failure
				if ( ! empty( $message ) ) {
					$base_url = menu_page_url( $this->full_slug, false );
					$redirect = add_query_arg( array( 'sl_'.$this->short_slug.'_activation' => 'false', 'message' => urlencode( $message ), 'active_tab' => 'license' ), $base_url );
					wp_redirect( $redirect );
					exit();
				}
				// $license_data->license will be either "valid" or "invalid"
				update_option( 'wpjobster_'.$this->short_slug.'_license_status', $license_data->license );
				$base_url = menu_page_url( $this->full_slug, false );
				$redirect = add_query_arg( array( 'active_tab' => 'license' ), $base_url );
				wp_redirect( $redirect );
				exit();
			}
		}

		public function deactivate_license() {

			if ( isset( $_POST['wpjobster_'.$this->short_slug.'_license_deactivate'] ) ) {
				// run a quick security check
			 	if ( ! check_admin_referer( 'wpjobster_'.$this->short_slug.'_nonce', 'wpjobster_'.$this->short_slug.'_nonce' ) )
					return; // get out if we didn't click the Activate button
				// retrieve the license from the database
				if ( isset( $_POST['wpjobster_'.$this->short_slug.'_license_key'] ) ) {
					$license = $_POST['wpjobster_'.$this->short_slug.'_license_key'];
					update_option( 'wpjobster_'.$this->short_slug.'_license_key', $license );
				} else {
					$license = trim( get_option( 'wpjobster_'.$this->short_slug.'_license_key' ) );
					if ( $license == '' && isset( $this->license ) ) {
						$license = trim( $this->license );
						update_option( 'wpjobster_'.$this->short_slug.'_license_key', $license );
					}
				}


				// data to send in our API request
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'    => $license,
					'item_name'  => urlencode( $this->item_name ), // the name of our product in EDD
					'url'        => home_url()
				);
				// Call the custom API.
				$response = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
				// make sure the response came back okay
				if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
					if ( is_wp_error( $response ) ) {
						$message = $response->get_error_message();
					} else {
						$message = __( 'An error occurred, please try again.', $this->textdomain );
					}
					$base_url = menu_page_url( $this->full_slug, false );
					$redirect = add_query_arg( array( 'sl_'.$this->short_slug.'_activation' => 'false', 'message' => urlencode( $message ), 'active_tab' => 'license' ), $base_url );
					wp_redirect( $redirect );
					exit();
				}
				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				// $license_data->license will be either "deactivated" or "failed"
				if ( $license_data->license == 'deactivated' ) {
					delete_option( 'wpjobster_'.$this->short_slug.'_license_status' );
				}
				$base_url = menu_page_url( $this->full_slug, false );
				$redirect = add_query_arg( array( 'active_tab' => 'license' ), $base_url );
				wp_redirect( $redirect );
				exit();
			}
		}

		public function wpjobster_check_license() {
			global $wp_version;
			// retrieve the license from the database
			$license = trim( get_option( 'wpjobster_'.$this->short_slug.'_license_key' ) );
			if ( $license == '' && isset( $this->license ) ) {
				$license = trim( $this->license );
				update_option( 'wpjobster_'.$this->short_slug.'_license_key', $license );
			}
			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $license,
				'item_name' => urlencode( $this->item_name ),
				'url'       => home_url()
			);
			// Call the custom API.
			$response = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
			if ( is_wp_error( $response ) )
				return false;
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $license_data->license == 'valid' ) {
				echo 'valid'; exit;
				// this license is still valid
			} else {
				echo 'invalid'; exit;
				// this license is no longer valid
			}
		}

		public function wpjobster_admin_notices() {
			if ( isset( $_GET['sl_'.$this->short_slug.'_activation'] ) && ! empty( $_GET['message'] ) ) {
				switch( $_GET['sl_'.$this->short_slug.'_activation'] ) {
					case 'false':
						$message = urldecode( $_GET['message'] );
						?>
						<div class="error">
							<p><?php echo $message; ?></p>
						</div>
						<?php
						break;
					case 'true':
					default:
						// success message
						break;
				}
			}
		}
	}
endif; // end class_exists check
