<?php
global $wpdb;
include_once get_template_directory() . '/lib/gateways/wpjobster_common_payment.php';

class wpjobster_subscription extends WPJ_Common_Payment{

	public $view_path,$_current_user,$_wp_query,$current_subscription;

	public $_wpdb,$_currency,$_payment_gateway,$_payment_type;

	public $messages,$subscription_arr,$subscription_levels,$subscription_eligibility_enabled,$errors,$subscription_duration,$current_user_sales;


	function __construct($payment_gateway='', $uid=0){
		global $wpdb,$wp_query;

		parent::__construct($payment_gateway);

		if(isset( $_GET['final_status'] ) && $_GET['final_status'] && ( $_GET['final_status'] == "cancelled" || $_GET['final_status'] == "success" ) ){
			$this->sub_final_status = $_GET['final_status'];
		}else{
			$this->sub_final_status = '';
		}

		$this->_payment_type = 'subscription';
		$this->_payment_gateway=$payment_gateway;
		$this->_current_user=wp_get_current_user();
		$this->_wpdb=$wpdb;

		if(isset($_GET['site_currency'])){
			$this->_currency = strtoupper($_GET['site_currency']);
		}else if(isset($_COOKIE["site_currency"])){
			$this->_currency=strtoupper($_COOKIE["site_currency"]);
		}else{
			global $wpjobster_currencies_array;
			$this->_currency=$wpjobster_currencies_array[0];
		}

		add_action("wpjobster_subscription_payment_success",array($this,"payment_success"),10,4);
		add_action("wpjobster_new_subscription_payment_success",array($this,"payment_process"),10,4);
		add_action("wpjobster_subscription_payment_failed",array($this,"payment_failed"),10,4);
		add_action("wpjobster_subscription_payment_other",array($this,"payment_other"),10,5);

		if($uid==0){
			$current_user = wp_get_current_user();
			global $current_user;
			$this->_current_user=$current_user;
		}else{
			$this->_current_user = get_userdata( $uid );
		}
		$this->_wpdb=$wpdb;

		$this->current_user_sales=get_sales_by_userid($this->_current_user->ID);
		$this->view_path="views/wpjobster_subscription";
		$this->subscription_eligibility_enabled = get_option('wpjobster_subscription_eligibility_enabled');
		$this->subscription_duration = array("weekly", "monthly", "quarterly", "yearly", "lifetime");
		$this->subscription_levels = array(
			"level0" => sprintf(__("Level %d", "wpjobster"), 0),
			"level1" => sprintf(__("Level %d", "wpjobster"), 1),
			"level2" => sprintf(__("Level %d", "wpjobster"), 2),
			"level3" => sprintf(__("Level %d", "wpjobster"), 3)
			);
		$this->errors = array(
			"not_enough_balance" => __("You do not have enough balance to purchase this subscription.", "wpjobster"),
			"no_subscription_levels" => __("Suscription levels not defined", "wpjobster"),
			"not_eligible" => __("You are not eligible for this level", "wpjobster"),
			"cancelled" => __("You cancelled this subscription.", "wpjobster"),
			"no_plan_selected" => __("Please select a plan.", "wpjobster"),
			);
		$this->messages = array(
			"balance_down_subscription" => __("Thank you for subscribing with us! We have sent you a confirmation email.", "wpjobster"),
			"balance_down_subscription_change" => __("You have successfully changed your subscription! We have sent you a confirmation email.", "wpjobster"),
			"subscription_schedule" => __("You have successfully scheduled your subscription. We have sent you a confirmation email.", "wpjobster"),
			"success" => __("Thank you for subscribing with us! If the subscription is not active, please refresh the page in a few minutes.", "wpjobster")
			);
	}
	function show_messages($error=1,$msg=''){
		if($error=='1'){
			return "<div class='white-cnt padding-cnt center red-cnt'>".$this->errors[$msg]."</div>";
		}else{
			return "<div class='ui segment white-cnt padding-cnt center green-cnt'>".$this->messages[$msg]."</div>";
		}
	}
	// send email to users when admin changes amount in the price settings
	function send_update_email_subscription($period_level_id){
		$nm = explode("_",$period_level_id);
		$sub_type =$nm[0];
		$sub_level =$nm[1];
		$subscriptions = $this->_wpdb->get_results("select * from ".$this->_wpdb->prefix."job_subscriptions where "
				. "(subscription_level='".$sub_level."' and subscription_type='".$sub_type."') or "
				. "(next_subscription_level='".$sub_level."' and next_subscription_type='".$sub_type."') ");

		if(is_array($subscriptions)){
			foreach($subscriptions as $subscription){
				$reason_email = 'price_update_subscription';
				$uid = $subscription->user_id;
				$new_amount = get_option( 'wpjobster_subscription_' . $sub_type . '_amount_' . $sub_level );

				wpjobster_send_email_allinone_translated($reason_email, $uid, false, false, false, false, false, false, false, $new_amount);

			}
				echo json_encode(array('msg'=>'done'));
		} //endif subscription
		else{
			echo json_encode(array('msg1'=>'done'));
			// nothing to do
		}
	}
	function unixtimestamp_to_date($timeStamp){
		//User Timezone Function
		wpjobster_timezone_change();
		return date_i18n(get_option( 'date_format' ), $timeStamp);
	}
	function get_current_subscription($user_id){
		$current_subscription = $this->_wpdb->get_row('select * from '.$this->_wpdb->prefix.'job_subscriptions where user_id="'.$user_id.'"');
		if(is_object($current_subscription)){
			$current_subscription->next_billing_date=$this->unixtimestamp_to_date($current_subscription->next_billing_date);
			$current_subscription->sub_start_date=$this->unixtimestamp_to_date($current_subscription->sub_start_date);
		}
		return $current_subscription ;
	}
	function get_subscription_levels(){
		if(empty($this->subscription_levels)){
			$this->show_error("no_subscription_levels");
		}

		$this->current_subscription = $this->get_current_subscription($this->_current_user->ID);
		foreach($this->subscription_levels as $key =>$value){
			$this->subscription_arr[$key]['weekly'] =get_option('wpjobster_subscription_weekly_amount_'.$key);
			$this->subscription_arr[$key]['monthly'] =get_option('wpjobster_subscription_monthly_amount_'.$key);
			$this->subscription_arr[$key]['quarterly'] =get_option('wpjobster_subscription_quarterly_amount_'.$key);
			$this->subscription_arr[$key]['yearly'] =get_option('wpjobster_subscription_yearly_amount_'.$key);
			$this->subscription_arr[$key]['lifetime'] =get_option('wpjobster_subscription_lifetime_amount_'.$key);
			$this->subscription_arr[$key]['eligility'] =get_option('wpjobster_subscription_eligibility_amount_'.$key);
			$this->subscription_arr[$key]['noof_extras'] =get_option('wpjobster_subscription_noof_extras_'.$key);
			$this->subscription_arr[$key]['max_extra_price'] =get_option('wpjobster_subscription_max_extra_price_'.$key);
			$this->subscription_arr[$key]['fees'] =get_option('wpjobster_subscription_fees_'.$key);
			$this->subscription_arr[$key]['profile_label'] =get_option('wpjobster_subscription_profile_label_'.$key);
			$this->subscription_arr[$key]['icon_url'] =get_option('wpjobster_subscription_icon_url_'.$key);
			$this->subscription_arr[$key]['max_job_price'] =get_option('wpjobster_subscription_max_job_price_'.$key);
			$this->subscription_arr[$key]['job_packages'] =get_option('wpjobster_subscription_packages_'.$key);

			$param1 = $this;
			do_action('add_item_in_array', $param1, $key);

		}
	}

