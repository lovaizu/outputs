# chee-portfolio Steering

## Design Specification

See [`design.md`](design.md) for architecture, CI/CD pipeline, E2E strategy, and WordPress design decisions. This file tracks tasks and session state only.

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Verification Policy

**All browser verification uses Playwright** (not curl).

---

## 完了タスク（Task 0〜9）

| # | 内容 | 成果物 |
|---|---|---|
| 0 | Design spec | `design.md` |
| 1 | Local dev environment | docker-compose, WP+プラグイン, README |
| 2 | Theme foundation | `theme.json`, `functions.php`, CPTs, Splide, フォント |
| 2.5 | Playwright setup | `e2e/`, smoke test |
| 3 | Fixture data | seed.sh, Works×3, Voice×4 |
| 4 | Template parts | `parts/header.html`, `parts/footer.html`, `templates/index.html` |
| 5 | Templates | front-page / archive-works / single-works |
| 6 | Block patterns × 8 | sec01〜sec08 パターン, 19 E2E tests pass |
| 7 | Styles + JS | `style.css` 全面再構築, `splide-init.js` |
| 8 | Code review | a11y, focus, reduced-motion, skip-link |
| 9 | Design fidelity | Figma完全一致（9.1〜9.6.12）, 19 E2E tests pass |

---

## Task List

<!-- paused: 2026-05-13 — next: #12.1 Header（画像ベース再検証） -->

### Task 10 — リファクタリング（不要ファイル整理）

ユーザー指示に従い進める。WP初心者向けに説明しながら作業する。

- [x] 10.1 — 不要な画像・アセットを洗い出してリスト提示（不要ファイルなし）
- [x] 10.2 — 使用中のアセット整理（フォントサブディレクトリ化・PNG→WebP変換）
- [x] 10.3 — 未使用CSS・PHP・JSコードの削除（43行削除）
- [x] 10.4 — seed.sh / fixture data 整合性確認・統合（fix-works-data.php削除・works.json構造化・detail_images対応・w12新規追加）

---

### Task 11 — 最終状態整理（git管理・WP管理画面データ）

ユーザー指示に従い進める。何をコードで管理し、何を管理画面から入力するかを整理する。

- [x] 11.1 — git対象 / 対象外の整理（`.gitignore` 見直し・方針説明）
- [x] 11.2 — WP管理画面から入力するデータの整理（Works/Voice投稿、メニュー、フォーム設定など）
- [x] 11.3 — 初期データ（`seed.sh`）を本番用コンテンツに近い状態に更新（post_title/client_name/post_excerpt/構造化post_content修正済み）
- [x] 11.4 — ローカル → 本番への移行手順を `README.md` に追記

---

### Task 12 — デザインカンプ完全一致（画像ベース再検証）

**方針:** Figma JSONは信頼しない。デザインカンプ画像（2x PNG）を唯一の正とする。

**セクション単位の進め方（各セクション共通）:**
1. カンプ画像からUI要素チェックリスト作成
   - 項目を **目視確認**（カンプ vs スクショの見た目比較）と **実装値確認**（Playwrightの`getComputedStyle`で取得して検証）に分類
   - **目視確認:** 色、形状、角丸、レイアウト構造、要素間の比率
   - **実装値確認:** 絶対px値、font-size、line-height、letter-spacing、font-weight、box-shadow、opacity
   - 実装値は基準要素（本文テキスト=16px）からの比率で算出し、2x画像の計測値÷2で目安値を導出
2. UX/UIデザインエキスパートがカンプ画像＋チェックリストをレビュー → 指摘ゼロまで改善
3. チェックリスト通りに実装修正
4. Playwrightスクショ（目視項目）＋`getComputedStyle`（実装値項目）でUX/UIデザインエキスパートが検証 → 指摘ゼロまで改善

**カンプ画像:** `input/chee-portforio/design/`
- `portforio.png` — トップページ全体（2880×18132, 2x）
- `sevtion03-カルーセルコンテンツ.png` — Voice カルーセル詳細
- `下層ページ_制作実績一覧.png` — 一覧ページ
- `下層ページ_LP詳細_アートメイク.png` — 詳細ページ（アートメイク）
- `下層ページ_LP詳細_ポジジョブ.png` — 詳細ページ（ポジジョブ）

**旧Task 12.0〜12.9（JSON依存チェックリスト方式）は無効。** 過去のFB対応（ヘッダーナビ・カード再構築・CTA等）の実装自体は残すが、検証結果は信頼しない。

---

#### トップページ
- [ ] 12.1 — Header
- [ ] 12.2 — sec01 FV
- [ ] 12.3 — sec02 Works
- [ ] 12.4 — sec03 Voice
- [ ] 12.5 — sec04 Service
- [ ] 12.6 — sec06 Profile
- [ ] 12.7 — sec07 Flow
- [ ] 12.8 — sec08 Contact
- [ ] 12.9 — Footer

#### 一覧ページ（archive-works）
- [ ] 12.10 — タイトルエリア（パンくず + h1 + 水色背景）
- [ ] 12.11 — カード一覧
- [ ] 12.12 — CTA・ページ下部

#### 詳細ページ（single-works）
- [ ] 12.13 — タイトルエリア（パンくず + h1 + グレー背景）
- [ ] 12.14 — カードヘッダー（モックアップ + pills + クライアント名）
- [ ] 12.15 — 本文セクション（目的 / 担当範囲 / 制作期間 / ポイント / ツール）
- [ ] 12.16 — 実績数値 + 詳細画像
- [ ] 12.17 — CTA

#### ユーザーFBラウンド
- [ ] 12.18 — ユーザーFB → 指摘ゼロまで繰り返す

---

### Task 13 — CI/CD

- **Before starting:** confirm with user — stg host/docroot, GitHub Secrets key names, release branch name
- [ ] `.github/workflows/deploy.yml` — 4 jobs: sync-stg → deploy-stg → e2e → deploy-prod
- [ ] Move Playwright config to use `STG_URL` for CI; local config stays `localhost:8080`
- [ ] Secrets added to GitHub repository

---

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- **Current state: Task12を画像ベースで再構築。旧JSON依存チェックリスト方式は無効。次は12.1 Header。**

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Task 10以降はユーザー指示に従い進める。自動実行しない。
4. All browser checks use `npx playwright test` — never curl.
