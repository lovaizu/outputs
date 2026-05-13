# 12.9 Footer — Checklist

**Comp source:** `input/design-comp/top/sec09-footer.png`
**Impl files:** `parts/footer.html`, `style.css` (.site-footer, .footer-*)

## Logo Area (left)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | "Chiaki Itoh" text | visual | dark text | text-secondary (#333) | ✓ |
| 2 | "Chiaki Itoh" font | impl | Jost | Jost, 500, md (20px) | ✓ |
| 3 | Subtitle "LP Design,Meta Ads" | visual | dark text, smaller | text-secondary, Jost, 400, xs (14px) | ✓ |
| 4 | Logo wraps in link | impl | — | ✓ (site-logo-link) | ✓ |

## Navigation (right)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 5 | Nav items | visual | ホーム｜制作実績｜お客様の声｜サービス｜プロフィール｜お問合せ | same (6 items) | ✓ |
| 6 | Nav separator | visual | "|" between items | CSS ::before "|" | ✓ |
| 7 | Nav font-size | impl | ~14px | xs (14px) | ✓ |
| 8 | Nav font-weight | impl | 400 | 400 | ✓ |
| 9 | Nav text color | visual | dark | text-primary (#111) | ✓ |

## Social Icons (right, below nav)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 10 | X (Twitter) icon | visual | present | SVG | ✓ |
| 11 | LinkedIn icon | visual | present | SVG | ✓ |
| 12 | Icons alignment | visual | right-aligned | justify-content: flex-end | ✓ |
| 13 | Icons color | visual | dark/black | currentColor (text-primary) | ✓ |
| 14 | Icons gap | impl | ~12px | gap: 12px | ✓ |

## Overall

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 15 | Background | visual | light blue | bg-main (#DCEFFB) | ✓ |
| 16 | Layout | visual | left logo, right nav+social | flex, space-between | ✓ |
| 17 | Vertical padding | impl | ~48px | 48px | ✓ |

## Expert Review

**Review 1:**
- All 17 items match the comp.
- **Result: PASS — 0 issues**
