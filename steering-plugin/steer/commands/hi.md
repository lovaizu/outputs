---
argument-hint: [goal description]
---

# /steer:hi — New steering session

Start a new steering session. Follow these steps exactly in order. Do not skip or collapse steps.

## Context

- Current branch: !`git branch --show-current`
- Repository root: !`git rev-parse --show-toplevel`
- Existing steering files: !`find . -name steering.md -not -path '*/.git/*' 2>/dev/null | head -5`

## Step 1: Hear the goal

If the user provided a goal in their message or as an argument (`$ARGUMENTS`), use it directly. If no goal was stated, respond: "No goal was provided. State the goal for this session." and stop until the user provides one.

Record the user's exact words. Never paraphrase, reinterpret, narrow, or expand the goal.

## Step 2: Propose steering.md location

**CRITICAL**: Do not ask where to put the file. Propose a location.

Derive the directory name from the goal. Use kebab-case. Check the Context section's existing steering files — if `work/{goal-slug}/steering.md` already exists, append a numeric suffix (e.g., `work/{goal-slug}-2/steering.md`).

Propose:

"Creating `work/{goal-slug}/steering.md`"

Wait for user confirmation before proceeding. If the user suggests a different location, use that.

## Step 3: Create steering.md

Read the template from @${CLAUDE_PLUGIN_ROOT}/skills/steer-execution/references/template.md

Create the steering.md file with all 7 sections:

1. **Goal**: the user's exact words from Step 1
2. **Verification**: define how to verify the goal is achieved. Include two axes — goal alignment and quality
3. **Assumptions**: list what is assumed vs what is verified. Define the complete scope
4. **Rules**: start with `1 task = 1 commit`, add task-specific conventions
5. **Tasks**: leave empty (filled in Step 4)
6. **Decisions**: leave empty (filled during work)
7. **State**: leave the placeholder text

## Step 4: Decompose into tasks

**CRITICAL**: This is one of the most important steps. DO NOT SKIP.

Work backwards from the end state defined in Verification:

1. Define the final state where the goal is achieved
2. Identify what must be true for that state to exist
3. Work backwards to identify all necessary steps
4. Order tasks by dependencies

For each task, include:
- **Purpose**: 1-2 sentences
- **Prerequisites**: which tasks must complete first (or "none")
- **Steps**: specific action items + review steps (self-check, QA review, expert reviews for code tasks, user review)
- **Completion criteria**: objectively verifiable by a third party, no vague terms

Every task purpose must be expressible in one sentence. If it grows, split the task.

## Step 5: Present steering.md

Present the complete steering.md to the user. Do not begin work until the user approves or requests changes. Iterate until approved.

**DO NOT START TASK #1 WITHOUT USER APPROVAL**

## Step 6: Begin task #1

Load the steer-execution skill and begin executing task #1.

Commit the steering.md file first:

```
git add {steering_path}
git commit -m "docs: create steering.md for {goal-slug}"
```

Then execute task #1 following the steer-execution skill's task execution process.
