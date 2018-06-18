<?php
// JOB METABOXES //
function wpjobster_set_metaboxes(){
	add_meta_box('job_images', 'Job Images', 'wpjobster_theme_job_images', 'job', 'advanced', 'high');
	add_meta_box('job_extra', 'Job Additional Services', 'wpjobster_theme_job_additional', 'job', 'advanced', 'high');

	$wpjobster_packages = get_option('wpjobster_packages_enabled');
	if ( $wpjobster_packages == "yes" ) {
		add_meta_box('job_packages', 'Job Packages', 'wpjobster_theme_job_packages', 'job', 'advanced', 'high');
	}

	add_meta_box('job_dets', 'Job Details', 'wpjobster_theme_job_dts', 'job', 'side', 'high');
	add_meta_box('job_instr', 'Seller Instructions', 'wpjobster_theme_job_instructions', 'job', 'advanced', 'high');
	add_meta_box('job_rejected', 'Job Rejected', 'wpjobster_theme_job_rejected', 'job', 'advanced', 'high');
	add_meta_box('offer_details', 'Offer Details', 'wpjobster_theme_offer_details', 'offer', 'side', 'high');
	do_action('wpjobster_set_meta_boxes');
}

if (!function_exists('wpjobster_theme_job_images')) {

	function wpjobster_theme_job_images() {

		if ( wpjobster_get_preferred_uploader() === 'html5fileupload' ) {

			global $current_user;
			$current_user = wp_get_current_user();
			$cid = $current_user->ID;
			wpjobster_add_html5fileupload_scripts_admin();

			global $post;
			$pid = $post->ID;
			$cwd = str_replace('wp-admin', '', getcwd());
			$cwd .= 'wp-content/uploads';
			?>

			<style>

			.cf:before, .cf:after {
				content: "";
				display: table;
			}

			.cf:after {
				clear: both;
			}

			.cf {
				zoom: 1;
			}

			.cb {
				clear: both;
			}

			.div_div,
			.div_div_input {
				float: left;
				width: 90px;
				height: 90px;
				margin: 0px 25px 25px 0;
				position: relative;
			}

			.delete-this {
				display: none;
				width: 0;
				height: 0;
				border-left: 32px solid transparent;
				border-top: 32px solid #f00;
				position: absolute;
				top: 0;
				right: 0;
			}

			.delete-this:after {
				content: "";
				background: url("<?php echo get_template_directory_uri(); ?>/images/icons-sprite.png") no-repeat -69px -19px;
				position: absolute;
				display: block;
				overflow: hidden;
				width: 13px;
				height: 13px;
				top: -29px;
				right: 3px;
			}

			.div_div:hover .delete-this {
				display: block;
			}

			.div_div_input {
				cursor: pointer;
				background-color: #f3f3f3;
				border: 1px dashed #cccccc;
			}

			.div_div_input:after {
				content: "";
				background: url("<?php echo get_template_directory_uri(); ?>/images/icons-sprite.png") no-repeat -57px -57px;
				position: absolute;
				display: block;
				overflow: hidden;
				width: 36px;
				height: 36px;
				top: 27px;
				left: 27px;
				opacity: 0.5;
			}

			.div_div_input:hover:after {
				opacity: 1;
			}

			.div_div_input.uploading:after {
				content: "";
				background: url("<?php echo get_template_directory_uri(); ?>/images/notifications-loading.gif") no-repeat;
				position: absolute;
				display: block;
				overflow: hidden;
				width: 16px;
				height: 16px;
				top: 37px;
				left: 37px;
				opacity: 1;
			}

			.hidden_input {
				width: 0px;
				height: 0px;
				overflow: hidden;
				opacity: 0;
			}

			</style>


			<script>

			//sortable thumbnails
			jQuery(window).ready(function(){


				jQuery( "#thumbnails" ).sortable({

					connectWith: '#thumbnails',

					start: function(event, ui) {

					},
					change: function(event, ui) {

					},
					update: function(event, ui) {
						var images_order = jQuery( "#thumbnails" ).sortable('toArray', { attribute: 'image_id' });
						jQuery( "#images_order" ).val( images_order );
					}
				});

				jQuery( "#thumbnails div" ).disableSelection();

			})


			</script>

			<?php if (!is_demo_admin()) { ?>
				<script type="text/javascript">
				function delete_this(id)
				{
					 jQuery.ajax({
									method: 'get',
									url : '<?php echo get_bloginfo('url'); ?>/index.php/?_ad_delete_pid='+id,
									dataType : 'text',
									success: function (text) {
										jQuery('#image_ss'+id).remove();
									}
								 });
				}

				</script>
			<?php } ?>

			<div id="thumbnails" class="cf" style="margin-top:20px">
			<?php

					$i = 0;
					$attachments = wpjobster_get_job_images( $pid );

					if ($attachments) {
						foreach ($attachments as $attachment) {
							$url = wpj_get_attachment_image_url( $attachment->ID, array( 90, 90 ) );
							echo '<div class="div_div"  id="image_ss' . $attachment->ID . '" image_id="' . $attachment->ID . '"><img width="90" class="image_class" height="90" src="' . $url . '" />
					<a href="javascript: void(0)" onclick="delete_this(\'' . $attachment->ID . '\')" class="delete-this"></a>
					</div>';
						}

					}

					?>


			</div>

			<input type="hidden" name="images_order" id="images_order" />

			<div class="col-xs-12 job-images-input-container">
				<div class="html5fileupload demo_multi" data-max-filesize="<?php echo $allowed_size?>" data-remove-done="true" data-autostart="true" data-valid-mime="image.*"  data-url="<?php echo get_template_directory_uri(); ?>/lib/html5fileupload/html5fileupload.php" data-multiple="true" style="width: 100%;">
					<input type="file" name="file" />
				</div>
				<script>
				jQuery(document).ready(function($){
					$.html5fileupload.defaults = {
						showErrors:			true,

						url:				null,
						downloadUrl:		null,
						removeUrl:			null,

						removeDone:			false,
						removeDoneDelay:	1200,

						file:				null,

						edit:				true,
						randomName:			false,
						randomNameLength:	8,

						form:				false,

						data:				{ID:'<?php echo $pid ?>',author:<?php echo $cid;?>},

						ajax:				true,
						ajaxType:			'POST',
						ajaxDataType:		'json',
						ajaxHeaders:		{},

						multiple:			false,

						validExtensions:	null,
						validMime:			null,
						labelInvalid:		null,

						autostart:			false,

						minFilesize:		0,
						maxFilesize:		2048000,
						labelMinFilesize:	null,
						labelMaxFilesize:	null,


						regexp:				/^[^\\/:\*\?"<>\|]+$/,

					}
					$('.html5fileupload.demo_multi').html5fileupload({
							onAfterStartSuccess: function(response) {
								if(response.error==''){
									$('#thumbnails').append('<div class="div_div" id="image_ss'+response.attach_id+'" image_id="'+response.attach_id+'" ><img width="90" class="image_class" height="90" src="' + response.go + '" /><a href="javascript: void(0)" onclick="delete_this('+ response.attach_id +')" class="delete-this"></a></div>');
								}else{
									alert(response.error);
								}
							}
					});
				});
				</script>
			</div>

		<?php
		} else {
			global $post;
			$pid = $post->ID;
			if ( wpj_bool_option( 'wpjobster_enable_job_cover' ) ) {
				wpjobster_dropzone_cover_uploader( $pid );
			}
			wpjobster_dropzone_image_uploader( $pid );
		}
	}
}

function wpjobster_theme_job_additional(){
	global $post;
	$pid = $post->ID;
	$aid = $post->post_author;
	$user_level = wpjobster_get_user_level($aid);
	$sts = get_option('wpjobster_get_level'.$user_level.'_extras');
	if (!is_numeric($sts)) $sts = 3;
	// display the number of extras allowed for this author
	echo "<h3>" . sprintf(__("This user is allowed to have max %s extras.", "wpjobster"), $sts) . "</h3>"; ?>

	<table width="100%">
		<?php
		// display all the possible extras for the admin
		for ($k = 1; $k <= 10; $k++) {
			?>
			 <tr><td width="200" >
							<?php
				 _e('For an extra', 'wpjobster'); ?> <input type="text" size="3" name="extra<?php
				 echo $k; ?>_price"
							value="<?php
				 echo get_post_meta($pid, 'extra' . $k . '_price', true); ?>" /><?php
				 echo wpjobster_get_currency_classic(); ?>
							 &nbsp; &nbsp; </td><td>  <textarea name="extra<?php
				 echo $k; ?>_content" cols="40" rows="2"><?php
				 echo get_post_meta($pid, 'extra' . $k . '_content', true); ?></textarea></td></tr>
				<?php
		} ?>
	</table>
<?php }

if ( !function_exists( 'wpjobster_theme_job_packages' ) ) {
	function wpjobster_theme_job_packages() {
		global $post;
		$pid = $post->ID;

		$package_name = get_post_meta( $pid, 'package_name', true );
		$package_description = get_post_meta( $pid, 'package_description', true );
		$package_max_days = get_post_meta( $pid, 'package_max_days', true );
		$package_revisions = get_post_meta( $pid, 'package_revisions', true );
		$package_price = get_post_meta( $pid, 'package_price', true );
		$package_custom_fields = get_post_meta( $pid, 'package_custom_fields', true );

		$packages = get_post_meta( $pid, 'job_packages', true ); ?>

		<table class="packages" width="100%">
			<thead>
				<tr>
					<th><?php echo __( 'Enable packages', 'wpjobster'); ?></th>
					<th>
						<select name="packages">
							<option <?php if ( $packages == 'yes' ) echo 'selected'; ?> value="yes"><?php echo __( 'Yes' ); ?></option>
							<option <?php if ( $packages == 'no' ) echo 'selected'; ?> value="no"><?php echo __( 'No' ); ?></option>
						</select>
					</th>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<th></th>
					<th><?php echo __( 'BASIC', 'wpjobster'); ?></th>
					<th><?php echo __( 'STANDARD', 'wpjobster'); ?></th>
					<th><?php echo __( 'PREMIUM', 'wpjobster'); ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th><?php echo __( 'Package name', 'wpjobster'); ?></th>
					<?php if ( $package_name ) {
							foreach ( $package_name as $p_n_key => $p_name ) { ?>
							<td>
								<input name="package_name[]" maxlength="35" type="text" placeholder="<?php echo __( 'Name your package', 'wpjobster'); ?>" value="<?php echo $p_name; ?>">
							</td>
						<?php }
					} else {
						for( $i=0; $i<3; $i++ ) { ?>
							<td>
								<input name="package_name[]" maxlength="35" type="text" placeholder="<?php echo __( 'Name your package', 'wpjobster'); ?>">
							</td>
						<?php }
					} ?>
				</tr>
				<tr>
					<th><?php echo __( 'Package description', 'wpjobster'); ?></th>
					<?php if ( $package_description ) {
						foreach ( $package_description as $p_d_key => $p_desc ) { ?>
							<td>
								<textarea rows="10" name="package_description[]" type="text" placeholder="<?php echo __( 'Describe the details of your offering', 'wpjobster'); ?>"><?php echo $p_desc; ?></textarea>
							</td>
						<?php }
					} else {
						for( $i=0; $i<3; $i++ ) { ?>
							<td>
								<textarea rows="10" name="package_description[]" type="text" placeholder="<?php echo __( 'Describe the details of your offering', 'wpjobster'); ?>"></textarea>
							</td>
						<?php }
					} ?>
				</tr>

				<?php if( $package_custom_fields ) {
					foreach ( $package_custom_fields as $key => $value ) { ?>
						<tr>
							<th><?php echo $value['name']; ?></th>
							<td>
								<?php if ( $value['basic'] == 'on' ){
									echo '<span class="dashicons dashicons-yes"></span>';
								} else {
									echo '<span class="dashicons dashicons-no-alt"></span>';
								} ?>
							</td>
							<td>
								<?php if ( $value['standard'] == 'on' ){
									echo '<span class="dashicons dashicons-yes"></span>';
								} else {
									echo '<span class="dashicons dashicons-no-alt"></span>';
								} ?>
							</td>
							<td>
								<?php if ( $value['premium'] == 'on' ){
									echo '<span class="dashicons dashicons-yes"></span>';
								} else {
									echo '<span class="dashicons dashicons-no-alt"></span>';
								} ?>
							</td>
						</tr>
					<?php }
				} ?>
				<tr>
					<th><?php echo __( 'Package delivery time', 'wpjobster'); ?></td>
					<?php if ( $package_max_days ) {
						foreach ( $package_max_days as $p_md_key => $p_max_days ) { ?>
							<td>
								<select id="max_days" name="package_max_days[]">
									<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
									<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
										<option value="<?php echo $i_count ?>" <?php echo ($i_count==$p_max_days?' selected="selected=" ':""); ?>>
											<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
										</option>
									<?php } ?>
								</select>
							</td>
						<?php }
					} else {
						for( $i=0; $i<3; $i++ ) { ?>
							<td>
								<select id="max_days" name="package_max_days[]">
									<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
									<?php for($i_count=1;$i_count<=get_option( 'wpjobster_job_max_delivery_days' );$i_count++){ ?>
										<option value="<?php echo $i_count ?>">
											<?php echo sprintf( _n( '%d day', '%d days',$i_count, 'wpjobster' ), $i_count);?>
										</option>
									<?php } ?>
								</select>
							</td>
						<?php }
					} ?>
				</tr>
				<tr>
					<th><?php echo __( 'Package revisions', 'wpjobster'); ?></td>
					<?php if ( $package_revisions ) {
						foreach ( $package_revisions as $p_r_key => $p_revisions ) { ?>
							<td>
								<select name="package_revisions[]">
									<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
									<?php for($i_count=1;$i_count<=9;$i_count++){ ?>
										<option value="<?php echo $i_count ?>" <?php echo ($i_count==$p_revisions?' selected="selected=" ':""); ?>>
											<?php echo $i_count;?>
										</option>
									<?php } ?>
									<option <?php if ( $p_revisions == 'unlimited' ) echo 'selected'; ?> value="unlimited"><?php echo __( 'Unlimited', 'wpjobster' ); ?></option>
								</select>
							</td>
						<?php }
					} else {
						for( $i=0; $i<3; $i++ ) { ?>
							<td>
								<select name="package_revisions[]">
									<option value="" disabled selected hidden><?php echo __("Please Select","wpjobster");?></option>
									<?php for($i_count=1;$i_count<=9;$i_count++){ ?>
										<option value="<?php echo $i_count ?>">
											<?php echo $i_count;?>
										</option>
									<?php } ?>
									<option value="unlimited"><?php echo __( 'Unlimited', 'wpjobster' ); ?></option>
								</select>
							</td>
						<?php }
					} ?>
				</tr>
				<tr>
					<th><?php echo __( 'Package price', 'wpjobster') . '&nbsp;('.wpjobster_get_currency_symbol(wpjobster_get_currency_classic()).')'; ?></th>
					<?php if ( $package_price ) {
						foreach ( $package_price as $p_p_key => $p_price ) { ?>
							<td>
								<input name="package_price[]" type="number" placeholder="<?php echo __( 'Insert package price in USD', 'wpjobster'); ?>" value="<?php echo $p_price; ?>">
							</td>
						<?php }
					} else {
						for( $i=0; $i<3; $i++ ) { ?>
							<td>
								<input name="package_price[]" type="number" placeholder="<?php echo __( 'Insert package price in USD', 'wpjobster'); ?>" value="<?php echo $p_price; ?>">
							</td>
						<?php }
					} ?>
				</tr>
			</tbody>
		</table>
		<?php
	}
}

