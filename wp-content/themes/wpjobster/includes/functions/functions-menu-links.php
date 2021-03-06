<?php
//----------------------------------------
// Add Class to menu items
//----------------------------------------

function custom_nav_class($classes, $item){
	$classes[] = "custom-class-".$item->menu_order;
	return $classes;
}
add_filter('nav_menu_css_class' , 'custom_nav_class' , 10 , 2);

function add_last_item_class($strHTML) {
	$intPos = strripos($strHTML,'menu-item');
	printf("%s last_item %s",
	substr($strHTML,0,$intPos),
	substr($strHTML,$intPos,strlen($strHTML))
	);
}
add_filter('wp_nav_menu','add_last_item_class');

function add_first_and_last($output) {
	$output = preg_replace('/class="menu-item/', 'class="first-menu-item menu-item', $output, 1);
	$output = substr_replace($output, strripos($output, 'class="menu-item'), strlen('class="menu-item'));
	return $output;
}
add_filter('wp_nav_menu', 'add_first_and_last');

class DD_Wolker_Menu extends Walker_Nav_Menu {
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
		$GLOBALS['dd_children'] = ( isset($children_elements[$element->ID]) )? 1:0;
		$GLOBALS['dd_depth'] = (int) $depth;
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
add_filter('nav_menu_css_class','add_parent_css',10,2);
function  add_parent_css($classes, $item){
	global  $dd_depth, $dd_children;
	$classes[] = 'depth'.$dd_depth;
	if($dd_children)
		 $classes[] = 'parent';
	return $classes;
}

function link_to_menu_editor($args) {

	if (!current_user_can('manage_options')) {
		return;
	}

	// see wp-includes/nav-menu-template.php for available arguments
	extract($args);

	$link = $link_before
		.'<a href="'.admin_url('nav-menus.php').'">'.$before.__('Add a menu here', 'wpjobster').$after.'</a>'
		.$link_after;

	// We have a list
	if (FALSE !== stripos($items_wrap, '<ul')
		or FALSE !== stripos($items_wrap, '<ol')) {

		$link = "<li>$link</li>";
	}

	$output = sprintf($items_wrap, $menu_id, $menu_class, $link);
	if (!empty($container)) {
		$output  = "<$container class='$container_class' id='$container_id'>$output</$container>";
	}

	if ($echo) {
		echo $output;
	}

	return $output;
}

function wpjobster_get_browse_jobs_link($taxonomy, $term, $sort = 'auto', $page = 1, $term_search = ''){
	$using_permalinks = wpjobster_using_permalinks();
	global $wp_query;
	$query_vars = $wp_query->query_vars;

	if (empty($term_search))        $term_search = $query_vars['term_search'];

	if ($using_permalinks == true) {

		if (empty($term_search))            return get_bloginfo('url') . "/jobs/" . $taxonomy . "/" . $term . "/" . $sort . "/page/" . $page; else            return get_bloginfo('url') . "/jobs/" . $taxonomy . "/" . $term . "/" . $sort . "/page/" . $page . "/?term_search=" . $term_search;
	} else {

		if (empty($term_search))            return get_bloginfo('url') . "/index.php?jb_action=jobs_total&job_sort=" . $sort . "&job_category=" . $term . "&job_tax=" . $taxonomy . "&page=" . $page; else            return get_bloginfo('url') . "/index.php?jb_action=jobs_total&job_sort=" . $sort . "&job_category=" . $term . "&job_tax=" . $taxonomy . "&page=" . $page . "&term_search=" . $term_search;
	}

}

function wpjobster_get_adv_search_pagination_link($pg){
	$page_id = get_option('wpjobster_advanced_search_id');
	$using_perm = wpjobster_using_permalinks();

	if ($using_perm)        $ssk = get_permalink(($page_id)) . "?pj=" . $pg; else        $ssk = get_bloginfo('url') . "/?page_id=" . ($page_id) . "&pj=" . $pg;
	$trif = '';
	foreach ($_GET as $key => $value) {

		if ($key != "pj" and $key != 'page_id' and $key != "custom_field_id")            $trif .= '&' . $key . "=" . $value;
	}


	if (is_array($_GET['custom_field_id']))        foreach ($_GET['custom_field_id'] as $values)            $trif .= "&custom_field_id[]=" . $values;
	return $ssk . $trif;
}

function wpjobster_post_new_link(){
	return get_permalink(get_option('wpjobster_post_new_page_id'));
}

function wpjobster_blog_link(){
	return get_permalink(get_option('wpjobster_blog_home_id'));
}

function wpjobster_my_account_link(){
	return get_permalink(get_option('wpjobster_my_account_page_id'));
}

function wpjobster_my_requests_link(){
	return get_permalink(get_option('wpjobster_my_requests_page_id'));
}

function wpjobster_new_request_link(){
	return get_permalink(get_option('wpjobster_new_request_page_id'));
}

function wpjobster_my_favorites_link(){
	return get_permalink(get_option('wpjobster_my_favorites_page_id'));
}

function wpjobster_is_adv_src_pg(){
	global $post;

	if ($post->ID == get_option('wpjobster_advanced_search_id')) return true;
	return false;
}

function wpj_get_subscription_info_path() {
	$path = include_once( get_template_directory() . '/classes/subscriptions/views/wpjobster_subscription_info.php');
	return $path;
}

add_action( 'display_user_dropdown_links', 'wpjobster_display_user_dropdown_links' );
if ( ! function_exists( 'wpjobster_display_user_dropdown_links' ) ) {
	function wpjobster_display_user_dropdown_links() {
		// Show Menu
		$drop_down_user_menu = wpjobster_sort_menu(wpjobster_dropdown_menu(),'DESC');
		$numItems = count($drop_down_user_menu); ?>
		<ul>
			<?php $i = 0; foreach ($drop_down_user_menu as $menu) {
				if(++$i !== $numItems) { ?>
					<li <?php if( $menu['childs'] ){ echo 'class="nh-accordion-container"'; } ?>>
						<a <?php if( $menu['childs'] ){ echo 'class="nh-accordion-handler"'; } ?> href="<?php if( is_numeric( $menu['url'] ) ){ echo get_permalink( $menu['url'] ); }else{ echo $menu['url']; } ?>"><?php echo $menu['label']; ?></a>
						<?php
							if( $menu['childs'] ){
                           
								$childs = wpjobster_sort_menu($menu['childs'],'DESC'); ?>
								<div class="nh-accordion styled" style="display: none;">
									<ul>
										<?php foreach ($childs as $child) { ?>
											<li><a href="<?php if( is_numeric( $child['url'] ) ){ echo get_permalink( $child['url'] ); }else{ echo $child['url']; } ?>"><?php echo $child['label']; ?></a></li>
										<?php } ?>
									</ul>
								</div>
							<?php }
						?>
					</li>
				<?php
				}
			}

			wp_nav_menu(
				array(
					'theme_location' => 'wpjobster_header_user_dropdown_extra',
					'container'      => '',
					'menu_class'     => '',
					'fallback_cb'    => '',
					'items_wrap'     => '%3$s'
				)
			);

			$i = 0; foreach ($drop_down_user_menu as $menu) {
				if(++$i === $numItems) { ?>
					<li>
						<a href="<?php if( is_numeric( $menu['url'] ) ){ echo get_permalink( $menu['url'] ); }else{ echo $menu['url']; } ?>"><?php echo $menu['label']; ?></a>
					</li>
				<?php }
			} ?>

		</ul>
	<?php }
}

add_action( 'display_user_responsive_links', 'wpjobster_display_user_responsive_links' );
if ( ! function_exists( 'wpjobster_display_user_responsive_links' ) ) {
	function wpjobster_display_user_responsive_links() {
		// Show Menu
		$drop_down_user_menu = wpjobster_sort_menu(wpjobster_dropdown_menu(),'DESC');
		$numItems = count($drop_down_user_menu); ?>

		<ul class="reset">
			<?php $i = 0; foreach ($drop_down_user_menu as $menu) {
				if(++$i !== $numItems) { ?>
					<li <?php if( $menu['childs'] ){ echo 'class="nh-accordion-container"'; } ?>>
						<a class="item <?php if( $menu['childs'] ){ echo 'nh-accordion-handler'; } ?>" href="<?php if( is_numeric( $menu['url'] ) ){ echo get_permalink( $menu['url'] ); }else{ echo $menu['url']; } ?>"><?php echo $menu['label']; ?></a>


						<?php
							if( $menu['childs'] ){

								$childs = wpjobster_sort_menu($menu['childs'],'DESC'); ?>
								<div class="nh-accordion" style="display: none;">
									<ul class="reset">
										<?php foreach ($childs as $child) { ?>
											<li><a class="item" href="<?php if( is_numeric( $child['url'] ) ){ echo get_permalink( $child['url'] ); }else{ echo $child['url']; } ?>"><?php echo $child['label']; ?></a></li>
										<?php } ?>
									</ul>
								</div>
							<?php }
						?>


					</li>
				<?php
				}
			}

			wp_nav_menu(
				array(
					'theme_location' => 'wpjobster_responsive_main_menu',
					'container'      => '',
					'menu_class'     => 'nav reset',
					'fallback_cb'    => '',
					'items_wrap'     => '%3$s'
				)
			);

			wpj_generate_language_select_responsive();

			display_currency_select_mobile();

			$i = 0; foreach ($drop_down_user_menu as $menu) {
				if(++$i === $numItems) { ?>
					<li>
						<a class="item" href="<?php if( is_numeric( $menu['url'] ) ){ echo get_permalink( $menu['url'] ); }else{ echo $menu['url']; } ?>"><?php echo $menu['label']; ?></a>
					</li>
				<?php }
			} ?>
		</ul>
		<?php
	}
}

// Dropdown Menu Array
if (!function_exists('wpjobster_dropdown_menu')) {
	function wpjobster_dropdown_menu( ){
	global $current_user;
	$current_user = wp_get_current_user();
	$uid = $current_user->ID;
	$type=user($uid, 'wpjobster_user_type');

		$dropdown_user_menu = array(
			'jobs' => array(
				'label' => __( 'Jobs', 'wpjobster' ),
				'url' => '',
				'childs' => array(
					'new_job' => array(
						'label' => __( 'Post New', 'wpjobster' ),
						'url' => get_option( 'wpjobster_post_new_page_id' ),
						'order' =>'1a',
					),
					'my_jobs' => array(
						'label' => __( 'My Jobs', 'wpjobster' ),
						'url' => get_option( 'wpjobster_my_account_page_id' ),
						'order' => '2a',
					),
					
				),
				'order' => '1a',
			),
			'class_availability' => array(
						'label' => __( 'Class Availability', 'wpjobster' ),
						'url' => "https://www.studythread.com/calendar-feature/?user_id=".$uid,
						'order' => '1a',
			),
			'teachers_video' => array(
						'label' => __( 'Teachers Video', 'wpjobster' ),
						'url' => "/teachers-video/",
						'order' => '2a',
			),
			'calendar' => array(
						'label' => __( 'Calendar', 'wpjobster' ),
						'url' => "/calendar/",
						'order' => '2a',
			),
			'parents_video' => array(
						'label' => __( 'Parents Video', 'wpjobster' ),
						'url' => "/parents-video/",
						'order' => '3a',
					),
			'my_schedule' => array(
				'label' => __( 'My Schedule', 'wpjobster' ),
				'url' => "/my-schedule/?usr_id=".$uid,
				'order' => '4a',
				),
			'sales' => array(
				'label' => __( 'Sales', 'wpjobster' ),
				'url' => get_option( 'wpjobster_my_account_sales_page_id' ),
				'childs' => array(),
				'order' => '4a',
			),
			'shopping' => array(
				'label' => __( 'Shopping', 'wpjobster' ),
				'url' => get_option( 'wpjobster_my_account_shopping_page_id' ),
				'childs' => array(),
				'order' => '5a',
			),
			'requests' => array(
				'label' => __( 'Requests', 'wpjobster' ),
				'url' => '',
				'childs' => array(
					'new_request' => array(
						'label' => __( 'Post a Request', 'wpjobster' ),
						'url' => get_option( 'wpjobster_new_request_page_id' ),
						'order' => '1a',
					),
					'my_request' => array(
						'label' => __( 'My Requests', 'wpjobster' ),
						'url' => get_option( 'wpjobster_my_requests_page_id' ),
						'order' => '2a',
					),
					'all_request' => array(
						'label' => __( 'All Requests', 'wpjobster' ),
						'url' => get_post_type_archive_link( 'request' ),
						'order' => '3a',
					),
				),
				'order' => '6a',
			),
			'my_favorites' => array(
				'label' => __( 'My Favorites', 'wpjobster' ),
				'url' => get_option( 'wpjobster_my_favorites_page_id' ),
				'childs' => array(),
				'order' => '7a',
			),
			'payments' => array(
				'label' => __( 'Payments', 'wpjobster' ),
				'url' => get_option( 'wpjobster_my_account_payments_page_id' ),
				'childs' => array(),
				'order' => '8a',
			),
			'profile' => array(
				'label' => __( 'Profile', 'wpjobster' ),
				'url' => '',
				'childs' => array(
					'my_profile' => array(
						'label' => __( 'My Profile', 'wpjobster' ),
						'url' => get_option( 'wpjobster_user_profile_page_id' ),
						'order' => '1a',
					),
					'edit_profile' => array(
						'label' => __( 'Edit Profile', 'wpjobster' ),
						'url' => get_option( 'wpjobster_my_account_personal_info_page_id' ),
						'order' => '2a',
					),
				),
				'order' => '9a',
			),
			'log_out' => array(
				'label' => __( 'Log out', 'wpjobster' ),
				'url' => wp_logout_url( get_site_URL() ),
				'childs' => array(),
				'order' => '9a',
			),
		);
	  
		$dropdown_user_menu = apply_filters( 'wpjobster_dropdown_menu_list', $dropdown_user_menu );
		return $dropdown_user_menu;
	}
}

// Sort menu
if (!function_exists('wpjobster_sort_menu')) {
	function wpjobster_sort_menu($array, $order){
		if( strtolower($order) == strtolower('asc') ){
			usort($array, function ( $item1, $item2 ) {
				return $item1['order'] < $item2['order'];
			});
		}else{
			usort($array, function ( $item1, $item2 ) {
				return $item1['order'] > $item2['order'];
			});
		}

		return $array;
	}
}

class Add_Class_To_Sub_Menu extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu reset\">\n";
	}
}

