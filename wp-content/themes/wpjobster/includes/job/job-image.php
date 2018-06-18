<?php
function wpjobster_get_job_image($pid = false) {
	if (!$pid) {
		global $post;
		$pid = $post->ID;
	}

	$pic_id = wpjobster_get_first_post_image_ID($pid);
	if ($pic_id != false) {
		$img = wpj_get_attachment_image_url( $pic_id, 'thumb_picture_size' );
	} else {
		$img = get_template_directory_uri() . '/images/nopic.jpg';
	}

	$wpjobster_video_thumbnails = get_option('wpjobster_video_thumbnails');
	if ($wpjobster_video_thumbnails == 'yes') {
		$youtube_link1 = get_post_meta($pid, 'youtube_link1', true);

		if ($youtube_link1) {
			$protocol = is_ssl() ? "https://" : "http://";
			$img = $protocol . 'img.youtube.com/vi/' . get_youtube_id($youtube_link1) . '/mqdefault.jpg';
		}
	}

	return $img;
}


function wpjobster_get_first_post_image($pid, $w = 100, $h = 100){
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

	$attachments = wpjobster_get_job_images( $pid, 1, $exclude );

	if ($attachments) {
		foreach ($attachments as $attachment) {
			return wpj_get_attachment_image_url( $attachment->ID, array( $w, $h ) );
		}

	} else {
		return get_template_directory_uri() . '/images/nopic.jpg';
	}
}


function wpjobster_get_first_post_image_ID($pid){
	//---------------------
	// build the exclude list
	$exclude = array();
	$args = array(
		'order' => 'ASC',
		'post_type' => 'attachment',
		'post_parent' => get_the_ID(),
		'meta_key' => 'another_reserved1',
		'meta_value' => '1',
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

	if ( function_exists( 'wpjobster_get_job_images' ) ) {
		$attachments = wpjobster_get_job_images( $pid, 1, $exclude );

		if ($attachments) {
			foreach ($attachments as $attachment) {
				return $attachment->ID;
			}

		}
	}

	return false;
}
