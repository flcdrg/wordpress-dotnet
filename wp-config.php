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
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );

/** Database username */
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );

/** Database password */
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );

/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/1356f6537220ffdc32b9dad2a6cdbe2d010b7a88/wp-admin/setup-config.php#L224-L238
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

/** Database hostname */
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8') );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );

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
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         '3e95a1e5bcb0bea512b319ce66ed7949e28e61e6') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  '054455a1b86bfaa9bcba3e148b81768462f7dfa0') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    'a268e1e89f8306a4e96a9155cd04e0913927d1b4') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        '0d159ff8f8db17065e7b459d0250c30cb6fc48d3') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        '4a192ec03fee7708224a01c9c57dd4be6850492c') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'f3f789288a734e0cf536f392359358501ab18a4d') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   '22aa3a5695878d273aff0107ce2df7d8e969c8ee') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       '5ce3ffb12655d1fe840a4133f1ee367dee1cddb6') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
//define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

define('WP_DEBUG', true);
if (WP_DEBUG) {
		@error_reporting(E_ALL);
		@ini_set('log_errors', true);
		@ini_set('log_errors_max_len', '0');
		define('WP_DEBUG_LOG', true);
		define('WP_DEBUG_DISPLAY', false);
		define('CONCATENATE_SCRIPTS', false);
		define('SAVEQUERIES', true);
}

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}


define( 'OIDC_PUBLIC_KEY', <<<OIDC_PUBLIC_KEY
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAuWR3HCW2gEBwV+jWRdAZ
+2tq+bNcwRJ9hzeI7FxIcqoFO19O1kSkub4p8pkVTYCMRue7naO9H3MOOzVMhgLL
OC8oXETXXg/Bz1q58YoIqumEj68t5c0KxqT9Ls4wqaHSevuJZTCLBh00PpL+kKCH
Pz5XyLSWTXZfL0W1H+ECv97GABux2RDN6xjPEERT6R9IiPeso34h+FZuUjah20uT
HCQ8TjFXAoxgg3kiJzGzGFuXcm+Y2y3uhqZfhqfy7tripwQDfFWK8HlR+VNZ7OAC
S7cpqw8eoKDAqp5DL0Juubppjl8fSTyY+a0BLqGt+8eT3yb1IN/vUG7vpo09zo52
e4ASxIGFC5+cQqoUihC1+XtdQgULiw5fEVq/e6/1tvArXO20+WdvQbFgzmSVX3VC
pZ0czlCzQIUfaaYXrhD/Vhx2yo3l6EpIFvb88opjJmqiOWs/3kTAfGfyJxumIL+d
wybRleXwmYjt5hMwTquDRiM0nwj9beeISDgazDKNLVLfhQT3X/Yvh/xfwBI8K/C7
eHVybAwLnMHxEDqYR0YwwB/VaD9qxlYN71dO88VnVynbaI4jVb4AxIe3YctTmjXa
UsSsm8uwsHha2IxGn2dQaaCQZ4r0kYHlIqnYQvu9AGWG7bpRh581Cizfy5Xuplj8
L/0AFbtxASB58cryAC6bpDMCAwEAAQ==
-----END PUBLIC KEY-----
OIDC_PUBLIC_KEY
);

define( 'OIDC_PRIVATE_KEY', <<<OIDC_PRIVATE_KEY
-----BEGIN RSA PRIVATE KEY-----
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
fXTFfgxUQ3BiSizfFlNYw0ZPJWkTdg==
-----END RSA PRIVATE KEY-----
OIDC_PRIVATE_KEY
);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
