<?php

$opt = get_option('wpjobster_first_run_emails');
if(empty($opt)) {

	update_option('wpjobster_first_run_emails', 'done');

	update_option('uz_email_user_new_en_subject', 'Welcome to ##your_site_name##');
	update_option('uz_email_user_new_en_message',
					'Hello ##username##,'.PHP_EOL.PHP_EOL.

					'Welcome to our website.'.PHP_EOL.
					'Your username: ##receiver_username##'.PHP_EOL.PHP_EOL.

					'Please follow this link to verify your email address: ##email_verification##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_user_noajax_new_en_subject', 'Welcome to ##your_site_name##');
	update_option('uz_email_user_noajax_new_en_message',
					'Hello ##username##,'.PHP_EOL.PHP_EOL.

					'Welcome to our website.'.PHP_EOL.
					'Your username: ##receiver_username##'.PHP_EOL.
					'Your password: ##password##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_user_admin_new_en_subject', 'New user registration on ##your_site_name##');
	update_option('uz_email_user_admin_new_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'A new user just registered on your site.'.PHP_EOL.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Email: ##user_email##');


	update_option('uz_email_user_verification_en_subject', 'Please verify your email address');
	update_option('uz_email_user_verification_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Please click the following link in order to verify your email address: ##email_verification##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_job_new_en_subject', 'New job: ##job_name##');
	update_option('uz_email_job_new_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your job <b>##job_name##</b> has been posted on the website. However it is not live yet.'.PHP_EOL.
					'The job needs to be approved by the admin before it goes live. '.PHP_EOL.
					'You will be notified by email when the job is active and published.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	update_option('uz_email_job_acc_en_subject', 'Your new job was published');
	update_option('uz_email_job_acc_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your job <b>##job_name##</b> was approved by the administrator.'.PHP_EOL.
					'You can see it here: ##job_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	update_option('uz_email_job_decl_en_subject', 'Your new job was not published yet');
	update_option('uz_email_job_decl_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Our team has checked your job <b>##job_name##</b>, and found that you need to make some changes.'.PHP_EOL.PHP_EOL.

					'For more information on what changes need to be done, please login to your account and check your account page: ##my_account_url##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_job_admin_new_en_subject', 'New job: ##job_name##');
	update_option('uz_email_job_admin_new_en_message',
					'The user ##username## has posted a new job on your website.'.PHP_EOL.
					'Job name: <b>##job_name##</b> '.PHP_EOL.
					'The job is not automatically approved so you have to manually approve the job before it appears on your website.'.PHP_EOL.PHP_EOL.

					'Go here: ##your_site_url##/wp-admin/edit.php?post_type=job');


	update_option('uz_email_job_admin_acc_en_subject', 'New job: ##job_name##');
	update_option('uz_email_job_admin_acc_en_message',
					'The user ##username## has posted a new job on your website.'.PHP_EOL.
					'Job name: <b>##job_name##</b> '.PHP_EOL.

					'The job was automatically approved on your website.'.PHP_EOL.PHP_EOL.

					'View the job here: ##job_link##');


	update_option('uz_email_withdraw_req_en_subject', 'You have requested a withdrawal');
	update_option('uz_email_withdraw_req_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'We have received your request. It will be processed within 2 to 3 working days.'.PHP_EOL.PHP_EOL.

					'Withdraw details:'.PHP_EOL.
					'Amount: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_withdraw_compl_en_subject', 'Your withdrawal request was processed');
	update_option('uz_email_withdraw_compl_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your withdraw request was processed.'.PHP_EOL.PHP_EOL.

					'Withdraw details:'.PHP_EOL.
					'Amount: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_withdraw_decl_en_subject', 'Your withdrawal request has been rejected');
	update_option('uz_email_withdraw_decl_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Unfortunately, your withdrawal request has been denied.'.PHP_EOL.PHP_EOL.

					'Withdraw details:'.PHP_EOL.
					'Amount: ##amount_withdrawn##'.PHP_EOL.
					'Method: ##withdraw_method##'.PHP_EOL.PHP_EOL.

					'A common reason for rejecting the request is incomplete or insufficient information at our disposal on your account.'.PHP_EOL.
					'Please contact support for more information abou that.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_level_down_en_subject', 'Your level was downgraded');
	update_option('uz_email_level_down_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your level was downgraded to ##current_level##.'.PHP_EOL.
					'Your level will be upgraded again based on your sales and ratings.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_level_up_en_subject', 'Your level was upgraded');
	update_option('uz_email_level_up_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Congratulations! Your level was upgraded to ##current_level##.'.PHP_EOL.
					'Keep up the good work!'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_new_message_en_subject', 'You have a new private message');
	update_option('uz_email_new_message_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new message from ##sender_username##.'.PHP_EOL.
					'If you want to read the message, please follow this link: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_new_request_en_subject', 'You have a new request');
	update_option('uz_email_new_request_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new request from ##sender_username##.'.PHP_EOL.
					'Please follow this link in order to answer: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_new_offer_en_subject', 'You have a new offer');
	update_option('uz_email_new_offer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have received a new offer from ##sender_username##.'.PHP_EOL.
					'Please follow this link in order to answer: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_offer_acc_buyer_en_subject', 'Your have accepted an offer');
	update_option('uz_email_offer_acc_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have accepted the offer from ##sender_username##.'.PHP_EOL.
					'In order to get in touch with the seller, please visit the following link and provide all the info he needs for the work: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_offer_acc_seller_en_subject', 'Your offer was accepted');
	update_option('uz_email_offer_acc_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your offer was accepted by ##sender_username##.'.PHP_EOL.
					'In order to get in touch with the buyer, please visit the following link and ask for all the info that you need for the work: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_offer_decl_en_subject', 'Your offer was declined');
	update_option('uz_email_offer_decl_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Unfortunately, ##sender_username## declined your offer.'.PHP_EOL.
					'Please contact him for more details using this link: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_offer_withdr_en_subject', 'The offer was withdrawn');
	update_option('uz_email_offer_withdr_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has withdrawn the offer.'.PHP_EOL.
					'Please contact him for more details using this link: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_offer_exp_en_subject', 'The offer has expired');
	update_option('uz_email_offer_exp_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The offer from ##sender_username## has expired.'.PHP_EOL.
					'Please contact him for more details using this link: ##private_message_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_purchased_buyer_en_subject', 'New job purchased: ##job_name##');
	update_option('uz_email_purchased_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have just bought this job: ##job_name##'.PHP_EOL.
					'In order to get in touch with the seller, please visit the following link and provide all the info he needs for the work: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_purchased_seller_en_subject', 'New job sold: ##job_name##');
	update_option('uz_email_purchased_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have just sold this job: ##job_name##'.PHP_EOL.
					'In order to get in touch with the buyer, please visit the following link and ask for all the info that you need for the work: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_order_message_en_subject', 'New message for transaction ##transaction_number##');
	update_option('uz_email_order_message_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have a new message from ##sender_username## related to transaction ##transaction_number##.'.PHP_EOL.
					'Please click here in order to read and respond to it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_buyer_en_subject', 'Mutual cancellation request for ##transaction_number##');
	update_option('uz_email_cancel_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link and accept or deny this request: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_seller_en_subject', 'Mutual cancellation request for ##transaction_number##');
	update_option('uz_email_cancel_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link and accept or deny this request: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_acc_buyer_en_subject', 'The transaction ##transaction_number## was cancelled');
	update_option('uz_email_cancel_acc_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_acc_seller_en_subject', 'The transaction ##transaction_number## was cancelled');
	update_option('uz_email_cancel_acc_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_decl_buyer_en_subject', 'The buyer refused to cancel the transaction');
	update_option('uz_email_cancel_decl_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_decl_seller_en_subject', 'The seller refused to cancel the transaction');
	update_option('uz_email_cancel_decl_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_abort_buyer_en_subject', 'The buyer aborted the cancellation for ##transaction_number##');
	update_option('uz_email_cancel_abort_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_abort_seller_en_subject', 'The seller aborted the cancellation for ##transaction_number##');
	update_option('uz_email_cancel_abort_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_admin_en_subject', 'The transaction ##transaction_number## was cancelled by admin');
	update_option('uz_email_cancel_admin_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Transaction ##transaction_number## was cancelled by admin. The money are refunded to the buyer.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_mod_buyer_en_subject', 'The buyer requested a modification for ##transaction_number##');
	update_option('uz_email_mod_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a modification for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_order_delivered_en_subject', 'Congratulations! Transaction ##transaction_number## was delivered');
	update_option('uz_email_order_delivered_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The seller has delivered the order. You can check and download the files here: ##transaction_page_link##'.PHP_EOL.
					'If everything is ok, please mark the job as completed and the funds will be released to the seller.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_order_complete_en_subject', 'Congratulations! Transaction ##transaction_number## was completed');
	update_option('uz_email_order_complete_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The buyer has marked the order as completed.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_new_feedback_en_subject', 'You have a new feedback for: ##job_name##');
	update_option('uz_email_new_feedback_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## just sent you the feedback for transaction ##transaction_number##'.PHP_EOL.
					'You can find the review here: ##transaction_page_link## or on the job page: ##job_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_buyer_en_subject', 'Mutual cancellation request for ##transaction_number##');
	update_option('uz_email_cancel_offer_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link and accept or deny this request: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_seller_en_subject', 'Mutual cancellation request for ##transaction_number##');
	update_option('uz_email_cancel_offer_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link and accept or deny this request: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_acc_buyer_en_subject', 'The transaction ##transaction_number## was cancelled');
	update_option('uz_email_cancel_offer_acc_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_acc_seller_en_subject', 'The transaction ##transaction_number## was cancelled');
	update_option('uz_email_cancel_offer_acc_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has accepted the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_decl_buyer_en_subject', 'The buyer refused to cancel the transaction');
	update_option('uz_email_cancel_offer_decl_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_decl_seller_en_subject', 'The seller refused to cancel the transaction');
	update_option('uz_email_cancel_offer_decl_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has denied the mutual cancellation for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_abort_buyer_en_subject', 'The buyer aborted the cancellation for ##transaction_number##');
	update_option('uz_email_cancel_offer_abort_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_abort_seller_en_subject', 'The seller aborted the cancellation for ##transaction_number##');
	update_option('uz_email_cancel_offer_abort_seller_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has aborted the mutual cancellation request for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_cancel_offer_admin_en_subject', 'The transaction ##transaction_number## was cancelled by admin');
	update_option('uz_email_cancel_offer_admin_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Transaction ##transaction_number## was cancelled by admin. The money are refunded to the buyer.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_mod_offer_buyer_en_subject', 'The buyer requested a modification for ##transaction_number##');
	update_option('uz_email_mod_offer_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## has requested a modification for transaction ##transaction_number##.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_order_offer_delivered_en_subject', 'Congratulations! Transaction ##transaction_number## was delivered');
	update_option('uz_email_order_offer_delivered_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The seller has delivered the order. You can check and download the files here: ##transaction_page_link##'.PHP_EOL.
					'If everything is ok, please mark the job as completed and the funds will be released to the seller.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_order_offer_complete_en_subject', 'Congratulations! Transaction ##transaction_number## was completed');
	update_option('uz_email_order_offer_complete_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The buyer has marked the order as completed.'.PHP_EOL.
					'Please click this link for more info: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_new_offer_feedback_en_subject', 'You have a new feedback for: ##transaction_number##');
	update_option('uz_email_new_offer_feedback_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'##sender_username## just sent you the feedback for transaction ##transaction_number##'.PHP_EOL.
					'You can find the review here: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

}

$emails224 = get_option('wpjobster_first_run_emails_224');
if(empty($emails224)) {

	update_option('wpjobster_first_run_emails_224', 'done');


	update_option('uz_email_balance_down_en_subject', 'Your in-site balance was decreased');
	update_option('uz_email_balance_down_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was decreased by admin with ##amount_updated##.'.PHP_EOL.
					'You need to top up your account'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_balance_up_en_subject', 'Your in-site balance was increased');
	update_option('uz_email_balance_up_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was increased by admin with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');
	update_option('uz_email_balance_up_paypal_en_subject', 'Your in-site balance was increased');
	update_option('uz_email_balance_up_paypal_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was increased by payment via paypal with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	update_option('uz_email_balance_up_topup_en_subject', 'Your in-site balance was increased');
	update_option('uz_email_balance_up_topup_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was increased by payment via topup with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_balance_admin_down_en_subject', 'Balance decreased for user ##username##');
	update_option('uz_email_balance_admin_down_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'You or another admin just decreased the in-site balance for the user: ##username## with ##amount_updated##.'.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Amount Decreased: ##amount_updated##');


	update_option('uz_email_balance_admin_up_en_subject', 'Balance increased for user ##username##');
	update_option('uz_email_balance_admin_up_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'You or another admin just increased the in-site balance for the user: ##username## with ##amount_updated##.'.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Amount Increased: ##amount_updated##');
	update_option('uz_email_balance_admin_up_paypal_en_subject', 'Balance increased for user ##username## via paypal');
	update_option('uz_email_balance_admin_up_paypal_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'User just increased the in-site balance via paypal. User: ##username## with ##amount_updated##.'.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Amount Increased: ##amount_updated##');
	update_option('uz_email_balance_admin_up_topup_en_subject', 'Balance increased for user ##username## via paypal');
	update_option('uz_email_balance_admin_up_topup_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'User just increased the in-site balance by topup. User: ##username## with ##amount_updated##.'.PHP_EOL.

					'Details:'.PHP_EOL.
					'Username: ##username##'.PHP_EOL.
					'Amount Increased: ##amount_updated##');

	update_option('uz_email_balance_negative_en_subject', 'Your in-site balance was negative');
	update_option('uz_email_balance_negative_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your in-site balance was negative with ##amount_updated##.'.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


}

$opt2 = get_option('wpjobster_first_run_emails2');
if(empty($opt2)) {

	update_option('wpjobster_first_run_emails2', 'done');

		update_option('uz_email_featured_new_en_subject', 'New featured job: ##job_name##');
	update_option('uz_email_featured_new_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your job <a href="##job_link##"><b>##job_name##</b></a> has been featured on the website.'.PHP_EOL.
					'Featured info: '.PHP_EOL.
										'##all_featured_info##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

		update_option('uz_email_featured_admin_new_en_subject', 'New featured job: ##job_name##');
	update_option('uz_email_featured_admin_new_en_message',
					'The user ##username## has featured a new job on your website.'.PHP_EOL.

					'Job name: <a href="##job_link##"><b>##job_name##</b></a>'.PHP_EOL.
					'Featured info: '.PHP_EOL.
										'##all_featured_info##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

}

$opt4 = get_option( 'wpjobster_first_run_emails4' );
if( empty( $opt4 ) ) {

	update_option('wpjobster_first_run_emails4', 'done');

	update_option('uz_email_request_new_en_subject', 'New request: ##request_name##');
	update_option('uz_email_request_new_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your request <b>##request_name##</b> has been posted on the website. However it is not live yet.'.PHP_EOL.
					'The request needs to be approved by the admin before it goes live. '.PHP_EOL.
					'You will be notified by email when the request is active and published.'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_request_acc_en_subject', 'Your new request was published');
	update_option('uz_email_request_acc_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Your request <b>##request_name##</b> was approved by the administrator.'.PHP_EOL.
					'You can see it here: ##request_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_request_decl_en_subject', 'Your new request was not published yet');
	update_option('uz_email_request_decl_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'Our team has checked your request <b>##request_name##</b>, and found that you need to make some changes.'.PHP_EOL.PHP_EOL.

					'For more information on what changes need to be done, please login to your account and check your account page: ##my_account_url##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	update_option('uz_email_request_admin_new_en_subject', 'New request: ##request_name##');
	update_option('uz_email_request_admin_new_en_message',
					'The user ##username## has posted a new request on your website.'.PHP_EOL.
					'Request name: <b>##request_name##</b> '.PHP_EOL.
					'The request is not automatically approved so you have to manually approve the request before it appears on your website.'.PHP_EOL.PHP_EOL.

					'Go here: ##your_site_url##/wp-admin/edit.php?post_type=request');


	update_option('uz_email_request_admin_acc_en_subject', 'New request: ##request_name##');
	update_option('uz_email_request_admin_acc_en_message',
					'The user ##username## has posted a new request on your website.'.PHP_EOL.
					'Request name: <b>##request_name##</b> '.PHP_EOL.

					'The request was automatically approved on your website.'.PHP_EOL.PHP_EOL.

					'View the request here: ##job_link##');

}


$opt5 = get_option( 'wpjobster_first_run_emails5' );
if( empty( $opt5 ) ) {

	update_option('wpjobster_first_run_emails5', 'done');

	// email to be sent to the buyer for JOB PURCHASE with bank trasnfer payment method
	update_option('uz_email_send_bankdetails_to_buyer_en_subject', 'Your payment for the following job is pending: ##job_name##');
	update_option('uz_email_send_bankdetails_to_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'We have received the order for the following job: ##job_name##. '.PHP_EOL.
					'You can check your order status here: ##transaction_page_link##. '.PHP_EOL.PHP_EOL.

					'In order to further process your order, please make the wire transfer using the following bank details:'.PHP_EOL.PHP_EOL.
					'##bank_details##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	// email to be sent to the buyer for TOPUP with bank trasnfer payment method
	update_option('uz_email_send_bankdetails_to_topup_buyer_en_subject', 'Your top up payment is pending');
	update_option('uz_email_send_bankdetails_to_topup_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'In order to further process your topup order, please make the wire transfer using the following bank details:'.PHP_EOL.PHP_EOL.
					'##bank_details##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');


	// email to be sent to the buyer for FEATURE a job with bank trasnfer payment method
	update_option('uz_email_send_bankdetails_to_feature_buyer_en_subject', 'Your job feature payment is pending');
	update_option('uz_email_send_bankdetails_to_feature_buyer_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'We have received the order for featuring the following job: ##job_name##. '.PHP_EOL.PHP_EOL.

					'In order to further process your order, please make the wire transfer using the following bank details:'.PHP_EOL.PHP_EOL.
					'##bank_details##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	// email to be sent to the user who reported a job
	update_option('uz_email_report_job_user_en_subject', 'You have reported a job');
	update_option('uz_email_report_job_user_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'You have reported the following job:'.PHP_EOL.PHP_EOL.

					'##job_name##'.PHP_EOL.
					'##job_link##'.PHP_EOL.PHP_EOL.

					'Report description:'.PHP_EOL.
					'##report_description##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	// email to be sent to the admin a job reported by any admin
	update_option('uz_email_report_job_admin_en_subject', 'An user has reported a job');
	update_option('uz_email_report_job_admin_en_message',
					'Hello admin,'.PHP_EOL.PHP_EOL.

					'##sender_username## has reported the following job:'.PHP_EOL.PHP_EOL.

					'##job_name##'.PHP_EOL.
					'##job_link##'.PHP_EOL.PHP_EOL.

					'Report description:'.PHP_EOL.
					'##report_description##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## team.');

}

$opt6 = get_option( 'wpjobster_first_run_emails6' );
if( empty( $opt6 ) ) {
	update_option('wpjobster_first_run_emails6', 'done');

	// email to be sent to the buyer when admin marks the payment as completed
	update_option('uz_email_payment_completed_by_admin_en_subject', 'Your payment was confirmed');
	update_option('uz_email_payment_completed_by_admin_en_message',
					'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

					'The admin just confirmed your payment.'.PHP_EOL.
					'In order to get in touch with the seller, please visit the following link and provide all the info he needs for the work: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

					'Thank you,'.PHP_EOL.
					'##your_site_name## Team');

	// email to be sent to the admin when he marks the payment as completed
	update_option('uz_email_admin_payment_completed_by_admin_en_subject', 'You have just confirmed a payment');
	update_option('uz_email_admin_payment_completed_by_admin_en_message',

					'Hello admin,'.PHP_EOL.PHP_EOL.

					'You have just confirmed a payment for the following order: ##transaction_page_link##');

}

$opt7 = get_option( 'wpjobster_first_run_emails7' );
if( empty( $opt7 ) ) {
	update_option('wpjobster_first_run_emails7', 'done');

	// email sent to buyer when new custom extra was added
	update_option('uz_email_new_custom_extra_en_subject', 'You have a new custom extra');
	update_option('uz_email_new_custom_extra_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'You have received a new custom extra from ##sender_username##.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to buyer when a custom extra was cancelled by seller
	update_option('uz_email_cancel_custom_extra_en_subject', 'You have a custom extra cancelled');
	update_option('uz_email_cancel_custom_extra_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'A custom extra from ##sender_username## was cancelled.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to seller when a custom extra was declined by buyer
	update_option('uz_email_decline_custom_extra_en_subject', 'You have a custom extra declined');
	update_option('uz_email_decline_custom_extra_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'A custom extra was declined by ##sender_username##.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to buyer of a custom extra
	update_option('uz_email_custom_extra_paid_new_en_subject', 'You paid a new custom extra');
	update_option('uz_email_custom_extra_paid_new_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'You just paid a custom extra.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to seller when a custom extra is bought
	update_option('uz_email_custom_extra_paid_new_seller_en_subject', 'New custom extra payment');
	update_option('uz_email_custom_extra_paid_new_seller_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'A custom extra was paid by ##sender_username##.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to admin when a custom extra is bought
	update_option('uz_email_custom_extra_paid_admin_new_en_subject', 'New custom extra payment on ##your_site_name##');
	update_option('uz_email_custom_extra_paid_admin_new_en_message',
		'Hello admin,'.PHP_EOL.PHP_EOL.

		'A custom extra was paid on your site.'.PHP_EOL.PHP_EOL.

		'Order: ##transaction_page_link##');

	// email sent to buyer when a BT custom extra is cancelled by admin
	update_option('uz_email_custom_extra_cancelled_by_admin_en_subject', 'Custom extra payment cancelled by admin');
	update_option('uz_email_custom_extra_cancelled_by_admin_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'A custom extra payment (with Bank Transfer) was cancelled by admin.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to seller when a BT custom extra is cancelled by admin
	update_option('uz_email_custom_extra_cancelled_by_admin_seller_en_subject', 'Custom extra payment cancelled by admin');
	update_option('uz_email_custom_extra_cancelled_by_admin_seller_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'A custom extra payment (with Bank Transfer) was cancelled by admin.'.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');

	// email sent to buyer when they chose to pay a custom extra using bank transfer payment menthod
	update_option('uz_email_send_bankdetails_to_custom_extra_buyer_en_subject', 'Your custom extra payment is pending');
	update_option('uz_email_send_bankdetails_to_custom_extra_buyer_en_message',
		'Hello ##receiver_username##,'.PHP_EOL.PHP_EOL.

		'We have received the order for custom extra. '.PHP_EOL.PHP_EOL.
		'Please follow this link in order to see it: ##transaction_page_link##'.PHP_EOL.PHP_EOL.

		'In order to further process your order, please make the wire transfer using the following bank details:'.PHP_EOL.PHP_EOL.
		'##bank_details##'.PHP_EOL.PHP_EOL.

		'Thank you,'.PHP_EOL.
		'##your_site_name## Team');
}

$opt8 = get_option( 'wpjobster_first_run_emails8' );
if( empty( $opt8 ) ) {
	update_option('wpjobster_first_run_emails8', 'done');

	update_option('uz_email_job_edit_en_subject', 'Edit job: ##job_name##');
	update_option('uz_email_job_edit_en_message',
		'The user ##username## has edited a job on your website.'.PHP_EOL.
		'Job name: <b>##job_name##</b> '.PHP_EOL.
		'The job is not automatically approved so you have to manually approve the job before it appears on your website.'.PHP_EOL.PHP_EOL.

		'Go here: ##your_site_url##/wp-admin/edit.php?post_type=job');

	update_option('uz_email_request_edit_en_subject', 'Edit request: ##request_name##');
	update_option('uz_email_request_edit_en_message',
		'The user ##username## has edited a request on your website.'.PHP_EOL.
		'Request name: <b>##request_name##</b> '.PHP_EOL.
		'The request is not automatically approved so you have to manually approve the request before it appears on your website.'.PHP_EOL.PHP_EOL.

		'Go here: ##your_site_url##/wp-admin/edit.php?post_type=request');
}

$opt9 = get_option( 'wpjobster_first_run_emails9' );
if( empty( $opt9 ) ) {
	update_option('wpjobster_first_run_emails9', 'done');

	update_option('uz_email_new_bank_transfer_pending_en_subject', 'New Bank Transfer Pending');
	update_option('uz_email_new_bank_transfer_pending_en_message',
		'Hello admin, '.PHP_EOL.PHP_EOL.

		'##sender_username## will make a bank transfer payment which you have to manually approve in the next few days, as soon as it gets cleared. '.PHP_EOL.PHP_EOL.

		'Payment type: ##payment_type## '.PHP_EOL.
		'Payment amount: ##payment_amount## '.PHP_EOL.PHP_EOL.

		'You can find more details and approve it on the following link: '.PHP_EOL.
		'##admin_orders_url## '.PHP_EOL.PHP_EOL.

		'Thank you, '.PHP_EOL.
		'##your_site_name## team.');
}
