# 12.1 Header — Checklist

**Comp source:** `input/design-comp/top/sec01-fv.png` (top ~80px area)
**Impl files:** `parts/header.html`, `style.css` (.site-header)

## Logo Area (left)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | "Chiaki Itoh" font-family | impl | Jost (sans-serif) | Jost | ✓ |
| 2 | "Chiaki Itoh" font-size | impl | ~20px (2x comp: ~40px) | md = 20px | ✓ |
| 3 | "Chiaki Itoh" font-weight | impl | medium (500) | 500 | ✓ |
| 4 | "Chiaki Itoh" color | visual | accent blue | accent (#4EB0EA) | ✓ |
| 5 | Subtitle "LP Design, Meta Ads" font-size | impl | ~14px | xs = 14px | ✓ |
| 6 | Subtitle font-weight | impl | regular (400) | 400 | ✓ |
| 7 | Subtitle color | visual | accent blue | accent (#4EB0EA) | ✓ |
| 8 | Logo wraps in link to "/" | impl | — | ✓ (site-logo-link) | ✓ |
| 9 | Line-height between name and subtitle | impl | tight, no gap | line-height: 1.3, blockGap: 0 | ✓ |

## Navigation (center-right)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 10 | Nav items | visual | Home / Works / Voice / Service / Profile | same | ✓ |
| 11 | Nav font-family | impl | sans-serif (Noto Sans JP) | noto-sans-jp | ✓ |
| 12 | Nav font-size | impl | ~14px | xs = 14px | ✓ |
| 13 | Nav font-weight | impl | 400 | 400 | ✓ |
| 14 | Nav text color | visual | dark | text-primary (#111) | ✓ |

## Contact Button (far right)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 15 | Button bg | visual | dark/black | text-primary (#111) | ✓ |
| 16 | Button text | visual | white "Contact" | white | ✓ |
| 17 | Button shape | visual | pill/rounded | border-radius: 50px | ✓ |
| 18 | Button shadow | visual | subtle shadow | 0 2px 2px rgba(0,0,0,0.2) | ✓ |
| 19 | Button font-size | impl | ~15px | 15px | ✓ |
| 20 | Button font-weight | impl | bold | 700 | ✓ |
| 21 | Button padding | impl | compact | 8px 24px | ✓ |

## Overall

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 22 | Background | visual | white | #fff | ✓ |
| 23 | Header shadow | visual | none | box-shadow: none | ✓ |
| 24 | Horizontal padding | impl | ~80px | 80px | ✓ |
| 25 | Vertical padding | impl | ~19px | 19px top/bottom | ✓ |
| 26 | Sticky position | impl | — (not visible in static comp) | sticky, top: 0 | — |

## Expert Review

**Review 1:**
- All 26 items checked against comp — no discrepancies found.
- Header implementation matches the design comp.
- **Result: PASS — 0 issues**
