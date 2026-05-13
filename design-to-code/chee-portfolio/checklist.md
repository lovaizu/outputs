# デザインカンプ完全一致チェックリスト

ソース: カンプ画像（2x PNG）のみ。Figma JSONは参照しない。  
採点方式: 各項目 ✅合格 / ❌不合格 / ⚠️要確認  
計測基準: 本文テキスト=16px（2x画像で32px）を基準要素とし、2x計測値÷2で目安値を導出。

---

## 12.1 — Header

参照カンプ: `portforio.png`（トップ）, `下層ページ_制作実績一覧.png`（一覧）, `下層ページ_LP詳細_アートメイク.png`（詳細）

### H-1: ヘッダーコンテナ

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-1-1 | 背景色（トップ・一覧ページ） | 目視確認 | 白 #FFFFFF |
| H-1-2 | 背景色（詳細ページ single-works） | 目視確認 | bg-single #DEE3EC |
| H-1-3 | position: sticky + top: 0 | 実装値確認 | sticky / 0 |
| H-1-4 | z-index（コンテンツの上に重なる） | 実装値確認 | ≥ 100 |
| H-1-5 | ヘッダー下端に影・ボーダーなし | 目視確認 | box-shadow: none, border-bottom: none |
| H-1-6 | 上下 padding | 実装値確認 | 約 19px |
| H-1-7 | 左右 padding | 実装値確認 | 約 80px |
| H-1-8 | レイアウト: 3要素横並び（ロゴ / ナビ / ボタン） | 目視確認 | flex, space-between |

### H-2: ロゴ — "Chiaki Itoh"（1行目）

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-2-1 | font-family | 実装値確認 | Jost |
| H-2-2 | font-size | 実装値確認 | 20px（md） |
| H-2-3 | font-weight | 実装値確認 | 500 |
| H-2-4 | color | 目視確認 | accent #4EB0EA |
| H-2-5 | line-height | 実装値確認 | 1.3 |
| H-2-6 | font-style | 実装値確認 | normal |

### H-3: ロゴ — "LP Design, Meta Ads"（2行目）

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-3-1 | font-family | 実装値確認 | Jost |
| H-3-2 | font-size | 実装値確認 | 14px（xs） |
| H-3-3 | font-weight | 実装値確認 | 400 |
| H-3-4 | color | 目視確認 | accent #4EB0EA |
| H-3-5 | line-height | 実装値確認 | 1.3 |
| H-3-6 | テキスト内容: カンマの後にスペース | 実装値確認 | "LP Design, Meta Ads" |

### H-4: ロゴリンク

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-4-1 | 2行がひとつの `<a>` で囲まれている | 実装値確認 | 単一リンク |
| H-4-2 | リンク先 | 実装値確認 | "/" |
| H-4-3 | text-decoration | 目視確認 | none |
| H-4-4 | 1行目と2行目の間の gap | 目視確認 | 0（blockGap: 0 — 隙間なく密着） |

### H-5: ナビゲーション

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-5-1 | 項目: Home, Works, Voice, Service, Profile（5項目） | 目視確認 | 全5項目が水平に並ぶ |
| H-5-2 | font-family | 実装値確認 | Noto Sans JP |
| H-5-3 | font-size | 実装値確認 | 14px（xs） |
| H-5-4 | font-weight | 実装値確認 | 400 |
| H-5-5 | color | 目視確認 | text-primary #111111 |
| H-5-6 | text-decoration（通常時） | 目視確認 | none |
| H-5-7 | リンク先: Home→/, Works→/#works, Voice→/#voice, Service→/#service, Profile→/#profile | 実装値確認 | 各アンカーリンク |
| H-5-8 | PC表示（≥1025px）: 水平メニュー表示 | 目視確認 | display: flex |
| H-5-9 | タブレット以下（≤1024px）: ハンバーガーメニュー | 目視確認 | overlayMenu: mobile |

### H-6: Contact ボタン

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-6-1 | 背景色 | 目視確認 | text-primary #111111 |
| H-6-2 | テキスト色 | 目視確認 | 白 #FFFFFF |
| H-6-3 | テキスト内容 | 目視確認 | "Contact" |
| H-6-4 | border-radius（ピル型） | 目視確認 | 50px |
| H-6-5 | font-size | 実装値確認 | 15px |
| H-6-6 | font-weight | 実装値確認 | 700 |
| H-6-7 | 上下 padding | 実装値確認 | 8px |
| H-6-8 | 左右 padding | 実装値確認 | 24px |
| H-6-9 | box-shadow（薄い影） | 実装値確認 | 0 2px 2px rgba(0,0,0,0.2) 程度 |
| H-6-10 | リンク先 | 実装値確認 | /#contact |

### H-7: 全体レイアウト・バランス

| # | チェック項目 | 分類 | 期待値 |
|---|---|---|---|
| H-7-1 | ロゴが左端、ナビが中央〜右寄り、Contactが右端 | 目視確認 | space-between 配置 |
| H-7-2 | 各要素が垂直方向に中央揃え | 目視確認 | align-items: center 相当 |
| H-7-3 | ヘッダー高さがカンプと一致（ロゴ+padding で適切な高さ） | 目視確認 | — |

### エキスパートレビュー結果

**レビュー実施:** 3ページのカンプ画像（トップ/一覧/詳細）を再照合

| 観点 | 結果 |
|---|---|
| 見落とし | なし — ヘッダー内の全UI要素（ロゴ2行、ナビ5項目、Contactボタン、コンテナ属性）をカバー |
| 誤読 | なし — 2x画像からの値導出は基準比率と整合 |
| 分類ミス | H-6-9 box-shadow: 目視→実装値に修正済み |

**指摘ゼロ確認: OK — ステップ3へ進む**
