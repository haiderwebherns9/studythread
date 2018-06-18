<?php
if ( ! function_exists( 'wpjobster_sms_allowed' ) ) {
	function wpjobster_sms_allowed() {
		return wpj_is_allowed( 'sms_notifications' );
	}
}

if ( ! function_exists( 'wpj_notification_key_exists' ) ) {
	function wpj_notification_key_exists( $key ) {
		$reasons = notifications_array();

		foreach ( $reasons as $reason ) {
			if ( array_key_exists( $key, $reason['items'] ) ) {
				return true;
			}
		}

		return false;
	}
}

//ARRAY FOR NOTIFICATIONS
if ( ! function_exists( 'notifications_array' ) ) {
	function notifications_array(){
		$reasons = array(
			"admin" => array(
				"title" => "Admin Notifications",
				"items" => array(
					"user_admin_new" => array(
						"title"       => __( "New User", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when a new user registers on the website.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##user_email##</strong>",
					),
					"job_admin_new" => array(
						"title"       => __( "New Job Not-Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when someone posts a job on the website if the job is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##job_name##</strong>",
					),
					"job_admin_acc" => array(
						"title"       => __( "New Job Approved", "wpjobster" ),
						"description" =>
							"This email will be received by the admin when someone posts a job on the website if the the job is automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##job_link##, <br /> ##job_name##</strong>",
					),
					"job_edit" => array(
						"title"       => __( "Edit Job", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when a user edit a job and the job is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##user_email##</strong>",
					),
					"featured_admin_new" => array(
						"title"       => __( "New Job Featured", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when someone features a job on the website.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##all_featured_info##</strong>",
					),
					"report_job_admin" => array(
						"title"       => __( "Report Job", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when any user reports the job.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,<br /> ##report_description## </strong>",
					),
					"request_admin_new" => array(
						"title"       => __( "New Request Not-Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when someone posts a request on the website if the request is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##request_name##</strong>",
					),
					"request_admin_acc" => array(
						"title"       => __( "New Request Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when someone posts a request on the website if the the job is automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##request_link##, <br /> ##request_name##</strong>",
					),
					"request_edit" => array(
						"title"       => __( "Edit Request", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when an user edits a request and the request is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##user_email##</strong>",
					),
					"custom_extra_paid_admin_new" => array(
						"title"       => __( "New Custom Extra Paid", "wpjobster" ),
						"description" =>
							"This notification will be received by admin when a custom extra is bought.
							<br><br> Available shortcodes:
							<br><br> <strong>##username##, <br> ##your_site_name##, <br> ##your_site_url##, <br> ##transaction_page_link##</strong>",
					),
					"balance_admin_down" => array(
						"title"       => __( "Balance Down", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when an user's credits are decreased by an admin.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_admin_up" => array(
						"title"       => __( "Balance Up", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when an user's credits are increased by an admin.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_admin_up_paypal" => array(
						"title"       => __( "Balance Up via Paypal", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when an user's credits are increased via Paypal.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_admin_up_topup" => array(
						"title"       => __( "Balance Up via Top Up", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when an user's credits are increased via Top Up.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## <br /> ##payment_gatway##  </strong>",
					),
					"admin_payment_completed_by_admin" => array(
						"title"       => __( "Admin Mark Payment Complete", "wpjobster" ),
						"description" =>
							"This notification will be received by admin when admin marks as completed any job payment.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,<br /> ##transaction_page_link##,  <br /> ##my_account_url##, <br /> ##job_name##</strong>",
					),
					"new_bank_transfer_pending" => array(
						"title"       => __( "New Bank Transfer Pending", "wpjobster" ),
						"description" =>
							"This notification will be received by the admin when user purchases via bank transfer.
							<br><br> Available shortcodes:
							<br><br> <strong>##sender_username##, <br> ##payment_type##, <br> ##payment_amount##, <br> ##admin_orders_url##, <br> ##your_site_name##  </strong>",
					),
				),
			),
			"registration" => array(
				"title" => "Registration",
				"items" => array(
					"user_new" => array(
						"title"       => __( "New User", "wpjobster" ),
						"description" =>
							"This notification will be received by all new users who register on your website using the Ajax form and social login options (most of them).
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##receiver_email##, <br /> ##email_verification##</strong>",
					),
					"user_noajax_new" => array(
						"title"       => __( "New User NoAjax", "wpjobster" ),
						"description" =>
							"This notification will be received by all new users who register on your website using the old register form (only a few).
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##receiver_email##, <br /> ##password##</strong>",
					),
					"user_verification" => array(
						"title"       => __( "User Email Verification", "wpjobster" ),
						"description" =>
							"This notification will be received by an user who wants to verify his email and he didn't receive the verification link into the registration email.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##receiver_email##, <br /> ##email_verification##</strong>",
					),
				),
			),
			"levels" => array(
				"title" => "User Levels",
				"items" => array(
					"level_down" => array(
						"title"       => __( "Level Down", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his level gets downgraded.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##current_level## </strong>",
					),
					"level_up" => array(
						"title"       => __( "Level Up", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his level gets upgraded.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##current_level## </strong>",
					),
				),
			),
			"messages" => array(
				"title" => "Messages",
				"items" => array(
					"new_message" => array(
						"title"       => __( "New Message", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when they receive a private message in their account.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
				),
			),
			"jobs" => array(
				"title" => "Jobs",
				"items" => array(
					"job_new" => array(
						"title"       => __( "New Job Not Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by your users after posting a new job on your website if the job is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_name##</strong>",
					),
					"job_acc" => array(
						"title"       => __( "New Job Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by your users after posting a new job on your website if the job is automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##</strong>",
					),
					"job_decl" => array(
						"title"       => __( "New Job Rejected", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when you reject a job by marking it as pending.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_name##</strong>",
					),
					"featured_new" => array(
						"title"       => __( "New Job Featured", "wpjobster" ),
						"description" =>
							"This notification will be received by your users after featuring a job on your website.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_name##, <br /> ##job_link##, <br /> ##all_featured_info##</strong>",
					),
					"report_job_user" => array(
						"title"       => __( "Report job", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when he reports a job.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,<br /> ##report_description## </strong>",
					),
				),
			),
			"job_orders" => array(
				"title" => "Job Orders",
				"items" => array(
					"purchased_buyer" => array(
						"title"       => __( "Job Purchased (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when they purchase a new job.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##,<br /> ##processing_fees##,<br /> ##tax_amount##,  <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"purchased_seller" => array(
						"title"       => __( "Job Sold (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when they sell one of their jobs.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"order_message" => array(
						"title"       => __( "Transaction Message", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when they receive a new message on a transaction page.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_buyer" => array(
						"title"       => __( "Cancellation Requested (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the job when the buyer requests a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_seller" => array(
						"title"       => __( "Cancellation Requested (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the job when the seller requests a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_acc_buyer" => array(
						"title"       => __( "Cancellation Accepted (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the job when the buyer accepts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_acc_seller" => array(
						"title"       => __( "Cancellation Accepted (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the job when the seller accepts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_decl_buyer" => array(
						"title"       => __( "Cancellation Declined (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the job when the buyer declines a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_decl_seller" => array(
						"title"       => __( "Cancellation Declined (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the job when the seller declines a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_abort_buyer" => array(
						"title"       => __( "Cancellation Aborted (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the job when the buyer aborts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_abort_seller" => array(
						"title"       => __( "Cancellation Aborted (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the job when the seller aborts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_admin" => array(
						"title"       => __( "Cancelled by Admin", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller and the buyer when the admin cancels the job.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"mod_buyer" => array(
						"title"       => __( "Modification Request (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the job when the buyer requests a modification.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"order_delivered" => array(
						"title"       => __( "Job Delivered (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when the seller marks the job as done/delivered.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"order_complete" => array(
						"title"       => __( "Job Completed (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when the buyer accepts the delivered job.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"new_feedback" => array(
						"title"       => __( "New Feedback (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when he received feedback from the buyer.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##job_link##, <br /> ##job_name##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
				),
			),
			"requests" => array(
				"title" => "Requests",
				"items" => array(
					"request_new" => array(
						"title"       => __( "New Request Not Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by your users after posting a new job on your website if the request is not automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##request_name##</strong>",
					),
					"request_acc" => array(
						"title"       => __( "New Request Approved", "wpjobster" ),
						"description" =>
							"This notification will be received by your users after posting a new request on your website if the job is automatically approved.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##request_link##, <br /> ##request_name##</strong>",
					),
					"request_decl" => array(
						"title"       => __( "New Request Rejected (Pending)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when you reject a job by marking it as pending.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##request_name##</strong>",
					),
					"withdraw_req" => array(
						"title"       => __( "Withdraw Request", "wpjobster" ),
						"description" =>
							"This notification will be received by the user after he requests a withdrawal.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_withdrawn##, <br /> ##withdraw_method##</strong>",
					),
					"withdraw_compl" => array(
						"title"       => __( "Withdraw Processed (Accepted)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user after his withdrawal request has been processed.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_withdrawn##, <br /> ##withdraw_method##</strong>",
					),
					"withdraw_decl" => array(
						"title"       => __( "Withdraw Rejected", "wpjobster" ),
						"description" =>
							"This notification will be received by the user after his withdrawal request has been rejected.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_withdrawn##, <br /> ##withdraw_method##</strong>",
					),
				),
			),
			"custom_offers" => array(
				"title" => "Custom Offers",
				"items" => array(
					"new_request" => array(
						"title"       => __( "New Request (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when they receive a custom offer request.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
					"new_offer" => array(
						"title"       => __( "New Offer (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when they receive a custom offer.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
					"offer_acc_buyer" => array(
						"title"       => __( "Offer Accepted (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when he accepted a custom offer.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##, <br /> ##transaction_page_link##, <br /> ##transaction_number##</strong>",
					),
					"offer_acc_seller" => array(
						"title"       => __( "Offer Accepted (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when his custom offer was accepted.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##, <br /> ##transaction_page_link##, <br /> ##transaction_number##</strong>",
					),
					"offer_decl" => array(
						"title"       => __( "Offer Declined (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when their custom offer was declined.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
					"offer_withdr" => array(
						"title"       => __( "Offer Withdrawn (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when their custom offer was withdrawn.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
					"offer_exp" => array(
						"title"       => __( "Offer Expired (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by your users when their custom offer expired.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##private_message_link##</strong>",
					),
				),
			),
			"custom_offer_orders" => array(
				"title" => "Custom Offer Orders",
				"items" => array(
					"cancel_offer_buyer" => array(
						"title"       => __( "Offer Cancellation Requested (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the custom offer when the buyer requests a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_seller" => array(
						"title"       => __( "Offer Cancellation Requested (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the custom offer when the seller requests a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_acc_buyer" => array(
						"title"       => __( "Offer Cancellation Accepted (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the custom offer when the buyer accepts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_acc_seller" => array(
						"title"       => __( "Offer Cancellation Accepted (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the custom offer when the seller accepts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_decl_buyer" => array(
						"title"       => __( "Offer Cancellation Declined (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the custom offer when the buyer declines a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_decl_seller" => array(
						"title"       => __( "Offer Cancellation Declined (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the custom offer when the seller declines a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_abort_buyer" => array(
						"title"       => __( "Offer Cancellation Aborted (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the custom offer when the buyer aborts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_abort_seller" => array(
						"title"       => __( "Offer Cancellation Aborted (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of the custom offer when the seller aborts a mutual cancellation.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"cancel_offer_admin" => array(
						"title"       => __( "Offer Cancelled by Admin", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller and the buyer when the admin cancels the custom offer.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"mod_offer_buyer" => array(
						"title"       => __( "Offer Modification Request (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller of the custom offer when the buyer requests a modification.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"order_offer_delivered" => array(
						"title"       => __( "Offer Job Delivered (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when the seller marks the custom offer as done.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"order_offer_complete" => array(
						"title"       => __( "Offer Job Completed (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when the buyer accepts the custom offer as delivered.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
					"new_offer_feedback" => array(
						"title"       => __( "Offer New Feedback (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when he received feedback from the buyer.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##transaction_number##, <br /> ##transaction_page_link##</strong>",
					),
				),
			),
			"custom_extras" => array(
				"title" => "Custom Extras",
				"items" => array(
					"new_custom_extra" => array(
						"title"       => __( "New Custom Extra (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when a new custom extra is added.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##sender_username##, <br> ##your_site_name##, <br> ##your_site_url##,<br> ##transaction_page_link## </strong>",
					),
					"cancel_custom_extra" => array(
						"title"       => __( "Custom Extra Cancelled (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when a custom extra is cancelled by seller.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##sender_username##, <br> ##your_site_name##, <br> ##your_site_url##,<br> ##transaction_page_link## </strong>",
					),
					"decline_custom_extra" => array(
						"title"       => __( "Custom Extra Declined (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when a custom extra is declined by buyer.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##sender_username##, <br> ##your_site_name##, <br> ##your_site_url##,<br> ##transaction_page_link## </strong>",
					),
					"custom_extra_paid_new" => array(
						"title"       => __( "Custom Extra Paid (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer of a custom extra.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##your_site_name##, <br> ##your_site_url##, <br> ##my_account_url##, <br> ##transaction_page_link##</strong>",
					),
					"custom_extra_paid_new_seller" => array(
						"title"       => __( "Custom Extra Paid (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when a custom extra is bought.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##sender_username##, <br> ##your_site_name##, <br> ##your_site_url##, <br> ##my_account_url##, <br> ##transaction_page_link##</strong>",
					),
					"custom_extra_cancelled_by_admin" => array(
						"title"       => __( "BT Custom Extra Cancelled (Buyer)", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when a Bank Transfer custom extra is cancelled by admin.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##your_site_name##, <br> ##your_site_url##,<br> ##transaction_page_link## </strong>",
					),
					"custom_extra_cancelled_by_admin_seller" => array(
						"title"       => __( "BT Custom Extra Cancelled (Seller)", "wpjobster" ),
						"description" =>
							"This notification will be received by the seller when a Bank Transfer custom extra is cancelled by admin.
							<br><br> Available shortcodes:
							<br><br> <strong>##receiver_username##, <br> ##your_site_name##, <br> ##your_site_url##,<br> ##transaction_page_link## </strong>",
					),
				),
			),
			"subscriptions" => array(
				"title" => "Subscriptions",
				"items" => array(
					"price_update_subscription" => array(
						"title"       => __( "Subscription Price Update", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when admin updates the subscription price.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_down_subscription" => array(
						"title"       => __( "New Subscription", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when he subscribes for any level.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##</strong>",
					),
					"balance_down_subscription_change" => array(
						"title"       => __( "Subscription Change", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when he changes his subscription level with immediate effect.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##,
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##</strong>",
					),
					"wpjobster_subscription_prior_notification" => array(
						"title"       => __( "Prior Subscription Renewal", "wpjobster" ),
						"description" =>
							"This notification will be received by the user few days before his subscription renewal.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##,<br />##no_of_days_subscription_left##</strong>",
					),
					"subscription_cancel" => array(
						"title"       => __( "Subscription Cancel (Manual)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when he cancels his subscription.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##,
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##</strong>",
					),
					"subscription_cancel_lowbalance" => array(
						"title"       => __( "Subscription Cancel (Low Balance)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his subscription was cancelled due to low credits balance.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##,
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##</strong>",
					),
					"subscription_schedule" => array(
						"title"       => __( "Subscription Schedule", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when he schedules his subscription.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated##,
							<br />##current_subscription_level##,<br />##next_subscription_amount##,<br />##next_billing_date##,<br />##next_subscription_level##,
							<br />##current_subscription_period##,<br />##current_subscription_amount##</strong>",
					),
				),
			),
			"user_balance" => array(
				"title" => "User Balance",
				"items" => array(
					"balance_down" => array(
						"title"       => __( "Balance Down", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his credits are decreased by an admin.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_up" => array(
						"title"       => __( "Balance Up", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his credits are increased by an admin.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
					"balance_up_paypal" => array(
						"title"       => __( "Balance Up via Paypal", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his credits are increased by payment via PayPal.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## <BR /> ##amount_updated_in_currency##</strong>",
					),
					"balance_up_topup" => array(
						"title"       => __( "Balance Up via Top Up", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when credits are increased via Topup.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## <BR /> ##amount_updated_in_currency##,<br /> ##payment_gatway##  </strong>",
					),
					"balance_negative" => array(
						"title"       => __( "Balance Negative", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when his credits are gone negative.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##, <br /> ##my_account_url##, <br /> ##amount_updated## </strong>",
					),
				),
			),
			"various_payments" => array(
				"title" => "Various Payments",
				"items" => array(
					"payment_completed_by_admin" => array(
						"title"       => __( "Admin mark Payment Complete", "wpjobster" ),
						"description" =>
							"This notification will be received by the buyer when admin marks the payment completed.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##sender_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,<br /> ##transaction_page_link##, <br /> ##my_account_url##, <br /> ##job_name##</strong>",
					),
					"send_bankdetails_to_buyer" => array(
						"title"       => __( "Send Bank Details", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when they choose to pay using the bank transfer payment menthod.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,##bank_details## </strong>",
					),
					"send_bankdetails_to_topup_buyer" => array(
						"title"       => __( "Send Bank Details (Top Up)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when they choose to pay using the bank transfer payment menthod.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,##bank_details## </strong>",
					),
					"send_bankdetails_to_feature_buyer" => array(
						"title"       => __( "Send Bank Details (Feature)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when they choose to pay using the bank transfer payment menthod.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,##bank_details## </strong>",
					),
					"send_bankdetails_to_custom_extra_buyer" => array(
						"title"       => __( "Send Bank Details (Custom Extra)", "wpjobster" ),
						"description" =>
							"This notification will be received by the user when they choose to pay using the bank transfer payment menthod.
							<br /><br /> Available shortcodes:
							<br /><br /> <strong>##receiver_username##, <br /> ##your_site_name##, <br /> ##your_site_url##,##bank_details## </strong>",
					),
				),
			),
		);
		$reasons = apply_filters( 'wpjobster_admin_menu_email_templates', $reasons );
		return $reasons;
	}
}

if ( ! function_exists( 'wpjobster_send_sms_allinone_translated' ) ) {
	function wpjobster_send_sms_allinone_translated($reason, $receiver = false, $sender = false, $pid = false, $oid = false, $method = false, $amount = false, $password = false, $email_key = false, $amount_updated = false,$amount_updated_in_currency=false, $payment_type=false, $payment_amount=false,$withdrawal_key=false) {

		if ( ! wpjobster_sms_allowed() ) {
			return false;
		}
		if ( ! wpj_notification_key_exists( $reason ) ) {
			return false;
		}

		$receiver=get_email_sms_receiver($receiver,$pid);
		if($receiver == false){return; }// returning if there is no receiver

		$current_subscription_level="";
		$current_subscription_period="";
		$current_subscription_amount="";
		$next_billing_date="";
		$next_subscription_level="";
		$next_subscription_amount="";
		if($receiver!='admin'){
			include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
			$wpjobster_subscription = new wpjobster_subscription();
			$current_subscription = $wpjobster_subscription->get_current_subscription($receiver);
			if($current_subscription){
				$current_subscription_level=$current_subscription->subscription_level;
				$current_subscription_period=$current_subscription->subscription_type;
				$current_subscription_amount=$current_subscription->subscription_amount;
				$next_billing_date=$current_subscription->next_billing_date;
				$next_subscription_level=$current_subscription->next_subscription_level;
				$next_subscription_amount=$current_subscription->next_subscription_amount;
			}
		}

		$default_lang = trim(get_option('wpjobster_language_1'));
		$lang = get_user_language_by_userid($receiver,$default_lang);// getting the user language. if no preference by user then using the default language

		$enable = get_option('uz_sms_'.$reason.'_enable');
		$message = get_option('uz_sms_'.$reason.'_'.$lang.'_message');

		$enable_user = get_user_meta($receiver, 'uz_sms_'.$reason.'_enable', true);

		if ($enable == "no") {
			return;
		}
		if ($enable_user == "no") {
			return;
		}

		if (!$message) {

			$message = get_option('uz_sms_'.$reason.'_'.$default_lang.'_message');

			if (!$message) {
				return;
			}
		}

		if($pid){
			$post = get_post($pid);
			$user = get_userdata($post->post_author);
			$job_name = $post->post_title;
		}
		if ($receiver == 'admin' && !$pid) {
			$user = get_userdata($sender);
		}

		$site_login_url = wpjobster_login_url();
		$site_name = get_bloginfo('name');
		$account_url = get_permalink(get_option('wpjobster_my_account_page_id'));
		$cnv_lnk = get_bloginfo('url') . "/?jb_action=chat_box&oid=" . $oid;

		$receiver_level = get_user_meta($receiver, 'user_level', true);
		$receiver_data = get_userdata($receiver);

		if(isset($receiver_data->user_nicename)){$receiver_user_nicename = $receiver_data->user_nicename; }else{ $receiver_user_nicename = ""; }

		if(isset($job_name)){ $job_name = $post->post_title; }
		$job_link = get_permalink($pid);

		global $wpdb; $prefix = $wpdb->prefix;

		if($oid){
			$r = wpjobster_get_order_details_by_orderid($oid);
			$date_made = $r->date_made;
			$_order_processing_fees = $r->processing_fees;
			$_tax_amount = $r->tax_amount;
			$uz_order = wpjobster_camouflage_order_id($oid, $date_made);
		}
		if ($sender) {
			$sender_data = get_userdata($sender);

			if(isset($sender_data->user_nicename)){
				$sender_user_nicename = $sender_data->user_nicename;
			}else{
				$sender_user_nicename = '';
			}
		} elseif ($oid) {
			if ($r->uid != $receiver) {
				$sender_data = get_userdata($r->uid);
			} else {
				$post = get_post($r->pid);
				$sender_data = get_userdata($post->post_author);
			}

			if(isset($sender_data->user_nicename)){
				$sender_user_nicename = $sender_data->user_nicename;
			}else{
				$sender_user_nicename = '';
			}
		}else{
			if(isset($sender_data->user_nicename)){
				$sender_user_nicename = $sender_data->user_nicename;
			}else{
				$sender_user_nicename = '';
			}
		}

		$private_message_link = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')).'?username='.$sender_user_nicename;

		if (isset($email_key)) {
			$email_key_processed = hash("sha256", $receiver_user_nicename . $email_key, false);
			$email_verification = get_bloginfo('url') . "/?jb_action=verify_email&username=" . $receiver_user_nicename . "&key=" . $email_key_processed;
		}

		if ( $withdrawal_key ) {
			$withdrawal_email_verification = get_bloginfo('url') . "/?jb_action=verify_email&username=" . $receiver_data->user_nicename . "&key=" . $withdrawal_key . "&action=withdrawal";
		}

		$all_featured_info = "";

		if(($reason=='featured_new' || $reason=='featured_admin_new')&&$pid!=false){
			$all_featured_info = wpjobster_get_allfeatured_info_by_postid($pid,"sms");
		}

		if( $payment_type == 'topup' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-10';
		}elseif( $payment_type == 'feature' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-11';
		}elseif( $payment_type == 'custom_extra' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-12';
		}else{
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats';
		}

		$find = array(
			'##username##',
			'##receiver_username##',
			'##sender_username##',
			'##site_login_url##',
			'##your_site_name##',
			'##your_site_url##',
			'##my_account_url##',
			'##job_link##',
			'##job_name##',
			'##transaction_number##',
			'##transaction_page_link##',
			'##amount_withdrawn##',
			'##withdraw_method##',
			'##current_level##',
			'##receiver_email##',
			'##user_email##',
			'##private_message_link##',
			'##password##',
			'##email_verification##',
			'##withdrawal_email_verification##',
			'##amount_updated##',
			"##all_featured_info##",
			'##processing_fees##',
			'##tax_amount##',
			"##current_subscription_level##",
			"##current_subscription_period##",
			"##current_subscription_amount##",
			"##next_billing_date##",
			"##next_subscription_level##",
			"##next_subscription_amount##",
			"##amount_updated_in_currency##",
			"##payment_type##",
			"##payment_amount##",
			"##admin_orders_url##"
		);

		$replace =  array(isset($user->user_login)&&$user->user_login!=''?$user->user_login:"##username##",
			isset($receiver_data->user_login)&&$receiver_data->user_login!=''?$receiver_data->user_login:"##receiver_username##",
			isset($sender_data->user_login)&&$sender_data->user_login!=''?$sender_data->user_login:"##sender_username##",
			isset($site_login_url)&&$site_login_url!=''?$site_login_url:"##site_login_url##",
			isset($site_name)&&$site_name!=''?$site_name:"##site_name##",
			get_bloginfo('url'),
			isset($account_url)&&$account_url!=''?$account_url:"##account_url##",
			isset($job_link)&&$job_link!=''?$job_link:"##job_link##",
			isset($job_name)&&$job_name!=''?$job_name:"##job_name##",
			isset($uz_order)&&$uz_order!=''?$uz_order:"##transaction_number##",
			isset($cnv_lnk)&&$cnv_lnk!=''?$cnv_lnk:"##transaction_page_link##",
			isset($amount)&&$amount!=''?$amount:"##amount_withdrawn##",
			isset($method)&&$method!=''?$method:"##withdraw_method##",
			isset($receiver_level)&&$receiver_level!=''?$receiver_level:"##current_level##",
			isset($receiver_data->user_email)&&$receiver_data->user_email!=''?$receiver_data->user_email:"##receiver_email##",
			isset($user->user_email)&&$user->user_email!=''?$user->user_email:"##user_email##",
			isset($private_message_link)&&$private_message_link!=''?$private_message_link:"##private_message_link##",
			isset($password)&&$password!=''?$password:"##password##",
			isset($email_verification)&&$email_verification!=''?$email_verification:"##email_verification##",
			isset($withdrawal_email_verification)&&$withdrawal_email_verification!=''?$withdrawal_email_verification:"##withdrawal_email_verification##",
			wpjobster_get_show_price_classic($amount_updated),
			isset($all_featured_info)&&$all_featured_info!=''?$all_featured_info:"##all_featured_info##",
			isset($_order_processing_fees)&&$_order_processing_fees!=''?$_order_processing_fees:"##processing_fees##",
			isset($_tax_amount)&&$_tax_amount!=''?$_tax_amount:"##tax_amount##",
			isset($current_subscription_level)&&$current_subscription_level!=''?$current_subscription_level:"##current_subscription_level##",
			isset($current_subscription_period)&&$current_subscription_period!=''?$current_subscription_period:"##current_subscription_period##",
			isset($current_subscription_amount)&&$current_subscription_amount!=''?$current_subscription_amount:"##current_subscription_amount##",
			isset($next_billing_date)&&$next_billing_date!=''?$next_billing_date:"##next_billing_date##",
			isset($next_subscription_level)&&$next_subscription_level!=''?$next_subscription_level:"##next_subscription_level##",
			isset($next_subscription_amount)&&$next_subscription_amount!=''?$next_subscription_amount:"##next_subscription_amount##",
			isset($amount_updated_in_currency)&&$amount_updated_in_currency!=''?$amount_updated_in_currency:"##amount_updated_in_currency##",
			isset($payment_type)&&$payment_type!=''?$payment_type:"##payment_type##",
			isset($payment_amount)&&$payment_amount!=''?$payment_amount:"##payment_amount##",
			isset($admin_orders_url)&&$admin_orders_url!=''?$admin_orders_url:"##admin_orders_url##"
		);

		$message = wpjobster_replace_stuff_for_me($find, $replace, $message);

		$sms_message = stripslashes($message);
		$sms_receiver = $receiver;

		apply_filters( 'wpjobster_send_sms_gateway_filter', $sms_receiver, $sms_message, 15, 2 );

	}
}

add_filter( 'wpjobster_send_sms_gateway_filter', 'wpjobster_send_sms', 10, 2 );
if ( ! function_exists( 'wpjobster_send_sms' ) ) {
	function wpjobster_send_sms( $sms_receiver, $sms_message ) {
		if ( ! wpjobster_sms_allowed() ) { return false; }

		// general options
		$sms_gateway  = get_option( "wpjobster_sms_gateways_enable" );
		$admin_number = stripslashes( get_option( 'wpjobster_sms_admin_numb_from' ) );
		$user_number  = get_user_meta( $sms_receiver, 'cell_number', true );

		if ( $sms_receiver == 'admin' ) {
			$receiver_number = $admin_number;
		} else {
			$receiver_number = $user_number;
		}

		// gateway switch
		switch ( $sms_gateway ) {
			case 'twilio':
				$AccountSid    = get_option( "wpjobster_theme_accountsid" );
				$AuthToken     = get_option( "wpjobster_theme_authtoken" );
				$client        = new Services_Twilio( $AccountSid, $AuthToken );
				$sender_number = stripslashes( get_option( 'wpjobster_sms_numb_twilio_from' ) );

				$response = "";
				try {
					$response = $client->account->messages->create( array(
						'To'   => $receiver_number,
						'From' => $sender_number,
						'Body' => $sms_message,
					) );
				} catch ( Services_Twilio_RestException $e ) {
					$response = $e->getMessage();
				}
				break;

			case 'cafe24':
				$cafeId         = get_option( "wpjobster_theme_cafe_userid" );
				$cafeKey        = get_option( "wpjobster_theme_cafe_secure" );
				$sender_number  = get_option( "wpjobster_sms_numb_cafe_from" );
				$cafe           = new ServicesCafe( $cafeId, $cafeKey );
				$response       = $cafe->send( $sms_message, $receiver_number, $sender_number );
				break;
		}
	}
}

if ( ! function_exists( 'write_log_sms' ) ) {
	function write_log_sms($message) {
		// Determine log file
		$logfile = "logSms.txt";

		// Get time of request
		if( ($time = $_SERVER['REQUEST_TIME']) == '') {
			$time = time();
		}
		// Get IP address
		if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
			$remote_addr = "REMOTE_ADDR_UNKNOWN";
		}
		// Get requested script
		if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
			$request_uri = "REQUEST_URI_UNKNOWN";
		}
		// Format the date and time
		$date = date("Y-m-d H:i:s", $time);

		// Append to the log file
		if($fd = @fopen($logfile, "a")) {
			$result = fputcsv($fd, array($date, $remote_addr, $request_uri, $message));
			fclose($fd);
			if($result > 0){
				echo "archivo abierto";
			}else{
				echo 'Unable to write to ';
			}
		} else {
			echo 'Unable to open log '.$logfile.'!';
		}
	}
}

if ( ! function_exists( 'get_email_sms_receiver' ) ) {
	function get_email_sms_receiver($receiver,$pid){
		if($receiver == false) {$receiver = get_post_field('post_author', $pid);}
		return $receiver;
	}
}

if ( ! function_exists( 'wpjobster_send_email_allinone_translated' ) ) {
	function wpjobster_send_email_allinone_translated($reason, $receiver = false, $sender = false, $pid = false, $oid = false, $method = false, $amount = false, $password = false, $email_key = false, $amount_updated = false,$amount_updated_in_currency=false,$package_oid=false,$payment_type=false,$payment_amount=false,$withdrawal_key=false) {
		global $wpdb; $prefix = $wpdb->prefix;

		if ( ! wpj_notification_key_exists( $reason ) ) {
			return false;
		}

		if(isset($_POST['content'])){
			$report_description = empty($_POST['content']) ? '' : trim(nl2br(strip_tags(wpj_encode_emoji($_POST['content']))));
		}else{
			$report_description='';
		}

		$bank_details = nl2br(get_option('wpjobster_bank_details'));

		$receiver = get_email_sms_receiver($receiver,$pid);// getting the userid of the receiver
		if($receiver == false) {return; }// returning if there is no receiver

		$default_lang = trim(get_option('wpjobster_language_1'));
		$lang = get_user_language_by_userid($receiver,$default_lang);// getting the user language. if no preference by user then using the default language

		$enable = get_option('uz_email_'.$reason.'_enable');
		$subject = get_option('uz_email_'.$reason.'_'.$lang.'_subject');
		$message = get_option('uz_email_'.$reason.'_'.$lang.'_message');

		$enable_user = get_user_meta($receiver, 'uz_email_'.$reason.'_enable', true);

		if ( $enable == "no" ) {
			return;
		}

		if ( $reason == 'user_verification' && get_option( 'wpjobster_verify_email' ) == 'no' ) {
			return;
		}

		if ( $enable_user == "no" ) {
			return;
		}

		if ( ! $subject || ! $message ) {
			$subject = get_option('uz_email_'.$reason.'_'.$default_lang.'_subject');
			$message = get_option('uz_email_'.$reason.'_'.$default_lang.'_message');

			if (!$subject || !$message) {
				return;
			}
		}

		$site_login_url = wpjobster_login_url();
		$site_name = get_bloginfo('name');
		$account_url = get_permalink(get_option('wpjobster_my_account_page_id'));
		if($pid){ // written this if statement to reduce the notices
			$post = get_post($pid);
			$user = get_userdata($post->post_author);
			$job_name = $post->post_title;
			$job_link = urldecode(get_permalink($pid));
		}elseif ($receiver == 'admin' && !$pid) {
			$user = get_userdata($sender);
		}
		$cnv_lnk = get_bloginfo('url') . "/?jb_action=chat_box&oid=" . $oid;

		$receiver_level = get_user_meta($receiver, 'user_level', true);
		$receiver_data = get_userdata($receiver);

		if ($oid) {
			$r = wpjobster_get_order_details_by_orderid($oid); // getting order details using order id
			$date_made = $r->date_made;
			$_order_processing_fees = $r->processing_fees;
			$_tax_amount = $r->tax_amount;

			$uz_order = wpjobster_camouflage_order_id($oid, $date_made);
			if ($r->uid != $receiver) {
				$sender_data = get_userdata($r->uid);
			} else {
				$post = get_post($r->pid);
				$sender_data = get_userdata($post->post_author);
			}
		}
		if ($sender) {
			$sender_data = get_userdata($sender);
		}
		if(isset($sender_data) && is_object($sender_data)){
			$private_message_link = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')).'?username='.$sender_data->user_nicename;
		}

		if ($email_key) {
			$email_key_processed = hash("sha256", $receiver_data->user_nicename . $email_key, false);
			$email_verification = get_bloginfo('url') . "/?jb_action=verify_email&username=" . $receiver_data->user_nicename . "&key=" . $email_key_processed;
		}

		if ( $withdrawal_key ) {
			$withdrawal_email_verification = get_bloginfo('url') . "/?jb_action=verify_email&username=" . $receiver_data->user_nicename . "&key=" . $withdrawal_key . "&action=withdrawal";
		}

		$all_featured_info = "";
		if(($reason=='featured_new' || $reason=='featured_admin_new')&&$pid!=false){
			$all_featured_info = wpjobster_get_allfeatured_info_by_postid($pid);
		}
		if(($reason=='balance_admin_up_topup' || $reason=='balance_up_topup') && $package_oid!=false){
			$select = "select * from {$wpdb->prefix}job_topup_orders where id='$package_oid'";
			$pacakge_result = $wpdb->get_results($select);
			if($pacakge_result){
				$package = $pacakge_result[0];
				$payment_gateway_name = $package->payment_gateway_name;
			}else{
				$payment_gateway_name = "Name cant find - $select ";
			}
		}
		if($receiver!='admin'){
			include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
			$wpjobster_subscription = new wpjobster_subscription();
			$current_subscription = $wpjobster_subscription->get_current_subscription($receiver);
			if($current_subscription){
				$current_subscription_level=$current_subscription->subscription_level;
				$current_subscription_period=$current_subscription->subscription_type;
				$current_subscription_amount=$current_subscription->subscription_amount;
				$next_billing_date=$current_subscription->next_billing_date;
				$next_subscription_level=$current_subscription->next_subscription_level;
				$next_subscription_amount=$current_subscription->next_subscription_amount;
			}
		}

		if( $payment_type == 'topup' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-10';
		}elseif( $payment_type == 'feature' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-11';
		}elseif( $payment_type == 'custom_extra' ){
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats&active_tab=tabs-12';
		}else{
			$admin_orders_url = get_admin_url() . 'admin.php?page=order-stats';
		}

		$find = array('##username##',
			'##receiver_username##',
			'##sender_username##',
			'##site_login_url##',
			'##your_site_name##',
			'##your_site_url##',
			'##my_account_url##',
			'##job_link##',
			'##request_link##',
			'##job_name##',
			'##request_name##',
			'##transaction_number##',
			'##transaction_page_link##',
			'##amount_withdrawn##',
			'##withdraw_method##',
			'##current_level##',
			'##receiver_email##',
			'##user_email##',
			'##private_message_link##',
			'##password##',
			'##email_verification##',
			'##withdrawal_email_verification##',
			'##amount_updated##',
			"##all_featured_info##",
			'##processing_fees##',
			'##tax_amount##',
			"##current_subscription_level##",
			"##current_subscription_period##",
			"##current_subscription_amount##",
			"##next_billing_date##",
			"##next_subscription_level##",
			"##next_subscription_amount##",
			"##amount_updated_in_currency##",
			"##payment_gatway##",
			"##bank_details##",
			'##report_description##',
			"##payment_type##",
			"##payment_amount##",
			"##admin_orders_url##"
		);

		$replace = array((isset($user->user_login)&&$user->user_login!='')?$user->user_login:
			((is_object($receiver_data)&&isset($receiver_data->user_login)&&$receiver_data->user_login!='')?
				$receiver_data->user_login:"##username##"),
					isset($receiver_data->user_login)&&$receiver_data->user_login!=''?$receiver_data->user_login:"##receiver_username##",
					isset($sender_data->user_login)&&$sender_data->user_login!=''?$sender_data->user_login:"##sender_username##",
					isset($site_login_url)&&$site_login_url!=''?$site_login_url:"##site_login_url##",
					isset($site_name)&&$site_name!=''?$site_name:"##site_name##",
					get_bloginfo('url'),
					isset($account_url)&&$account_url!=''?$account_url:"##account_url##",
					isset($job_link)&&$job_link!=''?$job_link:"##job_link##",
					isset($job_link)&&$job_link!=''?$job_link:"##request_link##",
					isset($job_name)&&$job_name!=''?$job_name:"##job_name##",
					isset($job_name)&&$job_name!=''?$job_name:"##request_name##",
					isset($uz_order)&&$uz_order!=''?$uz_order:"##transaction_number##",
					isset($cnv_lnk)&&$cnv_lnk!=''?$cnv_lnk:"##transaction_page_link##",
					isset($amount)&&$amount!=''?$amount:"##amount_withdrawn##",
					isset($method)&&$method!=''?$method:"##withdraw_method##",
					isset($receiver_level)&&$receiver_level!=''?$receiver_level:"##current_level##",
					isset($receiver_data->user_email)&&$receiver_data->user_email!=''?$receiver_data->user_email:"##receiver_email##",
					isset($user->user_email)&&$user->user_email!=''?$user->user_email:"##user_email##",
					isset($private_message_link)&&$private_message_link!=''?$private_message_link:"##private_message_link##",
					isset($password)&&$password!=''?$password:"##password##",
					isset($email_verification)&&$email_verification!=''?$email_verification:"##email_verification##",
					isset($withdrawal_email_verification)&&$withdrawal_email_verification!=''?$withdrawal_email_verification:"##withdrawal_email_verification##",
					wpjobster_get_show_price_classic($amount_updated),
					isset($all_featured_info)&&$all_featured_info!=''?$all_featured_info:"##all_featured_info##",
					isset($_order_processing_fees)&&$_order_processing_fees!=''?$_order_processing_fees:"##processing_fees##",
					isset($_tax_amount)&&$_tax_amount!=''?$_tax_amount:"##tax_amount##",
					isset($current_subscription_level)&&$current_subscription_level!=''?$current_subscription_level:"##current_subscription_level##",
					isset($current_subscription_period)&&$current_subscription_period!=''?$current_subscription_period:"##current_subscription_period##",
					isset($current_subscription_amount)&&$current_subscription_amount!=''?$current_subscription_amount:"##current_subscription_amount##",
					isset($next_billing_date)&&$next_billing_date!=''?$next_billing_date:"##next_billing_date##",
					isset($next_subscription_level)&&$next_subscription_level!=''?$next_subscription_level:"##next_subscription_level##",
					isset($next_subscription_amount)&&$next_subscription_amount!=''?$next_subscription_amount:"##next_subscription_amount##",
					isset($amount_updated_in_currency)&&$amount_updated_in_currency!=''?$amount_updated_in_currency:"##amount_updated_in_currency##",
					isset($payment_gateway_name)&&$payment_gateway_name!=''?$payment_gateway_name:"##payment_gateway##",
					isset($bank_details)&&$bank_details!=''?$bank_details:"##bank_details##",
					isset($report_description)&&$report_description!=''?$report_description:"##report_description##not found ",
					isset($payment_type)&&$payment_type!=''?$payment_type:"##payment_type##",
					isset($payment_amount)&&$payment_amount!=''?$payment_amount:"##payment_amount##",
					isset($admin_orders_url)&&$admin_orders_url!=''?$admin_orders_url:"##admin_orders_url##"
		);

		$message = wpjobster_replace_stuff_for_me($find, $replace, $message);
		$subject = wpjobster_replace_stuff_for_me($find, $replace, $subject);

		if ($receiver == 'admin') {
			$email = get_bloginfo('admin_email');
		}else{
			$email = $receiver_data->user_email;
		}

		wpjobster_send_email($email, stripslashes($subject), stripslashes($message));
	}
}

add_filter('wp_mail_from', 'wpjobster_mail_from');
if ( ! function_exists( 'wpjobster_mail_from' ) ) {
	function wpjobster_mail_from($old){
		$wpjobster_email_addr_from = get_option('wpjobster_email_addr_from');
		$wpjobster_email_addr_from = trim($wpjobster_email_addr_from);

		if (!empty($wpjobster_email_addr_from)) {       return $wpjobster_email_addr_from;
		}
		else {
			return 'info@wpjobster.com';
		}
	}
}

add_filter('wp_mail_from_name', 'wpjobster_mail_from_name');
if ( ! function_exists( 'wpjobster_mail_from_name' ) ) {
	function wpjobster_mail_from_name($old){
		$wpjobster_email_name_from = get_option('wpjobster_email_name_from');
		$wpjobster_email_name_from = trim($wpjobster_email_name_from);

		if (!empty($wpjobster_email_name_from)) {       return $wpjobster_email_name_from;
		}
		else {
			return 'wpjobster.com';
		}
	}
}

if ( ! function_exists( 'wpjobster_parseEmails' ) ) {
	function wpjobster_parseEmails($string){
		// Add <a> tags around all email addresses in $string
		return ereg_replace("[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,3})", "[email_removed]", $string);
	}
}

if ( ! function_exists( 'wpjobster_set_email_content_type' ) ) {
	function wpjobster_set_email_content_type(){
		if ( wpj_bool_option( 'wpjobster_allow_html_emails' )
			&& ! is_plugin_active( 'wp-better-emails/wpbe.php' ) ) {
			return "text/html";
		} else {
			return "text/plain";
		}
	}
}
add_filter( 'wp_mail_content_type','wpjobster_set_email_content_type' );

if ( ! function_exists( 'wpjobster_send_email' ) ) {
	function wpjobster_send_email( $recipients, $subject = '', $message = '' ) {

		$wpjobster_email_addr_from = get_option( 'wpjobster_email_addr_from' );
		$wpjobster_email_name_from = get_option( 'wpjobster_email_name_from' );

		if ( empty( $wpjobster_email_name_from ) ) {
			$wpjobster_email_name_from = get_bloginfo( 'name' );
		}

		if ( empty( $wpjobster_email_addr_from ) ) {
			$wpjobster_email_addr_from = get_bloginfo( 'admin_email' );
		}

		$headers = 'From: ' . $wpjobster_email_name_from . ' <' . $wpjobster_email_addr_from . '>' . PHP_EOL;

		if ( wpj_bool_option( 'wpjobster_allow_html_emails' )
			&& ! is_plugin_active( 'wp-better-emails/wpbe.php' ) ) {
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"" . get_bloginfo('charset') . "\"\n";
			$mailtext = "<html><head><title>" . $subject . "</title></head><body>" . $message . "</body></html>";
			return wp_mail( $recipients, $subject, $mailtext );
		} else {
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/plain; charset=\"" . get_bloginfo( 'charset' ) . "\"\n";
			$message = preg_replace( '|&[^a][^m][^p].{0,3};|', '', $message );
			$message = preg_replace( '|&amp;|', '&', $message );
			$mailtext = strip_tags($message);
			return wp_mail( $recipients, $subject, $mailtext );
		}
	}
}

if ( ! function_exists( 'wpjobster_isValidEmail' ) ) {
	function wpjobster_isValidEmail( $email ) {
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email);
	}
}

if ( ! function_exists( 'filterEmails' ) ) {
	function filterEmails($text, $replace = true) {
		preg_match_all('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+)/', $text, $matches, PREG_SET_ORDER);

		$matchesCount = count($matches);
		$status = 0;
		for ($i = 0; $i < $matchesCount; $i++) {
			if (filter_var($matches[$i][0], FILTER_VALIDATE_EMAIL)) {
				if ($replace) {
					$text = str_replace($matches[$i][0], '*****', $text);
				}
				$status = 1;
			}
		}
		return array($text, $status);
	}
}

if ( ! function_exists( 'filterPhoneNumbers' ) ) {
	function filterPhoneNumbers($text, $replace = true) {
		preg_match_all('/\S*((123)([.\/() -]|[0-9]){2,25})\S*/', $text, $matches, PREG_SET_ORDER);

		$matchesCount = count($matches);
		$status = 0;
		for ($i = 0; $i < $matchesCount; $i++) {
			$m = $matches[$i][0];
			if (strpos($m,'://') !== false) { continue; }
			$status = 1;
			if (!$replace) { continue; }
			$new = preg_replace('/((123)([.\/() -]|[0-9]){2,25})/', '*****', $m);

			$text = str_replace($m, $new, $text);


		}
		return array($text, $status);
	}
}

if ( ! function_exists('filterPhoneNumbersAdvanced') ) {
	function filterPhoneNumbersAdvanced($text, $replace = true) {
		$wpjobster_blacklisted_prefixes_pm = get_option('wpjobster_blacklisted_prefixes_pm');

		$blacklisted_prefixes_pm = str_replace("\r", "\n", $wpjobster_blacklisted_prefixes_pm);
		$blacklisted_prefixes_pm = explode("\n", $blacklisted_prefixes_pm);
		$blacklisted_prefixes_clean = array();

		foreach ($blacklisted_prefixes_pm as $prefix) {
			$prefix = trim($prefix);
			if ($prefix && ctype_digit($prefix)) {
				array_push($blacklisted_prefixes_clean, $prefix);
			}
		}

		if (!$blacklisted_prefixes_clean) {
			$regex_match_str = '/\S*(([0-9])([.\/() -]|[0-9]){4,12}([0-9]))\S*/';
			$regex_replace_str = '/(([0-9])([.\/() -]|[0-9]){4,12}([0-9]))/';

		} else {
			$prefixes_str = implode("|", $blacklisted_prefixes_clean);
			$regex_match_str = '/\S*(('.$prefixes_str.')([.\/() -]|[0-9]){2,9}([0-9]))\S*/';
			$regex_replace_str = '/(('.$prefixes_str.')([.\/() -]|[0-9]){2,9}([0-9]))/';

		}

		preg_match_all($regex_match_str, $text, $matches, PREG_SET_ORDER);

		$matchesCount = count($matches);
		$status = 0;
		for ($i = 0; $i < $matchesCount; $i++) {
			$m = $matches[$i][0];
			if (strpos($m,'://') !== false) { continue; }
			$status = 1;
			if (!$replace) { continue; }
			$new = preg_replace($regex_replace_str, '*****', $m);

			$text = str_replace($m, $new, $text);
		}

		return array($text, $status);
	}
}

//E-MAIL AND PHONE VERIFICATION
add_action('wpjobster_shopping_after_title', 'email_phone_verification');
add_action('wpjobster_my_account_after_title', 'email_phone_verification');
if ( ! function_exists( 'email_phone_verification' ) ) {
	function email_phone_verification($uid){
		if ( get_option( 'wpjobster_verify_email' ) != 'no' ) {
			if ( get_user_meta( $uid, 'uz_email_verification', true ) != 1 ) {
				$email_verification_url = get_bloginfo( 'url' ) . "/?jb_action=verify_email&resend=true"; ?>

				<div class="ui segment wrapper-verify">
					<div class="sixteen wide column">
						<div class="my-account-verify-email">
							<?php echo sprintf( __( "Please verify your email. If you haven't received the verification link please <a href=\"%s\">click here</a> to resend it.", "wpjobster" ), $email_verification_url ); ?>
						</div>
					</div>
				</div>

			<?php }
		}

		if( get_option('wpjobster_verify_phone_numbers') == 'yes'){
			if (get_user_meta($uid, 'cell_number', true) == ''
			) {
				$edit_profile_url = get_permalink(get_option('wpjobster_my_account_personal_info_page_id'));
				?>
				<div class="ui segment wrapper-verify">
					<div class="sixteen wide column center red-cnt">
						<?php echo sprintf(__("Phone verification is required, please %sedit your profile%s and fill your phone number.", "wpjobster"), "<a href='$edit_profile_url'>","</a>"); ?>
					</div>
				</div>
				<?php
			} elseif (get_user_meta($uid, 'cell_number', true) != '' && get_user_meta($uid, 'uz_phone_verification', true) != 1) {
				$phone_verification_url = get_bloginfo('url') . "/?jb_action=verify_phone&resend=true";
				?>
				<div class="ui segment wrapper-verify">
					<div class="sixteen wide column center red-cnt">
						<?php echo sprintf(__("Please verify your phone number. Please <a href=\"%s\">click here</a> to receive a verification code.", "wpjobster"), $phone_verification_url); ?>
					</div>
				</div>
			<?php
			}
		}
	}
}

//FORMAT PHONE NUMBER
if ( ! function_exists( 'wpjobster_phone_number_format' ) ) {
	function wpjobster_phone_number_format($uid, $phone_number){
		$country_code = get_user_meta( $uid, 'country_code', true );

		$phone_prefixes = get_all_phone_prefixes();

		if($phone_prefixes){
			foreach ($phone_prefixes as $key => $value) {
				if($country_code == $key){
					$user_code = $value;
				}
			}
		}

		if($phone_number){
			if(substr( $phone_number, 0, 2 ) === "00"){
				$phone_number = substr_replace($phone_number,'+',0,2);
			}
			if(substr( $phone_number, 0, 2 ) != "00" && substr( $phone_number, 0, 1 ) === "0"){
				if(isset($user_code))
					$phone_number = substr_replace($phone_number,'+'.$user_code,0,1);
			}
		}

		update_user_meta($uid, 'cell_number', $phone_number);
		return $phone_number;
	}
}

if ( ! function_exists( 'wpjobster_noajax_email_verification' ) ) {
	function wpjobster_noajax_email_verification($user_login, $user) {
		if (get_user_meta($user->ID, 'uz_email_verification', true) == -1) {
			update_user_meta($user->ID, 'uz_email_verification', 1);
		}
	}
}
add_action('wp_login', 'wpjobster_noajax_email_verification', 10, 2);

if ( ! function_exists( 'wpjobster_email_verification_init' ) ) {
	function wpjobster_email_verification_init($uid) {
		$key = hash("sha256", rand(0,1000000), false);
		update_user_meta( $uid, 'uz_email_verification_key', $key );
		update_user_meta( $uid, 'uz_email_verification', 0 );

		return $key;
	}
}

if ( ! function_exists( 'wpjobster_send_email_when_buyer_closes_the_job' ) ) {
	function wpjobster_send_email_when_buyer_closes_the_job($oid, $pid, $receiver, $sender = ''){
		$enable = get_option('wpjobster_order_closed_by_seller_email_enable');
		$subject = get_option('wpjobster_order_closed_by_seller_email_subject');
		$message = get_option('wpjobster_order_closed_by_seller_email_message');

		if ($enable != "no"):
			$user = get_userdata($post->post_author);
			$site_login_url = wpjobster_login_url();
			$site_name = get_bloginfo('name');
			$account_url = get_permalink(get_option('wpjobster_my_account_page_id'));
			$receiver = get_userdata($receiver);
			$sender = get_userdata($sender);
			$cnv_lnk = get_bloginfo('url') . "/?jb_action=chat_box&oid=" . $oid;
			$post = get_post($pid);
			$job_name = wpjobster_wrap_the_title($post->post_title, $pid);
			$job_link = get_permalink($pid);

			global $wpdb; $prefix = $wpdb->prefix;
			$s = "SELECT date_made from ".$prefix."job_orders WHERE id=".$oid;
			$date_made = $wpdb->get_var($s);
			$uz_order = wpjobster_camouflage_order_id($oid, $date_made);

			$find = array(
				'##receiver_username##',
				'##sender_username##',
				'##conversation_page_link##',
				'##site_login_url##',
				'##your_site_name##',
				'##your_site_url##',
				'##my_account_url##',
				'##job_link##',
				'##job_name##',
				'##transaction_number##'
			);

			$replace = array(
				$receiver->user_login,
				$sender->user_login,
				$cnv_lnk,
				$site_login_url,
				$site_name,
				get_bloginfo('url'),
				$account_url,
				$job_link,
				$job_name,
				$uz_order
			);

			$message = wpjobster_replace_stuff_for_me($find, $replace, $message);
			$subject = wpjobster_replace_stuff_for_me($find, $replace, $subject);

			$email = $receiver->user_email;
			wpjobster_send_email($email, stripslashes($subject), stripslashes($message));
		endif;
	}
}

if ( ! function_exists( 'get_wpjobster_sms_gateways' ) ) {
	function get_wpjobster_sms_gateways(){
		$wpjobster_sms_gateways = array(
			//"100"=>array("label"=>__("Twilio 1",'wpjobster'),"show_settigs_form"=>"show_twilio_form","unique_id"=>"twilio1"),
		);
		$wpjobster_sms_gateways = apply_filters( 'wpjobster_sms_gateways', $wpjobster_sms_gateways );
		ksort( $wpjobster_sms_gateways );
		return $wpjobster_sms_gateways;
	}
}
