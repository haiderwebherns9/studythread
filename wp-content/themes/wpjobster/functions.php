<?php
/**
* Jobster functions and definitions
*
* @author WPJobster <support@wpjobster.com>
* @copyright Copyright (c) 2017 WPJobster. All rights reserved.
*
* @link http://wpjobster.com/
* @link http://wpjobster.com/terms-of-service/
* @link http://wpjobster.com/privacy-policy/
*
* @package WPJobster
* @subpackage Jobster
* @since Jobster v1.0.0
*/

if(!isset($_SESSION)) { session_start(); }

DEFINE( "wpjobster_VERSION", "5.1.0" );
DEFINE( "wpjobster_RELEASE", "15 Feb 2018" );

require get_template_directory() . '/includes/init.php';
wpj_globals();

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
