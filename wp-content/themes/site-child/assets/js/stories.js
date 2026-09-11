/* ==========================================================================
   SITE - Stories (blog) page interactions
   - Reveals story cards with IntersectionObserver as they enter the viewport.
   - Respects prefers-reduced-motion by leaving cards visible statically.
   ========================================================================== */
( function () {
    'use strict';

    var section = document.querySelector( '.site-stories' );
    if ( ! section ) {
        return;
    }

    var cards = Array.from( section.querySelectorAll( '.st-card' ) );
    if ( ! cards.length ) {
        return;
    }

    var prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

    if ( prefersReducedMotion || ! ( 'IntersectionObserver' in window ) ) {
        cards.forEach( function ( card ) {
            card.classList.add( 'is-revealed' );
        } );
        return;
    }

    var observer = new IntersectionObserver( function ( entries ) {
        entries.forEach( function ( entry ) {
            if ( entry.isIntersecting ) {
                entry.target.classList.add( 'is-revealed' );
                observer.unobserve( entry.target );
            }
        } );
    }, {
        threshold: 0.10,
        rootMargin: '0px 0px -48px 0px',
    } );

    cards.forEach( function ( card ) {
        observer.observe( card );
    } );
} )();
