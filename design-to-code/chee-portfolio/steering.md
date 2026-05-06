# chee-portfolio Steering

## Design Specification

See [`design.md`](design.md) for architecture, CI/CD pipeline, E2E strategy, and WordPress design decisions. This file tracks tasks and session state only.

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Task List (workflow order)

Each task includes a checkpoint — validate before moving to the next task.

- [ ] -1. Local dev environment — Docker (`wordpress:php8.2-apache` + DB); bind-mount `theme/` into container themes dir; `wp-dev/` gitignored
  - ✔ `wp eval 'echo "ok";' --allow-root` returns `ok` inside container
- [ ] 0. Verify Figma JSON design tokens against guideline values; translate ACF field schema to Pods field groups → finalize token + field table
- [ ] 1. `theme.json` — colors, fonts, fontSizes, layout (templateParts added in Task 4 after parts files exist)
  - ✔ `wp theme activate` succeeds; `wp eval 'print_r(wp_get_global_settings());'` shows expected color/font presets
- [ ] 2. `style.css` / `index.php` / `functions.php` — theme header, Works CPT + taxonomy, Pods field registration, Splide.js enqueue
  - ✔ `wp eval 'echo "ok";'` — no PHP fatal; `wp post-type list` shows `works`
- [ ] 2b. Download Splide v4 core + Auto Scroll extension → `assets/js/vendor/` (self-hosted, no CDN)
- [ ] 3. Font assets — download WOFF2 via curl → `assets/fonts/` (4 families)
- [ ] 4. Template parts — `parts/header.html`, `parts/footer.html`; add `templateParts` declaration to `theme.json`
  - ✔ curl `localhost:8080` — header/footer render, no block validation errors in HTML
- [ ] 4b. Copy image assets from `input/chee-portforio/images/` → `theme/assets/images/`
- [ ] 5. Templates — `templates/front-page.html`, `templates/archive-works.html`, `templates/single-works.html`
  - ✔ curl each URL — expected blocks present, no fallback to `index.html`
- [ ] 6. Block patterns × 8 — sec01-fv, sec02-works, sec03-voice, sec04-service, sec05-cta, sec06-profile, sec07-flow, sec08-contact
  - ✔ Per pattern: `wp eval 'echo serialize_blocks(parse_blocks(file_get_contents("...")));'` — valid block grammar
- [ ] 7. Splide.js init — `assets/js/splide-init.js` (FV auto-scroll, Voice carousel, single-works gallery swipe)
  - ✔ curl page source — Splide script tag present, no 404 in enqueue path
- [ ] 8. Contact form spec — Fluent Forms field definition + shortcode doc
- [ ] 9. Responsive CSS — Navigation breakpoint override (1024px), FV mockup sizing
  - ✔ **User opens `localhost:8080` in browser** — visual check on 3 breakpoints (PC / tablet / mobile)
- [ ] 10. Expert review (WordPress / HTML / CSS / Accessibility sub-agents) + guideline compliance → fix all findings; verify `layout.allowEditing: false` lockdown in Site Editor
- [ ] 11. Handoff doc + GitHub Actions workflow template — Local setup steps, plugin install, deployment flow (prod→stg→confirm→prod), font swap guide

> Steps -1 to 11 complete one design cycle. Repeat from step 0 for each new design input.

## Design Policy

`input/figma-to-wp-guideline.md` をベースに、以下の変更を適用する。**この steering.md の記載がガイドラインより優先される。**

| 項目 | ガイドライン | 本プロジェクトでの採用 | 理由 |
|------|------------|---------------------|------|
| カスタムフィールド | ACF Free | **Pods + Meta Field Block** | ACF Freeでは Query Loop内のカスタムフィールド表示に別プラグインが必要。Podsは完全無料で CPT・タクソノミー・フィールドをすべてカバー |
| お問合せフォーム | Contact Form 7 | **Fluent Forms** | CF7は2026年に機能フリーズ宣言。Fluent Forms無料プランはAjax送信・プライバシー同意チェックボックスを標準で含む |
| フォントホスティング | Google Fonts CDN想定 | **WOFF2 セルフホスト** (`assets/fonts/`) | GDPR・パフォーマンス観点で外部CDN不使用（ガイドラインの意図通り、実装方法を明確化） |
| 開発フロー | コード生成後にレビュー | **タスクごとにWP-CLIで自動検証** + ブラウザ目視確認 | WPブロックテーマの不具合はサイレント失敗が多く、後工程での手戻りが大きいため |

## User Prep

開発を始める前にユーザー側で用意するもの。

### Task -1 開始前（ローカル環境）

| 項目 | コマンド / 手順 |
|------|----------------|
| WP-CLI インストール | `brew install wp-cli` |
| PHP 8.x 確認 | `php -v`（macOS標準搭載、8.0以上推奨） |

### Task 11 前（デプロイワークフロー構築時）

| 情報 | 用途 |
|------|------|
| Xserver サーバーID（例: sv12345） | SSH ユーザー名 / デプロイパス構築 |
| prod ドメイン名 | デプロイパス: `/home/{server_id}/{domain}/public_html/` |
| stg ドメイン名 または ディレクトリ | stg デプロイパス構築 |
| prod / stg に WP-CLI インストール済みか | DB コピー手順（`wp db export/import`） |
| テーマのデプロイ元リポジトリ（本リポジトリ or 別） | GitHub Actions trigger 設計 |
| GitHub Secrets のキー名（命名自由） | workflow.yml のシークレット参照名 |

