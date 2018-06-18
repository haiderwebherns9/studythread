<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WPJ_sales_report{
	public $_report_type, $_wpdb;

	function __construct(){
		global $wpdb;
		$this->_wpdb = $wpdb;
		$this->_prefix = $wpdb->prefix;

		add_action( 'sales_report_enqueue_scripts', array( $this, 'add_daterange_scripts' ) );

		add_action( 'wp_ajax_job_sales_report', array( $this, "job_sales_report" ) );
		add_action( 'wp_ajax_nopriv_job_sales_report', array( $this, "job_sales_report" ) );

		add_action( 'wp_ajax_topup_sales_report', array( $this, "topup_sales_report" ) );
		add_action( 'wp_ajax_nopriv_topup_sales_report', array( $this, "topup_sales_report" ) );

		add_action( 'wp_ajax_featured_sales_report', array( $this, "featured_sales_report" ) );
		add_action( 'wp_ajax_nopriv_featured_sales_report', array( $this, "featured_sales_report" ) );

		add_action( 'wp_ajax_refund_sales_report', array( $this, "refund_sales_report" ) );
		add_action( 'wp_ajax_nopriv_refund_sales_report', array( $this, "refund_sales_report" ) );

		add_action( 'wp_ajax_withdrawal_sales_report', array( $this, "withdrawal_sales_report" ) );
		add_action( 'wp_ajax_nopriv_withdrawal_sales_report', array( $this, "withdrawal_sales_report" ) );

		add_action( 'wp_ajax_custom_extra_sales_report', array( $this, "custom_extra_sales_report" ) );
		add_action( 'wp_ajax_nopriv_custom_extra_sales_report', array( $this, "custom_extra_sales_report" ) );

		add_action( 'wp_ajax_summary_sales_report', array( $this, "summary_sales_report" ) );
		add_action( 'wp_ajax_nopriv_summary_sales_report', array( $this, "summary_sales_report" ) );

		$this->includes();
		$this->get_date_and_user();
	}

	private function includes() {
		if( !class_exists( 'WPJ_job_orders' ) ) {
			include( get_template_directory() . '/classes/class-wpj-get-queries.php' );
		}
	}

	function summary_sales_report( $condition = array() ){

		$job_array = array(
			'columns' => 'sum(processing_fees) as total_processing_fees,
						sum(mc_gross) + sum(processing_fees) + sum(tax_amount) as total,
						sum(tax_amount) as total_tax_job',
			'where' => 'payment_status = "completed" AND date_made >= ' . $this->from_date . ' AND date_made <= '. $this->from_to . $this->job_where_array,
			'table' => $this->_prefix.'job_orders'
		);
		$topup_array = array(
			'columns' => 'sum(package_amount) as total_package_amount,
						sum(tax) as total_tax_topup',
			'table' => $this->_prefix.'job_topup_orders',
			'where' => 'payment_status = "completed" AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to . $this->topup_where_array,
		);
		$featured_array = array(
			'columns' => 'sum(featured_amount) as featured_amount,
						sum(tax) as total_tax_featured',
			'table' => $this->_prefix.'job_featured_orders',
			'where' => 'payment_status = "completed" AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to . $this->featured_where_array,
		);

		$job_all_orders = array(
			'table' => $this->_prefix.'job_orders',
			'where' => 'payment_status = "completed" AND date_made >= ' . $this->from_date . ' AND date_made <= '. $this->from_to . $this->job_where_array,
		);
		$topup_all_orders = array(
			'table' => $this->_prefix.'job_topup_orders',
			'where' => 'payment_status = "completed" AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to . $this->topup_where_array,
		);

		$getQuery = new WPJ_Query();

		$job      = $getQuery->get_wpj_query( $job_array );
		$topup    = $getQuery->get_wpj_query( $topup_array );
		$featured = $getQuery->get_wpj_query( $featured_array );

		// Total Sale //
		$this->total_sales_job      = ($job)      ? $job[0]      ->total                : 0;
		$this->total_sales_topup    = ($topup)    ? $topup[0]    ->total_package_amount : 0;
		$this->total_sales_featured = ($featured) ? $featured[0] ->featured_amount      : 0;
		$this->total_sales          = $this->total_sales_job + $this->total_sales_topup + $this->total_sales_featured;

		// Total Taxes //
		$this->total_taxes_job      = ($job)      ? $job[0]->total_tax_job           : 0;
		$this->total_taxes_topup    = ($topup)    ? $topup[0]->total_tax_topup       : 0;
		$this->total_taxes_featured = ($featured) ? $featured[0]->total_tax_featured : 0;
		$this->total_taxes          = $this->total_taxes_job + $this->total_taxes_topup + $this->total_taxes_featured;

		// Total Fees/Profit //
		//Topup
		$topup_orders = $getQuery->get_wpj_query( $topup_all_orders );
		$total_cost  = 0;
		$total_credt = 0;
		foreach ($topup_orders as $row) {
			if($row->currency != wpjobster_get_currency_classic()){
				$currency_from = $row->currency;
				$currency_to = wpjobster_get_currency_classic();

				$cost_input = $row->package_cost_without_tax;
				$cost = get_exchange_value($cost_input, $currency_from, $currency_to);

				$credit_input = $row->package_credit_without_tax;
				$credit = get_exchange_value($credit_input, $currency_from, $currency_to);
			}else{
				$cost = ($row->package_cost_without_tax != '') ? $row->package_cost_without_tax : '';
				$credit = ($row->package_credit_without_tax != '') ? $row->package_credit_without_tax : '';
			}

			if($credit == ''){
				$package = $row->package_id;

				if($this->user_to_search != ''){ $user_filter = ' AND user_id = '.$this->user_to_search; } else { $user_filter = ''; }
				$date_to_search = ' AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to;

				$topup_packages = $getQuery->get_wpj_query( array( 'columns' => 'credit', 'table' => $this->_prefix.'job_topup_packages', 'where' => 'id = '.$package . $user_filter . $date_to_search ) );
				$credit = $topup_packages[0]->credit;
			}
			$total_cost += $cost;
			$total_credt += $credit;
		}
		$this->total_topup_fees = $total_cost - $total_credt;

		//Job Purchase
		$total_site_fee  = 0;
		$processing_fees = 0;
		$job_orders = $getQuery->get_wpj_query( $job_all_orders );
		foreach ($job_orders as $row) {
			$site_fees = ($row->site_fees != '') ? $row->site_fees : '';
			if($site_fees == ''){
				$site_fees = wpjobster_calculate_fee($row->mc_gross);
			}
			$total_site_fee += $site_fees;
			$processing_fees += $row->processing_fees;
		}

		$this->total_job_fees = $total_site_fee + $processing_fees;
		$this->total_fees = $this->total_topup_fees + $this->total_job_fees + $this->total_sales_featured;

		// All Totals
		$this->total_job_purchase = floatval($this->total_sales_job) + floatval($this->total_job_fees) + floatval($this->total_taxes_job);
		$this->total_topup        = floatval($this->total_sales_topup) + floatval($this->total_topup_fees) + floatval($this->total_taxes_topup);
		$this->total_featured     = floatval($this->total_sales_featured) + floatval($this->total_sales_featured) + floatval($this->total_taxes_featured);
		$this->all_totals          = floatval($this->total_sales) + floatval($this->total_fees) + floatval($this->total_taxes);

		// TABLE FORMAT //

		$tableInfo = '';
		$tableInfo .= '<table id="summary-table" class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th width="20%"></th>';
					$tableInfo .= '<th width="20%">'.__('Job Purchase','wpjobster').'</th>';
					$tableInfo .= '<th width="20%">'.__('Topup','wpjobster').'</th>';
					$tableInfo .= '<th width="20%">'.__('Featured','wpjobster').'</th>';
					$tableInfo .= '<th width="20%">'.__('Total','wpjobster').'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total Sale','wpjobster').'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_sales_job)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_sales_topup)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_sales_featured)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_sales)).'</td>';
				$tableInfo .= '</tr>';

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total Fees/Profit','wpjobster').'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_job_fees)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_topup_fees)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_sales_featured)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_fees)).'</td>';
				$tableInfo .= '</tr>';

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total Taxes','wpjobster').'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_taxes_job)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_taxes_topup)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_taxes_featured)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_taxes)).'</td>';
				$tableInfo .= '</tr>';

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total','wpjobster').'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_job_purchase)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_topup)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->total_featured)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($this->all_totals)).'</td>';
				$tableInfo .= '</tr>';

				$tableInfo .= '<tr>';
					$tableInfo .= '<td align="center" colspan="5">';
						$tableInfo .= "<b>" . strtoupper( __( "Total credit owed to site users: ", "wpjobster" ) ) . $this->total_credits_owed_to_user() . "</b>";
					$tableInfo .= '</td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';
		$tableInfo .= '</table>';

		$tableInfo .= '<br />';

		$tableInfo .= '<table class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<tr>';
				$tableInfo .= '<td width="200">'.__("Total number of jobs","wpjobster").'</td>';
				$tableInfo .= '<td>'.wpjobster_get_total_nr_of_listings().'</td>';
			$tableInfo .= '</tr>';

			$tableInfo .= '<tr>';
				$tableInfo .= '<td>'.__("Open jobs","wpjobster").'</td>';
				$tableInfo .= '<td>'.wpjobster_get_total_nr_of_open_listings().'</td>';
			$tableInfo .= '</tr>';

			$tableInfo .= '<tr>';
				$tableInfo .= '<td>'.__("Closed","wpjobster").'</td>';
				$tableInfo .= '<td>'.wpjobster_get_total_nr_of_closed_listings().'</td>';
			$tableInfo .= '</tr>';

			$tableInfo .= '<tr>';
				$tableInfo .= '<td colspan="2" align="center">';
					$result = count_users();
					$tableInfo .= '<b>'.strtoupper ( __('There are ','wpjobster').' '.$result['total_users'].' '.__(' total users','wpjobster') ).'</b>';
						foreach($result['avail_roles'] as $role => $count)
							$tableInfo .= '<b>'.strtoupper( ', '.$count.' '.__(' are ','wpjobster').' '.$role.'s' ).'</b>';
					$tableInfo .= '.';
				$tableInfo .= '</td>';
			$tableInfo .= '</tr>';
		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Job Purchase','wpjobster'), 'type' => 'number'),
			array('label' => __('Topup','wpjobster'), 'type' => 'number'),
			array('label' => __('Featured','wpjobster'), 'type' => 'number'),
		);

		$rows = array();

		$temp = array();
		$temp[] = array('v' => (string) __('Total Sale','wpjobster') );
		$temp[] = array('v' => (float) $this->total_sales_job);
		$temp[] = array('v' => (float) $this->total_sales_topup);
		$temp[] = array('v' => (float) $this->total_sales_featured);
		$rows[] = array('c' => $temp);

		$temp = array();
		$temp[] = array('v' => (string) __('Total Fees/Profit','wpjobster') );
		$temp[] = array('v' => (float) $this->total_job_fees);
		$temp[] = array('v' => (float) $this->total_topup_fees);
		$temp[] = array('v' => (float) $this->total_sales_featured);
		$rows[] = array('c' => $temp);

		$temp = array();
		$temp[] = array('v' => (string) __('Total Taxes','wpjobster') );
		$temp[] = array('v' => (float) $this->total_taxes_job);
		$temp[] = array('v' => (float) $this->total_taxes_topup);
		$temp[] = array('v' => (float) $this->total_taxes_featured);
		$rows[] = array('c' => $temp);

		$graphInfo['rows'] = $rows;

		// END GRAPH FORMAT

		$jsonTable = json_encode( array( "tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError ) );
		echo $jsonTable;

		die();
	}

	function job_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$job_all_orders = array(
			'table' => $this->_prefix.'job_orders',
			'order_by' => 'id DESC',
			'where' => 'payment_status="completed" AND date_made >= ' . $this->from_date . ' AND date_made <= '. $this->from_to . $this->job_where_array,
		);
		$job_orders = $getQuery->get_wpj_query( $job_all_orders );

		$tableInfo = '';

		$tableInfo .= '<table id="job-purchase-table" class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th width="5%">'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="5%">'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Payment Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Transaction Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="9%">'.__( "User ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="12%">'.__( "Job Title", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Amount cleared", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Delivered date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Shipping", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Buyer Fees", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Seller Commision", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Tax", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$i=1;

				$total_transaction_amount = 0;
				$total_amount_cleared     = 0;
				$total_shipping           = 0;
				$total_buyer_fees         = 0;
				$total_seller_commision   = 0;
				$total_tax                = 0;

				foreach ($job_orders as $row) {
					$user = get_user_by('id', $row->uid);
					$user_name = ($user) ? $user->user_login : '';

					$get_job_id = $getQuery->get_wpj_query( array( 'columns' => 'ID', 'table' => $this->_prefix.'posts', 'where' => 'post_title = "' . $row->job_title .'"' ) );
					$job_id = (isset($get_job_id[0]->ID)) ? $get_job_id[0]->ID : '';

					$amount_cleared = floatval($row->mc_gross) - floatval($row->site_fees);

					$trans_amount = explode('|', $row->final_paidamount);
					if($trans_amount[0] != wpjobster_get_currency_classic()){
						$currency_from = $trans_amount[0];
						$currency_to = wpjobster_get_currency_classic();
						$transaction_amount = get_exchange_value($trans_amount[1], $currency_from, $currency_to);
					}else{
						$transaction_amount = $trans_amount[1];
					}

					if($row->date_made && $row->date_made != 0) $payment_date = date("Y/m/d H:i:s", $row->date_made);
					else $payment_date = '-';

					if($row->date_finished && $row->date_finished != 0) $delivered_date = date("Y/m/d H:i:s", $row->date_finished);
					else $delivered_date = '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.$payment_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($transaction_amount).'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$row->payment_gateway.'</td>';
						$tableInfo .= '<td>'.$job_id.'</td>';
						$tableInfo .= '<td>'.$row->job_title.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($amount_cleared).'</td>';
						$tableInfo .= '<td>'.$delivered_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->shipping)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->processing_fees)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->site_fees)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->tax_amount)).'</td>';
					$tableInfo .= '</tr>';

					$i++;

					$total_transaction_amount += $transaction_amount;
					$total_amount_cleared     += $amount_cleared;
					$total_shipping           += $row->shipping;
					$total_buyer_fees         += $row->processing_fees;
					$total_seller_commision   += $row->site_fees;
					$total_tax                += $row->tax_amount;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__( "Total", "wpjobster" ).'</td>';
					$tableInfo .= '<td colspan="2"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_transaction_amount)).'</td>';
					$tableInfo .= '<td colspan="4"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_amount_cleared)).'</td>';
					$tableInfo .= '<td></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_shipping)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_buyer_fees)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_seller_commision)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_tax)).'</td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';
		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Transaction Amount','wpjobster'), 'type' => 'number'),
			array('label' => __('Amount cleared','wpjobster'), 'type' => 'number'),
			array('label' => __('Shipping','wpjobster'), 'type' => 'number'),
			array('label' => __('Buyer Fees','wpjobster'), 'type' => 'number'),
			array('label' => __('Seller Commision','wpjobster'), 'type' => 'number'),
			array('label' => __('Tax','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_orders','date_made','',$this->job_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();

	}

	function topup_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$topup_all_orders = array(
			'table' => $this->_prefix.'job_topup_orders',
			'order_by' => 'id DESC',
			'where' => 'payment_status="completed" AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to . $this->topup_where_array,
		);
		$topup_orders = $getQuery->get_wpj_query( $topup_all_orders );

		$tableInfo = '';

		$tableInfo .= '<table id="topup-table" class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th>'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Transaction Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "User Credits", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Earnings", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "User ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Package ID", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$i=1;

				$total_cost     = 0;
				$total_credit   = 0;
				$total_earnings = 0;

				foreach ($topup_orders as $row) {
					$earnings = floatval($row->package_cost_without_tax) - floatval($row->package_credit_without_tax);

					$user = get_user_by('id', $row->user_id);
					$user_name = ($user) ? $user->user_login : '';

					if($row->paid_on && $row->paid_on != 0) $payment_date = date("Y/m/d H:i:s", $row->paid_on);
					else $payment_date = '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.$payment_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->package_cost_without_tax)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->package_credit_without_tax)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($earnings).'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$row->payment_gateway_name.'</td>';
						$tableInfo .= '<td>'.$row->package_id.'</td>';
					$tableInfo .= '</tr>';

					$total_cost += $row->package_cost_without_tax;
					$total_credit += $row->package_credit_without_tax;
					$total_earnings += $earnings;

					$i++;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__( "Total", "wpjobster" ).'</td>';
					$tableInfo .= '<td colspan="2"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_cost)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_credit)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic($total_earnings).'</td>';
					$tableInfo .= '<td colspan="3"></td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';
		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Transaction Amount','wpjobster'), 'type' => 'number'),
			array('label' => __('User Credits','wpjobster'), 'type' => 'number'),
			array('label' => __('Earnings','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_topup_orders','added_on','',$this->topup_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();
	}

	function featured_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$featured_all_orders = array(
			'table' => $this->_prefix.'job_featured_orders',
			'order_by' => 'id DESC',
			'where' => 'payment_status="completed" AND added_on >= ' . $this->from_date . ' AND added_on <= '. $this->from_to . $this->featured_where_array,
		);
		$featured_orders = $getQuery->get_wpj_query( $featured_all_orders );

		$tableInfo = '';

		$tableInfo .= '<table id="featured-table" class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th>'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Feature Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Tax", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "User ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job Title", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Featured duration Homepage", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Featured duration Category", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Featured duration Subcategory", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';
			$tableInfo .= '<tbody>';
				$i=1;

				$total_amount = 0;
				$total_tax    = 0;

				foreach ($featured_orders as $row) {
					$user = get_user_by('id', $row->user_id);
					$user_name = ($user) ? $user->user_login : '';

					$h_start_date = get_featured_start_date('homepage', $row->job_id);
					$h_end_date = get_featured_end_date($h_start_date);

					$c_start_date = get_featured_start_date('category', $row->job_id);
					$c_end_date = get_featured_end_date($c_start_date);

					$s_start_date = get_featured_start_date('subcategory', $row->job_id);
					$s_end_date = get_featured_end_date($s_start_date);

					$temp = array();
					$temp[] = array('v' => (int) $row->id);
					$temp[] = array('v' => (float) $row->featured_amount);
					$rows[] = array('c' => $temp);

					if($row->paid_on && $row->paid_on != 0) $payment_date = date("Y/m/d H:i:s", $row->paid_on);
					else $payment_date = '-';

					if($h_start_date && $h_start_date != 0) $h_start_date = date("Y/m/d", $h_start_date);
					else $h_start_date = '-';

					if($c_start_date && $c_start_date != 0) $c_start_date = date("Y/m/d", $c_start_date);
					else $c_start_date = '-';

					if($s_start_date && $s_start_date != 0) $s_start_date = date("Y/m/d", $s_start_date);
					else $s_start_date = '-';

					if($h_end_date && $h_end_date != 0) $h_end_date = date("Y/m/d", $h_end_date);
					else $h_end_date = '-';

					if($c_end_date && $c_end_date != 0) $c_end_date = date("Y/m/d", $c_end_date);
					else $c_end_date = '-';

					if($s_end_date && $s_end_date != 0) $s_end_date = date("Y/m/d", $s_end_date);
					else $s_end_date = '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.$payment_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->featured_amount)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($row->tax).'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$row->payment_gateway_name.'</td>';
						$tableInfo .= '<td>'.$row->job_id.'</td>';
						$tableInfo .= '<td>'.get_the_title($row->job_id).'</td>';
						$tableInfo .= '<td>'.$h_start_date .' - '. $h_end_date.'</td>';
						$tableInfo .= '<td>'.$c_start_date .' - '. $c_end_date.'</td>';
						$tableInfo .= '<td>'.$s_start_date .' - '. $s_end_date.'</td>';
					$tableInfo .= '</tr>';

					$total_amount += $row->featured_amount;
					$total_tax += $row->tax;

					$i++;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__( "Total", "wpjobster" ).'</td>';
					$tableInfo .= '<td colspan="2"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_amount)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic($total_tax).'</td>';
					$tableInfo .= '<td colspan="7"></td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';
		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Transaction Amount','wpjobster'), 'type' => 'number'),
			array('label' => __('Tax','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_featured_orders','added_on','',$this->featured_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();

	}
	function refund_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$job_all_orders = array(
			'table' => $this->_prefix.'job_orders',
			'order_by' => 'id DESC',
			'where' => 'payment_status="completed" AND closed = 1 AND date_made >= ' . $this->from_date . ' AND date_made <= '. $this->from_to . $this->job_where_array,
		);
		$job_orders = $getQuery->get_wpj_query( $job_all_orders );
		$tableInfo = '';

		$tableInfo .= '<table id="refund-table" class="widefat post fixed" cellspacing="0">';

			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th>'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Refund Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Refund Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Refunded To User", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job Title", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$i=1;
				$total_refund_amount = 0;

				foreach ($job_orders as $row) {
					$user = get_user_by('id', $row->uid);
					$user_name = ($user) ? $user->user_login : '';

					$get_job_id = $getQuery->get_wpj_query( array( 'columns' => 'ID', 'table' => $this->_prefix.'posts', 'where' => 'post_title = "' . $row->job_title .'"' ) );
					$job_id = (isset($get_job_id[0]->ID)) ? $get_job_id[0]->ID : '';

					$get_refund_amount = $getQuery->get_wpj_query( array( 'columns' => 'amount, datemade', 'table' => $this->_prefix.'job_payment_transactions', 'where' => 'oid = "' . $row->id .'" AND rid=7' ) );
					$refund_amount = (isset($get_refund_amount[0]->amount)) ? $get_refund_amount[0]->amount : '';
					$refund_date = (isset($get_refund_amount[0]->datemade)) ? $get_refund_amount[0]->datemade : '';
					$refund_date = $refund_date ? date("Y/m/d H:i:s", $refund_date) : '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.$refund_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($refund_amount)).'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$row->payment_gateway.'</td>';
						$tableInfo .= '<td>'.$job_id.'</td>';
						$tableInfo .= '<td>'.$row->job_title.'</td>';
					$tableInfo .= '</tr>';

					$i++;
					$total_refund_amount += $refund_amount;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total','wpjobster').'</td>';
					$tableInfo .= '<td colspan="2"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_refund_amount)).'</td>';
					$tableInfo .= '<td colspan="4"></td>';
				$tableInfo .= '</tr>';

			$tableInfo .= '</tbody>';

		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Transaction Amount','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_orders','date_made','refund',$this->job_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();
	}

	function withdrawal_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$withdrawal_all_orders = array(
			'table' => $this->_prefix.'job_withdraw',
			'order_by' => 'id DESC',
			'where' => 'datemade >= ' . $this->from_date . ' AND datemade <= '. $this->from_to . $this->withdrawal_where_array,
		);
		$withdrawal_orders = $getQuery->get_wpj_query( $withdrawal_all_orders );

		$tableInfo = '';
		$tableInfo .= '<table id="withdrawal-table" class="widefat post fixed" cellspacing="0">';

			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th>'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Withdrawal Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Withdrawal Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "User ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Status", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Approved On", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$i=1;
				$total_withdrawal = 0;

				foreach ($withdrawal_orders as $row) {
					$user = get_user_by("id", $row->uid);
					$user_name = ($user) ? $user->user_login : '';
					if($row->datedone == 0){
						$status = __("pending","wpjobster");
					}else if($row->rejected_on != 0){
						$status = __("rejected","wpjobster");
					}else if($row->rejected_on == 0 && $row->datedone != 0){
						$status = __("approved","wpjobster");
					}else{
						$status = __("N/A","wpjobster");
					}
					$datedone = ($row->datedone != 0) ? date("Y/m/d H:i:s", $row->datedone) : "N/A";

					$temp = array();
					$temp[] = array('v' => (int) $row->id);
					$temp[] = array('v' => (float) $row->amount);
					$rows[] = array('c' => $temp);

					if($row->datemade && $row->datemade != 0) $withdrawal_date = date("Y/m/d H:i:s", $row->datemade);
					else $withdrawal_date = '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->amount)).'</td>';
						$tableInfo .= '<td>'.$withdrawal_date.'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$row->methods.'</td>';
						$tableInfo .= '<td>'.$status.'</td>';
						$tableInfo .= '<td>'.$datedone.'</td>';
					$tableInfo .= '</tr>';

					$i++;
					$total_withdrawal += $row->amount;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__('Total','wpjobster').'</td>';
					$tableInfo .= '<td></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_withdrawal)).'</td>';
					$tableInfo .= '<td colspan="5"></td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';

		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Amount','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_withdraw','datemade','',$this->withdrawal_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();
	}

	function custom_extra_sales_report( $condition = array() ){

		$getQuery = new WPJ_Query();

		// TABLE FORMAT //

		$custom_extra_all_orders = array(
			'table' => $this->_prefix.'job_orders wjo,' . $this->_prefix.'job_custom_extra_orders wjceo',
			'order_by' => 'wjo.id ASC',
			'where' => 'wjo.id = wjceo.order_id AND wjceo.payment_status="completed" AND wjceo.paid_on >= ' . $this->from_date . ' AND wjceo.paid_on <= '. $this->from_to . $this->job_where_array,
		);
		$custom_extra_orders = $getQuery->get_wpj_query( $custom_extra_all_orders );

		$tableInfo = '';

		$tableInfo .= '<table id="custom-extra-table" class="widefat post fixed" cellspacing="0">';
			$tableInfo .= '<thead>';
				$tableInfo .= '<tr>';
					$tableInfo .= '<th width="5%">'.__( "No", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="5%">'.__( "Transaction ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Payment Date", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Transaction Amount", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="9%">'.__( "User ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Payment Gateway", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Job ID", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="12%">'.__( "Job Title", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Amount cleared", "wpjobster" ).'</th>';
					$tableInfo .= '<th width="8%">'.__( "Delivered date", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Processing Fees", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Seller Commision", "wpjobster" ).'</th>';
					$tableInfo .= '<th>'.__( "Tax", "wpjobster" ).'</th>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</thead>';

			$tableInfo .= '<tbody>';
				$i=1;

				$total_transaction_amount = 0;
				$total_amount_cleared     = 0;
				$total_shipping           = 0;
				$total_buyer_fees         = 0;
				$total_seller_commision   = 0;
				$total_tax                = 0;

				foreach ($custom_extra_orders as $row) {
					$user = get_user_by('id', $row->uid);
					$user_name = ($user) ? $user->user_login : '';

					$get_job_id = $getQuery->get_wpj_query( array( 'columns' => 'ID', 'table' => $this->_prefix.'posts', 'where' => 'post_title = "' . $row->job_title .'"' ) );
					$job_id = (isset($get_job_id[0]->ID)) ? $get_job_id[0]->ID : '';

					$get_custom_order_amount = $getQuery->get_wpj_query( array('table' => $this->_prefix.'job_payment_received', 'where' => 'payment_type_id = "' . $row->id .'" AND payment_type="custom_extra"' ) );
					$order = $get_custom_order_amount[0];
					$transaction_amount = (isset($order->final_amount)) ? $order->final_amount : '';
					$processing_fees = (isset($order->fees)) ? $order->fees : '';
					$tax = (isset($order->tax)) ? $order->tax : '';
					$amount_cleared = floatval($transaction_amount) - floatval($row->site_fees);

					if($order->datemade && $order->datemade != 0) $payment_date = date("Y/m/d H:i:s", $order->datemade);
					else $payment_date = '-';

					if($order->payment_made_on && $order->payment_made_on != 0) $delivered_date = date("Y/m/d H:i:s", $order->payment_made_on);
					else $delivered_date = '-';

					$tableInfo .= '<tr>';
						$tableInfo .= '<td>'.$i.'</td>';
						$tableInfo .= '<td>'.$row->id.'</td>';
						$tableInfo .= '<td>'.$payment_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($transaction_amount).'</td>';
						$tableInfo .= '<td>'.$user_name.'</td>';
						$tableInfo .= '<td>'.$order->payment_gateway.'</td>';
						$tableInfo .= '<td>'.$job_id.'</td>';
						$tableInfo .= '<td>'.$row->job_title.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic($amount_cleared).'</td>';
						$tableInfo .= '<td>'.$delivered_date.'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($processing_fees)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($row->site_fees)).'</td>';
						$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($tax)).'</td>';
					$tableInfo .= '</tr>';

					$i++;

					$total_transaction_amount += $transaction_amount;
					$total_amount_cleared     += $amount_cleared;
					$total_buyer_fees         += $processing_fees;
					$total_seller_commision   += $row->site_fees;
					$total_tax                += $tax;
				}

				$tableInfo .= '<tr>';
					$tableInfo .= '<td>'.__( "Total", "wpjobster" ).'</td>';
					$tableInfo .= '<td colspan="2"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_transaction_amount)).'</td>';
					$tableInfo .= '<td colspan="4"></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_amount_cleared)).'</td>';
					$tableInfo .= '<td></td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_buyer_fees)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_seller_commision)).'</td>';
					$tableInfo .= '<td>'.wpjobster_get_show_price_classic(floatval($total_tax)).'</td>';
				$tableInfo .= '</tr>';
			$tableInfo .= '</tbody>';
		$tableInfo .= '</table>';

		// END TABLE FORMAT //

		// GRAPH FORMAT //

		$graphInfo = array();
		$graphInfo['cols'] = array(
			array('label' => __('Date','wpjobster'), 'type' => 'string'),
			array('label' => __('Transaction Amount','wpjobster'), 'type' => 'number'),
			array('label' => __('Amount cleared','wpjobster'), 'type' => 'number'),
			array('label' => __('Processing Fees','wpjobster'), 'type' => 'number'),
			array('label' => __('Seller Commision','wpjobster'), 'type' => 'number'),
			array('label' => __('Tax','wpjobster'), 'type' => 'number'),
		);

		$graphInfo['rows'] = $this->graph_range_report('job_orders','date_made','custom_extra',$this->job_where_array);

		// END GRAPH FORMAT

		$jsonTable = json_encode(array("tableInfo" => $tableInfo, "graphInfo" => $graphInfo, "userError" => $this->userError));
		echo $jsonTable;

		die();
	}

	function total_credits_owed_to_user(){
		$sql = "SELECT sum(meta_value) as total_credits FROM `wp_usermeta` WHERE meta_key = 'credits' ";
		$result = $this->_wpdb->get_results($sql);
		if($result){
			return wpjobster_get_show_price_classic($result['0']->total_credits);
		}else{
			return false;
		}
	}

	function add_daterange_scripts(){
		$wpjobster_currency_position = get_option('wpjobster_currency_position');
		$wpjobster_currency_symbol_space = get_option('wpjobster_currency_symbol_space');

		wp_enqueue_style( 'custom_wp_admin_css2', get_template_directory_uri() . '/css/daterangepicker.css', false, '2.0.0' );
		wp_enqueue_style( 'custom_wp_admin_css3', get_template_directory_uri() . '/css/salesreport.css', false, '1.0.2' );
		wp_enqueue_script( 'custom_wp_admin_js2', get_template_directory_uri() . '/js/moment.js',  array(), '1.0.2', false );
		wp_enqueue_script( 'custom_wp_admin_js3', get_template_directory_uri() . '/js/daterangepicker.js', array(), '2.0.0', false );
		wp_enqueue_script( 'custom_wp_admin_js4', get_template_directory_uri() . '/js/wpjobster/salesreport.js',  array(), '2.0.0', false );
		wp_localize_script( 'custom_wp_admin_js4', 'base', array(
			'theme_path'     => get_template_directory_uri(),
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'current_symbol' => __('December', 'wpjobster'),
			'amount'         => __('Amount', 'wpjobster'),
			'date'           => __('Date', 'wpjobster'),
			'payment_type'   => __('Payment type', 'wpjobster'),
			'today'          => __('Today', 'wpjobster'),
			'yesterday'      => __('Yesterday', 'wpjobster'),
			'last_7_days'    => __('Last 7 Days', 'wpjobster'),
			'last_30_days'   => __('Last 30 Days', 'wpjobster'),
			'this_month'     => __('This Month', 'wpjobster'),
			'last_month'     => __('Last Month', 'wpjobster'),
			'submit'         => __('Submit', 'wpjobster'),
			'clear'          => __('Clear', 'wpjobster'),
			'from'           => __('From', 'wpjobster'),
			'to'             => __('To', 'wpjobster'),
			'custom'         => __('Cutom', 'wpjobster'),
			'su'             => __('Su', 'wpjobster'),
			'mo'             => __('Mo', 'wpjobster'),
			'tu'             => __('Tu', 'wpjobster'),
			'we'             => __('We', 'wpjobster'),
			'th'             => __('Th', 'wpjobster'),
			'fr'             => __('Fr', 'wpjobster'),
			'sa'             => __('Sa', 'wpjobster'),
			'january'        => __('January', 'wpjobster'),
			'february'       => __('February', 'wpjobster'),
			'march'          => __('March', 'wpjobster'),
			'april'          => __('April', 'wpjobster'),
			'may'            => __('May', 'wpjobster'),
			'june'           => __('June', 'wpjobster'),
			'july'           => __('July', 'wpjobster'),
			'august'         => __('August', 'wpjobster'),
			'september'      => __('September', 'wpjobster'),
			'october'        => __('October', 'wpjobster'),
			'november'       => __('November', 'wpjobster'),
			'december'       => __('December', 'wpjobster'),
			'symbol'         => wpjobster_get_currency_symbol(get_cur()),
			'position'       => $wpjobster_currency_position,
			'space'          => $wpjobster_currency_symbol_space
		));
		wp_enqueue_script( 'custom_wp_admin_js6', get_template_directory_uri() . '/js/loader.js', array( ), '2.0.0', true );
		wp_enqueue_script( 'custom_wp_admin_js7', get_template_directory_uri() . '/js/html5csv.js', array( 'jquery' ), '2.0.0', true );
	}

	function wpjobster_totalsales() {
		// ##### GENERAL SECTION OPTIONS #####
		$id_icon    = 'icon-options-general8';
		$ttl_of_stuff = __('Jobster - Summary', 'wpjobster');
		global $menu_admin_wpjobster_theme_bull;
		$arr = array( "yes" => __( "Yes", 'wpjobster' ), "no" => __( "No", 'wpjobster' ) );

		do_action('sales_report_enqueue_scripts');
		?>
		<div class="wrap sales-report">
			<div class="icon32" id="<?php echo $id_icon; ?>"><br/></div>
			<h2 class="my_title_class_sitemile"><?php echo $ttl_of_stuff; ?></h2>
			<div id="usual2" class="usual">
				<?php // TABS HEADERS ?>
				<ul>
					<li><a href="#tabs1" class="sales-report-title"><?php _e("Summary",'wpjobster'); ?></a></li>
					<li><a href="#tabs2" class="sales-report-title"><?php _e("Job Purchase",'wpjobster'); ?></a></li>
					<li><a href="#tabs3" class="sales-report-title"><?php _e("Topup ",'wpjobster'); ?></a></li>
					<li><a href="#tabs4" class="sales-report-title"><?php _e("Featured",'wpjobster'); ?></a></li>
					<li><a href="#tabs5" class="sales-report-title"><?php _e("Refunded",'wpjobster'); ?></a></li>
					<li><a href="#tabs6" class="sales-report-title"><?php _e("Withdrawal",'wpjobster'); ?></a></li>
					<li><a href="#tabs7" class="sales-report-title"><?php _e("Custom Extra",'wpjobster'); ?></a></li>
				</ul>

				<form action="javascript://" id="form_sales_report" class="">
					<div class="cf">

						<table width="100%" class="sitemile-table">

							<tr>
								<td>
									<div id="reportrange" style="width: 240px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle;">
										<i class="calendar icon"></i>
										<span></span> <i class="caret down icon" style="margin:0"></i>
									</div>

									<select id="graph-range" name="graph-range">
										<option value="days"><?php echo __('Days','wpjobster'); ?></option>
										<option value="weeks"><?php echo __('Weeks','wpjobster'); ?></option>
										<option value="months"><?php echo __('Months','wpjobster'); ?></option>
									</select>
								</td>
							</tr>

							<tr>
								<td>
									<input type="hidden" id="sales_report_action" name="action">
									<input type="hidden" name="report_type" id="report_type" value="table" />
									<input type="hidden" name="from_date" id="from_date" value="" />
									<input type="hidden" name="to_date" id="to_date" value="" />

									<input type="text" id="user-id-or-username" name="userid" placeholder="<?php _e( "User ID/Username", "wpjobster" ); ?>">

									<input type="submit" class="button-secondary" name="submit" value="<?php _e('Filter','wpjobster'); ?>" />
									<span id="user_error_report"></span>
								</td>
							</tr>
							<tr>
								<td>
									<select class="btn-right" name="csv-separator" id="csv-separator">
										<option value=",">,</option>
										<option value=";">;</option>
									</select>
									<span class="csv-separator"><?php echo __('CSV Separator','wpjobster'); ?>:</span>
								</td>
							</tr>

						</table>
					</div>

					<div id="tabs1">
						<button class="btn-right button-secondary" id="btn-summary-csv"><?php echo __('Export Summary Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Summary Report", "wpjobster" ); ?></h2>
						<div id="summary-report-graph" class="report-graph">
							<div id="summary-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="summary-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs2">
						<button class="btn-right button-secondary" id="btn-job-purchase-csv"><?php echo __('Export Job Purchase Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Job Purchase Report", "wpjobster" ); ?></h2>
						<div id="job-report-graph" class="report-graph">
							<div id="job-report-graph-chart" class="graph-position">
								<i class="report-loader notched circle loading icon"></i>
							</div>
						</div>
						<div id="job-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs3">
						<button class="btn-right button-secondary" id="btn-topup-csv"><?php echo __('Export Topup Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Top up Report", "wpjobster" ); ?></h2>
						<div id="topup-report-graph" class="report-graph">
							<div id="topup-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="topup-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs4">
						<button class="btn-right button-secondary" id="btn-featured-csv"><?php echo __('Export Featured Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Featured Report", "wpjobster" ); ?></h2>
						<div id="featured-report-graph" class="report-graph">
							<div id="featured-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="featured-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs5">
						<button class="btn-right button-secondary" id="btn-refund-csv"><?php echo __('Export Refund Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Refund Report", "wpjobster" ); ?></h2>
						<div id="refund-report-graph" class="report-graph">
							<div id="refunded-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="refund-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs6">
						<button class="btn-right button-secondary" id="btn-withdrawal-csv"><?php echo __('Export Withdrawal Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Withdrawal Report", "wpjobster" ); ?></h2>
						<div id="withdrawal-report-graph" class="report-graph">
							<div id="withdrawal-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="withdrawal-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
					<div id="tabs7">
						<button class="btn-right button-secondary" id="btn-custom-extra-csv"><?php echo __('Export Custom Extra Report to CSV','wpjobster'); ?></button>

						<h2 class="report-title"><?php _e( "Custom Extra Report", "wpjobster" ); ?></h2>
						<div id="custom-extra-report-graph" class="report-graph">
							<div id="custom-extra-report-graph-chart" class="graph-position"><i class="report-loader notched circle loading icon"></i></div>
						</div>
						<div id="custom-extra-report-table" class="report-table">
							<i class="report-loader notched circle loading icon"></i>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	function get_date_and_user(){
		$this->user_inp  = isset($_POST['userid']) ? $_POST['userid'] : '';
		$this->from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
		$this->from_to   = isset($_POST['to_date']) ? $_POST['to_date'] : '';

		$date_now = date("Y/m/d");
		if($this->from_to == $date_now)
			$this->from_to = date("Y/m/d H:i:s");

		$this->from_date = strtotime(trim(htmlspecialchars($this->from_date)));
		$this->from_to   = strtotime(trim(htmlspecialchars($this->from_to)));

		if($this->user_inp){
			if(username_exists($this->user_inp)){
				$user = get_userdatabylogin($this->user_inp);
				$this->user_to_search = $user->ID;
			}else{
				$user = get_userdata( $this->user_inp );
				if ( $user === false ) {
					$this->user_to_search = '';
				} else {
					$this->user_to_search = $_POST['userid'];
				}
			}
		}
		if(isset($this->user_to_search) && $this->user_to_search != ''){
			$this->job_where_array = ' AND uid = '.$this->user_to_search;
			$this->topup_where_array = ' AND user_id = '.$this->user_to_search;
			$this->featured_where_array = ' AND user_id = '.$this->user_to_search;
			$this->withdrawal_where_array =  ' AND uid = '.$this->user_to_search;
		}else{
			$this->job_where_array = '';
			$this->topup_where_array = '';
			$this->featured_where_array = '';
			$this->withdrawal_where_array = '';
		}

		if($this->user_inp && $this->user_to_search == ''){
			$this->userError = "This user does not exist!";
		}else{
			$this->userError = "";
		}
	}

	// FUNCTIONS FOR GRAPHS //

	function graph_range_report($table_name='', $date_db_label='', $type='', $whereArr=''){

		$getQuery = new WPJ_Query();

		$rows = array();

		$startTime = $this->from_date;
		$endTime = $this->from_to;

		$day_in_seconds     = 60 * 60 * 24;
		$week_in_seconds    = 60 * 60 * 24 * 7;
		$month_in_seconds   = 60 * 60 * 24 * 30;
		$year_in_seconds    = 60 * 60 * 24 * 365;

		$dateRangeNo = floor( ($endTime - $startTime) / (60 * 60 * 24));

		$rangeType = $_POST['graph-range'];

		if($rangeType == 'months'){
			$rangeToShow = $month_in_seconds;
		} else if($rangeType == 'weeks'){
			$rangeToShow = $week_in_seconds;
		} else {
			$rangeToShow = $day_in_seconds;
		}

		for ( $i = $startTime; $i <= $endTime; $i = $i + $rangeToShow ) {
			$dates = date( 'Y-m-d', $i );
			$datesToTime = strtotime($dates);

			$dateStart = $datesToTime - ($datesToTime % $rangeToShow) + $rangeToShow;
			$dateEnd = $dateStart + $rangeToShow;

			$query_graph = array(
				'table' => $this->_prefix.$table_name,
				'order_by' => 'id DESC',
				'where' => $date_db_label .' > ' . $dateStart . ' AND ' . $date_db_label . ' <= '. $dateEnd . $whereArr,
			);
			if($type =='custom_extra'){
				$query_graph = array(
					'table' => $this->_prefix.'job_orders wjo,' . $this->_prefix.'job_custom_extra_orders wjceo',
					'order_by' => 'wjo.id DESC',
					'where' => 'wjo.id = wjceo.order_id AND date_made >= ' . $dateStart . ' AND date_made <= '. $dateEnd . $whereArr,
				);
			}

			$result_query_graph = $getQuery->get_wpj_query( $query_graph );

			if($table_name == 'job_orders' && $type != 'refund' && $type != 'custom_extra'){
				$temp = $this->get_job_orders_data_graph($result_query_graph, $dates);
			}else if($table_name == 'job_topup_orders'){
				$temp = $this->get_topup_orders_data_graph($result_query_graph, $dates);
			}else if($table_name == 'job_featured_orders'){
				$temp = $this->get_featured_orders_data_graph($result_query_graph, $dates);
			}else if($table_name == 'job_orders' && $type =='refund'){
				$temp = $this->get_refund_orders_data_graph($result_query_graph, $dates);
			}else if($table_name == 'job_withdraw'){
				$temp = $this->get_withdraw_orders_data_graph($result_query_graph, $dates);
			}else if($table_name == 'job_orders' && $type =='custom_extra'){
				$temp = $this->get_custom_extra_orders_data_graph($result_query_graph, $dates);
			}else{
				$temp = array();
			}

			$rows[] = array('c' => $temp);
		}
		return $rows;
	}

	function get_job_orders_data_graph($results, $dates){
		$total_tr_am_graph     = 0;
		$total_am_cl_graph     = 0;
		$total_ship_graph      = 0;
		$total_pr_fees_graph   = 0;
		$total_site_fees_graph = 0;
		$total_tax_am_graph    = 0;

		foreach ($results as $r_graph) {

			$trans_amount_graph = explode('|', $r_graph->final_paidamount);
			if($trans_amount_graph[0] != wpjobster_get_currency_classic()){
				$currency_from_graph = $trans_amount_graph[0];
				$currency_to_graph = wpjobster_get_currency_classic();
				$transaction_amount_graph = get_exchange_value($trans_amount_graph[1], $currency_from_graph, $currency_to_graph);
			}else{
				$transaction_amount_graph = $trans_amount_graph[1];
			}

			$amount_cleared_graph = floatval($r_graph->mc_gross) - floatval($r_graph->site_fees);

			$total_tr_am_graph += $transaction_amount_graph;
			$total_am_cl_graph += $amount_cleared_graph;
			$total_ship_graph += $r_graph->shipping;
			$total_pr_fees_graph += $r_graph->processing_fees;
			$total_site_fees_graph += $r_graph->site_fees;
			$total_tax_am_graph += $r_graph->tax_amount;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_am_graph );
		$temp[] = array('v' => (float) $total_am_cl_graph );
		$temp[] = array('v' => (float) $total_ship_graph );
		$temp[] = array('v' => (float) $total_pr_fees_graph );
		$temp[] = array('v' => (float) $total_site_fees_graph );
		$temp[] = array('v' => (float) $total_tax_am_graph );

		return $temp;
	}

	function get_topup_orders_data_graph($results, $dates){
		$total_tr_amount_graph  = 0;
		$total_usr_credit_graph = 0;
		$total_earnings_graph   = 0;

		foreach ($results as $r_graph) {
			$earnings = floatval($r_graph->package_cost_without_tax) - floatval($r_graph->package_credit_without_tax);
			$total_tr_amount_graph += $r_graph->package_cost_without_tax;
			$total_usr_credit_graph += $r_graph->package_credit_without_tax;
			$total_earnings_graph += $earnings;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_amount_graph );
		$temp[] = array('v' => (float) $total_usr_credit_graph );
		$temp[] = array('v' => (float) $total_earnings_graph );

		return $temp;
	}

	function get_featured_orders_data_graph($results, $dates){
		$total_tr_amount_graph = 0;
		$total_usr_credit      = 0;

		foreach ($results as $r_graph) {
			$total_tr_amount_graph += $r_graph->featured_amount;
			$total_usr_credit += $r_graph->tax;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_amount_graph );
		$temp[] = array('v' => (float) $total_usr_credit );

		return $temp;
	}

	function get_refund_orders_data_graph($results, $dates){
		$getQuery = new WPJ_Query();

		$total_tr_amount_graph = 0;

		foreach ($results as $r_graph) {
			$get_refund_amount = $getQuery->get_wpj_query( array( 'columns' => 'amount, datemade', 'table' => $this->_prefix.'job_payment_transactions', 'where' => 'oid = "' . $r_graph->id .'" AND rid=7' ) );
			$refund_amount = (isset($get_refund_amount[0]->amount)) ? $get_refund_amount[0]->amount : '';

			$total_tr_amount_graph += $refund_amount;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_amount_graph );

		return $temp;
	}

	function get_withdraw_orders_data_graph($results, $dates){

		$total_tr_amount_graph = 0;

		foreach ($results as $r_graph) {
			$total_tr_amount_graph += $r_graph->amount;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_amount_graph );

		return $temp;
	}

	function get_custom_extra_orders_data_graph($results, $dates){
		$getQuery = new WPJ_Query();

		$total_tr_am_graph     = 0;
		$total_am_cl_graph     = 0;
		$total_pr_fees_graph   = 0;
		$total_site_fees_graph = 0;
		$total_tax_am_graph    = 0;

		foreach ($results as $r_graph) {

			$get_custom_order_amount = $getQuery->get_wpj_query( array('table' => $this->_prefix.'job_payment_received', 'where' => 'payment_type_id = "' . $r_graph->id .'" AND payment_type="custom_extra"' ) );

			$total_amount = (isset($get_custom_order_amount[0]->final_amount)) ? $get_custom_order_amount[0]->final_amount : '';
			$total_amount_cleared = floatval($total_tr_am_graph) - floatval($r_graph->site_fees);
			$total_processing_fees = (isset($get_custom_order_amount[0]->fees)) ? $get_custom_order_amount[0]->fees : '';
			$total_taxes = (isset($get_custom_order_amount[0]->tax)) ? $get_custom_order_amount[0]->tax : '';

			$total_tr_am_graph += $total_amount;
			$total_am_cl_graph += $total_amount_cleared;
			$total_pr_fees_graph += $total_processing_fees;
			$total_site_fees_graph += $r_graph->site_fees;
			$total_tax_am_graph += $total_taxes;
		}

		$temp = array();
		$temp[] = array('v' => (string) $dates);
		$temp[] = array('v' => (float) $total_tr_am_graph );
		$temp[] = array('v' => (float) $total_am_cl_graph );
		$temp[] = array('v' => (float) $total_pr_fees_graph );
		$temp[] = array('v' => (float) $total_site_fees_graph );
		$temp[] = array('v' => (float) $total_tax_am_graph );

		return $temp;
	}

	// END FUNCTIONS FOR GRAPHS //

}
$sr = new WPJ_sales_report();
