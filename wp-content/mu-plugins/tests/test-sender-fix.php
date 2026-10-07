<?php
/** Sender-fix tests: foreign domains rewritten, tags and same-host untouched. */

$GLOBALS['site_test_home'] = 'https://sitenet.org';
$GLOBALS['site_test_options']['site_smtp_from_email'] = 'info@sitenet.org';

$out = site_fix_cf7_sender_domain(
	array( 'sender' => 'SITE <wordpress@gundihillinc.com>', 'additional_headers' => 'Reply-To: [your-email]' ),
	new WPCF7_ContactForm()
);
site_test( 'SITE <info@sitenet.org>' === $out['sender'], 'sender: foreign domain rewritten to mailbox' );

$out2 = site_fix_cf7_sender_domain(
	array( 'sender' => 'Ian <[your-email]>', 'additional_headers' => '' ),
	new WPCF7_ContactForm()
);
site_test( 'Ian <[your-email]>' === $out2['sender'], 'sender: CF7 tag sender left alone' );

$out3 = site_fix_cf7_sender_domain(
	array( 'sender' => 'SITE <hello@sitenet.org>', 'additional_headers' => '' ),
	new WPCF7_ContactForm()
);
site_test( 'SITE <hello@sitenet.org>' === $out3['sender'], 'sender: same-domain sender untouched' );

$out4 = site_fix_cf7_sender_domain(
	array( 'sender' => 'SITE <no-reply@external.test>', 'additional_headers' => '' ),
	new WPCF7_ContactForm()
);
site_test(
	'Reply-To: no-reply@external.test' === $out4['additional_headers'],
	'sender: original address kept as Reply-To'
);

$out5 = site_fix_cf7_sender_domain( array(), new WPCF7_ContactForm() );
site_test( array() === $out5, 'sender: empty components returned unchanged' );
