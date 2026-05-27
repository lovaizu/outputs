# steering.md Template

Use this template when creating a new steering.md. Replace placeholder text in angle brackets.

```markdown
# Goal

<user's exact words — never paraphrase>

## Verification

- <how to verify the goal is achieved>
- <two axes: goal alignment + quality>

# Assumptions

- <distinguish facts from assumptions — state explicitly if unverified>
- <define complete scope, never sample>

# Rules

- 1 task = 1 commit
- <task-specific conventions>

# Tasks

### #1: <task name>

**Purpose**: what to achieve, 1-2 sentences
**Prerequisites**: tasks that must be completed first (or "none")
**Steps**:
- [ ] specific step 1
- [ ] specific step 2
- [ ] self-check (OK/NG per completion criterion, record in checks/{task-id}.md)
- [ ] QA engineer review (subagent)
- [ ] (code changes only) language expert review (subagent)
- [ ] (code changes only) software engineer review (subagent)
- [ ] user review

**Completion criteria**:
- objectively verifiable by a third party
- no vague terms ("appropriate", "correct")

# Decisions

## D-N: <what was decided>
- **Conclusion**: <the decision>
- **Rationale**: <why>
- **Evidence**: <facts/numbers backing the rationale>

# State

(written by /steer:bb, read and removed by /steer:hi)

- **Status**: paused
- **Date**: YYYY-MM-DD
- **Last completed**: #N description
- **Next**: #N description
- **Notes**: context needed for resume
```

## Task Definition Requirements

Every task must satisfy these four requirements:

- **Granularity**: purpose expressible in one sentence; split if it grows
- **Specificity**: not "implement" but "implement `methodName()` in `ClassName`"
- **Objectivity**: completion criteria must be judgeable by a third party
- **Prerequisites**: list dependencies; enables parallel/sequential judgment
