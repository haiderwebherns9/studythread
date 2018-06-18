<?php

/**
 * This is file is responsible for custom logic needed by all templates. NO
 * admin code should be placed in this file.
 */
Class ajax_login_register_Forgot Extends AjaxLogin {

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
        add_action( 'wp_ajax_nopriv_forgot_submit', array( &$this,'forgot_submit' ) );
        add_action( 'wp_ajax_forgot_submit', array( &$this,'forgot_submit' ) );

        add_shortcode( 'ajax_forgot', array( &$this, 'forgot_shortcode' ) );
    }


    /**
     * Any additional code to be ran during wp_footer
     *
     * If the user is not logged in we display the hidden jQuery UI dialog containers
     */
    public function footer(){
        load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/forgot-dialog.php' );
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
    public function forgot_submit( $email=null, $is_ajax=true ) {

        if ( $is_ajax ) check_ajax_referer('forgot_submit','security');

        $user = array(

            'email'    => empty( $_POST['email'] ) ? $email : sanitize_text_field( $_POST['email'] )
        );

        $valid['email'] = $this->validate_email( $user['email'], false );
        $user_id = null;
        $user_exists = false;

        if ( email_exists( $user['email'] ) ) {
            global $wpdb;

            $user_exists = true;
            $user = get_user_by_email( $user['email'] );

            $user_login = $user->user_login;
            $user_email = $user->user_email;


            /*
			 * Generating Random password using wordpress readymade function
			 */
			$key = get_password_reset_key($user);

            $msg = $this->status('success_reset'); // success

            //create email message
            $message = __('Someone has asked to reset the password for the following site and username.', 'wpjobster') . "\r\n\r\n";
            $message .= get_option('siteurl') . "\r\n\r\n";
            $message .= sprintf(__('Username: %s', 'wpjobster'), $user_login) . "\r\n\r\n";
            $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.', 'wpjobster') . "\r\n\r\n";
            $message .= get_option('siteurl') . "/wp-login.php?action=rp&key={$key}&login=" . rawurlencode($user_login) . "\r\n";
            //send email meassage
            if (FALSE == wp_mail($user_email, sprintf(__('[%s] Password Reset', 'wpjobster'), get_option('blogname')), $message)) {

            }



        } else {
           $msg = $this->status('email_not_in_use');
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
    public function forgot_shortcode(){
        ob_start();
        load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'views/forgot-form.php' );
        return ob_get_clean();
    }

}
new ajax_login_register_Forgot;
