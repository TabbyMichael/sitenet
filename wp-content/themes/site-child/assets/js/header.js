/**
 * SITE Enterprise Promotion — Header / Navigation behaviour.
 *
 * Vanilla JavaScript, no dependencies. Complements the CSS in header.css.
 *
 * Responsibilities:
 *  1. Scrolled state      → adds .site-header--scrolled (compact nav + shadow).
 *  2. Mobile panel        → hamburger toggles the full-width dropdown panel.
 *  3. Dropdowns           → keyboard (focus/Escape), touch and outside-click
 *                           handling on submenu parents; syncs aria-expanded.
 *
 * Desktop hover opening is pure CSS. This script covers every interaction
 * that hover cannot reach (keyboard, touch, click-away, Escape).
 */
( function () {
	'use strict';

	var header      = document.getElementById( 'site-header' );
	var nav         = document.getElementById( 'site-nav' );
	var toggleBtn   = document.querySelector( '[data-header-toggle]' );
	var menuEl      = document.getElementById( 'site-nav-menu' );

	if ( ! header || ! nav || ! menuEl ) {
		return;
	}

	var mobileMq  = window.matchMedia( '(max-width: 1023px)' );
	var noHoverMq = window.matchMedia( '(hover: none)' );
	var parents   = Array.prototype.slice.call(
		menuEl.querySelectorAll( 'li.menu-item-has-children' )
	);

	/* ------------------------------------------------------------------
	 * 0. Measure the fixed header and expose its height to CSS.
	 * header.css offsets non-home pages with
	 *   body:not(.home) { padding-top: calc(var(--site-header-offset) + 20px); }
	 * so content always starts below the navbar instead of under it.
	 * Re-measured on resize (logo scales per breakpoint) and skipped on
	 * the home page, whose hero intentionally flows under the header.
	 * ------------------------------------------------------------------ */
	function updateHeaderOffset() {
		if ( document.body && document.body.classList.contains( 'home' ) ) {
			return;
		}
		var h = header.offsetHeight || 0;
		if ( h > 0 ) {
			document.documentElement.style.setProperty( '--site-header-offset', h + 'px' );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', updateHeaderOffset );
	} else {
		updateHeaderOffset();
	}
	window.addEventListener( 'load', updateHeaderOffset );
	window.addEventListener( 'resize', updateHeaderOffset );

	/* ------------------------------------------------------------------
	 * 1. Scrolled state (rAF-throttled scroll listener)
	 * ------------------------------------------------------------------ */
	var ticking = false;

	function updateScrolledState() {
		header.classList.toggle( 'site-header--scrolled', window.scrollY > 8 );
		ticking = false;
	}

	window.addEventListener(
		'scroll',
		function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( updateScrolledState );
			}
		},
		{ passive: true }
	);

	updateScrolledState();

	/* ------------------------------------------------------------------
	 * 2. Dropdown helpers
	 * ------------------------------------------------------------------ */
	function isMobile() {
		return mobileMq.matches;
	}

	function touchPrimaryDevice() {
		/* Touch-first devices: tap once to open, tap again to navigate. */
		return noHoverMq.matches;
	}

	function setOpen( li, open ) {
		li.classList.toggle( 'is-open', open );
		li.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	function closeAll( exceptLi ) {
		parents.forEach( function ( li ) {
			if ( li !== exceptLi && li.classList.contains( 'is-open' ) ) {
				setOpen( li, false );
			}
		} );
	}

	function topLink( li ) {
		return li.querySelector( ':scope > a' );
	}

	parents.forEach( function ( li ) {
		/* The Aria_Walker_Nav_Menu sets aria-haspopup + aria-expanded on the <li>. */
		li.setAttribute( 'aria-expanded', 'false' );

		var link = topLink( li );

		/* Keyboard: open on focus, close when focus leaves the item. */
		li.addEventListener( 'focusin', function () {
			if ( ! isMobile() ) {
				closeAll( li );
				setOpen( li, true );
			}
		} );

		li.addEventListener( 'focusout', function ( event ) {
			if ( ! isMobile() && ! li.contains( event.relatedTarget ) ) {
				setOpen( li, false );
			}
		} );

		/* Click / tap on the parent link. */
		if ( link ) {
			link.addEventListener( 'click', function ( event ) {
				if ( isMobile() ) {
					/* Mobile: parent links toggle their accordion. */
					var willOpen = ! li.classList.contains( 'is-open' );
					if ( willOpen ) {
						event.preventDefault();
						closeAll( li );
						setOpen( li, willOpen );
					}
					/* If already open, allow navigation by not preventing default */
					return;
				}

				if ( touchPrimaryDevice() && ! li.classList.contains( 'is-open' ) ) {
					/*
					 * Touch on desktop layout: first tap opens the panel,
					 * second tap follows the link. This mirrors the parent
					 * theme's doubletaptogo behaviour (which preventDefault()s
					 * the first tap) without fighting it.
					 */
					event.preventDefault();
					closeAll( li );
					setOpen( li, true );
				}
				/* Otherwise (hover already open) — navigate normally. */
			} );
		}
	} );

	/* Escape closes any open dropdown / the mobile panel. */
	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}

		var closed = false;

		parents.forEach( function ( li ) {
			if ( li.classList.contains( 'is-open' ) ) {
				setOpen( li, false );
				closed = true;
				if ( ! isMobile() ) {
					var link = topLink( li );
					if ( link ) {
						link.focus();
					}
				}
			}
		} );

		if ( nav.classList.contains( 'site-nav--open' ) ) {
			closeMobilePanel();
			if ( toggleBtn ) {
				toggleBtn.focus();
			}
			closed = true;
		}

		if ( closed ) {
			event.stopPropagation();
		}
	} );

	/* Click outside the header closes dropdowns and the mobile panel. */
	document.addEventListener( 'click', function ( event ) {
		if ( header.contains( event.target ) ) {
			return;
		}
		closeAll();
		if ( nav.classList.contains( 'site-nav--open' ) ) {
			closeMobilePanel();
		}
	} );

	/* ------------------------------------------------------------------
	 * 3. Mobile panel (hamburger)
	 * ------------------------------------------------------------------ */
	function openMobilePanel() {
		nav.classList.add( 'site-nav--open' );
		if ( toggleBtn ) {
			toggleBtn.setAttribute( 'aria-expanded', 'true' );
		}
	}

	function closeMobilePanel() {
		nav.classList.remove( 'site-nav--open' );
		closeAll();
		if ( toggleBtn ) {
			toggleBtn.setAttribute( 'aria-expanded', 'false' );
		}
	}

	if ( toggleBtn ) {
		toggleBtn.addEventListener( 'click', function () {
			if ( nav.classList.contains( 'site-nav--open' ) ) {
				closeMobilePanel();
			} else {
				openMobilePanel();
			}
		} );
	}

	/* Leaving the mobile breakpoint: reset panel + accordions cleanly. */
	function handleBreakpointChange() {
		if ( ! mobileMq.matches ) {
			closeMobilePanel();
		}
	}

	if ( typeof mobileMq.addEventListener === 'function' ) {
		mobileMq.addEventListener( 'change', handleBreakpointChange );
	} else if ( typeof mobileMq.addListener === 'function' ) {
		mobileMq.addListener( handleBreakpointChange ); /* Safari < 14 */
	}
} )();
