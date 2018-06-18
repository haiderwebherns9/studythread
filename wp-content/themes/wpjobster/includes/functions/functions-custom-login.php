<?php
//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//  Custom Login Register
//
//------------------------------------------------

function wpjobster_custom_login_header() {
	get_header();
	echo '
	<style type="text/css">
		body, html {
			background: #f9f9f9 !important;
			font-family: "Open Sans", sans-serif;
		}

		#login > h1 { display:none; }
		#login > #backtoblog { display:none; }
		#login > #nav { display: none; }
		#login .forgetmenot { display: none; }
		#login #reg_passmail { display: none; }

		#login #login_error {
			background: #df3f3f;
			color: #fff;
			padding: 20px 25px;
			margin-top: 0px;
			border-radius: 0px;
			margin-bottom: 20px;
			overflow: hidden;
			text-align: center;
		}

		#login .message {
			background: #fff;
			border: 1px solid #f1f1f1;
			padding: 20px 25px;
			margin-top: 0px;
			border-radius: 0px;
			margin-bottom: 20px;
			overflow: hidden;
			text-align: center;
		}

		#loginform, #registerform, #lostpasswordform, #resetpassform {
			margin-bottom: 10px;
			background: #fff;
			border: 1px solid #f1f1f1;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			padding: 20px 20px;
			border-radius: 0px;
		}

		#login input[type="password"], #login input[type="text"], #login input[type="tel"], #login input[type="email"] {
			transition-timing-function: linear;
			width: 100%;
			margin: 0 0 15px 0;
			background: #fff;
			border: 1px solid #ddd;
			border-radius: 3px !important;
			font-size: 15px;
			letter-spacing: normal;
			padding: 10px;
			margin: 0;
			text-transform: none;
			box-sizing: border-box;
			height: 40px;
		}

		#login input#cell_number {
			padding-left: 50px;
		}

		#login input[type="submit"] {
			background: #83C124;
			border-radius: 3px;
			padding: 10px;
			text-transform: uppercase;
			text-shadow: none;
			cursor: pointer;
			font-size: 14px !important;
			letter-spacing: normal;
			width: 100%;
			color: #fff;
			border: 1px solid #60A63A;
			margin: 0;
			font-weight: 800 !important;
		}

		#pass-strength-result.strong, #pass-strength-result.short, #pass-strength-result.bad, #pass-strength-result.good {
			width:100%;
		}

		#login {
			width: 360px;
			padding: 40px 0;
		}

		input#login {
			width: 100%;
			padding: 10px;
		}

		#login form p {
			margin-top: 0px;
			margin-bottom: 20px;
		}

		#login form br {
			display: none;
		}

		#login form h2 {
			font-size: 22px;
			font-weight: 600;
			margin-top: 0.5em;
			margin-bottom: 1.5em;
		}

		a:focus {
			-webkit-box-shadow: none;
			box-shadow: none;
		}

		.intl-tel-input {
			position: relative;
			display: block;
		}

		@media only screen and (max-width:479px) {
			#login {
				width: 300px;
			}
		}

	</style>';

}
add_action('login_head', 'wpjobster_custom_login_header');

