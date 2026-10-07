<?php
/** Storage tests: body builder + submission persistence + sent/failed flags. */

site_test( '' === site_build_submission_body( array() ), 'body: empty data gives empty body' );

site_test(
	'Name: Ian' === site_build_submission_body( array( 'your-name' => 'Ian', '_wpcf7' => '630', 'empty' => '' ) ),
	'body: skips CF7 internals and blank fields'
);

site_test(
	'services: Milk, Soap' === site_build_submission_body( array( 'services' => array( 'Milk', 'Soap' ) ) ),
	'body: checkbox arrays joined with comma'
);

WPCF7_Submission::set_test_data(
	array(
		'your-name'    => 'Ian',
		'your-email'   => 'ian@example.com',
		'your-message' => 'hello',
		'_wpcf7'       => '630',
	)
);
$form = new WPCF7_ContactForm( 630, 'Contact Form' );
$id   = site_store_cf7_submission( $form );

site_test( $id > 0, 'storage: returns new post id' );
site_test( 'site_submission' === $GLOBALS['site_test_inserted'][ $id ]['post_type'], 'storage: inserts site_submission post' );
site_test( 'pending' === get_post_meta( $id, '_site_mail_status', true ), 'storage: status starts pending' );
site_test( 630 === (int) get_post_meta( $id, '_site_form_id', true ), 'storage: records form id' );
site_test( 'ian@example.com' === get_post_meta( $id, '_site_field_your-email', true ), 'storage: records field meta' );
site_test( '' === get_post_meta( $id, '_site_field__wpcf7', true ), 'storage: skips underscore keys in meta' );

site_cf7_mark_sent( $form );
site_test( 'sent' === get_post_meta( $id, '_site_mail_status', true ), 'storage: marks sent' );

site_cf7_mark_failed( $form );
site_test( 'failed' === get_post_meta( $id, '_site_mail_status', true ), 'storage: marks failed' );

site_test( 0 === site_store_cf7_submission( new stdClass() ), 'storage: rejects non-CF7 objects' );
