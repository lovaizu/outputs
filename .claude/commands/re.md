# /re — Resume work

You are resuming a paused session. Reconstruct the exact state before executing anything.

## Step 1 — Handle uncommitted changes

Run `git status` first. If the working tree is not clean:
- Do not proceed with new work.
- Tell the user what files are affected and ask: "Should I commit these as wip, or discard them?"
- Wait for their answer. If they say discard, confirm once more before running any destructive command (`git restore` / `git clean`).

If the tree is clean, continue.

## Step 2 — Locate steering.md

Run `find . -name steering.md -not -path '*/.git/*'`.
- No results: say "No steering.md found. Run /go to start a new session." and stop.
- Exactly one result: read it and continue to Step 3.
- Multiple results: proceed to auto-detect below.

### Auto-detect when multiple steering.md files exist

Run `git log --oneline -10` and note the current branch name.

Match the most likely active steering.md using these signals, in order:
1. A steering.md whose `<!-- paused: -->` comment exists and whose work directory name appears in recent commit messages.
2. A steering.md whose work directory name matches any segment of the current branch name.
3. A steering.md that has unchecked tasks (`- [ ]`) while others are fully checked off.

If exactly one candidate survives, read it and continue to Step 3 — do not ask the user.

If the signals are ambiguous (zero or multiple candidates remain after applying all three rules), list the candidates with a one-line summary of each and ask: "Which session should I resume?"

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
