<?php
// Lighter Ajax Process Execution

define( 'DOING_AJAX', true );

// removed define WP_ADMIN true



//make sure we skip most of the loading which we might not need
//http://core.trac.wordpress.org/browser/branches/3.4/wp-settings.php#L99

define( 'SHORTINIT', true );

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );
include_once( "light-ajax-functions.php" );

// require( ABSPATH . WPINC . '/class-wp-walker.php' );
// require( ABSPATH . WPINC . '/class-wp-ajax-response.php' );
require( ABSPATH . WPINC . '/formatting.php' );
require( ABSPATH . WPINC . '/capabilities.php' );
require( ABSPATH . WPINC . '/class-wp-roles.php' );
require( ABSPATH . WPINC . '/class-wp-role.php' );
require( ABSPATH . WPINC . '/class-wp-user.php' );
// require( ABSPATH . WPINC . '/query.php' );
// require( ABSPATH . WPINC . '/date.php' );
// require( ABSPATH . WPINC . '/theme.php' );
// require( ABSPATH . WPINC . '/class-wp-theme.php' );
// require( ABSPATH . WPINC . '/template.php' );
require( ABSPATH . WPINC . '/user.php' );
// require( ABSPATH . WPINC . '/class-wp-user-query.php' );
require( ABSPATH . WPINC . '/session.php' );
require( ABSPATH . WPINC . '/meta.php' );
// require( ABSPATH . WPINC . '/class-wp-meta-query.php' );
// require( ABSPATH . WPINC . '/class-wp-metadata-lazyloader.php' );
// require( ABSPATH . WPINC . '/general-template.php' );
// require( ABSPATH . WPINC . '/link-template.php' );
// require( ABSPATH . WPINC . '/author-template.php' );
// require( ABSPATH . WPINC . '/post.php' );
// require( ABSPATH . WPINC . '/class-walker-page.php' );
// require( ABSPATH . WPINC . '/class-walker-page-dropdown.php' );
// require( ABSPATH . WPINC . '/class-wp-post-type.php' );
// require( ABSPATH . WPINC . '/class-wp-post.php' );
// require( ABSPATH . WPINC . '/post-template.php' );
// require( ABSPATH . WPINC . '/revision.php' );
// require( ABSPATH . WPINC . '/post-formats.php' );
// require( ABSPATH . WPINC . '/post-thumbnail-template.php' );
// require( ABSPATH . WPINC . '/category.php' );
// require( ABSPATH . WPINC . '/class-walker-category.php' );
// require( ABSPATH . WPINC . '/class-walker-category-dropdown.php' );
// require( ABSPATH . WPINC . '/category-template.php' );
// require( ABSPATH . WPINC . '/comment.php' );
// require( ABSPATH . WPINC . '/class-wp-comment.php' );
// require( ABSPATH . WPINC . '/class-wp-comment-query.php' );
// require( ABSPATH . WPINC . '/class-walker-comment.php' );
// require( ABSPATH . WPINC . '/comment-template.php' );
// require( ABSPATH . WPINC . '/rewrite.php' );
// require( ABSPATH . WPINC . '/class-wp-rewrite.php' );
// require( ABSPATH . WPINC . '/feed.php' );
// require( ABSPATH . WPINC . '/bookmark.php' );
// require( ABSPATH . WPINC . '/bookmark-template.php' );
require( ABSPATH . WPINC . '/kses.php' );
// require( ABSPATH . WPINC . '/cron.php' );
// require( ABSPATH . WPINC . '/deprecated.php' );
// require( ABSPATH . WPINC . '/script-loader.php' );
// require( ABSPATH . WPINC . '/taxonomy.php' );
// require( ABSPATH . WPINC . '/class-wp-term.php' );
// require( ABSPATH . WPINC . '/class-wp-term-query.php' );
// require( ABSPATH . WPINC . '/class-wp-tax-query.php' );
// require( ABSPATH . WPINC . '/update.php' );
// require( ABSPATH . WPINC . '/canonical.php' );
// require( ABSPATH . WPINC . '/shortcodes.php' );
// require( ABSPATH . WPINC . '/embed.php' );
// require( ABSPATH . WPINC . '/class-wp-embed.php' );
// require( ABSPATH . WPINC . '/class-wp-oembed-controller.php' );
// require( ABSPATH . WPINC . '/media.php' );
// require( ABSPATH . WPINC . '/http.php' );
// require( ABSPATH . WPINC . '/class-http.php' );
// require( ABSPATH . WPINC . '/class-wp-http-streams.php' );
// require( ABSPATH . WPINC . '/class-wp-http-curl.php' );
// require( ABSPATH . WPINC . '/class-wp-http-proxy.php' );
// require( ABSPATH . WPINC . '/class-wp-http-cookie.php' );
// require( ABSPATH . WPINC . '/class-wp-http-encoding.php' );
// require( ABSPATH . WPINC . '/class-wp-http-response.php' );
// require( ABSPATH . WPINC . '/class-wp-http-requests-response.php' );
// require( ABSPATH . WPINC . '/widgets.php' );
// require( ABSPATH . WPINC . '/class-wp-widget.php' );
// require( ABSPATH . WPINC . '/class-wp-widget-factory.php' );
// require( ABSPATH . WPINC . '/nav-menu.php' );
// require( ABSPATH . WPINC . '/nav-menu-template.php' );
// require( ABSPATH . WPINC . '/admin-bar.php' );
require( ABSPATH . WPINC . '/rest-api.php' );
// require( ABSPATH . WPINC . '/rest-api/class-wp-rest-server.php' );
// require( ABSPATH . WPINC . '/rest-api/class-wp-rest-response.php' );
// require( ABSPATH . WPINC . '/rest-api/class-wp-rest-request.php' );

// Define constants that rely on the API to obtain the default value.
// Define must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
wp_plugin_directory_constants();

if ( is_multisite() )
	ms_cookie_constants(  );

// Define constants after multisite is loaded.
wp_cookie_constants();

// Define and enforce our SSL constants
wp_ssl_constants();

// Create common globals.
require( ABSPATH . WPINC . '/vars.php' );

// Load pluggable functions.
require( ABSPATH . WPINC . '/pluggable.php' ); // is_user_logged_in



// removed send_origin_headers()

// Require an action parameter
if ( empty( $_REQUEST['action'] ) ) {
	die( '0' );
}

// removed require_once admin.php api
// removed require_once ajax-actions.php core ajax handlers

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
nocache_headers();

// removed do_action admin_init
// removed add_action register core ajax calls
// removed add_action heartbeat

if ( is_user_logged_in() ) {
	do_action( 'light_ajax_' . $_REQUEST['action'] );
} else {
	do_action( 'light_ajax_nopriv_' . $_REQUEST['action'] );
}

// Default status
die( '0' );
