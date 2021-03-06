<?php
/**
 * This is file is responsible for custom logic needed by all templates. NO
 * admin code should be placed in this file.
 */
Class ajax_login_register_Register Extends AjaxLogin {

	/**
	 * Run the following methods when this class is loaded
	 */
	public function __construct(){
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'wp_footer', array( &$this, 'footer' ) );
		parent::__construct();
	}


	/**
	 * During WordPress' init load various methods.
	 */
	public function init(){
		add_action( 'wp_ajax_nopriv_register_submit', array( &$this,'register_submit' ) );
		add_action( 'wp_ajax_register_submit', array( &$this,'register_submit' ) );
		add_shortcode( 'ajax_register', array( &$this, 'register_shortcode' ) );
	}
	/**
	 * Any additional code to be ran during wp_footer
	 *
	 * If the user is not logged in we display the hidden jQuery UI dialog containers
	 */
	public function footer(){
		load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/register-dialog.php' );
	}


	/**
	 * Registers a new user, checks if the user email or name is
	 * already in use.
	 *
	 * @uses check_ajax_referer() http://codex.wordpress.org/Function_Reference/check_ajax_referer
	 * @uses get_user_by_email() http://codex.wordpress.org/Function_Reference/get_user_by_email
	 * @uses get_user_by() http://codex.wordpress.org/Function_Reference/get_user_by
	 * @uses wp_create_user() http://codex.wordpress.org/Function_Reference/wp_create_user
	 *
	 * @param $login
	 * @param $password
	 * @param $email
	 * @param $is_ajax
	 */
	public function register_submit( $login=null, $password=null, $email=null, $phone=null, $is_ajax=true, $g_response=null ) {
         // print_r($_POST);
		  
		if ( $is_ajax ) check_ajax_referer('register_submit','security');

		// TODO consider using wp_generate_password( $length=12, $include_standard_special_chars=false );
		// and wp_mail the users password asking to change it.
		$user = array(
			'login'       => empty( $_POST['login'] ) ? $login : sanitize_text_field( $_POST['login'] ),
			'email'       => empty( $_POST['email'] ) ? $email : sanitize_text_field( $_POST['email'] ),
			'password'    => empty( $_POST['password'] ) ? $password : sanitize_text_field( $_POST['password'] ),
			'country_id'    => empty( $_POST['country_name'] ) ? false : sanitize_text_field( $_POST['country_name'] ),
			'first_name'    => empty( $_POST['tchr_fname'] ) ? false : sanitize_text_field( $_POST['tchr_fname'] ),
			'last_name'    => empty( $_POST['tchr_lname'] ) ? false : sanitize_text_field( $_POST['tchr_lname'] ),
			'teacher_college'    => empty( $_POST['tchr_atnd_colg'] ) ? false : sanitize_text_field( $_POST['tchr_atnd_colg'] ),
			'teacher_education'    => empty( $_POST['tchr_edu'] ) ? false : sanitize_text_field( $_POST['tchr_edu'] ),
			'teacher_degree'    => empty( $_POST['tchr_degree'] ) ? false : sanitize_text_field( $_POST['tchr_degree'] ),
			'Bkash_number'    => empty( $_POST['tchr_bksh_no'] ) ? false : sanitize_text_field( $_POST['tchr_bksh_no'] ),
			'fb_id'       => empty( $_POST['fb_id'] ) ? false : sanitize_text_field( $_POST['fb_id'] ),
			'cell_number' => empty( $_POST['cell_number'] ) ? $phone : sanitize_text_field( $_POST['cell_number'] ),
			'g_response'  => empty( $_POST['g-recaptcha-response'] ) ? $g_response : $_POST['g-recaptcha-response']
		);
		$valid['email'] = $this->validate_email( $user['email'], false );
		$valid['username'] = $this->validate_username( $user['login'], false );
		$valid['phone_blank'] = $this->validate_phone_number( $user['cell_number'], false );
		$valid['phone'] = $this->validate_phone_exists( $user['cell_number'], false );
		$valid['captcha'] = $this->validate_reCaptcha( $user['g_response'], false );
		$user_id = null;
		if ( $valid['username']['code'] == 'error' ){
			$msg = $this->status('invalid_username'); // invalid user
		} else if ( $valid['email']['code'] == 'error' ) {
			$msg = $this->status('invalid_username'); // invalid user
		} else if ( $valid['phone_blank']['code'] == 'error' ){
			$msg = $this->status('phone_empty'); // phone empty
		} else if ( $valid['phone']['code'] == 'error' ){
			$msg = $this->status('phone_exists'); // phone exist
		} else if( get_option( 'wpjobster_enable_user_reCaptcha' ) == 'yes' && $valid['captcha']['code'] == 'error' ) {
			$msg = $this->status('captcha_invalid'); // captcha invalid
		} else {

			$user_id = wp_create_user( $user['login'], $user['password'], $user['email'] );

			do_action( 'zm_ajax_login_after_successfull_registration', $user_id );

			if ( ! is_wp_error( $user_id ) ) {
				
				update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
				update_user_meta( $user_id, 'fb_id', $user['fb_id'] );
                add_user_meta( $user_id, 'country_id', $user['country_id']); 
				update_user_meta( $user_id, 'first_name', $user['first_name']); 
				update_user_meta( $user_id, 'last_name', $user['last_name']); 
				add_user_meta( $user_id, 'teacher_college', $user['teacher_college']); 
				add_user_meta( $user_id, 'teacher_education', $user['teacher_education']); 
				add_user_meta( $user_id, 'teacher_degree', $user['teacher_degree']); 
				add_user_meta( $user_id, 'Bkash_number', $user['Bkash_number']); 
				if (get_option('ajax_login_register_phone_number') == 'on') {
					update_user_meta( $user_id, 'cell_number', $user['cell_number'] );
				}

				do_action( 'zm_ajax_login_register_extra_fields_update', $user_id );

				if ( is_multisite() ){
					$this->multisite_setup( $user_id );
				}

				wp_update_user( array( 'ID' => $user_id, 'role' => 'subscriber' ) );
				$wp_signon = wp_signon( array( 'user_login' => $user['login'], 'user_password' => $user['password'], 'remember' => true ), false );
				$msg = $this->status('success_registration'); // success
				$redirect = get_option('wpjobster_register_redirection_page');
				if($redirect >=1 ){
					$redirect_link = get_permalink($redirect);
					$msg['redirect'] = $redirect_link;
				}else{ $redirect_link =""; }
			} else {
				$msg = $this->status('username_exists'); // invalid user
			}
		}

		if ( $is_ajax ) {
			wp_send_json( $msg );
		} else {
			return $msg;
		}
	}


	/**
	 * Load the login shortcode
	 */
	public function register_shortcode(){
		ob_start();
		load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/register-form.php' );
		return ob_get_clean();
	}


	public function multisite_setup( $user_id=null ){
		return add_user_to_blog( get_current_blog_id(), $user_id, 'subscriber');
	}


	// Create Facebook User
	public function create_facebook_user( $user=array() ){

		$user['user_pass'] = wp_generate_password();
		$user['user_registered'] = date('Y-m-d H:i:s');
		$user['role'] = "subscriber";

		$user_id = wp_insert_user( $user );

		if ( is_wp_error( $user_id ) ){
			return $user_id;
		} else {
			// Store random password as user meta
			$meta_id = add_user_meta( $user_id, '_random', $user['user_pass'] );

			// Setup this user if this is Multisite/Networking
			if ( is_multisite() ){
				$this->multisite_setup( $user_id );
			}
		}

		return get_user_by( 'id', $user_id );
	}
}
new ajax_login_register_Register;