**確定済み（Xserver調査結果）**
- SSH ポート: **10022**、公開鍵認証のみ
- デプロイ方式: rsync over SSH（`-e "ssh -p 10022"`）
- ドキュメントルート: `/home/{server_id}/{domain}/public_html/`
- WP-CLI: デフォルト非搭載 → SSH で `~/bin/` に手動インストール必要
- DB コピー: `wp db export / wp db import`

## Local Dev Environment

**構成:** WP-CLI + SQLite（MySQL不要）+ `php -S localhost:8080`

```
design-to-code/chee-portfolio/
├── theme/               ← Git管理（成果物）
└── wp-dev/              ← Git管理外（.gitignoreで除外）
    ├── wp-content/
    │   ├── themes/
    │   │   └── chee-portfolio → symlink to ../../theme/
    │   └── database/    ← SQLiteファイル（gitignore）
    └── (WP core files)  ← gitignore
```

**動作確認の分担:**
- AI: WP-CLIコマンドでプリセット存在・PHP fatal・ブロック文法を自動検証
- ユーザー: `localhost:8080` をブラウザで目視確認（Task 9以降）

## Decisions

| Date | Decision |
|------|----------|
| 2026-05-06 | ACF Free → **Pods + Meta Field Block** (fully free; better Query Loop support; no Pro tier needed) |
| 2026-05-06 | Contact Form 7 → **Fluent Forms** (CF7 entered feature-freeze in 2026; Fluent Forms free covers Ajax, conditional logic, privacy checkbox) |
| 2026-05-06 | Fonts self-hosted as WOFF2 in `assets/fonts/`; downloaded via curl from Google Fonts API |
| 2026-05-06 | Theme code output: `design-to-code/chee-portfolio/theme/` in this repo |
| 2026-05-06 | Figma JSON used to verify guideline token values before generating theme.json; discrepancies flagged for user confirmation |
| 2026-05-06 | Local dev: WP-CLI + SQLite + php -S; AI runs automated checks, user does visual browser check from Task 9 |
| 2026-05-06 | Task 11 includes GitHub Actions workflow template; deployment-specific values left as placeholders until server info is provided |

## Key Specs (from guideline)

### Pages
| Page | Template | Notes |
|------|----------|-------|
| Home | front-page | 8-section LP-style, single page |
| Works archive | archive-works | Card grid, Query Loop |
| Works single | single-works | LP detail, Splide gallery on mobile |

### Home Sections
| # | Section | Type |
|---|---------|------|
| 01 | FV | Static + Splide auto-scroll |
| 02 | Works | Dynamic (Query Loop, postType: works) |
| 03 | Voice | Static pattern + Splide carousel |
| 04 | Service | Static pattern |
| 05 | CTA | Static pattern |
| 06 | Profile | Static pattern |
| 07 | Flow | Static pattern |
| 08 | Contact | Fluent Forms shortcode |
| — | Header / Footer | Template parts |

### Design Tokens
| Token | Value |
|-------|-------|
| bg-main | #DCEFFB |
| bg-sub | #F6F6F6 |
| white | #FFFFFF |
| text-primary | #111111 |
| text-secondary | #333333 |
| accent | #4EB0EA |
| highlight | #FBFF92 |
| border | #D7D7D7 |
| contentSize | 840px |
| wideSize | 1100px |

### Fonts
| slug | Family | Weights | Use |
|------|--------|---------|-----|
| noto-sans-jp | Noto Sans JP | 100/400/500/700 | Japanese body |
| roboto-condensed | Roboto Condensed | 300/500 | EN section headings |
| jost | Jost | 400/500 | EN subtext |
| zen-kurenaido | Zen Kurenaido | 400 | Decorative handwritten |

### Plugins / Libraries
| Name | Use | Cost |
|------|-----|------|
| Pods | Works CPT + taxonomy + custom fields | Free |
| Meta Field Block | Display custom fields in Query Loop | Free |
| Fluent Forms | Contact form | Free |
| Splide.js v4 + Auto Scroll | FV slider, Voice carousel, gallery | Free (MIT) |

## Directory Layout

```
design-to-code/chee-portfolio/
├── steering.md          ← this file
├── input/               ← design files as received (do not modify)
│   ├── figma-to-wp-guideline.md
│   └── chee-portforio/
│       ├── design/      ← design PNGs
│       ├── structures/  ← Figma JSON exports
│       └── images/      ← image assets
├── theme/               ← WordPress block theme output (Git管理)
│   ├── style.css
│   ├── theme.json
│   ├── index.php
│   ├── functions.php
│   ├── templates/
│   ├── parts/
│   ├── patterns/
│   └── assets/
│       ├── fonts/
│       ├── images/
│       └── js/
│           └── vendor/  ← Splide (self-hosted)
└── wp-dev/              ← ローカル開発用WP環境 (gitignore)
```

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- Input committed at: `design-to-code/chee-portfolio/input/`
- Design spec: `design-to-code/chee-portfolio/design.md`
- **Resume from: Task -1 (local dev environment — Docker)**

## How to Resume

1. Read this file.
2. Check git log to confirm last commit.
3. Start Task -1: set up Docker local environment in `wp-dev/`.
   - Write `wp-dev/docker-compose.yml` (wordpress:php8.2-apache + MariaDB)
   - Bind-mount `theme/` into container at `/var/www/html/wp-content/themes/chee-portfolio`
   - Add `wp-dev/` to `.gitignore`
   - `docker compose up -d` and install WP via WP-CLI
   - Validate: `docker compose exec wordpress wp eval 'echo "ok";' --allow-root`
4. Proceed through tasks in order, running the ✔ checkpoint after each one.
