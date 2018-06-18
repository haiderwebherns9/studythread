<?php
class WPJ_Banktransfer {
	public $_currency,$_package_id,$_current_user,$_wpdb,$_wp_query,$_pay_pg_lnk,$_package_description_credit,$_package_description_cost;
	public $_unique_id;

	public function __construct($gateway='banktransfer') {
		$this->_payment_gateway=$this->_unique_id=$gateway;
		add_action("show_banktransfer_form",array($this,"show_banktransfer_form"),10,3);
		add_action("banktransfer_response",array($this,"process_banktransfer"),10,3);
	}

	function process_banktransfer($payment_type){
		//-----------------------------------------------------
		// Calculate Total
		//-----------------------------------------------------

		$response_array["get"]=$_GET;
		$order_id = isset($_GET['order_id'])?$_GET['order_id']:0;
		if(isset($_POST)){
			$response_array["post"]=$_POST;
		}
		parse_str(file_get_contents("php://input"), $response_array["php_input"]);

		if ( $order_id ) {
			if ( current_user_can( 'administrator' ) ) {
				$cancel_message = "Cancelled by admin";
				$complete_message = "Completed by admin";
			} else {
				$cancel_message = "Cancelled by buyer";
			}
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'complete' ) {
				if ( current_user_can( 'administrator' ) ) {
					do_action( "wpjobster_" . $payment_type . "_payment_success", $order_id, $this->_unique_id, $complete_message, json_encode( $response_array ) );
				} else {
					wp_die(
						__( 'Only admins can complete bank transfers.', 'wpjobster' ),
						__( 'Unauthorized', 'wpjobster' ),
						array( 'response' => 401 )
					);
				}
			} else {
				do_action( "wpjobster_" . $payment_type . "_payment_failed", $order_id, $this->_unique_id, $cancel_message, json_encode( $response_array ) );
			}

			die();

		} else {
			wp_die(
				__( 'Missing order id.', 'wpjobster' ),
				__( 'Bad request', 'wpjobster' ),
				array( 'response' => 400 )
			);
		}
	}

	/*
	 * Process when requesting payment sending using bank transfer method
	 */

	function show_banktransfer_form( $payment_type, $common_details,$job_id=0 ){

		if( $job_id == 0 ){
			$job_id=$common_details['pid'];
		}

		$orderid = $common_details['order_id'];

		$receiver = $common_details['uid'];
		if($payment_type=='job_purchase' || $payment_type=='feature'){
			$pid = $common_details['pid'];
		}else{
			$pid = false;
		}
		if($payment_type=='topup'){
			$reason = 'send_bankdetails_to_topup_buyer';
		}elseif($payment_type=='feature'){
			$reason = 'send_bankdetails_to_feature_buyer';
		}elseif($payment_type=='custom_extra'){
			$reason = 'send_bankdetails_to_custom_extra_buyer';
		}else{
			$reason = 'send_bankdetails_to_buyer';
		}

		wpjobster_send_email_allinone_translated($reason, $receiver , $sender = false, $pid , $oid = $orderid);

		wpjobster_send_email_allinone_translated('new_bank_transfer_pending', 'admin', $common_details['uid'], $common_details['pid'], $orderid, false, false, false, false, false, false, false, $payment_type, wpjobster_get_show_price_classic( $common_details['wpjobster_final_payable_amount'] ) );
		wpjobster_send_sms_allinone_translated('new_bank_transfer_pending', 'admin', $common_details['uid'], $common_details['pid'], $orderid, false, false, false, false, false, false, false, $payment_type, wpjobster_get_show_price_classic( $common_details['wpjobster_final_payable_amount'] ) );

		if ( $orderid ) {
				if( $payment_type == 'topup' ){
					wp_redirect(get_bloginfo( 'siteurl' ).'/?jb_action=show_bank_details&oid='.$orderid.'&payment_type='.$payment_type);
				}elseif( $payment_type == 'feature' ){
					wp_redirect(get_bloginfo( 'siteurl' ).'/?jb_action=feature_job&jobid='.$job_id.'&payment_type='.$payment_type);
				}elseif( $payment_type == 'custom_extra' ){
					wp_redirect(get_bloginfo( 'siteurl' ).'/?jb_action=chat_box&oid='.$job_id.'&payment_type='.$payment_type);
				}else{
					wp_redirect( get_bloginfo( 'siteurl' ).'/?jb_action=chat_box&oid='.$orderid );
				}
			exit;
		} else {
			echo __( 'Error while inserting the order. Please contact the site administrator.', 'wpjobster' );
		}
	}
}

$cl = new WPJ_Banktransfer();
