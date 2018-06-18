<?php



// this file contains all display functions



// will show the add/remove links

function upb_bookmark_controls($add_text = 'Bookmark this Post', $delete_text = 'Remove Bookmark', $wrapper = true) {

	if(is_user_logged_in()) {

		$post_id = get_the_ID();



		// if this post has been bookmarked, show the remove link, otherwise show the add link

		$link = '';

		if($wrapper == true) {

			$link .= '<div class="upb_add_remove_links">';

		}

		if($add_text == '') { $add_text = 'Bookmark this Post'; }

		if($delete_text == '') { $delete_text = 'Remove Bookmark'; }



		if(upb_check_post_is_read($post_id, upb_get_user_id())) {

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_del_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '">' . $delete_text . '</a>';

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_add_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '" style="display:none;">' . $add_text . ' (' . upb_get_bookmark_count($post_id) . ')</a>';

		} else {

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_del_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '" style="display:none;">' . $delete_text . '</a>';

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_add_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '">' . $add_text . ' (' . upb_get_bookmark_count($post_id) . ')</a>';

		}

		if($wrapper == true) {

			$link .= '</div>';

		}

		return $link;

	}

}



// show add / remove bookmark links automatically if enabled. Do not use this as a template tag

function upb_show_bookmark_links($content) {

	global $post;

	global $upb_options;



	if( is_singular() && isset( $upb_options['show_links'] ) ) {



		if($upb_options['post_position'] == 'Top') {

			$content = upb_bookmark_controls($upb_options['add_link_text'], $upb_options['del_link_text']) . $content;

		} else {

			$content = $content . upb_bookmark_controls($upb_options['add_link_text'], $upb_options['del_link_text']);

		}

	}

	return $content;

}

add_filter('the_content', 'upb_show_bookmark_links');



// will show a list of a user's bookmarks

function upb_list_bookmarks( $delete_link = true, $delete_text = 'Delete' ) {



	if(is_user_logged_in()) {

		$display = '<ul class="upb-bookmarks-list">';



			$bookmarks = upb_get_user_meta(upb_get_user_id());

			if($bookmarks) {

				foreach( $bookmarks as $bookmark) {



					$display .= '<li class="upb_bookmark bookmark-' . $bookmark . '">';

						$display .= '<a href="' . get_permalink($bookmark) . '" class="upb_bookmark_link" title="' . get_the_title($bookmark) . '">' . get_the_title($bookmark) . '</a>';

						if($delete_link) {

							$display .= ' - <a href="#" class="upb_del_bookmark upb_del_bookmark_' . $bookmark . '" rel="' . $bookmark . '" title="' . __('Remove this Bookmark') . '">' . $delete_text . '</a>';

						}

					$display .= '</li>';



				}

			} else {

				$display .= '<li class="bookmark-link no-bookmarks">You do not have any bookmarked posts.</li>';

			}

		$display .= '</ul>';

	}

	else {

		$display .= 'You must be logged in to view your bookmarks.';

	}



	return $display;

}



function upb_most_bookmarked($number = 5, $count = true) {

	global $wpdb;

	global $upb_db_table;



	$bookmarks = $wpdb->get_results("SELECT * FROM " . $upb_db_table . " WHERE count != 0 ORDER BY count DESC LIMIT $number;");

	$popular_list = '<ul class="upb_most_popular_bookmarks">';



	foreach($bookmarks as $popular) {

		$popular_list .= '<li class="popular_bookmark">';

			$popular_list .= '<a href="' . get_permalink($popular->id) . '" class="popular_bookmark_link" title="' . get_the_title($popular->id) . ' - ' . __('Bookmarked ') . $popular->count . __(' times') . '">';

			$popular_list .= get_the_title($popular->id) . '</a>';

			if($count == true) {

				$popular_list .= ' (' . $popular->count . ')';

			}

		$popular_list .= '</li>';

	}



	$popular_list .= '</ul>';



	return $popular_list;

}

