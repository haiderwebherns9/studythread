<?php
$template_url = isset( $_COOKIE['template_url'] ) ? $_COOKIE['template_url'] : '/wp-content/themes/wpjobster';

$spath = $template_url . '/vendor/semantic-ui';
$semanticPath = $template_url . '/vendor/semantic-ui/semantic.less';
?>

@spath : "<?php echo $spath; ?>";

@import "<?php echo $semanticPath; ?>";
