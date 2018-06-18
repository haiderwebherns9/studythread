<?php

class UserBadges {

	function wpj_user_badges_wpjobster_save2() {

		$var_arr = array();

		global $wpdb;
		$usr = trim($_GET['search_user']);
		$rows_per_page = 10;

		if(isset($_GET['pj'])) $pageno = $_GET['pj'];
		else $pageno = 1;

		$s1 = "select ID from ".$wpdb->users." where user_login like '%$usr%' order by user_login asc ";
		$s = "select * from ".$wpdb->users." where user_login like '%$usr%' order by user_login asc ";
		$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		$r = $wpdb->get_results($s1);
		$nr = count($r);
		$lastpage      = ceil($nr/$rows_per_page);
		$r = $wpdb->get_results($s.$limit);

		$var_arr = array(
			'lastpage'  => $lastpage,
			'results'   => $r,
			'nr'        => $nr
		);

		return $var_arr;

	}

	function wpj_user_badges_pj() {

		$var_arr = array();

		$rows_per_page = 10;

		if(isset($_GET['pj'])) $pageno = $_GET['pj'];
		else $pageno = 1;

		global $wpdb;

		$s1 = "select ID from ".$wpdb->users." order by user_login asc ";
		$s = "select * from ".$wpdb->users." order by user_login asc ";
		$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

		$r = $wpdb->get_results($s1); $nr = count($r);
		$lastpage      = ceil($nr/$rows_per_page);

		$r = $wpdb->get_results($s.$limit);

		$var_arr = array(
			'lastpage'  => $lastpage,
			'results'   => $r,
			'nr'        => $nr,
			'pageno'    => $pageno
		);

		return $var_arr;
	}

	function wpjobster_user_badges() {

		$id_icon      = 'icon-options-general-badges';
		$ttl_of_stuff = 'Jobster - '.__('User Badges','wpjobster');

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_POST['wpjobster_save_badge'])){
				update_option('wpjobster_enable_badges_sale', trim($_POST['wpjobster_enable_badges_sale']));

				$wpjobster_min_rating_badge_2 = trim($_POST['wpjobster_min_rating_badge_2']);
				if (!is_numeric($wpjobster_min_rating_badge_2) || $wpjobster_min_rating_badge_2 < 0 || $wpjobster_min_rating_badge_2 > 5) {
						$wpjobster_min_rating_badge_2 = 4.5;
				}
				update_option('wpjobster_min_rating_badge_2', $wpjobster_min_rating_badge_2);

				$wpjobster_min_rating_number_badge_2 = trim($_POST['wpjobster_min_rating_number_badge_2']);
				if (!is_numeric($wpjobster_min_rating_number_badge_2) || $wpjobster_min_rating_number_badge_2 < 0) {
						$wpjobster_min_rating_number_badge_2 = 5;
				}
				update_option('wpjobster_min_rating_number_badge_2', $wpjobster_min_rating_number_badge_2);

				$wpjobster_first_badge_price = trim($_POST['wpjobster_first_badge_price']);
				if (!is_numeric($wpjobster_first_badge_price) || $wpjobster_first_badge_price < 0) {
					$wpjobster_first_badge_price = 1;
				}
				update_option('wpjobster_first_badge_price', $wpjobster_first_badge_price);

				$wpjobster_second_badge_price = trim($_POST['wpjobster_second_badge_price']);
				if (!is_numeric($wpjobster_second_badge_price) || $wpjobster_second_badge_price < 0) {
					$wpjobster_second_badge_price = 1;
				}
				update_option('wpjobster_second_badge_price', $wpjobster_second_badge_price);
				update_option('wpjobster_second_badge_icon', trim($_POST['wpjobster_second_badge_icon']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			wpj_user_badges_html();

		echo '</div>';
	}
}

$ub = new UserBadges();
