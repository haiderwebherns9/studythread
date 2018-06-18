<?php
/**
 * https://github.com/enyo/dropzone/releases
 */

function wpjobster_get_preferred_uploader() {

	$wpjobster_preferred_image_uploader = get_option( 'wpjobster_preferred_image_uploader' );

	if ( $wpjobster_preferred_image_uploader === 'html5fileupload' ) {
		$uploader = 'html5fileupload';
	} else {
		$uploader = 'dropzone';
	}

	return $uploader;
}

function wpjobster_init_uploader_scripts() {
	global $wpjobster_needs_uploader_scripts;
	$wpjobster_needs_uploader_scripts = 1;
}

function wpjobster_needs_uploader_scripts() {
	global $wpjobster_needs_uploader_scripts;
	if ( $wpjobster_needs_uploader_scripts === 1
		|| ( is_admin()
			&& isset( $_GET['action'] )
			&& $_GET['action'] === 'edit'
			&& get_post_type() === 'job' ) ) {
		return 1;
	} else {
		return 0;
	}
}

function wpjobster_max_img_upload_size() {
	$wpjobster_max_img_upload_size_mb = get_option( 'wpjobster_max_img_upload_size' );
	$wp_max_upload_size = wp_max_upload_size();

	if ( ! is_numeric( $wpjobster_max_img_upload_size_mb ) || ! $wpjobster_max_img_upload_size_mb ) {
		$wpjobster_max_img_upload_size_mb = 10;
	}

	// return the smallest limit
	if ( $wp_max_upload_size >= $wpjobster_max_img_upload_size_mb ) {
		$wpjobster_max_img_upload_size = $wpjobster_max_img_upload_size_mb * 1048576;
	} else {
		$wpjobster_max_img_upload_size = $wp_max_upload_size * 1048576; // 1024 * 1024
	}

	return $wpjobster_max_img_upload_size;
}

function wpjobster_profile_max_img_upload_size() {
	$wpjobster_max_img_upload_size_mb = get_option( 'wpjobster_profile_max_img_upload_size' );
	$wp_max_upload_size = wp_max_upload_size();

	if ( ! is_numeric( $wpjobster_max_img_upload_size_mb ) || ! $wpjobster_max_img_upload_size_mb ) {
		$wpjobster_max_img_upload_size_mb = 10;
	}

	// return the smallest limit
	if ( $wp_max_upload_size >= $wpjobster_max_img_upload_size_mb ) {
		$wpjobster_max_img_upload_size = $wpjobster_max_img_upload_size_mb * 1048576;
	} else {
		$wpjobster_max_img_upload_size = $wp_max_upload_size * 1048576; // 1024 * 1024
	}

	return $wpjobster_max_img_upload_size;
}

function wpjobster_get_job_images( $pid, $limit = -1, $exclude = array() ) {
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $pid,
		'exclude' => $exclude,
		'post_mime_type' => 'image',
		'numberposts' => $limit,

		'meta_query' => array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array( //check to see if images_order has been filled out
					'key' => 'images_order',
					'compare' => 'EXISTS',
				),
				array( //if no images_order has been added show these posts too
					'key' => 'images_order',
					'compare' => 'NOT EXISTS',
				),
			),
			array(
				'relation' => 'OR',
				array(
					'key' => 'is_cover',
					'value' => 1,
					'compare' => '!='
				),
				array(
					'key' => 'is_cover',
					'compare' => 'NOT EXISTS',
				),
			),
		),
		'orderby' => 'meta_value_num date',
		'order' => 'ASC',
	);
	$attachments = get_posts($args);
	return $attachments;
}

function wpjobster_get_portfolio_images( $uid, $limit = -1, $exclude = array() ) {
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => null,
		'author' => $uid,
		'exclude' => $exclude,
		'post_mime_type' => 'image',
		'numberposts' => $limit,

		'meta_query' => array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array( //check to see if images_order has been filled out
					'key' => 'images_order',
					'compare' => 'EXISTS',
				),
				array( //if no images_order has been added show these posts too
					'key' => 'images_order',
					'compare' => 'NOT EXISTS',
				),
			),
			array(
				'key' => 'is_portfolio',
				'value' => 1,
				'compare' => '=='
			),
		),
		'orderby' => 'meta_value_num date',
		'order' => 'ASC',
	);
	$attachments = get_posts($args);
	return $attachments;
}


