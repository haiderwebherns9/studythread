<?php
add_action( 'after_setup_theme', 'wpjobster_load_textdomain' );
function wpjobster_load_textdomain() {
	$domain = 'wpjobster';
	// wp-content/languages/wpjobster/xx_XX.mo
	load_theme_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain );
	// wp-content/themes/child-wpjobster/languages/xx_XX.mo
	load_theme_textdomain( $domain, get_stylesheet_directory() . '/languages' );
	// wp-content/themes/wpjobster/languages/xx_XX.mo
	load_theme_textdomain( $domain, get_template_directory() . '/languages' );
}

add_action( 'init', 'flush_cookie_buster' );
function flush_cookie_buster(){
	if ( isset( $_SERVER['HTTP_COOKIE'] ) && isset( $_GET['flush_cookies'] ) ) {
		$cookies = explode( ';', $_SERVER['HTTP_COOKIE'] );
		foreach ( $cookies as $cookie ) {
			$parts = explode( '=', $cookie );
			$name = trim( $parts[0] );
			setcookie( $name, '', time() - 1000 );
			setcookie( $name, '', time() - 1000, '/' );
		}

	}
}

add_action( 'init', 'do_output_buffer' );
function do_output_buffer() {
	ob_start();
}

add_action( 'after_setup_theme', 'wpjobster_theme_updater' );
function wpjobster_theme_updater() {
	require( get_template_directory() . '/updater/theme-updater.php' );
}

add_filter( 'acf/settings/load_json', function( $paths ) {
	$paths = array( get_template_directory() . '/acf-json' );
	return $paths;
});

// POST TYPE & TAXONOMY //
add_action( 'init', 'wpjobster_create_post_type' );
function wpjobster_create_post_type(){
	global $jobs_url_thing;
	$icn = get_template_directory_uri() . "/images/laptopcomputer.png";
	register_post_type('job',
		array(
			'labels' => array(
				'name'          => __( 'Jobs', 'wpjobster' ),
				'singular_name' => __( 'Job', 'wpjobster' ),
				'add_new'       => __( 'Add New Job', 'wpjobster' ),
				'new_item'      => __( 'New Job', 'wpjobster' ),
				'edit_item'     => __( 'Edit Job', 'wpjobster' ),
				'add_new_item'  => __( 'Add New Job', 'wpjobster' ),
				'search_items'  => __( 'Search Jobs', 'wpjobster' )
			),
			'public' => true,
			'menu_position' => 5,
			'register_meta_box_cb' => 'wpjobster_set_metaboxes',
			'has_archive' => 'all-jobs',
			'rewrite' => array(
				'slug'       => $jobs_url_thing . "/%job_cat%",
				'with_front' => false
			),
			'supports' => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'comments'
			),
			'_builtin' => false,
			'menu_icon' => $icn,
			'publicly_queryable' => true,
			'hierarchical' => false
		)
	);

	$icn = get_template_directory_uri() . "/images/tags2.png";
	register_post_type(
		'offer',
		array(
			'labels' => array(
				'name'          => __( 'Custom Offers', 'wpjobster' ),
				'singular_name' => __( 'Custom Offer', 'wpjobster' ),
				'add_new'       => __( 'Add New Offer', 'wpjobster' ),
				'new_item'      => __( 'New Offer', 'wpjobster' ),
				'edit_item'     => __( 'Edit Offer', 'wpjobster' ),
				'add_new_item'  => __( 'Add New Offer', 'wpjobster' ),
				'search_items'  => __( 'Search Offer', 'wpjobster' )
			),
			'public' => true,
			'menu_position' => 6,
			'register_meta_box_cb' => 'wpjobster_set_metaboxes',
			'has_archive' => "all-jobs",
			'rewrite' => array(
				'slug'       => "offers",
				'with_front' => false
				),
			'supports' => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'comments'
				),
			'_builtin' => false,
			'menu_icon' => $icn,
			'publicly_queryable' => true,
			'hierarchical' => false
		)
	);

	$icn = get_template_directory_uri() . "/images/question43.png";
	register_post_type('request',
		array(
			'labels' => array(
				'name'          => __( 'Requests', 'wpjobster' ),
				'singular_name' => __( 'Request', 'wpjobster' ),
				'add_new'       => __( 'Add New Request', 'wpjobster' ),
				'new_item'      => __( 'New Request', 'wpjobster' ),
				'edit_item'     => __( 'Edit Request', 'wpjobster' ),
				'add_new_item'  => __( 'Add New Request', 'wpjobster' ),
				'search_items'  => __( 'Search Requests', 'wpjobster' )
			),
			'public' => true,
			'menu_position' => 7,
			'has_archive' => true,
			'rewrite' => true,
			'supports' => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'comments'
			),
			'_builtin' => false,
			'menu_icon' => $icn,
			'publicly_queryable' => true,
			'hierarchical' => false
		)
	);
	register_taxonomy( 'request_cat', 'request', array( 'hierarchical' => true, 'label' => __('Request Categories', 'wpjobster') ) );
	register_taxonomy( 'job_cat', array('job'), array( 'hierarchical' => true, 'label' => __('Job Categories', 'wpjobster') ) );
	add_post_type_support( 'job', 'author' );
	register_taxonomy_for_object_type( 'post_tag', 'job' );
	register_taxonomy_for_object_type( 'post_tag', 'request' );

	$options = array(
		"name" => "news",
		"active" => "1",
		"labels" => Array
			(
				"name"               => __( "News","wpjbster" ),
				"singular_name"      => __( "News","wpjbster" ),
				"add_new"            => __( "Add New","wpjbster" ),
				"add_new_item"       => __( "Add New News","wpjbster" ),
				"edit_item"          => __( "Edit News","wpjbster" ),
				"new_item"           => __( "New News","wpjbster" ),
				"view_item"          => __( "View News","wpjbster" ),
				"search_items"       => __( "Search News","wpjbster" ),
				"not_found"          => __( "No News Found","wpjbster" ),
				"not_found_in_trash" => __( "No News Found In Trash","wpjbster" ),
				"parent_item_colon"  => __( "Parent News:","wpjbster" ),
				"menu_name"          => __( "News","wpjbster" ),
				"all_items"          => __( "All News","wpjbster" ),
			),

		"supports" => Array
			(
				"0" => "title",
				"1" => "editor",
				"2" => "trackbacks",
				"3" => "custom-fields",
				"4" => "comments",
				"5" => "revisions",
				"6" => "thumbnail"
			),

		"taxonomies" => Array
			(
				"0" => "category",
				"1" => "post_tag",
			),

		"query" => "yes",
		"hierarchy" => "no",
		"capabilites" => "type",
		"rewrite" => "yes",
		"rewrite_feeds" => "yes",
		"rewrite_pages" => "yes",
		"rewrite_front" => "no",
		"archive" => "yes_name",
		"public" => true,
		'has_archive' => true,
		"menu_position"=> 8,
		"ui" => "yes",
		"caps_type" => "post",
		"caps" => Array
			(
				"edit_post"              => "edit_post",
				"read_post"              => "read_post",
				"delete_post"            => "delete_post",
				"edit_posts"             => "edit_posts",
				"edit_others_posts"      => "edit_others_posts",
				"publish_posts"          => "publish_posts",
				"read_private_posts"     => "read_private_posts",
				"read"                   => "read",
				"delete_posts"           => "delete_posts",
				"delete_private_posts"   => "delete_private_posts",
				"delete_published_posts" => "delete_published_posts",
				"delete_others_posts"    => "delete_others_posts",
				"edit_private_posts"     => "edit_private_posts",
				"edit_published_posts"   => "edit_published_posts",
			),
		"exclude_from_search" => "no",
		"can_export" => "yes"
	);
	register_post_type( 'news', $options );
	register_taxonomy_for_object_type( 'category', 'news' );
	register_taxonomy_for_object_type( 'post_tag', 'news' );

	flush_rewrite_rules();
}

