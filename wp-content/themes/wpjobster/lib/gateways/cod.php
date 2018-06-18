<?php
global $wp_query;
$pid = $wp_query->query_vars['jobid'];
$currency = wpjobster_get_currency();

$wpjobster_cod_enable = get_option('wpjobster_cod_enable');

if($wpjobster_cod_enable == 'yes') {

	if (!is_demo_user()) {

		$price = get_post_meta($pid, 'price', true);

		if (empty($price)) {
			$price = get_option('wpjobster_job_fixed_amount');
		}

		if (get_post_type($pid) == 'offer') {
			$job_title = __("Private transaction with", "wpjobster") . ' ' . get_userdata($post->post_author)->user_login;

		} else {
			$job_title = get_post_meta($pid, 'job_title', true);
			if(empty($job_title)) $job_title = $post->post_title;
		}

		$extr_ttl = 0; $xtra_stuff = '';
		$extras = $_GET['extras'];
		$extras = explode("|", $extras);

		if(count($extras)) {
			foreach($extras as $myitem) {
				if(!empty($myitem)) {

					$extra_price = get_post_meta($pid, 'extra'.$myitem.'_price',true);
					$extr_ttl += $extra_price;
					$xtra_stuff .= '|'. $myitem;

				}
			}
		}

		$shipping 	= get_post_meta($pid, 'shipping', true);
		if(empty($shipping)) $shipping = 0;

		$buyer_processing_fees = wpjobster_get_site_processing_fee($price, $extr_ttl, $shipping);
		update_user_meta($uid, 'wpjobster_buyer_chargable_fees', $buyer_processing_fees);

		$tax_amount = wpjobster_get_site_tax($price, $extr_ttl, $shipping, $buyer_processing_fees);
		update_user_meta($uid, 'wpjobster_taxable_amount', $tax_amount);

		$total_price = wpjobster_formats_special_exchange(($price + $extr_ttl+$shipping+$buyer_processing_fees+$tax_amount), 2, $currency);


		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;

		$tm = current_time('timestamp', 1);

		$cust = $pid.'|'.$uid.'|'.$tm.$xtra_stuff;

		$with_credits = 0;
		$payment_status = 'completed';
		$payment_gateway = 'cod';
		$payment_details = '';

		if(isset($cust)) {

			$cust_array     = explode("|", $cust);
			$pid            = $cust_array[0];
			$uid            = $cust_array[1];
			$datemade       = $cust_array[2];

			$orderid = wpjobster_insert_order($cust,$currency,"|",$with_credits,$payment_status,$payment_gateway,$payment_details);

		}

		if ($orderid) {

			wp_redirect(get_bloginfo('url').'/?jb_action=chat_box&oid='.$orderid);
			exit;

		} else {

			echo __('Error while inserting the order. Please contact the site administrator.', 'wpjobster');
		}

	} //end not demo user
	else {
		wpjobster_show_gateway_demouser();
	}
} //end if cod
?>