// Enqueue Scripts
function dropzonejs_enqueue_scripts() {
	if ( wpjobster_needs_uploader_scripts() ) {

		wp_enqueue_script(
			'dropzonejs',
			get_template_directory_uri() . '/vendor/dropzone/min/dropzone.min.js',
			array(),
			4.3
		);

		wp_enqueue_script(
			'customdropzonejs',
			get_template_directory_uri() . '/lib/dropzone/dropzone.js',
			array( 'dropzonejs', 'jquery' ),
			4.3
		);

		wp_enqueue_style(
			'dropzonecss',
			get_template_directory_uri() . '/vendor/dropzone/min/dropzone.min.css',
			array(),
			4.3
		);

		wp_enqueue_style(
			'customdropzonecss',
			get_template_directory_uri() . '/lib/dropzone/dropzone.css',
			array(),
			4.3
		);
	}
}
add_action( 'wp_enqueue_scripts', 'dropzonejs_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'dropzonejs_enqueue_scripts' );


// Form Here
function wpjobster_dropzone_image_uploader( $pid, $uploader_purpose = 'job' ) {

	// limits
	if ( $uploader_purpose == 'job' ) {
		$wpjobster_default_nr_of_pics = get_option( 'wpjobster_default_nr_of_pics' );
		$wpjobster_max_img_upload_size = wpjobster_max_img_upload_size();
		$attachments = wpjobster_get_job_images( $pid );

	} elseif ( $uploader_purpose == 'portfolio' ) {

		$uid = $pid;

		$wpjobster_default_nr_of_pics = get_option( 'wpjobster_profile_default_nr_of_pics' );
		$wpjobster_max_img_upload_size = wpjobster_profile_max_img_upload_size();
		$attachments = wpjobster_get_portfolio_images( $uid );
	}

	if ( $wpjobster_default_nr_of_pics == "" ) {
		$wpjobster_default_nr_of_pics = '5';
	}

	$wpjobster_max_img_upload_size_mb = $wpjobster_max_img_upload_size / 1048576;


	$thumbs = '';

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$img_id = $attachment->ID;
			$img_thumb = wpj_get_attachment_image_url( $img_id, array( 110, 110 ) );

			$thumbs .= <<<THUMBS
<div class="dz-preview dz-image-preview dz-complete" data-id="$img_id" id="image_ss{$img_id}">
	<div class="dz-image"><img data-dz-thumbnail="" alt="" src="$img_thumb"></div>
	<a href="javascript: void(0);" onclick="delete_this('{$img_id}');" class="delete-this"></a>
</div>
THUMBS;
		}
	}


	$nonce_files = wp_nonce_field( 'protect_content', 'jobimages_nonce_field' );
	echo <<<DZFORM
<div id="dropzone-jobimages" class="dropzone needsclick dz-clickable cf" data-pid="$pid" data-maxfilesize="{$wpjobster_max_img_upload_size}" data-uploader_purpose="{$uploader_purpose}" data-maxnrofpictures="{$wpjobster_default_nr_of_pics}">
		$thumbs
</div>
<div id="dropzone-jobimages-fields">
	$nonce_files
	<input type='hidden' id='images_order' name='images_order'>
</div>
DZFORM;

	// translating default error messages
	$dictFallbackMessage = addslashes( __( "Your browser does not support drag'n'drop file uploads.", "wpjobster" ) );
	$dictFallbackText = addslashes( __( "Please use the fallback form below to upload your files like in the olden days.", "wpjobster" ) );
	$dictFileTooBig = addslashes( __( "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", "wpjobster" ) );
	$dictInvalidFileType = addslashes( __( "You can't upload files of this type.", "wpjobster" ) );
	$dictResponseError = addslashes( __( "Server responded with {{statusCode}} code.", "wpjobster" ) );
	$dictCancelUpload = addslashes( __( "Cancel upload", "wpjobster" ) );
	$dictCancelUploadConfirmation = addslashes( __( "Are you sure you want to cancel this upload?", "wpjobster" ) );
	$dictRemoveFile = addslashes( __( "Remove file", "wpjobster" ) );
	$dictMaxFilesExceeded = addslashes( __( "You cannot upload any more files.", "wpjobster" ) );

	echo <<<DZJS
