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
define( 'DB_NAME', 'database_name_here' );

/** Database username */
define( 'DB_USER', 'username_here' );

/** Database password */
define( 'DB_PASSWORD', 'password_here' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);
define('MYSQL_SSL_CA', ABSPATH . 'wp-content/aiven-ca.pem');

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
define('AUTH_KEY',         'GE!%Hb7Xp!5b}v1XBKe%aw++d4N}]rDxyrIwvZF]PLv>&yw~0?(AkYFDPDoRiim&');
define('SECURE_AUTH_KEY',  'r|>a$9i7W_l+/ZAqN5-1|5w6I}/Y8m:~-< =3rR#4~.}`A iwbeivP%l/2LJ46:H');
define('LOGGED_IN_KEY',    ':{/E5vb-8)9&.:x|;XIx{(:K[V$lB4FE+kJ+(8A{Pi.t{-:q[`M%#<p&Mz--Wq/z');
define('NONCE_KEY',        'IeR|^M;X+]{:&gNNv; g78+ +&bME)*gQWWlf;%j-6+CgS&y=<)#oo-sqJACgt)n');
define('AUTH_SALT',        ':da9-j++,$E(t<EB|*}8(*l0`eL;y=&oJ9O,_paDMawLyk$tYzNT;c8I(.Lx pAU');
define('SECURE_AUTH_SALT', 'pZ7|Btb}v-)FG(0k8MEK[X8zEOaQEn%oY#5n8}|@|x7tf3IN.lZ-%]*K)/kutQGI');
define('LOGGED_IN_SALT',   '/XwtiRZ3aB}m8PZ8U!Pw$>xN5%)|(${RwPcCBGla?S(_5X|~j,+8drLIgt66;(,;');
define('NONCE_SALT',       '@A3ai6XiAHGU@TU!<NyXJH #Cd#oz}sZc%cv]{X4q1emU5:A>V9-qLP{b)Z*d%I1');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
