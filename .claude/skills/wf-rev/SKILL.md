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

Workflows that violate core principles ship broken behavior silently. This skill catches every violation before a workflow is finalized — by reviewing it against `core-rules.md` and producing a verdict per rule, with exact quotes and ready-to-apply rewrites.

## Preconditions

Read `.claude/rules/core-rules.md` before doing anything. If the file is missing or empty, stop and report: "No core-rules.md found — cannot review."

Read the target workflow in full before judging anything. Do not infer its content from its filename or description.

This skill reviews one workflow per invocation. Do not modify any file.

## Definitions

**Confidence score:** how certain a violation is.
- 90–100: certain — rule text directly contradicts the workflow instruction
- 70–89: probable — workflow will likely cause rule-breaking behavior at runtime
- Below 70: do not report as a violation

**Verified fact:** confirmed by direct observation (reading the file, running the command) — not by inference or reasoning from related facts.

## Process

**Step 1 — Orient (Rule 2)**

State before acting:
- **Goal:** produce a report that judges this workflow against every rule in core-rules.md — this goal is fixed regardless of whether this skill was invoked directly by the user or automatically
- **Ideal end-state:** a report with a verdict for every rule; every FAIL backed by an exact quote and a ready-to-apply rewrite; simulation and review agents have confirmed zero concerns
- **Plan:** load facts → extract rules → pilot Rule 1 → spawn per remaining rule → verify coverage → draft report → simulation agent → review agent → output

**Step 2 — Load facts (Rule 1)**

Read the target workflow and core-rules.md directly from their file paths. Both must be read now — do not rely on memory, prior summaries, or content passed by the invoking agent.

**Step 3 — Extract rules (Rule 1 — complete population)**

From core-rules.md, list every rule heading and every sub-bullet as a separate checklist item. Count the total. Cross-check by listing each rule heading verbatim below the count. Do not proceed until the count matches the listed headings one-for-one.

**Step 4 — Pilot: Rule 1 (Fact-grounded)**

Rule 1 (Fact-grounded) governs how an AI reads and processes content, making it the most likely to surface process issues in any workflow. Always pilot this rule.

Spawn one subagent. Instruct it:
- Read core-rules.md from its file path
- Read the target workflow from its file path — do not rely on any content passed to you
- Extract Rule 1 (Fact-grounded) in full
- Review the workflow against every line of Rule 1
- Return PASS/FAIL with exact quote and confidence score for each violation

After receiving the output, verify format only: PASS/FAIL present, exact quote included, confidence score present. Do not re-evaluate the verdict content — that is the subagent's role; verdicts are re-examined in Steps 8 and 9.

Report: "Pilot complete (Rule 1 — Fact-grounded) — N rules total — proceeding to remaining N−1 rules."

**Step 5 — Spawn per remaining rule (Rule 1 — complete population)**

Spawn one subagent per remaining rule. For each subagent, instruct:
- Read core-rules.md from its file path
- Read the target workflow from its file path — do not rely on any content passed to you
- Extract the assigned rule by number, in full
- Review the workflow against every line of that rule
- Return PASS/FAIL with exact quote and confidence score for each violation

Collect all results before proceeding.

**Step 6 — Verify coverage (Rule 1)**

Confirm: number of verdicts collected = count from Step 3. No rule may be missing or duplicated. Do not proceed until verified.

**Step 7 — Draft report**

For each rule:
- **FAIL:** exact quote from the workflow (in double quotes or inline code), confidence score (≥ 70 only), ready-to-apply rewrite
- **PASS:** verdict line only
- **N/A:** verdict line with one-line justification

**Step 8 — Simulation agent (Rule 3)**

Spawn a simulation agent independent from you (the producer). Pass it the workflow file path and core-rules.md file path — not the file contents, so the agent reads them directly. Also pass the drafted report text. Instruct it:
- Read the workflow and core-rules.md directly from the provided file paths — do not rely on content passed to you
- Argue that each PASS should be FAIL and each FAIL fix is insufficient
- Evaluate whether the report as a whole enables the user to make a fully informed decision
- Check whether any rule was assessed without direct evidence from the workflow file
- Check whether the output format matches the template in this skill

Every finding must be incorporated — evaluator findings cannot be dismissed without being addressed. Before applying a finding, verify it maps to a violation of a rule in core-rules.md — not a stylistic preference. Reject findings that would expand the goal beyond producing a per-rule verdict report, unless the user directs otherwise.

If any incorporated finding involves a tradeoff or a choice the rules do not determine, surface it to the user and await judgment before continuing (Rule 4).

Report: "Simulation complete — N findings incorporated."

**Step 9 — Review agent (Rule 3)**

Spawn a review agent independent from you and from the simulation agent. Pass it the workflow file path, core-rules.md file path, and the revised report text. Instruct it:
- Read the workflow and core-rules.md directly from the provided file paths — do not rely on content passed to you
- Do not invoke wf-rev or any other skill
- Do not spawn further agents
- Independently verify: (a) every rule has a verdict, (b) every FAIL has an exact quote and a rewrite, (c) no verdict contradicts the evidence

Incorporate all findings. Repeat until the review agent returns zero new issues.

Report: "Review agent: zero new issues — report ready."

**Step 10 — Output (Rule 4)**

Lead with the overall Verdict and Top fix. List FAIL verdicts first ordered by confidence score descending, then PASS and N/A verdicts. Omit evidence that would not change the decision. End by stating any items that require the user's judgment before the workflow is finalized.

## Output format

```
## wf-rev report

**Verdict**: PASS | FAIL
**Top fix**: [single most important fix — omit this line when Verdict is PASS]

**Rule N — [name]**: FAIL
1. Violation (confidence: 95): "exact quote from workflow"
   Fix: [ready-to-apply rewrite]

**Rule N — [name]**: PASS

**Rule N — [name]**: N/A — [one-line justification]

Items requiring your judgment before finalizing: none | [list]

Done
```
