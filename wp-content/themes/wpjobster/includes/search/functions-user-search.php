<?php
function wpjobster_search_users_ajax() {
	global $wpdb;
	global $current_user;
	$current_user = wp_get_current_user();
	$cid = $current_user->ID;

	$searchInp = $_POST["searchInp"];
	$offsetInp = $_POST["limitInp"];

	$locationInp = WPJ_Form::post( 'locationInp', '' );
	$radiusInp   = WPJ_Form::post( 'radiusInp', '' );
	$latInp      = WPJ_Form::post( 'latInp', '' );
	$longInp     = WPJ_Form::post( 'longInp', '' );

	$searchInpExplode = explode(' ', $searchInp);

	$units = get_option( 'wpjobster_locations_unit' ) == 'kilometers' ? 6371 : 3959;

	if( empty( $_POST['radiusInp'] ) ){
		$radiusInp = 1;
	}

	if( empty( $_POST['locationInp'] ) ){
		$latInp = '';
		$longInp = '';
		$radiusInp = '';
	}

	if( !empty( $latInp ) && !empty( $longInp ) ) {
		$field = ", {$units} * acos( cos( radians({$latInp}) ) * cos( radians( um1.meta_value ) ) * cos( radians ( um2.meta_value ) - radians({$longInp}) ) + sin( radians({$latInp}) ) * sin( radians ( um1.meta_value ) ) ) as 'distance'";
		$field_join = "
			LEFT JOIN {$wpdb->prefix}usermeta AS um1 ON ( u.ID = um1.user_ID AND um1.meta_key='wpj_user_latitude' )
			LEFT JOIN {$wpdb->prefix}usermeta AS um2 ON ( u.ID = um2.user_ID AND um2.meta_key='wpj_user_longitude' )
		";
	}else{
		$field = '';
		$field_join = '';
	}

	$searchQuery = "
		SELECT * {$field}
		FROM {$wpdb->prefix}users u
		{$field_join}
		LEFT JOIN {$wpdb->prefix}usermeta AS um3 ON ( u.ID = um3.user_ID AND um3.meta_key='first_name' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um4 ON ( u.ID = um4.user_ID AND um4.meta_key='last_name' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um5 ON ( u.ID = um5.user_ID AND um5.meta_key='description' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um6 ON ( u.ID = um6.user_ID AND um6.meta_key='personal_info' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um8 ON ( u.ID = um8.user_ID AND um8.meta_key='city' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um9 ON ( u.ID = um9.user_ID AND um9.meta_key='country' )
		LEFT JOIN {$wpdb->prefix}usermeta AS um10 ON ( u.ID = um10.user_ID AND um10.meta_key='user_company' )
		";

	ob_start();
	do_action( 'wpjobster_user_search_left_join', $searchInp );
	$searchQuery .= ob_get_contents();
	ob_clean();

	$searchQuery .= "
		WHERE
		(
		u.user_login LIKE '%{$searchInp}%'
		OR um3.meta_value LIKE '%{$searchInp}%'
		OR um4.meta_value LIKE '%{$searchInp}%'
		OR um5.meta_value LIKE '%{$searchInp}%'
		OR um6.meta_value LIKE '%{$searchInp}%'
	";

		if( get_option( 'wpjobster_enable_user_company' ) == 'yes' ){
			$searchQuery .= " OR um10.meta_value LIKE '%{$searchInp}%' ";
		}

		if(isset($searchInpExplode[1]) && $searchInpExplode[1]){
			$searchQuery .= "
				OR (
					( um3.meta_value LIKE '%{$searchInpExplode[0]}%')
					AND (u.ID IN (SELECT um4.user_ID FROM {$wpdb->prefix}usermeta um7 WHERE (um4.meta_value LIKE '%{$searchInpExplode[1]}%') ))
				)
				OR (
					( um3.meta_value LIKE '%{$searchInpExplode[1]}%')
					AND (u.ID IN (SELECT um4.user_ID FROM {$wpdb->prefix}usermeta wum WHERE (um4.meta_value LIKE '%{$searchInpExplode[0]}%') ))
				)
			";
		}

	$searchQuery .= " ) ";
	if ( ! preg_match( '/,/',$locationInp ) && $locationInp ){
		$searchQuery .= " AND ( um8.meta_value LIKE '%{$locationInp}%' OR um9.meta_value LIKE '%{$locationInp}%' ) ";
	}

	ob_start();
	do_action( 'wpjobster_user_search_where', $searchInp );
	$searchQuery .= ob_get_contents();
	ob_clean();

	if( !empty( $field ) && !empty( $field_join ) && preg_match( '/,/',$locationInp ) ) {
		$searchQuery .= "
			HAVING distance < {$radiusInp}
			ORDER BY distance DESC
		";
	}

	if($offsetInp){
		$searchQuery .= " LIMIT ".$offsetInp;
	}

	$queryResults = $wpdb->get_results($searchQuery);

	if( count($queryResults) > 0 ){
		$querycount = count($queryResults);
	}else{
		$querycount = 0;
	}

	$users = array();
	foreach ($queryResults as $query) {
		$uid = $query->ID;
		$profilePicture = wpjobster_get_avatar($uid,180,180);

		$rtg = wpjobster_get_seller_rating($uid);
		$ratinggrade = $rtg / 20;
		if ($ratinggrade != 0) {
			$rating = wpjobster_show_big_stars_our_of_number($ratinggrade);
		} else {
			$rating = __('Not rated yet', 'wpjobster');
		}

		$uData = get_userdata( $uid );
		$reg = $uData->user_registered;
		$joined = strtotime( $uData->user_registered ) > 0 ? wpjobster_seconds_to_words_joined(time() - strtotime($reg)) : __( 'There is no record of the date', 'wpjobster' ) . '!';
		$profileaddress = wpj_get_user_profile_link( $uData->user_login );

		$personal_info = stripslashes( get_user_meta( $uid, 'personal_info', true ) );
		list( $personal_info, $validation_errors ) = filterMessagePlusErrors( $personal_info, true );

		if( get_option( 'wpjobster_wysiwyg_for_profile' ) != 'yes' ) {
			$personal_info = stripslashes( $personal_info );
		} else {
			$personal_info = wpj_description_parser( get_user_meta( $uid, 'personal_info', true ) );
		}

		$description = get_user_meta($uid, 'description', true);
		if(isset($personal_info) && $personal_info != ""){
			$user_desc = wpjobster_better_trim( $personal_info, 300 );
		}else if(isset($description) && $description != ""){
			$user_desc = wpjobster_better_trim( $description, 300 );
		}else{
			$user_desc = "";
		}

		$com_jb = wpjobster_get_number_of_completed_jobs($uid);

		if ( $uid != $cid ) {
			$contact_url = get_permalink( get_option( 'wpjobster_my_account_priv_mess_page_id' ) ) . '?username=' . $uData->user_login;
		} else {
			$contact_url = '';
		}

		$username = $uData->user_login;

		$level_icon = wpjobster_get_user_level_badge( $uid );
		$subscription_icon = wpjobster_get_user_subscription_icon( $uid );
		$country_flag = wpjobster_get_user_flag( $uid );
		$badges_icons = wpjobster_get_user_badges( $uid );

		ob_start();
		$u_id = $uid;
		include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );
		$user_status = ob_get_contents();
		ob_end_clean();

		if( get_option( 'wpjobster_enable_user_company' ) == 'yes' ){
			$user_company = get_user_meta( $uid, 'user_company', true );
		}else{
			$user_company = '';
		}

		$users[] = array(
			'id'                => $uid,
			'current_user'      => $cid,
			'username'          => $username,
			'avatar'            => $profilePicture,
			'rating'            => $rating,
			'joined'            => $joined,
			'description'       => $user_desc,
			'addr'              => $profileaddress,
			'com_jb'            => $com_jb,
			'contact_url'       => $contact_url,
			'level_icon'        => $level_icon,
			'subscription_icon' => $subscription_icon,
			'country_flag'      => $country_flag,
			'badges_icons'      => $badges_icons,
			'user_status'       => $user_status,
			'company'           => $user_company,
			'limit'             => $offsetInp,
			'queryresults'      => $querycount,
		);
	}

	$result = array(
		'usersInfo' => $users
	);

	echo json_encode($result);
	wp_die();
}
add_action( 'wp_ajax_search_users_ajax', 'wpjobster_search_users_ajax' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_search_users_ajax', 'wpjobster_search_users_ajax' ); // ajax for not logged in users
