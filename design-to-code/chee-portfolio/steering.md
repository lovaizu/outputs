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

<!-- paused: 2026-05-12 — next: #12.4 一覧ページ スクリーンショット→採点 -->

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

### Task 12 — デザインカンプ完全一致（最終修正）

ユーザー指示に従い進める。スクリーンショット vs デザインカンプを繰り返し確認しながら修正する。

**このセッションで対応済みのFB（ユーザー指摘）:**
- [x] ヘッダーナビ — 全項目をトップページ内アンカー（/#works 等）に修正
- [x] archive-works — カード完全再構築（[category_label] + client_name + 矢印 + 青線 + pills + 説明文 + サムネイル）
- [x] single-works — パンくず・カードヘッダー・CTA追加。デザインカンプ画像と照合済み
- [x] シードデータ修正 — post_title（プロジェクト名のみ）、client_name（様）、post_excerpt（説明文）、post_content（◆目的/担当範囲/制作期間/ポイント/ツール 構造化）
- [x] FVカルーセル — モックアップ画像クリックで詳細ページへ遷移
- [x] sec02 — 矢印アイコン・サムネイル画像クリックで詳細ページへ遷移
- [x] archive-works — 矢印アイコン・サムネイル画像クリックで詳細ページへ遷移
- [x] ヘッダー背景 — トップ・一覧=白、詳細=グレー(#DEE3EC)（ページ別に body.single-works で切り替え）
- [x] ページタイトルエリア — 一覧=水色(#DCEFFB)全幅背景 + パンくず + h1(32px)追加。詳細=グレー(#DEE3EC)全幅背景
- [x] 本文 works-section-title — 青四角(12×12px)::before追加
- [x] FVセクション背景 — bg-sub(#F6F6F6)を削除→ボディと同じ白に修正
- [x] 詳細ページグレー — #D7D7D7から#DEE3EC(Figmaカラー)に修正。bg-singleトークン追加

**残タスク:**

#### 事前準備
- [x] 12.0 — checklist.mdをUX/UIデザインエキスパートがデザインカンプ画像+Figma JSONを精読してレビュー → 指摘なくなるまで繰り返し洗練（デザインカンプ完全一致に必要な全項目を正確に網羅すること）

#### トップページ（sec01〜sec08）
- [x] 12.1 — Playwrightでスクリーンショット撮影 → checklist.mdで採点 → ❌項目をユーザーに提示・確認
- [x] 12.2 — 確認済み❌項目を全件修正
- [x] 12.3 — 再スクリーンショット → checklist.md再採点 → 全項目✅を確認

#### 一覧ページ（archive-works）
- [ ] 12.4 — Playwrightでスクリーンショット撮影 → checklist.mdで採点 → ❌項目をユーザーに提示・確認
- [ ] 12.5 — 確認済み❌項目を全件修正
- [ ] 12.6 — 再スクリーンショット → checklist.md再採点 → 全項目✅を確認

#### 詳細ページ（single-works）
- [ ] 12.7 — Playwrightでスクリーンショット撮影 → checklist.mdで採点 → ❌項目をユーザーに提示・確認
- [ ] 12.8 — 確認済み❌項目を全件修正
- [ ] 12.9 — 再スクリーンショット → checklist.md再採点 → 全項目✅を確認

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
- **Current state: Task11完了（.gitignore整理・管理画面データ方針整理・README本番移行手順追記）。次はTask12（デザインカンプ完全一致）。**

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Task 10以降はユーザー指示に従い進める。自動実行しない。
4. All browser checks use `npx playwright test` — never curl.
