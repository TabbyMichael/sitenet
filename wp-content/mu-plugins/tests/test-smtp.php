<?php
/** SMTP tests: constants, routing, gmail from, password keep, from filters. */

$GLOBALS['site_test_options'] = array(
	'site_smtp_host'       => ' smtp.gmail.com ',
	'site_smtp_port'       => 587,
	'site_smtp_encryption' => 'tls',
	'site_smtp_username'   => 'site.forms@gmail.com',
	'site_smtp_password'   => '  app-pass-123  ',
	'site_smtp_from_email' => 'info@sitenet.org',
	'site_smtp_from_name'  => 'SITE',
);

site_test( 'smtp.gmail.com' === site_smtp_get( 'host' ), 'smtp: host trimmed' );
site_test( 'app-pass-123  ' === site_smtp_get( 'password' ), 'smtp: password never trimmed' );
site_test( 'fallback' === site_smtp_get( 'missing_key', 'fallback' ), 'smtp: default returned for missing key' );

$m = new Fake_Mailer();
site_smtp_configure_phpmailer( $m );
site_test( true === $m->smtp_mode, 'smtp: switches mailer to SMTP' );
site_test( 'smtp.gmail.com' === $m->Host, 'smtp: host applied' );
site_test( 587 === $m->Port, 'smtp: port applied' );
site_test( 'tls' === $m->SMTPSecure, 'smtp: tls applied' );
site_test( true === $m->SMTPAuth, 'smtp: auth enabled when username set' );
site_test( 'site.forms@gmail.com' === $m->From, 'smtp: gmail forces From to auth account' );

$GLOBALS['site_test_options']['site_smtp_host']     = 'mail.sitenet.org';
$GLOBALS['site_test_options']['site_smtp_username'] = '';
$m2 = new Fake_Mailer();
site_smtp_configure_phpmailer( $m2 );
site_test( 'info@sitenet.org' === $m2->From, 'smtp: non-gmail keeps configured From' );
site_test( false === $m2->SMTPAuth, 'smtp: auth off without username' );

$m3 = new Fake_Mailer();
$GLOBALS['site_test_options']['site_smtp_host'] = '';
site_smtp_configure_phpmailer( $m3 );
site_test( false === $m3->smtp_mode, 'smtp: does nothing when host empty' );

$GLOBALS['site_test_options']['site_smtp_password'] = 'kept-secret';
site_test( 'kept-secret' === site_smtp_keep_password( '' ), 'smtp: blank password keeps saved value' );
site_test( 'new-secret' === site_smtp_keep_password( 'new-secret' ), 'smtp: new password replaces saved value' );

$GLOBALS['site_test_options']['site_smtp_from_email'] = 'info@sitenet.org';
$GLOBALS['site_test_options']['site_smtp_from_name']  = 'SITE Kenya';
site_test( 'info@sitenet.org' === site_smtp_mail_from( 'wordpress@example.com' ), 'smtp: wp_mail From default' );
site_test( 'SITE Kenya' === site_smtp_mail_from_name( 'WordPress' ), 'smtp: wp_mail From name default' );
