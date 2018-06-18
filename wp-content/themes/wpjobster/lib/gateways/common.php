<?php
if ( ! function_exists( 'get_common_details' ) ) {
	function get_common_details( $payment_gateway, $order_id = '0', $to_curreny = '', $params = array() ) {
		if(isset($_GET['order_id']) && $_GET['order_id'] && $order_id==0 && isset($_GET['process_pending'])){
			$order_id = $_GET['order_id'];
		}
		if ( $to_curreny != '' ) {
			$currency = $to_curreny;
		} else {
			$currency = wpjobster_get_currency();
		}
		$selected = strtoupper( $currency );

		global $wp_query;

		if ( isset( $params['jobid'] ) ) {
			$pid = $params['jobid'];
		} else {
			$pid = $wp_query->query_vars['jobid'] ? $wp_query->query_vars['jobid'] : 0;
		}

		global $current_user;
		get_currentuserinfo();
		$uid 	= $current_user->ID;
		$post 	= get_post( $pid );


		if ( ($current_user->ID == $post->post_author ) && $pid!='0' ) {
			echo 'DEBUG_INFO: You cannot buy your own stuff.';
			exit;
		}

		$wpjobster_enable_multiples = get_option('wpjobster_enable_multiples');
		$author = get_userdata($post->post_author);
		$author_level = wpjobster_get_user_level($author->ID);
		wpj_get_subscription_info_path();
		$wpjobster_subscription_info = get_wpjobster_subscription_info($author->ID);
		if(get_option('wpjobster_subscription_job_multiples_enabled')!='yes') {
			$wpjobster_subscription_info['wpjobster_subscription_job_multiples'] = get_option( 'wpjobster_get_' . 'level'. $author_level . '_jobmultiples' );
		}
		if(get_option('wpjobster_subscription_extra_multiples_enabled')!='yes'){
			$wpjobster_subscription_info['wpjobster_subscription_extra_multiples'] = get_option('wpjobster_get_'.'level'. $author_level .'_extramultiples');
		}
		$sample_price = get_post_meta( $pid, 'price', true );
		$sample_price = apply_filters( 'wpjobster_gateway_job_price', $sample_price, $pid );

		if ( isset( $params['amount'] ) ) {
			$amount = $params['amount'];
		} else {
			$amount = $_GET['amount'];
		}

		//check amount
		if($wpjobster_enable_multiples!='yes' && $amount>1){
			wp_redirect( get_bloginfo( 'url' ));
			die();
		}
		if($wpjobster_enable_multiples=='yes'){
			if($wpjobster_subscription_info['wpjobster_subscription_enabled']=='yes' && get_option('wpjobster_subscription_job_multiples_enabled')=='yes'){
				$sub_amt = (int)$wpjobster_subscription_info['wpjobster_subscription_job_multiples'];
				$sub_amt = $sub_amt==0||$sub_amt==''?1:$sub_amt;
				if($amount>$sub_amt){
					wp_redirect( get_bloginfo( 'url' ));
					die();
				}
			}
			else{
				if($amount>get_option('wpjobster_get_level'.$author_level.'_jobmultiples')){
					wp_redirect( get_bloginfo( 'url' ));
					die();
				}
			}
		}
		$price = $sample_price * $amount;
		if ( ! is_numeric( $price ) || $price < 0 ) {
			$price = get_option( 'wpjobster_job_fixed_amount' );
		}

		if ( get_post_type( $pid ) == 'offer' ) {
			$job_title = __( 'Private transaction with', 'wpjobster' ) . ' ' . get_userdata( $post->post_author )->user_login;
		} else {
			$job_title = get_post_meta( $pid, 'job_title', true );
			if ( empty( $job_title ) ) {
				$job_title = $post->post_title;
			}
		}

		// Extras & Shipping
		//---------------------------------------------------
		$extr_ttl = 0;

		if ( isset( $params['extras'] ) ) {
			$v_extras = $params['extras'];
		} else {
			$v_extras = $_GET['extras'];
		}
		$extras = explode( '|', $v_extras );

		if ( count( $extras ) <= 1 ) {
			$extras = explode( '_', $v_extras );
		}

		if ( isset( $params['extras_amounts'] ) ) {
			$v_extras_amounts = $params['extras_amounts'];
		} else {
			$v_extras_amounts = $_GET['extras_amounts'];
		}
		$extras_amounts = explode( '|', $v_extras_amounts );
		if ( count( $extras_amounts ) <= 1 ) {
			$extras_amounts = explode( '_', $v_extras_amounts );
		}

		if ( count( $extras ) && count( $extras_amounts ) ) {
			$i=0;
			$extras_disabled_included = 0;
			foreach ( $extras as $myitem ) {
				if ( ! empty( $myitem ) ) {
					if($myitem == 'f'){
						$extra_enabled = get_post_meta($pid, 'extra_fast_enabled', true);
						$extra_price = get_post_meta( $pid, 'extra_fast_price', true );
						$extra_m_enabled = 1;
					}
					else if($myitem == 'r') {
						$extra_enabled = get_post_meta($pid, 'extra_revision_enabled', true);
						$extra_price = get_post_meta( $pid, 'extra_revision_price', true );
						$extra_m_enabled = get_post_meta($pid, 'extra_revision_multiples_enabled', true);
					}
					else {
						$extra_enabled = get_post_meta($pid, 'extra'.$myitem.'_extra_enabled', true);
						$extra_price = get_post_meta( $pid, 'extra' . $myitem . '_price', true );
						$extra_m_enabled = get_post_meta($pid, 'extra'.$myitem.'_enabled', true);
					}
					if($extra_enabled){
						$extr_ttl += $extra_price*$extras_amounts[$i];

						if($wpjobster_enable_multiples!='yes' && $extras_amounts[$i]>1){
							wp_redirect( get_bloginfo( 'url' ));
							die();
						}
						if($wpjobster_enable_multiples=='yes'){
							// check if seller want to sell extras
							if(!$extra_m_enabled && $extras_amounts[$i]>1){
								wp_redirect( get_bloginfo( 'url' ));
								die();
							}
							if($wpjobster_subscription_info['wpjobster_subscription_enabled']=='yes' && get_option('wpjobster_subscription_extra_multiples_enabled')=='yes'){
								if($extras_amounts[$i]>$wpjobster_subscription_info['wpjobster_subscription_extra_multiples_enabled']){
									wp_redirect( get_bloginfo( 'url' ));
									die();
								}
							}
							else{
								if($extras_amounts[$i]>get_option('wpjobster_get_level'.$author_level.'_extramultiples')){
									wp_redirect( get_bloginfo( 'url' ));
									die();
								}
							}
						}
					} // extra enabled
				}
				$i++;
			}
			if($extras_disabled_included>=1){
				wp_redirect( get_bloginfo( 'url' ));
				die();
			}
		}

		$shipping = get_post_meta( $pid, 'shipping', true );
		if ( empty( $shipping ) ) {
			$shipping = 0;
		}

		// Processing Fee & Tax
		//---------------------------------------------------
		if ( ! is_demo_user() ) {
				$tm = time();
				$extras_str = implode( '|', $extras );
				$extras_amounts_str = implode( '|', $extras_amounts );
				$extras_amounts_str = substr($extras_amounts_str, 0, -1);
				$cust = $pid.'|'.$uid.'|'.$tm.'|'.$amount.'|'.(count($extras)-1).'|'.$extras_str.$extras_amounts_str;

				$buyer_processing_fees = wpjobster_get_site_processing_fee( $price, $extr_ttl, $shipping );
				$tax_amount = wpjobster_get_site_tax( $price, $extr_ttl, $shipping, $buyer_processing_fees );

				$wpjobster_final_payable_amount_original = $price + $extr_ttl + $shipping + $buyer_processing_fees + $tax_amount;

				if ( $to_curreny != '' ) {
					$wpjobster_final_payable_amount = wpjobster_formats_special_exchange( $wpjobster_final_payable_amount_original, '1', $to_curreny );
				} else {
					$wpjobster_final_payable_amount = wpjobster_formats_special_exchange( $wpjobster_final_payable_amount_original );
				}

				if ( $payment_gateway == 'credits' ) {
					$pmt_status = 'completed';
					$with_credits = 1;
				} elseif($payment_gateway == 'cod') {
					$with_credits = 0;
					$pmt_status = 'completed';
				}else{
					$pmt_status = 'pending';
					$with_credits = 0;
				}

			if ( $order_id == 0 ) {
				$order_id = wpjobster_insert_order(
					$cust, $currency, '|', $with_credits, $pmt_status, $payment_gateway, '',
					$buyer_processing_fees, $tax_amount, $wpjobster_final_payable_amount_original
				);
			}else{
				$order_details = wpjobster_get_order_details_by_orderid($order_id);//get_order_details($resultrow, $pid, $uid, $ttl, $buyer, $user_name, $buyer_name);
				$pid = $order_details->pid;
				$post=get_post($pid);
				$uid=$order_details->uid;
				$final_paidamount = $order_details->final_paidamount;
				$final_paidamount_arr = explode("|",$final_paidamount);
				$selected = $final_paidamount_arr['0'];
				$job_title = $order_details->job_title;
				$wpjobster_final_payable_amount = $final_paidamount_arr[1];
				$order_id = $order_id;
				$current_user = get_user_by("id",$uid);
				$currency=  wpjobster_get_currency();
				$buyer_processing_fees=$order_details->processing_fees;
				$tax_amount=$order_details->tax_amount;
				$price = $order_details->mc_gross;
				$wpjobster_final_payable_amount_original =$order_details->mc_gross+$order_details->processing_fees+$order_details->tax_amount;;

			}

			return array(
				'order_id'                                => $order_id,
				'pid'                                     => $pid,
				'post'                                    => $post,
				'job_title'                               => $job_title,
				'title'                                   => $job_title,
				'uid'                                     => $uid,
				'current_user'                            => $current_user,
				'currency'                                => $currency,
				'selected'                                => $selected,
				'price'                                   => $price,
				'wpjobster_job_price'                     => $price,
				'wpjobster_job_buyer_processing_fees'     => $buyer_processing_fees,
				'wpjobster_job_tax'                       => $tax_amount,
				'wpjobster_final_payable_amount_original' => $wpjobster_final_payable_amount_original,
				'wpjobster_final_payable_amount'          => $wpjobster_final_payable_amount,
			);

		} else {
			global $wpdb;
			$pref = $wpdb->prefix;
			$s = "select * from " . $pref . "job_orders where uid='$uid' order by id desc";
			$r = $wpdb->get_results( $s );
			$last_row = $r[0];
			$last_row_id = $last_row->id;
			wp_redirect( get_bloginfo( 'url' ) . '/?jb_action=chat_box&oid=' . $last_row_id );
		}
	}
}


