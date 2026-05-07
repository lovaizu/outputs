# /go — Start a new work session

You are starting a structured work session. Follow the steps below in order without skipping.

## Step 1 — Hear the task

If the user's message that invoked this command already describes the work, use that as the task description. Otherwise, ask: "What do you want to accomplish?" Wait for their reply before continuing.

## Step 2 — Create the work directory

Create a directory named after the task using lowercase kebab-case (e.g., `work/add-auth/`, `work/refactor-api/`). Place it directly under the repository root. Create it now.

## Step 3 — Create steering.md

Create `steering.md` inside the work directory. Use this exact structure (do not add fences around the file content itself):

---
# Goal

<paste the user's exact words here — do not paraphrase, preserve their language>

# Assumptions

- <constraint or scope boundary you infer from context>

# Rules

- <task-specific convention, if any>

# Tasks

---

Leave the Tasks section empty. Fill it in Step 4.

## Step 4 — Break into tasks

Decompose the goal into atomic tasks. One task = one commit. Each task should be completable and verifiable on its own. Add the task list to the Tasks section of `steering.md`:

```
- [ ] #1: <verb> <what>
- [ ] #2: <verb> <what>
```

Good granularity: "scaffold directory structure", "add auth middleware", "write unit tests for auth"
Too coarse: "build the feature"
Too fine: "add blank line to config"

## Step 5 — Show and start

Output the task list to the user in a single message, then immediately begin executing task #1.

## Step 6 — Execute and maintain

Work through the task list top to bottom. After each commit:
- Check off the task in `steering.md` (`- [x] #N`) and commit the change.

If scope expands mid-session, add new tasks to the list rather than silently doing extra work.
