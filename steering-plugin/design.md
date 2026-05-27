# rn Plugin Design

## 1. Requirements

rn (Right Now) is a Claude Code plugin that manages goal-driven work sessions through a `steering.md` file. It provides three commands:

| Command | Function | Trigger |
|---|---|---|
| `/rn:gm` | Start a new session | User states a goal |
| `/rn:bb` | Suspend the session | User needs to stop (context limit, break, etc.) |
| `/rn:hi` | Resume a suspended session | User returns in a new conversation |

The plugin must:

1. Capture the user's goal verbatim and decompose it into verifiable tasks
2. Execute tasks one at a time with independent quality reviews (subagents)
3. Persist session state to git so work survives across conversations
4. Work in any repository, any language — no project-specific dependencies

## 2. Assumptions and Constraints

### Platform constraints (Claude Code)

- Skills cannot invoke `/clear` — after `/rn:bb`, the user must `/clear` manually
- Hooks cannot receive context usage — automatic bb→clear→hi on threshold is not possible (future work)
- Subagents (Agent tool) have no conversation history — all review context must be passed in the prompt

### Design constraints

- All user interactions are proposals, never questions (action.md D principle)
- Rule files (.md read by AI) are written in English
- 1 task = 1 commit

## 3. Workflow

```
/rn:gm                    /rn:bb              /rn:hi
  │                          │                   │
  ▼                          ▼                   ▼
Create steering.md    Commit work           Find steering.md
  │                   Write State section    Read State section
  ▼                   Push                   Sync tasks with git log
Decompose tasks          │                   Remove State section
  │                      ▼                      │
  ▼                   "Session suspended"        ▼
Begin task #1         ← user does /clear →   Begin next task
  │                                              │
  ▼                                              ▼
[Task execution cycle]                    [Task execution cycle]
  │                                              │
  ├─ Execute work steps                          │
  ├─ Self-check                                  │
  ├─ QA review (subagent)                        │
  ├─ Expert reviews (code only, subagents)       │
  ├─ User review                                 │
  ├─ Commit                                      │
  └─ Next task (or /rn:bb to suspend)            │
```

## 4. Plugin Structure

```
rn/
├── .claude-plugin/
│   └── plugin.json              # { "name": "rn" }
├── commands/
│   ├── gm.md                    # Procedure: start session
│   ├── bb.md                    # Procedure: suspend session
│   └── hi.md                    # Procedure: resume session
├── skills/
│   └── rn-execution/
│       ├── SKILL.md             # Knowledge: task execution, reviews, check files
│       └── references/
│           └── template.md      # steering.md template
└── README.md
```

### Component responsibilities

| Component | Layer | What it specifies |
|---|---|---|
| gm.md | Procedure | Steps 1-6: hear goal → propose location → create steering.md (from template.md) → decompose tasks → present to user → begin task #1 (load rn-execution) |
| bb.md | Procedure | Steps 1-6: find steering.md → commit work → write State → push → verify clean → report |
| hi.md | Procedure | Steps 1-7: handle dirty tree → find steering.md → read State → sync tasks with git log → check blockers → remove State → begin next task (load rn-execution) |
| SKILL.md | Knowledge | Task completion process (3-step / 5-step), subagent review dispatch, review policies, check file format |
| template.md | Reference | Full steering.md template — loaded by gm.md at session start |

### Skill loading points

| When | What loads | Why |
|---|---|---|
| gm.md step 3 | `references/template.md` | Create steering.md from template |
| gm.md step 6 | `rn-execution` skill | Execute task #1 |
| hi.md step 7 | `rn-execution` skill | Execute next task |
| bb.md | Nothing | Suspend only, no task execution |

## 5. steering.md Specification

### 5.1 Template

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

(written by /rn:bb, read and removed by /rn:hi)

