<?php

function wpj_my_favorites($display, $bookmarks) {

	$delete_link = true;
	$delete_text = '<div class="bookmark-icon-smaller bookmark-remove tooltip"><span>' . __('Remove Favorite', 'wpjobster') . '</span></div>';

	foreach( $bookmarks as $bookmark) {

		$max_days   = get_post_meta($bookmark, "max_days", true);

		$display .= '<div class="bs-table-row post_results_item flex-middle cf upb_bookmark bookmark-'. $bookmark .' ">';

		$display .= '<div class="ui two column stackable grid my-favorites">';

		$display .= '<div class="two wide column">';
			$display .= '<a href="' . get_permalink($bookmark) . '" class="upb_bookmark_link" title="' . get_the_title($bookmark) . '">';
				$display .= '<img width="60" height="60" class="round-avatar" src="' . wpjobster_get_first_post_image($bookmark, 61, 61) . '" />';
			$display .= '</a>';
		$display .= '</div>';

		$display .= '<div class="six wide column">' . '<div class="ceva">';

			$display .= '<h4 class="small-heading-title">';
				$display .= '<a href="' . get_permalink($bookmark) . '" class="upb_bookmark_link" title="' . get_the_title($bookmark) . '">';
				$display .= get_the_title($bookmark);

				$display .= '</a>';
			$display .= '</h4>';


			$display .= '<div class="job-rating-top">';

					$ratinggrade = wpjobster_get_job_rating($bookmark);
					$ratinggrade = ($ratinggrade) / 20;

					if ( $ratinggrade != 0 ) {
						if( wpjobster_get_job_ratings_number( $bookmark ) >=3 ){
							$display .= wpjobster_show_stars_our_of_number($ratinggrade);
						}else{
							$display .= __("Not enough ratings", "wpjobster");
						}
					} else {
						$display .= __("Not rated yet", "wpjobster");
					}

			$display .= '</div>';
			$display .= '<div class="job-rating-top-text">';

				if (wpjobster_get_job_ratings_number($bookmark) > 0) {
					$display .=  wpjobster_get_job_ratings_number($bookmark) . " " .  _n("review", "reviews", wpjobster_get_job_ratings_number($bookmark), "wpjobster");
				}

			$display .= '</div>';

		$display .= '</div>' . '</div>';

		$display .= '<div class="three wide column favorite-deliver-days">';
			$display .= '<div class="responsive_titles">' . __('Delivery time', 'wpjobster') . '</div>' . '<div class="favorite-time-deliver">' . $max_days . ' ' . _n("day", "days", $max_days, "wpjobster") . '</div>';
		$display .= '</div>';

		$display .= '<div class="three wide column favorite-price">';
			$display .= '<div class="responsive_titles">' . __('Job Price', 'wpjobster') . '</div>' . '<div class="favorite-price">' . wpjobster_get_show_price(get_post_meta($bookmark, "price", true), 1) . '</div>';
		$display .= '</div>';

		$display .= '<div class="two wide column favorites-hearts">';
			if($delete_link) {
				$display .= '<div class="responsive_titles">' . __('Favorite', 'wpjobster') .  '</div>' . '<a href="#" class="upb_del_bookmark upb_del_bookmark_' . $bookmark . '" rel="' . $bookmark . '">' . $delete_text . '</a>';
			}
		$display .= '</div>';
		$display .= '</div>';
		$display .= '</div>';

	}

	return $display;

}
