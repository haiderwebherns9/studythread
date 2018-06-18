<?php

/**
 * Wpjobster Custom Offers
 */
function custom_offers_enqueue_scripts(){
	$dependencies = array(
		'jquery'
	);

	// Generic
	wp_enqueue_style( 'custom-offers-style', get_template_directory_uri() . "/lib/custom_offers/style.css" );
	wp_enqueue_script( 'custom-offers-script', get_template_directory_uri() . '/lib/custom_offers/scripts.js', $dependencies  );
	wp_localize_script( 'custom-offers-script', '_custom_offers_settings', custom_offers_localized_js() );
}

add_action( 'wp_enqueue_scripts', 'custom_offers_enqueue_scripts');

add_action( 'wp_ajax_custom_offers_request_form', 'custom_offers_request_form' );
add_action( 'wp_ajax_nopriv_custom_offers_request_form', 'custom_offers_request_form' );

add_action( 'wp_ajax_custom_offers_offer_form', 'custom_offers_offer_form' );
add_action( 'wp_ajax_nopriv_custom_offers_offer_form', 'custom_offers_offer_form' );

add_action( 'wp_ajax_custom_offers_request_submit', 'custom_offers_request_submit' );
add_action( 'wp_ajax_nopriv_custom_offers_request_submit', 'custom_offers_request_submit' );

add_action( 'wp_ajax_custom_offers_offer_submit', 'custom_offers_offer_submit' );
add_action( 'wp_ajax_nopriv_custom_offers_offer_submit', 'custom_offers_offer_submit' );

function custom_offers_localized_js() {
	$defaults = array(
		'ajaxurl'           => admin_url( "admin-ajax.php" ),
		'is_user_logged_in' => is_user_logged_in() ? 1 : 0,
		'close_text'        => __( 'Close', 'wpjobster' ),
		'live_notify'       => get_option( 'wpjobster_enable_live_notifications' )
	);

	return $defaults;
}


/**
 * Validation status responses
 */
