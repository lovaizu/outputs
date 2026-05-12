# Scenario: Unread Message Badge

A concrete walk-through of one task from `/hi` to merged PR. Shows how TC content
becomes CCS content, and how Steering Gates produce decisions rather than approvals.

This walk-through is abbreviated: representative steps are shown in full; groups of
intermediate steps are summarised. Step IDs map to
[goal-phase-steps.md](goal-phase-steps.md) and
[approach-phase-steps.md](approach-phase-steps.md).

---

## /hi — hear the issue

Expert types `/hi` and describes the situation in conversation.

AIYA files issue #42, creates branch `aiya/42`, and creates `.aiya/42/`.

---

## Goal phase

### G0 — Goal Intake [I]

CCS initialised from the /hi transcript.

```
episodic_trace:  hearing summary — team chat app, sidebar threads, unread uncertainty
semantic_gist:   investigate Situation and Pain for team chat user
focal_entities:  team member (candidate segment), chat threads
goal_orientation: (not yet locked — locked incrementally at G12 / G19 / G24)
uncertainty_signal: user segment not confirmed; Pain not yet identified
```

### G1 — User Identification [E]

AI proposes user segment. Expert confirms: **team member on desktop**.

### G2–G4 — Elicitation Scoping + Situation Draft + Revision [I/E]

G2 defines termination predicate. G3 proposes Situation draft. One G4 revision cycle.
Expert `/ty`:

> *"A team member opens a chat app after a two-hour meeting. Dozens of threads are
> visible in the sidebar."*

### G5a ‖ G5b — Situation Evidence Research (parallel) [R]

Pre-search populations enumerated before searching.

G5a (qualitative): sidebar overload confirmed as documented UX pattern — analyst
report cited. G5b (quantitative): average messages-per-session metric sourced from
industry survey. G5c merges both.

### G6–G6b — Situation Validation [I/E]

G6 validates claims against evidence: PASS. G6b is a pass-through; expert `/ty`.

### G7–G10 — Pain Elicitation, Prioritisation, Selection [I/E]

G7 defines Pain questions. G8 proposes candidates (two: no unread signal; no
notification sound). G9: one revision. G10: expert selects one Pain:

> *"There is no visual signal for unread messages. The user must open each thread
> manually to check for new content."*

### G11–G12 — Pain Evidence + Pain Lock [R/I]

G11 gathers evidence (user research analogues, support-ticket data). G12 confirms:
observable symptom ✓, not a root cause ✓, not a solution ✓. Pain **locked**.

### G13 — Root Cause Analysis [I]

Cause chain — 2 hops, each citing a G11 artifact:

```
Pain
  → no read-state tracked per user-thread        [cites: user-research report §3]
    → message storage designed for delivery only  [cites: DB schema analogue]
```

### G14–G19 — Similar Cases, Benefit Elicitation, Lock [R/I/E]

G14 finds analogues (email unread badge, Slack badge). G15 scopes Benefit questions
from the root cause. G16–G17: draft and one revision. G17b independently confirms:
not inverse-of-Pain ✓, traces to root cause ✓. G18: expert confirms strategic
relevance. G19 **locks** Benefit:

> *"Time-sensitive messages get faster responses. Team coordination cost drops
> because attention is directed, not scattered."*

### G20–G24 — Consistency, AS Draft, Testability, Lock [I/E]

G20: Situation–Pain–Benefit chain consistent. G21 drafts AS. G22–G23 testability
check passes. G24 **locks**:

```
AS-1: A thread with unread messages shows a badge with the unread count
AS-2: Opening the thread clears the badge
AS-3: Badge count updates when a new message arrives (no page reload required)
```

### G25–G26a — Traceability + goal.md Write [I]

G25: AS→Pain→Situation chain verified, zero orphans. G26 packages all locked
artifacts. G26a writes and commits `goal.md` to the branch.

### G27 — Gate (G1) [E]

AI presents two verification passes:

**(1) Goal alignment:** AS-1/AS-2/AS-3 each verify the observable absence of unread
signal — none test a mechanism.

**(2) Quality:** Evidence citations are dated ✓. Root cause chain has 2 observable
hops, each with a cited artifact ✓. AS format compliant (user-visible state,
no mechanism) ✓.

