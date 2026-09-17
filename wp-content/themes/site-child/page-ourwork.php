<?php
/**
 * Template Name: Our Work
 * Description: Impact hub for SITE Enterprise Promotion - hero, impact stats,
 *              focus areas with real images, programmes, and a closing CTA.
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
 * 1. Curated data - impact stats, focus areas and programmes.
 * ------------------------------------------------------------------------- */
$owk_stats = array(
	array( 'value' => '35,000+',  'label' => 'Men reached' ),
	array( 'value' => '100,000+', 'label' => 'Women & girls' ),
	array( 'value' => '51,000+',  'label' => 'Persons with disabilities' ),
	array( 'value' => '460+',      'label' => 'Refugees' ),
	array( 'value' => '20,000+',  'label' => 'Youth' ),
);

/*
 * The Focus Areas section is shared with the homepage and the About Us page.
 * It is rendered by template-parts/sections/focus-areas.php from the canonical
 * dataset in site_child_get_focus_areas(), so this page no longer keeps its own
 * copy of the array or its own card markup (the two had already drifted apart).
 */

$owk_programs = array(
	array(
		'icon'   => 'fa-briefcase',
		'title'  => 'Market Systems Development',
		'text'   => 'We strengthen local market systems so small enterprises can access finance, inputs and reliable buyers at scale.',
		'link'   => home_url( '/enterprise-development-and-value-chains/' ),
	),
	array(
		'icon'   => 'fa-users',
		'title'  => 'Community Led Livelihoods',
		'text'   => 'Working with community groups to design and own livelihood solutions that reduce vulnerability and build resilience.',
		'link'   => home_url( '/empowering-women-for-employment/' ),
	),
	array(
		'icon'   => 'fa-globe',
		'title'  => 'Climate-Smart Agriculture',
		'text'   => 'Helping households adopt climate smart practices that protect ecosystems while raising food security and incomes.',
		'link'   => home_url( '/climate-actions/' ),
	),
);
?>
<main id="primary" class="site-ourwork">

	<!-- HERO -->
	<section class="owk-hero" aria-labelledby="owk-hero-title">
		<div class="owk-container">
			<span class="owk-eyebrow">Our Work</span>
			<h1 id="owk-hero-title" class="owk-hero__title">Ideas into impact.</h1>
			<p class="owk-hero__lead">Explore the projects, research, stories and partnerships that turn skills and opportunity into lasting livelihoods across Kenya.</p>
		</div>
	</section>

	<!--
		IMPACT IN NUMBERS — one separate card per figure, matching the About Us
		page. The old markup wrapped all five figures in a single panel card;
		the card treatment is now applied per <li> in ourwork.css, so each number
		reads as its own card, exactly like `.au-stat` on About Us.

		The section now also carries the same "Our Reach / Impact in Numbers"
		head as About Us. This previously had no accessible name at all: it used
		aria-labelledby="owk-stats-title", but no element ever carried that id.
		The visible <h2> now provides that id, so the label and the visible text
		finally agree.
	-->
	<section class="owk-stats" aria-labelledby="owk-stats-title">
		<div class="owk-container">
			<div class="owk-section-head">
				<span class="owk-eyebrow">Our Reach</span>
				<h2 id="owk-stats-title" class="owk-section-head__title">Impact in Numbers</h2>
				<p class="owk-section-head__lead">Real lives transformed through enterprise, skills and inclusive markets.</p>
			</div>
			<ul class="owk-stats__grid" role="list">
				<?php foreach ( $owk_stats as $stat ) : ?>
					<li class="owk-stat">
						<span class="owk-stat__value"><?php echo esc_html( $stat['value'] ); ?></span>
						<span class="owk-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- FOCUS AREAS (shared component — identical to the homepage) -->
	<?php get_template_part( 'template-parts/sections/focus-areas' ); ?>

	<!-- PROGRAMMES -->
	<section class="owk-programs" aria-labelledby="owk-programs-title">
		<div class="owk-container">
			<div class="owk-section-head">
				<span class="owk-eyebrow">Other programmes</span>
				<h2 id="owk-programs-title" class="owk-section-head__title">More of our work</h2>
				<p class="owk-section-head__lead">Complementary programmes that deepen impact across markets, communities and the climate.</p>
			</div>
			<div class="owk-programs__grid">
				<?php foreach ( $owk_programs as $program ) : ?>
					<a class="owk-program-card" href="<?php echo esc_url( $program['link'] ); ?>">
						<span class="owk-program-card__icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $program['icon'] ); ?>"></i></span>
						<h3 class="owk-program-card__title"><?php echo esc_html( $program['title'] ); ?></h3>
						<p class="owk-program-card__text"><?php echo esc_html( $program['text'] ); ?></p>
						<span class="owk-program-card__link">
							Learn more
							<span aria-hidden="true">&#8594;</span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- CLOSING CTA -->
	<section class="owk-cta" aria-labelledby="owk-cta-title">
		<div class="owk-container">
			<div class="owk-cta__inner">
				<div class="owk-cta__text">
					<h2 id="owk-cta-title" class="owk-cta__title">Have a question about our work?</h2>
					<p class="owk-cta__lead">Whether you're interested in our programmes, research or a potential collaboration, we'd love to hear from you.</p>
				</div>
				<a class="owk-cta__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					Start a conversation
					<span aria-hidden="true">&#8594;</span>
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
