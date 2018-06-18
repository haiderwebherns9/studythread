<?php
/* CHATBOX CLASS */

class WPJobsterChatBox{
	public $_orderid, $_jb_action;

	function __construct($_orderid='', $_jb_action=''){
		global $wpdb, $current_user, $wp_query;
		$this->_wpdb = $wpdb;
		$this->_current_user = $current_user;
		$this->_wp_query = $wp_query;

		$this->orderid = $_orderid ? $_orderid : '';
		if( $this->orderid == '' ){
			$this->orderid = isset( $_GET['oid'] ) ? $_GET['oid'] : '';
		}

		$this->jb_action = $_jb_action ? $_jb_action : '';
		if( $this->jb_action == '' ){
			$this->jb_action = isset( $_GET['jb_action'] ) ? $_GET['jb_action'] : '';
		}

		$this->chat_box_init();

	}

	public function chat_box_init(){
		$wpdb = $this->_wpdb;
		if(!isset($_SESSION)) { session_start(); }

		$current_user = $this->_current_user;
		$uid = $current_user->ID;
		$cid = $current_user->ID;

		$orderid = $this->orderid;
		$current_order = wpjobster_get_order($orderid);


		if(!is_user_logged_in()) { wp_redirect(wp_login_url(get_current_page_url())); exit; }

		$s          = "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
		$r          = $wpdb->get_results($s);
		$row        = $r[0];

		$pid        = $row->pid;

		$attach     = "select * from ".$wpdb->prefix."posts where post_parent='$pid'";
		$attach_res = $wpdb->get_results($attach);

		$post_a     = get_post($row->pid);
		$other_uid  = $row->uid;
		$buyer      = $row->uid;
		$buyer_name = get_userdata($buyer)->user_login;
		$buyer_link = wpj_get_user_profile_link( $buyer );

		$m          = $row->date_made;

		$user_info  = get_userdata($post_a->post_author);
		$user_name  = $user_info->user_login;
		$user_link  = wpj_get_user_profile_link( $user_info );

		$max_days   = get_post_meta($row->pid, 'max_days', true);

		$date_made = $row->date_made;
		$expected  = $this->chat_box_get_order_time();

		//if is buyer
		if ( $uid != $user_info->ID) {

			// mark as read
			$wpdb->query(
				$wpdb->prepare( "
					UPDATE {$wpdb->prefix}job_chatbox SET rd_receiver='1'
					WHERE oid=%d
					AND uid != %d
					AND ( uid > 0
						OR uid = '-1'
						OR uid = '-8'
						OR uid = '-12'
						OR uid = '-13'
						OR uid = '-16'
						OR uid = '-19'
						OR uid = '-22'
						OR uid = '-31'
						OR uid = '-32'
					)
				", $orderid, $uid )
			);

			wpj_refresh_user_notifications( $uid, 'notifications' );

			$pid = $row->pid;
			$post = get_post($row->pid);
			$done_seller = $row->done_seller;
			$closed = $row->closed;

			$bought = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $date_made);

			$current_user = wp_get_current_user();
			$uid = $current_user->ID;
			$user = $row->uid;
			$user = get_userdata($user);

			$completed = 0;

			if ($row->done_buyer == 1) $completed = 1;
			$id = $row->id;
			$delivered = 0;

			if ($row->done_seller == 1) $delivered = 1;
			$can_be_closed = 0;
			$can_request_closed = 1;

			if ($uid == $row->uid) {
				$date_made = $row->date_made;
				$max_days = get_post_meta($row->pid, 'max_days', true) * 3600 * 24;
				$now = current_time('timestamp', 1);

				if ($date_made + $max_days < $now) $can_be_closed = 1;
			}

			if ($row->closed == 1) {
				$can_be_closed      = 0;
				$can_request_closed = 0;
			}

			if ($row->completed == 1) {
				$can_be_closed      = 0;
				$can_request_closed = 0;
			}
		}

		//if is seller
		if (isset($user_info) && is_object($user_info) && $uid == $user_info->ID) {

			// mark as read
			$wpdb->query(
				$wpdb->prepare("
					UPDATE {$wpdb->prefix}job_chatbox
					SET rd_receiver = '1'
					WHERE oid = %d
					AND uid != %d
					AND ( uid > 0
						OR uid = '0'
						OR uid = '-2'
						OR uid = '-9'
						OR uid = '-10'
						OR uid = '-11'
						OR uid = '-14'
						OR uid = '-15'
						OR uid = '-17'
						OR uid = '-18'
						OR uid = '-21'
						OR uid = '-33'
						OR uid = '-34'
						OR uid = '-35'
					)
				", $orderid, $uid )
			);

			wpj_refresh_user_notifications( $uid, 'notifications' );

			$delivered = 0;
			$done_seller = $row->done_seller;

			if ($row->done_seller == 1) $delivered = 1;
			$id = $row->id;
			$closed = $row->closed;
			$completed = 0;

			if ($row->done_buyer == 1) $completed = 1;
			$can_request_closed = 1;

			if ($closed == 1) $can_request_closed = 0;

			if ($row->completed == 1) {
				$can_be_closed      = 0;
				$can_request_closed = 0;
			}
		}

		$accept_cancellation_request = $row->accept_cancellation_request;
		$request_cancellation_from_seller = $row->request_cancellation_from_seller;
		$request_cancellation_from_buyer = $row->request_cancellation_from_buyer;
		$message_to_seller = $row->message_to_seller;
		$message_to_buyer = $row->message_to_buyer;

		$request_modification = $row->request_modification;
		$message_request_modification = $row->message_request_modification;

		$using_perm = wpjobster_using_permalinks();

		if($using_perm) $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')). "?";
		else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_priv_mess_page_id'). "&";

		if( ($row->uid != $uid && $post_a->post_author != $uid) || count($r) == 0 ){
			if( !current_user_can('administrator') ){
				wp_redirect(get_bloginfo('url'));
			}
		}

		//-----------------------------------------------------------------------------------------------

		$price      = get_post_meta($pid, 'price', true);
		$ttl        = $post_a->post_title;
		$max_days   = get_post_meta($pid, "max_days", true);
		$location   = wp_get_object_terms($pid, 'job_location');
		$cat        = wp_get_object_terms($pid, 'job_cat');

		//-----------------------------------------------------------------------------------------------

		$order_details_arr = $this->chat_box_get_order_details($current_order,$pid,$uid,$ttl,$buyer,$user_name,$buyer_name);
		$message = $order_details_arr['message'];

		$instant = get_post_meta($pid,'instant',true);

		return $vars = array(
			'accept_cancellation_request'      => ( isset( $accept_cancellation_request )      && $accept_cancellation_request )      ? $accept_cancellation_request      : '',
			'buyer'                            => ( isset( $buyer )                            && $buyer )                            ? $buyer                            : '',
			'buyer_link'                       => ( isset( $buyer_link )                       && $buyer_link )                       ? $buyer_link                       : '',
			'buyer_name'                       => ( isset( $buyer_name )                       && $buyer_name )                       ? $buyer_name                       : '',
			'can_request_closed'               => ( isset( $can_request_closed )               && $can_request_closed )               ? $can_request_closed               : '',
			'cancellation_count'               => ( isset( $cancellation_count )               && $cancellation_count )               ? $cancellation_count               : '',
			'closed'                           => ( isset( $closed )                           && $closed )                           ? $closed                           : '',
			'completed'                        => ( isset( $completed )                        && $completed )                        ? $completed                        : '',
			'current_order'                    => ( isset( $current_order )                    && $current_order )                    ? $current_order                    : '',
			'current_user'                     => ( isset( $this->_current_user )              && $this->_current_user )              ? $this->_current_user              : '',
			'date_made'                        => ( isset( $date_made )                        && $date_made )                        ? $date_made                        : '',
			'datemade'                         => ( isset( $datemade )                         && $datemade  )                        ? $datemade                         : '',
			'delivered'                        => ( isset( $delivered )                        && $delivered )                        ? $delivered                        : '',
			'done_seller'                      => ( isset( $done_seller )                      && $done_seller )                      ? $done_seller                      : '',
			'expected'                         => ( isset( $expected )                         && $expected )                         ? $expected                         : '',
			'first_message_sent'               => ( isset( $first_message_sent )               && $first_message_sent )               ? $first_message_sent               : '',
			'instant'                          => ( isset( $instant )                          && $instant )                          ? $instant                          : '',
			'max_days'                         => ( isset( $max_days )                         && $max_days )                         ? $max_days                         : '',
			'message'                          => ( isset( $message )                          && $message )                          ? $message                          : '',
			'orderid'                          => ( isset( $orderid )                          && $orderid )                          ? $orderid                          : '',
			'other_uid'                        => ( isset( $other_uid )                        && $other_uid )                        ? $other_uid                        : '',
			'pid'                              => ( isset( $pid )                              && $pid )                              ? $pid                              : '',
			'post_a'                           => ( isset( $post_a )                           && $post_a )                           ? $post_a                           : '',
			'privurl_m'                        => ( isset( $privurl_m )                        && $privurl_m )                        ? $privurl_m                        : '',
			'request_cancellation_from_buyer'  => ( isset( $request_cancellation_from_buyer )  && $request_cancellation_from_buyer )  ? $request_cancellation_from_buyer  : '',
			'request_cancellation_from_seller' => ( isset( $request_cancellation_from_seller ) && $request_cancellation_from_seller ) ? $request_cancellation_from_seller : '',
			'request_modification'             => ( isset( $request_modification )             && $request_modification )             ? $request_modification             : '',
			'row'                              => ( isset( $row )                              && $row )                              ? $row                              : '',
			'ttl'                              => ( isset( $ttl )                              && $ttl )                              ? $ttl                              : '',
			'uid'                              => ( isset( $uid )                              && $uid )                              ? $uid                              : '',
			'user_info'                        => ( isset( $user_info )                        && $user_info )                        ? $user_info                        : '',
			'user_link'                        => ( isset( $user_link )                        && $user_link )                        ? $user_link                        : '',
			'user_name'                        => ( isset( $user_name )                        && $user_name )                        ? $user_name                        : '',
			'wp_query'                         => ( isset( $this->_wp_query )                  && $this->_wp_query )                  ? $this->_wp_query                  : '',
			'wpdb'                             => ( isset( $this->_wpdb )                      && $this->_wpdb )                      ? $this->_wpdb                      : '',
		);
	}

	public function chat_box_current_order_status_details(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}

		$chat_box = $this; ?>

			<!-- TIMER -->
			<?php $chat_box->chat_box_timer(); ?>
			<!-- END TIMER -->

			<!-- CURRENT ORDER PENDING DETAILS - BANK TRANSFER -->
			<?php if ($current_order->payment_status == 'pending' && $current_order->payment_gateway == 'banktransfer') {
				do_action("wpjobster_before_bank_details_display",$current_order); ?>

				<div class="chatbox_post ui notpadded segment">
					<div class="chatbox_post_content">
						<div class="padding-cnt">
							<strong><?php  _e('Bank Details', 'wpjobster'); ?>:</strong><br>
							<?php echo nl2br(get_option('wpjobster_bank_details')); ?><br>
						</div>
					</div>
				</div>

				<?php do_action("wpjobster_after_bank_details_display",$current_order);
			} ?>
			<!-- END CURRENT ORDER PENDING DETAILS - BANK TRANSFER -->

			<!-- CURRENT ORDER PENDING DETAILS - NO BANK TRANSFER -->

			<?php if ($current_order->payment_status == 'pending') {
				// PENDING STATUS
				do_action("wpjobster_before_pending_order_details",$current_order);
				$chat_box->chat_box_order_pending();
				do_action("wpjobster_after_pending_order_details",$current_order);
				// END PENDING STATUS
			} elseif ($current_order->payment_status == 'failed') {
				// FAILED STATUS
				do_action("wpjobster_before_failed_order_details",$current_order);
				$chat_box->chat_box_order_failed();
				do_action("wpjobster_after_pending_order_details",$current_order);
				// END FAILED STATUS
			} elseif ($current_order->payment_status == 'processing') {
				// PROCESSING STATUS
				do_action("wpjobster_before_processing_order_details",$current_order);
				$chat_box->chat_box_order_processing();
				do_action("wpjobster_after_processing_order_details",$current_order);
				// END PROCESSING STATUS
			} elseif ($current_order->payment_status == 'cancelled') {
				// CANCELLED STATUS
				do_action("wpjobster_before_cancelled_order_details",$current_order);
				$chat_box->chat_box_order_cancelled();
				do_action("wpjobster_after_cancelled_order_details",$current_order);
				// END CANCELLED STATUS
			}

			$arg = '';
			if( $current_order->payment_status != 'pending' && $current_order->payment_status != 'failed' && $current_order->payment_status != 'processing' && $current_order->payment_status != 'cancelled' ){
				do_action("wpjobster_before_completed_order_details",$current_order);

				// BUTTONS AND NOTIFICATIONS
				$arg = $chat_box->chat_box_buttons();
				// END BUTTONS AND NOTIFICATIONS

				if ($completed == 1) {
					// ORDER COMPLETED
					$chat_box->chat_box_order_complete();
					// END ORDER COMPLETED
				}

				// RATINGS
				$chat_box->chat_box_rating($arg);
				// END RATINGS

			} ?>

			<!-- END CURRENT ORDER PENDING DETAILS - NO BANK TRANSFER -->

		<?php return $arg;

	}

