<?php
function get_featured_image($size){
	return get_the_post_thumbnail(get_the_ID(), $size);
}

function post_image($w,$h){
	if(get_post_thumbnail_id(get_the_ID())){
		$url=wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
	}else{
		$url=get_template_directory_uri()."/images/nopic.jpg";
	}
	?><img src="<?php echo wpjobster_generate_thumb($url, $w, $h); ?>"  /><?php
}

function validate_image_file( $url ) {
	$curl = curl_init();
	curl_setopt_array( $curl, array(
	CURLOPT_HEADER => true,
	CURLOPT_NOBODY => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_URL => $url ) );

	curl_exec( $curl );
	$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
	curl_close( $curl );

	$imgTypeArr = explode( '/', $contentType );
	$imgType = $imgTypeArr[0];

	if ( $imgType == 'image' ) {
		return true;
	} else {
		return false;
	}
}
