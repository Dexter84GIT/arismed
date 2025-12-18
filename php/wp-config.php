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
define( 'DB_NAME', 'u3355977_arismed' );

/** Database username */
define( 'DB_USER', 'u3355977_arismed' );

/** Database password */
define( 'DB_PASSWORD', 'p(dS)4c4s9' );

/** Database hostname */
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
define( 'AUTH_KEY',         'aku0n8n22mhw6atqmtar3pxcbhmowztbhmpgsqdxyvbgrk0c7ndzjncemtaj5ruw' );
define( 'SECURE_AUTH_KEY',  'm6uprnoa78a6mvlccjnw8gxgnaylsatjytxrzdpr7k11fsfrdveoykeelihy4355' );
define( 'LOGGED_IN_KEY',    'oxszpssqhe9kdgry0egajocpzbckxplvj6pdhxzxksfth7tnkxuozwhpjc2csuxo' );
define( 'NONCE_KEY',        'd42jtilzjzh3uvbfqi0pwolpjkkuqotsn25jpcjowac5tyk5cb61rnmgkt0o4wl4' );
define( 'AUTH_SALT',        '1jgxseulp5irl6qmo4ntdnr1exjxwzakox9yjwhxl6hdswvehtueucng5fs5i462' );
define( 'SECURE_AUTH_SALT', 'c3yb7fsofyggnwhvvjbustn038ist7l0zgznpjuhphg864jmgllekzbqcvoww3ut' );
define( 'LOGGED_IN_SALT',   'zpncxsuixclxdlz3yzlvtgph84fxlpwgivmjatwdxiwqwu86pkrvniptzfcxxbwq' );
define( 'NONCE_SALT',       'pmxstcr1hg76luvjc4zm7jfnrnydhobezcorqq3qfnmuj9neuir3v8v3v61b8cnx' );

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
$table_prefix = 'am_';

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
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
define('WP_CACHE', false);
define('DONOTCACHEPAGE', true);
define('DONOTCACHEDB', true);
define('DONOTMINIFY', true);
define('DONOTCDN', true);
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
