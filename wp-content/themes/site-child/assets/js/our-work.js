/* ==========================================================================
   SITE — Our Work page interactions
   --------------------------------------------------------------------------
   - Reads the ?filter=... URL parameter and activates the matching pill.
   - Switches categories client-side without page reload.
   - Updates the URL via history.replaceState for shareable links.
   - Reveals cards with IntersectionObserver as they enter the viewport.
   - Respects prefers-reduced-motion by leaving cards visible statically.
   ========================================================================== */

( function () {
'use strict';

var section = document.querySelector( '.site-our-work' );
if ( ! section ) {
return;
}

var validFilters = [ 'all', 'stories', 'case-studies', 'papers', 'news', 'press-releases' ];
var cards = Array.from( section.querySelectorAll( '.ow-card' ) );
var buttons = Array.from( section.querySelectorAll( '.ow-filter__btn' ) );
var grid = section.querySelector( '.ow-grid' );
var filterEmpty = section.querySelector( '.ow-filter-empty' );

function getFilterFromURL() {
var params = new URLSearchParams( window.location.search );
var filter = params.get( 'filter' );
return validFilters.indexOf( filter ) !== -1 ? filter : 'all';
}

function updateURL( filter ) {
var url = new URL( window.location.href );
if ( filter === 'all' ) {
url.searchParams.delete( 'filter' );
} else {
url.searchParams.set( 'filter', filter );
}
if ( window.history.replaceState ) {
window.history.replaceState( {}, '', url.toString() );
}
}

function setActiveFilter( filter, skipURL ) {
if ( validFilters.indexOf( filter ) === -1 ) {
filter = 'all';
}

buttons.forEach( function ( btn ) {
var isActive = btn.getAttribute( 'data-filter' ) === filter;
btn.classList.toggle( 'is-active', isActive );
btn.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
} );

cards.forEach( function ( card ) {
var type = card.getAttribute( 'data-type' ) || 'all';
var match = filter === 'all' || type === filter;
card.classList.toggle( 'is-filter-hidden', ! match );
} );

var visibleCards = cards.filter( function ( c ) {
return ! c.classList.contains( 'is-filter-hidden' );
} );

if ( filterEmpty ) {
filterEmpty.classList.toggle( 'is-visible', visibleCards.length === 0 );
}

if ( ! skipURL ) {
updateURL( filter );
}
}

buttons.forEach( function ( btn ) {
btn.addEventListener( 'click', function ( event ) {
var filter = btn.getAttribute( 'data-filter' );
if ( ! filter ) {
return;
}
event.preventDefault();
setActiveFilter( filter );
} );
} );

// IntersectionObserver scroll reveal (respects reduced motion).
var prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
if ( ! prefersReducedMotion && 'IntersectionObserver' in window ) {
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
} else {
cards.forEach( function ( card ) {
card.classList.add( 'is-revealed' );
} );
}

// Initialise filter state from URL without pushing extra history entries.
setActiveFilter( getFilterFromURL(), true );
} )();
