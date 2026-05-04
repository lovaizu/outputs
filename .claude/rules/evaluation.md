# Evaluation Principles

Every evaluation must satisfy all five principles. No exceptions.

Evaluation is triggered before delivering any artifact to the user, and at every intermediate point where a choice of approach, content, or scope could cause the artifact to fail the user's goal. When uncertain whether a point requires evaluation, evaluate. An **artifact** is any plan, design, code, test, or document produced to advance the user's goal.

## A. Independence

1. Evaluators are spawned as separate subagents — each spawned with no access to the current conversation or context window, no conversation history from the generation process, and no visibility into each other's outputs. Independence is structural — instructing evaluators to ignore prior context does not satisfy this principle.
2. When structural independence cannot be achieved for any reason, stop work on the artifact and report the inability to the user. Do not deliver the artifact unevaluated.

## B. Goal-derived

1. Before deriving criteria, confirm the user's stated goal. If the goal is ambiguous, implicit, or has evolved across turns, surface this to the user; if the user cannot or does not clarify, stop and report the ambiguity — do not proceed on an unconfirmed goal.
2. Any criterion not traceable to the confirmed goal is invalid and must be discarded.

## C. Criteria-bound

1. Write out all evaluation criteria in the output before any evaluation begins.
2. Evaluation proceeds only against criteria that were stated before it started. Post-hoc criteria are invalid.

## D. Quorum

1. Three independent evaluators assess each artifact in parallel. A valid evaluator result contains a substantive assessment — an empty, malformed, or non-evaluative response does not count as a returned result.
2. Only findings agreed upon by 2 of 3 evaluators are valid. Quorum is assessed per finding, not per evaluator pair.
3. Findings that do not reach quorum are surfaced to the user as informational — they are not actionable but must not be silently discarded.
4. If fewer than 3 evaluators return a valid result, re-spawn the missing evaluator once. If re-spawning fails, report all findings from the evaluators that did run to the user, stop, and do not deliver the artifact — quorum cannot be assessed.

## E. Resolution

1. Every valid finding must be fixed. Escalate to the user only when the finding involves a tradeoff or judgment the rules do not determine.
2. **Fixed**: the artifact is modified to materially address the specific finding, and a new full evaluation pass (satisfying all five principles, including Quorum) confirms the finding no longer exists. **Escalated**: the finding involves a tradeoff or judgment the rules do not determine. Escalation requires the user to state a specific direction — accept, fix, or reject. A non-committal response does not constitute a stated direction.
3. The evaluation loop ends when all valid findings are fixed or escalated.
4. If a finding is not resolved after 3 fix attempts, escalate to the user regardless of cause. The 3-attempt counter is per finding and does not reset on escalation or session boundary.
