<?php

class LayoutSettings {
	function wpjobster_layout_settings() {
		$id_icon      = 'icon-options-general-layout';
		$ttl_of_stuff = 'Jobster - '.__('Layout Settings','wpjobster');
		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
		echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
		echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';


		if(isset($_POST['wpjobster_save1'])){
			update_option('wpjobster_enable_responsive', trim($_POST['wpjobster_enable_responsive']));
			update_option('wpjobster_enable_lazy_loading', trim($_POST['wpjobster_enable_lazy_loading']));
			echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
		}

		if(isset($_POST['wpjobster_saveother'])){
			update_option('wpjobster_video_thumbnails', trim($_POST['wpjobster_video_thumbnails']));
			echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
		}

		if(isset($_POST['wpjobster_save2'])){
			update_option('wpjobster_custom_css_code', trim($_POST['wpjobster_custom_css_code']));
			echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
		}

		$wpjobster_home_page_layout = get_option('wpjobster_home_page_layout');
		if(empty($wpjobster_home_page_layout)) $wpjobster_home_page_layout = "1";

		wpj_layout_html();
	}
}

$ls = new LayoutSettings();