	function chose_subscriptions(){
		$levels = $this->get_subscription_levels();
		include $this->view_path.'/chose_subscription.php';
	}
	function remove_subscription($uid){
		$this->current_subscription = $this->get_current_subscription($uid);
		$this->_wpdb->delete($this->_wpdb->prefix."job_subscriptions",array("id"=>$this->current_subscription->id));
		wp_redirect(get_permalink(get_option('wpjobster_subscriptions_page_id')));
	}
	function process_cancellation($uid=0){
		if($uid && $uid != 0){
			$uid = $uid;
		}else{
			$uid=$this->_current_user->ID;
		}

		$this->remove_subscription($uid);
		do_user_level_extras_check($uid);
		do_user_level_extras_price_check($uid);
		do_user_level_job_price_check($uid);

		do_action('cancel_subscription', $uid);

		$subscription_order = $this->get_subscription_order_by_user_id($uid);
		if(isset($subscription_order) && $subscription_order){
			$profile_id = $subscription_order->profile_id;
			$order_id = $subscription_order->id;
			$payment_gateway_transaction_id = $subscription_order->payment_gateway_transaction_id;
			$subs = explode(' ', $payment_gateway_transaction_id);
			if($subs){
				$cust_id = isset($subs[0]) ? $subs[0] : 0;
				$sub_id = isset($subs[1]) ? $subs[1] : 0;
			}
			$payment_gateway = $subscription_order->payment_gateway_name;

			if($payment_gateway == 'paypal'){
				try{
					$this->change_subscription_status( $profile_id, 'Cancel' );
				}catch(Exception $e){}
			}

			do_action( 'cancel_subscription_gateway', $subscription_order );

			$this->update_subscription_order_status($order_id,'cancelled','inactive',$payment_gateway_transaction_id,'Subscription cancelled.','','');
		}

		$reason = 'subscription_cancel';
		$this->send_subscription_email($reason, $uid);
	}

