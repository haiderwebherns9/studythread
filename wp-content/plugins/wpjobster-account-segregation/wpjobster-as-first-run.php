<?php
add_action( 'init', 'as_custom_buyer_menu' );
function as_custom_buyer_menu() {
	register_nav_menu('wpjobster_header_buyer_account_menu', 'Header Buyer Account Menu');

	$menu_header = get_term_by('name', 'Header Buyer Account Menu', 'nav_menu');
	$menu_header_id = $menu_header->term_id;
	$locations = get_theme_mod('nav_menu_locations');
	$locations['wpjobster_header_buyer_account_menu'] = $menu_header_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

add_action( 'admin_init', 'as_first_run' );
function as_first_run(){
	if ( get_option( 'wpj_as_seller_account_verification' ) != 'done' ) {
		update_option( 'wpj_as_seller_account_verification', 'done' );

		wpjobster_insert_pages( 'wpjobster_account_verification_page_id',
			'Account Verification',
			'[wpj_as_seller_account_verification]',
			0,
			'page-vc-default.php'
		);

		update_option( 'wpj_as_enable_account_verification', 'no' );
		update_option( 'wpj_as_account_verification_page', get_option( 'wpjobster_account_verification_page_id' ) );
	}

	if ( get_option( 'wpj_as_default_emails' ) != 'done' ) {
		update_option( 'wpj_as_default_emails', 'done' );

		$as_new_buyer =
			'Hello ##username##,'.PHP_EOL.PHP_EOL.
			'Welcome to our website.'.PHP_EOL.PHP_EOL.
			'Thank you,'.PHP_EOL.
			'##your_site_name## Team';
		update_option( 'uz_email_as_new_buyer_en_subject', 'Welcome to ##your_site_name##' );
		update_option( 'uz_email_as_new_buyer_en_message', $as_new_buyer );
		update_option( 'uz_sms_as_new_buyer_en_message', $as_new_buyer );


		$as_new_buyer_admin =
			'Hello admin,'.PHP_EOL.PHP_EOL.
			'A new buyer just registered on your site.'.PHP_EOL.PHP_EOL.
			'Details:'.PHP_EOL.
			'Username: ##username##'.PHP_EOL.
			'Email: ##user_email##';
		update_option( 'uz_email_as_new_buyer_admin_en_subject', 'New buyer registration on ##your_site_name##' );
		update_option( 'uz_email_as_new_buyer_admin_en_message', $as_new_buyer_admin );
		update_option( 'uz_sms_as_new_buyer_admin_en_message', $as_new_buyer_admin );


		$as_new_seller_not_approved =
			'Hello ##username##,'.PHP_EOL.PHP_EOL.
			'Welcome to our website.'.PHP_EOL.
			'Your seller profile is pending admin review.'.PHP_EOL.PHP_EOL.
			'Thank you,'.PHP_EOL.
			'##your_site_name## Team';
		update_option( 'uz_email_as_new_seller_not_approved_en_subject', 'Welcome to ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_not_approved_en_message', $as_new_seller_not_approved );
		update_option( 'uz_sms_as_new_seller_not_approved_en_message', $as_new_seller_not_approved );


		$as_new_seller_not_approved_admin =
			'Hello admin,'.PHP_EOL.PHP_EOL.
			'A new seller just registered on your site and is pending approval.'.PHP_EOL.
			'Please go to the account segregation administration page in order to approve or reject.'.PHP_EOL.PHP_EOL.
			'Details:'.PHP_EOL.
			'Username: ##username##'.PHP_EOL.
			'Email: ##user_email##';
		update_option( 'uz_email_as_new_seller_not_approved_admin_en_subject', 'New seller registration needs approval on ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_not_approved_admin_en_message', $as_new_seller_not_approved_admin );
		update_option( 'uz_sms_as_new_seller_not_approved_admin_en_message', $as_new_seller_not_approved_admin );


		$as_new_seller_approved =
			'Hello ##username##,'.PHP_EOL.PHP_EOL.
			'Your seller status on ##your_site_name## was approved.'.PHP_EOL.PHP_EOL.
			'Thank you,'.PHP_EOL.
			'##your_site_name## Team';
		update_option( 'uz_email_as_new_seller_approved_en_subject', 'Seller status approved on ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_approved_en_message', $as_new_seller_approved );
		update_option( 'uz_sms_as_new_seller_approved_en_message', $as_new_seller_approved );


		$as_new_seller_approved_admin =
			'Hello admin,'.PHP_EOL.PHP_EOL.
			'A new seller has been approved on your site.'.PHP_EOL.PHP_EOL.
			'Details:'.PHP_EOL.
			'Username: ##username##'.PHP_EOL.
			'Email: ##user_email##';
		update_option( 'uz_email_as_new_seller_approved_admin_en_subject', 'New seller has been approved on ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_approved_admin_en_message', $as_new_seller_approved_admin );
		update_option( 'uz_sms_as_new_seller_approved_admin_en_message', $as_new_seller_approved_admin );


		$as_new_seller_rejected =
			'Hello ##username##,'.PHP_EOL.PHP_EOL.
			'Your seller status on ##your_site_name## was rejected.'.PHP_EOL.PHP_EOL.
			'Thank you,'.PHP_EOL.
			'##your_site_name## Team';
		update_option( 'uz_email_as_new_seller_rejected_en_subject', 'Seller status rejected on ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_rejected_en_message', $as_new_seller_rejected );
		update_option( 'uz_sms_as_new_seller_rejected_en_message', $as_new_seller_rejected );


		$as_new_seller_rejected_admin =
			'Hello admin,'.PHP_EOL.PHP_EOL.
			'A new seller has been rejected from your site.'.PHP_EOL.PHP_EOL.
			'Details:'.PHP_EOL.
			'Username: ##username##'.PHP_EOL.
			'Email: ##user_email##';
		update_option( 'uz_email_as_new_seller_rejected_admin_en_subject', 'New seller has been rejected on ##your_site_name##' );
		update_option( 'uz_email_as_new_seller_rejected_admin_en_message', $as_new_seller_rejected_admin );
		update_option( 'uz_sms_as_new_seller_rejected_admin_en_message', $as_new_seller_rejected_admin );

	}

	if( get_option( 'wpj_as_set_user_account_menu' ) != 'done' ){

		update_option( 'wpj_as_set_user_account_menu', 'done' );

		$menu_name = 'Header Buyer Account Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$pages = array(
				get_option( 'wpjobster_my_account_shopping_page_id', false ),
				get_option( 'wpjobster_my_account_payments_page_id', false ),
				get_option( 'wpjobster_my_account_priv_mess_page_id', false ),
				get_option( 'wpjobster_my_account_personal_info_page_id', false ),
				get_option( 'wpjobster_my_account_reviews_page_id', false )
			);

			if ( $pages ) {
				foreach ($pages as $page) {
					wp_update_nav_menu_item( $menu_id, 0, array(
							'menu-item-object-id' => $page,
							'menu-item-type' => 'post_type',
							'menu-item-object' => 'page',
							'menu-item-title' => get_the_title( $page ),
							'menu-item-status' => 'publish'
						)
					);
				}
			}
		}
	}

	if ( get_option( 'wpj_as_seller_redirect_pages' ) != 'done' ) {
		update_option( 'wpj_as_seller_redirect_pages', 'done' );

		update_option( 'wpj_as_seller_register_redirection',  get_option( 'wpjobster_my_account_sales_page_id' ) );
		update_option( 'wpj_as_buyer_register_redirection' ,  get_option( 'wpjobster_my_account_shopping_page_id' ) );
		update_option( 'wpj_as_seller_logged_in_homepage'  ,  get_option( 'main_page_url_user' ) );
		update_option( 'wpj_as_buyer_logged_in_homepage'   ,  get_option( 'main_page_url_user' ) );
	}
}
