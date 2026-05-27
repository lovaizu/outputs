# rn

Right Now — a Claude Code plugin for steering work sessions with structured task management.

## Commands

| Command | Description |
|---|---|
| `/rn:gm` | Start a new steering session — define a goal, decompose into tasks, begin work |
| `/rn:bb` | Suspend the current session — commit work, save state, push |
| `/rn:hi` | Resume a suspended session — restore context, sync progress, continue work |

## How it works

1. **`/rn:gm`** captures your goal verbatim, creates a `steering.md` with verification criteria, assumptions, and a task breakdown, then begins the first task.
2. Work proceeds task by task. Each task goes through self-check and subagent QA review (plus language expert and software engineer reviews for code changes) before user approval.
3. **`/rn:bb`** saves session state to `steering.md` and pushes. Resume later with **`/rn:hi`** in a new conversation — it finds the steering file from git history and picks up where you left off.

## Install

```
claude install github:lovaizu/ccpm/rn
```
