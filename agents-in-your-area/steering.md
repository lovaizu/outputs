# AIYA documentation polish — progress

## Original intent

> READMEのコミット履歴を参考に、docsの中の文書を同じように修正と更新してみて。目的はaiyaの実装に進むために設計とUXを固めることです

(Follow-up redirect from the user:)

> それは逆です、作る前に利用者をイメージして決めるんです。あるべき姿を先に考えるんです。

> 今は実装は不要、あるべき姿を書いて

## Current phase

**Goal + Approach phase step design complete (v4).** Both step lists refined to v4 via Action Principles assay (3 evaluators each, quorum 2/3). Next: Delivery phase step design.

Branch: `claude/check-aiya-status-quShQ`

## Completed

- [x] README: Concepts section names all 8 TC elements; Quickstart filled in with curl one-liner + 5 slash commands
- [x] tc-x-gates.md: renamed Success Scenarios → **Acceptance Scenarios**; added Japanese phase subtitles (達成すべきこと / 達成する方法 / 届ける); Planning Gate + Output Gate (G1/G2/G3) structure; 1 Step = 1 Turn invariant articulated; Storage section resolved (`.aiya/<issue-number>/`)
- [x] tc-x-gates.md: added Pain/Benefit writing discipline — Benefit is strategic impact, not Pain's inverse
- [x] aiya-jam.md: full rewrite with conversational commands (`/hi /go /ty /gm /bb`), up.sh/dn.sh lifecycle, Chain directory layout, Quickstart
- [x] acc.md: CCS lowercase filename convention (`t001.md`, `t002.md`) documented; physical-location open question resolved
- [x] docs/scenario.md: concrete end-to-end scenario (unread badge) showing TC→CCS linkage across all three phases with ACC Turns
- [x] docs/goal-phase-steps.md: Goal phase ACC Turn sequence v3 (G0–G27 + G-REDIRECT) — dual expert review (product discovery + AI agent design), re-review, full integration of all High/Medium/Low findings
- [x] docs/goal-phase-steps.md + approach-phase-steps.md: v4 refinement — Action Principles assay (6 evaluators total); Conventions section, research step hypotheses, gate 2-pass structure, fact-grounding fixes

## Remaining tasks

<!-- paused: 2026-05-12 — next: #2 Delivery phase step design -->

### Design (blocks the remaining Open questions)

Goal and Approach phase step sequences are complete (both v4, dual-expert-review + assay-refined). Delivery needs the same treatment so the whole lifecycle is closed before individual Open questions can be answered with confidence.

- [x] **#1 Approach phase step design** — A0–A19 + A-REDIRECT + A-NOFIT (v3). Key decisions: Testing-First enforced sequentially; A6b expert confirmation after codebase research; lock-step closure rule in A-REDIRECT; A17 sole coverage authority; A-NOFIT escalation for no-viable-technology.
- [x] **#1a Assay + v4 refinement** — Valid findings addressed: Conventions (D.2), phase completion state (A.2), research hypotheses on G5a/G5b/G11/G14/A5/A7 (C.3), gate 2-pass structure on G27/A19 (C.4/D.3), A0 required sections (B.1), A1 provisional labels (B.1), A3 verification method + completeness (C.1/B.2), A2 adequacy criteria (C.1), A14 pre-work checklist (C.1), A4 G21 deadlock path (C.5), A6 deferral rationale + runner-confirmed definition (B.1/C.5).
- [ ] **#2 Delivery phase step design** — Steps authoring, Turn execution, Verification, PR → merge. Key decisions: who authors Steps (expert / AI); how Verification ties back to Acceptance Scenarios; what lands in the PR vs in `delivery.md`.

Recommended next tactical step: Delivery phase (#2).

### Open questions — per document

**tc-x-gates.md**
- [ ] Per-element authoring schema (what exactly to write per element)
- [ ] Chain → CCS linkage (reference vs value copy)
- [ ] Gate criteria (what exactly must hold for `/ty` to be appropriate)
- [ ] Archival (how completed Chains are preserved after merge)
- [ ] Authoring split between expert and AI, per element

**acc.md**
- [ ] CCS versioning (whether to keep the state before replacement)
- [ ] Extension policy for the type vocabulary
- [ ] Runner implementation form (subagent / separate session / separate container)
- [ ] Async coordination for parallel Turns

**aiya-jam.md**
- [ ] SKILL.md placement and loading
- [ ] SKILL.md granularity (per phase / per Turn kind)
- [ ] Workflow definition language (YAML / TypeScript / plain Markdown)
- [ ] Parallel Turn handling
- [ ] Runner implementation form and location (inside / outside aiya-pit)
- [ ] Integration with aiya-pit (how Turns are launched in the sandbox)
- [ ] Integration with aiya-tape (whether CCS creation events are recorded)

**aiya-pit.md**
- [ ] CA cert distribution
- [ ] Base image selection
- [ ] In-container user privileges

**aiya-tape.md**
- [ ] Allowlist management
- [ ] Default dashboard presets
- [ ] Log retention
- [ ] Masking rules

**README**
- [ ] Contributing section (branch strategy, commit rules, review flow)

## Session context

- The user drove design via proposal-based hearing. Decisions crystallized in order: repo-scoped install model → conversational command names → phase-file structure → 8-element TC with Japanese definitions → 2-gate-per-phase (Planning + Output) → 1 Step = 1 Turn invariant maintained by plan updates → Acceptance Scenarios naming.
- Key constraint: the expert's judgment must be structurally embedded, not bolted on. Gates exist because drift is structurally detectable only when there is something concrete to compare against.
- Pain/Benefit discipline matters: Pain is observable symptom; Benefit is strategic downstream impact. Users often collapse them — the doc now enforces the distinction.
- Command naming: conversational phrases over functional labels (`/ty` not `/approve`, `/gm` not `/feedback`).
- Goal phase step design process: start with a concrete scenario (TC→CCS linkage), enumerate steps, run dual independent expert subagent review, integrate, re-review, fix all findings. This process works and should be reused for Approach and Delivery phases.
- Goal phase v3 key structural decisions: G0 Goal Intake initializes CCS; parallel research (G5a‖G5b) requires a merge Turn (G5c); Root Cause Analysis (G13) must follow Pain Lock (G12); Similar Cases Research (G14) precedes Benefit Scoping (G15); all revision loops have N=5 circuit-breakers; G-REDIRECT invalidates stale CCS components on re-entry; G27 is the only step that authorizes inter-phase transition.

## Document layout

```
agents-in-your-area/
  README.md                  # entry point — why AIYA, Concepts, Quickstart, Architecture
  progress.md                # this file
  docs/
    background.md            # prior art, scope, comparisons, FAQ
    tc-x-gates.md            # Traceability Chain × Steering Gates
    acc.md                   # Agent Cognitive Compressor runtime
    aiya-jam.md              # orchestrator package
    aiya-pit.md              # sandbox package
    aiya-tape.md             # auditor package
```

## Next session entry point

1. Read this file.
2. Pick up **Approach phase flow design** — use Goal phase as the template.
3. Once Approach + Delivery flows are set, walk each Open questions list one document at a time.
