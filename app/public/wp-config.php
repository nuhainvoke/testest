<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost:/Users/nuhainvoke/Library/Application Support/Local/run/5tWryuJ2W/mysql/mysqld.sock' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '/;j,oS(-;H+a|r3*LbkElfN9ZP-Ph85lm` 90^rTMYTx2}s.&UdUIfM,:x?^<s%]' );
define( 'SECURE_AUTH_KEY',   '7nkO8grRwT}XJ,xy.NCZdou% _gm;1E3a`p&:;YY6X%nBA+-- !mcZE8qJ9Kr5cH' );
define( 'LOGGED_IN_KEY',     'oWO%4=|qMG={o2KOYtd!1OoH+Ua>QbIlz,)^,n7E8j]XMZn&u;-c1KoV:!ggc]AI' );
define( 'NONCE_KEY',         '8hZVVbin@8o_V=Xc>ruD;+K)lVrw[f/COBdE{bAEopLG[~-c/9jNp0d^:AlOyR<U' );
define( 'AUTH_SALT',         '7.S %X7X/&VKt<0RV6&%M_wnpn1 ah,{&WEm9*hjvtd{0Me3hV eSc>h{F[pRrG/' );
define( 'SECURE_AUTH_SALT',  '?qj])MBpIw2?K^A3~I-Y9(_z3<GSGB2v&dy4YlZP/GqgRe:MtbIC%+P|/[XT|iEe' );
define( 'LOGGED_IN_SALT',    'cIPIC8wpdyYdo7RGK|tgS]l8#$(_MRDrgvZk8Oh3HeX0Eb#,Gc2r5mFH[<KGk VM' );
define( 'NONCE_SALT',        '2yr/P0/Tx/%)VZomfe?%JK1i<}/z& S!C=nE>#KU~!:RlW7=DoBkEnw=4RasGk=s' );
define( 'WP_CACHE_KEY_SALT', 'y:Z` 1lu Y6nNT.ut?55T(Olg99@AEO_76v/g$B,/b5a0FY9 E#B#HJ%K&g}K~M=' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
