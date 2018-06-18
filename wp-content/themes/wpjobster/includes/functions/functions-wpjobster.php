<?php
// Home Template Switch
if (!function_exists('member_home')) {
	function member_home($template) {
		$vc_inline = function_exists('wpj_vc_is_inline') ? wpj_vc_is_inline() : vc_is_inline();

		if ( ! $vc_inline ) {
			if (is_home() || is_front_page()) {
				if (is_user_logged_in()) {
					wp_redirect(get_permalink(get_option('main_page_url_user')));
					exit;
				} else {
					// not needed
					// return locate_template('page-homepage-public.php');
				}
			}
		}

		return $template;
	}
}
add_filter( 'template_include', 'member_home' );

if (!function_exists('wpjobster_is_home')) {

	function wpjobster_is_home()    {

		if (isset($_GET['pay_for_item']))            return false;
		global $current_user, $wp_query;
		$p_action = isset($wp_query->query_vars['jb_action'])?$wp_query->query_vars['jb_action']:"";
		$job_category = isset($wp_query->query_vars['job_category'])?$wp_query->query_vars['job_category']:"";

		if (!empty($job_category))            return true;

		if (!empty($p_action))            return false;

		if (is_home())            return true;
		return false;
	}

}

add_action( 'after_setup_theme', 'wpjobster_init_title' );
function wpjobster_init_title() {
	add_theme_support( 'title-tag' );
	// remove Yoast SEO titles at this point
	// hook it later, conditionally, when wp_query and jb_action are defined
	if ( class_exists('WPSEO_Frontend') ) {
		$wpseo_front = WPSEO_Frontend::get_instance();
		remove_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
		remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
	}
}

add_action( 'pre_get_posts', 'wpjobster_setup_title' );
function wpjobster_setup_title() {
	// wp_query and jb_action are defined now
	// hook custom titles for custom pages, where wp_query is useless
	// hook Yoast SEO titles for regular WP pages

	$jb_action = wpjobster_get_jb_action();
	if ( in_array( $jb_action, array(
		'chat_box',
		'loader_page',
		'purchase_this',
		'purchase_this_widget',
		'feature_job',
		'badges',
		'edit_job',
		'delete_job',
		'edit_request',
		'abort_mutual_cancelation',
		'verify_email',
		'verify_phone',
	) ) ) {
		add_filter( 'document_title_parts', 'wpjobster_custom_titles', 10 );
	} elseif ( class_exists('WPSEO_Frontend') ) {
		$wpseo_front = WPSEO_Frontend::get_instance();
		add_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
		add_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
	}
}

function wpjobster_custom_titles( $title_parts ) {

	$custom_title = $title_parts['title'];
	global $wp_query;
	$jb_action = wpjobster_get_jb_action();


	if ( $jb_action == "edit_job" ) {
		$pid = $_GET['jobid'];
		$posta = get_post( $pid );
		$custom_title = sprintf(__("Edit Job - %s", "wpjobster"), $posta->post_title);
	}
	elseif ( $jb_action == "chat_box" ) {
		$custom_title = sprintf( __( "Transaction #%s", "wpjobster" ), wpjobster_camouflage_order_id( $_GET['oid'] ) );
	}
	elseif ( $jb_action == "loader_page" ) {
		$custom_title = __( "Please wait...", "wpjobster" );
	}
	elseif ( $jb_action == "purchase_this" ) {
		$custom_title = __( "Review and Choose Payment Method", "wpjobster" );
	}
	elseif ( $jb_action == "purchase_this_widget" ) {
		$custom_title = __( "Review and Choose Payment Method", "wpjobster" );
	}
	elseif ( $jb_action == "feature_job" ) {
		$custom_title = __( "Feature Job", "wpjobster" );
	}
	elseif ( $jb_action == "badges" ) {
		$custom_title = __( "Buy Badge", "wpjobster" );
	}
	elseif ( $jb_action == "abort_mutual_cancelation" ) {
		$custom_title = __( "Abort Cancellation", "wpjobster" );
	}
	elseif ( $jb_action == "verify_email" ) {
		$custom_title = __( "Verify Email", "wpjobster" );
	}
	elseif ( $jb_action == "verify_phone" ) {
		$custom_title = __( "Verify Phone", "wpjobster" );
	}

	$title_parts['title'] = $custom_title;
	return $title_parts;
}

