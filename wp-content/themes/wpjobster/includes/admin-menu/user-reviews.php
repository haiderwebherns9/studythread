<?php
class UserReviews {
	function wpj_user_reviews_vars() {
		$vars = array();
		global $wpdb;
		$rows_per_page = 10;
		if(isset($_GET['pj'])) $pageno = $_GET['pj'];
		else $pageno = 1;
		$s1 = "select * from ".$wpdb->prefix."job_ratings where awarded>0 order by id desc";
		$all_r = $wpdb->get_results($s1); $nr = count($all_r);
		$post_count = 0;
		foreach($all_r as $row){
			$s_ql = "select * from ".$wpdb->prefix."job_orders where id='".$row->orderid."'";
			$r_ql = $wpdb->get_results($s_ql);
			$post = get_post($r_ql[0]->pid);
			if($post){
				$post_count++;
			}
		}
		$lastpage = ceil($post_count/$rows_per_page);
		$s = "select * from ".$wpdb->prefix."job_ratings where awarded>0 order by id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		$r = $wpdb->get_results($s);
		$vars = array(
			'r' => $r,
			'lastpage' => $lastpage,
			'pageno' => $pageno
		);
		return $vars;
	}
	function wpj_get_reviews_wpdb() {
		global $wpdb;
		$rows_per_page = 10;
		if(isset($_GET['pj'])) $pageno = $_GET['pj'];
		else $pageno = 1;
		$s1 = "select * from ".$wpdb->prefix."job_ratings where awarded>0 order by id desc";
		$all_r = $wpdb->get_results($s1); $nr = count($all_r);
		$post_count = 0;
		foreach($all_r as $row){
			$s_ql = "select * from ".$wpdb->prefix."job_orders where id='".$row->orderid."'";
			$r_ql = $wpdb->get_results($s_ql);
			$post = get_post($r_ql[0]->pid);
			if($post){
				$post_count++;
			}
		}
		$lastpage      = ceil($post_count/$rows_per_page);
		$s = "select * from ".$wpdb->prefix."job_ratings where awarded>0 order by id desc LIMIT " .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		$r = $wpdb->get_results($s);
		foreach($r as $row) {
			$s_ql = "select * from ".$wpdb->prefix."job_orders where id='".$row->orderid."'";
			$r_ql = $wpdb->get_results($s_ql);
			$post = get_post($r_ql[0]->pid);
			$userdata = ($post) ? get_userdata($post->post_author) : '';
			$pid = $r_ql[0]->pid;
			$response_sql = "select * from ".$wpdb->prefix."job_ratings_by_seller where orderid='".$row->orderid."' and awarded=1 order by id desc";
			$response_res = $wpdb->get_results($response_sql);
			if($response_res){
				$response_row = $response_res[0];
				$userdata_response = get_userdata($response_row->uid);
				$response_login = ( $userdata_response ) ? $userdata_response->user_login : '';
				$pid               = $r_ql[0]->pid;
				$response_grade    = ''.wpjobster_show_stars_our_of_number($response_row->grade);
				$response_reason   = ''.$response_row->reason;
				$response_datemade = ''.date('d-M-Y H:i:s', $response_row->datemade);
				$response_delete   = '<form method="POST" action=""><input type="hidden" name="action" value="delete_review_response"><input type="hidden" name="idofreview_response" value="'.$response_row->id.'"><input type="submit" class="button-secondary" value="' . __("Delete Response",'wpjobster') . '"></form>';
			}else{
				$response_login    = '';
				$response_grade    = '';
				$response_reason   = '';
				$response_datemade = '';
				$response_delete   = '';
			}
			if($post){
				echo '<tr>';
				echo '<th >'.$userdata->user_login.'</th>';
				echo '<th>'.wpjobster_get_show_price_classic(get_post_meta($r_ql[0]->pid, 'price', true)).'</th>';
				echo '<th><a href="'.get_permalink($pid).'">'.wpjobster_wrap_the_title($post->post_title, $pid).'</a></th>';
				echo '<th>'.wpjobster_show_stars_our_of_number($row->grade).'</th>';
				echo '<th>'.$row->reason.'</th>';
				echo '<th>'.date('d-M-Y H:i:s', $row->datemade).'</th>';
				echo '<th><form method="POST" action=""><input type="hidden" name="action" value="delete_review"><input type="hidden" name="idofreview" value="'.$row->id.'"><input type="submit" class="button-secondary" value="' . __("Delete",'wpjobster') . '"></form></th>';
				echo '</tr>';
			}
			if($response_login!=''){
				echo '<tr class="review-response-row" style="">';
				echo '<th >'.$response_login.'</th>';
				echo '<th>'.wpjobster_get_show_price_classic($r_ql[0]->mc_gross).'</th>';
				echo '<th><a href="'.get_permalink($pid).'">'.wpjobster_wrap_the_title($r_ql[0]->job_title, $pid).'</a></th>';
				echo '<th>'.$response_grade .'</th>';
				echo '<th>'.$response_reason.'</th>';
				echo '<th>'.$response_datemade.'</th>';
				echo '<th>'.$response_delete.'</th>';
				echo '</tr>';
			}
		}
	}
	function wpjobster_user_reviews_scr() {
		$id_icon      = 'icon-options-general-rev';
		$ttl_of_stuff = 'Jobster - '.__('User Reviews','wpjobster');
		global $wpdb;
		if(isset($_POST['action'])&&$_POST['action']=='delete_review') {
			$row_review = $wpdb->get_results("select * from ".$wpdb->prefix."job_ratings where id=".$_POST['idofreview']."");
			$orderid = $row_review[0]->orderid;
			$wpdb->query("delete from ".$wpdb->prefix."job_ratings_by_seller where orderid='".$orderid."' limit 1");
			$wpdb->query("delete from ".$wpdb->prefix."job_ratings where id=".$_POST['idofreview']."");
		}elseif(isset($_POST['action'])&&$_POST['action']=='delete_review_response'){
			$wpdb->query("delete from ".$wpdb->prefix."job_ratings_by_seller where id=".$_POST['idofreview_response']."");
		}
		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';
			wpj_reviews_html();
		echo '</div>';
	}
}
$ur = new UserReviews();
