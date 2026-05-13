# 12.3 sec02 Works — Checklist

**Comp source:** `input/design-comp/top/sec02-works.png`
**Impl files:** `patterns/sec02-works.php`, `style.css` (.sec-works, .work-card-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title text | visual | "Works" | "Works" | ✓ |
| 2 | Title alignment | visual | centered | text-align: center | ✓ |
| 3 | Title font-family | impl | design.md says roboto-condensed for EN headings; comp shows sans-serif with letter-spacing (判別不能: cannot distinguish from noto-sans-jp at comp resolution) | noto-sans-jp | **?** |
| 4 | Title font-size | impl | ~36px (2x: ~72px) | xl = 36px | ✓ |
| 5 | Title font-weight | impl | medium | 500 | ✓ |
| 6 | Title color | visual | dark | text-primary (#111) | ✓ |
| 7 | Title letter-spacing | visual | appears to have some tracking | none set | **?** |
| 8 | Title margin-bottom | impl | ~48px | 48px | ✓ |

## Work Card Structure (×2 cards)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 9 | Card 1 header text | visual | "[眉毛アートメイクLP] TWC ART MAKE STUDIO様" | dynamic (data) | ✓ |
| 10 | Header bottom border | visual | thin accent blue line | 1px solid accent (#4EB0EA) | ✓ |
| 11 | Arrow icon (right) | visual | circle with right arrow, accent blue | SVG circle + arrow, stroke #4EB0EA | ✓ |
| 12 | Arrow icon size | impl | ~24px | 24px | ✓ |
| 13 | Pills below header | visual | accent blue bg, white text, rounded | accent bg, #fff text, 4px radius | ✓ |
| 14 | Pill font-size | impl | ~15px | 15px | ✓ |
| 15 | Pill padding | impl | — | 5px 10px | ✓ |
| 16 | Description text | visual | dark, regular weight | sm (16px), text-primary | ✓ |
| 17 | Description line-height | impl | generous | 1.8 | ✓ |
| 18 | Thumbnail image | visual | full-width, slightly rounded | width: 100%, border-radius: 4px | ✓ |
| 19 | Card spacing | impl | ~48px between cards | margin-bottom: 48px | ✓ |

## CTA Button

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 20 | Button text | visual | "制作実績の一覧へ" + arrow icon | same | ✓ |
| 21 | Button style | visual | outline, accent blue border, pill shape | border 1px solid accent, radius 999px | ✓ |
| 22 | Button alignment | visual | centered | text-align: center | ✓ |
| 23 | Button font-size | impl | ~16px | sm (16px) | ✓ |
| 24 | Button padding | impl | — | 12px 32px | ✓ |
| 25 | Button margin-top | impl | ~48px | 48px | ✓ |

## Section Padding

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 26 | Section top padding | impl | ~80px | 80px | ✓ |
| 27 | Section bottom padding | impl | ~80px | 80px | ✓ |
| 28 | Section background | visual | white | white (default) | ✓ |

## Expert Review

**Review 1:**
- Items 3 & 7: Section title font-family and letter-spacing. Design spec says EN section headings use `roboto-condensed`. Comp text has visible letter-spacing. Current impl uses `noto-sans-jp` with no letter-spacing.
  - **Decision:** This applies to ALL section titles (Works/Voice/Service/Profile/Flow/Contact). Will fix font-family to `roboto-condensed` weight 300, and add `letter-spacing: 0.15em` in `.sec-title` CSS. These values are estimated — exact values are 判別不能 from the comp resolution.
- All other items match.
- **Result: 2 issues → fix sec-title font + letter-spacing (global fix for all sections)**
