# chee-portfolio Steering

## Design Specification

See [`design.md`](design.md) for architecture, CI/CD pipeline, E2E strategy, and WordPress design decisions. This file tracks tasks and session state only.

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Task List (workflow order)

Checkpoint legend:
- **[AI]** — static check run by AI in this session (JSON syntax, file structure, block markup)
- **[User]** — runtime check requiring Docker / WP-CLI / browser on user's machine

Complete all checkpoints before advancing to the next task.

---

- [x] -1. Local dev environment — `wp-dev/docker-compose.yml` (wordpress:php8.2-apache + mariadb:10.11); theme/ bind-mounted; `wp-dev/` gitignored
  - [AI] ✔ `wp-dev/docker-compose.yml` exists and is valid YAML
  - [AI] ✔ `wp-dev/.gitignore` excludes everything except docker-compose.yml
  - [User] `cd wp-dev && docker compose up -d` starts without error
  - [User] `docker compose run --rm cli wp core install --url=http://localhost:8080 --title="Chee Portfolio" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root`
  - [User] `docker compose run --rm cli wp eval 'echo "ok";' --allow-root` → `ok`

- [ ] 0. Verify Figma JSON design tokens against `theme.json` values; finalize Pods field group schema
  - [AI] ✔ Token values in `theme.json` match Figma JSON exports
  - [AI] ✔ Pods field group spec documented in `design.md`

- [ ] 1. `theme.json` complete — templateParts added after Task 4
  - [AI] ✔ `theme.json` passes `python3 -m json.tool`
  - [AI] ✔ All 8 color slugs, 4 font families, 7 font sizes, layout values present
  - [User] `docker compose run --rm cli wp theme activate chee-portfolio --allow-root` → no error
  - [User] `docker compose run --rm cli wp eval 'print_r(wp_get_global_settings()["color"]["palette"]["theme"]);' --allow-root` → 8 colors listed

- [ ] 2. `functions.php` — Works CPT + taxonomy, Pods field registration, Splide enqueue
  - [AI] ✔ `register_post_type('works', ...)` present; `show_in_rest => true`
  - [AI] ✔ Splide enqueue referencing `assets/js/vendor/splide.min.js`
  - [User] `docker compose run --rm cli wp eval 'echo "ok";' --allow-root` → no PHP fatal
  - [User] `docker compose run --rm cli wp post-type list --allow-root` → `works` listed

- [ ] 2b. Download Splide v4 core + Auto Scroll → `assets/js/vendor/` (self-hosted)
  - [AI] ✔ `splide.min.js` and `splide-extension-auto-scroll.min.js` exist in `assets/js/vendor/`

- [ ] 3. Font WOFF2 files → `assets/fonts/` (4 families, 9 files)
  - [AI] ✔ All 9 `.woff2` files present under `assets/fonts/`
  - [AI] ✔ Filenames match `src` paths in `theme.json` fontFace definitions

- [ ] 4. Template parts — `parts/header.html`, `parts/footer.html`; add `templateParts` to `theme.json`
  - [AI] ✔ Both files exist; contain `<!-- wp:` block markup
  - [AI] ✔ `theme.json` `templateParts` array has header and footer entries
  - [User] `curl -s http://localhost:8080 | grep -c 'wp-block'` → non-zero
  - [User] No block validation errors in browser console

- [ ] 4b. Copy image assets `input/chee-portforio/images/` → `theme/assets/images/`
  - [AI] ✔ All source images present in `theme/assets/images/`

- [ ] 5. Templates — `front-page.html`, `archive-works.html`, `single-works.html`
  - [AI] ✔ Each file exists; contains expected block names (query-loop in archive, etc.)
  - [User] `curl -s http://localhost:8080/ | grep 'wp-block-template-part'` → found
  - [User] `curl -s http://localhost:8080/works/ | grep 'wp-block-query'` → found

- [ ] 6. Block patterns × 8 (sec01–sec08)
  - [AI] ✔ 8 `.php` files under `patterns/`; each has valid `register_block_pattern` header comment
  - [AI] ✔ Block markup parses as valid JSON-in-comment (python3 structural check)
  - [User] `docker compose run --rm cli wp block-pattern list --allow-root` → 8 patterns listed

- [ ] 7. Splide init — `assets/js/splide-init.js`
  - [AI] ✔ File exists; references correct selectors for FV, Voice, single-works
  - [User] `curl -s http://localhost:8080 | grep 'splide-init.js'` → script tag present

- [ ] 8. Fluent Forms contact form — shortcode documented
  - [AI] ✔ Shortcode placeholder in `sec08-contact` pattern present
  - [User] Plugin activated, form created, shortcode confirmed in pattern

- [ ] 9. Responsive CSS — nav 1024px breakpoint, FV mockup sizing
  - [AI] ✔ CSS file exists; `@media` rules for 1024px and 781px present
  - [User] **Browser visual check** — `localhost:8080` at PC / tablet / mobile widths

- [ ] 10. Expert review — WordPress / HTML / CSS / Accessibility sub-agents → fix all findings
  - [AI] Spawn 4 reviewer sub-agents; apply all fixes
  - [AI] ✔ `layout.allowEditing: false` confirmed in `theme.json`

- [ ] 11. GitHub Actions workflow + handoff doc
  - [AI] ✔ `.github/workflows/deploy.yml` exists with 4 jobs (sync-stg, deploy-stg, e2e, deploy-prod)
  - [AI] ✔ Playwright test files exist under `theme/e2e/`
  - [User] Secrets (`STG_URL`, `server_id`, domains) added to GitHub repository

> Tasks -1 to 11 complete one design cycle. Repeat from Task 0 for each new design input.

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
- **Current state: Task -1 AI checks passed. Waiting on Colima install to run User checks.**

### Decisions made this session (2026-05-07)

| Item | Decision |
|------|----------|
| Container runtime | **Colima** (free, CLI-only, no Docker Desktop needed) |
| Local dev | Docker via Colima — `brew install colima docker docker-compose && colima start` |
| Task checkpoint model | [AI] static checks run in session; [User] runtime checks via Docker/WP-CLI/browser |
| E2E | Playwright in `theme/e2e/`, runs in GHA against stg with prod data |

## How to Resume

1. Read `design.md` for architecture decisions.
2. Read this file — current state is in "Session Context" above.
3. **If Colima not yet installed:**
   ```bash
   brew install colima docker docker-compose
   colima start
   ```
4. **Run Task -1 User checkpoints:**
   ```bash
   cd design-to-code/chee-portfolio/wp-dev
   docker compose up -d
   docker compose run --rm cli wp core install \
     --url=http://localhost:8080 --title="Chee Portfolio" \
     --admin_user=admin --admin_password=admin \
     --admin_email=admin@example.com --allow-root
   docker compose run --rm cli wp eval 'echo "ok";' --allow-root
   ```
   Confirm `ok` is returned, then mark Task -1 `[x]` and proceed to Task 0.
5. For each subsequent task: AI runs [AI] checks, then hand off [User] checks with exact commands.
