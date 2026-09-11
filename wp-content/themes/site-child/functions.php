<?php
/**
 * SITE Child Theme Functions
 * 
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fix Parent Theme Enqueue Order & Load Child Theme Styles
 */
function site_child_enqueue_styles() {
	// 1. Ensure Bootstrap CSS loads first
	wp_enqueue_style(
		'bootstrap',
		get_template_directory_uri() . '/assets/css/bootstrap.css',
		array(),
		'3.4.1'
	);

	// 2. Parent Theme main style.css
	wp_enqueue_style(
		'thelandscaper-parent-style',
		get_template_directory_uri() . '/style.css',
		array( 'bootstrap' ),
		'2.6.1'
	);

	// 3. Child Theme style.css
	wp_enqueue_style(
		'site-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'bootstrap', 'thelandscaper-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_styles', 15 );

/**
 * Register SITE custom image sizes.
 */
function site_child_image_sizes() {
	add_image_size( 'site-hero', 1920, 730, true );
	add_image_size( 'site-program-card', 600, 400, true );
	add_image_size( 'site-story-thumb', 400, 300, true );
}
add_action( 'after_setup_theme', 'site_child_image_sizes' );

/**
 * Enqueue hero carousel assets on the front page only.
 *
 * Vanilla JS — no carousel library, no jQuery dependency.
 * Files are versioned with filemtime() so editors always get fresh assets.
 */
function site_child_enqueue_carousel_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	$css_path = get_stylesheet_directory() . '/assets/css/carousel.css';
	$js_path  = get_stylesheet_directory() . '/assets/js/carousel.js';

	wp_enqueue_style(
		'site-carousel',
		get_stylesheet_directory_uri() . '/assets/css/carousel.css',
		array( 'site-child-style' ),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-carousel',
		get_stylesheet_directory_uri() . '/assets/js/carousel.js',
		array(),
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_carousel_assets', 20 );

/**
 * Enqueue Swiper 11 + assets for the "Our Partners & Donors" carousels
 * (rendered by template-parts/homepage/partner-carousel.php).
 *
 * Swiper loads from jsDelivr CDN; the carousel styles/init script live in
 * the child theme and are versioned with filemtime() for cache busting.
 */
function site_child_enqueue_partners_donors_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'swiper-bundle',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11.0.0'
	);

	wp_enqueue_script(
		'swiper-bundle',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	$pd_css_path = get_stylesheet_directory() . '/assets/css/partners-donors.css';
	$pd_js_path  = get_stylesheet_directory() . '/assets/js/partners-donors.js';

	wp_enqueue_style(
		'site-partners-donors',
		get_stylesheet_directory_uri() . '/assets/css/partners-donors.css',
		array( 'site-child-style', 'swiper-bundle' ),
		file_exists( $pd_css_path ) ? (string) filemtime( $pd_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-partners-donors',
		get_stylesheet_directory_uri() . '/assets/js/partners-donors.js',
		array( 'swiper-bundle' ),
		file_exists( $pd_js_path ) ? (string) filemtime( $pd_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_partners_donors_assets', 20 );

/**
 * Enqueue the homepage responsive overrides (front page only).
 *
 * Styling: assets/css/homepage-responsive.css — breakpoint bands covering
 * large monitors, laptops, iPad landscape/portrait, huge phones, small
 * phones and old 4" phones. Loaded after the carousel and partners/donors
 * stylesheets so it wins equal-specificity ties. Versioned with filemtime()
 * for cache busting during development.
 */
function site_child_enqueue_homepage_responsive_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	$responsive_css_path = get_stylesheet_directory() . '/assets/css/homepage-responsive.css';

	wp_enqueue_style(
		'site-homepage-responsive',
		get_stylesheet_directory_uri() . '/assets/css/homepage-responsive.css',
		array( 'site-child-style', 'site-carousel', 'site-partners-donors' ),
		file_exists( $responsive_css_path ) ? (string) filemtime( $responsive_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_homepage_responsive_assets', 25 );

/**
 * Enqueue assets for the reusable Client Testimonials slider
 * (rendered by template-parts/homepage/testimonials.php).
 *
 * Styling: assets/css/testimonials.css  — self-contained responsive component.
 * Behaviour: assets/js/testimonials.js  — vanilla, no dependencies, deferred.
 * Versioned with filemtime() for cache busting during development.
 */
function site_child_enqueue_testimonials_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	$tm_css_path = get_stylesheet_directory() . '/assets/css/testimonials.css';
	$tm_js_path  = get_stylesheet_directory() . '/assets/js/testimonials.js';

	wp_enqueue_style(
		'site-testimonials',
		get_stylesheet_directory_uri() . '/assets/css/testimonials.css',
		array( 'site-child-style' ),
		file_exists( $tm_css_path ) ? (string) filemtime( $tm_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-testimonials',
		get_stylesheet_directory_uri() . '/assets/js/testimonials.js',
		array(),
		file_exists( $tm_js_path ) ? (string) filemtime( $tm_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_testimonials_assets', 26 );

/**
 * Enqueue the rebuilt header / navigation assets (site-wide).
 *
 * Styling: assets/css/header.css  — loaded after style.css so it wins ties.
 * Behaviour: assets/js/header.js  — vanilla, no dependencies, deferred.
 * Both are versioned with filemtime() for cache busting during development.
 */
function site_child_enqueue_header_assets() {
	$header_css_path = get_stylesheet_directory() . '/assets/css/header.css';
	$header_js_path  = get_stylesheet_directory() . '/assets/js/header.js';

	wp_enqueue_style(
		'site-header',
		get_stylesheet_directory_uri() . '/assets/css/header.css',
		array( 'site-child-style' ),
		file_exists( $header_css_path ) ? (string) filemtime( $header_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-header',
		get_stylesheet_directory_uri() . '/assets/js/header.js',
		array(),
		file_exists( $header_js_path ) ? (string) filemtime( $header_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_header_assets', 20 );

/**
 * Enqueue the modern dark footer assets (site-wide).
 *
 * Styling: assets/css/footer.css  — loaded after style.css so it wins ties.
 * Behaviour: assets/js/footer.js  — vanilla, no dependencies, deferred.
 * Both are versioned with filemtime() for cache busting during development.
 */
function site_child_enqueue_footer_assets() {
	$footer_css_path = get_stylesheet_directory() . '/assets/css/footer.css';
	$footer_js_path  = get_stylesheet_directory() . '/assets/js/footer.js';

	wp_enqueue_style(
		'site-footer',
		get_stylesheet_directory_uri() . '/assets/css/footer.css',
		array( 'site-child-style' ),
		file_exists( $footer_css_path ) ? (string) filemtime( $footer_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-footer',
		get_stylesheet_directory_uri() . '/assets/js/footer.js',
		array(),
		file_exists( $footer_js_path ) ? (string) filemtime( $footer_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_footer_assets', 20 );

/**
 * Enqueue Our Work page assets.
 *
 * Loaded only on the Our Work page template. CSS + vanilla JS are scoped to
 * .site-our-work and do not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_our_work_assets() {
	if ( ! is_page_template( 'page-our-work.php' ) ) {
		return;
	}

	$ow_css_path = get_stylesheet_directory() . '/assets/css/our-work.css';
	$ow_js_path  = get_stylesheet_directory() . '/assets/js/our-work.js';

	wp_enqueue_style(
		'site-our-work',
		get_stylesheet_directory_uri() . '/assets/css/our-work.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $ow_css_path ) ? (string) filemtime( $ow_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-our-work',
		get_stylesheet_directory_uri() . '/assets/js/our-work.js',
		array(),
		file_exists( $ow_js_path ) ? (string) filemtime( $ow_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_our_work_assets', 25 );
/**
 * Enqueue Our Work (page-ourwork.php) assets.
 *
 * Loaded only on the Our Work page template. CSS is scoped to
 * .site-ourwork and does not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_ourwork_assets() {
	if ( ! is_page_template( 'page-ourwork.php' ) ) {
		return;
	}

	$owk_css_path = get_stylesheet_directory() . '/assets/css/ourwork.css';

	wp_enqueue_style(
		'site-ourwork',
		get_stylesheet_directory_uri() . '/assets/css/ourwork.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $owk_css_path ) ? (string) filemtime( $owk_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_ourwork_assets', 26 );



/**
 * Enqueue About Us page assets.
 *
 * Loaded only on the About Us page template. CSS is scoped to
 * .site-about-us and does not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_about_us_assets() {
	if ( ! is_page_template( 'page-about-us.php' ) ) {
		return;
	}

	$au_css_path = get_stylesheet_directory() . '/assets/css/about-us.css';

	wp_enqueue_style(
		'site-about-us',
		get_stylesheet_directory_uri() . '/assets/css/about-us.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $au_css_path ) ? (string) filemtime( $au_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_about_us_assets', 25 );

/**
 * Enqueue Stories (blog) page assets.
 *
 * Loaded only on the blog posts page. CSS + vanilla JS are scoped to
 * .site-stories and do not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_stories_assets() {
	if ( ! is_home() && ! is_post_type_archive( 'site_story' ) ) {
		return;
	}

	$st_css_path = get_stylesheet_directory() . '/assets/css/stories.css';
	$st_js_path  = get_stylesheet_directory() . '/assets/js/stories.js';

	wp_enqueue_style(
		'site-stories',
		get_stylesheet_directory_uri() . '/assets/css/stories.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $st_css_path ) ? (string) filemtime( $st_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-stories',
		get_stylesheet_directory_uri() . '/assets/js/stories.js',
		array(),
		file_exists( $st_js_path ) ? (string) filemtime( $st_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}

/**
 * Enqueue Resources page assets.
 *
 * Loaded only on the Resources page template. CSS is scoped to
 * .site-resources and does not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_resources_assets() {
	if ( ! is_page_template( 'page-resources.php' ) ) {
		return;
	}

	$res_css_path = get_stylesheet_directory() . '/assets/css/resources.css';

	wp_enqueue_style(
		'site-resources',
		get_stylesheet_directory_uri() . '/assets/css/resources.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $res_css_path ) ? (string) filemtime( $res_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_resources_assets', 25 );
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_stories_assets', 25 );

/**
 * Enqueue single-story (site_story detail) assets.
 *
 * Loaded only on singular site_story posts. CSS is scoped to
 * .site-story-single and does not affect the locked navbar, header, or
 * footer. Loaded before dark.css so both light and dark palettes are
 * self-contained.
 */
function site_child_enqueue_story_single_assets() {
	if ( ! is_singular( 'site_story' ) ) {
		return;
	}

	$ss_css_path = get_stylesheet_directory() . '/assets/css/story-single.css';

	wp_enqueue_style(
		'site-story-single',
		get_stylesheet_directory_uri() . '/assets/css/story-single.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $ss_css_path ) ? (string) filemtime( $ss_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_story_single_assets', 25 );

/**
 * Force the modernised revamp shells for the nine programme + resource pages.
 *
 * WHY: these nine pages carry legacy Elementor/SiteOrigin content. Elementor's
 * Modules\PageTemplates\Module::template_include (priority 11) replaces the
 * resolved template with the parent theme's page.php for builder-owned pages,
 * so the native page-{slug}.php hierarchy never takes effect (verified via
 * get_page_template() + $wp_filter inspection in the live stack). Hooking
 * `template_include` at 99 deliberately runs after every plugin loader
 * (RevSlider/WooCommerce @10, Elementor @11) and reasserts the shell.
 *
 * Locked boundaries: header + footer are untouched — each shell calls
 * get_header()/get_footer() itself. Only the nine slugs below are routed;
 * every other page (including the Our Work hub and galleries index) keeps
 * its existing template. Never add a builder-owned page here.
 *
 * @param string $template Path to the template WordPress resolved.
 * @return string Path to the revamp shell for the nine slugs, else $template.
 */
function site_child_revamp_page_template( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	$rv_shells = array(
		'enterprise-development-and-value-chains' => 'page-enterprise-development-and-value-chains.php',
		'climate-actions'                         => 'page-climate-actions.php',
		'empowering-women-for-employment'         => 'page-empowering-women-for-employment.php',
		'sample-page-2'                           => 'page-sample-page-2.php',
		'case-studys'                             => 'page-case-studys.php',
		'irrigation-and-drainage'                 => 'page-irrigation-and-drainage.php',
		'papers'                                  => 'page-papers.php',
		'gallery-full-width-2'                    => 'page-gallery-full-width-2.php',
		'gallery-lightbox'                        => 'page-gallery-lightbox.php',
	);

	$rv_slug = get_post_field( 'post_name', get_queried_object_id() );
	if ( ! $rv_slug || ! isset( $rv_shells[ $rv_slug ] ) ) {
		return $template;
	}

	$rv_shell = locate_template( array( $rv_shells[ $rv_slug ] ) );
	return $rv_shell ? $rv_shell : $template;
}
add_filter( 'page_template', 'site_child_revamp_page_template', 5 );
add_filter( 'template_include', 'site_child_revamp_page_template', 99 );

/**
 * Enqueue assets for the modernised programme + resource pages.
 *
 * Loaded ONLY on the revamped pages (programme detail pages, resource
 * landing pages and the two galleries). CSS + vanilla JS are scoped to
 * .site-revamp and do not affect the locked navbar, header, or footer.
 * Registered below priority 30, before dark.css, so the light+dark palette
 * for these pages is self-contained in revamp.css.
 */
function site_child_enqueue_revamp_assets() {
	$rv_slugs = array(
		'enterprise-development-and-value-chains',
		'climate-actions',
		'empowering-women-for-employment',
		'sample-page-2',
		'case-studys',
		'irrigation-and-drainage',
		'papers',
		'gallery-full-width-2',
		'gallery-lightbox',
	);

	if ( ! is_page( $rv_slugs ) ) {
		return;
	}

	$rv_css_path = get_stylesheet_directory() . '/assets/css/revamp.css';
	$rv_js_path  = get_stylesheet_directory() . '/assets/js/revamp.js';

	wp_enqueue_style(
		'site-revamp',
		get_stylesheet_directory_uri() . '/assets/css/revamp.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $rv_css_path ) ? (string) filemtime( $rv_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-revamp',
		get_stylesheet_directory_uri() . '/assets/js/revamp.js',
		array(),
		file_exists( $rv_js_path ) ? (string) filemtime( $rv_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_revamp_assets', 25 );

/**
 * Build story cards for a category (used by the revamped programme pages).
 *
 * Returns an array of cards (title|link|image|date) pulled from published
 * posts assigned to $category_slug, newest first. Falls back to an empty
 * array, which pages render as a friendly empty state.
 */
function site_child_revamp_stories( $category_slug, $limit = 3 ) {
	$card_stack = array();

	$category = get_category_by_slug( $category_slug );
	if ( ! $category ) {
		return $card_stack;
	}

	$story_posts = get_posts(
		array(
			'post_type'      => 'post',
			'cat'            => $category->term_id,
			'posts_per_page' => absint( $limit ),
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		)
	);

	foreach ( $story_posts as $story_post ) {
		$story_image = '';
		$story_thumb = get_post_thumbnail_id( $story_post );
		if ( $story_thumb ) {
			$story_image = wp_get_attachment_image_url( $story_thumb, 'site-story-thumb' );
			if ( ! $story_image ) {
				$story_image = wp_get_attachment_image_url( $story_thumb, 'medium' );
			}
		}

		$card_stack[] = array(
			'title' => get_the_title( $story_post ),
			'link'  => get_permalink( $story_post ),
			'image' => $story_image ? $story_image : '',
			'date'  => get_the_date( '', $story_post ),
		);
	}

	return $card_stack;
}

/**
 * Return a reusable "arrow right" SVG icon.
 *
 * Used by the Stories index and site_story archive templates. Centralised here
 * so both templates share one declaration (defining it in each template would
 * cause a fatal "Cannot redeclare" error if both ever load in one request).
 *
 * @param int $size SVG width/height in pixels.
 * @return string Raw SVG markup (already escaped for attribute context).
 */
function site_child_svg_arrow( $size = 16 ) {
	$size = absint( $size );
	return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

/**
 * Build a styled fallback card image for Our Work items lacking a thumbnail.
 *
 * Returns a badge + icon block that matches the designed fallback style.
 * Centralised here so page-our-work.php does not define its own helper
 * (which would fatal if the template ever loads twice in one request).
 *
 * @param array $item Content item with optional 'badge' and 'icon' keys.
 * @return string HTML markup for the fallback image.
 */
function site_child_ow_get_fallback_image( $item ) {
	$badge = isset( $item['badge'] ) ? esc_html( $item['badge'] ) : 'Work';
	$icon  = isset( $item['icon'] ) ? esc_attr( $item['icon'] ) : 'fa-folder';
	return '<div class="ow-image-fallback" aria-hidden="true"><span class="ow-image-fallback__icon"><i class="fa ' . $icon . '"></i></span><span class="ow-image-fallback__label">' . $badge . '</span></div>';
}

/**
 * Output the custom favicon + apple-touch-icon links.
 *
 * Assets live in the child theme (assets/images) rather than
 * wp-includes/images, which is replaced on every WP core update.
 */
function site_child_output_favicon() {
	$favicon = get_stylesheet_directory_uri() . '/assets/images/favicon.ico';
	$apple   = get_stylesheet_directory_uri() . '/assets/images/apple-touch-icon.png';
	printf( '<link rel="icon" href="%s" type="image/x-icon">' . PHP_EOL, esc_url( $favicon ) );
	echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '" type="image/x-icon">' . PHP_EOL;
	echo '<link rel="apple-touch-icon" href="' . esc_url( $apple ) . '">' . PHP_EOL;
}
add_action( 'wp_head', 'site_child_output_favicon', 5 );

/**
 * Apply the saved colour theme (light/dark) BEFORE first paint.
 *
 * Runs at wp_head priority 0 — ahead of the theme stylesheets — so visitors
 * never see a flash of the wrong theme. The actual JS lives in
 * assets/js/theme-init.js (read inline to avoid an extra HTTP request and any
 * dependency on how WordPress might defer/defer other scripts).
 */
function site_child_theme_early_script() {
	$js_path = get_stylesheet_directory() . '/assets/js/theme-init.js';
	if ( file_exists( $js_path ) ) {
		echo '<script>' . file_get_contents( $js_path ) . '</script>' . PHP_EOL;
	}
}
add_action( 'wp_head', 'site_child_theme_early_script', 0 );

/**
 * Enqueue the dark-mode stylesheet (loaded last so it wins specificity ties).
 *
 * Light mode is the default and untouched; dark rules are scoped under
 * html[data-theme="dark"] (set by theme-init.js). Versioned with filemtime()
 * for cache busting during development.
 */
function site_child_enqueue_dark_assets() {
	$dark_css_path = get_stylesheet_directory() . '/assets/css/dark.css';

	wp_enqueue_style(
		'site-dark',
		get_stylesheet_directory_uri() . '/assets/css/dark.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $dark_css_path ) ? (string) filemtime( $dark_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_dark_assets', 30 );