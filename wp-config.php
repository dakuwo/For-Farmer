<?php
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8x1j8pcfO4CXIprt3xziVi3pQUnoy82TUCn3islN/z60NU9fv2V1YBQPs1TuhuqGntfmXDRCiMJ5J+UFWfBglQ==');
define('SECURE_AUTH_KEY',  'ms6awGgfcPmQKM3gNwo5RKl42Nsp+sBmtU4k4rLCNGs7z6HUI4ZZdqt7YL1wfPyJLWQFaYQX2GpXGiogZNktbQ==');
define('LOGGED_IN_KEY',    'B0fElavOXXm8uMBd7t1G1rmmLwr3hOzaybzNMT01YsWR8tynMnO6xSjvL4g61FhG0iIk571Y8kbyIdSTxQpgZw==');
define('NONCE_KEY',        'bvJyWT2NiN1QJMu9b6A1HdWvNiPRiw3hoIUeTXOs6hh5KFvXJUS1u/hAjuW6d+02ACJ5TofCp4nqOq5PM1+sOg==');
define('AUTH_SALT',        'YRjNZHzxZkAhrehByhpUWsbsSrykPZnFHk94EQdECy0ht/h336NA1SyP+IPWh7XNl4neZJhd+cKBnjZmruVrxw==');
define('SECURE_AUTH_SALT', '8NCuPMkMNSZOH+4QbaeXC3By0B/CX5V+D8kB+jRpFj96hMjeOcvxm4EfFI8EKro9IJjbnYz4T7WYMdi/8bt70A==');
define('LOGGED_IN_SALT',   'hxheN4f6S/a/swBBHn+uVF5Xd9kQOjRvADHH/+FSHHjrfhVDzVjPhAtjdhK396MWYUXULApe+8XxO2+b96RTRg==');
define('NONCE_SALT',       'YC3d0KV+bYtB4tcwNBJlrmc9lhqCBhs8MfgVJ69ixZsVGoZko9GWcUJBoxVeMc46SxqPCTIkFPZgmk5fuiueiQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
