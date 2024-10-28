<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '5|dVZ:LAZsObaWj/G|=K]ty4!SIqu1K<NP}DpZqXu+hG$00T[!{ DiFL%.OHt[s$' );
define( 'SECURE_AUTH_KEY',  'R?JP*d&$)sB(1So_&IO+k[e$>69BqEj:h{c9/*Gly|_W^LHZ;T(NLage.&}z4BGU' );
define( 'LOGGED_IN_KEY',    'rT9LLTtT1@ud^y{Ffd>L#nUJ>zAYn|ki?6!rxPntS1{0_xUtevwoSH#).b4)I-7d' );
define( 'NONCE_KEY',        'o0jF.a; ,qL!o`xY)}%lfyva~WG*,(?kLNyxgWw^%mRVCiqu7TX%d)c 2nlr:|3#' );
define( 'AUTH_SALT',        '. f>8n]npASj_yhB.u/WwXHfNhM^ppK1Mco=4pLfF$A_|2uD7`xOL@=s2?,$^FwL' );
define( 'SECURE_AUTH_SALT', '1=^K8iq%9FoofsJ8~J3%4%gD78s$UcrQ|,&JEQ-I#Q->^97dFs&OKbe>J:pom:M:' );
define( 'LOGGED_IN_SALT',   'zu,TK hlak>28Og}9Y]@EX&0`(%fRb>fv-C#3so4GR+2r)f_5]4G`TNWD_8j.=dY' );
define( 'NONCE_SALT',       'z7$C1DT`>>*9l<XfoPi}rrwV]kP2xVAg%2i0z8U/r0tu5$g9H%@KiZlw:ozX8?iI' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
