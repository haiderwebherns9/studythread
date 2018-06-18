<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!class_exists('WPJ_request') ){
	class WPJ_request {
		public $_current_user,$_wpdb,$_prefix,$_template_path;
		public static function init( ){
			$class_name=__CLASS__;
			new $class_name;
		}
		public function __construct( ) {
			global $current_user,$wpdb, $prefix,$_uid;
			$this->_template_path = get_template_directory();
			$this->_current_user = wp_get_current_user();
			$this->_uid = $this->_current_user->ID;
			$prefix = $wpdb->prefix;
			add_shortcode( 'show_request_list', array( $this, 'show_request_list_func' ) );
			add_shortcode( 'request-category-lists', array( $this, 'show_request_category_list'));
			add_shortcode( 'wpjobster_theme_my_requests', array( $this, 'show_my_requests' ) );
			add_shortcode( 'wpjobster_nr_active_requests', array( $this, 'show_nr_requests' ) );
			add_action('wp_ajax_nopriv_show_request_list_ajax',  array( $this, 'show_request_list_ajax') );
			add_action('wp_ajax_show_request_list_ajax',  array( $this, 'show_request_list_ajax') );
		}
		public function show_nr_requests( $attrs ){
			$wpjobster_admin_approve_request = get_option('wpjobster_admin_approve_request');
			$attrs = shortcode_atts(array(),$attrs);
			if(isset($attrs['uid']) ){$uid = $attrs['uid'];}else{$uid = $this->_current_user->ID;}
			$args = array('posts_per_page' => '-1','post_type' => 'request','author' => $uid,"post_status"=>array("publish"));
			$args_rev = array('posts_per_page' => '-1','post_type' => 'request','author' => $uid,"post_status"=>array("draft"));
			$args_rejected = array('posts_per_page' => '-1','post_type' => 'request','author' => $uid,"post_status"=>array("pending"));
			$q_rev = new WP_Query($args_rev);
			$arr['inreview_count']=$q_rev->post_count;
			wp_reset_postdata();
			$q_rejected = new WP_Query($args_rejected);
			$arr['rejected_count']=$q_rejected->post_count;
			wp_reset_postdata();
			$q = new WP_Query($args);
			$arr['active_count']=$q->post_count;
			return json_encode($arr);
		}
		public function show_request_list_item($atts='') {

			extract(shortcode_atts(array(
				'directlink' => ''
			), $atts));

			$using_perm = wpjobster_using_permalinks();
			if ( $using_perm ) {
				$privurl_m = get_permalink( get_option( 'wpjobster_my_account_priv_mess_page_id' ) ) . '?';
			} else {
				$privurl_m = get_bloginfo( 'url' ) . '/?page_id=' . get_option( 'wpjobster_my_account_priv_mess_page_id' ) . '&';
			}
			$post = get_post(get_the_ID());
			$auth = $post->post_author;
			$auth_slug = get_userdata($auth);
			$auth_slug = $auth_slug->user_nicename;
			$lnk = $privurl_m . 'username='.$auth_slug;
			$author = get_the_author_meta('user_login');
			$author_url = wpjobster_get_user_profile_link($author);
			$wpjobster_request_location = get_option('wpjobster_request_location');
			$wpjobster_request_lets_meet = get_option('wpjobster_request_lets_meet');
			$wpjobster_request_location_display_condition = get_option('wpjobster_request_location_display_condition');
			$wpjobster_request_date_display_condition = get_option('wpjobster_request_date_display_condition');
			$wpjobster_request_location_display_map = get_option('wpjobster_request_location_display_map');
			$lets_meet = get_post_meta( get_the_ID(), 'request_lets_meet', true );
			$contact_link = $privurl_m . 'username='.$auth_slug;
			$contact_link_html = '';
			$class = is_user_logged_in() ? '' : ' login-link';
			if (get_current_user_id() == $auth) {
				$contact_link_html = '';
			} else {
				$contact_link_html = '<a href="' . $contact_link . '" class="ui primary button db contact'.$class.'">' . __('Contact User', 'wpjobster') . '</a>';
			}
			?>

			<div class="request-job-wrapper main-margin ui segment background-request-<?php echo get_the_ID(); ?>" id="request-<?php echo get_the_ID(); ?>">
				<div class="ui three column stackable grid">
					<div class="two wide column">
						<a href="<?php echo $author_url; ?>">
						<img class="round-avatar" width="45" height="45" border="0" src="<?php echo wpjobster_get_avatar($auth,46,46); ?>" />
						</a>
					</div>

					<div class="ten wide column">
						<a class="author-link" href="<?php echo $author_url; ?>"><?php echo $author; ?></a>
						<span class="bottom-simple-view">
							<?php if ( $wpjobster_request_lets_meet && $lets_meet ) { ?>
								<span class="lets-meet lets-meet-request" data-tooltip="<?php _e( "Let's meet", "wpjobster"); ?>" data-position="top left" data-inverted="">
									<img src="<?php echo get_template_directory_uri() . '/images/shake-icon.png'; ?>" alt="lets-meet">
								</span>
							<?php } ?>
						</span>
						<?php echo '<div class="request-content-title">'; ?>
						<?php echo '<a href="' . $post->guid . '">' . get_the_title() . '</a>'; ?>
						<?php echo '</div>'; ?>
						<?php
							echo '<div class="request-content-view-more nn" style="width: 100%; display: none;">';
							echo '<div style="margin-bottom: 20px;">';
							echo get_the_content() ? get_the_content() : get_the_title();
							echo '</div>';
							$budget_from = get_post_meta(get_the_ID(), 'budget_from', true);
							$budget_from = ($budget_from) ? $budget_from : 0;
							$budget_to = get_post_meta(get_the_ID(), 'budget', true);
							$max_deliv = get_post_meta(get_the_ID(), 'job_delivery', true);
							$deadline = get_post_meta(get_the_ID(), 'request_deadline', true);
							$req_attachments = get_post_meta(get_the_ID(), 'req_attachments', true);
							$pid = get_the_ID();
							$subj_cat = get_post_meta(get_the_ID(), 'req_subj_cat', true );
							$sub_arr=explode(",",$subj_cat);
							$subj_name='';
							global $wpdb;
							
							foreach($sub_arr as $kk=>$sbj_val){
								
								$query1 = "
										SELECT *
										FROM `wp_87fsrr_terms`
										WHERE `term_id` =".$sbj_val;
								//echo $sbj_val;
								//print_r($query1);
								//exit;
								$qry_res=$wpdb->get_results($query1);
								
								foreach($qry_res as $ky=>$vy)
								{
									$subj_name.=$vy->name;									
									if($kk < (count($sub_arr) -1))
									{
										 $subj_name .=', ';
									}
								}
								
								
							}
							$request_tags = '';
							$t = wp_get_post_tags($pid);
							$i = 0;
							$i_separator = '';
							foreach( $t as $tag ) {
								$request_tags .= $i_separator . $tag->name;
								$i++;
								if ($i > 0) { $i_separator = ', '; }
							}
							$days_plural = sprintf( _n( '%d day', '%d days', $max_deliv, 'wpjobster' ), $max_deliv );
							if ( $budget_to ) {
								echo '<div>' . __( 'Budget', 'wpjobster' ) . ': ' . wpjobster_get_show_price( $budget_from) . ' - ' . wpjobster_get_show_price( $budget_to ) . '</div>';
							}
							if ( $max_deliv ) {
								echo '<div>' . __( 'Expected delivery', 'wpjobster' ) . ': ' . $days_plural . '</div>';
							}
							if ( $subj_name ) {
								echo '<div>' . __( 'Subjects', 'wpjobster' ) . ': ' . $subj_name . '</div>';
							}
							if ( $deadline ) {
								echo '<div>' . __( 'Deadline', 'wpjobster' ) . ': ' . date(get_option('date_format') ? get_option('date_format') : 'Y-m-d', $deadline) . '</div>';
							}
							if ( $request_tags ) {
								echo '<div>' . __( 'Tags', 'wpjobster' ) . ': ' . $request_tags . '</div>';
							}
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
							if ($wpjobster_request_date_display_condition == "always"
								|| $wpjobster_request_date_display_condition == "ifchecked") {
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
									echo '<div class="request-map" data-address="' . $request_address . '"></div>';
								}
							}
							echo '</div>';
						?>

						<?php if ( get_the_term_list( get_the_ID(), 'request_cat') ) { ?>
							<div class="request-cat cf p20t">
							<?php
							echo __("Posted in","wpjobster") . " " . get_the_term_list( get_the_ID(), 'request_cat', '', ', ', '' ); ?>
							</div>
						<?php } ?>
					</div>
					<div class="four wide column">
						<div class="request-btns">
							<?php
							$view_more_action = get_option( 'wpjobster_view_more_action' );
							$view_more_link = $post->guid;

							if ( isset( $directlink ) && $directlink == 'true' ) {
								echo '<a href="' . $view_more_link . '" class="ui primary button db request-view-more-link">' . __('View More', 'wpjobster') . '</a>';
							} else {
								if ( $view_more_action != 'directlink' ) {
									echo '<span data-requestid="' . get_the_ID() . '" class="ui primary button db request-view-more-link">' . __('View More', 'wpjobster') . '</span>';
								} else {
									echo '<a href="' . $view_more_link . '" class="ui primary button db request-view-more-link">' . __('View More', 'wpjobster') . '</a>';
								}
							}
							echo '<div class="request-right-view-more cf" style="width: 100%; display: none;">';

								$active_job_required = get_option( 'wpjobster_active_job_cutom_offer' ); $display_custom_offer_button = apply_filters( 'display_or_hide_section_filter', true ); ?>
								<?php if ( get_current_user_id() == $auth ) { ?>
									<span class="ui secondary button request-error db"><?php _e("Delete Request", "wpjobster"); ?></span>
									<span class="request-error-container" style="display: none;"><?php _e( 'Are you sure to delete this request?', 'wpjobster' ); ?>
										<a class="ajax_delete_request ui negative button" data-request-id="<?php the_ID(); ?>" href="<?php echo network_site_url( '/' );?>?jb_action=delete_job&amp;jobid=<?php the_ID(); ?>">
											<?php _e( 'Yes', 'wpjobster' ); ?>
										</a>
										<a class="ui positive button" href="javascript:void(0);">
											<?php _e( 'No', 'wpjobster' ); ?>
										</a>
									</span>

								<?php } elseif ( $display_custom_offer_button == 'true' && $active_job_required == 'yes' && get_current_user_id() != 0 && wpjobster_nr_active_jobs( get_current_user_id() ) < 1 && get_option( 'wpjobster_enable_custom_offers' ) != 'no' ) { ?>
									<span data-requestid="<?php echo get_the_ID(); ?>" class="ui button db btn_inactive grey_btn open-modal-request-error ellipsis"><?php _e("Send Custom Offer", "wpjobster"); ?></span>

									<?php wpj_send_customer_offer_request_error( get_the_ID() ); ?>

								<?php } elseif ( $display_custom_offer_button == 'true' && get_option( 'wpjobster_enable_custom_offers' ) != 'no' ) { ?>

									<a href="<?php echo $lnk; ?>" data-requestid="<?php echo get_the_ID(); ?>" class="ui primary button db adv-search-req <?php echo is_user_logged_in() ? 'open-modal-recent-request' : 'login-link'; ?>"><?php _e("Send Custom Offer", "wpjobster"); ?></a>

									<?php wpj_send_customer_offer_recent_request( $auth, get_the_ID() ); ?>

								<?php } ?>

								<?php
								echo $contact_link_html;
							echo '</div>';
							?>
						</div>
					</div>
				</div>
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
			</div>
			<?php
		}

		// query the posts for a page
		public function show_request_list_ajax() {
			$page = WPJ_Form::post( 'page', 2 );

			$my_order = wpjobster_get_current_order_by_thing();
			$order = ( $my_order == "old" ) ? "ASC" : "DESC";

			$args = array(
				'post_type'      => 'request',
				'paged'          => $page,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => $order,
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$this->show_request_list_item();
				}
			}
			// die only if called from ajax, so we can use the function without ajax as well
			if ( is_ajax() ) {
				wp_die();
			}
		}
		// initial page query including load more button if needed
		public function show_request_list_func($atts=''){

			$my_order = wpjobster_get_current_order_by_thing();
			$order = ( $my_order == "old" ) ? "ASC" : "DESC";

			$args = array(
				'post_type'   => 'request',
				'paged'       => 1,
				'post_status' => 'publish',
				'orderby'     => 'date',
				'order'       => $order,
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) { ?>
				<div>
					<div id="suggest_jobs" class="wpj-load-more-target">
						<?php while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$this->show_request_list_item($atts);
						} ?>
					</div>

					<?php if ( $the_query->max_num_pages > 1 ) { ?>
						<div class="load-more-button wpj-load-more <?php if (get_option('wpjobster_enable_auto-load') == "yes") { echo 'auto-load'; } ?>" data-querytype="request" data-action="show_request_list_ajax" data-max="<?php echo $the_query->max_num_pages; ?>">
							<?php _e("Load More","wpjobster"); ?>
						</div>
					<?php } ?>
				</div>
			<?php }else{ ?>
				<div class="ui segment"><?php echo __("There are no requests yet.",'wpjobster'); ?></div>
			<?php }
		}
		public function show_request_category_list(){ ?>
			<?php
			$taxonomy_name = 'request_cat';
			$term = get_term_by( 'slug', '', $taxonomy_name );
			$hide_empty_categories = ( get_option( 'wpjobster_display_request_empty_categories' ) == 'yes' ) ? false : true;
			$terms = get_terms($taxonomy_name, array( 'hide_empty' => $hide_empty_categories ) );

			if( count( $terms ) > 0 ) { ?>
				<div class="ui segment">
					<ul class="xoxo xyxy">
						<div class="new-subcategory-listing">
							<ul>
								<?php foreach($terms as $trm) {
									if ($trm->parent == 0) { ?>
										<li><a href="<?php echo get_term_link( $trm, $taxonomy_name ) ?>"><?php echo $trm->name ?></a></li>
									<?php }
								} ?>
							</ul>
						</div>
					</ul>
				</div>
			<?php }else{ ?>
				<div class="ui segment"><?php echo __( 'There are no categories yet.', 'wpjobster' ); ?></div>
			<?php }

			if ( is_active_sidebar( 'category-top-widgets-area' ) ) { ?>
				<div class="ui segment">
					<div id="category-top-widgets-area" class="primary-sidebar request widget-area" role="complementary">
						<ul>
							<?php dynamic_sidebar( 'category-top-widgets-area' ); ?>
						</ul>
					</div>
				</div>
			<?php }
		}
		public function show_my_requests($args){
			if(!is_user_logged_in()){
				wp_redirect(get_bloginfo('url'));
			}
			$uid = $this->_current_user->ID;
			?>
			<div id="content-full-ov">
				<div class="ui basic notpadded segment">
					<h1 class="ui header wpj-title-icon">
						<i class="announcement icon"></i>
						<?php _e("My Requests",'wpjobster'); ?>
					</h1>
				</div>

				<?php
				$using_perm = wpjobster_using_permalinks();
				if($using_perm) $acc_pg_lnk = get_permalink(get_option('wpjobster_theme_my_requests'));
				else $acc_pg_lnk = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_theme_my_requests'). "&";

				global $wp_query;
				$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'active';
				$pages = array( 'active', 'in_review', 'rejected' );
				if( ! in_array($pg, $pages) ){ $pg = 'active'; }
				?>

				<div class="ui basic notpadded segment">
					<div class="stackable-buttons">
						<?php
							$request_count_json = do_shortcode("[wpjobster_nr_active_requests uid='$uid']");
							$request_count = json_decode($request_count_json);
						?>
						<a class="ui white button <?php  echo ($pg == "active" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>active"><?php _e("Active","wpjobster")?> (<span class="ticket-count-active"><?php echo $request_count->active_count; ?></span>)</a>

						<a class="ui white button <?php  echo ($pg == "in_review" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>in_review"><?php _e("In Review","wpjobster")?> (<span class="ticket-count-review"><?php echo  $request_count->inreview_count; ?></span>)</a>

						<a class="ui white button <?php  echo ($pg == "rejected" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>rejected"><?php _e("Rejected","wpjobster")?> (<span class="ticket-count-rejected"><?php echo  $request_count->rejected_count; ?></span>)</a>
					</div>
				</div>

				<div class="ui segment">
					<?php
					$posts_per_page = 12;
					if($pg == "active"){
						$args = array(
							'posts_per_page' => $posts_per_page,
							'post_type'      => 'request',
							'author'         => $uid,
							'post_status'    => array("publish"),
						);
					}else if($pg == "in_review"){
						$args = array(
							'posts_per_page' => $posts_per_page,
							'post_type'      => 'request',
							'author'         => $uid,
							'post_status'    => array("draft"),
						);
					}else if($pg == "rejected"){
						$args = array(
							'posts_per_page' => $posts_per_page,
							'post_type'      => 'request',
							'author'         => $uid,
							'post_status'    => array("pending"),
						);
					}else{
						$args = array(
							'posts_per_page' => $posts_per_page,
							'post_type'      => 'request',
							'author'         => $uid,
							'post_status'    => array("publish","draft","pending"),
							'order'          => 'DESC',
							'orderby'        => 'id',
						);
					}
					?><div class="ui two column stackable grid"><?php
						$wpj_req = new WPJ_Load_More_Posts( $args + array( 'function_name'=>'get_post_small_req', 'container_class'=>'all-requests-page' ) );
						if($wpj_req->have_rows()){ ?>

							<div class="eight wide column request-job-title">
								<?php _e('Request Title', 'wpjobster'); ?>
							</div>
							<div class="five wide column request-job-title">
								<?php _e('Date', 'wpjobster'); ?>
							</div>
							<div class="three wide column request-job-title">
								<?php _e('Status', 'wpjobster'); ?>
							</div>

							<?php $wpj_req->show_posts_list_func(); ?>

						<?php }else{
							echo '<div class="sixteen wide column">';
							_e("There are no requests yet.",'wpjobster');
							echo '</div>';
						}
						wp_reset_query(); ?>
					</div>
				</div>

				<?php wpj_request_modal(); ?>

			</div>
			<?php
		}
	}

	function init_request(){
		WPJ_request::init() ;
	}
	add_action( 'init', 'init_request');
}
