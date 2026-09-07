/**
 * Our Partners & Donors — Swiper carousel init.
 * Requires Swiper 11 (enqueued from CDN in functions.php).
 */
(function () {
  function initCarousels() {
    document.querySelectorAll('[data-pd-carousel]').forEach(function (el) {
      if (el.dataset.pdInit) return;
      el.dataset.pdInit = '1';

      new Swiper(el, {
        slidesPerView: 2,            /* Mobile < 640px */
        spaceBetween: 16,
        loop: true,
        grabCursor: true,            /* touch/swipe affordance */
        speed: 650,
        autoplay: {
          delay: 3000,               /* <- customize slide speed here (ms) */
          disableOnInteraction: false,
          pauseOnMouseEnter: true    /* pause while hovering a logo */
        },
        pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        navigation: {
          nextEl: el.querySelector('.pd-next'),
          prevEl: el.querySelector('.pd-prev')
        },
        breakpoints: {
          640:  { slidesPerView: 3, spaceBetween: 18 },  /* Tablet */
          1024: { slidesPerView: 5, spaceBetween: 20 }   /* Desktop */
        }

        /* OPTIONAL — "smooth continuous" marquee mode (never stops):
           remove the autoplay block above and use instead:
        autoplay: { delay: 0, disableOnInteraction: false, pauseOnMouseEnter: true },
        speed: 4500,
        */
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarousels);
  } else {
    initCarousels();
  }
})();
