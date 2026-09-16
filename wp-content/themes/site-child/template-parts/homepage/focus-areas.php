<?php
/**
 * Homepage Focus Areas Section
 *
 * Thin delegate. The section itself is rendered by the shared part
 * template-parts/sections/focus-areas.php, which is also used by the About Us
 * page and the Our Work pages. Keeping one renderer is what guarantees the
 * cards, imagery, copy and styling are identical on every page that shows them.
 *
 * The homepage wrapper is retained (rather than calling the shared part from
 * front-page.php) so front-page.php and its template-part list stay unchanged.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'template-parts/sections/focus-areas' );
