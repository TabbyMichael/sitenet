<?php
/**
 * Shared "Donors & Promoters" section.
 *
 * Prints the markup built by site_child_get_donors_html() (which owns all
 * escaping). Include anywhere with:
 * get_template_part( 'template-parts/donors' );
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo site_child_get_donors_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built and escaped in the helper.
