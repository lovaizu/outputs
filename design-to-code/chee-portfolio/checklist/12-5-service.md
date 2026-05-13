# 12.5 sec04 Service — Checklist

**Comp source:** `input/design-comp/top/sec04-Service.png`
**Impl files:** `patterns/sec04-service.php`, `style.css` (.sec-service, .service-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title "Service" | visual | centered | centered | ✓ |
| 2 | Title font | impl | (global fix) | noto-sans-jp | **→ global fix** |
| 3 | Title color | visual | dark | text-secondary (#333) | **?** |

## Blue Container

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 4 | Container bg | visual | light blue | bg-main (#DCEFFB) | ✓ |
| 5 | Container border-radius | visual | large rounded corners | 40px | ✓ |
| 6 | Container padding | impl | ~48px vertical, ~32px horizontal | 48px top/bottom, 32px left/right | ✓ |

## Service Cards (×3)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 7 | Card bg | visual | white | white | ✓ |
| 8 | Card border-radius | visual | rounded | 8px | ✓ |
| 9 | Card padding | impl | — | 24px | ✓ |
| 10 | Card layout | visual | left icon + right text | flex, nowrap, top-aligned | ✓ |
| 11 | Label pill bg | visual | accent blue | accent (#4EB0EA) | ✓ |
| 12 | Label pill text | visual | white, bold | white, 700 | ✓ |
| 13 | Label pill font-size | impl | ~16px | sm (16px) | ✓ |
| 14 | Icon images | visual | line art illustrations | plan.webp, do.webp, action.webp | ✓ |
| 15 | Description font-size | impl | ~16px | sm (16px) | ✓ |
| 16 | Left column width | impl | ~150px | 150px | ✓ |

## LP制作 Card — Tool Icons

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 17 | "利用ツール" label | visual | present | present | ✓ |
| 18 | Tool icons | visual | Figma, Ps, Ai, WP, /Studio | 5 SVG/text badges | ✓ |

## Section Background

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 19 | Section bg | visual | white | white (default) | ✓ |
| 20 | Section padding | impl | ~80px top/bottom | 80px | ✓ |

## Expert Review

**Review 1:**
- Item 2: Global sec-title font fix.
- Item 3: Title color — comp shows dark text. Current impl uses `text-secondary` (#333) whereas all other sections use `text-primary` (#111). Looking at the comp, the "Service" title appears similarly dark to other section titles.
  - **Fix: change to text-primary to be consistent with other sections.**
- All other items match.
- **Result: 1 section-specific issue (title color) + global sec-title font fix**
