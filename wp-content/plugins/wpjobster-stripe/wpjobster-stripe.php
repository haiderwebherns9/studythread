<?php
/**
 * Plugin Name: WPJobster Stripe Gateway
 * Plugin URI: http://wpjobster.com/
 * Description: This plugin extends Jobster Theme to accept payments with Stripe.
 * Author: WPJobster
 * Author URI: http://wpjobster.com/
 * Version: 2.1
 *
 * Copyright (c) 2016 WPJobster
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !class_exists("WPJobster_Stripe_Loader") ) {

// INCLUDE CLASS FOR CREATING LICENSE FIELDS
if( !class_exists( 'WPJ_Gateway_License' ) ) {
    include( 'updater/plugin-updater.php' );
}
$wpj_stripe_license = new WPJ_Gateway_License(
    array(
		'file'       => __FILE__,
		'item_name'  => 'Stripe',
		'version'    => '2.1',
		'author'     => 'WPJobster',
		'api_url'    => 'http://wpjobster.com',
		'short_slug' => 'stripe',
		'full_slug'  => 'wpjobster_stripe'
    )
);


/**
 * Required minimums
 */
define( 'WPJOBSTER_STRIPE_MIN_PHP_VER', '5.4.0' );


class WPJobster_Stripe_Loader {

	/**
	 * @var Singleton The reference the *Singleton* instance of this class
	 */
	private static $instance;
	public $priority, $unique_slug;


	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Notices (array)
	 * @var array
	 */
	public $notices = array();


	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->priority = 4085;           // 100, 200, 300 [...] are reserved
		$this->unique_slug = 'stripe';    // this needs to be unique

                global $wpdb;
		$this->_wpdb=$wpdb;

                $this->pKey = get_option('wpjobster_pstripe_key');
		$this->key  = get_option('wpjobster_sstripe_key');

		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
		add_action( 'plugins_loaded', array( $this, 'init_gateways' ), 0 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

		add_action( 'wpjobster_taketo_'.$this->unique_slug.'_gateway', array( $this, 'taketogateway_function' ), 10,2 );
		add_action( 'wpjobster_processafter_'.$this->unique_slug.'_gateway', array( $this, 'processgateway_function' ), 10,2 );

                add_action( 'wpjobster_purchase_subscription_add_payment_method', array( $this, 'purchase_subscription_add_payment_method_function' ) );

		if ( isset( $_POST[ 'wpjobster_save_' . $this->unique_slug ] ) ) {
			add_action( 'wpjobster_payment_methods_action', array( $this, 'save_gateway' ), 11 );
		}

                $plugin_path = plugin_dir_path(__FILE__);
                require_once $plugin_path . "stripe-new/Stripe.php";
		\Stripe\Stripe::setApiKey($this->key);

	}

	/**
	 * Initialize the gateway. Called very early - in the context of the plugins_loaded action
	 *
	 * @since 1.0.0
	 */
	public function init_gateways() {
		load_plugin_textdomain( 'wpjobster-stripe', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
		add_filter( 'wpjobster_payment_gateways', array( $this, 'add_gateways' ) );
	}


	/**
	 * Add the gateways to WPJobster
	 *
	 * @since 1.0.0
	 */
	public function add_gateways( $methods ) {
		$methods[$this->priority] =
			array(
				'label'           => __( 'Stripe', 'wpjobster-stripe' ),
                                'unique_id'       => $this->unique_slug,
				'action'          => 'wpjobster_taketo_'.$this->unique_slug.'_gateway', // action called when user request to send payment to gateway
				'response_action' => 'wpjobster_processafter_'.$this->unique_slug.'_gateway', //action called when any response comes from gateway after payment
			);
		add_action( 'wpjobster_show_paymentgateway_forms', array( $this, 'show_gateways' ), $this->priority, 3 );

		return $methods;
	}


	/**
	 * Save the gateway settings in admin
	 *
	 * @since 1.0.0
	 */
	public function save_gateway() {
		if ( isset( $_POST['wpjobster_save_' . $this->unique_slug] ) ) {

			// _enable and _button_caption are mandatory
			update_option( 'wpjobster_' . $this->unique_slug . '_enable',           trim( $_POST['wpjobster_' . $this->unique_slug . '_enable'] ) );
			update_option( 'wpjobster_' . $this->unique_slug . '_button_caption',   trim( $_POST['wpjobster_' . $this->unique_slug . '_button_caption'] ) );

                        global $payment_type_enable_arr;
                        foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
				if( $payment_type_enable_key != 'job_purchase' ) {
					if( isset( $_POST['wpjobster_'.$this->unique_slug.'_enable_'.$payment_type_enable_key] ) )
						update_option( 'wpjobster_'.$this->unique_slug.'_enable_'.$payment_type_enable_key, trim( $_POST['wpjobster_'.$this->unique_slug.'_enable_'.$payment_type_enable_key] ) );
				}
			}

			// you can add here any other information that you need from the user
			update_option( 'wpjobster_stripe_enablesandbox',    trim( $_POST['wpjobster_stripe_enablesandbox'] ) );
                        update_option( 'wpjobster_pstripe_key',              trim( $_POST['wpjobster_pstripe_key'] ) );
                        update_option( 'wpjobster_sstripe_key',              trim( $_POST['wpjobster_sstripe_key'] ) );
                        update_option( 'wpjobster_stripe_logo_url',          trim( $_POST['wpjobster_stripe_logo_url'] ) );
                        update_option( 'wpjobster_stripe_enablepopup',       trim( $_POST['wpjobster_stripe_enablepopup'] ) );
                        update_option( 'wpjobster_stripe_default_language',  trim( $_POST['wpjobster_stripe_default_language'] ) );
                        update_option( 'wpjobster_stripe_save_card_info',   trim( $_POST['wpjobster_stripe_save_card_info'] ) );
                        update_option( 'wpjobster_stripe_developed_in_theme',trim($_POST['wpjobster_stripe_developed_in_theme'] ) );
                        update_option( 'wpjobster_stripe_marketplace_name',  trim( $_POST['wpjobster_stripe_marketplace_name'] ) );
                        update_option( 'wpjobster_stripe_success_page',     trim( $_POST['wpjobster_stripe_success_page'] ) );
			update_option( 'wpjobster_stripe_failure_page',     trim( $_POST['wpjobster_stripe_failure_page'] ) );

			echo '<div class="updated fade"><p>' . __( 'Settings saved!', 'wpjobster-stripe' ) . '</p></div>';
		}
	}


