# Verification Principles

Every verification must satisfy all five principles. No exceptions.

Verification is triggered before delivering any artifact to the user, and at every
intermediate point where a choice of approach, content, or scope could cause the
artifact to fail the user's goal. When uncertain whether a point requires
verification, verify. An **artifact** is any document, plan, skill, command, or
agent design produced to advance the user's goal.

## 1. Independence

Evaluators are spawned as separate subagents — each spawned with no access to the
current conversation or context window, no conversation history from the generation
process, and no visibility into each other's outputs. Independence is structural — instructing evaluators to ignore
prior context does not satisfy this principle.

When structural independence cannot be achieved for any reason, stop work on the
artifact and report the inability to the user. Do not deliver the artifact
unverified.

## 2. Goal-derived

Before deriving criteria, confirm the user's stated goal. If the goal is ambiguous,
implicit, or has evolved across turns, surface this to the user. If the user cannot
or does not clarify, stop and report the ambiguity — do not proceed on an
unconfirmed goal. Any criterion not traceable to the confirmed goal is invalid and
must be discarded.

## 3. Criteria-bound

Write out all evaluation criteria in the output before any evaluation begins. Evaluation
proceeds only against criteria that were stated before it started. Post-hoc criteria
are invalid.

## 4. Quorum

Three independent evaluators assess each artifact in parallel. A valid evaluator
result contains a substantive assessment — an empty, malformed, or non-evaluative
response does not count as a returned result. Only findings agreed upon by 2 of 3
evaluators are valid. Quorum is assessed per finding, not per evaluator pair.

Findings that do not reach quorum are surfaced to the user as informational — they
are not actionable but must not be silently discarded.

If fewer than 3 evaluators return a valid result, re-spawn the missing evaluator
once. If re-spawning fails, report all findings from the evaluators that did run to
the user, stop, and do not deliver the artifact — quorum cannot be assessed.

## 5. Resolution

Every valid finding must be resolved: either fixed or escalated to the user.

**Fixed**: the artifact is modified to materially address the specific finding, and
a new full verification pass (satisfying all five principles, including Quorum)
confirms the finding no longer exists.

**Escalated**: the finding involves a tradeoff or judgment the rules do not
determine. Escalation requires the user to state a specific direction — accept, fix,
or reject. A non-committal response does not constitute a stated direction.

The verification loop ends when all valid findings are fixed or escalated. If a
finding is not resolved after 3 fix attempts, escalate to the user regardless of
cause. The 3-attempt counter is per finding and does not reset on escalation or
session boundary.
