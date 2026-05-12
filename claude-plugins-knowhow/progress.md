# Progress Log

> Resumption log. smith design lives in `smith-design.md`. Knowhow taxonomy lives in `docs/taxonomy.md`. Per-file audit findings live in `audit-notes.md`.

## Original intent

First message (Japanese, preserved verbatim):

> plugin-smith
> aiya-jam
> の順に作成を進めたい。
> すべてaiyaのモノレポで開発、ただしこの2つはモノレポのパッケージ開発でも使いながら改善するので、モノレポの.claudeに配置します。
> イメージできますか？
> smith作って、smith使ってjamを作る

Subsequent intent (this work-stream, verbatim):

> claude pluginのノウハウ、ノウハウに分かりやすく覚えやすい名前を付けて、チェック項目を扱いやすくしたい。

User's explicit 2-step plan (verbatim):

> **１** taxonomyにノウハウ集めてるんですよね？チェックリストやケーススタディなど、すべてのドキュメントを精査してtaxonomyに具体的なノウハウとして集めましょう
>
> **２** １でノウハウが固まるので、あとはチェックリストがあればよいですよね。ノウハウからチェックリストを作成しましょう、チェックリストにはチェック方法、改善方法、改善例など、smithの実行を想定して必要な項目を定義してから進めましょう。

## Pivots

- jam removed from smith's scope; Create mode dropped.
- "Consultant" reframed to "craftsperson".
- Knowhow indexing surfaced as a prerequisite to smith implementation.
- Original 49-item extraction was contaminated by domain-classification bias. All 7 files re-scanned.

## Step status

### Step 1 — Scrub all docs, collect all knowhow into taxonomy ✅ COMPLETE

All 7 source files scanned. taxonomy.md updated.

| File | Status |
|---|---|
| `docs/concepts.md` | ✅ scanned (commit b7f53bd) |
| `docs/components.md` | ✅ scanned (commit 55433d5) |
| `docs/patterns.md` | ✅ scanned (commit 778936d) |
| `docs/case-studies.md` | ✅ scanned (commit 496d639) |
| `docs/checklists.md` | ✅ scanned (commit 5f758f9) |
| `README.md` | ✅ scanned (commit d436b2e) |
| `smith-design.md` | ✅ scanned (commit 4a5d54b) |

Reconciliation complete (commit 4489a96). taxonomy.md: **107 items** across 5 domains.

| Domain | Items |
|---|---|
| ARC | 10 |
| SPC | 32 |
| PRM | 24 |
| FLW | 29 |
| CTX | 12 |
| **Total** | **107** |

Excluded 2 items with reasons (see taxonomy.md §Excluded).

### Step 2 — Generate checklists from taxonomy ❌ NOT STARTED

For each taxonomy item, define:

| Field | Description |
|---|---|
| `id` | taxonomy ID (e.g. `PRM-IV`) |
| `severity` | Mandatory / Recommended / Quality |
| `auto` | `[auto]` (machine-verifiable) or `[judgment]` (requires LLM/human) |
| `check` | What to verify (one sentence) |
| `fix` | How to improve when NG (one sentence) |
| `example` | Before/after illustration (optional but preferred) |

Output: a structured checklist file that smith's `smith-knowhow` skill can load at runtime. One entry per taxonomy ID.

**Before starting Step 2**: agree on the output format and file structure with the user. Do not generate 107 entries without alignment.

## Decisions made

- 5 domains: `ARC` / `SPC` / `PRM` / `FLW` / `CTX` (mechanism-axis).
- ID format: `DOMAIN-INITIALS` (e.g. `SPC-ATR`).
- Name: kebab-case 2–4 words, mechanism-focused.
- IDs are stable once assigned.
- Severity model: Mandatory / Recommended / Quality (3-tier).
- Automation stance: `[auto]` / `[judgment]` per item.
- All 7 source files in taxonomy scope (no exclusions by file).

### PRM domain pre-decisions (Step 2 input — from expert review 2026-05-12)

#### Taxonomy changes already applied

| Change | Detail |
|---|---|
| `PRM-SBS` renamed | `skill-body-style` → `concrete-example-shape`. Check: skill body examples are concrete in I/O shape (input format + expected output shown). |
| `PRM-IFL` renamed | `instruction-freedom-level` → `PRM-FLD` (`freedom-level-declaration`). Check: prompt explicitly declares intended freedom level (open-ended / parameterized / procedural). `[auto]` — presence detectable. |
| `PRM-NT` removed | Not an inspectable file property. Moved to Excluded with rationale. |
| `PRM-EI` added | `example-inclusion` — at least one concrete example is present. `[judgment]` for commands/agents; `[auto]` for skills (SKILL.md §3 examples rule). |

