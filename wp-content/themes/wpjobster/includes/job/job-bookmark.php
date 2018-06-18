<?php
function uz_upb_bookmark_controls() {
	$post_id = get_the_ID();
	global $site_url_localized;

	if(is_user_logged_in()) {

		$number = upb_get_bookmark_count($post_id);
		$number_plus = $number + 1;
		$number_minus = $number - 1;

		$add_text = '<div class="bookmark-icon bookmark-add tooltip"><span>' . $number . '</span></div>';
		$delete_text = '<div class="bookmark-icon bookmark-remove tooltip"><span>' . $number . '</span></div>';

		$add_text_minus = '<div class="bookmark-icon bookmark-add tooltip"><span>' . $number_minus . '</span></div>';
		$delete_text_plus = '<div class="bookmark-icon bookmark-remove tooltip"><span>' . $number_plus . '</span></div>';


		// if this post has been bookmarked, show the remove link, otherwise show the add link

		$link = '';

		$link .= '<div class="upb_add_remove_links">';

		if(upb_check_post_is_read($post_id, upb_get_user_id())) {

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_del_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '"><div class="full-heart"></div>' . $delete_text . '</a>';

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_add_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '" style="display:none;"><div class="empty-heart"></div>' . $add_text_minus . '</a>';

		} else {

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_del_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '" style="display:none;"><div class="full-heart"></div>' . $delete_text_plus . '</a>';

			$link .= '<a href="#" rel="' . $post_id . '" class="upb_add_bookmark upb_bookmark_control upb_bookmark_control_' . $post_id . '"><div class="empty-heart"></div>' . $add_text . '</a>';

		}



		$link .= '</div>';

	} else {

		$link = '';

		$link .= '<div class="upb_add_remove_links">';

		$link .= '<a href="' . $site_url_localized . '/wp-login.php" rel="' . $post_id . '" class="login-link"><div class="bookmark-icon bookmark-add tooltip"><div class="empty-heart"></div><span>' . upb_get_bookmark_count($post_id) . '</span></div></a>';

		$link .= '</div>';

	}

	return $link;
}
