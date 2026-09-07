/**
 * SITE Enterprise Promotion — Footer behaviour.
 *
 * Vanilla JS, no dependencies:
 *   1. IntersectionObserver reveal for staggered fade-up entrance.
 *   2. Smooth scroll for back-to-top links (respects reduced motion).
 *
 * If JavaScript is unavailable the reveal elements stay hidden is avoided:
 * the observer adds .is-revealed immediately when IO is missing.
 */
(function () {
	'use strict';

	var prefersReducedMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	var revealEls = document.querySelectorAll('.site-footer [data-reveal]');

	// 1 — Staggered reveal on viewport entry.
	if (!revealEls.length) {
		// No-op.
	} else if (prefersReducedMotion || !('IntersectionObserver' in window)) {
		revealEls.forEach(function (el) {
			el.classList.add('is-revealed');
		});
	} else {
		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-revealed');
						observer.unobserve(entry.target);
					}
				});
			},
			{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
		);

		revealEls.forEach(function (el) {
			observer.observe(el);
		});
	}

	// 2 — Smooth back-to-top.
	document.querySelectorAll('.site-footer__totop, .site-footer__totop-link').forEach(function (link) {
		link.addEventListener('click', function (event) {
			var target = document.getElementById('site-header');

			if (!target) {
				return;
			}

			event.preventDefault();

			target.scrollIntoView({
				behavior: prefersReducedMotion ? 'auto' : 'smooth',
				block: 'start'
			});

			// Keep the URL clean after the jump.
			if (window.history && window.history.replaceState) {
				window.history.replaceState(null, '', window.location.href.split('#')[0]);
			}
		});
	});
})();
