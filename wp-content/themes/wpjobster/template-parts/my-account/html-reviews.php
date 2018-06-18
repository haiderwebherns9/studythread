<?php
if ( ! function_exists( 'wpjobster_my_account_reviews_area_function' ) ) {
	function wpjobster_my_account_reviews_area_function() {

		ob_start();

		$vars = wpj_reviews_vars();
		$uid = $vars['uid'];
		$third_page = $vars['third_page'];

		if(empty($third_page)) $third_page = 'home'; ?>

		<div id="content-full-ov">
			<div class="ui basic notpadded segment">
				<h1 class="ui header wpj-title-icon">
					<i class="star icon"></i>
					<?php _e("My Ratings",'wpjobster'); ?>
				</h1>
			</div>

			<div class="ui basic notpadded segment">
				<?php
				$using_perm = wpjobster_using_permalinks();
				if($using_perm) $rev_pg_lnk = get_permalink(get_option('wpjobster_my_account_reviews_page_id'));
				else $rev_pg_lnk = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_reviews_page_id'). "&";
				?>

				<div class="ui basic notpadded segment">
					<div class="stackable-buttons">

						<a class="ui white button <?php echo ($third_page == "home" ? 'active' : ""); ?>" href="<?php echo $rev_pg_lnk; ?>"><?php _e("Ratings to Award","wpjobster"); ?></a>

						<a class="ui white button <?php echo ($third_page == "pending-ratings" ? 'active' : ""); ?>" href="<?php echo $rev_pg_lnk; ?>pending-ratings"><?php _e("Pending Ratings","wpjobster"); ?></a>

						<a class="ui white button <?php echo ($third_page == "my-ratings" ? 'active' : ""); ?>" href="<?php echo $rev_pg_lnk; ?>my-ratings"><?php _e("My Ratings","wpjobster"); ?></a>

					</div>
				</div>
			</div>

			<script>
				jQuery(document).ready(function() {
					jQuery('.dd-submit-rating').click(function() {
					var id = jQuery(this).attr('rel');

					var uprating = jQuery("input[name=stars]:checked", "#rating-" + id).val();
					var reason = jQuery("#reason-" + id).val();
					if(reason.length < 10) { alert("<?php _e('Please input a longer description for your rating','wpjobster'); ?>"); return false; }
					if(uprating === undefined) { alert("<?php _e('Please select the amount of stars','wpjobster'); ?>"); return false; }
					jQuery.ajax({
						type: "POST",
						url: "<?php echo get_bloginfo('url'); ?>/",
						data: "rate_me=1&ids="+id+"&uprating="+uprating+"&reason="+reason,
						success: function(msg){
							jQuery("#post-" + id).hide('slow');
						}
						});
					return false;
					});
				});
			</script>

			<?php wpj_feedback_tabs($third_page); ?>

		</div>
		
		<?php
		$ret = ob_get_contents();
		ob_clean();

		return $ret;	
	}
}
?>
