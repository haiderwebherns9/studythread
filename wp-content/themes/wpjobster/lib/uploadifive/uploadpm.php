<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );

$tm = time();
$rnd = rand(0,199);
$allowed_file_ext = get_option( 'wpjobster_allowed_mime_types' );

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
		$unique_name = $_POST['unique_name'];

		require_once(ABSPATH . "wp-admin" . '/includes/file.php');


		$upload_overrides = array( 'test_form' => false );

		function wpse_183245_upload_dir( $dirs ) {

			$dirs['path'] = $dirs['basedir'] . '/secure'.$dirs['subdir'];
			$dirs['url'] = $dirs['baseurl'] . '/secure'.$dirs['subdir'];

			return $dirs;
		}

		if($_POST['secure']){
			if ( false === get_transient( 'wpjobster_check_secure_folder' ) ) {
				// Make sure the /secure folder is created
				$upload_dir = wp_upload_dir();
				$secure_upload_path = $upload_dir['basedir'] . '/secure';
				wp_mkdir_p( $secure_upload_path );

				// Top level .htaccess file
				$rules = "Options -Indexes\n";
				$rules .= "deny from all\n";

				if ( file_exists( $secure_upload_path . '/.htaccess' ) ) {
					$contents = @file_get_contents( $secure_upload_path . '/.htaccess' );
					if ( $contents !== $rules || ! $contents ) {
						// Update the .htaccess rules if they don't match
						@file_put_contents( $secure_upload_path . '/.htaccess', $rules );
					}
				} elseif( wp_is_writable( $secure_upload_path ) ) {
					// Create the file if it doesn't exist
					@file_put_contents( $secure_upload_path . '/.htaccess', $rules );
				}

				// Top level blank index.php
				if ( ! file_exists( $secure_upload_path . '/index.php' ) && wp_is_writable( $secure_upload_path ) ) {
					@file_put_contents( $secure_upload_path . '/index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
				}

				// Now place index.php files in all sub folders

				$folders = wpj_scan_folders( $secure_upload_path );
				foreach ( $folders as $folder ) {
					// Create index.php, if it doesn't exist
					if ( ! file_exists( $folder . 'index.php' ) && wp_is_writable( $folder ) ) {
						@file_put_contents( $folder . 'index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
					}
				}
				// Check for the files once per day
				set_transient( 'wpjobster_check_secure_folder', true, 3600 * 24 );
			}

			add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
			$uploaded_file      = wp_handle_upload($_FILES['Filedata'], $upload_overrides);
			remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
		} else{
			$uploaded_file      = wp_handle_upload($_FILES['Filedata'], $upload_overrides);
		}

		$file_name_and_location = $uploaded_file['file'];
		$file_title_for_media_library = $_FILES['Filedata']['name'];


		$arr_file_type = wp_check_filetype(basename($_FILES['Filedata']['name']));


		if ( $arr_file_type ) {
			// check extension
			if (in_array(strtolower($arr_file_type['ext']), $allowed_file_ext)) {
				// allowed extension


					$status = "ok";
					$uploaded_file_type = $arr_file_type['type'];

					$attachment = array(
						'post_mime_type' => $uploaded_file_type,
						'post_title' => addslashes($file_title_for_media_library),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_parent' =>  $pid,
						'post_author' => $cid,
					);

					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');

					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );

					echo $status."|".$attach_id."|".$unique_name;

					if(!is_demo_user()) {

						$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );

						wp_update_attachment_metadata($attach_id,  $attach_data);

					} else {

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