add_action( 'wp_head', 'wpjobster_custom_css_thing' );
function wpjobster_custom_css_thing(){
	if ( is_home() ):
		$opt = get_option( 'wpjobster_main_how_it_works' );
		$asd = get_template_directory_uri() . '/images/main_graphic.jpg';

		if ( ! empty( $opt ) ) $asd = $opt; ?>

		<style type="text/css">
			.main-how-it-works { background:url('<?php echo $asd; ?>') }
		</style>

		<?php
	endif;
}

add_action( 'wp_head', 'wpjobster_header_meta_info' );
function wpjobster_header_meta_info() {
	global $wp_query;
	global $is_profile_pg; ?>

	<meta name="jobster-license" content="<?php echo wpjobster_return_license_name(); ?>" />

	<?php if ( wpjobster_is_responsive() ) { ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php } ?>

	<?php if ( is_singular( 'job' ) ) { ?>
		<?php $job_image_url = wpj_get_attachment_image_url( wpjobster_get_first_post_image_ID( get_the_ID() ), 'job_slider_image' ); ?>
		<meta name="twitter:image" content="<?php echo $job_image_url; ?>" />
		<meta property="og:image" content="<?php echo $job_image_url; ?>" />
		<meta property="og:image:width" content="720" />
		<meta property="og:image:height" content="405" />
	<?php } ?>

	<?php if ( $is_profile_pg ) { ?>
		<?php
			$username = urldecode( $wp_query->query_vars['username'] );
			$user_object = get_user_by( 'login', $username );
			$user_avatar = wpjobster_get_avatar( $user_object->ID, 180, 180 );
		?>
		<meta name="twitter:image" content="<?php echo $user_avatar; ?>" />
		<meta property="og:image" content="<?php echo $user_avatar; ?>" />
		<meta property="og:image:width" content="180" />
		<meta property="og:image:height" content="180" />
	<?php }
}

