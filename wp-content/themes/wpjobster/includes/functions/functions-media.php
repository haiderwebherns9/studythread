<?php
//--------------------------------------
// WYSIWYG Parser
//--------------------------------------
function wpj_description_parser( $desc ){
	$description = trim( nl2br( wp_kses( $desc, array(
		'br' => array(),
		'b' => array(),
		'i' => array(),
		'u' => array(),
		'ul' => array(),
		'ol' => array(),
		'strong' => array(),
		'em' => array(),
		'p' => array(),
		'span' => array(),
		'li' => array(),
	) ) ) );

	return $description;
}

//--------------------------------------
// Download Attachments
//--------------------------------------
add_action( 'init', 'wpj_download_attachemnts' );
function wpj_download_attachemnts(){
	if ( isset( $_GET['secure_download'] ) && is_numeric( $_GET['secure_download'] ) ) {
		global $wpdb;
		global $current_user;
		$attachment_id = $_GET['secure_download'];

		$direct_download = 0;
		$allowed_users = array();

		$pm_id = get_post_meta( $attachment_id, 'pm_id', true );
		$message_id = get_post_meta( $attachment_id, 'message_id', true );
		$job_id = get_post_meta( $attachment_id, 'job_id', true );
		$direct_download = 0;

		if ( $pm_id && is_numeric( $pm_id ) ) {
			$p_message = $wpdb->get_row( "select * from {$wpdb->prefix}job_pm where id = '$pm_id'" );

			$allowed_users[] = $p_message->initiator; // sender
			$allowed_users[] = $p_message->user; // receiver

		} elseif ( $message_id && is_numeric( $message_id ) ) {
			// get message
			$o_message = $wpdb->get_row( "select * from {$wpdb->prefix}job_chatbox where id = '$message_id'" );

			// get order based on message id, then post based on order pid
			$order = $wpdb->get_row( "select * from {$wpdb->prefix}job_orders where id = '{$o_message->oid}'" );
			$post = get_post( $order->pid );

			$allowed_users[] = $post->post_author; // seller
			$allowed_users[] = $order->uid; // buyer

		} elseif ( $job_id && is_numeric( $job_id ) ) {
			// add job author to the array
			$post = get_post( $job_id );
			$allowed_users[] = $post->post_author; // seller

			// loop and add to the array all the buyers of this job
			$all_orders = $wpdb->get_results( "select * from {$wpdb->prefix}job_orders where pid = '$job_id'" );
			foreach( $all_orders as $order ) {
				$allowed_users[] = $order->uid; // buyer
			}

		} else {
			// no user specified for this download
			$direct_download = 1;
		}

		if ( ! is_user_logged_in() ) {
			if ( isset( $_GET['auth_token'] ) ) {
				$auth_token_transient = get_post_meta_transient( $_GET['secure_download'], 'wpj_authentication_token' );
				if ( $_GET['auth_token'] == $auth_token_transient ) {
					$direct_download = 1;
				}
			}
		}

		if ( user_can( $current_user, 'manage_options' ) || in_array( $current_user->ID, $allowed_users ) || $direct_download ) {
			$file = get_attached_file( $attachment_id );
			if ( $file ) {
				$filename = basename( $file );

				$fp = @fopen( $file, 'rb' );

				if (strstr( $_SERVER['HTTP_USER_AGENT'], "MSIE") ) {
					header( 'Content-Type: "application/octet-stream"' );
					header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
					header( 'Expires: 0' );
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Content-Transfer-Encoding: binary' );
					header( 'Pragma: public' );
					header( 'Content-Length: ' . filesize( $file ) );
				} else {
					header( 'Content-Type: "application/octet-stream"' );
					header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
					header( 'Content-Transfer-Encoding: binary' );
					header( 'Expires: 0' );
					header( 'Pragma: no-cache' );
					header( 'Content-Length: ' . filesize( $file ) );
				}

				fpassthru( $fp );
				fclose( $fp );

				exit; // all good
			} else {
				exit( __( 'Wrong attachment ID!', 'wpjobster' ) );
			}
		}
		exit( __( 'You are not allowed to download this!', 'wpjobster' ) );

	}
}

//--------------------------------------
// Audio Upload
//--------------------------------------
if (!function_exists('wpjobster_theme_job_audios_html5')) {

	function wpjobster_theme_job_audios_html5($pid)    {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		$cwd = str_replace('wp-admin', '', getcwd());
		$cwd .= 'wp-content/uploads';
		$max_audio_filesize  =  get_option('wpjobster_max_audio_upload_size');
		if(!is_numeric($max_audio_filesize)){
			$max_audio_filesize = 10;
		}

		wpjobster_add_uploadifive_scripts();
		?>

		<div class="hidden_input">
			<div id="queue"></div>
			<input id="audio_file_upload" name="audio_file_upload" type="file" multiple="false" accept="image/*">
		</div>
		<script type="text/javascript">
		<?php if (!is_demo_admin()) { ?>
			function delete_this_audio(id){
				jQuery.ajax({
					method: 'get',
					url : '<?php echo get_bloginfo('url'); ?>/index.php/?_ad_delete_pid='+id,
					dataType : 'text',
					success: function (text) {
						jQuery('#image_ss'+id).remove();
						jQuery("#thumbnails_audio").append('<div class="div_div_input_audio not_sortable"></div>');
					}
				});
			}
		<?php } ?>

		jQuery(document).ready(function() {
			var i = 0;
			var n = jQuery("#thumbnails_audio .div_div").length;

			for (x = n; x < <?php echo get_option('wpjobster_max_uploads_audio'); ?>; x++) {
				jQuery("#thumbnails_audio").append('<div class="div_div_input_audio not_sortable"></div>');
			};

			jQuery(document).on('click', '.div_div_input_audio', (function(){

				if (!jQuery(this).hasClass("div_div_inactive_audio")) {
					jQuery("#uploadifive-audio_file_upload input:last").click();

					i = i + 1;
					jQuery(this).addClass("clicked_" + i);

				}

			}));

			jQuery("#audio_file_upload").uploadifive({
				'auto'             : true,
				'multi'            : false,
				'uploadScript'     : '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/uploady_audio.php',
				'fileTypeExts'     : '*.mp3;*.wav;',
				'formData'         : {'ID':<?php echo $pid; ?>,'author':<?php echo $cid; ?>},
				'fileType'         : 'audio/mp3,audio/wav',
				'fileSizeLimit'    : <?php echo $max_audio_filesize*1024;?>,
				'onProgress'         : function() {
					jQuery(".clicked_" + i).addClass('uploading');
					jQuery(".div_div_input_audio").not(".div_div_active").addClass("div_div_inactive_audio");
				},
				'onError'          : function(data) {
					console.log(data);
					if (data == "FILE_SIZE_LIMIT_EXCEEDED") {
					   alert(<?php echo json_encode(sprintf(__("Maximum file size is %s MB!", "wpjobster"), $max_audio_filesize)); ?>);
					} else if (data == "FORBIDDEN_FILE_TYPE") {
						alert(<?php echo json_encode(__('Allowed file types: .mp3, .wav', 'wpjobster')); ?>);
					} else {
						alert(data);
					}
					jQuery(".div_div_input_audio").removeClass('uploading').removeClass('div_div_inactive_audio');
				},
				'onUploadComplete' : function(file, data, response) {
					var bar = data.split("|");

					if (bar[0] == "ok") {
						jQuery(".clicked_" + i).replaceWith('<div class="div_div" id="image_ss'+bar[2]+'" audio_id="'+bar[2]+'" ><p>' + bar[1] + ' </p><a href="javascript: void(0)" onclick="delete_this_audio('+ bar[2] +')" class="delete-this"></a></div>');
					} else if (bar[0] == "extension") {
						alert(<?php echo json_encode(__('Allowed file types: .mp3, .wav', 'wpjobster')); ?>);
					} else {
						//alert(<?php echo json_encode(__('Error!', 'wpjobster')); ?>);
					}

					jQuery(".clicked_" + i).removeClass('uploading');
					jQuery(".div_div_input_audio").removeClass('uploading').removeClass('div_div_inactive_audio');
				}

			});

		});

		</script>

		<div id="thumbnails_audio" class="cf">
			<?php
			$args = array(

				'post_type' => 'attachment',
				'post_parent' => $pid,
				'post_mime_type' => 'audio',
				'numberposts' => -1,
				'orderby' => 'meta_value_num date',
				'order' => 'ASC'
			);
			$i = 0;
			$attachments = get_posts($args);
			if ($attachments) {
				foreach ($attachments as $attachment) {
					//$url = wp_get_attachment_url($attachment->ID);
					$filename = basename( get_attached_file( $attachment->ID ) );
					echo '<div class="div_div"  id="image_ss' . $attachment->ID . '" audio_id="' . $attachment->ID . '"><p>' . $filename . '</p>
			<a href="javascript: void(0)" onclick="delete_this_audio(\'' . $attachment->ID . '\')" class="delete-this"></a>
			</div>';
				}

			}

			?>
		</div>
	<?php }
}