add_action( 'after_setup_theme', 'wpjobster_setup_thumbnails' );
function wpjobster_setup_thumbnails() {
	add_theme_support('post-thumbnails');

	// job images
	add_image_size('thumb_picture_size', 300, 207, true);
	add_image_size('job_cover_image', 980, 180, true);
	add_image_size('job_slider_image', 720, 405, false);

	// news & blog images
	add_image_size('blog_thumbnail_big', 980, 550, true);
	add_image_size('blog_thumbnail_big', 980, 405, true);
	add_image_size('news_slider', 486, 220, true);

	// square for general use
	add_image_size('square_150', 150, 150, true);
	add_image_size('square_180', 180, 180, true);
}

add_action('query_vars', 'wpjobster_add_query_vars');
function wpjobster_add_query_vars($public_query_vars){
	$public_query_vars[] = 'jb_action';
	$public_query_vars[] = 'orderid';
	$public_query_vars[] = 'step';
	$public_query_vars[] = 'my_second_page';
	$public_query_vars[] = 'third_page';
	$public_query_vars[] = 'username';
	$public_query_vars[] = 'pid';
	$public_query_vars[] = 'term_search';
	$public_query_vars[] = 'method';
	$public_query_vars[] = 'jobid';
	$public_query_vars[] = 'page';
	$public_query_vars[] = 'job_category';
	$public_query_vars[] = 'job_sort';
	$public_query_vars[] = 'job_tax';
	$public_query_vars[] = 'pg';

	return $public_query_vars;
}

function wpjobster_get_option_drop_down($arr, $name, $default = '',$other_atts='') {
	$opts = get_option($name);

	$selected = "";
	if ( $opts !== false && isset( $arr[$opts] ) ) {
		$selected = $opts;
	} else {
		$selected = $default;
	}

	$r = '<select class="ui dropdown" name="' . $name . '" id="'. $name .'" '.$other_atts.'>';
	foreach ($arr as $key => $value) {
		$r .= '<option value="' . $key . '" ' . ($selected == $key ? ' selected="selected" ' :
		"") . '>' . $value . '</option>';
	}

	return $r . '</select>';
}

function wpjobster_create_auto_draft($uid){
	$my_post = array();
	$my_post['post_title'] = 'Auto Draft';
	$my_post['post_type'] = 'job';
	$my_post['post_status'] = 'auto-draft';
	$my_post['post_author'] = $uid;
	return wp_insert_post($my_post, true);
}

function wpjobster_get_auto_draft($uid){
	global $wpdb;
	$querystr = "
	SELECT distinct wposts.*
	FROM $wpdb->posts wposts where
	wposts.post_author = '$uid' AND wposts.post_status = 'auto-draft'
	AND wposts.post_type = 'job'
	ORDER BY wposts.ID DESC LIMIT 1 ";
	$row = $wpdb->get_results($querystr, OBJECT);

	if (count($row) > 0) {
		$row = $row[0];
		return $row->ID;
	}

	return wpjobster_create_auto_draft($uid);
}

function wpjobster_using_permalinks(){
	global $wp_rewrite;

	if ($wp_rewrite->using_permalinks())        return true; else        return false;
}

function wpjobster_get_jb_action() {
	global $wp_query;
	if ( isset( $wp_query->query_vars['jb_action'] ) ) {
		$jb_action = $wp_query->query_vars['jb_action'];
	} else {
		$jb_action = "";
	}

	return $jb_action;
}

function wpjobster_login_url(){
	if (function_exists('qtrans_removeLanguageURL')) {
		return qtrans_removeLanguageURL(get_bloginfo('url') . "/wp-login.php");
	}
	return get_bloginfo('url') . "/wp-login.php";
}

function wpjobster_get_i_will_strg(){
	$opt = get_option('wpjobster_i_will_strg');

	if (empty($opt))        return __(" ", "wpjobster");
	return $opt;
}

function wpjobster_get_for_strg(){
	$opt = get_option('wpjobster_for_strg');

	if (empty($opt))        return __("for", "wpjobster");
	return $opt;
}

function wpjobster_reomve_i_will($title, $price){
	$title = str_replace(__("I will", "wpjobster"), "", $title);
	$title = str_replace(__("for", "wpjobster") . " " . get_option('wpjobster_currency_symbol') . $price, "", $title);
	$title = str_replace(__("for", "wpjobster") . " " . $price . get_option('wpjobster_currency_symbol'), "", $title);
	$title = str_replace(__("for", "wpjobster") . " " . get_option('wpjobster_currency_symbol') . $price, "", $title);
	$title = str_replace(__("for", "wpjobster") . " " . $price . get_option('wpjobster_currency_symbol'), "", $title);
	return trim($title);
}


function wpjobster_get_post_by_title($page_title, $output = OBJECT){
	global $wpdb;
	$post = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s", $page_title));

	if ($post)        return get_post($post, $output);
	return false;
}


