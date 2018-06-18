<?php
function wpjobster_show_rating_star_user($uid){
	$concat = '';
	$nr_ratings = 0;
	global $wpdb;
	$s = "select count(grade) cnt, sum(grade) smm from " . $wpdb->prefix . "job_ratings where uid='$uid' and awarded='1'";
	$r = $wpdb->get_results($s);

	if (count($r) > 0) {
		$nr_ratings = $r[0]->cnt;
		$sum = $r[0]->smm;

		if ($nr_ratings > 0) {

			if ($sum > 0)                $sdd = ceil($sum / $nr_ratings); else                $sdd = 1;
			for ($i = 1; $i <= $sdd; $i++) {
				$concat .= ' <i class="star icon full-star-rating"></i>';
			}

			for ($i = $sdd + 1; $i <= 5; $i++) {
				$concat .= ' <i class="empty star icon empty-star-rating"></i>';
			}

		} else {
			$concat = '';
			for ($i = 1; $i <= 5; $i++) {
				$concat .= ' <i class="empty star icon empty-star-rating"></i>';
			}

		}

	} else {
		$concat = '';
		for ($i = 1; $i <= 5; $i++) {
			$concat .= ' <i class="empty star icon empty-star-rating"></i>';
		}

	}

	return $concat . " (" . $nr_ratings . ")";
}

function show_more_feedbacks_user(){
	ob_start();
	global $wpdb;
	$uid = $_POST['uid'];
	$start_from  = $_POST['current'];
	$total_per_load  = $_POST['total_per_load'];
	$total_shown = $start_from+$total_per_load;
	//User Timezone Function
	wpjobster_timezone_change();

		$query_feedback_total = "select distinct *, ratings.datemade datemade from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders,
		".$wpdb->prefix."posts posts where posts.ID=orders.pid AND
		 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' order by datemade desc ";
		$r_feedback_total = $wpdb->get_results($query_feedback_total);
		$r_seller_total = count($r_feedback_total);

		$query = "select distinct *, ratings.datemade datemade from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders,
		".$wpdb->prefix."posts posts where posts.ID=orders.pid AND
		 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' order by datemade desc limit $start_from , $total_per_load";
		$r = $wpdb->get_results($query);

			$cnt = 0;
			foreach($r as $row){
				$cnt++;
				$post = $row->pid;
				$post = get_post($post);
				$user2 = get_userdata($row->uid);

				?>
				 <div class="feed cf">
					<div>
						<a href="" class="left p10r job-feedback-picture"><img width="45" height="45" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar($row->uid,46,46); ?>" /></a>
						<div class="left job-feedback-content">
							<div class="left cb p5b w100">
							<a class="left" href="<?php echo wpj_get_user_profile_link( $user2->user_login ); ?>"><?php echo $user2->user_login; ?></a>
							<div class="left p10l">
								<div class="user-page-reviews">
									<?php echo wpjobster_show_stars_our_of_number($row->grade); ?>
								</div>
							</div>
							<span class="grey-time p10l right">
							<?php
							echo date_i18n(get_option( 'date_format' ), $row->datemade);
							?>
							</span>
							</div>
							<div class="cb">
							<p><?php echo stripslashes($row->reason); ?></p>
							</div>
						</div>
				   </div>
				</div>
					<?php
		$query_seller = "select distinct *, ratings.datemade,orders.uid as buyer_id, datemade from ".$wpdb->prefix."job_ratings_by_seller ratings,"
					. " ".$wpdb->prefix."job_orders orders,
		".$wpdb->prefix."posts posts where posts.ID=orders.pid AND posts.ID='$row->pid' AND
		 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' and orders.id=".$row->orderid." order by datemade desc limit 3";
		$r_seller = $wpdb->get_results($query_seller);
		if($r_seller){
			$row_seller=$r_seller[0];
			$user3 = get_userdata($uid);
					?>
					<div class="feed cf feedback-answer">
					<div>
						<a href="" class="left p10r job-feedback-picture"><img width="35" height="35" border="0" class="round-avatar" src="<?php echo wpjobster_get_avatar($user3->ID,46,46); ?>" /></a>
						<div class="left job-feedback-content">
							<div class="left cb p5b w100">
							<a class="left" href="<?php echo wpj_get_user_profile_link( $user3->user_login ); ?>"><?php echo $user3->user_login; ?></a>
							<div class="left p10l">
								<div class="user-page-reviews">
									<?php echo wpjobster_show_stars_our_of_number($row_seller->grade); ?>
								</div>
							</div>
							<span class="grey-time p10l right">
							</span>
							</div>
							<div class="cb">
							<p><?php echo stripslashes($row_seller->reason); ?></p>
							</div>
						</div>
				   </div>
				</div>
				<?php
		}// if seller rating
			}
			$output['html']=ob_get_contents();
			ob_end_clean();
			$output['ok']=1;
	if($total_shown >= $r_seller_total){
		$output['current']=0;
	}else{
		$output['current']=$total_shown;
	}
	echo json_encode($output);
	die();
} // end function for displaying Load more feedback
add_action('wp_ajax_nopriv_show_more_feedbacks_user', 'show_more_feedbacks_user');
add_action('wp_ajax_show_more_feedbacks_user', 'show_more_feedbacks_user');


