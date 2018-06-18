<?php
if ( ! function_exists( 'wpj_sng_req_desc' ) ) {
	function wpj_sng_req_desc(){

		$author = get_the_author();
		$post = get_post(get_the_ID());
		$auth = $post->post_author;
		$author_url = wpjobster_get_user_profile_link($author);
		$wpjobster_request_lets_meet = get_option('wpjobster_request_lets_meet');
		$lets_meet = get_post_meta( get_the_ID(), 'request_lets_meet', true );
		$wpjobster_request_date_display_condition = get_option('wpjobster_request_date_display_condition');
		$wpjobster_request_location_display_map = get_option('wpjobster_request_location_display_map');
		?>

		<div class="ui three column stackable grid single-req-wrapper">
			<div class="three wide column">
				<a class="user-link" href="<?php echo $author_url; ?>">
					<img class="round-avatar" width="100" height="100" border="0" src="<?php echo wpjobster_get_avatar($auth,46,46); ?>" />
					<?php echo $author; ?>
				</a>
			</div>

			<div class="nine wide column">
				<div class="desc-wrapper">
					<?php if ( $wpjobster_request_lets_meet && $lets_meet ) { ?>
						<span class="lets-meet lets-meet-request" data-tooltip="<?php _e( "Let's meet", "wpjobster"); ?>" data-position="top right" data-inverted="">
							<img src="<?php echo get_template_directory_uri() . '/images/shake-icon.png'; ?>" alt="lets-meet">
						</span>
					<?php }

					the_content();

					$budget_from = get_post_meta(get_the_ID(), 'budget_from', true);
					$budget_from = ($budget_from) ? $budget_from : 0;
					$budget_to = get_post_meta(get_the_ID(), 'budget', true);
					$max_deliv = get_post_meta(get_the_ID(), 'job_delivery', true);
					$deadline = get_post_meta(get_the_ID(), 'request_deadline', true);
					$req_attachments = get_post_meta(get_the_ID(), 'req_attachments', true);

					$pid = get_the_ID();
					$request_tags = '';
					$t = wp_get_post_tags($pid);
					$i = 0;
					$i_separator = '';

					foreach($t as $tag)
					{
						$request_tags .= $i_separator . $tag->name;
						$i++;
						if ($i > 0) { $i_separator = ', '; }
					}

					$days_plural = sprintf( _n( '%d day', '%d days', $max_deliv, 'wpjobster' ), $max_deliv );
					echo '<div class="single-request-content">';
						if ( $budget_to ) {
							echo '<div>' . __( 'Budget', 'wpjobster' ) . ': ' . wpjobster_get_show_price( $budget_from) . ' - ' . wpjobster_get_show_price( $budget_to ) . '</div>';
						}

						if ( $max_deliv ) {
							echo '<div>' . __( 'Expected delivery', 'wpjobster' ) . ': ' . $days_plural . '</div>';
						}

						if ( $deadline ) {
							echo '<div>' . __( 'Deadline', 'wpjobster' ) . ': ' . date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $deadline) . '</div>';
						}

						if ( $request_tags ) {
							echo '<div>' . __( 'Tags', 'wpjobster' ) . ': ' . $request_tags . '</div>';
						}

						if ($wpjobster_request_date_display_condition == "always" || $wpjobster_request_date_display_condition == "ifchecked") {
							$request_start_date = get_post_meta(get_the_ID(), 'request_start_date', true);
							if ($request_start_date) {
								echo '<div>' . __('Start Date', 'wpjobster') . ': ' . date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $request_start_date) . '</div>';
							}
							$request_end_date = get_post_meta(get_the_ID(), 'request_end_date', true);
							if ($request_end_date) {
								echo '<div>' . __('End Date', 'wpjobster') . ': ' . date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $request_end_date) . '</div>';
							}
						}

						if ($wpjobster_request_location_display_map == 'yes') {
							$request_address = get_post_meta(get_the_ID(), 'request_location_input', true);
							if ($request_address != '') {
								echo '<div>' . __('Location', 'wpjobster') . ': ' . $request_address . '</div>';
								echo '<div class="request-map" data-address="' . $request_address . '"></div>'; ?>

								<iframe class='request-google-map' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?q=<?php echo $request_address; ?>&output=embed'></iframe>
							<?php }
						}
					echo "</div>";

					if ($req_attachments) {
						$attachments = explode(",", $req_attachments);
						if(array_filter($attachments)) {
							echo '<div class="pm-attachments"><div class="pm-attachments-title">';
							_e("Attachments", "wpjobster");
							echo '</div>';
							foreach ($attachments as $attachment) {
								if($attachment != ''){
									echo '<div class="pm-attachment-rtl"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
									echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div><br>';
								}
							}
							echo '</div>';
						}
					}

					if ( get_the_term_list( get_the_ID(), 'request_cat') ) { ?>
						<div class="request-cat cf p5t">
							<?php echo __("Posted in","wpjobster") . " " . get_the_term_list( get_the_ID(), 'request_cat', '', ', ', '' ); ?>
						</div>
					<?php } ?>

					<div class="extra content">
						<i class="calendar icon"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
					</div>

					<?php wpj_sng_req_custom_offer(); ?>

				</div>
			</div>

			<div class="four wide column">
				<div class="request-btns">
					<?php echo wpj_sng_req_button(); ?>
				</div>
			</div>
		</div>

	<?php }
}

