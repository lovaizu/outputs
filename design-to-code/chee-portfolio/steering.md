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

<!-- paused: 2026-05-10 — 次: #10.1 Works CPTフィールド設計確定（詳細画像・フィールド確認）→ design.md更新 → Pods設定 → seed更新 -->

### Task 10 — リファクタリング（不要ファイル整理）

ユーザー指示に従い進める。WP初心者向けに説明しながら作業する。

- [ ] 10.1 — 不要な画像・アセットを洗い出してリスト提示（ユーザー確認後に削除）
- [ ] 10.2 — 使用中のアセット整理（ファイル名・ディレクトリ構成の見直し）
- [ ] 10.3 — 未使用CSS・PHP・JSコードの削除
- [ ] 10.4 — `seed.sh` / fixture data の整合性確認

---

### Task 11 — 最終状態整理（git管理・WP管理画面データ）

ユーザー指示に従い進める。何をコードで管理し、何を管理画面から入力するかを整理する。

- [ ] 11.1 — git対象 / 対象外の整理（`.gitignore` 見直し・方針説明）
- [ ] 11.2 — WP管理画面から入力するデータの整理（Works/Voice投稿、メニュー、フォーム設定など）
- [ ] 11.3 — 初期データ（`seed.sh`）を本番用コンテンツに近い状態に更新
- [ ] 11.4 — ローカル → 本番への移行手順を `README.md` に追記

---

### Task 12 — デザインカンプ完全一致（最終修正）

ユーザー指示に従い進める。スクリーンショット vs デザインカンプを繰り返し確認しながら修正する。

- [ ] 12.1 — 全セクション現状スクリーンショット撮影 → デザインカンプと照合 → 差分リスト作成
- [ ] 12.2 — 差分リストをユーザーに提示・確認
- [ ] 12.3 — 確認済み差分を全件修正
- [ ] 12.4 — 修正後スクリーンショット撮影 → 再照合 → 完全一致を確認

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
- **Current state: Works CPTフィールド設計を議論中。フィールド案は design.md に記載（?要確認: 詳細ページの複数画像）。次: フィールド設計確定 → Pods設定変更 → seed更新 → 画像マッピング。**

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Task 10以降はユーザー指示に従い進める。自動実行しない。
4. All browser checks use `npx playwright test` — never curl.
