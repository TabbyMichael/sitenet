/**
 * SITE Hero Carousel — vanilla JS, no dependencies.
 *
 * Features: autoplay (pause on hover/focus/touch/hidden tab), prev/next,
 * keyboard arrows, touch/pointer swipe with drag tracking, numeric counter,
 * ARIA live slide announcements, respects prefers-reduced-motion.
 *
 * Expects markup from template-parts/homepage/header-hero.php.
 */
(function () {
	'use strict';

	var REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)');

	function pad(n) {
		return String(n).padStart(2, '0');
	}

	function SiteCarousel(section) {
		this.section = section;
		this.track = section.querySelector('[data-carousel-track]');
		this.slides = Array.prototype.slice.call(section.querySelectorAll('.site-carousel-slide'));
		this.prevBtn = section.querySelector('[data-carousel-prev]');
		this.nextBtn = section.querySelector('[data-carousel-next]');
		this.toggleBtn = section.querySelector('[data-carousel-toggle]');
		this.currentEl = section.querySelector('[data-carousel-current]');
		this.statusEl = section.querySelector('[data-carousel-status]');
		this.delay = parseInt(section.getAttribute('data-autoplay-delay'), 10) || 6000;

		this.index = 0;
		this.total = this.slides.length;
		this.timer = null;
		this.hovering = false;
		this.focused = false;
		this.userPaused = false;
		this.dragging = false;

		if (this.total < 2) {
			return;
		}

		this.bind();
		this.update();
		this.maybeStart();
	}
	SiteCarousel.prototype.bind = function () {
		var self = this;

		this.prevBtn.addEventListener('click', function () { self.go(self.index - 1, true); });
		this.nextBtn.addEventListener('click', function () { self.go(self.index + 1, true); });

		if (this.toggleBtn) {
			this.toggleBtn.addEventListener('click', function () {
				self.userPaused = !self.userPaused;
				self.toggleBtn.setAttribute('aria-pressed', self.userPaused ? 'true' : 'false');
				self.toggleBtn.setAttribute('aria-label',
					self.userPaused ? 'Play automatic sliding' : 'Pause automatic sliding');
				if (self.userPaused) { self.stop(); } else { self.maybeStart(); }
			});
		}

		// Keyboard: Left / Right arrows anywhere inside the carousel.
		this.section.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') {
				e.preventDefault();
				self.go(self.index - 1, true);
			} else if (e.key === 'ArrowRight') {
				e.preventDefault();
				self.go(self.index + 1, true);
			}
		});

		// Pause on hover and while keyboard focus is inside the carousel.
		this.section.addEventListener('mouseenter', function () { self.hovering = true; self.stop(); });
		this.section.addEventListener('mouseleave', function () { self.hovering = false; self.maybeStart(); });
		this.section.addEventListener('focusin', function () { self.focused = true; self.stop(); });
		this.section.addEventListener('focusout', function (e) {
			if (!self.section.contains(e.relatedTarget)) {
				self.focused = false;
				self.maybeStart();
			}
		});

		// Pause when the tab is hidden.
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) { self.stop(); } else { self.maybeStart(); }
		});

		// Pause if the reduced-motion preference changes to "reduce".
		if (REDUCED_MOTION.addEventListener) {
			REDUCED_MOTION.addEventListener('change', function () { self.maybeStart(); });
		}

		this.bindDrag();
	};
	/**
	 * Touch / pointer swipe with drag tracking.
	 */
	SiteCarousel.prototype.bindDrag = function () {
		var self = this;
		var startX = 0;
		var currentX = 0;
		var pointerId = null;

		function isInteractive(el) {
			return !!(el.closest && el.closest('a, button'));
		}

		this.track.addEventListener('pointerdown', function (e) {
			if (e.pointerType === 'mouse' && e.button !== 0) { return; }
			pointerId = e.pointerId;
			startX = e.clientX;
			currentX = startX;
			self.dragging = true;
			self.stop();
			self.track.style.transition = 'none';
		});

		this.track.addEventListener('pointermove', function (e) {
			if (!self.dragging || e.pointerId !== pointerId) { return; }
			currentX = e.clientX;
			var delta = currentX - startX;
			// Only track horizontal intent; ignore vertical scrolling gestures.
			if (Math.abs(delta) > 8 && isInteractive(e.target)) {
				e.preventDefault();
			}
			self.track.style.transform = 'translateX(' + delta + 'px)';
		});

		function endDrag(e) {
			if (!self.dragging || (e.pointerId !== undefined && e.pointerId !== pointerId)) { return; }
			self.dragging = false;
			self.track.style.transition = '';
			self.track.style.transform = '';

			var delta = currentX - startX;
			if (Math.abs(delta) > 60) {
				self.go(delta < 0 ? self.index + 1 : self.index - 1, true);
			} else {
				self.maybeStart();
			}
			pointerId = null;
		}

		this.track.addEventListener('pointerup', endDrag);
		this.track.addEventListener('pointercancel', endDrag);
		this.track.addEventListener('pointerleave', endDrag);
	};
	SiteCarousel.prototype.go = function (targetIndex, byUser) {
		if (this.total < 2) { return; }

		this.index = (targetIndex + this.total) % this.total;
		this.update();

		if (byUser) {
			// Restart the autoplay clock after manual navigation.
			this.stop();
			this.maybeStart();
		}
	};

	SiteCarousel.prototype.update = function () {
		var self = this;
		var offset = -this.index * 100;

		this.track.style.transform = 'translateX(' + offset + '%)';

		this.slides.forEach(function (slide, i) {
			var active = i === self.index;
			slide.classList.toggle('is-active', active);
			if (active) {
				slide.removeAttribute('aria-hidden');
				slide.removeAttribute('inert');
			} else {
				slide.setAttribute('aria-hidden', 'true');
				slide.setAttribute('inert', '');
			}
		});

		if (this.currentEl) {
			this.currentEl.textContent = pad(this.index + 1);
		}

		if (this.statusEl) {
			var title = this.slides[this.index].querySelector('.site-carousel-title');
			var label = 'Slide ' + (this.index + 1) + ' of ' + this.total;
			if (title && title.textContent) {
				label += ': ' + title.textContent.trim();
			}
			this.statusEl.textContent = label;
		}
	};

	SiteCarousel.prototype.maybeStart = function () {
		if (REDUCED_MOTION.matches || this.userPaused || this.hovering || this.focused || this.dragging) {
			return;
		}
		if (document.hidden) {
			return;
		}
		this.start();
	};

	SiteCarousel.prototype.start = function () {
		var self = this;
		if (this.timer) { return; }
		this.timer = window.setInterval(function () {
			self.go(self.index + 1, false);
		}, this.delay);
	};

	SiteCarousel.prototype.stop = function () {
		if (this.timer) {
			window.clearInterval(this.timer);
			this.timer = null;
		}
	};

	/**
	 * Full-height hero: measure the site header and expose its bottom edge as
	 * a CSS custom property so .site-carousel-frame can fill the remaining
	 * viewport exactly (100vh - header). Re-measured on resize.
	 */
	function setHeroOffset() {
		/* Carousel fills the full viewport height — it extends right up to the
		   top of the navbar so the hero image flows underneath the header. */
		document.documentElement.style.setProperty('--site-hero-offset', '0px');
	}

	function init() {
		setHeroOffset();
		window.addEventListener('resize', setHeroOffset);

		var sections = document.querySelectorAll('.site-hero-carousel');
		Array.prototype.forEach.call(sections, function (section) {
			if (!section.dataset.carouselReady) {
				section.dataset.carouselReady = 'true';
				new SiteCarousel(section);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
