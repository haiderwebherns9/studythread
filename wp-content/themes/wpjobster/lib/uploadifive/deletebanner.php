<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );
require_once( ABSPATH . "wp-admin" . '/includes/image.php' );

$user_id = $_POST['user'];
$attachmentid = $_POST['attach_id'];
wp_delete_attachment( $attachmentid);

if ( delete_user_meta( $user_id, 'banner' ) && delete_user_meta( $user_id, 'attachmentid' ) ) {
	echo "resultok";
} else {
	echo "faild";
}

exit();
