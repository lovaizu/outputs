---
description: Evaluate an artifact or workflow against Action Principles. Reports violations with principle references and improvement suggestions. Use when producing or reviewing any artifact, plan, or workflow.
allowed-tools:
  - Read
  - Agent
---

# /assay — Principle Evaluation

**Goal**: Evaluate the target artifact or workflow against Action Principles. Report violations by principle reference and suggest concrete improvements.

**Evaluation goal (confirmed)**: Assess compliance with Action Principles. Criteria that are not applicable to a given target type will yield no findings for that criterion.

**End state**: A report with a PASS/FAIL verdict, valid findings (quorum: 2 of 3 evaluators agree), and informational findings (1 evaluator only).

## Autonomous invocation

When invoked by an agent rather than a user, `$ARGUMENTS` must be supplied by the calling agent as the artifact text or an absolute file path. Empty `$ARGUMENTS` are not valid for autonomous invocation.

## Steps

### 1. Identify the target

Take `$ARGUMENTS` as the evaluation target:
- If it is a file path, read the file. If the file cannot be read, stop and report: "Cannot read [path]. Please provide an accessible path or paste the artifact text directly." If the file is readable but empty, stop and report: "The file at [path] is empty — cannot evaluate."
- If it is a description of an artifact or workflow, use it as-is.
- If empty, stop and report: "No evaluation target provided. Supply artifact text or a file path as the argument."

### 2. Read the principles

Read `.claude/rules/action.md`. If the file cannot be read, stop and report it missing — do not proceed to spawn evaluators.

### 3. Spawn 3 independent evaluators

Spawn 3 subagents in parallel using the Agent tool. Each evaluator prompt must contain **only** these three items — nothing from the current conversation, prior turns, or intermediate work:

1. The full text of the evaluation target
2. The full text of action.md and evaluation.md
3. This instruction verbatim: "You are an independent evaluator. First, write out all evaluation criteria you will use, derived from the Action Principles provided. These are the only valid criteria — do not add post-hoc criteria. Then evaluate the artifact against each criterion. For each violation, quote the specific sentence or phrase from the artifact, state the criterion code (e.g. A.2, B.1), describe the violation concretely, and suggest a specific improvement. List each concrete issue as a separate finding even if it shares a criterion code with another. For each criterion with no violation, explicitly state PASS."

If the execution environment does not support spawning evaluators as structurally isolated subagents with no access to the parent conversation context, stop and report this inability to the user — do not proceed, per EA.2.

A valid evaluator result contains: criteria stated before the evaluation begins, and a substantive assessment referencing specific criterion codes with either PASS statements or concrete violation descriptions. An empty response, a refusal, a response that states no criteria, or a response that lists criterion codes without descriptions is not a valid result and triggers the re-spawn rule.

If fewer than 3 evaluators return a valid result, re-spawn the missing evaluator once. If it still fails, report all findings received, state that quorum cannot be assessed, and stop — do not deliver the artifact.

Do not share any evaluator's output with the others before all three complete.

**Known constraint**: If the evaluation target is a file that is automatically loaded into the system context (e.g. project instructions, rules files), complete structural isolation of evaluators is architecturally impossible — the target content will be present in each subagent's context regardless of the prompt. This is an accepted limitation. Evaluators should note this constraint but must proceed and report findings as usual.

### 4. Determine quorum

For each reported finding:
- Reported by 2 or 3 evaluators → **valid finding**
- Reported by 1 evaluator only → **informational**

A finding is "the same" if two evaluators cite the same criterion code AND (the same quoted passage OR the same described behavior). When ambiguous, treat as the same finding (conservative toward actionability) and note the ambiguity in the report. If one evaluator lists two sub-findings under a criterion and another lists one, match each sub-finding individually — an unmatched sub-finding is informational.

### 5. Report

Lead with the verdict:

```
## Assay Report

**Verdict**: PASS / FAIL

### Valid Findings
- [A.2] "<quoted passage>" — <description of violation> → Improvement: <specific action>

### Informational (1 evaluator only — not actionable)
- [B.3] <description> — flagged by evaluator N only
```

PASS: no valid findings.
FAIL: one or more valid findings remain. List what must be addressed.