Expert confirms both passes. `/ty` → Approach phase begins.

---

## Approach phase

### A0 — Approach Intake [I]

CCS seeded from `goal.md`. `goal_orientation` locked to Benefit + AS verbatim.

### A1–A2 — Investigation Plan + Planning Gate [I/E]

A1 produces first-pass AS→test-method sketch and provisional targets:

```
AS-1 → E2E (badge visible), unit (count increment)
AS-2 → E2E (badge clears)
AS-3 → (provisional) E2E (real-time update) — technology TBD
provisional paths: server/, db/schema.sql, package.json
technology categories: WebSocket push, polling, Redis
```

**A2 [E] — Planning Gate:** AI leads with Issue + Conclusion (PROCEED) + Rationale
(one sentence) + full plan as Supporting material. Gate package labels all names
as unverified hypotheses. Expert `/ty` — approves investigation scope, not the names.

### A3–A4 — Testing Scoping + Review [I/E]

A3 maps each AS to test method. AS-3 flagged: infrastructure question open
(per-user push unconfirmed). A4 expert `/ty`.

### A5 — Codebase Investigation [R]

Pre-investigation scope stated before reading. Hypotheses per A3 question stated
before reading.

```
retrieved_artifacts:
  observed(server/ws.js — WebSocket server; broadcast-only to room, not per-user)
  observed(db/schema.sql — messages table present; no unread_counts column)
  observed(package.json — Playwright in devDependencies; db-migrate present; no Redis)
uncertainty_signal:
  gap(per-user WebSocket channel not implemented — AS-3 push path unconfirmed)
```

### A6–A6b — Testing Strategy + Confirmation [I/E]

Runner confirmed: Playwright (`package.json` → `devDependencies.@playwright/test:
"^1.40.0"`). Unit runner: vitest (`devDependencies.vitest: "^1.0.0"`).

AS-3 test method flagged as **pending** — real-time push depends on Technology choice.
Expert `/ty` → Testing **locked**.

### A7–A11 — Ecosystem Research, Options, Technology Review [R/I/E]

A7 pre-research population stated. Findings:

- WebSocket per-user: requires new channel architecture — Socket.io docs confirm
  ~3× scope of broadcast-only pattern. Ruled out.
- Redis: not in stack (`package.json` verified, zero dependencies). Out of scope.
- Polling on page load: compatible with locked Playwright runner. Viable.

A8–A10: polling selected. **A11 [E]:** AI leads with Issue + Conclusion (CONFIRM
polling) + evidence citations including quantitative scope comparison for ruled-out
options. Expert `/ty`.

### A12 — Technology Lock [I]

**Pending AS resolution:** AS-3 test method (real-time push) is incompatible with
polling-on-load. → Route to A-NOFIT.

**A-NOFIT:** options presented in sequence:
(1) relax constraint — not applicable, polling is the correct choice.
(2) narrow AS-3 — AI proposes: defer AS-3 to a future WebSocket phase; AS-1/AS-2
still fully serve the Benefit (directed attention without real-time is the core gain).
(3) not reached.

Expert selects option (2). AS-3 removed from current scope; recorded as NOFIT
compromise row. Technology **locked**.

### A13–A16 — Design Scoping, Draft, Review, Lock [I/E]

Design covers: `unread_counts(user_id, thread_id, count)` table via db-migrate;
server increment on insert; server reset to 0 on open; `UnreadCountBadge` client
component on `ThreadListItem`, count fetched on page load. Each decision traces
to AS-1 or AS-2. A15 expert `/ty`. Design **locked**.

### A17 — Consistency Validation [I]

AS-coverage matrix:

| AS | Test method | Technology support | Design element | Lock source |
|----|-------------|-------------------|----------------|-------------|
| AS-1 | E2E (Playwright) | polling ✓ | UnreadCountBadge + unread_counts | A16 |
| AS-2 | E2E (Playwright) | polling ✓ | server reset on open | A16 |
| AS-3 | — | — | — | A-NOFIT compromise |

PASS (AS-3 is a NOFIT row, not an orphan).

### A18–A18a — Gate Prep + approach.md Write [I]

