<?php

$scriptPath = dirname(__FILE__);
$path = realpath($scriptPath . '/./');
$filepath = explode("wp-content", $path);

define('WP_USE_THEMES', false);
require(''.$filepath[0].'/wp-load.php');

global $wpdb;
$uid = $current_user->ID;
if(isset($_GET['notification'])){
//echo $uid;
$nid=$_GET['notification'];
}
$wpdb->update(
    'wp_87fsrr_notification',
    array(
        'ntf_read' =>0 
    ),
    array( 'notif_id' => $nid ),
    array(
        '%d'    
    ),
    array( '%d' )
);
//print_r($notif_data);
?>