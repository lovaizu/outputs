document.addEventListener('DOMContentLoaded', function () {
  const Extensions = (window.splide && window.splide.Extensions) ? window.splide.Extensions : {};

  // FV auto-scroll carousel
  const fvEl = document.querySelector('.fv-splide');
  if (fvEl && typeof Splide !== 'undefined') {
    new Splide(fvEl, {
      type:       'loop',
      drag:       'free',
      arrows:     false,
      pagination: false,
      perPage:    3,
      gap:        '1rem',
      autoScroll: {
        speed:        1,
        pauseOnHover: true,
        pauseOnFocus: true,
      },
      breakpoints: {
        781:  { perPage: 1 },
        1024: { perPage: 2 },
      },
    }).mount(Extensions);
  }

  // Voice carousel
  const voiceEl = document.querySelector('.voice-splide');
  if (voiceEl && typeof Splide !== 'undefined') {
    new Splide(voiceEl, {
      type:         'loop',
      autoplay:     true,
      interval:     8000,
      speed:        600,
      perPage:      1,
      drag:         true,
      pauseOnHover: true,
      pauseOnFocus: true,
    }).mount();
  }
});