	public function chat_box_timer(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}
		$tm = current_time('timestamp', 1);

		$order = wpjobster_get_order( $orderid );

		$show_timer = 0;
		if( $instant != 1 ){
			if( $order->extra_fast == 1 && ( $order->extra_fast_days != 'instant' && $order->extra_fast_days != 0 ) ){
				$show_timer = 1;
			} elseif ( $order->expected_delivery >= $tm ){
				$show_timer = 1;
			}else{
				$show_timer = 0;
			}
		} elseif ( $order->expected_delivery >= $tm ){
			$show_timer = 1;
		} else {
			$show_timer = 0;
		}

		if( $show_timer == 1 && $order->payment_status != 'pending' && $order->closed != 1 && $completed != 1 && $closed != 1 ){ ?>
			<div class="cf">
				<div class="flipTimer">
					<div class="days"></div>
					<div class="hours"></div>
					<div class="minutes"></div>
					<div class="seconds"></div>
				</div>
				<div class="contentTimer">
					<span class="day"><?php _e( 'Days', 'wpjobster' ); ?></span>
					<span class="hour"><?php _e( 'Hours', 'wpjobster' ); ?></span>
					<span class="min"><?php _e( 'Minutes', 'wpjobster' ); ?></span>
					<span class="sec"><?php _e( 'Seconds', 'wpjobster' ); ?></span>
				</div>
			</div>

			<script type="text/javascript">
				$(document).ready(function() {
					var oid = '<?php echo $orderid; ?>';
					$('.flipTimer').flipTimer({
						direction: 'down',
						date: '<?php echo $expected; ?>',
					});
				});
			</script>
		<?php }
	}

	public function chat_box_list_notifications(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}

		$notok=1;
		$first_message_sent = 0;
		$s = "select DISTINCT * from ".$wpdb->prefix."job_chatbox where oid='$orderid' order by id desc";
		$r = $wpdb->get_results($s);

		foreach($r as $row){
			$sss1 = "select DISTINCT * from ".$wpdb->prefix."job_orders where id='{$row->oid}' ";
			$rrr1 = $wpdb->get_results($sss1);
			$row1 = $rrr1[0];
			$post_a = get_post($row1->pid);
			//----------------------
			$icon   = get_template_directory_uri().'/images/bulb.png';
			$iconsize = 50;
			$class  = "";
			$message = __('The order has been sent to', 'wpjobster') . " <a class=\"green-username\" href=\"" . $user_link . "\">" . $user_name . "</a>.";
			$datine = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->datemade);
			$class1 = "";
			if($row->uid > 0){
				$notok=0;
			}
			if($notok) break;
		}

		if (isset($_SESSION['tried_to_leave']) && $_SESSION['tried_to_leave'] == 1 && $closed != 1) {
			echo '<div class="error">'. __("ERROR! Please provide instructions before you can continue.","wpjobster"). '</div> <div class="clear10"></div>';
		}

		//  0 order info (like, end, start)
		// -1 marked as finished
		// -2 finished
		// -3 not finished
		$s = "select DISTINCT * from ".$wpdb->prefix."job_chatbox where oid='$orderid' order by id asc";
		$r = $wpdb->get_results($s);
		$cancellation_count = 0;
		$modification_count = 0;

		foreach($r as $row){
			// skip decline/cancel custom extra
			if($row->uid == -32 || $row->uid == -33 || $row->uid == -34)
				continue;

			$sss1 = "select DISTINCT * from ".$wpdb->prefix."job_orders where id='{$row->oid}' ";
			$rrr1 = $wpdb->get_results($sss1);
			$row1 = $rrr1[0];
			$post_a = get_post($row1->pid);

			//----------------------
			$icon   = get_template_directory_uri().'/images/sent.png';
			$icon2 = $icon;
			$iconsize = 50;
			$iconsize2 = $iconsize;
			$bgcolor = "blue";
			$bgcolor2 = $bgcolor;
			$class  = "";
			$class2 = $class;


			$message = __('The order has been sent to', 'wpjobster') . " <a class=\"green-username\" href=\"" . $user_link . "\">" . $user_name . "</a>.";
			$message2 = '';

			if($row->uid == 0)
			{

				$query = "SELECT * ".
							"FROM ".$wpdb->prefix."job_orders ".
							"WHERE id = ".$row->oid;
				$result = $wpdb->get_results($query);

				foreach($result as $resultrow) {

					$order_details_arr = $this->chat_box_get_order_details($resultrow,$pid,$uid,$ttl,$buyer,$user_name,$buyer_name);

					$message = $order_details_arr['message'];
					$processing_fees = $order_details_arr['processing_fees'];
					$tax_amount = $order_details_arr['tax_amount'];




					if ($resultrow->job_instructions) {
						$job_instructions = $resultrow->job_instructions;

					} elseif (get_post_meta($resultrow->pid, "instruction_box", true)) {
						$job_instructions = get_post_meta($resultrow->pid, "instruction_box", true);
					} else {
						$job_instructions = '';
					}


					if ($job_instructions) {

						list($filtered_message, $validation_errors) = filterMessagePlusErrors($job_instructions, false);
						$validation_errors_display = displayValidationErrors($validation_errors);
						$message .= '<div class="cp-instructions"><h3>' . __("Instructions for the buyer:","wpjobster") . '</h3>' .
							$filtered_message . $validation_errors_display . '</div>';
					}
					$instant = get_post_meta($pid,'instant',true);
					$job_any_attachments = get_post_meta($pid, 'job_any_attachments', true);
					if($instant == 1){
						if (isset($job_any_attachments) && $job_any_attachments != "") {
							$message .= '<div class="pm-attachments">';
							$attachments = explode(",", $job_any_attachments);
							foreach ($attachments as $attachment) {
								if($attachment != ""){
									$message .= '<div class="pm-attachment-rtl"><a class="download-req" target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';
									$message .= get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span></div><br>';
								}
							}
							$message .= '</div>';
						}else{
							if(isset($attach_res)){
								foreach($attach_res as $attach){
									if($attach->post_mime_type == 'application/zip'){
										$message .= '<div class="pm-attachments">';
										$message .= '<a class="download-req" href="'.wp_get_attachment_url($attach->ID).'" download>';
										$message .= $attach->post_title.'</a> <span class="pm-filesize">'.size_format(filesize(get_attached_file($attach->ID))).'</span><br>';
										$message .= '</div>';
									}
								}
							}
						}

					}

					if ( strtoupper($current_order->payment_gateway) == strtoupper('cod')
						&& $uid == wpj_get_seller_id( $current_order ) ) {
						$icon2 = get_template_directory_uri().'/images/cash.png';
						$iconsize2 = $iconsize;
						$bgcolor2 = "orange";
						$message2 .= '<h3 class="nomargin p10b">' . __( "Payment method: Cash on Delivery", "wpjobster" ) . "</h3>";
						$message2 .= __( "Since the payment has not been secured by the platform, please make sure you have collected the payment from the buyer, before marking this transaction as delivered.", "wpjobster" );
						$class2 = $class;
					}
				}
			}

			$datine = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->datemade);
			$class1 = "";
			if($row->uid > 0)
			{
				$icon = wpjobster_get_avatar($row->uid,61, 61);
				$iconsize = 60;

				list($filtered_message, $validation_errors) = filterMessagePlusErrors($row->content, false);
				$validation_errors_display = displayValidationErrors($validation_errors);

				$message = $filtered_message;
				$message .= $validation_errors_display;


				$class1 = ""   ;
				$bgcolor = "white";
				$usr_dt = get_userdata($row->uid);
				$cls_m = '';
				$first_message_sent = 1;

				if($row->uid == $post_a->post_author)
				{
					$class1 = ""  ;
					$bgcolor = "white";
					$cls_m = '';
				}
				$username_k = '<div class="'.$cls_m.'"><a href="'.wpj_get_user_profile_link( $usr_dt->user_login ).'">'.$usr_dt->user_login."</a></div>";
			}
			else
			{

				if($row->uid == -1) // marked as finished
				{
					$icon   = get_template_directory_uri().'/images/delivered.png';
					$iconsize = 50;
					$bgcolor = "green";
					if ($uid == $user_info->ID) {
						$message = __('You have marked this order as delivered.','wpjobster');
					} else {
						$message = __('The order has been marked as delivered by the seller.<br/>Please confirm using the button below.','wpjobster');
					}
				}
				if($row->uid == -2) // marked as finished
				{
					$icon   = get_template_directory_uri().'/images/accepted.png';
					$iconsize = 50;
					$bgcolor = "green";
					if ($uid == $user_info->ID) {
						$message = __('The order has been accepted by the buyer.','wpjobster');
					} else {
						$message = __('You have accepted the order.<br/>Please review it using the form below.','wpjobster');
					}
				}
				if($row->uid == -8) //the seller requested cancellation
				{
					$icon   = get_template_directory_uri().'/images/declined.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('You have requested a mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('The seller of this job has requested a mutual cancellation for this order.','wpjobster');
					}

					list($filtered_message, $validation_errors) = filterMessagePlusErrors($row->content, false);
					$validation_errors_display = displayValidationErrors($validation_errors);

					$message .= '<br>' . __('Cancellation Message:','wpjobster') . ' "' . $filtered_message . '"';
					$message .= $validation_errors_display;
					$cancellation_count++;

				}
				if($row->uid == -9) //the buyer requested cancellation
				{
					$icon   = get_template_directory_uri().'/images/declined.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has requested a mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('You have requested a mutual cancellation for this order.','wpjobster');
					}

					list($filtered_message, $validation_errors) = filterMessagePlusErrors($row->content, false);
					$validation_errors_display = displayValidationErrors($validation_errors);

					$message .= '<br>' . __('Cancellation Message:','wpjobster') . ' "' . $filtered_message . '"';
					$message .= $validation_errors_display;
					$cancellation_count++;
				}
				if($row->uid == -10) // the buyer accepted mutual cancellation
				{
					$icon   = get_template_directory_uri().'/images/accepted.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has accepted the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('You have accepted the mutual cancellation for this order.','wpjobster');
					}
				}
				if($row->uid == -11) // the buyer declined mutual cancellation
				{
					$icon   = get_template_directory_uri().'/images/declined2.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has declined the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('You have declined the mutual cancellation for this order.','wpjobster');
					}
				}
				if($row->uid == -12) // the seller accepted mutual cancellation
				{
					$icon   = get_template_directory_uri().'/images/accepted.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('You have accepted the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('The seller has accepted the mutual cancellation for this order.','wpjobster');
					}
				}
				if($row->uid == -13) // the seller declined mutual cancellation
				{
					$icon   = get_template_directory_uri().'/images/declined2.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('You have declined the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('The seller has declined the mutual cancellation for this order.','wpjobster');
					}
				}

				if($row->uid == -14) // transaction was cancelled by admin
				{
					$icon   = get_template_directory_uri().'/images/admin-action.png';
					$iconsize = 50;
					$bgcolor = "red";
					$message = __('Transaction has been cancelled by the admin based on the requested arbitrage.','wpjobster');
				}

				if($row->uid == -15) // buyer request modification
				{
					$icon   = get_template_directory_uri().'/images/modification.png';
					$iconsize = 50;
					$bgcolor = "orange";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has requested a modification for this order.', 'wpjobster' );
					} else {
						$message = __('You have requested a modification for this order.', 'wpjobster' );
					}

					list($filtered_message, $validation_errors) = filterMessagePlusErrors($row->content, false);
					$validation_errors_display = displayValidationErrors($validation_errors);

					$message .= '<br>' . __('Modification Message:','wpjobster') . ' "' . $filtered_message . '"';
					$message .= $validation_errors_display;
					$modification_count++;

				}

				if($row->uid == -16) // mutual cancellation was aborted by seller
				{
					$icon   = get_template_directory_uri().'/images/declined2.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('You have aborted the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('The seller has aborted the mutual cancellation for this order.','wpjobster');
					}
				}

				if($row->uid == -17) // mutual cancellation was aborted by buyer
				{
					$icon   = get_template_directory_uri().'/images/declined2.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has aborted the mutual cancellation for this order.','wpjobster');
					} else {
						$message = __('You have aborted the mutual cancellation for this order.','wpjobster');
					}
				}

				if($row->uid == -18) // buyer rating
				{
					$buyer_given_feedback=1;
					$icon   = get_template_directory_uri().'/images/staricon.png';
					$iconsize = 50;
					$bgcolor = "orange";

					$s_rat = "select * from " . $wpdb->prefix . "job_ratings where orderid='$orderid'";
					$r_rat = $wpdb->get_results($s_rat);
					$r_rat = $r_rat[0];
					$message = '';
					$message .= '<div class="cf wpj-star-rating-static">';
					$message .= wpjobster_show_big_stars_our_of_number($r_rat->grade);
					$message .= '</div>';

					if ($uid == $user_info->ID) {
						$message .= __('The buyer has rated this order.','wpjobster');
					} else {
						$message .= __('You have rated this order.','wpjobster');
					}

					list($filtered_message, $validation_errors) = filterMessagePlusErrors(stripslashes($r_rat->reason), false);
					$validation_errors_display = displayValidationErrors($validation_errors);

					$message .= '<div class="cf">'.__("Feedback Message", "wpjobster").': "';
					$message .= $filtered_message;
					$message .= '"</div>';
					$message .= $validation_errors_display;
				}

				if($row->uid == -19) // seller rating
				{
					$icon   = get_template_directory_uri().'/images/staricon.png';
					$iconsize = 50;
					$bgcolor = "orange";

					$s_rat = "select * from " . $wpdb->prefix . "job_ratings_by_seller where orderid='$orderid'";
					$r_rat = $wpdb->get_results($s_rat);
					$r_rat = $r_rat[0];
					$message_reply = '';
					$message_reply .= '<div class="cf wpj-star-rating-static">';
					$message_reply .= wpjobster_show_big_stars_our_of_number($r_rat->grade);
					$message_reply .= '</div>';

					if ($uid == $user_info->ID) {
						$message_reply .= __('You have replied to buyer\'s feedback.','wpjobster');
					} else {
						$message_reply .= __('Seller replied to your feedback.','wpjobster');
					}

					list($filtered_message, $validation_errors) = filterMessagePlusErrors(stripslashes($r_rat->reason), false);
					$validation_errors_display = displayValidationErrors($validation_errors);
					if ($uid == $user_info->ID) {
						$message_reply .= '<div class="cf">'.__("Your response", "wpjobster").': "';
					}else{
						$message_reply .= '<div class="cf">'.__("Seller’s response", "wpjobster").': "';

					}

					$message_reply .= $filtered_message;
					$message_reply .= '"</div>';
					$message_reply .= $validation_errors_display;

					$message = $message_reply;
				}

				if($row->uid == -31) // custom extra
				{

					$icon   = get_template_directory_uri().'/images/sent.png';
					$iconsize = 50;
					$bgcolor = "green";
					if ($uid == $user_info->ID) {
						$message = '<div>' . __('Custom extra','wpjobster') . '</div>';
					} else {
						$message = '<div>' . __('Custom extra','wpjobster') . '</div>';
					}
				}

				if($row->uid == -35) // order cancellation by buyer( delivery time expired );
				{
					$icon   = get_template_directory_uri().'/images/declined.png';
					$iconsize = 50;
					$bgcolor = "red";
					if ($uid == $user_info->ID) {
						$message = __('The buyer has cancelled the transaction.','wpjobster');
					} else {
						$message = __('You cancelled this transaction.','wpjobster');
					}
				}

				$username_k = '';
			}
			if($row->uid == -21)
			{
				$icon   = get_template_directory_uri().'/images/declined2.png';
				$iconsize = 50;
				$bgcolor = "red";
				if ($uid == $user_info->ID) {
					$message = __('You have aborted the mutual cancellation for this order.','wpjobster');
				} else {
					$message = __('The buyer has aborted the mutual cancellation for this order.','wpjobster');
				}
			}

			//-------------------------
			echo '<div class="chatbox_post ui notpadded segment"><div class="chatbox_post_content">';
			echo '<div class="job_post_left '.$bgcolor.' '.$class1.'"><img width="'.$iconsize.'" height="'.$iconsize.'" src="'.$icon.'" class="round-avatar" /></div>';
			echo '<div class="job_post_right">';

			if ( $row->uid == 0 ) {
				do_action( 'wpj_transaction_page_before_first_message', $current_order, $this->jb_action );
			}

			if ( $row->uid == -31 ) {
				do_action( 'wpj_transaction_page_before_custom_extra_title', $current_order, $this->jb_action, $row->content );
			}

			echo '<div>'.$username_k.' '.wpj_make_links_clickable($message).'';

			// Display Attachments
			if(!empty($row->attachment)):
				echo '<div class="pm-attachments"><div class="pm-attachments-title">';
				_e("Attachments", "wpjobster");
				echo '</div>';
				$attachments = explode(",", $row->attachment);
				foreach ($attachments as $attachment) {
					echo '<a class="download-req" target="_blank" href="'. get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';

					echo get_the_title($attachment).'</a> <span class="pm-filesize">('.size_format(filesize(get_attached_file($attachment))).')</span><br>';
				}
				echo '</div>';
			endif;

			// display custom extra content
			if($row->uid == -31) {
				$custom_extras = json_decode( $current_order->custom_extras );
				$custom_extra = $custom_extras[$row->content];

				echo '<table class="pm-custom-extra-meta"><tbody>';

				echo '<tr><td><strong>'.__('Description:','wpjobster').'</strong></td>
						<td>'.$custom_extra->description.'</td></tr>';
				echo '<tr><td><strong>'.__('Price:','wpjobster').'</strong></td>
						<td>'.wpjobster_get_show_price_classic($custom_extra->price, 1) . '</td></tr>';

				echo '<tr><td><strong>'.__('Delivery:','wpjobster').'</strong></td>
						<td>'.sprintf( _n("%s day", "%s days", $custom_extra->delivery, "wpjobster"), $custom_extra->delivery ).'</td></tr>';

				if($custom_extra->declined == true)
					echo '<tr><td><strong>'.__('Status:','wpjobster').'</td><td><span class="offer-red">'.__('declined','wpjobster').'</span></strong></td></tr>';
				elseif($custom_extra->cancelled == true)
					echo '<tr><td><strong>'.__('Status:','wpjobster').'</td><td><span class="offer-red">'.__('cancelled','wpjobster').'</span></strong></td></tr>';
				elseif($custom_extra->paid == true)
					echo '<tr><td><strong>'.__('Status:','wpjobster').'</td><td><span class="offer-green">'.__('paid','wpjobster').'</span></strong></td></tr>';
				else{
					// check if bank transfer pending
					$sql_q = " select * from ".$wpdb->prefix."job_custom_extra_orders where order_id='{$current_order->id}' and custom_extra_id='{$row->content}' and payment_status='pending' and payment_gateway_name='banktransfer' limit 1 ";
					$pend_rows = $wpdb->get_results($sql_q);
					if ( $pend_rows ) {
						echo '<tr><td><strong>'.__('Status:','wpjobster').'</strong></td><td>'.__('pending','wpjobster').'</td></tr>';
						if($uid != $user_info->ID)
							echo '<tr><td><strong>'.__('Bank Details:','wpjobster').'</strong></td><td>'.nl2br(get_option('wpjobster_bank_details')).'</td></tr>';
					} else {
						echo '<tr><td><strong>'.__('Status:','wpjobster').'</strong></td><td>'.__('waiting','wpjobster');
						if ( WPJ_Form::get( 'status' ) == 'fail' ) {
							echo ' ';
							do_action( 'wpj_gateway_transaction_cancelled_message', $orderid, 'custom_extra' );
						}
						echo '</td></tr>';
					}
				}

				echo '</tbody></table>';

				if ($uid != $user_info->ID && !$custom_extra->paid && !$custom_extra->cancelled && !$custom_extra->declined && $completed != 1 && $closed != 1) {
					if($pend_rows){
						$pend_row = $pend_rows[0];
						?>
						<div class="custom_extra_chatbox_buttons pm-custom-offer-bottom cf">
							<a href="<?php bloginfo('siteurl'); ?>/?payment_response=<?php echo $pend_row->payment_gateway_name; ?>&payment_type=custom_extra&order_id=<?php echo $pend_row->id; ?>&action=cancel" class="ui button custom-offer-pm lightgrey_btn">
								<?php _e('Cancel','wpjobster'); ?>
							</a>
						</div>
						<?php
					}
					else{ ?>

						<div class="custom_extra_chatbox_buttons pm-custom-offer-bottom cf">
							<a href="#" class="ui positive button pr btn-accept-custom-extra custom-offer-pm"
								<?php echo is_user_logged_in() ? 'data-submit="[name=purchase_custom_extra'.$row->content.']"' : ''; ?>>
								<?php _e('Accept custom extra','wpjobster'); ?>
							</a>
							<form id="accept_custom_extra" method="post" name="purchase_custom_extra<?php echo $row->content; ?>" action="<?php echo get_bloginfo('url') . '/?jb_action=purchase_this&oid=' . $orderid . '&custom_extra=' . $row->content; ?>" style="display: none;">
								<input type="hidden" name="purchaseformvalidation" value="ok" />
							</form>
							<span>
								<a class="ui button decline_custom_extra custom-offer-pm">
									<?php _e('Decline custom extra','wpjobster'); ?>
								</a>
								<form id="decline_custom_extra" method="post" name="decline_custom_extra<?php echo $row->content; ?>" action="" style="display: none;">
									<input type="hidden" value="1" name="decline_custom_extra" />
									<input type="hidden" value="<?php echo $row->content; ?>" name="custom_extra" />
									<input type="hidden" value="chat_box_deny_custom_extra" name="action" />
									<input type="hidden" value="<?php echo $orderid; ?>" name="order_id" />
								</form>
							</span>
						</div>
					<?php } ?>
				<?php }
				if ($uid == $user_info->ID && !$custom_extra->paid && !$custom_extra->cancelled && !$custom_extra->declined && !$pend_rows && $completed != 1 && $closed != 1) { ?>

					<div class="custom_extra_chatbox_buttons pm-custom-offer-bottom cf">
						<a class="ui button cancel_custom_extra">
							<?php _e('Cancel custom extra','wpjobster'); ?>
						</a>
						<form id="cancel_custom_extra" method="post" name="cancel_custom_extra<?php echo $row->content; ?>" action="" style="display: none;">
							<input type="hidden" value="1" name="cancel_custom_extra" />
							<input type="hidden" value="<?php echo $row->content; ?>" name="custom_extra" />
							<input type="hidden" value="chat_box_cancel_custom_extra" name="action" />
							<input type="hidden" value="<?php echo $orderid; ?>" name="order_id" />
						</form>
					</div>

				<?php }

			} ?>

			<script type="text/javascript">
				jQuery(function($){
					$("[data-submit]").click(function(){
						var ob=$(this).parents("form");
						if($(this).data("submit")){ob=$($(this).data("submit"));}
						ob.submit();
						return false;
					});
				});
			</script>

			<?php
			echo '<div class="the_time">'.$datine.'</div>';
			echo '</div></div>';
			echo '</div></div>';

			if ( $message2 ) {
				// this is not an entry in chatbox, but an additional message displayed right after an existing entry only for the transaction page
				echo '<div class="chatbox_post ui notpadded segment"><div class="chatbox_post_content">';
					echo '<div class="job_post_left '.$bgcolor2.' '.$class2.'"><img width="'.$iconsize2.'" height="'.$iconsize2.'" src="'.$icon2.'" /></div>';
					echo '<div class="job_post_right"><div>'.$message2.'';
						echo '<div class="the_time">'.$datine.'</div>';
					echo '</div></div>';
				echo '</div></div>';
				// reset variable so this message doesn't display again
				$message2 = '';
			}
		}

		return $vars = array(
			'cancellation_count'   => ( isset( $cancellation_count )   && $cancellation_count )   ? $cancellation_count   : '',
			'modification_count'   => ( isset( $modification_count )   && $modification_count )   ? $modification_count   : '',
			'buyer_given_feedback' => ( isset( $buyer_given_feedback ) && $buyer_given_feedback ) ? $buyer_given_feedback : ''

		);
	}

	// first order message
	public function chat_box_get_order_details($current_order,$pid,$uid,$ttl,$buyer,$user_name,$buyer_name){

		$resultrow = $current_order;

		if($current_order->payment_status=='pending'){
			$order_status=' <span class="title-status pending">('.__('Pending','wpjobster').')</span>';
		}elseif($current_order->payment_status=='failed'){
			$order_status=' <span class="title-status failed">('.__('Failed','wpjobster').')</span>';
		}elseif($current_order->payment_status=='cancelled'){
			$order_status=' <span class="title-status">('.__('Cancelled','wpjobster').')</span>';
		}elseif($current_order->payment_status!='completed'){
			$order_status=' <span class="title-status">('.$current_order->payment_status.')</span>';
		}else{
			$order_status='';
		}

		$message='';
		$offer_class = '';
		if (get_post_type($pid) == 'offer') {
			$offer_class = 'cp-offer-class';

		}

		$message .= '<div class="ui two column stackable grid job-avatar-title-price">';


		if (!$offer_class) {
			$message .= '<div class="two wide column">';
				if ($resultrow->job_image) {
					$message .= '<img width="60" height="60" class="round-avatar" src="' . wpj_get_attachment_image_url( $resultrow->job_image, array( 60, 60 ) ) . '" />';

				} else {
					$message .= '<img width="60" height="60" class="round-avatar" src="'. wpjobster_get_first_post_image( $pid, 60, 60 ) .'" />';
				}
			$message .= '</div>';
		}
		$no = $offer_class ? "ten" : "eight";
		$message .= '<div class="' . $no . ' wide column chat-box-title-job ' . $offer_class . '">';
			$message .= '<h2>';

				if (get_post_type($pid) == 'offer') {
					$message .= __("Private transaction with", "wpjobster") . ' ';

					$message .= $uid == $buyer ? $user_name : $buyer_name;
				} else {
					if ($resultrow->job_title) {
						$message .= wpjobster_wrap_the_title($resultrow->job_title, $pid);

					} else {
						$message .= wpjobster_wrap_the_title($ttl, $pid);
					}
				}

			$message .= '</h2>';
			$message .= sprintf( __( "Order: #%s%s", 'wpjobster' ), wpjobster_camouflage_order_id( $current_order->id, $current_order->date_made ), $order_status );

			$message .= ' &nbsp; | &nbsp; ';

			if ( $uid == wpj_get_seller_id( $current_order ) ) {
				$message .= sprintf( __( 'Buyer: %s', 'wpjobster' ), '<a href="' . wpj_get_user_profile_link( $buyer_name ) . '">' . $buyer_name . '</a>' );
			} else {
				$message .= sprintf( __( 'Seller: %s', 'wpjobster' ), '<a href="' . wpj_get_user_profile_link( $user_name ) . '">' . $user_name . '</a>' );
			}

		$message .= '</div>';

		$message .= '<div class="six wide column chat-box-job-price">';
		$message .= '<div class="chat-box-price-job">';
			if ($resultrow->job_price) {
				if($resultrow->job_amount>1)
					$message .= '<span class="amount-nr">' . $resultrow->job_amount . "<span class='x-sign'> x </span></span>";
				$message .= wpjobster_get_show_price_classic($resultrow->job_price, 1);
			}
		$message .= '</div></div></div>';

		$message .= '<ul class="cp-extras">';

		// default extras
		$extran = 'extra_fast';
		$extran_price = 'extra_fast_price';
		$extran_title = __( 'Extra fast delivery', 'wpjobster' );

		if ( $resultrow->$extran != 0 ) {
			if ($resultrow->$extran_price) {
				$message .= '<li>';
				$message .= '<div class="cp-price-extra">';
				if($resultrow->$extran>1)
					$message .= '<span class="amount-nr">' . $resultrow->$extran . ' </span> x ';
				$message .= wpjobster_get_show_price_classic($resultrow->$extran_price, 1) .'</div>';
				$message .= '<div class="cp-title-extra">' .$extran_title. '</div>';
				$message .= '</li>';
			}
		}
		$extran = 'extra_revision';
		$extran_price = 'extra_revision_price';
		$extran_title = __( 'Extra revision', 'wpjobster' );

		if ( $resultrow->$extran != 0 ) {
			if ($resultrow->$extran_price) {
				$message .= '<li>';
				$message .= '<div class="cp-price-extra">';
				if($resultrow->$extran>1)
					$message .= '<span class="amount-nr">' . $resultrow->$extran . '</span> x ';
				$message .= wpjobster_get_show_price_classic($resultrow->$extran_price, 1) .'</div>';
				$message .= '<div class="cp-title-extra">' .$extran_title. '</div>';
				$message .= '</li>';
			}
		}

		for ( $n = 1; $n <= 10; $n++ ) {
			$extran = 'extra' . $n;
			$extran_price = 'extra' . $n . '_price';
			$extran_title = 'extra' . $n . '_title';
			$extran_content = 'extra' . $n . '_content';

			if ( $resultrow->$extran != 0 ) {
				if ($resultrow->$extran_price) {
					$message .= '<li>';
					$message .= '<div class="cp-price-extra">';
					if($resultrow->$extran>1)
						$message .= '<span class="amount-nr">' . $resultrow->$extran . '</span> x ';
					$message .= wpjobster_get_show_price_classic($resultrow->$extran_price, 1) .'</div>';
					$message .= '<div class="cp-title-extra">' .$resultrow->$extran_title. '</div>';
					$message .= '</li>';
				} else {
					$message .= '<li>';
					$message .= '<div class="cp-title-extra">' .get_post_meta($resultrow->pid, $extran_content, true). '</div>';
					$message .= '</li>';
				}
			}
		}

		// show custom extras
		$custom_extras = json_decode($resultrow->custom_extras);
		$total_custom_extras = 0;
		$processing_fees_custom_extras = 0;
		$tax_custom_extras = 0;

		if($custom_extras){
			$i = -1;
			foreach ($custom_extras as $custom_extra){
				$i++;
				if($custom_extra->paid){
					// get order from custom_extras_orders
					global $wpdb;
					$select_extra_ord = "select * from ".$wpdb->prefix."job_custom_extra_orders where order_id='$resultrow->id' and custom_extra_id='$i'";
					$r = $wpdb->get_results($select_extra_ord);
					if($r){
						$custom_extra_ord = $r[0];

						$custom_extra_payment = wpj_get_payment( array(
							'payment_type' => 'custom_extra',
							'payment_type_id' => $custom_extra_ord->id,
						 ) );

						$processing_fees_custom_extras += $custom_extra_payment->fees;
						$tax_custom_extras += $custom_extra_payment->tax;
						$total_custom_extras += $custom_extra_payment->amount;

						$message .= '<li>';
						$message .= '<div class="cp-price-extra">' . wpjobster_get_show_price_classic($custom_extra_ord->custom_extra_amount, 1) .'</div>';
						$message .= '<div class="cp-title-extra">' . __( 'Custom extra:', 'wpjobster' ) . ' ' . $custom_extra->description;
						$message .= '</div>';
						$message .= '</li>';
					}
				}
			}
		}

		// display shipping
		$shipping = get_post_meta($resultrow->pid, 'shipping', true);
		if(!empty($shipping)) {
			$message .= '<li>';
			$message .= '<div class="cp-price-extra">' . wpjobster_get_show_price_classic($shipping, 1) .'</div>';
			$message .= '<div class="cp-title-extra">' .__("Shipping:", "wpjobster"). '</div>';
			$message .= '</li>';
		}

		// display processing fees
		$processing_fees = $resultrow->processing_fees;
		if ( ! is_numeric( $processing_fees ) ) {
			$processing_fees = 0;
		}
		$processing_fees += $processing_fees_custom_extras;
		if ( $processing_fees > 0 ) {
			$message .= '<li>';
			$message .= '<div class="cp-price-extra">' . wpjobster_get_show_price_classic($processing_fees, 1) .'</div>';
			$message .= '<div class="cp-title-extra">' .__("Processing fees:", "wpjobster"). '</div>';
			$message .= '</li>';
		}

		// display tax
		$tax_amount = $resultrow->tax_amount;
		if ( ! is_numeric( $tax_amount ) ) {
			$tax_amount = 0;
		}
		$tax_amount += $tax_custom_extras;
		if ( $tax_amount > 0 ) {
			$message .= '<li>';
			$message .= '<div class="cp-price-extra">' . wpjobster_get_show_price_classic($tax_amount, 1) .'</div>';
			$message .= '<div class="cp-title-extra">' .__("Tax:", "wpjobster"). '</div>';
			$message .= '</li>';
		}

		$total_price = $resultrow->mc_gross + $processing_fees + $tax_amount + $total_custom_extras;

		ob_start();
		do_action( 'list_after_tax_price', $total_price, 'chat_box', $resultrow->payment_gateway );
		$message .= ob_get_contents();
		ob_end_clean();

		$message .= '</ul>';

		$message .= '<div class="cp-total">';
		if ( $resultrow->payment_gateway == 'payoneer' ) {
			$message .= __("Total:", "wpjobster") . ' <strong>' . wpjobster_get_show_price_classic( apply_filters( 'wpj_price_filter', $total_price ), 1 );
		} else {
			$message .= __("Total:", "wpjobster") . ' <strong>' . wpjobster_get_show_price_classic( $total_price, 1 );
		}
		$message .= '</strong></div>';

		return array('message'=>wpj_make_links_clickable( $message ),'tax_amount'=>$tax_amount,'processing_fees'=>$processing_fees);

	}

	public function chat_box_buttons(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}

		$vars2 = $this->chat_box_list_notifications();
		foreach ($vars2 as $key => $value) {
			$$key = $value;
		} ?>

		<script type="text/javascript">
			var oid = "<?php echo $orderid ?>";
		</script>

		<?php if($uid == $buyer || $uid == $user_info->ID){
			// REQUEST MUTUAL CANCELLATION
			if( $uid == $buyer && $completed != 1 && $closed != 1 && $this->chatbox_is_time_up() == 1 && $instant != 1 && $request_cancellation_from_buyer != 1 && $request_cancellation_from_seller != 1 ){
				// -> ORDER CANCELLATION BUTTON
			} else {
				if(isset($can_request_closed) && $can_request_closed == 1):
					if ($request_cancellation_from_seller == 0 and $request_cancellation_from_buyer == 0 and $cancellation_count < get_option('wpjobster_number_of_cancellations')):
						?>

						<div class="request-cancellation-modal-wrapper">
							<a href="#" onclick="event.preventDefault();" class="open-modal-request-cancellation cp-grey"><?php _e("Request Mutual Cancellation", "wpjobster"); ?></a>
						</div>

					<?php endif;
				endif;
			}
			// END REQUEST MUTUAL CANCELLATION BUTTON ?>

			<div class="modal-for-cancellation">
				<?php wpj_modal_request_mutual_cancellation(); ?>
			</div>

			<div class="cp-center-button">
				<!-- CURRENT USER - CONTENT -->
				<?php if ($uid == $user_info->ID):
					if (isset($can_request_closed) && $can_request_closed == 1 && $accept_cancellation_request != -1 && $request_cancellation_from_buyer == 1): ?>

						<!-- ACCEPT/DENY CANCELLATION -->
						<div class="cf pr">
							<a class="ui huge positive uppercase button accept_cancellation submit-button" onclick="clickAndDisable(this);"><?php _e('Accept Cancellation','wpjobster') ?></a>  &nbsp;

							<a class="ui huge negative uppercase button deny_cancellation submit-button" onclick="clickAndDisable(this);"><?php _e('Deny Cancellation','wpjobster') ?></a>

							<span style="display: none;" class="chatbox-loading"></span>
						</div>
						<!-- END ACCEPT/DENY CANCELLATION -->

					<?php elseif ($closed != 1 && $request_cancellation_from_seller == 1): ?>

						<!-- ABORT CANCELLATION -->
						<a class="ui huge uppercase button abort_mutual_cancelation submit-button" onclick="clickAndDisable(this);"><?php _e("Abort Cancellation", "wpjobster"); ?>
							<span style="display: none;" class="chatbox-loading"></span>
						</a>
						<!-- END ABORT CANCELLATION -->

					<?php elseif ($delivered == 0 && $closed != 1 && $request_cancellation_from_buyer != 1 && $request_cancellation_from_seller != 1): ?>

						<!-- MARK AS DELIVERED -->
						<a class="ui huge positive uppercase button mark_delivered submit-button" onclick="clickAndDisable(this);"><?php _e("Mark as Delivered", "wpjobster"); ?>
							<span style="display: none;" class="chatbox-loading"></span>
						</a>
						<!-- END MARK AS DELIVERED -->

					<?php elseif ($completed != 1 && $closed != 1 && $request_cancellation_from_buyer != 1 && $request_cancellation_from_seller != 1): ?>
						<!-- WAITING BUYER TO CONFIRM -->
						<span class="ui huge disabled uppercase button alwaysdisabled"><?php _e('Waiting for the buyer to confirm', 'wpjobster'); ?></span>
						<!-- END WAITING BUYER TO CONFIRM -->

					<?php endif;

				endif;
				// END CURRENT USER - CONTENT
				// OTHER USER - CONTENT
				if ($uid != $user_info->ID):
					if (isset($can_request_closed) && $can_request_closed == 1 && $accept_cancellation_request != -1 && $request_cancellation_from_seller == 1): ?>

						<!-- ACCEPT/DENY CANCELLATION -->
						<div class="cf pr">
							<a class="ui huge positive uppercase button accept_cancellation submit-button" onclick="clickAndDisable(this);"><?php _e('Accept Cancellation','wpjobster') ?></a> &nbsp;

							<a class="ui huge negative uppercase button deny_cancellation submit-button" onclick="clickAndDisable(this);"><?php _e('Deny Cancellation','wpjobster') ?></a>

							<span style="display: none;" class="chatbox-loading"></span>
						</div>
						<!-- END ACCEPT/DENY CANCELLATION -->

					<?php elseif ($closed != 1 && $request_cancellation_from_buyer == 1): ?>

						<!-- ABORT CANCELLATION -->
						<a class="ui huge uppercase button abort_mutual_cancelation submit-button" onclick="clickAndDisable(this);"><?php _e("Abort Cancellation", "wpjobster"); ?>
							<span style="display: none;" class="chatbox-loading"></span>
						</a>
						<!-- END ABORT CANCELLATION -->

					<?php elseif ($completed == 0 && $done_seller == 1 && $closed != 1): ?>

						<!-- MARK COMPLETED -->
						<a class="ui huge positive uppercase button mark_completed submit-button" onclick="clickAndDisable(this);">
							<?php _e("Mark Completed", "wpjobster"); ?>
							<span style="display: none;" class="chatbox-loading"></span>
						</a> &nbsp;
						<!-- END MARK COMPLETED -->

						<!-- REQUEST MODIFICATION -->
						<?php if ($modification_count < get_option('wpjobster_number_of_modifications')) : ?>
							<a href="#" onclick="event.preventDefault();" class="ui huge yellow uppercase button open-modal-request-modifications submit-button" onclick="clickAndDisable(this);"><?php _e('Request Modification', 'wpjobster'); ?></a>
						<?php endif; ?>

						<?php wpj_modal_request_modification(); ?>

						<!-- END REQUEST MODIFICATION -->

					<?php elseif ($delivered != 1 && $closed != 1 && $first_message_sent != 0 && $request_cancellation_from_seller == 0): ?>

						<!-- WAITING SELLER TO DELIVER -->
						<span class="ui huge disabled uppercase button"><?php _e('Waiting for the seller to deliver', 'wpjobster'); ?></span>
						<!-- END WAITING SELLER TO DELIVER -->

					<?php endif;

					if( $completed != 1 && $closed != 1 && $this->chatbox_is_time_up() == 1 && $instant != 1 && $request_cancellation_from_buyer != 1 && $request_cancellation_from_seller != 1 ){ ?>
						<!-- ORDER CANCELLATION -->
						<a class="ui huge negative uppercase button order_cancellation submit-button" onclick="clickAndDisable(this);"><?php _e("Cancel Order", "wpjobster"); ?>
							<span style="display: none;" class="chatbox-loading"></span>
						</a>
						<!-- END ORDER CANCELLATION -->
					<?php }

				endif; ?>
				<!-- END OTHER USER - CONTENT -->

			</div>
		<?php }

		return $buyer_given_feedback;
	}

	public function chat_box_send_message_form(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}
		$order = wpjobster_get_order( $orderid );

		if ($completed != 1 && $closed != 1 && $order->force_cancellation != 2 ) {

			if ( $first_message_sent == 0 && $uid != $user_info->ID && $closed != 1 &&  $delivered != 1 && $request_cancellation_from_seller == 0 && $request_cancellation_from_buyer == 0 && $request_modification == 0 && $this->chatbox_is_time_up() == 0 ) { ?>
				<div class="w100 center">
					<img src="<?php echo get_template_directory_uri().'/images/arrow-down.png'; ?>" />
				</div>
			<?php }

			$_SESSION['formrandomid'] = md5(rand(0,10000000)); ?>

			<div class="ui padded segment cool-message-input">
				<form id="chatbox_message_form" method="post" enctype="multipart/form-data" class="ui form">
				<div class="ui grid">
					<div class="eight wide column">
						<div class="cmi-top message-title-chat-box">
							<?php _e('Enter your Availability', 'wpjobster'); ?>
						</div>
					</div>
					<div class="eight wide column">
						<?php
						$custom_extras = json_decode( $order->custom_extras );
						wpj_get_subscription_info_path();
						$wpjobster_subscription_info = get_wpjobster_subscription_info($uid);
						$max_allowed = 0;
						$total_amount = 0;
						if ( $custom_extras[0] ) {
							foreach ( $custom_extras as $c_extra ) {
								if ( !$c_extra->declined && !$c_extra->cancelled ) {
									$total_amount += $c_extra->price;
								}
							}
						}
						$max_allowed = $wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'] - $total_amount;
						if ($uid == $user_info->ID && get_option('wpjobster_enable_custom_extras')=='yes' && $max_allowed>0){ ?>
							<div class="right">
								<a data-extra="true" data-user="<?php echo $uid; ?>" class="ui nomargin button open-modal-extra-chatbox"><?php _e("Add Custom Extra", "wpjobster"); ?></a>
							</div>
							<?php wpjobster_add_chatbox_scripts(); ?>
							<script type="text/javascript">
								jQuery(document).ready(function($){
									// Open custom extra modal
									$('.open-modal-extra-chatbox').click(function(e){
										e.preventDefault();

										$('.ui.modal.add-extra-chatbox')
										.modal({
											onApprove: function() { return false; },
											onDeny: function() { return false; }
										})
										.modal('show')
										.modal('setting', 'transition', 'fly down')
										.modal('refresh');

										var oid = getUrlParameter('oid');
										var user = $(this).attr('data-user');
										var extra = $(this).attr('data-extra');

										jQuery.ajax({
											type: 'post',
											url : base_main2.ajax_url,
											data: {
												action: 'custom_offers_offer_form',
												oid: oid,
												user: user,
												extra: extra
											},
											success: function( data ) {
												$('.modal-content-add-extra').html(data);
												$('.ui.modal.add-extra-chatbox').modal('refresh');
											}
										});
									});
								});
							</script>
							<?php wpj_modal_add_extra_chatbox();
						} ?>
					</div>
				</div>
				<div class="ui grid">
					<div class="sixteen wide column">
						<input type="hidden" name="formrandomid" value="<?php echo $_SESSION['formrandomid']; ?>" />
						<input type="hidden" name="action" value="chat_box_send_message_post" />
						<input type="hidden" name="order_id" value="<?php echo $orderid; ?>" />

						<textarea class="wpj-grey-textarea chat_box_messages charlimit-message resizevertical cmi-listen" name="messaje"><?php if(isset($_POST['messaje'])  ) echo $_POST['messaje']; ?></textarea>

						<span class="charscounter"> <?php echo __( 'characters left.', 'wpjobster' ); ?></span>
					</div>
				</div>
				<div class="ui grid">
					<div class="eight wide column">
						<?php do_action('wpjobster_add_stuff_to_chat_box_form'); ?>
						<?php wpjobster_theme_attachments_uploader_html5(1,"file_upload_chat_box_attachments", "hidden_files_chat_box_attachments", "chat_box"); ?>
					</div>
					<div class="eight wide column">
						<button class="ui primary button send-message right" type="submit" onclick="clickAndDisable(this);" name="send-message">
							<?php _e('Send','wpjobster'); ?>
						</button>
					</div>
				</div><!-- end grid -->
				</form>

				<script>
					jQuery(document).ready(function($) {
						<?php $wpjobster_characters_message_max = get_option("wpjobster_characters_message_max");
						$wpjobster_characters_message_max = (empty($wpjobster_characters_message_max)|| $wpjobster_characters_message_max==0)?1200:$wpjobster_characters_message_max; ?>

						jQuery(".charlimit-message").counted({count:<?php echo $wpjobster_characters_message_max;?>});
					});
				</script>
			</div>
			<?php
		}
	}

	public function chat_box_rating($buyer_given_feedback){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}

		$buyer_info = get_userdata($buyer);
		$user_infonew = get_userdata($user_info->ID);
		$seller_login = $user_infonew->user_login;
		$ajax_nonce =  wp_create_nonce( "buyer-review" );
		if ($uid != $user_info->ID && isset($completed) && $completed == 1 && isset($done_seller ) && $done_seller == 1) { ?>
			<!-- BUYER RATING -->
			<div class="cf cb-rating-input">
				<script>
				jQuery(document).ready(function() {
					var isClicked = false;
					jQuery('.dd-submit-rating').click(function() {
						jQuery("#chat_box_message_form").hide('slow');

						if (isClicked) {
							return false;
						}else{

							var id = jQuery(this).attr('rel');
							var uprating = jQuery("input[name=stars]:checked", "#rating").val();
							var reason = jQuery("#reason-" + id).val();
							var ajax_nonce = '<?php echo $ajax_nonce; ?>';
							if(reason.length < 10) { alert("<?php _e('Please input a longer description for your rating','wpjobster'); ?>"); isClicked = false; return false; }
							if(uprating === undefined) { alert("<?php _e('Please select the amount of stars','wpjobster'); ?>"); isClicked = false; return false; }
							jQuery.ajax({
								type: "POST",
								url: "<?php echo get_bloginfo('url'); ?>/",
								data: "rate_me=1&ids="+id+"&uprating="+uprating+"&reason="+encodeURIComponent(reason)+"&ajax_nonce="+ajax_nonce,
								success: function(msg){
									jQuery("#post-" + id).hide('slow');
									if ( chatbox_vars.live_notify == 'yes' ) {
										initLiveNotifications();
									} else {
										location.reload();
									}
								}
							});

							isClicked = true;
						}

						return false;
					});
				});
				</script>

				<?php
				global $wpdb;
				$query = "select distinct * from ".$wpdb->prefix."job_ratings where awarded='0' AND orderid='$orderid' AND uid='$user_info->ID'";
				$r = $wpdb->get_results($query);
				if(count($r) > 0) {
					$row = $r[0];
					$post = $row->pid;
					$post = get_post($post); ?>

					<div class="cb-message-input green" id="post-<?php echo $row->id; ?>">
						<p class="center p0b inif"><?php echo sprintf(__('Please rate your experience with %s.', 'wpjobster'), $seller_login); ?></p>

						<div class="center p10b">
							<form class="rating" id="rating">
								<input type="radio" name="stars" id="5_stars" value="5" >
								<label class="stars" for="5_stars"></label>

								<input type="radio" name="stars" id="4_stars" value="4" >
								<label class="stars" for="4_stars"></label>

								<input type="radio" name="stars" id="3_stars" value="3" >
								<label class="stars" for="3_stars"></label>

								<input type="radio" name="stars" id="2_stars" value="2" >
								<label class="stars" for="2_stars"></label>

								<input type="radio" name="stars" id="1_stars" value="1" required>
								<label class="stars" for="1_stars"></label>
							</form>
						</div>

						<div class="p10b">
							<textarea placeholder="<?php _e('Help the community by sharing your order experience...', 'wpjobster'); ?>" class="buyer_rating grey_input white" id="reason-<?php echo $row->id; ?>" rows="2"></textarea>
						</div>

						<div class="cf">
							<a href="#" rel="<?php echo $row->id; ?>" class="ui button dd-submit-rating right"><?php _e('Submit Rating','wpjobster') ?></a>
						</div>

					</div>
					<div class="ui hidden divider"></div>

				<?php } ?>
			</div>
			<!-- END BUYER RATING -->

		<?php } elseif ($uid == $user_info->ID && $completed == 1 && $done_seller == 1 && isset($buyer_given_feedback) && $buyer_given_feedback==1) {
			// SELLER RATING
			$user3 = get_userdata($uid); ?>

			<div class="cf cb-rating-input">

				<script>
					buyer = '<?php echo $buyer;?>';
					pid = '<?php echo $pid;?>';
					oid = '<?php echo $orderid;?>';
					jQuery(document).ready(function() {
						var isClicked = false;
						jQuery('.dd-submit-rating').click(function() {
							jQuery("#chat_box_message_form").hide('slow');

							if (isClicked) {
								return false;
							}else{

								var id = jQuery(this).attr('rel');
								var uprating = jQuery("input[name=stars]:checked", "#rating").val();
								var reason_feedback = jQuery("#reason_feedback-"+id).val();
								if(reason_feedback.length < 10) { alert("<?php _e('Please input a longer description for your rating','wpjobster'); ?>"); isClicked = false; return false; }
								if(uprating === undefined) { alert("<?php _e('Please select the amount of stars','wpjobster'); ?>"); isClicked = false; return false; }
								jQuery.ajax({
									type: "POST",
									url: "<?php echo get_bloginfo('url'); ?>/",
									data: "rate_me=1&uprating="+uprating+"&reason="+encodeURIComponent(reason_feedback)+"&buyer="+buyer+"&orderid="+oid+"&pid="+pid,
									success: function(msg){
										jQuery("#post_feedback" ).hide('slow');
										if ( chatbox_vars.live_notify == 'yes' ) {
											initLiveNotifications();
										} else {
											location.reload();
										}
									}
								});

								isClicked = true;
							}

							return false;
						});
					});
				</script>

				<?php
				global $wpdb;
				$query = "select distinct * from ".$wpdb->prefix."job_ratings where"
						. " awarded='1' AND orderid='$orderid' and orderid not in (select orderid from ".$wpdb->prefix."job_ratings_by_seller where orderid='$orderid'  )";
				$r = $wpdb->get_results($query);
				if(count($r) > 0) {
					$pstid = $r[0]->pid;
					$post = get_post($pstid);
					$user = get_userdata($buyer); ?>

					<div class="cf cb-message-input green" id="post_feedback">

						<p class="center p0b inelse"><?php echo sprintf(__('Please rate your experience with %s.', 'wpjobster'), $buyer_info->user_login); ?></p>

						<div class="center p10b">
							<form class="rating" id="rating">
								<input type="radio" name="stars" id="5_stars" value="5" >
								<label class="stars" for="5_stars"></label>

								<input type="radio" name="stars" id="4_stars" value="4" >
								<label class="stars" for="4_stars"></label>

								<input type="radio" name="stars" id="3_stars" value="3" >
								<label class="stars" for="3_stars"></label>

								<input type="radio" name="stars" id="2_stars" value="2" >
								<label class="stars" for="2_stars"></label>

								<input type="radio" name="stars" id="1_stars" value="1" required>
								<label class="stars" for="1_stars"></label>
							</form>
						</div>

						<div class="p10b">
							<textarea placeholder="<?php _e('Help the community by sharing your order experience...', 'wpjobster'); ?>" class="seller_rating grey_input white" id="reason_feedback-<?php echo $r[0]->id; ?>" rows="2"></textarea>
						</div>

						<div class="cf">
							<a href="#" rel="<?php echo $r[0]->id; ?>" class="ui button dd-submit-rating right"><?php _e('Submit Rating','wpjobster') ?></a>
						</div>

					</div>

				<?php } ?>

			</div>
			<!-- END SELLER RATING -->

		<?php }
	}

	public function chat_box_order_pending(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		} ?>

		<div class="chatbox_post ui notpadded segment">
			<div class="chatbox_post_content">
				<div class="padding-cnt">
					<?php
					echo $message."<br />";
					_e('Deposit Waiting!', 'wpjobster');
					echo '<br>';
					echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway);
					echo '<br>';
					if ($uid != $user_info->ID) { // if is buyer
						_e('The transaction will start as soon as you complete the payment process.', 'wpjobster');
						do_action( 'transaction_incomplete', $orderid );
						echo '<br>'; ?>

						<script>
							function pending_order_process(act,order_id,payment_gateway){
								if(act=='process'){
									window.location="<?php bloginfo('url'); ?>/?pay_for_item="+payment_gateway+"&order_id="+order_id+"&process_pending=1";
									return;
								}else{
									var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
									jQuery.ajax({
										type: "POST",
										url: ajaxurl,
										data: "action=process_pending_order&process="+act+"&order_id=" + order_id,
										success: function(msg){
											if ( chatbox_vars.live_notify == 'yes' ) {
												initLiveNotifications();
											} else {
												location.reload();
											}
										}
									});
								}
							}
						</script>

						<?php
						$pid = $row->pid;
						$post = get_post($row->pid);
						if( isset($post->post_status) && $post->post_status == 'publish') {
							?>

							<br />

							<a class="redlink" href='javascript:void(0)' onclick='pending_order_process("cancel","<?php echo $row->id;?>","<?php echo $row->payment_gateway;?>")'><?php _e( 'Cancel', 'wpjobster' ); ?></a>

							<?php if($row->payment_gateway!='banktransfer'){ ?>
									| <a class="greenlink" href='javascript:void(0)' onclick='pending_order_process("process","<?php echo $row->id;?>","<?php echo $row->payment_gateway;?>")'><?php _e( 'Process', 'wpjobster' ); ?></a>
							<?php } // for every gateway other than banktransfer
						} else {
							echo '<br />';
							_e('This job is not available anymore', 'wpjobster');
						}
					} else {
							_e('The transaction will start as soon as the buyer pays.', 'wpjobster');
							echo '<br>';
					} ?>
				</div>
			</div>
		</div>
		<div class="ui hidden divider"></div>
	<?php }

	public function chat_box_order_complete(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		}

		if ($uid == $user_info->ID) { //seller here
			$other_uid_data = get_userdata($other_uid); ?>
			<p class="center p0b"><?php echo sprintf(__("This order is complete. Click %s to contact the buyer",'wpjobster'), '<a href="'.$privurl_m.'username='.$other_uid_data->user_login.'">'.__("here","wpjobster").'</a>'); ?></p>
		<?php } else { //buyer here ?>
			<p class="center p0b"><?php echo sprintf(__("This order is complete. Click %s to contact the seller",'wpjobster'), '<a href="'.$privurl_m.'username='.$user_info->user_login.'">'.__("here","wpjobster").'</a>'); ?></p>
		<?php }
	}

	public function chat_box_order_failed(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		} ?>

		<div class="chatbox_post ui notpadded segment">
			<div class="chatbox_post_content">
				<div class="padding-cnt">
					<?php echo $message."<br />"; ?>
					<?php _e('Failed!', 'wpjobster'); ?><br>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway); ?><br>
				</div>
			</div>
		</div>

		<div class="ui divider hidden"></div>

	<?php }

	public function chat_box_order_processing(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		} ?>

		<div class="chatbox_post ui notpadded segment">
			<div class="chatbox_post_content">
				<div class="padding-cnt">
					<?php echo $message."<br />"; ?>
					<?php _e('Processing!', 'wpjobster'); ?><br>
					<?php echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway); ?><br>
				</div>
			</div>
		</div>

		<div class="ui divider hidden"></div>

	<?php }

	public function chat_box_order_cancelled(){
		$vars = $this->chat_box_init();
		foreach ($vars as $key => $value) {
			$$key = $value;
		} ?>

		<div class="chatbox_post ui notpadded segment">
			<div class="chatbox_post_content">
				<div class="padding-cnt">
					<?php
					echo $message."<br />";
					if( $row->force_cancellation == 1 ){
						_e('Admin cancelled the order!', 'wpjobster');
					}else{
						_e('Order cancelled!', 'wpjobster');
					}
					echo '<br>';
					do_action( 'wpj_gateway_transaction_cancelled_message', $orderid, 'job_purchase'  );
					echo __('Method','wpjobster') .': ' . wpjobster_translate_string($current_order->payment_gateway); ?><br>
				</div>
			</div>
		</div>

		<div class="ui divider hidden"></div>

	<?php }

	public function chat_box_get_order_time(){

		$order_date = wpj_get_payment( array(
			'payment_type' => 'job_purchase',
			'payment_type_id' => $this->orderid,
		) );

		if( $order_date ){
			if ( $order_date->payment_made_on && $order_date->payment_made_on != 0 ) {
				$date = $order_date->payment_made_on;
			} else {
				$date = $order_date->datemade;
			}
		}else{
			$date = date( 'D M d Y H:i:s O' );
		}

		$order = wpjobster_get_order( $this->orderid );
		$pid = $order->pid;

		$job_max_days = get_post_meta( $pid, 'max_days', true );
		$order_expected_delivery = $order->expected_delivery;

		$job_fast_delivery_days = get_post_meta($pid, 'extra_fast_days', true);
		$order_fast_delivery_days = $order->extra_fast_days;

		if( $order_expected_delivery && $order_expected_delivery != 0 ){
			$timer_date = date( 'D M d Y H:i:s O', $order_expected_delivery );
		}elseif( $order->extra_fast != 0 ){
			$timer_date = date( 'D M d Y H:i:s O', $date + ( 24 * 3600 * $job_fast_delivery_days ) );
		}else{
			$timer_date = date( 'D M d Y H:i:s O', $date + ( 24 * 3600 * $job_max_days ) );
		}

		return $timer_date;
	}

	public function chatbox_is_time_up(){
		$orderid      = $this->orderid;

		$current_date = strtotime( date( 'Y-m-d H:i:s' ) );
		$order_date   = strtotime( $this->chat_box_get_order_time() );

		if( $current_date <= $order_date ){
			return 0;
		}else{
			return 1;
		}
	}

}