add_filter( 'template_redirect', 'wpjobster_template_redirect' );
function wpjobster_template_redirect(){
	global $wp_query, $wp_rewrite, $post;
	global $wp;
	$my_pid = isset($post->ID)?$post->ID:'';
	$wpjobster_my_account_page_id = get_option( 'wpjobster_my_account_page_id' );
	$wpjobster_my_account_shopping_page_id = get_option( 'wpjobster_my_account_shopping_page_id' );
	$wpjobster_my_account_personal_info_page_id = get_option( 'wpjobster_my_account_personal_info_page_id' );

	if ( is_user_logged_in() ) {
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		$user_type = get_user_meta( $uid,'wpjobster_user_type', true );
	}

	if( ! ( isset( $user_type ) ) ){
		$user_type = '';
	}

	// add all page ids in this array what are allowed if phone number is still not verified
	if( $user_type == "seller" ){
		$allowed_pages_id=array( $wpjobster_my_account_page_id,$wpjobster_my_account_personal_info_page_id );
	}else{
		$allowed_pages_id=array( $wpjobster_my_account_shopping_page_id,$wpjobster_my_account_personal_info_page_id );
	}

	if( ! ( isset( $_GET['jb_action'] ) && $_GET['jb_action']=='verify_phone' ) ){ // checking current page is not sending verification code
		if( is_user_logged_in() ){
			global $current_user;
			$current_user = wp_get_current_user();
			$uid = $current_user->ID;
			$user_cell_number = get_user_meta( $uid,"cell_number" );

			$is_verify_number = get_option( 'wpjobster_verify_phone_numbers' );
			if( $is_verify_number=='yes'
				&& get_user_meta( $uid, 'uz_phone_verification', true ) != 1
				&& get_option( 'wpjobster_lock_verify_phone_numbers' )=='yes'
				&& !in_array( $my_pid,$allowed_pages_id ) )
			{

				if( $user_type == "seller" ){
					wp_redirect( get_permalink( $wpjobster_my_account_page_id ) );
				}else{
					wp_redirect( get_permalink( $wpjobster_my_account_shopping_page_id ) );
				}
			}

			$is_email_address = get_option( 'wpjobster_lock_verify_email_address' );
			if( $is_email_address=='yes' && get_option( 'wpjobster_verify_email' ) != 'no' && get_user_meta( $uid, 'uz_email_verification', true ) != 1 && ! in_array( $my_pid,$allowed_pages_id ) )
			{

				if( $user_type == "seller" ){
					wp_redirect( get_permalink( $wpjobster_my_account_page_id ) );
				}else{
					wp_redirect( get_permalink( $wpjobster_my_account_shopping_page_id ) );
				}
			}
		}
	}

	$paagee = isset( $wp_query->query_vars['my_custom_page_type'] ) ? $wp_query->query_vars['my_custom_page_type'] : "";
	$jb_action = wpjobster_get_jb_action();
	$post_parent = isset( $post->post_parent ) ? $post->post_parent : '';


	$wpjobster_post_new_page_id = get_option('wpjobster_post_new_page_id');
	$wpjobster_my_account_priv_mess_page_id = get_option('wpjobster_my_account_priv_mess_page_id');
	$wpjobster_my_account_reviews_page_id = get_option('wpjobster_my_account_reviews_page_id');
	$wpjobster_my_account_sales_page_id = get_option('wpjobster_my_account_sales_page_id');
	$wpjobster_my_account_shopping_page_id = get_option('wpjobster_my_account_shopping_page_id');
	$wpjobster_my_favorites_page_id = get_option('wpjobster_my_favorites_page_id');
	$wpjobster_my_account_payments_page_id = get_option('wpjobster_my_account_payments_page_id');

	if (isset($_GET['get_subcats_for_me'])) {
		$cat_id = $_POST['queryString'];

		if (empty($cat_id)) {
			echo " ";
		} elseif($cat_id==3){
		echo '<input type="text" name="other_subcat[]" class="subcat_txt">';
		}else {
			echo wpjobster_get_subcategories_clck(
				'subcat[]',
				$cat_id,
				'',
				__( 'Select Subcategory', 'wpjobster' ),
				'do_input styledselect uz-listen2'
			);
		}

		die();
	}

	if (isset($_GET['get_subcategories_for_me'])) {
		$cat_id = $_POST['queryString'];

		if (empty($cat_id)) {
			echo " ";
		} else {
			echo wpjobster_get_subcategories_clck(
				'job_subcategory',
				$cat_id,
				'',
				__( 'Select Subcategory', 'wpjobster' ),
				'styledselect focus-area'
			);
		}

		die();
	}

	global $wp_rewrite;

	if( $my_pid == $wpjobster_my_account_personal_info_page_id){
		wpjobster_init_uploader_scripts();
	}
	if ( $my_pid != ''
		&& ( $my_pid == $wpjobster_my_account_page_id
		|| $post_parent == $wpjobster_my_account_page_id
		|| $my_pid == $wpjobster_my_account_priv_mess_page_id
		|| $my_pid == $wpjobster_my_account_reviews_page_id
		|| $my_pid == $wpjobster_my_account_sales_page_id
		|| $my_pid == $wpjobster_my_account_shopping_page_id
		|| $my_pid == $wpjobster_my_favorites_page_id ) ) {

		if (!is_user_logged_in()) {
			wp_redirect(wp_login_url(get_current_page_url()));
			exit;
		}
	}


	if (isset($_GET['switch_grd'])) {
		$_SESSION['view_tp'] = $_GET['switch_grd'];
		wp_redirect($_GET['get_urls']);
		die();
	}


	if (isset($_GET['switch_filter'])) {
		$_SESSION['current_order'] = $_GET['switch_filter'];
		wp_redirect($_GET['get_urls']);
		die();
	}

	if (isset($_GET['posting_new'])) {
		$_SESSION['i_will'] = $_POST['i_will'];
		$_SESSION['job_cost'] = $_POST['job_cost'];
		wp_redirect(get_permalink(get_option('wpjobster_post_new_page_id')));
		die();
	}


	if ($jb_action == "pay_featured") {
		$method = $wp_query->query_vars['method'];
		include_once( get_template_directory() . '/lib/gateways/pay_listing_' . $method . '.php');
		die();
	}

	if ($jb_action == "show_bank_details") {
		get_template_part('template-parts/pages/payments/page', 'payment-bank-details');
		die();
	}


	if ($jb_action == "purchase_this") {
		get_template_part('template-parts/pages/payments/page', 'payment-purchase-this');
		die();
	}


	if ($jb_action == "purchase_this_widget") {
		include_once( get_template_directory() . '/includes/payments/payment-widgets.php');
		die();
	}

	if ($jb_action == "abort_mutual_cancelation") {
		wpj_abort_mutual_cancelation();
		die();
	}

	if ($jb_action == "answer_mutual_cancellation") {
		wpj_answer_mutual_cancellation();
		die();
	}

	if ($jb_action == "order_cancellation") {
		include_once( get_template_directory() . '/includes/job/job-order-cancellation.php');
		die();
	}


	if ($jb_action == "close_job") {
		wpj_close_job();
		die();
	}


	if ($jb_action == "edit_job") {
		wpjobster_edit_job_area_function();
		die();
	}

	if($jb_action == "edit_request"){
		get_template_part('template-parts/pages/request/page', 'request-edit');
		die();
	}


	if ($jb_action == "mark_delivered") {
		wpj_mark_delivered();
		die();
	}


	if ($jb_action == "mark_completed") {
		wpj_mark_completed();
		die();
	}

	if ($jb_action == "chat_box") {
		$orderid = isset( $_GET['oid'] ) ? $_GET['oid'] : '';
		$order_exist = wpjobster_get_order( $orderid );
		if ( $order_exist ) {
			get_template_part('template-parts/pages/payments/page', 'payment-chat-box');
		}else{
			wp_redirect( get_site_url() );
		}
		die();
	}

	if ($jb_action == "loader_page") {
		get_template_part('template-parts/pages/payments/page', 'payment-loader');
		die();
	}

	if ($jb_action == "verify_email") {
		get_template_part('template-parts/pages/user/page', 'user-email');
		die();
	}

	if ($jb_action == "verify_phone") {
		get_template_part('template-parts/pages/user/page', 'user-phone');
		die();
	}


	if (!empty($_GET['payment_response_listing'])) {
		$sk = $_GET['payment_response_listing'];
		include_once( get_template_directory() . '/lib/gateways/listing_response_' . $sk . '.php');
		die();
	}

	if ($jb_action == 'feature_job') {
		get_template_part('template-parts/pages/job/page', 'feature-job');
		die();
	}
	if ($jb_action == 'badges') {
		get_template_part('template-parts/pages/user/page', 'user-badges');
		die();
	}

	if (!empty($_GET['featured_response'])) {
		$sk = $_GET['featured_response'];
		die();
	}

	if (!empty($_GET['pay_badges'])) {
		$sk = $_GET['pay_badges'];
		include_once( get_template_directory() . '/lib/gateways/' . $sk . '-badges.php');
		die();
	}

	if( isset( $_REQUEST['custom'] ) ){
		$payment = wpj_get_payment( array(
			'id' => $_REQUEST['custom'],
		) );
		$payment_type = $payment->payment_type;
	}else{
		$payment_type = '';
	}

	if (isset($_GET['payment_response']) && $_GET['payment_response']!=''){
		$action = "process_payment";

		$sk = $_GET['payment_response'];
		if( ( isset( $_REQUEST['payment_type'] ) && $_REQUEST['payment_type']=='feature' ) || $payment_type == 'feature' ){
			$payment_type = "feature";
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['response_action'];
				}

			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_featured.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '-featured.php');
			}
			die();
		}elseif( ( isset( $_REQUEST['payment_type'] ) && $_REQUEST['payment_type']=='custom_extra' ) || $payment_type == 'custom_extra' ){
			$payment_type = "custom_extra";
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['response_action'];
				}

			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_custom_extra.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '-custom_extra.php');
			}
			die();
		}elseif (
			( isset( $_REQUEST['payment_type'] ) && $_REQUEST['payment_type']=='subscription' ) ||
			( isset( $_REQUEST['payment_type_name'] ) && $_REQUEST['payment_type_name']=='subscription' ) ||
			$payment_type == 'subscription'
		) {
			$payment_type = "subscription";
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['response_action'];
				}
			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_subscription.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
			die();
		}elseif( ( isset( $_REQUEST['payment_type'] ) && $_REQUEST['payment_type']=='topup' ) || $payment_type == 'topup' ){
			$payment_type = "topup";
			include_once( get_template_directory() . '/lib/gateways/common.php');

			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['response_action']) ){
					$response_action = $gateway['response_action'];
				}
			}

			if($response_action!=''){
				$process_action=$response_action;
				include_once( get_template_directory() . '/lib/gateways/user_credit.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
			die();
		}elseif(((isset($_REQUEST['payment_type']) && $_REQUEST['payment_type']=='job_purchase' ) || $payment_type == 'job_purchase') || $sk!='paypal'){
			$payment_type = "job_purchase";
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';

			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['response_action']) ){
					$response_action = $gateway['response_action'];
				}
			}
			if($response_action!=''){
				$process_action=$response_action;
				include_once( get_template_directory() . '/lib/gateways/user_job_purchase.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
			die();
		}else{
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				$res_str= $gateway['unique_id']."_response";
				if(($sk == $res_str ||$sk ==  $gateway['unique_id'] ) && isset($gateway['response_action']) ){
					$response_action = $gateway['response_action'];
				}
			}
			if($response_action!=''){
				$process_action=$response_action;
				global $wcjp,$wct,$wcf;
				if(!class_exists('wpjobster_common_featured')){
					include_once get_template_directory()."/lib/gateways/wpjobster_common_featured.php";
					$wcf = new WPJ_Common_Featured($sk);
				}
				if(!class_exists('wpjobster_common_custom_extra')){
					include_once get_template_directory()."/lib/gateways/wpjobster_common_custom_extra.php";
					$wcf = new WPJ_Common_Custom_Extra($sk);
				}
				if(!class_exists('wpjobster_common_topup')){
					include_once get_template_directory()."/lib/gateways/".'wpjobster_common_topup.php';
					$wct = new WPJ_Common_Topup($sk);
				}
				if(!class_exists('wpjobster_common_job_purchase')){
					include_once get_template_directory()."/lib/gateways/wpjobster_common_job_purchase.php";
					$wcjp = new WPJ_Common_Job_Purchase($sk);
				}
				if(!class_exists('wpjobster_subscription')){
					include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
					$wcs = new wpjobster_subscription( $sk );
				}
				if(!class_exists('wpjobster_'.$sk) && file_exists(get_template_directory()."/lib/gateways/".'wpjobster_'.$sk.'.php')){
					if ( $sk == 'paypal' ) {
						if ( class_exists( 'WPJobster_PayPal_Loader' ) ) {
							$paypalClass = new WPJobster_PayPal_Loader();
						}
					} else {
						include_once get_template_directory()."/lib/gateways/".'wpjobster_'.$sk.'.php';
					}
				}
				do_action($process_action,'','');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}

		}
	}

	if (!empty($_GET['pay_for_item'])) {
		$sk = $_GET['pay_for_item'];
		$action = "payment";
		if(isset($_REQUEST['payment_type']) && $_REQUEST['payment_type']=='feature'){
			$payment_type = 'feature';
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			if($_GET['pay_for_item']=='credits'){
				$response_action = $process_action = 'show_credit_form';
			}
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				if(($sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['action'];
				}
			}

			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_featured.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '-featured.php');
			}
			die();
		}
		if(isset($_REQUEST['payment_type']) && $_REQUEST['payment_type']=='custom_extra'){
			$payment_type = 'custom_extra';
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			if($_GET['pay_for_item']=='credits'){
				$response_action = $process_action = 'show_credit_form';
			}
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				if(($sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['action'];
				}
			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_custom_extra.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '-custom_extra.php');
			}
			die();
		}
		if(isset($_REQUEST['payment_type']) && $_REQUEST['payment_type']=='subscription'){
			$payment_type = 'subscription';
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$response_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				if(($sk ==  $gateway['unique_id'] ) && isset($gateway['action']) ){
					$process_action = $gateway['action'];
				}
			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_subscription.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
			die();
		}
		if(isset($_REQUEST['payment_type']) && $_REQUEST['payment_type']=='topup')
		{
			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$process_action = '';
			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				if($sk == $gateway['unique_id'] &&  isset($gateway['action']) && $gateway['action']!='' ){
					$process_action = $gateway['action'];
				}
			}
			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_credit.php');

			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
		}else{
			if(!isset($_REQUEST['payment_type'])){
				$_REQUEST['payment_type'] =$payment_type = 'job_purchase';
			}else{
				$payment_type = $_REQUEST['payment_type'];
			}

			include_once( get_template_directory() . '/lib/gateways/common.php');
			$wpjobster_payment_gateways = get_wpjobster_payment_gateways();
			$process_action = '';

			if($_GET['pay_for_item']=='credits'){
				$process_action = 'show_credit_form';
			}

			foreach($wpjobster_payment_gateways as $order_index=>$gateway){
				if($sk == $gateway['unique_id'] &&  isset($gateway['action']) && $gateway['action']!='' ){
					$process_action = $gateway['action'];
				}
			}

			if($process_action!=''){
				include_once( get_template_directory() . '/lib/gateways/user_job_purchase.php');
			}else{
				include_once( get_template_directory() . '/lib/gateways/' . $sk . '.php');
			}
		}
		die();
	}

	if (!empty($_GET['topup_response'])) {
		$sk = $_GET['topup_response'];
		die();
	}


	if (!empty($_GET['topup_for_item'])) {
		die();
	}

	// check if logged in when access the post new page
	$vc_inline = function_exists('wpj_vc_is_inline') ? wpj_vc_is_inline() : vc_is_inline();

	if( ! $vc_inline ){
		if ( $my_pid == $wpjobster_post_new_page_id ) {

			wpjobster_init_uploader_scripts();

			if ( ! is_user_logged_in() ) {
				wp_redirect( wp_login_url( get_permalink() ) );
				exit;
			}

			if ( ! isset( $_GET['jobid'] ) ) {
				$set_ad = 1;
			} else {
				$set_ad = 0;
			}

			global $current_user;
			$current_user = wp_get_current_user();

			if ( $set_ad == 1 ) {
				$pid = wpjobster_get_auto_draft( $current_user->ID );
				update_post_meta( $pid, 'home_featured_now', "z" );
				update_post_meta( $pid, 'category_featured_now', "z" );
				update_post_meta( $pid, 'subcategory_featured_now', "z" );
				wp_redirect( wpjobster_post_new_with_pid_stuff_thg( $pid ) );
				exit;
			}

			wpjobster_post_new_post_area_function();
		}

		// check if logged in when accessing the my account page

		if ($my_pid == $wpjobster_my_account_page_id) {

			if (!is_user_logged_in()) {
				wp_redirect(wp_login_url(get_permalink()));
				exit;
			}

		}
	}
}

