<?php

// adds a shortcode that will display a user's bookmarks
function upb_bookmarks_list_shortcode($atts, $content = null ) {

	extract( shortcode_atts( array(
      'delete_link' => 'true',
      'delete_text' => 'Delete'
      ), $atts ) 
	);

	if($delete_link == 'true') {
		$delete = true;
	} else {
		$delete = false;
	}
	
	ob_start();
	
	echo upb_list_bookmarks($delete, $delete_text);
	
	return ob_get_clean();
}
add_shortcode('bookmarks_list', 'upb_bookmarks_list_shortcode');

// adds a shortcode that will display add/remove bookmark link
function upb_bookmarks_links($atts, $content = null ) {

	extract( shortcode_atts( array(
      'add_text' => 'Bookmark this Post',
      'delete_text' => 'Remove Bookmark'
      ), $atts ) 
	);
		
	return upb_bookmark_controls($add_text, $delete_text);	
}
add_shortcode('bookmark_links', 'upb_bookmarks_links');

// adds a shortcode that will display add/remove bookmark link
function upb_most_bookmarked_shortcode($atts, $content = null ) {

	extract( shortcode_atts( array(
      'number' => 'Bookmark this Post'
      ), $atts ) 
	);
		
	return upb_most_bookmarked($number);	
}
add_shortcode('most_bookmarked', 'upb_most_bookmarked_shortcode');