/* END CHATBOX CLASS */

/* CHATBOX AJAX FUNCTIONS */

// AJAX Send Message System
add_action( 'wp_ajax_chat_box_send_message_post', 'chat_box_send_message_post' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_chat_box_send_message_post', 'chat_box_send_message_post' ); // ajax for not logged in users
function chat_box_send_message_post(){
	$chat_box = new WPJobsterChatBox( $_POST['order_id'], 'chat_box' );

	$vars = $chat_box->chat_box_init();
	foreach ($vars as $key => $value) {
		$$key = $value;
	} ?>

	<?php
	$_SESSION['formrandomid'] = '';

	// reset status
	$_SESSION['cb_message_status'] = "";
	$wpjobster_characters_message_max = get_option("wpjobster_characters_message_max");
	$wpjobster_characters_message_max = (empty($wpjobster_characters_message_max)|| $wpjobster_characters_message_max==0)?1200:$wpjobster_characters_message_max;
	$wpjobster_characters_message_min = get_option("wpjobster_characters_message_min");
	$wpjobster_characters_message_min = (empty($wpjobster_characters_message_min))?0:$wpjobster_characters_message_min;

	$messaje = trim(nl2br(strip_tags(htmlspecialchars(wpj_encode_emoji($_POST['messaje'])))));
	$datemade = current_time('timestamp', 1);

	global $wpdb;
	$pref = $wpdb->prefix;
	$msg2 = get_parsed_countable_string($_POST['messaje']);

	if( mb_strlen($msg2) == 0 || mb_strlen($msg2) < $wpjobster_characters_message_min || mb_strlen($msg2) > $wpjobster_characters_message_max ) {
		$isOk = 0;
		$_SESSION['error_message'] = sprintf(__('The message needs to have at least %d characters and %d at most!', 'wpjobster'), $wpjobster_characters_message_min, $wpjobster_characters_message_max);
		$_SESSION['cb_message_status'] = "empty";
	} else {
		$_SESSION['cb_message_status'] = "sent";

		// attach files
		if ( $_POST['hidden_files_chat_box_attachments'] ) {
			$attachment = $_POST['hidden_files_chat_box_attachments'];
		} else {
			$attachment = "";
		}

		if (!is_demo_user()) {
			$g1 = "insert into ".$pref."job_chatbox (datemade, uid, oid, content, attachment) values('$datemade','$uid','$orderid','$messaje', '$attachment')";
			$wpdb->query($g1);
			$this_message = $wpdb->insert_id; // !!! saved this first, because it changes after the first add_post_meta in the loop below !!!
			$attachments = explode( ',', $attachment );
			foreach ( $attachments as $attachment ) {
				add_post_meta( $attachment, 'message_id', $this_message );
			}
		}

		$_SESSION['tried_to_leave'] = 0;
		$_SESSION['first_time_here'] = 0;

		if($uid == $post_a->post_author) $uid_to_send = $other_uid;
		else $uid_to_send = $post_a->post_author;

		if (!is_demo_user()) {
			wpj_update_user_notifications( $uid_to_send, 'notifications', +1 );

			wpjobster_send_email_allinone_translated('order_message', $uid_to_send, $uid, $pid, $orderid);
			wpjobster_send_sms_allinone_translated('order_message', $uid_to_send, $uid, $pid, $orderid);
		}
	}

	if(!empty($_SESSION['cb_message_status'])){

		if ($_SESSION['cb_message_status'] == "empty"):
			if(isset($_SESSION['error_message']) && $_SESSION['error_message']!=''){
				echo '<div class="error">'. $_SESSION['error_message']. '</div>';
			}else{
				echo '<div class="error">'. __("ERROR! Please enter a message.","wpjobster"). '</div>';
			}
		endif;

		$_SESSION['cb_message_status'] = "";

	}

	wp_die();
}

// AJAX Cancel Custom Extra
add_action( 'wp_ajax_chat_box_cancel_custom_extra', 'chat_box_cancel_custom_extra' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_chat_box_cancel_custom_extra', 'chat_box_cancel_custom_extra' ); // ajax for not logged in users
function chat_box_cancel_custom_extra(){
	$chat_box = new WPJobsterChatBox( $_POST['order_id'], 'chat_box' );

	$vars = $chat_box->chat_box_init();
	foreach ($vars as $key => $value) {
		$$key = $value;
	}

	if (isset($_POST['cancel_custom_extra'] ) && $_POST['cancel_custom_extra'] == 1 && isset($_POST['custom_extra']) && $uid!=$current_order->uid) {
		$ind = $_POST['custom_extra'];
		$custom_extras = json_decode($current_order->custom_extras);
		if($custom_extras[$ind]->cancelled == false && $custom_extras[$ind]->declined == false && $custom_extras[$ind]->paid == false && $completed != 1 && $closed != 1){
			// check if payment is pending
			$q = "select * from ".$wpdb->prefix."job_custom_extra_orders where order_id='".$current_order->id."' and custom_extra_id='".$ind."'";
			$r = $wpdb->get_results($q);
			if( ! isset( $r[0] ) && ! $r[0] ) {
				$custom_extras[ $ind ]->cancelled = true;
				wpjobster_update_order_meta( $current_order->id, 'custom_extras', json_encode( $custom_extras ) );
				$current_order = wpjobster_get_order( $orderid );

				wpjobster_send_email_allinone_translated( 'cancel_custom_extra', $current_order->uid, $uid, false, $current_order->id );
				wpjobster_send_sms_allinone_translated( 'cancel_custom_extra', $current_order->uid, $uid, false, $current_order->id );

				$pref     = $wpdb->prefix;
				$datemade = current_time( 'timestamp', 0 );
				$g1       = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','-32','$current_order->id','$ind')";
				$wpdb->query( $g1 );
				wpj_update_user_notifications( $current_order->uid, 'notifications', +1 );
			}
		}
	}

	wp_die();
}

// AJAX Deny Custom Extra
add_action( 'wp_ajax_chat_box_deny_custom_extra', 'chat_box_deny_custom_extra' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_chat_box_deny_custom_extra', 'chat_box_deny_custom_extra' ); // ajax for not logged in users
function chat_box_deny_custom_extra(){
	$chat_box = new WPJobsterChatBox( $_POST['order_id'], 'chat_box' );

	$vars = $chat_box->chat_box_init();
	foreach ($vars as $key => $value) {
		$$key = $value;
	}

	if (isset($_POST['decline_custom_extra'] ) && $_POST['decline_custom_extra'] == 1 && isset($_POST['custom_extra']) && $uid==$current_order->uid) {
		$ind = $_POST['custom_extra'];
		$custom_extras = json_decode($current_order->custom_extras);
		if($custom_extras[$ind]->declined == false && $custom_extras[$ind]->declined == false && $custom_extras[$ind]->paid == false && $completed != 1 && $closed != 1){
			$custom_extras[$ind]->declined = true;
			wpjobster_update_order_meta($current_order->id, 'custom_extras', json_encode($custom_extras));
			$current_order = wpjobster_get_order($orderid);

			wpjobster_send_email_allinone_translated('decline_custom_extra', $post_a->post_author, $current_order->uid, false, $current_order->id);
			wpjobster_send_sms_allinone_translated('decline_custom_extra', $post_a->post_author, $current_order->uid, false, $current_order->id);

			$pref = $wpdb->prefix;
			$datemade = current_time('timestamp',0);
			$g1 = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','-33','$current_order->id','$ind')";
			$wpdb->query($g1);
			wpj_update_user_notifications( $post_a->post_author, 'notifications', +1 );
		}
	}

	wp_die();
}

/* END CHATBOX AJAX FUNCTIONS */
