<?php
/**
 * Template Name: About Us
 * Description: About SITE Enterprise Promotion - hero, mission/vision/values,
 *				our story timeline, impact stats, focus areas, and closing CTA.
 *
 * Locked boundaries: header (get_header) and footer (get_footer) are untouched.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* --------------------------------------------------------------------------- *\
 * 1. Curated content — mission/vision/values, story timeline, impact stats,
 *	  focus areas.
\* --------------------------------------------------------------------------- */
$au_mvv = array(
	array(
		'icon'	=> 'fa-bullseye',
		'title' => 'Mission',
		'text'	=> 'To reduce poverty by strengthening livelihoods, competitive markets, and community-owned enterprises across Kenya.',
	),
	array(
		'icon'	=> 'fa-eye',
		'title' => 'Vision',
		'text'	=> 'Better quality of life for people and communities through economic dignity and sustainable enterprise.',
	),
	array(
		'icon'	=> 'fa-gem',
		'title' => 'Values',
		'text'	=> 'Inclusion, integrity, innovation, and impact — guiding every partnership, program, and investment we make.',
	),
);

$au_timeline = array(
	array(
		'year'	=> '1996',
		'title' => 'SITE is founded',
		'text'	=> 'Established as a Kenyan not-for-profit to promote enterprise-led development and dignified livelihoods.',
	),
	array(
		'year'	=> '2005',
		'title' => 'Expanding reach across Kenya',
		'text'	=> 'Scaled programs to serve women, youth, and marginalized entrepreneurs in multiple counties.',
	),
	array(
		'year'	=> '2012',
		'title' => 'Market-linked value chains',
		'text'	=> 'Launched enterprise development and value-chain programs connecting producers to sustainable markets.',
	),
	array(
		'year'	=> '2018',
		'title' => '100,000 lives reached',
		'text'	=> 'Crossed a major milestone — over 100,000 women, youth, and persons with disabilities supported.',
	),
	array(
		'year'	=> 'Today',
		'title' => 'Deepening community impact',
		'text'	=> 'Continuing to build resilient livelihoods, inclusive markets, and climate-smart communities nationwide.',
	),
);

$au_stats = array(
	array( 'value' => '35,000+',  'label' => 'Men reached' ),
	array( 'value' => '100,000+', 'label' => 'Women & girls' ),
	array( 'value' => '51,000+',  'label' => 'Persons with disabilities' ),
	array( 'value' => '460+',	  'label' => 'Refugees' ),
	array( 'value' => '20,000+',  'label' => 'Youth' ),
);

$au_focus = array(
	array(
		'kicker'  => 'Youth & Skills',
		'title'	  => 'Skilling Youth for Employment',
		'image'	  => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
		'summary' => 'Market-led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
		'link'	  => home_url( '/sample-page-2/' ),
	),
	array(
		'kicker'  => 'Inclusive Markets',
		'title'	  => 'Enterprise Development & Value Chains',
		'image'	  => content_url( 'uploads/2021/11/journeyofgrowth-850x567.jpg' ),
		'summary' => 'Business development and value-chain strengthening for entrepreneurs, MSMEs, and producer groups seeking better markets and sustainable growth.',
		'link'	  => home_url( '/enterprise-development-and-value-chains/' ),
	),
	array(
		'kicker'  => 'Women & Inclusion',
		'title'	  => 'Empowering Women for Employment',
		'image'	  => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ),
		'summary' => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision-making power.',
		'link'	  => home_url( '/empowering-women-for-employment/' ),
	),
	array(
		'kicker'  => 'Resilient Communities',
		'title'	  => 'Food Security & Climate Action',
		'image'	  => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
		'summary' => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
		'link'	  => home_url( '/climate-actions/' ),
	),
);
?>

