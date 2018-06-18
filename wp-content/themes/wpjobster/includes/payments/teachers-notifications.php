<?php

$scriptPath = dirname(__FILE__);
$path = realpath($scriptPath . '/./');
$filepath = explode("wp-content", $path);

define('WP_USE_THEMES', false);
require(''.$filepath[0].'/wp-load.php');
global $wpdb;
$uid = $current_user->ID;
//echo $uid;
$query = "
    SELECT *
    FROM `wp_87fsrr_notification`
    WHERE `user_id` = '".$uid."' AND `created_at` >= DATE_SUB(CURDATE(), INTERVAL 10 DAY) order by `notif_id` desc";
$notif_data=$wpdb->get_results($query);
//print_r($notif_data);
?>
<div class="antiscroll-wrap">
	<div class="antiscroll-inner">
	       <ul>
		         <?php foreach($notif_data as $val) { ?>
		        <li>
			      <a href="javascript:void(0);" alt="<?php echo $val->notif_id; ?>">
				  <?php echo $val->ntf_msg;?>
				  </a>
				</li>
				 <?php } ?>  
		   </ul>
	</div>
</div>