function wpjobster_add_wrap_the_title($title, $pid){
	$post = get_post($pid);

	if ($post != false) {

		if ($post->post_type == "job") {
			$data = wpjobster_wrap_the_title($title, $post->ID);
			return $data;
		}

	}

	return $title;
}

function wpjobster_wrap_the_title($title, $pid){
	return $title;
}

add_action( 'template_redirect', 'the_loop_check_action' );
function the_loop_check_action() {
	if ( ! the_loop_check() ) {
		if ( current_user_can( 'manage_options' ) ) {
			$general_settings = "<a href='" . admin_url( 'admi' . 'n.php?pa' . 'ge=genera' . 'l-optio' . 'ns#usual2' ) . "' targe" . "t='_bla" . "nk'>gene" . "ral se" . "ttin" . "gs</a>";
		} else {
			$general_settings = "gene" . "ral set" . "tings";
		}

		wp_die(
			"Ple" . "ase f" . "ill" . " you" . "r " . "th" . "e" . "me licen" . "se key in " . $general_settings . ".<br>
			Don't" . " hav" . "e on" . "e? You ca" . "n ge" . "t yo" . "ur" . "s <a href='ht" . "tps://wpjob" . "ster.co" . "m/bu" . "y/' target='_blank'>he" . "re</a>, or co" . "ntact us <a hr" . "ef='htt" . "ps://wpj" . "obst" . "er.com/co" . "ntact/' target" . "='_blank'>h" . "ere</" . "a>." );
	}
}

function the_loop_check() {
	$esnecil_yek = get_option('emos_laer_euqinu_eman');

	if (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
		return 1;
	}

	if (!isset($esnecil_yek) || $esnecil_yek == '') {
		return false;
	}

	$uz_dellatsni_niamod = get_host_no_www();
	$uz_terces_edoc = 'd945jfht';

	if ($esnecil_yek == hash("sha256", $uz_dellatsni_niamod . $uz_terces_edoc . '3' , false)
		|| $esnecil_yek == hash("sha256", get_host() . $uz_terces_edoc . '3' , false)) {
		return 3;
	} elseif ($esnecil_yek == hash("sha256", $uz_dellatsni_niamod . $uz_terces_edoc . '2' , false)
		|| $esnecil_yek == hash("sha256", get_host() . $uz_terces_edoc . '2' , false)) {
		return 2;
	} elseif ($esnecil_yek == hash("sha256", $uz_dellatsni_niamod . $uz_terces_edoc . '1' , false)
		|| $esnecil_yek == hash("sha256", get_host() . $uz_terces_edoc . '1' , false)) {
		return 1;
	} elseif ($esnecil_yek == hash("sha256", $uz_dellatsni_niamod . $uz_terces_edoc , false)
		|| $esnecil_yek == hash("sha256", get_host() . $uz_terces_edoc , false)) {
		return 1;
	} elseif ($esnecil_yek == hash("sha256", $uz_dellatsni_niamod . $uz_terces_edoc . '0.5' , false)
		|| $esnecil_yek == hash("sha256", get_host() . $uz_terces_edoc . '0.5' , false)) {
		return 0.5;
	}

	return false;
}

function wpjobster_return_license_name() {
	if (the_loop_check() == 3) {
		return "Entrepreneur";
	} elseif (the_loop_check() == 2) {
		return "Developer";
	} elseif (the_loop_check() == 1) {
		return "Webmaster";
	} elseif (the_loop_check() == 0.5) {
		return "Beginner";
	} else {
		return "None";
	}
}

function wpjobster_needs_jquery() {
	wp_enqueue_script( 'jquery' );
}

function theme_apto_get_orderby($new_orderBy, $orderBy, $query) {
	return $orderBy;
}

function wpjobster_insert_pages( $page_id_meta, $page_title, $page_content, $parent_pg = 0, $wp_page_template = 'wpjobster-special-page-template.php' ) {

	$existing_page = get_option( $page_id_meta );
	if ( ! wpjobster_check_if_page_existed( $existing_page ) ) {
		$post = array(
			'post_title' => $page_title,
			'post_content' => $page_content,
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_author' => 1,
			'ping_status' => 'closed',
			'post_parent' => $parent_pg
		);
		$new_page = wp_insert_post( $post );
		update_post_meta( $new_page, '_wp_page_template', 'page-templates/' . $wp_page_template );
		update_option( $page_id_meta, $new_page );

		return $new_page;
	}

	return $existing_page;
}


function wpjobster_insert_homepage($page_ids, $page_title, $page_tag, $parent_pg = 0){
	$opt = get_option($page_ids);

	if (!wpjobster_check_if_page_existed($opt)) {
		$post = array(            'post_title' => $page_title,            'post_content' => $page_tag,            'post_status' => 'publish',            'post_type' => 'page',            'post_author' => 1,            'ping_status' => 'closed',            'post_parent' => $parent_pg        );
		$post_id = wp_insert_post($post);
		update_post_meta($post_id, '_wp_page_template', 'page-templates/page-homepage-public.php');
		update_option($page_ids, $post_id);
	}
}

function wpjobster_check_if_page_existed($pid){
	global $wpdb;
	$s = "select * from " . $wpdb->prefix . "posts where post_type='page' AND post_status='publish' AND ID='$pid'";
	$r = $wpdb->get_results($s);

	if (count($r) > 0) return true;
	return false;
}

function wpjobster_processing_fee_allowed() {
	return wpj_is_allowed( 'processing_fee' );
}

function wpjobster_topup_allowed() {
	return wpj_is_allowed( 'top_up' );
}

function wpjobster_subscriptions_allowed() {
	return wpj_is_allowed( 'subscriptions' );
}

function wpjobster_multilanguage_allowed() {
	return wpj_is_allowed( 'multi_language' );
}

function wpj_get_min_lcs_for( $feature ) {
	if ( in_array( $feature, array(
			'top_up',
			'subscriptions',
			'sms_notifications',
			'packages',
		) ) ) {
		return 3;

	} elseif ( in_array( $feature, array(
			'processing_fee',
			'multi_language',
		) ) ) {
		return 2;

	} elseif ( in_array( $feature, array(
			'multi_currency',
			'featured_job',
			'custom_offers',
			'flexible_fees',
			'job_multiples',
			'custom_extras',
			'fast_del_multiples',
		) ) ) {
		return 1;
	}

	return 0;
}

function wpj_is_allowed( $feature ) {
	if ( the_loop_check() >= wpj_get_min_lcs_for( $feature ) ) {
		return true;
	}
	return false;
}


function wpj_disabled_settings_class( $feature ) {
	if ( ! wpj_is_allowed( $feature ) ) {
		echo 'wpjobster-disabled-settings';
	}
}


function wpj_disabled_settings_error( $feature ) {
	$the_loop_check = the_loop_check();
	$min_lcs_for_this = wpj_get_min_lcs_for( $feature );

	if ( $min_lcs_for_this == 1 ) {
		$available_for_txt = "Webmaster, Developer or Entrepreneur";
	} elseif ( $min_lcs_for_this == 2 ) {
		$available_for_txt = "Developer or Entrepreneur";
	} elseif ( $min_lcs_for_this == 3 ) {
		$available_for_txt = "Entrepreneur";
	} else {
		return;
	}

	if ( $the_loop_check < $min_lcs_for_this ) {
		?>
		<div class="error notice">
			<p>Some settings could not be saved. Please <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a> your license to <?php echo $available_for_txt; ?> in order to use this feature.</p>
		</div>

		<?php
	}
}

function wpj_disabled_settings_notice( $feature ) {
	$the_loop_check = the_loop_check();
	$min_lcs_for_this = wpj_get_min_lcs_for( $feature );

	if ( $min_lcs_for_this == 1 ) {
		$available_for_txt = "This feature is only available for the Webmaster, Developer and Entrepreneur licenses.";
	} elseif ( $min_lcs_for_this == 2 ) {
		$available_for_txt = "This feature is only available for the Developer and Entrepreneur licenses.";
	} elseif ( $min_lcs_for_this == 3 ) {
		$available_for_txt = "This feature is only available for the Entrepreneur license.";
	} else {
		return;
	}

	if ( $the_loop_check < $min_lcs_for_this ) {
		?>
		<div class="wpjobster-update-nag wpjobster-notice">
			<?php echo $available_for_txt; ?> <a href="http://wpjobster.com/features/" target="_blank">Learn more</a> about the features, <a href="http://wpjobster.com/buy/" target="_blank">buy</a> a new license or <a href="http://wpjobster.com/contact/" target="_blank">contact us</a> for upgrading.
		</div>
		<?php
	}
}

// NOTIFICATIONS FOR NEW POSTS
add_filter( 'add_menu_classes', 'show_pending_number_request');
function show_pending_number_request( $menu ) {
	$types = array( "job", "request" );

	foreach ($types as $type) {
		$status = "draft";
		$num_posts = wp_count_posts( $type, 'readable' );
		$pending_count = 0;
		if ( !empty($num_posts->$status) ){
			$pending_count = $num_posts->$status;
		}

		if ($type == 'post') {
			$menu_str = 'edit.php';
		} else {
			$menu_str = 'edit.php?post_type=' . $type;
		}

		foreach( $menu as $menu_key => $menu_data ) {
			if( $menu_str != $menu_data[2] ){
				continue;
			}
			$menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
		}
	}
	return $menu;
}

//ARRAY FOR PAGE ASSIGNMENTS
if ( ! function_exists( 'get_wpjobster_page_ids' ) ) {
	function get_wpjobster_page_ids(){
		$pages = array(
			__( 'Blog Posts','wpjobster' )             => 'wpjobster_blog_home_id',
			__( 'Post New Job','wpjobster' )           => 'wpjobster_post_new_page_id',
			__( 'Post New Request','wpjobster' )       => 'wpjobster_new_request_page_id',
			__( 'My Account','wpjobster' )             => 'wpjobster_my_account_page_id',
			__( 'My Requests','wpjobster' )            => 'wpjobster_my_requests_page_id',
			__( 'My Favorites','wpjobster' )           => 'wpjobster_my_favorites_page_id',
			__( 'Shopping','wpjobster' )               => 'wpjobster_my_account_shopping_page_id',
			__( 'Sales','wpjobster' )                  => 'wpjobster_my_account_sales_page_id',
			__( 'Personal Information','wpjobster' )   => 'wpjobster_my_account_personal_info_page_id',
			__( 'Reviews/Feedback','wpjobster' )       => 'wpjobster_my_account_reviews_page_id',
			__( 'Payments','wpjobster' )               => 'wpjobster_my_account_payments_page_id',
			__( 'Private Messages','wpjobster' )       => 'wpjobster_my_account_priv_mess_page_id',
			__( 'All Notifications','wpjobster' )      => 'wpjobster_my_account_all_notifications_page_id',
			__( 'All Categories','wpjobster' )         => 'wpjobster_all_categories_page_id',
			__( 'Search Jobs','wpjobster' )            => 'wpjobster_advanced_search_id',
			__( 'Search Requests','wpjobster' )        => 'wpjobster_advanced_search_request_page_id',
			__( 'Search Users','wpjobster' )           => 'wpjobster_search_user_page_id',
			__( 'Email settings','wpjobster' )         => 'wpjobster_email_settings_page_id',
			__( 'User Profile','wpjobster' )           => 'wpjobster_user_profile_page_id',
			__( 'Levels','wpjobster' )                 => 'wpjobster_levels_page_id',
			__( 'Subscriptions','wpjobster' )          => 'wpjobster_subscriptions_page_id',
			__( 'Privacy Policy','wpjobster' )         => 'wpjobster_privacy_policy_page_id',
			__( 'Terms of Service','wpjobster' )       => 'wpjobster_terms_of_service_page_id',
			__( 'How it Works ','wpjobster' )          => 'wpjobster_how_it_works_page_id',
			__( 'New Ticket Page URL','wpjobster' )    => 'wpjobster_new_ticket_page_id',
			__( 'Single Ticket Page URL','wpjobster' ) => 'wpjobster_single_ticket_page_id',
			__( 'Support Page URL','wpjobster' )       => 'wpjobster_ticket_list_page_id',
		);

		$pages = apply_filters( 'wpjobster_page_assignments_list', $pages );

		return $pages;
	}
}

// TIMEZONE CHANGE
function wpjobster_timezone_change(){
	if (is_user_logged_in()) {
		global $current_user;
		$current_user = wp_get_current_user();

		$timezone = stripslashes(get_user_meta($current_user->ID, 'timezone_select', true));
		if(isset($timezone) && $timezone != ''){
			date_default_timezone_set($timezone);
		}
	}
}

add_action('wp_loaded', 'time_zone_function');
function time_zone_function(){
	$timezone_identifier = get_option( 'timezone_string', true );
	if( isset( $timezone_identifier ) && $timezone_identifier != '' ) {
		date_default_timezone_set ( $timezone_identifier );
	}
}

function get_parsed_countable_string($raw_string){
	$msg = stripslashes($raw_string);
	$msg1=str_replace(array("\r\n","\n\r")," ",$msg);
	$countable_string = str_replace(array("\n")," ",$msg1);
	return $countable_string;
}

if (!function_exists('wpjobster_return_datetimepicker_language')) {
	function wpjobster_return_datetimepicker_language() {

		// all the languages in "/js/jquery/datetimepicker.js"
		$datetimepicker_language_codes = array('ar', 'ro', 'id', 'is', 'bg', 'fa', 'ru', 'uk', 'en', 'el', 'de', 'nl', 'tr', 'fr', 'es', 'th', 'pl', 'pt', 'ch', 'se', 'kr', 'it', 'da', 'no', 'ja', 'vi', 'sl', 'cs', 'hu', 'az', 'bs', 'ca', 'en-GB', 'et', 'eu', 'fi', 'gl', 'hr', 'ko', 'lt', 'lv', 'mk', 'mn', 'pt-BR', 'sk', 'sq', 'sr-YU', 'sr', 'sv', 'zh-TW', 'zh', 'he', 'hy', 'kg');

		// if is translated "/js/jquery/datetimepicker.js"

		if (function_exists('qtranxf_getLanguage')) {
			$qtrans_language = qtranxf_getLanguage();
			if (in_array($qtrans_language, $datetimepicker_language_codes)) {
				return $qtrans_language;
			}
		}

		$wp_locale = get_locale();
		if (in_array($wp_locale, $datetimepicker_language_codes)) {
			return $wp_locale;
		}

		return 'en';
	}
}

if ( ! function_exists( 'is_demo_user' ) ) {
	//--------------------------------------
	// Check if current user is demo
	//--------------------------------------

	function is_demo_user() {
		if ( get_current_user_role() == "demo_user" ) {
			return 1;
		}
	}
}


if ( ! function_exists( 'is_demo_admin' ) ) {
	//--------------------------------------
	// Check if current user is demo admin
	//--------------------------------------

	function is_demo_admin() {
		if ( get_current_user_role() == "demo_admin" ) {
			return 1;
		}
	}
}

function tmp_add_demo_user_role() {

	$result = add_role(
		'demo_user',
		__( 'Demo User' ),
		array(
			'read'         => true,  // true allows this capability
			'edit_posts'   => true,
			'delete_posts' => false, // Use false to explicitly deny
		)
	);
	if ( null !== $result ) {
		echo 'Yay! New role created!';
	}
	else {
		echo 'Oh... the demo_user role already exists.';
	}

}

function displayValidationErrors($validation_errors) {
	if ($validation_errors) {
		$validation_errors_display = '<div class="pm-parse-errors">';
		foreach ($validation_errors as $e) {
			$validation_errors_display .= '<span class="parse-error">'.$e.'</span><br>';
		}
		$validation_errors_display .= '</div>';
	}else{
		$validation_errors_display='';
	}
	return $validation_errors_display;
}

function filterWords($text, $words, $replace = true) {
	$escaped = array();
	foreach ($words as $w) {
		array_push($escaped, preg_quote($w));
	}
	$re = '/(' . implode('|', $escaped) . ')/i';
	preg_match_all($re, $text, $matches, PREG_SET_ORDER);

	$matchesCount = count($matches);
	$status = 0;
	for ($i = 0; $i < $matchesCount; $i++) {
		if ($replace) {
			$text = str_replace($matches[$i], '*****', $text);
		}
		$status = 1;
	}
	return array($text, $status);
}

function filterMessagePlusErrors($message, $replace = true) {
	$validation_errors = array();
	$contact_details = 0;

	$message = stripslashes( $message );

	// Filter Emails
	list($message, $status) = filterEmails($message, $replace);
	if ($status == 1) {
		if (get_option('wpjobster_blacklisted_email')) {
			array_push($validation_errors, get_option('wpjobster_blacklisted_email'));
		}
	}

	// Filter Phone Numbers
	list($message, $status) = filterPhoneNumbersAdvanced($message, $replace);
	if ($status == 1) {
		if (get_option('wpjobster_blacklisted_phone')) {
			array_push($validation_errors, get_option('wpjobster_blacklisted_phone'));
		}
	}

	// Filter Words 1
	if (get_option('wpjobster_blacklisted_words_pm')) {
		$wpjobster_blacklisted_words_pm = get_option('wpjobster_blacklisted_words_pm');

		$blacklisted_words_pm = str_replace("\r", "\n", $wpjobster_blacklisted_words_pm);
		$blacklisted_words_pm = explode("\n", $blacklisted_words_pm);
		$blacklisted_words_clean = array();
		foreach ($blacklisted_words_pm as $word) {
			$word = trim($word);
			if ($word) {
				array_push($blacklisted_words_clean, $word);
			}
		}

		list($message, $status) = filterWords($message, $blacklisted_words_clean, $replace);
		if ($status == 1) {
			if (get_option('wpjobster_blacklisted_words_pm_err')) {
				array_push($validation_errors, get_option('wpjobster_blacklisted_words_pm_err'));
			}
		}
	}

	// Filter Words 2
	if (get_option('wpjobster_blacklisted_words2_pm')) {
		$wpjobster_blacklisted_words2_pm = get_option('wpjobster_blacklisted_words2_pm');

		$blacklisted_words2_pm = str_replace("\r", "\n", $wpjobster_blacklisted_words2_pm);
		$blacklisted_words2_pm = explode("\n", $blacklisted_words2_pm);
		$blacklisted_words2_clean = array();
		foreach ($blacklisted_words2_pm as $word) {
			$word = trim($word);
			if ($word) {
				array_push($blacklisted_words2_clean, $word);
			}
		}

		list($message, $status) = filterWords($message, $blacklisted_words2_clean, $replace);
		if ($status == 1) {
			if (get_option('wpjobster_blacklisted_words2_pm_err')) {
				array_push($validation_errors, get_option('wpjobster_blacklisted_words2_pm_err'));
			}
		}
	}

	// Filter Words 3
	if (get_option('wpjobster_blacklisted_words3_pm')) {
		$wpjobster_blacklisted_words3_pm = get_option('wpjobster_blacklisted_words3_pm');

		$blacklisted_words3_pm = str_replace("\r", "\n", $wpjobster_blacklisted_words3_pm);
		$blacklisted_words3_pm = explode("\n", $blacklisted_words3_pm);
		$blacklisted_words3_clean = array();
		foreach ($blacklisted_words3_pm as $word) {
			$word = trim($word);
			if ($word) {
				array_push($blacklisted_words3_clean, $word);
			}
		}

		list($message, $status) = filterWords($message, $blacklisted_words3_clean, $replace);
		if ($status == 1) {
			if (get_option('wpjobster_blacklisted_words3_pm_err')) {
				array_push($validation_errors, get_option('wpjobster_blacklisted_words3_pm_err'));
			}
		}
	}

	return array($message, $validation_errors);
}

function wpjobster_make_date_format_readable( $format ) {
	if ( $format == 'Y-m-d' ) {
		return __( 'YYYY-MM-DD', 'wpjobster' );
	} elseif ( $format == 'd-m-Y' ) {
		return __( 'DD-MM-YYYY', 'wpjobster' );
	} elseif ( $format == 'd.m.Y' ) {
		return __( 'DD.MM.YYYY', 'wpjobster' );
	} elseif ( $format == 'm/d/Y' ) {
		return __( 'MM/DD/YYYY', 'wpjobster' );
	} else {
		return $format;
	}
}

function wpjobster_get_safe_date_format() {
	$format = get_option('wpjobster_safe_date_format');

	if ( $format == '' ) {
		$format = 'Y-m-d';
	}

	return $format;
}

function wpjobster_get_current_view_grid_list(){
	if (isset($_SESSION['view_tp']) && $_SESSION['view_tp'] == "list")        return "list"; else        return "grid";
}


function wpjobster_filter_switch_link_from_home_page($tp){
	return get_bloginfo('url') . "?switch_filter=" . $tp . "&get_urls=" . urlencode(get_current_page_url());
}

function wpjobster_switch_link_from_home_page($tp){
	return get_bloginfo('url') . "?switch_grd=" . $tp . "&get_urls=" . urlencode(get_current_page_url());
}

function wpjobster_post_new_with_pid_stuff_thg($pid){
	$using_perm = wpjobster_using_permalinks();

	if ($using_perm)
		return get_permalink(get_option('wpjobster_post_new_page_id')) . "?jobid=" . $pid;
	else
		return get_bloginfo('url') . "/?page_id=" . get_option('wpjobster_post_new_page_id') . "&jobid=" . $pid;
}

add_action( 'wp_head', 'vc_ajaxurl', 1 );
function vc_ajaxurl() {
	$scheme = is_ssl() ? 'https' : 'http';
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', $scheme ); ?>';
		var is_user_logged_in='<?php echo is_user_logged_in(); ?>';
	</script>
	<?php
}

add_action('template_redirect', 'check_data_completion');
function check_data_completion(){
	global $current_user;
	$wpjobster_my_account_personal_info_page_id = get_option('wpjobster_my_account_personal_info_page_id');

	if (is_user_logged_in() && !is_page($wpjobster_my_account_personal_info_page_id) && isset($_GET['jb_action']) && $_GET['jb_action'] != "verify_email") {

		if (get_user_meta($current_user->ID, 'first_name', true) == '' || get_user_meta($current_user->ID, 'last_name', true) == '' || get_user_meta($current_user->ID, 'city', true) == '' || get_user_meta($current_user->ID, 'zip', true) == '' || get_user_meta($current_user->ID, 'country_code', true) == '') {
		} else {
			$last = get_user_meta( $current_user->ID, 'last_user_login', true );
			if(empty($last)) {
				add_action( 'wp_footer', 'wpjobster_analytics_registration_goal' );
				update_user_meta($current_user->ID,'last_user_login', current_time('timestamp', 1));
			}

		}

	}
}

add_filter('get_archives_link', 'translate_archive_month');
function translate_archive_month($list) {
	$patterns = array(
	'/January/', '/February/', '/March/', '/April/', '/May/', '/June/',
	'/July/', '/August/', '/September/', '/October/',  '/November/', '/December/'
	);
	$replacements = array(
	__('January','wpjobster'), __('February','wpjobster'), __('March','wpjobster'), __('April','wpjobster'), __('May','wpjobster'), __('June','wpjobster'),
	__('July','wpjobster'), __('August','wpjobster'), __('September','wpjobster'), __('October','wpjobster'), __('November','wpjobster'), __('December','wpjobster')
	);
	$list = preg_replace($patterns, $replacements, $list);
	return $list;
}

function wpjobster_database_strings_list(){
	$strings_arr = array(
		'credits'       => _x('Credits', 'Credits gateway', 'wpjobster'),
		'paypal'        => _x('PayPal', 'PayPal gateway', 'wpjobster'),
		'cod'           => _x('COD', 'COD gateway', 'wpjobster'),
		'banktransfer'  => _x('Bank Transfer', 'Bank Transfer gateway', 'wpjobster'),
	);

	$strings = apply_filters( 'wpjobster_database_strings_filter', $strings_arr );

	return $strings;
}

// TRANSLATABLE STRINGS
function wpjobster_translate_string($string){
	$strings_arr = wpjobster_database_strings_list();

	if ( in_array_r( $string, $strings_arr ) ) {
		foreach ($strings_arr as $key => $str) {
			if( strtolower( $string ) == $key ){
				return $str;
			}
		}
	}else{
		return $string;
	}
}

function wpjobster_set_template_url_cookie(){
	setcookie("template_url", get_template_directory_uri() ,time()+86400 ,'/');
}
add_action( 'admin_init','wpjobster_set_template_url_cookie' );

// IS FRONT VC EDITOR
function wpj_vc_is_inline(){
	global $vc_is_inline;

	if ( function_exists( 'vc_is_inline' ) ) {
		if ( vc_is_inline() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

// GET POPUP
function wpj_get_popup( $var, $action_type = "new", $post_type = "job", $pid = "", $field = "" ){
	$ret = '';

	if ( $var ){
		if ( $action_type == "edit" && $post_type == "job" && $pid && $field ) {
			$rejected_title = get_post_meta( $pid, "rejected_" . $field, true );
			$rejected_comment = get_post_meta( $pid, "rejected_" . $field . "_comment", true );
			$rej_class = ( get_post_status( $pid ) == 'pending' && $rejected_title == 1 ) ? 'rejected-input' : '';

			$ret .= '<div class="ui popup ' . $rej_class . '">';
				if ( get_post_status( $pid ) == 'pending' && $rejected_title == 1 ) {
					$ret .= $rejected_comment;
				} else {
					if ( get_field( $var, 'options' ) ) {
						$ret .= get_field( $var, 'options' );
					} elseif ( current_user_can( 'manage_options' ) ) {
						$ret .= __( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
					}
				}
			$ret .= '</div>';
		} elseif ( $action_type == "edit" && $post_type == "request" && $pid && $field ) {
			$rejected_title = get_post_meta( $pid, "req_rejected_" . $field, true );
			$rejected_comment = get_post_meta( $pid, "req_rejected_" . $field . "_comment", true );
			$rej_class = ( get_post_status( $pid ) == 'pending' && $rejected_title == 1 ) ? 'rejected-input' : '';

			$ret .= '<div class="ui popup ' . $rej_class . '">';
				if ( get_post_status( $pid ) == 'pending' && $rejected_title == 1 ) {
					$ret .= $rejected_comment;
				} else {
					if ( get_field( $var, 'options' ) ) {
						$ret .= get_field( $var, 'options' );
					} elseif ( current_user_can( 'manage_options' ) ) {
						$ret .= __( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
					}
				}
			$ret .= '</div>';
		} else {
			$ret .= '<div class="ui popup">';
				if ( get_field( $var, 'options' ) ) {
					$ret .= get_field( $var, 'options' );
				} elseif ( current_user_can( 'manage_options' ) ) {
					$ret .= __( 'Regular users will not see this message unless you edit it from WP Admin > Options > Post New Job Instructions.', 'wpjobster' );
				}
			$ret .= '</div>';
		}
	}

	return $ret;
}
