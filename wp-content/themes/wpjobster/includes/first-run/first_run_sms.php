<?php
$opt_sms = get_option('wpjobster_first_run_sms');

if(empty($opt_sms)) {

	update_option('wpjobster_first_run_sms', 'done');

	$sms_user_new_en_message = get_option('uz_sms_user_new_en_message');
	if(empty($sms_user_new_en_message)) {
		update_option('uz_sms_user_new_en_message',

					'Hello ##username##,'.PHP_EOL.PHP_EOL.

					'Your username: ##receiver_username##'.PHP_EOL.PHP_EOL.

					'Link to verify your email address: ##email_verification##');
	}

	$uz_sms_user_noajax_new_en_message = get_option('uz_sms_user_noajax_new_en_message');
	if(empty($uz_sms_user_noajax_new_en_message)) {
		update_option('uz_sms_user_noajax_new_en_message',

					'Hello ##username##,'.PHP_EOL.PHP_EOL.

					'Your username: ##receiver_username##'.PHP_EOL.
					'Your password: ##password##'.PHP_EOL.PHP_EOL.

					'##your_site_name## Team');
	}

	$uz_sms_user_admin_new_en_message = get_option('uz_sms_user_admin_new_en_message');
	if(empty($uz_sms_user_admin_new_en_message)) {
		update_option('uz_sms_user_admin_new_en_message',

					'Hello admin,'.PHP_EOL.PHP_EOL.

					'A new user just registered on your site.'.PHP_EOL.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Email: ##user_email##');

	}

	$uz_sms_user_verification_en_message = get_option('uz_sms_user_verification_en_message');
	if(empty($uz_sms_user_verification_en_message)) {
		update_option('uz_sms_user_verification_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Click on following link in order to verify your email address: ##email_verification##');

	}

	$uz_sms_job_new_en_message = get_option('uz_sms_job_new_en_message');
	if(empty($uz_sms_job_new_en_message)) {
		update_option('uz_sms_job_new_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your job ##job_name## has been posted on website.'.PHP_EOL.
					'The job needs to be approved by the admin before it goes live.');

	}

	$uz_sms_job_acc_en_message = get_option('uz_sms_job_acc_en_message');
	if(empty($uz_sms_job_acc_en_message)) {
		update_option('uz_sms_job_acc_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your job ##job_name## was approved by the administrator.'.PHP_EOL.
					'You can see it here: ##job_link##');

	}

	$uz_sms_job_decl_en_message = get_option('uz_sms_job_decl_en_message');
	if(empty($uz_sms_job_decl_en_message)) {
		update_option('uz_sms_job_decl_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Our team has checked your job ##job_name##, and found that you need to make some changes.'.PHP_EOL.PHP_EOL.

					'For more info please login to your account');

	}

	$uz_sms_job_admin_new_en_message = get_option('uz_sms_job_admin_new_en_message');
	if(empty($uz_sms_job_admin_new_en_message)) {
		update_option('uz_sms_job_admin_new_en_message',

					'Job name: ##job_name##'.PHP_EOL.
					'The job is not automatically approved so you have to manually approve the job before it appears on your website.');

	}

	$uz_sms_job_admin_acc_en_message = get_option('uz_sms_job_admin_acc_en_message');
	if(empty($uz_sms_job_admin_acc_en_message)) {
		update_option('uz_sms_job_admin_acc_en_message',

					'The user ##username## has posted a new job on your website.'.PHP_EOL.
					'Job name: <b>##job_name##</b> '.PHP_EOL.

					'The job was automatically approved on your website.');

	}

	$uz_sms_withdraw_req_en_message = get_option('uz_sms_withdraw_req_en_message');
	if(empty($uz_sms_withdraw_req_en_message)) {
		update_option('uz_sms_withdraw_req_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.

					'Amt: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##');

	}

	$uz_sms_withdraw_compl_en_message = get_option('uz_sms_withdraw_compl_en_message');
	if(empty($uz_sms_withdraw_compl_en_message)) {
		update_option('uz_sms_withdraw_compl_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your withdraw request was processed.'.PHP_EOL.PHP_EOL.

					'Withdraw details:'.PHP_EOL.
					'Amount: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##');
	}

	$uz_sms_withdraw_decl_en_message = get_option('uz_sms_withdraw_decl_en_message');
	if(empty($uz_sms_withdraw_decl_en_message)) {
		update_option('uz_sms_withdraw_decl_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Unfortunately, your withdrawal request has been denied.'.PHP_EOL.PHP_EOL.

					'Withdraw details:'.PHP_EOL.
					'Amount: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##');
	}

	$uz_sms_level_down_en_message = get_option('uz_sms_level_down_en_message');
	if(empty($uz_sms_level_down_en_message)) {
		update_option('uz_sms_level_down_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your level was downgraded to ##current_level##.'.PHP_EOL.
					'Your level will be upgraded again based on your sales and ratings.');
	}

	$uz_sms_level_up_en_message = get_option('uz_sms_level_up_en_message');
	if(empty($uz_sms_level_up_en_message)) {
		update_option('uz_sms_level_up_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Congratulations! Your level was upgraded to ##current_level##.'.PHP_EOL.
					'Keep up the good work!');
	}

	$uz_sms_balance_down_en_message = get_option('uz_sms_balance_down_en_message');
	if(empty($uz_sms_balance_down_en_message)) {
		update_option('uz_sms_balance_down_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was decreased by admin with ##amount_updated##.'.PHP_EOL.
					'You need to top up your account'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');
	}

	$uz_sms_balance_up_en_message = get_option('uz_sms_balance_up_en_message');
	if(empty($uz_sms_balance_up_en_message)) {
		update_option('uz_sms_balance_up_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was increased by admin with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');
	}

	$uz_sms_balance_up_paypal_en_message = get_option('uz_sms_balance_up_paypal_en_message');
	if(empty($uz_sms_balance_up_paypal_en_message)) {
		update_option('uz_sms_balance_up_paypal_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was increased by payment via paypal with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');
	}

	$uz_sms_balance_admin_down_en_message = get_option('uz_sms_balance_admin_down_en_message');
	if(empty($uz_sms_balance_admin_down_en_message)) {
		update_option('uz_sms_balance_admin_down_en_message',

					'Hello admin,'.PHP_EOL.PHP_EOL.

					'You or another admin just decreased the in-site balance for the user: ##username## with ##amount_updated##.'.PHP_EOL.

					'Amount Decreased: ##amount_updated##');

	}

	$uz_sms_balance_admin_up_en_message = get_option('uz_sms_balance_admin_up_en_message');
	if(empty($uz_sms_balance_admin_up_en_message)) {
		update_option('uz_sms_balance_admin_up_en_message',

					'Hello admin,'.PHP_EOL.PHP_EOL.

					'You or another admin just increased the in-site balance for the user: ##username## with ##amount_updated##.'.PHP_EOL.

					'Amount Increased: ##amount_updated##');

	}

	$uz_sms_balance_admin_up_paypal_en_message = get_option('uz_sms_balance_admin_up_paypal_en_message');
	if(empty($uz_sms_balance_admin_up_paypal_en_message)) {
		update_option('uz_sms_balance_admin_up_paypal_en_message',

					'Hello admin,'.PHP_EOL.PHP_EOL.

					'User just increased the in-site balance via paypal. User: ##username## with ##amount_updated##.'.PHP_EOL.

					'Amount Increased: ##amount_updated##');
	}

	$uz_sms_new_message_en_message = get_option('uz_sms_new_message_en_message');
	if(empty($uz_sms_new_message_en_message)) {
		update_option('uz_sms_new_message_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new message from ##sender_username##.'.PHP_EOL.
					'To read the message, follow this link: ##private_message_link##');
	}

	$uz_sms_new_request_en_message = get_option('uz_sms_new_request_en_message');
	if(empty($uz_sms_new_request_en_message)) {
		update_option('uz_sms_new_request_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new request from ##sender_username##.'.PHP_EOL.
					'Follow this link in order to answer: ##private_message_link##');
	}

	$uz_sms_new_offer_en_message = get_option('uz_sms_new_offer_en_message');
	if(empty($uz_sms_new_offer_en_message)) {
		update_option('uz_sms_new_offer_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new offer from ##sender_username##.'.PHP_EOL.
					'Follow this link in order to answer: ##private_message_link##');
	}

	$uz_sms_offer_acc_buyer_en_message = get_option('uz_sms_offer_acc_buyer_en_message');
	if(empty($uz_sms_offer_acc_buyer_en_message)) {
		update_option('uz_sms_offer_acc_buyer_en_message',

					'You have accepted the offer from ##sender_username##.'.PHP_EOL.
					'In order to get in touch with the seller, please visit the following link: ##transaction_page_link##');
	}

	$uz_sms_offer_acc_seller_en_message = get_option('uz_sms_offer_acc_seller_en_message');
	if(empty($uz_sms_offer_acc_seller_en_message)) {
		update_option('uz_sms_offer_acc_seller_en_message',

					'Your offer was accepted by ##sender_username##.'.PHP_EOL.
					'In order to get in touch with the buyer, please visit the following link: ##transaction_page_link##');
	}

	$uz_sms_offer_decl_en_message = get_option('uz_sms_offer_decl_en_message');
	if(empty($uz_sms_offer_decl_en_message)) {
		update_option('uz_sms_offer_decl_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Unfortunately, ##sender_username## declined your offer.'.PHP_EOL.
					'Contact him for more details using link: ##private_message_link##');
	}

	$uz_sms_offer_withdr_en_message = get_option('uz_sms_offer_withdr_en_message');
	if(empty($uz_sms_offer_withdr_en_message)) {
		update_option('uz_sms_offer_withdr_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has withdrawn the offer.'.PHP_EOL.
					'Contact him for more details using link: ##private_message_link##');
	}

	$uz_sms_offer_exp_en_message = get_option('uz_sms_offer_exp_en_message');
	if(empty($uz_sms_offer_exp_en_message)) {
		update_option('uz_sms_offer_exp_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The offer from ##sender_username## has expired.'.PHP_EOL.
					'Contact him for more details using link: ##private_message_link##');
	}

	$uz_sms_purchased_buyer_en_message = get_option('uz_sms_purchased_buyer_en_message');
	if(empty($uz_sms_purchased_buyer_en_message)) {
		update_option('uz_sms_purchased_buyer_en_message',

					'You have just bought this job: ##job_name##'.PHP_EOL.
					'In order to get in touch with the seller, please visit the following link: ##transaction_page_link##');
	}

	$uz_sms_purchased_seller_en_message = get_option('uz_sms_purchased_seller_en_message');
	if(empty($uz_sms_purchased_seller_en_message)) {
		update_option('uz_sms_purchased_seller_en_message',

					'You have just sold this job: ##job_name##'.PHP_EOL.
					'In order to get in touch with the buyer, please visit the following link: ##transaction_page_link##');
	}

	$uz_sms_order_message_en_message = get_option('uz_sms_order_message_en_message');
	if(empty($uz_sms_order_message_en_message)) {
		update_option('uz_sms_order_message_en_message',

					'You have a new msg from ##sender_username## related to transaction ##transaction_number##.'.PHP_EOL.
					'Click here to read and respond: ##transaction_page_link##');
	}

	$uz_sms_cancel_buyer_en_message = get_option('uz_sms_cancel_buyer_en_message');
	if(empty($uz_sms_cancel_buyer_en_message)) {
		update_option('uz_sms_cancel_buyer_en_message',

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link to accept or deny request: ##transaction_page_link##');
	}

	$uz_sms_cancel_seller_en_message = get_option('uz_sms_cancel_seller_en_message');
	if(empty($uz_sms_cancel_seller_en_message)) {
		update_option('uz_sms_cancel_seller_en_message',

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link to accept or deny request: ##transaction_page_link##');
	}

	$uz_sms_cancel_acc_buyer_en_message = get_option('uz_sms_cancel_acc_buyer_en_message');
	if(empty($uz_sms_cancel_acc_buyer_en_message)) {
		update_option('uz_sms_cancel_acc_buyer_en_message',

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_acc_seller_en_message = get_option('uz_sms_cancel_acc_seller_en_message');
	if(empty($uz_sms_cancel_acc_seller_en_message)) {
		update_option('uz_sms_cancel_acc_seller_en_message',

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_decl_buyer_en_message = get_option('uz_sms_cancel_decl_buyer_en_message');
	if(empty($uz_sms_cancel_decl_buyer_en_message)) {
		update_option('uz_sms_cancel_decl_buyer_en_message',

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_decl_seller_en_message = get_option('uz_sms_cancel_decl_seller_en_message');
	if(empty($uz_sms_cancel_decl_seller_en_message)) {
		update_option('uz_sms_cancel_decl_seller_en_message',

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_abort_buyer_en_message = get_option('uz_sms_cancel_abort_buyer_en_message');
	if(empty($uz_sms_cancel_abort_buyer_en_message)) {
		update_option('uz_sms_cancel_abort_buyer_en_message',

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_abort_seller_en_message = get_option('uz_sms_cancel_abort_seller_en_message');
	if(empty($uz_sms_cancel_abort_seller_en_message)) {
		update_option('uz_sms_cancel_abort_seller_en_message',

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_admin_en_message = get_option('uz_sms_cancel_admin_en_message');
	if(empty($uz_sms_cancel_admin_en_message)) {
		update_option('uz_sms_cancel_admin_en_message',

					'Transaction ##transaction_number## was cancelled by admin. The money are refunded to the buyer.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_mod_buyer_en_message = get_option('uz_sms_mod_buyer_en_message');
	if(empty($uz_sms_mod_buyer_en_message)) {
		update_option('uz_sms_mod_buyer_en_message',

					'##sender_username## has requested a modification for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_order_delivered_en_message = get_option('uz_sms_order_delivered_en_message');
	if(empty($uz_sms_order_delivered_en_message)) {
		update_option('uz_sms_order_delivered_en_message',

					'The seller has delivered the order. You can check and download the files here: ##transaction_page_link##'.PHP_EOL.
					'Mark the job as completed if ok');
	}

	$uz_sms_order_complete_en_message = get_option('uz_sms_order_complete_en_message');
	if(empty($uz_sms_order_complete_en_message)) {
		update_option('uz_sms_order_complete_en_message',

					'The buyer has marked the order as completed.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##');
	}

	$uz_sms_new_feedback_en_message = get_option('uz_sms_new_feedback_en_message');
	if(empty($uz_sms_new_feedback_en_message)) {
		update_option('uz_sms_new_feedback_en_message',

					'##sender_username## just sent you the feedback for transaction ##transaction_number##'.PHP_EOL.
					'You can find the review here: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_buyer_en_message = get_option('uz_sms_cancel_offer_buyer_en_message');
	if(empty($uz_sms_cancel_offer_buyer_en_message)) {
		update_option('uz_sms_cancel_offer_buyer_en_message',

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link to accept or deny request: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_seller_en_message = get_option('uz_sms_cancel_offer_seller_en_message');
	if(empty($uz_sms_cancel_offer_seller_en_message)) {
		update_option('uz_sms_cancel_offer_seller_en_message',

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link to accept or deny request: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_acc_buyer_en_message = get_option('uz_sms_cancel_offer_acc_buyer_en_message');
	if(empty($uz_sms_cancel_offer_acc_buyer_en_message)) {
		update_option('uz_sms_cancel_offer_acc_buyer_en_message',

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_acc_seller_en_message = get_option('uz_sms_cancel_offer_acc_seller_en_message');
	if(empty($uz_sms_cancel_offer_acc_seller_en_message)) {
		update_option('uz_sms_cancel_offer_acc_seller_en_message',

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_decl_buyer_en_message = get_option('uz_sms_cancel_offer_decl_buyer_en_message');
	if(empty($uz_sms_cancel_offer_decl_buyer_en_message)) {
		update_option('uz_sms_cancel_offer_decl_buyer_en_message',

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_decl_seller_en_message = get_option('uz_sms_cancel_offer_decl_seller_en_message');
	if(empty($uz_sms_cancel_offer_decl_seller_en_message)) {
		update_option('uz_sms_cancel_offer_decl_seller_en_message',

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_abort_buyer_en_message = get_option('uz_sms_cancel_offer_abort_buyer_en_message');
	if(empty($uz_sms_cancel_offer_abort_buyer_en_message)) {
		update_option('uz_sms_cancel_offer_abort_buyer_en_message',

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_abort_seller_en_message = get_option('uz_sms_cancel_offer_abort_seller_en_message');
	if(empty($uz_sms_cancel_offer_abort_seller_en_message)) {
		update_option('uz_sms_cancel_offer_abort_seller_en_message',

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_cancel_offer_admin_en_message = get_option('uz_sms_cancel_offer_admin_en_message');
	if(empty($uz_sms_cancel_offer_admin_en_message)) {
		update_option('uz_sms_cancel_offer_admin_en_message',

					'Transaction ##transaction_number## was cancelled by admin. The money are refunded to the buyer.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_mod_offer_buyer_en_message = get_option('uz_sms_mod_offer_buyer_en_message');
	if(empty($uz_sms_mod_offer_buyer_en_message)) {
		update_option('uz_sms_mod_offer_buyer_en_message',

					'##sender_username## has requested a modification for transaction ##transaction_number##.'.PHP_EOL.
					'Link for more info: ##transaction_page_link##');
	}

	$uz_sms_order_offer_delivered_en_message = get_option('uz_sms_order_offer_delivered_en_message');
	if(empty($uz_sms_order_offer_delivered_en_message)) {
		update_option('uz_sms_order_offer_delivered_en_message',

					'The seller has delivered the order. You can check and download the files here: ##transaction_page_link##'.PHP_EOL.
					'Mark the job as completed, If ok');
	}

	$uz_sms_order_offer_complete_en_message = get_option('uz_sms_order_offer_complete_en_message');
	if(empty($uz_sms_order_offer_complete_en_message)) {
		update_option('uz_sms_order_offer_complete_en_message',

					'The buyer has marked the order as completed.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##');
	}

	$uz_sms_new_offer_feedback_en_message = get_option('uz_sms_new_offer_feedback_en_message');
	if(empty($uz_sms_new_offer_feedback_en_message)) {
		update_option('uz_sms_new_offer_feedback_en_message',

					'##sender_username## just sent you the feedback for transaction ##transaction_number##'.PHP_EOL.
					'You can find the review here: ##transaction_page_link##');
	}

	$uz_sms_featured_new_en_message = get_option('uz_sms_featured_new_en_message');
	if(empty($uz_sms_featured_new_en_message)) {
		update_option('uz_sms_featured_new_en_message',

					'Your job ##job_link## ##job_name## has been featured on the website.'.PHP_EOL.
					'Featured info: '.PHP_EOL.
										'##all_featured_info##');
	}

	$uz_sms_balance_negative_en_message = get_option('uz_sms_balance_negative_en_message');
	if(empty($uz_sms_balance_negative_en_message)) {
		update_option('uz_sms_balance_negative_en_message',

					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was negative with ##amount_updated##.');
	}

}


$opt_sms2 = get_option('wpjobster_first_run_sms2');
if(empty($opt_sms2)) {

	update_option( 'wpjobster_first_run_sms2', 'done' );

	// sms sent to buyer when new custom extra was added
	$uz_sms_new_custom_extra_en_message = get_option('uz_sms_new_custom_extra_en_message');
	if(empty($uz_sms_new_custom_extra_en_message)) {
		update_option('uz_sms_new_custom_extra_en_message',

			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'You have received a new custom extra from ##sender_username##.'.PHP_EOL.
			'Follow this link in order to see it: ##transaction_page_link##');
	}

	// sms sent to buyer when a custom extra was cancelled by seller
	$uz_sms_cancel_custom_extra_en_message = get_option('uz_sms_cancel_custom_extra_en_message');
	if(empty($uz_sms_cancel_custom_extra_en_message)) {
		update_option('uz_sms_cancel_custom_extra_en_message',

			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'A custom extra from ##sender_username## was cancelled.'.PHP_EOL.
			'Follow this link in order to see it: ##transaction_page_link##');
	}

	// sms sent to seller when a custom extra was declined by buyer
	$uz_sms_decline_custom_extra_en_message = get_option('uz_sms_decline_custom_extra_en_message');
	if(empty($uz_sms_decline_custom_extra_en_message)) {
		update_option('uz_sms_decline_custom_extra_en_message',

			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'A custom extra was declined by ##sender_username##.'.PHP_EOL.
			'Follow this link in order to see it: ##transaction_page_link##');
	}

	// sms sent to buyer of a custom extra
	$uz_sms_custom_extra_paid_new_en_message = get_option('uz_sms_custom_extra_paid_new_en_message');
	if(empty($uz_sms_custom_extra_paid_new_en_message)) {
		update_option('uz_sms_custom_extra_paid_new_en_message',

			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'You just paid a custom extra.'.PHP_EOL.
			'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

			'Thank you,'.PHP_EOL.
			'##your_site_name## Team');
	}

	// sms sent to seller when a custom extra is bought
	$uz_sms_custom_extra_paid_new_seller_en_message = get_option('uz_sms_custom_extra_paid_new_seller_en_message');
	if(empty($uz_sms_custom_extra_paid_new_seller_en_message)) {
		update_option('uz_sms_custom_extra_paid_new_seller_en_message',

			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'A custom extra was paid by ##sender_username##.'.PHP_EOL.
			'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

			'Thank you,'.PHP_EOL.
			'##your_site_name## Team');
	}

	// sms sent to admin when a custom extra is bought
	$uz_sms_custom_extra_paid_admin_new_en_message = get_option('uz_sms_custom_extra_paid_admin_new_en_message');
	if(empty($uz_sms_custom_extra_paid_admin_new_en_message)) {
		update_option('uz_sms_custom_extra_paid_admin_new_en_message',

			'Hello admin,'.PHP_EOL.PHP_EOL.

			'A custom extra was paid on your site.'.PHP_EOL.PHP_EOL.

			'Order: ##transaction_page_link##');
	}

	// sms sent to buyer when a BT custom extra is cancelled by admin
	$uz_sms_custom_extra_cancelled_by_admin_en_message = get_option('uz_sms_custom_extra_cancelled_by_admin_en_message');
	if(empty($uz_sms_custom_extra_cancelled_by_admin_en_message)) {
		update_option('uz_sms_custom_extra_cancelled_by_admin_en_message',
			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'A custom extra payment (with Bank Transfer) was cancelled by admin.'.PHP_EOL.
			'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

			'Thank you,'.PHP_EOL.
			'##your_site_name## Team');
	}

	// sms sent to seller when a BT custom extra is cancelled by admin
	$uz_sms_custom_extra_cancelled_by_admin_seller_en_message = get_option('uz_sms_custom_extra_cancelled_by_admin_seller_en_message');
	if(empty($uz_sms_custom_extra_cancelled_by_admin_seller_en_message)) {
		update_option('uz_sms_custom_extra_cancelled_by_admin_seller_en_message',
			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'A custom extra payment (with Bank Transfer) was cancelled by admin.'.PHP_EOL.
			'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

			'Thank you,'.PHP_EOL.
			'##your_site_name## Team');
	}

	// sms sent to buyer when they chose to pay a custom extra using bank transfer payment menthod
	$uz_sms_send_bankdetails_to_custom_extra_buyer_en_message = get_option('uz_sms_send_bankdetails_to_custom_extra_buyer_en_message');
	if(empty($uz_sms_send_bankdetails_to_custom_extra_buyer_en_message)) {
		update_option('uz_sms_send_bankdetails_to_custom_extra_buyer_en_message',
			'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

			'We have received the order for custom extra. '.PHP_EOL.PHP_EOL.
			'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

			'In order to further process your order, please make the wire transfer using the following bank details:'.PHP_EOL.PHP_EOL.
			'##bank_details##'.PHP_EOL.PHP_EOL.

			'Thank you,'.PHP_EOL.
			'##your_site_name## Team');
	}

}
?>