<script>
jQuery(document).ready(function ($) {

	$("div#dropzone-jobimages").dropzone({
		url: ajaxurl,
		maxFiles: {$wpjobster_default_nr_of_pics},
		maxFilesize: {$wpjobster_max_img_upload_size_mb},
		acceptedFiles: 'image/*',
		addRemoveLinks: false,

		dictDefaultMessage: "",
		dictFallbackMessage: "{$dictFallbackMessage}",
		dictFallbackText: "{$dictFallbackText}",
		dictFileTooBig: "{$dictFileTooBig}",
		dictInvalidFileType: "{$dictInvalidFileType}",
		dictResponseError: "{$dictResponseError}",
		dictCancelUpload: "{$dictCancelUpload}",
		dictCancelUploadConfirmation: "{$dictCancelUploadConfirmation}",
		dictRemoveFile: "{$dictRemoveFile}",
		dictRemoveFileConfirmation: null,
		dictMaxFilesExceeded: "{$dictMaxFilesExceeded}",

		init: function() {
			dzClosure = this;
			this.on("sending", function(data, xhr, formData) {
				formData.append("action", "submit_dropzone_image");
				formData.append("jobimages_nonce_field", $("#jobimages_nonce_field").val());
				formData.append("pid", $("#dropzone-jobimages").data("pid"));
				formData.append("uploader_purpose", $("#dropzone-jobimages").data("uploader_purpose"));
				formData.append("MAX_FILE_SIZE", $("#dropzone-jobimages").data("maxfilesize"));

				$('#dropzone-jobimages').append($('#dropzone-jobimages > div.dz-message'));
			});
			this.on("success", function(file, response) {
				var response = $.parseJSON( response );
				if ( response.error !== false ) {
					$( file.previewElement ).removeClass( "dz-success" );
					$( file.previewElement ).addClass( "dz-error" );
					$( file.previewElement ).find( ".dz-error-message > span" ).append( response.error );
				} else {
					$( file.previewElement ).attr( "data-id", response.id );
					$( file.previewElement ).attr( "id", "image_ss" + response.id );
				}
				if ( response.id !== 0 ) {
					$( file.previewElement ).append( '<a href="javascript: void(0);" onclick="delete_this(' + response.id + ');" class="delete-this"></a>' );
				} else {
					$( file.previewElement ).append( '<a href="javascript: void(0);" onclick="$(this).parent().remove();" class="delete-this"></a>' );
				}
			});
			this.on("error", function(file, errorMessage, xhr) {
				$(file.previewElement).append( '<a href="javascript: void(0);" onclick="$(this).parent().remove();" class="delete-this"></a>' );
			});
			this.on("complete", function(data, xhr, formData) {
				if ( $( "#dropzone-jobimages .dz-preview.dz-image-preview.dz-complete" ).length >= $("#dropzone-jobimages").data("maxnrofpictures") ) {
					$( "#dropzone-jobimages" ).addClass( "maxnrofpictures" );
				}
			});
		}
	});
});
</script>
DZJS;
}


