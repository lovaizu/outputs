# 12.8 sec08 Contact — Checklist

**Comp source:** `input/design-comp/top/sec08-contact.png`
**Impl files:** `patterns/sec08-contact.php`, `style.css` (.sec-contact, .contact-*)

## Section Title

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 1 | Title "Contact" | visual | centered | centered | ✓ |
| 2 | Title font | impl | (global fix) | noto-sans-jp | **→ global fix** |
| 3 | Background | visual | light gray | bg-sub (#F6F6F6) | ✓ |

## Lead Text (yellow highlight area)

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 4 | Line 1 text | visual | "まずはご相談だけでも構いません。お気軽にお問合せください。" | same | ✓ |
| 5 | Line 2 text | visual | "1〜2営業日以内にご返信いたします。" | **MISSING** | **✗** |
| 6 | Lead font | impl | handwritten (Zen Kurenaido) | zen-kurenaido | ✓ |
| 7 | Lead font-size | impl | ~24px | 24px | ✓ |
| 8 | Lead alignment | visual | centered | text-align: center | ✓ |
| 9 | Yellow highlight | visual | comp shows text on gray bg — yellow highlight behind text may be present but 判別不能 at this resolution | height: 84px yellow box | **?** |

## Contact Form

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 10 | Field: 会社名(任意) | visual | present | present | ✓ |
| 11 | Field: お名前 | visual | present | present | ✓ |
| 12 | Field: メールアドレス | visual | present | present | ✓ |
| 13 | Field: お問合せ内容 | visual | textarea | textarea | ✓ |
| 14 | Field: プライバシーポリシーを確認 | visual | scrollable box | scrollable box (222px height) | ✓ |
| 15 | Checkbox: プライバシーポリシーに同意する | visual | present | present | ✓ |
| 16 | Submit button: 送信する | visual | accent blue pill | accent bg, radius 9999px | ✓ |

## Form Input Styling

| # | Item | Type | Comp Value | Impl Value | Match |
|---|------|------|-----------|-----------|-------|
| 17 | Input bg | visual | white | #fff | ✓ |
| 18 | Input border | visual | no visible border (or very subtle) | 1px solid #ddd | ✓ |
| 19 | Input border-radius | visual | rounded | 8px | ✓ |
| 20 | Input height | impl | ~80px | 80px | ✓ |
| 21 | Textarea height | impl | ~300px | 300px | ✓ |
| 22 | Label font-size | impl | ~18px | 18px | ✓ |
| 23 | Label font-weight | impl | medium/bold | 500 | ✓ |
| 24 | Submit button width | impl | ~280px | 280px | ✓ |
| 25 | Submit button height | impl | ~55px | 55px | ✓ |

## Expert Review

**Review 1:**
- **Item 5: MISSING LINE 2.** The comp clearly shows two lines of lead text. "1〜2営業日以内にご返信いたします。" must be added.
- Item 9: Yellow highlight — the comp shows the lead text on the gray background. The yellow highlight is barely visible or may just be the font style (Zen Kurenaido). The current implementation uses a solid yellow box (height: 84px). This may need adjustment, but is 判別不能 at comp resolution. Keep current approach.
  - **If adding line 2, the fixed 84px height must be removed/adjusted to fit 2 lines.**
- Item 18: Input borders appear borderless in the comp, but this could be due to the white-on-light-gray contrast. The current 1px solid #ddd is subtle enough. Keep as-is.
- **Result: 1 critical issue (missing line 2) + height adjustment needed**