if(!function_exists('wpjobster_add_html5fileupload_scripts')){
	function wpjobster_add_html5fileupload_scripts() {
		wp_register_script( 'bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js', array(), '3.0.1', true );

		wp_enqueue_script( 'bootstrap-js' );

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

if (!function_exists('wpjobster_theme_cover_image_html5')) {
	function wpjobster_theme_cover_image_html5($pid){
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		wpjobster_add_html5fileupload_scripts();

		if (!is_demo_admin()) { ?>
			<script type="text/javascript">
			function delete_this_cover(id){
				 $.ajax({
					method: 'get',
					url : '<?php echo get_bloginfo('url'); ?>/index.php/?_ad_delete_pid='+id+'&cover_parent=<?php echo $pid;?>',
					dataType : 'text',
					success: function (text) {
						$('#image_ss'+id).remove();
					}
				});
			}
			</script>
		<?php } ?>
		<div id="cover-image" class="cf" style="margin-top:20px">
			<?php
			$cover_image_id = get_post_meta($pid,"cover-image",1);

			if($cover_image_id && !empty($cover_image_id)){
				$cover_image_url = wpj_get_attachment_image_url( $cover_image_id, array( 980, 180 ) ); ?>
					<div class="div_div cover_div_div"  id="image_ss<?php echo $cover_image_id;?>" image_id="<?php echo $cover_image_id;?>">
						<img class="image_class" src="<?php echo $cover_image_url; ?>" />
					<a href="javascript: void(0)" onclick="delete_this_cover('<?php echo $cover_image_id;?>')" class="delete-this"></a>
				</div>
			<?php }
			$allowed_size_mb = get_option('wpjobster_max_img_upload_size'); // total images allowed by the system to be uploaded
			if (!$allowed_size_mb) { $allowed_size_mb = 10; }
			$allowed_size=$allowed_size_mb*1024*1000;
			?>
		</div>

		<div class="col-xs-12 job-cover-input-container">
			<div class="html5fileupload cover-image" data-max-filesize="<?php echo $allowed_size?>" data-remove-done="true" data-autostart="true" data-valid-mime="image.*"  data-url="<?php echo get_template_directory_uri(); ?>/lib/html5fileupload/save_wp_jobcover_attachment.php" data-multiple="false" style="width: 100%;">
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
				$('.html5fileupload.cover-image').html5fileupload({
					onAfterStartSuccess: function(response) {
						if(response.error==''){
							$('#cover-image').html('<div class="div_div cover_div_div" id="image_ss'+response.attach_id+'" image_id="'+response.attach_id+'" ><img class="image_class"  src="' + response.go + '" /><a href="javascript: void(0)" onclick="delete_this_cover('+ response.attach_id +')" class="delete-this"></a></div>');
						}else{
							alert(response.error);
						}
					}
				});
			});
			</script>
		</div>
	<?php }
}

