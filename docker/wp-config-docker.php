<?php
/**
 * WordPress configuration for the Docker deployment.
 *
 * Mounted over the repo's (gitignored) LocalWP wp-config.php by
 * docker/docker-compose.yml — the LocalWP file is never modified.
 *
 * Uses the same salts as LocalWP so admin sessions/cookies stay valid.
 */

// ** Database settings — the compose "db" service (MySQL 8.4) ** //
define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', 'wpuser' );
define( 'DB_PASSWORD', 'wppass123' );
define( 'DB_HOST', 'db:3306' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/** Authentication unique keys and salts (identical to LocalWP wp-config.php). */
define( 'AUTH_KEY',          'NPK0`Rd^Mm(l,FDjNx p5d,p>fc],XiV0v@[!;fGEF~^W0tQ>Mh3%^h[n.Y;#22+' );
define( 'SECURE_AUTH_KEY',   'e/L>.E#8_E>t9Dp Dfv{C>(#d4@!}^6TmQ)sB,aYvy0%>[^E(}SGIF$bmW2;Eh-R' );
define( 'LOGGED_IN_KEY',     ')R6._D$R(/Ot}AwnLMRU_#]x4!n(0icM]i0)t6-B/~1?PjHpH5W^x=C?#X:wcu#*' );
define( 'NONCE_KEY',         '@mbh!a7YfZM8sUTl zVAN?]v4Rku*waUm~,f_NY9#&[~KD9q*IP+Zb3 6GLk13aM' );
define( 'AUTH_SALT',         'pbdIp(B??:%,}2/a.(%M;K)+YSE:d~M!w]M to/sR^$IJ20n;1B9Nf[SoPSn,J0[' );
define( 'SECURE_AUTH_SALT',  'J+ dfo*De7LP]HZ9C!t!oUKK`}6sHDB)vkbm4SFa.3X]d]`N u9PrfP`+Fn_0]dg' );
define( 'LOGGED_IN_SALT',    'Vb#cZ1S?t;k_V:&AZ+O5iWb1)`$Y{QLTSf-K{90iOn>huJo I8@~qh:%.9@[y4}p' );
define( 'NONCE_SALT',        'n8A]/K9WE5ElBF{6HE/P_wLVnnPzB&U/8O_fE{]td,<H[+MEZ:&&fvl%0v,l{)P@' );
define( 'WP_CACHE_KEY_SALT', '(^ss>y2i6_171OXTc*L*FUrtJB:e+84s;H&0c;HNZY [Lk%e!8NtkMxZ^@~UO&W1' );

/** Database table prefix. */
$table_prefix = 'wp_';

/* Docker-specific values. */

/**
 * Normalize the request scheme behind TLS-terminating proxies (Cloudflare
 * Tunnel reaches Apache over plain HTTP but sends X-Forwarded-Proto: https).
 * Without this, is_ssl() is false at the origin while WP_HOME is https, and
 * redirect_canonical() emits a self-referential 301 loop on every page load.
 */
if ( ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) )
	|| ( ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && 'on' === strtolower( $_SERVER['HTTP_X_FORWARDED_SSL'] ) ) ) {
	$_SERVER['HTTPS'] = 'on';
}

/**
 * Serve the site from whichever host the visitor used (localhost:2026,
 * LAN IP http://192.168.x.x:2026, or a future tunnel hostname) without
 * redirects or broken asset URLs. Must be defined BEFORE wp-settings.php.
 *
 * Proxy-aware: Cloudflare Tunnel (and most reverse proxies) terminate TLS at
 * the edge and reach Apache over plain HTTP, signalling the original scheme
 * via X-Forwarded-Proto. Without this, enqueued CSS/JS would be printed as
 * http:// URLs on an https:// page and browsers would block them as mixed
 * content ("site renders with no styling").
 */
if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
	$scheme = 'http';
	if ( ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] )
		|| ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) )
		|| ( ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && 'on' === strtolower( $_SERVER['HTTP_X_FORWARDED_SSL'] ) )
		|| ( isset( $_SERVER['SERVER_PORT'] ) && 443 === (int) $_SERVER['SERVER_PORT'] ) ) {
		$scheme = 'https';
	}
	define( 'WP_HOME', $scheme . '://' . $_SERVER['HTTP_HOST'] );
	define( 'WP_SITEURL', WP_HOME );
}

/** Direct filesystem method (no FTP prompts) when editing via wp-admin. */
define( 'FS_METHOD', 'direct' );

/** Debugging off for the shared deployment. */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

define( 'WP_ENVIRONMENT_TYPE', 'production' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';