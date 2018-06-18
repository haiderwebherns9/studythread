<?php
//-------------------------
// (c) WPJobster My Account
//-------------------------
if (!function_exists('wpjobster_get_post_small_new')) {

	function wpjobster_get_post_small_new($arr = '') {

		if (isset($arr[0]) && $arr[0] == "winner") $pay_this_me = 1;
		if (isset($arr[0]) && $arr[0] == "unpaid") $unpaid = 1;
		$ending = get_post_meta(get_the_ID(), 'ending', true);
		$sec = $ending - time();
		$closed = get_post_meta(get_the_ID(), 'closed', true);
		$active = get_post_meta(get_the_ID(), 'active', true);
		$featured = get_post_meta(get_the_ID(), 'featured', true);
		$paid = get_post_meta(get_the_ID(), 'paid', true);
		$post = get_post(get_the_ID());
		$featured = get_post_meta(get_the_ID(), 'featured', true);
		$under_review = get_post_meta(get_the_ID(), "under_review", true);
		$more_extras = get_post_meta(get_the_ID(), "more_extras", true);
		$more_extra_price = get_post_meta(get_the_ID(), "more_extra_price", true);
		$more_job_price = get_post_meta(get_the_ID(), "more_job_price", true);
		$img_class = "image_class";
		$post = get_post(get_the_ID());
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		global $wpjobster_currencies_array;
		$pid = get_the_ID();
		$img = wpjobster_get_job_image( $pid ); ?>

		<div class="ui two stackable grid grid user-jobs background-delete-<?php echo $pid; ?>">
			<div class="two wide column">
				<a href="<?php the_permalink(); ?>"><img width="60" height="60" class="round-avatar <?php echo $img_class; ?>" src="<?php echo $img; ?>" /></a>
			</div>
			<div class="six wide column">
				<h4 class="small-heading-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h4>

				<ul class="job-edit-deac-delete">
					<li>
						<a class="btn green important lighter smallest p5r" href="<?php bloginfo('url'); ?>/?jb_action=edit_job&jobid=<?php the_ID(); ?>" class="edit_job"><?php _e('Edit', 'wpjobster'); ?></a>
					</li>


					<?php
						$pid = get_the_ID();
						if ($active == "1"):
					?>
					<li>
						<a class="btn blue important lighter smallest p5r open-deactivate-job" data-id="<?php echo $pid; ?>" data-title-deactivate="<?php the_title(); ?>"><?php _e('Deactivate', 'wpjobster'); ?></a>
					</li>
					<?php  else : ?>

					<li>
						<a class="btn blue lighter smallest p5r open-activate-job" data-id="<?php echo $pid; ?>" data-title-activate="<?php the_title(); ?>"><?php _e('Activate', 'wpjobster'); ?></a>
					</li>
					<?php endif; ?>

					<li>
						<a class="btn red lighter smallest p5r open-delete-job" data-id="<?php echo $pid; ?>" data-title-delete="<?php the_title(); ?>"><?php _e('Delete', 'wpjobster'); ?></a>
					</li>

					<!-- feature button -->
					<?php
					$feature_enabled = get_option('wpjobster_featured_enable');
					if($feature_enabled=='yes'):

					$g=0;
					$ppid = get_the_ID();
					if(get_post_meta($ppid, 'home_featured_until', true)=='z' || get_post_meta($ppid, 'home_featured_until', true)==false) $g++;
					if(get_post_meta($ppid, 'category_featured_until', true)=='z' || get_post_meta($ppid, 'category_featured_until', true)==false) $g++;
					if(get_post_meta($ppid, 'subcategory_featured_until', true)=='z' || get_post_meta($ppid, 'subcategory_featured_until', true)==false) $g++;
					if($g==0){ ?>

					<li>
						<a class="btn feature lighter smallest p5r" href="<?php bloginfo('url'); ?>/?jb_action=feature_job&jobid=<?php the_ID(); ?>" class="" style="background: #f21aa5; color: #fff;"><?php _e('Featured', 'wpjobster'); ?></a>
					</li>

					<?php }
					else{ ?>
					<li>
						<a class="btn feature lighter smallest p5r" href="<?php bloginfo('url'); ?>/?jb_action=feature_job&jobid=<?php the_ID(); ?>" class="del_job"><?php _e('Feature', 'wpjobster'); ?></a>
					</li>
					<?php }
					endif; ?>
					<?php
					if ($post->post_status == "publish" && $under_review != "1" && $active == 1 && get_option('wpjobster_enable_widget_embed_code') == 'yes') {
						wpjobster_myjobs_embed_code(get_the_ID());
					} ?>
				</ul>
			</div>
			<div class="two wide column my-account-job-date">
				<?php echo get_the_date(get_option( 'date_format' )); ?>
			</div>
			<div class="three wide column my-account-price">
				<?php
					$wpjobster_packages = get_option('wpjobster_packages_enabled');
					$packages = get_post_meta( $pid, 'job_packages', true );
					$package_price = get_post_meta( get_the_ID(), 'package_price', true );

					if ( $wpjobster_packages == 'yes' && $packages == 'yes' && $package_price ) {
						sort( $package_price );
						$package_price = array_diff($package_price, array(null));
						echo wpjobster_get_show_price( min( $package_price ) );
						echo ' - ';
						echo wpjobster_get_show_price( max( $package_price ) );
					} else {
						if( get_post_meta(get_the_ID(), 'price', true) > 0 ) {
							echo wpjobster_get_show_price(get_post_meta(get_the_ID(), 'price', true), 1);
						} else {
							if ( wpj_bool_option( 'wpjobster_replace_zero_with_free' ) ) {
								_e('Free','wpjobster');
							} else {
								echo wpjobster_get_show_price(get_post_meta(get_the_ID(), 'price', true), 1);
							}
						}
					}
				?>
			</div>
			<div class="three wide column">
				<div class="my-account-job-status">
					<?php
					if ($post->post_status == "pending" && ($more_extras=='yes' || $more_extra_price=='yes' || $more_job_price=='yes')): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('disabled', 'wpjobster'); ?></span>
					<?php
					elseif ($post->post_status == "pending"): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('rejected', 'wpjobster'); ?></span>
					<?php
					elseif ($under_review == "1"): ?>
						<span class="oe-status-btn oe-orange oe-full"><?php _e('pending', 'wpjobster'); ?></span>
					<?php
					elseif ($active == 0): ?>
						<span class="oe-status-btn oe-yellow oe-full"><?php _e('paused', 'wpjobster'); ?></span>
					<?php
					else: ?>
						<span class="oe-status-btn oe-green oe-full"><?php _e('published', 'wpjobster'); ?></span>
					<?php
					endif; ?>
				</div>
			</div>


			<?php
			if ($post->post_status == "pending"): ?>
				<div class="sixteen wide column">
					<div class="cf">
						<span class="pending-message"><?php _e('Please edit the job following the provided instructions.', 'wpjobster'); ?></span>
					</div>
				</div>
			<?php
			endif; ?>
		</div>
	<?php }
}

