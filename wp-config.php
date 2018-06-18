<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'studythread_com');

/** MySQL database username */
define('DB_USER', 'studythreadcom');

/** MySQL database password */
define('DB_PASSWORD', 'DRN^JSMn');

/** MySQL hostname */
define('DB_HOST', 'mysql.studythread.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'xJ1J;/T+&@21f1N7^J8@1#)S*xq6o:GSn^Y`$%QO#wYVt$Y:bJ%zyi"6FFQ&#mZv');
define('SECURE_AUTH_KEY',  '*(6nNzo)n&Uz6JP3FO4B6y&h)X450JV#w|mZe:kfDG?BKbJ3#0lQ#Js0Yv&4ps7d');
define('LOGGED_IN_KEY',    ':g_WHU_LF@z4D+?:2?)MhRs6aH*$%la%;uSD$"T)MPfJejEc?t3fQcmcG`b8fZCt');
define('NONCE_KEY',        '1~8?LjY3/`dG:B?T4ztm`"u56NKH#Ua;kPabYHHrm3dUTP?yTyfkUeGZ1~9AquU0');
define('AUTH_SALT',        '71zk;zUYN_xe5K_nhup/U4Cr7JA;B9DT!OgQy"Bqi~m#us;|LjF2JJ3^9Gy:E|e:');
define('SECURE_AUTH_SALT', '+N:q(Fl+9nwCH7xEvC+^H!)1zKl@8v(msk01s9IPR:Q76A3g2|QkcjNVPDyO:q;B');
define('LOGGED_IN_SALT',   'XohkvBv^wTf;wlQ+E3DaLRs~zWg?"lYDjPRyX~A3n_)le|E%G29XTHwkV$sbce%F');
define('NONCE_SALT',       'H+bj%!$?V$faj2Csrw@uhh3xILBgzU(~KC4d;NU%Y2fVp&ni94Lzs9cka$$l@3m#');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_87fsrr_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

