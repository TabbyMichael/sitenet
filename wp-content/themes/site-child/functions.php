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