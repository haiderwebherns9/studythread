<?php
if ( $process_action != '' ) {
	if ( ! class_exists( 'wpjobster_common_job_purchase' ) ) {
		include_once get_template_directory() . "/lib/gateways/wpjobster_common_job_purchase.php";
		$wcjp = new WPJ_Common_Job_Purchase( $sk );
	}

	if ( $sk == 'paypal' ) {
		if ( class_exists( 'WPJobster_PayPal_Loader' ) ) {
			$paypalClass = new WPJobster_PayPal_Loader();
		}
	} else {
		if ( ! class_exists( 'wpjobster_' . $sk ) && file_exists( get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php' ) ) {
			include_once get_template_directory() . '/lib/gateways/wpjobster_' . $sk . '.php';
		}
	}

	if ( $action == 'payment' ) {
		$common_details = $wcjp->insert_job_prchase( $sk );
		if ( $sk == 'credits' ) {
			$wcjp->job_purchase_success( $sk, $common_details['order_id'] );
		} else {
			do_action( $process_action, 'job_purchase', $common_details ); // $process_action = wpjobster_taketo_'$plugin_name'_gateway( send payment )
		}
	}

	if ( $action=='process_payment' ) {
		do_action( $process_action, 'job_purchase', $wcjp ); // $process_action = wpjobster_processafter_'$plugin_name'_gateway( receive response )
	}
} else {
	echo __( "Problem occured. Contact site administrator", "wpjobster" );
	die();
}
