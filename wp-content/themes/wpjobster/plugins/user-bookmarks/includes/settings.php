<?php

// this file contains all settings pages and options

function upb_settings_page()
{
	global $upb_options;
		
	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">
			<h2>User Bookmarks Settings</h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'upb_settings_group' ); ?>
				
				<h4>Add / Remove Links</h4>
				<p>
					<input id="upb_settings[show_links]" name="upb_settings[show_links]" type="checkbox" value="1" <?php checked( true, isset( $upb_options['show_links'] ) ); ?>/>
					<label class="description" for="upb_settings[show_links]"><?php _e( 'Display the Add / Remove Bookmark links on posts/pages automatically?' ); ?></label>
				</p>
				<p>
					<?php $positions = array('Top', 'Bottom'); ?>
                    <select name="upb_settings[post_position]">
						<?php foreach ($positions as $option) { ?>
							<option <?php if ($upb_options['post_position'] == $option ){ echo 'selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
						<?php } ?>
					</select>					
					<label class="description" for="upb_settings[style]"><?php _e( 'Display Add / Remove Bookmark links at top or bottom of the post/page content?' ); ?></label>
				</p>				
				<p>
					<input id="upb_settings[add_link_text]" name="upb_settings[add_link_text]" type="text" value="<?php echo $upb_options['add_link_text'];?>" />
					<label class="description" for="upb_settings[add_link_text]"><?php _e( 'Enter the text you\'d like to use for the Add Bookmark text' ); ?></label><br/>					
				</p>
				<p>
					<input id="upb_settings[del_link_text]" name="upb_settings[del_link_text]" type="text" value="<?php echo $upb_options['del_link_text'];?>" />
					<label class="description" for="upb_settings[del_link_text]"><?php _e( 'Enter the text you\'d like to use for the Remove Bookmark text' ); ?></label><br/>
					<div>Note, the above two options only apply when automatically displaying the Add / Remove Bookmark links</div>
				</p>
				
				<h4>Alert Messages</h4>
				<p>
					<input id="upb_settings[bookmark_added_text]" name="upb_settings[bookmark_added_text]" type="text" value="<?php echo $upb_options['bookmark_added_text'];?>" />
					<label class="description" for="upb_settings[bookmark_added_text]"><?php _e( 'Enter the text you\'d like to be shown in the alert when a bookmark is added' ); ?></label><br/>					
				</p>
				<p>
					<input id="upb_settings[remove_bookmark_text]" name="upb_settings[remove_bookmark_text]" type="text" value="<?php echo $upb_options['remove_bookmark_text'];?>" />
					<label class="description" for="upb_settings[remove_bookmark_text]"><?php _e( 'Enter the text you\'d like to be shown in the alert when a bookmark is deleted' ); ?></label><br/>
				</p>
				
				<h4>Custom CSS</h4>
				<p>
					<label class="description" for="upb_settings[bookmarks_css]"><?php _e( 'Enter custom CSS here to customize the appearance of this plugin. To assist you, a list of available class names are available in the Help tab in the top right.' ); ?></label><br/>					
					<textarea id="upb_settings[bookmarks_css]" style="width: 400px; height: 150px;" name="upb_settings[bookmarks_css]" type="text"><?php echo $upb_options['bookmarks_css'];?></textarea>
				</p>

				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
				</p>
								
				
			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
		
	<?php
}

// register the plugin settings
function upb_register_settings() {

	// create whitelist of options
	register_setting( 'upb_settings_group', 'upb_settings' );
}
//call register settings function
add_action( 'admin_init', 'upb_register_settings' );


function upb_settings_menu() {

	// add settings page
	add_submenu_page('options-general.php', 'Bookmarks Settings', 'Bookmarks Settings','manage_options', 'bookmarks-settings', 'upb_settings_page');
}
add_action('admin_menu', 'upb_settings_menu');


function upb_contextual_help($contextual_help, $screen_id, $screen) {
 
	ob_start(); ?>
 
	<h3>HTML Class Names</h3>
	<p>The selectors below can be used in your CSS to customize the look of your bookmark links, the add / remove bookmark links, and more.</p>
	<p><strong>User Bookmark List Selectors</strong></p>
	<ul>
		<li><em>ul.upb-bookmarks-list</em> - this is the unordered list wrapper that contains a user's bookmark links</li>
		<li><em>li.upb_bookmark</em> - this wraps each bookmark link</li>
		<li><em>a.upb_bookmark_link</em> - this is the bookmark's anchor link</li>
		<li><em>a.upb_del_bookmark</em> - this is the delete link next to each bookmark in the user's list</li>
	</ul>
	
	<p><strong>Add / Remove Bookmark Controls</strong></p>
	<ul>
		<li><em>div.upb_add_remove_links</em> - this is the DIV tag that wraps the add / remove links, if enabled</li>
		<li><em>a.upb_bookmark_control</em> - this is the generic class given to both add and remove bookmark links</li>
		<li><em>a.upb_del_bookmark</em> - this is the remove bookmark link class</li>
		<li><em>a.upb_add_bookmark</em> - this is the add bookmark link class</li>
	</ul>
	
	<p><strong>Most Popular Bookmarks List</strong></p>
	<ul>
		<li><em>ul.upb_most_popular_bookmarks</em> - this is the UL that wraps the most popular bookmarks list</li>
		<li><em>li.popular_bookmark</em> - this is LI tag that wraps each popular bookmark link</li>
		<li><em>a.popular_bookmark_link</em> - this is anchor tag for each popular bookmark link</li>
	</ul>
 
	<?php
	return ob_get_clean();
}
if (isset($_GET['page']) && $_GET['page'] == 'bookmarks-settings')
{
	add_action('contextual_help', 'upb_contextual_help', 10, 3);
}