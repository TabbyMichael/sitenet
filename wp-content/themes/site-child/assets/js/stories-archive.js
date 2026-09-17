/* ==========================================================================
   SITE — Stories archive interactions
   - Reveals story cards + the featured card with IntersectionObserver as they
     enter the viewport.
   - The hidden state is only added via JS (.sa-reveal), so content is always
     visible when JavaScript is unavailable.
   - Bails out entirely for prefers-reduced-motion or when IntersectionObserver
     is missing.
   Vanilla JS only, no dependencies. Deferred + in footer by
   site_child_enqueue_stories_archive_assets().
   ========================================================================== */

( function () {
    'use strict';

    var root = document.querySelector( '.site-stories-archive' );
    if ( ! root ) {
        return;
    }

    var prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
    if ( prefersReducedMotion || ! ( 'IntersectionObserver' in window ) ) {
        return;
    }

    var targets = Array.from( root.querySelectorAll( '.sa-card, .sa-featured-card' ) );
    if ( ! targets.length ) {
        return;
    }

    targets.forEach( function ( el ) {
        el.classList.add( 'sa-reveal' );
    } );

    var observer = new IntersectionObserver( function ( entries ) {
        entries.forEach( function ( entry ) {
            if ( entry.isIntersecting ) {
                entry.target.classList.add( 'is-revealed' );
                observer.unobserve( entry.target );
            }
        } );
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px',
    } );

    targets.forEach( function ( el ) {
        observer.observe( el );
    } );
} )();
