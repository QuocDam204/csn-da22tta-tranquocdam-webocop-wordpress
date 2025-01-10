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
define( 'DB_NAME', 'webocop_wp' );

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
define( 'AUTH_KEY',         'l-L.CTddw>h?Q{r5B0/FzG T</J;`.x3wMw<U 6Qz3`pJwj32d mVd-._J&J F=#' );
define( 'SECURE_AUTH_KEY',  'xU-A2YZ]h`jg52%mM0J]#}F,2DM:).ZU<*rBJS-HbQv]z9Aaz0=HIWY{/58>Qte@' );
define( 'LOGGED_IN_KEY',    '6U))C`gu&cP~Cr^eA?0eiB(~`fqb0IDm~6l+J*W-=$V.lMAh(y]vop!~JX6YbRv;' );
define( 'NONCE_KEY',        'x,Em9.uQ0}1fH-Gt!O=S;DFG1x%cT|MYQ +lq|YCKC <)F^mpTK.^h4l gE`LaJC' );
define( 'AUTH_SALT',        'ck;0h^k-}[v`R t|*n}9&1hm%d.Gz,o2)X[D :`uG7NZrO3RhV~z5KA=u-1aW6Mc' );
define( 'SECURE_AUTH_SALT', '<Q,XDo[Qi^k]|0gyx)e,:xjN!lfa]Us&3yEMX.YX4LC8~5uLg-AB)OgOD&qC>bxk' );
define( 'LOGGED_IN_SALT',   'qcxALK*_>-%]o$r!5K:t6z#]?gh;h$~$r|+V?,XawdV7 5oCS[C!*pe,D-m*j(l1' );
define( 'NONCE_SALT',       'kL*%wDFs)cyIaI5pv2s6G#Md/thLd}m  H-JZrU!y7?AT!O4 @U:w}o^%%zH[47[' );

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