	function change_subscription_status( $profile_id, $action ) {

		$sdb = get_option('wpjobster_paypal_enable_sdbx');
		$paypal_url = 'https://api-3t.paypal.com/nvp';
		if($sdb == "yes")
			$paypal_url = 'https://api-3t.sandbox.paypal.com/nvp';

		$api_request = 'USER=' . urlencode( get_option('wpjobster_theme_apiusername') )
					.  '&PWD=' . urlencode( get_option('wpjobster_theme_apipassword') )
					.  '&SIGNATURE=' . urlencode( get_option('wpjobster_theme_apisignature') )
					.  '&VERSION=76.0'
					.  '&METHOD=ManageRecurringPaymentsProfileStatus'
					.  '&PROFILEID=' . urlencode( $profile_id )
					.  '&ACTION=' . urlencode( $action )
					.  '&NOTE=' . urlencode( 'Profile cancelled at store' );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $paypal_url );
		curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );

		// Set the API parameters for this transaction
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );

		// Request response from PayPal
		$response = curl_exec( $ch );

		// If no response was received from PayPal there is no point parsing the response
		if( ! $response )
			die( 'Calling PayPal to change_subscription_status failed: ' . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );

		curl_close( $ch );

		parse_str( $response, $parsed_response );

		return $parsed_response;
	}

	function get_next_billing_date($sub_type){
		$start_date = date('Y-m-d');
		$start_date_obj = new DateTime($start_date);
		if($sub_type == 'weekly'){
			$interval= new DateInterval('P7D');
		}
		if($sub_type == 'monthly'){
			$interval= new DateInterval('P1M');
		}
		if($sub_type == 'quarterly'){
			$interval= new DateInterval('P3M');
		}
		if($sub_type == 'yearly'){
			$interval= new DateInterval('P12M');
		}

		if($sub_type == 'lifetime'){
			$next_billing_date= 0;
		}else{
			$start_date_obj->add($interval);
			$next_billing_date=strtotime($start_date_obj->format('Y-m-d'));
		}

		return $next_billing_date;
	}

	// PAYPAL START //

	function get_subscription_order_by_id($order_id=0){
		$select_package = "select * from ".$this->_wpdb->prefix."job_subscription_orders where id='$order_id'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}
	function get_subscription_order_by_user_id($user_id=''){
		$select_package = "select * from ".$this->_wpdb->prefix."job_subscription_orders where user_id='$user_id' AND subscription_status != 'inactive'";
		$r = $this->_wpdb->get_results($select_package);
		return isset($r['0'])?$r['0']:0;
	}
	function payment_other( $order_id, $gateway_slug, $payment_details = '', $payment_response = '', $payment_status = 'processing'){
		$this->update_subscription_order_status($order_id,
			$payment_status,'inactive',$payment_details, //-1 stands for failed topup
			$payment_response);
		$subscription_order=$this->get_subscription_order_by_id($order_id);
		$user_id = $subscription_order->user_id;
		$this->process_cancellation($user_id);
	}
	function payment_failed($order_id,$gateway_slug,$payment_details='',$payment_response=''){
		$this->update_subscription_order_status($order_id,
			'cancelled','inactive',$payment_details, //-1 stands for failed topup
			$payment_response);

		$subscription_order=$this->get_subscription_order_by_id($order_id);
		$user_id = $subscription_order->user_id;
		$this->process_cancellation($user_id);
	}
	function payment_process($order_id,$payment_gateway_slug,$payment_details,$response){
		if(is_array($response)){
			$payment_response = json_encode($response);
		}else{
			$payment_response = $response;
		}
		$payment_response_original = json_encode($_REQUEST);

		$subscription_order=$this->get_subscription_order_by_id($order_id);

		$plan = $subscription_order->plan;
		$level = $subscription_order->level;
		$user_id = $subscription_order->user_id;

		$level_arr = explode( '-', $level );

		$level = isset( $level_arr[0] ) && $level_arr[0] ? $level_arr[0] : '';
		$usr_type = isset( $level_arr[1] ) && $level_arr[1] ? $level_arr[1] : '';

		$register_sub = $plan . "-" . $level . "-" . $usr_type;

		$this->do_subscription($register_sub,$user_id,$payment_gateway_slug);

		$subs = explode(' ', $payment_details);
		if($subs){
			$cust_id = $subs[0];
		}else{
			$cust_id = 0;
		}

		if(isset($_POST['subscr_id']) && $_POST['subscr_id']){
			$profile_id = $_POST['subscr_id'];
		}else{
			$profile_id = $cust_id;
		}

		$this->update_subscription_order_status($order_id,'Completed','active',$payment_details,$payment_response,$payment_response_original,$profile_id);
		$this->update_subscription_payment_received($order_id,'Completed',$payment_details,$payment_response);

		do_action( 'wpjobster_new_subscription_payment_completed', $order_id );
	}
	function payment_success($order_id,$payment_gateway_slug,$payment_details,$response){
		if(is_array($response)){
			$payment_response = json_encode($response);
		}else{
			$payment_response = $response;
		}

		if(!(isset($_POST['payment_status']))){
			$_POST['payment_status'] = "Completed";
		}

		$this->update_subscription_payment_received($order_id,$_POST['payment_status'],$payment_details,$payment_response);

		$this->update_subscription_order_status($order_id,'','',$payment_details,'','','');

		do_action( 'wpjobster_subscription_payment_completed', $order_id );
	}
	function send_payment_details($payment_method='', $jb_action=''){
		$subscription_name=$_GET['sub_id'];

		$uid = $this->_current_user->ID;

		$nm           = explode( "-",$subscription_name );
		$sub_type     = isset( $nm[0] ) ? $nm[0] : '';
		$sub_level    = isset( $nm[1] ) ? $nm[1] : '';
		$sub_usr_type = isset( $nm[2] ) ? $nm[2] : '';
		$sub_amount = get_option('wpjobster_subscription_'.$nm[0].'_amount_'.$nm[1]);

		if( $jb_action == 'change_subscription' ){
			$this->current_subscription = $this->get_current_subscription($uid);
			$last_sub_level = $this->current_subscription->subscription_level;
			$last_sub_plan = $this->current_subscription->subscription_type;
			$last_sub_amount = get_option('wpjobster_subscription_'.$last_sub_plan.'_amount_'.$last_sub_level);
			$sub_amount = $sub_amount - $last_sub_amount;
		}


		if($jb_action=="change_subscription"){
			$this->process_cancellation();
			$cr = wpjobster_get_credits($uid);
			wpjobster_update_credits($uid, $cr + $nm[2]);
		}

		if(is_numeric($sub_amount)){ ?>
			<script type="text/javascript">
				$(document).ready(function() {
					take_to_gateway_subscription();
				});

				function take_to_gateway_subscription() {

					gateway_name = '<?php echo $this->_payment_gateway; ?>';
					enable_popup = '<?php echo get_option('wpjobster_'.$this->_payment_gateway.'_enablepopup'); ?>';

					base_url = "<?php echo bloginfo('url')?>";
					base_url = base_url + '/?pay_for_item=' + gateway_name;
					base_url = base_url + '&payment_type=' + 'subscription';
					base_url = base_url + '&user_id=<?php echo $uid; ?>';
					base_url = base_url + '&sub_amount=<?php echo $sub_amount; ?>';
					base_url = base_url + '&sub_type=<?php echo $sub_type; ?>';
					base_url = base_url + '&sub_level=<?php echo $sub_level; ?>';
					base_url = base_url + '&sub_usr_type=<?php echo $sub_usr_type; ?>';

					if( enable_popup === 'yes' ) {

						jQuery.ajax({
							type: "POST",
							url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							data: {
								action: 'wpjobster_check_payment_gateway_popup',
								user_id: '<?php echo $uid; ?>',
								payment_type: 'subscription',
								sub_amount: '<?php echo $sub_amount; ?>',
								sub_type: '<?php echo $sub_type; ?>',
								sub_level: '<?php echo $sub_level; ?>',
								sub_usr_type: '<?php echo $sub_usr_type; ?>',
								gateway: gateway_name
							},
							success: function (output) {
								jQuery(".payment-gateway-popup").html(output);
							}
						});

					} else {
						window.location = base_url;
					}
				}
			</script>
			<?php
		}
	}
	function calculate_tax_and_fees($amount){
		$_subscription_amount = $amount;
		$buyer_processing_fees_orignal = wpjobster_get_site_processing_fee( $_subscription_amount, 0, 0);
		$tax_orignal = wpjobster_get_site_tax($_subscription_amount,0,0,$buyer_processing_fees_orignal);
		$total_amount_orignal= $_subscription_amount+$tax_orignal+$buyer_processing_fees_orignal;

		$_tax = wpjobster_formats_special_exchange( $tax_orignal, '1', $this->_currency );
		$buyer_processing_fees = wpjobster_formats_special_exchange( $buyer_processing_fees_orignal, '1', $this->_currency );
		$total= wpjobster_formats_special_exchange( $_subscription_amount, '1', $this->_currency );
		$_payable_amount = $total+$buyer_processing_fees+$_tax;

		$data = array(
			'buyer_processing_fees_orignal' => $buyer_processing_fees_orignal,
			'tax_orignal' => $tax_orignal,
			'total_amount_orignal' => $total_amount_orignal,
			'tax' => $_tax,
			'buyer_processing_fees' => $buyer_processing_fees,
			'payable_amount' => $_payable_amount
		);

		return $data;
	}
	function update_subscription_order_status($order_id, $payment_status, $subscription_status, $transaction_id=0, $payment_response='', $payment_response_original='',$profile_id='', $sub_sts=''){

		$tm = time();

		$sql = " update ".$this->_wpdb->prefix."job_subscription_orders set payment_date='$tm'";
		if($subscription_status != '')
			$sql .=", subscription_status = '$subscription_status'";
		if($payment_status != '')
			$sql .=", payment_status='$payment_status'";
		if($transaction_id != '')
			$sql .=", payment_gateway_transaction_id='$transaction_id'";
		if($payment_response_original != '')
			$sql .=", payment_response='".$payment_response_original."'";
		if($profile_id != '')
			$sql .=", profile_id='".$profile_id."'";

		$sql .= "WHERE id='$order_id'";

		if($sub_sts != '')
			$sql .= " AND subscription_status='active'";

		$update_result = $this->_wpdb->query($sql);
	}
	function update_subscription_payment_received($order_id,$payment_status='Completed',$payment_details='',$response=''){
		$payment['order_id'] = $order_id;
		$payment['status']=$payment_status;
		$payment['payment_type']='subscription';
		$payment['payment_response']=$response;
		$payment['payment_details']=$payment_details;
		$this->update_payment($payment);
	}
	function insert_subscription_payment_received($order_id,$payment_gateway_slug='',$payment_status='Completed',$payment_details='',$response=''){
		$payment['payment_status'] = $payment_status;
		$payment['payment_gateway'] = $payment_gateway_slug;
		$payment['payment_response'] = $response;
		$payment['payment_details'] = $payment_details;
		$payment['payment_type'] = 'subscription';
		$payment['payment_type_id'] = $order_id;
		$payment['currency'] = get_option('wpjobster_currency_1');

		$current_subs = $this->get_subscription_order_by_id($order_id);
		$amount = $current_subs->payable_amount;
		$datemate = $current_subs->addon_date;

		$prices = $this->calculate_tax_and_fees($amount);

		$payment['amount'] = $amount;
		$payment['tax'] = 0; //$prices['tax_orignal'];
		$payment['fees'] = 0; //$prices['buyer_processing_fees_orignal'];
		$payment['final_amount'] = $amount; //$prices['total_amount_orignal'];
		$payment['final_amount_exchanged'] = $amount; //$prices['payable_amount'];
		$payment['final_amount_currency'] = $this->_currency;
		$payment['datemade'] = $datemate;
		$payment['created_on'] = date('Y-m-d H:i:s', $datemate);
		$payment['payment_made_on'] = time();

		$this->insert_payment($payment);
	}
	function insert_subscription_order($subscription_order=array()){
		if(!isset($subscription_order['payment_gateway_name']) || $subscription_order['payment_gateway_name']==''){
			$subscription_order['payment_gateway_name']=$this->_payment_gateway;
		}
		$tm = time();
		$sql = "insert into ".$this->_wpdb->prefix."job_subscription_orders set
		user_id='".$subscription_order['user_id']."',
		amount='".$subscription_order['subscription_amount']."',
		payment_status='".$subscription_order['payment_status']."',
		subscription_status='".$subscription_order['subscription_status']."',
		addon_date='".$tm."',
		mc_currency='".$subscription_order['currency']."',
		plan='".$subscription_order['plan']."',
		level='".$subscription_order['level']."',
		payment_gateway_name ='".$subscription_order['payment_gateway_name']."',
		payable_amount='".$subscription_order['payable_amount']."',
		tax='".$subscription_order['tax']."'";

		$insert_result = $this->_wpdb->query($sql);

		$order_id = $this->_wpdb->insert_id;

		return $order_id;
	}

	// END PAYPAL //

	function do_subscription($register_sub='', $uid=0, $payment_method){

		if($register_sub){
			$subscription_name = $register_sub;
			$uid = $uid;
		}else{
			$subscription_name=$_GET['sub_id'];
			$uid = $this->_current_user->ID;
		}

		$nm = explode("-",$subscription_name);
		$sub_type =$nm[0];
		$sub_level =$nm[1];

		if(get_option('wpjobster_subscription_eligibility_enabled')!='no'){
			$sub_eligibility = get_option('wpjobster_subscription_eligibility_amount_'.$sub_level);
			if(wpjobster_formats_special(get_sales_by_userid($uid),2) < wpjobster_formats_special($sub_eligibility,2)){
				return array("error"=>'not_eligible');
			}
		}

		$this->current_subscription = $this->get_current_subscription($uid);
		$schedule_only = 0;
		if(isset($_REQUEST['schedule_only'])){
			$schedule_only = 1;
		}

		// Upgrade Subscription
		if(is_object($this->current_subscription) ){
			$where = array("user_id"=>$uid);
			$is_subscribed = 1;
			$last_paid_amount = $this->current_subscription->subscription_amount;

			$last_sub_level = $this->current_subscription->subscription_level;
			$last_sub_plan = $this->current_subscription->subscription_type;
			$last_sub_amount = get_option('wpjobster_subscription_'.$last_sub_plan.'_amount_'.$last_sub_level);

			$subscription_type = $this->current_subscription->subscription_type;
			$sub_field = 'wpjobster_subscription_'.$nm[0].'_amount_'.$nm[1];
			$sub_amount = get_option('wpjobster_subscription_'.$nm[0].'_amount_'.$nm[1]);
			$new_amount = get_option('wpjobster_subscription_'.$subscription_type.'_amount_'.$nm[1]);
			$payable_amount = $sub_amount - $last_sub_amount;

			$start_date = time();
		}else{
			$where = "";
			$is_subscribed = 0;
			$sub_field = 'wpjobster_subscription_'.$nm[0].'_amount_'.$nm[1];
			$sub_amount = get_option('wpjobster_subscription_'.$nm[0].'_amount_'.$nm[1]);
			$payable_amount = $sub_amount;
			$next_billing_date=$this->get_next_billing_date($sub_type);
			$start_date = time();
		}

		if (is_numeric($payable_amount)) {

			$_subscription_amount = $sub_amount;
			$prices = $this->calculate_tax_and_fees($_subscription_amount);
			$buyer_processing_fees_orignal = $prices['buyer_processing_fees_orignal'];
			$tax_orignal = $prices['tax_orignal'];
			$total_amount_orignal= $prices['total_amount_orignal'];

			$_tax = $prices['tax'];
			$buyer_processing_fees = $prices['buyer_processing_fees'];
			$_payable_amount = $prices['payable_amount'];

			$cr = wpjobster_get_credits($uid);

			$subscription_order= array(
				'user_id'              => $uid,
				'subscription_amount'  => $sub_amount,
				'payment_status'       => 'completed',
				'subscription_status'  => 'active',
				'payment_gateway_name' => $payment_method,
				'payable_amount'       => $payable_amount,
				'currency'             => $this->_currency,
				'plan'                 => $sub_type,
				'level'                => $sub_level,
				'tax'                  => $_tax,
				'tax_orignal'          => $tax_orignal,
				'fees'                 => $buyer_processing_fees,
				'total_amount_orignal' => $total_amount_orignal,
				'fees_orignal'         => $buyer_processing_fees_orignal
			);

			if($payment_method == "credits"){
				if($cr >= $payable_amount){
					if($schedule_only==0){
						wpjobster_update_credits($uid, $cr - $payable_amount);
						do_action('before_save_subscription', $nm);
					}
					if( $is_subscribed == 0 && $schedule_only == 0 ){ // new subscriber
						$order_id = $this->insert_subscription_order($subscription_order);
						$this->update_subscription_order_status($order_id,'Completed','active',$order_id,'',json_encode($subscription_order),$uid);
						$this->insert_subscription_payment_received($order_id,$payment_method,'Completed',$order_id,'New subscription');
						$this->update_subscription_payment_received($order_id,'Completed','New Subscription',json_encode($subscription_order));
					} elseif( $is_subscribed != 0 && $schedule_only == 0 ){ //upgrate subscriber
						$current_subs = $this->get_subscription_order_by_user_id($uid);
						if($current_subs){
							$order_id = $current_subs->id;
							$this->update_subscription_order_status($order_id,'cancelled','inactive',$order_id,'','Subscription cancelled',$uid);
						}
						$order_id = $this->insert_subscription_order($subscription_order);
						$this->update_subscription_order_status($order_id,'Completed','active',$order_id,'',json_encode($subscription_order),$uid);
						$this->insert_subscription_payment_received($order_id,$payment_method,'Completed',$order_id,'Subscription upgrated');
						$this->update_subscription_payment_received($order_id,'Completed','Subscription upgrated',json_encode($subscription_order));
					}
				}else{
					return array("error"=>'not_enough_balance');
				}
			} else {
				do_action('before_save_subscription', $nm);
			}

			//Schedule
			if($schedule_only=='1' && is_array($where)){
				$subscription_data = array(
					"next_subscription_level" => $sub_level,
					"next_subscription_type" => $sub_type,
					"next_subscription_amount" => $sub_amount
					);
				$this->_wpdb->update($this->_wpdb->prefix.'job_subscriptions',$subscription_data,$where);
				$email_reason = 'subscription_schedule';
				$email_reason_admin = 'subscription_schedule_admin';

				do_action('before_save_subscription', $nm);

			//New Subscription
			} elseif($is_subscribed==0){
				$subscription_data = array(
					"subscription_level" => $sub_level,
					"subscription_type" => $sub_type,
					"sub_start_date" => $start_date,
					"subscription_amount" => "$sub_amount",
					"user_id" => $uid,
					"next_billing_date" => $next_billing_date,
					"subscription_status" => 'active',
					"next_subscription_level" => $sub_level,
					"next_subscription_type" => $sub_type,
					"next_subscription_amount" => $sub_amount
				);

				$reason = __("Payment for subscription", "wpjobster");
				$details = $nm[0] . "_" . $nm[1] . "_" . "new";
				$reason_admin = sprintf(__('A user has subscribed for %1$s subscription %2$s', 'wpjobster'), $nm[0], $nm[1]);
				$this->_wpdb->insert($this->_wpdb->prefix.'job_subscriptions',$subscription_data);

				if(isset($_COOKIE["site_currency"]) && $_COOKIE["site_currency"]!='' && get_option('wpjobster_currency_1') != $_COOKIE["site_currency"]){
					$cur_value = get_exchange_value($payable_amount, get_option('wpjobster_currency_1'), $_COOKIE["site_currency"]);
					$payed_amount = $_COOKIE["site_currency"].'|'.$cur_value;
				}else{
					$payed_amount = '';
				}

				wpjobster_add_history_log('0', $reason, $payable_amount, $uid, '', '',11 , $details,$payed_amount);
				$email_reason = 'balance_down_subscription';
				$email_reason_admin = 'balance_down_subscription_admin';

			//Upgrade subscription
			}else{
				$subscription_data = array(
					"subscription_level" => $sub_level,
					"subscription_type" => $sub_type,
					"subscription_status" => 'active',
					"next_subscription_level" => $sub_level,
					"next_subscription_type" => $sub_type,
					"next_subscription_amount" => $sub_amount
					);
				$this->_wpdb->update($this->_wpdb->prefix.'job_subscriptions',$subscription_data,$where);
				$reason = __("Payment for changing subscription", "wpjobster");
				$details = $nm[0] . "_" . $nm[1] . "_" . "change";
				$reason_admin = sprintf(__('A user has changed subscription to %1$s subscription %2$s', 'wpjobster'), translate_subscription_strings($nm[0]), translate_subscription_strings($nm[1]));
				if(isset($_COOKIE["site_currency"]) && $_COOKIE["site_currency"]!='' && get_option('wpjobster_currency_1') != $_COOKIE["site_currency"]){
					$cur_value = get_exchange_value($payable_amount, get_option('wpjobster_currency_1'), $_COOKIE["site_currency"]);
					$payed_amount = "1".$_COOKIE["site_currency"].'|'.$cur_value."";
				}else{
					$payed_amount = '';
				}
				wpjobster_add_history_log('0', $reason, $payable_amount, $uid, '', '',11 , $details,$payed_amount);
				$email_reason = 'balance_down_subscription_change';
				$email_reason_admin = 'balance_down_subscription_change_admin';

			}
			$this->send_subscription_email($email_reason,$uid,$payable_amount);
			do_user_level_extras_check($uid);
			do_user_level_extras_price_check($uid);
			do_user_level_job_price_check($uid);
			return $email_reason;
		}
	}
	function send_subscription_email($email_reason,$uid,$payable_amount=0){
		$email_reason_admin = $email_reason."_admin";

		wpjobster_send_email_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false,$payable_amount);
		wpjobster_send_sms_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false, $payable_amount);
		wpjobster_send_email_allinone_translated($email_reason_admin, 'admin', $uid, false, false, false, false, false, false, $payable_amount);
		wpjobster_send_sms_allinone_translated($email_reason_admin, 'admin', $uid, false, false, false, false, false, false, $payable_amount);
	}
	function get_active_subscription(){
		$subscriptions = $this->_wpdb->get_results("select * from ".$this->_wpdb->prefix."job_subscriptions where next_billing_date<='".time()."' and next_billing_date!='0000-00-00' and next_billing_date != '' and next_billing_date != 0");
		return $subscriptions;
	}
	function send_subscription_renewal_reminder(){
		$subscriptions=$this->get_active_subscription();
		if(is_array($subscriptions)){
			foreach($subscriptions as $subscription){

				$no_of_days_prior = get_option('wpjobster_subscription_prior_notification');
				$one_days_seconds = 60*60*24;
				$total_seconds_prior = $no_of_days_prior * $one_days_seconds;

				$next_billing_date=$subscription->next_billing_date;
				$uid = $subscription->user_id;
				$wpjobster_subscription_prior_email_notification_sent = get_user_meta($uid,"wpjobster_subscription_prior_email_notification_sent",true);

				if($total_seconds_prior+time()>$subscription->next_billing_date && $wpjobster_subscription_prior_email_notification_sent!='1'){
					$email_reason = 'wpjobster_subscription_prior_notification';
					update_user_meta($uid,"wpjobster_subscription_prior_email_notification_sent","1");
					wpjobster_send_email_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false,0);
					wpjobster_send_sms_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false, 0);

				}
			}
		}
	}

	function check_scheduled_subscription(){
		$subscriptions=$this->get_active_subscription();
		if(is_array($subscriptions)){
			foreach($subscriptions as $subscription){
				$email_reason = 'balance_down_subscription';
				$email_reason_admin = 'balance_down_subscription_admin';

				$uid = $subscription->user_id;
				$sub_level=$subscription->next_subscription_level;
				$sub_type=$subscription->next_subscription_type;

				$sub_id = 'wpjobster_subscription_'.$sub_type.'_amount_'.$sub_level;
				$sub_amount = get_option($sub_id);
				$payable_amount = wpjobster_formats_special($sub_amount,2);

				$where = array("user_id"=>$uid);
				$cr = wpjobster_get_credits($uid);

				if($cr >= $payable_amount && $payable_amount>=1){

					$prices = $this->calculate_tax_and_fees($payable_amount);
					$buyer_processing_fees_orignal = $prices['buyer_processing_fees_orignal'];
					$tax_orignal = $prices['tax_orignal'];
					$total_amount_orignal= $prices['total_amount_orignal'];

					$_tax = $prices['tax'];
					$buyer_processing_fees = $prices['buyer_processing_fees'];
					$_payable_amount = $prices['payable_amount'];

					$subscription_order= array(
						'user_id'              => $uid,
						'subscription_amount'  => $payable_amount,
						'payment_status'       => 'completed',
						'subscription_status'  => 'active',
						'payment_gateway_name' => $this->_payment_gateway,
						'payable_amount'       => $_payable_amount,
						'currency'             => $this->_currency,
						'plan'                 => $sub_type,
						'level'                => $sub_level,
						'tax'                  => $_tax,
						'tax_orignal'          => $tax_orignal,
						'fees'                 => $buyer_processing_fees,
						'total_amount_orignal' => $total_amount_orignal,
						'fees_orignal'         => $buyer_processing_fees_orignal
					);

					$order_id = $this->insert_subscription_order($subscription_order);
					$this->update_subscription_order_status($order_id,'completed','active',$order_id,'',json_encode($subscription_order),$uid);
					$this->insert_subscription_payment_received($order_id,$this->_payment_gateway,'Completed',$order_id,'Schedule subscription');
					$this->update_subscription_payment_received($order_id,'completed','Schedule Subscription',json_encode($subscription_order));

					wpjobster_update_credits($uid, $cr - $payable_amount);
					$reason = __("Payment for subscription renewal", "wpjobster");
					$details = $sub_type . "_" . $sub_level . "_" . "renew";


					wpjobster_add_history_log('0', $reason, $payable_amount, $uid, '', '',11 , $details);
					$next_billing_date=$this->get_next_billing_date($sub_type);
					$subscription_data= array("subscription_level"=>$sub_level,
					"subscription_type"=>$sub_type,"subscription_amount"=>$payable_amount,"next_billing_date"=>$next_billing_date);

					$this->_wpdb->update($this->_wpdb->prefix.'job_subscriptions',$subscription_data,$where);
					delete_user_meta($uid,"wpjobster_subscription_prior_email_notification_sent");
					$this->send_subscription_email($email_reason, $uid,$payable_amount);


				}else{
					$this->remove_subscription($uid);
					$email_reason='subscription_cancel_lowbalance';
					wpjobster_send_email_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false,$payable_amount);
					wpjobster_send_sms_allinone_translated($email_reason, $uid, false, false, false, false, false, false, false, $payable_amount);

				}

				do_user_level_extras_check($uid);
				do_user_level_extras_price_check($uid);

				do_user_level_job_price_check($uid);

			}
		}
	}
}

