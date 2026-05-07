# chee-portfolio Steering

## Design Specification

See [`design.md`](design.md) for architecture, CI/CD pipeline, E2E strategy, and WordPress design decisions. This file tracks tasks and session state only.

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Task List

Complete all checkpoints before advancing to the next task.
Browser verification (`localhost:8080`) is available from Task 1 onward.

---

### Task 0 — Design spec ✅

- ✔ `design.md`: environments, Git scope, release flow, CPT fields, design tokens

---

### Task 1 — Local dev environment

- ✔ `wp-dev/docker-compose.yml` exists and is valid YAML
- ✔ `wp-dev/.gitignore` configured
- [ ] Colima + Docker installed (`brew install colima docker docker-compose && colima start`)
- [ ] `docker compose up -d` starts without error
- [ ] WP core install:
  ```
  docker compose run --rm cli wp core install \
    --url=http://localhost:8080 --title="Chee Portfolio" \
    --admin_user=admin --admin_password=admin \
    --admin_email=admin@example.com --allow-root
  ```
- [ ] Theme activated: `docker compose run --rm cli wp theme activate chee-portfolio --allow-root`
- [ ] Plugins installed and activated: Pods, Meta Field Block, Fluent Forms
- [ ] `README.md` written — local dev startup procedure

---

### Task 2 — Theme foundation

- [ ] `theme.json` — all 8 colors, 4 font families, 7 font sizes, layout values
- [ ] `functions.php` — Works CPT + `works-category` taxonomy + Voice CPT; Splide enqueue
- [ ] Pods field groups registered: Works (6 fields) + Voice (5 fields) per `design.md`
- [ ] `works-category` initial terms created: ディレクション / 広告バナー / 広告運用 / LPデザイン / HPデザイン / LP制作 / HP制作
- [ ] Vendor assets downloaded: `splide.min.js` + `splide-extension-auto-scroll.min.js` → `assets/js/vendor/`
- [ ] Font WOFF2 files (9 files, 4 families) → `assets/fonts/`
- [ ] Image assets copied: `input/chee-portforio/images/` → `assets/images/`
- [ ] Verification: `wp post-type list` → `works`, `voice` listed; no PHP fatal

---

### Task 3 — Fixture data

- [ ] 3 dummy Works posts — each with all Pods fields filled; at least 2 with `fv_featured = true`
- [ ] 4 dummy Voice posts — all Pods fields filled (matching design samples)
- [ ] Verification: Works and Voice visible in WP admin; browser at `localhost:8080/works/` returns posts

---

### Task 4 — Template parts

- [ ] `parts/header.html` + `parts/footer.html` with `<!-- wp:` block markup
- [ ] `templateParts` array added to `theme.json`
- [ ] Verification: `localhost:8080` shows header and footer

---

### Task 5 — Templates

- [ ] `templates/front-page.html` — assembles all sec patterns
- [ ] `templates/archive-works.html` — Query Loop for Works CPT
- [ ] `templates/single-works.html` — fixed header area (title, client, tags) + free block body
- [ ] Verification: home / `/works/` / single works page all render without errors

---

### Task 6 — Block patterns × 8

- [ ] `sec01-fv.php` — Splide auto-scroll; queries Works with `fv_featured = true`, ordered by `fv_order`
- [ ] `sec02-works.php` — Query Loop (Works CPT), card grid
- [ ] `sec03-voice.php` — Splide carousel; queries Voice CPT
- [ ] `sec04-service.php` — static pattern
- [ ] `sec05-cta.php` — static pattern
- [ ] `sec06-profile.php` — static pattern
- [ ] `sec07-flow.php` — static pattern
- [ ] `sec08-contact.php` — Fluent Forms shortcode placeholder
- [ ] Verification: all 8 patterns listed via `wp block-pattern list`; home page renders each section

---

### Task 7 — Styles + JS

- [ ] `style.css` — `@media` breakpoints at 1024px and 781px; nav + FV mockup sizing
- [ ] `assets/js/splide-init.js` — initializes FV carousel, Voice carousel, single-works gallery
- [ ] Verification: responsive layout correct in browser at 375px / 768px / 1280px

---

### Task 8 — Code review

- [ ] Spawn 4 reviewer sub-agents: WordPress / HTML / CSS / Accessibility
- [ ] Apply all findings
- [ ] `layout.allowEditing: false` confirmed in `theme.json`

---

### Task 9 — CI/CD

- **Before starting:** hear from user — stg host/docroot, DB copy method, WP-CLI on Xserver, GitHub Secrets key names, release branch name
- [ ] `.github/workflows/deploy.yml` — 4 jobs: sync-stg (release branch only) → deploy-stg → e2e → deploy-prod
- [ ] Playwright test files under `theme/e2e/`
- [ ] Secrets added to GitHub repository

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- **Current state: Task 0 complete. Next: Task 1 (Colima install + WP setup).**

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Proceed in order. Do not skip tasks.
