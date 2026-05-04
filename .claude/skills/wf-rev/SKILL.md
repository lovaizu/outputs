---
name: wf-rev
description: >-
  Reviews workflow definitions (slash commands, skills, sub-agents, step sequences,
  or CLAUDE.md rules) against core-rules.md. Use when the user asks to review,
  check, validate, lint, or audit a workflow, command, or rule against the project's
  core rules. Also invoke automatically when creating or revising a workflow to
  confirm it passes before finalizing.
argument-hint: "[workflow-definition-or-path]"
effort: high
allowed-tools: Read, Agent
context: fork
---

# /wf-rev — Workflow Reviewer

Workflows that violate core principles ship broken behavior silently. The existing rules exist but are not enforced at authoring time, so violations accumulate undetected. This skill closes that gap: it reviews every workflow definition against `core-rules.md` before it ships, surfacing violations with exact quotes, confidence scores, and ready-to-apply rewrites.

You review workflow definitions (slash command prompts, skill definitions, sub-agent instructions, or step sequences) against the core rules. Your output is a structured checklist: one verdict per rule, with quoted violations and concrete rewrites.

## Setup

Workflow definitions that violate core-rules ship broken behavior into production. Your job is to catch every violation before the workflow is finalized.

Read `.claude/rules/core-rules.md` to load all current rules before reviewing. If the file does not exist or is empty, stop and report: "No core-rules.md found — cannot review."

This skill reviews one workflow per invocation. If multiple workflow files need review, invoke wf-rev separately for each file.

Do not modify the workflow under review or any other file.

## Review process

1. **Orient** (Rule 2 — Goal-anchored): State the goal (produce a complete report judging this workflow against every core rule), the ideal end-state (a report with a verdict for every rule, every FAIL backed by an exact quote and a concrete rewrite), and the plan backwards from that end-state: read workflow → read rules → pilot subagent → spawn remaining subagents → spawn simulation agent → spawn review agent → output.

2. Read the full workflow once before judging anything.

3. Read `core-rules.md` and build a flat checklist:
   - **3a. Extract**: For each numbered rule, list its heading and every sub-bullet as a separate line item. Count the total number of rules. Do not proceed until the count is confirmed.
   - **3b. Pilot**: Scan the rule list and derive which rule is most likely to surface integration issues — the rule whose sub-bullets most directly constrain how the AI reads and processes the workflow under review. State your selection and rationale before spawning. Spawn one subagent for that rule; instruct it to read `.claude/rules/core-rules.md`, extract that rule by number, and review the workflow against its full text. If spawning fails, review the rule directly in this session and note: "Subagent spawn failed for pilot rule — reviewing directly." Review the output: confirm the format is correct and the verdict is justified. Report: "Found N rules — pilot complete ([rule name]) — proceeding to remaining N−1 rules." Only then proceed to 3c.
   - **3c. Spawn**: Spawn one subagent per remaining rule. Instruct each subagent to read `.claude/rules/core-rules.md`, extract its assigned rule by number, and return PASS/FAIL with exact quotes and confidence scores. If spawning fails for one or more rules, review those rules directly in this session and note: "Subagent spawn failed for rules: [list] — reviewed directly." Do not proceed until all rule reviews are collected. Report: "All rule reviews collected — beginning simulation."
   - **3d. Verify**: Confirm your report has exactly as many rule entries as the count from 3a — no more, no fewer. Do not proceed to Step 4 until this check passes.

4. Evaluate each rule:
   - For violations: require an exact quote from the workflow (in double quotes or inline code), a confidence score (0–100), and a concrete rewrite ready to apply. Confidence score guide:
     - 90–100: Certain violation. The rule text directly contradicts the workflow instruction.
     - 70–89: Probable violation. The workflow does not explicitly contradict the rule but will likely cause rule-breaking behavior at runtime.
     - Below 70: Do not report. Mark the rule as PASS with a one-line note if the concern is worth mentioning.
   - For irrelevant rules: mark N/A with one-line justification.

5. **Simulation agent** (Rule 3 — Verified at every stage): Spawn a simulation agent; pass it the drafted report, the workflow under review, and `core-rules.md`. Instruct it to attempt to argue that each PASS should be FAIL and that each FAIL fix is insufficient. Incorporate every finding — evaluator findings cannot be dismissed without being addressed. If any finding involves a tradeoff or a choice the rules do not determine, surface it to the user and await judgment before continuing (Rule 4). If spawning is technically infeasible, the user performs this evaluation — do not skip or substitute with self-review. Report: "Simulation complete — N findings incorporated."

6. **Review agent** (Rule 3 — Verified at every stage): Spawn a review agent with an isolated context; pass it the revised report, the workflow under review, and `core-rules.md`. Instruct it to independently verify: (a) every rule has a verdict, (b) every FAIL has an exact quote and a concrete rewrite, (c) no verdict contradicts the evidence. Incorporate any findings, then repeat review on the revised report. Continue until the review agent returns zero new issues. If spawning would cause recursive wf-rev invocation, or if spawning is technically infeasible, the user performs this evaluation — do not skip. Report: "Review agent: zero new issues — report ready to output."

## Output format

Plain markdown. Start with `## wf-rev report`, followed immediately by the overall `**Verdict**` and `**Top fix**` (or "No issues found" if all pass) — before any per-rule detail. End with `Done`. Omit **Top fix** when Verdict is PASS. See example below for exact structure.

For each rule: write the verdict on one line. If FAIL, list violations as numbered items directly beneath — each with an exact quote, a confidence score, and a ready-to-apply rewrite. Only report violations with confidence ≥ 70. If PASS or N/A, the verdict line alone is sufficient.

## Example output

The example below is illustrative only. Cover every rule found in `core-rules.md` — do not limit output to the rules shown here.

**Verdict**: FAIL
**Top fix**: Add a `git status` / `git diff` read step before writing the state summary.

**Rule N — [name from core-rules.md]**: FAIL
1. Violation (confidence: 95): "write a summary of where we are to tasks.md"
   Fix: Run `git status` and `git diff` first; write the summary from actual output, not from memory.

**Rule N — [name from core-rules.md]**: PASS

**Rule N — [name from core-rules.md]**: N/A — workflow updates a task list (structured file), not a narrative document.

Done