A18 packages all locked elements including NOFIT row. A18a writes and commits
`approach.md` to the branch.

### A19 — Gate (G2) [E]

AI presents two verification passes:

**(1) Goal alignment:** AS-1 and AS-2 covered. AS-3 is a NOFIT compromise row —
expert must explicitly acknowledge before `/ty` is accepted.

**(2) Quality:** Playwright naming convention followed ✓. Technology rationale cites
quantitative scope comparison for ruled-out options ✓. All design decisions map to
≥1 AS ✓.

Expert acknowledges AS-3 NOFIT and confirms both passes. `/ty` → Approach phase
complete.

---

## Delivery phase

*(Delivery phase step list is forthcoming. The structure below is illustrative of
the intended pattern — it will be updated once the Delivery step design is complete.)*

`delivery.md` generated from locked `approach.md`:

```markdown
## Steps
1. Migration: add unread_counts(user_id, thread_id, count) table
2. Server: increment count on message insert
3. Server: reset count to 0 on thread open
4. Client: UnreadCountBadge component
5. Client: wire badge into ThreadListItem; fetch count on load
6. Tests: E2E — open thread, verify badge clears (AS-1, AS-2)

## Verification
Run E2E suite. AS-1 and AS-2 must pass. AS-3 out of scope (NOFIT, deferred).
```

Planning Gate (Delivery): Expert `/ty`.

### CCS_0 — initial state, derived from TC

```
goal_orientation:
  deliver(unread_count badge on thread list)
  ensure(badge clears on thread open)

constraints:
  must_not(implement WebSocket push)
  must_not(implement AS-3)
  follow(approach.md technology decisions)

predictive_cue:
  next(Step 1: migration — unread_counts table)

uncertainty_signal:
  level(low)

retrieved_artifacts:
  spec(.aiya/42/goal.md)
  spec(.aiya/42/approach.md)
  spec(.aiya/42/delivery.md)
```

`goal_orientation` ← TC Benefit + AS, locked at G19/G24.
`constraints` ← A-NOFIT resolution captured in Approach.
`predictive_cue` ← Step 1 of delivery.md.

### Turns 1–6 — execution

Each Turn: consume previous CCS → execute one Step → advance `predictive_cue`.
`goal_orientation` and `constraints` persist unchanged.

**Turn 6** writes and runs E2E tests. CCS_6:

```
episodic_trace:
  completed(tests/e2e/unread-badge.spec.ts)
  observed(AS-1 pass: badge shows unread count)
  observed(AS-2 pass: badge clears on thread open)

predictive_cue:
  verify(delivery.md Verification section)
```

### Output Gate (Delivery)

Expert reads `delivery.md` Verification:

```
AS-1 ✓  badge shows unread count
AS-2 ✓  badge clears on thread open
AS-3 —  out of scope (NOFIT, deferred)
```

Expert `/ty`. PR opened. Branch merged.

---

## What the scenario shows

| Question | Answer |
|---|---|
| Where does `goal_orientation` come from? | TC Benefit + AS — locked at G19/G24, carried verbatim through Approach into Delivery CCS_0 |
| Where do `constraints` come from? | A-NOFIT resolutions captured in Approach phase |
| Where does `predictive_cue` come from? | Current Step in delivery.md (Delivery); current investigation target (Goal/Approach) |
| What persists across all Turns in a phase? | `goal_orientation` and `constraints` — the expert's locked judgment |
| What changes per Turn? | `episodic_trace`, `predictive_cue`, `uncertainty_signal`, `retrieved_artifacts` |
| When does expert judgment enter? | At each [E] gate — not at every Turn |
| How does a scope reduction propagate? | A-NOFIT resolution → NOFIT row in AS-coverage matrix → carried verbatim into A18/A19 → acknowledged at G2 → constraint in Delivery CCS_0 |
| Why does Approach need ACC? | Investigation spans multiple Turns; without CCS each Turn re-derives context from scratch, making the draft Turn operate on guesses rather than verified facts |
| What is each phase's CCS for? | Goal: hearing → elicitation → locked artifacts. Approach: investigation → locked strategy. Delivery: locked plan → implementation. |