#### Severity (apply in Step 2)

| ID | Name | Severity | Note |
|---|---|---|---|
| `PRM-PIF` | positive-instruction-form | **Mandatory** | |
| `PRM-VSC` | verifiable-success-criteria | **Mandatory** | |
| `PRM-IS`  | instruction-specificity | **Mandatory** | |
| `PRM-TC`  | terminology-consistency | **Mandatory** | [auto] FP guard: do not flag intentional paired synonyms (e.g., "command" / "slash command" used together explicitly). Flag only when the same concept has two names without an explicit pairing statement. |
| `PRM-EI`  | example-inclusion | **Recommended** | `auto` is conditional by component: skill → `[auto]` (presence-only); command/agent → `[judgment]`. Schema implementation: if a single `auto` field cannot hold both, split into PRM-EI-S (skill, auto) and PRM-EI-CA (command/agent, judgment). |
| `PRM-IR`  | instruction-rationale | **Recommended** | Binary check (presence only); adequacy is out of scope. |
| `PRM-FLD` | freedom-level-declaration | **Recommended** | Governs agent autonomy scoping; absence leaves freedom level implicit. |
| `PRM-SMC` | single-message-completion | **Mandatory** | Absence in a command that requires single-turn completion is workflow-breaking, not advisory. `[auto]` — phrase-presence detectable. |
| `PRM-NRP` | null-result-protocol | **Mandatory** | Same failure mode as PRM-VSC (no terminal condition specified). Absence causes silent failure or undefined behavior when zero results occur. |
| `PRM-DPE` | default-plus-escape | **Quality** | Conditional: only when prompt governs choice with ≥2 valid approaches. Not for single-path or open-ended tasks. |
| `PRM-SAC` | single-approach-commitment | **Quality** | Conditional: only when prompt governs structured, reproducible task. Not for exploration or open-ended tasks. |
| `PRM-TIC` | time-independent-content | **Recommended** | Stale dates/release refs cause silent failures. Schema implementation: severity is Recommended for command/agent; Quality for skill. If a single severity field cannot represent both, record Recommended and handle the skill-only downgrade in the `applies_to` filter logic at inspection time. |

#### `applies_to` constraints (apply in Step 2)

| ID | Constraint |
|---|---|
| `PRM-CPM` | command, agent only (multi-phase workflows) |
| `PRM-MSS` | command, agent, skill — only when prompt contains multi-step procedure |
| `PRM-CD`  | command, agent — orchestrator-type only. Orchestrator = a command or agent that dispatches ≥1 other agent or invokes ≥1 skill via the Skill tool. Non-dispatching commands are excluded. |
| `PRM-LFD` | skill only — applies to the `description` field in SKILL.md front matter. `[judgment]`: active verb + concrete outcome requires human/LLM assessment. |
| `PRM-EI`  | skill (`[auto]` for presence only; adequacy is out of scope), command, agent (`[judgment]`) |
| `PRM-FPE` | inspection-class prompts only — commands or agents whose output is a list of findings, issues, or recommendations. Not applicable to prompts that produce code, files, or non-finding outputs. |
| `PRM-NRP` | prompts containing action verbs: find, list, search, scan, match, return, collect, detect — or any prompt that can complete with zero results by design. |
| `PRM-DPE` | content-conditional (not component-conditional): apply only when the prompt contains a choice gateway — a branch point where ≥2 approaches are valid and Claude must select one. |
| `PRM-SAC` | content-conditional: apply only when the prompt governs a structured, reproducible task with a defined completion state. |

Note on PRM-MSS / PRM-CPM asymmetry: a skill with a multi-step procedure triggers PRM-MSS but never PRM-CPM. This is intentional — critical phase markers (DO NOT SKIP, DO NOT START WITHOUT APPROVAL) are a command/agent construct. Agents must not flag the asymmetry as inconsistency.

#### Item redefinitions (apply in Step 2)