if ( ! function_exists( 'wpjobster_mark_job_prchase_completed' ) ) {
	function wpjobster_mark_job_prchase_completed( $orderid, $payment_status, $payment_response, $payment_details = '' ) {
		// this seems to be used only for free jobs at the moment

		// changing order info
		if ( $payment_status == '' ) {
			$payment_status = 'completed';
		}

		wpjobster_update_order_meta( $orderid, 'payment_status', $payment_status );
		wpjobster_update_order_meta( $orderid, 'payment_response', $payment_response );
		if ( $payment_details != '' ) {
			wpjobster_update_order_meta( $orderid, 'payment_details', $payment_details );
		}

		// get order info from database
		$order_info = wpjobster_get_order_details_by_orderid( $orderid );

		$post_title            = $order_info->job_title;
		$mc_gross              = $order_info->mc_gross;
		$uid                   = $order_info->uid;
		$pid                   = $order_info->pid;
		$buyer_processing_fees = $order_info->processing_fees;
		$wpjobster_tax_amount  = $order_info->tax_amount;
		$post                  = get_post( $pid );
		$post_author_id        = $post->post_author;

		wpjobster_maintain_log( $orderid, $post_title, $mc_gross, $uid, $pid, $post_author_id, $buyer_processing_fees, $wpjobster_tax_amount );
		wpjobster_send_sms_allinone_translated( 'purchased_buyer', $uid, $post_author_id, $pid, $orderid );
		wpjobster_send_sms_allinone_translated( 'purchased_seller', $post_author_id, $uid, $pid, $orderid );
		wpjobster_send_email_allinone_translated( 'purchased_buyer', $uid, $post_author_id, $pid, $orderid );
		wpjobster_send_email_allinone_translated( 'purchased_seller', $post_author_id, $uid, $pid, $orderid );
	}
}
