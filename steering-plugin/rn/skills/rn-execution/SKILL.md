---
name: rn-execution
description: This skill should be used when executing a task from a steering.md file,
  when completing a task with self-check and review, when dispatching QA or expert
  review subagents, or when recording completion checks. It is loaded by /rn:gm
  and /rn:hi when task execution begins.
version: 0.1.0
---

# Task Execution

To execute a task from steering.md, follow this exact sequence. Do not skip steps.

## 1. Read the task

Read the task's Purpose, Prerequisites, Steps, and Completion criteria from steering.md. Verify all prerequisites are marked complete before starting.

## 2. Execute work steps

Complete each step in the Steps list sequentially. Check off each step as it is completed. Follow the project's Rules section. Commit after completing the work steps (before reviews).

## 3. Run the completion process

The completion process has two variants based on whether the task involves code changes. A task is a code change task if its Steps include creating, modifying, or deleting source code files. Design documents, configuration prose, and steering.md itself are non-code tasks.

### All tasks — 3-step process

**Step 1: Self-check.** Verify each completion criterion. For each criterion, record OK or NG with specific evidence of what was confirmed. Write results to `{steering_dir}/checks/{task-id}.md` using the check file format below.

**Step 2: QA engineer review.** Dispatch a subagent (see Review Dispatch below). The QA engineer evaluates:
- Are tests/verifications meaningful to the purpose? (not just "passed")
- Are edge cases covered? (boundary, error, empty, max, type conversion edges)

Iterate until all QA verdicts are OK, up to 3 iterations. If still NG after 3 iterations, record remaining findings and escalate to user review with the unresolved items listed.

**Step 3: User review.** Present the completed work and check file to the user. Iterate until the user approves. If the user requests changes, fix the issues and re-present. Re-run affected reviews if the fix is substantial (changes the logic or structure, not just formatting).

### Code change tasks — 5-step process

Insert between QA review (step 2) and user review (step 3):

**Step 3: Language expert review.** Dispatch a subagent. The language expert evaluates:
- Best practices (naming, error handling, null safety, thread safety)
- Consistency with existing codebase style
- Test code in GWT (Given/When/Then) format

**Step 4: Software engineer review.** Dispatch a subagent. The software engineer evaluates:
- Appropriate separation of concerns
- System-wide integrity (interface contracts, API compatibility)
- Maintainability (no duplication, deep nesting, magic numbers)

**Step 5: User review.** Same as step 3 in the 3-step process.

Each expert review follows the same iteration protocol: up to 3 iterations, then escalate.

## Review Policies

- Address all findings. Do not skip any as "minor" or "low priority."
- To skip a finding, get user confirmation first.
- Only dismiss findings with factual errors, stating the evidence.

## Coverage Verification (code tasks)

- Use the project-appropriate tool (Jest, pytest, JaCoCo, gcov, etc.)
- Check line and branch coverage; record uncovered areas in the self-check.

## Review Dispatch

Dispatch each reviewer as a subagent using the Agent tool. Each subagent runs in an independent context with no conversation history.

### Prompt construction

Build the prompt with these 5 elements:

1. **Role**: "You are a {QA engineer | language expert specializing in {language} | software engineer} reviewing a task artifact."
2. **Artifact**: Include the full content of the file(s) under review. The subagent cannot read prior conversation.
3. **Criteria**: The specific checklist items for this reviewer type (listed above).
4. **Completion criteria**: The task's completion criteria from steering.md.
5. **Output format**: "For each criterion, give OK or NG with specific evidence. End with an overall pass/fail verdict."

### Context to include

- The task's purpose and completion criteria (from steering.md)
- The actual file content or diff being reviewed
- Project-specific rules (from steering.md Rules section)
- For language expert: the project's language and framework
- For software engineer: relevant interface contracts or API boundaries

### Iteration protocol

- If any finding is NG: fix the issue, then re-run the same subagent review.
- Repeat until all verdicts are OK, up to 3 iterations per reviewer.
- If still NG after 3 iterations: record remaining findings and escalate to user review with the unresolved items listed.
- Record final results in `checks/{task-id}.md`.

## Check File Format

Output to `{steering_dir}/checks/{task-id}.md`:

```markdown
# {task-id} Completion Check

## Completion Criteria

| Criterion | Self-check | Evidence | QA | QA Evidence |
|---|---|---|---|---|
| (criterion text) | OK / NG | (what was confirmed) | OK / NG | (QA findings) |

## QA Engineer Review

| Aspect | Verdict | Evidence / Improvement |
|---|---|---|
| Meaningful tests/verification | OK / NG | |
| Edge case coverage | OK / NG | |

## Expert Reviews (code changes only)

### Language Expert

| Aspect | Verdict | Evidence / Improvement |
|---|---|---|
| Best practices | OK / NG | |
| Codebase style consistency | OK / NG | |
| GWT test format | OK / NG | |

### Software Engineer

| Aspect | Verdict | Evidence / Improvement |
|---|---|---|
| Separation of concerns | OK / NG | |
| System integrity | OK / NG | |
| Maintainability | OK / NG | |

## Overall Verdict

- Self-check: OK / NG
- QA: OK / NG
- Language expert: OK / NG / N/A
- Software engineer: OK / NG / N/A
- Ready for user review: Yes / No (reason)
```

## After Task Completion

Once the user approves:

1. Check off the task in steering.md (change `- [ ]` to `- [x]`)
2. Commit: `docs: complete task #{id} — {description}`
3. Identify the next unchecked task and begin it immediately
4. If all tasks are checked off, report completion to the user. Propose running the verification criteria from the Goal section to confirm the overall goal is achieved

## Additional Resources

### Reference Files
- **`references/template.md`** — Full steering.md template with task definition requirements
