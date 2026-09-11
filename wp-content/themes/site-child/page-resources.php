<?php
/**
 * Template Name: Resources
 * Description: Research, publications and learning hub for SITE Enterprise
 *				Promotion — hero, resource categories, featured publications,
 *				and a closing CTA.
 *
 * Uses real images from the media library and links to the live resource
 * landing pages.
 *
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* ---------------------------------------------------------------------------
 * 1. Curated resource categories — real images + live landing pages.
 * ------------------------------------------------------------------------- */
$res_categories = array(
	array(
		'title' => 'Case Studies',
		'desc'	=> 'In-depth looks at how our projects and partnerships create measurable change across Kenya.',
		'icon'	=> 'fa-book',
		'image' => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
		'link'	=> home_url( '/resource-hub/case-studys/' ),
	),
	array(
		'title' => 'Papers',
		'desc'	=> 'Research, surveys and reports from our work in enterprise development and value chains.',
		'icon'	=> 'fa-file-text-o',
		'image' => content_url( 'uploads/2021/11/journeyofgrowth-850x567.jpg' ),
		'link'	=> home_url( '/resource-hub/papers/' ),
	),
	array(
		'title' => 'Press Releases',
		'desc'	=> 'Official statements and media releases from SITE Enterprise Promotion.',
		'icon'	=> 'fa-bullhorn',
		'image' => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
		'link'	=> home_url( '/resource-hub/irrigation-and-drainage/' ),
	),
	array(
		'title' => 'Video Gallery',
		'desc'	=> 'Visual stories, documentaries and event highlights from the field.',
		'icon'	=> 'fa-play-circle',
		'image' => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ),
		'link'	=> home_url( '/galleries/gallery-full-width-2/' ),
	),
);

/* ---------------------------------------------------------------------------
 * 2. Featured publications — real PDF titles + live landing pages.
 * ------------------------------------------------------------------------- */
$res_publications = array(
	array(
		'title' => 'Aspirations and gains of small-scale camel owners in Isiolo County, Kenya',
		'meta'	=> 'Research Paper &mdash; 2023',
		'link'	=> home_url( '/resource-hub/papers/' ),
	),
	array(
		'title' => 'Harbole watering point: community-led rangeland management',
		'meta'	=> 'Case Study &mdash; 2022',
		'link'	=> home_url( '/resource-hub/case-studys/' ),
	),
	array(
		'title' => 'Policy brief: working spaces in Mathare informal settlement',
		'meta'	=> 'Policy Brief &mdash; 2023',
		'link'	=> home_url( '/resource-hub/papers/' ),
	),
);
?>
<main id="primary" class="site-resources">

	<!-- HERO -->
	<section class="res-hero" aria-labelledby="res-hero-title">
		<div class="res-container">
			<span class="res-eyebrow">Resources</span>
			<h1 id="res-hero-title" class="res-hero__title">Research, publications and learning.</h1>
			<p class="res-hero__lead">Explore our growing library of case studies, research papers, press releases and video &mdash; the evidence behind SITE Enterprise Promotion's work across Kenya.</p>
		</div>
	</section>

	<!-- RESOURCE CATEGORIES -->
	<section class="res-categories" aria-labelledby="res-cats-title">
		<div class="res-container">
			<div class="res-section-head">
				<span class="res-eyebrow">Browse by type</span>
				<h2 id="res-cats-title" class="res-section-head__title">Resource categories</h2>
				<p class="res-section-head__lead">Four curated collections to help you find the research, stories and media that matter to your work.</p>
			</div>
			<div class="res-categories__grid">
				<?php foreach ( $res_categories as $cat ) : ?>
					<a class="res-cat-card" href="<?php echo esc_url( $cat['link'] ); ?>">
						<div class="res-cat-card__media">
							<img src="<?php echo esc_url( $cat['image'] ); ?>" alt="<?php echo esc_attr( $cat['title'] ); ?>" loading="lazy" decoding="async" width="850" height="567" />
							<span class="res-cat-card__overlay" aria-hidden="true"></span>
							<span class="res-cat-card__icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $cat['icon'] ); ?>"></i></span>
						</div>
						<div class="res-cat-card__content">
							<h3 class="res-cat-card__title"><?php echo esc_html( $cat['title'] ); ?></h3>
							<p class="res-cat-card__text"><?php echo esc_html( $cat['desc'] ); ?></p>
							<span class="res-cat-card__link">
								Explore
								<span class="res-pub-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

	<!-- FEATURED PUBLICATIONS -->
	<section class="res-featured" aria-labelledby="res-featured-title">
		<div class="res-container">
			<div class="res-section-head">
				<span class="res-eyebrow">Latest research</span>
				<h2 id="res-featured-title" class="res-section-head__title">Featured publications</h2>
				<p class="res-section-head__lead">A selection of our most recent papers, case studies and policy briefs.</p>
			</div>
			<div class="res-featured__grid">
				<?php foreach ( $res_publications as $pub ) : ?>
					<article class="res-pub-card">
						<div class="res-pub-card__media">
							<span class="res-pub-card__badge">Publication</span>
							<svg viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width:100%;height:100%;display:block;">
								<rect width="300" height="400" fill="url(#res-pub-grad)"/>
								<defs>
									<linearGradient id="res-pub-grad" x1="0%" y1="0%" x2="100%" y2="100%">
										<stop offset="0%" style="stop-color:#01622c;stop-opacity:1" />
										<stop offset="100%" style="stop-color:#014720;stop-opacity:1" />
									</linearGradient>
								</defs>
								<rect x="30" y="30" width="240" height="340" rx="4" fill="none" stroke="rgba(249,200,2,0.3)" stroke-width="1.5"/>
								<line x1="60" y1="80" x2="240" y2="80" stroke="rgba(255,255,255,0.4)" stroke-width="1"/>
								<line x1="60" y1="100" x2="220" y2="100" stroke="rgba(255,255,255,0.25)" stroke-width="1"/>
								<line x1="60" y1="120" x2="230" y2="120" stroke="rgba(255,255,255,0.25)" stroke-width="1"/>
								<line x1="60" y1="140" x2="180" y2="140" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
								<rect x="100" y="280" width="100" height="30" rx="15" fill="#f9c802"/>
								<text x="150" y="300" text-anchor="middle" fill="#1a1500" font-family="sans-serif" font-size="11" font-weight="700">PDF</text>
							</svg>
						</div>
						<div class="res-pub-card__content">
							<h3 class="res-pub-card__title"><a href="<?php echo esc_url( $pub['link'] ); ?>"><?php echo esc_html( $pub['title'] ); ?></a></h3>
							<span class="res-pub-card__meta"><?php echo wp_kses_post( $pub['meta'] ); ?></span>
							<a href="<?php echo esc_url( $pub['link'] ); ?>" class="res-pub-card__cta">
								Read publication
								<span class="res-pub-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- CLOSING CTA -->
	<section class="res-contact-cta" aria-labelledby="res-cta-title">
		<div class="res-container">
			<div class="res-contact-cta__inner">
				<div class="res-contact-cta__text">
					<span class="res-eyebrow">Get in touch</span>
					<h2 id="res-cta-title" class="res-contact-cta__title">Can't find what you're looking for?</h2>
					<p class="res-contact-cta__lead">Whether you need a specific report, want to collaborate on research, or have a question about our work, we'd love to hear from you.</p>
				</div>
				<a class="res-contact-cta__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					Contact us
					<span class="res-pub-card__cta-arrow" aria-hidden="true"><?php echo site_child_svg_arrow( 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();