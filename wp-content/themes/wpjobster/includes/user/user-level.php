<?php
if (!function_exists('wpjobster_get_user_level')) {
	function wpjobster_get_user_level($uid)    {
		$user_level = get_user_meta($uid, 'user_level', true);

		if (empty($user_level) && $user_level !== '0') {
			$wpjobster_default_level_nr = get_option('wpjobster_default_level_nr');

			if ($wpjobster_default_level_nr == "0")                $user_level = 0; else                $user_level = $wpjobster_default_level_nr;
		}

		return $user_level;
	}
}

if ( ! function_exists( 'wpjobster_display_user_level_badge' ) ) {
	function wpjobster_display_user_level_badge( $uid ) {
		echo wpjobster_get_user_level_badge( $uid );
	}
}

if ( ! function_exists( 'wpjobster_get_user_level_badge' ) ) {
	function wpjobster_get_user_level_badge( $uid ) {
		$html = '';
		$user_level = wpjobster_get_user_level( $uid );

		if ( $user_level == "1" || $user_level == "2" || $user_level == "3" ) {
			$icon_url = get_field( 'user_level_' . $user_level . '_icon', "options" );

			if ( $icon_url != '' ) {
				$icon_bg = 'background-image: url(' . $icon_url . ');';
			} else {
				$icon_bg = '';
			}

			$html .= '<div class="user-badge user-level-' . $user_level . '" style="' . $icon_bg . '"></div>';

			$html .= '<div class="nh-tooltip">';
				if ( $user_level == 1 ) {
					$html .= __( "Level 1 Seller", "wpjobster" );
				} elseif ( $user_level == 2 ) {
					$html .= __( "Level 2 Seller", "wpjobster" );
				} else {
					$html .= __( "Top Rated Seller", "wpjobster" );
				}
			$html .= '</div>';
		}

		return $html;
	}
}

add_action('wp_ajax_update_level_user', 'wpjobster_update_level_user');
function wpjobster_update_level_user(){
	if (current_user_can('manage_options'))
	if ($_POST['action'] == "update_level_user") {
		$uid = $_POST['uid'];
		$level1 = $_POST['level1'];
		$level2 = $_POST['level2'];
		$level3 = $_POST['level3'];
		$level0 = $_POST['level0'];
		$o_lvl=get_user_meta($uid, 'user_level',1);
		if ($level1 == "1") {
			update_user_meta($uid, 'user_level', "1");
			update_user_meta($uid, 'date_toclear', strtotime('+1 month', time()));
		}

		if ($level2 == "1") {
			update_user_meta($uid, 'user_level', "2");
			update_user_meta($uid, 'date_toclear', strtotime('+2 month', time()));
		}

		if ($level3 == "1") {
			update_user_meta($uid, 'user_level', "3");
			update_user_meta($uid, 'date_toclear', strtotime('+2 month', time()));
		}

		if ($level0 == "1") {
			update_user_meta($uid, 'user_level', "0");
			update_user_meta($uid, 'date_toclear', strtotime('+1 month', time()));
		}


		if($o_lvl<get_user_meta($uid, 'user_level',1)){
			wpjobster_send_email_allinone_translated('level_up', $uid);
			wpjobster_send_sms_allinone_translated('level_up', $uid);

		}

		if($o_lvl>get_user_meta($uid, 'user_level',1)){
			wpjobster_send_email_allinone_translated('level_down', $uid);
			wpjobster_send_sms_allinone_translated('level_down', $uid);

		}
		do_user_level_extras_check($uid);

	}
}