if (!function_exists('wpjobster_theme_job_images_html5')) {
	function wpjobster_theme_job_images_html5($pid)    {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		wpjobster_add_html5fileupload_scripts();
		if (!is_demo_admin()) { ?>
			<script type="text/javascript">
			function delete_this(id){
				 $.ajax({
					method: 'get',
					url : '<?php echo get_bloginfo('url'); ?>/index.php/?_ad_delete_pid='+id,
					dataType : 'text',
					success: function (text) {
						$('#image_ss'+id).remove();
					}
				});
			}
			</script>
		<?php } ?>
		<div id="thumbnails" class="cf" style="margin-top:20px">
			<?php
			$attachments = wpjobster_get_job_images( $pid );
			if ($attachments) {
				foreach ($attachments as $attachment) {
					$url = wpj_get_attachment_image_url( $attachment->ID, array( 90, 90 ) );
					echo '<div class="div_div"  id="image_ss' . $attachment->ID . '" image_id="' . $attachment->ID . '"><img width="90" class="image_class" height="90" src="' . $url . '" />
							<a href="javascript: void(0)" onclick="delete_this(\'' . $attachment->ID . '\')" class="delete-this"></a>
						</div>';
				}
			}

			$allowed_size_mb = get_option('wpjobster_max_img_upload_size'); // total images allowed by the system to be uploaded
			$allowed_size=$allowed_size_mb*1024*1000; ?>
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
					edit:				true, //deze tonen? je kunt de button ook verbergen?!
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
					regexp:				/^[^\\/:\*\?"<>\|]+$/, // " fix color scheme
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
	<?php }
}

if (!function_exists('wpjobster_theme_job_images_html5')) {
	function wpjobster_theme_job_images_html5($pid) {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		$cwd = str_replace('wp-admin', '', getcwd());
		$cwd .= 'wp-content/uploads';
		wpjobster_add_uploadifive_scripts();
		?>

		<div class="hidden_input">
			<div id="queue"></div>
			<input id="file_upload" name="file_upload" type="file" multiple="false" accept="image/*">
		</div>

		<script type="text/javascript">
		<?php if (!is_demo_admin()) { ?>
			function delete_this(id){
				 jQuery.ajax({
					method: 'get',
					url : '<?php echo get_bloginfo('url'); ?>/index.php/?_ad_delete_pid='+id,
					dataType : 'text',
					success: function (text) {
						jQuery('#image_ss'+id).remove();
						jQuery("#thumbnails").append('<div class="div_div_input not_sortable"></div>');
					}
				});
			}
		<?php } ?>

		jQuery(document).ready(function() {
			var i = 0;
			var n = jQuery("#thumbnails .div_div").length;

			for (x = n; x < <?php echo get_option('wpjobster_default_nr_of_pics'); ?>; x++) {
				jQuery("#thumbnails").append('<div class="div_div_input not_sortable"></div>');

			};

			jQuery(document).on('click', '.div_div_input', (function(){

				if (!jQuery(this).hasClass("div_div_inactive")) {
					jQuery("#uploadifive-file_upload input:last").click();

					i = i + 1;
					jQuery(this).addClass("clicked_" + i);

				}

			}));

			jQuery("#file_upload").uploadifive({
				'auto'             : true,
				'multi'            : false,
				'uploadScript'     : '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/uploady.php',
				'fileTypeExts'     : '*.jpg;*.jpeg;*.gif;*.png',
				'formData'         : {'ID':<?php echo $pid; ?>,'author':<?php echo $cid; ?>},
				'fileType'         : 'image/*',
				'fileSizeLimit'    : 2048,
				'onProgress'         : function() {
					jQuery(".clicked_" + i).addClass('uploading');
					jQuery(".div_div_input").not(".div_div_active").addClass("div_div_inactive");
				},
				'onError'          : function(data) {
					console.log(data);
					if (data == "FILE_SIZE_LIMIT_EXCEEDED") {
						alert(<?php echo json_encode(__('Maximum file size is 2MB!', 'wpjobster')); ?>);
					} else if (data == "FORBIDDEN_FILE_TYPE") {
						alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif', 'wpjobster')); ?>);
					} else {
						alert(data);
					}
					jQuery(".div_div_input").removeClass('uploading').removeClass('div_div_inactive');
				},
				'onUploadComplete' : function(file, data, response) {
					var bar = data.split("|");

					if (bar[0] == "ok") {
						jQuery(".clicked_" + i).replaceWith('<div class="div_div" id="image_ss'+bar[2]+'" image_id="'+bar[2]+'" ><img width="90" class="image_class" height="90" src="' + bar[1] + '" /><a href="javascript: void(0)" onclick="delete_this('+ bar[2] +')" class="delete-this"></a></div>');
					} else if (bar[0] == "size") {
						alert(<?php echo json_encode(__('Minimum file size: 690px x 388px', 'wpjobster')); ?>);
					} else if (bar[0] == "extension") {
						alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif', 'wpjobster')); ?>);
					} else {
						alert(<?php echo json_encode(__('Error!', 'wpjobster')); ?>);
					}

					jQuery(".clicked_" + i).removeClass('uploading');
					jQuery(".div_div_input").removeClass('uploading').removeClass('div_div_inactive');
				}

			});

		});
		</script>

		<div data="admin-side" id="thumbnails" class="cf" style="margin-top:20px">
			<?php
			$i = 0;
			$attachments = wpjobster_get_job_images( $pid );

			if ($attachments) {
				foreach ($attachments as $attachment) {
					$url = wpj_get_attachment_image_url( $attachment->ID, array( 90, 90 ) );
					echo '<div class="div_div"  id="image_ss' . $attachment->ID . '" image_id="' . $attachment->ID . '"><img width="90" class="image_class" height="90" src="' . $url . '" /><a href="javascript: void(0)" onclick="delete_this(\'' . $attachment->ID . '\')" class="delete-this"></a></div>';
				}
			}
			?>
		</div>

		<input type="hidden" name="images_order" id="images_order" />
	<?php }
}

if (!function_exists('wpjobster_theme_attachments_uploader_html5')) {
	function wpjobster_theme_attachments_uploader_html5($secure=0, $input_file_upload, $input_hidden_store_id, $unique_name)    {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		$cwd = str_replace('wp-admin', '', getcwd());
		$cwd .= 'wp-content/uploads';
		wpjobster_add_uploadifive_scripts();
		?>

		<div id="queue"></div>
		<input class="<?php echo $input_file_upload; ?>" id="<?php echo $input_file_upload; ?>" name="<?php echo $input_file_upload; ?>" type="file" multiple>

		<input type="hidden" name="<?php echo $input_hidden_store_id; ?>" value="" />

		<script type="text/javascript">
		jQuery(document).ready(function() {
			var input_file_upload     = "<?php echo $input_file_upload; ?>";
			var input_hidden_store_id = "<?php echo $input_hidden_store_id; ?>";
			var unique_name           = "<?php echo $unique_name; ?>";

			jQuery("#"+input_file_upload).uploadifive({
				'auto'             : true,
				'multi'            : true,
				'height'           : 'auto',
				'width'            : 'auto',
				'uploadScript'     : '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/uploadpm.php',
				'formData'         : {'author':<?php echo $cid; ?>,'secure':'<?php echo $secure;?>','unique_name':'<?php echo $unique_name; ?>'},
				'uploadLimit'      : 10,
				'fileSizeLimit'    : 51200,
				'buttonText'      : <?php echo json_encode(__('Select File', 'wpjobster')); ?>,
				'onProgress'         : function() {
					$( "input[type=submit]" ).prop('disabled', true);

					if(unique_name == 'custom_offer'){
						$( '#uploadifive-file_upload_custom_offer_attachments-queue:not(:empty)' )
						.css({
							'height' : '80px',
							'overflow-y' : 'auto'
						});
					}
				},
				'onError' : function(data) {

				},
				'onUploadComplete' : function(file, data, response) {
					var bar = data.split("|");

					// show values

					$("#uploadifive-"+input_file_upload+"-queue").addClass("test");
					$(".filename:contains('" + file.name + "')").parent().parent().attr({'data-fileid': bar[1], 'data-filetype': bar[2]});

					$( "input[type=submit]" ).prop('disabled', false);

				},
				'onCancel' : function(file) {
					if(unique_name == 'custom_offer'){
						if( $('#uploadifive-file_upload_custom_offer_attachments-queue > div').length == 1 ){
							$( '#uploadifive-file_upload_custom_offer_attachments-queue:not(:empty)' )
							.css({
								'height' : '0px',
								'overflow-y' : 'auto'
							});
						}
					}
				}
			});

			jQuery("#uploadifive-"+input_file_upload+"-queue").bind('DOMSubtreeModified',function(){

				$("input[name="+input_hidden_store_id+"]").val("");
				$(".uploadifive-queue-item.complete").each(function(){
					var file_id_value = $(this).attr("data-fileid");
					var file_type_value = $(this).attr("data-filetype");

					if(file_type_value===unique_name){
						$("input[name="+input_hidden_store_id+"]").val(function(i,val){
							if (file_id_value != undefined && file_type_value != undefined) {
								return val + (val ? ',' : '') + file_id_value;
							}
						});
					}
				});

				var disable_input = 0;
				$(".uploadifive-queue-item").each(function(){
					var file_id_disabled = $(this).attr("data-fileid");
					if (file_id_disabled == undefined) {
						disable_input = 1;
					}
				});

				if (disable_input == 0) {
					$( "input[type=submit]" ).prop('disabled', false);
				}
			})

		});
		</script>
	<?php
	}

}

if (!function_exists('wpjobster_avatar_upload_html5')) {
	function wpjobster_avatar_upload_html5($width = 50, $height = 50) {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		$cwd = str_replace('wp-admin', '', getcwd());
		$cwd .= 'wp-content/uploads';

		$max_image_filesize = get_option('wpjobster_max_img_upload_size');
		if (!is_numeric($max_image_filesize)) {
			$max_image_filesize = 10;
		}

		wpjobster_add_uploadifive_scripts()
		?>

		<div class="hidden_input">
			<div id="queue"></div>
			<input id="file_upload" name="file_upload" type="file" multiple="false" accept="image/*">
		</div>

		<div class="avatar_input" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px">
			<img src="<?php echo wpjobster_get_avatar($current_user->ID, $width, $height); ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
			<div class="ei-cnt ei-small ei-photo-camera">
				<svg class="ei-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#ei-photo-camera"></use></svg>
			</div>
		</div>

		<script type="text/javascript">

		jQuery(document).ready(function() {
			var i = 0;
			var n = 1;

			jQuery(document).on('click', '.avatar_input', (function(){

				if (!jQuery(this).hasClass("avatar_input_inactive")) {
					jQuery("#uploadifive-file_upload input:last").click();

					i = i + 1;
					jQuery(this).addClass("clicked_" + i);
				}

			}));

			jQuery("#file_upload").uploadifive({
				'auto'             : true,
				'multi'            : false,
				'uploadScript'     : '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/uploadavatar.php',
				'fileTypeExts'     : '*.jpg;*.jpeg;*.gif;*.png',
				'formData'         : {'author':<?php echo $cid; ?>,'width':<?php echo $width; ?>, 'height':<?php echo $height; ?>},
				'fileType'         : 'image/*',
				'fileSizeLimit'    : <?php echo $max_image_filesize * 1024; ?>,
				'onProgress'         : function() {
					jQuery(".clicked_" + i).addClass('uploading');
					jQuery(".avatar_input").addClass("avatar_input_inactive");
				},
				'onError'          : function(data) {
					console.log(data);
					if (data == "FILE_SIZE_LIMIT_EXCEEDED") {
						alert(<?php echo json_encode(sprintf(__("Maximum file size is %s MB!", "wpjobster"), $max_image_filesize)); ?>);
					} else if (data == "FORBIDDEN_FILE_TYPE") {
						alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif x', 'wpjobster')); ?>);
					} else {
						alert(data);
					}
					jQuery(".avatar_input").removeClass('uploading').removeClass('avatar_input_inactive');
				},
				'onUploadComplete' : function(file, data, response) {
					var bar = data.split("|");

					// show values
					console.log(data);
					console.log(bar);

				if (bar[0] == "ok") {
				jQuery(".clicked_" + i).replaceWith('<div class="avatar_input" id="image_ss'+bar[2]+'" image_id="'+bar[2]+'" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;"><img width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="' + bar[1] + '"><div class="ei-cnt ei-small ei-photo-camera"><svg class="ei-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#ei-photo-camera"></use></svg></div></div>');
				} else if (bar[0] == "size") {
					alert(<?php echo json_encode(__('Minimum file size: 250px x 250px', 'wpjobster')); ?>);
				} else if (bar[0] == "extension") {
					alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif', 'wpjobster')); ?>);
				} else {
					alert(<?php echo json_encode(__('Error1!', 'wpjobster')); ?>);
				}

					jQuery(".clicked_" + i).removeClass('uploading');
					jQuery(".avatar_input").removeClass('uploading').removeClass('avatar_input_inactive');
				}
			});

		});

		</script>
	<?php }
}

if (!function_exists('wpjobster_banner_upload_html5')) {
	function wpjobster_banner_upload_html5($width = 50, $height = 50, $banner) {
		global $current_user;
		$current_user = wp_get_current_user();
		$cid = $current_user->ID;
		$cwd = str_replace('wp-admin', '', getcwd());
		$cwd .= 'wp-content/uploads';

		$max_image_filesize = get_option('wpjobster_max_img_upload_size');
		if (!is_numeric($max_image_filesize)) {
			$max_image_filesize = 10;
		}

		$width1 = $width;
		$height1 = $height;
		wpjobster_add_uploadifive_scripts();
		?>
		<div id="banner_upload_input" class="hidden_input" style="display: none;">
			<div id="queue"></div>
			<input id="file_upload_banner" name="file_upload_banner" type="file" multiple="false" accept="image/*">
		</div>
		<?php if (empty($banner)): ?>
			<div class="banner_input" style="display: inline;">
				<div id="contentbanner" style="display: inline;">
					<span><a href="#"><?php _e("Upload Cover Background","wpjobster"); ?> </a></span>
					<span style="display:none;" id="dvloader"><img src="<?php echo get_template_directory_uri().'/images/notifications-loading.gif'; ?>" /></span>
				</div>
			</div>
		<?php else: ?>
			<div id="contentbanner" style="display: inline;">
				<span><a href="#"><?php _e("Remove Cover Image","wpjobster"); ?> </a></span>
				<span style="display:none;" id="dvloader"><img src="<?php echo get_template_directory_uri().'/images/notifications-loading.gif'; ?>" /></span>
			</div>
		<?php endif; ?>

		<script type="text/javascript">
		jQuery(document).ready(function() {
			var i = 0;
			var n = 1;

			jQuery(document).on('click', '.banner_input', (function(){

				if (!jQuery(this).hasClass("avatar_input_inactive")) {
					jQuery("#uploadifive-file_upload_banner input:last").click();
					i = i + 1;
					jQuery(this).addClass("clicked_" + i);
				}

			}));

			jQuery("#file_upload_banner").uploadifive({
				'auto'             : true,
				'multi'            : false,
				'uploadScript'     : '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/uploadbanner.php',
				'fileTypeExts'     : '*.jpg;*.jpeg;*.gif;*.png',
				'formData'         : {'author':<?php echo $cid; ?>,'width':<?php echo $width; ?>, 'height':<?php echo $height; ?>},
				'fileType'         : 'image/*',
				'fileSizeLimit'    : <?php echo $max_image_filesize * 1024; ?>,
				onProgress         : function() {
					jQuery("#dvloader").show();
					jQuery(".banner_input").addClass("avatar_input_inactive");
				},
				onError : function(data) {
					if (data == "FILE_SIZE_LIMIT_EXCEEDED") {
						alert(<?php echo json_encode(sprintf(__("Maximum file size is %s MB!", "wpjobster"), $max_image_filesize)); ?>);
					} else if (data == "FORBIDDEN_FILE_TYPE") {
						alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif x', 'wpjobster')); ?>);
					} else {
						alert(data);
					}
					jQuery("#load_waiting").removeClass('uploading').removeClass('avatar_input_inactive');
				},
				onUploadComplete : function(file, data, response) {
					jQuery("#dvloader").hide();
					var bar = data.split("|");

					if (bar[0] == "ok") {
						jQuery('#banner').addClass('ub-cover-photo');
						jQuery('#banner').css({'background-image': 'url(' + bar[1] + ')'});
						jQuery('#banner').attr('data-attach_id', bar[2]);
						jQuery('#contentbanner').html("<span><a href='#'><?php _e('Remove Backgroud', 'wpjobster'); ?></a></span><span style='display:none;' id='dvloader'><img src='<?php echo get_template_directory_uri().'/images/notifications-loading.gif'; ?>' /></span>");
					} else if (bar[0] == "size") {
						alert(<?php echo json_encode(__('Minimum file size: 250px x 250px', 'wpjobster')); ?>);
						jQuery(".banner_input").removeClass("avatar_input_inactive");
					} else if (bar[0] == "extension") {
						alert(<?php echo json_encode(__('Allowed file types: .jpg, .jpeg, .png, .gif', 'wpjobster')); ?>);
						jQuery(".banner_input").removeClass("avatar_input_inactive");
					} else {
						alert(<?php echo json_encode(__('Error1!', 'wpjobster')); ?>);
						jQuery(".banner_input").removeClass("avatar_input_inactive");
					}

					jQuery("#load_waiting").removeClass('uploading');
					jQuery(".avatar_input").removeClass('uploading').removeClass('avatar_input_inactive');
				}
			});

			/* Remove cover user image */
			jQuery(document).on('click', '#contentbanner', (function(){
				attach_id =  jQuery("#banner").data('attach_id');
				jQuery.ajax({
					type: "POST",
					url: '<?php echo get_template_directory_uri(); ?>/lib/uploadifive/deletebanner.php',
					data: {'user':<?php echo $cid;?>,'attach_id':attach_id},
					onProgress : function() {
							jQuery("#dvloader").show();
					},
					success: function (result) {
						jQuery("#dvloader").hide();
						if ('resultok' == result) {
							jQuery('#banner').css('background', 'transparent');
							jQuery('#banner').removeClass('ub-cover-photo');
							jQuery('#contentbanner').html("<div class='banner_input' style='display: inline;'><div id='contentbanner' style='display: inline;'><span><a href='#'><?php _e('Upload Backgroud', 'wpjobster'); ?></a></span><span style='display:none;' id='dvloader'><img src='<?php echo get_template_directory_uri().'/images/notifications-loading.gif'; ?>' /></span></div>");
						}
					}
				});

			}));
			/* END remove cover user image */

		});
		</script>
	<?php }
}

function wpjobster_generate_thumb2($img_ID, $width, $height, $cut = true){
	return wpj_get_attachment_image_url($img_ID, array(        $width,        $height    ));
}


function wpjobster_generate_thumb3($img_ID, $size_string){
	return wpj_get_attachment_image_url($img_ID, $size_string);
}


function wpjobster_wp_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = ''){
	$html = '';
	$image = wp_get_attachment_image_src($attachment_id, $size, $icon);

	if ($image) {
		list($src, $width, $height) = $image;
		$hwstring = image_hwstring($width, $height);

		if (is_array($size)) $size = join('x', $size);
		$attachment = get_post($attachment_id);
		$default_attr = array(
			'src' => $src,
			'class' => "attachment-$size",
			'alt' => trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))), // Use Alt field first
			'title' => trim(strip_tags($attachment->post_title))
		);

		if (empty($default_attr['alt'])) $default_attr['alt'] = trim(strip_tags($attachment->post_excerpt));
		// If not, Use the Caption

		if (empty($default_attr['alt'])) $default_attr['alt'] = trim(strip_tags($attachment->post_title));
		// Finally, use the title
		$attr = wp_parse_args($attr, $default_attr);
		$attr = apply_filters('wp_get_attachment_image_attributes', $attr, $attachment);
		$attr = array_map('esc_attr', $attr);
		$html = rtrim("<img $hwstring");
		$html = $attr['src'];
	}

	return $html;
}

