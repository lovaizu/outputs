# CSS Guidelines

## File structure

```
@charset "utf-8";

/* 1. Reset / base          — box-sizing, element defaults */
/* 2. Show/hide             — .pc / .sp */
/* 3. Layout utilities      — l-* classes */
/* 4. Typography            — headings, body, accents */
/* ◤◢ don't touch above ◤◢ */
/* 5. Components            — header, footer, sections */
/* 6. Accent utilities      — a-*, is-* */
```

Sections 1–4 (base) are shared across projects. Only sections 5–6 change per design.

## Breakpoint

Single breakpoint: `599px` (desktop-first).

```css
/* mobile override */
@media (max-width: 599px) { ... }
```

Do not add other breakpoints unless the design explicitly requires them.

## Color palette

Define as CSS custom properties at `:root` for each project. Reference values from the LP template:

```css
:root {
  --color-accent:  #336699;   /* #369 — links, icons, headers */
  --color-bg:      #f2efe6;   /* section backgrounds */
  --color-text:    #333333;   /* body text */
  --color-border:  #dddddd;   /* dividers, table borders */
  --color-danger:  #cc2200;   /* sale price, urgency text */
}
```

Do not hardcode hex values inside component rules — always use a variable.

## Typography

- Body: `font-size` on the root element, use `em` or `rem` for component-level overrides.
- Use `clamp()` for fluid heading sizes: `font-size: clamp(18px, 5.07vw, 27px)`.
- Apply `font-feature-settings: "palt"` and `-webkit-font-smoothing: antialiased` to all headings.
- `line-height: 1.618` for body, `1.41` for headings, `1.5` for UI elements (table cells, list items).

## Layout utilities (l-*)

These are shared and must not be modified per project.

| Class | Purpose |
|-------|---------|
| `.l-width` | Center column, max-width 808px, padding 0 24px |
| `.l-wide` | Full-bleed background (negative margin trick) |
| `.l-margin` | Vertical rhythm: 40px (desktop) / 24px (mobile) |
| `.l-padding` | Inner spacing: 40px (desktop) / 24px (mobile) |
| `.l-padding-s` | Compact inner spacing: 1.5em |
| `.l-grid-2` / `.l-grid-3` | CSS Grid: 2 or 3 equal columns |
| `.l-columns-2` / `.l-columns-3` | CSS columns for lists |

Do not override these classes inside component rules. Add a wrapper class instead.

## Component naming (BEM-style)

```css
.block { }
.block__element { }
.block__element--modifier { }
```

Keep component selectors flat (one level of nesting max in the selector chain). Exception: `:first-child`, `:last-child`, pseudo-elements.

```css
/* ok */
.faq__q::before { }
.faq__item:last-child { }

/* avoid — specificity creep */
.faq .faq__item .faq__q h4 { }
```

When specificity must be raised to override a base rule, prefix with the parent element:

```css
/* template pattern */
.main ul.bullet__ul li p { }
```

## Accent utilities (a-*)

Typography-only utilities applied via `class` in HTML:

| Class | Effect |
|-------|--------|
| `.a-bold` | `font-weight: 700` |
| `.a-big` / `.a-big1` / `.a-big2` / `.a-big3` | 21 / 24 / 27 / 30px (scaled on mobile) |
| `.a-marker` | Yellow highlight underline |
| `.a-red` | Danger color text |
| `.a-underline` | Plain underline |
| `.a-blink` | CSS blink animation (use sparingly) |
| `.a-center` | `text-align: center` |

Do not create new `a-*` classes without updating this table.

## State / decoration utilities (is-*)

| Class | Effect |
|-------|--------|
| `.is-shadow-s` | Subtle drop shadow |
| `.is-shadow-l` | Large drop shadow (filter) |
| `.is-round` | `border-radius: 8px` |

## Images

Base rule applies to all `<img>`:

```css
img {
  max-width: 100%;
  height: auto;
  vertical-align: middle;
}
```

For cover images inside fixed-height containers, use `object-fit: cover` on the `<img>`, not on the container.

## Animations

Use `animate.css` classes for entrance animations. Custom keyframes go at the bottom of `style.css`, after all component rules.

Do not use `transition` for layout-affecting properties (`width`, `height`, `padding`) — prefer `transform` and `opacity`.

## What not to put in style.css

- Inline styles belong in HTML only for third-party embeds (e.g. Vimeo aspect-ratio wrapper).
- JavaScript-driven class toggles use a separate `js-*` class, never a styled class.

## TODO

- [ ] Decide whether to adopt CSS custom properties project-wide or keep hex literals
- [ ] Define print styles policy
- [ ] Dark mode strategy (if any)
