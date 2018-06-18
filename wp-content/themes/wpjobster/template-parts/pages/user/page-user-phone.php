<?php get_header();
$vars = wpj_user_phone_vars(); ?>

<div class="cf main">
	<div class="static-login-box">
		<div class="white-cnt heading-cnt">
		<h1 class="heading-title"><?php echo $vars['title']; ?></h1>
		</div>
		<div class="p30b">
			<div class="white-cnt padding-cnt">

				<?php if(is_user_logged_in()) { ?>
					<div class="main-margin cf">
						<?php echo $vars['message']; ?>
					</div>

					<div class="cf">

						<?php if ($vars['status'] == "resent") { ?>

							<form method="post" enctype="multipart/form-data" action="<?php echo get_bloginfo('url') . '/?jb_action=verify_phone&resend=true'; ?>">
								<input type="text" name="phone_key" class="main-margin" />
								<input type="submit" name="submit-key" value="<?php _e("Submit", "wpjobster"); ?>" />
							</form>

						<?php } else { ?>

							<a class="btn bigger w100 login-link" href="<?php echo $vars['my_account_url']; ?>"><?php echo __("My Account","wpjobster"); ?></a>

						<?php } ?>
					</div>

				<?php } else { ?>

					<a class="btn bigger w100 login-link" href="<?php echo get_bloginfo('url').'/wp-login.php?redirect_to=' . urlencode( get_permalink() ); ?>"><?php echo __("Login","wpjobster"); ?></a>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
