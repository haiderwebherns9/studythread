<?php
/*
Plugin Name: User Bookmarks
Plugin URI: http://cgcookie.com
Description: Allows users to bookmark their favorite posts, pages, and custom post types.
Version: 1.0.8
Author: CG Cookie, INC
Contributors: Pippin Williamson
Author URI: http://cgcookie.com
*/
$plugin_dir_url = get_template_directory_uri().'/plugins/user-bookmarks/';
// plugin folder url
if(!defined('UPB_PLUGIN_URL')) {
	define('UPB_PLUGIN_URL', $plugin_dir_url);
}
// plugin folder path
if(!defined('UPB_PLUGIN_DIR')) {
	define('UPB_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
}
// plugin root file
if(!defined('UPB_PLUGIN_FILE')) {
	define('UPB_PLUGIN_FILE', __FILE__);
}

// globals
global $wpdb;

global $upb_options;
$upb_options = get_option('upb_settings');

// bookmark count table
global $upb_db_table;
$upb_db_table = $wpdb->prefix . "upb_bookmark_counts";

// bookmark count table version
global $upb_table_version;
$upb_table_version = 1.0;

include(UPB_PLUGIN_DIR . '/includes/display-functions.php');
include(UPB_PLUGIN_DIR . '/includes/scripts.php');
include(UPB_PLUGIN_DIR . '/includes/bookmark-functions.php');
include(UPB_PLUGIN_DIR . '/includes/shortcodes.php');
include(UPB_PLUGIN_DIR . '/includes/settings.php');
include(UPB_PLUGIN_DIR . '/includes/dashboard-widgets.php');


function upb_install()
{
	$table_exist = get_option( 'upb_database_version', true );
	if( $table_exist && $table_exist != '' ){
		global $wpdb;
		global $upb_db_table;
		global $upb_table_version;

		if($wpdb->get_var("show tables like '$upb_db_table'") != $upb_db_table)
		{
			$sql = "CREATE TABLE " . $upb_db_table . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			count mediumint NOT NULL,
			UNIQUE KEY id (id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			add_option("upb_database_version", $upb_table_version);
		}
	}
}
add_action( 'init', 'upb_install' );


/**
 * List User Bookmarks Widget
 */
class upb_bookmarks_list_widget extends WP_Widget {


	/** constructor */
	function upb_bookmarks_list_widget() {
		parent::WP_Widget(false, $name = 'List User Bookmarks', array('description' => 'List the current user\'s bookmarks'));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );

		$title  = apply_filters('widget_title', $instance['title']);
		$delete = apply_filters('widget_title', $instance['delete']);
		$delete_text = apply_filters('widget_title', $instance['delete_text']);

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		if($delete_text == '') { $delete_text = 'Delete'; }
		echo upb_list_bookmarks($delete, $delete_text);
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['delete'] = strip_tags($new_instance['delete']);
		$instance['delete_text'] = strip_tags($new_instance['delete_text']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {

		$title = esc_attr($instance['title']);
		$delete = esc_attr($instance['delete']);
		$delete_text = esc_attr($instance['delete_text']);

		?>
		<p>
		  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		  <input id="<?php echo $this->get_field_id('delete'); ?>" name="<?php echo $this->get_field_name('delete'); ?>" type="checkbox" value="1" <?php checked( '1', $delete ); ?>/>
		  <label for="<?php echo $this->get_field_id('delete'); ?>"><?php _e('Display Delete link?'); ?></label>
		</p>
		<p>
		  <label for="<?php echo $this->get_field_id('delete_text'); ?>"><?php _e('Delete Text:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('delete_text'); ?>" name="<?php echo $this->get_field_name('delete_text'); ?>" type="text" value="<?php echo $delete_text; ?>" />
		</p>
		<?php
	}


} // class upb_bookmarks_list_widget

/**
 * Show Add / Remove Links Widget
 */
class upb_bookmarks_links extends WP_Widget {


	/** constructor */
	function upb_bookmarks_links() {
		parent::WP_Widget(false, $name = 'Bookmark Post Widget', array('description' => 'Show Add/Remove Bookmark links'));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );

		if(is_singular() && is_user_logged_in()) {
			$title  = apply_filters('widget_title', $instance['title']);
			$add_text = apply_filters('widget_title', $instance['add_text']);
			$delete_text = apply_filters('widget_title', $instance['delete_text']);

			echo $before_widget;
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			if($add_text == '') { $add_text = 'Bookmark this Post'; }
			if($delete_text == '') { $delete_text = 'Remove Bookmark'; }
			echo upb_bookmark_controls($add_text, $delete_text);
			echo $after_widget;
		}
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['add_text'] = strip_tags($new_instance['add_text']);
		$instance['delete_text'] = strip_tags($new_instance['delete_text']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {

		$title = esc_attr($instance['title']);
		$add_text = esc_attr($instance['add_text']);
		$delete_text = esc_attr($instance['delete_text']);

		?>
		<p>
		  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		  <label for="<?php echo $this->get_field_id('add_text'); ?>"><?php _e('Bookmark Post Text:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('add_text'); ?>" name="<?php echo $this->get_field_name('add_text'); ?>" type="text" value="<?php echo $add_text; ?>" />
		</p>
		<p>
		  <label for="<?php echo $this->get_field_id('delete_text'); ?>"><?php _e('Delete Text:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('delete_text'); ?>" name="<?php echo $this->get_field_name('delete_text'); ?>" type="text" value="<?php echo $delete_text; ?>" />
		</p>
		<?php
	}


} // class upb_bookmarks_links


/**
 * Show Add / Remove Links Widget
 */
class upb_most_popular extends WP_Widget {


	/** constructor */
	function upb_most_popular() {
		parent::WP_Widget(false, $name = 'Most Bookmarked', array('description' => 'Show your most bookmarked items'));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );

		$title  = apply_filters('widget_title', $instance['title']);
		$number = (int)$instance['number'];
		if($number == '' || !isset($number)) { $number = 5; }
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		echo upb_most_bookmarked($number);
		echo $after_widget;

	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {

		$title = esc_attr($instance['title']);
		$number = esc_attr($instance['number']);

		?>
		<p>
		  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		  <input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
		  <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number to show:'); ?></label>
		</p>
		<?php
	}


} // class upb_most_popular

function upb_register_widgets() {
	register_widget('upb_bookmarks_list_widget');
	register_widget('upb_bookmarks_links');
	register_widget('upb_most_popular');
}
add_action('widgets_init', 'upb_register_widgets');
