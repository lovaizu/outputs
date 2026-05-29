# Workflow Authoring Patterns

> A prompt is a **work instruction = a workflow**. This is the Layer-2 template+pattern library smith uses to author procedure prompts (commands / skills / agents). It defines the **Phase > Step > Action** skeleton and the control-flow constructs — sequential, conditional branch, loop, user notification, common rules, common flow, alternative/exception flow — with concrete templates.
>
> Feeds the `smith-prompt-patterns` skill (steering task #3). Cross-references Actions in `actions.md`.

## Format rule (derived from smith's axes — not optional)

The markdown-vs-XML choice is **derived** from reproducibility (A) + quality (B) + official guidance, not a free preference:

| Content | Form | Why |
|---|---|---|
| Linear step sequence | **markdown** numbered list / `- [ ]` checklist | Official idiom for procedures; wrapping each step in `<step>` is verbose and lowers B. |
| Structural block needing an unambiguous boundary — common rules, named common flow, multi-line branch case, alternative/exception flow, injected context, examples, output envelope | **XML tags** | Clear boundaries reduce misread → fewer nondeterministic interpretations (A) without polluting the step prose (B). |
| Control flow that must be deterministic / byte-repeatable | **a script** (FLW-DSAS), invoked from the prompt | A prompt cannot guarantee repeatability; a script can. Strongest A lever. |

Hard constraints: XML is **forbidden** on skill `name`/`description`. Short single-line branches stay inline markdown (`If X → …`); promote to XML `<case>` only when a branch spans multiple lines or nests.

→ Net: **hybrid** — markdown for the spine, XML for bounded blocks, scripts for determinism.

## Skeleton — Phase > Step > Action

The top-level shape of every smith-authored workflow. Phases are gated; steps are markdown; actions are imperative.

```markdown
## Phase N — <name>
**Goal:** <what this phase achieves.>
1. **<step>** — <imperative action>.
2. **<step>** — <imperative action>.
**Exit:** <the condition that must hold before the next phase.>
```

- A: fixed phase order + explicit exit gates ⇒ same path every run (FLW-PC, FLW-EAG).
- B: a stated per-phase Goal keeps each step purposeful.

## Control-flow patterns

Each pattern: **when** · **A/B** · **template**. All map to Actions in `actions.md`.

### 1. Sequential

When: steps run in fixed order with no decision. Default.

```markdown
1. <action>.
2. <action>.
3. <action>.
```
A+B (PRM-MSS). Use a `- [ ]` checklist instead when the model should track completion across a long run (CTX-TWA).

### 2. Conditional branch ("in the case of XXX")

When: the path depends on a condition. **Enumerate cases exhaustively, including the fall-through**, so no path is left to guess (A).

Short branches — inline markdown:
```markdown
3. If tests exist, run them; otherwise note "no tests" and continue.
```

Multi-line / nested branches — XML `<case>`:
```markdown
Determine the archetype, then follow the matching case:

<case condition="procedure the user triggers">
1. ...
2. ...
</case>
<case condition="reference knowledge only">
1. ...
</case>
<case condition="none of the above">
Report that the target is unclassifiable and stop. Do not guess.
</case>
```
A: exhaustive cases + explicit fall-through remove a degree of freedom. B: XML boundaries keep multi-line branches unambiguous (PRM-SC, PRM-ESL, PRM-CTX).

### 3. Loop (loop-until / for-each)

When: repeat until a condition holds, or iterate over a known set. **Always bound iterations and forbid false completion** (FLW-LEB).

```markdown
Repeat until <termination condition> (max <N> iterations):
1. <action>.
2. Re-check <termination condition>.
If still unmet after <N>, report the residual and stop — do not emit a completion claim.
```
For-each:
```markdown
For each <item> in <set>:
1. <action on the item>.
(Process every item; do not sample. State the count handled.)
```
A: bounded + explicit termination + no-false-completion + full-population (action.md §B.2).

### 4. User notification & approval gate

When: a consequential or irreversible decision. **Calibrate the emphasis to reversibility** (PRM-CPM / PRM-RGC): hard gate only when irreversible; otherwise act and notify.

Irreversible (hard gate):
```markdown
**Get explicit user approval before proceeding.**
Present: <summary of what will happen>.
Options: apply all / select a subset / reject.
```
Reversible (notify, don't block):
```markdown
Proceed, then report what changed in one line. The user can revert via git.
```
A: gate position is fixed. B: calibrated emphasis avoids over-triggering (FLW-EAG, PRM-RGC).

### 5. Common rules block

When: constraints that apply to every step. State **once**, near the top, in a bounded block.

```markdown
<common_rules>
- Never write outside the repository.
- Stop and ask before any destructive or irreversible action.
- Report facts; if a step is skipped, say so.
</common_rules>
```
A: identical constraints every run. B: single source, no per-step repetition (PRM-CWF).

### 6. Common flow (shared sub-procedure)

When: the same sub-procedure is invoked from several places. Define once as a named flow; reference it by name.

```markdown
<flow name="verify_preimage">
1. Re-read the target file.
2. Confirm it still matches the expected pre-image; if not, halt and report.
</flow>

... later ...
Before each write, run the `verify_preimage` flow.
```
A: the same sub-procedure runs identically wherever invoked (DRY → no drift) (FLW-PIV, FLW-DSAS for the deterministic parts).

### 7. Alternative / exception flow (on-failure / fallback)

When: define what happens when a step fails — separated from the happy path so it can't be skipped.

```markdown
<on_failure>
- Halt at the failing step. Do not auto-rollback.
- Report the partial state and point the user at `git status`.
- Never emit a completion claim while the failure stands.
</on_failure>
```
A: failure handling is fixed and explicit, not improvised. B: bounded block keeps it out of the happy-path prose (FLW-PVE, FLW-LEB).

## Composition order within a single prompt

To support long-context ordering (PRM-LCO) and clean parsing:

1. Role lead (one sentence) — PRM-RLA.
2. `<common_rules>` — shared constraints.
3. Injected context (`<diff>`, `<git_status>`, …) — large material near the top.
4. The Phase > Step > Action body (markdown), with inline branches and referenced `<flow>`s.
5. `<case>` / `<on_failure>` blocks where needed.
6. Output contract / `<example>` / output envelope near the end (PRM-OSD, PRM-EI-*, PRM-CTX).

## Action cross-reference

| Pattern | Actions |
|---|---|
| Skeleton | FLW-PC, FLW-EAG, PRM-MSS |
| Sequential | PRM-MSS, CTX-TWA |
| Branch | PRM-SC, PRM-ESL, PRM-CTX |
| Loop | FLW-LEB, action.md §B.2 (full population) |
| Notify / approval | FLW-EAG, PRM-RGC, PRM-CPM (calibrated) |
| Common rules | PRM-CWF, PRM-CTX |
| Common flow | FLW-PIV, FLW-DSAS |
| Alternative / exception | FLW-PVE, FLW-LEB |
| Composition order | PRM-LCO, PRM-RLA, PRM-OSD, PRM-CTX |
