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

**カンプ画像:** `input/chee-portforio/design/`
- `portforio.png` — トップページ全体（2880×18132, 2x）
- `sevtion03-カルーセルコンテンツ.png` — Voice カルーセル詳細
- `下層ページ_制作実績一覧.png` — 一覧ページ
- `下層ページ_LP詳細_アートメイク.png` — 詳細ページ（アートメイク）
- `下層ページ_LP詳細_ポジジョブ.png` — 詳細ページ（ポジジョブ）

**旧Task 12.0〜12.9（JSON依存チェックリスト方式）は無効。** 過去のFB対応（ヘッダーナビ・カード再構築・CTA等）の実装自体は残すが、検証結果は信頼しない。

---

**各セクション共通プロセス（全4ステップ、省略不可）:**

> **チェックリストがこのタスクの成果物の核。** チェックリストが正確なら修正は容易。チェックリストが間違っていたら全部やり直し。ステップ1-2に最大の時間をかけること。

**ステップ1: チェックリスト作成（慎重に・正確に）**
- カンプ画像の該当セクションをズーム表示し、全UI要素を漏れなく列挙
- 各項目を **目視確認** or **実装値確認** に分類
  - **目視確認:** 色、形状、角丸、レイアウト構造、要素間の比率
  - **実装値確認:** 絶対px値、font-size、line-height、letter-spacing、font-weight、box-shadow、opacity
  - 実装値は基準要素（本文テキスト=16px）からの比率で算出し、2x画像の計測値÷2で目安値を導出
- `checklist.md` に書き出す

**ステップ2: UX/UIデザインエキスパートレビュー（厳しく）**
- カンプ画像を再度見ながら、チェックリストを1項目ずつ照合
- レビュー観点:
  - 見落とし: カンプに見えているのにチェックリストにない要素はないか
  - 誤読: カンプの値を読み間違えていないか
  - 分類ミス: 目視/実装値の分類は適切か
- 指摘があれば修正し、指摘ゼロになるまで繰り返す
- レビュー結果を `checklist.md` に記録

**ステップ3: 実装修正**
- チェックリスト通りにCSS/HTMLを修正
- チェックリストにない変更はしない

**ステップ4: Playwright検証 + エキスパート最終確認**
- 目視項目 → Playwrightスクショ撮影、カンプと並べて比較
- 実装値項目 → `getComputedStyle` で取得、期待値と照合
- UX/UIデザインエキスパートが最終判定 → 指摘ゼロまで改善

---

#### トップページ

##### 12.1 — Header
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.2 — sec01 FV
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.3 — sec02 Works
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.4 — sec03 Voice
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.5 — sec04 Service
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.6 — sec06 Profile
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.7 — sec07 Flow
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.8 — sec08 Contact
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.9 — Footer
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

#### 一覧ページ（archive-works）

##### 12.10 — タイトルエリア（パンくず + h1 + 水色背景）
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.11 — カード一覧
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.12 — CTA・ページ下部
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

#### 詳細ページ（single-works）

##### 12.13 — タイトルエリア（パンくず + h1 + グレー背景）
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.14 — カードヘッダー（モックアップ + pills + クライアント名）
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.15 — 本文セクション（目的 / 担当範囲 / 制作期間 / ポイント / ツール）
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.16 — 実績数値 + 詳細画像
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

##### 12.17 — CTA
- [ ] ステップ1: チェックリスト作成
- [ ] ステップ2: エキスパートレビュー（指摘ゼロまで）
- [ ] ステップ3: 実装修正
- [ ] ステップ4: Playwright検証 + エキスパート最終確認

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
