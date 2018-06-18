<?php $vars = wpj_loader_page_vars(); ?>
<div id="loader" style="position:relative; width:100%; height:100%;"><img style="position:absolute; left:50%; top:50%; margin-left:-50px; margin-top:-50px;" src="<?php echo get_bloginfo('siteurl');?>/wp-content/themes/wpjobster/images/ajax-loader.gif" alt="Loading..."></div>
<script>
	setTimeout(sendTotransaction,0);

	function sendTotransaction() {
		window.location='<?php echo $vars['location']; ?>';
	}
</script>
