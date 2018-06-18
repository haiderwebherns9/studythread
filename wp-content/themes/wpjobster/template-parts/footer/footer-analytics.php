<?php
$wpjobster_enable_google_analytics = get_option('wpjobster_enable_google_analytics');
if($wpjobster_enable_google_analytics == "yes"):
	echo stripslashes(get_option('wpjobster_analytics_code'));
endif;

$wpjobster_enable_other_tracking = get_option('wpjobster_enable_other_tracking');
if($wpjobster_enable_other_tracking == "yes"):
	echo stripslashes(get_option('wpjobster_other_tracking_code'));
endif;
?>