add_action('wpjobster_setup_schedule_daily_levelcheck_event', 'user_levels_update');
function user_levels_update(){
	global $wpdb;
	$all_users = get_users();
	if($all_users){
		foreach($all_users as $_user)  {

			$uid = $_user->ID;
			$tm = time();

			if ($tm > get_user_meta($uid, 'date_toclear', true)) {
				$datebefore = get_start_date_for_active_period($uid, 1);
				$datemax = strtotime('+1 day', time());

				global $wpdb;
				$prefix = $wpdb->prefix;
				$s = "select distinct * from " . $prefix . "job_orders orders, " . $prefix . "posts posts
				 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND
				 orders.done_buyer='1' AND orders.closed='0' AND orders.date_completed>'$datebefore' AND orders.date_completed<'$datemax' order by orders.id desc";
				$add = 0;
				$r = $wpdb->get_results($s);

				if (count($r) >= 0) {
					foreach ($r as $row) {
						$add += $row->mc_gross;
					}
				}
				$total1 = $add;
				global $wpdb;
				$prefix = $wpdb->prefix;
				$s = "select distinct * from " . $prefix . "job_ratings ratings, " . $prefix . "posts posts, " . $prefix . "job_orders orders
				 where posts.post_author='$uid' AND posts.ID=ratings.pid AND ratings.awarded='1' AND
				 ratings.datemade>'$datebefore' AND orders.date_made<'$datemax' order by ratings.id desc";
				$r = $wpdb->get_results($s);

				$ratings_sum = 0;
				$ratings_count = 0;
				$ratings_average1 = 0;
				if (count($r) > 0) {
					foreach ($r as $row) {
						$ratings_sum += $row->grade;
						$ratings_count++;
					}
					$ratings_average1 = ($ratings_sum / $ratings_count) * 20;
				}

				$datebefore = get_start_date_for_active_period($uid, 2);
				$datemax = strtotime('+ 1 day', time());

				global $wpdb;
				$prefix = $wpdb->prefix;
				$s = "select distinct * from " . $prefix . "job_orders orders, " . $prefix . "posts posts
				 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND
				 orders.done_buyer='1' AND orders.closed='0' AND orders.date_completed>'$datebefore' AND orders.date_completed<'$datemax' order by orders.id desc";
				$level1 = 0;
				$level2 = 0;
				$add = 0;
				$r = $wpdb->get_results($s);

				if (count($r) >= 0) {
					foreach ($r as $row) {
						$add += $row->mc_gross;
					}
				}
				$total2 = $add;
				global $wpdb;
				$prefix = $wpdb->prefix;
				$s = "select distinct * from " . $prefix . "job_ratings ratings, " . $prefix . "posts posts, " . $prefix . "job_orders orders
				 where posts.post_author='$uid' AND posts.ID=ratings.pid AND ratings.awarded='1' AND
				 ratings.datemade>'$datebefore' AND orders.date_made<'$datemax' order by ratings.id desc";
				$r = $wpdb->get_results($s);

				$ratings_sum = 0;
				$ratings_count = 0;
				$ratings_average2 = 0;
				if (count($r) > 0) {
					foreach ($r as $row) {
						$ratings_sum += $row->grade;
						$ratings_count++;
					}
					$ratings_average2 = ($ratings_sum / $ratings_count) * 20;
				}

				if(!isset($wpjobster_level2_upgrade_rating))$wpjobster_level2_upgrade_rating = get_option("wpjobster_level2_upgrade_rating");
				if($wpjobster_level2_upgrade_rating>1){
					//do nothing
				}else{
					$wpjobster_level2_upgrade_rating=95;
				}
				if ($total2 >= get_option('wpjobster_level2_min') && $ratings_average2 >= $wpjobster_level2_upgrade_rating) {
					$level2 = 1;
				}

				if(!isset($wpjobster_level1_upgrade_rating))$wpjobster_level1_upgrade_rating = get_option("wpjobster_level1_upgrade_rating");
				if($wpjobster_level1_upgrade_rating>1){

				}else{
					$wpjobster_level1_upgrade_rating=90;
				}

				if ($total1 >= get_option('wpjobster_level1_min') && $ratings_average1 >= $wpjobster_level1_upgrade_rating) {
					$level1 = 1;
				}

				$current_level = get_user_meta($uid, 'user_level', true);
				$lvl = $current_level;

				if ($current_level != 3) {
					if(!isset($wpjobster_level0_recheck_interval))$wpjobster_level0_recheck_interval=get_option("wpjobster_level0_recheck_interval");
					if(!isset($wpjobster_level1_recheck_interval))$wpjobster_level1_recheck_interval=get_option("wpjobster_level1_recheck_interval");
					if(!isset($wpjobster_level2_recheck_interval))$wpjobster_level2_recheck_interval=get_option("wpjobster_level2_recheck_interval");
					if($wpjobster_level2_recheck_interval=='' || !is_numeric($wpjobster_level2_recheck_interval) || $wpjobster_level2_recheck_interval<=1){
						$wpjobster_level2_recheck_interval = 2;
					}
					if ($level2) {
						//echo "\n updated user level to 2";
						$current_level = 2;
						update_user_meta($uid, 'date_toclear', strtotime('+'.$wpjobster_level2_recheck_interval.' month', time()));
					}
					elseif ($level1) {
						$current_level = 1;
						update_user_meta($uid, 'date_toclear', strtotime('+'.$wpjobster_level1_recheck_interval.' month', time()));
					}
					else {

						$current_level = 0;
						update_user_meta($uid, 'date_toclear', strtotime('+'.$wpjobster_level0_recheck_interval.' month', time()));
					}
				}
				if(!isset($wpjobster_auto_upgrade_user_level))
					$wpjobster_auto_upgrade_user_level = get_option("wpjobster_auto_upgrade_user_level");
				if(!isset($wpjobster_auto_downgrade_user_level))
					$wpjobster_auto_downgrade_user_level = get_option("wpjobster_auto_downgrade_user_level");

				if ($current_level > $lvl && $wpjobster_auto_upgrade_user_level!='no') {
					wpjobster_send_email_allinone_translated('level_up', $uid);
					wpjobster_send_sms_allinone_translated('level_up', $uid);
					update_user_meta($uid, 'user_level', $current_level);
				}


				elseif ($current_level < $lvl && $wpjobster_auto_downgrade_user_level!='no') {
					wpjobster_send_email_allinone_translated('level_down', $uid);
					wpjobster_send_sms_allinone_translated('level_down', $uid);
					update_user_meta($uid, 'user_level', $current_level);
					// wpjobster_deactivate_all_jobs($uid);
				}


			}
			do_user_level_extras_check($uid);
		}//end foreach
	}// end if
}

