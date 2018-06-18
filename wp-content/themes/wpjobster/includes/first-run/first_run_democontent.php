<?php

global $pagenow;

if ( is_admin() && 'admin.php' == $pagenow && isset($_GET['importdemo']) ) {

	// [page] How it Works
	$how_it_works_page_id = wpjobster_insert_pages(
		'wpjobster_how_it_works_page_id',
		'How it works?',
		'<p>Secure, innovative and user friendly platform</p>
		<p>for buying and selling online services.</p>',
		0,
		'page-how-it-works.php'
	);

	$top_section_background = 'field_54788ff376aca';
	wpjobster_acf_update_image_field( $top_section_background, 'computer-how11.jpg', $how_it_works_page_id );
	update_field( 'top_section_background_color', '#2d5767', $how_it_works_page_id );
	update_field( 'top_section_background_opacity', '25', $how_it_works_page_id );
	update_field( 'top_section_text_color', '#ffffff', $how_it_works_page_id );
	update_field( 'top_section_title_size', '14', $how_it_works_page_id );
	update_field( 'top_section_paragraph_size', '32', $how_it_works_page_id );

	update_field( 'buyer_title', 'Buyers', $how_it_works_page_id );
	update_field( 'buyer_description', 'Whether you need a logo design for your new blog, or a video presenter who will help your introduce your company to potential clients, you are at the right place. For everything that you do not know how to do yourself, or you simply don’t have the time, Jobster freelancers are at your service.', $how_it_works_page_id );

	$buyer_repeater = 'field_547888db843c3';
	update_field( $buyer_repeater, array(
		array(
			'b_icon' => wpjobster_acf_update_image_field( '', 'b1_pointer.png', $how_it_works_page_id ),
			'b_title' => '1. Find a service that you need',
			'b_text' => 'Compare prices, portfolios, delivery time, and community recommendations in order to find a seller that best suits your needs. If you have a specific question, simply send them an enquiry.',
		),
		array(
			'b_icon' => wpjobster_acf_update_image_field( '', 'b2_card.png', $how_it_works_page_id ),
			'b_title' => '2. Supply your brief',
			'b_text' => 'Be as detailed as possible so the seller can provide you with the quality service that you are expecting. Your payment is held secure until you confirm that the service is performed to your satisfaction.',
		),
		array(
			'b_icon' => wpjobster_acf_update_image_field( '', 'b3_userred.png', $how_it_works_page_id ),
			'b_title' => '3. Manage transaction',
			'b_text' => 'Exchange files and feedback with the seller via the built-in conversation and transaction management system. The seller will deliver service within a specified time frame.',
		),
		array(
			'b_icon' => wpjobster_acf_update_image_field( '', 'b4_checkgreen.png', $how_it_works_page_id ),
			'b_title' => '4. Approve service delivered',
			'b_text' => 'Once you are happy with the service performed, you can mark the transaction complete, and we\'ll make sure that the seller gets paid. Help the community by leaving a feedback for the seller.',
		),
	), $how_it_works_page_id );

	update_field( 'seller_title', 'Sellers', $how_it_works_page_id );
	update_field( 'seller_description', 'Jobster provides you with an opportunity to turn knowledge, talent or hobby into a permanent source of income! We\'re here to provide security, privacy, and timely payments, so you can keep doing what you love the most.', $how_it_works_page_id );

	$seller_repeater = 'field_54788823843bf';
	update_field( $seller_repeater, array(
		array(
			's_icon' => wpjobster_acf_update_image_field( '', 's1_redlist.png', $how_it_works_page_id ),
			's_title' => '1. Post your service',
			's_text' => 'Post a service offer in accordance with your skills. Be as specific as possible so your clients will know exactly what they are getting for the money they are paying.' ),
		array(
			's_icon' => wpjobster_acf_update_image_field( '', 's2_messageblue.png', $how_it_works_page_id ),
			's_title' => '2. Communicate promptly',
			's_text' => 'Respond to customer enquiries, as well as requests for custom offers, within a reasonable time. Good communication is a prerequisite for successful cooperation.' ),
		array(
			's_icon' => wpjobster_acf_update_image_field( '', 's3_staricon.png', $how_it_works_page_id ),
			's_title' => '3. Build your reputation',
			's_text' => 'Make sure you treat all of your customers the same, and try to provide the best possible service regardless of the transaction value. Satisfied customers will recommend you to their friends.' ),
	), $how_it_works_page_id );


	// [page] Levels
	$levels_page_id = wpjobster_insert_pages(
		'wpjobster_levels_page_id',
		'Levels',
		'Jobster provides you with an opportunity to turn knowledge, talent, or hobby into a permanent source of income. Therefore, invest in self-promotion, keep your customers satisfied, perform quality service, and you\'ll be awarded with higher statuses which will open up a whole new door and opportunity for higher earnings.',
		0,
		'page-levels.php'
	);

	$level_1_image = 'field_54ddee39ae693';
	wpjobster_acf_update_image_field( $level_1_image, 'image1.jpg', $levels_page_id );
	update_field( 'level_1_title', 'Level 1 - Rookie', $levels_page_id );
	update_field( 'level_1_description', 'You\'ve completed $50 worth of orders within the past 30 days, and you\'ve kept your customers happy with a reputation of over 90%. Congratulations, you\'ve just earned yourself a Rookie status!', $levels_page_id );

	$level_2_image = 'field_54ddefa0ae696';
	wpjobster_acf_update_image_field( $level_2_image, 'image2.jpg', $levels_page_id );
	update_field( 'level_2_title', 'Level 2 - Master', $levels_page_id );
	update_field( 'level_2_description', 'After two months of diligent work, and $200 worth of sales, like any true master of his trade, you\'ve kept your overall reputation at 95% or higher. Keep up the good work and it will only get better from here!', $levels_page_id );

	$level_3_image = 'field_54ddefafae697';
	wpjobster_acf_update_image_field( $level_3_image, 'image3.jpg', $levels_page_id );
	update_field( 'level_3_title', 'Top Rated Seller - Expert', $levels_page_id );
	update_field( 'level_3_description', 'Welcome to Jobster Expert Elite! You\'ve worked hard and earned the highest status. For you, Jobster has already become a serious source of income and and a full time job. Work, earn, and enjoy!', $levels_page_id );

	update_field( 'level_bottom_title', 'Commission', $levels_page_id );
	update_field( 'level_bottom_description', 'Posting a service on Jobster is 100% free. We keep a percentage from each successfully completed transaction. The fee varies depending on the total value of the transaction and/or status of the seller.', $levels_page_id );


	// [page] Privacy Policy
	$privacy_policy_page_content = <<<EOT
<h3>1. Introduction</h3>
Thank you for visiting Jobster and, if applicable, choosing to use our Service. We try to make our Privacy Policy easy to understand so that you are informed as to how we use your information. This Privacy Policy, like our Terms of Service, is an integral part of using our Service; therefore you must completely agree to our Privacy Policy in order to use our Site or Service. If you are under 18 please stop using our Service immediately.
<h3>2. Information Collected</h3>
<span style="text-decoration: underline;">Identifying Information Submitted by You</span>

You will not be required to submit any information when visiting our Site. When creating an account, we may collect your name, email, address and country of origin. When purchasing Jobster Theme License you will be required to submit information to our third party payment processor – PayPal. We will not collect any of your payment information, this information will solely be collected and stored by our third party processor.

<span style="text-decoration: underline;">Non-Identifying Information</span>

Whenever you visit our Site, we may collect non-identifying information from you, such as your IP address, interactions with the Site and Service, referring URL, browser, operating system, cookie information, usage, data transferred and Internet Service Provider. Without a subpoena, voluntary compliance on the part of your Internet Service Provider, or additional records from a third party such as your wireless provider, this information alone cannot usually be used to identify you.
<h3>4. Use of Your Information</h3>
We will never sell, transfer or give your information to a third party without your permission. However, you agree that we may use your information:

To enhance or improve our users’ experiences.
To provide our Service to you.
To contact you and to respond to inquiries.
To process transactions.
To register an account and use our Service.

Additionally, we may give your information to law enforcement if we are compelled to by a court order, if there has been a violation of any US laws, EU laws or if a violation of the Terms of Service or Privacy Policy has occurred.
<h3>5. Accessing, Editing, and Removing Your Information</h3>
After creating an account you may be able to edit and/or delete information submitted by contacting <a href="http://wpjobster.com/support/">support</a>. However, you will be unable to opt of our data collection practices. If you want your information to be removed from our database please <a href="http://wpjobster.com/contact/">contact us</a>. We will generally endeavor to delete our database of user information at regular intervals, but we cannot guarantee that your information has been or will be deleted immediately. Although some changes may occur immediately, information may still be stored in a web browser’s cache. We take no responsibility for stored information in your cache, or in other devices that may store information, and disclaim all liability of such.
<h3>6. Cookies</h3>
We use cookies to save your preferences and to remember your shopping cart. For this reason, you must have cookies enabled in your browser to use our Site and Services. Additionally, some or all of our cookies may be accessible by third parties.
<h3>7. Third Party Access to Your Information</h3>
Although you are entering into an Agreement with Jobster to disclose your information to us, we do use third party individuals and organizations to assist us, including contractors, web hosts, and others. Throughout the course of our provision of our Services to you, we may delegate our authority to collect, access, use, and disseminate your information. For example, when you submit a form with personally identifiable information to us, that information will be disseminated or forwarded through our Service to your local law enforcement agency. It is therefore necessary that you grant the third parties we may use in the course of our business the same rights that you afford us under this Privacy Policy. For this reason, you hereby agree that for every authorization which you grant to us in this Privacy Policy, you also grant to any third party that we may hire, contract, or otherwise retain the services of for the purpose of operating, maintaining, repairing, or otherwise improving or preserving our website or its underlying files or systems. You agree not to hold us liable for the actions of any of these third parties, even if we would normally be held vicariously liable for their actions, and that you must take legal action against them directly should they commit any tort or other actionable wrong against you. The following is a non-exhaustive list of other entities that we may store, share, or transfer your information with: PayPal.
<h3>8. Law Enforcement</h3>
You agree that we may disclose your information to authorities if compelled to by a court order. Additionally, you agree that we may disclose your information if we reasonably believe that you have violated a US law or the terms of our Terms of Service or Privacy Policy or if we believe that a third party is at risk of bodily harm. In the event that we receive a subpoena affecting your privacy, we may elect to notify you to give you an opportunity to file a motion to quash the subpoena, or we may attempt to quash it ourselves, but we are not obligated to do either. We may also proactively report you and release your information without receiving any request to third parties where we believe that it is proper to do so for legal reasons, such as instances where we believe your publications violate any law of the United States or any other country having jurisdiction over us, our Site, Services, or our Terms of Service. You release us from any damages that may arise from or relate to the release of your information to a request from law enforcement agencies or private litigants. We may release your information under the conditions listed in this paragraph whether it is to individuals or entities and to any state or Federal authorities within the United States, or elsewhere.
<h3>9. Commercial and Non-Commercial Communications</h3>
By providing information to the Site that forms the basis of communication with you, such as contact information, you waive all rights to file complaints concerning unsolicited email from us, since you have agreed to such communication by providing your information to us. However, you may unsubscribe from certain communications by notifying Jobster that you no longer wish to receive solicitations or information and we will endeavour to remove you from our database where you have the right to request this under our Agreement, Privacy Policy, or applicable law, or where we voluntarily decide to grant the request.
<h3>10. Third Parties</h3>
Jobster may post links to third party websites on our Site or Service, which may include information that we have no control over. When accessing a third party site through our Site or Service, you acknowledge that you are aware that these third party websites are not screened for privacy or security issues by us, and you release us from any liability for the conduct of these third party websites.

Please be aware that this Privacy Policy, and any other policies in place, in addition to any amendments, does not create rights enforceable by third parties. Jobster bears no responsibility for the information collected or used by any advertiser or third party website. You must review their Terms of Service and Privacy to understand how their information collection practices work.
<h3>11. Security Measures</h3>
We use SSL Certificates and vulnerability scanning to enhance the security of our Site and Services. However, we make no guarantees as to the security or privacy of your information. It is in our interest to keep our website secure, but we recommend that you use anti-virus software, routine credit checks, firewalls, and other precautions to protect yourself from security and privacy threats.
<h3>12. Age Compliance</h3>
We intend to fully comply with COPPA and international laws respecting children’s privacy. Therefore, we do not collect or process any information for any persons under the age of 18. If you are under 18 and using our Site or Service, please stop immediately and do not submit any information to us.
<h3>13. International Transfer</h3>
Your information may be transferred to – and maintained on – computers located outside of your state, province, country, or other governmental jurisdiction where the privacy laws may not be as protective as those in your jurisdiction. We may transfer personal information to the United States or elsewhere and process it there. Your consent to this Privacy Policy followed by your submission of such information represents your agreement to that transfer.
<h3>14. Amendments</h3>
Like our Terms of Service, we may amend this Privacy Policy from time to time. When we amend this Privacy Policy, we will place a note on our Site or we may contact you. You must agree to the amendments as a condition of your continued use of our Site and Service. If you do not agree, you must immediately cease using our Site and Service and notify us of your refusal to agree by e-mailing us at support@wpjobster.com.
EOT;
	$privacy_policy_page_id = wpjobster_insert_pages(
		'wpjobster_privacy_policy_page_id',
		'Privacy Policy',
		$privacy_policy_page_content,
		0,
		'page-full-width.php'
	);


	// [page] Terms of Service
	$terms_of_service_page_content = <<<EOT
<h1><span style="font-size: 16px; line-height: 1.5;">These terms and conditions apply when purchasing a Jobster Theme License:</span></h1>
<strong>1. General Usage</strong>

With the purchase of Jobster Theme License, you are entitled to use the theme on one (1) domain only. As a courtesy, we include design files with Jobster Theme — making it easier to meet the needs of your personal site, or providing a Jobster Theme as a base for client work. Our theme is developed with this usage in mind. Jobster retains the right to change the terms and conditions and licensing of this site and our theme at any time.

<strong>2. Guarantee</strong>

Jobster Theme is guaranteed to function correctly upon proper installation, activation and options configuration of the theme within the latest WordPress platform. We can not and do not guarantee compatibility with 3rd party plugins, other then the ones that come bundled with the theme. If for some reason the theme is not working properly, report the issue to our support team, and we will do our best to resolve the problem in a timely manner.

<strong>3. Support Subscription</strong>

With purchase of Jobster theme license you get one (1) year of support and updates. After the initial year has passed you will have an opportunity to pay for an annual support subscription fee. The subscription is for ongoing theme support, updates, maintenance and account access. The subscription renews once per year from the date of purchase at the same rate as the purchased theme license. The subscription may be canceled at any time within your PayPal account. Canceling a subscription will result in suspended access to your Jobster Theme account and suspended access to support resources after one year from your initial purchase date or the last successful subscription renewal. Jobster is not responsible for the failure to cancel a support subscription in the event that it is no longer desired. Canceling a support subscription is the responsibility of the customer.

<strong>Note:</strong> A support subscription is not to be confused with a theme license. Canceling a support subscription will <strong>not</strong> affect your theme in any way.

<strong>4. Support</strong>

Support is provided for a year after the purchase date, or for as long as the annual support subscription is renewed. Support includes the rectifying of issues arising from Jobster Theme features, bug fixes and basic usage questions. We do <strong>not</strong> provide support for the use or issues arising from the use of 3<sup>rd</sup> party plugins, other then the ones that come bundled with the theme; troubleshooting issues occurring in versions of Internet Explorer older than version 9; WordPress itself; or customization. Questions and inquiries should be directed to our support ticketing system.

We are unable to provide support that requires us to view websites with questionable content. Such content includes, but is not limited to, pornography, prostitution, racist content, possible scams, etc.

<strong>Note:</strong> Support is offered Monday through Friday, 9AM to 6PM CEST (GMT+2). Support requests are answered in order received.

<strong>5. Theme Setup Service</strong>

The Theme Setup Service is generally completed within 48 hours from receiving the necessary customer information requested via contact form upon the purchase of the service. The service includes the basic installation of WordPress and/or Jobster Theme, and it includes the setup of the theme <strong>as per the theme demo</strong>. In addition, we may upload and configure a single logo and background/slider image for the site.

The Theme Setup Service does <strong>not</strong> include the creation of pages, posts, content, navigation items or anything outside of the theme demo content. It does <strong>not</strong> include any customizations or changes to the theme code. The Theme Setup Service should <strong>not</strong> be used for established websites with existing content.

<strong>6. Refunds</strong>

We do <strong>not</strong> offer refunds for digital product sales or support subscription renewal fees. All sales are final. If there is an issue regarding a theme, we will address the problem. Unfortunately, there is no effective way for us to both combat fraud and issue refunds. We will be happy to answer questions regarding our theme before your purchase to insure it will meet your needs. Thank you for your understanding.

<strong>7. Licensing</strong>

Jobster theme is sold under a split license. This means that we as authors protect our rights and freedoms to respect (and comply with) the GPL as well as to control our own work.

The Jobster Theme License grants you, the purchaser, an ongoing, non-exclusive, worldwide license to make use of the digital work (Jobster Theme) you have purchased.

You are licensed to use the Item to create one single End Product (website/marketplace) for yourself or for one client (a “single application”). You can create one End Product for a client, and you can transfer that single End Product to your client for any fee. This license is then transferred to your client.

You can modify or manipulate the Jobster Theme. You can combine the Jobster Theme with other works and make a derivative work from it. The resulting works are subject to the terms of this license.

You can’t re-distribute the Jobster Theme as stock, in a tool or template, or with source files. You can’t do this with Jobster Theme on its own or bundled with other items, and even if you modify the Jobster Theme. You can’t re-distribute or make available the Jobster Theme as-is or with superficial modifications.

Although you can modify the Jobster Theme and therefore delete unwanted components, you can’t extract and use a single component of a Jobster Theme on a stand-alone basis.

You can only use the Jobster Theme for lawful purposes. Moreover, you can’t use it in a way that is defamatory, obscene or demeaning, or in connection with sensitive subjects.

<strong>8. Theme Compatibility</strong>

Currently Jobster Theme is designed to function properly with WordPress 3.5 and higher. It is our job to make sure the theme is up to date with the latest versions of WordPress. We cannot guarantee it will work properly on older installations of WordPress. We cannot guarantee the compatibility of our theme with all third party software and plugins. If a conflict does arise, we will do our best resolve the situation. Moreover, we cannot guarantee the Jobster Theme will function correctly after modification to the code, or a failure to install the theme or WordPress properly. Jobster Theme is not compatible with the wordpress.com blogging community. It is developed for individual installations of the WordPress platform.
EOT;
	$terms_of_service_page_id = wpjobster_insert_pages(
		'wpjobster_terms_of_service_page_id',
		'Terms of Service',
		$terms_of_service_page_content,
		0,
		'page-full-width.php'
	);


	$lipsum = <<<EOT
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
EOT;
	$news_demo_1 = array(
		'post_title' => 'Lorem Ipsum News One',
		'post_content' => $lipsum,
		'post_status' => 'publish',
		'post_type' => 'news',
		'post_author' => 1,

	);
	$news_demo_1_id = wp_insert_post( $news_demo_1 );
	set_post_thumbnail( $news_demo_1_id, wpjobster_acf_update_image_field( '', 'news-demo-1.jpeg', $news_demo_1_id ) );

	$news_demo_2 = array(
		'post_title' => 'Lorem Ipsum News Two',
		'post_content' => $lipsum,
		'post_status' => 'publish',
		'post_type' => 'news',
		'post_author' => 1,

	);
	$news_demo_2_id = wp_insert_post( $news_demo_2 );
	set_post_thumbnail( $news_demo_2_id, wpjobster_acf_update_image_field( '', 'news-demo-2.jpg', $news_demo_2_id ) );


	// options for pages
	if ( get_option( 'wpjobster_update_380_default_options_acf' ) != 'done'
		&& get_option( 'wpjobster_sql_10extras' ) != 'done' ) {
		if ( function_exists( 'update_field' ) ) {

			update_field( 'post_new_job_page_url', get_post( get_option( 'wpjobster_post_new_page_id', false ) ), 'options' );
			update_field( 'how_it_works_page_url', get_post( get_option( 'wpjobster_how_it_works_page_id', false ) ), 'options' );

			update_field( 'primary_color', '#83C124', 'options' );
			update_field( 'secondary_color', '#2d5767', 'options' );

			update_option( 'wpjobster_update_380_default_options_acf', 'done' );
		}
	}


	// job categories
	function wpjobster_create_demo_categories() {

		$categories_to_insert = array(
			array( 'Other',
				array(
				)
			),
			array( 'Business',
				array( 'Analysis', 'Business plan', 'Finances', 'Legal advice', 'Other', 'Presentations', 'Virtual assistant',
				)
			),
			array( 'Graphics & Design',
				array( 'Architecture', 'Banners', 'Business cards', 'Caricatures', 'Flyers & Posters', 'Illustrations', 'Logo design', 'Other', 'Photoshop', 'Web design',
				)
			),
			array( 'Lifestyle',
				array( 'Beauty & Fashion', 'Health & Fitness', 'Horoscope & Tarot', 'Other', 'Psychotherapy',
				)
			),
			array( 'Marketing',
				array( 'Advertisments', 'Internet marketing', 'Other', 'Photo models',
				)
			),
			array( 'Online classes & Teaching',
				array( 'Languages', 'Music & Instruments', 'Other', 'Science',
				)
			),
			array( 'Programming & IT',
				array( '.NET', 'HTML & CSS', 'Joomla & Drupal', 'MySQL', 'Other', 'PHP', 'WordPress',
				)
			),
			array( 'Video & Audio',
				array( 'Animation & 3D', 'Commercials', 'Jingles', 'Other', 'Postproduction', 'Sound effects', 'Video spokesperson', 'Voice-over',
				)
			),
			array( 'Writing & Translation',
				array( 'Copywriting', 'Other', 'Press release', 'Proofreading & Editing', 'Resumes', 'Student Papers', 'Translation', 'Web content',
				)
			),
		);

		foreach ( $categories_to_insert as $category ) {

			$category_name = $category[0];
			$subcategories_to_insert = $category[1];

			$this_cat = array(
				'cat_name' => $category_name,
				'category_parent' => '',
				'taxonomy' => 'job_cat'
			);

			$this_cat_id = wp_insert_category( $this_cat );

			if ( $this_cat_id != false
				&& isset( $subcategories_to_insert )
				&& ! empty( $subcategories_to_insert ) ) {

				foreach ( $subcategories_to_insert as $subcategory_name ) {

					$this_subcat = array(
						'cat_name' => $subcategory_name,
						'category_parent' => $this_cat_id,
						'taxonomy' => 'job_cat'
					);

					$this_subcat_id = wp_insert_category( $this_subcat );
				}
			}
		}
	}

	add_action( 'admin_init','wpjobster_create_demo_categories' );


	// menus
	function wpjobster_create_demo_menus() {

		$menu_name = 'Custom Links Menu Test';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  __('Home'),
					'menu-item-classes' => 'home',
					'menu-item-url' => home_url( '/' ),
					'menu-item-status' => 'publish'
				)
			);

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  __('Custom Page'),
					'menu-item-url' => home_url( '/custom/' ),
					'menu-item-status' => 'publish'
				)
			);
		}


		$menu_name = 'Header Main Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$header_menu_categories = array(
				'graphics-design', 'programming-it', 'video-audio', 'marketing', 'business', 'writing-translation', 'online-classes-teaching', 'lifestyle', 'other',
			);

			foreach ( $header_menu_categories as $slug ) {
				$this_term = get_term_by( 'slug', $slug, 'job_cat' );
				if ( ! empty( $this_term ) ) {
					if ( $slug == 'online-classes-teaching' ) {
						$this_term_name = 'Online Classes';
					} else {
						$this_term_name = $this_term->name;
					}
					wp_update_nav_menu_item( $menu_id, 0, array(
							'menu-item-object-id' => $this_term->term_id,
							'menu-item-object' => 'job_cat',
							'menu-item-type' => 'taxonomy',
							'menu-item-title' => $this_term_name,
							'menu-item-status' => 'publish'
						)
					);
				}
			}
		}


		$menu_name = 'Header User Dropdown Extra';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$page_id = get_option( 'wpjobster_my_account_reviews_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-title' => 'Feedback',
						'menu-item-status' => 'publish'
					)
				);
			}
		}

		$menu_name = 'Header User Account Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$pages = array(
				get_option( 'wpjobster_my_account_page_id', false ),
				get_option( 'wpjobster_my_account_shopping_page_id', false ),
				get_option( 'wpjobster_my_account_sales_page_id', false ),
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


		$menu_name = 'C1 Footer Menu - Logged In';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$page_id = get_option( 'wpjobster_my_account_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}

			$page_id = get_option( 'wpjobster_post_new_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}
		}


		$menu_name = 'C2 Footer Menu - Logged In';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Requests',
					'menu-item-url' => home_url( '/request/' ),
					'menu-item-status' => 'publish'
				)
			);

			$page_id = get_option( 'wpjobster_my_requests_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}
		}


		$menu_name = 'C1 Footer Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Home',
					'menu-item-url' => home_url( '/' ),
					'menu-item-status' => 'publish'
				)
			);

			$page_id = get_option( 'wpjobster_how_it_works_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}
		}


		$menu_name = 'C2 Footer Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Login',
					'menu-item-classes' => 'login-link',
					'menu-item-url' => home_url( '/wp-login.php' ),
					'menu-item-status' => 'publish'
				)
			);

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Register',
					'menu-item-classes' => 'register-link',
					'menu-item-url' => home_url( '/wp-login.php?action=register' ),
					'menu-item-status' => 'publish'
				)
			);
		}


		$menu_name = 'C3 Footer Useful Links';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'News',
					'menu-item-url' => home_url( '/news/' ),
					'menu-item-status' => 'publish'
				)
			);

			$page_id = get_option( 'wpjobster_levels_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}
		}


		$menu_name = 'C4 Footer Useful Links';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$page_id = get_option( 'wpjobster_terms_of_service_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}

			$page_id = get_option( 'wpjobster_privacy_policy_page_id', false );
			if ( $page_id ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-object-id' => $page_id,
						'menu-item-type' => 'post_type',
						'menu-item-object' => 'page',
						'menu-item-status' => 'publish'
					)
				);
			}
		}


		$menu_name = 'C5 Footer Social';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu_exists ) {
			$menu_id = wp_create_nav_menu( $menu_name );

			$menu_item_id = wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Facebook',
					'menu-item-url' => '#',
					'menu-item-status' => 'publish'
				)
			);
			$menu_icon_array = array(
				'type' => 'dashicons',
				'dashicons-icon' => 'dashicons-facebook-alt',
				'elusive-icon' => '',
				'fa-icon' => '',
				'foundation-icons-icon' => '',
				'genericon-icon' => '',
				'image-icon' => '',
				'hide_label' => '',
				'position' => 'before',
				'image_size' => 'full',
				'vertical_align' => 'middle',
				'font_size' => '1.2',
			);
			update_post_meta( $menu_item_id, 'menu-icons', $menu_icon_array );

			$menu_item_id = wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Twitter',
					'menu-item-url' => '#',
					'menu-item-status' => 'publish'
				)
			);
			$menu_icon_array = array(
				'type' => 'dashicons',
				'dashicons-icon' => 'dashicons-twitter',
				'elusive-icon' => '',
				'fa-icon' => '',
				'foundation-icons-icon' => '',
				'genericon-icon' => '',
				'image-icon' => '',
				'hide_label' => '',
				'position' => 'before',
				'image_size' => 'full',
				'vertical_align' => 'middle',
				'font_size' => '1.2',
			);
			update_post_meta( $menu_item_id, 'menu-icons', $menu_icon_array );

			$menu_item_id = wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title' =>  'Google+',
					'menu-item-url' => '#',
					'menu-item-status' => 'publish'
				)
			);
			$menu_icon_array = array(
				'type' => 'dashicons',
				'dashicons-icon' => 'dashicons-googleplus',
				'elusive-icon' => '',
				'fa-icon' => '',
				'foundation-icons-icon' => '',
				'genericon-icon' => '',
				'image-icon' => '',
				'hide_label' => '',
				'position' => 'before',
				'image_size' => 'full',
				'vertical_align' => 'middle',
				'font_size' => '1.2',
			);
			update_post_meta( $menu_item_id, 'menu-icons', $menu_icon_array );
		}



		// $locations = get_theme_mod( 'nav_menu_locations' ); // this is empty for new installations
		$locations = array(
			'wpjobster_header_main_menu' => 0,
			'wpjobster_header_secondary_menu' => 0,
			'wpjobster_header_user_dropdown_extra' => 0,
			'wpjobster_header_user_account_menu' => 0,
			'wpjobster_responsive_main_menu' => 0,
			'wpjobster_responsive_secondary_menu' => 0,
		);

		if ( ! empty( $locations ) ) {
			foreach ( $locations as $locationId => $menuValue ) {
				switch ( $locationId ) {
					case 'wpjobster_header_main_menu':
						$menu = get_term_by( 'name', 'Header Main Menu', 'nav_menu' );
					break;

					case 'wpjobster_responsive_secondary_menu':
						$menu = get_term_by( 'name', 'Header Main Menu', 'nav_menu' );
					break;

					case 'wpjobster_header_user_dropdown_extra':
						$menu = get_term_by( 'name', 'Header User Dropdown Extra', 'nav_menu' );
					break;

					case 'wpjobster_header_user_account_menu':
						$menu = get_term_by( 'name', 'Header User Account Menu', 'nav_menu' );
					break;

					default:
						$menu = '';
					break;
				}

				if ( $menu ) {
					$locations[$locationId] = $menu->term_id;
				} else {
					$locations[$locationId] = 0;
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	add_action( 'admin_init', 'wpjobster_create_demo_menus' );


	// Homepage Featured Categories
	function wpjobster_add_homepage_featured_categories() {

		$main_page_url = get_option( 'main_page_url' );
		if ( ! $main_page_url ) {
			return;
		}

		$featured_category = 'field_55ae8aab087f8';

		$cat_programming_it = get_term_by( 'slug', 'programming-it', 'job_cat' );
		$cat_programming_it = isset( $cat_programming_it->term_id ) ? $cat_programming_it->term_id : '';

		$cat_flyers_posters = get_term_by( 'slug', 'flyers-posters', 'job_cat' );
		$cat_flyers_posters = isset( $cat_flyers_posters->term_id ) ? $cat_flyers_posters->term_id : '';

		$cat_writing_translation = get_term_by( 'slug', 'writing-translation', 'job_cat' );
		$cat_writing_translation = isset( $cat_writing_translation->term_id ) ? $cat_writing_translation->term_id : '';

		$cat_business_plan = get_term_by( 'slug', 'business-plan', 'job_cat' );
		$cat_business_plan = isset( $cat_business_plan->term_id ) ? $cat_business_plan->term_id : '';

		update_field( $featured_category, array(
			array(
				'featured_category_id' => $cat_programming_it,
				'featured_category_image' => wpjobster_acf_update_image_field( '', 'featured-programming-it.jpg', $main_page_url ),
			),
			array(
				'featured_category_id' => $cat_flyers_posters,
				'featured_category_image' => wpjobster_acf_update_image_field( '', 'featured-flyers-posters.jpg', $main_page_url ),
			),
			array(
				'featured_category_id' => $cat_writing_translation,
				'featured_category_image' => wpjobster_acf_update_image_field( '', 'featured-writing-translation.jpg', $main_page_url ),
			),
			array(
				'featured_category_id' => $cat_business_plan,
				'featured_category_image' => wpjobster_acf_update_image_field( '', 'featured-business-plan.jpg', $main_page_url ),
			),
		), $main_page_url );
	}

	add_action( 'admin_init', 'wpjobster_add_homepage_featured_categories' );


	function wpjobster_import_rev_sliders() {

		$absolute_path = __FILE__;
		$path_to_file = explode( 'wp-content', $absolute_path );
		$path_to_wp = $path_to_file[0];

		require_once( $path_to_wp. '/wp-load.php' );
		require_once( $path_to_wp. '/wp-includes/functions.php' );

		$uploads = wp_upload_dir();
		$sliders_dir = $uploads['basedir'] . '/democontent/demosliders/';

		$slider_array = array(
			$sliders_dir . "home.zip",
			$sliders_dir . "logged-in-home.zip",
		);
		$slider = new RevSlider();

		foreach( $slider_array as $filepath ) {
			$slider->importSliderFromPost( true, true, $filepath );
		}
	}

	add_action( 'admin_notices', 'wpjobster_import_rev_sliders', 11 );

	function wpjobster_import_demo_content_menu_in_widgets(){

		update_option( 'wpjobster_set_footer_widgets_content', 'done' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'User Menu', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c1-footer-menu' ) ), 'footer-widget-1' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'User Menu', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c1-footer-menu-logged-in' ) ), 'footer-widget-1-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c2-footer-menu' ) ), 'footer-widget-2' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c2-footer-menu-logged-in' ) ), 'footer-widget-2-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Useful Links', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c3-footer-useful-links' ) ), 'footer-widget-3' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Useful Links', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c3-footer-useful-links' ) ), 'footer-widget-3-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c4-footer-useful-links' ) ), 'footer-widget-4' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => '', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c4-footer-useful-links' ) ), 'footer-widget-4-logged-in' );

		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Follow us on', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c5-footer-social' ) ), 'footer-widget-5' );
		wpjobster_insert_widget_in_sidebar( 'nav_menu', array( 'title' => 'Follow us on', 'nav_menu' => wpjobster_get_menu_id_by_slug( 'c5-footer-social' ) ), 'footer-widget-5-logged-in' );
	}

	add_action( 'admin_init','wpjobster_import_demo_content_menu_in_widgets' );

	update_option( 'wpjobster_democontent_import', 'done' );
	add_action( 'admin_notices', 'wpjobster_demo_content_imported_notice' );

} // endif


