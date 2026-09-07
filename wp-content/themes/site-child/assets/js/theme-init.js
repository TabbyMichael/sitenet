/*
 * SITE — Colour theme init (light/dark).
 * Runs inline in <head> (via functions.php wp_head priority 0) BEFORE the
 * theme stylesheets paint, so there is never a flash of the wrong theme.
 * Light mode is the default; dark mode applies data-theme="dark" to <html>.
 * The user's choice is remembered in localStorage; first-time visitors
 * follow their OS "prefers-color-scheme" preference.
 */
( function () {
	'use strict';

	var storageKey = 'site-theme';
	var saved = null;

	try {
		saved = localStorage.getItem( storageKey );
	} catch ( err ) {
		saved = null;
	}

	var dark;
	if ( saved === 'dark' ) {
		dark = true;
	} else if ( saved === 'light' ) {
		dark = false;
	} else if ( 'matchMedia' in window ) {
		dark = window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
	} else {
		dark = false;
	}

	document.documentElement.setAttribute( 'data-theme', dark ? 'dark' : 'light' );

	function syncToggle() {
		var btn = document.querySelector( '[data-theme-toggle]' );
		if ( ! btn ) {
			return;
		}
		var isDark = document.documentElement.getAttribute( 'data-theme' ) === 'dark';
		btn.setAttribute( 'aria-pressed', isDark ? 'true' : 'false' );
		btn.setAttribute( 'aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode' );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', syncToggle );
	} else {
		syncToggle();
	}

	document.addEventListener( 'click', function ( event ) {
		var el = event.target && event.target.closest ? event.target.closest( '[data-theme-toggle]' ) : null;
		if ( ! el ) {
			return;
		}
		var next = document.documentElement.getAttribute( 'data-theme' ) === 'dark' ? 'light' : 'dark';
		document.documentElement.setAttribute( 'data-theme', next );
		syncToggle();
		try {
			localStorage.setItem( storageKey, next );
		} catch ( err ) {}
	} );
} )();