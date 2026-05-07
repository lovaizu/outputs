# /bk — Resume work (back)

You are resuming a paused session. Reconstruct the exact state before executing anything.

## Step 1 — Handle uncommitted changes

Run `git status` first. If the working tree is not clean:
- Do not proceed with new work.
- Tell the user what is uncommitted and ask: "Should I commit these as wip, or discard them?"
- Wait for their answer, then act on it.

If the tree is clean, continue.

## Step 2 — Locate steering.md

Look for `steering.md` files under the repository. If you find exactly one, read it. If you find multiple, list them and ask the user which session to resume. If you find none, say: "No steering.md found. Run /go to start a new session."

## Step 3 — Review commit history

Run `git log --oneline -20`. Cross-check the commits against the task list in steering.md. If a task's commit exists in the log but the checkbox is unchecked, check it off in steering.md and commit the fix (`docs: sync steering with commits`).

## Step 4 — Identify next task

Find the first unchecked task (`- [ ]`). Output:

```
Resuming session: <work directory>
Last completed: #N <description>   (or "none" if nothing is checked)
Next: #M <description>
```

## Step 5 — Execute

Begin task #M immediately. Follow the same 1-task-1-commit discipline. Update steering.md after each commit.
