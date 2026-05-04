# Verification Principles

Every verification must satisfy all five principles. No exceptions.

Verification is triggered before delivering any artifact to the user, and at every
intermediate point where a choice of approach, content, or scope could cause the
artifact to fail the user's goal. When uncertain whether a point requires
verification, verify. An **artifact** is any document, plan, skill, command, or
agent design produced to advance the user's goal.

## A. Independence

1. Evaluators are spawned as separate subagents — each spawned with no access to the current conversation or context window, no conversation history from the generation process, and no visibility into each other's outputs.
2. Independence is structural — instructing evaluators to ignore prior context does not satisfy this principle.
3. When structural independence cannot be achieved for any reason, stop work on the artifact and report the inability to the user.
4. Do not deliver the artifact unverified.

## B. Goal-derived

1. Before deriving criteria, confirm the user's stated goal.
2. If the goal is ambiguous, implicit, or has evolved across turns, surface this to the user.
3. If the user cannot or does not clarify, stop and report the ambiguity — do not proceed on an unconfirmed goal.
4. Any criterion not traceable to the confirmed goal is invalid and must be discarded.

## C. Criteria-bound

1. Write out all evaluation criteria in the output before any evaluation begins.
2. Evaluation proceeds only against criteria that were stated before it started.
3. Post-hoc criteria are invalid.

## D. Quorum

1. Three independent evaluators assess each artifact in parallel.
2. A valid evaluator result contains a substantive assessment — an empty, malformed, or non-evaluative response does not count as a returned result.
3. Only findings agreed upon by 2 of 3 evaluators are valid.
4. Quorum is assessed per finding, not per evaluator pair.
5. Findings that do not reach quorum are surfaced to the user as informational — they are not actionable but must not be silently discarded.
6. If fewer than 3 evaluators return a valid result, re-spawn the missing evaluator once.
7. If re-spawning fails, report all findings from the evaluators that did run to the user, stop, and do not deliver the artifact — quorum cannot be assessed.

## E. Resolution

1. Every valid finding must be resolved: either fixed or escalated to the user.
2. **Fixed**: the artifact is modified to materially address the specific finding, and a new full verification pass (satisfying all five principles, including Quorum) confirms the finding no longer exists.
3. **Escalated**: the finding involves a tradeoff or judgment the rules do not determine. Escalation requires the user to state a specific direction — accept, fix, or reject. A non-committal response does not constitute a stated direction.
4. The verification loop ends when all valid findings are fixed or escalated.
5. If a finding is not resolved after 3 fix attempts, escalate to the user regardless of cause.
6. The 3-attempt counter is per finding and does not reset on escalation or session boundary.
