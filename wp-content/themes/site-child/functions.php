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
 * Loaded only on the Our Work page (slug: ourwork). CSS is scoped to
 * .site-ourwork and does not affect the locked navbar, header, or footer.
 * Loaded before dark.css so both light and dark palettes are self-contained.
 */
function site_child_enqueue_ourwork_assets() {
	if ( ! is_page( 'ourwork' ) ) {
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
	if ( ! is_home() ) {
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
 * Enqueue the Stories archive (site_story) assets.
 *
 * Loaded only on the stories CPT archive (/stories/). CSS + vanilla JS are
 * scoped to .site-stories-archive and do not affect the locked navbar, header,
 * footer, or the blog index (which keeps stories.css). Loaded before dark.css
 * (priority 30) so both light and dark palettes stay self-contained.
 */
function site_child_enqueue_stories_archive_assets() {
	if ( ! is_post_type_archive( 'site_story' ) ) {
		return;
	}

	$sa_css_path = get_stylesheet_directory() . '/assets/css/stories-archive.css';
	$sa_js_path  = get_stylesheet_directory() . '/assets/js/stories-archive.js';

	wp_enqueue_style(
		'site-stories-archive',
		get_stylesheet_directory_uri() . '/assets/css/stories-archive.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $sa_css_path ) ? (string) filemtime( $sa_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-stories-archive',
		get_stylesheet_directory_uri() . '/assets/js/stories-archive.js',
		array(),
		file_exists( $sa_js_path ) ? (string) filemtime( $sa_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_stories_archive_assets', 26 );

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
	$ss_js_path  = get_stylesheet_directory() . '/assets/js/story-single.js';

	wp_enqueue_style(
		'site-story-single',
		get_stylesheet_directory_uri() . '/assets/css/story-single.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $ss_css_path ) ? (string) filemtime( $ss_css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'site-story-single',
		get_stylesheet_directory_uri() . '/assets/js/story-single.js',
		array(),
		file_exists( $ss_js_path ) ? (string) filemtime( $ss_js_path ) : '1.0.0',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_story_single_assets', 25 );

/**
 * Build the shared "Donors & Promoters" rotating logo loop markup.
 *
 * Four logo-only cards (no text) that rotate continuously in a seamless
 * loop: the set is rendered twice, the second copy aria-hidden, and a CSS
 * keyframe animation translates the track by exactly one set width. The
 * animation pauses on hover and is fully disabled for visitors who prefer
 * reduced motion (a plain swipeable row remains). Logo images live in
 * assets/images/partners/. Used by the the_content filter, the site_story
 * template, and available via template-parts/donors.php.
 *
 * @return string HTML for the donors loop section (fully escaped).
 */
function site_child_get_donors_html() {
	$sd_logos = array(
		array( 'file' => 'partners/2-3.png',          'w' => 200, 'h' => 188 ),
		array( 'file' => 'partners/ilo.png',          'w' => 820, 'h' => 729 ),
		array( 'file' => 'partners/12.png',           'w' => 150, 'h' => 138 ),
		array( 'file' => 'partners/15.png',           'w' => 150, 'h' => 141 ),
		array( 'file' => 'partners/5-2-100x100.jpg',  'w' => 100, 'h' => 100 ),
		array( 'file' => 'partners/13.jpg',           'w' => 150, 'h' => 141 ),
		array( 'file' => 'partners/10.jpg',           'w' => 150, 'h' => 141 ),
		array( 'file' => 'partners/11.jpg',           'w' => 150, 'h' => 141 ),
		array( 'file' => 'partners/5-100x100.png',    'w' => 100, 'h' => 100 ),
		array( 'file' => 'partners/8.png',            'w' => 150, 'h' => 57 ),
		array( 'file' => 'partners/9-1.png',          'w' => 150, 'h' => 90 ),
		array( 'file' => 'partners/9-2.png',          'w' => 200, 'h' => 188 ),
		array( 'file' => 'partners/eu.jpg',           'w' => 256, 'h' => 238 ),
	);

	$sd_cards = '';
	foreach ( $sd_logos as $sd_logo ) {
		$sd_path = get_theme_file_path( 'assets/images/' . $sd_logo['file'] );
		if ( ! file_exists( $sd_path ) ) {
			continue;
		}
		$sd_url    = get_theme_file_uri( 'assets/images/' . $sd_logo['file'] );
		$sd_cards .= '<li class="sd-card"><img class="sd-logo" src="' . esc_url( $sd_url ) . '" alt="" width="' . (int) $sd_logo['w'] . '" height="' . (int) $sd_logo['h'] . '" loading="lazy" decoding="async"></li>';
	}

	if ( '' === $sd_cards ) {
		return '';
	}

	// Second, aria-hidden copy of the set makes the loop seamless.
	$sd_clones = str_replace( 'class="sd-card"', 'class="sd-card sd-clone" aria-hidden="true"', $sd_cards );

	return '<section class="site-donors" aria-labelledby="sd-title">'
		. '<h2 class="sd-title" id="sd-title">' . esc_html__( 'Donors & Promoters', 'site-child' ) . '</h2>'
		. '<div class="sd-viewport">'
		. '<ul class="sd-track">' . $sd_cards . $sd_clones . '</ul>'
		. '</div>'
		. '</section>';
}

/**
 * Append the donors section to the main content on the four programme /
 * resource revamp pages and on single posts.
 *
 * Runs late on the_content so builders and shortcodes finish first. Scoped
 * with is_main_query() (excludes widgets and secondary queries) rather than
 * in_the_loop() because the revamp shells call the_content() outside a formal
 * loop. Feed output is never touched (feeds don't run this template), and
 * empty content is left empty.
 *
 * @param string $content Post content.
 * @return string Content with the donors section appended.
 */
function site_child_append_donors_to_content( $content ) {
	if ( is_admin() || wp_doing_ajax() || ! is_main_query() ) {
		return $content;
	}

	$sd_pages = array(
		'climate-actions',
		'sample-page-2',
		'enterprise-development-and-value-chains',
		'empowering-women-for-employment',
	);

	if ( ! ( is_singular( 'post' ) || is_page( $sd_pages ) ) ) {
		return $content;
	}

	if ( '' === trim( (string) $content ) ) {
		return $content;
	}

	return $content . site_child_get_donors_html();
}
add_filter( 'the_content', 'site_child_append_donors_to_content', 20 );

/**
 * Donor / partner logo → stories map for the homepage carousel.
 *
 * Keys are logo FILENAMES from assets/images/partners/ (basename only, e.g.
 * 'ilo.png'). Values are arrays of story SLUGS (site_story) in which
 * that donor or partner features. Clicking a card routes the visitor to a
 * story chosen at random from its list — a different one each click.
 *
 * To associate a logo with stories, add a line like:
 *     'ilo.png' => array(
 *         'camel-milk-child-nutrition-fafi-bare',
 *         'a-young-small-scale-trader-with-big-dreams',
 *     ),
 * Logos without an entry fall back to a random published story (see
 * site_child_get_all_story_urls()), so every card is clickable.
 *
 * Developers can also extend this via the site_child_donor_story_map filter.
 *
 * @return array Map of logo basename => array of story slugs.
 */
function site_child_get_donor_story_map() {
	$map = array(
		// 'ilo.png' => array(
		// 	'camel-milk-child-nutrition-fafi-bare',
		// 	'a-young-small-scale-trader-with-big-dreams',
		// ),
	);

	return apply_filters( 'site_child_donor_story_map', $map );
}

/**
 * Resolve a logo's mapped story slugs to published permalinks.
 *
 * @param string $basename Logo filename, e.g. 'ilo.png'.
 * @return string[] Permalink URLs, or an empty array when unmapped.
 */
function site_child_resolve_donor_stories( $basename ) {
	static $sd_resolved = array();

	if ( isset( $sd_resolved[ $basename ] ) ) {
		return $sd_resolved[ $basename ];
	}

	$map  = site_child_get_donor_story_map();
	$urls = array();

	if ( ! empty( $map[ $basename ] ) && is_array( $map[ $basename ] ) ) {
		foreach ( $map[ $basename ] as $sd_slug ) {
			$sd_posts = get_posts(
				array(
					'name'           => sanitize_title( $sd_slug ),
					'post_type'      => array( 'site_story' ),
					'post_status'    => 'publish',
					'numberposts'    => 1,
					'no_found_rows'  => true,
				)
			);
			if ( $sd_posts ) {
				$urls[] = get_permalink( $sd_posts[0] );
			}
		}
		$urls = array_values( array_unique( $urls ) );
	}

	$sd_resolved[ $basename ] = $urls;
	return $urls;
}

/**
 * Permalinks of every published story (site_story only).
 *
 * Used as the fallback target pool for logo cards that have no entry in the
 * donor map, so every card routes somewhere meaningful.
 *
 * @return string[] Permalink URLs.
 */
function site_child_get_all_story_urls() {
	static $sd_all = null;

	if ( null !== $sd_all ) {
		return $sd_all;
	}

	$sd_all = array();
	$sd_posts = get_posts(
		array(
			'post_type'      => array( 'site_story' ),
			'post_status'    => 'publish',
			'numberposts'    => 100,
			'no_found_rows'  => true,
			'fields'         => 'ids',
		)
	);
	foreach ( $sd_posts as $sd_id ) {
		$sd_all[] = get_permalink( $sd_id );
	}

	return $sd_all;
}

/**
 * Enqueue the shared donors section assets.
 *
 * Loaded ONLY on the pages/posts listed in site_child_append_donors_to_content
 * plus single site_story pages. Styling: assets/css/donors.css — the rotating
 * loop is pure CSS (no JavaScript needed). Registered below priority 30 so
 * dark.css stays last.
 */
function site_child_enqueue_donors_assets() {
	$sd_pages = array(
		'climate-actions',
		'sample-page-2',
		'enterprise-development-and-value-chains',
		'empowering-women-for-employment',
	);

	if ( ! ( is_singular( 'post' ) || is_singular( 'site_story' ) || is_page( $sd_pages ) ) ) {
		return;
	}

	$sd_css_path = get_stylesheet_directory() . '/assets/css/donors.css';

	wp_enqueue_style(
		'site-donors',
		get_stylesheet_directory_uri() . '/assets/css/donors.css',
		array( 'site-child-style', 'site-header', 'site-footer' ),
		file_exists( $sd_css_path ) ? (string) filemtime( $sd_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_donors_assets', 25 );

/**
 * Render the homepage location map on the Contact Us and Partnership pages.
 *
 * WHY it is done here rather than in a template:
 * /contact-us/ (ID 227) and /make-an-appointment/ (ID 5268) are legacy
 * SiteOrigin Panels pages. Their bodies live in the DATABASE — post_content
 * begins <div class="panel-layout"> — so there is no markup to edit in PHP,
 * and rewriting them would mean migrating live builder content. Creating a
 * page-{slug}.php override is also fragile on this install, because plugin
 * template loaders (Elementor's PageTemplates module hooks template_include
 * at priority 11) can take template resolution away from the child theme.
 * A hook is immune to that, so it works no matter which template wins.
 *
 * WHY the `get_footer` action:
 * The parent theme's page.php renders the builder content via the_content(),
 * closes <div class="content">, then calls get_footer(). The `get_footer`
 * action fires before footer.php is loaded, and this child theme's footer.php
 * only closes <div class="layout-boxed"> at its very end. The map is therefore
 * printed as a SIBLING of .content — outside .container — which reproduces the
 * edge-to-edge width it has on the homepage exactly.
 *
 * No new CSS is required: the partial's light-mode rules live in style.css and
 * its dark-mode rule in dark.css, both of which already load site-wide. The
 * partial is reused verbatim, so the map stays in sync with the homepage.
 * To remove the map from a page, delete its slug from the is_page() list.
 *
 * (get_footer() also passes the footer template name; it is unused here.)
 */
function site_child_maybe_render_location_map() {
	if ( ! is_page( array( 'contact-us', 'make-an-appointment' ) ) ) {
		return;
	}

	get_template_part( 'template-parts/homepage/location-map' );
}
add_action( 'get_footer', 'site_child_maybe_render_location_map', 10 );

/**
 * Resolve the canonical URL of a child page under the Resources hub.
 *
 * WHY: the "Resources" hub (post 921) has the slug `services`, so its children
 * live at /services/papers/, /services/case-studys/ and
 * /services/irrigation-and-drainage/. Templates previously hardcoded
 * /resources/... and /resource-hub/... — neither prefix exists, so every one of
 * those links returned a 404. Resolving the permalink from the page itself means
 * the links follow the hub if its slug or the page hierarchy ever changes.
 *
 * Falls back to the literal path so a link is never rendered empty.
 *
 * @param string $slug Slug of the child page, e.g. 'papers'.
 * @return string Absolute permalink, or the fallback path when not found.
 */
function site_child_resource_hub_url( $slug ) {
	static $rv_urls = array();

	$rv_slug = sanitize_title( $slug );
	if ( ! $rv_slug ) {
		return home_url( '/services/' );
	}

	if ( isset( $rv_urls[ $rv_slug ] ) ) {
		return $rv_urls[ $rv_slug ];
	}

	$rv_page = get_page_by_path( 'services/' . $rv_slug, OBJECT, 'page' );
	if ( ! $rv_page ) {
		$rv_page = get_page_by_path( $rv_slug, OBJECT, 'page' );
	}

	if ( $rv_page && 'publish' === get_post_status( $rv_page ) ) {
		$rv_urls[ $rv_slug ] = get_permalink( $rv_page );
	} else {
		$rv_urls[ $rv_slug ] = home_url( '/services/' . $rv_slug . '/' );
	}

	return $rv_urls[ $rv_slug ];
}

/**
 * Human-readable size of a file referenced by a site uploads URL.
 *
 * WHY: the Papers page offers PDFs as free downloads. Showing each file's size
 * lets a visitor judge the download before starting it, which matters most on
 * metered mobile data. These legacy PDFs are referenced by literal uploads
 * paths rather than attachment IDs, so the size is read from disk.
 *
 * WHY wp_get_upload_dir(): unlike wp_upload_dir() it never attempts to create
 * the uploads directory, so rendering a page stays free of write side effects.
 *
 * Compares URL *paths* rather than whole URLs because the stored uploads baseurl
 * may be an absolute hostname while the template supplies root-relative links.
 *
 * Returns '' when the URL is outside the uploads directory or the file cannot
 * be read, so callers omit the size rather than print a misleading "0 bytes".
 *
 * @param string $url Absolute or site-relative uploads URL.
 * @return string Formatted size such as "99 KB", or '' when unavailable.
 */
function site_child_upload_size( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return '';
	}

	$url_path = wp_parse_url( $url, PHP_URL_PATH );
	if ( ! $url_path ) {
		return '';
	}

	$uploads     = wp_get_upload_dir();
	$uploads_url = wp_parse_url( $uploads['baseurl'], PHP_URL_PATH );

	if ( ! $uploads_url || 0 !== strpos( $url_path, $uploads_url ) ) {
		return '';
	}

	$relative = ltrim( substr( $url_path, strlen( $uploads_url ) ), '/' );
	if ( '' === $relative ) {
		return '';
	}

	$absolute = trailingslashit( $uploads['basedir'] ) . $relative;
	if ( ! is_readable( $absolute ) ) {
		return '';
	}

	$bytes = filesize( $absolute );
	if ( ! $bytes ) {
		return '';
	}

	return size_format( $bytes, 0 );
}


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
 * Base URI for the child-theme Focus Areas imagery folder.
 *
 * The folder name contains a space ("assets/images/focus area/"), so it is
 * percent-encoded exactly once here. Centralising the rule keeps every template
 * that renders the Focus Areas section consistent: esc_url() encodes a literal
 * space in `src`, while esc_attr() (used for the `srcset` candidate list) would
 * leave it raw and split the list on it.
 *
 * @return string Trailing-slash base URI.
 */
function site_child_focus_area_image_uri() {
	return get_stylesheet_directory_uri() . '/assets/images/' . rawurlencode( 'focus area' ) . '/';
}

/**
 * Canonical Focus Areas dataset.
 *
 * Single source of truth shared by the homepage, the About Us page and both
 * Our Work templates, so the four cards can never drift apart in image, copy,
 * order or destination again. Imagery lives in the child theme
 * (assets/images/focus area/) rather than the Media Library, so it travels with
 * the code and stays version-controlled.
 *
 * @return array List of focus-area definitions.
 */
function site_child_get_focus_areas() {
	$image_uri = site_child_focus_area_image_uri();

	return array(
		array(
			'title'     => 'Skilling Youth for Employment',
			'kicker'    => 'Youth & Skills',
			'icon'      => 'fa-graduation-cap',
			'image'     => $image_uri . 'skilling-youth.jpeg',
			'image_alt' => 'SITE trainees in blue overalls, lab coats and yellow safety helmets with trainers after a youth skills session',
			'image_w'   => 1280,
			'image_h'   => 960,
			'summary'   => 'Market led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
			'link'      => home_url( '/sample-page-2/' ),
		),
		array(
			'title'     => 'Enterprise Development and Value Chains',
			'kicker'    => 'Inclusive Markets',
			'icon'      => 'fa-line-chart',
			'image'     => $image_uri . 'Capture23-768x330.jpg',
			'image_alt' => 'Hands pouring milk from a stainless steel vessel into processing machinery',
			'image_w'   => 768,
			'image_h'   => 330,
			'summary'   => 'Business development and value chain strengthening for entrepreneurs, MSMEs, and producer groups seeking better markets and sustainable growth.',
			'link'      => home_url( '/enterprise-development-and-value-chains/' ),
		),
		array(
			'title'     => 'Empowering Women for Employment',
			'kicker'    => 'Women & Inclusion',
			'icon'      => 'fa-female',
			'image'     => $image_uri . 'women-empowerment.jpg',
			'image_alt' => 'Two women in hijabs and hairnets filling plastic bottles from funnels at a production table',
			'image_w'   => 2048,
			'image_h'   => 1536,
			'summary'   => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision making power.',
			'link'      => home_url( '/empowering-women-for-employment/' ),
		),
		array(
			'title'     => 'Food Security & Climate Action',
			'kicker'    => 'Resilient Communities',
			'icon'      => 'fa-leaf',
			'image'     => $image_uri . 'Gallery-3b-800x730.jpg',
			'image_alt' => 'Women sorting grains and beans into buckets during an outdoor community agriculture meeting',
			'image_w'   => 800,
			'image_h'   => 730,
			'summary'   => 'Climate smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
			'link'      => home_url( '/climate-actions/' ),
		),
	);
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

/**
 * Enqueue the shared Focus Areas responsive stylesheet.
 *
 * The Focus Areas cards are rendered by one shared part
 * (template-parts/sections/focus-areas.php) on the homepage, on the About Us
 * page and on the Our Work pages. Its base styling is already global (style.css,
 * scoped to .site-focus-areas-section) and its dark styling is global (dark.css,
 * also scoped), but its *responsive* overrides live in homepage-responsive.css,
 * which is front-page only. This loads the focus-area-specific equivalent on the
 * other pages that render the section, so the cards break down identically at
 * every breakpoint instead of snapping back to desktop sizes.
 *
 * Guarded: front page, About Us template, the Our Work page (slug "ourwork") and
 * the Our Work template. Versioned with filemtime() for cache busting during
 * development. Registered below priority 30 so dark.css still loads last.
 */
function site_child_enqueue_focus_areas_assets() {
	$renders_focus_areas = is_front_page()
		|| is_page_template( 'page-about-us.php' )
		|| is_page_template( 'page-our-work.php' )
		|| is_page( 'ourwork' );

	if ( ! $renders_focus_areas ) {
		return;
	}

	$focus_css_path = get_stylesheet_directory() . '/assets/css/focus-areas.css';

	wp_enqueue_style(
		'site-focus-areas',
		get_stylesheet_directory_uri() . '/assets/css/focus-areas.css',
		array( 'site-child-style' ),
		file_exists( $focus_css_path ) ? (string) filemtime( $focus_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'site_child_enqueue_focus_areas_assets', 26 );