# /bk — Resume work (back)

You are resuming a paused session. Reconstruct the exact state before executing anything.

## Step 1 — Handle uncommitted changes

Run `git status` first. If the working tree is not clean:
- Do not proceed with new work.
- Tell the user what files are affected and ask: "Should I commit these as wip, or discard them?"
- Wait for their answer. If they say discard, confirm once more before running any destructive command (`git restore` / `git clean`).

If the tree is clean, continue.

## Step 2 — Locate steering.md

Run `find . -name steering.md -not -path '*/.git/*'`.
- Exactly one result: read it.
- Multiple results: list them and ask the user which session to resume.
- No results: say "No steering.md found. Run /go to start a new session." and stop.

## Step 3 — Review commit history

Run `git log --oneline -20`. For each unchecked task (`- [ ]`) in steering.md, check whether a commit whose message contains the task description or task number exists in the log. If a match is found, check off that task. Commit the correction: `docs: sync steering with commits`.

## Step 4 — Identify next task

Find the first unchecked task (`- [ ]`). Output:

```
Resuming: <work directory>
Last completed: #N <description>   (or "none")
Next: #M <description>
```

## Step 5 — Execute

Begin task #M immediately. Follow the same 1-task-1-commit discipline. Update steering.md after each commit.
