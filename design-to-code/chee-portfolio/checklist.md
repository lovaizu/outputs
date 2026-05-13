# デザインカンプ完全一致チェックリスト

ソース: カンプ画像（2x PNG）のみ。Figma JSONは参照しない。  
採点方式: 各項目 ✅合格 / ❌不合格 / ⚠️要確認

---

## Task 12.1 — Header（画像ベース再検証）

カンプ画像: `portforio.png` 上端 0–160px（2x）  
現実装スクショ: `test-results/header-current.png`

### 目視確認（カンプ vs スクショの見た目比較）

| # | 観点 | カンプの見え方 |
|---|---|---|
| H-V1 | 全体レイアウト | ロゴ左 / ナビ中央寄り / Contactボタン右 の3ゾーン構成 |
| H-V2 | ロゴテキスト色 | 水色（accent blue） |
| H-V3 | ナビリンクテキスト色 | 濃い黒〜ダークグレー |
| H-V4 | ナビリンクhover/active表現 | 「Works」にテキスト幅程度の短い下線あり |
| H-V5 | Contactボタン形状 | ダーク背景のpill型、白テキスト |
| H-V6 | Contactボタンの影 | 微細なdrop shadow |
| H-V7 | ヘッダー背景色（トップ・一覧） | 白 |
| H-V8 | 垂直方向の中央揃え | ロゴ・ナビ・ボタンがヘッダー高さの中央に揃っている |

### 実装値確認（PlaywrightのgetComputedStyleで検証）

| # | 観点 | 期待値（カンプ2x÷2で算出） | 現在値 | 判定 |
|---|---|---|---|---|
| H-C1 | ヘッダー padding-top / bottom | ~19px | 19px | ✅ |
| H-C2 | ヘッダー padding-left / right | ~80px | 80px | ✅ |
| H-C3 | ロゴ名 font-size | 20px | 20px | ✅ |
| H-C4 | ロゴ名 font-weight | 500 | 500 | ✅ |
| H-C5 | ロゴ名 font-family | Jost | Jost, sans-serif | ✅ |
| H-C6 | ロゴ名 color | #4EB0EA | rgb(78,176,234) | ✅ |
| H-C7 | ロゴサブ font-size | 14px | 14px | ✅ |
| H-C8 | ロゴサブ font-weight | 400 | 400 | ✅ |
| H-C9 | ロゴサブ color | #4EB0EA | rgb(78,176,234) | ✅ |
| H-C10 | ナビリンク font-size | 14px | 14px | ✅ |
| H-C11 | ナビリンク font-weight | 400 | 400 | ✅ |
| H-C12 | ナビリンク font-family | Noto Sans JP | "Noto Sans JP", sans-serif | ✅ |
| H-C13 | ナビリンク color | #111111 | rgb(17,17,17) | ✅ |
| H-C14 | ナビリンク gap | ~24px | 24px | ✅ |
| H-C15 | ナビリンク hover時 underline | text-decoration: underline | underline, 1px solid, offset 4px | ✅ |
| H-C16 | Contact font-size | 15px | 15px | ✅ |
| H-C17 | Contact font-weight | 700 | 700 | ✅ |
| H-C18 | Contact background-color | #111111 | rgb(17,17,17) | ✅ |
| H-C19 | Contact color | #FFFFFF | rgb(255,255,255) | ✅ |
| H-C20 | Contact border-radius | 50px | 50px | ✅ |
| H-C21 | Contact padding | 8px 24px | 8px 24px | ✅ |
| H-C22 | Contact box-shadow | 0 2px 2px rgba(0,0,0,0.2) | 0px 2px 2px rgba(0,0,0,0.2) | ✅ |

### 採点サマリ

- 目視: 8/8 ✅
- 実装値: 22/22 ✅
- **全項目合格** — hover underline 追加済み（style.css）

