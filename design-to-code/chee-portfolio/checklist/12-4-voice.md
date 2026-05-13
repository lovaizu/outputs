# 12.4 sec03 Voice — Checklist

**Comp source:** `input/design-comp/top/sec03-Voice.png`, `sevtion03-カルーセルコンテンツ.png`
**Impl files:** `patterns/sec03-voice.php`, `style.css` (.voice-card-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title "Voice" | visual | centered | centered | ✓ |
| 2 | Title font/style | impl | (same issue as 12.3) | noto-sans-jp | **→ global fix** |
| 3 | Background | visual | light blue | bg-main (#DCEFFB) | ✓ |

## Voice Card Layout

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 4 | Layout | visual | left column + right column | flex, gap 60px | ✓ |
| 5 | Left column width | impl | ~160px | 160px | ✓ |

## Left Column (number/photo/name)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 6 | Number "01" font-size | impl | ~70px (large) | 70px | ✓ |
| 7 | Number font-family | impl | Roboto Condensed (condensed numeral) | roboto-condensed | ✓ |
| 8 | Number font-weight | impl | medium | 500 | ✓ |
| 9 | Number color | visual | white | #fff | ✓ |
| 10 | "voice" label font-size | impl | ~24px | 24px | ✓ |
| 11 | "voice" label color | visual | white | #fff | ✓ |
| 12 | "voice" label letter-spacing | impl | slight | 0.05em | ✓ |
| 13 | Photo size | impl | ~140px circle | 140px × 140px, radius 50% | ✓ |
| 14 | Photo border | visual | white border | 3px solid #fff | ✓ |
| 15 | Role text font-size | impl | ~14px | 14px | ✓ |
| 16 | Name text font-size | impl | ~14px | 14px | ✓ |

## Right Column (catchphrase/body)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 17 | Catchphrase bg | visual | white box | #fff | ✓ |
| 18 | Catchphrase border-radius | visual | slightly rounded | 4px | ✓ |
| 19 | Catchphrase font | impl | handwritten (Zen Kurenaido) | zen-kurenaido | ✓ |
| 20 | Catchphrase font-size | impl | ~24px | 24px | ✓ |
| 21 | Catchphrase padding | impl | — | 10px 20px | ✓ |
| 22 | Body text font-size | impl | ~18px | 18px | ✓ |
| 23 | Body text line-height | impl | generous (~1.9) | 1.9 | ✓ |

## Carousel

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 24 | Pagination dots | visual | dots visible at bottom of section | Splide pagination (CSS defined) | ✓ |
| 25 | Card vertical padding | impl | ~48px | 48px top/bottom | ✓ |

## Expert Review

**Review 1:**
- Item 2 (title font): same global fix as 12.3.
- All other items match the comp.
- **Result: PASS (after global sec-title fix) — 0 section-specific issues**
