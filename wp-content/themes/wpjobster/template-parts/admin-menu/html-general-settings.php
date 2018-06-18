<?php

function wpj_general_settings_html() {

	$arr                         = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));
	$arr_no_first                = array("no" => __("No",'wpjobster'), "yes" => __("Yes",'wpjobster'));
	$max_uploads                 = array("1" => __("1",'wpjobster'), "2" => __("2",'wpjobster'), "3" => __("3",'wpjobster'), "4" => __("4",'wpjobster'), "5" => __("5",'wpjobster'));
	$jobs_order_arr              = array("new" => __("Newest First",'wpjobster'), "old" => __("Oldest First",'wpjobster'), "rand" =>  __("Random",'wpjobster'));
	$adv_search_arr              = array("jobs" => __("Jobs",'wpjobster'), "requests" => __("Requests",'wpjobster'), "users" =>  __("Users",'wpjobster'));
	$user_status_arr             = array("yes_with_text" => __("Yes, with text",'wpjobster'), "yes_with_icon" => __("Yes, with icon",'wpjobster'), "no" =>  __("No",'wpjobster'));
	$locations_unit_arr          = array("kilometers" => __("kilometers",'wpjobster'), "miles" => __("miles",'wpjobster'));
	$location_lets_meet_cond_arr = array("always" => __("Always", 'wpjobster'), "ifchecked" => __("If User Checked Let's Meet",'wpjobster'), "never" => __("Never", 'wpjobster'));
	$view_more_action_cond_arr = array("details" => __("View details",'wpjobster'), "directlink" => __("Direct link", 'wpjobster'));
	$safe_date_format_arr        = array('Y-m-d' => 'ISO 8601 [YYYY-MM-DD]', 'd-m-Y' => 'European [DD-MM-YYYY]', 'd.m.Y' => 'European [DD.MM.YYYY]', 'm/d/Y' => 'American [MM/DD/YYYY]');
	$image_uploader_arr          = array( 'dropzone' => 'DropzoneJS', 'html5fileupload' => 'HTML5 File Upload' );

	$pages                       = new WP_Query(array("post_type"=>"page","posts_per_page"=>-1));
	$arr_pages['']               = __( 'Default', 'wpjobster' );

	while($pages->have_posts()){
		$pages->the_post();
		$arr_pages[get_the_ID()]=get_the_title();
	}

	?>

	<div id="usual2" class="usual">
		<ul>
			<li><a href="#tabs1"><?php _e('Main Settings','wpjobster'); ?></a></li>
			<li><a href="#job-settings"><?php _e('Job Settings', 'wpjobster'); ?></a></li>
			<li><a href="#requests-settings"><?php _e('Request Settings', 'wpjobster'); ?></a></li>
			<li><a href="#user-settings"><?php _e('User Settings', 'wpjobster'); ?></a></li>
			<li><a href="#search-settings"><?php _e('Search Settings', 'wpjobster'); ?></a></li>
			<li><a href="#tabs4"><?php _e('Character Limits','wpjobster'); ?></a></li>
			<li><a href="#tabs2"><?php _e('Filters','wpjobster'); ?></a></li>
			<li><a href="#page-assignments"><?php _e('Page assignments','wpjobster'); ?></a></li>

			<?php do_action('wpjobster_general_options_tabs'); ?>

		</ul>

		<div id="tabs1" >
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=general-options">
				<table width="100%" class="sitemile-table">

				<tr>
					<td></td>
					<td><h2><?php _e("License", "wpjobster"); ?></h2></td>
					<td></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Theme License Key:','wpjobster'); ?></td>
					<td>
						<input type="text" size="30" name="wpjobster_license_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_license_key') ); ?>"/>
						<?php do_action( 'wpjobster_display_license_action_button' ); ?>
					</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Theme License Status:','wpjobster'); ?></td>
					<td><?php do_action( 'wpjobster_display_license_status_text' ); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Theme License Type:','wpjobster'); ?></td>
					<td><?php echo wpjobster_return_license_name(); ?><?php if (the_loop_check() < 3) { ?> - <a href="http://wpjobster.com/buy/" target="_blank">upgrade</a><?php } ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Theme Version:','wpjobster'); ?></td>
					<td><?php echo wpjobster_VERSION; ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Release Date:','wpjobster'); ?></td>
					<td><?php echo wpjobster_RELEASE; ?></td>
				</tr>


				<tr>
					<td></td>
					<td><h2><?php _e("Display", "wpjobster"); ?></h2></td>
					<td></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Jobs Order:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($jobs_order_arr, 'wpjobster_jobs_order'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="240"><?php _e('Enable Auto-Load Posts:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_auto-load'); ?></td>
				</tr>

				<tr>
					<td></td>
					<td><h2><?php _e("Job & Transaction Flow", "wpjobster"); ?></h2></td>
					<td></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="240"><?php _e('Admin approves each job:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_admin_approve_job'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="240"><?php _e('Admin approves each request:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_admin_approve_request'); ?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
					<td ><?php _e('Auto-mark job as completed after:', 'wpjobster');?></td>
					<td><input type="text" size="6" name="wpjobster_max_time_to_wait" value="<?php echo get_option('wpjobster_max_time_to_wait'); ?>"/> <?php _e("hours", "wpjobster");?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__("The number of days that need to pass until the seller is credited with the money for the job he completed", "wpjobster"));?></td>
					<td ><?php _e('Clearing Period:', 'wpjobster');?></td>
					<td><input type="text" size="6" name="wpjobster_clearing_period" value="<?php echo get_option('wpjobster_clearing_period'); ?>"/> <?php _e("days", "wpjobster");?></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__("Limit how many times users can request cancellation for a particular transaction", "wpjobster"));?></td>
					<td ><?php _e('Maximum number of cancellations:', 'wpjobster');?></td>
					<td><input type="text" size="5" name="wpjobster_number_of_cancellations" value="<?php echo get_option('wpjobster_number_of_cancellations'); ?>"/></td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
					<td ><?php _e('Maximum number of modifications:', 'wpjobster');?></td>
					<td><input type="text" size="5" name="wpjobster_number_of_modifications" value="<?php echo get_option('wpjobster_number_of_modifications'); ?>"/></td>
				</tr>
				<tr>
					<td valign=top width="22">
						<?php wpjobster_theme_bullet( __( "How many days should the buyer be able to try again the payment for a pending payment job transaction?", "wpjobster" ) ); ?>
					</td>
					<td width="240">
						<?php _e( 'Auto-close pending payment after:', 'wpjobster' ); ?>
					</td>
					<td>
						<?php $pending_days_arr = range(0,60);
						echo wpjobster_get_option_drop_down( $pending_days_arr, 'wpjobster_pending_jobs_days', '7' ); ?> days
					</td>
				</tr>


				<tr>
					<td></td>
					<td><h2><?php _e("API Keys", "wpjobster"); ?></h2></td>
					<td></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__("If you don't have one, please visit openexchangerates.org and sign up for any of the plans. The smallest plan is good enough as long as it offers at least 100 API Requests per month.", "wpjobster")); ?></td>
					<td><?php _e("OpenExchange App ID:", "wpjobster"); ?></td>
					<td>
						<input type="text" size="30" name="openexchangerates_appid" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('openexchangerates_appid') ); ?>" />
						<?php if (get_option('openexchangerates_appid') == '') { ?>
						  &nbsp; <a href="https://openexchangerates.org/signup/free" target="_blank"><?php _e('Sign Up for a free Open Exchange Rates account', 'wpjobster'); ?></a>
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__("Needed for the Google Maps on the site. If you don't use job locations/maps or have very few visitors, it is not required.", "wpjobster"));?></td>
					<td ><?php _e('Google Maps API Key:', 'wpjobster');?></td>
					<td>
						<input type="text" size="30" name="wpjobster_google_maps_api_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_google_maps_api_key') ); ?>"/>
						<?php if (get_option('wpjobster_google_maps_api_key') == '') { ?>
						  &nbsp; <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php _e('Register a free API Key here', 'wpjobster'); ?></a>
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Needed for the country detection for new users registering on the site. If empty, the country may not be filled automatically by IP.', 'wpjobster')); ?></td>
					<td ><?php _e('IPI Info DB Key (Country Detection):','wpjobster'); ?></td>
					<td><input type="text" size="30" name="wpjobster_ip_key_db" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_ip_key_db') ); ?>"/>
						<?php if (get_option('wpjobster_ip_key_db') == '') { ?>
						  &nbsp; <a href="http://www.ipinfodb.com/register.php" target="_blank"><?php _e('Register a free API Key here', 'wpjobster'); ?></a>
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Needed for the Google reCaptcha login/register.', 'wpjobster')); ?></td>
					<td ><?php _e('reCAPTCHA API Key:','wpjobster'); ?></td>
					<td><input type="text" size="30" name="wpjobster_recaptcha_api_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_recaptcha_api_key') ); ?>"/>
						<?php if (get_option('wpjobster_recaptcha_api_key') == '') { ?>
						  &nbsp; <a href="https://www.google.com/recaptcha/admin#list" target="_blank"><?php _e('Register a free API Key here', 'wpjobster'); ?></a>
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Needed for the Google reCaptcha login/register.', 'wpjobster')); ?></td>
					<td ><?php _e('reCAPTCHA API Secret:','wpjobster'); ?></td>
					<td><input type="text" size="30" name="wpjobster_recaptcha_api_secret" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_recaptcha_api_secret') ); ?>"/>
						<?php if (get_option('wpjobster_recaptcha_api_secret') == '') { ?>
						  &nbsp; <a href="https://www.google.com/recaptcha/admin#list" target="_blank"><?php _e('Register a free API Key here', 'wpjobster'); ?></a>
						<?php } ?>
					</td>
				</tr>


				<tr>
					<td></td>
					<td><h2><?php _e("Other", "wpjobster"); ?></h2></td>
					<td></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Delete credits from site.', 'wpjobster')); ?></td>
					<td width="240"><?php _e('Show credits:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_credits_enable', 'yes'); ?></td>
				</tr>

				<?php $allowed = get_option( 'wpjobster_allowed_mime_types' ); ?>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('To redirect the user to a custom page after registration.', 'wpjobster')); ?></td>
					<td width="240"><?php _e('Allowed Mime-types:','wpjobster'); ?></td>
					<td>
						<input type="text" size="30" name="wpjobster_allowed_mime_types" value="<?php $numItems = count($allowed); $i = 0; if ( $allowed ) { foreach ( $allowed as $val ){ echo trim( $val ); if(++$i !== $numItems) { echo ","; } } } ?>" />
					</td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('To redirect the user to a custom page after registration.', 'wpjobster')); ?></td>
					<td width="240"><?php _e('Register redirection:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr_pages, 'wpjobster_register_redirection_page','', ' class="select2" '); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('When enabled, the notification icons from the header will update automatically without refreshing the whole page.', 'wpjobster')); ?></td>
					<td width="240"><?php _e('Enable live notifications:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_live_notifications', 'no'); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(__('Display country flag on profile page and job pages, based on the country selected by the user.', 'wpjobster')); ?></td>
					<td width="240"><?php _e('Enable country flags:','wpjobster'); ?></td>
					<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_en_country_flags'); ?></td>
				</tr>

				<?php if ( ! wpj_is_allowed( 'custom_offers' ) ) { ?>
				<tr>
					<td colspan="3">
						<?php wpj_disabled_settings_notice( 'custom_offers' ); ?>
					</td>
				</tr>
				<?php } ?>

				<tr class="<?php wpj_disabled_settings_class( 'custom_offers' ); ?>">
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td width="240"><?php _e( 'Enable custom offers:','wpjobster' ); ?></td>
					<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_custom_offers', 'yes' ); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
					<td ><?php _e('Locations Radius Unit:', 'wpjobster');?></td>
					<td><?php echo wpjobster_get_option_drop_down($locations_unit_arr, 'wpjobster_locations_unit', 'kilometers'); ?></td>
				</tr>

				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Slug for Jobs Permalink:','wpjobster'); ?></td>
					<td><input type="text" size="30" name="wpjobster_jobs_permalink" value="<?php echo get_option('wpjobster_jobs_permalink'); ?>"/> *if left empty will show 'jobs'</td>
				</tr>
				<tr>
					<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
					<td ><?php _e('Slug for Category Permalink:','wpjobster'); ?></td>
					<td><input type="text" size="30" name="wpjobster_category_permalink" value="<?php echo get_option('wpjobster_category_permalink'); ?>"/> *if left empty will show 'section'</td>
				</tr>

				<?php do_action('wpjobster_general_settings_main_details_options'); ?>

				<tr>
					<td ></td>
					<td ></td>
					<td><input type="submit" class="button-secondary" name="wpjobster_save1" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

				</table>
			</form>
		</div>

		<div id="job-settings">
			<form method="post" action="<?php bloginfo('url');?>/wp-admin/admin.php?page=general-options&active_tab=job-settings">
				<table width="100%" class="sitemile-table">

					<tr>
						<td></td>
						<td><h2><?php _e("Images", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Mandatory to upload pictures for jobs:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_mandatory_pics_for_jbs'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Amount of Pictures:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_default_nr_of_pics" value="<?php echo get_option('wpjobster_default_nr_of_pics'); ?>"/> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Image upload size:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_max_img_upload_size" value="<?php echo get_option('wpjobster_max_img_upload_size'); ?>"/> <?php _e("MB", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Min Image upload width:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_min_img_upload_width" value="<?php echo get_option('wpjobster_min_img_upload_width'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Min Image upload height:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_min_img_upload_height" value="<?php echo get_option('wpjobster_min_img_upload_height'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Cover Image for Jobs', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_enable_job_cover'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Cover Image minimum upload width:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_min_cover_img_upload_width" value="<?php echo get_option('wpjobster_min_cover_img_upload_width'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>

						<td ><?php _e('Cover Image minimum upload height:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_min_cover_img_upload_height" value="<?php echo get_option('wpjobster_min_cover_img_upload_height'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __('This may not change all of the image uploaders, but will set higher priority on the selected one.', 'wpjobster') );?></td>
						<td><?php _e( 'Preferred Image Uploader:', 'wpjobster' ); ?></td>
						<td><?php echo wpjobster_get_option_drop_down( $image_uploader_arr, 'wpjobster_preferred_image_uploader', 'dropzone'); ?></td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Audio Files", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Audio Files:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_audio'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Mandatory to upload audio for jobs:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_mandatory_audio_for_jbs'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Audio Files Number:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($max_uploads, 'wpjobster_max_uploads_audio'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Audio File Size:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_max_audio_upload_size" value="<?php echo get_option('wpjobster_max_audio_upload_size'); ?>"/> <?php _e("MB", 'wpjobster')?> </td>
					</tr>


					<tr>
						<td></td>
						<td><h2><?php _e("Job Location", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__("Needed for the Google Maps on the site. If you don't use job locations/maps or have very few visitors, it is not required.", "wpjobster"));?></td>
						<td ><?php _e('Google Maps API Key:', 'wpjobster');?></td>
						<td>
						<input type="text" size="30" name="wpjobster_google_maps_api_key" value="<?php echo apply_filters( 'wpj_sensitive_info_credentials', get_option('wpjobster_google_maps_api_key') ); ?>"/>
						<?php if (get_option('wpjobster_google_maps_api_key') == '') { ?>
						&nbsp; <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php _e('Register a free API Key here', 'wpjobster'); ?></a>
						<?php } ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Locations:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_location', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Jobs where the users need to meet in person', 'wpjobster'));?></td>
						<td ><?php _e("Enable Let's meet:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_lets_meet', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e("Display Location Input:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($location_lets_meet_cond_arr, 'wpjobster_location_display_condition', 'always'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Seller can type how much he can travel for a job where he needs to meet the buyer.', 'wpjobster'));?></td>
						<td ><?php _e("Display Distance Input:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($location_lets_meet_cond_arr, 'wpjobster_distance_display_condition', 'never'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('The map will be displayed on the job page, under the description.', 'wpjobster'));?></td>
						<td ><?php _e("Display Location Google Map:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_location_display_map', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Let the user choose whether to display the map or not on his job.', 'wpjobster'));?></td>
						<td ><?php _e("Let the User Choose to Hide Map:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_location_display_map_user_choice', 'no'); ?></td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Delivery & Shipping", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet('This enables or disables the sidebar in the category and archive pages.');?></td>
						<td width="240"><?php _e('Enable Instant Delivery File:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_instant_deli'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="200"><?php _e('Enable Shipping:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_shipping'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="200"><?php _e('Max delivery days:', 'wpjobster');?></td>
						<td><input type="number" name="wpjobster_job_max_delivery_days" value="<?php echo get_option('wpjobster_job_max_delivery_days'); ?>" style="width: 55px;" /> </td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Other", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<?php if ( ! wpj_is_allowed( 'packages' ) ) { ?>
					<tr>
						<td colspan="3">
							<?php wpj_disabled_settings_notice( 'packages' ); ?>
						</td>
					</tr>
					<?php } ?>

					<tr class="<?php wpj_disabled_settings_class( 'packages' ); ?>">
						<td valign=top width="22"><?php wpjobster_theme_bullet( __('This will let logged in users to use packages when a new job is posted.', 'wpjobster') );?></td>
						<td><?php _e( 'Enable Packages:', 'wpjobster' ); ?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr_no_first, 'wpjobster_packages_enabled', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __('This will let logged in users to report any job to admin with a custom message.', 'wpjobster') );?></td>
						<td><?php _e( 'Enable Report Job:', 'wpjobster' ); ?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr_no_first, 'wpjobster_report_job_enabled'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet( __('Allow your users to upload sample files for their job, downloadable by everyone from the single job page.', 'wpjobster') );?></td>
						<td><?php _e( 'Enable Job Preview:', 'wpjobster' ); ?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr_no_first, 'wpjobster_job_attachments_enabled'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Display empty cateogories:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_display_job_empty_categories', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Allow WYSIWYG editor for description:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_allow_wysiwyg_job_description', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Choose whether to require or not the terms of service agreement when posting a new job and how to display it.', 'wpjobster'));?></td>
						<td ><?php _e('Terms of Service Agreement', 'wpjobster');?>:</td>
							<td>
							<?php
							$arr_terms = array(
							"disabled" => __("Disabled"),
							"link" => __("Checkbox and Link"),
							"show_on_page" => __("Checkbox and Full Content"));
							echo wpjobster_get_option_drop_down($arr_terms, "wpjobster_tos_type", "disabled");?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Link to Terms of Service page if the user should agree when posting a job. If you leave it blank, the Terms of Service Agreement will be treated as disabled.', 'wpjobster'));?></td>
						<td ><?php _e('Terms of Service Page Link:', 'wpjobster');?></td>
						<td><input type="text" size="30" name="wpjobster_tos_page_link" value="<?php echo get_option('wpjobster_tos_page_link'); ?>"/> </td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_job_settings" value="<?php _e('Save Options', 'wpjobster');?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="requests-settings">
			<form method="post" action="<?php bloginfo('url');?>/wp-admin/admin.php?page=general-options&active_tab=requests-settings">
				<table width="100%" class="sitemile-table">

					<tr>
						<td></td>
						<td><h2><?php _e("General Request Settings", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Expected Delivery for Requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_max_deliv', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Deadline for Requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_deadline', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Budget for Requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_budget', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable File Upload for Requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_file_upload', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('User need to have at least one active job to send a custom offer:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_active_job_cutom_offer', 'yes'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Display empty cateogories:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_display_request_empty_categories', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="200"><?php _e('Max delivery days:', 'wpjobster');?></td>
						<td><input type="number" name="wpjobster_request_max_delivery_days" value="<?php echo get_option('wpjobster_request_max_delivery_days'); ?>" style="width: 55px;" /> </td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e("View more click action:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($view_more_action_cond_arr, 'wpjobster_view_more_action', 'details'); ?></td>
					</tr>

					<tr>
						<td></td>
						<td><h2><?php _e("Request Location", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Enable Locations for Requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_location', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Requests where the users need to meet in person', 'wpjobster'));?></td>
						<td ><?php _e("Enable Let's meet for Requests:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_lets_meet', 'no'); ?></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e("Display Location Input:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($location_lets_meet_cond_arr, 'wpjobster_request_location_display_condition', 'ifchecked'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Buyer can select the date for a request where he needs to meet the seller.', 'wpjobster'));?></td>
						<td ><?php _e("Display Date Input:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($location_lets_meet_cond_arr, 'wpjobster_request_date_display_condition', 'ifchecked'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Select the date format which should be displayed on the input, from the list of code-friendly date formats.', 'wpjobster'));?></td>
						<td ><?php _e("Date Input Format:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($safe_date_format_arr, 'wpjobster_safe_date_format', 'Y-m-d'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('The map will be displayed on each request, under the description.', 'wpjobster'));?></td>
						<td ><?php _e("Display Location Google Map:", 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_request_location_display_map', 'no'); ?></td>
					</tr>
					<?php do_action( 'add_option_after_request_location' ); ?>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_requests_settings" value="<?php _e('Save Options', 'wpjobster');?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="user-settings">
			<form method="post" action="<?php bloginfo('url');?>/wp-admin/admin.php?page=general-options&active_tab=user-settings">
				<table width="100%" class="sitemile-table">
					<!-- General settings -->
					<tr>
						<td></td>
						<td><h2><?php _e("General settings", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Allow WYSIWYG editor for description:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_wysiwyg_for_profile', 'no' ); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Display my jobs section:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_jobs_section_on_user_profile', 'yes' ); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Default country flag for phone number:','wpjobster'); ?></td>
						<td>
							<select id="wpjobster_phone_country_select" class="grey_input styledselect2 select2" name="wpjobster_phone_country_select">
								<?php
									$tm = get_country_name();
									$selected = get_option('wpjobster_phone_country_select');
									list_options($tm, $selected);
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable or disable the basic user stats bar on my account, sales and shopping pages.', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Enable user stats bar:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_user_stats', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable the advanced user stats charts. User stats bar needs to be active.', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Enable user stats charts:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_user_charts', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Enable user online status:', 'wpjobster');?></td>
						<td>
							<?php echo wpjobster_get_option_drop_down( $user_status_arr, 'wpjobster_en_user_online_status' ); ?>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Let the users choose their country, even after filling it automatically by IP.', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Enable user country select:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_country_select'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Default user time zone:','wpjobster'); ?></td>
						<td>
							<?php $wpjobster_user_time_zone = get_option('wpjobster_user_time_zone'); ?>
							<select id="wpjobster_user_time_zone" class="select2" name="wpjobster_user_time_zone">
								<option <?php if($wpjobster_user_time_zone == 'autodetect'){ echo 'selected'; } ?> value='autodetect'>Autodetect</option>
								<?php
								$tm = get_timezone_name();
								foreach ($tm as $key => $value) {
									$selected = ($wpjobster_user_time_zone == $key) ? 'selected' : '';
									echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="21%"><?php _e('"Jobs By" title on user page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_enable_jobs_title'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="21%"><?php _e('"Last seen" on user page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_enable_last_seen', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="21%"><?php _e('Enable user level icons for thumbnails:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr_no_first, 'wpjobster_user_level_for_thumbnails', 'no'); ?></td>
					</tr>

					<!-- User login/register -->
					<tr>
						<td></td>
						<td><h2><?php _e("User login/register", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Enable or disable the field for phone number on registration page.', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Enable user phone number on registration page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_phone_number', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Phone number mandatory on registration page', 'wpjobster')); ?></td>
						<td width="240"><?php _e('Phone number mandatory on registration:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_phone_number_mandatory', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="240"><?php _e('Enable user company on registration page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_user_company', 'no'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__( 'reCAPTCHA API Key and Secret need to be filled in API Keys from Main Settings TAB' )); ?></td>
						<td width="240"><?php _e('Enable reCapthca on registration/login page:','wpjobster'); ?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_user_reCaptcha', 'no'); ?></td>
					</tr>

					<!-- User portfolio -->
					<tr>
						<td></td>
						<td><h2><?php _e("User portfolio", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Enable user portfolio:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down($arr, 'wpjobster_enable_user_profile_portfolio'); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Amount of Pictures:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_profile_default_nr_of_pics" value="<?php echo get_option('wpjobster_profile_default_nr_of_pics'); ?>"/> </td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Max Image upload size:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_profile_max_img_upload_size" value="<?php echo get_option('wpjobster_profile_max_img_upload_size'); ?>"/> <?php _e("MB", 'wpjobster')?> </td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Min Image upload width:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_profile_min_img_upload_width" value="<?php echo get_option('wpjobster_profile_min_img_upload_width'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td ><?php _e('Min Image upload height:', 'wpjobster');?></td>
						<td><input type="text" size="5" name="wpjobster_profile_min_img_upload_height" value="<?php echo get_option('wpjobster_profile_min_img_upload_height'); ?>"/> <?php _e("px", 'wpjobster')?> </td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_profile_settings" value="<?php _e('Save Options', 'wpjobster');?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="search-settings">
			<form method="post" action="<?php bloginfo('url');?>/wp-admin/admin.php?page=general-options&active_tab=search-settings">
				<table width="100%" class="sitemile-table">

					<tr>
						<td></td>
						<td><h2><?php _e("General settings", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Default search:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $adv_search_arr, 'wpjobster_default_advanced_search' ); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Live search for jobs:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_jobs_for_advanced_search', 'yes' ); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Live search for requests:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_requests_for_advanced_search', 'no' ); ?></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Live search for users:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_users_for_advanced_search', 'yes' ); ?></td>
					</tr>
					<tr>
						<td></td>
						<td><h2><?php _e("User search settings", "wpjobster"); ?></h2></td>
						<td></td>
					</tr>
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet();?></td>
						<td width="240"><?php _e('Location filter:', 'wpjobster');?></td>
						<td><?php echo wpjobster_get_option_drop_down( $arr, 'wpjobster_enable_search_user_location', 'yes' ); ?></td>
					</tr>
					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_search_settings" value="<?php _e('Save Options', 'wpjobster');?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="tabs2">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=general-options&active_tab=tabs2">
				<table width="100%" class="sitemile-table">
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words_pm" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words_pm' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words Error:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words_pm_err" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words_pm_err' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words 2:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words2_pm" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words2_pm' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words 2 Error:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words2_pm_err" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words2_pm_err' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words 3:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words3_pm" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words3_pm' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Words 3 Error:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_words3_pm_err" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_words3_pm_err' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Email Error:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_email" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_email' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Blacklisted Phone Prefixes:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_prefixes_pm" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_prefixes_pm' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(); ?></td>
						<td width="250"><?php _e('Phone Number Error:','wpjobster'); ?></td>
						<td><textarea name="wpjobster_blacklisted_phone" rows="5" cols="50"><?php echo stripslashes( get_option( 'wpjobster_blacklisted_phone' ) ); ?></textarea></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save2" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>

				</table>
			</form>

		</div>

		<div id="tabs4">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=general-options&active_tab=tabs4">
				<table width="100%" class="sitemile-table">
					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Job Title','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_jobtitle_min" value="<?php echo get_option('wpjobster_characters_jobtitle_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_jobtitle_max" value="<?php echo get_option('wpjobster_characters_jobtitle_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Job Description','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_description_min" value="<?php echo get_option('wpjobster_characters_description_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_description_max" value="<?php echo get_option('wpjobster_characters_description_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Job Instructions','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_instructions_min" value="<?php echo get_option('wpjobster_characters_instructions_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_instructions_max" value="<?php echo get_option('wpjobster_characters_instructions_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Job Extra Description','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_extradescription_min" value="<?php echo get_option('wpjobster_characters_extradescription_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_extradescription_max" value="<?php echo get_option('wpjobster_characters_extradescription_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Request Description','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_request_min" value="<?php echo get_option('wpjobster_characters_request_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_request_max" value="<?php echo get_option('wpjobster_characters_request_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Private Message','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_message_min" value="<?php echo get_option('wpjobster_characters_message_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_message_max" value="<?php echo get_option('wpjobster_characters_message_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Personal Info','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_personalinfo_min" value="<?php echo get_option('wpjobster_characters_personalinfo_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_personalinfo_max" value="<?php echo get_option('wpjobster_characters_personalinfo_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td valign=top width="22"><?php wpjobster_theme_bullet(__('Set min and max character limit.')); ?></td>
						<td width="220"><?php _e('Custom Extra','wpjobster'); ?>:</td>
						<td>
						<input type="number" style="width: 65px;" name="wpjobster_characters_customextra_min" value="<?php echo get_option('wpjobster_characters_customextra_min');?>"><?php _e("min", "wpjobster"); ?>
						<input type="number" style="width: 65px;" name="wpjobster_characters_customextra_max" value="<?php echo get_option('wpjobster_characters_customextra_max');?>"><?php _e("max", "wpjobster"); ?>
						</td>
					</tr>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save4" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
					</tr>
				</table>
			</form>
		</div>

		<div id="page-assignments">
			<form method="post" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=general-options&active_tab=page-assignments">
				<table width="100%" class="sitemile-table">

					<?php
					$pages = get_pages();
					$wp_pages = get_wpjobster_page_ids();
					foreach ($wp_pages as $key => $value) { ?>
						<tr>
							<td valign=top width="22"><?php wpjobster_theme_bullet(__('')); ?></td>
							<td width="220"><?php _e( $key,'wpjobster' ); ?>:</td>
							<td>
								<?php $current_page = get_option($value); ?>
								<select class="wpj-page-assignments" id="<?php echo $value; ?>" name="<?php echo $value; ?>">
									<option value="">-</option>
									<?php foreach ($pages as $page) {
										$selected = ($current_page == $page->ID) ? 'selected' : '';
										echo '<option '.$selected.' value="'.$page->ID.'">'.$page->post_title.'</option>';
									} ?>
								</select>
								<span id="<?php echo $value; ?>"><a href="<?php echo admin_url() . 'post.php?post='.$current_page.'&action=edit'; ?>"><?php echo __( 'Edit page','wpjobster' ); ?></a></span>
							</td>
						</tr>
					<?php } ?>

					<tr>
						<td ></td>
						<td ></td>
						<td><input type="submit" class="button-secondary" name="wpjobster_save_page_assignments" value="<?php _e('Save Options','wpjobster'); ?>"/></td>
				</tr>

			</table>
		</form>
	</div>
<?php
}
