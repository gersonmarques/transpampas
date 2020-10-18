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
define( 'DB_NAME', 'gpeduc35_wp788' );

/** MySQL database username */
define( 'DB_USER', 'gpeduc35_wp788' );

/** MySQL database password */
define( 'DB_PASSWORD', 'S8)p!v0544' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '2ofnxy35vubs29hkteuzogp4ywxpevanpibnwzvjhoqy8qtr4mwwnaq6zjqskvvy' );
define( 'SECURE_AUTH_KEY',  'tcrpeat1tylb5p2gsinxurkjddgctp9fhpzzrmd8sqtan1eqkpxcr2vztrpt7goj' );
define( 'LOGGED_IN_KEY',    'i06f7i61azsptjutuimfka8occmnypyyg1pj0ff2b8bw3owujd8pf5p18xsnrzjm' );
define( 'NONCE_KEY',        'jmrfeh0t0lq0pcqzovlthtcnnkclhifnjl0c4rsghfhi786d4zvnqafjdk5jpblg' );
define( 'AUTH_SALT',        'wroidfkfz20r4a9tssxto0ltq3lbweaqmxsmbqctixsp4clo3oyrrs6rwaelpccu' );
define( 'SECURE_AUTH_SALT', 'okkgdldyg5lrore7hbauzkrtp6ndlwoxnkvyxxhh1davtei8b88ra7kfn7fpb6rs' );
define( 'LOGGED_IN_SALT',   'hzt9zazgyannorshhkuwp0jehexknlbtpdltium5ekqqjwbl0wi59xsvnibzcu1i' );
define( 'NONCE_SALT',       'c9rsaux5ewdbu5iqoriisy0vap0mdjncbbvhtgltn76fliaq9shttwgxzcaxxdf5' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpuv_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
