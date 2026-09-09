/**
 * Client Testimonials — "two at a time" pair slider + scroll reveal.
 *
 * Shows one pair of testimonial cards per slide and cycles through them with
 * prev/next arrows, clickable dots, gentle autoplay (paused on hover/focus and
 * when the tab is hidden) and keyboard support. Vanilla JS, no dependencies.
 * Respects prefers-reduced-motion (autoplay and the entrance reveal are
 * disabled via JS + CSS).
 *
 * @package SITE Child
 */
(function () {
	'use strict';

	function initSlider() {
		var root = document.querySelector('.site-testimonials [data-tm-slider]');
		if (!root) {
			return;
		}

		var track = root.querySelector('.site-testimonials__track');
		var slides = Array.prototype.slice.call(root.querySelectorAll('[data-tm-slide]'));
		var prevBtn = root.querySelector('[data-tm-prev]');
		var nextBtn = root.querySelector('[data-tm-next]');
		var pagination = root.querySelector('[data-tm-pagination]');
		var autoplayMs = 7000;

		if (!track || slides.length < 2) {
			return;
		}

		var index = 0;
		var timer = null;
		var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		// Build one dot per pair.
		var dots = [];
		if (pagination) {
			slides.forEach(function (slide, i) {
				var dot = document.createElement('button');
				dot.type = 'button';
				dot.className = 'site-testimonials__dot';
				dot.setAttribute('aria-label', 'Show testimonial pair ' + (i + 1) + ' of ' + slides.length);
				dot.addEventListener('click', function () {
					goTo(i);
					restart();
				});
				pagination.appendChild(dot);
				dots.push(dot);
			});
		}

		function markActive(i) {
			slides.forEach(function (slide, s) {
				slide.setAttribute('aria-hidden', s === i ? 'false' : 'true');
			});
			dots.forEach(function (dot, d) {
				dot.classList.toggle('is-active', d === i);
				if (d === i) {
					dot.setAttribute('aria-current', 'true');
				} else {
					dot.removeAttribute('aria-current');
				}
			});
		}

		function goTo(i) {
			index = (i + slides.length) % slides.length;
			track.style.transform = 'translateX(' + (-index * 100) + '%)';
			markActive(index);
		}

		function next() {
			goTo(index + 1);
		}

		function prev() {
			goTo(index - 1);
		}

		function start() {
			if (prefersReduced || timer) {
				return;
			}
			timer = setInterval(next, autoplayMs);
		}

		function stop() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function restart() {
			stop();
			start();
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				prev();
				restart();
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				next();
				restart();
			});
		}

		root.addEventListener('mouseenter', stop);
		root.addEventListener('mouseleave', start);
		root.addEventListener('focusin', stop);
		root.addEventListener('focusout', start);
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stop();
			} else {
				restart();
			}
		});

		root.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				prev();
				restart();
			} else if (event.key === 'ArrowRight') {
				next();
				restart();
			}
		});

		goTo(0);
		start();
	}

	function initReveal() {
		var slider = document.querySelector('.site-testimonials .site-testimonials__slider');
		if (!slider) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
			slider.classList.add('is-visible');
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15 });

		observer.observe(slider);
	}

	function init() {
		initSlider();
		initReveal();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
