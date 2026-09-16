<?php
/**
 * Shared "Focus Areas" section.
 *
 * Single renderer for the four Focus Area cards, so the homepage, the About Us
 * page and the Our Work pages output byte-identical markup and therefore inherit
 * one identical set of styles (style.css + homepage-responsive.css) and one
 * identical dark-mode treatment (dark.css). Data comes from
 * site_child_get_focus_areas() in functions.php.
 *
 * The section is deliberately NOT scoped to a page root class: its styling is
 * supplied entirely by .site-focus-areas-section / .focus-card-* rules that
 * already exist in style.css, so reusing the class names is what guarantees the
 * cards look the same everywhere. Nothing here targets the locked header or
 * footer.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$focus_areas = site_child_get_focus_areas();
?>
<section class="site-focus-areas-section" aria-labelledby="site-focus-areas-title">
	<div class="container">
		<div class="site-section-title site-modern-section-title">
			<span class="site-section-kicker">Strategic Focus</span>
			<h2 id="site-focus-areas-title">Focus Areas</h2>
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
							sizes="(max-width: 599px) 100vw, (max-width: 1023px) 50vw, 25vw"
							width="<?php echo esc_attr( ! empty( $area['image_w'] ) ? $area['image_w'] : 850 ); ?>"
							height="<?php echo esc_attr( ! empty( $area['image_h'] ) ? $area['image_h'] : 567 ); ?>"
							alt="<?php echo esc_attr( ! empty( $area['image_alt'] ) ? $area['image_alt'] : wp_strip_all_tags( $area['title'] ) ); ?>"
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
	   Respects prefers-reduced-motion automatically via CSS.
	   Guarded against double-initialisation: this part can be loaded more than
	   once per request (it is not, today, but the guard keeps that safe). */
	if (window.siteFocusAreasBound) {
		return;
	}
	window.siteFocusAreasBound = true;

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
