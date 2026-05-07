# /zz — Suspend work cleanly

You are pausing the current session. The goal is a clean git state and an up-to-date steering.md so the next session can resume without confusion.

## Step 1 — Identify the active work directory

Find the `steering.md` that was being worked on this session. If unclear, run `find . -name steering.md -not -path '*/.git/*'` and ask the user which one is active.

## Step 2 — Commit in-progress work

Run `git status`. For any staged, unstaged, or untracked files (except `steering.md`):
- If the current task is complete: commit with a normal message.
- If the task is partially done: commit with a `wip: ` prefix (e.g., `wip: auth middleware half-done`).
- For untracked files: either include them in the commit or delete them. Do not leave them floating.

If there is nothing to commit, skip to Step 3.

## Step 3 — Update steering.md

Open the active `steering.md` and:
- Check off all tasks that are now committed (`- [x]`)
- Add any tasks that emerged during this session but are not yet listed
- Update Assumptions or Rules if they changed
- Add or replace this line at the top of the Tasks section:
  `<!-- paused: <YYYY-MM-DD> — next: #N <task description> -->`

## Step 4 — Commit steering.md

If steering.md changed, commit it: `docs: update steering`

## Step 5 — Verify clean state

Run `git status`. The output must show `nothing to commit, working tree clean`. If it does not, resolve the remaining files before proceeding.

## Step 6 — Report to user

Output:
```
Suspended. Last completed: #N <description>
Next: #M <description>   [omit this line if all tasks are done]
Branch: <branch-name> — clean
```

If nothing was completed this session, say so explicitly.