| ID | Redefinition |
|---|---|
| `PRM-IV`  | Imperative form throughout: direct commands ("Do X"), not descriptions ("This command does X") or questions. All `.md` files in a plugin. |
| `PRM-IR`  | Binary check only: does the prompt contain ≥ 1 sentence explaining *why* the instruction matters? Adequacy is out of scope. |
| `PRM-CWF` | Structural proxy: no paragraph exceeds 60 words (~80 tokens at 4 chars/token); no phrase of 4+ words repeated within 150 words of itself. Word-count used throughout to avoid tokenizer ambiguity. `[auto]` for repetition; `[judgment]` for paragraph density. |
| `PRM-LFD` | Skill description uses forward-leaning language: active verbs + concrete outcome. Examples — OK: "Analyzes PR diffs and returns structured findings"; NG: "A tool for PR analysis". Applies to the `description` field only; not the SKILL.md body. |
| `PRM-FLD` | Check: does the prompt contain an explicit statement of intended freedom level using one of these exact terms or clear paraphrases — open-ended ("Claude chooses the approach," "explore freely"), parameterized ("approach is fixed, inputs vary," "follow this template"), or procedural ("step-by-step," "do not deviate," "follow phases in order"). A vague preamble does not qualify. Examples — NG: "This command helps you create features." OK: "This is a procedural workflow — follow phases in order, do not deviate." The statement must be present in the prompt body, not inferred from surrounding structure. |
| `PRM-SMC` | Check `[auto]`: does the prompt include a directive to complete the task in a single response without requesting clarification or issuing follow-up messages? Detectable by presence of phrases such as "do not send any other text," "complete in one turn," "do not ask for confirmation." Absence = NG. |
| `PRM-OSD` | Defines the *structure* of the output: what sections, fields, or format the response must contain (e.g., "Return a JSON array with fields X, Y, Z", "Output a markdown table with columns A and B"). Check: does the prompt specify what the output must look like structurally? |
| `PRM-OFD` | Defines the *discipline* of output format rules: each rule is precise, complete, and internally consistent. Check (structural, agent-observable): does every format rule in the prompt name (a) what must appear, (b) what must not appear, and (c) any quantity/ordering constraint? e.g., "Every finding must include a link to the file and line range" is OFD-compliant; "Keep it brief" is not. Distinct from PRM-OSD (structure of output) — OFD is about the *precision* of format instructions. |
| `PRM-APE` | Check: when the prompt prohibits ≥2 behaviors, are those prohibitions co-located in a named anti-pattern list (e.g., "Do not do any of the following: …")? Co-located = within the same markdown section (delimited by the same heading level). Prohibitions under different headings = not co-located = NG. Threshold: ≥2 prohibitions not co-located = NG. Single prohibition anywhere = out of scope. |
| `PRM-NRP` | Check: does the prompt specify what to output when no result is found — one of: explicit empty marker (e.g., "Return an empty array"), silence with rationale, or escalation path (e.g., "Report 'no findings' and exit")? Absence = NG. Trigger: prompt contains action verbs find / list / search / scan / match / return / collect / detect, or otherwise can complete with zero results by design. |
| `PRM-SC`  | Check: does the prompt bound its operating scope — explicitly stating what inputs, domains, or file types are in-scope and/or excluded? (e.g., "Only review files changed in this PR", "Do not check build signal"). Absence means Claude must infer scope, risking over-reach. |
| `PRM-FPE` | Check: does the prompt include an explicit enumeration of false-positive categories — findings that look valid but must not be reported (e.g., "Do not report pre-existing issues", "Do not flag issues lint will catch")? Absence causes systematic false positives in inspection-type prompts. |

#### `related` links (apply in Step 2)

These clusters must be linked so `expected_effect` is correctly computed. A fix to any item in a cluster will cause all others in the cluster to change state.

| Cluster | Members |
|---|---|
| Explanatory content | `PRM-IR` ↔ `PRM-IS` |
| Scope / specificity | `PRM-IS` ↔ `PRM-SC` ↔ `PRM-FPE` ↔ `PRM-DPE` |
| Prompt length | `PRM-CWF` ↔ `PRM-SMC` |
| Negative instruction removal | `PRM-PIF` ↔ `PRM-APE` ↔ `PRM-FPE` |
| Multi-step structure | `PRM-MSS` ↔ `PRM-CPM` ↔ `PRM-SAC` (CPM and SAC are subsets; MSS is parent) |
| Terminal conditions | `PRM-VSC` ↔ `PRM-NRP` |
| Output specification | `PRM-OSD` ↔ `PRM-OFD` |
| Example quality | `PRM-SBS` ↔ `PRM-EI` |
| Silent failure | `PRM-TIC` ↔ `PRM-VSC` |