// wrapper function for compatibility maintenance
// returns the url, not array as the original function
function wpj_get_attachment_image_url( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '' ) {
	$image = wp_get_attachment_image_src( $attachment_id, $size, $icon, $attr );
	$url = '';
	if ( $image ) {
		list( $url, $width, $height, $is_intermediate ) = $image;
	}
	return $url;
}

add_filter('upload_mimes', 'wpjobster_custom_upload_mimes');
function wpjobster_custom_upload_mimes($existing_mimes = array()){
	$existing_mimes['zip'] = 'application/zip';
	$existing_mimes['psd'] = 'application/octet-stream';
	$existing_mimes['ai'] = 'application/postscript';
	$existing_mimes['cdr'] = 'application/cdr';
	$existing_mimes['eps'] = 'application/postscript';
	return $existing_mimes;
}

add_filter('upload_mimes', 'wpjobster_custom_admin_upload_mimes');
function wpjobster_custom_admin_upload_mimes( $existing_mimes = array() ) {

	$allowed = get_option( 'wpjobster_allowed_mime_types' );
	$mime_types = wpjobster_mimes_type();

	if ( ! empty( $allowed ) ) {
		foreach ( $allowed as $val ) {
			if ( isset( $mime_types[$val] ) ) {
				$existing_mimes[$val] = $mime_types[$val];
			} else {
				$existing_mimes[$val] = 'application/'.$val;
			}
		}
	} else {
		$existing_mimes[] = '';
	}

	return $existing_mimes;
}

