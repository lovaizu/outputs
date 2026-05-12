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

## Goal phase — ACC Turns

**Planning Gate (Goal):** AI presents its understanding of the hearing. Expert reads.
Types `/ty`. Drafting begins.

One Turn handles the AI's contribution: extract Situation and Pain from the hearing.

### Goal Turn 1 — draft Situation and Pain

Input: CCS_g0 (initialized from hearing transcript) + "Draft Situation and Pain for goal.md."

```
semantic_gist:
  draft(Situation Pain) from(hearing #42)

goal_orientation:
  produce(goal.md Situation Pain sections)

retrieved_artifacts:
  log(hearing transcript #42)
```

Turn writes the draft. Expert reads, then authors Benefit and Acceptance Scenarios
directly into `goal.md`.

**G1:** Expert reads the completed `goal.md`. Types `/ty`. File committed.

```markdown
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

---

## Approach phase — ACC Turns

**Planning Gate (Approach):** AI presents its investigation plan. Expert reads.
Types `/ty`. Investigation begins.

Three Turns: investigate the codebase → evaluate technology → draft approach.md.
Without ACC here, Turn 3 drafts against assumptions instead of verified facts.

### Approach Turn 1 — investigate existing stack

Input: CCS_a0 (initialized from goal.md) + "Investigate messaging infrastructure,
DB schema, and existing WebSocket usage."

Turn reads `server/`, `db/schema.sql`, `package.json`. Produces CCS_a1:

```
episodic_trace:
  observed(WebSocket server exists: server/ws.js — used for presence only)
  observed(DB has messages table; no unread tracking column)
  observed(Redis not in stack: package.json has no redis dependency)
  observed(Playwright in devDependencies — E2E runner available)

semantic_gist:
  investigate(unread badge implementation options)

goal_orientation:
  produce(approach.md grounded in actual stack)

constraints:
  follow(goal.md Acceptance Scenarios AS-1 AS-2 AS-3)

uncertainty_signal:
  level(medium)
  gap(WebSocket broadcast pattern unknown — can it push badge updates?)
  gap(DB migration tooling not confirmed)

retrieved_artifacts:
  code(server/ws.js)
  code(db/schema.sql)
  config(package.json)
```

### Approach Turn 2 — evaluate technology options

Input: CCS_a1 + "Evaluate WebSocket vs polling for badge updates given findings."

Turn reads `server/ws.js` broadcast pattern in detail. Produces CCS_a2:

```
episodic_trace:
  observed(WebSocket broadcasts to room — badge push requires per-user channel)
  observed(per-user WebSocket channel not implemented — significant new work)
  observed(migration tooling: db-migrate in package.json — confirmed)

semantic_gist:
  investigate(unread badge implementation options)

goal_orientation:
  produce(approach.md grounded in actual stack)

constraints:
  follow(goal.md Acceptance Scenarios AS-1 AS-2 AS-3)

uncertainty_signal:
  level(low)
  assumption(AS-3 real-time requires per-user WebSocket — non-trivial scope)

retrieved_artifacts:
  code(server/ws.js — broadcast pattern detail)
```

### Approach Turn 3 — draft approach.md

Input: CCS_a2 + "Draft Testing, Technology, and Design for approach.md."

Turn writes the draft using verified facts from CCS_a2. Produces approach.md:

```markdown
## Plan
Investigate existing WebSocket infrastructure and message storage before committing
to technology. Planning Gate review before investigation begins.

## Testing
- Unit: unread count calculation (increment on insert, reset on open)
- E2E (Playwright): open thread → verify badge disappears

## Technology
- WebSocket: exists but broadcast-only; per-user push requires new channel work
- Redis: not in stack — adding it would be significant scope
- Proposal: load badge count on page load (no real-time); AS-3 deferred

## Design
- DB: unread_counts(user_id, thread_id, count) via db-migrate
- Server: increment on message insert, reset to 0 on thread open
- Client: UnreadCountBadge component on ThreadListItem; count fetched on load
```

**Planning Gate (Approach):** Expert reads. The draft already surfaces the WebSocket
scope problem — not as a surprise, but as a verified finding. Expert types:

```
/gm Real-time is out of scope for now. Remove AS-3 explicitly.
    The current proposal is correct — ship it without WebSocket.
```

AI updates `approach.md` — marks AS-3 as deferred, removes the proposal hedge.
Expert reads the update. Types `/ty`.

**G2:** Expert reads the final `approach.md`. Types `/ty`. File committed.

> **Without ACC in Approach:** Turn 3 would draft against guesses ("WebSocket might
> work", "Redis might be available"). The expert would discover the real constraints
> only after reading the draft — requiring a deeper redirect or a research loop.
> With ACC: the investigation is verified first; the draft reflects reality; the
> Planning Gate conversation is about scope, not surprises.

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
| Where does `goal_orientation` come from? | TC Benefit + Acceptance Scenarios (Goal phase) |
| Where do `constraints` come from? | TC Approach phase + `/gm` redirects |
| Where does `predictive_cue` come from? | Current Step in delivery.md (Delivery); current investigation target (Goal/Approach) |
| What persists across all Turns within a phase? | `goal_orientation` and `constraints` — the expert's judgment |
| What changes per Turn? | `episodic_trace`, `predictive_cue`, `uncertainty_signal` |
| When does expert judgment enter? | At each Steering Gate — not at every Turn |
| How does a redirect propagate? | `/gm` updates the TC file; TC flows into the next phase's CCS_0; every Turn inherits it |
| Why does Approach need ACC? | Investigation spans multiple Turns; without CCS, Turn 3 drafts against assumptions rather than verified facts — producing surprises at the gate instead of decisions |
| What is each phase's CCS for? | Goal: carry hearing → draft. Approach: carry investigation → draft. Delivery: carry steps → implementation. |