function wpjobster_custom_login_footer() {
	get_footer(); ?>
		<script>
			$("#login form p label").contents().filter(function(){
				return this.nodeType === 3;
			}).remove();

			$( "#loginform" ).prepend( "<h2 style='text-align:center;'><?php echo addslashes( __( 'Login', 'wpjobster') ); ?></h2>" );
			$( "#registerform" ).prepend( "<h2 style='text-align:center;'><?php echo addslashes( __( 'Register', 'wpjobster') ); ?></h2>" );
			$( "#lostpasswordform" ).prepend( "<h2 style='text-align:center;'><?php echo addslashes( __( 'Forgot Password', 'wpjobster') ); ?></h2>" );
			$( "#resetpassform" ).prepend( "<h2 style='text-align:center;'><?php echo addslashes( __( 'Reset Password', 'wpjobster') ); ?></h2>" );

			$( "#user_login" ).attr( "placeholder", "<?php echo addslashes( __( 'User Name', 'wpjobster') ); ?>" );
			$( "#loginform #user_login" ).attr( "placeholder", "<?php echo addslashes( __( 'User Name / Email Address', 'wpjobster') ); ?>" );
			$( "#lostpasswordform #user_login" ).attr( "placeholder", "<?php echo addslashes( __( 'User Name / Email Address', 'wpjobster') ); ?>" );
			$( "#lostpasswordform #wp-submit" ).attr( "value", "<?php echo addslashes( __( 'Reset Password', 'wpjobster') ); ?>" );
			$( "#loginform #wp-submit" ).attr( "value", "<?php echo addslashes( __( 'Login', 'wpjobster') ); ?>" );

			$( "#user_email" ).attr( "placeholder", "<?php echo addslashes( __( 'Email', 'wpjobster') ); ?>" );
			$( "#user_pass" ).attr( "placeholder", "<?php echo addslashes( __( 'Password', 'wpjobster') ); ?>" );
			$( "#cell_number" ).attr( "placeholder", "<?php echo addslashes( __( 'Phone Number', 'wpjobster') ); ?>" );
			$( "#user_company" ).attr( "placeholder", "<?php echo addslashes( __( 'Company', 'wpjobster') ); ?>" );
			$( "#user_password" ).attr( "placeholder", "<?php echo addslashes( __( 'Password', 'wpjobster') ); ?>" );
			$( "#user_confirm_password" ).attr( "placeholder", "<?php echo addslashes( __( 'Confirm Password', 'wpjobster') ); ?>" );

			$('#login > h1').remove();
			$('#login > #backtoblog').remove();
			$('#login > #nav').remove();
			$('#login .forgetmenot').remove();
			$('#login #reg_passmail').remove();

			$('#divider_login_outer').appendTo('#login form');
		</script>
<?php }
add_action('login_footer', 'wpjobster_custom_login_footer');

function wpjobster_login_form_extra_fileds() { ?>
	<p class="recaptcha_extra_field">
		<?php wpj_recaptcha_form('default_login'); ?>
	</p>

	<div id="divider_login_outer">
		<div style="margin-top:10px;z-index:10;">
			<div style="float:left">
				<div class="ui checkbox">
					<input class="input" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<label><span style="font-size:11px;" ><?php esc_attr_e('Keep me logged in', 'wpjobster'); ?> </span></label>
				</div>
			</div>
			<div style="float:right">
				<span style="font-size:11px;" >
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Forgot Password', 'wpjobster' ); ?></a>
				</span>
			</div>
		</div>

		<div id="divider_login" style="text-align: center; clear: both;">
			<div class="divider">
				<span><?php _e('or', 'wpjobster'); ?></span>
			</div>

			<?php do_action( 'wordpress_social_login' ); ?>

			<br/><a class="" href="<?php bloginfo('wpurl'); ?>/wp-register.php"><?php _e('Are you a member?', 'wpjobster'); ?></a>
		</div>
	</div>
<?php }
add_action( 'login_form', 'wpjobster_login_form_extra_fileds' );

function wpjobster_custom_login_form_message() {
	return " ";
}
add_filter( 'login_message', 'wpjobster_custom_login_form_message', 11 );