if ( ! function_exists( 'wpj_sng_req_custom_offer' ) ) {
	function wpj_sng_req_custom_offer(){
		global $post, $wpdb;

		$myuid = $otheruid = 0;
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;
		}

		wpj_pm_support_file( $otheruid, $myuid );
		pm_buttons_action();

		if(is_user_logged_in()){
			$s = "SELECT * FROM "
				. "(SELECT * FROM ".$wpdb->prefix."job_pm "
				. "WHERE associate_request_id='".get_the_ID()."' and ((user='{$myuid}' AND show_to_destination = '1') or (initiator='{$myuid}'  AND show_to_source = '1')) "
				. "ORDER BY datemade DESC ) AS t1 ORDER BY datemade ASC";
			$r = $wpdb->get_results($s);

			if(count($r) > 0 ) {
				$show_heading = 0;
				?>
				<script>
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
						beforeSend:function(){

						},
						success:function(data){
							$(current_pm).hide(400);
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
						beforeSend:function(){

						},
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
						beforeSend:function(){

						},
						success:function(data){
							$(".pm-action-archive").toggleClass("pm-action-inactive");
						}
					});
				});
				$(window).ready(function(){

					// scroll to last message
					$('html, body').animate({
						scrollTop: $(".pm-holder").last().offset().top - 94
					}, 2000);

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

				</script>
				<?php
				echo '<div class="pm-list sng-req-cs-content">';
					if ( isset( $show_heading ) ) {
						$show_heading++;
					} else {
						$show_heading = '';
					}
					wpjobster_pm_loop($r, $myuid, $otheruid,$show_heading);
				echo '</div>';
			}
		}
	}
}

if ( ! function_exists( 'wpj_sng_req_button' ) ) {
	function wpj_sng_req_button(){
		global $post, $wpdb;

		$myuid = $otheruid = 0;
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;
		}

		if(is_user_logged_in()){

			$display_custom_offer_button = apply_filters( 'display_or_hide_section_filter', true );
			if ( $myuid != $post->post_author && $display_custom_offer_button == 'true' &&  get_option( 'wpjobster_enable_custom_offers' ) != 'no' ) {
				echo "<div class='padding-cnt'>";
					$active_job_required = get_option( 'wpjobster_active_job_cutom_offer' );
					if (get_current_user_id() != 0 && $active_job_required == 'yes' && wpjobster_nr_active_jobs(get_current_user_id()) < 1) { ?>
							<span data-requestid="<?php echo get_the_ID(); ?>" class="ui button db btn_inactive grey_btn open-modal-request-error ellipsis"><?php _e("Send Custom Offer", "wpjobster"); ?></span>
							<?php wpj_send_customer_offer_request_error( get_the_ID() ); ?>
					<?php } else { ?>
						<a href="" style="margin:0; width:100%;" class="ui primary button <?php echo is_user_logged_in() ? 'open-single-req-offer' : 'login-link'; ?>" data-user="<?php echo $post->post_author ?>" data-requestid="<?php echo get_the_ID(); ?>"><?php _e("Send Custom Offer", "wpjobster"); ?></a>

						<?php wpj_single_req_custom_offer_modal(); ?>

					<?php }
				echo "</div>";
			}

			$s = "SELECT * FROM "
				. "(SELECT * FROM ".$wpdb->prefix."job_pm "
				. "WHERE associate_request_id='".get_the_ID()."' and ((user='{$myuid}' AND show_to_destination = '1') or (initiator='{$myuid}'  AND show_to_source = '1')) "
				. "ORDER BY datemade DESC ) AS t1 ORDER BY datemade ASC";
			$r = $wpdb->get_results( $s );

			if( count( $r ) <= 0 ) {
				if ( $myuid == $post->post_author ) {
					echo '<div class="pm-holder padding-cnt">';
						_e( 'No offers here.','wpjobster' );
					echo '</div>';
				}
			}
		}
	}
}

if ( ! function_exists( 'wpj_sng_req_upd_custom_offer' ) ) {
	function wpj_sng_req_upd_custom_offer(){
		$myuid = $otheruid = 0;
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			$myuid = $current_user->ID;
		}
		if (isset($_POST['decline_offer']) && $_POST['decline_offer'] == 1 && isset($_POST['custom_offer'])) {
			if (get_post_meta($_POST['custom_offer'], "offer_buyer", true) == $myuid) {
				$custom_offer_object = get_post($_POST['custom_offer']);

				if (!is_demo_user()) {
					update_post_meta($_POST['custom_offer'], 'offer_declined', 1);
					wpjobster_send_email_allinone_translated('offer_decl', $custom_offer_object->post_author, $myuid);
					wpjobster_send_sms_allinone_translated('offer_decl', $custom_offer_object->post_author, $myuid);
				}
			}
		}

		if (isset($_POST['withdraw_offer'] ) && $_POST['withdraw_offer'] == 1 && isset($_POST['custom_offer'])) {
			$custom_offer_object = get_post($_POST['custom_offer']);

			if ($custom_offer_object->post_author == $myuid) {
				if (!is_demo_user()) {
					update_post_meta($_POST['custom_offer'], 'offer_withdrawn', 1);
					wpjobster_send_email_allinone_translated('offer_withdr', get_post_meta($_POST['custom_offer'], "offer_buyer", true), $custom_offer_object->post_author);
					wpjobster_send_sms_allinone_translated('offer_withdr', get_post_meta($_POST['custom_offer'], "offer_buyer", true), $custom_offer_object->post_author);
				}
			}
		}
	}
}
