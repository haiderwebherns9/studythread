<?php

class TransactionMessage {

	function transaction_messages_vars() {

		global $wpdb;

		$vars = array();

		$nrpostsPage = 10;
			if (isset($_GET['pj'])) { $page = $_GET['pj']; }  else { $page = 1; }

			$my_page = $page;

			$s  = "select * from ".$wpdb->prefix."job_chatbox where uid >= 1 order by id desc limit ".($nrpostsPage * ($page - 1) )." ,$nrpostsPage";
			$r  = $wpdb->get_results($s);

			$s1 = "select id from ".$wpdb->prefix."job_chatbox where uid >= 1 order by id desc";
			$r1 = $wpdb->get_results($s1);

			if(count($r) > 0){

				$total_nr   = count($r1);

				$nrposts    = $total_nr;
				$totalPages = ceil($nrposts / $nrpostsPage);
				$pagess     = $totalPages;
				$batch      = 10;

				$start      = floor($my_page/$batch) * $batch + 1;
				$end        = $start + $batch - 1;
				$end_me     = $end + 1;
				$start_me   = $start - 1;

				if($end > $totalPages) $end          = $totalPages;
				if($end_me > $totalPages) $end_me    = $totalPages;

				if($start_me <= 0) $start_me         = 1;

				$previous_pg                         = $my_page - 1;
				if($previous_pg <= 0) $previous_pg   = 1;

				$next_pg                             = $my_page + 1;
				if($next_pg >= $totalPages) $next_pg = 1;

			}

			$vars = array(
				'r'           => $r,
				'totalPages'  => $totalPages,
				'start'       => $start,
				'my_page'     => $my_page,
				'end'         => $end,
				'previous_pg' => $previous_pg,
				'next_pg'     => $next_pg,
				'start_me'    => $start_me,
				'page'        => $page,
				'end_me'      => $end_me
			);

			return $vars;

	}

	function wpjobster_chat_messages_scr() {
		$id_icon      = 'icon-options-general-mess';
		$ttl_of_stuff = 'Jobster - '.__('Chat Box Messages','wpjobster');
		global $wpdb;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_GET['del_mess']))
			{
				$del_mess = $_GET['del_mess'];
				global $wpdb;

				$chatbox_message = wpj_get_chatbox_message( $del_mess );
				$related_order   = wpjobster_get_order( $chatbox_message->oid );

				$s               = "delete from ".$wpdb->prefix."job_chatbox where id='$del_mess'";
				$wpdb->query($s);

				wpj_refresh_user_notifications( wpj_get_seller_id( $related_order ), 'notifications' );
				wpj_refresh_user_notifications( wpj_get_buyer_id( $related_order ), 'notifications' );

				echo '<div class="updated fade">'.__('Message was deleted','wpjobster').'</div>';
			}

			wpj_transaction_messages_html();

		echo '</div>';
	}

}

$tm = new TransactionMessage();
