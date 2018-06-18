<?php
function show_more_feedbacks(){
	ob_start();
	global $wpdb;
	$pid = $_POST['pid'];
	$uid = $_POST['uid'];
	$start_from  = $_POST['current'];
	$total_per_load  = $_POST['total_per_load'];
	$total_shown = $start_from+$total_per_load;

	//User Timezone Function
	wpjobster_timezone_change();

	$query_feedback_total = "select distinct *, ratings.datemade datemade from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders,
	".$wpdb->prefix."posts posts where posts.ID=orders.pid AND posts.ID='$pid' AND
	 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' order by datemade desc ";

	$r_feedback_total = $wpdb->get_results($query_feedback_total);
	$r_seller_total = count($r_feedback_total);

	$query = "select distinct *, ratings.datemade datemade from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders,
	".$wpdb->prefix."posts posts where posts.ID=orders.pid AND posts.ID='$pid' AND
	 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' order by datemade desc limit $start_from , $total_per_load";

	$r = $wpdb->get_results($query);

	$cnt = 0;
	foreach($r as $row){
		$cnt++;
		$post = $row->pid;
		$post = get_post($post);
		$user2 = get_userdata($row->uid); ?>

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
		".$wpdb->prefix."posts posts where posts.ID=orders.pid AND posts.ID='$pid' AND
		 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' and orders.id=".$row->orderid." order by datemade desc limit 3";

		$r_seller = $wpdb->get_results($query_seller);
		if($r_seller){
			$row_seller=$r_seller[0];
			$user3 = get_userdata($uid); ?>
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
							<span class="grey-time p10l right"></span>
						</div>
						<div class="cb">
							<p><?php echo stripslashes($row_seller->reason); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		} //if seller rating
	}

	$output['html']=ob_get_contents();
        ob_flush();
	$output['ok']=1;

	if($total_shown >= $r_seller_total){
		$output['current']=0;
	}else{
		$output['current']=$total_shown;
	}

	echo json_encode($output);
	die();
} // end function for displaying Load more feedback

add_action('wp_ajax_nopriv_show_more_feedbacks', 'show_more_feedbacks');
add_action('wp_ajax_show_more_feedbacks', 'show_more_feedbacks');

function wpjobster_show_stars_our_of_number_old($nr){
	$concat = '';
	for ($i = 1; $i <= $nr; $i++) {
		$concat .= ' <i class="star icon full-star-rating"></i>';
	}

	for ($i = ($nr + 1); $i <= 5; $i++) {
		$concat .= ' <i class="empty star icon empty-star-rating"></i>';
	}

	return $concat;
}

function wpjobster_show_stars_our_of_number($x){
	$n = 5;
	$halves = ($x + 0.25) * 2;
	$full = (int) ($halves / 2);
	$half = $halves % 2;

	$full_str = ' <i class="star icon full-star-rating"></i>';
	if( is_rtl() ){
		$half_str = ' <i class="star half empty icon half-star-rating star-rtl-reverse"></i>';
	}else{
		$half_str = ' <i class="star half empty icon half-star-rating"></i>';
	}
	$empty_str = ' <i class="empty star icon empty-star-rating"></i>';

	$stars = '<div class="wpj-star-rating-static">';
	for ($i = 0; $i < $full; $i++) $stars .= $full_str;
	if ($half) $stars .= $half_str;
	for ($i = $full + $half; $i < $n; $i++) $stars .= $empty_str;
	$stars .= '</div>';

	return $stars;
}

function wpjobster_show_big_stars_our_of_number($x){
	$n = 5;
	$halves = ($x + 0.25) * 2;
	$full = (int) ($halves / 2);
	$half = $halves % 2;

	$full_str = ' <i class="star icon full-star-rating"></i>';
	$half_str = ' <i class="star half empty icon half-star-rating"></i>';
	$empty_str = ' <i class="empty star icon empty-star-rating"></i>';
	$stars='';
	for ($i = 0; $i < $full; $i++) $stars .= $full_str;
	if ($half) $stars .= $half_str;
	for ($i = $full + $half; $i < $n; $i++) $stars .= $empty_str;

	return $stars;
}

function wpjobster_get_job_rating($pid){
	global $wpdb;
	$query = "select distinct ratings.grade, ratings.id ratid from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders,
				" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND
				 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.ID='$pid' ";
	$r = $wpdb->get_results($query);
	$total = count($r);
	$good = 0;
	foreach ($r as $row) {
		$good += $row->grade;
	}


	if ($total == 0)        return 0;
	$prc = round($good / $total, 2);
	$xx = round((100 * $prc) / 5);
	return $xx;
}

function wpjobster_get_job_rating_new($pid){
	global $wpdb;
	$query = "select distinct ratings.grade, ratings.id ratid from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders,
				" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND
				 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.ID='$pid' ";
	$r = $wpdb->get_results($query);
	$total = count($r);
	$good = 0;
	foreach ($r as $row) {
		$good += $row->grade;
	}

	if ($total != 0 && wpjobster_get_job_ratings_number( $pid ) >=3){
		$rating = round($good / $total, 2);
		update_post_meta($pid, 'wpj_new_rating', $rating);
	}else{
		$rating = 0;
		update_post_meta($pid, 'wpj_new_rating', $rating);
	}

	return $rating;
}

function wpjobster_get_job_ratings_number($pid){
	global $wpdb;
	$query = "select distinct ratings.grade, ratings.id ratid from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders,
				" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND
				 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.ID='$pid' ";
	$r = $wpdb->get_results($query);
	$total = count($r);
	return $total;
}

function wpjobster_get_seller_rating($uid){
	global $wpdb;
	$query = "select distinct ratings.grade, ratings.id ratid from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders,
				" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND
				 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' ";
	$r = $wpdb->get_results($query);
	$total = count($r);
	$good = 0;
	foreach ($r as $row) {
		$good += $row->grade;
	}


	if ($total == 0)        return 0;
	$prc = round($good / $total, 2);
	$xx = round((100 * $prc) / 5);
	return $xx;
}

function wpjobster_get_avg_rating($uid){
	global $wpdb;
	$query = "select AVG(ratings.grade) from " . $wpdb->prefix . "job_ratings ratings WHERE awarded = 1 AND ratings.uid ='$uid'";
	$r = $wpdb->get_results($query);
	$rating = round($r[0]->{'AVG(ratings.grade)'},0);
	return (int)$rating;
}

function wpjobster_get_seller_ratings_number($uid){

	global $wpdb;
	$query = "select distinct ratings.grade, ratings.id ratid from " . $wpdb->prefix . "job_ratings ratings, " . $wpdb->prefix . "job_orders orders,
				" . $wpdb->prefix . "posts posts where posts.ID=orders.pid AND
				 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid' ";
	$r = $wpdb->get_results($query);
	$total = count($r);
	return $total;
}