function custom_offers_status( $key = null, $extra = false ) {

	if( $extra ){
		$price_min = 1;
		$price_max = get_current_user_max_custom_extra();
	}
	else{
		$price_min = get_option( 'wpjobster_offer_price_min' );
		$price_max = get_option( 'wpjobster_offer_price_max' );

		if ( ! is_numeric( $price_min ) || ! is_numeric( $price_max ) || $price_min > $price_max ) {
			$price_min = 5;
			$price_max = 5000;
		}
	}

	$status = array(

		'ok' => array(
			'description' =>  __( 'All good', 'wpjobster' ),
			'cssClass' => 'noon',
			'code' => 'success'
			),
		'message_sent' => array(
			'description' => __( 'Your message was successfully sent.', 'wpjobster' ),
			'cssClass' => 'success-container',
			'code' => 'success'
			),
		'request_sent' => array(
			'description' => __( 'Your request was successfully sent.', 'wpjobster' ),
			'cssClass' => 'success-container',
			'code' => 'success'
			),
		'offer_sent' => array(
			'description' => __( 'Your offer was successfully sent.', 'wpjobster' ),
			'cssClass' => 'success-container',
			'code' => 'success'
			),
		'message_already_sent' => array(
			'description' => __( 'Your message was already sent.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'request_already_sent' => array(
			'description' => __( 'Your request was already sent.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'offer_already_sent' => array(
			'description' => __( 'Your offer was already sent.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'something_wrong' => array(
			'description' => __( 'Something went wrong... Please try again.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'empty_message' => array(
			'description' => __( 'The message cannot be empty.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'empty_request' => array(
			'description' => __( 'The request cannot be empty.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'empty_offer' => array(
			'description' => __( 'The offer cannot be empty.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'request_yourself' => array(
			'description' => __( 'You cannot send a request to yourself.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'offer_yourself' => array(
			'description' => __( 'You cannot send a offer to yourself.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'wrong_price' => array(
			'description' => __( 'The price must be in the following range:', 'wpjobster' ) . ' ' . $price_min . ' - ' . $price_max . ' ' . wpjobster_get_currency_classic(),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'wrong_delivery' => array(
			'description' => sprintf( __( 'The delivery time must be between 1 and %s days.', 'wpjobster' ), get_option( 'wpjobster_request_max_delivery_days' ) ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'not_eligible' => array(
			'description' => __( 'You need to have at least one active job in order to send custom offers.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
			),
		'custom_extras_not_enabled' => array(
			'description' => __( 'Custom extras are not enabled.', 'wpjobster' ),
			'cssClass' => 'error-container',
			'code' => 'error'
		)
		);
	if($extra){
		$status['offer_sent']['description'] = __( 'Your extra was successfully sent.', 'wpjobster' );
		$status['offer_already_sent']['description'] = __( 'Your extra was already sent.', 'wpjobster' );
		$status['empty_offer']['description'] = __( 'The extra cannot be empty.', 'wpjobster' );
		$status['offer_yourself']['description'] = __( 'You cannot send an extra to yourself.', 'wpjobster' );
		$status['not_eligible']['description'] = __( 'You need to have at least one active job in order to send custom extras.', 'wpjobster' );
	}


	if (!empty($key)) {
		return $status[$key];
	} else {
		return $status['something_wrong'];
	}
}

/**
 * LOAD the request and offer forms via AJAX.
 */
if ( ! function_exists( 'custom_offers_request_form' ) ) {
	function custom_offers_request_form() {
		if ( isset( $_REQUEST ) ) {
			?>
			<div class="custom-offers-default-container">
				<form action="javascript://" name="requestform" class="custom-offers-default-form-container request_form ui form">
					<div class="form-wrapper">
						<div class="custom-offers-status-container">
							<div class="custom-offers-msg-target"></div>
						</div>

						<div class="noon"><textarea required name="content" placeholder="<?php _e( 'Mention the Dates and Time', 'wpjobster' ); ?>" class="request_message"></textarea></div>

						<?php $_SESSION['formrandomid'] = md5( rand( 0,10000000 ) ); ?>
						<input type="hidden" name="formrandomid" value="<?php echo $_SESSION['formrandomid']; ?>" />
						<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
						<input type="hidden" name="jid" value="<?php echo $_POST['jid']; ?>" />
                           <div class="two fields">
						     <div class="field">
							      <label>Price(USD)</label>
							     <input type="number" name="offer_price"/>
                                </div>	
                                <div class="field">
							      <label>Days in Month</label>
							     <input type="number" min="1" max="31"  name="offer_days"/>
                                </div>										
						 </div>
						<div class="noon cf">
							<?php //wpjobster_theme_attachments_uploader_html5( $secure=1, "file_upload_custom_offer_attachments", "hidden_files_custom_offer_attachments", "custom_offer" ); ?>
						</div>

						<div class="actions">
							<button class="ui positive uppercase labeled icon button semantic-req-btn" data-modal-user="<?php echo $_POST['user']; ?>" type="submit" name="request">
								<?php _e( 'Submit Request', 'wpjobster' ); ?>
								<i class="checkmark icon"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
			<?php
			}
			die();
		}
}

function get_current_user_max_custom_extra(){
	if ( is_user_logged_in() ){
		wpj_get_subscription_info_path();
		$current_user = wp_get_current_user();
		$wpjobster_subscription_info = get_wpjobster_subscription_info( $current_user->ID );
		$oid = isset( $_POST['oid'] ) ? $_POST['oid'] : '';
		$order = wpjobster_get_order( $oid );
		$custom_extras = json_decode( $order->custom_extras );
		if ( $custom_extras[0] ) {
			$total_amount = 0;
			foreach ( $custom_extras as $c_extra ) {
				if ( !$c_extra->declined && !$c_extra->cancelled ) {
					$total_amount += $c_extra->price;
				}
			}
			return $wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'] - $total_amount;
		}
		else
			return $wpjobster_subscription_info['wpjobster_subscription_max_custom_extras'];
	}
	else
		return 0;
};

function custom_offers_offer_form() {
	if ( isset( $_REQUEST ) ) {

		$is_extra = ( isset( $_POST['extra'] ) && $_POST['extra']=='true' );

		if($is_extra){
			$price_min = 1;
			$price_max = get_current_user_max_custom_extra();
		}
		else{
			$price_min = get_option( 'wpjobster_offer_price_min' );
			$price_max = get_option( 'wpjobster_offer_price_max' );

			if (!is_numeric($price_min) || !is_numeric($price_max) || $price_min > $price_max) {
				$price_min = 5;
				$price_max = 5000;
			}
		}
		?>
		<div class="custom-offers-default-container alz">
		<?php if ( is_user_logged_in() ) { ?>
			<form action="javascript://" name="offerform" class="custom-offers-default-form-container offer_form ui form">
				<div class="custom-offers-status-container">
					<div class="custom-offers-msg-target"></div>
				</div>

					<?php
					$wpjobster_characters_customextra_max = get_option("wpjobster_characters_customextra_max");
					$wpjobster_characters_customextra_max = (empty($wpjobster_characters_customextra_max)|| $wpjobster_characters_customextra_max==0)?50:$wpjobster_characters_customextra_max;

					$wpjobster_characters_customextra_min = get_option("wpjobster_characters_customextra_min");
					$wpjobster_characters_customextra_min = (empty($wpjobster_characters_customextra_min))?0:$wpjobster_characters_customextra_min;
					?>

					<div class="field">
						<textarea required name="content" rows="5" placeholder="<?php _e( 'Mention the Dates and Time', 'wpjobster' ); ?>" minlength="<?php echo $wpjobster_characters_customextra_min; ?>" maxlength="<?php echo $wpjobster_characters_customextra_max; ?>" class="offer_description"></textarea>
					</div>

				<?php $_SESSION['formrandomid'] = md5(rand(0,10000000)); ?>
				<input type="hidden" name="formrandomid" value="<?php echo $_SESSION['formrandomid']; ?>" />
				<?php if ( $is_extra ) { ?>
					<input type="hidden" name="custom_extra_form" value="true" />
					<input type="hidden" name="oid" value="<?php echo $_POST['oid']; ?>" />
				<?php } ?>
				<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
				<input type="hidden" name="associate_request_id" value="<?php echo WPJ_Form::post( 'associate_request_id', '' ); ?>" />
				<input type="hidden" name="jid" value="<?php echo WPJ_Form::post( 'jid', '' ); ?>" />
				<input type="hidden" name="page" value="<?php echo WPJ_Form::post( 'page', '' ); ?>" />

				<div class="field">
					<div class="two fields">
						<div class="field">
							<label><?php _e( 'Price (USD)', 'wpjobster' ); ?></label>
							<input type="number" step="0.01" min="<?php echo $price_min; ?>" max="<?php echo $price_max; ?>" required name="price" placeholder="<?php echo wpjobster_get_show_price_classic( $price_min, 1 ) . ' - ' . wpjobster_get_show_price_classic( $price_max, 1 ); ?>" />
						</div>

						<div class="field">
							<label><?php _e( 'Days in Month', 'wpjobster' ); ?></label>
							<input type="number" min="1" max="<?php echo get_option( 'wpjobster_request_max_delivery_days' ); ?>" required name="delivery" placeholder="1-<?php echo get_option( 'wpjobster_request_max_delivery_days' ); ?>" />
						</div>
					</div>
				</div>

				<div class="actions">
					<button class="ui positive uppercase labeled icon button offer_button custom-offer pm_ajax" type="submit" name="offer">
						<?php if ( $is_extra ) _e( 'Submit Custom Extra', 'wpjobster' ); else _e( 'Submit Custom Offer', 'wpjobster' ); ?>
						<i class="checkmark icon"></i>
					</button>
				</div>
			</form>
		<?php
		}
		else {
			_e("Not logged in", "wpjobster");
		}
		?>
		</div>
	<?php
	}
	die();
}

/**
 * SUBMIT the request and offer forms via AJAX.
 */
function custom_offers_request_submit( $content = null, $datemade = 0, $initiator = 0, $user = 0, $attached = 0, $custom_offer = -1 ) {
    
	if ( $_POST['formrandomid'] == $_SESSION['formrandomid'] ) {

		$content = empty( $_POST['content'] ) ? $content : trim( nl2br( strip_tags( htmlspecialchars( wpj_encode_emoji($_POST['content'] ) ) ) ) );

		$user = empty( $_POST['user'] ) ? $user : $_POST['user'];
		
		$offer_price = empty($_POST['offer_price']) ? $offer_price : $_POST['offer_price'];
		$offer_day = empty($_POST['offer_days']) ? $offer_day : $_POST['offer_days'];

		if ( get_current_user_id() == $user ) {
			$msg = custom_offers_status( 'request_yourself' );
		} elseif (!$content) {
			$msg = custom_offers_status( 'empty_request' );
		} else {
			$_SESSION['formrandomid'] = '';
			$datemade = current_time('timestamp', 1);
			$initiator = get_current_user_id();

			$attached = empty( $_POST['hidden_files_custom_offer_attachments'] ) ? $attached : $_POST['hidden_files_custom_offer_attachments'];

			$jid = $_POST['jid'];

			if (!is_demo_user()) {
				wpj_insert_private_message( array(
					'content'          => $content,
					'datemade'         => $datemade,
					'initiator'        => $initiator,
					'user'             => $user,
					'offer_price'  => $offer_price,
					'offer_day'             =>$offer_day,
					'attached'         => $attached ,
					'custom_offer'     => $custom_offer,
					'associate_job_id' => $jid
				));
			}

			$msg = custom_offers_status( 'request_sent' );
		}

	} else {
		$msg = custom_offers_status( 'request_already_sent' );
	}

	wp_send_json( $msg );
}

function custom_offers_offer_submit( $content = null, $datemade = 0, $initiator = 0, $user = 0, $price = 0, $delivery = 0, $send_json = true , $jid='') {

	$active_job_required = get_option( 'wpjobster_active_job_cutom_offer' );
	$enable_custom_extras = get_option( 'wpjobster_enable_custom_extras' );

	$custom_extra_form = ( ! empty( $_POST['custom_extra_form'] ) && $_POST['custom_extra_form'] == 'true' );
	if ( $custom_extra_form && $enable_custom_extras!='yes' ) {
		$msg = custom_offers_status( 'custom_extras_not_enabled' );
	}
	elseif ( $active_job_required == 'yes' && wpjobster_nr_active_jobs( get_current_user_id() ) < 1 ) {
		$msg = custom_offers_status( 'not_eligible' );
	} else {

		$content = empty( $_POST['content'] ) ? $content : trim( nl2br( strip_tags( htmlspecialchars( wpj_encode_emoji( $_POST['content'] ) ) ) ) );

		$price = empty( $_POST['price'] ) ? $price : $_POST['price'];
		$delivery = empty( $_POST['delivery'] ) ? $delivery : $_POST['delivery'];
		$user = empty( $_POST['user'] ) ? $user : $_POST['user'];
		$associate_request_id = empty($_POST['associate_request_id']) ? 0 : $_POST['associate_request_id'];
		$oid = WPJ_Form::post( 'oid', 0 );

		if( $custom_extra_form ) {
			$price_min = 1;
			$price_max = get_current_user_max_custom_extra();
		}
		else{
			$price_min = get_option( 'wpjobster_offer_price_min' );
			$price_max = get_option( 'wpjobster_offer_price_max' );

			if ( ! is_numeric( $price_min ) || !is_numeric( $price_max ) || $price_min > $price_max ) {
				$price_min = 5;
				$price_max = 5000;
			}
		}

		if ( get_current_user_id() == $user && !$custom_extra_form ) {
			$msg = custom_offers_status( 'offer_yourself' );
		} elseif ( ! $content ) {
			$msg = custom_offers_status( 'empty_offer' );
		} elseif ( $price < $price_min || $price > $price_max ) {
			if( ! $custom_extra_form )
				$msg = custom_offers_status( 'wrong_price' );
			else
				$msg = custom_offers_status( 'wrong_price', true );
		} elseif ( $delivery < 1 || $delivery > get_option( 'wpjobster_request_max_delivery_days' ) ) {
			$msg = custom_offers_status( 'wrong_delivery' );
		} elseif ( $custom_extra_form && !$oid ) {
			$msg = custom_offers_status( 'something_wrong', true );
		} elseif ( $custom_extra_form && get_option( 'wpjobster_enable_custom_extras' ) != 'yes' ) {
			$msg = custom_offers_status( 'something_wrong', true );
		} else {
			$_SESSION['formrandomid'] = '';
			$datemade = current_time( 'timestamp', 1 );
			$initiator = get_current_user_id();

			$initiator_data = get_userdata( $initiator );
			$user_data = get_userdata( $user );

			if( ! $custom_extra_form ) {

				$post_title = sprintf(__("Private transaction between %s and %s", "wpjobster"), $initiator_data->user_login, $user_data->user_login);

				$post = array(
					'post_title'   => $post_title,
					'post_content' => $content,
					'post_author'  => $initiator,
					'post_status'  => 'publish',
					'post_type'    => 'offer'
				);
				$post_id = wp_insert_post( $post );

				if ($post_id == 0) {
					$msg = custom_offers_status( 'something_wrong' );

				} else {

					if (!is_demo_user()) {

						update_post_meta( $post_id, 'price', $price );
						update_post_meta( $post_id, 'max_days', $delivery );
						update_post_meta( $post_id, 'offer_buyer', $user );
						update_post_meta( $post_id, 'offer_date', $datemade );
						update_post_meta( $post_id, 'offer_date_expire', strtotime( '+7 days', $datemade ) );
						update_post_meta( $post_id, 'offer_accepted', 0 );
						update_post_meta( $post_id, 'offer_declined', 0 );
						update_post_meta( $post_id, 'offer_withdrawn', 0 );
						update_post_meta( $post_id, 'offer_expired', 0 );

						$this_pm = wpj_insert_private_message( array(
							'content'           => $content,
							'datemade'          => $datemade,
							'initiator'         => $initiator,
							'user'              => $user,
							'custom_offer'      => $post_id,
							'associate_request_id' => $associate_request_id,
							'associate_job_id'     => $jid
						) );
					}

					$msg = custom_offers_status( 'offer_sent' );
					$msg['this_pm'] = $this_pm;
				}

			}
			else{
				// is custom extra
				$order = wpjobster_get_order( $oid );
				if ($order->uid==get_current_user_id()) {
					$msg = custom_offers_status( 'something_wrong', true );
				}
				else{
					$new_custom_extra['description'] = $content;
					$new_custom_extra['price'] = $price;
					$new_custom_extra['delivery'] = $delivery;
					$new_custom_extra['time'] = current_time( 'timestamp', 1 );
					$new_custom_extra['paid'] = false;
					$new_custom_extra['declined'] = false;
					$new_custom_extra['cancelled'] = false;
					if(!$order->custom_extras)
						$order->custom_extras = array();
					else
						$order->custom_extras = json_decode( $order->custom_extras );
					array_push( $order->custom_extras, $new_custom_extra );
					$count = count( $order->custom_extras )-1;
					wpjobster_update_order_meta( $oid, 'custom_extras', json_encode( $order->custom_extras ) );

					global $wpdb;
					$pref = $wpdb->prefix;
					$datemade = current_time( 'timestamp', 1 );
					$g1 = "insert into " . $pref . "job_chatbox (datemade, uid, oid, content) values('$datemade','-31','$oid','$count')";
					$wpdb->query( $g1 );
					wpj_update_user_notifications( $order->uid, 'notifications', +1 );

					//$msg = $order;
					$msg = custom_offers_status( 'offer_sent', true );
					wpjobster_send_email_allinone_translated( 'new_custom_extra', $order->uid, $user, false, $oid );
					wpjobster_send_sms_allinone_translated( 'new_custom_extra', $order->uid, $user, false, $oid );
				}
			}
		}

	}

	if ( $send_json == true ) {
		wp_send_json( $msg );
	} else {
		return $msg;
	}
}