function wpjobster_register_form_extra_fileds() {
	$user_pass = WPJ_Form::post( 'user_password' );
	$user_confirm_password = WPJ_Form::post( 'user_confirm_password' );
	$cell_number = WPJ_Form::post( 'cell_number' );
	$user_company = WPJ_Form::post( 'user_company' );

	do_action('register_form_below_email_field');
	if (get_option('wpjobster_enable_phone_number') == 'yes') { ?>
		<p class="phone_extra_field">
			<label for="cell_number">
				<?php _e( "Phone Number", "wpjobster" ); ?>
				<br>
				<input data-default-country="<?php echo get_option('wpjobster_phone_country_select'); ?>" type="tel" class="cell_number" name="cell_number" id="cell_number" size="30" maxlength="100" value="<?php echo $cell_number; ?>" />
				<input type="hidden" id="country_number" name="country_number" />
			</label>
		</p>
	<?php }

	if (get_option('wpjobster_enable_user_company') == 'yes') { ?>
		<p class="company_extra_field">
			<label for="user_company">
				<?php _e( "Company", "wpjobster" ); ?>
				<br>
				<input type="text" class="user_company" name="user_company" id="user_company" size="30" maxlength="100" value="<?php echo $user_company; ?>" />
			</label>
		</p>
	<?php }

	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] != 'wordpress_social_authenticated' ){ ?>
		<p class="pass_extra_field">
			<label for="user_password">
				<?php _e( "Password", "wpjobster" ); ?>
				<br>
				<input type="password" name="user_password" id="user_password" size="30" maxlength="100" value="<?php echo $user_pass; ?>" />
			</label>
		</p>

		<p class="pass_extra_field">
			<label for="user_confirm_password">
				<?php _e( "Confirm Password", "wpjobster" ); ?>
				<br>
				<input type="password" name="user_confirm_password" id="user_confirm_password" size="30" maxlength="100" value="<?php echo $user_confirm_password; ?>" />
			</label>
		</p>

		<p class="recaptcha_extra_field">
			<?php wpj_recaptcha_form('register_form'); ?>
		</p>

		<div id="divider_login_outer">

			<div id="divider_login" style="text-align: center; clear: both;">
				<?php global $pagenow;
				if (( $pagenow == 'wp-login.php' && isset($_GET['action']) && $_GET['action'] == "register")){ ?>
					<div class="divider">
						<span><?php _e('or', 'wpjobster'); ?></span>
					</div>

					<?php do_action( 'wordpress_social_login' ); ?>

					<br/><a href="<?php bloginfo('wpurl'); ?>/wp-login.php"><?php _e('Already Registered?', 'wpjobster'); ?></a>
				<?php } ?>
			</div>
		</div>
	<?php }
}
add_action( 'register_form', 'wpjobster_register_form_extra_fileds', 1, 1 );

function wpjobster_register_validation( $errors ) {
	// Check the password
	if ( $_POST['user_password'] == '' ) {
		$errors->add( 'empty_password', __( '<strong>ERROR</strong>: Please enter a password.', 'wpjobster' ) );
	} elseif ( $_POST['user_password'] != $_POST['user_confirm_password'] ) {
		$errors->add( 'confirm_password', __( '<strong>ERROR</strong>: Passwords do not match', 'wpjobster' ) );
	}

	if ( get_option('wpjobster_enable_phone_number') == 'yes' ) {
		if( get_option('wpjobster_phone_number_mandatory') == 'yes' ) {
			if ( $_POST['cell_number'] == '' ) {
				$errors->add( 'empty_cell_number', __( '<strong>ERROR</strong>: Please enter phone number.', 'wpjobster' ) );
			}
		}
	}

	if(isset($_POST['cell_number']) && $_POST['cell_number']){
		global $wpdb;
		$results = $wpdb->get_results('select * from `wp_usermeta` where meta_key = "cell_number" and meta_value = "'.$_POST['cell_number'].'"');
		if ( $results ) {
			$errors->add( 'phone_exists', __( '<strong>ERROR</strong>: This phone number is already registered, please choose another one.', 'wpjobster' ) );
		}
	}

	/* reCaptcha */
	if ( get_option( 'wpjobster_enable_user_reCaptcha' ) == 'yes' ) {
		$parameters = array(
			'secret' => get_option( 'wpjobster_recaptcha_api_secret' ),
			'response' => $_POST["g-recaptcha-response"]
		);
		$url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($parameters);

		$response = wpj_recaptcha_open_url( $url );
		$json_response = json_decode( $response, true );

		$captcha_success = json_decode( $response, true );

		if ( $captcha_success['success'] == false ) {
			$errors->add( 'captcha_error', __( '<strong>ERROR</strong>: Please verify the captcha!', 'wpjobster' ) );
		}
	}

	return $errors;
}
add_filter( 'registration_errors', 'wpjobster_register_validation', 10, 3 );