// check if the demo content can be imported
if ( get_option( 'wpjobster_democontent_import' ) != 'done'
	&& get_option( 'wpjobster_sql_10extras' ) != 'done'
	&& ! has_action( 'admin_notices', 'wpjobster_required_plugins' )
	&& ! has_action( 'admin_notices', 'wpjobster_database_update_required_notice' ) ) {
	add_action( 'admin_notices', 'wpjobster_import_demo_content_notice' );
}


function wpjobster_import_demo_content_notice() {
	if ( ! PAnD::is_admin_notice_active( 'notice-import-demo-forever' ) ) {
		return;
	}
	?>
	<div data-dismissible="notice-import-demo-forever" class="updated notice is-dismissible">
		<h2><?php _e('Do you want to import the Jobster demo content?', 'wpjobster'); ?>
		<a class="page-title-action" href="<?php echo get_bloginfo('url').'/wp-admin/admin.php?page=PT1_admin_mnu&importdemo=true' ?>"><?php _e('Import Now', 'wpjobster'); ?></a></h2>
	</div>
	<?php
}


function wpjobster_demo_content_imported_notice() {
	?>
	<div class="updated notice is-dismissible">
		<p><?php _e('Jobster demo content was successfully imported!', 'wpjobster'); ?></p>
	</div>
	<?php
}