//----------------------------------
// (c) WPJobster Pending Withdrawals
//----------------------------------
if (!function_exists('wpjobster_get_pending_withdrawals_queries')){
	function wpjobster_get_pending_withdrawals_queries($row){
		echo '<div class="ui stackable grid payments-list-border">';
			echo '<div class="five wide column">'. '<div class="responsive_titles">'.__("Date", "wpjobster").'</div>'. '<div class="responsive_content_payment">'.date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->datemade).'</div>'.'</div>';
			echo '<div class="four wide column">' .'<div class="responsive_titles">'.__("Amount", "wpjobster").'</div>'. '<div class="responsive_content_payment">'.wpjobster_deciphere_amount_classic($row->payedamount).' '.'</div>' .'</div>';
			echo '<div class="four wide column">'.'<div class="responsive_titles">'.__("Type of payment", "wpjobster").'</div>'. '<div class="responsive_content_payment">'.$row->methods .'</div>'.'</div>';
			echo '<div class="three wide column">'.'<div class="responsive_titles">'.__("Status", "wpjobster").'</div>'.'<div class="responsive_content_payment">';
				if ( $row->activation_key != '' ) {
					echo __('Unconfirmed','wpjobster') . '<br>';
					$receiver_data = get_userdata( $row->uid );
					$act_link = get_bloginfo('url') . "/?jb_action=verify_email&username=" . $receiver_data->user_nicename . "&key=" . $row->activation_key . "&action=withdrawal";
					echo '<a class="greengreen" href="'.$act_link.'">' . __('Confirm','wpjobster') . '</a>';
				} else {
					echo __('Processing','wpjobster');
				}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}

//-------------------------------
// (c) WPJobster Pending Payments
//-------------------------------
if (!function_exists('wpjobster_get_pending_payments_queries')){
	function wpjobster_get_pending_payments_queries($row){
		$post = get_post($row->pid);
		$from = $row->uid ? get_userdata($row->uid) : '';
		$username = $from ? $from->user_login : '-';

		// custom extras total
		$total_custom_extras = 0;

		$custom_extras = json_decode( $row->custom_extras );
		if ( $custom_extras ) {
			$i = 0;
			foreach ( $custom_extras as $custom_extra ) {
				if ( $custom_extra->paid ) {
					$custom_extra_ord = wpj_get_custom_extra( $row->id, $i );
					$custom_extra_payment = wpj_get_payment( array(
						'payment_type' => 'custom_extra',
						'payment_type_id' => $custom_extra_ord->id,
					) );

					$total_custom_extras += $custom_extra_payment->amount;
				}
				$i++;
			}
		}

		// mc_gross + custom_extras - seller_fee
		$total_amount = $row->mc_gross + $total_custom_extras;
		$total_amount_minus_fee = $total_amount - wpjobster_calculate_fee( $total_amount );

		$transaction_id = wpjobster_camouflage_order_id($row->id,$row->date_made);

		if (get_post_type($row->pid) == 'offer') {
			$real_post_title = __("Private transaction with", "wpjobster") . ' ' . $username;
		} else {
			if ($row->job_title) {
				$real_post_title = $row->job_title;
			} else {
				$real_post_title = $post->post_title;
			}
		}

		echo '<div class="ui fitted divider"></div>';

		echo '<div class="two wide column">'.'<span class="responsive_titles">'.__("Buyer", "wpjobster").'</span>'.$username.'</div>';
		echo '<div class="two wide column">'.'<span class="responsive_titles">'.__("Job", "wpjobster").'</span>'.'<a href="'.get_home_url().'?jb_action=chat_box&oid='.$row->id.'">'.$real_post_title . ' (#' . $transaction_id .')</a></div>';
		echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Date Purchased", "wpjobster").'</span>'.date_i18n(get_option( 'date_format' ), $row->date_made).'</div>';

		if ($row->date_completed) {
			echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Date Completed", "wpjobster").'</span>'.date_i18n(get_option( 'date_format' ), $row->date_completed).'</div>';
		} else {
			echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Date Completed", "wpjobster").'</span>'.'-</div>';
		}

		if ($row->date_to_clear) {
			echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Date Clearing", "wpjobster").'</span>'.date_i18n(get_option( 'date_format' ), $row->date_to_clear).'</div>';
		} else {
			echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Date Clearing", "wpjobster").'</span>'.'-</div>';
		}

		echo '<div class="three wide column">'.'<span class="responsive_titles">'.__("Amount", "wpjobster").'</span>'.wpjobster_get_show_price_classic($total_amount_minus_fee).'</div>';
	}
}