function wpjobster_mimes_type(){
	$mt = array(
		'123'         => 'application/vnd.lotus-1-2-3',
		'3dml'        => 'text/vnd.in3d.3dml',
		'3g2'         => 'video/3gpp2',
		'3gp'         => 'video/3gpp',
		'7z'          => 'application/x-7z-compressed',
		'aab'         => 'application/x-authorware-bin',
		'aac'         => 'audio/x-aac',
		'aam'         => 'application/x-authorware-map',
		'aas'         => 'application/x-authorware-seg',
		'abw'         => 'application/x-abiword',
		'ac'          => 'application/pkix-attr-cert',
		'acc'         => 'application/vnd.americandynamics.acc',
		'ace'         => 'application/x-ace-compressed',
		'acu'         => 'application/vnd.acucobol',
		'adp'         => 'audio/adpcm',
		'aep'         => 'application/vnd.audiograph',
		'afp'         => 'application/vnd.ibm.modcap',
		'ahead'       => 'application/vnd.ahead.space',
		'ai'          => 'application/postscript',
		'aif'         => 'audio/x-aiff',
		'air'         => 'application/vnd.adobe.air-application-installer-package+zip',
		'ait'         => 'application/vnd.dvb.ait',
		'ami'         => 'application/vnd.amiga.ami',
		'apk'         => 'application/vnd.android.package-archive',
		'application' => 'application/x-ms-application',
		'apr'         => 'application/vnd.lotus-approach',
		'asf'         => 'video/x-ms-asf',
		'aso'         => 'application/vnd.accpac.simply.aso',
		'atc'         => 'application/vnd.acucorp',
		'atom'        => 'application/atom+xml',
		'atomcat'     => 'application/atomcat+xml',
		'atomsvc'     => 'application/atomsvc+xml',
		'atx'         => 'application/vnd.antix.game-component',
		'au'          => 'audio/basic',
		'avi'         => 'video/x-msvideo',
		'aw'          => 'application/applixware',
		'azf'         => 'application/vnd.airzip.filesecure.azf',
		'azs'         => 'application/vnd.airzip.filesecure.azs',
		'azw'         => 'application/vnd.amazon.ebook',
		'bcpio'       => 'application/x-bcpio',
		'bdf'         => 'application/x-font-bdf',
		'bdm'         => 'application/vnd.syncml.dm+wbxml',
		'bed'         => 'application/vnd.realvnc.bed',
		'bh2'         => 'application/vnd.fujitsu.oasysprs',
		'bin'         => 'application/octet-stream',
		'bmi'         => 'application/vnd.bmi',
		'bmp'         => 'image/bmp',
		'box'         => 'application/vnd.previewsystems.box',
		'btif'        => 'image/prs.btif',
		'bz'          => 'application/x-bzip',
		'bz2'         => 'application/x-bzip2',
		'c'           => 'text/x-c',
		'c11amc'      => 'application/vnd.cluetrust.cartomobile-config',
		'c11amz'      => 'application/vnd.cluetrust.cartomobile-config-pkg',
		'c4g'         => 'application/vnd.clonk.c4group',
		'cab'         => 'application/vnd.ms-cab-compressed',
		'car'         => 'application/vnd.curl.car',
		'cat'         => 'application/vnd.ms-pki.seccat',
		'ccxml'       => 'application/ccxml+xml',
		'cdbcmsg'     => 'application/vnd.contact.cmsg',
		'cdkey'       => 'application/vnd.mediastation.cdkey',
		'cdmia'       => 'application/cdmi-capability',
		'cdmic'       => 'application/cdmi-container',
		'cdmid'       => 'application/cdmi-domain',
		'cdmio'       => 'application/cdmi-object',
		'cdmiq'       => 'application/cdmi-queue',
		'cdx'         => 'chemical/x-cdx',
		'cdxml'       => 'application/vnd.chemdraw+xml',
		'cdy'         => 'application/vnd.cinderella',
		'cer'         => 'application/pkix-cert',
		'cgm'         => 'image/cgm',
		'chat'        => 'application/x-chat',
		'chm'         => 'application/vnd.ms-htmlhelp',
		'chrt'        => 'application/vnd.kde.kchart',
		'cif'         => 'chemical/x-cif',
		'cii'         => 'application/vnd.anser-web-certificate-issue-initiation',
		'cil'         => 'application/vnd.ms-artgalry',
		'cla'         => 'application/vnd.claymore',
		'class'       => 'application/java-vm',
		'clkk'        => 'application/vnd.crick.clicker.keyboard',
		'clkp'        => 'application/vnd.crick.clicker.palette',
		'clkt'        => 'application/vnd.crick.clicker.template',
		'clkw'        => 'application/vnd.crick.clicker.wordbank',
		'clkx'        => 'application/vnd.crick.clicker',
		'clp'         => 'application/x-msclip',
		'cmc'         => 'application/vnd.cosmocaller',
		'cmdf'        => 'chemical/x-cmdf',
		'cml'         => 'chemical/x-cml',
		'cmp'         => 'application/vnd.yellowriver-custom-menu',
		'cmx'         => 'image/x-cmx',
		'cod'         => 'application/vnd.rim.cod',
		'cpio'        => 'application/x-cpio',
		'cpt'         => 'application/mac-compactpro',
		'crd'         => 'application/x-mscardfile',
		'crl'         => 'application/pkix-crl',
		'cryptonote'  => 'application/vnd.rig.cryptonote',
		'csh'         => 'application/x-csh',
		'csml'        => 'chemical/x-csml',
		'csp'         => 'application/vnd.commonspace',
		'css'         => 'text/css',
		'csv'         => 'text/csv',
		'cu'          => 'application/cu-seeme',
		'curl'        => 'text/vnd.curl',
		'cww'         => 'application/prs.cww',
		'dae'         => 'model/vnd.collada+xml',
		'daf'         => 'application/vnd.mobius.daf',
		'davmount'    => 'application/davmount+xml',
		'dcurl'       => 'text/vnd.curl.dcurl',
		'dd2'         => 'application/vnd.oma.dd2+xml',
		'ddd'         => 'application/vnd.fujixerox.ddd',
		'deb'         => 'application/x-debian-package',
		'der'         => 'application/x-x509-ca-cert',
		'dfac'        => 'application/vnd.dreamfactory',
		'dir'         => 'application/x-director',
		'dis'         => 'application/vnd.mobius.dis',
		'djvu'        => 'image/vnd.djvu',
		'dmg'         => 'application/x-apple-diskimage',
		'dna'         => 'application/vnd.dna',
		'doc'         => 'application/msword',
		'docm'        => 'application/vnd.ms-word.document.macroenabled.12',
		'docx'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'dotm'        => 'application/vnd.ms-word.template.macroenabled.12',
		'dotx'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dp'          => 'application/vnd.osgi.dp',
		'dpg'         => 'application/vnd.dpgraph',
		'dra'         => 'audio/vnd.dra',
		'dsc'         => 'text/prs.lines.tag',
		'dssc'        => 'application/dssc+der',
		'dtb'         => 'application/x-dtbook+xml',
		'dtd'         => 'application/xml-dtd',
		'dts'         => 'audio/vnd.dts',
		'dtshd'       => 'audio/vnd.dts.hd',
		'dvi'         => 'application/x-dvi',
		'dwf'         => 'model/vnd.dwf',
		'dwg'         => 'image/vnd.dwg',
		'dxf'         => 'image/vnd.dxf',
		'dxp'         => 'application/vnd.spotfire.dxp',
		'ecelp4800'   => 'audio/vnd.nuera.ecelp4800',
		'ecelp7470'   => 'audio/vnd.nuera.ecelp7470',
		'ecelp9600'   => 'audio/vnd.nuera.ecelp9600',
		'edm'         => 'application/vnd.novadigm.edm',
		'edx'         => 'application/vnd.novadigm.edx',
		'efif'        => 'application/vnd.picsel',
		'ei6'         => 'application/vnd.pg.osasli',
		'eml'         => 'message/rfc822',
		'emma'        => 'application/emma+xml',
		'eol'         => 'audio/vnd.digital-winds',
		'eot'         => 'application/vnd.ms-fontobject',
		'epub'        => 'application/epub+zip',
		'es'          => 'application/ecmascript',
		'es3'         => 'application/vnd.eszigno3+xml',
		'esf'         => 'application/vnd.epson.esf',
		'etx'         => 'text/x-setext',
		'exe'         => 'application/x-msdownload',
		'exi'         => 'application/exi',
		'ext'         => 'application/vnd.novadigm.ext',
		'ez2'         => 'application/vnd.ezpix-album',
		'ez3'         => 'application/vnd.ezpix-package',
		'f'           => 'text/x-fortran',
		'f4v'         => 'video/x-f4v',
		'fbs'         => 'image/vnd.fastbidsheet',
		'fcs'         => 'application/vnd.isac.fcs',
		'fdf'         => 'application/vnd.fdf',
		'fe_launch'   => 'application/vnd.denovo.fcselayout-link',
		'fg5'         => 'application/vnd.fujitsu.oasysgp',
		'fh'          => 'image/x-freehand',
		'fig'         => 'application/x-xfig',
		'fli'         => 'video/x-fli',
		'flo'         => 'application/vnd.micrografx.flo',
		'flv'         => 'video/x-flv',
		'flw'         => 'application/vnd.kde.kivio',
		'flx'         => 'text/vnd.fmi.flexstor',
		'fly'         => 'text/vnd.fly',
		'fm'          => 'application/vnd.framemaker',
		'fnc'         => 'application/vnd.frogans.fnc',
		'fpx'         => 'image/vnd.fpx',
		'fsc'         => 'application/vnd.fsc.weblaunch',
		'fst'         => 'image/vnd.fst',
		'ftc'         => 'application/vnd.fluxtime.clip',
		'fti'         => 'application/vnd.anser-web-funds-transfer-initiation',
		'fvt'         => 'video/vnd.fvt',
		'fxp'         => 'application/vnd.adobe.fxp',
		'fzs'         => 'application/vnd.fuzzysheet',
		'g2w'         => 'application/vnd.geoplan',
		'g3'          => 'image/g3fax',
		'g3w'         => 'application/vnd.geospace',
		'gac'         => 'application/vnd.groove-account',
		'gdl'         => 'model/vnd.gdl',
		'geo'         => 'application/vnd.dynageo',
		'gex'         => 'application/vnd.geometry-explorer',
		'ggb'         => 'application/vnd.geogebra.file',
		'ggt'         => 'application/vnd.geogebra.tool',
		'ghf'         => 'application/vnd.groove-help',
		'gif'         => 'image/gif',
		'gim'         => 'application/vnd.groove-identity-message',
		'gmx'         => 'application/vnd.gmx',
		'gnumeric'    => 'application/x-gnumeric',
		'gph'         => 'application/vnd.flographit',
		'gqf'         => 'application/vnd.grafeq',
		'gram'        => 'application/srgs',
		'grv'         => 'application/vnd.groove-injector',
		'grxml'       => 'application/srgs+xml',
		'gsf'         => 'application/x-font-ghostscript',
		'gtar'        => 'application/x-gtar',
		'gtm'         => 'application/vnd.groove-tool-message',
		'gtw'         => 'model/vnd.gtw',
		'gv'          => 'text/vnd.graphviz',
		'gxt'         => 'application/vnd.geonext',
		'h261'        => 'video/h261',
		'h263'        => 'video/h263',
		'h264'        => 'video/h264',
		'hal'         => 'application/vnd.hal+xml',
		'hbci'        => 'application/vnd.hbci',
		'hdf'         => 'application/x-hdf',
		'hlp'         => 'application/winhlp',
		'hpgl'        => 'application/vnd.hp-hpgl',
		'hpid'        => 'application/vnd.hp-hpid',
		'hps'         => 'application/vnd.hp-hps',
		'hqx'         => 'application/mac-binhex40',
		'htke'        => 'application/vnd.kenameaapp',
		'html'        => 'text/html',
		'hvd'         => 'application/vnd.yamaha.hv-dic',
		'hvp'         => 'application/vnd.yamaha.hv-voice',
		'hvs'         => 'application/vnd.yamaha.hv-script',
		'i2g'         => 'application/vnd.intergeo',
		'icc'         => 'application/vnd.iccprofile',
		'ice'         => 'x-conference/x-cooltalk',
		'ico'         => 'image/x-icon',
		'ics'         => 'text/calendar',
		'ief'         => 'image/ief',
		'ifm'         => 'application/vnd.shana.informed.formdata',
		'igl'         => 'application/vnd.igloader',
		'igm'         => 'application/vnd.insors.igm',
		'igs'         => 'model/iges',
		'igx'         => 'application/vnd.micrografx.igx',
		'iif'         => 'application/vnd.shana.informed.interchange',
		'imp'         => 'application/vnd.accpac.simply.imp',
		'ims'         => 'application/vnd.ms-ims',
		'ipfix'       => 'application/ipfix',
		'ipk'         => 'application/vnd.shana.informed.package',
		'irm'         => 'application/vnd.ibm.rights-management',
		'irp'         => 'application/vnd.irepository.package+xml',
		'itp'         => 'application/vnd.shana.informed.formtemplate',
		'ivp'         => 'application/vnd.immervision-ivp',
		'ivu'         => 'application/vnd.immervision-ivu',
		'jad'         => 'text/vnd.sun.j2me.app-descriptor',
		'jam'         => 'application/vnd.jam',
		'jar'         => 'application/java-archive',
		'java'        => 'text/x-java-source',
		'jisp'        => 'application/vnd.jisp',
		'jlt'         => 'application/vnd.hp-jlyt',
		'jnlp'        => 'application/x-java-jnlp-file',
		'joda'        => 'application/vnd.joost.joda-archive',
		'jpeg'        => 'image/jpeg',
		'jpg'         => 'image/jpeg',
		'jpgv'        => 'video/jpeg',
		'jpm'         => 'video/jpm',
		'js'          => 'application/javascript',
		'json'        => 'application/json',
		'karbon'      => 'application/vnd.kde.karbon',
		'kfo'         => 'application/vnd.kde.kformula',
		'kia'         => 'application/vnd.kidspiration',
		'kml'         => 'application/vnd.google-earth.kml+xml',
		'kmz'         => 'application/vnd.google-earth.kmz',
		'kne'         => 'application/vnd.kinar',
		'kon'         => 'application/vnd.kde.kontour',
		'kpr'         => 'application/vnd.kde.kpresenter',
		'ksp'         => 'application/vnd.kde.kspread',
		'ktx'         => 'image/ktx',
		'ktz'         => 'application/vnd.kahootz',
		'kwd'         => 'application/vnd.kde.kword',
		'lasxml'      => 'application/vnd.las.las+xml',
		'latex'       => 'application/x-latex',
		'lbd'         => 'application/vnd.llamagraphics.life-balance.desktop',
		'lbe'         => 'application/vnd.llamagraphics.life-balance.exchange+xml',
		'les'         => 'application/vnd.hhe.lesson-player',
		'link66'      => 'application/vnd.route66.link66+xml',
		'lrm'         => 'application/vnd.ms-lrm',
		'ltf'         => 'application/vnd.frogans.ltf',
		'lvp'         => 'audio/vnd.lucent.voice',
		'lwp'         => 'application/vnd.lotus-wordpro',
		'm21'         => 'application/mp21',
		'm3u'         => 'audio/x-mpegurl',
		'm3u8'        => 'application/vnd.apple.mpegurl',
		'm4v'         => 'video/x-m4v',
		'ma'          => 'application/mathematica',
		'mads'        => 'application/mads+xml',
		'mag'         => 'application/vnd.ecowin.chart',
		'mathml'      => 'application/mathml+xml',
		'mbk'         => 'application/vnd.mobius.mbk',
		'mbox'        => 'application/mbox',
		'mc1'         => 'application/vnd.medcalcdata',
		'mcd'         => 'application/vnd.mcd',
		'mcurl'       => 'text/vnd.curl.mcurl',
		'mdb'         => 'application/x-msaccess',
		'mdi'         => 'image/vnd.ms-modi',
		'meta4'       => 'application/metalink4+xml',
		'mets'        => 'application/mets+xml',
		'mfm'         => 'application/vnd.mfmp',
		'mgp'         => 'application/vnd.osgeo.mapguide.package',
		'mgz'         => 'application/vnd.proteus.magazine',
		'mid'         => 'audio/midi',
		'mif'         => 'application/vnd.mif',
		'mj2'         => 'video/mj2',
		'mlp'         => 'application/vnd.dolby.mlp',
		'mmd'         => 'application/vnd.chipnuts.karaoke-mmd',
		'mmf'         => 'application/vnd.smaf',
		'mmr'         => 'image/vnd.fujixerox.edmics-mmr',
		'mny'         => 'application/x-msmoney',
		'mods'        => 'application/mods+xml',
		'movie'       => 'video/x-sgi-movie',
		'mp4'         => 'application/mp4',
		'mp4'         => 'video/mp4',
		'mp4a'        => 'audio/mp4',
		'mpc'         => 'application/vnd.mophun.certificate',
		'mpeg'        => 'video/mpeg',
		'mpga'        => 'audio/mpeg',
		'mpkg'        => 'application/vnd.apple.installer+xml',
		'mpm'         => 'application/vnd.blueice.multipass',
		'mpn'         => 'application/vnd.mophun.application',
		'mpp'         => 'application/vnd.ms-project',
		'mpy'         => 'application/vnd.ibm.minipay',
		'mqy'         => 'application/vnd.mobius.mqy',
		'mrc'         => 'application/marc',
		'mrcx'        => 'application/marcxml+xml',
		'mscml'       => 'application/mediaservercontrol+xml',
		'mseq'        => 'application/vnd.mseq',
		'msf'         => 'application/vnd.epson.msf',
		'msh'         => 'model/mesh',
		'msl'         => 'application/vnd.mobius.msl',
		'msty'        => 'application/vnd.muvee.style',
		'mts'         => 'model/vnd.mts',
		'mus'         => 'application/vnd.musician',
		'musicxml'    => 'application/vnd.recordare.musicxml+xml',
		'mvb'         => 'application/x-msmediaview',
		'mwf'         => 'application/vnd.mfer',
		'mxf'         => 'application/mxf',
		'mxl'         => 'application/vnd.recordare.musicxml',
		'mxml'        => 'application/xv+xml',
		'mxs'         => 'application/vnd.triscape.mxs',
		'mxu'         => 'video/vnd.mpegurl',
		'n-gage'      => 'application/vnd.nokia.n-gage.symbian.install',
		'n3'          => 'text/n3',
		'nbp'         => 'application/vnd.wolfram.player',
		'nc'          => 'application/x-netcdf',
		'ncx'         => 'application/x-dtbncx+xml',
		'ngdat'       => 'application/vnd.nokia.n-gage.data',
		'nlu'         => 'application/vnd.neurolanguage.nlu',
		'nml'         => 'application/vnd.enliven',
		'nnd'         => 'application/vnd.noblenet-directory',
		'nns'         => 'application/vnd.noblenet-sealer',
		'nnw'         => 'application/vnd.noblenet-web',
		'npx'         => 'image/vnd.net-fpx',
		'nsf'         => 'application/vnd.lotus-notes',
		'oa2'         => 'application/vnd.fujitsu.oasys2',
		'oa3'         => 'application/vnd.fujitsu.oasys3',
		'oas'         => 'application/vnd.fujitsu.oasys',
		'obd'         => 'application/x-msbinder',
		'oda'         => 'application/oda',
		'odb'         => 'application/vnd.oasis.opendocument.database',
		'odc'         => 'application/vnd.oasis.opendocument.chart',
		'odf'         => 'application/vnd.oasis.opendocument.formula',
		'odft'        => 'application/vnd.oasis.opendocument.formula-template',
		'odg'         => 'application/vnd.oasis.opendocument.graphics',
		'odi'         => 'application/vnd.oasis.opendocument.image',
		'odm'         => 'application/vnd.oasis.opendocument.text-master',
		'odp'         => 'application/vnd.oasis.opendocument.presentation',
		'ods'         => 'application/vnd.oasis.opendocument.spreadsheet',
		'odt'         => 'application/vnd.oasis.opendocument.text',
		'oga'         => 'audio/ogg',
		'ogv'         => 'video/ogg',
		'ogx'         => 'application/ogg',
		'onetoc'      => 'application/onenote',
		'opf'         => 'application/oebps-package+xml',
		'org'         => 'application/vnd.lotus-organizer',
		'osf'         => 'application/vnd.yamaha.openscoreformat',
		'osfpvg'      => 'application/vnd.yamaha.openscoreformat.osfpvg+xml',
		'otc'         => 'application/vnd.oasis.opendocument.chart-template',
		'otf'         => 'application/x-font-otf',
		'otg'         => 'application/vnd.oasis.opendocument.graphics-template',
		'oth'         => 'application/vnd.oasis.opendocument.text-web',
		'oti'         => 'application/vnd.oasis.opendocument.image-template',
		'otp'         => 'application/vnd.oasis.opendocument.presentation-template',
		'ots'         => 'application/vnd.oasis.opendocument.spreadsheet-template',
		'ott'         => 'application/vnd.oasis.opendocument.text-template',
		'oxt'         => 'application/vnd.openofficeorg.extension',
		'p'           => 'text/x-pascal',
		'p10'         => 'application/pkcs10',
		'p12'         => 'application/x-pkcs12',
		'p7b'         => 'application/x-pkcs7-certificates',
		'p7m'         => 'application/pkcs7-mime',
		'p7r'         => 'application/x-pkcs7-certreqresp',
		'p7s'         => 'application/pkcs7-signature',
		'p8'          => 'application/pkcs8',
		'par'         => 'text/plain-bas',
		'paw'         => 'application/vnd.pawaafile',
		'pbd'         => 'application/vnd.powerbuilder6',
		'pbm'         => 'image/x-portable-bitmap',
		'pcf'         => 'application/x-font-pcf',
		'pcl'         => 'application/vnd.hp-pcl',
		'pclxl'       => 'application/vnd.hp-pclxl',
		'pcurl'       => 'application/vnd.curl.pcurl',
		'pcx'         => 'image/x-pcx',
		'pdb'         => 'application/vnd.palm',
		'pdf'         => 'application/pdf',
		'pfa'         => 'application/x-font-type1',
		'pfr'         => 'application/font-tdpfr',
		'pgm'         => 'image/x-portable-graymap',
		'pgn'         => 'application/x-chess-pgn',
		'pgp'         => 'application/pgp-encrypted',
		'pgp'         => 'application/pgp-signature',
		'pic'         => 'image/x-pict',
		'pjpeg'       => 'image/pjpeg',
		'pki'         => 'application/pkixcmp',
		'pkipath'     => 'application/pkix-pkipath',
		'plb'         => 'application/vnd.3gpp.pic-bw-large',
		'plc'         => 'application/vnd.mobius.plc',
		'plf'         => 'application/vnd.pocketlearn',
		'pls'         => 'application/pls+xml',
		'pml'         => 'application/vnd.ctc-posml',
		'png'         => 'image/png',
		'png'         => 'image/x-png',
		'png'         => 'image/x-citrix-png',
		'pnm'         => 'image/x-portable-anymap',
		'portpkg'     => 'application/vnd.macports.portpkg',
		'potm'        => 'application/vnd.ms-powerpoint.template.macroenabled.12',
		'potx'        => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'ppam'        => 'application/vnd.ms-powerpoint.addin.macroenabled.12',
		'ppd'         => 'application/vnd.cups-ppd',
		'ppm'         => 'image/x-portable-pixmap',
		'ppsm'        => 'application/vnd.ms-powerpoint.slideshow.macroenabled.12',
		'ppsx'        => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppt'         => 'application/vnd.ms-powerpoint',
		'pptm'        => 'application/vnd.ms-powerpoint.presentation.macroenabled.12',
		'pptx'        => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'prc'         => 'application/x-mobipocket-ebook',
		'pre'         => 'application/vnd.lotus-freelance',
		'prf'         => 'application/pics-rules',
		'psb'         => 'application/vnd.3gpp.pic-bw-small',
		'psd'         => 'image/vnd.adobe.photoshop',
		'psf'         => 'application/x-font-linux-psf',
		'pskcxml'     => 'application/pskc+xml',
		'ptid'        => 'application/vnd.pvi.ptid1',
		'pub'         => 'application/x-mspublisher',
		'pvb'         => 'application/vnd.3gpp.pic-bw-var',
		'pwn'         => 'application/vnd.3m.post-it-notes',
		'pya'         => 'audio/vnd.ms-playready.media.pya',
		'pyv'         => 'video/vnd.ms-playready.media.pyv',
		'qam'         => 'application/vnd.epson.quickanime',
		'qbo'         => 'application/vnd.intu.qbo',
		'qfx'         => 'application/vnd.intu.qfx',
		'qps'         => 'application/vnd.publishare-delta-tree',
		'qt'          => 'video/quicktime',
		'qxd'         => 'application/vnd.quark.quarkxpress',
		'ram'         => 'audio/x-pn-realaudio',
		'rar'         => 'application/x-rar-compressed',
		'ras'         => 'image/x-cmu-raster',
		'rcprofile'   => 'application/vnd.ipunplugged.rcprofile',
		'rdf'         => 'application/rdf+xml',
		'rdz'         => 'application/vnd.data-vision.rdz',
		'rep'         => 'application/vnd.businessobjects',
		'res'         => 'application/x-dtbresource+xml',
		'rgb'         => 'image/x-rgb',
		'rif'         => 'application/reginfo+xml',
		'rip'         => 'audio/vnd.rip',
		'rl'          => 'application/resource-lists+xml',
		'rlc'         => 'image/vnd.fujixerox.edmics-rlc',
		'rld'         => 'application/resource-lists-diff+xml',
		'rm'          => 'application/vnd.rn-realmedia',
		'rmp'         => 'audio/x-pn-realaudio-plugin',
		'rms'         => 'application/vnd.jcp.javame.midlet-rms',
		'rnc'         => 'application/relax-ng-compact-syntax',
		'rp9'         => 'application/vnd.cloanto.rp9',
		'rpss'        => 'application/vnd.nokia.radio-presets',
		'rpst'        => 'application/vnd.nokia.radio-preset',
		'rq'          => 'application/sparql-query',
		'rs'          => 'application/rls-services+xml',
		'rsd'         => 'application/rsd+xml',
		'rss'         => 'application/rss+xml',
		'rtf'         => 'application/rtf',
		'rtx'         => 'text/richtext',
		's'           => 'text/x-asm',
		'saf'         => 'application/vnd.yamaha.smaf-audio',
		'sbml'        => 'application/sbml+xml',
		'sc'          => 'application/vnd.ibm.secure-container',
		'scd'         => 'application/x-msschedule',
		'scm'         => 'application/vnd.lotus-screencam',
		'scq'         => 'application/scvp-cv-request',
		'scs'         => 'application/scvp-cv-response',
		'scurl'       => 'text/vnd.curl.scurl',
		'sda'         => 'application/vnd.stardivision.draw',
		'sdc'         => 'application/vnd.stardivision.calc',
		'sdd'         => 'application/vnd.stardivision.impress',
		'sdkm'        => 'application/vnd.solent.sdkm+xml',
		'sdp'         => 'application/sdp',
		'sdw'         => 'application/vnd.stardivision.writer',
		'see'         => 'application/vnd.seemail',
		'seed'        => 'application/vnd.fdsn.seed',
		'sema'        => 'application/vnd.sema',
		'semd'        => 'application/vnd.semd',
		'semf'        => 'application/vnd.semf',
		'ser'         => 'application/java-serialized-object',
		'setpay'      => 'application/set-payment-initiation',
		'setreg'      => 'application/set-registration-initiation',
		'sfd-hdstx'   => 'application/vnd.hydrostatix.sof-data',
		'sfs'         => 'application/vnd.spotfire.sfs',
		'sgl'         => 'application/vnd.stardivision.writer-global',
		'sgml'        => 'text/sgml',
		'sh'          => 'application/x-sh',
		'shar'        => 'application/x-shar',
		'shf'         => 'application/shf+xml',
		'sis'         => 'application/vnd.symbian.install',
		'sit'         => 'application/x-stuffit',
		'sitx'        => 'application/x-stuffitx',
		'skp'         => 'application/vnd.koan',
		'sldm'        => 'application/vnd.ms-powerpoint.slide.macroenabled.12',
		'sldx'        => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'slt'         => 'application/vnd.epson.salt',
		'sm'          => 'application/vnd.stepmania.stepchart',
		'smf'         => 'application/vnd.stardivision.math',
		'smi'         => 'application/smil+xml',
		'snf'         => 'application/x-font-snf',
		'spf'         => 'application/vnd.yamaha.smaf-phrase',
		'spl'         => 'application/x-futuresplash',
		'spot'        => 'text/vnd.in3d.spot',
		'spp'         => 'application/scvp-vp-response',
		'spq'         => 'application/scvp-vp-request',
		'src'         => 'application/x-wais-source',
		'sru'         => 'application/sru+xml',
		'srx'         => 'application/sparql-results+xml',
		'sse'         => 'application/vnd.kodak-descriptor',
		'ssf'         => 'application/vnd.epson.ssf',
		'ssml'        => 'application/ssml+xml',
		'st'          => 'application/vnd.sailingtracker.track',
		'stc'         => 'application/vnd.sun.xml.calc.template',
		'std'         => 'application/vnd.sun.xml.draw.template',
		'stf'         => 'application/vnd.wt.stf',
		'sti'         => 'application/vnd.sun.xml.impress.template',
		'stk'         => 'application/hyperstudio',
		'stl'         => 'application/vnd.ms-pki.stl',
		'str'         => 'application/vnd.pg.format',
		'stw'         => 'application/vnd.sun.xml.writer.template',
		'sub'         => 'image/vnd.dvb.subtitle',
		'sus'         => 'application/vnd.sus-calendar',
		'sv4cpio'     => 'application/x-sv4cpio',
		'sv4crc'      => 'application/x-sv4crc',
		'svc'         => 'application/vnd.dvb.service',
		'svd'         => 'application/vnd.svd',
		'svg'         => 'image/svg+xml',
		'swf'         => 'application/x-shockwave-flash',
		'swi'         => 'application/vnd.aristanetworks.swi',
		'sxc'         => 'application/vnd.sun.xml.calc',
		'sxd'         => 'application/vnd.sun.xml.draw',
		'sxg'         => 'application/vnd.sun.xml.writer.global',
		'sxi'         => 'application/vnd.sun.xml.impress',
		'sxm'         => 'application/vnd.sun.xml.math',
		'sxw'         => 'application/vnd.sun.xml.writer',
		't'           => 'text/troff',
		'tao'         => 'application/vnd.tao.intent-module-archive',
		'tar'         => 'application/x-tar',
		'tcap'        => 'application/vnd.3gpp2.tcap',
		'tcl'         => 'application/x-tcl',
		'teacher'     => 'application/vnd.smart.teacher',
		'tei'         => 'application/tei+xml',
		'tex'         => 'application/x-tex',
		'texinfo'     => 'application/x-texinfo',
		'tfi'         => 'application/thraud+xml',
		'tfm'         => 'application/x-tex-tfm',
		'thmx'        => 'application/vnd.ms-officetheme',
		'tiff'        => 'image/tiff',
		'tmo'         => 'application/vnd.tmobile-livetv',
		'torrent'     => 'application/x-bittorrent',
		'tpl'         => 'application/vnd.groove-tool-template',
		'tpt'         => 'application/vnd.trid.tpt',
		'tra'         => 'application/vnd.trueapp',
		'trm'         => 'application/x-msterminal',
		'tsd'         => 'application/timestamped-data',
		'tsv'         => 'text/tab-separated-values',
		'ttf'         => 'application/x-font-ttf',
		'ttl'         => 'text/turtle',
		'twd'         => 'application/vnd.simtech-mindmapper',
		'txd'         => 'application/vnd.genomatix.tuxedo',
		'txf'         => 'application/vnd.mobius.txf',
		'txt'         => 'text/plain',
		'ufd'         => 'application/vnd.ufdl',
		'umj'         => 'application/vnd.umajin',
		'unityweb'    => 'application/vnd.unity',
		'uoml'        => 'application/vnd.uoml+xml',
		'uri'         => 'text/uri-list',
		'ustar'       => 'application/x-ustar',
		'utz'         => 'application/vnd.uiq.theme',
		'uu'          => 'text/x-uuencode',
		'uva'         => 'audio/vnd.dece.audio',
		'uvh'         => 'video/vnd.dece.hd',
		'uvi'         => 'image/vnd.dece.graphic',
		'uvm'         => 'video/vnd.dece.mobile',
		'uvp'         => 'video/vnd.dece.pd',
		'uvs'         => 'video/vnd.dece.sd',
		'uvu'         => 'video/vnd.uvvu.mp4',
		'uvv'         => 'video/vnd.dece.video',
		'vcd'         => 'application/x-cdlink',
		'vcf'         => 'text/x-vcard',
		'vcg'         => 'application/vnd.groove-vcard',
		'vcs'         => 'text/x-vcalendar',
		'vcx'         => 'application/vnd.vcx',
		'vis'         => 'application/vnd.visionary',
		'viv'         => 'video/vnd.vivo',
		'vsd'         => 'application/vnd.visio',
		'vsdx'        => 'application/vnd.visio2013',
		'vsf'         => 'application/vnd.vsf',
		'vtu'         => 'model/vnd.vtu',
		'vxml'        => 'application/voicexml+xml',
		'wad'         => 'application/x-doom',
		'wav'         => 'audio/x-wav',
		'wax'         => 'audio/x-ms-wax',
		'wbmp'        => 'image/vnd.wap.wbmp',
		'wbs'         => 'application/vnd.criticaltools.wbs+xml',
		'wbxml'       => 'application/vnd.wap.wbxml',
		'weba'        => 'audio/webm',
		'webm'        => 'video/webm',
		'webp'        => 'image/webp',
		'wg'          => 'application/vnd.pmi.widget',
		'wgt'         => 'application/widget',
		'wm'          => 'video/x-ms-wm',
		'wma'         => 'audio/x-ms-wma',
		'wmd'         => 'application/x-ms-wmd',
		'wmf'         => 'application/x-msmetafile',
		'wml'         => 'text/vnd.wap.wml',
		'wmlc'        => 'application/vnd.wap.wmlc',
		'wmls'        => 'text/vnd.wap.wmlscript',
		'wmlsc'       => 'application/vnd.wap.wmlscriptc',
		'wmv'         => 'video/x-ms-wmv',
		'wmx'         => 'video/x-ms-wmx',
		'wmz'         => 'application/x-ms-wmz',
		'woff'        => 'application/x-font-woff',
		'wpd'         => 'application/vnd.wordperfect',
		'wpl'         => 'application/vnd.ms-wpl',
		'wps'         => 'application/vnd.ms-works',
		'wqd'         => 'application/vnd.wqd',
		'wri'         => 'application/x-mswrite',
		'wrl'         => 'model/vrml',
		'wsdl'        => 'application/wsdl+xml',
		'wspolicy'    => 'application/wspolicy+xml',
		'wtb'         => 'application/vnd.webturbo',
		'wvx'         => 'video/x-ms-wvx',
		'x3d'         => 'application/vnd.hzn-3d-crossword',
		'xap'         => 'application/x-silverlight-app',
		'xar'         => 'application/vnd.xara',
		'xbap'        => 'application/x-ms-xbap',
		'xbd'         => 'application/vnd.fujixerox.docuworks.binder',
		'xbm'         => 'image/x-xbitmap',
		'xdf'         => 'application/xcap-diff+xml',
		'xdm'         => 'application/vnd.syncml.dm+xml',
		'xdp'         => 'application/vnd.adobe.xdp+xml',
		'xdssc'       => 'application/dssc+xml',
		'xdw'         => 'application/vnd.fujixerox.docuworks',
		'xenc'        => 'application/xenc+xml',
		'xer'         => 'application/patch-ops-error+xml',
		'xfdf'        => 'application/vnd.adobe.xfdf',
		'xfdl'        => 'application/vnd.xfdl',
		'xhtml'       => 'application/xhtml+xml',
		'xif'         => 'image/vnd.xiff',
		'xlam'        => 'application/vnd.ms-excel.addin.macroenabled.12',
		'xls'         => 'application/vnd.ms-excel',
		'xlsb'        => 'application/vnd.ms-excel.sheet.binary.macroenabled.12',
		'xlsm'        => 'application/vnd.ms-excel.sheet.macroenabled.12',
		'xlsx'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xltm'        => 'application/vnd.ms-excel.template.macroenabled.12',
		'xltx'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xml'         => 'application/xml',
		'xo'          => 'application/vnd.olpc-sugar',
		'xop'         => 'application/xop+xml',
		'xpi'         => 'application/x-xpinstall',
		'xpm'         => 'image/x-xpixmap',
		'xpr'         => 'application/vnd.is-xpr',
		'xps'         => 'application/vnd.ms-xpsdocument',
		'xpw'         => 'application/vnd.intercon.formnet',
		'xslt'        => 'application/xslt+xml',
		'xsm'         => 'application/vnd.syncml+xml',
		'xspf'        => 'application/xspf+xml',
		'xul'         => 'application/vnd.mozilla.xul+xml',
		'xwd'         => 'image/x-xwindowdump',
		'xyz'         => 'chemical/x-xyz',
		'yaml'        => 'text/yaml',
		'yang'        => 'application/yang',
		'yin'         => 'application/yin+xml',
		'zaz'         => 'application/vnd.zzazz.deck+xml',
		'zip'         => 'application/zip',
		'zir'         => 'application/vnd.zul',
		'zmm'         => 'application/vnd.handheld-entertainment+xml',
		'N/A'         => 'application/andrew-inset'
	);

	return $mt;
}