if (!function_exists('wpjobster_theme_job_dts')) {

	function wpjobster_theme_job_dts() {
		global $post;
		$pid = $post->ID;
		$price = get_post_meta($pid, "price", true);
		$location = get_post_meta($pid, "Location", true);
		$f = get_post_meta($pid, "featured", true);
		$t = get_post_meta($pid, "closed", true);
		$active = get_post_meta($pid, "active", true);

		$wpjobster_location = get_option('wpjobster_location');

		if ($wpjobster_location == "yes" ) {
			$wpjobster_google_maps_api_key = get_option('wpjobster_google_maps_api_key');
			if ($wpjobster_google_maps_api_key != '') {
				$maps_key_url = 'key=' . $wpjobster_google_maps_api_key . '&';
			} else {
				$maps_key_url = '';
			}
			wp_enqueue_script('maps-api', 'https://maps.googleapis.com/maps/api/js?' . $maps_key_url . 'v=3.exp&libraries=places', array('jquery'));
		}

		wp_enqueue_script('script', get_template_directory_uri() . '/script.js', array('jquery'), wpjobster_VERSION);

		?>

		<ul id="post-new4">
			<input name="fromadmin" type="hidden" value="1" />
		 	<li>
				<h2><?php echo __('Job Price', 'wpjobster'); ?>:</h2>
				<p>
					 <?php
					$wpjobster_enable_dropdown_values = get_option('wpjobster_enable_dropdown_values');
					$wpjobster_enable_free_input_box = get_option('wpjobster_enable_free_input_box');

					if ($wpjobster_enable_free_input_box == "yes") {

						if (wpjobster_show_price_in_front() == true)                echo wpjobster_get_currency_classic();
						echo ' <input type="text" name="job_cost" class="do_input" value="' . get_post_meta($pid, 'price', true) . '" size="5" /> ';

						if (wpjobster_show_price_in_front() == false)                echo wpjobster_get_currency_classic();
					} else
					if ($wpjobster_enable_dropdown_values == "yes") {
						echo wpjobster_get_variale_cost_dropdown('do_input', get_post_meta($pid, 'price', true));
					} else echo wpjobster_get_show_price(get_option('wpjobster_job_fixed_amount'));
					?>
				</p>
			</li>

			<?php
			$wpjobster_location = get_option('wpjobster_location');
			if($wpjobster_location == "yes"){ ?>

				<li>

					<h2><?php echo __('Location', 'wpjobster'); ?>:</h2>
					<p class="lighter">
						<input class="grey_input uz-listen1" type="text" data-replaceplaceholder="<?php _e('Select a valid location','wpjobster') ?>" placeholder="<?php _e('Location','wpjobster') ?>" id="location_input"
						value="<?php echo get_post_meta($pid, 'location_input', true); ?>" name="location_input">
						<input id="lat" type="hidden" name="lat"  id="lat" value="<?php echo get_post_meta($pid, 'lat', true); ?>">
						<input id="long" type="hidden" name="long"  id="long" value="<?php echo get_post_meta($pid, 'long', true); ?>">
					</p>

				</li>

			<?php } ?>

			<li>
				<h2><?php echo __('Delivery', 'wpjobster'); ?>:</h2>
				<p><input type="text" size="10" name="max_days" class="do_input" value="<?php echo get_post_meta($pid, 'max_days', true); ?>" /> days</p>
			</li>
			<li>
				<h2><?php echo __('Youtube Video Link', 'wpjobster'); ?>:</h2>
				<p><input type="text" size="10" name="youtube_link1" class="do_input" value="<?php echo get_post_meta($pid, 'youtube_link1', true); ?>" /></p>
			</li>
			<?php
			$wpjobster_enable_shipping = get_option('wpjobster_enable_shipping');
			if ($wpjobster_enable_shipping == "yes"){ ?>
				<li>
					<h2><?php echo __('Requires shipping?', 'wpjobster'); ?>:</h2>
					<p>
						<?php if (wpjobster_show_price_in_front()) echo wpjobster_get_currency_classic(); ?>
						<input type="text" size="5" class="do_input"  name="shipping" value="<?php
						echo (empty($shipping) ? get_post_meta($pid, 'shipping', true) :$shipping); ?>" />
						<?php if (!wpjobster_show_price_in_front()) echo wpjobster_get_currency_classic(); ?>
					</p>
				</li>
			<?php } ?>
			<li>
				<h2><?php _e("Active Job?", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="active" <?php if ($active == '1') echo ' checked="checked" '; ?> /></p>
			</li>
			<li>
				<h2><?php _e("Closed", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="closed" <?php if ($t == '1') echo ' checked="checked" '; ?> /></p>
			</li>
			<li>
				<h2><?php _e("Let's Meet", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="yes" name="lets_meet" <?php if (get_post_meta($pid, 'lets_meet', true) == 'yes') echo ' checked="checked" '; ?> /></p>
			</li>
			<li>
				<h2><?php echo __('Fake Queue', 'wpjobster'); ?>:</h2>
				<p><input type="text" size="5" name="fake_queue" class="do_input" value="<?php echo get_post_meta($pid, 'fake_queue', true); ?>" /></p>
			</li>
		</ul>
	<?php }
}

function wpjobster_theme_job_instructions(){
	global $post; ?>
	<textarea cols="60" rows="5" name="instruction_box"><?php
		echo get_post_meta($post->ID, 'instruction_box', true); ?>
	</textarea>
<?php }

if (!function_exists('wpjobster_theme_job_rejected')) {

	function wpjobster_theme_job_rejected()    {
		global $post;
		$pid = $post->ID;
		$aid = $post->post_author;
		$user_level = wpjobster_get_user_level($aid);
		$sts = get_option('wpjobster_get_level'.$user_level.'_extras');

		$rejected_name = get_post_meta($pid, "rejected_name", true);
		$rejected_description = get_post_meta($pid, "rejected_description", true);
		$rejected_instructions = get_post_meta($pid, "rejected_instructions", true);
		$rejected_tags = get_post_meta($pid, "rejected_tags", true);
		$rejected_images = get_post_meta($pid, "rejected_images", true);
		$rejected_audio = get_post_meta($pid, "rejected_audio", true);
		$rejected_video = get_post_meta($pid, "rejected_video", true);
		$rejected_job_preview = get_post_meta($pid, "rejected_job_preview", true);
		$rejected_instant_delivery = get_post_meta($pid, "rejected_instant_delivery", true);
		$rejected_extra[1] = get_post_meta($pid, "rejected_extra1", true);
		$rejected_extra[2] = get_post_meta($pid, "rejected_extra2", true);
		$rejected_extra[3] = get_post_meta($pid, "rejected_extra3", true);
		$rejected_extra[4] = get_post_meta($pid, "rejected_extra4", true);
		$rejected_extra[5] = get_post_meta($pid, "rejected_extra5", true);
		$rejected_extra[6] = get_post_meta($pid, "rejected_extra6", true);
		$rejected_extra[7] = get_post_meta($pid, "rejected_extra7", true);
		$rejected_extra[8] = get_post_meta($pid, "rejected_extra8", true);
		$rejected_extra[9] = get_post_meta($pid, "rejected_extra9", true);
		$rejected_extra[10] = get_post_meta($pid, "rejected_extra10", true);

		$rejected_name_comment = get_post_meta($pid, "rejected_name_comment", true);
		$rejected_description_comment = get_post_meta($pid, "rejected_description_comment", true);
		$rejected_instructions_comment = get_post_meta($pid, "rejected_instructions_comment", true);
		$rejected_tags_comment = get_post_meta($pid, "rejected_tags_comment", true);
		$rejected_images_comment = get_post_meta($pid, "rejected_images_comment", true);
		$rejected_audio_comment = get_post_meta($pid, "rejected_audio_comment", true);
		$rejected_video_comment = get_post_meta($pid, "rejected_video_comment", true);
		$rejected_job_preview_comment = get_post_meta($pid, "rejected_job_preview_comment", true);
		$rejected_instant_delivery_comment = get_post_meta($pid, "rejected_instant_delivery_comment", true);
		$rejected_extra1_comment = get_post_meta($pid, "rejected_extra1_comment", true);
		$rejected_extra2_comment = get_post_meta($pid, "rejected_extra2_comment", true);
		$rejected_extra3_comment = get_post_meta($pid, "rejected_extra3_comment", true);
		$rejected_extra4_comment = get_post_meta($pid, "rejected_extra4_comment", true);
		$rejected_extra5_comment = get_post_meta($pid, "rejected_extra5_comment", true);
		$rejected_extra6_comment = get_post_meta($pid, "rejected_extra6_comment", true);
		$rejected_extra7_comment = get_post_meta($pid, "rejected_extra7_comment", true);
		$rejected_extra8_comment = get_post_meta($pid, "rejected_extra8_comment", true);
		$rejected_extra9_comment = get_post_meta($pid, "rejected_extra9_comment", true);
		$rejected_extra10_comment = get_post_meta($pid, "rejected_extra10_comment", true);
		?>

		<table width="100%">

			<tr>
				<td><?php _e("Rejected Name", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_name" <?php if ($rejected_name == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_name_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_name_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Description", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_description" <?php if ($rejected_description == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_description_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_description_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Instructions", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_instructions" <?php if ($rejected_instructions == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_instructions_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_instructions_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Tags", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_tags" <?php if ($rejected_tags == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_tags_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_tags_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Images", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_images" <?php if ($rejected_images == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_images_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_images_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Audio", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_audio" <?php if ($rejected_audio == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_audio_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_audio_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Video", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_video" <?php if ($rejected_video == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_video_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_video_comment', true); ?></textarea></td>
			</tr>

			<?php if (empty($sts))        $sts = 10;
			for ($k = 1; $k <= $sts; $k++) { ?>
			<tr>
				<td><?php echo sprintf(__("Rejected Extra %d", 'wpjobster'), $k); ?></td>
				<td><input type="checkbox" value="1" name="rejected_extra<?php echo $k; ?>" <?php if ($rejected_extra[$k] == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_extra<?php echo $k; ?>_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_extra'.$k.'_comment', true); ?></textarea></td>
			</tr>
			<?php } ?>

			<tr>
				<td><?php _e("Rejected Job Preview", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_job_preview" <?php if ($rejected_job_preview == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_job_preview_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_job_preview_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Instant Delivery", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="rejected_instant_delivery" <?php if ($rejected_instant_delivery == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="rejected_instant_delivery_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'rejected_instant_delivery_comment', true); ?></textarea></td>
			</tr>

		</table>
	<?php }
}

if (!function_exists('wpjobster_theme_offer_details')) {

	function wpjobster_theme_offer_details() {
		global $post;
		$pid = $post->ID;
		$price = get_post_meta($pid, "price", true);
		$t = get_post_meta($pid, "closed", true);
		$active = get_post_meta($pid, "active", true);
		$buyer_id = get_post_meta($pid, "offer_buyer", true);
		$buyer_data = get_userdata($buyer_id);
		?>
		<ul id="post-new4">
			<input name="fromadmin" type="hidden" value="1" />
			<li>
				<h2><?php echo __('Buyer', 'wpjobster'); ?>: <strong><?php echo $buyer_data->user_login; ?></strong></h2>
			</li>
			<li>
				<h2><?php echo __('Job Price', 'wpjobster'); ?>:</h2>
				<p>
				<?php
				$wpjobster_enable_dropdown_values = get_option('wpjobster_enable_dropdown_values');
				$wpjobster_enable_free_input_box = get_option('wpjobster_enable_free_input_box');

				if ($wpjobster_enable_free_input_box == "yes") {

					if (wpjobster_show_price_in_front() == true)                echo wpjobster_get_currency();
					echo ' <input type="text" name="job_cost" class="do_input" value="' . get_post_meta($pid, 'price', true) . '" size="5" /> ';

					if (wpjobster_show_price_in_front() == false)                echo wpjobster_get_currency();
				} else
				if ($wpjobster_enable_dropdown_values == "yes") {
					echo wpjobster_get_variale_cost_dropdown('do_input', get_post_meta($pid, 'price', true));
				} else echo wpjobster_get_show_price(get_option('wpjobster_job_fixed_amount'));
				?>
				</p>
			</li>
			<li>
				<h2><?php echo __('Delivery', 'wpjobster'); ?>:</h2>
				<p><input type="text" size="10" name="max_days" class="do_input"
				value="<?php echo get_post_meta($pid, 'max_days', true); ?>" /> days</p>
			</li>
			<li>
				<h2><?php _e("Offer Accepted?", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="active" <?php if (get_post_meta($pid, 'offer_accepted', true) == 1) echo ' checked="checked" '; ?> disabled /></p>
			</li>
			<li>
				<h2><?php _e("Offer Declined?", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="active" <?php if (get_post_meta($pid, 'offer_declined', true) == 1) echo ' checked="checked" '; ?> disabled /></p>
			</li>
			<li>
				<h2><?php _e("Offer Withdrawn?", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="active" <?php if (get_post_meta($pid, 'offer_withdrawn', true) == 1) echo ' checked="checked" '; ?> disabled /></p>
			</li>
			<li>
				<h2><?php _e("Offer Expired?", 'wpjobster'); ?>:</h2>
				<p><input type="checkbox" value="1" name="active" <?php if (get_post_meta($pid, 'offer_expired', true) == 1) echo ' checked="checked" '; ?> disabled /></p>
			</li>

		</ul>
	<?php }
}
// END JOB METABOXES //

// REQUEST METABOXES //
add_action("admin_init", "admin_init");
function admin_init() {
	add_meta_box('job_budget_from', 'Budget From', 'wpjobster_job_budget_from', 'request', 'side', 'default');
	add_meta_box('job_budget_to', 'Budget To', 'wpjobster_job_budget_to', 'request', 'side', 'default');
	add_meta_box('job_max_days_to_deliver', 'Max days to deliver', 'wpjobster_job_max_days_to_deliver', 'request', 'side', 'default');
	add_meta_box('req_attachments', 'Attachments', 'wpjobster_request_attchments', 'request', 'side', 'default');
	add_meta_box('job_attachments', 'Instant Delivery Attachments', 'wpjobster_job_attchments', 'job', 'side', 'default');
	add_meta_box('preview_job_attchments', 'Job Preview', 'wpjobster_preview_job_attchments', 'job', 'side', 'default');
	add_meta_box('request_deadline', 'Deadline', 'wpjobster_req_deadline', 'request', 'side', 'default');
	add_meta_box('request_rejected', 'Request Rejected', 'wpjobster_theme_request_rejected', 'request', 'advanced', 'high');
}

function wpjobster_job_budget_from(){
	global $post;
	$value = get_post_custom($post->ID);
	$budget_from = ( isset( $value["budget_from"][0] ) && $value["budget_from"][0] != '' ) ? $value["budget_from"][0] : '';
	echo '<input type="text" name="budget_from" value="'.$budget_from.'" id="budget_from" />';
}

function wpjobster_job_budget_to(){
	global $post;
	$value = get_post_custom($post->ID);
	$budget = ( isset( $value["budget"][0] ) && $value["budget"][0] != '' ) ? $value["budget"][0] : '';
	echo '<input type="text" name="budget" value="'.$budget.'" id="budget" />';
}

function wpjobster_job_max_days_to_deliver() {
	global $post;
	$value = get_post_custom($post->ID);
	$job_delivery = ( isset( $value["job_delivery"][0] ) && $value["job_delivery"][0] != '' ) ? $value["job_delivery"][0] : '';
	echo '<input type="text" name="job_delivery" value="'.$job_delivery.'" id="job_delivery" />';
}

function wpjobster_request_attchments(){
	global $post;
	$value = get_post_custom($post->ID);
	$req_attachments = isset($value["req_attachments"][0]) ? $value["req_attachments"][0] : '';
	if ($req_attachments) {
		$attachments = explode(",", $req_attachments);
		foreach ($attachments as $attachment) {
			if($attachment != ''){
				echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
				echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div>';
			}
		}
	}
}

function wpjobster_job_attchments(){
	global $post;
	$value = get_post_custom($post->ID);
	$instant_attachments = isset($value["job_any_attachments"][0]) ? $value["job_any_attachments"][0] : '';
	if ($instant_attachments) {
		$attachments = explode(",", $instant_attachments);
		foreach ($attachments as $attachment) {
			if($attachment != ''){
				echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
				echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div>';
			}
		}
	}
}

function wpjobster_preview_job_attchments(){
	global $post;
	$value = get_post_custom($post->ID);
	$preview_job_attchments = isset($value["preview_job_attchments"][0]) ? $value["preview_job_attchments"][0] : '';
	if ($preview_job_attchments) {
		$attachments = explode(",", $preview_job_attchments);
		foreach ($attachments as $attachment) {
			if($attachment != ''){
				echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
				echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div>';
			}
		}
	}
}

function wpjobster_req_deadline(){
	global $post;
	$value = get_post_custom($post->ID);
	$wpjobster_safe_date_format = get_option('wpjobster_safe_date_format');
	$request_deadline = isset($value["request_deadline"][0]) ? date($wpjobster_safe_date_format ? $wpjobster_safe_date_format : 'Y-m-d', $value["request_deadline"][0]) : '';
	echo '<input type="text" name="request_deadline" value="'.$request_deadline.'" id="request_deadline" />';
}

if (!function_exists('wpjobster_theme_request_rejected')) {
	function wpjobster_theme_request_rejected() {
		global $post;
		$pid = $post->ID;

		$req_rejected_name = get_post_meta($pid, "req_rejected_name", true);
		$req_rejected_description = get_post_meta($pid, "req_rejected_description", true);
		$req_rejected_tags = get_post_meta($pid, "req_rejected_tags", true);
		$req_rejected_deadline = get_post_meta($pid, "req_rejected_deadline", true);
		$req_rejected_budget_from = get_post_meta($pid, "req_rejected_budget_from", true);
		$req_rejected_budget_to = get_post_meta($pid, "req_rejected_budget_to", true);
		$req_rejected_attachments = get_post_meta($pid, "req_rejected_attachments", true);
		?>

		<input name="fromadmin" type="hidden" value="1" />
		<table width="100%">

			<tr>
				<td><?php _e("Rejected Name", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_name" <?php if ($req_rejected_name == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_name_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_name_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Description", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_description" <?php if ($req_rejected_description == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_description_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_description_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Tags", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_tags" <?php if ($req_rejected_tags == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_tags_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_tags_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Deadline", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_deadline" <?php if ($req_rejected_deadline == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_deadline_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_deadline_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Budget From", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_budget_from" <?php if ($req_rejected_budget_from == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_budget_from_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_budget_from_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Budget To", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_budget_to" <?php if ($req_rejected_budget_to == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_budget_to_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_budget_to_comment', true); ?></textarea></td>
			</tr>

			<tr>
				<td><?php _e("Rejected Attachments", 'wpjobster'); ?></td>
				<td><input type="checkbox" value="1" name="req_rejected_attachments" <?php if ($req_rejected_attachments == '1') echo ' checked="checked" '; ?> /></td>
				<td><textarea name="req_rejected_attachments_comment" cols="40" rows="2"><?php echo get_post_meta($pid, 'req_rejected_attachments_comment', true); ?></textarea></td>
			</tr>

		</table>
	<?php }
}
// END REQUEST METABOXES //
