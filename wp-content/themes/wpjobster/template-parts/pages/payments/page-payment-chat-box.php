<?php get_header();

$chat_box = new WPJobsterChatBox();

$vars = $chat_box->chat_box_init();
foreach ($vars as $key => $value) {
	$$key = $value;
}

wpjobster_add_uploadifive_scripts();
wpjobster_add_chatbox_scripts(); ?>

<?php 
global $wpdb;
 if(isset($_SESSION['student'])) { 
   //print_r($_SESSION['student']);
   $booking_slot=$_SESSION['student']['book_slot'];
   //print_r($booking_slot);
    //exit;
	foreach($booking_slot as $val_slot){
   $qry=$wpdb->query( $wpdb->prepare(
				"INSERT INTO `wp_87fsrr_calendar_stud` (`steacher_id`,`student_id`,`job_id`,`scal_time`, `scal_date`,`scal_time_zone`,`cal_day`) VALUES ( %d, %d, %d, %s, %s, %s, %s )",
				array(
					$_SESSION['student']['teacher_id'],
					$_SESSION['student']['student_id'],
					$_SESSION['student']['job_id'],
					trim($val_slot),
					$_SESSION['student']['book_date'],
					$_SESSION['student']['book_tzone'],
					$_SESSION['student']['book_day']
				)
			));
	   }
			session_destroy ();
			if($qry){
				echo '<h2 style="color:green">Time slot has been booked successfully.</h2>';
			}
}

?>
<div class="ui divider hidden"></div>

<div id="payment_status" style="display:none;"></div><!-- Do not delete this div. Used by Ajax -->
<div id="order_status" style="display:none;"></div><!-- Do not delete this div. Used by Ajax -->
<div id="times_up" style="display:none;"></div><!-- Do not delete this div. Used by Ajax -->
<div id="order_cancellation" style="display:none;"></div><!-- Do not delete this div. Used by Ajax -->

<div class="content-full-ov">
	<!-- CURRENT ORDER STATUS DETAILS -->
	<div id="all_chatbox_messages">
	<?php $arg = $chat_box->chat_box_current_order_status_details(); ?>
	</div>

	<div id="chat_box_message_form">
	<?php
		if( $current_order->payment_status != 'pending' && $current_order->payment_status != 'failed' && $current_order->payment_status != 'processing' && $current_order->payment_status != 'cancelled' ){

			// MESSAGE FORM
			echo $chat_box->chat_box_send_message_form();
			// END MESSAGE FORM

		}
	?>
	</div>
	<!-- END CURRENT ORDER STATUS DETAILS -->

	<?php do_action( 'wpjobster_run_on_transaction_page', $current_order ); ?>

</div>

<?php if(!is_user_logged_in()) { wp_redirect(wp_login_url(get_current_page_url())); exit; }

do_action('wpjobster_transaction_page',20,30,40); ?>

<div class="ui divider hidden"></div>

<script>
$(window).ready(function(){
	// scroll to last message
	$('html, body').animate({
		scrollTop: $(".chatbox_post").last().offset().top - 89
	}, 1000);
});
</script>

<?php

get_footer();

?>
