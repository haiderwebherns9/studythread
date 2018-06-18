<?php get_header();
/*
Template Name: My Schedule
*/
?>
<?php 
global $wpdb;
if(isset($_GET['usr_id'])){
$query = "
    SELECT *
    FROM `wp_87fsrr_notification`
    WHERE `user_id` ='".$_GET['usr_id']."'
	ORDER BY `notif_id` DESC
	";	
$notif_data=$wpdb->get_results($query);
//print_r($notif_data);
?>
<div id="content-full-ov">
<h2>My Schedule</h2>
  <ul class="notific_ul">
  <?php foreach ($notif_data as $val){ ?>
       <li><?php echo $val->ntf_msg;?></li>
  <?php } ?>
  </ul>
<?php } else { ?>
   <h1 class="ui header"> Record Not Found</h1> 
<?php } ?>
</div>
<div class="ui hidden divider"></div>

<?php get_footer(); ?>
