<?php

/**
 * Our abstract class for zM Ajax Login.
 *
 * This class is designed to reduce and factor out the shared details between our classes.
 * Thus allowing us to focus on as few concepts at a time.
 */
abstract Class AjaxLogin {

	public $scripts = array();

	/**
	 * WordPress hooks to be ran during init
	 */
	public function __construct(){

		add_action( 'wp_head', array( &$this, 'header' ) );

		add_action( 'wp_ajax_nopriv_validate_email', array( &$this, 'validate_email' ) );
		add_action( 'wp_ajax_validate_email', array( &$this, 'validate_email' ) );

		add_action( 'wp_ajax_nopriv_validate_email_exists', array( &$this, 'validate_email_exists' ) );
		add_action( 'wp_ajax_validate_email_exists', array( &$this, 'validate_email_exists' ) );

		add_action( 'wp_ajax_nopriv_validate_phone_number', array( &$this, 'validate_phone_number' ) );
		add_action( 'wp_ajax_validate_phone_number', array( &$this, 'validate_phone_number' ) );

		add_action( 'wp_ajax_nopriv_validate_phone_exists', array( &$this, 'validate_phone_exists' ) );
		add_action( 'wp_ajax_validate_phone_exists', array( &$this, 'validate_phone_exists' ) );

		add_action( 'wp_ajax_nopriv_validate_reCaptcha', array( &$this, 'validate_reCaptcha' ) );
		add_action( 'wp_ajax_validate_reCaptcha', array( &$this, 'validate_reCaptcha' ) );

		add_action( 'wp_ajax_nopriv_validate_username', array( &$this, 'validate_username' ) );
		add_action( 'wp_ajax_validate_username', array( &$this, 'validate_username' ) );

		add_action( 'wp_ajax_nopriv_load_template', array( &$this, 'load_template' ) );
		add_action( 'wp_ajax_load_template', array( &$this, 'load_template' ) );
	}


	/**
	 * Any additional code to be ran during wp_head
	 *
	 * Prints the ajaxurl in the html header.
	 * Prints the meta tags template.
	 */
	public function header(){
		load_template( plugin_dir_path( dirname( __FILE__ ) ) . "views/meta-tags.php" );
	}


	/**
	 * Build the settings array
	 *
	 * @todo use add_settings_field()
	 */
	public function get_settings(){
		$settings['advanced_usage'] = array(
				array(
					'key' => 'ajax_login_register_advanced_usage_login',
					'label' => __('Login Handle','wpjobster'),
					'type' => 'text',
					'description' => sprintf('%s <code>%s</code>', __('Type the class name or ID of the element you want to launch the dialog box when clicked, example','wpjobster'), '.login-link')
					),
				array(
					'key' => 'ajax_login_register_advanced_usage_register',
					'label' => __('Register Handle','wpjobster'),
					'type' => 'text',
					'description' => sprintf('%s <code>%s</code>',__('Type the class name or ID of the element you want to launch the dialog box when clicked, example','wpjobster'), '.register-link')
					),
				array(
					'key' => 'ajax_login_register_advanced_usage_forgot',
					'label' => __('Forgot Handle','wpjobster'),
					'type' => 'text',
					'description' => sprintf('%s <code>%s</code>',__('Type the class name or ID of the element you want to launch the dialog box when clicked, example','wpjobster'), '.forgot-password-handle')
					),
				array(
					'key' => 'ajax_login_register_additional_styling',
					'label' => __('Additional Styling','wpjobster'),
					'type' => 'textarea',
					'description' => __('Type your custom CSS styles that are applied to the dialog boxes.','wpjobster')
					),
				array(
					'key' => 'ajax_login_register_redirect',
					'label' => __('Redirect After Login URL','wpjobster'),
					'type' => 'text',
					'description' => sprintf( '%s <code>%s</code>', __('Enter the URL or slug you want users redirected to after login, example: ','wpjobster'), 'http://example.com/, /dashboard/, /wp-admin/' )
					),
				array(
					'key' => 'ajax_login_register_default_style',
					'label' => __('Form Layout','wpjobster'),
					'type' => 'text',
					'description' => ''
					)
				);

		$settings['general'] = array(
				array(
					'key' => 'ajax_login_register_keep_me_logged_in',
					'label' => __('Disable "keep me logged in"', 'wpjobster'),
					'description' => __('Use this option to disable the check box shown to keep users logged in.','wpjobster')
				),
				array(
					'key' => 'ajax_login_register_phone_number',
					'label' => __('Enable Phone Number','wpjobster'),
					'description' => __('Display the Phone Number input on the register form.','wpjobster')
				),
				array(
					'key' => 'ajax_login_register_user_email',
					'label' => __('Allow Login with Email','wpjobster'),
					'description' => __('Use this option to enable login with user email address','wpjobster')
				)
			);

		return $settings;
	}


	/**
	 * Generates the needed markup for a given form field.
	 *
	 * @param $type string text, textarea
	 * @param $key string used for the form field "name" and "id"
	 * @param $extras array containing the following keys: 'class','attributes'
	 * @todo use add_settings_field()
	 */
	public function build_input( $type=null, $key=null, $extras=null ){

		switch( $type ){
			case 'textarea':
				$field = '<textarea name="' . $key . '" id="' . $key . '" rows="10" cols="80" class="code">' . wp_kses( get_option( $key ),'' ) . '</textarea>';
				break;

			case 'text':
				$field = '<input type="text" name="' . $key . '" id="' . $key . '" class="regular-text" value="' . esc_attr( get_option( $key ) ) . '" />';
				break;

			case 'select':
				$field = 'select here';
				break;
		}
		return $field;
	}


	/**
	 * Check if an email is "valid" using PHPs filter_var & WordPress
	 * email_exists();
	 *
	 * @param $email (string) Emailt to be validated
	 * @param $is_ajax (bool)
	 * @todo check ajax refer
	 */
	public function validate_email( $email=null, $is_ajax=true ) {

		$email = is_null( $email ) ? $email : $_POST['email'];

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$msg = $this->status('email_invalid');
		} else if ( email_exists( $email ) ){
			$msg = $this->status('email_in_use');
		} else {
			$msg = $this->status('email_valid');
		}

		if ( $is_ajax ){
			print json_encode( $msg );
			die();
		} else {
			return $msg;
		}
	}


	/**
	 * Check if an email is "valid" using PHPs filter_var & WordPress
	 * email_exists();
	 *
	 * @param $email (string) Emailt to be validated
	 * @param $is_ajax (bool)
	 * @todo check ajax refer
	 */
	public function validate_email_exists( $email=null, $is_ajax=true ) {

		$email = is_null( $email ) ? $email : $_POST['email'];

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$msg = $this->status('email_invalid');
		} else if ( ! email_exists( $email ) ){
			$msg = $this->status('email_not_in_use');
		} else {
			$msg = $this->status('email_valid');
		}

		if ( $is_ajax ){
			print json_encode( $msg );
			die();
		} else {
			return $msg;
		}
	}

		/**
	 * Check if a phone is "not blank" using PHPs filter_var & WordPress
	 *
	 * @param $phone (string) Phone to be validated
	 * @param $is_ajax (bool)
	 * @todo check ajax refer
	 */
	public function validate_phone_number( $phone=null, $is_ajax=true ) {
		if ( get_option('wpjobster_enable_phone_number') == 'yes' ) {
			if( get_option('wpjobster_phone_number_mandatory') == 'yes' ) {
				if ( $_POST['cell_number'] == '' ) {
					$msg = $this->status('phone_empty');
				} else {
					$msg = $this->status('phone_valid');
				}
			} else {
				$msg = $this->status('phone_valid');
			}
		} else {
			$msg = $this->status('phone_valid');
		}

		if ( $is_ajax ){
			wp_send_json( $msg );
		} else {
			return $msg;
		}
	}

	/**
	 * Check if a phone is "valid" using PHPs filter_var & WordPress
	 *
	 * @param $phone (string) Phone to be validated
	 * @param $is_ajax (bool)
	 * @todo check ajax refer
	 */
	public function validate_phone_exists( $phone=null, $is_ajax=true ) {

		$post_phone = ( isset( $_POST['cell_number'] ) ? $_POST['cell_number'] : '' );
		$phone = $phone ? $phone : $post_phone;

		if (strpos($phone, '+') === false) {
			$phone = "+".trim($phone);
		}else{
			$phone = trim($phone);
		}

		global $wpdb;
		$results = $wpdb->get_results('select * from ' . $wpdb->prefix . 'usermeta where meta_key = "cell_number" and meta_value = "' . $phone . '"');
		if ( $results ) {
			$msg = $this->status('phone_exists');
		}else{
			$msg = $this->status('phone_valid');
		}

		if ( $is_ajax ){
			wp_send_json( $msg );
		} else {
			return $msg;
		}
	}

	public function validate_reCaptcha( $captcha=null, $is_ajax=true ) {
		$post_g_captcha = ( isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '' );
		$g_captcha = $captcha ? $captcha : $post_g_captcha;

		$parameters = array(
			'secret' => get_option( 'wpjobster_recaptcha_api_secret' ),
			'response' => $g_captcha
		);
		$url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($parameters);

		$response = wpj_recaptcha_open_url( $url );
		$json_response = json_decode( $response, true );

		$captcha_success = json_decode( $response, true );

		if ( $captcha_success['success'] == false ) {
			$msg = $this->status('captcha_invalid');
		} else {
			$msg = $this->status('captcha_valid');
		}

		if ( $is_ajax ){
			wp_send_json( $msg );
		} else {
			return $msg;
		}
	}


	/**
	 * Process request to pass variables into WordPress' validate_username();
	 *
	 * @uses validate_username()
	 * @param $username (string)
	 * @param $is_ajax (bool) Process as an AJAX request or not.
	 */
	public function validate_username( $username=null, $is_ajax=true ) {

		$username = empty( $_POST['login'] ) ? esc_attr( $username ) : $_POST['login'];

		if ( validate_username( $username ) ) {
			$user_id = username_exists( $username );
			if ( $user_id ){
				$msg = $this->status('username_exists');
			} else {
				$msg = $this->status('valid_username');
			}
		} else {
			$msg = $this->status('invalid_username');
		}



		if ( $is_ajax ){
			wp_send_json( $msg );
		} else {
			return $msg;
		}
	}


	/**
	 * Load the login form via an AJAX request.
	 *
	 * @package AJAX
	 */
	public function load_template(){
		check_ajax_referer( $_POST['referer'],'security');
		load_template( plugin_dir_path( dirname( __FILE__ ) ) . "views/" . $_POST['template'] . '.php' );
		die();
	}


	/**
	 * Validation status responses
	 */
	static function status( $key=null, $value=null ){

		$status = array(

			'valid_username' => array(
				'description' => null,
				'cssClass' => 'noon',
				'code' => 'success'
				),
			'username_exists' => array(
				'description' => __('Username already exists', 'wpjobster'),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'invalid_username' => array(
				'description' => __( 'Invalid username', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'username_does_not_exists' => array(
				'description' => __( 'Username does not exists', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'phone_empty' => array(
				'description' => __('Please enter phone number', 'wpjobster'),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'phone_exists' => array(
				'description' => __('Phone number already exists', 'wpjobster'),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'phone_valid' => array(
				'description' => null,
				'cssClass' => 'noon',
				'code' => 'success'
				),
			'captcha_invalid' => array(
				'description' => __('Please verify the captcha!', 'wpjobster'),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'captcha_valid' => array(
				'description' => null,
				'cssClass' => 'noon',
				'code' => 'success'
				),

			'incorrect_password' => array(
				'description' => __( 'Wrong Password', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'passwords_do_not_match' => array(
				'description' => __('Passwords do not match.','wpjobster'),
				'cssClass' =>'error-container',
				'code' => 'error'
				),

			'email_valid' => array(
				'description' => null,
				'cssClass' => 'noon',
				'code' => 'success'
				),
			'email_invalid' => array(
				'description' => __( 'Invalid Email', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'email_in_use' => array(
				'description' => __( 'Email already in use', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),
			'email_not_in_use' => array(
				'description' => __( 'Email not in use', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				),

			'success_login' => array(
				'description' => __( 'Success! One moment while we log you in...', 'wpjobster' ),
				'cssClass' => 'success-container',
				'code' => 'success_login'
				),
			'success_registration' => array(
				'description' => __( 'Success! One moment while we log you in...', 'wpjobster' ),
				'cssClass' => 'noon',
				'code' => 'success_registration'
				),
			'success_reset' => array(
				'description' => __( 'Success! Please check your email for more instructions...', 'wpjobster' ),
				'cssClass' => 'success-container',
				'code' => 'success_reset'
				),
			'something_wrong' => array(
				'description' => __( 'Something went wrong... Please try again.', 'wpjobster' ),
				'cssClass' => 'error-container',
				'code' => 'error'
				)
			);

		$status = apply_filters( 'ajax_login_register_status_codes', $status );

		if ( empty( $value ) ){
			return $status[ $key ];
		} else {
			return $status[ $key ][ $value ];
		}
	}
}
