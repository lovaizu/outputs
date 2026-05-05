# chee-portfolio Steering

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Task List (workflow order)

- [ ] 0. Verify Figma JSON design tokens against guideline values → finalize token table
- [ ] 1. `theme.json` — colors, fonts, fontSizes, layout, templateParts declaration
- [ ] 2. `style.css` / `index.php` / `functions.php` — theme header, Works CPT + taxonomy, Pods field registration, Splide.js enqueue
- [ ] 3. Font assets — download WOFF2 via curl → `assets/fonts/` (4 families)
- [ ] 4. Template parts — `parts/header.html`, `parts/footer.html`
- [ ] 5. Templates — `templates/front-page.html`, `templates/archive-works.html`, `templates/single-works.html`
- [ ] 6. Block patterns × 8 — sec01-fv, sec02-works, sec03-voice, sec04-service, sec05-cta, sec06-profile, sec07-flow, sec08-contact
- [ ] 7. Splide.js init — `assets/js/splide-init.js` (FV auto-scroll, Voice carousel, single-works gallery swipe)
- [ ] 8. Contact form spec — Fluent Forms field definition + shortcode doc
- [ ] 9. Responsive CSS — Navigation breakpoint override (1024px), FV mockup sizing
- [ ] 10. Expert review (WordPress / HTML / CSS / Accessibility sub-agents) + guideline compliance → fix all findings
- [ ] 11. Handoff doc — Local environment setup, plugin install steps, font swap guide

> Steps 0–11 complete one design cycle. Repeat from step 0 for each new design input.

## Decisions

| Date | Decision |
|------|----------|
| 2026-05-06 | ACF Free → **Pods + Meta Field Block** (fully free; better Query Loop support; no Pro tier needed) |
| 2026-05-06 | Contact Form 7 → **Fluent Forms** (CF7 entered feature-freeze in 2026; Fluent Forms free covers Ajax, conditional logic, privacy checkbox) |
| 2026-05-06 | Fonts self-hosted as WOFF2 in `assets/fonts/`; downloaded via curl from Google Fonts API |
| 2026-05-06 | Theme code output: `design-to-code/chee-portfolio/theme/` in this repo |
| 2026-05-06 | Figma JSON used to verify guideline token values before generating theme.json; discrepancies flagged for user confirmation |
| 2026-05-06 | AI scope: code generation only; environment setup documented in handoff doc |

## Key Specs (from guideline)

### Pages
| Page | Template | Notes |
|------|----------|-------|
| Home | front-page | 8-section LP-style, single page |
| Works archive | archive-works | Card grid, Query Loop |
| Works single | single-works | LP detail, Splide gallery on mobile |

### Home Sections
| # | Section | Type |
|---|---------|------|
| 01 | FV | Static + Splide auto-scroll |
| 02 | Works | Dynamic (Query Loop, postType: works) |
| 03 | Voice | Static pattern + Splide carousel |
| 04 | Service | Static pattern |
| 05 | CTA | Static pattern |
| 06 | Profile | Static pattern |
| 07 | Flow | Static pattern |
| 08 | Contact | Fluent Forms shortcode |
| — | Header / Footer | Template parts |

### Design Tokens
| Token | Value |
|-------|-------|
| bg-main | #DCEFFB |
| bg-sub | #F6F6F6 |
| white | #FFFFFF |
| text-primary | #111111 |
| text-secondary | #333333 |
| accent | #4EB0EA |
| highlight | #FBFF92 |
| border | #D7D7D7 |
| contentSize | 840px |
| wideSize | 1100px |

### Fonts
| slug | Family | Weights | Use |
|------|--------|---------|-----|
| noto-sans-jp | Noto Sans JP | 100/400/500/700 | Japanese body |
| roboto-condensed | Roboto Condensed | 300/500 | EN section headings |
| jost | Jost | 400/500 | EN subtext |
| zen-kurenaido | Zen Kurenaido | 400 | Decorative handwritten |

### Plugins / Libraries
| Name | Use | Cost |
|------|-----|------|
| Pods | Works CPT + taxonomy + custom fields | Free |
| Meta Field Block | Display custom fields in Query Loop | Free |
| Fluent Forms | Contact form | Free |
| Splide.js v4 + Auto Scroll | FV slider, Voice carousel, gallery | Free (MIT) |

## Directory Layout

```
design-to-code/chee-portfolio/
├── steering.md          ← this file
├── input/               ← design files as received (do not modify)
│   ├── figma-to-wp-guideline.md
│   └── chee-portforio/
│       ├── design/      ← design PNGs
│       ├── structures/  ← Figma JSON exports
│       └── images/      ← image assets
└── theme/               ← WordPress block theme output
    ├── style.css
    ├── theme.json
    ├── index.php
    ├── functions.php
    ├── templates/
    ├── parts/
    ├── patterns/
    └── assets/
        ├── fonts/
        └── js/
```

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- Input committed at: `design-to-code/chee-portfolio/input/`
- Resume from: Task 0 (verify Figma JSON tokens)
