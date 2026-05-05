# JavaScript Guidelines

## Stack

- **jQuery 3.6.0** — DOM manipulation, event handling, AJAX
- **BudouX** — Japanese line-break optimization (loaded as a custom element, no JS API call needed)
- **Vimeo Player.js** — loaded inline via the Vimeo embed snippet; do not bundle separately

No build step. All JS is vanilla ES5-compatible or jQuery-based, delivered as plain `.js` files.

## File layout

```
js/
  jquery-3.6.0.min.js   ← vendored, do not edit
  function.js           ← all custom behavior for this LP
```

Do not split custom code across multiple files unless the file exceeds ~300 lines.

## function.js structure

Wrap everything in a document-ready handler:

```js
$(function () {
  // scroll behavior
  // sticky button
  // form interaction
  // animation triggers
});
```

No global variables. No inline `<script>` blocks in HTML except third-party embeds.

## Scroll

Smooth scroll to anchor:

```js
$('a[href^="#"]').on('click', function (e) {
  e.preventDefault();
  var target = $($(this).attr('href'));
  if (target.length) {
    $('html, body').animate({ scrollTop: target.offset().top }, 400);
  }
});
```

CSS `scroll-behavior: smooth` is already set on `html` — use it for native browser scroll. The jQuery fallback above handles older browsers and precise offset control (e.g. accounting for sticky header height).

## Animations (animate.css)

Trigger entrance animations on scroll using IntersectionObserver (preferred) or a scroll event fallback:

```js
var observer = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      entry.target.classList.add('animate__animated', 'animate__fadeIn');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.js-animate').forEach(function (el) {
  observer.observe(el);
});
```

Mark animatable elements with `js-animate` in HTML. Never add `animate__animated` statically in HTML unless the animation should fire on page load.

## Sticky footer button

Show/hide based on scroll position:

```js
$(window).on('scroll', function () {
  if ($(this).scrollTop() > 300) {
    $('.sticky-footer-btn').addClass('is-visible');
  } else {
    $('.sticky-footer-btn').removeClass('is-visible');
  }
});
```

CSS controls visibility via `.is-visible`. JavaScript only toggles the class.

## Forms

- Do not submit forms with jQuery `.ajax()` unless explicitly required — use native form submission or the embed script from the form provider (e.g. Kartra, Kajabi).
- Validate required fields before submission:

```js
$('form').on('submit', function (e) {
  var email = $(this).find('input[type="email"]').val();
  if (!email) {
    e.preventDefault();
    alert('メールアドレスを入力してください。');
  }
});
```

## What not to do

- Do not use `document.write`.
- Do not load jQuery twice.
- Do not put behavior in `style.css` via `content` tricks.
- Do not use `var` at module scope — keep variables inside the ready handler.

## TODO

- [ ] Decide whether to migrate from jQuery to vanilla JS for new projects
- [ ] Define error handling policy for failed form submissions
- [ ] Accessibility: keyboard navigation and focus trap for any modal/overlay
