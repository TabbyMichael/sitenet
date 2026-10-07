<?php
/**
 * SITE Form Delivery — SMTP config + mailer routing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get one SMTP setting: constant wins, then DB option.
 *
 * @param string $key     Key without prefix.
 * @param mixed  $default Fallback.
 * @return mixed Value.
 */
function site_smtp_get( $key, $default = '' ) {
	$const = 'SITE_SMTP_' . strtoupper( $key );
	if ( defined( $const ) && '' !== constant( $const ) ) {
		return constant( $const );
	}
	$value = get_option( 'site_smtp_' . $key, $default );
	if ( 'password' === $key ) {
		return $value;
	}
	return is_string( $value ) ? trim( $value ) : $value;
}

/**
 * Register SMTP settings.
 */
function site_smtp_register_settings() {
	register_setting( 'site_smtp_group', 'site_smtp_host', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting( 'site_smtp_group', 'site_smtp_port', array( 'sanitize_callback' => 'absint' ) );
	register_setting( 'site_smtp_group', 'site_smtp_encryption', array( 'sanitize_callback' => 'sanitize_key' ) );
	register_setting( 'site_smtp_group', 'site_smtp_username', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting( 'site_smtp_group', 'site_smtp_from_email', array( 'sanitize_callback' => 'sanitize_email' ) );
	register_setting( 'site_smtp_group', 'site_smtp_from_name', array( 'sanitize_callback' => 'sanitize_text_field' ) );
	register_setting( 'site_smtp_group', 'site_smtp_password' );
}
add_action( 'admin_init', 'site_smtp_register_settings' );

/**
 * Add Settings > SITE Mail screen.
 */
function site_smtp_menu() {
	add_options_page(
		__( 'SITE Mail', 'site-child' ),
		__( 'SITE Mail', 'site-child' ),
		'manage_options',
		'site-smtp',
		'site_smtp_page'
	);
}
add_action( 'admin_menu', 'site_smtp_menu' );

/**
 * Keep blank password field from wiping saved password.
 *
 * @param mixed $value Submitted value.
 * @return mixed Kept or new value.
 */
function site_smtp_keep_password( $value ) {
	if ( '' === (string) $value ) {
		return get_option( 'site_smtp_password', '' );
	}
	return $value;
}
add_filter( 'pre_update_option_site_smtp_password', 'site_smtp_keep_password' );

/**
 * Route wp_mail() through configured SMTP server.
 *
 * Gmail note: when host is smtp.gmail.com, Gmail only allows the From
 * address to be the authenticated account (or a verified Send-As alias),
 * so the From is forced to the SMTP username in that case.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer Mailer instance.
 */
function site_smtp_configure_phpmailer( $phpmailer ) {
	$host = site_smtp_get( 'host' );
	if ( '' === $host ) {
		return;
	}
	$phpmailer->isSMTP();
	$phpmailer->Host     = $host;
	$phpmailer->Port     = (int) site_smtp_get( 'port', '587' ) ?: 587;
	$phpmailer->SMTPAuth = '' !== site_smtp_get( 'username' );
	$phpmailer->Username = site_smtp_get( 'username' );
	$phpmailer->Password = site_smtp_get( 'password' );
	$enc                 = site_smtp_get( 'encryption', 'tls' );
	if ( 'ssl' === $enc ) {
		$phpmailer->SMTPSecure = 'ssl';
	} elseif ( 'tls' === $enc ) {
		$phpmailer->SMTPSecure = 'tls';
	} else {
		$phpmailer->SMTPSecure = '';
	}
	$from = site_smtp_get( 'from_email' );
	$is_gmail = false !== stripos( (string) $host, 'gmail' );
	if ( $is_gmail && is_email( site_smtp_get( 'username' ) ) ) {
		$from = site_smtp_get( 'username' );
	}
	if ( is_email( $from ) ) {
		$phpmailer->setFrom( $from, site_smtp_get( 'from_name', 'SITE' ) );
	}
}
add_action( 'phpmailer_init', 'site_smtp_configure_phpmailer' );

/**
 * Capture the SMTP debug transcript for the current request.
 *
 * Debug level 2 logs the client/server conversation (no passwords) into a
 * global so the failure logger below can store the real reason instead of
 * the generic CF7 "Failed to send your message" notice.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer Mailer instance.
 */
function site_smtp_capture_debug( $phpmailer ) {
	if ( '' === site_smtp_get( 'host' ) ) {
		return;
	}
	$phpmailer->SMTPDebug   = 2;
	$phpmailer->Debugoutput = function ( $str ) {
		$GLOBALS['site_smtp_debug'][] = $str;
	};
}
add_action( 'phpmailer_init', 'site_smtp_capture_debug', 5 );

/**
 * Log every wp_mail() failure with the SMTP transcript.
 *
 * @param WP_Error $error Mail error object.
 */
function site_log_mail_failure( $error ) {
	$debug = isset( $GLOBALS['site_smtp_debug'] ) ? implode( "\n", (array) $GLOBALS['site_smtp_debug'] ) : '';
	$message = $error instanceof WP_Error ? $error->get_error_message() : 'unknown mail error';
	if ( function_exists( 'error_log' ) ) {
		error_log( 'SITE mail failed: ' . $message . ( $debug ? ' | SMTP: ' . substr( $debug, -2000 ) : '' ) );
	}
	update_option( 'site_smtp_last_error', current_time( 'mysql' ) . ' — ' . $message . ( $debug ? ' | ' . substr( $debug, -2000 ) : '' ), false );
}
add_action( 'wp_mail_failed', 'site_log_mail_failure' );

/**
 * Default wp_mail From to configured mailbox.
 *
 * @param string $email Default address.
 * @return string Filtered address.
 */
function site_smtp_mail_from( $email ) {
	$from = site_smtp_get( 'from_email' );
	return is_email( $from ) ? $from : $email;
}
add_filter( 'wp_mail_from', 'site_smtp_mail_from' );

/**
 * Default wp_mail From name.
 *
 * @param string $name Default name.
 * @return string Filtered name.
 */
function site_smtp_mail_from_name( $name ) {
	$from_name = site_smtp_get( 'from_name' );
	return '' !== $from_name ? $from_name : $name;
}
add_filter( 'wp_mail_from_name', 'site_smtp_mail_from_name' );