- **Status**: paused
- **Date**: YYYY-MM-DD
- **Last completed**: #N description
- **Next**: #N description
- **Notes**: context needed for resume
```

### 5.2 Task definition requirements

| Requirement | Rule |
|---|---|
| Granularity | Purpose expressible in one sentence; split if it grows |
| Specificity | Not "implement" but "implement `methodName()` in `ClassName`" |
| Objectivity | Completion criteria judgeable by a third party |
| Prerequisites | List dependencies explicitly; enables parallel/sequential judgment |

### 5.3 steering.md discovery

| Command | How it finds steering.md |
|---|---|
| gm | Never searches. Creates a new file at the proposed location |
| bb | Uses the path already known from the current session. Fallback: commit history search |
| hi | Always searches via commit history |

**Commit history search algorithm:**

1. `git log --diff-filter=AM --name-only --pretty=format: -- '*/steering.md' | head -5`
2. Filter to files that exist on disk (`test -f`)
3. One result → use it
4. Multiple → rank by (a) has `Status: paused` in State section, (b) most recent commit. Propose top candidate
5. Zero → "No steering.md found. Run `/rn:gm` to start."

## 6. Command Steps

### 6.1 /rn:gm — Start

| Step | Action | Detail |
|---|---|---|
| 1 | Hear the goal | Use user's message or `$ARGUMENTS`. If none, ask. Record exact words — never paraphrase |
| 2 | Propose location | Derive directory from goal in kebab-case. Propose `work/{goal-slug}/steering.md`. Wait for confirmation |
| 3 | Create steering.md | Load template from `@${CLAUDE_PLUGIN_ROOT}/skills/rn-execution/references/template.md`. Fill Goal, Verification, Assumptions, Rules. Leave Tasks, Decisions, State empty |
| 4 | Decompose tasks | Work backwards from Verification end state. Each task gets Purpose, Prerequisites, Steps, Completion criteria |
| 5 | Present to user | Show complete steering.md. Do not proceed without user approval |
| 6 | Begin task #1 | Commit steering.md. Load rn-execution skill. Execute task #1 |

### 6.2 /rn:bb — Suspend

| Step | Action | Detail |
|---|---|---|
| 1 | Find steering.md | Use known path, or commit history search |
| 2 | Commit work | Clean tree → skip. Dirty: all task steps checked → normal commit. Some unchecked → `wip:` prefix |
| 3 | Update steering.md | Check off done tasks. Add new tasks/decisions. Write State section (status, date, last completed, next, notes) |
| 4 | Commit and push | `git commit` + `git push`. If push fails, continue (user can push later) |
| 5 | Verify clean | `git status` must show clean tree |
| 6 | Report | Output: last completed, next task, branch name |

**State section notes**: Include enough context for `/rn:hi` to resume without the conversation. Mention: current work, blockers, pending decisions, next concrete action.

### 6.3 /rn:hi — Resume

| Step | Action | Detail |
|---|---|---|
| 1 | Handle dirty tree | Clean → proceed. Dirty → propose wip commit or discard. Wait for confirmation |
| 2 | Find steering.md | Commit history search |
| 3 | Restore context | Read State section: last completed, next task, notes |
| 4 | Sync tasks | Cross-check git log vs unchecked tasks. If a commit matches a task, check it off |
| 5 | Check blockers | If State notes mention a blocker: investigate, find alternative approach before removing tasks |
| 6 | Clean up State | Replace State section with placeholder. Commit |
| 7 | Begin next task | Load rn-execution skill. Execute next unchecked task. If all done, propose running Verification |

## 7. Task Execution (rn-execution skill)

### 7.1 Process overview

| Task type | Steps |
|---|---|
| Non-code (docs, config, design) | Self-check → QA review → User review |
| Code changes | Self-check → QA review → Language expert review → Software engineer review → User review |

### 7.2 Self-check

Verify each completion criterion. Record OK/NG with specific evidence. Write results to `{steering_dir}/checks/{task-id}.md`.

### 7.3 Subagent reviews

Each reviewer runs as an independent subagent (Agent tool, no conversation history).

**Prompt construction** — include these 5 elements:

1. **Role**: reviewer persona (QA engineer / language expert in {language} / software engineer)
2. **Artifact**: full file content or diff under review
3. **Criteria**: the checklist for this reviewer type (see below)
4. **Completion criteria**: the task's criteria from steering.md
5. **Output format**: OK/NG per criterion with evidence, overall pass/fail

**QA engineer checklist:**

- Are tests/verifications meaningful to the purpose? (not just "passed")
- Are edge cases covered? (boundary, error, empty, max, type conversion)

**Language expert checklist** (code tasks only):

- Best practices (naming, error handling, null safety, thread safety)
- Consistency with existing codebase style
- Test code in GWT (Given/When/Then) format

**Software engineer checklist** (code tasks only):

- Appropriate separation of concerns
- System-wide integrity (interface contracts, API compatibility)
- Maintainability (no duplication, deep nesting, magic numbers)

**Iteration protocol:**

- NG on any finding → fix → re-run same reviewer
- Max 3 iterations per reviewer
- Still NG after 3 → record findings, escalate to user review with unresolved items

### 7.4 Review policies

- Address all findings. Never skip as "minor" or "low priority"
- To skip a finding, get user confirmation first
- Only dismiss findings with factual errors, stating the evidence

### 7.5 Coverage verification (code tasks)

- Use project-appropriate tool (Jest, pytest, JaCoCo, gcov, etc.)
- Check line and branch coverage; record uncovered areas in self-check

### 7.6 Check file format

Output to `{steering_dir}/checks/{task-id}.md`:

```markdown
# {task-id} Completion Check

## Completion Criteria

| Criterion | Self-check | Evidence | QA | QA Evidence |
|---|---|---|---|---|
| (text) | OK / NG | (what was confirmed) | OK / NG | (findings) |

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

### 7.7 After task completion

1. Check off the task in steering.md
2. Commit: `docs: complete task #{id} — {description}`
3. Begin next unchecked task immediately
4. If all tasks done → propose running Verification criteria

## Appendix: Action Principle Traceability

How each action.md principle is enforced in the plugin workflow:

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
| D.1 Always propose | All user interactions | Questions replaced with proposals |
| D.3 Issue-Conclusion format | Decisions section | All decisions recorded in structured format |