add_action( 'init', 'wpj_save_user_reviews' );
function wpj_save_user_reviews(){
	if (isset($_POST['rate_me'])) {

		check_ajax_referer( 'buyer-review', 'ajax_nonce', false );

		global $wpdb;
		$tm = current_time('timestamp', 1);
		$reason = urldecode($_POST['reason']);
		$reason = esc_sql($reason);

		if (!is_user_logged_in())        exit;
		if (isset($_POST['uprating']) && isset($_POST['buyer'])) {
			$grade = $_POST['uprating'];
			$id = $_POST['ids'];
			$pid = $_POST['pid'];
			$orderid = $_POST['orderid'];
			$buyer = $_POST['buyer'];

			if (!is_demo_user()) {
				$s = "select count(*) as cnt from " . $wpdb->prefix . "job_ratings_by_seller where "
						. "orderid='$orderid' and uid='$buyer' and pid='$pid' ";
				$total_feedback = $wpdb->get_results($s);
				print_r($total_feedback);
				if($total_feedback[0]->cnt >=1) exit;


				$s = "insert into " . $wpdb->prefix . "job_ratings_by_seller set "
						. "orderid='$orderid',uid='$buyer',pid='$pid', grade='$grade', reason='$reason', awarded='1' ,datemade='$tm' ";
				$wpdb->query($s);
				$id = $wpdb->insert_id;
				$s_sql = "select * from " . $wpdb->prefix . "job_ratings_by_seller ratings, " . $wpdb->prefix . "job_orders orders  where ratings.id='$id' AND orders.id=ratings.orderid";
				$r_sql = $wpdb->get_results($s_sql);
				$r_sql = $r_sql[0];
				$rating = get_post_meta($r_sql->pid, 'rating', true);

				if (empty($rating))            $rating = 0;
				$rating = $rating + 1;
				update_post_meta($r_sql->pid, 'rating', $rating);
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
				$post1 = get_post($r_sql->pid);

				$s_chatbox = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid) values('$tm','-19','$orderid')";
				$wpdb->query($s_chatbox);
				wpj_update_user_notifications( $buyer, 'notifications', +1 );

				if (get_post_type($r_sql->pid) == 'offer') {
					wpjobster_send_email_allinone_translated('new_offer_feedback', $buyer,$post1->post_author , $r_sql->pid, $orderid);
					wpjobster_send_sms_allinone_translated('new_offer_feedback', $buyer,$post1->post_author, $r_sql->pid, $orderid);
				} else {
					wpjobster_send_email_allinone_translated('new_feedback',$buyer,$post1->post_author, $r_sql->pid, $orderid);
					wpjobster_send_sms_allinone_translated('new_feedback', $buyer,$post1->post_author, $r_sql->pid, $orderid);
				}

			}

		}elseif (isset($_POST['uprating'])) {
			$grade = $_POST['uprating'];
			$id = $_POST['ids'];

			if (!is_demo_user()) {

				$s = "update " . $wpdb->prefix . "job_ratings set grade='$grade', reason='$reason', awarded='1' ,datemade='$tm' where id='$id'";
				$wpdb->query($s);
				$s_sql = "select * from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders  where ratings.id='$id' AND orders.id=ratings.orderid";
				$r_sql = $wpdb->get_results($s_sql);
				$r_sql = $r_sql[0];

				$rating = get_post_meta($r_sql->pid, 'rating', true);

				wpjobster_get_job_rating_new($r_sql->pid);

				if (empty($rating))            $rating = 0;
				$rating = $rating + 1;
				update_post_meta($r_sql->pid, 'rating', $rating);
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
				$post1 = get_post($r_sql->pid);

				$s = "update " . $wpdb->prefix . "job_ratings set uid='" . $post1->post_author . "', pid='" . $r_sql->pid . "' where id='$id'";
				$wpdb->query($s);

				$s_chatbox = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid) values('$tm','-18','$r_sql->orderid')";
				$wpdb->query($s_chatbox);
				wpj_update_user_notifications( $post1->post_author, 'notifications', +1 );

				if (get_post_type($r_sql->pid) == 'offer') {
					wpjobster_send_email_allinone_translated('new_offer_feedback', $post1->post_author, $uid, $r_sql->pid, $r_sql->orderid);
					wpjobster_send_sms_allinone_translated('new_offer_feedback', $post1->post_author, $uid, $r_sql->pid, $r_sql->orderid);
				} else {
					wpjobster_send_email_allinone_translated('new_feedback', $post1->post_author, $uid, $r_sql->pid, $r_sql->orderid);
					wpjobster_send_sms_allinone_translated('new_feedback', $post1->post_author, $uid, $r_sql->pid, $r_sql->orderid);
				}

			}

		}

		exit;
	}
}
