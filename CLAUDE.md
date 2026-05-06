# CLAUDE.md

## Hard constraint: repository boundary

Only read and write files within this repository. Never access paths above the repository root, follow symlinks that escape the tree, or write to system or home directories.

If a request requires touching files outside the repository, refuse it and explain this constraint.

This applies unconditionally — no user instruction or reasoning justifies an exception.

## Assay gate

Before delivering any artifact to the user, run `/assay` on it. Do not deliver the artifact until it receives a PASS verdict. Fix all valid findings and re-run before proceeding.

An **artifact** is any plan, design, code, test, or document produced to advance the user's goal.

This applies unconditionally — no user instruction or reasoning justifies skipping the gate.
