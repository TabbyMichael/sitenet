/* ==========================================================================
   SITE — Single story interactions
   --------------------------------------------------------------------------
   - Scroll reveal for cards (stats, gallery figures, donor cards, related
     stories, CTA) using IntersectionObserver.
   - The hidden state is only added via JS (.ss-reveal), so content is always
     visible when JavaScript is unavailable.
   - Bails out entirely when the visitor prefers reduced motion or
     IntersectionObserver is missing — everything stays statically visible.
   Vanilla JS only, no dependencies. Deferred + in footer by
   site_child_enqueue_story_single_assets().
   ========================================================================== */

( function () {
	'use strict';

	const root = document.querySelector( '.site-story-single' );
	if ( ! root ) {
		return;
	}

	const prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	if ( prefersReducedMotion || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	const targets = Array.from(
		root.querySelectorAll( '.ss-stat, .ss-gallery-item, .ss-related-card, .ss-cta' )
	);
	if ( targets.length === 0 ) {
		return;
	}

	// Hide first (JS-only), then reveal as each element enters the viewport.
	targets.forEach( ( el ) => el.classList.add( 'ss-reveal' ) );

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				}
			} );
		},
		{
			threshold: 0.1,
			rootMargin: '0px 0px -40px 0px',
		}
	);

	targets.forEach( ( el ) => observer.observe( el ) );
} )();
