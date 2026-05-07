# chee-portfolio Steering

## Design Specification

See [`design.md`](design.md) for architecture, CI/CD pipeline, E2E strategy, and WordPress design decisions. This file tracks tasks and session state only.

## Purpose

WEBデザイナー・伊藤千晶（Chee Design）のポートフォリオサイトを、Figmaデザインに基づきWordPressブロックテーマ（FSE）で構築する。

## Verification Policy

**All browser verification uses Playwright** (not curl).  
Write E2E tests incrementally — one assertion per task — so Task 9 CI/CD wiring is the only remaining work by the time we reach it.

---

## Task List

Complete all checkpoints before advancing to the next task.

---

### Task 0 — Design spec ✅

- ✔ `design.md`: environments, Git scope, release flow, CPT fields, design tokens

---

### Task 1 — Local dev environment ✅

- ✔ `wp-dev/docker-compose.yml` + `.gitignore`
- ✔ Colima + Docker installed; containers running
- ✔ WP core installed at http://localhost:8080
- ✔ Theme activated: chee-portfolio
- ✔ Plugins: Pods 3.3.8 / Meta Field Block 1.5.3 / Fluent Forms 6.2.2
- ✔ `README.md` — local dev quick-start

---

### Task 2 — Theme foundation ✅

- ✔ `theme.json` — 8 colors / 4 font families / 7 font sizes / layout
- ✔ `functions.php` — Works + Voice CPTs + works-category taxonomy + Splide enqueue
- ✔ Pods: Works 6 fields + Voice 5 fields (`voice_*` prefix — `name`/`body` are WP reserved)
- ✔ `works-category` 7 initial terms
- ✔ Splide v4 + auto-scroll vendor JS
- ✔ Font WOFF2 (9 files, fontsource subsets) + image assets

---

### Task 2.5 — Playwright setup ✅

- ✔ `brew install node` → Node v25.9.0 / npx 11.12.1
- ✔ `npm init` + `@playwright/test` v1.59.1 (TypeScript, chromium only for local)
- ✔ `playwright.config.ts` — baseURL: `http://localhost:8080` (`STG_URL` override), single project: chromium
- ✔ Smoke test: `e2e/smoke.spec.ts` — `page.goto('/')` → expect no PHP fatal in body
- ✔ `npx playwright test` passes locally (1 passed)
- ✔ `theme/e2e/` committed (config + smoke spec; `node_modules/` gitignored)

---

### Task 3 — Fixture data ✅

- ✔ 3 dummy Works posts (ArtMake LP, PosiJob HP, Ad banner set) — all Pods fields filled; 2 with `fv_featured = true`
- ✔ 4 dummy Voice posts — all Pods fields filled
- ✔ Playwright: `works-archive.spec.ts` written (skipped until archive template exists in Task 5)
- ✔ `wp-dev/seed.sh` — repeatable fixture script

---

### Task 4 — Template parts ✅

- ✔ `parts/header.html` — logo + Navigation (Home/Works/Voice/Service/Profile) + Contact button
- ✔ `parts/footer.html` — logo + Japanese nav (ホーム/制作実績/お客様の声/サービス/プロフィール/お問合せ), bg-main
- ✔ `templateParts` array added to `theme.json`
- ✔ `templates/index.html` — fallback template (header + post-content + footer)
- ✔ Playwright: `layout.spec.ts` — header `<nav>` and `footer.site-footer` visible

---

### Task 5 — Templates ✅

- ✔ `templates/front-page.html` — header + 8 pattern refs + footer (patterns created in Task 6)
- ✔ `templates/archive-works.html` — Query Loop (3-col grid), client_name + category_label via Meta Field Block
- ✔ `templates/single-works.html` — post-title h1, client_name + category_label meta, post-content
- ✔ Permalink structure set to `/%postname%/`
- ✔ Playwright: `/works/` shows card; single Works shows client field
- ⏳ Home `<h1>` deferred to Task 6 (comes from sec01-fv pattern)

---

### Task 6 — Block patterns × 8 ✅

- ✔ `sec01-fv.php` — h1 + "Design" hero text + Splide auto-scroll (Works fv_featured, fv_order)
- ✔ `sec02-works.php` — Query Loop 2-col grid + "制作実績一覧へ" button
- ✔ `sec03-voice.php` — Splide carousel; Voice CPT dynamic query
- ✔ `sec04-service.php` — 3 service cards (ディレクション/LP制作/広告運用) with icons
- ✔ `sec05-cta.php` — accent bg, "お問合せはこちら" button
- ✔ `sec06-profile.php` — bio text + profile photo + 3 detail rows
- ✔ `sec07-flow.php` — 7 numbered steps (PHP array)
- ✔ `sec08-contact.php` — Fluent Forms shortcode id=1
- ✔ Pattern cache fix: `delete_pattern_cache()` on theme switch
- ✔ Playwright: h1 visible + all 7 section IDs attached (7 tests pass)

---

### Task 7 — Styles + JS

- [ ] `style.css` — `@media` at 1024px and 781px; nav + FV mockup sizing
- [ ] `assets/js/splide-init.js` — FV carousel, Voice carousel, single-works gallery
- [ ] Playwright: screenshot at 375px / 768px / 1280px — no layout overflow

---

### Task 8 — Code review

- [ ] Spawn 4 reviewer sub-agents: WordPress / HTML / CSS / Accessibility
- [ ] Apply all findings
- [ ] `layout.allowEditing: false` confirmed in `theme.json`

---

### Task 9 — CI/CD

- **Before starting:** confirm with user — stg host/docroot, GitHub Secrets key names, release branch name
- [ ] `.github/workflows/deploy.yml` — 4 jobs: sync-stg → deploy-stg → e2e → deploy-prod
- [ ] Move Playwright config to use `STG_URL` for CI; local config stays `localhost:8080`
- [ ] Secrets added to GitHub repository

---

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- **Current state: Task 6 complete. Next: Task 7 (styles + JS).**

## Unresolved

- ~~`localhost:8080` returns empty body~~ — resolved: `templates/index.html` added in Task 4.

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Proceed in order. Do not skip tasks.
4. All browser checks use `npx playwright test` — never curl.
