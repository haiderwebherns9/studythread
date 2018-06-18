<?php $wpjobster_enable_second_footer = get_option('wpjobster_enable_second_footer');
	if($wpjobster_enable_second_footer == "yes"){ ?>
		<div id="stretch_footer_area">
			<div id="stretch_footer_area_inner">
				<?php
				echo '<div class="padd10"><ul class="xoxo">';
					dynamic_sidebar( 'footer-stretch-area' );
				echo '</ul></div>';
				?>
			</div>
		</div>
<?php } ?>
