<div class="ui container new-footer-cols cf">
	<?php $widget_columns = get_theme_mod('select_number_cols');
	if( ! $widget_columns || $widget_columns == '' ){
		$widget_columns = 5;
	}

	for ( $x = 1; $x <= $widget_columns; $x++ ) {
		echo '<div class="col-' . $widget_columns . '-footer">';
			if ( is_user_logged_in() ) {
				if ( is_active_sidebar( 'footer-widget-' . $x . '-logged-in' ) ) {
					dynamic_sidebar( 'footer-widget-' . $x . '-logged-in' );
				}
			} else {
				if ( is_active_sidebar( 'footer-widget-' . $x ) ) {
					dynamic_sidebar( 'footer-widget-' . $x );
				}
			}
		echo '</div>';
	}
	?>
</div>
