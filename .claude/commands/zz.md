# /zz — Suspend work

Cleanly pause the current session so it can be resumed later without loss.

## Steps

1. **Update steering.md** — Open the current work directory's `steering.md` and:
   - Check off completed tasks
   - Add any new tasks discovered during the session
   - Update assumptions or rules that changed
   - Add a **Session snapshot** line at the top of the Tasks section: `Last paused: <date> — <one-line status>`
2. **Commit staged work** — If there are any staged or unstaged changes, commit them now. Use a descriptive message. Do not leave partial work uncommitted.
3. **Commit steering.md** — If `steering.md` changed, commit it separately: `docs: update steering`.
4. **Verify clean state** — Run `git status` and confirm the working tree is clean. If untracked files remain, either commit or `.gitignore` them — do not leave them floating.
5. **Report** — Tell the user: work directory, last completed task, and next task to resume from.

## Notes

- "Clean state" means `git status` shows `nothing to commit, working tree clean`.
- If work was interrupted mid-task, commit what exists with a `wip:` prefix and note it in steering.md.
