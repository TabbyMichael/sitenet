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

$owk_focus = array(
	array(
		'kicker'  => 'Youth & Skills',
		'title'   => 'Skilling Youth for Employment',
		'icon'    => 'fa-graduation-cap',
		'image'   => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
		'summary' => 'Market-led technical, vocational, entrepreneurship and mentorship support that helps young people move into dignified work.',
		'link'    => home_url( '/sample-page-2/' ),
	),
	array(
		'kicker'  => 'Inclusive Markets',
		'title'   => 'Enterprise Development & Value Chains',
		'icon'    => 'fa-line-chart',
		'image'   => content_url( 'uploads/2021/11/journeyofgrowth-850x567.jpg' ),
		'summary' => 'Business development and value-chain strengthening for entrepreneurs, MSMEs and producer groups seeking better markets.',
		'link'    => home_url( '/enterprise-development-and-value-chains/' ),
	),
	array(
		'kicker'  => 'Women & Inclusion',
		'title'   => 'Empowering Women for Employment',
		'icon'    => 'fa-female',
		'image'   => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ),
		'summary' => 'Pathways for women and marginalized groups to build income, leadership, resilience and stronger decision-making power.',
		'link'    => home_url( '/empowering-women-for-employment/' ),
	),
	array(
		'kicker'  => 'Resilient Communities',
		'title'   => 'Food Security & Climate Action',
		'icon'    => 'fa-leaf',
		'image'   => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
		'summary' => 'Climate-smart livelihood actions that improve food security, household incomes and community capacity to adapt.',
		'link'    => home_url( '/climate-actions/' ),
	),
);

$owk_programs = array(
	array(
		'icon'   => 'fa-briefcase',
		'title'  => 'Market Systems Development',
		'text'   => 'We strengthen local market systems so small enterprises can access finance, inputs and reliable buyers at scale.',
		'link'   => home_url( '/enterprise-development-and-value-chains/' ),
	),
	array(
		'icon'   => 'fa-users',
		'title'  => 'Community-Led Livelihoods',
		'text'   => 'Working with community groups to design and own livelihood solutions that reduce vulnerability and build resilience.',
		'link'   => home_url( '/empowering-women-for-employment/' ),
	),
	array(
		'icon'   => 'fa-globe',
		'title'  => 'Climate-Smart Agriculture',
		'text'   => 'Helping households adopt climate-smart practices that protect ecosystems while raising food security and incomes.',
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

	<!-- START STATS ROW -->
	<section class="owk-stats" aria-labelledby="owk-stats-title">
		<div class="owk-container">
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

	<!-- FOCUS AREAS -->
	<section class="owk-focus" id="owk-focus" aria-labelledby="owk-focus-title">
		<div class="owk-container">
			<div class="owk-section-head">
				<span class="owk-eyebrow">What we do</span>
				<h2 id="owk-focus-title" class="owk-section-head__title">Focus areas</h2>
				<p class="owk-section-head__lead">Our programmes connect skills, enterprise growth, gender inclusion and climate resilience so communities can build sustainable incomes.</p>
			</div>
			<div class="owk-focus__grid">
				<?php foreach ( $owk_focus as $area ) : ?>
					<article class="owk-focus-card" aria-label="<?php echo esc_attr( $area['title'] ); ?>">
						<a class="owk-focus-card__media" href="<?php echo esc_url( $area['link'] ); ?>" tabindex="-1" aria-hidden="true">
							<img src="<?php echo esc_url( $area['image'] ); ?>"
								alt="<?php echo esc_attr( $area['title'] ); ?>"
								loading="lazy"
								decoding="async"
								width="850"
								height="567" />
						</a>
						<div class="owk-focus-card__body">
							<span class="owk-focus-card__kicker"><?php echo esc_html( $area['kicker'] ); ?></span>
							<h3 class="owk-focus-card__title"><a href="<?php echo esc_url( $area['link'] ); ?>"><?php echo esc_html( $area['title'] ); ?></a></h3>
							<p class="owk-focus-card__text"><?php echo esc_html( $area['summary'] ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

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
