<?php
/**
 * Markup needed for jQuery UI dialog, our form is actually loaded via AJAX
 */
?>

<div id="ajax-login-register-login-dialog" class="ui modal login smaller" title="<?php _e('Login','wpjobster'); ?>" data-security="<?php print wp_create_nonce( 'login_form' ); ?>" style="display:none;">
	<i class="close icon"></i>
	<div id="ajax-login-register-login-target" class="content-login-form ajax-login-register-login-dialog"><?php _e('Loading...','wpjobster'); ?></div>
</div>
