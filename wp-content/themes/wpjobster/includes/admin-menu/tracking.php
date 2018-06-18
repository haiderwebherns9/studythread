<?php

class Tracking {

	function wpjobster_tracking_tools() {

		$id_icon      = 'icon-options-general-track';
		$ttl_of_stuff = 'Jobster - '.__('Tracking','wpjobster');

		global $menu_admin_wpjobster_theme_bull;

		echo '<div class="wrap">';
			echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
			echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

			if(isset($_POST['wpjobster_save1']))
			{
				update_option('wpjobster_enable_google_analytics', trim($_POST['wpjobster_enable_google_analytics']));
				update_option('wpjobster_analytics_code',          trim($_POST['wpjobster_analytics_code']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			if(isset($_POST['wpjobster_save2']))
			{
				update_option('wpjobster_enable_other_tracking', trim($_POST['wpjobster_enable_other_tracking']));
				update_option('wpjobster_other_tracking_code',   trim($_POST['wpjobster_other_tracking_code']));

				echo '<div class="updated fade"><p>'.__('Settings saved!','wpjobster').'</p></div>';
			}

			wpj_tracking_html();

		echo '</div>';

	}

}

$tracking = new Tracking();
