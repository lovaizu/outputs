# HTML Guidelines

## Document setup

```html
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ページタイトル</title>
  ...
</head>
```

- Always `lang="ja"` on `<html>`.
- Always include `noindex, nofollow` for LPs (prevent accidental indexing before release).

## CSS load order

```html
<link rel="stylesheet" href="css/normalize.min.css">
<link rel="stylesheet" href="css/hover-min.css">
<link rel="stylesheet" href="css/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="style.css">
```

Order: reset → vendor → site CSS. Site CSS is always last.

## JavaScript load order

Load jQuery and BudouX in `<head>`. Load custom scripts after jQuery:

```html
<script src="https://unpkg.com/budoux/bundle/budoux-ja.min.js"></script>
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/function.js"></script>
```

Third-party inline scripts (e.g. Vimeo embed) go inline at the point of use.

## Semantic structure

Use structural elements: `<header>`, `<main>`, `<section>`, `<footer>`, `<figure>`, `<nav>`, `<blockquote>`.

One `<h1>` per page. Heading levels must not skip (h1 → h2 → h3, not h1 → h3).

## PC / SP visibility

Use `.pc` / `.sp` utility classes for content that differs structurally between breakpoints (not just visually):

```html
<div class="pc"><!-- desktop version --></div>
<div class="sp"><!-- mobile version --></div>
```

Do not use inline `style="display:none"`.

## Responsive images

Use `<picture>` with `<source>` when the image file itself differs per breakpoint:

```html
<picture>
  <source srcset="img/btn--sp.png" media="(max-width: 599px)">
  <source srcset="img/btn.png"     media="(min-width: 598px)">
  <img src="img/btn.png" alt="ボタンの説明">
</picture>
```

Always include a meaningful `alt`. Use empty `alt=""` only for decorative images.

## Japanese text wrapping

Wrap body text and headings with `<budoux-ja>` to prevent awkward line breaks:

```html
<h3 class="h3"><budoux-ja>ここに見出しが入ります。</budoux-ja></h3>
<p><budoux-ja>ここに本文が入ります。</budoux-ja></p>
```

Do not apply to short single-line labels or button text.

## Class naming

Follow the component CSS naming convention (BEM-style):

| Pattern | Example | Use |
|---------|---------|-----|
| `block` | `.faq` | Component root |
| `block__element` | `.faq__q` | Child element |
| `l-*` | `.l-width`, `.l-margin` | Layout utility |
| `a-*` | `.a-red`, `.a-bold` | Accent/typography utility |
| `is-*` | `.is-shadow-l` | Visual state |

Do not invent new prefixes. Add new utilities under the appropriate prefix.

## Forms

Use `type="email"` for email inputs (enables native validation). Never use `type="image"` for buttons; use `<button>` instead:

```html
<!-- avoid -->
<input class="form__btn" type="image" src="img/form__btn.png" />

<!-- prefer -->
<button class="form__btn" type="submit">
  <img src="img/form__btn.png" alt="送信">
</button>
```

## TODO

- [ ] Accessibility checklist (focus management, ARIA roles, color contrast)
- [ ] `<head>` order for OGP/SNS meta tags
- [ ] Font preload strategy for Noto Sans JP
