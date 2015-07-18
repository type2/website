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
define('DB_NAME', 'site');

/** MySQL database username */
define('DB_USER', 'site');

/** MySQL database password */
define('DB_PASSWORD', '!site1');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'aZP]djIDN-gH[GG#QK5K1uuZU++0.i/EW|`t{P8H4dAs&BC`d,7BUmmGj:.jpUE1');
define('SECURE_AUTH_KEY',  '@(*Ku#KNx7YYI9)T?o9Q7=VnWGB%&q~Ngv@Y/+_vc-v.o0O{{^S%LjOgXHvW_*DX');
define('LOGGED_IN_KEY',    'K9gu5|!D$.dv-SiQL@?8Q0yR]N$|$8);U@@o?.l%SME`J11ziNH0w)MOuUwZhwu$');
define('NONCE_KEY',        '4|5xbyXW/_$>BIsT)orERT@N*E_WLT[s4E#]FOrp::@u}uB _W<Ics5YuxY[5C<Z');
define('AUTH_SALT',        '@*}I%=DY2!W`HuFOV}9zU0GB v-2X-q;CHFBa{E+(*+bp-}!CS4u(!y+TN`Cg[^v');
define('SECURE_AUTH_SALT', '2j5<=+K)m&CD4Or9ovBl<hFSquBQ>$kHg|i<YCOU}[h;QWI^m]St-hlyB*:Rp1H-');
define('LOGGED_IN_SALT',   'L`NEf!mYIJjH9km7Tb>Xfn6|3dtP,)Mv,<2OYnG:/c[,Xm>SC|<&xA[lgaLD7f61');
define('NONCE_SALT',       'G(-YR#pYTSBcD9i-U|C32!%f)|H, +&y).D+&^!RFHGKbHKHVCnyybP]m#kx$`BC');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

