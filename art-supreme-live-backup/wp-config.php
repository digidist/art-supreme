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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', '7592292ad4a21854de88ccb231616db4f0f328c9fee560a1' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'dS%mvw!]_:WhpZ1[k+JIBF~Rh1f+qe7>i2Y<5(6bTu_5kJ46X/!?%hQl)%~KwUP;' );
define( 'SECURE_AUTH_KEY',  '0g?=SUkY=(J=y(Qw7ZdTQpV|QpHIC>,-j/AgQ-vz4+@:]x{hTS)54NGw2PRH/j6m' );
define( 'LOGGED_IN_KEY',    '-O^-lD<B94?[H3B|kTsec5UPzcjY%l1~pH(]#maYk$0QglxPo$Z&SU>Ny)who/=!' );
define( 'NONCE_KEY',        ':~mhjVlZIsM6cOcf=2?rl/7luLoj(6ljt_~Nc,3pD21:Hfv!w~l/bt-;ZC[T4.8s' );
define( 'AUTH_SALT',        'Qtj]`.z]sZa_PUS[mnW>,U~//Z/SJj{~E*2$?cN&c#p8 0C<bJg$:GW^np8-5uP2' );
define( 'SECURE_AUTH_SALT', 'Q_x|5E3,wV O-=p/*3]7o0>gV+p<vlzX-|@M_H3Sf? ~qZ:Ct-/{aM,q]:;#KIMO' );
define( 'LOGGED_IN_SALT',   ')siRn[#tWC|3AZ=O:PJz5S*7/5^t$Wtbi5@]AUmt{r~XkSu4[%n`9_{{,(z+(~T(' );
define( 'NONCE_SALT',       'HN#TD/JwK/^@dj[$Ck,!*{7^{7?Hk5E^urbIxb7kMVzP*fVP1I$@:f//VgkoTeO!' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'dc0_';

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
// Enable WP_DEBUG mode
define('WP_DEBUG', false);

// Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