function wpjobster_login_validation( $user, $password ){
	if ( get_option( 'wpjobster_enable_user_reCaptcha' ) == 'yes' ) {
		if ( ! isset( $_POST['_wp_http_referer'] ) ) {
			$post = isset( $_POST["g-recaptcha-response"] ) ? $_POST["g-recaptcha-response"] : '';
			$parameters = array(
				'secret' => get_option( 'wpjobster_recaptcha_api_secret' ),
				'response' => $post
			);
			$url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($parameters);

			$response = wpj_recaptcha_open_url( $url );
			$json_response = json_decode( $response, true );

			$captcha_success = json_decode( $response, true );

			if ( $captcha_success['success'] == false ) {
				return new WP_Error( 'captcha_error', __( '<strong>ERROR</strong>: Please verify the captcha!', 'wpjobster' ) );
			}
		}
	}

	return $user;
}
add_filter( 'wp_authenticate_user', 'wpjobster_login_validation', 10, 2 );

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function register_form_custom_password() {
	$user_pass = WPJ_Form::post( 'user_password', '');
	if($user_pass == '') {
		$user_pass = generateRandomString();
	}
	return $user_pass;
}
add_filter( 'random_password', 'register_form_custom_password' );

function wpjobster_custom_login_redirect() {
	return home_url();
}
add_filter( 'login_redirect', 'wpjobster_custom_login_redirect', 10, 3 );

function wpjobster_custom_user_register( $user_id ) {
	if ($user_id > 0) {

		if (get_option('wpjobster_enable_phone_number') == 'yes') {
			//Save phone number and country number
			update_user_meta($user_id, 'cell_number', $_POST['cell_number']);
		}

		if (get_option('wpjobster_enable_user_company') == 'yes') {
			//Save user company
			update_user_meta($user_id, 'user_company', $_POST['user_company']);
		}

		update_user_meta( $user_id, 'uz_email_verification', 0 );
		$email_key = wpjobster_email_verification_init($user_id);
		wpjobster_registration_completed_functions($user_id);

		wpjobster_send_email_allinone_translated('user_new', $user_id, false, false, false, false, false, false, $email_key);
		wpjobster_send_sms_allinone_translated('user_new', $user_id, false, false, false, false, false, false, $email_key);

		wpjobster_send_email_allinone_translated('user_admin_new', 'admin', $user_id);
		wpjobster_send_sms_allinone_translated('user_admin_new', 'admin', $user_id);
	}
}
add_filter( 'user_register', 'wpjobster_custom_user_register' );
remove_action( 'register_new_user', 'wp_send_new_user_notifications' );

// NEW PASSWORD RESET REDIRECT
function wpjobster_lost_password_reset_redirect( $user, $new_pass ) {
	wp_set_password( $new_pass, $user->ID );
	wp_redirect( wp_login_url() );
	exit;
}
add_action( 'password_reset', 'wpjobster_lost_password_reset_redirect', 10, 2 );

function auto_login_new_user($user_id){
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, false, is_ssl() );

	$redirect = get_option('wpjobster_register_redirection_page');
	if($redirect >=1 ){
		$redirect_link = get_permalink($redirect);
		wp_redirect($redirect_link);
		exit;
	}else{
		wp_redirect(home_url());
		exit;
	}
}

if(isset($_POST['wp-submit']) && $_POST['wp-submit'] == 'Register'){ add_action('user_register','auto_login_new_user'); }

function wpjobster_register_redirect($user_id){ auto_login_new_user($user_id); }

function wpjobster_registration_completed_functions($uid) {

	// user ip
	//$ip = $_SERVER['REMOTE_ADDR'];
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	update_user_meta($uid, 'ip_reg', $ip);

	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "job_ipcache where ipnr='$ip'";
	$r = $wpdb->get_results($s);
	if (count($r) > 0) {
		update_user_meta($uid, 'ip_reg_used', 1);
	}

	// user country
	$user_country = wpjobster_get_country_code_of_ip($ip);
	update_user_meta($uid, 'country_code', $user_country);

	$get_info = json_decode($r[0]->info);

	if( $get_info ){
		// user city
		$user_city = $get_info->cityName;
		update_user_meta($uid, 'city', $user_city);

		//user zipcode
		$user_zipcode = $get_info->zipCode;
		update_user_meta($uid, 'zip', $user_zipcode);
	}

	//user timezone
	$wpjobster_user_time_zone = get_option('wpjobster_user_time_zone');
	if($wpjobster_user_time_zone != 'autodetect'){
		update_user_meta($uid, 'timezone_select', $wpjobster_user_time_zone);
	}else{
		if ( $user_country != '' ) {
			$timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $user_country);
			update_user_meta($uid, 'timezone_select', $timezone[0]);
		}
	}

	// user level (default)
	$wpjobster_default_level_nr = get_option('wpjobster_default_level_nr');
	if (is_numeric($wpjobster_default_level_nr)
		&& $wpjobster_default_level_nr > 0
		&& $wpjobster_default_level_nr <= 3) {
		update_user_meta($uid, 'user_level', $wpjobster_default_level_nr);

		if ($wpjobster_default_level_nr == 3
			|| $wpjobster_default_level_nr == 2) {
			update_user_meta($uid, 'date_toclear', strtotime('+2 month', time()));
		} else {
			update_user_meta($uid, 'date_toclear', strtotime('+1 month', time()));
		}

	} else {
		update_user_meta($uid, 'user_level', "0");
		update_user_meta($uid, 'date_toclear', strtotime('+1 month', time()));
	}

}

