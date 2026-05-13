# 12.7 sec07 Flow — Checklist

**Comp source:** `input/design-comp/top/sec07-flow.png`
**Impl files:** `patterns/sec07-flow.php`, `style.css` (.sec-flow, .flow-step-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title "Flow" | visual | centered | centered | ✓ |
| 2 | Title font | impl | (global fix) | noto-sans-jp | **→ global fix** |
| 3 | Title font-size | impl | comp shows ~32px heading | 32px (.sec-flow .sec-title override) | ✓ |
| 4 | Background | visual | white | white (default) | ✓ |

## Flow Steps (×7)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 5 | Step layout | visual | pentagon left + text right | flex, gap 24px | ✓ |
| 6 | Pentagon shape | visual | downward-pointing pentagon | clip-path polygon | ✓ |
| 7 | Pentagon bg | visual | light gray | bg-sub (#F6F6F6) | ✓ |
| 8 | Pentagon size | impl | ~60×69px | 60px × 69px | ✓ |
| 9 | Pentagon shadow | visual | subtle blue shadow below | drop-shadow(0 1px 0 rgb(171,212,237)) | ✓ |
| 10 | Number font | impl | Jost | Jost | ✓ |
| 11 | Number font-size | impl | ~24px | 24px | ✓ |
| 12 | Number color | visual | accent blue | accent (#4EB0EA) | ✓ |
| 13 | Step title font-size | impl | ~16px (normal) / ~24px (lg) | sm (16px) / 24px (title_lg) | ✓ |
| 14 | Step title font-weight | impl | medium (500) | 500 | ✓ |
| 15 | Step body font-size | impl | ~16px | sm (16px) | ✓ |
| 16 | Step spacing | impl | ~32px between steps | margin-bottom: 32px | ✓ |

## Specific Steps

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 17 | Step 4 "デザイン" large title | visual | larger than other titles | title_lg = 24px | ✓ |
| 18 | Step 6 "納品" large title | visual | larger | title_lg = 24px | ✓ |
| 19 | Step 6 subtitle "アフターフォロー2週間" | visual | smaller text next to title | flow-step__subtitle, 16px, weight 400 | ✓ |

## Expert Review

**Review 1:**
- Item 2: Global sec-title font fix.
- All other items match the comp.
- **Result: PASS (after global fix) — 0 section-specific issues**
