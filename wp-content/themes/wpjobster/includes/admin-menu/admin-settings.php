<?php
add_action( 'init', 'wpj_show_hide_menu_bar' );
function wpj_show_hide_menu_bar(){
	global $current_user;
	$current_user = wp_get_current_user();

	if ( user_can( $current_user, "manage_options" ) ){
		show_admin_bar( true );
	} else {
		show_admin_bar( false );
	}
}

add_action( 'admin_enqueue_styles', 'wpjobster_load_admin_style' );
function wpjobster_load_admin_style() {
	wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/css/select2.css', false, '1.0.0' );
	wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/css/daterangepicker.css', false, '1.0.0' );

	wp_enqueue_style( 'custom_wp_admin_css' );
}

add_action( 'admin_enqueue_scripts', 'wpjobster_load_admin_scripts' );
function wpjobster_load_admin_scripts($hook) {
	wp_enqueue_script( 'my_custom_script', get_template_directory_uri(). '/js/select2.js' );
	wp_enqueue_script( 'my_custom_script', get_template_directory_uri(). '/js/moment.js' );
	wp_enqueue_script( 'my_custom_script', get_template_directory_uri(). '/js/daterangepicker.js' );
	wp_enqueue_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', array( 'jquery' ), wpjobster_VERSION);
	wp_enqueue_script( 'less-js', get_template_directory_uri(). '/js/less.js', array('jquery'), wpjobster_VERSION );

	wp_enqueue_script( 'main-admin', get_template_directory_uri(). '/js/wpjobster/main-admin.js', array('jquery'), wpjobster_VERSION );
	wp_localize_script( 'main-admin', 'base_main_admin', array(
		'current_role'      => get_current_user_role(),
		'ajax_url'          => admin_url( 'admin-ajax.php' ),
		'header_fixed'      => get_theme_mod('header_fixed', true),
		'semantic_file_url' => get_template_directory_uri(). '/vendor/semantic-ui/app.php',
		'primaryColor'      => get_theme_mod( 'primary_color' ),
		'secondaryColor'    => get_theme_mod( 'secondary_color' )
	));

	wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/css/select2.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_wp_admin_css' );
}

if (function_exists('acf_add_options_page')) {
	acf_add_options_page();
}

add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');
function custom_menu_order( $menu_ord ) {
	if (!$menu_ord) return true;
	// vars
	$menu1 = 'acf-options';
	// remove from current menu
	$menu_ord = array_diff($menu_ord, array( $menu1 ));
	// append after index.php [0]
	array_splice( $menu_ord, 1, 0, array( $menu1 ) );
	// return
	return $menu_ord;
}

add_action('admin_head', 'wpjobster_admin_stylesheet');
function wpjobster_admin_stylesheet(){
	wp_enqueue_script("jquery-ui-widget");
	wp_enqueue_script("jquery-ui-mouse");
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script("jquery-ui-datepicker");
	wp_enqueue_script('jquery-ui-sortable');

	// Semantic UI
	wp_enqueue_style( 'semantic-ui-icon', get_template_directory_uri() . '/vendor/semantic-ui/components/icon.css', array(), '2.2.1' );
	wp_enqueue_style( 'semantic-ui-accordion-style', get_template_directory_uri() . '/vendor/semantic-ui/components/accordion.css', array(), '2.2.1' );
	wp_enqueue_script( 'semantic-ui-accordion-script', get_template_directory_uri() . '/vendor/semantic-ui/components/accordion.js', array( 'jquery' ), '2.2.1' );
	?>

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/tipTip.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/admin.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/colorpicker.css" type="text/css" />
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/layout.css" />
	<link type="text/css" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/jquery.tipTip.js"></script>
	<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/idtabs.js"></script>
	<script type="text/javascript">
		<?php
		$tb = 'tabs1';
		if (isset($_GET['active_tab'])) $tb = $_GET['active_tab'];
		if(isset($_GET['page']) && $_GET['page']=='payment-methods' && $tb == 'tabs1'){ $tb = "tabspaypal"; }
		?>

		jQuery(document).ready(function() {
			jQuery("#usual2 ul").idTabs("<?php echo $tb; ?>");
			jQuery(".tltp_cls").tipTip({maxWidth: "330"});
		});
	</script>
	<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/colorpicker.js"></script>
	<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/utils.js"></script>
	<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri()); ?>/js/layout.js?ver=1.0.2"></script>
	<?php
}

