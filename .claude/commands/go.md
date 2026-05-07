# /go — Start work

Start a new work session from scratch.

## Steps

1. **Hear the task** — Ask the user to describe what they want to accomplish. One focused question: "何をしますか？"
2. **Create work directory** — Based on the task description, create a dedicated directory (e.g., `work/<slug>/` or a meaningful name). If the task fits an existing directory, use it.
3. **Create steering.md** — Inside the work directory, create `steering.md` with:
   - **Goal** — verbatim quote from the user's first description (preserve their exact words and language)
   - **Assumptions** — constraints, environment, or scope boundaries you infer
   - **Rules** — any task-specific rules or conventions agreed upon
   - **Tasks** — empty for now; filled in step 4
4. **Break into tasks** — Decompose the goal into atomic tasks where each task = one commit. Add a numbered task list to the **Tasks** section of `steering.md`. Each entry: `- [ ] #N: <verb> <what>`.
5. **Confirm and start** — Show the task list to the user and ask for a quick go-ahead. When approved (or user says "k" / "進めて"), begin executing from task #1.
6. **Execute and update** — Work through tasks in order. After each commit, check the box in `steering.md`. Update assumptions/rules as they evolve.

## Notes

- Keep task granularity at commit-sized chunks — not too big, not trivial.
- If scope expands mid-work, add tasks to the list rather than silently doing extra work.
- Always update `steering.md` to reflect reality, not the original plan.