//--------------------------------------
// (c) WPJobster Helper Function
//--------------------------------------

function wpjobster_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;

	if ( '' == $attachment_url ) {
		return;
	}

	$upload_dir_paths = wp_upload_dir();

	if ( substr( $upload_dir_paths['baseurl'], 0, 8 ) != substr( $attachment_url, 0, 8 ) ) {
		if ( substr( $upload_dir_paths['baseurl'], 0, 8 ) === 'https://' ) {
			$attachment_url = substr_replace( $attachment_url, 'https://', 0, 7 );
		} else {
			$attachment_url = substr_replace( $attachment_url, 'http://', 0, 8 );
		}
	}

	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}

	return $attachment_id;
}

//--------------------------------------
// (c) WPJobster Helper Function
//--------------------------------------

function wpjobster_acf_update_image_field( $acf_field = '', $image_name, $parent_id ) {
	include_once( ABSPATH . 'wp-admin/includes/image.php' );
	global $current_user;
	$current_user = wp_get_current_user();
	$uploads = wp_upload_dir();
	$content_url = $uploads['baseurl'] . '/democontent/';
	$content_dir = $uploads['basedir'] . '/democontent/';

	$attach_id = wpjobster_get_attachment_id_from_url( $content_url . $image_name );

	if ( ! $attach_id ) {
		$save_path = $content_dir . $image_name;
		if ( file_exists( $save_path ) ) {
			$file_info = @getimagesize( $save_path );
		} else {
			return false;
		}

		$attachment = array(
			'post_mime_type' => $file_info['mime'],
			'post_title'     => addslashes( $image_name ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_parent'    => $parent_id,
			'post_author'    => $current_user->ID,
		);

		$attach_id = wp_insert_attachment( $attachment, $save_path, $parent_id );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $save_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
	}

	if ( function_exists( 'update_field' ) ) {
		if ( $acf_field ) {
			update_field( $acf_field, $attach_id, $parent_id );
		}
		return $attach_id;
	}

	return false;
}
