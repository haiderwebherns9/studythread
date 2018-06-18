<?php

if(!function_exists('wpjobster_my_account_area_function')){
	function wpjobster_my_account_area_function(){

		ob_start();

		$vars = wpj_my_account_vars();
		$uid = $vars['uid'];
		$bal = $vars['bal'];

		if (get_option('wpjobster_enable_user_stats') == 'yes') { ?>
			<div class="ui hidden divider"></div>

			<div class="ui segment chart-wrapper">
				<div class="ui two column stackable grid account-statistics chart-box">
						<div class="three wide column no-padding">
							<div class="earned">
								<h3>
									<?php if ( get_user_meta($uid, 'user_total_earnings', true ) ) {
										echo wpjobster_get_show_price( get_user_meta($uid, 'user_total_earnings', true ) );
									} else {
											echo '0';
									} ?>
								</h3>
								<span><?php _e("Earned",'wpjobster'); ?></span>
							</div>
						</div>

						<div class="three wide column no-padding">
							<div class="withdrawals">
							<h3><?php echo get_total_withdrawals($uid); ?></h3>
									<span><?php _e("Withdrawals",'wpjobster'); ?></span>
							</div>
						</div>

						<div class="three wide column no-padding">
							<div class="used-for-orders">
								<h3>
									<?php if(get_user_meta($uid, 'user_total_spendings', true)) {
											echo wpjobster_get_show_price(get_user_meta($uid, 'user_total_spendings', true));
									} else {
											echo '0';
									} ?>
								</h3>
								<span><?php _e("Used for Orders",'wpjobster'); ?></span>
							</div>
						</div>

						<div class="three wide column no-padding">
							<div class="pending-clearance">
								<h3><?php echo get_pending_clearance($uid); ?></h3>
								<span><?php _e("Pending Clearance",'wpjobster'); ?></span>
							</div>
						</div>

						<div class="four wide column no-padding">
							<div class="available-funds">
								<h3>
									<?php if (wpjobster_get_credits($uid) > 0) {
										echo wpjobster_get_show_price($bal);
									} else {
										echo "0";
									} ?>
								</h3>
								<span><?php _e("Available Funds",'wpjobster'); ?></span>
							</div>
					 </div>
				</div>

				<div class="ui two column stackable grid">
					<?php if (get_option('wpjobster_enable_user_charts') == 'yes') { if( is_rtl() ){ $direction = "rtl"; } else { $direction = "ltr"; } ?>
							<div id="chart-div-container" class="sixteen wide column" style="display: none;">
								<?php
								$graph_data = wpjobster_get_graph($uid,(isset($_GET['disp_type'])?$_GET['disp_type']:""),(isset($_GET['select_year'])?$_GET['select_year']:""),(isset($_GET['select_month'])?$_GET['select_month']:""),'my_account');
								extract($graph_data);

								wpjobster_show_graph_controls($disp_type,$select_year,$select_month);
								wpjobster_show_graph($data_table,$uid,$type,'my_account');
								?>
							</div>
					<?php } ?>
				</div>
		<?php } ?></div>

		<div class="wrapper-graph-dropdown">
			<a class="graph-link"><i class="angle double down icon"></i></a>
		</div>

		<div class="wrapper-graph-dropdown my-account-floating-items">
			<div class="vacation-mode-cnt right">
				<?php wpj_vacation_mode_modal(); ?>
			</div>
		</div>

		<div id="content-full-ov">
			<div class="ui basic notpadded segment">
				<div class="ui two column stackable grid">
					<div class="eight wide column">
						<h1 class="ui header wpj-title-icon">
							<i class="announcement icon"></i>
							<?php _e("My Jobs",'wpjobster'); ?>
						</h1>
					</div>
					<div class="eight wide column">
						<div class="account-post-new stackable-buttons">
							<a class="ui primary button my-account-post-btn" href="<?php echo get_permalink(get_option('wpjobster_post_new_page_id')); ?>">
								<?php _e("Post New", "wpjobster"); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

			<?php if ( wpjobster_user_eligible_for_first_badge( $uid ) && get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
				<div class="ui segment">
					<div class="ui two column stackable grid">
						<div class="eight wide column title-badge">
							<h1 class="heading-title" style="display: inline;"><?php _e("Buy your first badge",'wpjobster'); ?></h1>
						</div>
						<div class="eight wide column">
							<div class="account-post-new">
								<a href="<?php bloginfo('url'); ?>/?jb_action=badges" class="ui secondary button"><?php _e("Buy Now", "wpjobster"); ?></a>
							</div>
						</div>
					</div>
				</div>
			<?php } elseif ( wpjobster_user_eligible_for_second_badge( $uid ) && get_option( 'wpjobster_credits_enable' ) != 'no' ) { ?>
				<div class="ui segment">
					<div class="ui two column stackable grid">
						<div class="eight wide column title-badge">
							<h1 class="heading-title" style="display: inline;"><?php _e("Buy your second badge", 'wpjobster'); ?></h1>
						</div>
						<div class="eight wide column">
							<div class="account-post-new">
								<a href="<?php bloginfo('url'); ?>/?jb_action=badges&second_badge=true" class="ui secondary button"><?php _e("Buy Now", "wpjobster"); ?></a>
							</div>
						</div>
					</div>
				</div>
			<?php }

			do_action('wpjobster_my_account_after_title',$uid);
			$using_perm = wpjobster_using_permalinks();
			if($using_perm) $acc_pg_lnk = get_permalink(get_option('wpjobster_my_account_page_id'));
			else $acc_pg_lnk = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_page_id'). "&";

			global $wp_query;
			$pg = isset( $wp_query->query_vars['pg']) ? urldecode($wp_query->query_vars['pg']) : 'active';
			$pages = array( 'active', 'inactive', 'under-review', 'rejected' );
			if( ! in_array($pg, $pages) ){ $pg = 'active'; }
			?>

			<div class="ui basic notpadded segment">
				<div class="stackable-buttons">
					<a class="ui white button <?php echo ($pg == "active" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>active"><?php _e("Active","wpjobster")?> (<span class="ticket-count-active"><?php echo wpjobster_nr_active_jobs($uid); ?></span>)</a>

					<a class="ui white button <?php echo ($pg == "inactive" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>inactive"><?php _e("Inactive","wpjobster")?> (<span class="ticket-count-inactive"><?php echo wpjobster_nr_inactive_jobs($uid); ?></span>)</a>

					<a class="ui white button <?php echo ($pg == "under-review" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>under-review"><?php _e("Under Review","wpjobster")?> (<span class="ticket-count-under-review"><?php echo wpjobster_nr_in_review_jobs($uid); ?></span>)</a>

					<a class="ui white button <?php echo ($pg == "rejected" ? 'active' : ""); ?>" href="<?php echo $acc_pg_lnk; ?>rejected"><?php _e("Rejected","wpjobster")?> (<span class="ticket-count-rejected"><?php echo wpjobster_nr_rejected_jobs($uid); ?></span>)</a>
				</div>
			</div>

			<div class="box_content ui segment">
				<?php

				$vars = wpj_load_more_my_account($pg);
				$wpj_job = $vars['wpj_job'];
				$no_jobs_text = $vars['no_jobs_text'];

				if($wpj_job->have_rows()){ ?>
					<div class="ui two column stackable grid">
						<div class="eight wide column my-account-job-title">
							<?php _e('Job Title', 'wpjobster'); ?>
						</div>
						<div class="two wide column my-account-job-title">
							<div class="my-account-job-date-title">
								<?php _e('Date', 'wpjobster'); ?>
							</div>
						</div>
						<div class="three wide column my-account-job-title">
							<?php _e('Job Price', 'wpjobster'); ?>
						</div>
						<div class="three wide column my-account-job-title">
							<?php _e('Status', 'wpjobster'); ?>
						</div>
						<?php $wpj_job->show_posts_list_func(); ?>
					</div>
				<?php }else{
					echo $no_jobs_text;
				} ?>
			</div>

			<?php
			wpj_deactivate_modal();
			wpj_activate_modal();
			wpj_delete_modal();
			?>

		</div>

		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;
	}
}
?>
