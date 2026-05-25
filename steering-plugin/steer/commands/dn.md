# /steer:dn — Suspend session

Suspend the current steering session. Follow these steps exactly in order.

## Context

- Current branch: !`git branch --show-current`
- Working tree status: !`git status --short`
- Recent steering commits: !`git log --diff-filter=AM --name-only --pretty=format: -- '*/steering.md' | head -5`

## Step 1: Identify active steering.md

If the steering.md path is already known from the current conversation, use it.

If not (e.g., invoked standalone), find it via the commit history shown in Context above. Filter to files that exist on disk. If exactly one result, use it. If multiple results, rank by (a) has a `# State` section with `Status: paused`, (b) most recent commit date, and propose the top-ranked candidate. If zero results, report: "No steering.md found on this branch. Run `/steer:hi` to start." and stop.

## Step 2: Commit in-progress work

Check the working tree status from Context above.

If the tree is clean, skip to Step 3.

If the tree is dirty, commit the changes:
- **Complete task**: all Steps checkboxes for the current task are checked → commit with a normal descriptive message
- **Partial task**: some Steps checkboxes are unchecked → commit with `wip:` prefix (e.g., `wip: partial implementation of task #3`)

Stage only the files relevant to the current task. Do not stage unrelated files.

## Step 3: Update steering.md

Read the current steering.md and update it:

1. Check off any tasks whose Steps are all completed
2. Add any new tasks identified during the session
3. Add any new Decisions made during the session
4. Write the **State** section:

```markdown
# State

- **Status**: paused
- **Date**: {today's date, YYYY-MM-DD}
- **Last completed**: #{id} {description}   (or "none")
- **Next**: #{id} {description}
- **Notes**: {context needed for the next session to resume without re-reading the full conversation}
```

The Notes field is critical — include enough context that `/steer:up` can resume without the current conversation history. Mention: what was being worked on, any blockers encountered, decisions pending, and what the next concrete action should be.

## Step 4: Commit and push

Commit the updated steering.md:

```
git add {steering_path}
git commit -m "docs: suspend session — next is #{next_id}"
git push
```

If push fails (e.g., no remote configured, authentication error), report the failure but continue to Step 5. The local commit is preserved; the user can push manually later.

## Step 5: Verify clean state

Run `git status` and confirm the working tree is clean. If not, identify and resolve the remaining dirty files.

## Step 6: Report

Output the session summary:

```
Session suspended.
Last completed: #{id} {description}
Next: #{id} {description}
Branch: {branch_name}
```
