# 12.6 sec06 Profile — Checklist

**Comp source:** `input/design-comp/top/sec06-Profile.png`
**Impl files:** `patterns/sec06-profile.php`, `style.css` (.sec-profile, .profile-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title "Profile" | visual | centered | centered | ✓ |
| 2 | Title font | impl | (global fix) | noto-sans-jp | **→ global fix** |
| 3 | Background | visual | light blue | bg-main (#DCEFFB) | ✓ |

## White Card (bio + photo)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 4 | Card bg | visual | white | white | ✓ |
| 5 | Card border-radius | visual | rounded corners | 16px | ✓ |
| 6 | Card padding | impl | ~40px | 40px | ✓ |
| 7 | Card layout | visual | left bio text + right photo | flex, nowrap, top-aligned | ✓ |

## Bio Text (left)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 8 | Bio font-size | impl | ~18px | 18px | ✓ |
| 9 | Bio line-height | impl | generous (~1.8) | 1.8 (global) | ✓ |
| 10 | Bio paragraphs | visual | 3 paragraphs | 3 paragraphs | ✓ |

## Profile Photo (right)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 11 | Photo shape | visual | circle | border-radius: 100% | ✓ |
| 12 | Photo size | impl | ~145px | 145px × 145px | ✓ |
| 13 | "WEBデザイナー" below photo | visual | centered, medium weight | text-align center, 500, md (20px) | ✓ |
| 14 | "伊藤 千晶" below role | visual | centered, medium weight | text-align center, 500, sm (16px) | ✓ |
| 15 | "Chiaki Itoh" below name | visual | accent blue, Jost | accent, Jost, xs (14px) | ✓ |

## Detail Rows

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 16 | Row layout | visual | label | value, separated by dotted line | flex, nowrap; dotted border-bottom | ✓ |
| 17 | Label width | impl | ~180px | 180px | ✓ |
| 18 | Label color | visual | gray/secondary | text-secondary (#333) | ✓ |
| 19 | Row 1 label | visual | "ご提供できること" | same | ✓ |
| 20 | Row 2 label | visual | "居住地" | same | ✓ |
| 21 | Row 3 label | visual | "趣味" | same | ✓ |
| 22 | Dotted separator | visual | rows 1-2 have dotted bottom border, row 3 does not | profile-row-sep class on rows 1-2 | ✓ |
| 23 | Separator color | impl | gray | rgb(153,153,153) | ✓ |
| 24 | Detail rows top padding | impl | ~32px from card bottom | padding-top: 32px | ✓ |

## Expert Review

**Review 1:**
- Item 2: Global sec-title font fix.
- All other items match the comp.
- **Result: PASS (after global fix) — 0 section-specific issues**
