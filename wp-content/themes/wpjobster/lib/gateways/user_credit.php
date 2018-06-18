<?php
if ( $process_action != '' ) {
	if ( ! class_exists( 'wpjobster_common_topup' ) ) {
			include_once get_template_directory() . '/lib/gateways/wpjobster_common_topup.php';
			$wct = new WPJ_Common_Topup( $sk );
		}

		if ( $sk == 'paypal' ) {
			if ( class_exists( 'WPJobster_PayPal_Loader' ) ) {
				$paypalClass = new WPJobster_PayPal_Loader();
			}
		} else {
			if ( ! class_exists( 'wpjobster_'.$sk ) && file_exists( get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php' ) ) {
				include_once get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php';
			}
		}

		if ( $action == 'payment' ) {

			$wct->_currency = apply_filters( "wpjobster_take_allowed_currency_$sk", $wct->_currency );

			$wct->get_package( $wct->_package_id );

			$price = $wct->_package_cost;
			$price_original = $wct->_package_cost_original;

			$details['pid']                            = $package_id = $wct->_package_id;
			$details['uid']                            = $wct->_current_user->ID;
			$order_id                                  = $wct->insert_topup_prchase(
				array(
					"package_id"           => $package_id,
					"package_amount"       => $price,
					"payment_gateway_name" => $sk,
					"user_id"              => $uid,
					"price_original"       => $price_original,
					"currency"             => $wct->_currency
				)
			);
			$details['title']                          = $details['job_title'] = $wct->_package_description_credit;
			$details['wpjobster_final_payable_amount'] = $wct->_package_cost;
			$details['price']                          = $price;
			$details['selected']                       = $wct->_currency;;
			$details['current_user']                   = $wct->_current_user;
			$details['order_id']                       = $order_id;

			do_action($process_action,'topup',$details);
		}

		if( $action == 'process_payment' ){
			do_action( $process_action, 'topup', $wct );
		}
	}
