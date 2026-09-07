<?php
/**
 * Header layout override: transparent
 *
 * All header layouts render the same rebuilt SITE header
 * (template-parts/site-header.php) so navigation is consistent site-wide.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'template-parts/site-header' );
