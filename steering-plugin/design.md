# rn Plugin Design

## steering.md Template

```markdown
# Goal

<user's exact words — never paraphrase (A.5)>

## Verification

- <how to verify the goal is achieved (C.1)>
- <two axes: goal alignment + quality (C.4)>

# Assumptions

- <distinguish facts from assumptions — state explicitly if unverified (B.1)>
- <define complete scope, never sample (B.2)>

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

## D-N: <what was decided (D.3 Issue)>
- **Conclusion**: <the decision>
- **Rationale**: <why>
- **Evidence**: <facts/numbers backing the rationale>

# State

(written by /rn:bb, read and removed by /rn:hi)

- **Status**: paused
- **Date**: YYYY-MM-DD
- **Last completed**: #N description
- **Next**: #N description
- **Notes**: context needed for resume
```

## Task Definition Requirements

- **Granularity**: purpose expressible in one sentence; split if it grows
- **Specificity**: not "implement" but "implement `methodName()` in `ClassName`"
- **Objectivity**: completion criteria must be judgeable by a third party
- **Prerequisites**: list dependencies; enables parallel/sequential judgment

## Task Completion Process

### All tasks (3 steps)

1. **Self-check**: verify each completion criterion, record OK/NG with evidence
2. **QA engineer review** (subagent): evaluate exhaustively, iterate until no substantive feedback
   - Are tests/verifications meaningful to the purpose? (not just "passed")
   - Are edge cases covered? (boundary, error, empty, max, type conversion edges)
3. **User review**: request after self-check and QA pass; iterate until OK

### Code change tasks (5 steps)

Insert between step 2 and 3 above:

3. **Language expert review** (subagent): evaluate against language best practices, iterate
   - Best practices (naming, error handling, null safety, thread safety)
   - Consistency with existing codebase style
   - Test code in GWT (Given/When/Then) format
4. **Software engineer review** (subagent): evaluate design, iterate
   - Appropriate separation of concerns
   - System-wide integrity (interface contracts, API compatibility)
   - Maintainability (no duplication, deep nesting, magic numbers)
5. **User review**

### Review policies

- Address all findings. Do not skip as "minor" or "low priority"
- To skip a finding, get user confirmation first
- Only dismiss findings with factual errors, stating the evidence

### Coverage verification

- Use project-appropriate tool (Jest, pytest, JaCoCo, gcov, etc.)
- Check line and branch coverage; record uncovered areas in self-check

### Check file format

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

## steering.md Discovery

How each command locates the steering file:

| Command | Method |
|---|---|
| gm | Never searches. Creates a new steering.md at the proposed location |
| bb | Uses the steering.md already known from the current session. If unknown (e.g., invoked standalone), falls back to commit history search |
| hi | Searches commit history of the current branch |

### Commit history search algorithm

1. Run `git log --diff-filter=AM --name-only --pretty=format: -- '*/steering.md' | head -5`
2. Filter to files that currently exist on disk (`test -f`)
3. If exactly one result: use it
4. If multiple: rank by (a) has a `# State` section with `Status: paused`, (b) most recent commit date. Propose the top-ranked candidate to the user for confirmation
5. If zero results: report "No steering.md found on this branch. Run `/rn:gm` to start." and stop

## Subagent Review Guidelines

Subagent reviews use the Agent tool with independent context (no conversation history).

### Prompt structure for all review subagents

1. **Role**: state the reviewer persona (QA engineer, language expert, or software engineer)
2. **Artifact**: full content of the file(s) under review — subagents cannot read prior conversation
3. **Criteria**: the specific checklist items from the Task Completion Process
4. **Completion criteria**: the task's completion criteria from steering.md
5. **Output format**: verdict per criterion (OK/NG) with evidence; overall pass/fail

### Context to include

- The task's purpose and completion criteria (from steering.md)
- The actual file content or diff being reviewed
- Project-specific rules (from steering.md Rules section)
- For language expert: the project's language and framework
- For software engineer: relevant interface contracts or API boundaries

### Iteration protocol

- If any finding is NG: fix the issue, then re-run the same subagent review
- Repeat until all verdicts are OK, up to 3 iterations per reviewer
- If still NG after 3 iterations: record remaining findings and escalate to user review with the unresolved items listed
- Record final results in `checks/{task-id}.md`

## Action Principle Enforcement

How rn enforces each action.md principle through its workflow:

| Principle | Enforcement point | Mechanism |
|---|---|---|
| A.1 Goal as starting point | gm step 1 | Goal captured verbatim before any planning |
| A.2 Work backwards from end state | gm step 4 | Task decomposition starts from Verification criteria |
| A.3 Means adapt, goal fixed | All commands | Goal section is read-only after creation |
| A.4 Find alternatives before giving up | hi step 5 | Blockers trigger alternative search, not task removal |
| A.5 No reinterpretation | gm step 3 | User's exact words stored, never paraphrased |
| B.1 Act on verified facts | Task execution | Assumptions section forces explicit declaration |
| B.2 Verify complete population | QA review | Subagent checks edge case coverage exhaustively |
| C.1 Define hypothesis + verification | gm step 3 | Verification section written before tasks |
| C.4 Two-axis verification | Completion process | Goal alignment + quality as separate review passes |
| C.5 Address every finding | Review policies | No skipping without user confirmation |
| D.1 Always propose | All user interactions | Questions replaced with proposals (D-9) |
| D.3 Issue-Conclusion format | Decisions section | All decisions recorded in structured format |

## Plugin Structure

```
rn/
├── .claude-plugin/
│   └── plugin.json              # { "name": "rn" }
├── commands/
│   ├── gm.md                    # Procedure: create session, begin task #1
│   ├── bb.md                    # Procedure: suspend session
│   └── hi.md                    # Procedure: resume session, begin next task
├── skills/
│   └── rn-execution/
│       ├── SKILL.md             # Task execution: completion process, review dispatch, check file
│       └── references/
│           └── template.md      # Full steering.md template
└── README.md
```

### Component responsibilities

| Component | Layer | Content |
|---|---|---|
| gm.md | Procedure | Steps 1-6: hear goal, propose location, create steering.md (load template from skill reference), decompose tasks, present, begin task #1 (load rn-execution skill) |
| bb.md | Procedure | Steps 1-6: find steering.md, commit work, write State, push, verify, report |
| hi.md | Procedure | Steps 1-7: check dirty, find steering.md, read State, sync tasks, remove State, begin next task (load rn-execution skill) |
| rn-execution SKILL.md | Knowledge | Task completion process (3-step/5-step), review dispatch with inline Agent tool (prompt construction, iteration protocol with 3-iteration cap and escalation), review policies, coverage verification, check file format |
| template.md | Reference | Full steering.md template — loaded by gm.md when creating a new steering.md |

**Note**: The Action Principle Enforcement table is design-time documentation. It is not included in the plugin output — it serves as a design rationale for why each command step exists.

### Skill loading points

- **gm.md step 3**: loads `references/template.md` to create steering.md
- **gm.md step 6**: loads `rn-execution` skill to execute task #1
- **hi.md step 7**: loads `rn-execution` skill to execute next task
- **bb.md**: does not load the skill (suspend-only, no task execution)

### Review subagent dispatch

The rn-execution skill constructs review prompts dynamically using the Agent tool. No dedicated agent files. Each dispatch includes:

1. Static part: reviewer persona + checklist (from SKILL.md)
2. Dynamic part: task purpose, completion criteria, file content/diff (from steering.md + workspace)

## Command Steps

### /rn:gm — New session

| Step | Action |
|---|---|
| 1 | Hear the goal from the user (use their message if already stated) |
| 2 | Propose steering.md location (e.g., "Creating `work/auth-refactor/steering.md`") |
| 3 | Create steering.md with all sections. Fill Goal (user's exact words), Verification, Assumptions, and Rules (default: `1 task = 1 commit`). Leave Tasks, Decisions, and State empty |
| 4 | Decompose goal into tasks (work backwards from end state per A.2). Fill Tasks section |
| 5 | Present complete steering.md to user |
| 6 | Begin task #1 |

### /rn:bb — Suspend

| Step | Action |
|---|---|
| 1 | Identify active steering.md (known from session, or find via commit history). If not found, report error and stop |
| 2 | If `git status` is dirty, commit in-progress work. "Complete" = all Steps checkboxes for the current task are checked → normal commit. "Partial" = some Steps unchecked → `wip:` prefix commit |
| 3 | Update steering.md — check off done tasks, add new tasks, write State section |
| 4 | Commit and push steering.md |
| 5 | Verify `git status` is clean |
| 6 | Report: last completed, next task, branch name |

### /rn:hi — Resume

| Step | Action |
|---|---|
| 1 | Check `git status` — if dirty, propose: "wip commit します" or "discard します" |
| 2 | Find steering.md from current branch commit history |
| 3 | Read State section, restore work context |
| 4 | Cross-check git log vs unchecked tasks — if a commit message matches an unchecked task, check it off in steering.md |
| 5 | If blocker exists, find alternative means before redesigning tasks (A.4) |
| 6 | Remove State section, commit steering.md |
| 7 | Announce next task, begin execution |