function wpjobster_generate_thumb($img_url, $width, $height, $cut = true){
	require_once(ABSPATH . '/wp-admin/includes/image.php');
	$uploads = wp_upload_dir();
	$basedir = $uploads['basedir'] . '/';
	$exp = explode('/', $img_url);
	$nr = count($exp);
	$pic = $exp[$nr - 1];
	$year = $exp[$nr - 3];
	$month = $exp[$nr - 2];

	if ($uploads['basedir'] == $uploads['path']) {
		$img_url = $basedir . '/' . $pic;
		$ba = $basedir . '/';
		$iii = $uploads['url'];
	} else {
		$img_url = $basedir . $year . '/' . $month . '/' . $pic;
		$ba = $basedir . $year . '/' . $month . '/';
		$iii = $uploads['baseurl'] . "/" . $year . "/" . $month;
	}

	list($width1, $height1, $type1, $attr1) = getimagesize($img_url);

	$a = false;

	if ($width == -1) {
		$a = true;
	}


	if ($width > $width1) $width = $width1 - 1;
	if ($height > $height1) $height = $height1 - 1;

	if ($a == true) {
		$prop = $width1 / $height1;
		$width = round($prop * $height);
	}

	$width = $width - 1;
	$height = $height - 1;
	$xxo = "-" . $width . "x" . $height;
	$exp = explode(".", $pic);
	$new_name = $exp[0] . $xxo . "." . $exp[1];
	$tgh = str_replace("//", "/", $ba . $new_name);

	if (file_exists($tgh)) return $iii . "/" . $new_name;
	$thumb = image_resize($img_url, $width, $height, $cut);

	if (is_wp_error($thumb)) return "is-wp-error";
	$exp = explode($basedir, $thumb);
	return $uploads['baseurl'] . "/" . $exp[1];
}

