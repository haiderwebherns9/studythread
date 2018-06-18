<?php
function get_user_language_by_userid($receiver,$default_lang){
	$lang = get_user_meta($receiver, 'preferred_language', true);
	if (!$lang) {
		$lang = $default_lang;
	}
	return $lang;
}