// AJAX Here
function wpjobster_dropzone_image_uploader_action() {
	if ( ! empty($_FILES) && wp_verify_nonce($_REQUEST['jobimages_nonce_field'], 'protect_content') ) {

		$pid = $_POST['pid'];
		$uploader_purpose = $_POST['uploader_purpose'];

		global $current_user;
		$current_user = wp_get_current_user();

		// limits
		if ( $uploader_purpose == 'job' ) {

			$wpjobster_default_nr_of_pics = get_option( 'wpjobster_default_nr_of_pics' );
			$allowed_size_width = get_option( 'wpjobster_min_img_upload_width' );
			$allowed_size_height = get_option( 'wpjobster_min_img_upload_height' );
			$wpjobster_max_img_upload_size = wpjobster_max_img_upload_size();

			$post = get_post( $pid );
			$attachments = wpjobster_get_job_images( $pid );

		} elseif ( $uploader_purpose == 'portfolio' ) {

			$wpjobster_default_nr_of_pics = get_option( 'wpjobster_profile_default_nr_of_pics' );
			$allowed_size_width = get_option( 'wpjobster_profile_min_img_upload_width' );
			$allowed_size_height = get_option( 'wpjobster_profile_min_img_upload_height' );
			$wpjobster_max_img_upload_size = wpjobster_profile_max_img_upload_size();

			$uid = $pid;
			$attachments = wpjobster_get_portfolio_images( $uid );
		}

		if ( $wpjobster_default_nr_of_pics == '' ) {
			$wpjobster_default_nr_of_pics = 5;
		}



		$error = false;
		$attach_id = 0;

		$attachments_count = count( $attachments );

		$wpjobster_max_img_upload_size_mb = $wpjobster_max_img_upload_size / 1048576;

		if ( ! is_numeric( $allowed_size_width ) || ! $allowed_size_width ) { $allowed_size_width = 720; }
		if ( ! is_numeric( $allowed_size_height ) || ! $allowed_size_height ) { $allowed_size_height = 405; }
		if ( isset( $_FILES['file']['tmp_name'] ) ) {
			$tmp_name = $_FILES['file']['tmp_name'];
			list( $file_width, $file_height ) = getimagesize( $tmp_name );
		} else {
			$file_width = 0;
			$file_height = 0;
		}

		try {

			if ( $uploader_purpose == 'job' && $post->post_author != $current_user->ID && ! user_can( $current_user, 'manage_options' ) ) {
				throw new RuntimeException( __('You can upload pictures only to your own job.', 'wpjobster') );
			}

			if ( $uploader_purpose == 'portfolio' && $uid != $current_user->ID && ! user_can( $current_user, 'manage_options' ) ) {
				throw new RuntimeException( __('You can upload pictures only to your portfolio.', 'wpjobster') );
			}

			if ( $attachments_count >= $wpjobster_default_nr_of_pics ) {
				throw new RuntimeException( sprintf(__('Only %s images are allowed.', 'wpjobster'), $wpjobster_default_nr_of_pics) );
			}

			if ( ! isset($_FILES['file']['error']) || is_array($_FILES['file']['error']) ) {
				throw new RuntimeException( __('Invalid parameters.', 'wpjobster') );
			}

			// Check $_FILES['file']['error'] value.
			switch ( $_FILES['file']['error'] ) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException( __('No file sent.', 'wpjobster') );
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException( sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $wpjobster_max_img_upload_size_mb) );
				default:
					throw new RuntimeException( __('Unknown errors.', 'wpjobster') );
			}

			// Check filesize again
			if ( $_FILES['file']['size'] > $wpjobster_max_img_upload_size ) {
				throw new RuntimeException( sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $wpjobster_max_img_upload_size_mb) );
			}

			// DO NOT TRUST $_FILES['file']['mime'] VALUE !!
			// Check MIME Type by yourself.
			if ( class_exists( 'finfo' ) ) {
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (false === $ext = array_search(
					$finfo->file($_FILES['file']['tmp_name']),
					array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
						'gif' => 'image/gif',
					),
					true
				)) {
					throw new RuntimeException( __('Invalid file format.', 'wpjobster') );
				}
			}

			// Check file dimensions
			if ( $file_width < $allowed_size_width || $file_height < $allowed_size_height ) {
				throw new RuntimeException( sprintf(__('Minimum file size: %1$s x %2$s px.', 'wpjobster'), $allowed_size_width, $allowed_size_height) );
			}

		} catch ( RuntimeException $e ) {
			$error = $e->getMessage();
		}

		if ( $error === false ) {

			$uploaded_bits = wp_upload_bits(
				$_FILES['file']['name'],
				null, //deprecated
				file_get_contents( $_FILES['file']['tmp_name'] )
			);

			if ( false !== $uploaded_bits['error'] ) {
				$error = $uploaded_bits['error'];

			} else {
				$uploaded_file     = $uploaded_bits['file'];
				$uploaded_url      = $uploaded_bits['url'];
				$uploaded_filetype = wp_check_filetype( basename( $uploaded_bits['file'] ), null );

				// generate metadata
				if ( $uploader_purpose == 'job' ) {
					$attachment = array(
						'post_mime_type' => $uploaded_bits['type'],
						'post_title'     => 'Uploaded image ' . addslashes($uploaded_file),
						'post_content'   => '',
						'post_status'    => 'inherit',
						'post_parent'    =>  $pid,
						'post_author'    => $current_user->ID,
					);
				} elseif ( $uploader_purpose == 'portfolio' ) {
					$attachment = array(
						'post_mime_type' => $uploaded_bits['type'],
						'post_title'     => 'Uploaded image ' . addslashes($uploaded_file),
						'post_content'   => '',
						'post_status'    => 'publish',
						'post_author'    => $uid,
					);
				}
				$attach_id = wp_insert_attachment( $attachment, $uploaded_file, $pid );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				if ( $uploader_purpose == 'portfolio' ) {
					update_post_meta( $attach_id, 'is_portfolio', 1 );
				}
			}
		}

		$return = array(
			'id' => $attach_id,
			'error' => $error,
		);

		echo wp_json_encode( $return );
	}
	die();
}
add_action( 'wp_ajax_nopriv_submit_dropzone_image', 'wpjobster_dropzone_image_uploader_action' );
add_action( 'wp_ajax_submit_dropzone_image', 'wpjobster_dropzone_image_uploader_action' );


