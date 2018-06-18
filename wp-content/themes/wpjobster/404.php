<?php get_header(); ?>
<div class="col2 page404">
	<div>
		<div class="not_found"><strong>404</strong><span><?php echo __("Page Not Found","wpjobster"); ?></span></div>
	</div>
	<div class="ul">
		<h2 class="small"><?php echo __("This is not the page you are looking for","wpjobster"); ?></h2>
		<?php
		if(!user()){
			wp_nav_menu(array("menu"=>"Footer Menu Not Logged In","container"=>""));
		}else{
			wp_nav_menu(array("menu"=>"Footer Menu Logged In","container"=>""));
		}
		 ?>
	</div>

</div>
<?php get_footer(); ?>
