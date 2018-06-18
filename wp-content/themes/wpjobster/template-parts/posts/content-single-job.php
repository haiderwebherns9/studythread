<?php
wpj_display_cover_image();
wpj_js_map_display();
wpj_js_audio_display();
wpj_update_last_viewed();
?>
<div class="ui hidden divider"></div>

<div class="ui two column tight stackable block grid">
	<div class="eleven wide column">
		<div class="ui segment">
			<h1><?php the_title(); ?></h1>
			<?php echo wpjobster_display_job_categories();?>
			<?php echo wpj_get_single_job_location(); ?>

			<div class="ui divider"></div>

			<div class="ui grid">
				<div class="column">
					<ul class="single-job-rate-delivery">
						<li><?php echo wpj_get_single_job_rating(); ?></li>
						<li><?php echo wpj_get_single_job_order_queue(); ?></li>
						<li><?php echo wpj_get_single_job_delivery_time(); ?></li>
					</ul>
				</div>
			</div>

			<div class="ui grid single-job-slider">
				<div class="sixteen wide column">
					<?php echo wpj_get_single_job_thumbnail_carousel(); ?>
				</div>
			</div>
		</div>

		<?php wpj_get_social_icons(); ?>

	</div>
  <?php 	
    global $current_user;
	global $post;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$wpjobster_user_type = get_user_meta( $uid, 'wpjobster_user_type', true );
	$type=$wpjobster_user_type;
	$job_id = get_the_ID();
	$postAuth = $post->post_author;
	$currentUser = $current_user->ID;
	$key_price_select = get_post_meta( $job_id, 'job_price_select',true ); 
	$key_price_max = get_post_meta( $job_id, 'job_max_price',true ); 
	$key_price_min= get_post_meta( $job_id, 'job_min_price',true ); 
	$user_info = get_userdata($postAuth);
		?>
	<div class="five wide right floated column">
       <?php if($key_price_select=="fix_price"){ ?>
		<div class="ui basic notpadded segment">
		    <?php
     			if(isset($_SESSION['student'])) { 
			       echo wpj_get_single_job_buy_btn_sidebar(); 
		     	  } else{
					  ?>
				   <div class="pay"><?php echo wpj_get_single_job_buy_btn_sidebar();?></div> 
				  <?php }
			       ?>
		</div>
       <?php } elseif($key_price_select=="negotiable") { ?>
	   
	   <div class="ui basic notpadded segment">
             <div class="pay negotiable">	     
		       <a href="https://www.studythread.com/my-account/private-messages/?username=<?php echo $user_info->user_login ;?>"class="ui primary huge fluid icon right labeled button  uppercase nomargin  no-arrow" target="_blank">Negotiate Price <?php echo "$".$key_price_min."  To  $".$key_price_max; ?> <i class="caret down icon"></i></a>
 			</div>
	   </div>
	   <?php } ?>
	   <div class="ui segment">
	       <strong>"A tutor can only teach maximum of 15 days at a time. 
		    You have to rehire a tutor every 15 days."<strong>
	   </div>
		<div class="ui basic notpadded segment">
		<div class="custm_offer">	
				<div id="job_Modal" class="jmodal">
				  <!-- Modal content -->
				  <div class="jmodal-content">
				  <span class="cclose">&times;</span>
					<div class="jmodal-body">
						 <?php if ( is_active_sidebar( 'custm_offer_video' ) ) : ?>
												<?php dynamic_sidebar( 'custm_offer_video' ); ?>
									   <?php endif; ?>
					</div>
					</div>
				 </div>
				 </div>
              <script>
				 $(document).ready(function() {
					$("#cust_offer").click(function(){
						$("#job_Modal").show();
						$('.ui.dimmer').css('z-index',0);
					});
					$(".cclose").click(function(){
						var video = $("#job_Modal iframe").attr("src");
				         $("#job_Modal iframe").attr("src","");
				         $("#job_Modal iframe").attr("src",video);
						 $("#job_Modal").hide();
						 $('.ui.dimmer').css('z-index',1);
					});
			   });
		</script>
            
			<?php
			if ( is_user_logged_in() ) {
				if ( $postAuth != $currentUser ) {
					echo '<a href="" class="ui big white fluid uppercase button open-modal-single-request-offer">' . __( "Request Custom Offer", "wpjobster" ) . '</a>';
				}

			} else {
				echo '<a href="" class="ui big white fluid uppercase button login-link">' . __( "Request Custom Offer", "wpjobster" ) . '</a>';

			}

			wpj_single_job_request_custom_offer($post->post_author, get_the_ID() ); ?>
		</div>
		<?php
			if($type=='buyer') { 
		 ?>
         <div class="ui segment">
			<a href="https://www.studythread.com/calendar-feature/?job_id=<?php echo $job_id.'&stu_id='.$uid.'&user_id='.$postAuth ;?>" class="ui big white fluid uppercase button">
			   Check teachers availability
			</a>
		 </div>
			<?php } ?>
		<div class="ui segment">
			<?php echo wpj_get_single_job_user_level(); ?>
		</div>
			<div class="ui segment">
				<div class="secure-info-badge">
					<h3><?php echo '100% ' . __("Secure", "wpjobster"); ?></h3>
					<?php _e("Job is done or money back", "wpjobster"); ?>

				</div>
			</div>
			<div class="ui segment">
				<ul class="green-list">
					<li><?php _e("You pay only the listed price without any hidden costs.", "wpjobster"); ?></li>
					<li><?php _e("We keep your money until you are happy with the delivered work.", "wpjobster"); ?></li>
					<li><?php _e("The job will be done or your money will be returned.", "wpjobster"); ?></li>
				</ul>
				<div class="center">
					<a href="<?php echo get_permalink(get_option("wpjobster_how_it_works_page_id")); ?>" class="custom-link-class"><?php _e("How it works", "wpjobster"); ?></a>
				</div>
			</div>
			<?php  if ( get_the_tags() ) { ?>
				<div class="sidebar-job-tags cf ui segment">
					<h4><?php _e( "Related Topics", "wpjobster" ); ?></h4>
					<?php the_tags( '', '', '' ); ?>
				</div>
			<?php }  ?>

			<?php if ( is_active_sidebar( 'single-job-widgets-area' ) ) { ?>
				<div class="ui segment">
					<div id="single-job-widgets-area" class="primary-sidebar widget-area" role="complementary">
						<ul>
							<?php dynamic_sidebar( 'single-job-widgets-area' ); ?>
						</ul>
					</div>
				</div>
			<?php } ?>

	</div>


	<div class="eleven wide column">
		<?php if ( wpj_get_single_job_audio() ) { ?>
			<div class="audio-attachement-wrapper">
				<?php echo wpj_get_single_job_audio(); ?>
			</div>
		<?php } ?>

		<div class="ui segment">
			<div class="single-job-title-description">
				<h2><?php _e("Job Description",'wpjobster'); ?></h2>
			</div>

			<div class="ui divider"></div>

			<div class="single-job-job-description">
				<p>
					<?php
					$desc_content = get_the_content();
					if(wpj_bool_option('wpjobster_allow_wysiwyg_job_description')){
						echo wpautop( $desc_content );
					}else{
						echo wpautop( strip_tags( $desc_content ) );
					}

					echo wpj_get_single_job_report();
					?>
				</p>
			</div>
		</div>

		<?php wpj_get_single_job_packages() ?>

		<div class="single-job-preview-wrapper">
			<?php echo wpj_get_single_job_preview(); ?>
		</div>

		<div class="single-job-display-map">
			<?php echo wpj_get_single_job_map_display(); ?>
		</div>

		<div class="ui segment">
		 <?php if($key_price_select=="fix_price"){ 
			 echo get_single_job_order_additional(); 
		       } else if($key_price_select=="negotiable") { ?>
			<div class="pay negotiable" style="width:100%">	     
		       <a href="https://www.studythread.com/my-account/private-messages/?username=<?php echo $user_info->user_login ;?>"class="ui primary huge fluid icon right labeled button  uppercase nomargin  no-arrow" target="_blank">Negotiate Price <?php echo "$".$key_price_min."  To  $".$key_price_max; ?> <i class="caret down icon"></i></a>
 			</div>
			   <?php } ?>
		</div>

		<div class="single-job-review-wrapper">
			<?php echo wpj_get_single_job_feedback(); ?>
		</div>

		<div class="single-job-other-job">
			<?php echo wpj_get_single_job_other_jobs(); ?>
		</div>

		<div class="ui hidden divider"></div>

	</div>


</div>