function wpjobster_dropzone_cover_uploader( $pid ) {

	// limits
	$wpjobster_max_img_upload_size = wpjobster_max_img_upload_size();
	$wpjobster_max_img_upload_size_mb = $wpjobster_max_img_upload_size / 1048576;

	$cover_id = get_post_meta( $pid, 'cover-image', 1 );



	$cover = '';

	if ( is_numeric( $cover_id ) ) { // has cover
		$cover_thumb = wpj_get_attachment_image_url( $cover_id, array( 980, 180 ) );
		if ( $cover_thumb != '' ) {

			$cover .= <<<COVER
<div class="dz-preview dz-image-preview dz-complete" data-id="$cover_id" id="image_ss{$cover_id}">
	<div class="dz-image"><img data-dz-thumbnail="" alt="" src="$cover_thumb"></div>
	<a href="javascript: void(0);" onclick="delete_this_cover('{$cover_id}', '{$pid}');" class="delete-this"></a>
</div>

COVER;
		}
	}



	$nonce_files = wp_nonce_field( 'protect_content', 'jobcover_nonce_field' );
	echo <<<DZFORM
<div id="dropzone-jobcover" class="dropzone needsclick dz-clickable cf" data-pid="$pid" data-maxfilesize="{$wpjobster_max_img_upload_size}">
		$cover
</div>
<div id="dropzone-jobcover-fields">
	$nonce_files
</div>
DZFORM;


	// translating default error messages
	$dictFallbackMessage = addslashes( __( "Your browser does not support drag'n'drop file uploads.", "wpjobster" ) );
	$dictFallbackText = addslashes( __( "Please use the fallback form below to upload your files like in the olden days.", "wpjobster" ) );
	$dictFileTooBig = addslashes( __( "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", "wpjobster" ) );
	$dictInvalidFileType = addslashes( __( "You can't upload files of this type.", "wpjobster" ) );
	$dictResponseError = addslashes( __( "Server responded with {{statusCode}} code.", "wpjobster" ) );
	$dictCancelUpload = addslashes( __( "Cancel upload", "wpjobster" ) );
	$dictCancelUploadConfirmation = addslashes( __( "Are you sure you want to cancel this upload?", "wpjobster" ) );
	$dictRemoveFile = addslashes( __( "Remove file", "wpjobster" ) );
	$dictMaxFilesExceeded = addslashes( __( "You cannot upload any more files.", "wpjobster" ) );

	echo <<<DZJS
<script>
jQuery(document).ready(function ($) {
	var pid = $("#dropzone-jobcover").data("pid");

	$("div#dropzone-jobcover").dropzone({
		url: ajaxurl,
		// maxFiles: 1,
		maxFilesize: {$wpjobster_max_img_upload_size_mb},
		acceptedFiles: 'image/*',
		addRemoveLinks: false,
		thumbnailWidth: 980,
		thumbnailHeight: 180,

		dictDefaultMessage: "",
		dictFallbackMessage: "{$dictFallbackMessage}",
		dictFallbackText: "{$dictFallbackText}",
		dictFileTooBig: "{$dictFileTooBig}",
		dictInvalidFileType: "{$dictInvalidFileType}",
		dictResponseError: "{$dictResponseError}",
		dictCancelUpload: "{$dictCancelUpload}",
		dictCancelUploadConfirmation: "{$dictCancelUploadConfirmation}",
		dictRemoveFile: "{$dictRemoveFile}",
		dictRemoveFileConfirmation: null,
		dictMaxFilesExceeded: "{$dictMaxFilesExceeded}",

		accept: function(file, done) {
			done();
		},

		init: function() {
			dzClosure = this;
			this.on("addedfile", function() {
				$( "#dropzone-jobcover" ).addClass( "maxnrofpictures" );
				if ( this.files[1] != null ) {
					this.removeFile( this.files[0] );
					// this.files[0].previewElement = null;
					// ^ this works in certain conditions, but creates other problems atm
				}
				$( "#dropzone-jobcover .dz-preview.dz-image-preview.dz-complete" ).remove();
			});
			this.on("sending", function(data, xhr, formData) {
				formData.append("action", "submit_dropzone_cover");
				formData.append("jobcover_nonce_field", $("#jobcover_nonce_field").val());
				formData.append("pid", $("#dropzone-jobcover").data("pid"));
				formData.append("MAX_FILE_SIZE", $("#dropzone-jobcover").data("maxfilesize"));

				$( "#dropzone-jobcover" ).addClass( "maxnrofpictures" );
			});
			this.on("success", function(file, response) {
				var response = $.parseJSON( response );
				if ( response.error !== false ) {
					$( file.previewElement ).removeClass( "dz-success" );
					$( file.previewElement ).addClass( "dz-error" );
					$( file.previewElement ).find( ".dz-error-message > span" ).append( response.error );
				} else {
					$( file.previewElement ).attr( "data-id", response.id );
					$( file.previewElement ).attr( "id", "image_ss" + response.id );
				}
				if ( response.id !== 0 ) {
					$( file.previewElement ).append( '<a href="javascript: void(0);" onclick="delete_this_cover(' + response.id + ', ' + pid + ');" class="delete-this"></a>' );
				} else {
					$( file.previewElement ).append( '<a href="javascript: void(0);" onclick="delete_this_cover_thumb( $(this) );" class="delete-this"></a>' );
				}
			});
			this.on("error", function(file, errorMessage, xhr) {
				$(file.previewElement).append( '<a href="javascript: void(0);" onclick="delete_this_cover_thumb( $(this) );" class="delete-this"></a>' );
			});
			this.on("complete", function(data, xhr, formData) {
				// if ( $( "#dropzone-jobcover .dz-preview.dz-image-preview.dz-complete" ).length >= $("#dropzone-jobcover").data("maxnrofpictures") ) {
				// 	$( "#dropzone-jobcover" ).addClass( "maxnrofpictures" );
				// }
			});
		}
	});
});
</script>
DZJS;
}