//----------------------------
// (c) WPJobster Transactions
//----------------------------
if (!function_exists('wpjobster_get_transactions_queries')){
	function wpjobster_get_transactions_queries($row){
		global $wpjobster_currencies_array;
		global $wpdb;

		if (count($wpjobster_currencies_array) > 1) { $multiple_currencies = 1; } else { $multiple_currencies = 0; }
		if ($multiple_currencies) { $descr_col_class = "bs-col466fill"; } else { $descr_col_class = "bs-col46fill"; }

		$transaction_id = wpjobster_camouflage_order_id($row->oid,$row->datemade);

		if($row->tp == 0){ $class="redred"; $sign = "-"; }
		elseif($row->tp == 1) { $class="greengreen"; $sign = "+"; }
		else { $class="orangeorange"; $sign = ""; }
		$anchor_text = explode(':', strip_tags($row->reason));
		$txt=explode(':',$row->reason);
		$txt=$txt[0];
		$txt=trim($txt);
		$txt=str_replace(' ','_',$txt);


		echo '<div class="ui stackable grid transaction-list-border">';
		echo '<div class="eight wide column overflow-ellipsis">'.'<span class="responsive_titles transfer">'.__("Description", "wpjobster").'</span>';

		if ($row->rid > 0) {
			$job_title = '';
			$order_url = '';

			if ($row->oid > 0) {
				$select_order = "select distinct * from " . $wpdb->prefix . "job_orders where id='$row->oid'";
				$row_order = $wpdb->get_results($select_order);
				$row_order = $row_order[0];

				if (get_post_type($row_order->pid) == 'offer') {
					global $current_user;
					$uid = $current_user->ID;
					$private_uid = $row_order->uid;

					if ($private_uid != $uid) {
						$private_userdata = get_userdata($private_uid);
						$with_username = $private_userdata->user_login;
					} else {
						$private_post = get_post($row_order->pid);
						$private_authorid = $private_post->post_author;
						$private_authordata = get_userdata($private_authorid);
						$with_username = $private_authordata->user_login;
					}

					$job_title = __("Private transaction with", "wpjobster") . ' ' . $with_username . ' (#' . $transaction_id . ')';
				} else {
					$job_title = $row_order->job_title . ' (#'. $transaction_id. ')';
				}

				$order_url = get_bloginfo('url').'/?jb_action=chat_box&oid='.$row->oid;
			}

			if ($row->rid == 1) {
				echo __('Payment received from Site Admin', 'wpjobster');

			} elseif ($row->rid == 2) {
				echo __('Payment withdrawn by Site Admin', 'wpjobster');

			} elseif ($row->rid == 3) {
				echo __('Payment made for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 4) {
				echo __('Payment collected for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 5) {
				echo __('Payment cleared for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 6) {
				echo __('Fee charged for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 7 || $row->rid == 8) {
				echo __('Payment refunded for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 9) {
				echo __('Withdrawal to', 'wpjobster') . ' ' . $row->details;

			} elseif ($row->rid == 10) {
				echo __('Feature Job', 'wpjobster') . ': <a href="' . get_permalink($row->details) . '">' . get_the_title($row->details) . '</a>';

			} elseif ($row->rid == 11) {
				$details_arr = explode("_", $row->details);
				$duration_arr = array(
					"weekly" => __("weekly", "wpjobster"),
					"quarterly" => __("quarterly", "wpjobster"),
					"monthly" => __("monthly", "wpjobster"),
					"yearly" => __("yearly", "wpjobster")
					);

				$duration = $duration_arr[$details_arr[0]];
				if ($details_arr[2] == "new") {
					echo __('Payment for subscription', 'wpjobster') . ": " . $duration;
				} elseif ($details_arr[2] == "change") {
					echo __('Payment for changing subscription', 'wpjobster') . ": " . $duration;
				} elseif ($details_arr[2] == "renew") {
					echo __('Payment for subscription renewal', 'wpjobster') . ": " . $duration;
				} else {
					echo __('Payment for subscription', 'wpjobster');
				}

			} elseif ($row->rid == 12) {
				echo __('Top Up account balance', 'wpjobster');

			} elseif ($row->rid == 13) {
				echo __('Processing fee for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 14) {
				echo __('Tax for', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 15) {
				echo __('Payment received from Affiliate System', 'wpjobster');

			} elseif ($row->rid == 16) {
				$order_url = get_bloginfo('url').'/?jb_action=chat_box&oid='.$row->oid;
				$ord = wpjobster_get_order($row->oid);
				$custom_extras = json_decode($ord->custom_extras);
				$job_title = $custom_extras[$row->details]->description;
				echo __('Processing fee for custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 17) {
				$order_url = get_bloginfo('url').'/?jb_action=chat_box&oid='.$row->oid;
				$ord = wpjobster_get_order($row->oid);
				$custom_extras = json_decode($ord->custom_extras);
				$job_title = $custom_extras[$row->details]->description;
				echo __('Tax for custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 18) {
				$order_url = get_bloginfo('url').'/?jb_action=chat_box&oid='.$row->oid;
				$ord = wpjobster_get_order($row->oid);
				$custom_extras = json_decode($ord->custom_extras);
				$job_title = $custom_extras[$row->details]->description;
				echo __('Custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} elseif ($row->rid == 19) {
				$order_url = get_bloginfo('url').'/?jb_action=chat_box&oid='.$row->oid;
				$ord = wpjobster_get_order($row->oid);
				$custom_extras = json_decode($ord->custom_extras);
				$job_title = $custom_extras[$row->details]->description;
				echo __('Payment collected for custom extra', 'wpjobster') . ': <a href="' . $order_url . '">' . $job_title . '</a>';

			} else {
				echo $row->reason;
			}

		} else {
			echo $row->reason;
		}


		echo '</div>';
		echo '<div class="four wide column">'.'<span class="responsive_titles transfer">'.__("Date", "wpjobster").'</span>'.date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->datemade).'</div>';
		echo '<div class="two wide column '.$class.'">'.'<span class="responsive_titles transfer">'.__("Amount", "wpjobster").'</span>'.$sign.wpjobster_get_show_price_classic($row->amount).'</div>';
		if ($multiple_currencies) {
			echo '<div class="two wide column '.$class.'">'.'<span class="responsive_titles transfer">'.__("Currency", "wpjobster").'</span>'.$sign.wpjobster_deciphere_amount_classic($row->payedamount).'</div>';
		}

		echo '</div>';

	}
}

//-----------------------------
// (c) WPJobster Award Ratings
//-----------------------------
if (!function_exists('wpjobster_get_to_award_ratings')){
	function wpjobster_get_to_award_ratings($row){
		$post = $row->pid;
		$post = get_post($post);
		$title = isset( $post->post_title ) ? $post->post_title : '';
		?>
		<div class="ui segment" id="post-<?php echo $row->ratid; ?>">
			<div class="ui two column stackable grid">
				<div class="eight wide column">
					<ul class="review-title-avatar">
						<li>
							<a href="<?php echo get_permalink($row->pid); ?>"><img width="40" height="40" src="<?php echo wpjobster_get_first_post_image($row->pid,41,41); ?>" class="round-avatar" /></a>
						</li>
						<li>
							<h3 class="title-job-left"><a href="<?php echo get_permalink($row->pid); ?>"><?php echo wpjobster_wrap_the_title($title,$row->pid); ?></a></h3>
						</li>
					</ul>
				</div>
				<div class="eight wide column">
					<div class="wrapper-rating-stars job-review-right">
						<div class="job-review-subtitle rating-title"><?php _e("Rate","wpjobster"); ?>:</div>
						<form class="rating rating-review" id="rating-<?php echo $row->ratid; ?>">
							<div class="rate review">
								<input type="radio" id="5_stars-<?php echo $row->ratid; ?>" name="stars" value="5" />
								<label for="5_stars-<?php echo $row->ratid; ?>" title="text"></label>
								<input type="radio" id="4_stars-<?php echo $row->ratid; ?>" name="stars" value="4" />
								<label for="4_stars-<?php echo $row->ratid; ?>" title="text"></label>
								<input type="radio" id="3_stars-<?php echo $row->ratid; ?>" name="stars" value="3" />
								<label for="3_stars-<?php echo $row->ratid; ?>" title="text"></label>
								<input type="radio" id="2_stars-<?php echo $row->ratid; ?>" name="stars" value="2" />
								<label for="2_stars-<?php echo $row->ratid; ?>" title="text"></label>
								<input type="radio" id="1_stars-<?php echo $row->ratid; ?>" name="stars" value="1" />
								<label for="1_stars-<?php echo $row->ratid; ?>" title="text"></label>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="sixteen wide column">
				<form class="ui form">
					<textarea class="grey_input textarea-review-page" id="reason-<?php echo $row->ratid; ?>" rows="4" placeholder="<?php _e("Rating description","wpjobster"); ?>"></textarea>
				</form>
			</div>
			<div class="sixteen wide column">
				<div class="btn-submit-review">
					<a href="#" rel="<?php echo $row->ratid; ?>" class="ui primary button dd-submit-rating"><?php _e('Submit Rating Now','wpjobster'); ?></a>
				</div>
			</div>

		</div>

		<div class="ui hidden divider"></div>
		<?php
	}
}

//-------------------------------
// (c) WPJobster Pending Ratings
//-------------------------------
if (!function_exists('wpjobster_get_pending_ratings')){
	function wpjobster_get_pending_ratings($row){
		$post = $row->pid;
		$post = get_post($post);
		$user = get_userdata($row->uid);
		?>
		<div class="ui segment" id="post-<?php echo $row->ratid; ?>">
			<div class="ui two column stackable grid">
				<div class="two wide column">
					<a href="<?php echo get_permalink($row->pid); ?>"><img width="60" height="60" src="<?php echo wpjobster_get_first_post_image($row->pid,41,41); ?>" class="round-avatar" /></a>
				</div>
				<div class="fourteen wide column">
					<h3><a href="<?php echo get_permalink($row->pid); ?>"><?php echo wpjobster_wrap_the_title($post->post_title,$row->pid); ?></a></h3>
					<p><?php echo sprintf(__('Waiting for: %s','wpjobster'), $user->user_login ); ?></p>
				</div>
			</div>
		</div>

		<?php
	}
}

//-------------------------
// (c) WPJobster My Ratings
//-------------------------
if (!function_exists('wpjobster_get_my_ratings')){
	function wpjobster_get_my_ratings($row){
		$post = $row->pid;
		$datemade = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->datemade);
		$post = get_post($post);

		if( get_current_user_id() == wpj_get_seller_id( $row->orderid ) ){
			$buyer_info = get_user_by('id', $row->uid);
		}else{
			$buyer_info = get_user_by('id', $row->post_author);
		}
		$buyer_slug = $buyer_info->data->user_nicename;
		?>
		<div class="ui segment" id="post-<?php echo $row->ratid; ?>">
			<div class="ui two column stackable grid">
				<div class="two wide column">
					<div class="review-job-image">
						<a href="<?php echo get_permalink($row->pid); ?>"><img width="60" height="60" src="<?php echo wpjobster_get_first_post_image($row->pid,41,41); ?>" class="round-avatar" /></a>
					</div>

					<div class="review-user-buy-name">
						<?php echo "<a class='user-link' href='".wpj_get_user_profile_link( $buyer_slug )."'>".$buyer_slug."</a>"; ?>
					</div>

				</div>
				<div class="fourteen wide column">
					<div class="review-job-title">
						<h3><a href="<?php echo get_permalink($row->pid); ?>"><?php echo wpjobster_wrap_the_title($post->post_title, $row->pid); ?></a></h3>
					</div>
					<div class="review-rating-job my-ratings">
						<p>
							<?php echo wpjobster_show_stars_our_of_number($row->grade); ?><?php echo "<span class='grey-time p10l'>".$datemade."</span>"; ?>
						</p>
					</div>
					<div class="review-comment-job">
						<p><?php echo stripslashes($row->reason); ?></p>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
}

//-----------------------
// (c) WPJobster Reviews
//-----------------------
if (!function_exists('wpjobster_get_news')){
	function wpjobster_get_news(){ ?>
		<div class="ui segment">
			<div class="blog_post blog_post_wrapper" id="post-<?php the_ID(); ?>">
				<h2 class="heading-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>

				<a href="<?php the_permalink() ?>"><div class="blog_thumbnail"><?php the_post_thumbnail('blog_thumbnail_big'); ?></div></a>

				<div class="blog_post_content white-cnt overflow-visible">
					<div class="padding-cnt">
						<?php the_excerpt(); ?>
					</div>
					<div class="extra content center">
						<i class="calendar icon"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
					</div>
				</div>
			</div>
		</div>
	<?php }
}

//---------------------------------------
// (c) WPJobster Request Category Archive
//---------------------------------------
if (!function_exists('wpjobster_get_req_cat')){
	function wpjobster_get_req_cat(){

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
		$author = get_the_author();
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
							<span class="lets-meet lets-meet-request">
								<img src="<?php echo get_template_directory_uri() . '/images/shake-icon.png'; ?>" alt="lets-meet">
								<div class="nh-tooltip"><?php _e("Let's meet", "wpjobster"); ?></div>
							</span>
						<?php } ?>
					</span>
					<?php echo '<div class="request-content-title">'; ?>
					<?php echo get_the_title(); ?>
					<?php echo '</div>'; ?>
					<?php
						echo '<div class="request-content-view-more" style="width: 100%; display: none;">';
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
						if ($req_attachments) {
							$attachments = explode(",", $req_attachments);
							if(array_filter($attachments)) {
								echo '<div class="pm-attachments"><div class="pm-attachments-title">';
								_e("Attachments", "wpjobster");
								echo '</div>';
								foreach ($attachments as $attachment) {
									if($attachment != ''){
										echo '<div class="pm-attachment-rtl"><a class="download-req" target="_blank" href="' . get_bloginfo( 'url' ) . '/?secure_download=' . $attachment . wpjobster_get_token() . '" download>';

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

							if ( $view_more_action != 'directlink' ) {
								echo '<span data-requestid="' . get_the_ID() . '" class="ui primary button db request-view-more-link">' . __('View More', 'wpjobster') . '</span>';
							} else {
								echo '<a href="' . $view_more_link . '" class="ui primary button db request-view-more-link">' . __('View More', 'wpjobster') . '</a>';
							}

							echo '<div class="request-right-view-more cf" style="width: 100%; display: none;">';
						?>

						<?php $active_job_required = get_option( 'wpjobster_active_job_cutom_offer' ); $display_custom_offer_button = apply_filters( 'display_or_hide_section_filter', true ); ?>
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
		</div>
		<?php
	}
}

//------------------------------
// (c) WPJobster - Archive Posts
//------------------------------
if (!function_exists('wpjobster_get_archive_posts')){
	function wpjobster_get_archive_posts(){ ?>
		<div class="blog_post cf">
			<a href="<?php the_permalink() ?>"><div class="blog_thumbnail"><?php the_post_thumbnail('blog_thumbnail'); ?></div></a>
			<div class="blog_post_content">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
				<div class="the_time"><?php echo get_the_date(get_option('date_format')); ?></div>
				<p><?php echo substr(get_the_content(),0,300); ?></p>
				<a href="<?php the_permalink(); ?>" class="btn smaller green"><?php _e("Read More",'wpjobster'); ?></a>
			</div>
			<div class="bottom-border-simulator"></div>
		</div><?php
	}
}

//------------------------
// (c) WPJobster - Archive
//------------------------
if (!function_exists('wpjobster_get_archive')){
	function wpjobster_get_archive(){ ?>
		<div class="blog_post blog_post_wrapper white-cnt cf" id="post-<?php the_ID(); ?>">
			<h2 class="heading-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>

			<a href="<?php the_permalink() ?>"><div class="blog_thumbnail"><?php the_post_thumbnail('blog_thumbnail_big'); ?></div></a>

			<div class="blog_post_content white-cnt overflow-visible">
				<div class="padding-cnt">
					<?php the_content(); ?>
				</div>
				<div class="extra content center">
					<i class="calendar icon"></i><?php echo get_the_date( get_option( 'date_format' ) ); ?>
				</div>
			</div>


		</div><?php
	}
}

//------------------------
// (c) WPJobster - Request
//------------------------
if (!function_exists('get_post_small_req')){
	function get_post_small_req(){
		$ending = get_post_meta(get_the_ID(), 'ending', true);
		$sec = $ending - time();
		$post = get_post(get_the_ID());
		$rid = $post->ID;

		if(strtolower($post->post_status) == 'publish'){
			$under_review=0;
		}elseif(strtolower($post->post_status) == 'pending'){
			$under_review=-1;
		}else{
			$under_review =1;
		}

		$img_class = "image_class";
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		global $wpjobster_currencies_array;
		?>

		<div class="ui two stackable grid user-requests background-request-<?php echo $post->ID; ?>">
			<div class="eight wide column">
				<h4 class="small-heading-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
				</h4>
				<ul class="request-edit-delete">
					<li>
						<a class="ui primary button btn important lighter smallest p5r" href="<?php bloginfo('url'); ?>/?jb_action=edit_request&request_id=<?php the_ID(); ?>" class="edit_request">
						<?php _e( 'Edit', 'wpjobster' ); ?>
						</a>
					</li>
					<li>
						<a class="ui secondary button btn lighter smallest p5r request-open-modal" data-request-id="<?php echo $rid; ?>" data-title-request="<?php the_title(); ?>">
							<?php _e( 'Delete', 'wpjobster' ); ?>
						</a>
					</li>
				</ul>
			</div>

			<div class="five wide column request-date-format">
				<?php echo get_the_date(get_option( 'date_format' )); ?>
			</div>

			<div class="three wide column request-status-job">
				<div class="small-single-line">
					<?php
					if ($under_review == "1"): ?>
						<span class="oe-status-btn oe-orange oe-full"><?php _e('pending', 'wpjobster'); ?></span>
					<?php
					elseif($under_review == "-1"): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('Rejected', 'wpjobster'); ?></span>
					<?php
					else: ?>
						<span class="oe-status-btn oe-green oe-full"><?php _e('published', 'wpjobster'); ?></span>
					<?php
					endif; ?>
				</div>
			</div>
			<?php if ($post->post_status == "pending"): ?>
				<div class="cf">
					<span class="pending-message"><?php _e('Please edit the job following the provided instructions.', 'wpjobster'); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<?php
	}
}

//--------------------------
// (c) WPJobster - Affiliate
//--------------------------
if (!function_exists('wpjobster_show_affiliate_transaction_row')) {
	function wpjobster_show_affiliate_transaction_row($row="") {
		//User Timezone Function
		wpjobster_timezone_change();

		$uid = get_user_by('id', $row->user_id);
		$rid = get_user_by('id', $row->referral_id);
		$uid_user_login = isset($uid->user_login) ? $uid->user_login : '-';
		$rid_user_login = isset($rid->user_login) ? $rid->user_login : '-';
		$date = date_i18n(get_option('date_format') . ' - ' . get_option('time_format'), $row->date);
		$reason = wpjobster_text_reason($row->reason_id, $row->referral_id);
		$sign = '+';
		?>

		<div class="bs-table-row post_results_item flex-middle cf">
			<div class="bs-col-container cf">
				<div class="bs-col2">
					<div class="small-single-line">
						<span class="responsive_titles"> <?php _e('Reason', 'wpjobster-affiliate'); ?></span><?php echo $reason; ?>
					</div>
				</div>
				<div class="bs-col3">
					<div class="small-single-line">
						<span class="responsive_titles"> <?php _e('Date', 'wpjobster-affiliate'); ?></span>
						<?php echo $date; ?>
					</div>
				</div>
				<div class="bs-col6 greengreen text-right">
					<div class="small-single-line">
						<span class="responsive_titles"> <?php _e('Amount', 'wpjobster-affiliate'); ?></span>
						<?php echo $sign.wpjobster_get_show_price(($row->amount), 2); ?>
					</div>
				</div>
			</div>
		</div>
	<?php }
}

//--------------------------------
// (c) WPJobster - List User Jobs
//--------------------------------

if (!function_exists('wpj_get_user_post_tumb_card')) {
function wpj_get_user_post_tumb_card() {
	global $post;
	$pid = $post->ID;
    $cid=$_COOKIE['country_name'];
	//echo $cid;
	$img = wpjobster_get_job_image( $pid );

	$job_price = get_post_meta( $pid, 'price', true );
	$job_price_display = wpjobster_get_show_price( $job_price, 2, true );

	$usr = get_userdata( $post->post_author );
	$userdata = get_userdata( $post->post_author );
	$post_author_id = get_post_field( 'post_author', $post_id );
	$usr_country_id=user($post_author_id, 'country_id');

	global $wpdb;

	$enable_lets_meet = wpj_bool_option( 'wpjobster_lets_meet' );
	$video_thumbnails = wpj_bool_option( 'wpjobster_video_thumbnails' );
	$enable_lazy_loading = wpj_bool_option( 'wpjobster_enable_lazy_loading' );
	$enable_instant_delivery = wpj_bool_option( 'wpjobster_enable_instant_deli' );
     
	$lets_meet = get_post_meta( $pid, 'lets_meet', true );
	$youtube_link1 = get_post_meta( $pid, 'youtube_link1', true );
	$instant = get_post_meta( $pid, "instant", true );
    
	$home_featured_now = get_post_meta( $pid, 'home_featured_now', true );
	$category_featured_now = get_post_meta( $pid, 'category_featured_now', true );
	$subcategory_featured_now = get_post_meta( $pid, 'subcategory_featured_now', true );
	$job_is_featured = ( $home_featured_now != 'z' || $category_featured_now != 'z' || $subcategory_featured_now != 'z' ) ? true : false;

	$postthumb_cnt_classes = '';
	$postthumb_cnt_classes .= ( $home_featured_now != 'z' ) ? ' featured_home ' : '';
	$postthumb_cnt_classes .= ( $category_featured_now != 'z' ) ? ' featured_category ' : '';
	$postthumb_cnt_classes .= ( $subcategory_featured_now != 'z' ) ? ' featured_subcategory ' : '';
	$postthumb_cnt_classes .= ( $video_thumbnails && $youtube_link1 ) ? ' video_thumbnail ' : '';
   
	?>
   <?php if($usr_country_id==$cid || isset($_GET['teacher'])) { ?>
	<div class="ui card card-pusher-overflow<?php echo $postthumb_cnt_classes; ?>">
		<div class="content card-pusher-master">
			<a class="card-username" href="<?php echo wpjobster_get_user_profile_link( $userdata->user_login ); ?>">
				<img class="ui avatar image" src="<?php echo wpjobster_get_avatar( $userdata->ID, 28, 28 ); ?>">
				<?php echo wpjobster_better_trim( $userdata->user_login, 22 ); ?>
			</a>
			<div class="card-rating meta">
				<?php wpj_get_single_job_rating( $pid, false ); ?>
			</div>
		</div>

		<a class="image card-pusher-slave" href="<?php the_permalink(); ?>">
			<div class="card-image-helper">
			<img class="card-blurry-bg" src="<?php echo $img; ?>" alt="<?php the_title(); ?>" />
			<?php if ( $enable_lazy_loading ) { ?>
				<img class="my_image echo-lazy-load" src="<?php echo get_template_directory_uri()."/images/blank.gif"; ?>" data-echo="<?php echo $img; ?>" alt="<?php the_title(); ?>" />
			<?php } else { ?>
				<img class="my_image" src="<?php echo $img; ?>" alt="<?php the_title(); ?>" />
			<?php } ?>
			</div>
		</a>

		<div class="content card-pusher-cover">
			<a class="header" href="<?php the_permalink(); ?>">
				<?php echo wpjobster_better_trim( get_the_title(), 65 ); ?>
			</a>
			<div class="description visible-on-list" style="display: none;">
				<?php echo wpjobster_better_trim( wp_strip_all_tags( get_the_content(), true ), 200 ); ?>
			</div>
		</div>

		<div class="extra content">
			<?php if ( $job_is_featured ) { ?>
				<div class="featured-job card-old-icon">
					<div class="featured-icon" data-tooltip="<?php _e( "Featured", "wpjobster" ); ?>" data-position="top left" data-inverted="">
						<img src="<?php echo get_template_directory_uri() . '/images/circle-featured.png'; ?>" alt="featured">
					</div>
				</div>
			<?php } ?>

			<?php if ( $enable_lets_meet && $lets_meet ) { ?>
				<div class="lets-meet card-old-icon">
					<div class="lets-meet-icon" data-tooltip="<?php _e( "Let's meet", "wpjobster" ); ?>" data-position="top left" data-inverted="">
						<img src="<?php echo get_template_directory_uri() . '/images/shake-icon.png'; ?>" alt="lets-meet">
					</div>
				</div>
			<?php } ?>

			<?php if ( $enable_instant_delivery && $instant ) { ?>
				<div class="instant-job card-old-icon">
					<div class="instant-icon" data-tooltip="<?php _e( "Instant", "wpjobster" ); ?>" data-position="top left" data-inverted="">
						<img src="<?php echo get_template_directory_uri() . '/images/circle-instant.png'; ?>" alt="instant">
					</div>
				</div>
			<?php }

			if( get_option( 'wpjobster_user_level_for_thumbnails' ) == 'yes' ){
				if ( wpjobster_get_user_level( $post->post_author ) == 1 ) { ?>
					<div class="top-user-badge card-old-icon">
						<div class="top-user-badge-icon" data-tooltip="<?php _e( "Rookie Seller", "wpjobster"); ?>" data-position="top left" data-inverted="">
							<?php if ( get_field('user_level_1_icon_listing', "options" ) ) { ?>
								<img src="<?php echo get_field( 'user_level_1_icon_listing', "options" ); ?>" alt="user-badge">
							<?php } else { ?>
								<img src="<?php echo get_template_directory_uri() . '/images/top-user-badge-icon-lvl1.png'; ?>" alt="user-badge-server">
							<?php } ?>
						</div>
					</div>
				<?php }

				if ( wpjobster_get_user_level( $post->post_author ) == 2 ) { ?>
					<div class="top-user-badge card-old-icon">
						<div class="top-user-badge-icon" data-tooltip="<?php _e( "Master Seller", "wpjobster"); ?>" data-position="top left" data-inverted="">
							<?php if ( get_field('user_level_2_icon_listing', "options" ) ) { ?>
								<img src="<?php echo get_field( 'user_level_2_icon_listing', "options" ); ?>" alt="user-badge">
							<?php } else { ?>
								<img src="<?php echo get_template_directory_uri() . '/images/top-user-badge-icon-lvl2.png'; ?>" alt="user-badge-server">
							<?php } ?>
						</div>
					</div>
				<?php }
			}

			if ( wpjobster_get_user_level( $post->post_author ) == 3 ) { ?>
				<div class="top-user-badge card-old-icon">
					<div class="top-user-badge-icon" data-tooltip="<?php _e( "Top Rated Seller", "wpjobster"); ?>" data-position="top left" data-inverted="">
						<?php if ( get_field('user_level_3_icon_listing', "options" ) ) { ?>
							<img src="<?php echo get_field( 'user_level_3_icon_listing', "options" ); ?>" alt="user-badge">
						<?php } else { ?>
							<img src="<?php echo get_template_directory_uri() . '/images/top-user-badge-icon-lvl3.png'; ?>" alt="user-badge-server">
						<?php } ?>
					</div>
				</div>
			<?php } ?>

			<div class="right floated meta">
				<span class="card-job-price">
					<?php
					$wpjobster_packages = get_option('wpjobster_packages_enabled');
					$packages = get_post_meta( $pid, 'job_packages', true );
					$package_price = get_post_meta( get_the_ID(), 'package_price', true );

					if ( $wpjobster_packages == 'yes' && $packages == 'yes' && $package_price ) {
						$package_price = array_diff($package_price, array(null));
						echo '<span class="small-job">' . __('Starting at','wpjobster') . ' </span>' . wpjobster_get_show_price( min( $package_price ) );
					} else {
						echo $job_price_display;
					} ?>
				</span>
			</div>
		</div>
	</div>
<?php  } ?>
<?php }
}

//---------------------------
// (c) WPJobster - Blog Posts
//---------------------------

if (!function_exists('wpjobster_get_post_blog')) {

	function wpjobster_get_post_blog() {
		$arrImages = get_children('post_type=attachment&post_mime_type=image&post_parent=' . get_the_ID());

		if ($arrImages) {
			$arrKeys = array_keys($arrImages);
			$iNum = $arrKeys[0];
			$sThumbUrl = wp_get_attachment_thumb_url($iNum);
			$sImgString = '<a href="' . get_permalink() . '">' . '<img class="image_class" src="' . $sThumbUrl . '" width="100" height="100" />' . '</a>';
		} else {
			$sImgString = '<a href="' . get_permalink() . '">' . '<img class="image_class" src="' . get_template_directory_uri() . '/images/nopic.jpg" width="100" height="100" />' . '</a>';
		} ?>
		<div class="ui segment load-more-post" id="post-<?php the_ID(); ?>">
			<div class="blog-image-left">
				<?php echo get_the_post_thumbnail(get_the_ID(), 'thumb_picture_size'); ?>
			</div>
			<div class="blog-content-right cf">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

				<p class="mypostedon"><?php echo sprintf(__('Posted on %1$s by %2$s', 'wpjobster'), get_the_date(), get_the_author()); ?></p>

				<p class="blog_post_preview"><?php the_excerpt(); ?></p>

				<a href="<?php the_permalink(); ?>" class="ui primary button blog-read-more"><?php _e('Read More', 'wpjobster'); ?></a>
			</div>
		</div>
		<?php
	}
}


//-------------------------
// (c) WPJobster - Shopping
//-------------------------
if (!function_exists('wpjobster_show_bought_new')) {

	function wpjobster_show_bought_new($row)    {
		$pid = $row->pid;
		$post = get_post($row->pid);
		$max_days = get_post_meta($row->pid, 'max_days', true);
		$date_made = $row->date_made;
		$show_price = explode("|", $row->payedamount);
		$bought = date_i18n(get_option( 'date_format' ), $date_made);
		$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
		$done_seller = $row->done_seller;
		$closed = $row->closed;
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$user = $row->uid;
		$user = get_userdata($user);
		$completed = 0;

		if ($row->done_buyer == 1)            $completed = 1;
		$id = $row->id;
		$delivered = 0;

		if ($row->done_seller == 1)            $delivered = 1;
			$can_be_closed = 0;
			$can_request_closed = 1;

		if ($uid == $row->uid) {
			$date_made = $row->date_made;
			$max_days = get_post_meta($row->pid, 'max_days', true) * 3600 * 24;
			$now = current_time('timestamp', 0);

			if ($date_made + $max_days < $now)                $can_be_closed = 1;
		}


		if ($row->closed == 1) {
			$can_be_closed      = 0;
			$can_request_closed = 0;
		}


		if ($row->completed == 1) {
			$can_be_closed      = 0;
			$can_request_closed = 0;
		}

		$accept_cancellation_request = $row->accept_cancellation_request;
		$request_cancellation_from_seller = $row->request_cancellation_from_seller;
		$request_cancellation_from_buyer = $row->request_cancellation_from_buyer;
		$request_modification = $row->request_modification;

		if ($row->job_image) {
			$job_image_url = wpj_get_attachment_image_url( $row->job_image, array( 60, 60 ) );

		} else {
			$job_image_url = wpjobster_get_first_post_image( $pid, 60, 60 );

		}

		global $wpjobster_currencies_array;
		?>

		<div class="ui stackable grid shopping-list-border">

			<div class="five wide column">
				<ul class="sales-title-image">
					<li class="image-sales">
						<a href="<?php bloginfo('url'); ?>/?jb_action=chat_box&oid=<?php echo $row->id; ?>" rel="<?php echo $id; ?>" title=""><img width="60" height="60" class="round-avatar" src="<?php echo get_post_type($pid) == 'offer' ? get_template_directory_uri().'/images/custom-offer-thumbnail.png' : $job_image_url; ?>" /></a>
					</li>

					<li class="title-sales">
						<h4 class="small-heading-title small-single-line-title">
						<a href="<?php bloginfo('url'); ?>/?jb_action=chat_box&oid=<?php echo $row->id; ?>" rel="<?php echo $id; ?>" title="">
							<?php
							if (get_post_type($pid) == 'offer') {
								$this_post_title = __("Private transaction with", "wpjobster") . ' ' . get_userdata($post->post_author)->user_login;

							} elseif ($row->job_title) {
								$this_post_title = wpjobster_wrap_the_title($row->job_title, $pid);

							} elseif ($post->post_title) {
								$this_post_title = wpjobster_wrap_the_title($post->post_title, $pid);

							} else {
								$this_post_title = __('No title', 'wpjobster');
							}

							echo $this_post_title;
							?>
						</a>
						<?php
						if($row->payment_status == 'pending'){
                                                    if( isset($post->post_status) && $post->post_status == 'publish') {
                                                    ?>
							<br />
							<a class="redlink" href='javascript:void(0)' onclick='pending_order_process("cancel","<?php echo $row->id;?>","<?php echo $row->payment_gateway;?>")'><?php _e( 'Cancel', 'wpjobster' ); ?></a>
							| <a class="greenlink" href='javascript:void(0)' onclick='pending_order_process("process","<?php echo $row->id;?>","<?php echo $row->payment_gateway;?>")'><?php _e( 'Process', 'wpjobster' ); ?></a>
						<?php } else {
                                                        echo "<br /> <span style='font-size: 1rem; font-weight: 400; font-style: normal;'>";
                                                        _e('This job is not available anymore', 'wpjobster');
                                                        echo "</span>";
                                                    }
                                                }
						$days_needed = get_post_meta($pid, 'max_days', true);
						$instant = get_post_meta($pid, 'instant', true);
						?>
						</h4>
					</li>
				</ul>
			</div>


			<div class="three wide column shopping-purchased-date">
				<div class="responsive_titles"> <?php _e('Purchased On', 'wpjobster'); ?></div><div class="shopping-date-reponsive"><?php echo $bought; ?></div>
			</div>

			<div class="three wide column shopping-delivery-date">
				<div class="responsive_titles"> <?php _e('Delivery', 'wpjobster'); ?></div><div class="shopping-date-reponsive"><?php echo $expected; ?></div>
			</div>

			<div class="two wide column shopping-total-price">
				<div class="responsive_titles"> <?php _e('Total', 'wpjobster'); ?></div>
				<div class="shopping-date-reponsive">
					<?php

						if( ($row->mc_gross + $row->processing_fees + $row->tax_amount) > 0 ) {
							echo wpjobster_get_show_price_classic(($row->mc_gross + $row->processing_fees + $row->tax_amount), 1);
						} else {
							if ( wpj_bool_option( 'wpjobster_replace_zero_with_free' ) ) {
								_e('Free','wpjobster');
							} else {
								echo wpjobster_get_show_price_classic(($row->mc_gross + $row->processing_fees + $row->tax_amount), 1);
							}
						}
					?>
				</div>
			</div>



			<div class="three wide column shopping-status-job">
				<div class="status-list-sales">
					<?php
					if ($row->payment_status == 'pending'): ?>
						<span class="oe-status-btn oe-orange oe-full"><?php _e('pending', 'wpjobster'); ?></span>
						<?php
					elseif ($row->payment_status == 'failed'): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('failed', 'wpjobster'); ?></span>
					<?php
					elseif ($row->payment_status == 'cancelled'): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('cancelled', 'wpjobster'); ?></span>
					<?php
					elseif ($row->payment_status == 'expired'): ?>
						<span class="oe-status-btn oe-red oe-full"><?php _e('expired', 'wpjobster'); ?></span>
					<?php
					elseif ($completed == 1): ?>
						<span class="oe-status-btn oe-green-txt"><?php _e('completed', 'wpjobster'); ?></span>
					<?php
					elseif ($closed == 1): ?>
						<span class="oe-status-btn oe-red-txt"><?php _e('cancelled', 'wpjobster'); ?></span>
					<?php
					elseif ($delivered == 1 && $completed != 1): ?>
						<span class="oe-status-btn oe-green oe-full"><?php _e('delivered', 'wpjobster'); ?></span>
					<?php
					else:
						if ($request_cancellation_from_buyer == 1 && $accept_cancellation_request == 0): $statusclass = 'oe-red oe-full'; $statusName = __('problem', 'wpjobster');
						elseif ($request_cancellation_from_seller == 1 && $accept_cancellation_request == 0): $statusclass = 'oe-red'; $statusName = __('problem', 'wpjobster');
						elseif ($request_modification == 1): $statusclass = 'oe-orange oe-full'; $statusName = __('modification', 'wpjobster');
						elseif ($delivered == 0 && $closed != 1): $statusclass = 'oe-green oe-full'; $statusName = __('active', 'wpjobster');
						else: $statusclass = 'oe-green'; $statusName = __('active', 'wpjobster');
						endif;
					?>
						<span class="oe-status-btn <?php echo $statusclass; ?>"><?php echo $statusName; ?></span>
					<?php
					endif;
					?>
				</div>
			</div>

		</div>
<?php }
}

//----------------------
// (c) WPJobster - Sales
//----------------------
if (!function_exists('wpjobster_show_sale_new')) {

	function wpjobster_show_sale_new($row)    {
		//User Timezone Function
		wpjobster_timezone_change();

		$pid = $row->pid;
		$post = get_post($row->pid);
		$max_days = get_post_meta($pid, 'max_days', true);
		$date_made = $row->date_made;
		$show_price = explode("|", $row->payedamount);
		$sold = date_i18n(get_option( 'date_format' ), $date_made);
		$expected = date_i18n( get_option( 'date_format' ), wpj_get_expected_delivery( $row->id ) );
		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$delivered = 0;

		if ($row->done_seller == 1)            $delivered = 1;
		$id = $row->id;
		$closed = $row->closed;
		$completed = 0;

		if ($row->done_buyer == 1)            $completed = 1;
		$can_request_closed = 1;

		if ($closed == 1)            $can_request_closed = 0;


		if ($row->completed == 1) {
			$can_be_closed      = 0;
			$can_request_closed = 0;
		}

		$accept_cancellation_request = $row->accept_cancellation_request;
		$request_cancellation_from_seller = $row->request_cancellation_from_seller;
		$request_cancellation_from_buyer = $row->request_cancellation_from_buyer;
		$request_modification = $row->request_modification;

		if ($row->job_image) {
			$job_image_url = wpj_get_attachment_image_url( $row->job_image, array( 60, 60 ) );

		} else {
			$job_image_url = wpjobster_get_first_post_image( $pid, 60, 60 );

		}

		global $wpjobster_currencies_array;
		?>

		<div class="ui stackable grid sales-list-border">

			<div class="five wide column">
				<ul class="sales-title-image">
				<li class="image-sales">
					<a href="<?php bloginfo('url'); ?>/?jb_action=chat_box&oid=<?php echo $row->id; ?>" rel="<?php echo $id; ?>" title=""><img width="60" height="60" class="round-avatar" src="<?php echo get_post_type($pid) == 'offer' ? get_template_directory_uri().'/images/custom-offer-thumbnail.png' : $job_image_url; ?>" /></a>
				</li>

				<li class="title-sales">
					<h4 class="small-heading-title">
						<?php
						$days_needed = get_post_meta($pid, 'max_days', true);
						$instant = get_post_meta($pid, 'instant', true);
						?>
						<a href="<?php bloginfo('url'); ?>/?jb_action=chat_box&oid=<?php echo $row->id; ?>" rel="<?php echo $id; ?>" title="">
							<?php
							if (get_post_type($pid) == 'offer') {
								$this_post_title = __("Private transaction with", "wpjobster") . ' ' . get_userdata($row->uid)->user_login;
							} elseif ($row->job_title) {
								$this_post_title = wpjobster_wrap_the_title($row->job_title, $pid);

							} elseif ($post->post_title) {
								$this_post_title = wpjobster_wrap_the_title($post->post_title, $pid);

							} else {
								$this_post_title = __('No title', 'wpjobster');
							}

							echo $this_post_title;
							?>
						</a>
					</h4>
				</li>
			</ul>
		</div>

		<div class="three wide column sales-sold-date">
			<div class="responsive_titles"> <?php _e('Sold On', 'wpjobster'); ?></div>
			<div class="sales-sold-responsive"><?php echo $sold; ?></div>
		</div>
		<div class="three wide column sales-delivery-date">
			<div class="responsive_titles"> <?php _e('Delivery', 'wpjobster'); ?></div>
			<div class="sales-delivery-responsive"><?php echo $expected; ?></div>
		</div>
		<div class="two wide column sales-total-cash">
			<div class="responsive_titles"> <?php _e('Total', 'wpjobster'); ?></div>
			<div class="sold-date-sales">
				<?php
					if( ($row->mc_gross + $row->processing_fees + $row->tax_amount) > 0 ) {
							echo wpjobster_get_show_price_classic(($row->mc_gross + $row->processing_fees + $row->tax_amount), 1);
						} else {
							if ( wpj_bool_option( 'wpjobster_replace_zero_with_free' ) ) {
								_e('Free','wpjobster');
							} else {
								echo wpjobster_get_show_price_classic(($row->mc_gross + $row->processing_fees + $row->tax_amount), 1);
							}
						}
				?>
			</div>
		</div>

		<div class="three wide column sales-status-box">
			<div class="status-list-sales">
				<?php
				if ($row->payment_status == 'pending' && $closed != 1): ?>
					<span class="oe-status-btn oe-orange"><?php _e('pending', 'wpjobster'); ?></span>
					<?php
				elseif ($row->payment_status == 'processing'): ?>
					<span class="oe-status-btn oe-orange"><?php _e('processing', 'wpjobster'); ?></span>
				<?php
				elseif ($row->payment_status == 'failed'): ?>
					<span class="oe-status-btn oe-red"><?php _e('failed', 'wpjobster'); ?></span>
				<?php
				elseif ($row->payment_status == 'expired'): ?>
					<span class="oe-status-btn oe-red"><?php _e('expired', 'wpjobster'); ?></span>
				<?php
				elseif ($completed == 1): ?>
					<span class="oe-status-btn oe-green-txt"><?php _e('completed', 'wpjobster'); ?></span>
				<?php
				elseif ($closed == 1): ?>
					<span class="oe-status-btn oe-red-txt"><?php _e('cancelled', 'wpjobster'); ?></span>
				<?php
				elseif ($delivered == 1 && $completed != 1): ?>
					<span class="oe-status-btn oe-green oe-full"><?php _e('delivered', 'wpjobster'); ?></span>
				<?php
				else:
					if ($request_cancellation_from_buyer == 1 && $accept_cancellation_request == 0): $statusclass = 'oe-red'; $statusName = __('problem', 'wpjobster');
					elseif ($request_cancellation_from_seller == 1 && $accept_cancellation_request == 0): $statusclass = 'oe-red oe-full'; $statusName = __('problem', 'wpjobster');
					elseif ($request_modification == 1): $statusclass = 'oe-orange'; $statusName = __('modification', 'wpjobster');
					elseif ($delivered == 0 && $closed != 1): $statusclass = 'oe-green'; $statusName = __('active', 'wpjobster');
					else: $statusclass = 'oe-green oe-full'; $statusName = __('active', 'wpjobster');
					endif;
				?>
					<span class="oe-status-btn <?php echo $statusclass; ?>"><?php echo $statusName; ?></span>
				<?php
				endif;
				?>
			</div>
		</div>
	</div>
<?php }
}

add_action('wpjobster_get_post', 'wpjobster_get_post_fnc');
function wpjobster_get_post(){
	do_action('wpjobster_get_post');
}

if (!function_exists('wpjobster_get_post_fnc')) {
	function wpjobster_get_post_fnc() {
		if ($arr[0] == "winner") $pay_this_me = 1;
		if ($arr[0] == "unpaid") $unpaid = 1;
		$ending = get_post_meta(get_the_ID(), 'ending', true);
		$sec = $ending - time();
		$closed = get_post_meta(get_the_ID(), 'closed', true);
		$post = get_post(get_the_ID());
		$featured = get_post_meta(get_the_ID(), 'featured', true);
		$img_class = "image_class_pst";

		global $current_user;

		$post = get_post(get_the_ID());
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		$prc = wpjobster_get_show_price2(get_post_meta(get_the_ID(), 'price', true), 2);
		?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<?php if ($featured == "1"): ?>
				<div class="featured"></div>
			<?php endif; ?>
			<div class="padd10_1">
				<div class="image_holder">
					<a href="<?php the_permalink(); ?>"><img width="102" height="72" class="<?php echo $img_class; ?>" src="<?php echo wpjobster_get_first_post_image(get_the_ID(), 102, 72); ?>" /></a>
				</div>
				<div class="title_holder">
					<div class="ttl_holder_down">
						<h2><?php
							$days_needed = get_post_meta(get_the_ID(), 'max_days', true);
							$instant = get_post_meta(get_the_ID(), 'instant', true);

							if ($instant == 1) echo '<span class="instant_job_spn">' . __('Instant Delivery', 'wpjobster') . '</span>'; else
							if ($days_needed == 1) echo '<span class="express_job_spn">' . __('Express Job', 'wpjobster') . '</span>'; ?>

							<a class="title_of_job" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
								<?php echo ucfirst(strtolower($post->post_title)); ?>
							</a>
						</h2>
					</div>
					<p class="mypostedon"><?php
						$usr = get_userdata($post->post_author);
						$flag = strtoupper(wpjobster_get_user_country($post->post_author)) . " " . wpjobster_get_user_flag($post->post_author);

						$reg = $usr->user_registered;
						$joined = wpjobster_prepare_seconds_to_words(time() - strtotime($reg));

						$max_days = get_post_meta(get_the_ID(), "max_days", true);
						$instant = get_post_meta(get_the_ID(), 'instant', true);

						if ($instant == "1")     $del = __("Instant", "wpjobster"); else {
							if ($max_days == 1)  $del = __("24Hrs", "wpjobster"); else
												 $del = sprintf(__("%s days", "wpjobster"), $max_days);
						}

						echo sprintf(__("<a href='%s' class='title_of_job2'>%s</a>", 'wpjobster'), wpjobster_get_user_profile_link($usr->user_login), $usr->user_login); ?> &nbsp; &nbsp;
						<?php echo sprintf(__('<span class="spn_txt_diff">From</span>: %s', 'wpjobster'), $flag); ?> &nbsp; &nbsp;
						<?php echo sprintf(__('<span class="spn_txt_diff">Joined</span>: %s', 'wpjobster'), $joined); ?> &nbsp; &nbsp;
						<?php echo sprintf(__('<span class="spn_txt_diff">Delivery</span>: %s', 'wpjobster'), $del); ?>
					</p>
				</div>

				<div class="order_now_new_btn">
					<a href="<?php echo get_permalink($pid); ?>" class="order_now_new"><?php echo sprintf(__('Order Now %s', 'wpjobster'), $prc); ?></a>
				</div>
			</div>
		</div>
	<?php }
}
