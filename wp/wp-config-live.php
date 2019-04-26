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
define('DB_NAME', 'db203502_prototype');

/** MySQL database username */
define('DB_USER', 'db131208_func');

/** MySQL database password */
define('DB_PASSWORD', 'p2btta30n');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s131208.gridserver.com');

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
define('AUTH_KEY',         '|)3n(VD&(8H!U*Ub2@QTDVu{x6TL=L?Jn:6,A,Dm|(q(-Y`U-bNR(fUTD>h01Exr');
define('SECURE_AUTH_KEY',  ')Uv_TlxD|Ob+P40fqX$MH3D|q[DjCM1ypx9]#xi KJI{)4U<^9Yio@8{-[3aD/K#');
define('LOGGED_IN_KEY',    'OjGN*dR4gB:zwf]zObq=g!E*<!mektw6:AtEQi-6ZXR7|4*#Io>eY}YKCQObi!rU');
define('NONCE_KEY',        'qZ%Y+<joWPqS=U.-aJCgpzBIcWW+|@C]BW(=/;|Qn`L@Ef%*+m,0GQ#[x Z?lka{');
define('AUTH_SALT',        'Va:pQT#ftbU UW(3C%lH$#?;|[:B-W!Ub|=k4Kh(h2I/>uNk0<#*z-V@%?N7{(eQ');
define('SECURE_AUTH_SALT', '!Igq1}mX$LL?Us5z+&04k!+_8!YhEC{YYkz|!c+y1fCY3-?0UMdPHh+4__au[,&n');
define('LOGGED_IN_SALT',   ' $do]52yS5._(0R[S6af:D>95wR&2#tW.S33>pqOVULzr*YWHwShIp(KhgOT+{+@');
define('NONCE_SALT',       'Ug!UsADaRX22@5^%|PK]^W-h*ddh&.imL-8%|KiaY]rxr$H?89<uq#/0P^!sTF+n');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'efhbokhh71_';

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

define( 'AUTOMATIC_UPDATER_DISABLED', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