function wpjobster_social_login_form($provider_id, $provider_name, $authenticate_url) {
	$return = '<a rel="nofollow" href="' . $authenticate_url . '" title="' . sprintf( __("Connect with %s", 'wpjobster'), $provider_name ) . '" class="wp-social-login-provider wp-social-login-provider-' . strtolower( $provider_id ) . '" data-provider="' . $provider_id . '">' . sprintf( __("Connect with %s", 'wpjobster'), $provider_name ) . '</a>';
	return $return;
}
add_filter('wsl_render_auth_widget_alter_provider_icon_markup', 'wpjobster_social_login_form', 10, 3);

function wpjobster_email_ajax_registration($user_id) {
	if ($user_id > 0) {
		update_user_meta( $user_id, 'uz_email_verification', 0 );
		$email_key = wpjobster_email_verification_init($user_id);
		wpjobster_registration_completed_functions($user_id);

		wpjobster_send_email_allinone_translated('user_new', $user_id, false, false, false, false, false, false, $email_key);
		wpjobster_send_sms_allinone_translated('user_new', $user_id, false, false, false, false, false, false, $email_key);

		wpjobster_send_email_allinone_translated('user_admin_new', 'admin', $user_id);
		wpjobster_send_sms_allinone_translated('user_admin_new', 'admin', $user_id);
	}
}

if(!function_exists('wpjobster_do_login_scr')) {
	function wpjobster_sitemile_filter_ttl($title){
		global $skm_ttl;
		return $real_ttl." - ".get_bloginfo('sitename');
	}
}

if ( !function_exists( 'wpj_recaptcha_open_url' ) ) {
	function wpj_recaptcha_open_url( $url ) {
		if ( function_exists( 'curl_init' ) && function_exists( 'curl_setopt' ) && function_exists( 'curl_exec' ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			$response = curl_exec( $ch );
			curl_close( $ch );
		} else {
			$response = file_get_contents( $url );
		}
		return trim( $response );
	}
}

if ( !function_exists( 'wpj_recaptcha_form' ) ) {
	function wpj_recaptcha_form( $identifier = 'g-recaptcha' ) {
		if ( get_option( 'wpjobster_enable_user_reCaptcha' ) == 'yes' ) { ?>
			<script src='https://www.google.com/recaptcha/api.js?onload=reCaptchaCallback'></script>

			<div class="g-recaptcha-class" data-sitekey="<?php echo get_option( 'wpjobster_recaptcha_api_key' ); ?>" id="<?php echo $identifier; ?>"></div>
			<input type="hidden" name="g-recaptcha-response" id="<?php echo $identifier; ?>_response">

			<script type="text/javascript">
				var key = '<?php echo get_option( 'wpjobster_recaptcha_api_key' ); ?>';
				var identifier = '<?php echo $identifier; ?>';

				var reCaptchaCallback = function() {
					if ( jQuery('#' + identifier).length ) {
						grecaptcha.render( identifier, { 'sitekey' : key, 'callback' : 'var_' + identifier + '_callback' } );
					}
				};
				window['var_' + identifier + '_callback'] = function( response ) {
					jQuery( '#' + identifier + '_response' ).val( response );
				};
			</script>
		<?php }
	}
}
