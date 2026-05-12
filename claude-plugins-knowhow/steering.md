# Plugin Knowhow Smith — Steering

<!-- paused: 2026-05-12 — next: #4 Port checklist-items.md into smith-knowhow skill (clarify deploy target first) -->

## Goal

Build `smith` — a Claude Code hybrid plugin (Archetype C) that evaluates and improves Claude Code plugin setups via an Evaluate → Propose → Apply pipeline.

## Tasks

- [x] **#1 Step 1** — Scrub all 7 source docs, collect knowhow into `docs/taxonomy.md` (107 items across ARC/SPC/PRM/FLW/CTX). Commit 4a5d54b.
- [x] **#2 Step 2** — Generate `docs/checklist-items.md` from taxonomy: 108 entries (107 + PRM-EI split), 10 fields each. Sanity review passed. Commits 6e430ab, 203ec00, 3cba887.
- [x] **#3 Step 2 refinement** — Expert review (Plugin Practitioner / Automation Engineer / Prompt Quality Expert) × 3 parallel subagents. 17 fixes applied. All three returned VERDICT: Clean. Commits f7ca982.
- [ ] **#4 Step 3.1** — Port `checklist-items.md` into `smith-knowhow` skill at `agents-in-your-area/.claude/plugins/smith/skills/smith-knowhow/`. **Note**: confirm whether `agents-in-your-area` in this repo is the correct deploy target or if there is a separate aiya monorepo (open question from session).
- [ ] **#5 Step 3.2** — Write `/smith` command, 3 inspector agents, 3 scripts per `smith-design.md`.
- [ ] **#6 Step 3.3** — Dogfood smith on the `claude-plugins-knowhow` repo itself.

## Key files

| File | Role |
|---|---|
| `smith-design.md` | Authoritative implementation spec |
| `docs/taxonomy.md` | 108-item knowhow index |
| `docs/checklist-items.md` | Machine-readable checklist (smith input) |

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

## Background

### Original intent

smith + aiya-jam を aiya モノレポの `.claude/` 配下で開発。smith を使って jam を作る。

Knowhow workstream: taxonomy にノウハウを集め → チェックリスト化 → smith 実装。

### Pivots

- jam は smith のスコープから除外; Create mode 削除。
- "Consultant" → "craftsperson" に再定義。
- ノウハウ整理が smith 実装の前提として浮上。
- 当初49件の抽出はドメイン分類バイアスで汚染 → 全7ファイル再スキャン。

## Step history

### Step 1 — 完了

全7ソースファイルをスキャン。taxonomy.md: **107件**（ARC:10 / SPC:32 / PRM:24 / FLW:29 / CTX:12）。除外2件（理由は taxonomy.md §Excluded）。

### Step 2 — 完了

checklist-items.md: **108エントリ**（PRM-EI を EI-S + EI-CA に分割して +1）。

| Domain | Entries | Mandatory | Recommended | Quality | [auto] |
|---|---|---|---|---|---|
| ARC | 10 | 2 | 7 | 1 | 2 |
| SPC | 32 | 12 | 15 | 5 | 10 |
| PRM | 25 | 6 | 16 | 3 | 8 |
| FLW | 29 | 8 | 19 | 2 | 4 |
| CTX | 12 | 1 | 10 | 1 | 1 |
| **Total** | **108** | **29** | **67** | **12** | **25** |

## Notes for Step 3 implementers

### smith-autocheck.sh

- **PRM-CWF thresholds**: 60-word paragraph cap; identical 4+-word phrase repeated within 150-word window = NG. Both fully `[auto]`.
- **PRM-TC**: `[judgment]` canonical. Mechanical string-scanning is first-pass only; must be followed by FP filter before marking NG. Do NOT tag as `[auto]`.
- **PRM-SMC known phrases**: "do not send any other text", "complete in one turn", "do not ask for confirmation", "respond in a single message", "no follow-up".
- **PRM-NRP known phrases**: "if no results", "return empty", "nothing to report", "no findings", "when nothing is found".

### smith-evaluate.sh

- **NG beats OOS for Mandatory items**: if any lens marks NG on a Mandatory item, promote regardless of OOS votes from other lenses.
- **expected_effect ranking**: sort findings by `len(expected_effect)` descending before output.

### smith-knowhow SKILL.md

- **PRM-LFD is additive**: does not replace SPC items (third-person check, trigger check). If a description violates both PRM-LFD and a SPC item, report under the more specific SPC item.
- **Content-conditional OOS carve-out**: PRM-DPE and PRM-SAC resolve OOS (not NG) when their content precondition is absent. This is intentional; divergent lens verdicts (one OOS, one NG) will drop Quality findings — by design.
- **Architecture-lens singleton risk**: PRM-SC, PRM-FLD, PRM-MSS are holistic items only the architecture lens sees. Mitigate by including these in `references/` so conventions + patterns lenses can also evaluate them independently.
- **finding_type slug list**: include known-slugs validation list so lenses derive `finding_type` from slug column, never from ID.
