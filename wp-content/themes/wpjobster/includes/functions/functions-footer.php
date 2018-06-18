<?php
add_filter( 'copyright_text_new', 'copyright_footer_text' );
function copyright_footer_text() {
	if( get_theme_mod( 'copyright_text') != "" ) {
		echo get_theme_mod( 'copyright_text' );
	}
}

add_action( 'widgets_init', 'wpb_widgets_init' );
function wpb_widgets_init() {

	$customizer_columns_select = get_theme_mod('select_number_cols');
	if ( ! $customizer_columns_select || $customizer_columns_select == ''  ){
		$customizer_columns_select = 5;
	}

	for ( $x = 1; $x <= $customizer_columns_select; $x++ ) {

		register_sidebar( array(
			'name'          => 'Footer Widget ' . $x,
			'id'            => 'footer-widget-' . $x,
			'before_widget' => '<div class="chw-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="chw-title">',
			'after_title'   => '</h2>',
		) );

		register_sidebar( array(
			'name'          => 'Footer Widget ' . $x . ' Logged In',
			'id'            => 'footer-widget-' . $x . '-logged-in',
			'before_widget' => '<div class="chw-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="chw-title">',
			'after_title'   => '</h2>',
		) );

	}
}