function wpjobster_get_avatar( $uid, $w = 25, $h = 25 ) {
	if ( is_array( $w ) ) {
		$h = $w[1];
		$w = $w[0];
	}
	$avatar_id = get_user_meta( $uid, 'avatar_id', true );
	$avatar = get_user_meta( $uid, 'avatar', true );
	if ( ! empty( $avatar_id ) && is_numeric( $avatar_id ) ) {
		return wpj_get_attachment_image_url( $avatar_id, array( $w, $h ) );
	} elseif ( ! empty( $avatar ) ) {
		$avatar_id = wpjobster_get_attachment_id_from_url( $avatar );
		if( $avatar_id ){
			return wpj_get_attachment_image_url( $avatar_id, array( $w, $h ) );
		}else{
			return $avatar;
		}
	} else {
		return get_template_directory_uri() . "/images/noav.jpg";
	}
}

function wpjobster_get_post_images($pid, $limit = -1){
	//---------------------
	// build the exclude list
	$exclude = array();
	$args = array(
		'order'       => 'ASC',
		'post_type'   => 'attachment',
		'post_parent' => get_the_ID(),
		'meta_key'    => 'another_reserved1',
		'meta_value'  => '1',
		'numberposts' => -1,
		'post_status' => null
	);
	$attachments = get_posts($args);

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$url = $attachment->ID;
			array_push($exclude, $url);
		}

	}

	$arr = array();
	$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'post_parent'    => $pid,
		'exclude'        => $exclude,
		'post_mime_type' => 'image',
		'numberposts'    => $limit
	);
	$i = 0;
	$attachments = get_posts($args);

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$url = wp_get_attachment_url($attachment->ID);
			array_push($arr, $url);
		}

		return $arr;
	}

	return false;
}

