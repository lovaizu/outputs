document.addEventListener('DOMContentLoaded', function () {
  const Extensions = (window.splide && window.splide.Extensions) ? window.splide.Extensions : {};
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // FV auto-scroll carousel
  const fvEl = document.querySelector('.fv-splide');
  if (fvEl && typeof Splide !== 'undefined') {
    new Splide(fvEl, {
      type:       'loop',
      drag:       'free',
      arrows:     false,
      pagination: false,
      perPage:    5,
      gap:        '1.5rem',
      autoScroll: reducedMotion ? false : {
        speed:        1,
        pauseOnHover: true,
        pauseOnFocus: true,
      },
      breakpoints: {
        781:  { perPage: 2 },
        1024: { perPage: 3 },
      },
    }).mount(Extensions);
  }

  // Voice carousel
  const voiceEl = document.querySelector('.voice-splide');
  if (voiceEl && typeof Splide !== 'undefined') {
    new Splide(voiceEl, {
      type:         'loop',
      autoplay:     !reducedMotion,
      interval:     8000,
      speed:        reducedMotion ? 0 : 600,
      perPage:      1,
      drag:         true,
      arrows:       false,
      pauseOnHover: true,
      pauseOnFocus: true,
    }).mount();
  }
});
