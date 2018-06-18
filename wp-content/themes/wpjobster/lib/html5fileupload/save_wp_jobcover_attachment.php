<?php
    $absolutedir            = dirname(__FILE__);
    $absolutedir_arr        = explode("/wp-content",$absolutedir,2);
    $absolutedir_str        = $absolutedir_arr['0'];

    if (file_exists($absolutedir_str."/wp-load.php")) {
        require($absolutedir_str.'/wp-load.php');
    } else {
        $response ['debug1'] ="not found ".$absolutedir_str."/wp-load.php";
    }

    $dir                    = '/wp-content/uploads/html5fileupload';
    $dir_path                    = 'wp-content/uploads/html5fileupload';
    if (!is_dir($absolutedir_str.$dir)) {
        @mkdir($absolutedir_str.$dir,0777);// or die("can not create directory");
    }

    $error                  = false;
    $dir                    = $dir."/";
    $serverdir              = $absolutedir_str.$dir;

    $tmp                    = explode(',',$_POST['file']);
    $file                   = base64_decode($tmp[1]);

    $extension              = @strtolower(@end(@explode('.',$_POST['filename'])));
    $filename               = str_replace(array(" ",",","\"","'"),"-",$_POST['name']).'.'.$extension;
    //$filename             = $file.'.'.substr(sha1(time()),0,6).'.'.$extension;

    $handle                 = fopen($serverdir.$filename,'w');
    fwrite($handle, $file);
    fclose($handle);

    $response = array(
        "result"        => true,
        "filepath"      =>ABSPATH.$dir_path."/".$filename,
        "absolutedir_str_path"=>$absolutedir_str.$dir.$filename,
        "url"           => site_url().$dir.$filename,//.'?'.time(), //added the time to force update when editting multiple times
        "filename"      => $filename
    );


$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );

$tm = time();
$rnd = rand(0,199);

if(!is_user_logged_in()) { exit; }

    $pid = $_POST['data']['ID'];

    $cid = $_POST['data']['author'];


    $allowed_size_mb = get_option('wpjobster_max_img_upload_size'); // total images allowed by the system to be uploaded
    if (!$allowed_size_mb) { $allowed_size_mb = 10; }
  $allowed_size=$allowed_size_mb*1024*1000;

{
    $response['error']='';

    // gives us access to the download_url() and wp_handle_sideload() functions
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    // URL to the WordPress logo
    //$url = 'http://s.w.org/style/images/wp-header-logo.png';
    //$url = get_template_directory_uri()."/lib/html5fileupload".$response['url'];
         $url =$response['url'];
    $timeout_seconds = 5;
    // download file to temp dir
    $temp_file =  $response['filepath'];//download_url( $url, $timeout_seconds );
    $image_attrs = getimagesize($temp_file);
    $img_size_inbytes = filesize($temp_file);
    //$response['debug']= "sizes $img_size_inbytes > $allowed_size ";
        // checking image size in bytes if that is allowed
    if($img_size_inbytes > $allowed_size){
        $response['error'] = sprintf(__('Filesize is too big. Only %sMB is allowed.', 'wpjobster'), $allowed_size_mb);
    }
        // checking height and width of cover image if that is allowed
    $allowed_cover_size_width = get_option('wpjobster_min_cover_img_upload_width'); // wpjobster_min_img_upload_width to be uploaded
    $allowed_cover_size_height = get_option('wpjobster_min_cover_img_upload_height'); // wpjobster_min_img_upload_height to be uploaded
    if(!$allowed_cover_size_width){
            $allowed_cover_size_width=800;
        }
        if(!$allowed_cover_size_height){
            $allowed_cover_size_height=250;
        }
        //print_r($image_attrs);
    if($image_attrs['1'] < $allowed_cover_size_height || $image_attrs['0'] < $allowed_cover_size_width){
            $response['error'] = sprintf(__('Minimum file size: %1$s x %2$s px.', 'wpjobster'), $allowed_cover_size_width, $allowed_cover_size_height);
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
                'post_author' => $cid,
            );
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attach_id = wp_insert_attachment( $attachment, $file_name_and_location,0);
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
        wp_update_attachment_metadata($attach_id,  $attach_data);
        $go = wpj_get_attachment_image_url( $attach_id, array( 980, 180 ) );
                //deleting pevious cover images
                $cover_image_id_arr = get_post_meta($pid,"cover-image");
                foreach ($cover_image_id_arr as $cover_image_id){
                    wp_delete_post($cover_image_id);
                }
                delete_post_meta($pid,"cover-image");

                update_post_meta($pid,"cover-image",$attach_id);
        $response['go']=$go;
        $response['attach_id']=$attach_id; //$uploads['url']."/".$xx;
    }
}

// delete tmp file
unlink($absolutedir_str.$dir.$filename);

// response
echo json_encode($response);
?>
