<?php
/*************************************************************************
this file contains all of the functions used to add and delete bookmarks
************************************************************************/

function upb_check_post_is_read($post_id, $user_id) {
	// this line was just for testing purposes
	//delete_user_meta(upb_get_user_id(), 'upb_read_posts');
	$user_meta = upb_get_user_meta($user_id);
	if($user_meta) :
		foreach ($user_meta as $read_post) {
            if ($read_post == $post_id) {
				return true;
			}
		}
	endif;
	return false;
}

function upb_get_user_id() {
    global $current_user;
    get_currentuserinfo();
    return $current_user->ID;
}

function upb_add_to_usermeta($post_id) {
	$user_id = upb_get_user_id();
    $upb_read = upb_get_user_meta($user_id);
	// add out post id to the end of the array
	$upb_read[] = $post_id;
 	upb_update_user_meta($upb_read, $user_id);
}

function upb_remove_from_usermeta($post_id) {
	$user_id = upb_get_user_id();
    $upb_read = upb_get_user_meta($user_id);
	foreach ($upb_read as $read_post => $id) {
		if ($id == $post_id) {
			unset($upb_read[$read_post]);
		}
	}	
 	upb_update_user_meta($upb_read, $user_id);
}

function upb_update_user_meta($arr, $user_id) {
    return update_user_option($user_id,'upb_read_posts', $arr);
}
function upb_get_user_meta($user_id) {
	return get_user_option('upb_read_posts', $user_id);
}

function upb_post_mark_as_read() {
  	if ( isset( $_POST["post_read"] ) ) {
		$post_id = intval($_POST["post_read"]);
    	$marked_as_read = upb_add_to_usermeta($post_id);
    	$update_count = upb_increase_count($post_id);
		die();
	}
}
add_action('wp_ajax_bookmark_post', 'upb_post_mark_as_read');

function upb_insert_bookmark() {
	global $upb_options;
  	if ( isset( $_POST["post_id"] ) ) {
		$post_id = intval($_POST["post_id"]);
		$bookmark = '';
    	$bookmark .= '<li class="bookmark-link bookmark-' . $post_id . '">';
			$bookmark .= '<a href="' . get_permalink($post_id) . '" class="cgc_bookmark" title="' . get_the_title($post_id) . '">' . get_the_title($post_id) . '</a>';
			
		$bookmark .= '</li>';
		echo $bookmark;
		die();
	}
}
add_action('wp_ajax_insert_bookmark', 'upb_insert_bookmark');

function upb_post_delete_bookmark() {
  	if ( isset( $_POST["del_post_id"] ) ) {
		$post_id = intval($_POST["del_post_id"]);
    	$del_bookmark = upb_remove_from_usermeta($post_id);
    	$update_count = upb_decrease_count($post_id);
		die();
	}
}
add_action('wp_ajax_del_bookmark', 'upb_post_delete_bookmark');

// increases the bookmark count for $post_id
function upb_increase_count($post_id) {
	global $wpdb;
	global $upb_db_table;
	
	// add the post ID to the count database if it doesn't already exist
	if(!$wpdb->query("SELECT `count` FROM `" . $upb_db_table . "` WHERE id=" . $post_id . ";")) {
		$add_post_id = $wpdb->insert( $upb_db_table, 
			array(
				'id' => $post_id, 
				'count' => 1 
			)
		);
	} else {	
		$count = $wpdb->query( $wpdb->prepare("UPDATE " . $upb_db_table . " SET count = count + 1 WHERE id=%d;", $post_id ) );
	}
}
// decreases the bookmark count for $post_id
function upb_decrease_count($post_id) {
	global $wpdb;
	global $upb_db_table;
	
	$count = $wpdb->query( $wpdb->prepare("UPDATE " . $upb_db_table . " SET count = count - 1 WHERE id=%d;", $post_id ) );
}

function upb_get_bookmark_count($post_id) {
	global $wpdb, $upb_db_table;
	
	$count = $wpdb->get_var( $wpdb->prepare( "SELECT `count` FROM `" . $upb_db_table . "` WHERE id=%d;", $post_id ) );
	if($count)
		return $count;
	return 0;
}