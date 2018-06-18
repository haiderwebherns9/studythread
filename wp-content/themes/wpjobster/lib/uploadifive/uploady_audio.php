<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );

$tm = time();
$rnd = rand(0,199);
$allowed_file_ext = array("mp3", "wav");

if(!is_user_logged_in()) { exit; }

function errorHandler($errno, $errstr, $errfile, $errline) {
	return true;
}

$old_error_handler = set_error_handler("errorHandler");

if (!empty($_FILES)) {
	$fileData = $_FILES['Filedata'];
	if ($fileData) {
		$tempFile   = $fileData['tmp_name'];
		$pid = $_POST['ID'];
		$cid = $_POST['author'];
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		$upload_overrides   = array( 'test_form' => false );
		$uploaded_file      = wp_handle_upload($_FILES['Filedata'], $upload_overrides);
		$file_name_and_location = $uploaded_file['file'];
		$file_title_for_media_library = $_FILES['Filedata']['name'];
		$arr_file_type      = wp_check_filetype(basename($_FILES['Filedata']['name']));
		if ( $arr_file_type ) {
			// check extension
			if (in_array(strtolower($arr_file_type['ext']), $allowed_file_ext)) {
				// allowed extension

					$status = "ok";
					$uploaded_file_type = $arr_file_type['type'];
					$attachment = array(
						'post_mime_type' => $uploaded_file_type,
						'post_title' => 'Uploaded audio ' . addslashes($file_title_for_media_library),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_parent' =>  $pid,
						'post_author' => $cid,
					);
					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
					$filename = basename( get_attached_file( $attach_id ) ); // Just the file name

					echo $status."|".$filename."|".$attach_id;
					if(is_demo_user()) {
						wp_delete_attachment($attach_id);
					}
			} else {
				// not allowed extension
				$status = "extension";
				echo $status;
			}
		} else {
			exit;
		}
	}
}

switch ( $_FILES['Filedata']['error'] ) {
	case 0:
		$msg = __("No Error","wpjobster");
		break;
	case 1:
		$msg = __("The file is bigger than this PHP installation allows","wpjobster");
		break;
	case 2:
		$msg = __("The file is bigger than this form allows","wpjobster");
		break;
	case 3:
		$msg = __("Only part of the file was uploaded","wpjobster");
		break;
	case 4:
		$msg = __("No file was uploaded","wpjobster");
		break;
	case 6:
		$msg = __("Missing a temporary folder","wpjobster");
		break;
	case 7:
		$msg = __("Failed to write file to disk","wpjobster");
		break;
	case 8:
		$msg = __("File upload stopped by extension","wpjobster");
		break;
	default:
		$msg = "unknown error ".$_FILES['Filedata']['error'];
		break;
}
?>
