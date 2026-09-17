/* ==========================================================================
   SITE — Modernised programme + resource pages
   --------------------------------------------------------------------------
   - Scroll reveal for cards (IntersectionObserver, bails out when the
     visitor prefers reduced motion or IntersectionObserver is missing —
     cards stay statically visible).
   - Accessible photo lightbox driven by the [data-rv-lightbox-grid]
     button grids (one per photo collection): prev/next, caption, close via
     button / backdrop / Escape, focus returned to the trigger, body scroll
     locked. Items from every grid are walked in document order.
   Vanilla JS only, no dependencies. Deferred + in footer by
   site_child_enqueue_revamp_assets().
   ========================================================================== */

( function () {
	'use strict';

	const root = document.querySelector( '.site-revamp' );
	if ( ! root ) {
		return;
	}

	const prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ------------------------------------------------------------------
	 * Reveal-on-scroll.
	 * ------------------------------------------------------------------ */
	const revealTargets = Array.from(
		root.querySelectorAll( '.rv-feature-card, .rv-program-card, .rv-story-card, .rv-case-card, .rv-paper-card, .rv-video-card, .rv-resource-link, .rv-imagery__item, .rv-showcase__item, .rv-photo-grid__item' )
	);

	if ( prefersReducedMotion || ! ( 'IntersectionObserver' in window ) ) {
		revealTargets.forEach( function ( card ) {
			card.classList.add( 'is-revealed' );
		} );
	} else {
		const revealObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-revealed' );
						revealObserver.unobserve( entry.target );
					}
				} );
			},
			{
				threshold: 0.08,
				rootMargin: '0px 0px -40px 0px',
			}
		);

		revealTargets.forEach( function ( card ) {
			card.classList.add( 'rv-reveal' );
			revealObserver.observe( card );
		} );
	}

	/* ------------------------------------------------------------------
	 * Video card hover-to-play (muted preview).
	 * ------------------------------------------------------------------ */
	const hoverVideos = Array.from( root.querySelectorAll( '[data-hover-video] .rv-video-card__media' ) );
	hoverVideos.forEach( function ( video ) {
		if ( video.tagName !== 'VIDEO' ) {
			return;
		}
		const card = video.closest( '[data-hover-video]' );
		if ( ! card ) {
			return;
		}

		card.addEventListener( 'mouseenter', function () {
			video.play().catch( function () {} );
		} );
		card.addEventListener( 'mouseleave', function () {
			video.pause();
		} );
		/* Touch / no-hover devices: tap to toggle playback. */
		card.addEventListener( 'click', function () {
			if ( video.paused ) {
				video.play().catch( function () {} );
			} else {
				video.pause();
			}
		} );
	} );

	/* ------------------------------------------------------------------
	 * Photo lightbox.
	 * ------------------------------------------------------------------ */
	const grids = Array.from( root.querySelectorAll( '[data-rv-lightbox-grid]' ) );
	if ( grids.length === 0 ) {
		return;
	}

	/* The gallery can be split into several collections, each with its own
	   grid — collect every item in document order so prev/next walks them all. */
	let items = [];
	grids.forEach( function ( grid ) {
		items = items.concat( Array.from( grid.querySelectorAll( '.rv-photo-grid__item' ) ) );
	} );

	if ( items.length === 0 ) {
		return;
	}

	let currentIndex = 0;
	let lastTrigger  = null;
	let allowScroll  = true;

	const state = {
		overlay: null,
	};

	const buildOverlay = function () {
		const overlay = document.createElement( 'div' );
		overlay.className = 'rv-lightbox';
		overlay.setAttribute( 'role', 'dialog' );
		overlay.setAttribute( 'aria-modal', 'true' );
		overlay.setAttribute( 'aria-label', 'Photo viewer' );

		const dialog = document.createElement( 'div' );
		dialog.className = 'rv-lightbox__dialog';

		const figure = document.createElement( 'figure' );
		figure.className = 'rv-lightbox__figure';

		const img = document.createElement( 'img' );
		img.className = 'rv-lightbox__img';
		img.setAttribute( 'alt', '' );

		const caption = document.createElement( 'figcaption' );
		caption.className = 'rv-lightbox__caption';

		const counter = document.createElement( 'p' );
		counter.className = 'rv-lightbox__counter';
		counter.setAttribute( 'aria-hidden', 'true' );

		const closeBtn = document.createElement( 'button' );
		closeBtn.type = 'button';
		closeBtn.className = 'rv-lightbox__close';
		closeBtn.setAttribute( 'aria-label', 'Close photo viewer' );
		closeBtn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';

		const prevBtn = document.createElement( 'button' );
		prevBtn.type = 'button';
		prevBtn.className = 'rv-lightbox__prev';
		prevBtn.setAttribute( 'aria-label', 'Show previous photo' );
		prevBtn.innerHTML = '<i class="fa fa-angle-left" aria-hidden="true"></i>';

		const nextBtn = document.createElement( 'button' );
		nextBtn.type = 'button';
		nextBtn.className = 'rv-lightbox__next';
		nextBtn.setAttribute( 'aria-label', 'Show next photo' );
		nextBtn.innerHTML = '<i class="fa fa-angle-right" aria-hidden="true"></i>';

		figure.appendChild( img );
		figure.appendChild( caption );
		dialog.appendChild( figure );
		dialog.appendChild( counter );
		dialog.appendChild( closeBtn );
		dialog.appendChild( prevBtn );
		dialog.appendChild( nextBtn );
		overlay.appendChild( dialog );

		closeBtn.addEventListener( 'click', closeOverlay );

		prevBtn.addEventListener( 'click', function ( event ) {
			event.stopPropagation();
			showPhoto( ( currentIndex - 1 + items.length ) % items.length );
		} );

		nextBtn.addEventListener( 'click', function ( event ) {
			event.stopPropagation();
			showPhoto( ( currentIndex + 1 ) % items.length );
		} );

		overlay.addEventListener( 'click', function ( event ) {
			if ( event.target === overlay ) {
				closeOverlay();
			}
		} );

		state.overlay = overlay;
		return overlay;
	};

	const showPhoto = function ( index ) {
		const { overlay } = state;
		if ( ! overlay ) {
			return;
		}
		currentIndex = index;

		const item    = items[ currentIndex ];
		const url     = item.getAttribute( 'data-photo-url' ) || '';
		const caption = item.getAttribute( 'data-photo-caption' ) || '';

		const img     = overlay.querySelector( '.rv-lightbox__img' );
		const capEl   = overlay.querySelector( '.rv-lightbox__caption' );
		const countEl = overlay.querySelector( '.rv-lightbox__counter' );

		if ( img ) {
			img.setAttribute( 'src', url );
			img.setAttribute( 'alt', caption );
		}
		if ( capEl ) {
			capEl.textContent = caption;
		}
		if ( countEl ) {
			countEl.textContent = ( currentIndex + 1 ) + ' of ' + items.length;
		}
	};

	const openOverlay = function ( index, trigger ) {
		const overlay = state.overlay || buildOverlay();
		lastTrigger = trigger || null;

		allowScroll = document.body.style.overflow !== 'hidden';
		document.body.style.overflow = 'hidden';
		root.classList.add( 'rv-lightbox-open' );
		document.body.appendChild( overlay );
		showPhoto( index );
		overlay.classList.add( 'is-visible' );

		const closeBtn = overlay.querySelector( '.rv-lightbox__close' );
		if ( closeBtn ) {
			closeBtn.focus();
		}
	};

	const closeOverlay = function () {
		const { overlay } = state;
		if ( ! overlay || ! overlay.parentNode ) {
			return;
		}
		overlay.classList.remove( 'is-visible' );
		if ( allowScroll ) {
			document.body.style.overflow = '';
		}
		root.classList.remove( 'rv-lightbox-open' );
		overlay.parentNode.removeChild( overlay );
		if ( lastTrigger ) {
			lastTrigger.focus();
		}
	};

	const handleKeys = function ( event ) {
		const { overlay } = state;
		if ( ! overlay || ! overlay.parentNode ) {
			return;
		}
		if ( event.key === 'Escape' ) {
			closeOverlay();
		} else if ( event.key === 'ArrowLeft' ) {
			event.preventDefault();
			showPhoto( ( currentIndex - 1 + items.length ) % items.length );
		} else if ( event.key === 'ArrowRight' ) {
			event.preventDefault();
			showPhoto( ( currentIndex + 1 ) % items.length );
		}
	}

	document.addEventListener( 'keydown', handleKeys );

	items.forEach( function ( item, index ) {
		item.addEventListener( 'click', function () {
			openOverlay( index, item );
		} );
	} );
} )();