	/**
	 * Display the gateway settings in admin
	 *
	 * @since 1.0.0
	 */
	public function show_gateways( $wpjobster_payment_gateways, $arr, $arr_pages ) {
		$tab_id = get_tab_id( $wpjobster_payment_gateways );
		?>
		<div id="tabs<?php echo $tab_id?>">
			<form method="post" enctype="multipart/form-data" action="<?php bloginfo( 'siteurl' ); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs<?php echo $tab_id; ?>">
			<table width="100%" class="sitemile-table">
				  <?php do_action('wpj_stripe_add_tab_content'); ?>
				  <tr>
					<td></td>
					<td><h2><?php _e("General Settings", "wpjobster-stripe"); ?></h2></td>
					<td></td>
				  </tr>
                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
                                    <td valign="top"><?php _e( 'Stripe Gateway Note:', 'wpjobster-stripe' ); ?></td>
                                    <td>
                                        <p>
                                            <?php _e( 'Please set your payment HTTP Notification URLs on your Stripe Dashboard as below if you want to use stripe for subscription payment', 'wpjobster-stripe' ); ?>
                                        </p>
                                        <p>
                                            <strong><?php _e( 'Webhooks -> Settings Tab -> Add endpoint', 'wpjobster-stripe' ); ?></strong><br>
                                            <code><?php echo get_bloginfo( 'url' ) . '/?payment_response=stripe&payment_type_name=subscription'; ?></code>
                                        </p>
                                    </td>
                                </tr>
				<tr>
					<?php // _enable and _button_caption are mandatory ?>
					<td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Enable/Disable Stripe payment gateway', 'wpjobster-stripe') ); ?></td>
					<td width="200"><?php _e( 'Enable:', 'wpjobster-stripe' ); ?></td>
					<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_stripe_enable', 'no' ); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Enable/Disable Stripe test mode.', 'wpjobster-stripe' ) ); ?></td>
					<td width="200"><?php _e( 'Enable Test Mode:', 'wpjobster-stripe' ); ?></td>
					<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_stripe_enablesandbox', 'no' ); ?></td>
				</tr>
                                <?php global  $payment_type_enable_arr; foreach( $payment_type_enable_arr as $payment_type_enable_key => $payment_type_enable ) {
					if($payment_type_enable_key != 'job_purchase'){ ?>
					  <tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet($payment_type_enable['hint_label']); ?></td>
						<td width="200"><?php echo $payment_type_enable['enable_label']; ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_'.$this->unique_slug.'_enable_'.$payment_type_enable_key); ?></td>
					  </tr>
				<?php }//end if
				} // end foreach ?>
				<tr>
					<?php // _enable and _button_caption are mandatory ?>
					<td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Put the Stripe button caption you want user to see on purchase page', 'wpjobster-stripe' ) ); ?></td>
					<td><?php _e( 'Stripe Button Caption:', 'wpjobster-stripe' ); ?></td>
					<td><input type="text" size="45" name="wpjobster_<?php echo $this->unique_slug; ?>_button_caption" value="<?php echo get_option( 'wpjobster_' . $this->unique_slug . '_button_caption' ); ?>" /></td>
				</tr>

                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Your Stripe Publishable key', 'wpjobster-stripe' ) ); ?></td>
                                    <td ><?php _e('Stripe Publishable key:','wpjobster-stripe'); ?></td>
                                    <td><input type="text" size="45" name="wpjobster_pstripe_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_pstripe_key') ); ?>"/></td>
                                </tr>

                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Your Stripe Secret key', 'wpjobster-stripe' ) ); ?></td>
                                    <td ><?php _e('Stripe Secret key:','wpjobster-stripe'); ?></td>
                                    <td><input type="text" size="45" name="wpjobster_sstripe_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_sstripe_key') ); ?>"/></td>
                                </tr>

				<tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
                                    <td ><?php _e('Stripe Form Marketplace Name:','wpjobster-stripe'); ?></td>
                                    <td><input type="text" size="45" name="wpjobster_stripe_marketplace_name" value="<?php echo get_option('wpjobster_stripe_marketplace_name'); ?>"/></td>
                                </tr>

                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet(__('Eg: http://your-site-url.com/images/logo.jpg','wpjobster-stripe')); ?></td>
                                    <td ><?php _e('Stripe Form Logo URL:','wpjobster-stripe'); ?></td>
                                    <td><input type="text" size="45" name="wpjobster_stripe_logo_url" value="<?php echo get_option('wpjobster_stripe_logo_url'); ?>"/></td>
                                </tr>
                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet(__("Open in a popup on the purchase-this page")); ?></td>
                                    <td width="200"><?php _e('Enable/Show popup:','wpjobster-stripe'); ?></td>
                                    <td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_stripe_enablepopup', 'no'); ?></td>
                                </tr>
                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Set Stripe checkout default language', 'wpjobster-stripe' ) ); ?></td>
                                    <td ><?php _e( 'Stripe Default Language:', 'wpjobster-stripe' ); ?></td>
                                    <td>
                                        <?php
                                            $arr_stripe_default_language = array(
                                                "auto" => __("Auto",'wpjobster-stripe'),
                                                "zh" => __("Simplified Chinese",'wpjobster-stripe'),
                                                "da" => __("Danish",'wpjobster-stripe'),
                                                "nl" => __("Dutch",'wpjobster-stripe'),
                                                "en" => __("English",'wpjobster-stripe'),
                                                "fi" => __("Finnish",'wpjobster-stripe'),
                                                "fr" => __("French",'wpjobster-stripe'),
                                                "de" => __("German",'wpjobster-stripe'),
                                                "it" => __("Italian",'wpjobster-stripe'),
                                                "ja" => __("Japanese",'wpjobster-stripe'),
                                                "no" => __("Norwegian",'wpjobster-stripe'),
                                                "es" => __("Spanish",'wpjobster-stripe'),
                                                "sv" => __("Swedish",'wpjobster-stripe'),
                                            );
                                            echo wpjobster_get_option_drop_down( $arr_stripe_default_language, 'wpjobster_stripe_default_language', 'auto' );
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Stripe show remember me option', 'wpjobster-stripe' ) ); ?></td>
                                    <td ><?php _e( 'Show remember me option:', 'wpjobster-stripe' ); ?></td>
                                    <td>
                                        <?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_stripe_save_card_info'); ?>
                                    </td>
                                </tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Please select a page to show when Stripe payment successful. If empty, it redirects to the transaction page', 'wpjobster-stripe' ) ); ?></td>
					<td><?php _e( 'Transaction Success Redirect:', 'wpjobster-stripe' ); ?></td>
					<td><?php
					echo wpjobster_get_option_drop_down( $arr_pages, 'wpjobster_' . $this->unique_slug . '_success_page', '', ' class="select2" '); ?>
						</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet( __( 'Please select a page to show when Stripe payment failed. If empty, it redirects to the transaction page', 'wpjobster-stripe' ) ); ?></td>
					<td><?php _e( 'Transaction Failure Redirect:', 'wpjobster-stripe' ); ?></td>
					<td><?php
					echo wpjobster_get_option_drop_down( $arr_pages, 'wpjobster_' . $this->unique_slug . '_failure_page', '', ' class="select2" '); ?></td>
				</tr>
				<tr>
                                        <td>
                                            <input type="hidden" name="wpjobster_stripe_developed_in_theme" value="no" />
                                        </td>
					<td></td>
					<td><input type="submit" name="wpjobster_save_<?php echo $this->unique_slug; ?>" value="<?php _e( 'Save Options', 'wpjobster-stripe' ); ?>" /></td>
				</tr>
				</table>
			</form>
		</div>
		<?php
	}


	/**
	 * Collect all the info that we need and forward to the gateway
	 *
	 * @since 1.0.0
	 */
	public function taketogateway_function( $payment_type, $common_details ) {

                $publishable_stripe_key = get_option('wpjobster_pstripe_key');;
		$secret_stripe_key = get_option('wpjobster_sstripe_key');

                if( $publishable_stripe_key !='' && $secret_stripe_key !='' ) {

		// wpjobster_get_currency() returns the currency selected by the user or the default site currency
		// if the gateway requires a specific currency you can declare it there, like 'USD' or 'EUR'
		// currency conversions are done automatically
		//$currency = wpjobster_get_currency();


		// get_common_details' second parameter ($order_id) as 0 will insert a pending order

		/* Array(
			[price] => 109                              // job price
			[post] => WP_Post Object                    // job object
			[uid] => 3                                  // buyer id
			[pid] => 3318                               // job id
			[selected] => USD                           // selected currency
			[job_title] => Title Example                // job title
			[wpjobster_final_payable_amount] => 134.72  // final price including all taxes
			[order_id] => 768                           // order id
			[current_user] => Object                    // current user object
			[currency] => USD                           // currency
		) */
            if( $payment_type !='subscription' ) {

                if( isset( $common_details['sub_type'] ) && strtolower( $common_details['sub_type'] ) == strtolower( 'Lifetime' ) ){
                    $job_title = $common_details['title'];
		}else{
                    $job_title = $common_details['job_title'];
		}
		$response_url = get_bloginfo('siteurl') . '/?payment_response=stripe&payment_type='.$payment_type;
		$wpjobster_stripe_failure_page_id = get_option("wpjobster_stripe_failure_page");
		$order_id = $common_details['order_id'];

		$price = $common_details['wpjobster_final_payable_amount'];
		$uid = $common_details['uid'];

		$ccnt_url     = get_permalink($wpjobster_stripe_failure_page_id);
		$zero_decimal_currencies = array(
			"BIF",
			"CLP",
			"DJF",
			"GNF",
			"JPY",
			"KMF",
			"KRW",
			"MGA",
			"PYG",
			"RWF",
			"VND",
			"VUV",
			"XAF",
			"XOF",
			"XPF"
		);
		$wpjobster_final_payable_amount=$common_details['wpjobster_final_payable_amount'];//$this->_payable_amount;
		if (!in_array($common_details['selected'], $zero_decimal_currencies)) {
                    $wpjobster_final_payable_amount = $wpjobster_final_payable_amount * 100;
		}

		$wpjobster_stripe_save_card_info = get_option( 'wpjobster_stripe_save_card_info' );
		if ( $wpjobster_stripe_save_card_info == 'yes' ) {
                    $stripe_rememberme = "true";
		} else {
                    $stripe_rememberme = "false";
		}

		$current_user = get_user_by( 'id', $uid );


		//Stripe Request

        ?>
                <html>
                    <head>
                        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
                        <title>Stripe</title>
                        <style>
                            body {
                                text-align:center;
                            }
                            .stripe-button-el {
                                -moz-user-select: none;
                                background: linear-gradient(#7DC5EE, #008CDD 85%, #30A2E4) repeat scroll 0 0 #1275FF;
                                border: 0 none;
                                border-radius: 5px;
                                box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
                                cursor: pointer;
                                display: inline-block;
                                overflow: hidden;
                                padding: 0px;
                                text-decoration: none;
                                visibility: visible !important;
                                margin:300px auto;
                            }

                            .stripe-button-el span {
                                background: linear-gradient(#7DC5EE, #008CDD 85%, #30A2E4) repeat scroll 0 0 #1275FF;
                                border-radius: 4px;
                                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset;
                                color: #FFFFFF;
                                display: block;
                                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                                font-size: 14px;
                                font-weight: bold;
                                height: 30px;
                                line-height: 30px;
                                padding: 0 12px;
                                position: relative;
                                text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
                            }
                        </style>
                        <script type="text/javascript">window.history.forward();</script>
                    </head>
                    <body>
                        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
                        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
                        <script src="https://checkout.stripe.com/checkout.js"></script>

                        <button id="customButton" class="stripe-button-el" type="submit" style="display: none;">
                            <span style="display: block; min-height: 30px;"><?php _e('Please wait...','wpjobster-stripe') ?></span>
                        </button>

                        <script>
                            function tokenisinplace() {
                                if(jQuery('input[name="stripeToken"]').val()!='') {
                                        return true
                                } else {
                                        return false
                                }
                            }

                            jQuery(document).ready(function(e) {
                                jQuery('#customButton').trigger('click');
                            });

                            var handler = StripeCheckout.configure({
                                key: '<?php echo get_option('wpjobster_pstripe_key');  ?>',

                                image: '<?php if (get_option('wpjobster_stripe_logo_url')) { echo get_option('wpjobster_stripe_logo_url'); } else { echo get_template_directory_uri().'/images/stripe-img.png'; } ?>',
                                locale: '<?php if ( get_option( 'wpjobster_stripe_default_language' ) ) { echo get_option( 'wpjobster_stripe_default_language' ); } else { echo 'auto'; } ?>',

                                token: function(token, args) {
                                    // Use the token to create the charge with a server-side script.
                                    // You can access the token ID with `token.id`
                                    jQuery('input[name="stripeToken"]').val(token.id);
                                    jQuery('form[name="form_skrill"]').trigger('submit');
                                }
                            });

                            document.getElementById('customButton').addEventListener('click', function(e) {
                                // Open Checkout with further options
                                handler.open({
                                    name: '<?php if (get_option('wpjobster_stripe_marketplace_name')) { echo get_option('wpjobster_stripe_marketplace_name'); } else { echo get_bloginfo('name'); } ?>',
                                    email: '<?php echo $current_user->user_email; ?>',
                                    billingAddress:false,
                                    description: '<?php echo addslashes( $job_title ); ?>',
                                    amount: <?php echo $wpjobster_final_payable_amount; ?>,
                                    currency:'<?php echo $common_details['selected']; ?>',
                                    allowRememberMe: !!JSON.parse(String("<?php echo $stripe_rememberme; ?>").toLowerCase()),
                                    panelLabel: '<?php  _e('Pay','wpjobster-stripe') ?>  {{amount}}' ,
                                    closed:function(){
                                        var myVar=setInterval(function(){

                                            if(!tokenisinplace()){
                                                    clearInterval(myVar);
                                                    window.history.back()
                                            }
                                        },200);
                                    }
                                });

                                e.preventDefault();
                                jQuery("input#card_number").attr('placeholder','Broj kartice');

                            });

                            jQuery(window).load(function(e) {

                            });
                        </script>

                        <form name="form_skrill" method="POST" action="<?php echo $response_url; ?>">
                            <input type="hidden" name="recipient_description" value="<?php bloginfo('name'); ?>">
                            <!--<input type="hidden" name="language" value="EN">-->
                            <input type="hidden" name="amount" value="<?php echo $wpjobster_final_payable_amount; ?>">
                            <input type="hidden" name="currency" value="<?php echo $common_details['selected']; ?>">
                            <input type="hidden" name="detail1_description" value="Feature job  ">
                            <input type="hidden" name="detail1_text" value="<?php echo "Feature job text"; ?>">
                            <input type="hidden" name="extras" value="<?php echo $order_id; ?>">
                            <input type="hidden" name="sub_type" value="<?php echo $common_details['sub_type']; ?>">
                            <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">
                            <input type="hidden" name="stripeToken" value="">
                        </form>
                    </body>
		</html>
        <?php
            }

            if ( $payment_type == 'subscription' ) {

                        $response_url = get_bloginfo('siteurl') . '/?payment_response=stripe&payment_type='.$payment_type;

			$sub_type                       = isset($common_details['sub_type']) ? $common_details['sub_type'] : '';
			$sub_level                      = isset($common_details['sub_level']) ? $common_details['sub_level'] : '';
			$wpjobster_final_payable_amount = isset($common_details['wpjobster_final_payable_amount']) ? $common_details['wpjobster_final_payable_amount'] : '';
			if( $wpjobster_final_payable_amount == '0' || $wpjobster_final_payable_amount =='' ){
				$wpjobster_final_payable_amount=0.1;
			}
			$price                          = isset($common_details['price']) ? $common_details['price'] : '';
			$selected                       = isset($common_details['selected']) ? $common_details['selected'] : '';
			$current_user_id                = isset($common_details['uid']) ? $common_details['uid'] : '';
			$order_id                       = isset($common_details['order_id']) ? $common_details['order_id'] : '';

			$current_user = get_user_by( 'id', $current_user_id );

			if($sub_level == 'level1'){
				$plan = __("Starter Plan","wpjobster-stripe")." ".ucfirst($sub_type);
				$plan_slug = "starter-plan-".$sub_type;
			}elseif($sub_level == 'level2'){
				$plan = __("Business Plan","wpjobster-stripe")." ".ucfirst($sub_type);
				$plan_slug = "business-plan-".$sub_type;
			}elseif($sub_level == 'level3'){
				$plan = __("Professional Plan","wpjobster-stripe")." ".ucfirst($sub_type);
				$plan_slug = "professional-plan-".$sub_type;
			}else{
				$plan = __("-","wpjobster-stripe");
			}

			$zero_decimal_currencies = array(
				"BIF",
				"CLP",
				"DJF",
				"GNF",
				"JPY",
				"KMF",
				"KRW",
				"MGA",
				"PYG",
				"RWF",
				"VND",
				"VUV",
				"XAF",
				"XOF",
				"XPF"
			);

			if (!in_array($common_details['selected'], $zero_decimal_currencies)) {
				$wpjobster_final_payable_amount = $wpjobster_final_payable_amount * 100;
			}

			$product_name = strtoupper($plan);
			$product_currency = $selected;
			$client_email = isset($current_user->user_email) ? $current_user->user_email : '';
                        ?>

                        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
			<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
			<script src="https://checkout.stripe.com/checkout.js"></script>

			<button id="customButton-stripe" class="stripe-button -el" type="submit" style="visibility: visible;">
				<span style="display: block; min-height: 20px;"><?php _e('Please wait...','wpjobster-stripe') ?></span>
			</button>

			<script>
			var $ = jQuery;
			function tokenisinplacesubs() {
				if(jQuery('input[name="source"]').val()!='') {
					return true
				} else {
					return false
				}
			}

			jQuery(document).ready(function(e) {
				jQuery('#customButton-stripe').trigger('click');
			});

			var handlerSub = StripeCheckout.configure({
				key: '<?php echo $this->pKey; ?>',

				image: '<?php if (get_option('wpjobster_stripe_logo_url')) { echo get_option('wpjobster_stripe_logo_url'); } else { echo get_template_directory_uri().'/images/stripe-img.png'; } ?>',

				token: function(token, args) {
					jQuery('input[name="email"]').val(token.email);
					jQuery('input[name="source"]').val(token.id);
					jQuery('form[name="form_stripe_subscription"]').trigger('submit');
				}
			});

			document.getElementById('customButton-stripe').addEventListener('click', function(e) {
				handlerSub.open({
					name: '<?php echo $product_name; ?>',
					description: '<?php echo $sub_type; ?>',
					email: '<?php echo $client_email; ?>',
					amount: <?php echo $wpjobster_final_payable_amount; ?>,
					currency:'<?php echo $common_details['selected']; ?>',
					panelLabel: '<?php _e('Subscribe for','wpjobster-stripe') ?> {{amount}} <?php echo "/ ".substr($sub_type, 0,-2); ?>' ,
					closed:function(){
						var myVar=setInterval(function(){

							if(!tokenisinplacesubs()){
								clearInterval(myVar);
								window.history.back()
							}
						},200);
					}
				});

				e.preventDefault();

			});
			</script>

			<form name="form_stripe_subscription" method="POST" action="<?php echo $response_url; ?>">
				<input type="hidden" name="email" />
				<input type="hidden" name="source" />
				<input type="hidden" name="amount" value="<?php echo $wpjobster_final_payable_amount; ?>" />
				<input type="hidden" name="currency" value="<?php echo $common_details['selected']; ?>" />
				<input type="hidden" name="interval" value="<?php echo substr($sub_type, 0,-2); ?>" />
				<input type="hidden" name="plan" value="<?php echo $plan; ?>" />
				<input type="hidden" name="plan_slug" value="<?php echo $plan_slug; ?>" />
				<input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
				<input type="hidden" name="user_id" value="<?php echo $current_user_id; ?>" />
			</form>

    <?php   }

                } else {
                    echo __("Please enter Stripe Publishable Key, Secret Key","wpjobster-stripe");
                }

                exit;
	}


	/**
	 * Process the response from the gateway and mark the order as completed or failed
	 *
	 * @since 1.0.0
	 */
	function processgateway_function( $payment_type, $details ) {

            if( $payment_type != 'subscription' ) {

            $plugin_path = plugin_dir_path(__FILE__);
            require_once $plugin_path . "stripe-new/Stripe.php";

		if (isset($_POST)) {
			//-----------------------------------------------------
			// calculate total
			//-----------------------------------------------------

			$order_id=$_POST['extras'];
			if($payment_type=='feature'){
                            $wcf = $details;
                            $currency = $wcf->_currency;
                            $featured_order=$wcf->get_featured_order_by_id($order_id);
                            if($featured_order){
                                    $featured_order_id = $featured_order->id;
                                    $job = get_post($featured_order->job_id);
                                    $amn = $credit = $featured_order->payable_amount;
                                    $uid = $featured_order->user_id;
                                    $user = get_userdata($uid);
                                    $email = $user->user_email;
                                    $currency = $featured_order->currency;
                            }
			}
			elseif($payment_type=='custom_extra'){
                            $wcf = $details;
                            $currency = $wcf->_currency;
                            $custom_extra_order=$wcf->get_custom_extra_order_by_id($order_id);
                            if($custom_extra_order){
                                    $featured_order_id = $custom_extra_order->id;
                                    $amn = $credit = $custom_extra_order->payable_amount;
                                    $uid = $custom_extra_order->user_id;
                                    $user = get_userdata($uid);
                                    $email = $user->user_email;
                                    $currency = $custom_extra_order->currency;
                            }
			}elseif($payment_type =='job_purchase'){
                            $common_details = get_common_details("stripe",$order_id);
                            $amn = $common_details['wpjobster_final_payable_amount'];
                            $selected =$common_details['selected'];
                            $uid = $common_details['uid'];
                            $user = get_userdata($uid);
                            $email = $user->user_email;
                            $currency = $common_details['selected'];
			}elseif($payment_type == 'topup'){
                            $wct = $details;
                            $wct_order = $wct->get_topup_order_by_id($order_id);
                            $amn = $wct_order->package_amount;
                            $currency = $selected = $wct_order->currency;
                            $uid = $wct_order->uid;
                            $user = get_userdata($uid);
                            $email = $user->user_email;
			}elseif($payment_type == 'subscription'){
                            $wcs = $details;
                            $wcs_order = wpjobster_get_subscription_order_by( 'id', $order_id, $status='inactive' );
                            if($wcs_order){
                                    $subscription_order_id = $wcs_order->id;
                                    $amn = $credit = $wcs_order->payable_amount;
                                    $uid = $wcs_order->user_id;
                                    $user = get_userdata($uid);
                                    $email = $user->user_email;
                                    $currency = $wcs_order->mc_currency;
                            }
			}

			$zero_decimal_currencies = array(
				"BIF",
				"CLP",
				"DJF",
				"GNF",
				"JPY",
				"KMF",
				"KRW",
				"MGA",
				"PYG",
				"RWF",
				"VND",
				"VUV",
				"XAF",
				"XOF",
				"XPF"
			);

			if (!in_array($currency, $zero_decimal_currencies)) {
				$amn = $amn * 100;
			}

			\Stripe\Stripe::setApiKey(get_option('wpjobster_sstripe_key'));
			try {
				if (!isset($_POST['stripeToken']))
					throw new Exception("The Stripe Token was not generated correctly");
                                $payment_response = \Stripe\Charge::create(array(
					"amount" => $amn,
					"currency" => $currency,
					"card" => $_POST['stripeToken'],
					"description"=> $email
				));
				$success = __('Your payment was successful.', 'wpjobster-stripe');
				$transaction_id  = $values = $txn_id = $payment_response->__get('id');

				$error = '';
			}
			catch (Exception $e) {
				$error = $e->getMessage();
				$txn_id = '';
			}

			if ($error=='') {
				$payment_response  = maybe_serialize($payment_response);
				$tm = time();

				$orderid = $order_id;
				if($order_id) {
					do_action("wpjobster_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$payment_response);
					if( isset( $wcs_order ) && strtolower( $wcs_order->plan ) == strtolower( 'Lifetime' ) ){
						do_action("wpjobster_new_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$payment_response);
						wp_redirect( get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) . '?sub_action=details&thankyou=1&message_code=success');
					}else{
						do_action("wpjobster_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$payment_response);
					}
				}else{
					do_action("wpjobster_".$payment_type."_payment_failed",$order_id,$this->unique_slug,$transaction_id,  $payment_response);
				}
			} else {
				do_action("wpjobster_".$payment_type."_payment_failed",$order_id,$this->unique_slug,$transaction_id,  $payment_response);
				?>

				<html>
					<head>
						<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
						<title>Stripe</title>
						<style>
							body {
								text-align:center;
							}

							.stripe-button-el {
								-moz-user-select: none;
								background: linear-gradient(#7DC5EE, #008CDD 85%, #30A2E4) repeat scroll 0 0 #1275FF;
								border: 0 none;
								border-radius: 5px;
								box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
								cursor: pointer;
								display: inline-block;
								overflow: hidden;
								padding: 0px;
								text-decoration: none;
								visibility: visible !important;
								margin:300px auto;
							}

							.stripe-button-el span {
								background: linear-gradient(#7DC5EE, #008CDD 85%, #30A2E4) repeat scroll 0 0 #1275FF;
								border-radius: 4px;
								box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset;
								color: #FFFFFF;
								display: block;
								font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
								font-size: 14px;
								font-weight: bold;
								height: 30px;
								line-height: 30px;
								padding: 0 12px;
								position: relative;
								text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
							}
						</style>
					</head>

					<body>
						<button id="customButton" class="stripe-button-el" type="submit" style="visibility: visible;">
							<p><?php echo $error; ?></p>
							<span style="display: block; min-height: 30px;">Back</span>
						</button>

						<script>
						document.getElementById('customButton').addEventListener('click', function(e) {
							// Open Checkout with further options
						});
						</script>
					</body>
				</html>
			<?php }
		}
            }

            if( $payment_type == 'subscription' ) {
                $token = $_POST['source'];
		$email = $_POST['email'];
		$plan = $_POST['plan'];
		$plan_slug = $_POST['plan_slug'];
		$amount = $_POST['amount'];
		$currency = $_POST['currency'];

		$current_user_id = $_POST['user_id'];
		$order_id = $_POST['order_id'];
		if(isset($token) && $token){

			if($_POST['interval'] == "quarter"){
				$interval_count = 3;
				$interval = 'month';
			}else{
				$interval_count = 1;
				$interval = $_POST['interval'];
			}

			$new_plan = $this->add_plan($plan_slug,$plan,$interval,$interval_count,$currency,$amount);
			$new_customer = $this->add_customer($email, $token, $plan_slug);

			$cusID = $new_customer['id'];
			$subID = $new_customer['subscriptions']['data'][0]['id'];

			$transaction_id = $cusID." ".$subID." ".$plan_slug;
			$payment_response = json_encode($new_customer);

			do_action("wpjobster_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$payment_response);
			do_action("wpjobster_new_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$payment_response);

			wp_redirect( get_permalink( get_option( 'wpjobster_subscriptions_page_id' ) ) . '?sub_action=details&thankyou=1&message_code=success');
		}else{
			$input = @file_get_contents('php://input');
			$event_json = json_decode($input);
			if(isset($event_json->type) && $event_json->type){
				$event_type = $event_json->type;

				if($event_type == 'invoice.payment_succeeded' || $event_type == 'invoice.payment_failed'){
					$customer_id = $event_json->data->object->customer;
					$sub_id = $event_json->data->object->subscription;
					$plan_id = $event_json->data->object->lines->data[0]->plan->id;
				}elseif( $event_type == 'customer.deleted' ){
					$customer_id = $event_json->data->object->id;
					$sub_id = '';
					$plan_id = '';
				}elseif($event_type == 'customer.subscription.deleted'){
					$customer_id = $event_json->data->object->customer;
					$sub_id = $event_json->data->object->id;
					$plan_id = $event_json->data->object->items->data[0]->plan->id;
				}

				$select_order = "select * from ".$this->_wpdb->prefix."job_subscription_orders where profile_id='$customer_id'";
				$r = $this->_wpdb->get_results($select_order);
				$order_id =  isset($r['0']->id)?$r['0']->id:0;

				$transaction_id = $customer_id." ".$sub_id." ".$plan_id;

				if( $event_type == 'customer.subscription.deleted' || $event_type == 'customer.deleted' ){
					//mail('aleximbir92@gmail.com', 'Subscription deleted', $input);
					do_action("wpjobster_".$payment_type."_payment_failed",$order_id,$this->unique_slug,$transaction_id,$input);
				}elseif( $event_type == 'invoice.payment_succeeded' ){
					//mail('aleximbir92@gmail.com', 'Payment success', $input);
					do_action("wpjobster_".$payment_type."_payment_success",$order_id,$this->unique_slug,$transaction_id,$input);
				}elseif( $event_type == 'invoice.payment_failed' ){
					//mail('aleximbir92@gmail.com', 'Payment failed', $input);
					do_action("wpjobster_".$payment_type."_payment_failed",$order_id,$this->unique_slug,$transaction_id,$input);
				}
			}
		}
            }

	}

        function purchase_subscription_add_payment_method_function( ){
            $wpjobster_stripe_enable = get_option( 'wpjobster_stripe_enable' );
            $wpjobster_stripe_enable_subs = get_option( 'wpjobster_stripe_enable_subscription' );
            $wpjobster_stripe_enablepopup = get_option( 'wpjobster_stripe_enablepopup' );
            if( $wpjobster_stripe_enable == "yes" && $wpjobster_stripe_enable_subs == "yes" && $wpjobster_stripe_enablepopup != 'yes' ):
    ?>
                <button name="method" value="stripe" class="ui white button"><?php _e('Stripe','wpjobster-stripe'); ?></button>
    <?php
            endif;

            if( $wpjobster_stripe_enable == "yes" && $wpjobster_stripe_enable_subs == "yes" && $wpjobster_stripe_enablepopup == 'yes' && ( isset($_GET['sub_action']) && $_GET['sub_action']=='change' )  ): ?>
                <button id="stripe-upgrade" name="method" value="stripe" class="btn white"><?php _e('Stripe','wpjobster-stripe'); ?></button>
    <?php   endif;

            if( $wpjobster_stripe_enable == "yes" && $wpjobster_stripe_enable_subs == "yes" && $wpjobster_stripe_enablepopup == 'yes' && ( !isset($_GET['sub_action']) || $_GET['sub_action']!='change' )  ): ?>
                <button id="stripe" name="method" value="stripe" class="btn white"><?php _e('Stripe','wpjobster-stripe'); ?></button>
    <?php   endif; ?>

    		<script type="text/javascript">
				jQuery( document ).ready(function($) {
					$('#stripe').click(function(e){
						e.preventDefault();
						take_to_gateway_subscription_popup( "stripe" );
					});

					$('#stripe-upgrade').click(function(e){
						e.preventDefault();
						take_to_gateway_subscription_upgrade_popup( "stripe" );
					});
				});
			</script>
    <?php }

        public function add_plan($id,$name,$interval,$interval_count,$currency,$amount){
		try{
			$plan = \Stripe\Plan::create(array(
				"name" => $name,
				"id" => $id,
				"interval" => $interval,
				"interval_count" => $interval_count,
				"currency" => $currency,
				"amount" => $amount,
			));
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function delete_plan($id){
		$cu = \Stripe\Plan::retrieve($id);
		$cu->delete();
	}

	public function delete_plan_cURL($id){
		$ch = curl_init();
		$headers = array('Authorization: Bearer '.$this->key);
		curl_setopt($ch, CURLOPT_URL,'https://api.stripe.com/v1/plans/'.$id);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);

		return $result;
	}

	public function add_customer($email, $token, $plan){
		$add_customer = \Stripe\Customer::create(array(
			'email' => $email,
			'source'  => $token,
			'plan' => $plan,
		));
		return $add_customer;
	}

	public function delete_customer($id){
		$cu = \Stripe\Customer::retrieve($id);
		$cu->delete();
	}

	public function delete_customer_cURL($id){
		$ch = curl_init();
		$headers = array('Authorization: Bearer '.$this->key);
		curl_setopt($ch, CURLOPT_URL,'https://api.stripe.com/v1/customers/'.$id);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		$result = json_decode($result);
		curl_close($ch);

		return $result;
	}

	public function add_subscription($customer_id, $plan_id){
		$add_subscription = \Stripe\Subscription::create(array(
			"customer" => $customer_id,
			"plan" => $plan_id,
		));
	}

	public function change_subscription($id, $new_plan_id){
		$subscription = \Stripe\Subscription::retrieve($id);
		$subscription->plan = $new_plan_id;
		$subscription->save();
	}

	public function cancel_subscription($id){
		$subscription = \Stripe\Subscription::retrieve($id);
		$subscription->cancel();
	}

	public function stripe_response(){
		$input = @file_get_contents('php://input');
		$event_json = json_decode($input);
	}

	/**
	 * Allow this class and other classes to add slug keyed notices (to avoid duplication)
	 */
	public function add_admin_notice( $slug, $class, $message ) {
		$this->notices[ $slug ] = array(
			'class' => $class,
			'message' => $message
		);
	}


	/**
	 * The primary sanity check, automatically disable the plugin on activation if it doesn't
	 * meet minimum requirements.
	 *
	 * Based on http://wptavern.com/how-to-prevent-wordpress-plugins-from-activating-on-sites-with-incompatible-hosting-environments
	 */
	public static function activation_check() {
		update_option( 'wpjobster_stripe_developed_in_theme','no' );

		$environment_warning = self::get_environment_warning( true );
		if ( $environment_warning ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( $environment_warning );
		}
	}


	/**
	 * The backup sanity check, in case the plugin is activated in a weird way,
	 * or the environment changes after activation.
	 */
	public function check_environment() {
		$environment_warning = self::get_environment_warning();
		if ( $environment_warning && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			$this->add_admin_notice( 'bad_environment', 'error', $environment_warning );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}


	/**
	 * Checks the environment for compatibility problems.  Returns a string with the first incompatibility
	 * found or false if the environment has no problems.
	 */
	static function get_environment_warning( $during_activation = false ) {
		if ( version_compare( phpversion(), WPJOBSTER_STRIPE_MIN_PHP_VER, '<' ) ) {
			if ( $during_activation ) {
				$message = __( 'The plugin could not be activated. The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'wpjobster-stripe' );
			} else {
				$message = __( 'The Stripe Powered by wpjobster plugin has been deactivated. The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'wpjobster-stripe' );
			}
			return sprintf( $message, WPJOBSTER_STRIPE_MIN_PHP_VER, phpversion() );
		}
		return false;
	}


	/**
	 * Adds plugin action links
	 *
	 * @since 1.0.0
	 */
	public function plugin_action_links( $links ) {
		$setting_link = $this->get_setting_link();
		$plugin_links = array(
			'<a href="' . $setting_link . '">' . __( 'Settings', 'wpjobster-stripe' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}


	/**
	 * Get setting link.
	 *
	 * @return string Braintree checkout setting link
	 */
	public function get_setting_link() {
		$section_slug = $this->unique_slug;
		return admin_url( 'admin.php?page=payment-methods&active_tab=tabs' . $section_slug );
	}

	/**
	 * Display any notices we've collected thus far (e.g. for connection, disconnection)
	 */
	public function admin_notices() {
		foreach ( (array) $this->notices as $notice_key => $notice ) {
			echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
			echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) );
			echo "</p></div>";
		}
	}
}

$GLOBALS['WPJobster_Stripe_Loader'] = WPJobster_Stripe_Loader::get_instance();
register_activation_hook( __FILE__, array( 'WPJobster_Stripe_Loader', 'activation_check' ) );

}

//POpup
if( isset( $payment_type ) && $payment_type == 'job_purchase' &&  isset($pid) ) { ?>
	<script type="text/javascript">
		$(document).ready(function() { take_to_gateway_popup(); });
		function take_to_gateway_popup() {
			base_url = "<?php echo bloginfo('url'); ?>";
			base_url = base_url + '/?pay_for_item=<?php echo $gateway; ?>';
			base_url = base_url + '&jobid=<?php echo $pid; ?>';
			base_url = base_url + '&amount=<?php echo $main_amount; ?>';
			base_url = base_url + '&extras=<?php echo $extrs2; ?>';
			base_url = base_url + '&extras_amounts=<?php echo $extrs_amounts2; ?>';

			$.ajax({
				method: 'get',
				url : base_url,
				dataType : 'text',
				success: function (text) {
					$(".wpjobster-stripe-payment").html(text);
					$('#customButton').trigger('click');
				}
			});
			return false;
		}
	</script>

	<div class="wpjobster-stripe-payment">
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</div>
	<?php
	exit;
}

if( isset( $payment_type ) && $payment_type == 'topup'  ) { ?>
	<script type="text/javascript">
		$(document).ready(function() { take_to_gateway_popup(); });
		function take_to_gateway_popup() {
			base_url = "<?php echo bloginfo('url'); ?>";
			base_url = base_url + '/?pay_for_item=<?php echo $gateway; ?>';
			base_url = base_url + '&payment_type=<?php echo $payment_type; ?>';
			base_url = base_url + '&package_id=<?php echo $package_id; ?>';

			$.ajax({
				method: 'get',
				url : base_url,
				dataType : 'text',
				success: function (text) {
					$(".wpjobster-stripe-payment").html(text);
					 $('#customButton').trigger('click');
				}
			});
			return false;
		}
	</script>

	<div class="wpjobster-stripe-payment">
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</div>
	<?php
}

if( isset( $payment_type ) && $payment_type == 'feature' && isset($pid_feature)  ) {?>
	<script type="text/javascript">
		$(document).ready(function() { take_to_gateway_popup(); });
		function take_to_gateway_popup() {
			base_url = "<?php echo bloginfo('url'); ?>";
			base_url = base_url + '/?pay_for_item=<?php echo $gateway; ?>';
			base_url = base_url + '&payment_type=<?php echo $payment_type; ?>';
			base_url = base_url + '&jobid=<?php echo $pid_feature; ?>';
			base_url = base_url + '&h_date_start=<?php echo $h_start_date; ?>';
			base_url = base_url + '&c_date_start=<?php echo $c_start_date; ?>';
			base_url = base_url + '&s_date_start=<?php echo $s_start_date; ?>';
			base_url = base_url + '&feature_pages=<?php echo $feature_pages; ?>';

			$.ajax({
				method: 'get',
				url : base_url,
				dataType : 'text',
				success: function (text) {
					$(".wpjobster-stripe-payment").html(text);
					$('#customButton').trigger('click');
				}
			});
			return false;
		}
	</script>

	<div class="wpjobster-stripe-payment">
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</div>
	<?php
}

if( isset( $payment_type ) && $payment_type == 'custom_extra' && isset($oid_custom_extra)  ) { ?>
	<script type="text/javascript">
		$(document).ready(function() { take_to_gateway_popup(); });
		function take_to_gateway_popup() {
			base_url = "<?php echo bloginfo('url')?>";
			base_url = base_url + '/?pay_for_item=<?php echo $gateway; ?>';
			base_url = base_url + '&payment_type=<?php echo $payment_type; ?>';
			base_url = base_url + '&oid=<?php echo $oid_custom_extra; ?>';
			base_url = base_url + '&amount=<?php echo $amount; ?>';
			base_url = base_url + '&custom_extra=<?php echo $custom_extra; ?>';

			$.ajax({
				method: 'get',
				url : base_url,
				dataType : 'text',
				success: function (text) {
					$(".wpjobster-stripe-payment").html(text);
					$('#customButton').trigger('click');
				}
			});
			return false;
		}
	</script>

	<div class="wpjobster-stripe-payment">
		<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</div>
	<?php
}

if( isset( $payment_type ) && $payment_type == 'subscription' && isset($user_id)  ) { ?>
	<script type="text/javascript">
		$(document).ready(function() { take_to_gateway_popup(); });
		function take_to_gateway_popup() {
			var plan = '<?php echo $sub_type; ?>';
			base_url = "<?php echo bloginfo('url')?>";
			base_url = base_url + '/?pay_for_item=<?php echo $gateway; ?>';
			base_url = base_url + '&payment_type=<?php echo $payment_type; ?>';
			base_url = base_url + '&user_id=<?php echo $user_id; ?>';
			base_url = base_url + '&sub_amount=<?php echo $sub_amount; ?>';
			base_url = base_url + '&sub_type=<?php echo $sub_type; ?>';
			base_url = base_url + '&sub_level=<?php echo $sub_level; ?>';

			$.ajax({
				method: 'get',
				url : base_url,
				dataType : 'text',
				success: function (text) {
					$(".wpjobster-stripe-payment").html(text);
					$("#please-wait").html("Please wait...");
					if(plan != 'lifetime'){
						$('#customButton-stripe').trigger('click');
					}else{
						$('#customButton').trigger('click');
					}
				}
			});
			return false;
		}
	</script>

	<div id="please-wait"></div>
	<div class="wpjobster-stripe-payment" style="display:none">
		<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</div>
	<?php
}