function do_user_level_extras_check($userid){
	$level_chk = new WP_Query( "meta_key=closed&meta_value=0&post_status=publish,draft,pending&post_type=job&order=DESC&orderby=id&author=".$userid);
	if($level_chk->have_posts() ){
		$user_level = wpjobster_get_user_level($userid);
		$total_allowed_extras =  get_option('wpjobster_get_level'.$user_level.'_extras');

		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info($userid);
		extract($wpjobster_subscription_info);

		if($wpjobster_subscription_noof_extras){
			$total_allowed_extras =  $wpjobster_subscription_noof_extras;

		}
		if (!is_numeric($total_allowed_extras)) $total_allowed_extras = 3;

		while ( $level_chk->have_posts() ) : $level_chk->the_post();
				$post_id = get_the_ID();
				$all_extras = get_number_of_extras_by_job($post_id);
				if($all_extras>$total_allowed_extras){
					update_post_meta($post_id,	'more_extras',	'yes');
					$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"pending"));
				}else{
					$more_extras = get_post_meta($post_id,	'more_extras',	true);
					if($more_extras=='yes'){
						delete_post_meta($post_id,	'more_extras');

						// check if the admin has enabled "Admin approves each job"
						$wpjobster_admin_approve_job = get_option('wpjobster_admin_approve_job');
						if ($wpjobster_admin_approve_job == "yes") {
							$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"draft"));
							update_post_meta($post_id, 'under_review', "1");
						} else {
							$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"publish"));
							update_post_meta($post_id, 'under_review', "0");
						}

						//echo "ok ".get_post_status($post_id);
					}
				}
				$more_extras = get_post_meta($post_id,	'more_extras',	true);
		endwhile;
	}
}

