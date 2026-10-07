<?php
/**
 * SITE Form Delivery — CF7 sender-domain fix + admin notice.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Rewrite foreign literal CF7 senders to the site mailbox.
 *
 * @param array $components Mail components.
 * @param mixed $form       Form object.
 * @return array Filtered components.
 */
function site_fix_cf7_sender_domain( $components, $form ) {
	if ( empty( $components['sender'] ) ) {
		return $components;
	}
	$home_host = (string) wp_parse_url( home_url(), PHP_URL_HOST );
	$site_root = preg_replace( '/^www\./', '', strtolower( $home_host ) );
	$mailbox   = site_smtp_get( 'from_email', 'info@sitenet.org' );
	$sender    = (string) $components['sender'];
	if ( preg_match( '/<([^>]+)>/', $sender, $m ) ) {
		$sender_email = strtolower( trim( $m[1] ) );
		$pos          = strrpos( $sender_email, '@' );
		$sender_host  = $pos ? substr( $sender_email, $pos + 1 ) : '';
		$is_tag       = false !== strpos( $sender_email, '[' );
		$same_host    = ( $sender_host === $site_root ) || ( 'sitenet.org' === $sender_host );
		if ( ! $is_tag && '' !== $sender_host && ! $same_host ) {
			$components['sender'] = str_replace( $m[1], $mailbox, $sender );
			$headers              = (string) $components['additional_headers'];
			if ( '' === $headers ) {
				$components['additional_headers'] = 'Reply-To: ' . $m[1];
			} elseif ( false === stripos( $headers, 'reply-to' ) ) {
				$components['additional_headers'] = $headers . "\nReply-To: " . $m[1];
			}
		}
	}
	return $components;
}
add_filter( 'wpcf7_mail_components', 'site_fix_cf7_sender_domain', 10, 2 );

/**
 * Warn admins on submissions screen when SMTP is missing.
 */
function site_smtp_missing_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'site_submission' === $screen->post_type && '' === site_smtp_get( 'host' ) ) {
		echo '<div class="notice notice-warning"><p>';
		esc_html_e( 'SITE Mail: submissions are being saved, but SMTP is not configured — emails are not sending yet. Go to Settings > SITE Mail.', 'site-child' );
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'site_smtp_missing_notice' );
