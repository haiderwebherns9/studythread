<?php
//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//------------------------------------------------

if(!function_exists('wpjobster_my_account_sales_area_function')){
	function wpjobster_my_account_sales_area_function(){

		ob_start();

		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		global $wpdb; $prefix = $wpdb->prefix;

		//-------------------------------------

		global $wp_query;
		$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'home';
		$pages = array( 'home', 'pending-payment', 'delivered', 'completed', 'cancelled' );
		if( ! in_array($pg, $pages) ){ $pg = 'home'; }

		$bal = wpjobster_get_credits($uid);

		if (get_option('wpjobster_enable_user_stats') == 'yes') { ?>
			<div class="ui hidden divider"></div>
				<div class="ui segment chart-wrapper">
					<div class="ui two column stackable grid account-statistics chart-box">
						<div class="four wide column no-padding">
							<div class="earned">
								<h3><?php if(get_user_meta($uid, 'user_total_earnings', true)) {
								echo wpjobster_get_show_price(get_user_meta($uid, 'user_total_earnings', true));
								} else {
									echo '0';
								} ?></h3>
								<span><?php _e("Earned",'wpjobster'); ?></span>
							</div>
						</div>
						<div class="four wide column no-padding">
							<div class="withdrawals">
								<h3><?php echo get_total_withdrawals($uid); ?></h3>
								<span><?php _e("Withdrawals",'wpjobster'); ?></span>
							</div>
						</div>
						<div class="four wide column no-padding">
							<div class="pending-clearance">
								<h3><?php echo get_pending_clearance($uid); ?></h3>
								<span><?php _e("Pending Clearance",'wpjobster'); ?></span>
							</div>
						</div>
						<div class="four wide column no-padding">
							<div class="available-founds">
								<h3><?php
									if (wpjobster_get_credits($uid) > 0) {
										echo wpjobster_get_show_price($bal);
									} else {
										echo "0";
									}
								?></h3>
								<span><?php _e("Available Funds",'wpjobster'); ?></span>
							</div>
						</div>
					</div>

					<?php if (get_option('wpjobster_enable_user_charts') == 'yes') { ?>
						<div class="ui two column stackable grid">
							<div id="chart-div-container" class="sixteen wide column" style="display: none;">
								<?php
									$graph_data = wpjobster_get_graph($uid,(isset($_GET['disp_type'])?$_GET['disp_type']:""),(isset($_GET['select_year'])?$_GET['select_year']:""),(isset($_GET['select_month'])?$_GET['select_month']:""),'sales');
									extract($graph_data);

									wpjobster_show_graph_controls($disp_type,$select_year,$select_month);
									wpjobster_show_graph($data_table,$uid,$type,'sales');
								?>
							</div>
						</div>
					<?php }
				} ?>
			</div>

			<div class="wrapper-graph-dropdown">
				<a class="graph-link"><i class="angle double down icon"></i></a>
			</div>

			<div id="content-full-ov">
				<div class="ui basic notpadded segment">
					<h1 class="ui header wpj-title-icon">
						<i class="tag icon"></i>
						<?php _e("My Sales",'wpjobster'); ?>
					</h1>
				</div>

				<div class="ui basic notpadded segment">
					<?php
					$act_jb = wpjobster_get_number_of_active_jobs($uid);
					$pending_jb = wpjobster_get_number_of_pending_pmt_jobs($uid);
					$del_jb = wpjobster_get_number_of_delivered_jobs($uid);
					$com_jb = wpjobster_get_number_of_completed_jobs($uid);
					$can_jb = wpjobster_get_number_of_cencelled_jobs($uid);
					$using_perm = wpjobster_using_permalinks();

					if($using_perm) $sal_pg_lnk = get_permalink(get_option('wpjobster_my_account_sales_page_id'));
					else $sal_pg_lnk = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_sales_page_id'). "&";
					?>

					<div class="stackable-buttons">

						<a class="ui white button <?php echo ($pg == "home" ? 'active' : ""); ?>" href="<?php echo $sal_pg_lnk; ?>">
							<?php _e("Active","wpjobster")?> <span>&nbsp;(<?php echo $act_jb;?>)</span></a>

						<a class="ui white button <?php echo ($pg == "pending-payment" ? 'active' : ""); ?>" href="<?php echo $sal_pg_lnk; ?>pending-payment">
						<?php _e("Pending Jobs ","wpjobster")?>(<span><?php echo $pending_jb;?></span>)</a>

						<a class="ui white button <?php echo ($pg == "delivered" ? 'active' : ""); ?>" href="<?php echo $sal_pg_lnk; ?>delivered"><?php _e("Delivered ","wpjobster")?>(<span><?php echo $del_jb;?></span>)</a>

						<a class="ui white button <?php echo ($pg == "completed" ? 'active' : ""); ?>" href="<?php echo $sal_pg_lnk; ?>completed"><?php _e("Completed ","wpjobster")?>(<span><?php echo $com_jb;?></span>)</a>

						<a class="ui white button <?php echo ($pg == "cancelled" ? 'active' : ""); ?>" href="<?php echo $sal_pg_lnk; ?>cancelled"><?php _e("Cancelled ","wpjobster")?>(<span><?php echo $can_jb; ?></span>)</a>

					</div>
				</div>

				<div class="ui segment">
					<?php
					if ( $pg == 'home' ) {
						$query_status = 'active';
						$no_jobs_text = __( 'No active jobs.', 'wpjobster' );
					} elseif ( $pg == 'pending-payment' ) {
						$query_status = 'pending_payment';
						$no_jobs_text = __( 'No active jobs.', 'wpjobster' );
					} elseif ( $pg == 'delivered' ) {
						$query_status = 'delivered';
						$no_jobs_text = __( 'No delivered jobs.', 'wpjobster' );
					} elseif ( $pg == 'cancelled' ) {
						$query_status = 'cancelled';
						$no_jobs_text = __( 'No cancelled jobs.', 'wpjobster' );
					} elseif ( $pg == 'completed' ) {
						$query_status = 'completed';
						$no_jobs_text = __( 'No completed jobs.', 'wpjobster' );
					}

					$wpj_job = new WPJ_Load_More_Queries(
						array(
							'query_type'     => 'sales',
							'query_status'   => $query_status,
							'function_name'  => 'wpjobster_show_sale_new',
							'posts_per_page' => '10',
							'new_class_row'  => 'my-account-shopping-list'
						)
					);

					if ( $wpj_job->have_rows() ) { ?>
						<div class="ui two column stackable grid">
							<div class="five wide column payment-title-table">
								<?php _e( 'Order Details', 'wpjobster' ); ?>
							</div>
							<div class="three wide column payment-title-table">
								<?php _e( 'Sold On', 'wpjobster' ); ?>
							</div>
							<div class="three wide column payment-title-table">
								<?php _e( 'Delivery', 'wpjobster' ); ?>
							</div>
							<div class="two wide column payment-title-table">
								<?php _e( 'Total', 'wpjobster' ); ?>
							</div>
							<div class="three wide column payment-title-table">
								<?php _e( 'Status', 'wpjobster' ); ?>
							</div>
							<?php $wpj_job->show_queries_list_func(); ?>
						</div>
					<?php } else {
						echo $no_jobs_text;
					} ?>
				</div>

				<div class="ui hidden divider"></div>

			</div><!-- /content -->
		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
} ?>
