<?php

class PrivateMessages {

	function wpj_private_messages_vars1() {

		global $wpdb;

		$vars1 = array();

		$nrpostsPage = 10;
		if(isset($_GET['pj'])){
			$page = $_GET['pj'];
		}else{
			$page = 1;
			$_GET['pj'] = 1;
		}
		$my_page = $page;

		$s  = "select * from ".$wpdb->prefix."job_pm order by id desc limit ".($nrpostsPage * ($page - 1) )." ,$nrpostsPage";
		$r  = $wpdb->get_results($s);

		$s1 = "select id from ".$wpdb->prefix."job_pm order by id desc";
		$r1 = $wpdb->get_results($s1);

		if(count($r) > 0):

			$total_nr = count($r1);

			$nrposts    = $total_nr;
			$totalPages = ceil($nrposts / $nrpostsPage);
			$pagess     = $totalPages;
			$batch      = 10;

			$start    = floor($my_page/$batch) * $batch + 1;
			$end      = $start + $batch - 1;
			$end_me   = $end + 1;
			$start_me = $start - 1;

			if($end > $totalPages) $end = $totalPages;
			if($end_me > $totalPages) $end_me = $totalPages;

			if($start_me <= 0) $start_me = 1;

			$previous_pg = $my_page - 1;
			if($previous_pg <= 0) $previous_pg = 1;

			$next_pg = $my_page + 1;
			if($next_pg >= $totalPages) $next_pg = 1;

		endif;

		$vars1 = array(
			'r'           => $r,
			'next_pg'     => $next_pg,
			'totalPages'  => $totalPages,
			'previous_pg' => $previous_pg,
			'start'       => $start,
			'start_me'    => $start_me,
			'my_page'     => $my_page,
			'end_me'      => $end_me,
			'end'         => $end
		);

		return $vars1;

	}

	function wpj_private_messages_vars2() {

		global $wpdb;

		$vars2 = array();

		$rows_per_page = 10;

		if(isset($_GET['pj'])) $pageno = $_GET['pj'];
		else $pageno = 1;

		$search_user = trim($_GET['search_user']);
		$user        = get_user_by('login', $search_user);
		$uid         = ($user) ? $user->ID : 0;
		$s1          = "select id from ".$wpdb->prefix."job_pm where initiator='$uid' OR user='$uid' order by id desc";
		$s           = "select * from ".$wpdb->prefix."job_pm where initiator='$uid' OR user='$uid' order by id desc";
		$limit       = ' LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		$r           = $wpdb->get_results($s1);
		$nr          = count($r);
		$lastpage    = ceil($nr/$rows_per_page);
		$r           = $wpdb->get_results($s.$limit);

		$vars2 = array(
			'r'        => $r,
			'lastpage' => $lastpage,
			'pageno'   => $pageno
		);

		return $vars2;

	}

	function wpjobster_private_messages_scr() {

		$id_icon      = 'icon-options-general-mess';
		$ttl_of_stuff = 'Jobster - '.__('Private Messages','wpjobster');
		global $wpdb;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if (!is_demo_admin()) {
				if(isset($_GET['del_mess']))
				{
					$del_mess = $_GET['del_mess'];
					global $wpdb;

					$message = wpj_get_private_message( $del_mess );
					$s = "update ".$wpdb->prefix."job_pm set show_to_source='0', show_to_destination='0' where id='$del_mess'";
					$wpdb->query($s);
					wpj_refresh_user_notifications( $message->user, 'messages' );

					echo '<div class="updated fade">'.__('Message was deleted','wpjobster').'</div>';
				}

				if(isset($_GET['p_del_mess']))
				{
					$del_mess = $_GET['p_del_mess'];
					global $wpdb;

					$message = wpj_get_private_message( $del_mess );
					$s = "delete from ".$wpdb->prefix."job_pm  where id='$del_mess'";
					$wpdb->query($s);
					wpj_refresh_user_notifications( $message->user, 'messages' );

					echo '<div class="updated fade">'.__('Message was deleted','wpjobster').'</div>';
				}
			}

			wpj_private_messages_html();

		echo '</div>';

	}

}

$pm = new PrivateMessages();
