# Scenario: Unread Message Badge

A concrete walk-through of one task from `/hi` to merged PR. Shows how TC content
becomes CCS content, and how Steering Gates produce decisions rather than approvals.

---

## Setup

```
.aiya/up.sh       # expert runs once — sandbox + auditor start
```

---

## /hi — hear the issue

Expert types `/hi` and describes the situation in conversation.

AIYA files issue #42, creates branch `aiya/42`, and creates `.aiya/42/`.

---

## Goal phase

Expert authors `.aiya/42/goal.md`:

```markdown
## Plan
AI will draft Situation and Pain based on the hearing. Expert authors Benefit and
Acceptance Scenarios. Planning Gate review before drafting.

## Situation
A user opens a team chat app after a two-hour meeting. Dozens of threads are visible
in the sidebar.

## Pain
There is no visual signal for unread messages. The user must open each thread
manually to check for new content.

## Benefit
Time-sensitive messages get faster responses. Team coordination cost drops because
attention is directed, not scattered.

## Acceptance Scenarios
- AS-1: A thread with unread messages shows a badge with the unread count
- AS-2: Opening the thread clears the badge
- AS-3: Badge count updates when a new message arrives (no page reload required)
```

**Planning Gate (Goal):** AI summarizes its understanding of the goal. Expert reads.
Types `/ty`. Drafting begins.

**G1:** Expert reads the completed `goal.md`. Types `/ty`. File committed.

---

## Approach phase

AI drafts `.aiya/42/approach.md`:

```markdown
## Plan
Investigate existing WebSocket infrastructure and message storage before committing
to technology. Planning Gate review before investigation begins.

## Testing
- Unit: unread count calculation (increment on insert, reset on open)
- E2E (Playwright): open thread → verify badge disappears

## Technology
- WebSocket: already in stack — use for real-time badge updates (AS-3)
- Redis: unread count per user per thread

## Design
- DB: unread_counts(user_id, thread_id, count)
- Server: increment on message insert, reset to 0 on thread open
- Client: UnreadCountBadge component on ThreadListItem; WebSocket subscription
```

**Planning Gate (Approach):** AI presents the plan. Expert reads and types:

```
/gm Real-time is out of scope for now. Remove AS-3 and the WebSocket work.
    Start with polling or page-load refresh — we can promote AS-3 later.
```

AI updates `approach.md` — removes WebSocket, Redis, and AS-3 reference.
Expert reads the update. Types `/ty`.

**G2:** Expert reads the final `approach.md`. Types `/ty`. File committed.

---

## Delivery phase

AI generates `.aiya/42/delivery.md`:

```markdown
## Steps
1. Migration: add unread_counts(user_id, thread_id, count) table
2. Server: increment count on message insert
3. Server: reset count to 0 on thread open
4. Client: UnreadCountBadge component
5. Client: wire badge into ThreadListItem, fetch count on load
6. Test: E2E — open thread, verify badge clears (AS-1, AS-2)

## Verification
Run E2E suite. AS-1 and AS-2 must pass. AS-3 is out of scope.
```

**Planning Gate (Delivery):** Expert reads the Steps. Types `/ty`.

---

## Delivery execution — ACC Turns

aiya-jam (Runner) drives Turns sequentially. Each Turn receives the previous CCS.

### CCS_0 — initial state, derived from TC

```
semantic_gist:
  implement(unread badge) scoped(AS-1 AS-2)

goal_orientation:
  deliver(unread_count badge on thread list)
  ensure(badge clears on thread open)

constraints:
  must_not(implement WebSocket)
  must_not(implement AS-3)
  follow(approach.md technology decisions)

predictive_cue:
  next(Step 1: migration unread_counts table)

uncertainty_signal:
  level(low)
  assumption(unread_counts table does not yet exist)

retrieved_artifacts:
  spec(.aiya/42/goal.md)
  spec(.aiya/42/approach.md)
  spec(.aiya/42/delivery.md)
```

`goal_orientation` comes from Benefit + Acceptance Scenarios (TC Goal phase).
`constraints` comes from the `/gm` redirect captured in Approach phase.
`predictive_cue` is Step 1 from delivery.md.

### Turn 1 — migration

Input: CCS_0 + "Execute Step 1: add unread_counts table migration."

Turn writes `db/migrations/004_unread_counts.sql`. Produces CCS_1:

```
episodic_trace:
  completed(migration 004_unread_counts.sql)

semantic_gist:
  implement(unread badge) scoped(AS-1 AS-2)

goal_orientation:
  deliver(unread_count badge on thread list)
  ensure(badge clears on thread open)

constraints:
  must_not(implement WebSocket)
  must_not(implement AS-3)

predictive_cue:
  next(Step 2: increment count on message insert)

uncertainty_signal:
  level(low)

retrieved_artifacts:
  code(db/migrations/004_unread_counts.sql)
  spec(.aiya/42/approach.md)
```

### Turn 2 — server: increment

Input: CCS_1 + "Execute Step 2: increment unread_counts on message insert."

Turn edits `server/messages.js`. Produces CCS_2. `episodic_trace` records the change.
`predictive_cue` advances to Step 3.

### Turns 3–5 — server reset, client component, client wiring

Each Turn: consumes previous CCS, does one Step, advances `predictive_cue`.
No transcript is carried. The constraint (`must_not WebSocket`) persists in every CCS.

### Turn 6 — E2E test

Input: CCS_5 + "Execute Step 6: write and run E2E test for AS-1 and AS-2."

Turn writes `tests/e2e/unread-badge.spec.ts`. Runs Playwright. Tests pass. Produces CCS_6:

```
episodic_trace:
  completed(e2e unread-badge.spec.ts)
  observed(AS-1 pass: badge shows unread count)
  observed(AS-2 pass: badge clears on thread open)

semantic_gist:
  implement(unread badge) scoped(AS-1 AS-2)

goal_orientation:
  deliver(unread_count badge on thread list)
  ensure(badge clears on thread open)

constraints:
  must_not(implement WebSocket)
  must_not(implement AS-3)

predictive_cue:
  verify(delivery.md Verification section)

uncertainty_signal:
  level(low)

retrieved_artifacts:
  code(tests/e2e/unread-badge.spec.ts)
  spec(.aiya/42/delivery.md)
```

Runner updates `delivery.md` Verification section with test results.

---

## G3 — Delivery Output Gate

Expert reads `delivery.md` Verification:

```
AS-1 ✓ badge shows unread count
AS-2 ✓ badge clears on thread open
AS-3 — out of scope (deferred)
```

Expert types `/ty`. PR is opened. Branch merged.

---

## What the scenario shows

| Question | Answer |
|---|---|
| Where does `goal_orientation` come from? | TC Benefit + Acceptance Scenarios |
| Where do `constraints` come from? | TC Approach phase + `/gm` redirects |
| Where does `predictive_cue` come from? | Current Step in delivery.md |
| What persists across all Turns? | `goal_orientation` and `constraints` — the expert's judgment |
| What changes per Turn? | `episodic_trace`, `predictive_cue`, `uncertainty_signal` |
| When does expert judgment enter? | At each Steering Gate — not at every Turn |
| How does a redirect propagate? | `/gm` updates the TC file; TC flows into CCS_0; every Turn inherits it |