<main id="primary" class="site-main site-about-us">

	<!-- HERO -->
	<section class="au-hero" aria-labelledby="au-hero-title">
		<div class="au-container">
			<div class="au-hero__inner">
				<h1 id="au-hero-title" class="au-hero__title">About SITE Enterprise Promotion</h1>
				<p class="au-hero__lead">
					SITE is a Kenyan not-for-profit development organization working with communities, entrepreneurs, women, youth, and marginalized groups to turn skills into sustainable incomes and dignified work. Since 1996, we have championed enterprise-led development as the pathway out of poverty.
				</p>
			</div>
		</div>
	</section>

	<!-- MISSION / VISION / VALUES -->
	<section class="au-mvv" aria-labelledby="au-mvv-title">
		<div class="au-container">
			<div class="au-section-head au-section-head--center">
				<span class="au-eyebrow">Who We Are</span>
				<h2 id="au-mvv-title" class="au-section-title">Mission, Vision &amp; Values</h2>
				<p class="au-section-lead">
					Three pillars guide everything we do — from the communities we serve to the partnerships we build.
				</p>
			</div>

			<div class="au-mvv__grid">
				<?php foreach ( $au_mvv as $card ) : ?>
					<div class="au-mvv-card">
						<span class="au-mvv-card__icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $card['icon'] ); ?>"></i></span>
						<h3 class="au-mvv-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
						<p class="au-mvv-card__text"><?php echo esc_html( $card['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- OUR STORY / TIMELINE -->
	<section class="au-story" aria-labelledby="au-story-title">
		<div class="au-container">
			<div class="au-section-head au-section-head--center">
				<span class="au-eyebrow">Our Journey</span>
				<h2 id="au-story-title" class="au-section-title">Our Story</h2>
				<p class="au-section-lead">
					From a grassroots initiative in 1996 to a nationwide force for enterprise-led development.
				</p>
			</div>

			<div class="au-timeline">
				<?php foreach ( $au_timeline as $item ) : ?>
					<div class="au-timeline__item">
						<span class="au-timeline__dot" aria-hidden="true"></span>
						<span class="au-timeline__year"><?php echo esc_html( $item['year'] ); ?></span>
						<h3 class="au-timeline__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="au-timeline__text"><?php echo esc_html( $item['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- IMPACT STATS -->
	<section class="au-stats" aria-labelledby="au-stats-title">
		<div class="au-container">
			<div class="au-section-head au-section-head--center">
				<span class="au-eyebrow">Our Reach</span>
				<h2 id="au-stats-title" class="au-section-title">Impact in Numbers</h2>
				<p class="au-section-lead">
					Real lives transformed through enterprise, skills, and inclusive markets.
				</p>
			</div>

			<div class="au-stats__grid">
				<?php foreach ( $au_stats as $stat ) : ?>
					<div class="au-stat">
						<span class="au-stat__number"><?php echo esc_html( $stat['value'] ); ?></span>
						<span class="au-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- FOCUS AREAS -->
	<section class="au-focus" aria-labelledby="au-focus-title">
		<div class="au-container">
			<div class="au-section-head au-section-head--center">
				<span class="au-eyebrow">Strategic Focus</span>
				<h2 id="au-focus-title" class="au-section-title">Focus Areas</h2>
				<p class="au-section-lead">
					Our programs connect skills, enterprise growth, gender inclusion, and climate resilience so communities can solve local challenges and build sustainable incomes.
				</p>
			</div>

			<div class="au-focus__grid">
				<?php foreach ( $au_focus as $area ) : ?>
					<article class="au-focus-card" aria-label="<?php echo esc_attr( $area['title'] ); ?>">
						<div class="au-focus-card__media">
							<img src="<?php echo esc_url( $area['image'] ); ?>"
								sizes="(max-width: 767px) 100vw, 50vw"
								width="850"
								height="567"
								alt="<?php echo esc_attr( wp_strip_all_tags( $area['title'] ) ); ?>"
								loading="lazy" decoding="async">
						</div>
						<div class="au-focus-card__body">
							<span class="au-focus-card__kicker"><?php echo esc_html( $area['kicker'] ); ?></span>
							<h3 class="au-focus-card__title"><?php echo wp_kses_post( $area['title'] ); ?></h3>
							<p class="au-focus-card__text"><?php echo esc_html( $area['summary'] ); ?></p>
							<a href="<?php echo esc_url( $area['link'] ); ?>" class="au-focus-card__link">
								Explore Focus Area <i class="fa fa-angle-right" aria-hidden="true"></i>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- CLOSING CTA -->
	<section class="au-cta" aria-labelledby="au-cta-title">
		<div class="au-container">
			<div class="au-cta__inner">
				<div class="au-cta__text">
					<h2 id="au-cta-title" class="au-cta__title">Partner with SITE</h2>
					<p class="au-cta__lead">Join us in building resilient livelihoods and inclusive markets across Kenya. Together, we can create lasting change.</p>
				</div>
				<a class="au-cta__button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					Get in touch
					<i class="fa fa-angle-right" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();