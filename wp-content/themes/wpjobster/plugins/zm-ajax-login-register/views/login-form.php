<?php
/**
 * This is the template for our login form. It should contain as less logic as possible
 */
?>

<!-- Login Form -->
<div class="ajax-login-register-login-container">
	<?php if ( is_user_logged_in() ) {
			echo  '<p>' . printf("%s <a href=%s title='%s'>%s</a>", __('You are already logged in','wpjobster'), wp_logout_url( site_url() ), __('Logout','wpjobster'), __('Logout','wpjobster') ) . '</p>' ;
	} else { ?>
		<div class="header modal-login">
			<?php _e('Login','wpjobster'); ?>
		</div>
		<form action="javascript://" class="ui form ajax-login-default-form-container cf login_form <?php print get_option('ajax_login_register_default_style'); ?>">
			<div class="form-wrapper">
				<?php
				wp_nonce_field( 'facebook-nonce', 'facebook_security' );
				wp_nonce_field( 'login_submit', 'security' );
				?>
				<div class="ajax-login-register-status-container">
					<div class="ajax-login-register-msg-target"></div>
				</div>

				<div class="login-input-wrappers">
					<?php if (get_option('ajax_login_register_user_email') == 'on') { ?>
						<div class="field"><input type="text" name="user_login" id="user_login" placeholder="<?php _e('User Name / Email Address','wpjobster'); ?>" size="30" required autofocus /></div>
					<?php } else { ?>
						<div class="field"><input type="text" name="user_login" id="user_login" placeholder="<?php _e('User Name','wpjobster'); ?>" size="30" required autofocus /></div>
					<?php } ?>

					<div class="field"><input type="password" name="password" placeholder="<?php _e('Password','wpjobster'); ?>" size="30" required /></div>

					<div class="field"><?php wpj_recaptcha_form( 'zm_login' ) ?></div>

					<div class="button-container">
						<button class="ui fluid button login_button green" type="submit" name="submit">
							<?php _e('Login','wpjobster'); ?>
						</button>
					</div>
				</div>

				<?php
				$keep_logged_in = get_option('ajax_login_register_keep_me_logged_in');
				if ( $keep_logged_in != "on") : ?>
					<div class="ui checkbox left">
						<input type="checkbox" name="rememberme" />
						<label><?php _e('Keep me logged in','wpjobster'); ?></label>
					</div>
				<?php endif; ?>
				<span class="meta right"><a href="#" class="forgot-password-handle" title="<?php _e('Forgot Password','wpjobster' ); ?>"><?php _e('Forgot Password','wpjobster'); ?></a></span>

				<div class="divider">
					<span><?php _e("or", 'wpjobster'); ?></span>
				</div>

				<?php do_action( 'wordpress_social_login' ); ?>

				<div class="field"><a href="#" class="not-a-member-handle"><?php echo apply_filters( 'ajax_login_not_a_member_text', __('Are you a member?','wpjobster') ); ?></a></div>
			</div>
		</form>
	<?php } ?>
</div>
<!-- End Login Form -->