add_action('widgets_init', 'wpjobster_framework_init_widgets');
function wpjobster_framework_init_widgets(){
	register_sidebar(
		array(
			'name' => __('wpjobster - Page Sidebar', 'wpjobster'),
			'id' => 'page-widgets-area',
			'description' => __('This sidebar is placed on the default pages.', 'wpjobster'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => __('wpjobster - Job Single', 'wpjobster'),
			'id' => 'single-job-widgets-area',
			'description' => __('This sidebar is placed on the single job on the right bottom side.', 'wpjobster'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => __('wpjobster - Categories Top', 'wpjobster', 'wpjobster'),
			'id' => 'category-top-widgets-area',
			'description' => __('This sidebar is placed on the top side of the category pages.', 'wpjobster'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	register_sidebar(
		array(
			'name' => __('wpjobster - Categories Bottom', 'wpjobster', 'wpjobster'),
			'id' => 'category-bottom-widgets-area',
			'description' => __('This sidebar is placed on the bottom side of the category pages.', 'wpjobster'),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
}

add_action('init', 'wpjobster_register_my_menus');
function wpjobster_register_my_menus(){
	register_nav_menu('wpjobster_header_main_menu', 'Header Main Menu');
	register_nav_menu('wpjobster_header_secondary_menu', 'Header Secondary Menu');
	register_nav_menu('wpjobster_header_user_dropdown_extra', 'Header User Dropdown Extra');
	register_nav_menu('wpjobster_header_user_account_menu', 'Header User Account Menu');
	register_nav_menu('wpjobster_responsive_main_menu', 'Responsive Main Menu');
	register_nav_menu('wpjobster_responsive_secondary_menu', 'Responsive Secondary Menu');
}

add_filter( 'wp_nav_menu_items', 'add_menuclass_active', 10, 2 );
function add_menuclass_active( $nav_menu, $args ) {
	if ( in_array( $args->theme_location, array(
			'wpjobster_responsive_main_menu',
			'wpjobster_responsive_secondary_menu',
	) ) ) {
		return preg_replace( '/<a /', '<a class="item"', $nav_menu );
	} else {
		return $nav_menu;
	}
}

add_action('wp_enqueue_scripts', 'wpjobster_add_theme_styles');
function wpjobster_add_theme_styles(){
	global $wp_query;
	global $wp_styles, $wp_scripts;
	$current_user = wp_get_current_user();
	wp_enqueue_style( 'wpj-reset', get_template_directory_uri() . '/css/wpj-reset.css', array(), wpjobster_VERSION, 'all' );
	wp_enqueue_style( 'owlcarousel', get_template_directory_uri() . '/vendor/owlcarousel2/assets/owl.carousel.css', array(), '2.2.1', 'all' );
	wp_enqueue_style( 'owlthemedefault', get_template_directory_uri() . '/vendor/owlcarousel2/assets/owl.theme.default.min.css', array(), '2.2.1', 'all' );
	wp_register_style( 'jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css', array(), '1.11.1', 'all' );
	wp_register_style( 'jplayer-flat-css', get_template_directory_uri() . '/css/flat.audio.css' );
	wp_enqueue_style( 'tagsinput-css', get_template_directory_uri() . '/css/jquery.tagsinput.css' );
	wp_enqueue_style( 'phone-css', get_template_directory_uri() . '/css/intlTelInput.css' );
	wp_enqueue_script( 'server-date', get_template_directory_uri(). '/js/server_date.js', array('jquery'), wpjobster_VERSION, true );
	wp_enqueue_style( 'fliptimer-css', get_template_directory_uri() . '/css/flipTimer.css' ); //Counter CSS

	$upload_dir = wp_upload_dir();
	$semantic_path = $upload_dir['basedir'] . '/wpjobster/semantic.css';
	$semantic_dir = $upload_dir['baseurl'] . '/wpjobster/semantic.css';
	if ( file_exists( $semantic_path ) ) {
		wp_enqueue_style( 'semantic-ui-css', $semantic_dir, array(), get_option( 'wpj_last_css_compiled' ) ); // Semantic UI CSS
	} else {
		wp_enqueue_style( 'semantic-ui-css', get_template_directory_uri() . '/vendor/semantic-ui/semantic.min.css', array(), '2.2.12' ); // Semantic UI CSS
	}
	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-semantic-ui-css', get_template_directory_uri() . '/vendor/semantic-ui/semantic-rtl-autogenerated.css', array(), '2.2.12' ); // Semantic UI CSS
	}

	wp_enqueue_style( 'semantic-ui-calendar-css', get_template_directory_uri() . '/vendor/semantic-ui-calendar/calendar.min.css', array(), '0.0.6' ); // Semantic UI Calendar CSS
	wp_enqueue_script( 'semantic-ui-js', get_template_directory_uri() . '/vendor/semantic-ui/semantic.min.js', array( 'jquery' ), '2.2.12', true ); // Semantic UI JS
	wp_enqueue_script( 'semantic-ui-calendar-js', get_template_directory_uri() . '/vendor/semantic-ui-calendar/calendar.min.js', array( 'jquery' ), '0.0.6', true ); // Semantic min JS
	wp_enqueue_script( 'jqueryhoverintent', get_template_directory_uri() . '/js/jquery.hoverIntent.minified.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_enqueue_script( 'jq-timer', get_template_directory_uri() . '/js/jquery.flipTimer.js', array( 'jquery' ), wpjobster_VERSION, true );

	$is_page_pm = is_page( get_option('wpjobster_my_account_priv_mess_page_id') );
	wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/wpjobster/custom.js', array( 'jquery' ), wpjobster_VERSION, true ); // Custom JS
	wp_localize_script( 'custom-js', 'base_main2', array(
		'current_user'      => $current_user->user_login,
		'theme_path'        => get_site_url(),
		'ajax_url'          => admin_url( 'admin-ajax.php' ),
		'header_fixed'      => get_theme_mod('header_fixed', true),
		'paused'            => __( 'paused', 'wpjobster' ),
		'published'         => __( 'published', 'wpjobster' ),
		'activate'          => __( 'Activate', 'wpjobster' ),
		'deactivate'        => __( 'Deactivate', 'wpjobster' ),
		'msg_err'           => __( 'Please write something in the text field.', 'wpjobster' ),
		'amount_err'        => __( 'Please type an amount of money.', 'wpjobster' ),
		'deliv_err'         => __( 'Please type delivery days.', 'wpjobster' ),
		'price_min'         => get_option( 'wpjobster_offer_price_min' ),
		'price_min_err'     => sprintf( __('Money amount must be bigger than %s', 'wpjobster' ), wpjobster_get_show_price_classic( get_option( 'wpjobster_offer_price_min' ) ) ),
		'price_max'         => get_option( 'wpjobster_offer_price_max' ),
		'price_max_err'     => sprintf( __('Maximum money amount is %s', 'wpjobster' ), wpjobster_get_show_price_classic( get_option( 'wpjobster_offer_price_max' ) ) ),
		'deliv_max'         => get_option( 'wpjobster_request_max_delivery_days' ),
		'deliv_min_err'     => __('Delivery days must be greater than or equal to 1', 'wpjobster' ),
		'deliv_max_err'     => sprintf( __('Delivery days must be less than or equal to %s', 'wpjobster' ), get_option( 'wpjobster_request_max_delivery_days' ) ),
		'is_page_chatbox'   => ( isset ( $_GET['jb_action'] ) && $_GET['jb_action'] == 'chat_box' ) ? 1 : 0,
		'is_page_pm'        => ( $is_page_pm && ! isset( $_GET['username'] ) ) ? $is_page_pm : 0,
		'is_page_pm_single' => ( isset ( $_GET['username'] ) && $is_page_pm ) ? 1 : 0,
	));

	wp_enqueue_script( 'modals-js', get_template_directory_uri() . '/js/wpjobster/modals.js', array( 'jquery' ), wpjobster_VERSION, true ); // Modals JS
	wp_localize_script( 'modals-js', 'modals', array(
		'starting_day' 			  => get_option( 'start_of_week' ),
		'current_user'            => $current_user->user_login,
		'theme_path'              => get_site_url(),
		'ajax_url'                => admin_url( 'admin-ajax.php' ),
		'_ajax_nonce'             => wp_create_nonce( 'modals' ),
		'err'                     => __( 'Error', 'wpjobster' ),
		'err_unknown'             => __( 'Unknown error', 'wpjobster' ),
		'err_something_wrong'     => __( 'Something went wrong.', 'wpjobster' ),
		'err_try_again'           => __( 'Try again', 'wpjobster' ),
		'err_try_again_later'     => __( 'Please try again later.', 'wpjobster' ),
		'err_small_end_date'      => __( 'Please select a date in the future.', 'wpjobster' ),
		'err_empty_end_date'      => __( 'Please select the end date.', 'wpjobster' ),
		'err_already_in_vacation' => __( 'You already have vacation mode active.', 'wpjobster' ),
		'success'                 => __( 'Success', 'wpjobster' ),
		'success_saved'           => __( 'Your settings have been saved.', 'wpjobster' ),
		'days' => array(
			'sun' => _x( 'S', 'Abbreviation of: Sunday', 'wpjobster' ),
			'mon' => _x( 'M', 'Abbreviation of: Monday', 'wpjobster' ),
			'tue' => _x( 'T', 'Abbreviation of: Tuesday', 'wpjobster' ),
			'wed' => _x( 'W', 'Abbreviation of: Wednesday', 'wpjobster' ),
			'thu' => _x( 'T', 'Abbreviation of: Thursday', 'wpjobster' ),
			'fri' => _x( 'F', 'Abbreviation of: Friday', 'wpjobster' ),
			'sat' => _x( 'S', 'Abbreviation of: Saturday', 'wpjobster' ),
		),
		'monthsShort' => array(
			'jan' => _x( 'Jan', 'Abbreviation of: January', 'wpjobster' ),
			'feb' => _x( 'Feb', 'Abbreviation of: February', 'wpjobster' ),
			'mar' => _x( 'Mar', 'Abbreviation of: March', 'wpjobster' ),
			'apr' => _x( 'Apr', 'Abbreviation of: April', 'wpjobster' ),
			'may' => _x( 'May', 'Abbreviation of: May', 'wpjobster' ),
			'jun' => _x( 'Jun', 'Abbreviation of: June', 'wpjobster' ),
			'jul' => _x( 'Jul', 'Abbreviation of: July', 'wpjobster' ),
			'aug' => _x( 'Aug', 'Abbreviation of: August', 'wpjobster' ),
			'sep' => _x( 'Sep', 'Abbreviation of: September', 'wpjobster' ),
			'oct' => _x( 'Oct', 'Abbreviation of: October', 'wpjobster' ),
			'nov' => _x( 'Nov', 'Abbreviation of: November', 'wpjobster' ),
			'dec' => _x( 'Dec', 'Abbreviation of: December', 'wpjobster' ),
		),
		'months' => array(
			'jan' => _x( 'January', 'Full name of: January', 'wpjobster' ),
			'feb' => _x( 'February', 'Full name of: February', 'wpjobster' ),
			'mar' => _x( 'March', 'Full name of: March', 'wpjobster' ),
			'apr' => _x( 'April', 'Full name of: April', 'wpjobster' ),
			'may' => _x( 'May', 'Full name of: May', 'wpjobster' ),
			'jun' => _x( 'June', 'Full name of: June', 'wpjobster' ),
			'jul' => _x( 'July', 'Full name of: July', 'wpjobster' ),
			'aug' => _x( 'August', 'Full name of: August', 'wpjobster' ),
			'sep' => _x( 'September', 'Full name of: September', 'wpjobster' ),
			'oct' => _x( 'October', 'Full name of: October', 'wpjobster' ),
			'nov' => _x( 'November', 'Full name of: November', 'wpjobster' ),
			'dec' => _x( 'December', 'Full name of: December', 'wpjobster' ),
		),
		'today' => __( 'Today', 'wpjobster' ),
		'now'   => __( 'Now', 'wpjobster' ),
		'am'    => __( 'AM', 'wpjobster' ),
		'pm'    => __( 'PM', 'wpjobster' ),
	));

	wp_enqueue_script( 'owlcarouseljs', get_template_directory_uri() . '/vendor/owlcarousel2/owl.carousel.min.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'tagsinputjs', get_template_directory_uri() . '/js/jquery.tagsinput.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_enqueue_script( 'jquery-mousewheel', get_template_directory_uri() . '/js/jquery-mousewheel.min.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_enqueue_script( 'modernizr-touch', get_template_directory_uri() . '/js/modernizr.touch.js', array( 'jquery' ), '1.00', true );
	wp_enqueue_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', array( 'jquery' ), '1.41', true );
	wp_enqueue_script( 'jplayer', get_template_directory_uri() . '/js/jquery.jplayer.js', array( 'jquery' ), '1.41', true );
	wp_enqueue_script( 'jplayerflat', get_template_directory_uri() . '/js/flat.audio.js', array( 'jquery' ), '1.41', true );

	$wpjobster_location = get_option('wpjobster_location');
	if( $wpjobster_location == "yes" ){
		$wpjobster_google_maps_api_key = get_option( 'wpjobster_google_maps_api_key' );
		if ( $wpjobster_google_maps_api_key != '' ) {
			$maps_key_url = 'key=' . $wpjobster_google_maps_api_key . '&';
		} else {
			$maps_key_url = '';
		}
		wp_enqueue_script( 'maps-api', 'https://maps.googleapis.com/maps/api/js?' . $maps_key_url . 'v=3.exp&libraries=places', array('jquery'), false, true );
	}
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_enqueue_script( 'phone-scripts', get_template_directory_uri(). '/js/intlTelInput.js', array(), false, true );
	wp_enqueue_script( 'main-js-scripts', get_template_directory_uri(). '/js/wpjobster/main.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_localize_script( 'main-js-scripts', 'base_main', array(
		'theme_path'        => get_template_directory_uri(),
		'ajaxurl'           => admin_url( 'admin-ajax.php' ),
		'rating'            => __( 'Rating:', 'wpjobster' ),
		'completed_jobs'    => __( 'Completed Jobs:', 'wpjobster' ),
		'registered'        => __( 'Registered:', 'wpjobster' ),
		'companylbl'        => __( 'Company:', 'wpjobster' ),
		'contact'           => __( 'Contact', 'wpjobster' ),
		'nothing_found'     => __( 'No results found.', 'wpjobster' ),
		'posts_per_page'    => ( get_option('posts_per_page' ) ) ? get_option( 'posts_per_page' ) : '12',
		'check_all_email'   => __( 'Check all', 'wpjobster' ),
		'uncheck_all_email' => __( 'Uncheck all', 'wpjobster' ),
		'check_all_sms'     => __( 'Check all', 'wpjobster' ),
		'uncheck_all_sms'   => __( 'Uncheck all', 'wpjobster' ),
		'youtube_error'     => __( 'The youtube link is invalid!', 'wpjobster' ),
	));
	if ( wpjobster_live_notifications_enabled() ) {
		$is_page_pm = is_page( get_option( 'wpjobster_my_account_priv_mess_page_id' ) );

		wp_enqueue_script( 'live-notifications', get_template_directory_uri(). '/js/wpjobster/live-notifications.js', array( 'jquery' ), wpjobster_VERSION, true );
		wp_localize_script( 'live-notifications', 'live_notifications',
			array(
				'theme_path'        => get_template_directory_uri(),
				'light_ajax_url'    => get_light_ajax_url(),
				'is_page_pm'        => ( $is_page_pm && ! isset( $_GET['username'] ) ) ? $is_page_pm : 0,
				'is_page_pm_single' => ( isset ( $_GET['username'] ) && $is_page_pm ) ? 1 : 0,
				'is_page_chatbox'   => ( isset ( $_GET['jb_action'] ) && $_GET['jb_action'] == 'chat_box' ) ? 1 : 0
			)
		);
	}
	// localized script.js
	wp_enqueue_script( 'script', get_template_directory_uri() . '/script.js', array( 'jquery' ), wpjobster_VERSION, true );

	global $post;
	if ( is_page ( get_option( 'wpjobster_advanced_search_request_page_id' ) ) || ( isset( $post->post_type ) && $post->post_type == 'request' ) ) {
		$max_default_days = get_option( 'wpjobster_request_max_delivery_days' );
	} else {
		$max_default_days = get_option( 'wpjobster_job_max_delivery_days' );
	}

	wp_localize_script('script', 'script_vars',
		array(
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'day'              => __( 'day', 'wpjobster' ),
			'days'             => __( 'days', 'wpjobster' ),
			'max_default_days' => $max_default_days,
			'is_rtl'           => is_rtl() ? 'true' : 'false',
			'current_currency' => wpjobster_get_currency(),
			'is_payoneer'      => ( class_exists( 'WPJobster_Payoneer_Loader' ) && get_option( 'wpjobster_payoneer_enable' ) == 'yes' ) ? true : false,
		)
	);
	// localized my-script.js
	wp_enqueue_script( 'my-script', get_template_directory_uri() . '/js/wpjobster/my-script.js', array( 'jquery' ), wpjobster_VERSION, true );

	if( get_option( 'wpjobster_admin_approve_request' ) == 'yes' ){
		$my_request_url = get_permalink( get_option( 'wpjobster_my_requests_page_id' ) ) . 'in_review';
	} else {
		$my_request_url = get_permalink( get_option( 'wpjobster_my_requests_page_id' ) );
	}

	wp_localize_script( 'my-script', 'my_script_vars',
		array(
			'scroll_up'      => __( 'Scroll up to see the error.', 'wpjobster' ),
			'my_request_url' => $my_request_url,
			'homeUrl'        => get_site_URL()
		)
	);
	// ^^ no need atm
	wp_enqueue_script( 'search-autocomplete', get_template_directory_uri() . '/js/wpjobster/search-autocomplete.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_localize_script( 'search-autocomplete', 'search_autocomplete',
		array(
			'theme_path'          => get_template_directory_uri(),
			'ajaxurl'             => admin_url( 'admin-ajax.php' ),
			'search_jobs_url'     => get_permalink( get_option( 'wpjobster_advanced_search_id' ) ),
			'search_requests_url' => get_permalink( get_option( 'wpjobster_advanced_search_request_page_id' ) ),
			'search_users_url'    => get_permalink( get_option( 'wpjobster_search_user_page_id' ) ),
			'user_profile_url'    => wpj_get_user_profile_link(),
			'jobs_label'          => __( 'Jobs', 'wpjobster' ),
			'requests_label'      => __( 'Requests', 'wpjobster' ),
			'users_label'         => __( 'Users', 'wpjobster' ),
			'search_users_label'  => __( 'Search users containing', 'wpjobster' ),
			'allow_job'           => get_option( 'wpjobster_enable_jobs_for_advanced_search' ),
			'allow_request'       => get_option( 'wpjobster_enable_requests_for_advanced_search' ),
			'allow_users'         => get_option( 'wpjobster_enable_users_for_advanced_search' )
		)
	);
	wp_enqueue_script( 'jqueryhoverintent' );
	wp_enqueue_style( 'jplayer-flat-css' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-effects-slide' );
	wp_enqueue_style( 'jquery-ui-css' );
	wp_enqueue_style( 'datetimepicker-style' );
	wp_enqueue_script( 'chartjs', get_template_directory_uri() . '/js/google_charts_loader.js', array(), wpjobster_VERSION, true );

	// Lazy Loading images
	// https://github.com/toddmotto/echo
	if(get_option( 'wpjobster_enable_lazy_loading' ) == 'yes' ){
		wp_enqueue_script( 'echo-lazy-loading', get_template_directory_uri() . '/js/echo.min.js', array(), '1.7.3', true );
	}
	// https://github.com/malihu/malihu-custom-scrollbar-plugin
	wp_enqueue_style( 'm-custom-scrollbar-css', get_template_directory_uri() . '/css/jquery.mCustomScrollbar.css', array(), '3.1.3' );
	wp_enqueue_script( 'm-custom-scrollbar', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'), '3.1.3', true );
	// WYSIWYG HTML Editor
	// https://github.com/xing/wysihtml5
	// https://github.com/Voog/wysihtml
	wp_register_script( 'wysihtml5', get_template_directory_uri() . '/vendor/wysihtml5/wysihtml5.min.js', array(), '0.4.0pre', false );
	wp_register_style( 'wysihtml5-style', get_template_directory_uri() . '/css/wysiwyg.css' );
	if ( wpjobster_get_jb_action() == "edit_job" || wpjobster_parameter_exist( 'jobid','yes' ) || is_page( get_option( 'wpjobster_my_account_personal_info_page_id' ) ) ) {
		wp_enqueue_script( 'wysihtml5' );
		wp_enqueue_style( 'wysihtml5-style' );
	}
}

add_action('wp_head', 'wpjobster_add_theme_responsive_styles', 99);
function wpjobster_add_theme_responsive_styles(){
	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-responsive-main-stylesheet', get_template_directory_uri() . '/responsive-rtl.css?ver='.wpjobster_VERSION );
	}
}

add_action( 'wp_enqueue_scripts', 'wpjobster_add_theme_styles_last', 11 );
function wpjobster_add_theme_styles_last() {
	wp_enqueue_style( 'semantic-ui-custom-css', get_template_directory_uri() . '/css/semantic-custom.css' ); // Semantic UI Custom CSS
	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-semantic-ui-custom-css', get_template_directory_uri() . '/css/semantic-custom-rtl-autogenerated.css' ); // Semantic UI Custom CSS
	}

	wp_enqueue_style( 'semantic-style-css', get_template_directory_uri() . '/css/semantic-style.css' ); // Semantic UI Custom CSS
	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-semantic-style-css', get_template_directory_uri() . '/css/semantic-style-rtl-autogenerated.css' ); // Semantic UI Custom CSS
	}

	wp_enqueue_style( 'main-stylesheet', get_stylesheet_uri() . '?ver='.wpjobster_VERSION );

	if ( wpjobster_is_responsive() ) {
		wp_enqueue_style('responsive-main-stylesheet', get_template_directory_uri().'/responsive.css?ver='.wpjobster_VERSION);
	}
}

add_action( 'wp_print_styles', 'load_fonts' );
function load_fonts() {
	wp_register_style( 'googleFonts', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin-ext,latin' );
	wp_enqueue_style( 'googleFonts' );
}

function wpjobster_add_uploadifive_scripts() {
	// localized jquery.uploadifive.js
	wp_enqueue_script( 'uploadifive-js', get_template_directory_uri() . '/lib/uploadifive/jquery.uploadifive.js', array( 'jquery' ), '1.3', true );
	wp_localize_script( 'uploadifive-js', 'uploadifive_vars',
		array(
			'finished'                         => __( 'Finished', 'wpjobster' ),
			'cancelled'                        => __( 'Cancelled', 'wpjobster' ),
			'unknown_error'                    => __( 'Unknown Error', 'wpjobster' ),
			'invalid_file_type'                => __( 'Invalid file type.', 'wpjobster' ),
			'error_404'                        => __( '404 Error', 'wpjobster' ),
			'error_403'                        => __( '403 Forbidden', 'wpjobster' ),
			'forbidden_file_type'              => __( 'Forbidden file type', 'wpjobster' ),
			'maximum_file_size_exceeded'       => __( 'Maximum file size exceeded', 'wpjobster' ),
			'maximum_number_of_files_exceeded' => __( 'Maximum number of files exceeded.', 'wpjobster '),
			'allowed_mime_types'               => json_encode( get_option( 'wpjobster_allowed_mime_types' ) )
		)
	);
	wp_enqueue_style( 'uploadifive-css', get_template_directory_uri() . '/lib/uploadifive/uploadifive.css' );
}

function wpjobster_add_chatbox_scripts() {
	wp_enqueue_script( 'chatbox-js', get_template_directory_uri(). '/js/wpjobster/chatbox.js', array( 'jquery' ), wpjobster_VERSION, true );
	wp_localize_script('chatbox-js', 'chatbox_vars',
		array(
			'theme_path'  => get_template_directory_uri(),
			'blog_url'    => get_bloginfo( 'url' ),
			'ajaxurl'     => admin_url( 'admin-ajax.php' ),
			'live_notify' => get_option( 'wpjobster_enable_live_notifications' )
		)
	);
}

//--------------------------------------
// Is Responsive Condition
//--------------------------------------

function wpjobster_is_responsive() {
	if ( get_option('wpjobster_enable_responsive') == 'yes' ) {
		return true;
	}
	return false;
}

add_action( 'generate_rewrite_rules', 'wpjobster_rewrite_rules' );
function wpjobster_rewrite_rules( $wp_rewrite ){
	if ( ! is_admin() ){
		global $category_url_link, $location_url_link;
		$user_profile_id = get_option( 'wpjobster_user_profile_page_id' );

		if ( ! empty( $user_profile_id ) ) {
			$post = get_post( $user_profile_id );
			$user_profile_slug = $post->post_name;
		} else {
			$user_profile_slug = 'user-profile';
		}

		$pages_id = array(
			get_option( 'wpjobster_my_requests_page_id' ),
			get_option( 'wpjobster_my_account_personal_info_page_id' ),
			get_option( 'wpjobster_my_account_payments_page_id' ),
			get_option( 'wpjobster_my_account_shopping_page_id' ),
			get_option( 'wpjobster_my_account_sales_page_id' ),
			get_option( 'wpjobster_my_account_reviews_page_id' ),
		);

		$post_page_my_account = get_post( get_option( 'wpjobster_my_account_page_id' ) );
		$my_account_slug = $post_page_my_account->post_name;

		$post_page_my_request = get_post( get_option( 'wpjobster_my_requests_page_id' ) );
		$my_request_slug = $post_page_my_request->post_name;

		foreach ($pages_id as $page) {
			$post_page = get_post( $page );

			if ( $post_page->post_name == $my_request_slug ) {
				$post_slug = $post_page->post_name;
			} else {
				$post_slug = $my_account_slug . '/' . $post_page->post_name;
			}

			$new_pages_rules[$post_slug . '/([^/]+)/?$'] = 'index.php?pagename='.$post_slug.'&pg=' . $wp_rewrite->preg_index(1);
		}

		$my_account_page = $_SERVER['REQUEST_URI'];
		$count = explode( '/', $my_account_page );

		if( $count && ! empty( $count[1] ) ) {
			if( end( $count ) == '' ){
				$compare = $count[count( $count )-3].'/'.$count[count( $count )-2];
			}else{
				$compare = $count[count( $count )-2].'/'.$count[count( $count )-1];
			}
			if( $compare == $my_account_slug . '/active' || $compare == $my_account_slug . '/inactive' || $compare == $my_account_slug . '/under-review' || $compare == $my_account_slug . '/rejected' ) {
				$my_account[$my_account_slug . '/([^/]+)/?$'] = 'index.php?pagename='.$my_account_slug.'&pg=' . $wp_rewrite->preg_index(1);
			} else {
				$my_account[] = '';
			}
		} else {
			$my_account[] = '';
		}

		$new_rules = array(
			'page/([^/]+)/?$' => 'index.php?paged=' . $wp_rewrite->preg_index(1),

			$user_profile_slug . '/([^/]+)/?$' => 'index.php?pagename='.$user_profile_slug.'&username=' . $wp_rewrite->preg_index(1),

			'jobs/([^/]+)/([^/]+)/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?jb_action=jobs_total&job_sort=' . $wp_rewrite->preg_index(3) . '&job_category=' . $wp_rewrite->preg_index(2) . '&job_tax=' . $wp_rewrite->preg_index(1) . '&page=' . $wp_rewrite->preg_index(4),
			'jobs/([^/]+)/([^/]+)/([^/]+)/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?jb_action=jobs_total&job_sort=' . $wp_rewrite->preg_index(3) . '&job_category=' . $wp_rewrite->preg_index(2) . '&job_tax=' . $wp_rewrite->preg_index(1) . '&page=' . $wp_rewrite->preg_index(5) . '&term_search=' . $wp_rewrite->preg_index(4), $category_url_link . '/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?job_cat=' . $wp_rewrite->preg_index(1) . "&feed=" . $wp_rewrite->preg_index(2),
			$category_url_link . '/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?job_cat=' . $wp_rewrite->preg_index(1) . "&feed=" . $wp_rewrite->preg_index(2),
			$category_url_link . '/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?job_cat=' . $wp_rewrite->preg_index(1) . "&paged=" . $wp_rewrite->preg_index(2),
			$category_url_link . '/([^/]+)/?$' => 'index.php?job_cat=' . $wp_rewrite->preg_index(1)
		);
		$wp_rewrite->rules = $new_rules + $new_pages_rules + $my_account + $wp_rewrite->rules;
	} else {
		return $wp_rewrite;
	}
}

function wpj_globals(){
	global $default_search;
	$default_search = __("Begin to search by typing here...", 'wpjobster');

	global $allowed_files_in_conversation;
	$allowed_files_in_conversation = get_option( 'wpjobster_allowed_mime_types' );

	global $current_theme_locale_name;
	$current_theme_locale_name = 'wpjobster';

	global $default_search;
	$default_search = __("Begin to search by typing here...", 'wpjobster');

	global $category_url_link, $location_url_link, $jobs_url_thing;
	$category_url_link = get_option("wpjobster_category_permalink");
	$location_url_link = get_option("wpjobster_location_permalink");
	$jobs_url_thing    = get_option("wpjobster_jobs_permalink");

	if (empty($category_url_link)) $category_url_link = 'section';
	if (empty($location_url_link)) $location_url_link = 'location';
	if (empty($jobs_url_thing)) $jobs_url_thing       = 'jobs';
}

add_action('wp_head', 'wpjobster_colorbox_stuff');
function wpjobster_colorbox_stuff(){
	?>
	<script>
		var $ = jQuery;
		jQuery(document).ready(function(){

			jQuery("#report-this-link").click( function() {
				if(jQuery("#report-this").css('display') == 'none')
				jQuery("#report-this").show('slow');
				else
				jQuery("#report-this").hide('slow');
				return false;
			});

			jQuery("#contact_seller-link").click( function() {
				if(jQuery("#contact-seller").css('display') == 'none')
				jQuery("#contact-seller").show('slow');
				else
				jQuery("#contact-seller").hide('slow');
				return false;
			});

			jQuery('.like_this_job').click(function() {
				var pid = jQuery(this).attr('rel');
				jQuery.ajax({
						type: "POST",
						url: "<?php echo get_bloginfo('url'); ?>/",
						data: "like_this_job=" + pid ,
						success: function(msg){
							jQuery("#lk-stuff" + pid).html('<a href="#" class="unlike_this_job" rel="'+pid+'"><?php echo addslashes(__("Unlike this Job", "wpjobster")); ?></a>');
						}
				});

				return false;
			});

			jQuery('.unlike_this_job').click(function() {
				var pid = jQuery(this).attr('rel');
				jQuery.ajax({
						type: "POST",
						url: "<?php echo get_bloginfo('url'); ?>/",
						data: "unlike_this_job=" + pid ,
						success: function(msg){
							jQuery("#lk-stuff" + pid).html('<a href="#" class="like_this_job" rel="'+pid+'"><?php echo addslashes(__("Like this Job", "wpjobster")); ?></a>');
						}
				});

				return false;
			});
		});
	</script>

<?php }

// Hide private files from media library
add_filter( 'ajax_query_attachments_args', function( $args ) {
	$args['meta_query'] = array( 'relation' => 'AND',
		array( 'key' => 'pm_id', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'message_id', 'compare' => 'NOT EXISTS' ),
		array( 'key' => 'job_id', 'compare' => 'NOT EXISTS' )
	);
	return $args;
} );
