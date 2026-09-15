/**
 * Our Partners & Donors — Swiper carousel init + story routing.
 * Requires Swiper 11 (enqueued from CDN in functions.php).
 */
(function () {
  /* ---- Random-story routing ------------------------------------------------
     Clicking a logo card routes the visitor to a story chosen at random from
     that card's data-pd-stories list (a different one on each click). A
     pointer-move guard prevents navigation when the click is really the end
     of a carousel swipe/drag. Without JavaScript the href (first story) is
     used as a fallback. */
  var downX = null;
  var downY = null;
  var dragged = false;

  function onPointerDown(e) {
    downX = e.clientX;
    downY = e.clientY;
    dragged = false;
  }

  function onPointerMove(e) {
    if (downX === null) return;
    if (Math.abs(e.clientX - downX) > 10 || Math.abs(e.clientY - downY) > 10) {
      dragged = true;
    }
  }

  function onClick(e) {
    var link = e.target && e.target.closest ? e.target.closest('a.pd-card-link[data-pd-stories]') : null;
    if (!link || dragged) return;

    var urls;
    try {
      urls = JSON.parse(link.getAttribute('data-pd-stories'));
    } catch (err) {
      return;
    }
    if (!urls || !urls.length) return;

    e.preventDefault();
    window.location.assign(urls[Math.floor(Math.random() * urls.length)]);
  }

  document.addEventListener('pointerdown', onPointerDown, true);
  document.addEventListener('pointermove', onPointerMove, true);
  document.addEventListener('click', onClick);

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
