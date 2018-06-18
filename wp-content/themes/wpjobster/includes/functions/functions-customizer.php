<?php
require_once get_template_directory() . '/vendor/kirki/wpjobster-kirki.php';

function tcx_register_theme_customizer( $wp_customize ) {

	// HEADER CUSTOMIZER
	WPJ_Kirki::add_config( 'wpjobster', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );

	$wp_customize->add_section('header_options', array(
		'title'    => __('Header Options', 'wpjobster'),
		'description' => '',
		'priority' => 2,
	));

	$wp_customize->add_setting('site_logo', array(
		'default' => ''
	));

	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'site_logo', array(
		'label'    => __('Logo', 'wpjobster'),
		'section'  => 'header_options',
		'settings' => 'site_logo',
	)));

	Kirki::add_field( 'logo_height', array(
		'type'        => 'slider',
		'settings'    => 'logo_height',
		'label'       => esc_attr__( 'Logo Height', 'wpjobster' ),
		'section'     => 'header_options',
		'default'     => 44,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'logo_spacing', array(
		'type'        => 'spacing',
		'settings'    => 'logo_spacing',
		'label'       => __( 'Logo Spacing', 'wpjobster' ),
		'section'     => 'header_options',
		'description' => 'For spacing you can use px, %, em, rem',
		'default'     => array(
			'top'    => '10px',
			'bottom' => '0px',
			'left'   => '0px',
			'right'  => '0px',
		),
		'priority'    => 10,
	) );

	Kirki::add_field( 'header_fixed', array(
		'type'        => 'switch',
		'settings'    => 'header_fixed',
		'label'       => __( 'Header Fixed on Scroll', 'wpjobster' ),
		'section'     => 'header_options',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => array(
			'on'  => esc_attr__( 'Enable', 'wpjobster' ),
			'off' => esc_attr__( 'Disable', 'wpjobster' ),
		),
	) );

	// TYPOGRAPHY CUSTOMIZER

	$wp_customize->add_section('typography_options', array(
		'title'    => __('Typography Options', 'wpjobster'),
		'description' => '',
		'priority' => 30,
	));

	Kirki::add_field( 'primary_font_family', array(
		'type'        => 'typography',
		'settings'    => 'primary_font_family',
		'label'       => esc_attr__( 'Primary Font Family', 'kirki' ),
		'section'     => 'typography_options',
		'default'     => array(
			'font-family'    => 'Helvetica,Arial,sans-serif',
			'variant'        => 'regular',
		),
		'priority'    => 1,
		'output'      => array(
			array(
				'element' => 'body',
			),
		),
	) );

	Kirki::add_field( 'secondary_font_family', array(
		'type'        => 'typography',
		'settings'    => 'secondary_font_family',
		'label'       => esc_attr__( 'Secondary Font Family', 'kirki' ),
		'section'     => 'typography_options',
		'default'     => array(
			'font-family'    => 'Lato',
			'variant'        => 'regular',
		),
		'priority'    => 2,
		'output'      => array(
			array(
				'element' => 'body',
			),
		),
	) );

	Kirki::add_field( 'h1_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h1_font_size',
		'label'       => esc_attr__( 'H1 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 24,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'h2_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h2_font_size',
		'label'       => esc_attr__( 'H2 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 18,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'h3_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h3_font_size',
		'label'       => esc_attr__( 'H3 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 16,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'h4_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h4_font_size',
		'label'       => esc_attr__( 'H4 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 14,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'h5_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h5_font_size',
		'label'       => esc_attr__( 'H5 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 12,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'h6_font_size', array(
		'type'        => 'slider',
		'settings'    => 'h6_font_size',
		'label'       => esc_attr__( 'H6 Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 10,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'p_font_size', array(
		'type'        => 'slider',
		'settings'    => 'p_font_size',
		'label'       => esc_attr__( 'Paragraph Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 14,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'anchor_font_size', array(
		'type'        => 'slider',
		'settings'    => 'anchor_font_size',
		'label'       => esc_attr__( 'Anchor Font Size', 'wpjobster' ),
		'section'     => 'typography_options',
		'default'     => 14,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	// COLORS CUSTOMIZER

	$wp_customize->add_section('color_options', array(
		'title'    => __('Color Options', 'wpjobster'),
		'description' => '',
		'priority' => 120,
	));

	Kirki::add_field( 'primary_color', array(
		'type'        => 'color',
		'settings'    => 'primary_color',
		'label'       => __( 'Primary Color', 'wpjobster' ),
		'section'     => 'color_options',
		'default'     => '#83C124',
		'priority'    => 1,
		'choices'     => array(
			'alpha' => true,
		),
	) );

	Kirki::add_field( 'secondary_color', array(
		'type'        => 'color',
		'settings'    => 'secondary_color',
		'label'       => __( 'Secondary Color', 'wpjobster' ),
		'section'     => 'color_options',
		'default'     => '#2D5767',
		'priority'    => 2,
		'choices'     => array(
			'alpha' => true,
		),
	) );

	// FOOTER CUSTOMIZER

	$wp_customize->add_section('footer_options', array(
		'title'    => __('Footer Options', 'wpjobster'),
		'description' => '',
		'priority' => 120,
	));

	Kirki::add_field( 'copyright_text', array(
		'type'     => 'textarea',
		'settings' => 'copyright_text',
		'label'    => __( 'Copyright', 'wpjobster' ),
		'section'  => 'footer_options',
		'default'  => esc_attr__( '&copy; 2017 ', 'wpjobster' ) . '<a href="http://wpjobster.com">wpjobster.com</a>',
		'priority' => 1,
	) );

	Kirki::add_field( 'select_number_cols', array(
		'type'        => 'select',
		'settings'    => 'select_number_cols',
		'label'       => __( 'Select Footer Columns', 'wpjobster' ),
		'section'     => 'footer_options',
		'default'     => '5',
		'priority'    => 3,
		'multiple'    => 1,
		'choices'     => array(
			'2' => esc_attr__( '2 Columns', 'wpjobster' ),
			'3' => esc_attr__( '3 Columns', 'wpjobster' ),
			'4' => esc_attr__( '4 Columns', 'wpjobster' ),
			'5' => esc_attr__( '5 Columns', 'wpjobster' ),
			'6' => esc_attr__( '6 Columns', 'wpjobster' ),
		),
	) );

	Kirki::add_field( 'image_demo', array(
		'type'        => 'repeater',
		'label'       => esc_attr__( 'Repeater Cards', 'wpjobster' ),
		'section'     => 'footer_options',
		'priority'    => 4,
		'row_label' => array(
			'type' => 'image',
			'value' => esc_attr__('Card', 'wpjobster' ),
		),
		'settings'    => 'image_demo',
		'default'     => array(
			array(
				'link_url'  => '',
			),
		),
		'fields' => array(
			'link_url' => array(
				'type'        => 'image',
				'label'       => __( 'Card', 'wpjobster' ),
				'description' => __( 'Upload card images', 'wpjobster' ),
				'default'     => '',
			),
		)
	) );

	Kirki::add_field( 'footer_background', array(
		'type'        => 'color',
		'settings'    => 'footer_background',
		'label'       => __( 'Background Footer', 'wpjobster' ),
		'section'     => 'footer_options',
		'default'     => '#4A4D52',
		'priority'    => 10,
		'choices'     => array(
			'alpha' => true,
		),
	) );

	Kirki::add_field( 'scroll_to_top_background', array(
		'type'        => 'color',
		'settings'    => 'scroll_to_top_background',
		'label'       => __( 'Background Scroll to Top', 'wpjobster' ),
		'section'     => 'footer_options',
		'default'     => '#737B88',
		'priority'    => 10,
		'choices'     => array(
			'alpha' => true,
		),
	) );

	Kirki::add_field( 'scroll_to_top_border_size', array(
		'type'        => 'slider',
		'settings'    => 'scroll_to_top_border_size',
		'label'       => esc_attr__( 'Border Size Scroll to Top', 'wpjobster' ),
		'section'     => 'footer_options',
		'default'     => 0,
		'choices'     => array(
			'min'  => '0',
			'max'  => '100',
			'step' => '1',
		),
	) );

	Kirki::add_field( 'scroll_to_top_border_style', array(
		'type'        => 'select',
		'settings'    => 'scroll_to_top_border_style',
		'label'       => __( 'Border Style Scroll to Top', 'my_textdomain' ),
		'section'     => 'footer_options',
		'default'     => 'solid',
		'priority'    => 10,
		'multiple'    => 1,
		'choices'     => array(
			'none'    => esc_attr__( 'none', 'wpjobster' ),
			'hidden'  => esc_attr__( 'hidden', 'wpjobster' ),
			'dotted'  => esc_attr__( 'dotted', 'wpjobster' ),
			'dashed'  => esc_attr__( 'dashed', 'wpjobster' ),
			'solid'   => esc_attr__( 'solid', 'wpjobster' ),
			'double'  => esc_attr__( 'double', 'wpjobster' ),
			'groove'  => esc_attr__( 'groove', 'wpjobster' ),
			'ridge'   => esc_attr__( 'ridge', 'wpjobster' ),
			'inset'   => esc_attr__( 'inset', 'wpjobster' ),
			'outset'  => esc_attr__( 'outset', 'wpjobster' ),
			'initial' => esc_attr__( 'initial', 'wpjobster' ),
			'inherit' => esc_attr__( 'inherit', 'wpjobster' ),
		),
	) );

	Kirki::add_field( 'scroll_to_top_border_color', array(
		'type'        => 'color',
		'settings'    => 'scroll_to_top_border_color',
		'label'       => esc_attr__( 'Border Color Scroll to Top', 'wpjobster' ),
		'section'     => 'footer_options',
		'default'     => '#737B88',
		'priority'    => 10,
		'choices'     => array(
			'alpha' => true,
		),
	) );

	Kirki::add_field( 'scroll_icon_color', array(
		'type'        => 'multicolor',
		'settings'    => 'scroll_icon_color',
		'label'       => esc_attr__( 'Scroll Icon Color', 'wpjobster' ),
		'section'     => 'footer_options',
		'priority'    => 10,
		'choices'     => array(
			'link'    => esc_attr__( 'Color', 'wpjobster' ),
			'hover'   => esc_attr__( 'Hover', 'wpjobster' ),
		),
		'default'     => array(
			'link'    => '#ffffff',
			'hover'   => '#83C124',
		),
	) );

}
add_action( 'customize_register', 'tcx_register_theme_customizer' );

add_action( 'wp_ajax_nopriv_wpjobster_save_less_css_file', 'wpjobster_save_less_css_file' );
add_action( 'wp_ajax_wpjobster_save_less_css_file', 'wpjobster_save_less_css_file' );
function wpjobster_save_less_css_file(){
	$upload_dir = wp_upload_dir();

	$dirname = $upload_dir['basedir'].'/wpjobster';

	if ( ! file_exists( $dirname ) ) {
		wp_mkdir_p( $dirname );
	}

	$path = $upload_dir['basedir'] . '/wpjobster/semantic.css';
	file_put_contents( $path, stripslashes( $_POST['content'] ) );

	update_option( 'wpjobster_compile_original_colours', 'done' );
	update_option( 'wpj_last_css_compiled', time() );

	wp_die();
}

function pagination($pages = '', $range = 4){
	 $showitems = ($range * 2)+1;

	 global $paged;
	 if(empty($paged)) $paged = 1;

	 if($pages == '')
	 {
		 global $wp_query;
		 $pages = $wp_query->max_num_pages;
		 if(!$pages)
		 {
			 $pages = 1;
		 }
	 }

	 if(1 != $pages)
	 {
		 echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
		 if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
		 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";

		 for ($i=1; $i <= $pages; $i++)
		 {
			 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			 {
				 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
			 }
		 }

		 if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
		 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
		 echo "</div>\n";
	 }
}

function wpj_semantic_sidebar_left(){

	global $site_url_localized;

	if (is_user_logged_in()) {
		do_action( 'display_user_responsive_links' );
	} else {
		?>
		<ul class="reset">
			<li><a class="item login-link" href="<?php echo $site_url_localized; ?>/wp-login.php">
				<?php echo __("Login","wpjobster"); ?>
			</a></li>
			<li><a class="item register-link" href="<?php echo $site_url_localized; ?>/wp-login.php?action=register">
				<?php echo __("Register","wpjobster"); ?>
			</a></li>
			<?php
				wpj_generate_language_select_responsive();
				display_currency_select_mobile();
			?>
		</ul>
		<?php
	}
}

function wpj_semantic_sidebar_right() { ?>

	<div class="mobile-search-input-container">
		<?php wpjobster_display_reponsive_search(); ?>
	</div>

	<div class="category-menu">
		<?php wp_nav_menu(array(
			'theme_location' => 'wpjobster_responsive_secondary_menu',
			'container'      => '',
			'menu_class'     => 'nav reset',
			'walker'         => new Add_Class_To_Sub_Menu(),
			'fallback_cb'    => '')
		);?>
	</div>

<?php }

function wpj_set_cookie_colors(){
	setcookie("primaryColor", get_theme_mod( 'primary_color' ) ,time()+86400 ,'/');
	setcookie("secondaryColor", get_theme_mod( 'secondary_color' ) ,time()+86400 ,'/');

	$primary_font = get_theme_mod( 'primary_font_family' );
	setcookie("fontName", $primary_font['font-family'], time()+86400 ,'/');
}
add_action( 'customize_save_after','wpj_set_cookie_colors' );

function wpj_compile_colours_error() {
	if ( get_option( 'wpjobster_compile_original_colours' ) != 'done'
		&& get_option( 'wpjobster_update_413' ) == 'done' ) {
		// display this only when updating from v4.x.x

		echo '<div class="notice notice-warning padd10">';

			$message = '<h2>' . __( "Color Options", "wpjobster" ) . '</h2>';

			$message .= __( "Your color options need to be compiled into a new CSS file.", "wpjobster" );
			$message .= '<br>';
			$message .= __( "Please visit Appearance -> Customize -> Color Options and click the Save button.", "wpjobster" );

			echo $message;
			?>
			<br><br>
			<a class="button action" href="<?php echo admin_url( 'customize.php?autofocus[section]=color_options' ); ?>"><?php _e('Color Options', 'wpjobster'); ?></a>
			<?php
		echo '</div>';
	}
}
add_action( 'admin_notices', 'wpj_compile_colours_error' );
