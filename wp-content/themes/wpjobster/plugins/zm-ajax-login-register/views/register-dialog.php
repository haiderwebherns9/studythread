<?php
/**
 * Markup needed for jQuery UI dialog, our form is actually loaded via AJAX
 */
?>

<div id="ajax-login-register-dialog" class="ui modal register smaller" title="<?php _e('Register', 'wpjobster'); ?>" data-security="<?php print wp_create_nonce( 'register_form' ); ?>" style="display: none;">
	<i class="close icon"></i>
	<div id="ajax-login-register-target" class="content-register-form ajax-login-register-dialog"><?php _e('Loading...','wpjobster'); ?></div>
</div>
