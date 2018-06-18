<?php
//------------------------------------------------
//
//   (c) WPJobster
//   URL: http://wpjobster.com/
//
//------------------------------------------------

if(!function_exists('wpjobster_my_account_shopping_area_function')){
	function wpjobster_my_account_shopping_area_function(){
		ob_start();

		global $current_user;
		$current_user = wp_get_current_user();
		$uid = $current_user->ID;
		global $wpdb; $prefix = $wpdb->prefix;

		//-------------------------------------

		global $wp_query;
		$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'home';
		$pages = array( 'home', 'pending-review', 'completed', 'pending-payment', 'cancelled' );
		if( ! in_array($pg, $pages) ){ $pg = 'home'; }

		$act_nr = wpjobster_shooping_active_nr($uid);
		$pending_nr = wpjobster_shooping_pending_nr($uid);
		$rev_nr = wpjobster_shooping_review_nr($uid);
		$can_nr = wpjobster_shooping_cancelled_nr($uid);
		$com_nr = wpjobster_shooping_completed_nr($uid);
		$using_perm = wpjobster_using_permalinks();

		if($using_perm) $shp_pg_lnk = get_permalink(get_option('wpjobster_my_account_shopping_page_id'));
		else $shp_pg_lnk = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_shopping_page_id'). "&";

		$bal = wpjobster_get_credits($uid);

		if (get_option('wpjobster_enable_user_stats') == 'yes') { ?>
			<div class="ui hidden divider"></div>
			<div class="ui segment chart-wrapper">
				<div class="ui two column stackable grid account-statistics chart-box">
					<div class="four wide column no-padding">
						<div class="earned">
							<h3><?php echo get_total_spent( $uid ); ?></h3>
							<span><?php _e("Total spent",'wpjobster'); ?></span>
						</div>
					</div>
					<div class="four wide column no-padding">
						<div class="active-orders">
							<h3><?php echo get_pending_clearance_buyer($uid); ?></h3>
							<span><?php _e("Active Orders",'wpjobster'); ?></span>
						</div>
					</div>

					<div class="four wide column no-padding">
						<div class="completed-orders">
							<h3><?php if(get_user_meta($uid, 'user_total_spendings', true)) {
								echo wpjobster_get_show_price(get_user_meta($uid, 'user_total_spendings', true));
								} else {
									echo '0';
									} ?></h3>
							 <span><?php _e("Completed Orders",'wpjobster'); ?></span>
						 </div>
					</div>
					<div class="four wide column no-padding">
						<div class="current-balance">
							<h3><?php
								if (wpjobster_get_credits($uid) > 0) {
									echo wpjobster_get_show_price($bal);
								} else {
									echo "0";
								}
							 ?></h3>
							 <span><?php _e("Current Balance",'wpjobster'); ?></span>
						 </div>
					</div>
				</div>

				<div class="ui two column stackable grid my-chart">
					<?php if (get_option('wpjobster_enable_user_charts') == 'yes') { ?>
						<div id="chart-div-container" class="sixteen wide column" style="display: none;">
							<?php
								$graph_data = wpjobster_get_graph($uid,(isset($_GET['disp_type'])?$_GET['disp_type']:""),(isset($_GET['select_year'])?$_GET['select_year']:""),(isset($_GET['select_month'])?$_GET['select_month']:""),"shopping");
								//print_R($graph_data);
								extract($graph_data);

								wpjobster_show_graph_controls($disp_type,$select_year,$select_month);
								wpjobster_show_graph($data_table,$uid,$type,'shopping');
							?>
						</div>
					<?php }
				} ?>
			</div>
		</div>

		<div class="wrapper-graph-dropdown">
			<a class="graph-link"><i class="angle double down icon"></i></a>
		</div>

		<div id="content-full-ov">

			<div class="ui basic notpadded segment">
				<h1 class="ui header wpj-title-icon">
					<i class="shop icon"></i>
					<?php _e("My Shopping",'wpjobster'); ?>
				</h1>
			</div>

			<?php do_action('wpjobster_shopping_after_title',$uid); ?>

			<div class="ui basic notpadded segment">
				<div class="stackable-buttons">

					<a class="ui white button <?php  echo ($pg == "home" ? 'active' : ""); ?>" href="<?php echo $shp_pg_lnk; ?>"><?php _e("Active","wpjobster")?> <span>&nbsp;(<?php echo $act_nr ;?>)</span></a>

					<a class="ui white button <?php  echo ($pg == "pending-review" ? 'active' : ""); ?>" href="<?php echo $shp_pg_lnk; ?>pending-review"><?php _e("Pending Review","wpjobster")?> <span>&nbsp;(<?php echo $rev_nr;?>)</span></a>

					<a class="ui white button <?php  echo ($pg == "completed" ? 'active' : ""); ?>" href="<?php echo $shp_pg_lnk; ?>completed"><?php _e("Completed","wpjobster")?> <span>&nbsp;(<?php echo $com_nr;?>)</span></a>

					<a class="ui white button <?php  echo ($pg == "pending-payment" ? 'active' : ""); ?>" href="<?php echo $shp_pg_lnk; ?>pending-payment"><?php _e("Pending Payment","wpjobster")?> <span>&nbsp;(<?php echo $pending_nr ;?>)</span></a>

					<a class="ui white button <?php  echo ($pg == "cancelled" ? 'active' : ""); ?>" href="<?php echo $shp_pg_lnk; ?>cancelled"><?php _e("Cancelled","wpjobster")?> <span>&nbsp;(<?php echo $can_nr;?>)</span></a>

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
				} elseif ( $pg == 'pending-review' ) {
					$query_status = 'pending_review';
					$no_jobs_text = __( 'No pending review jobs.', 'wpjobster' );
				} elseif ( $pg == 'cancelled' ) {
					$query_status = 'cancelled';
					$no_jobs_text = __( 'No cancelled jobs.', 'wpjobster' );
				} elseif ( $pg == 'completed' ) {
					$query_status = 'completed';
					$no_jobs_text = __( 'No completed jobs.', 'wpjobster' );
				}

				$wpj_job = new WPJ_Load_More_Queries(
					array(
						'query_type'     => 'shopping',
						'query_status'   => $query_status,
						'function_name'  => 'wpjobster_show_bought_new',
						'posts_per_page' => '10',
						'new_class_row'  => 'my-account-shopping-list'
					)
				);

				if ( $wpj_job->have_rows() ) { ?>
					<div class="ui two wide column stackable grid">
						<div class="five wide column payment-title-table">
							<?php _e( 'Order Details', 'wpjobster' ); ?>
						</div>
						<div class="three wide column payment-title-table">
							<?php _e( 'Purchased On', 'wpjobster' ); ?>
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

				<script>
					function pending_order_process(act,order_id,payment_gateway){
						if(act=='process'){
							window.location="<?php bloginfo('url'); ?>/?pay_for_item="+payment_gateway+"&order_id="+order_id+"&process_pending=1";
							return;
						}else{
							jQuery.ajax({
								type: "POST",
								url: ajaxurl,
								data: "action=process_pending_order&process="+act+"&order_id=" + order_id,
								success: function(msg){
									window.location.reload();
								}
							});
						}
					}
				</script>
			</div>

			<div class="ui hidden divider"></div>

		</div>
		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
} ?>
