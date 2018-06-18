<?php
/**
 * This is the template for our register form. It should contain as less logic as possible
 */
?>

<!-- Forgot -->
<?php if ( is_user_logged_in() ) { ?>
	<p><?php printf('%s <a href="%s" title="%s">%s</a>', __('You are already registered','wpjobster'), wp_logout_url( site_url() ), __('Logout', 'wpjobster'), __('Logout', 'wpjobster') ); ?></p>
<?php } else { ?>
	<div class="header modal-login">
		<?php _e('Forgot Password','wpjobster'); ?>
	</div>
	<form action="javascript://" name="forgotform" class="ui form ajax-login-default-form-container forgot_form <?php print get_option('ajax_login_register_default_style'); ?>">

		<div class="form-wrapper forgot">
			<?php
			wp_nonce_field( 'facebook-nonce', 'facebook_security' );
			wp_nonce_field( 'forgot_submit', 'security' );
			?>
			<div class="ajax-login-register-status-container">
				<div class="ajax-login-register-msg-target"></div>
			</div>

			<div class="field"><input type="text" required name="email" id="email" placeholder="<?php _e('Email', 'wpjobster'); ?>" class="user_email ajax-login-register-validate-email-exists" autofocus /></div>

			<div class="button-container">
				<button class="ui fluid button forgot_button green" type="submit" name="forgot" disabled>
					<?php _e('Reset Password','wpjobster'); ?>
				</button>
			</div>

			<div class="field"><a href="#" class="already-registered-handle"><?php echo apply_filters( 'ajax_login_register_already_registered_text', __('Back to Login','wpjobster') ); ?></a></div>
		</div>
	</form>
<?php } ?>

<!-- End Forgot -->
