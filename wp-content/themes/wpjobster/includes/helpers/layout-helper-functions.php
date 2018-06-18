<?php
/**
 * Layout php and wp helper functions
 */

function wpj_get_cards_layout_class() {
	if ( isset( $_COOKIE['cards-layout'] ) && $_COOKIE['cards-layout'] == 'list' ) {
		$list_class = 'list-grid';
	} else {
		$list_class = '';
	}

	return $list_class;
}
