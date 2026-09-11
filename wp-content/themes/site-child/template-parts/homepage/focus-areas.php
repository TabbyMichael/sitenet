<?php
/**
 * Homepage Focus Areas Section
 * 
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$focus_areas = array(
	array(
		'title'        => 'Skilling Youth for Employment',
		'kicker'       => 'Youth & Skills',
		'icon'         => 'fa-graduation-cap',
		'image'        => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ),
		'image_srcset' => content_url( 'uploads/2021/11/Gallery-1-850x567.jpg' ) . ' 850w, ' . content_url( 'uploads/2021/11/Gallery-1-1536x1025.jpg' ) . ' 1536w',
		'summary'      => 'Market-led technical, vocational, entrepreneurship, and mentorship support that helps young people transition from training into dignified work.',
		'link'         => home_url( '/sample-page-2/' ),
	),
	array(
		'title'        => 'Enterprise Development and Value Chains',
		'kicker'       => 'Inclusive Markets',
		'icon'         => 'fa-line-chart',
		'image'        => content_url( 'uploads/2021/11/wrm-848x450.png' ),
		'image_srcset' => '',
		'summary'      => 'Business development and value-chain strengthening for entrepreneurs, MSMEs, and producer groups seeking better markets and sustainable growth.',
		'link'         => home_url( '/enterprise-development-and-value-chains/' ),
	),
	array(
		'title'        => 'Empowering Women for Employment',
		'kicker'       => 'Women & Inclusion',
		'icon'         => 'fa-female',
		'image'        => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ),
		'image_srcset' => content_url( 'uploads/2021/11/bilatha-850x567.jpg' ) . ' 850w, ' . content_url( 'uploads/2021/11/bilatha-1024x647.jpg' ) . ' 1024w',
		'summary'      => 'Practical pathways for women and marginalized groups to build income, leadership, resilience, and stronger decision-making power.',
		'link'         => home_url( '/empowering-women-for-employment/' ),
	),
	array(
		'title'        => 'Food Security & Climate Action',
		'kicker'       => 'Resilient Communities',
		'icon'         => 'fa-leaf',
		'image'        => content_url( 'uploads/2021/11/harbole-water-850x567.jpg' ),
		'image_srcset' => '',
		'summary'      => 'Climate-smart livelihood actions that improve food security, household incomes, and community capacity to adapt and thrive.',
		'link'         => home_url( '/climate-actions/' ),
	),
);
?>

<section class="site-focus-areas-section">
	<div class="container">
		<div class="site-section-title site-modern-section-title">
			<span class="site-section-kicker">Strategic Focus</span>
			<h2>Focus Areas</h2>
			<p class="section-lead">
				Our programs connect skills, enterprise growth, gender inclusion, and climate resilience so communities can solve local challenges and build sustainable incomes.
			</p>
		</div>

		<div class="site-focus-grid" id="site-focus-grid">
			<?php foreach ( $focus_areas as $index => $area ) : ?>
				<article class="focus-card" aria-label="<?php echo esc_attr( $area['title'] ); ?>">

					<!-- Numbered badge -->
					<span class="focus-card-badge" aria-hidden="true"><?php echo esc_html( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>

					<div class="focus-card-media">
						<img src="<?php echo esc_url( $area['image'] ); ?>"
							<?php if ( ! empty( $area['image_srcset'] ) ) : ?>
								srcset="<?php echo esc_attr( $area['image_srcset'] ); ?>"
							<?php endif; ?>
							sizes="(max-width: 599px) 100vw, (max-width: 1023px) 50vw, 25vw"
							width="<?php echo esc_attr( ! empty( $area['image_w'] ) ? $area['image_w'] : 850 ); ?>"
							height="<?php echo esc_attr( ! empty( $area['image_h'] ) ? $area['image_h'] : 567 ); ?>"
							alt="<?php echo esc_attr( wp_strip_all_tags( $area['title'] ) ); ?>"
							loading="lazy" decoding="async">
						<span class="focus-card-icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $area['icon'] ); ?>"></i></span>
					</div>

					<div class="focus-card-content">
						<span class="focus-card-kicker"><?php echo esc_html( $area['kicker'] ); ?></span>
						<h3 class="focus-card-title"><?php echo wp_kses_post( $area['title'] ); ?></h3>
						<p class="focus-card-text"><?php echo esc_html( $area['summary'] ); ?></p>
						<a href="<?php echo esc_url( $area['link'] ); ?>" class="focus-card-link">
							Explore Focus Area <i class="fa fa-angle-right" aria-hidden="true"></i>
						</a>
					</div>

				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<script>
(function () {
	'use strict';

	/* Scroll-triggered fade-up for Focus Area cards.
	   Respects prefers-reduced-motion automatically via CSS. */
	function initFocusCardAnimations() {
		var cards = document.querySelectorAll('.site-focus-areas-section .focus-card');
		if (!cards.length) return;

		/* If IntersectionObserver isn't available, just show everything. */
		if (!('IntersectionObserver' in window)) {
			cards.forEach(function (card) { card.classList.add('is-visible'); });
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.12,
			rootMargin: '0px 0px -40px 0px'
		});

		cards.forEach(function (card) { observer.observe(card); });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFocusCardAnimations);
	} else {
		initFocusCardAnimations();
	}
}());
</script>
