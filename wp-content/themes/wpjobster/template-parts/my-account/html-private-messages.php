<?php
//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//------------------------------------------------
if( ! function_exists( 'wpjobster_my_account_priv_mess_area_function' ) ) {
	function wpjobster_my_account_priv_mess_area_function() {

		ob_start();

		$otheruid = '';
		$myuid = '';
		$vars = wpj_private_messages_vars();
		$myuid = $vars['myuid'];
		$wpdb = $vars['wpdb'];
		$privurl_m = $vars['privurl_m'];

		wpjobster_add_chatbox_scripts();

		if( isset($_GET['custom-offer']) && $_GET['custom-offer'] == ""){ ?>
			<script>
				jQuery(function(){
					jQuery('.offer-link').click();
				});
			</script>
		<?php } ?>

		<div id="content-full-ov">
			<div <?php if( isset( $_GET['username'] ) || isset( $_GET['user_id'] ) ){ echo 'id=""'; }else{ echo 'id=""'; } ?>>

				<?php
				//conversation page
				if (isset($_GET['username']) || isset($_GET['user_id'])) {

					$vars = wpj_private_messages_avatar_username($myuid, $wpdb);
					$otheruid = $vars['otheruid'];
					$r = $vars['r'];
					$user = $vars['user'];
					$user_avatar = $vars['user_avatar'];
					$archive_class = $vars['archive_class'];
					$unarchive_class = $vars['unarchive_class'];

					?>

					<div class="ui basic notpadded segment">
						<div class="ui two column stackable grid">
							<div class="message-user-avatar">
								<?php echo $user_avatar; ?>
							</div>

							<div class="conversation-title">
								<h1>
									<?php echo __("Conversation with", "wpjobster") . ' ' . (is_object($user)&& isset($user->user_login)?$user->user_login:__("Deleted User",'wpjobster')); ?>
									<?php
										$u_id = $user->ID;
										include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );
									?>
								</h1>
							</div>
						</div>
					</div>


						<div class="ui segment">
							<a class="pm-nav-btn pm-nav-inbox tooltip" href="<?php echo $privurl_m; ?>">
								<div class="ei-cnt ei-small ei-envelope-modern-icon">
									<i class="mail icon"></i>
								</div>
								<span><?php _e("Inbox", "wpjobster"); ?></span>
							</a>

							<a class="pm-nav-btn pm-nav-archive tooltip" href="<?php echo $privurl_m; ?>?type=archived">
								<div class="ei-cnt ei-small ei-archive-icon">
									<i class="file archive outline icon"></i>
								</div>
								<span><?php _e("Archived", "wpjobster"); ?></span>
							</a>

							<a class="pm-nav-btn pm-action-archive tooltip <?php echo $archive_class; ?>" href="<?php echo $privurl_m.'?username='.$user->user_nicename.'&archive_conversation=1'; ?>">
								<div class="ei-cnt ei-small ei-archive-in-icon">
									<i class="download icon"></i>
								</div>
								<span><?php _e("Archive Conversation", "wpjobster"); ?></span>
							</a>

							<a class="pm-nav-btn pm-action-archive pm-action-unarchive tooltip <?php echo $unarchive_class; ?>" href="<?php echo $privurl_m.'?username='.$user->user_nicename.'&unarchive_conversation=1'; ?>">
								<div class="ei-cnt ei-small ei-archive-out-icon">
									<i class="upload icon"></i>
								</div>
								<span><?php _e("Unarchive Conversation", "wpjobster"); ?></span>
							</a>

							<a class="pm-nav-btn pm-action-delete tooltip" href="#">
								<div class="ei-cnt ei-small ei-trash-icon">
									<i class="trash icon"></i>
								</div>
								<span><?php _e("Delete Conversation", "wpjobster"); ?></span>
							</a>

							<div class="pm-confirmation pm-action-delete-confirmation" style="display:none;">
							<?php _e("Are you sure?", "wpjobster"); ?>
							<a class="pm-action-confirm-delete" href="<?php echo $privurl_m.'?username='.$user->user_nicename.'&delete_conversation=1'; ?>"><span><?php _e("Delete", "wpjobster"); ?></span></a> /
							<span class="pm-action-deny-delete"><?php _e("Cancel", "wpjobster"); ?></span>
							</div>
						</div>

						<script>
						jQuery( document ).ready(function($) {
							// ajax for load more
							$(".pm-ajax-trigger").click(function(){
								var at = Math.ceil($('.pm-holder').length / 10) + 1;
								url = document.URL + "&ajax=true&pg=" + at;

								$.ajax({
									url: url,
									beforeSend:function(){
										$('.pm-ajax-trigger').addClass('loading');
									},
									success:function(data){
										var pm_count = (data.split("pm-holder").length) - 1;
										var new_data = $(data).find(".pm-list-ajax").html();
										$(new_data).hide().prependTo('.pm-list').slideDown(400);
										$('.pm-ajax-trigger').removeClass('loading');
										if (pm_count < 10) {
											jQuery(".pm-ajax-trigger-container").slideUp(400);
										}
									}
								});
							});

							// ajax for delete
							$(document).on("click", ".pm-delete-ok", function(event){
								event.preventDefault();
								url = $(this).attr('href');
								current_pm = $(this).parents(".pm-holder");

								$.ajax({
									url: url,
									type: "POST",
									data: { delete_pm: "1" },
									beforeSend:function(){ },
									success:function(data){
										$(current_pm).fadeOut(400, function() { $(this).remove(); });

										if($(".private-message-from-user div").length == 9){
											$(".private-message-from-user").html("<div class='ui basic segment error-no-messages'><?php _e('No messages here.','wpjobster'); ?></div>");
										}
									}
								});
							});

							// ajax for delete all from single
							$(document).on("click", ".pm-action-confirm-delete", function(event){
								event.preventDefault();
								url = $(this).attr('href');
								inbox_url = $(".pm-nav-inbox").attr('href');

								$.ajax({
									url: url,
									type: "POST",
									data: { action_button: "1" },
									beforeSend:function(){ },
									success:function(data){
										$(".pm-holder").hide(400);
										$(".pm-ajax-trigger-container").hide(400);
										window.location.href = inbox_url;
									}
								});
							});


							// ajax for archive, unarchive from single
							$(document).on("click", ".pm-action-archive", function(event){
								event.preventDefault();
								url = $(this).attr('href');

								$.ajax({
									url: url,
									type: "POST",
									data: { action_button: "1" },
									beforeSend:function(){ },
									success:function(data){
										$(".pm-action-archive").toggleClass("pm-action-inactive");
									}
								});
							});


							$(window).ready(function(){

								// expand delete confirmation
								$('html').click(function() {
									$(".pm-action-delete-confirmation").css("display","none");
								});

								$('.pm-action-delete').click(function(event){
									if ($(".pm-action-delete-confirmation").css("display") == "inline-block") {
										$(".pm-action-delete-confirmation").css("display","none");
										event.preventDefault();
										event.stopPropagation();
									}
									else {
										$(".pm-action-delete-confirmation").css("display","inline-block");
										event.preventDefault();
										event.stopPropagation();
									}
								});


								// expand delete confirmation on single list
								$('html').click(function() {
									$(".pm-delete-confirm").css("display","none");
								});

								$(document).on("click", ".pm-delete", function(event){
									if ($(this).parent().children(".pm-delete-confirm").css("display") == "inline-block") {
										$(this).parent().children(".pm-delete-confirm").css("display","none");
										event.preventDefault();
										event.stopPropagation();
									}
									else {
										$(this).parent().children(".pm-delete-confirm").css("display","inline-block");
										event.preventDefault();
										event.stopPropagation();
									}
								});

							});

						});

						</script>

					<div class="ui segments">

						<?php
						if(count($r) > 0) {
							if(count($r) >= 10) {
								echo '<div class="ui segment pm-ajax-trigger-container center cf"><div class="ui white button pm-ajax-trigger">'.__("Load Older Messages", "wpjobster").'</div></div>';
							}
							?>


							<script type="text/javascript">
								$(window).ready(function(){

									// scroll to last message
									$('html, body').animate({
										scrollTop: $(".pm-holder").last().offset().top - 94
									}, 1000);
								});
							</script>


							<?php
							echo '<div class="pm-list ui basic notpadded segment">';
								echo '<div class="private-message-from-user">';

									wpjobster_pm_loop($r, $myuid, $otheruid);

								echo '</div>';
							echo '</div>';

						} else {
							echo '<div class="pm-list ui basic notpadded segment">';
								echo '<div class="private-message-from-user">';
									echo '<div class="ui basic segment error-no-messages">';

										_e('No messages here.','wpjobster');

									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
						?>
					</div>

					<?php if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != "") {
						echo '<div class="error">'.$_SESSION['error_message'].'</div>';
						$_SESSION['error_message'] = "";
					} ?>
						<?php if(is_object($user) && isset($user->user_login)){?>
						<div class="">
							<div class="ui segment responsive-pm send-my-pm">
								<div class="ui two column stackable grid wrapper-pm-to-user">
									<div class="eight wide column send-message-private">
										<span><?php echo __("Send a message to", "wpjobster") . " " . $user->user_login; ?></span>
										<?php
											//// start user online status
											$u_id = $user->ID;
											include ( locate_template( 'template-parts/pages/user/page-user-status.php' ) );
											//// end user online status
										?>
									</div>
									<div class="eight wide column">
										<?php

										$display_custom_offer_button = apply_filters( 'display_or_hide_section_filter', true );
										if ( $display_custom_offer_button == true && get_option( 'wpjobster_enable_custom_offers' ) != 'no' ) {
											$active_job_required = get_option( 'wpjobster_active_job_cutom_offer' );
											if($active_job_required == 'yes'){
												if (wpjobster_nr_active_jobs($myuid) > 0) { ?>
													<div class="right private-message-send-custom-offer">
														<a href="" class="ui button lightgrey_btn open-custom-request-received-modal"><?php _e("Send Custom Offer", "wpjobster"); ?></a>
													</div>
												<?php }else{ ?>
													<div class="right private-message-send-custom-offer">
														<a data-requestid="<?php echo $u_id; ?>" href="" class="ui button btn_inactive grey_btn open-modal-request-error"><?php _e("Send Custom Offer", "wpjobster"); ?></a>
													</div>
													<?php wpj_send_customer_offer_request_error( $u_id );
												}
											}else{ ?>
												<div class="right private-message-send-custom-offer">
													<a href="" class="ui button lightgrey_btn open-custom-request-received-modal"><?php _e("Send Custom Offer", "wpjobster"); ?></a>
												</div>
											<?php } ?>
										<?php } ?>
									</div>

									<?php wpj_send_custom_offer_modal( $otheruid ); ?>

									<div class="wrapper-pm-errors"></div>

									<div class="sixteen wide column form-container-pm">
										<form method="post" class="ui form">
											<?php $_SESSION['formrandomid'] = md5(rand(0,10000000)); ?>
											<input type="hidden" name="formrandomid" value="<?php echo $_SESSION['formrandomid']; ?>" />
											<input type="hidden" id="pm_current_read_user_id" value="<?php echo $_GET['username']; ?>" />

											<div class="field">
												<textarea class="grey_input resizevertical charlimit-message cmi-listen" id="message-pm-user" name="message" rows="6" cols="50"><?php if( isset( $_POST['message'] ) ) { echo $_POST['message']; } ?></textarea>
												<span class="charscounter"> <?php echo __( 'characters left.', 'wpjobster' ); ?></span>
											</div>


											<div class="fields">
												<div class="four wide field margin-auto-top-bottom">
													<?php wpjobster_theme_attachments_uploader_html5($secure=1,"file_upload_pm_attachments", "hidden_files_pm_attachments", "private_messages"); ?>
												</div>
												<div class="eight wide field margin-auto-top-bottom"></div>
												<div class="four wide field">
													<div class="field private-message-send-message">
														<input class="ui button right submit-private-message" data-otheruid="<?php echo $otheruid; ?>" type="submit" name="send" value="<?php _e('Send','wpjobster'); ?>" />
													</div>
												</div>
											</div>

										</form>
									</div>

								</div>

								<?php $wpjobster_characters_message_max = get_option("wpjobster_characters_message_max"); ?>

								<script>
									jQuery(document).ready(function($) {
										jQuery(".charlimit-message").counted({count:<?php echo $wpjobster_characters_message_max;?>});
									});
								</script>
							</div>
						</div>
						<div class="ui hidden divider"></div>
						<?php }// endif user is_object ?>

				<?php wpj_send_customer_offer_request_received( $otheruid );

				// conversations list
				} else {

					if (isset($_GET['type'] ) && $_GET['type'] == 'archived') {

						$r = wpj_private_messages_select_db1();

						?>
						<div class="ui basic notpadded segment">
							<h1 class="ui header wpj-title-icon">
								<i class="file archive outline icon"></i>
								<?php echo __("Messages Archive", "wpjobster"); ?>
							</h1>
						</div>
					<?php
					} else {

						$r = wpj_private_messages_select_db2();

						?>

						<div class="ui basic notpadded segment">
							<h1 class="ui header wpj-title-icon">
								<i class="mail icon"></i>
								<?php echo __("Inbox", "wpjobster"); ?>
							</h1>
						</div>
					<?php } ?>

					<div class="ui segment">
						<a class="pm-nav-btn pm-nav-inbox tooltip" href="<?php echo $privurl_m; ?>">
							<div class="ei-cnt ei-small ei-envelope-modern-icon message">
								<i class="mail icon inbox-messages"></i>
							</div>
							<span><?php _e("Inbox", "wpjobster"); ?></span>
						</a>
						<a class="pm-nav-btn pm-nav-archive tooltip" href="<?php echo $privurl_m; ?>?type=archived">
							<div class="ei-cnt ei-small ei-archive-icon archive">
								<i class="file archive outline icon archived-messages"></i>
							</div>
							<span><?php _e("Archived", "wpjobster"); ?></span>
						</a>
					</div>

					<div class="ui segments" id="user_messages">
						<?php wpj_private_messages_return_messages($r, $privurl_m); ?>
					</div>

					<script>

						// ajax for delete, archive, unarchive conversations
						$(document).on("click", ".action-listener", function(event){
							event.preventDefault();
							url = $(this).attr('href');
							current_pm = $(this).parents(".pm-holder");

							$.ajax({
							  url: url,
							  type: "POST",
							  data: { action_button: "1" },
							  beforeSend:function(){

							  },
							  success:function(data){
								$(current_pm).hide(400);
							  }
							});
						});



						$(window).ready(function(){
							// expand delete confirmation on list
							$('html').click(function() {
								$(".action-confirm").css("display","none");
							});

							$('.action-confirm-request').click(function(event){
								if ($(".action-confirm").css("display") == "inline-block") {
									$(".action-confirm").css("display","none");
									event.preventDefault();
									event.stopPropagation();
								}
								else {
									$(".action-confirm").css("display","inline-block");
									event.preventDefault();
									event.stopPropagation();
								}
							});
						});

					</script>


				<?php
				}
				// end conversations list

				?>
			</div>
		</div>

		<?php wpj_pm_support_file( $otheruid, $myuid );

		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}
?>
