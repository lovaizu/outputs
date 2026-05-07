# /go — Start a new work session

You are starting a structured work session. Follow the steps below in order without skipping.

## Step 1 — Hear the task

If the user's message that invoked this command already describes the work, use that as the task description. Otherwise, ask: "What do you want to accomplish?" Wait for their reply before continuing.

## Step 2 — Create the work directory

Create a directory named after the task using lowercase kebab-case (e.g., `work/add-auth/`, `work/refactor-api/`). Place it under the repository root unless the context clearly implies a different location. Create it now.

## Step 3 — Create steering.md

Create `steering.md` inside the work directory with exactly this structure:

```markdown
# Goal

<paste the user's exact words here — do not paraphrase, preserve their language>

# Assumptions

- <constraint or scope boundary you infer from context>

# Rules

- <task-specific convention, if any>

# Tasks

- [ ] #1: <verb> <what>
- [ ] #2: <verb> <what>
```

Leave Tasks empty for now. Fill it in Step 4.

## Step 4 — Break into tasks

Decompose the goal into atomic tasks. One task = one commit. Each task should be completable and verifiable on its own. Write the task list into the Tasks section of `steering.md`.

Good granularity: "scaffold directory structure", "add auth middleware", "write unit tests for auth"
Too coarse: "build the feature"
Too fine: "add blank line to config"

## Step 5 — Show and start

Output the task list to the user in a single message, then immediately begin executing task #1 without waiting for approval unless the task list has more than 10 items (in that case, wait for "k" before starting).

## Step 6 — Execute and maintain

Work through the task list top to bottom. After each commit:
- Check off the task in `steering.md` (`- [x] #N`)
- Commit the steering.md update together with the task's code changes, or in a separate `docs: update steering` commit if the task is large

If scope expands mid-session, add new tasks to the list rather than silently doing extra work.