function do_user_level_job_price_check($userid,$post_id=0){ // check for the jobs with the status
	$uid = $userid;
	$level_chk = new WP_Query( "meta_key=closed&meta_value=0&post_status=publish,draft,pending&post_type=job&order=DESC&orderby=id&author=".$userid);
	if($level_chk->have_posts() ){
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info($userid);
		extract($wpjobster_subscription_info);
		$user_level = wpjobster_get_user_level($uid);
		$job_price_allowed=get_option('wpjobster_level'.$user_level.'_max');
		if($wpjobster_subscription_max_job_price)$job_price_allowed = $wpjobster_subscription_max_job_price;

		while ( $level_chk->have_posts() ) : $level_chk->the_post();
			$post_id = get_the_ID();
			$job_price  = get_post_meta($post_id,"price",true);
			if($job_price > $job_price_allowed){
				update_post_meta($post_id,	'more_job_price',	'yes');
				$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"pending"));
			}else{

				$more_job_price = get_post_meta($post_id,	'more_job_price',	true);
				if($more_job_price=='yes'){
					delete_post_meta($post_id,	'more_job_price');

					// check if the admin has enabled "Admin approves each job"
					$wpjobster_admin_approve_job = get_option('wpjobster_admin_approve_job');
					if ($wpjobster_admin_approve_job == "yes") {
						$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"draft"));
						update_post_meta($post_id, 'under_review', "1");
					} else {
						$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"publish"));
						update_post_meta($post_id, 'under_review', "0");
					}
				}
			}
			$more_job_price = get_post_meta($post_id,	'more_job_price',	true);
		endwhile;
	}
}
function do_user_level_extras_price_check($userid,$pid=0){
	$uid = $userid;
	if($pid!=0){
		$pid_stmt = "&p=$pid";
	}else{
		$pid_stmt="";
	}
	$level_chk = new WP_Query( "meta_key=closed&meta_value=0&post_status=publish,draft,pending&post_type=job&order=DESC&orderby=id&author=".$userid.$pid_stmt);

	if($level_chk->have_posts() ){

		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info($userid);
		extract($wpjobster_subscription_info);
		$user_level = wpjobster_get_user_level($uid);
		$max_allowed_extra_price=get_option('wpjobster_level'.$user_level.'_max');
		if($wpjobster_subscription_max_job_price)$max_allowed_extra_price = $wpjobster_subscription_max_extra_price ;

		if(!$max_allowed_extra_price){
			return false;
		}
		while ( $level_chk->have_posts() ) : $level_chk->the_post();
			$post_id = get_the_ID();
			$do_more=1;
			for($i=1;$i<=$wpjobster_subscription_noof_extras && $do_more==1; $i++):
				$cur_extra_price=(int)get_post_meta($post_id, 'extra'.$i.'_price', true);

				if($cur_extra_price > $max_allowed_extra_price){


					update_post_meta($post_id,	'more_extra_price',	'yes');
					$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"pending"));
					$do_more=0;
				}
				if($i==$wpjobster_subscription_noof_extras && $do_more==1){

					if(get_post_meta($post_id,"more_extra_price",true)=='yes'){
						delete_post_meta($post_id,	'more_extra_price');
						$wpjobster_admin_approve_job = get_option('wpjobster_admin_approve_job');
						if ($wpjobster_admin_approve_job == "yes") {
							$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"draft"));
							update_post_meta($post_id, 'under_review', "1");
						} else {
							$post_update = wp_update_post(array("ID"=>$post_id,"post_status"=>"publish"));
							update_post_meta($post_id, 'under_review', "0");
						}
					}
				}
			endfor;

			$more_extra_price = get_post_meta($post_id,	'more_extra_price',	true);
		endwhile;
	}
}