function wpjobster_get_post_images_ids($pid, $limit = -1){
	// build the exclude list
	$exclude = array();
	$args = array(
		'order'       => 'ASC',
		'post_type'   => 'attachment',
		'post_parent' => get_the_ID(),
		'meta_key'    => 'another_reserved1',
		'meta_value'  => '1',
		'numberposts' => -1,
		'post_status' => null
	);
	$attachments = get_posts($args);

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$url = $attachment->ID;
			array_push($exclude, $url);
		}

	}

	$arr = array();
	$i = 0;
	$attachments = wpjobster_get_job_images( $pid, $limit, $exclude );

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$url = $attachment->ID;
			array_push($arr, $url);
		}
	}

	return $arr;
}

add_filter( 'get_avatar', 'wpjobster_custom_avatar', 1, 5 );
function wpjobster_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	$user = false;

	if ( is_numeric( $id_or_email ) ) {

		$id = (int) $id_or_email;
		$user = get_user_by( 'id' , $id );

	} elseif ( is_object( $id_or_email ) ) {

		if ( ! empty( $id_or_email->user_id ) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_user_by( 'id' , $id );
		}

	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( $user && is_object( $user ) ) {

		if ( isset( $id ) && isset( $user->data->ID ) && $user->data->ID == $id ) {

			$av = wpjobster_get_avatar( $id, 64, 64 );

			$avatar = "<img alt='{$alt}' src='{$av}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}

	}

	return $avatar;
}

// UPDATE PROFILE PICTURE - WP SOCIAL LOGIN
add_action( 'user_register', 'wpjobster_set_social_login_avatar', 10, 1 );
function wpjobster_set_social_login_avatar( $user_id ){
	update_user_meta( $user_id, 'wsl_photo_updated', '0' );
}

add_action( 'init','wpjobster_set_fb_user_avatar' );
function wpjobster_set_fb_user_avatar(){
	if ( function_exists( 'wsl_get_stored_hybridauth_user_profiles_by_user_id' ) ) {
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$is_social = wsl_get_stored_hybridauth_user_profiles_by_user_id( $uid );

		// add conditions in cascade to reduce unnecessary overload
		if( $is_social ){
			if ( $uid ) {
				$wsl_photo_updated = get_user_meta( $uid, 'wsl_photo_updated', true );
				if ( $wsl_photo_updated != 'done' ) {
					$avatar = get_user_meta( $uid, 'avatar', true );
					if ( $avatar == '' ) {
						$wsl_account = wsl_get_stored_hybridauth_user_profiles_by_user_id( $uid );
						if ( $wsl_account != '' ) {
							if( isset( $wsl_account[0]->photourl ) ){
								$wsl_avatar = $wsl_account[0]->photourl;
								update_user_meta( $uid, 'avatar', $wsl_avatar );
								update_user_meta( $uid, 'wsl_photo_updated', 'done' );
							}
						}
					}
				}
			}
		}
	}
}

// SET AUTHENTICATION TOKEN TRANSIENT
add_action( 'wp_ajax_nopriv_wpjobster_save_auth_token_transient', 'wpjobster_save_auth_token_transient' );
add_action( 'wp_ajax_wpjobster_save_auth_token_transient', 'wpjobster_save_auth_token_transient' );
function wpjobster_save_auth_token_transient(){
	set_post_meta_transient( $_POST['secure_download'], 'wpj_authentication_token', $_POST['auth_token'], 600 );
	wp_die();
}
