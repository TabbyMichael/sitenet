<?php
/**
 * Plugin Name: SITE Form Delivery
 * Description: Stores every Contact Form 7 submission in WordPress and routes wp_mail through SMTP so forms actually deliver.
 * Version: 1.0.0
 * Author: SITE Development Team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/site-form-delivery-storage.php';
require_once __DIR__ . '/site-form-delivery-smtp.php';
require_once __DIR__ . '/site-form-delivery-admin.php';
require_once __DIR__ . '/site-form-delivery-fix.php';
