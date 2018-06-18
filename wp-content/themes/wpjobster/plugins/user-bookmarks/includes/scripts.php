<?php

function upb_bookmarks_js() {
	global $upb_base_dir;
	global $upb_options;

	if($upb_options['bookmark_added_text'] == '' || !isset($upb_options['bookmark_added_text'])) {
		$bookmarked_text = __( 'Bookmark Added', 'wpjobster' );
	} else {
		$bookmarked_text = $upb_options['bookmark_added_text'];
	}

	if($upb_options['remove_bookmark_text'] == '' || !isset($upb_options['remove_bookmark_text'])) {
		$remove_bookmark_text = __( 'Are you sure you want to remove this bookmark?', 'wpjobster' );
	} else {
		$remove_bookmark_text = $upb_options['remove_bookmark_text'];
	}

	wp_enqueue_script( "bookmarks", UPB_PLUGIN_URL . 'includes/bookmarks.js', array( 'jquery' ) );
	wp_localize_script( 'bookmarks', 'upb_vars',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'added_message' => $bookmarked_text,
			'delete_message' => $remove_bookmark_text
		)
	);
}

add_action('wp_print_scripts', 'upb_bookmarks_js');

function upb_custom_css() {
	global $upb_options;

	if($upb_options['bookmarks_css']) {
		echo '<style type="text/css">' . PHP_EOL;
		echo $upb_options['bookmarks_css'] . PHP_EOL;
		echo '</style>' . PHP_EOL;
	}
}
add_action('wp_head', 'upb_custom_css');
