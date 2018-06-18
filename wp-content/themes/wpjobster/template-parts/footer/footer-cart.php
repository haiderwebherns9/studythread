<?php
$defaults = " ";
$settings = get_theme_mod( 'image_demo', $defaults );
?>

<div class="col-2-footer">
	<div class="reponsive-menu payment">
		<ul>
			<?php
			if( $settings != ' ' ){
				foreach( $settings as $setting ){ ?>
					<li><?php echo wp_get_attachment_image( $setting['link_url'] ); ?></li>
				<?php }
			} ?>
		</ul>
	</div>
</div>