function wpjobster_dropzone_cover_uploader_action() {
	if ( ! empty($_FILES) && wp_verify_nonce($_REQUEST['jobcover_nonce_field'], 'protect_content') ) {

		$pid = $_POST['pid'];
		$post = get_post( $pid );

		global $current_user;
		$current_user = wp_get_current_user();

		$error = false;
		$attach_id = 0;

		// limits
		$wpjobster_max_img_upload_size = wpjobster_max_img_upload_size();
		$wpjobster_max_img_upload_size_mb = $wpjobster_max_img_upload_size / 1048576;

		$allowed_size_width = get_option( 'wpjobster_min_cover_img_upload_width' );
		$allowed_size_height = get_option( 'wpjobster_min_cover_img_upload_height' );
		if ( ! is_numeric( $allowed_size_width ) || ! $allowed_size_width ) { $allowed_size_width = 720; }
		if ( ! is_numeric( $allowed_size_height ) || ! $allowed_size_height ) { $allowed_size_height = 405; }
		if ( isset( $_FILES['file']['tmp_name'] ) ) {
			$tmp_name = $_FILES['file']['tmp_name'];
			list( $file_width, $file_height ) = getimagesize( $tmp_name );
		} else {
			$file_width = 0;
			$file_height = 0;
		}

		try {
			if ( $post->post_author != $current_user->ID && ! user_can( $current_user, 'manage_options' ) ) {
				throw new RuntimeException( __('You can upload pictures only to your own job.', 'wpjobster') );
			}

			if ( ! isset($_FILES['file']['error']) || is_array($_FILES['file']['error']) ) {
				throw new RuntimeException( __('Invalid parameters.', 'wpjobster') );
			}

			// Check $_FILES['file']['error'] value.
			switch ( $_FILES['file']['error'] ) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException( __('No file sent.', 'wpjobster') );
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException( sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $wpjobster_max_img_upload_size_mb) );
				default:
					throw new RuntimeException( __('Unknown errors.', 'wpjobster') );
			}

			// Check filesize again
			if ( $_FILES['file']['size'] > $wpjobster_max_img_upload_size ) {
				throw new RuntimeException( sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $wpjobster_max_img_upload_size_mb) );
			}

			// DO NOT TRUST $_FILES['file']['mime'] VALUE !!
			// Check MIME Type by yourself.
			if ( class_exists( 'finfo' ) ) {
				$finfo = new finfo(FILEINFO_MIME_TYPE);
				if (false === $ext = array_search(
					$finfo->file($_FILES['file']['tmp_name']),
					array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
						'gif' => 'image/gif',
					),
					true
				)) {
					throw new RuntimeException( __('Invalid file format.', 'wpjobster') );
				}
			}

			// Check file dimensions
			if ( $file_width < $allowed_size_width || $file_height < $allowed_size_height ) {
				throw new RuntimeException( sprintf(__('Minimum file size: %1$s x %2$s px.', 'wpjobster'), $allowed_size_width, $allowed_size_height) );
			}

		} catch ( RuntimeException $e ) {
			$error = $e->getMessage();
		}

		if ( $error === false ) {

			$uploaded_bits = wp_upload_bits(
				$_FILES['file']['name'],
				null, //deprecated
				file_get_contents( $_FILES['file']['tmp_name'] )
			);

			if ( false !== $uploaded_bits['error'] ) {
				$error = $uploaded_bits['error'];

			} else {
				$uploaded_file     = $uploaded_bits['file'];
				$uploaded_url      = $uploaded_bits['url'];
				$uploaded_filetype = wp_check_filetype( basename( $uploaded_bits['file'] ), null );

				// generate metadata
				$attachment = array(
					'post_mime_type' => $uploaded_bits['type'],
					'post_title'     => 'Uploaded image ' . addslashes($uploaded_file),
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_parent'    =>  $pid,
					'post_author'    => $current_user->ID,
				);
				$attach_id = wp_insert_attachment( $attachment, $uploaded_file, $pid );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_file );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				update_post_meta( $attach_id, 'is_cover', 1 );

				$cover_image_id_arr = get_post_meta( $pid, 'cover-image' );
				foreach ( $cover_image_id_arr as $cover_image_id ) {
					wp_delete_post( $cover_image_id );
				}
				delete_post_meta( $pid, 'cover-image' );

				update_post_meta( $pid, 'cover-image', $attach_id );
			}
		}

		$return = array(
			'id' => $attach_id,
			'error' => $error,
		);

		echo wp_json_encode( $return );
	}
	die();
}
add_action( 'wp_ajax_nopriv_submit_dropzone_cover', 'wpjobster_dropzone_cover_uploader_action' );
add_action( 'wp_ajax_submit_dropzone_cover', 'wpjobster_dropzone_cover_uploader_action' );
