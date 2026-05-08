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

<!-- paused: 2026-05-08 — 次: #9.6.12 スクリーンショットとデザインカンプを全セクション目視比較 → 差分リスト全件列挙 → ユーザー確認 → 修正 -->

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

### Task 7 — Styles + JS ✅

- ✔ `style.css` — Splide core CSS (self-hosted), sticky header, nav hamburger at ≤1024px, section/card/voice/flow styles, responsive
- ✔ `assets/js/splide-init.js` — FV auto-scroll (window.splide.Extensions) + Voice carousel
- ✔ 3 viewports verified: 375px hamburger nav, 1280px full nav, Splide carousels visible
- ✔ 7 Playwright tests pass

---

### Task 8 — Code review ✅

- ✔ Reviewed: WordPress best practices, HTML semantics, CSS quality, Accessibility
- ✔ Added skip-to-content link (visually hidden, visible on focus) in header
- ✔ Logo in header + footer wrapped in `<a href="/">` link with aria-label
- ✔ Added `id="main"` anchor to `<main>` in all 4 templates
- ✔ Added `:focus-visible` outline using accent color
- ✔ Added `prefers-reduced-motion` CSS (disables Splide transitions)
- ✔ Splide JS respects `prefers-reduced-motion` (disables autoScroll / autoplay)
- ✔ `layout.allowEditing: false` confirmed in `theme.json`

---

### Task 9 — Design fidelity (pixel-perfect match)

Goal: rebuild theme CSS/patterns/theme.json from scratch based on Figma JSON + design comp images.
Do NOT patch existing styles — start from correct architecture.

#### 9.1 — Extract & audit ✅
- ✔ Parsed `portforio.json` + section JSON files
- ✔ Read all design comp images (sec01–sec09)
- ✔ Design spec table produced: colors, typography, section layouts
- ✔ Key deltas identified: section heading style, FV bg, Voice layout, Contact button, footer icons

#### 9.2 — theme.json rebuild ✅
- ✔ Colors already correct in theme.json — no changes needed
- ✔ Font slugs confirmed against WOFF2 files (jost = Futura PT substitute; zen-kurenaido = handwritten substitute)

#### 9.3 — CSS rebuild ✅
- ✔ `style.css` rewritten from scratch (below WP header): header, FV, works, voice, service, flow, profile, contact, footer
- ✔ Voice two-column layout (number+photo left, catchphrase+body right)
- ✔ Flow step pentagon/hexagon number badge via clip-path
- ✔ Responsive: mobile (≤781px), tablet (782–1024px), desktop (≥1025px)

#### 9.4 — Pattern / template rebuild ✅
- ✔ All 8 patterns: section headings → single English title, Noto Sans JP 36px w500
- ✔ `sec01-fv.php`: bg-main → bg-sub (#F6F6F6)
- ✔ `sec03-voice.php`: complete rewrite to two-column Figma layout
- ✔ `sec07-flow.php`: pentagon number badge structure
- ✔ `sec08-contact.php`: zen-kurenaido lead text
- ✔ `parts/header.html`: Contact button → dark/pill shape; nav → noto-sans-jp
- ✔ `parts/footer.html`: X + LinkedIn SVG social icons added

#### 9.5 — Visual verification ✅
- ✔ Playwright screenshot each section; compared against design comp images
- ✔ Fixes applied: FV decorative text (Chee Design overlay), subtitle text, h1 nowrap, footer nav separators
- ✔ All 18 E2E tests pass; visual.spec.ts committed
- ✔ Remaining differences are content/data only (placeholder images, unregistered contact form)

#### 9.6 — Design fidelity (セクション別完全一致) — **IN PROGRESS**

**確定プロセス**（このセッションで決定）:
1. Figma JSON（portforio.json + 各セクションJSON）を全パース → 全要素・全プロパティをチェック項目として列挙
2. デザインカンプ画像で補完（JSONにない視覚情報: セクション間余白・全体バランス等）
3. チェック表に基づき実装（実装者: Claude）
4. チェック表に基づきレビュー（レビュアー: 別Claude。差分あり→即修正、判断なし）
5. ユーザーは最終「OK/NG」のみ。品質保証は全てClaude責任。

**チェック表フォーマット**:
```
| # | 要素 | プロパティ | 期待値(Figma) | 実装 | レビュー |
```

**セクション別タスク（1セクション = 1タスク）**:

- [x] 9.6.1 — Header: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.2 — sec01 FV: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.3 — sec02 Works: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.4 — sec03 Voice: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.5 — sec04 Service: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.6 — sec05 CTA: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.7 — sec06 Profile: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.8 — sec07 Flow: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.9 — sec08 Contact: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.10 — sec09 Footer: チェック表生成→実装→レビュー→完全一致
- [x] 9.6.11 — Playwright computed style vs Figma JSON 数値照合 → 差分リスト生成 → CSS修正
- [ ] 9.6.12 — スクリーンショット vs デザインカンプ 全セクション目視比較 → 差分リスト全件列挙 → ユーザー確認 → 全修正

**既完了ラウンド** (参考):
- Round 1–3: FV/Flow/Works/Voice/Service/Profile/Contact の主要差分修正済み（ただし未承認）
- 9.6.11: CSS数値差分2件修正済み（Flow見出し32px, Contact lead 24px）
- ユーザー指摘: まだ目視レベルの差分が多数残存。9.6.12で全件洗い出し→修正が必要。

---

### Task 10 — CI/CD

- **Before starting:** confirm with user — stg host/docroot, GitHub Secrets key names, release branch name
- [ ] `.github/workflows/deploy.yml` — 4 jobs: sync-stg → deploy-stg → e2e → deploy-prod
- [ ] Move Playwright config to use `STG_URL` for CI; local config stays `localhost:8080`
- [ ] Secrets added to GitHub repository

---

## Session Context

- Branch: `worktree-design-coding`
- PR: https://github.com/lovaizu/outputs/pull/13
- **Current state: 9.6.11 完了。9.6.12 未着手。ユーザーから「目視レベルの差分が多数残っている、全件洗い出してから修正せよ」との指示。次セッションで 9.6.12 を実施する。**

## Unresolved

- ~~`localhost:8080` returns empty body~~ — resolved: `templates/index.html` added in Task 4.

## How to Resume

1. Read `design.md` — full architecture, CPT fields, design tokens.
2. Read this file — find the first unchecked item in the Task List.
3. Proceed in order. Do not skip tasks.
4. All browser checks use `npx playwright test` — never curl.