Note: `PRM-FPE` appears in two clusters (scope/specificity and negative-instruction-removal). This is intentional — a false-positive list fix simultaneously tightens scope and removes negative framing. Both `related` links must be recorded on the PRM-FPE entry.

#### Schema notes (apply in Step 2)

- **`self_confidence`**: Inspector agents self-report on a 0–100 integer scale. Definition and scoring table are in `smith-design.md §Interfaces §Convergence score formula`. The checklist schema does not need to store this field — it is emitted by agents at inspection time, not stored per checklist item.
- **Conditional auto/severity**: PRM-TIC and PRM-EI have `applies_to`-dependent auto/severity. If the schema supports only one value per field, split into two items at generation time (e.g., PRM-EI-S for skill, PRM-EI-CA for command/agent). Record the split decision in Step 2.6 sanity review.

## Decisions deferred

- Single-file vs per-domain split for the Step 2 checklist output.
- Whether Step 2 output is a new file (`docs/checklist-items.md`) or replaces / extends the existing `docs/checklists.md`.
- Exact schema for `example` field (inline markdown vs separate file reference).
- Whether to generate all 107 at once or domain by domain.

## Remaining tasks

### Step 2 — Generate checklists from taxonomy

**Step 2.0 — Agree on output format** (in progress)

Proposed schema (awaiting user confirmation):

| Field | Description |
|---|---|
| `id` | taxonomy ID (e.g. `PRM-IV`) |
| `severity` | `Mandatory` / `Recommended` / `Quality` |
| `auto` | `[auto]` (machine-verifiable) or `[judgment]` (LLM / human judgment) |
| `check` | What to verify (one sentence) |
| `fix` | How to improve when NG (one sentence) |
| `example` | Before/after illustration (preferred but optional) |

Proposed file: `docs/checklist-items.md` (new file; keeps existing `docs/checklists.md` as source material).

Proposed generation cadence: **one domain per turn**, in order ARC → SPC → PRM → FLW → CTX.

**Step 2.1 — Generate ARC entries** (10 items) — blocked on 2.0 agreement.

**Step 2.2 — Generate SPC entries** (32 items) — blocked on 2.1.

**Step 2.3 — Generate PRM entries** (24 items) — blocked on 2.2.

**Step 2.4 — Generate FLW entries** (29 items) — blocked on 2.3.

**Step 2.5 — Generate CTX entries** (12 items) — blocked on 2.4.

**Step 2.6 — Sanity review** — cross-check all 107 entries for: ID coverage (no taxonomy ID missing), severity distribution reasonable, `[auto]` items are actually machine-verifiable, `fix` fields are actionable.

**Step 2.7 — Retire `docs/checklists.md`** — once Step 2.6 passes, decide whether to delete the original checklists.md (now superseded by per-ID entries) or keep it as a narrative overview pointing at checklist-items.md.

### Step 3 — Implement smith

**Step 3.1 — Port checklist-items.md into smith-knowhow skill** at `agents-in-your-area/.claude/plugins/smith/skills/smith-knowhow/`.

**Step 3.2 — Write `/smith` command, 3 inspector agents, 3 scripts** per `smith-design.md` spec.

**Step 3.3 — Dogfood smith on the claude-plugins-knowhow repo itself** (first real inspection target).

## Session context

- Working branch: `claude/plugin-knowledge-naming-dYL6G`.
- `k` or `y` = approve. `進めて` = proceed autonomously.
- One domain or one batch per turn — do not generate all 107 at once without user signal.

## How to resume

1. Read `.claude/rules/*.md` (esp. `interaction.md`, `workflow.md`, `language.md`).
2. Read this file — current state is in "Step status" and "Remaining tasks" above.
3. Read `docs/taxonomy.md` to know the 107 items to be checklisted.
4. Locate the first task in "Remaining tasks" that is not yet done.
   - If Step 2.0 is still "awaiting user confirmation", re-ask the user about the schema and file name before generating anything.
   - If Step 2.0 is agreed and the user said "go", start with Step 2.1 (ARC).
5. Do NOT restart Step 1 scans. `docs/taxonomy.md` is the authoritative output of Step 1.
6. Do NOT generate multiple domains in one turn without an explicit signal — one domain per turn is the default.
