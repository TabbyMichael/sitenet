<?php
/**
 * SITE Form Delivery tests — bootstrap with WP + CF7 stubs.
 *
 * Standalone runner: no database, no plugins needed. Stubs the WP/CF7 APIs
 * the mu-plugin files touch, loads them, and runs the assertions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

error_reporting( E_ALL & ~E_DEPRECATED );

require_once __DIR__ . '/stubs.php';

$pass = 0;
$fail = 0;

/**
 * Run one assertion.
 *
 * @param bool   $cond Condition result.
 * @param string $name Test name.
 */
function site_test( $cond, $name ) {
	global $pass, $fail;
	if ( $cond ) {
		$pass++;
		echo "PASS: {$name}\n";
	} else {
		$fail++;
		echo "FAIL: {$name}\n";
	}
}

$plugin_dir = dirname( __DIR__ );
require_once $plugin_dir . '/site-form-delivery-storage.php';
require_once $plugin_dir . '/site-form-delivery-smtp.php';
require_once $plugin_dir . '/site-form-delivery-fix.php';

require_once __DIR__ . '/test-storage.php';
require_once __DIR__ . '/test-smtp.php';
require_once __DIR__ . '/test-sender-fix.php';
require_once __DIR__ . '/test-hooks.php';

echo "\n{$pass} passed, {$fail} failed\n";
exit( $fail > 0 ? 1 : 0 );
