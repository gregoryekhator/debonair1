<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dbyjgfgmwpi3cs');

/** MySQL database username */
define('DB_USER', 'upnxxdvqzbtrf');

/** MySQL database password */
define('DB_PASSWORD', 'taqzbobngawg');

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
define('AUTH_KEY',         'Ly7eCwBOyumcXusdfp1ohEsAlVYkcQgWJTjRz8pykZ7xK7k2PK6u1eVxvZhKuyDC');
define('SECURE_AUTH_KEY',  'fklpmVYGVTkquLoHK8983a0kIhLzXi6QvRMDxlY4SYIfjoH9GpxGzb2GGQkIzQBz');
define('LOGGED_IN_KEY',    '0QOuP3Wos9xZEevbO2V5jrz96XZ2U1QlqluTBQnzLc6Cb3xgYa1RcPKENSfMxyxH');
define('NONCE_KEY',        'WBMn84dxA22Ny8bhfZdPL5Rf2zOeWq6Nc7X0VrRv5SBisRC3b7tV8Y77EjkNVm99');
define('AUTH_SALT',        '2efYhXkGiTY5y7xEFjV6iZMEoGf9Joiz4B65798znQ28LxRSvwkrFjmDJWZ94ig4');
define('SECURE_AUTH_SALT', 's81BW36B5SnwKhI2rb7ZOhGZr6rHZFT7jLKtSjmN3a0vRPkGoi8a9b1RrLu2nWoN');
define('LOGGED_IN_SALT',   'OD7jDm894nNdUnoEtyjQdTCVNDj6yMYZqqD1bzhaECFzyMS4ly43kuW668UtxNYJ');
define('NONCE_SALT',       'k4hC8nakMYARJGHKSjGlYuq8WWSX5OZwfwANG37DxD7cfcWZ7j91D2eF1tVhfJLF');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
