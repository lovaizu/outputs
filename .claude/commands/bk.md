# /bk — Resume work (back)

Pick up exactly where the previous session left off.

## Steps

1. **Locate steering.md** — Find the active work directory. If ambiguous (multiple work dirs), ask the user which one. Read `steering.md` in full.
2. **Review commit history** — Run `git log --oneline -20` to see recent commits and confirm what was completed.
3. **Reconcile** — Cross-check the task list checkboxes in `steering.md` against the commit log. If they are out of sync, update the checkboxes to match reality.
4. **Identify next task** — Find the first unchecked task in the list. State it clearly to the user: "Resuming from task #N: <description>".
5. **Execute** — Proceed with the next task immediately, following the same 1-task-1-commit discipline. Update `steering.md` as you go.

## Notes

- Do not re-do work that is already committed, even if the checkbox was missed.
- If the goal in `steering.md` looks stale or the scope has changed, surface that to the user before proceeding.
- If no `steering.md` is found, suggest running `/go` to start fresh.
