<?php
/**
 * Markup needed for jQuery UI dialog, our form is actually loaded via AJAX
 */
?>

<div id="ajax-login-register-forgot-dialog" class="ui modal forgot smaller" title="<?php _e('Forgot Password', 'wpjobster'); ?>" data-security="<?php print wp_create_nonce( 'forgot_form' ); ?>" style="display: none;">
	<i class="close icon"></i>
	<div id="ajax-login-register-forgot-target" class="content-forgot-form ajax-login-register-forgot-dialog"><?php _e('Loading...','wpjobster'); ?></div>
</div>
