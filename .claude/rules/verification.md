# Verification Principles

Every verification must satisfy all five principles. No exceptions.

Verification is triggered before delivering any artifact to the user, and at every
intermediate point where a choice of approach, content, or scope could cause the
artifact to fail the user's goal. When uncertain whether a point requires
verification, verify. An **artifact** is any document, plan, skill, command, or
agent design produced to advance the user's goal.

## 1. Independence

1.1 Spawn evaluators as separate subagents — each with no access to the current conversation or context window, no conversation history from the generation process, and no visibility into each other's outputs.

1.2 Independence is structural — instructing evaluators to ignore prior context does not satisfy this principle.

1.3 If structural independence cannot be achieved for any reason: stop work and report to the user — do not deliver the artifact unverified.

## 2. Goal-derived

2.1 Confirm the user's stated goal before deriving criteria.

2.2 Discard any criterion not traceable to the confirmed goal.

2.3 If the goal is ambiguous, implicit, or has evolved across turns: surface this to the user.

2.4 If the user cannot or does not clarify: stop and report the ambiguity — do not proceed on an unconfirmed goal.

## 3. Criteria-bound

3.1 Write out all evaluation criteria in the output before any evaluation begins.

3.2 Evaluate only against criteria that were stated before evaluation started.

3.3 Post-hoc criteria are invalid.

## 4. Quorum

4.1 Three independent evaluators assess each artifact in parallel.

4.2 A valid evaluator result contains a substantive assessment — empty, malformed, or non-evaluative responses do not count.

4.3 A finding is valid only if 2 of 3 evaluators agree. Quorum is assessed per finding, not per evaluator pair.

4.4 Findings that do not reach quorum are surfaced to the user as informational — do not silently discard them.

4.5 If fewer than 3 evaluators return a valid result: re-spawn the missing evaluator once.

4.6 If re-spawning fails: report all findings from the evaluators that did run, stop, and do not deliver the artifact — quorum cannot be assessed.

## 5. Resolution

5.1 Resolve every valid finding: either fix or escalate.

5.2 **Fixed**: modify the artifact to materially address the finding, then run a new full verification pass (satisfying all five principles) confirming the finding no longer exists.

5.3 **Escalated**: the finding involves a tradeoff or judgment the rules do not determine. Obtain a specific user direction — accept, fix, or reject. A non-committal response does not constitute a stated direction.

5.4 If a finding is not resolved after 3 fix attempts: escalate regardless of cause. The counter is per finding and does not reset on escalation or session boundary.
