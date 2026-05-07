# /zz — Suspend work cleanly

You are pausing the current session. The goal is a clean git state and an up-to-date steering.md so the next session can resume without confusion.

## Step 1 — Commit in-progress code

Run `git status`. If there are any staged or unstaged changes to code files (anything that is not `steering.md`):
- If the current task is complete: commit with a normal message.
- If the task is partially done: commit with a `wip: ` prefix (e.g., `wip: auth middleware half-done`).

Do not skip this step even if the changes feel small.

## Step 2 — Update steering.md

Open `steering.md` and:
- Check off all tasks that are now committed (`- [x]`)
- Add any tasks that emerged during this session but are not yet listed
- Update Assumptions or Rules if they changed
- Add or replace this line at the top of the Tasks section:
  `<!-- paused: <YYYY-MM-DD> — next: #N <task description> -->`

## Step 3 — Commit steering.md

If steering.md changed, commit it: `docs: update steering`

## Step 4 — Verify clean state

Run `git status`. The output must show `nothing to commit, working tree clean`.

If untracked files remain: either commit them or add them to `.gitignore`. Do not leave them floating.

## Step 5 — Report to user

Output exactly:
```
Suspended. Last completed: #N <description>
Next: #M <description>
Branch: <branch-name> — clean
```

If nothing was completed this session, say so explicitly.
