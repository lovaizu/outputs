# Plugin Knowhow Smith — Steering

<!-- paused: 2026-05-12 — next: #4 Port checklist-items.md into smith-knowhow skill (clarify deploy target first) -->

## Goal

Build `smith` — a Claude Code hybrid plugin (Archetype C) that evaluates and improves Claude Code plugin setups via an Evaluate → Propose → Apply pipeline.

## Tasks

- [x] **#1 Step 1** — Scrub all 7 source docs, collect knowhow into `docs/taxonomy.md` (107 items across ARC/SPC/PRM/FLW/CTX). Commit 4a5d54b.
- [x] **#2 Step 2** — Generate `docs/checklist-items.md` from taxonomy: 108 entries (107 + PRM-EI split), 10 fields each (id, slug, domain, applies_to, severity, auto, check, fix, related, example). Sanity review passed. Commits 6e430ab, 203ec00, 3cba887.
- [x] **#3 Step 2 refinement** — Expert review of `checklist-items.md` with 3 independent parallel subagents:
  - Plugin Practitioner: high-signal vs. noise, applies_to correctness, OOS realism
  - Automation Engineer: [auto] implementability, check description precision for scripts
  - Prompt Quality Expert: PRM domain checks, fix actionability
  Fix all findings, re-review until clean.
- [ ] **#4 Step 3.1** — Port `checklist-items.md` into `smith-knowhow` skill at `agents-in-your-area/.claude/plugins/smith/skills/smith-knowhow/`. **Note**: confirm whether `agents-in-your-area` is the correct deploy target or if there is a separate aiya monorepo (open question from session).
- [ ] **#5 Step 3.2** — Write `/smith` command, 3 inspector agents, 3 scripts per `smith-design.md`.
- [ ] **#6 Step 3.3** — Dogfood smith on the `claude-plugins-knowhow` repo itself.

## Key files

| File | Role |
|---|---|
| `smith-design.md` | Authoritative implementation spec |
| `docs/taxonomy.md` | 108-item knowhow index |
| `docs/checklist-items.md` | Machine-readable checklist (smith input) |
| `progress.md` | PRM pre-decisions + step log |

## Assumptions / Rules

- Branch: `claude/plugin-smith-status-WBa5m` (PR #20)
- `k` / `y` / `進めて` = approve and proceed
- One domain or one batch per turn unless user says otherwise
- `[auto]` items bypass the convergence threshold (deterministic = always promoted)
- Severity tiers: Mandatory / Recommended / Quality only (no "Optional")
- `finding_type` format: `checklist:<component-type>:<slug>` (slug not ID)
- NG beats OOS for Mandatory items in the merge step

## Convergence formula

```
score = (num_lenses_caught × 30) + (max(self_confidence) × 0.3)
threshold = 80
[auto] items bypass threshold
```