if(!function_exists('wpjobster_add_html5fileupload_scripts_admin')){
	function wpjobster_add_html5fileupload_scripts_admin() {
		wp_register_script( 'bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js', array(), '3.0.1', true );
		wp_enqueue_script( 'bootstrap-js' );
		wp_enqueue_style( 'bootstrap-css' );
		wp_enqueue_script('html5fileupload-js', get_template_directory_uri() . '/lib/html5fileupload/assets/js/html5fileupload.js', array('jquery'), '1.3');
		wp_localize_script('html5fileupload-js', 'multipleupload_vars',
			array(
				'finished' => __('Finished', 'wpjobster'),
				'cancelled' => __('Cancelled', 'wpjobster'),
				'unknown_error' => __('Unknown Error', 'wpjobster'),
				'invalid_file_type' => __('Invalid file type.', 'wpjobster'),
				'error_404' => __('404 Error', 'wpjobster'),
				'error_403' => __('403 Forbidden', 'wpjobster'),
				'forbidden_file_type' => __('Forbidden file type', 'wpjobster'),
				'maximum_file_size_exceeded' => __('Maximum file size exceeded', 'wpjobster'),
				'maximum_number_of_files_exceeded' => __('Maximum number of files exceeded.', 'wpjobster')
			)
		);
		wp_enqueue_style('multipleupload-css', get_template_directory_uri() . '/lib/html5fileupload/assets/css/html5fileupload.css');
	}
}

add_filter('manage_edit-job_columns', 'wpjobster_my_jobs_columns');
function wpjobster_my_jobs_columns($columns){ //this function display the columns headings
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __("Job Title", 'wpjobster'),
		"price" => __("Price", 'wpjobster'),
		"author" => __("Author", 'wpjobster'),
		"posted" => __("Posted On", 'wpjobster'),
		"closed" => __("Status", 'wpjobster'),
		"thumbnail" => __("Thumbnail", 'wpjobster'),
		"options" => __("Options", 'wpjobster')
	);
	return $columns;
}

add_action('manage_posts_custom_column', 'wpjobster_my_custom_columns');
function wpjobster_my_custom_columns($column){
	global $post;

	if ("ID" == $column) echo $post->ID;
	//displays title
	elseif ("description" == $column) echo $post->ID;
	//displays the content excerpt
	elseif ("posted" == $column) echo date('jS \of F, Y \<\b\r\/\>H:i:s', strtotime($post->post_date));
	//displays the content excerpt
	elseif ("thumbnail" == $column) {
		echo '<a href="' . get_bloginfo('url') . '/wp-admin/post.php?post=' . $post->ID . '&action=edit"><img class="image_class" src="' . wpjobster_get_first_post_image($post->ID, 65, 55) . '" width="65" height="55" /></a>';
	}
	elseif ("price" == $column) {
		$wpjobster_packages = get_option('wpjobster_packages_enabled');
		$packages = get_post_meta( $post->ID, 'job_packages', true );
		$package_price = get_post_meta( $post->ID, 'package_price', true );

		if ( $wpjobster_packages == 'yes' && $packages == 'yes' && $package_price ) {
			sort( $package_price );
			$package_price = array_diff($package_price, array(null));
			echo wpjobster_get_show_price( min( $package_price ) );
			echo ' - ';
			echo wpjobster_get_show_price( max( $package_price ) );
		} else {
			echo wpjobster_get_show_price(get_post_meta($post->ID, 'price', true));
		}
	}
	elseif ("author" == $column) {
		echo $post->post_author;
	}
	elseif ("closed" == $column) {
		$closed = get_post_meta($post->ID, 'closed', true);
		if ($closed == "1") echo __("Closed", "wpjobster");
		else echo __("Open", "wpjobster");
	}
	elseif ("options" == $column) {
		echo '<div style="padding-top:20px">';
		echo '<a class="awesome" href="' . get_bloginfo('url') . '/wp-admin/post.php?post=' . $post->ID . '&action=edit">Edit</a> | ';
		echo '<a class="awesome" href="' . get_permalink($post->ID) . '" target="_blank">View</a> | ';
		echo '<a class="trash" href="' . get_delete_post_link($post->ID) . '">Trash</a> ';
		echo '</div>';
	}
}

