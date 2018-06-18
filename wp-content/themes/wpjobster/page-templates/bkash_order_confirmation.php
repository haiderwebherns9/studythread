<?php get_header();
/*
Template Name: Bkash Order Confirmation
*/
?>
<?php
$wpjobster_adv_code_single_page_above_content = stripslashes(get_option('wpjobster_adv_code_single_page_above_content'));
if(!empty($wpjobster_adv_code_single_page_above_content)):
	echo '<div class="full_width_a_div">';
	echo $wpjobster_adv_code_single_page_above_content;
	echo '</div>';
endif;
?>
<div id="content">
<div class="ui segment">
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
  if(isset($_SESSION['notif'])) {
	 if($_SESSION['notif']['teacher_id']!=''){
		 $teacher_msg="Hired You, Please schedule the classes after the Bkash Payment have made to you." ;
		 $qry=$wpdb->query( $wpdb->prepare(
				"INSERT INTO `wp_87fsrr_notification` (`user_id`,`ntf_read`,`ntf_msg`) VALUES ( %d, %d, %s )",
				array(
					$_SESSION['notif']['teacher_id'],
					0,
					$teacher_msg
				)
			));
	    }
		if($_SESSION['notif']['student_id']!=''){
		 $stud_msg="Hey Thank you for selecting the time for the teacher ".$_SESSION['notif']['teacher_fname'] .' '.$_SESSION['notif']['teacher_lname'].", Your Classes will be scheduled after the Bkash Payment has been made to the Teacher." ;
		 $qry=$wpdb->query( $wpdb->prepare(
				"INSERT INTO `wp_87fsrr_notification` (`user_id`,`ntf_read`,`ntf_msg`) VALUES ( %d, %d, %s )",
				array(
					$_SESSION['notif']['student_id'],
					0,
					$stud_msg
				)
			));
	    }
	
 }

?>
<?php
if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
			<h1 class="page_title"><?php the_title() ?></h1>
			<?php the_content(); ?>
	<?php endwhile; ?>
<?php endif; ?>
 </div>
 </div>
<?php get_footer(); ?>