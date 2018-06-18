<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );

$tm = time();
$rnd = rand(0,199);

if(!is_user_logged_in()) { exit; }

$pid = $_POST['data']['ID'];
$cid = $_POST['data']['author'];

$attachments = wpjobster_get_job_images( $pid );
$total_exists = count($attachments); // total images uploaded by user so far

$total_allowed = get_option('wpjobster_default_nr_of_pics'); // total images allowed by the system to be uploaded
$allowed_size_mb = get_option('wpjobster_max_img_upload_size'); // total images allowed by the system to be uploaded
if (!$allowed_size_mb) { $allowed_size_mb = 10; }
$allowed_size=$allowed_size_mb*1024*1000;

$allowed_size_width = get_option('wpjobster_min_img_upload_width'); // wpjobster_min_img_upload_width to be uploaded
$allowed_size_height = get_option('wpjobster_min_img_upload_height'); // wpjobster_min_img_upload_height to be uploaded
if(!$allowed_size_width){
	$allowed_size_width=720;
}
if(!$allowed_size_height){
	$allowed_size_height=405;
}

if($total_exists>=$total_allowed){
	$response['error'] = sprintf(__('Only %s images are allowed.', 'wpjobster'), $total_allowed);
}else {
	$response['error'] = '';
	// gives us access to the download_url() and wp_handle_sideload() functions
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	// URL to the WordPress logo
	//$url = get_template_directory_uri()."/lib/html5fileupload".$response['url'];
	$url = $response['url'];

	$timeout_seconds = 5;
	// download file to temp dir
	$temp_file = $response['filepath'];//download_url( $url, $timeout_seconds );
		$image_attrs = getimagesize($temp_file);
		//print_r($image_attrs);

	$img_size_inbytes = filesize($temp_file);
	$response['debug']= json_encode($image_attrs);//"sizes $img_size_inbytes > $allowed_size ";
	if($img_size_inbytes > $allowed_size){
			$response['error'] = sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $allowed_size_mb);
	}
	if($image_attrs['1'] < $allowed_size_height || $image_attrs['0'] < $allowed_size_width){
			$response['error'] = sprintf(__('Minimum file size: %1$s x %2$s px.', 'wpjobster'), $allowed_size_width, $allowed_size_height);
	}


	if (!is_wp_error( $temp_file ) && $response['error']=='') {

		// array based on $_FILE as seen in PHP file uploads
		$file = array(
			'name' => basename($url), // ex: wp-header-logo.png
			'type' => $image_attrs['mime'],
			'tmp_name' => $temp_file,
			'error' => 0,
			'size' => filesize($temp_file),
		);

		$overrides = array(
			// tells WordPress to not look for the POST form
			// fields that would normally be present, default is true,
			// we downloaded the file from a remote server, so there
			// will be no form fields
			'test_form' => false,

			// setting this to false lets WordPress allow empty files, not recommended
			////'test_size' => true,

			// A properly uploaded file will pass this test.
			// There should be no reason to override this one.
			////'test_upload' => true,
		);
		define('ALLOW_UNFILTERED_UPLOADS', true);
		// move the temporary file into the uploads directory
		$results = wp_handle_sideload( $file, $overrides );

		if (!empty($results['error'])) {
			$response['error']==$results['error'];// insert any error handling here
			exit;
		} else {
			//$filename = $results['file']; // full path to the file
			//$local_url = $results['url']; // URL to the file in the uploads dir
			//$type = $results['type']; // MIME type of the file
			$file_name_and_location = $results['file'];
			$file_title_for_media_library = $temp_file;//$_FILES['Filedata']['name'];
			$uploaded_file_type = $results['type'];;//$arr_file_type['type'];
		}

	}else{
		// if file not properly downloaded from URL given
	}
	if($response['error']==''){
		$attachment = array(
				'post_mime_type' => $uploaded_file_type,
				'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
				'post_content' => '',
				'post_status' => 'inherit',
				'post_parent' =>  $pid,
				'post_author' => $cid,
			);
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
		wp_update_attachment_metadata($attach_id,  $attach_data);
		$go = wpj_get_attachment_image_url( $attach_id, array( 90, 90 ) );

		$response['go']=$go;
		$response['attach_id']=$attach_id; //$uploads['url']."/".$xx;

	}

}
?>
