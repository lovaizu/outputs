# Plugin Knowhow Smith — Steering

<!-- paused: 2026-05-29 — next: #2 Modernize PRM items (mostly done in actions.md; verify) then #3 author pattern libraries -->

## Goal

Build `smith` — a tool that **applies best practices** to produce a Claude Code artifact (default: a plugin combining a custom slash command + skill + subagent(s)) that is **reproducible (A)** and **high quality (B)**.

- **A — Reproducibility**: built artifact behaves the same every run (same trigger, same files touched, byte-stable script output).
- **B — Quality**: built artifact beats the no-artifact baseline.

## Pivot (2026-05-29)

smith is **no longer a checker or converter** — it is a **best-practice-applying builder**. The old Evaluate → Propose → Apply checker (3-lens inspectors, convergence scoring, `[auto]` pre-pass, `smith-evaluate.sh`/`smith-autocheck.sh`) is **deleted**.

- **Input**: a rough throwaway draft (skill-creator or AI) used only to make intent legible; smith strips it to `{goal, trigger, inputs, outputs, ≤3 scenarios}` and **discards its structure**.
- **Pipeline position**: skill-creator drafts → **smith builds (BP applied → A + B)** → skill-creator optionally blind-A/B-compares revisions.
- **Flow**: Requirements (P1-4) → Design from BP (5-1, 5-2) → Implementation from BP (5-3, 5-4) → Verify (P6).

## Confirmed decisions

- **A/B per step**: drop single-axis labels. **Every step addresses both A and B**; structure steps (5-1/5-2) have higher leverage on both. (The earlier "5-3/5-4 = A-centric" asymmetry was rejected by two experts as backwards.)
- **Verification timing**: **evaluations-first**. Eval suite + no-artifact baseline authored at **Phase 4**; eval subsets run continuously in 5-3/5-4; Phase 6 is the final gate (A test + B test). (Matches `action.md` §C and official "evaluations first".)
- **smith owns the baseline**; skill-creator's blind A/B measures B only, not A.
- **Default output** = full plugin, but down-scope to the smallest archetype that meets the goal (minimum-viable).
- **Three phase-scoped knowledge skills** (requirements / structure-patterns / prompt-patterns), not one monolith.
- **Selective-Action governance**: Mandatory always applies; applicability by component type (mechanical); Recommended/Quality applied only if a failing eval scenario needs it; skipped items logged (not silently dropped).

## Live-docs facts (verified 2026-05-29) to bake into the checklist

- **🔴 Calibrated emphasis**: Opus 4.5/4.6/4.8 — dial back "CRITICAL: You MUST…" → "Use this tool when…". **Reverses PRM-CPM.** (Bigger impact than XML.)
- **XML scope**: only for mixed-content prompts (instructions+context+examples+input), example/output envelopes, imported steering snippets. **Forbidden** on skill `name`/`description`. Not on simple procedures.
- **Commands merged into skills**: `commands/` legacy; entry = skill with `disable-model-invocation: true`. Subagents cannot spawn subagents (all fan-out from top). Subagent `model` defaults to `inherit`.
- **Coverage-over-conservatism** for finding-stage review prompts (modernize PRM-FPE).

## Tasks

- [x] **#1 Re-color + prune the checklist** — done via 5 parallel subagents (full 108-item population, no sampling) → `docs/actions.md`. **87 survive** (A≈41 / B≈42 / BOTH≈9), **21 removed** (9 CC-default/spec-obsolete + 10 checker-only + 2 folded), **5 new** PRM items from live docs. Survivors mapped to P4 / 5-1 / 5-2 / 5-3 / 5-4 / P6.
- [x] **#2 Modernize PRM items** — folded into #1: PRM-CPM inverted (calibrated emphasis, A/B-conflict flagged), PRM-FPE inverted (coverage-then-filter), PRM-SMC→FLW, PRM-APE→PIF, PRM-CWF demoted; added PRM-CTX / PRM-ESL / PRM-RLA / PRM-LCO / PRM-RGC.
- [ ] **#3 Author the 3 knowledge-skill pattern libraries** from the pruned checklist (hand-authored prose, cross-linked to taxonomy IDs). `workflow-patterns.md` is the started Layer-2 workflow-authoring source; extend with the remaining composition/writing Actions.
- [ ] **#4 Implement the plugin** — orchestrator skill + 3 knowledge skills + architect/writer/verifier subagents per `smith-design.md`.
- [ ] **#5 Define `.smith.local.md` schema** — pinned intent, eval suite, selection log, reconcile history.
- [ ] **#6 Dogfood** smith to build/improve itself.
- [ ] **#7 Reconcile archetype trichotomy** — `smith-design.md` + `docs/concepts.md` still say "Archetype A/B/C"; commands merged into skills makes it legacy. Reframe to driver/knowledge/execution roles (see `actions.md` §Open follow-ups).

## Key files

| File | Role |
|---|---|
| `smith-design.md` | **Authoritative spec** (rewritten 2026-05-29 for the builder model) |
| `README.md` | User-facing overview (rewritten 2026-05-29) |
| `docs/actions.md` | **A/B-colored, pruned, layer-mapped Actions** (output of #1/#2) |
| `docs/workflow-patterns.md` | **Workflow-authoring templates** (prompt-as-workflow): Phase>Step>Action skeleton + sequential/branch/loop/notify/common-rules/common-flow/exception patterns + hybrid markdown/XML/script rule |
| `docs/checklist-items.md` | 108-item checklist — **source** for actions.md (kept for traceability) |
| `docs/taxonomy.md` | 108-item knowhow index |
| `docs/concepts.md`, `components.md`, `patterns.md`, `checklists.md`, `case-studies.md` | Knowhow sources for the pattern libraries |

## Assumptions / Rules

- `k` / `y` / `進めて` = approve and proceed.
- One domain or one batch per turn unless the user says otherwise.
- A = reproducibility; B = quality.
- CC default format/syntax checks are **out of scope** — never re-implement.
- Work only in this worktree (`.claude/worktrees/smith`); never touch the original repo root.

## Knowhow build history (pre-pivot, still valid as source material)

- **Step 1** — Scanned 7 source docs → `taxonomy.md`: 107 items (ARC:10 / SPC:32 / PRM:24 / FLW:29 / CTX:12). Commit 4a5d54b.
- **Step 2** — `checklist-items.md`: 108 entries (PRM-EI split). Commits 6e430ab, 203ec00, 3cba887.
- **Step 2 refinement** — Expert review × 3 parallel subagents; 17 fixes; all Clean. Commit f7ca982.

These remain valid as the knowhow corpus the pattern libraries draw on. The content survives; only the framing (checker scoring → A/B builder guidance) changes.

## Design consultation (2026-05-29)

Three expert subagents (plugin architecture / prompt engineering / agentic process & QA), each with live official docs, reviewed the builder model. Key convergent findings (all adopted): A/B asymmetry is backwards → both-axes-per-step; evaluations-first → P4 authoring + continuous run; throwaway draft must be stripped to intent (don't inherit archetype); calibrated emphasis reverses PRM-CPM; XML only for mixed-content; commands merged into skills; deterministic selective-Action governance; A test (N-run invariance) ≠ B test (baseline win), skill-creator can't own A.

## Archived (checker era — do not use)

Evaluate→Propose→Apply 10-step pipeline; 3 parallel inspector lenses; convergence formula `(num_lenses_caught × 30) + (max(self_confidence) × 0.3)` threshold 80; `[auto]` pre-pass; `smith-autocheck.sh`/`smith-evaluate.sh`/`smith-state.sh`; Finding schema / OOS rule. Superseded by the builder model.