add_action('save_post', 'wpjobster_save_custom_fields');
if (!function_exists('wpjobster_save_custom_fields')) {
	function wpjobster_save_custom_fields($pid) {
		if (isset($_POST['fromadmin'])) {
			$post = get_post($pid);
			$aid = $post->post_author;
			$user_level = wpjobster_get_user_level($aid);
			$sts = get_option('wpjobster_get_level'.$user_level.'_extras');

			update_post_meta($pid, 'instruction_box', trim($_POST['instruction_box']));

			if (empty($sts)) $sts = 10;
			for ($k = 1; $k <= $sts; $k++) {
				$extra_price = trim($_POST['extra' . $k . '_price']);
				$extra_content = trim($_POST['extra' . $k . '_content']);

				if (!empty($extra_price) && is_numeric($extra_price) && !empty($extra_content)):

				update_post_meta($pid, 'extra' . $k . '_price', $extra_price);
				update_post_meta($pid, 'extra' . $k . '_content', $extra_content); else :
				update_post_meta($pid, 'extra' . $k . '_price', '');
				update_post_meta($pid, 'extra' . $k . '_content', '');
				endif;
			}

			$title_variable = get_post_meta($pid, 'title_variable', true);

			if (!empty($ttl)) $ttl = $title_variable;
			$job_cost = htmlspecialchars($_POST['job_cost']);
			update_post_meta($pid, "title_variable", $ttl);
			update_post_meta($pid, "max_days", trim($_POST['max_days']));

			$wpjobster_enable_dropdown_values = get_option('wpjobster_enable_dropdown_values');
			$wpjobster_enable_free_input_box = get_option('wpjobster_enable_free_input_box');

			if ($wpjobster_enable_free_input_box == "yes") update_post_meta($pid, "price", $job_cost); else
			if ($wpjobster_enable_dropdown_values == "yes") update_post_meta($pid, "price", $job_cost); else {
				$prc = get_option('wpjobster_job_fixed_amount');
				update_post_meta($pid, "price", $prc);
			}

			$ending = get_post_meta($pid, "ending", true);
			$views = get_post_meta($pid, "views", true);
			$closed = get_post_meta($pid, "closed", true);
			update_post_meta($pid, "shipping", trim($_POST['shipping']));

			if (empty($views)) update_post_meta($pid, "views", 0);

			if ($_POST['active'] == '1') update_post_meta($pid, "active", '1'); else update_post_meta($pid, "active", '0');

			if ($_POST['closed'] == '1') {
				update_post_meta($pid, "closed", '1');
			} else {
				if ($closed == "1") update_post_meta($pid, "ending", time() + 30 * 24 * 3600);
				update_post_meta($pid, "closed", '0');
			}

			update_post_meta($pid, "featured",'0');
			update_post_meta($pid, "rating",'0');
			update_post_meta($pid, "wpj_new_rating",'0');

			$home_featured_now = get_post_meta($pid, "home_featured_now", true);
			$category_featured_now = get_post_meta($pid, "category_featured_now", true);
			$subcategory_featured_now = get_post_meta($pid, "subcategory_featured_now", true);

			if (empty($home_featured_now)) { update_post_meta($pid, "home_featured_now", 'z'); }
			if (empty($category_featured_now)) { update_post_meta($pid, "category_featured_now", 'z'); }
			if (empty($subcategory_featured_now)) { update_post_meta($pid, "subcategory_featured_now", 'z'); }

			$lets_meet = trim(strip_tags(htmlspecialchars($_POST['lets_meet'])));

			update_post_meta($pid, "lets_meet", $lets_meet);

			$wpjobster_featured_job_listing = get_option('wpjobster_featured_job_listing');

			if (empty($wpjobster_featured_job_listing)) $wpjobster_featured_job_listing = 30;
			update_post_meta($pid, 'featured_until', (current_time('timestamp', 1) + (3600 * 24 * $wpjobster_featured_job_listing)));

			if (isset($_POST['youtube_link1'])) update_post_meta($pid, "youtube_link1", trim(htmlspecialchars($_POST['youtube_link1'])));
			else update_post_meta($pid, "youtube_link1", '');

			$mi_videos = 0;
			for ($i = 1; $i <= 3; $i++):
			$video_thing = get_post_meta($pid, 'youtube_link' . $i, true);

			if (empty($video_thing)) $mi_videos++;
			endfor;
			update_post_meta($pid, 'has_videos', '1');

			if ($mi_videos == 3) update_post_meta($pid, 'has_videos', '0');

			$wpjobster_location = get_option('wpjobster_location');
			if($wpjobster_location == "yes"){
				if (isset($_POST['location_input']))
					update_post_meta($pid, "location_input", trim(htmlspecialchars($_POST['location_input'])));
				else
					update_post_meta($pid, "location_input", '');

				if (isset($_POST['lat']))
					update_post_meta($pid, "lat", trim(htmlspecialchars($_POST['lat'])));
				else
					update_post_meta($pid, "lat", '');

				if (isset($_POST['long']))
					update_post_meta($pid, "long", trim(htmlspecialchars($_POST['long'])));
				else
					update_post_meta($pid, "long", '');
			}

			if (isset($_POST['images_order'])) {
				$images_order  = htmlspecialchars($_POST['images_order']);

				if ($images_order) {
					$images_order = explode(',', $images_order);
					$i = 1;
					foreach ($images_order as $image) {
						update_post_meta($image, 'images_order', $i);
						$i++;
					}
				}
			}

			if (isset($_POST['fake_queue'])) {
				update_post_meta($pid, "fake_queue", trim(htmlspecialchars($_POST['fake_queue'])));
				update_post_meta($pid, 'fake_queue_exp', '');
			} else {
				update_post_meta($pid, "fake_queue", '');
				update_post_meta($pid, 'fake_queue_exp', '');
			}

			$wpjobster_packages = get_option('wpjobster_packages_enabled');
			update_post_meta( $pid, 'job_packages', $_POST['packages'] );
			if ( $wpjobster_packages == "yes" ) {

				if ( isset( $_POST['package_name'] ) ) {
					update_post_meta( $pid, 'package_name', $_POST['package_name']  );
				}
				if ( isset( $_POST['package_description'] ) ) {
					update_post_meta( $pid, 'package_description', $_POST['package_description']  );
				}
				if ( isset( $_POST['package_max_days'] ) ) {
					update_post_meta( $pid, 'package_max_days', $_POST['package_max_days']  );
				}
				if ( isset( $_POST['package_revisions'] ) ) {
					update_post_meta( $pid, 'package_revisions', $_POST['package_revisions']  );
				}
				if ( isset( $_POST['package_price'] ) ) {
					update_post_meta( $pid, 'package_price', $_POST['package_price']  );
				}
			}

			if ($_POST['rejected_name'] == '1') update_post_meta($pid, "rejected_name", '1');
			else update_post_meta($pid, "rejected_name", '0');

			if ($_POST['rejected_description'] == '1') update_post_meta($pid, "rejected_description", '1');
			else update_post_meta($pid, "rejected_description", '0');

			if ($_POST['rejected_instructions'] == '1') update_post_meta($pid, "rejected_instructions", '1');
			else update_post_meta($pid, "rejected_instructions", '0');

			if ($_POST['rejected_tags'] == '1') update_post_meta($pid, "rejected_tags", '1');
			else update_post_meta($pid, "rejected_tags", '0');

			if ($_POST['rejected_images'] == '1') update_post_meta($pid, "rejected_images", '1');
			else update_post_meta($pid, "rejected_images", '0');

			if ($_POST['rejected_audio'] == '1') update_post_meta($pid, "rejected_audio", '1');
			else update_post_meta($pid, "rejected_audio", '0');

			if ($_POST['rejected_video'] == '1') update_post_meta($pid, "rejected_video", '1');
			else update_post_meta($pid, "rejected_video", '0');

			if ($_POST['rejected_job_preview'] == '1') update_post_meta($pid, "rejected_job_preview", '1');
			else update_post_meta($pid, "rejected_job_preview", '0');

			if ($_POST['rejected_instant_delivery'] == '1') update_post_meta($pid, "rejected_instant_delivery", '1');
			else update_post_meta($pid, "rejected_instant_delivery", '0');

			if ($_POST['rejected_extra1'] == '1') update_post_meta($pid, "rejected_extra1", '1');
			else update_post_meta($pid, "rejected_extra1", '0');

			if ($_POST['rejected_extra2'] == '1') update_post_meta($pid, "rejected_extra2", '1');
			else update_post_meta($pid, "rejected_extra2", '0');

			if ($_POST['rejected_extra3'] == '1') update_post_meta($pid, "rejected_extra3", '1');
			else update_post_meta($pid, "rejected_extra3", '0');
			if ($_POST['rejected_extra4'] == '1') update_post_meta($pid, "rejected_extra4", '1');
			else update_post_meta($pid, "rejected_extra4", '0');
			if ($_POST['rejected_extra5'] == '1') update_post_meta($pid, "rejected_extra5", '1');
			else update_post_meta($pid, "rejected_extra5", '0');
			if ($_POST['rejected_extra6'] == '1') update_post_meta($pid, "rejected_extra6", '1');
			else update_post_meta($pid, "rejected_extra6", '0');
			if ($_POST['rejected_extra7'] == '1') update_post_meta($pid, "rejected_extra7", '1');
			else update_post_meta($pid, "rejected_extra7", '0');
			if ($_POST['rejected_extra8'] == '1') update_post_meta($pid, "rejected_extra8", '1');
			else update_post_meta($pid, "rejected_extra8", '0');
			if ($_POST['rejected_extra9'] == '1') update_post_meta($pid, "rejected_extra9", '1');
			else update_post_meta($pid, "rejected_extra9", '0');
			if ($_POST['rejected_extra10'] == '1') update_post_meta($pid, "rejected_extra10", '1');
			else update_post_meta($pid, "rejected_extra10", '0');

			if (isset($_POST['rejected_name_comment'])) update_post_meta($pid, "rejected_name_comment", trim(htmlspecialchars($_POST['rejected_name_comment'])));
			else update_post_meta($pid, "rejected_name_comment", '');

			if (isset($_POST['rejected_description_comment'])) update_post_meta($pid, "rejected_description_comment", trim(htmlspecialchars($_POST['rejected_description_comment'])));
			else update_post_meta($pid, "rejected_description_comment", '');

			if (isset($_POST['rejected_instructions_comment'])) update_post_meta($pid, "rejected_instructions_comment", trim(htmlspecialchars($_POST['rejected_instructions_comment'])));
			else update_post_meta($pid, "rejected_instructions_comment", '');

			if (isset($_POST['rejected_tags_comment'])) update_post_meta($pid, "rejected_tags_comment", trim(htmlspecialchars($_POST['rejected_tags_comment'])));
			else update_post_meta($pid, "rejected_tags_comment", '');

			if (isset($_POST['rejected_images_comment'])) update_post_meta($pid, "rejected_images_comment", trim(htmlspecialchars($_POST['rejected_images_comment'])));
			else update_post_meta($pid, "rejected_images_comment", '');

			if (isset($_POST['rejected_audio_comment'])) update_post_meta($pid, "rejected_audio_comment", trim(htmlspecialchars($_POST['rejected_audio_comment'])));
			else update_post_meta($pid, "rejected_audio_comment", '');

			if (isset($_POST['rejected_video_comment'])) update_post_meta($pid, "rejected_video_comment", trim(htmlspecialchars($_POST['rejected_video_comment'])));
			else update_post_meta($pid, "rejected_video_comment", '');

			if (isset($_POST['rejected_job_preview_comment'])) update_post_meta($pid, "rejected_job_preview_comment", trim(htmlspecialchars($_POST['rejected_job_preview_comment'])));
			else update_post_meta($pid, "rejected_job_preview_comment", '');

			if (isset($_POST['rejected_instant_delivery_comment'])) update_post_meta($pid, "rejected_instant_delivery_comment", trim(htmlspecialchars($_POST['rejected_instant_delivery_comment'])));
			else update_post_meta($pid, "rejected_instant_delivery_comment", '');

			if (isset($_POST['rejected_extra1_comment'])) update_post_meta($pid, "rejected_extra1_comment", trim(htmlspecialchars($_POST['rejected_extra1_comment'])));
			else update_post_meta($pid, "rejected_extra1_comment", '');

			if (isset($_POST['rejected_extra2_comment'])) update_post_meta($pid, "rejected_extra2_comment", trim(htmlspecialchars($_POST['rejected_extra2_comment'])));
			else update_post_meta($pid, "rejected_extra2_comment", '');

			if (isset($_POST['rejected_extra3_comment'])) update_post_meta($pid, "rejected_extra3_comment", trim(htmlspecialchars($_POST['rejected_extra3_comment'])));
			else update_post_meta($pid, "rejected_extra3_comment", '');
			if (isset($_POST['rejected_extra4_comment'])) update_post_meta($pid, "rejected_extra4_comment", trim(htmlspecialchars($_POST['rejected_extra4_comment'])));
			else update_post_meta($pid, "rejected_extra4_comment", '');
			if (isset($_POST['rejected_extra5_comment'])) update_post_meta($pid, "rejected_extra5_comment", trim(htmlspecialchars($_POST['rejected_extra5_comment'])));
			else update_post_meta($pid, "rejected_extra5_comment", '');
			if (isset($_POST['rejected_extra6_comment'])) update_post_meta($pid, "rejected_extra6_comment", trim(htmlspecialchars($_POST['rejected_extra6_comment'])));
			else update_post_meta($pid, "rejected_extra6_comment", '');
			if (isset($_POST['rejected_extra7_comment'])) update_post_meta($pid, "rejected_extra7_comment", trim(htmlspecialchars($_POST['rejected_extra7_comment'])));
			else update_post_meta($pid, "rejected_extra7_comment", '');
			if (isset($_POST['rejected_extra8_comment'])) update_post_meta($pid, "rejected_extra8_comment", trim(htmlspecialchars($_POST['rejected_extra8_comment'])));
			else update_post_meta($pid, "rejected_extra8_comment", '');
			if (isset($_POST['rejected_extra9_comment'])) update_post_meta($pid, "rejected_extra9_comment", trim(htmlspecialchars($_POST['rejected_extra9_comment'])));
			else update_post_meta($pid, "rejected_extra9_comment", '');
			if (isset($_POST['rejected_extra10_comment'])) update_post_meta($pid, "rejected_extra10_comment", trim(htmlspecialchars($_POST['rejected_extra10_comment'])));
			else update_post_meta($pid, "rejected_extra10_comment", '');

			if( $_POST['post_type'] == 'job' ){
				if ($_POST['post_status'] == 'draft') {
					update_post_meta($pid, 'under_review', '1');
				}
				if ($_POST['post_status'] == 'pending') {
					wpjobster_send_email_allinone_translated('job_decl', false, false, $pid);
					wpjobster_send_sms_allinone_translated('job_decl', false, false, $pid);
				}
				if ($_POST['post_status'] == 'publish') {
					wpjobster_send_sms_allinone_translated('job_acc', false, false, $pid);
					do_action('wpjobster_new_job_completed',$aid, $pid);

					update_post_meta($pid, 'under_review', '0');
				}
			}

			// START REQUEST //
			if ($_POST['req_rejected_name'] == '1') update_post_meta($pid, "req_rejected_name", '1');
			else update_post_meta($pid, "req_rejected_name", '0');

			if ($_POST['req_rejected_description'] == '1') update_post_meta($pid, "req_rejected_description", '1');
			else update_post_meta($pid, "req_rejected_description", '0');

			if ($_POST['req_rejected_tags'] == '1') update_post_meta($pid, "req_rejected_tags", '1');
			else update_post_meta($pid, "req_rejected_tags", '0');

			if ($_POST['req_rejected_category'] == '1') update_post_meta($pid, "req_rejected_category", '1');
			else update_post_meta($pid, "req_rejected_category", '0');

			if ($_POST['req_rejected_deadline'] == '1') update_post_meta($pid, "req_rejected_deadline", '1');
			else update_post_meta($pid, "req_rejected_deadline", '0');

			if ($_POST['req_rejected_budget_from'] == '1') update_post_meta($pid, "req_rejected_budget_from", '1');
			else update_post_meta($pid, "req_rejected_budget_from", '0');

			if ($_POST['req_rejected_budget_to'] == '1') update_post_meta($pid, "req_rejected_budget_to", '1');
			else update_post_meta($pid, "req_rejected_budget_to", '0');

			if ($_POST['req_rejected_attachments'] == '1') update_post_meta($pid, "req_rejected_attachments", '1');
			else update_post_meta($pid, "req_rejected_attachments", '0');

			if (isset($_POST['req_rejected_name_comment'])) update_post_meta($pid, "req_rejected_name_comment", trim(htmlspecialchars($_POST['req_rejected_name_comment'])));
			else update_post_meta($pid, "req_rejected_name_comment", '');

			if (isset($_POST['req_rejected_description_comment'])) update_post_meta($pid, "req_rejected_description_comment", trim(htmlspecialchars($_POST['req_rejected_description_comment'])));
			else update_post_meta($pid, "req_rejected_description_comment", '');

			if (isset($_POST['req_rejected_tags_comment'])) update_post_meta($pid, "req_rejected_tags_comment", trim(htmlspecialchars($_POST['req_rejected_tags_comment'])));
			else update_post_meta($pid, "req_rejected_tags_comment", '');

			if (isset($_POST['req_rejected_category_comment'])) update_post_meta($pid, "req_rejected_category_comment", trim(htmlspecialchars($_POST['req_rejected_category_comment'])));
			else update_post_meta($pid, "req_rejected_category_comment", '');

			if (isset($_POST['req_rejected_deadline_comment'])) update_post_meta($pid, "req_rejected_deadline_comment", trim(htmlspecialchars($_POST['req_rejected_deadline_comment'])));
			else update_post_meta($pid, "req_rejected_deadline_comment", '');

			if (isset($_POST['req_rejected_budget_from_comment'])) update_post_meta($pid, "req_rejected_budget_from_comment", trim(htmlspecialchars($_POST['req_rejected_budget_from_comment'])));
			else update_post_meta($pid, "req_rejected_budget_from_comment", '');

			if (isset($_POST['req_rejected_budget_to_comment'])) update_post_meta($pid, "req_rejected_budget_to_comment", trim(htmlspecialchars($_POST['req_rejected_budget_to_comment'])));
			else update_post_meta($pid, "req_rejected_budget_to_comment", '');

			if (isset($_POST['req_rejected_attachments_comment'])) update_post_meta($pid, "req_rejected_attachments_comment", trim(htmlspecialchars($_POST['req_rejected_attachments_comment'])));
			else update_post_meta($pid, "req_rejected_attachments_comment", '');
			// END REQUEST //
		}

		$post = get_post( $pid );
		$post_type = $post->post_type;
		if(isset($_REQUEST['screen'])){
			if( $post_type == 'job' && $_REQUEST['screen'] == 'edit-job' ) {
				switch ( get_post_status( $pid ) ) {
					case 'publish':
						// A published post or page
						wpjobster_send_email_allinone_translated( 'job_acc', false, false, $pid );
						wpjobster_send_sms_allinone_translated( 'job_acc', false, false, $pid );

						update_post_meta( $pid, 'under_review', '0' );
						break;

					case 'pending':
						// post is pending review
						wpjobster_send_email_allinone_translated( 'job_decl', false, false, $pid );
						wpjobster_send_sms_allinone_translated( 'job_decl', false, false, $pid );
						break;

					case 'draft':
						// a post in draft status
						wpjobster_send_email_allinone_translated('job_new', false, false, $pid);
						wpjobster_send_sms_allinone_translated('job_new', false, false, $pid);

						update_post_meta( $pid, 'under_review', '1' );
						break;
				}
			}
		}

		if(isset($_REQUEST['action'])){
			if( $_REQUEST['action'] == 'editpost' && $post_type == 'job' ) {

				switch ( get_post_status( $pid ) ) {
					case 'draft':
					case 'drafteditpost' :

						wpjobster_send_email_allinone_translated('job_new', false, false, $pid);
						wpjobster_send_sms_allinone_translated('job_new', false, false, $pid);

						update_post_meta( $pid, 'under_review', '1' );
					break;
				}
			}
		}
	}
}

$wpjobster_subscription_enabled = get_option('wpjobster_subscription_enabled');
if($wpjobster_subscription_enabled=='yes'){
	function new_modify_user_table( $column ) {
		$column['subscription'] = 'Subscriptions';
		$column['userlevel'] = 'User Level';

		return $column;
	}
	add_filter( 'manage_users_columns', 'new_modify_user_table' );

	function new_modify_user_table_row( $val, $column_name, $user_id ) {
		$retu = $val;
		switch ($column_name) {
			case 'subscription' :
				include_once get_template_directory() . '/classes/subscriptions/wpjobster_subscription.php';
				$wpjobster_subscription = new wpjobster_subscription($user_id);
				$cur_sub = $wpjobster_subscription->get_current_subscription($user_id);

				if(is_object($cur_sub) && isset($cur_sub->subscription_level)){
					$retu = $cur_sub->subscription_level;
				}else{
					$retu ="level0";
				}
				break;
			case 'userlevel' :
				$retu = wpjobster_get_user_level($user_id);

				break;
			default:
		}
		return $retu;
	}
	add_action( 'manage_users_custom_column', 'new_modify_user_table_row', 15,3 );
}
