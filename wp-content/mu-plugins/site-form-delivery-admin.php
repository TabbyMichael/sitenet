<?php
/**
 * SITE Form Delivery — admin screen.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render Settings > SITE Mail screen with test-mail sender.
 */
function site_smtp_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$test_result = '';
	$test_sent   = null;
	if ( isset( $_POST['site_smtp_test'] ) && check_admin_referer( 'site_smtp_test_action', 'site_smtp_test_nonce' ) ) {
		$to        = isset( $_POST['site_smtp_test_to'] ) ? sanitize_email( wp_unslash( $_POST['site_smtp_test_to'] ) ) : '';
		$test_sent = $to ? wp_mail( $to, __( 'SITE test email', 'site-child' ), __( 'If you received this, SITE mail delivery works.', 'site-child' ) ) : false;
		if ( $test_sent ) {
			$test_result = __( 'Test email sent. Check the inbox (and spam).', 'site-child' );
		} else {
			$test_result = __( 'Test email FAILED. Check host, port, username, password below, then retry.', 'site-child' );
		}
	}
	$host      = site_smtp_get( 'host', 'smtp.gmail.com' );
	$port      = site_smtp_get( 'port', '587' );
	$enc       = site_smtp_get( 'encryption', 'tls' );
	$user      = site_smtp_get( 'username' );
	$from_mail = site_smtp_get( 'from_email', 'info@sitenet.org' );
	$from_name = site_smtp_get( 'from_name', 'SITE' );
	$last_err  = get_option( 'site_smtp_last_error', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'SITE Mail (SMTP)', 'site-child' ); ?></h1>
		<p><?php esc_html_e( 'Forms are always saved under Form Submissions. For Gmail sending: host smtp.gmail.com, port 587, TLS, username = your Gmail address, password = a Gmail App Password (not your login password), From email = info@sitenet.org if added as a Send-As alias, else the Gmail address.', 'site-child' ); ?></p>
		<?php if ( '' !== $test_result ) : ?>
			<div class="notice notice-info"><p><?php echo esc_html( $test_result ); ?></p></div>
		<?php endif; ?>
		<?php if ( '' !== (string) $last_err ) : ?>
			<div class="notice notice-error"><p><strong><?php esc_html_e( 'Last send error:', 'site-child' ); ?></strong> <?php echo esc_html( (string) $last_err ); ?></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'site_smtp_group' ); ?>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="site_smtp_host"><?php esc_html_e( 'SMTP host', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_host" name="site_smtp_host" type="text" class="regular-text" value="<?php echo esc_attr( $host ); ?>"></td></tr>
				<tr><th scope="row"><label for="site_smtp_port"><?php esc_html_e( 'Port', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_port" name="site_smtp_port" type="number" min="1" max="65535" value="<?php echo esc_attr( $port ); ?>"></td></tr>
				<tr><th scope="row"><label for="site_smtp_encryption"><?php esc_html_e( 'Encryption', 'site-child' ); ?></label></th>
					<td><select id="site_smtp_encryption" name="site_smtp_encryption">
						<option value="tls" <?php selected( $enc, 'tls' ); ?>>TLS (port 587)</option>
						<option value="ssl" <?php selected( $enc, 'ssl' ); ?>>SSL (port 465)</option>
						<option value="none" <?php selected( $enc, 'none' ); ?>><?php esc_html_e( 'None', 'site-child' ); ?></option>
					</select></td></tr>
				<tr><th scope="row"><label for="site_smtp_username"><?php esc_html_e( 'Username', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_username" name="site_smtp_username" type="text" class="regular-text" autocomplete="username" value="<?php echo esc_attr( $user ); ?>"></td></tr>
				<tr><th scope="row"><label for="site_smtp_password"><?php esc_html_e( 'Password', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_password" name="site_smtp_password" type="password" class="regular-text" autocomplete="new-password" value="">
						<p class="description"><?php esc_html_e( 'Leave blank to keep the saved password.', 'site-child' ); ?></p></td></tr>
				<tr><th scope="row"><label for="site_smtp_from_email"><?php esc_html_e( 'From email', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_from_email" name="site_smtp_from_email" type="email" class="regular-text" value="<?php echo esc_attr( $from_mail ); ?>"></td></tr>
				<tr><th scope="row"><label for="site_smtp_from_name"><?php esc_html_e( 'From name', 'site-child' ); ?></label></th>
					<td><input id="site_smtp_from_name" name="site_smtp_from_name" type="text" class="regular-text" value="<?php echo esc_attr( $from_name ); ?>"></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
		<h2><?php esc_html_e( 'Send a test email', 'site-child' ); ?></h2>
		<form method="post" action="">
			<?php wp_nonce_field( 'site_smtp_test_action', 'site_smtp_test_nonce' ); ?>
			<p><input type="email" name="site_smtp_test_to" class="regular-text" required>
			<?php submit_button( __( 'Send test', 'site-child' ), 'secondary', 'site_smtp_test', false ); ?></p>
		</form>
	</div>
	<?php
}
