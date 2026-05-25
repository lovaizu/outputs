# /steer:up — Resume session

Resume a suspended steering session. Follow these steps exactly in order.

## Context

- Current branch: !`git branch --show-current`
- Working tree status: !`git status --short`
- Recent commits: !`git log --oneline -10`
- Steering file commits: !`git log --diff-filter=AM --name-only --pretty=format: -- '*/steering.md' | head -5`

## Step 1: Handle dirty working tree

Check the working tree status from Context above.

If clean, proceed to Step 2.

If dirty, propose one of:
- "Committing dirty files as wip: {brief description}"
- "Discarding uncommitted changes"

Wait for user confirmation. Execute the chosen action. Do not proceed until the tree is clean.

## Step 2: Find steering.md

Use the steering file commits from Context above. Filter to files that currently exist on disk.

- If exactly one result: use it.
- If multiple results: rank by (a) has a `# State` section with `Status: paused`, (b) most recent commit date. Propose the top-ranked candidate for confirmation.
- If zero results: report "No steering.md found on this branch. Run `/steer:hi` to start." and stop.

## Step 3: Restore work context

Read the steering.md file. Find the **State** section. Extract:
- Last completed task
- Next task
- Notes (context for resuming)

If no State section exists, skip to Step 4 and determine the next task from the Tasks list (first unchecked task).

## Step 4: Sync tasks with commit history

Cross-check the recent commits from Context against unchecked tasks in steering.md. If a commit message matches an unchecked task's description or ID, check it off.

If any tasks were synced, note the corrections.

## Step 5: Check for blockers

Read the Notes from the State section. If a blocker is mentioned:

1. Investigate whether the blocker still exists
2. If it does, find an alternative approach before removing or redesigning tasks
3. Propose the alternative to the user

Do not remove tasks or change the goal to work around a blocker.

## Step 6: Clean up State section

Remove the State section content, replacing it with the placeholder:

```markdown
# State

(written by /steer:dn, read and removed by /steer:up)
```

Commit:

```
git add {steering_path}
git commit -m "docs: resume session — starting #{next_id}"
```

## Step 7: Begin next task

Output the resume summary:

```
Resuming: {steering.md directory}
Last completed: #{id} {description}   (or "none")
Next: #{id} {description}
```

If there is an unchecked task, load the steer-execution skill and begin executing it immediately.

If all tasks are checked off, report: "All tasks are complete. Propose running the verification criteria from the Goal section to confirm the overall goal is achieved."
