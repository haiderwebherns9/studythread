<?php

class UserLevels {

	function wpj_user_level_vars1() {

		$vars1 = array();

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
		$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));

		$vars1 = array(
			'r'        => $r,
			'lastpage' => $lastpage,
			'pageno'   => $pageno,
			'nr'       => $nr,
			'arr'      => $arr
		);

		return $vars1;
	}

	function wpj_user_level_vars2() {

		$vars2 = array();

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

		$vars = array(

			'r'             => $r,
			'lastpage'      => $lastpage,
			'rows_per_page' => $rows_per_page,
			'pageno'        => $pageno,
			'nr'            => $nr,

		);

		return $vars;

	}

	function wpj_user_levels_update_user_level() {

		$arr = array("yes" => __("Yes",'wpjobster'), "no" => __("No",'wpjobster'));
		if(isset($_POST['wpjobster_update_user_level_setting'])){

			update_option('wpjobster_get_level0_packages', trim($_POST['wpjobster_get_level0_packages']));
			update_option('wpjobster_get_level1_packages', trim($_POST['wpjobster_get_level1_packages']));
			update_option('wpjobster_get_level2_packages', trim($_POST['wpjobster_get_level2_packages']));
			update_option('wpjobster_get_level3_packages', trim($_POST['wpjobster_get_level3_packages']));

			update_option('wpjobster_enable_extra_fast_delivery', trim($_POST['wpjobster_enable_extra_fast_delivery']));

			update_option('wpjobster_get_level0_fast_delivery_multiples', trim($_POST['wpjobster_get_level0_fast_delivery_multiples']));
			update_option('wpjobster_get_level1_fast_delivery_multiples', trim($_POST['wpjobster_get_level1_fast_delivery_multiples']));
			update_option('wpjobster_get_level2_fast_delivery_multiples', trim($_POST['wpjobster_get_level2_fast_delivery_multiples']));
			update_option('wpjobster_get_level3_fast_delivery_multiples', trim($_POST['wpjobster_get_level3_fast_delivery_multiples']));

			update_option('wpjobster_enable_extra_additional_revision', trim($_POST['wpjobster_enable_extra_additional_revision']));

			update_option('wpjobster_get_level0_add_rev_multiples', trim($_POST['wpjobster_get_level0_add_rev_multiples']));
			update_option('wpjobster_get_level1_add_rev_multiples', trim($_POST['wpjobster_get_level1_add_rev_multiples']));
			update_option('wpjobster_get_level2_add_rev_multiples', trim($_POST['wpjobster_get_level2_add_rev_multiples']));
			update_option('wpjobster_get_level3_add_rev_multiples', trim($_POST['wpjobster_get_level3_add_rev_multiples']));

			$wpjobster_default_level_nr = $_POST['wpjobster_default_level_nr'];
			if($wpjobster_default_level_nr > 3 or $wpjobster_default_level_nr < 0 or !is_numeric($wpjobster_default_level_nr)) $wpjobster_default_level_nr = 0;
			update_option('wpjobster_default_level_nr',         trim($wpjobster_default_level_nr));
			update_option('wpjobster_level1_min',     trim($_POST['wpjobster_level1_min']));
			update_option('wpjobster_level2_min',     trim($_POST['wpjobster_level2_min']));

			update_option('wpjobster_enable_extra',trim($_POST['wpjobster_enable_extra']));

			$wpjobster_get_level0_extras = $_POST['wpjobster_get_level0_extras'];
			if ( $wpjobster_get_level0_extras < 1 or !is_numeric($wpjobster_get_level0_extras ) ) {
				$wpjobster_get_level0_extras = 3;
			} elseif ( $wpjobster_get_level0_extras > 10 ) {
				$wpjobster_get_level0_extras = 10;
			}
			update_option('wpjobster_get_level0_extras',         trim($wpjobster_get_level0_extras));

			$wpjobster_get_level1_extras = $_POST['wpjobster_get_level1_extras'];
			if ( $wpjobster_get_level1_extras < 1 or !is_numeric($wpjobster_get_level1_extras ) ) {
				$wpjobster_get_level1_extras = 3;
			} elseif ( $wpjobster_get_level1_extras > 10 ) {
				$wpjobster_get_level1_extras = 10;
			}
			update_option('wpjobster_get_level1_extras',         trim($wpjobster_get_level1_extras));

			$wpjobster_get_level2_extras = $_POST['wpjobster_get_level2_extras'];
			if ( $wpjobster_get_level2_extras < 1 or !is_numeric($wpjobster_get_level2_extras ) ) {
				$wpjobster_get_level2_extras = 3;
			} elseif ( $wpjobster_get_level2_extras > 10 ) {
				$wpjobster_get_level2_extras = 10;
			}
			update_option('wpjobster_get_level2_extras',         trim($wpjobster_get_level2_extras));

			$wpjobster_get_level3_extras = $_POST['wpjobster_get_level3_extras'];
			if ( $wpjobster_get_level3_extras < 1 or !is_numeric($wpjobster_get_level3_extras)) {
				$wpjobster_get_level3_extras = 3;
			} elseif ( $wpjobster_get_level3_extras > 10 ) {
				$wpjobster_get_level3_extras = 10;
			}
			update_option('wpjobster_get_level3_extras',         trim($wpjobster_get_level3_extras));

			// multiples
			$wpjobster_enable_multiples = trim( $_POST['wpjobster_enable_multiples'] );

			if ( $wpjobster_enable_multiples == "yes" &&
				! wpj_is_allowed( 'job_multiples' ) ) {

				update_option( 'wpjobster_enable_multiples', 'no' );
			wpj_disabled_settings_error( 'job_multiples' );

		} else {
			update_option( 'wpjobster_enable_multiples', $wpjobster_enable_multiples );
		}

		$wpjobster_multiples_err_flag = 0;

		$wpjobster_get_level0_jobmultiples = $_POST['wpjobster_get_level0_jobmultiples'];
		if($wpjobster_get_level0_jobmultiples < 2 or !is_numeric($wpjobster_get_level0_jobmultiples)) {
			$wpjobster_get_level0_jobmultiples = 3; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level0_jobmultiples', trim($wpjobster_get_level0_jobmultiples));
		$wpjobster_get_level0_extramultiples = $_POST['wpjobster_get_level0_extramultiples'];
		if($wpjobster_get_level0_extramultiples < 2 or !is_numeric($wpjobster_get_level0_extramultiples)) {
			$wpjobster_get_level0_extramultiples = 3; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level0_extramultiples', trim($wpjobster_get_level0_extramultiples));

		$wpjobster_get_level1_jobmultiples = $_POST['wpjobster_get_level1_jobmultiples'];
		if($wpjobster_get_level1_jobmultiples < 2 or !is_numeric($wpjobster_get_level1_jobmultiples)) {
			$wpjobster_get_level1_jobmultiples = 5; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level1_jobmultiples', trim($wpjobster_get_level1_jobmultiples));
		$wpjobster_get_level1_extramultiples = $_POST['wpjobster_get_level1_extramultiples'];
		if($wpjobster_get_level1_extramultiples < 2 or !is_numeric($wpjobster_get_level1_extramultiples)) {
			$wpjobster_get_level1_extramultiples = 5; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level1_extramultiples', trim($wpjobster_get_level1_extramultiples));

		$wpjobster_get_level2_jobmultiples = $_POST['wpjobster_get_level2_jobmultiples'];
		if($wpjobster_get_level2_jobmultiples < 2 or !is_numeric($wpjobster_get_level2_jobmultiples)) {
			$wpjobster_get_level2_jobmultiples = 10; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level2_jobmultiples', trim($wpjobster_get_level2_jobmultiples));
		$wpjobster_get_level2_extramultiples = $_POST['wpjobster_get_level2_extramultiples'];
		if($wpjobster_get_level2_extramultiples < 2 or !is_numeric($wpjobster_get_level2_extramultiples)) {
			$wpjobster_get_level2_extramultiples = 10; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level2_extramultiples', trim($wpjobster_get_level2_extramultiples));

		$wpjobster_get_level3_jobmultiples = $_POST['wpjobster_get_level3_jobmultiples'];
		if($wpjobster_get_level3_jobmultiples < 2 or !is_numeric($wpjobster_get_level3_jobmultiples)) {
			$wpjobster_get_level3_jobmultiples = 20; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level3_jobmultiples', trim($wpjobster_get_level3_jobmultiples));
		$wpjobster_get_level3_extramultiples = $_POST['wpjobster_get_level3_extramultiples'];
		if($wpjobster_get_level3_extramultiples < 2 or !is_numeric($wpjobster_get_level3_extramultiples)) {
			$wpjobster_get_level3_extramultiples = 20; $wpjobster_multiples_err_flag = 1;
		}
		update_option('wpjobster_get_level3_extramultiples', trim($wpjobster_get_level3_extramultiples));

		// custom extras
		$wpjobster_enable_custom_extras = trim( $_POST['wpjobster_enable_custom_extras'] );

		if ( $wpjobster_enable_custom_extras == "yes" &&
			! wpj_is_allowed( 'custom_extras' ) ) {

			update_option( 'wpjobster_enable_custom_extras', 'no' );
		wpj_disabled_settings_error( 'custom_extras' );

		} else {
			update_option( 'wpjobster_enable_custom_extras', $wpjobster_enable_custom_extras );
		}

		$wpjobster_customextras_err_flag = 0;

		$wpjobster_get_level0_customextrasamount = $_POST['wpjobster_get_level0_customextrasamount'];
		if($wpjobster_get_level0_customextrasamount < 1 or !is_numeric($wpjobster_get_level0_customextrasamount)) {
			$wpjobster_get_level0_customextrasamount = 1; $wpjobster_customextras_err_flag = 1;
		}
		update_option('wpjobster_get_level0_customextrasamount', trim($wpjobster_get_level0_customextrasamount));

		$wpjobster_get_level1_customextrasamount = $_POST['wpjobster_get_level1_customextrasamount'];
		if($wpjobster_get_level1_customextrasamount < 1 or !is_numeric($wpjobster_get_level1_customextrasamount)) {
			$wpjobster_get_level1_customextrasamount = 1; $wpjobster_customextras_err_flag = 1;
		}
		update_option('wpjobster_get_level1_customextrasamount', trim($wpjobster_get_level1_customextrasamount));

		$wpjobster_get_level2_customextrasamount = $_POST['wpjobster_get_level2_customextrasamount'];
		if($wpjobster_get_level2_customextrasamount < 1 or !is_numeric($wpjobster_get_level2_customextrasamount)) {
			$wpjobster_get_level2_customextrasamount = 1; $wpjobster_customextras_err_flag = 1;
		}
		update_option('wpjobster_get_level2_customextrasamount', trim($wpjobster_get_level2_customextrasamount));

		$wpjobster_get_level3_customextrasamount = $_POST['wpjobster_get_level3_customextrasamount'];
		if($wpjobster_get_level3_customextrasamount < 1 or !is_numeric($wpjobster_get_level3_customextrasamount)) {
			$wpjobster_get_level3_customextrasamount = 1; $wpjobster_customextras_err_flag = 1;
		}
		update_option('wpjobster_get_level3_customextrasamount', trim($wpjobster_get_level3_customextrasamount));


		echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
		if ( $wpjobster_multiples_err_flag ) {
			echo '<div class="error notice is-dismissible"><p>'.__('Job multiples need to be greater than or equal to 2! Default values have been saved!','wpjobster').'</p></div>';
		}
		if ( $wpjobster_customextras_err_flag ) {
			echo '<div class="error notice is-dismissible"><p>'.__('Max total custom extras amount need to be greater than or equal to 1! Default values have been saved!','wpjobster').'</p></div>';
		}

		update_option("wpjobster_auto_upgrade_user_level",$_POST['wpjobster_auto_upgrade_user_level']);
		update_option("wpjobster_auto_downgrade_user_level",$_POST['wpjobster_auto_downgrade_user_level']);
		update_option("wpjobster_level1_upgrade_rating",$_POST['wpjobster_level1_upgrade_rating']);
		update_option("wpjobster_level2_upgrade_rating",$_POST['wpjobster_level2_upgrade_rating']);
		update_option("wpjobster_level0_recheck_interval",$_POST['wpjobster_level0_recheck_interval']);
		update_option("wpjobster_level1_recheck_interval",$_POST['wpjobster_level1_recheck_interval']);
		update_option("wpjobster_level2_recheck_interval",$_POST['wpjobster_level2_recheck_interval']);
		}
	}

	function wpjobster_user_levels() {
		$id_icon    = 'icon-options-general-lvls';
		$ttl_of_stuff   = 'Jobster - '.__('User Levels','wpjobster');

		echo '<div class="wrap">';
		echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
		echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

		wpj_user_levels_html();

		echo '</div>';
	}

}

$ul = new UserLevels();
