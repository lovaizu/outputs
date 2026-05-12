# Checklist Items

> Machine-readable checklist entries for smith's inspector agents. Each item is derived from the taxonomy in `taxonomy.md` and carries all fields needed to inspect, score, and propose fixes for plugin quality issues.
>
> Source decisions: `progress.md §PRM domain pre-decisions` (PRM domain); `concepts.md`, `components.md`, `patterns.md`, `checklists.md` (all other domains).

## Schema

| Field | Description |
|---|---|
| `id` | Taxonomy ID (e.g. `ARC-TLS`) |
| `slug` | Kebab-case name used in `finding_type` strings (e.g. `three-layer-separation`) |
| `domain` | `ARC` / `SPC` / `PRM` / `FLW` / `CTX` |
| `applies_to` | Component types this check targets: `command`, `agent`, `skill`, `hook`, `all`. Comma-separated. Content-conditional items note their precondition. |
| `severity` | `Mandatory` — blocker; `Recommended` — quality debt; `Quality` — polish. |
| `auto` | `[auto]` — deterministic, machine-verifiable; `[judgment]` — requires LLM or human assessment. `[auto]` findings bypass the convergence threshold. |
| `check` | One sentence: what to verify. |
| `fix` | One sentence: how to correct a NG finding. |
| `related` | IDs of other items in the same convergence cluster. A fix to any cluster member may cause others to change verdict. |
| `example` | Optional before/after illustration. |

**finding_type format**: `checklist:<component-type>:<slug>` — e.g. `checklist:command:three-layer-separation`. Use the `slug` field, never the `id`.

**OOS rule**: Mark OOS when the item is logically inapplicable to the file being inspected (wrong component type, or content precondition absent for content-conditional items). OOS is not NG.

**NG beats OOS for Mandatory items**: If any lens marks NG on a Mandatory item, the finding is promoted regardless of OOS votes from other lenses. Smith-evaluate.sh implements this.

---

## ARC — Architecture (10 items)

---

### ARC-SDL

| Field | Value |
|---|---|
| **id** | `ARC-SDL` |
| **slug** | `standard-directory-layout` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Recommended |
| **auto** | `[auto]` |
| **check** | Do all top-level directories inside the plugin root match the standard set (`.claude-plugin/`, `commands/`, `agents/`, `skills/`, `hooks/`, `hooks-handlers/`, `scripts/`)? |
| **fix** | Move files into the closest standard directory; rename or remove non-standard top-level directories. |
| **related** | `ARC-MVP` |
| **example** | NG: `my-plugin/utils/` (non-standard directory). OK: `my-plugin/scripts/` (standard). |

---

### ARC-ACA

| Field | Value |
|---|---|
| **id** | `ARC-ACA` |
| **slug** | `archetype-command-agent` |
| **domain** | ARC |
| **applies_to** | all (content-conditional: plugin has commands + agents, no skills — Archetype A) |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | In an Archetype A plugin (commands + agents, no skills), does the command act as the sole procedure driver while each agent performs a bounded, isolated subtask with no knowledge-delivery role? |
| **fix** | If an agent serves as a knowledge source, convert it to a skill; if the command contains execution logic (tool calls, file writes), extract it into a dedicated agent. |
| **related** | `ARC-TLS`, `ARC-AFD` |
| **example** | NG: agent that returns static guidance text. OK: agent that reads files and writes a patch. |

---

### ARC-ASO

| Field | Value |
|---|---|
| **id** | `ARC-ASO` |
| **slug** | `archetype-skill-only` |
| **domain** | ARC |
| **applies_to** | all (content-conditional: plugin has skills only, no commands or agents — Archetype B) |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | In an Archetype B plugin (skills only), does the skill description match the user's natural-language trigger, and does the skill body avoid dispatching to subagents or performing side effects? |
| **fix** | Improve the skill description to match likely user phrasing; remove any agent dispatch from the skill body (convert to Archetype C if dispatch is needed). |
| **related** | `ARC-TLS`, `ARC-AFD` |
| **example** | NG: skill body that calls `Agent(...)`. OK: skill body that contains domain knowledge only. |

---

### ARC-AH

| Field | Value |
|---|---|
| **id** | `ARC-AH` |
| **slug** | `archetype-hybrid` |
| **domain** | ARC |
| **applies_to** | all (content-conditional: plugin has commands + agents + skills — Archetype C) |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | In an Archetype C plugin (commands + agents + skills), do skills serve exclusively as on-demand knowledge sources loaded via the Skill tool or description match, rather than as procedure drivers? |
| **fix** | If a skill contains phase logic or user-interaction steps, move that logic into a command; if it produces findings directly, route through an agent. |
| **related** | `ARC-TLS`, `ARC-AFD` |
| **example** | NG: skill with numbered phases that instruct Claude to ask the user for approval. OK: skill loaded mid-command to supply domain knowledge. |

---

### ARC-TLS

| Field | Value |
|---|---|
| **id** | `ARC-TLS` |
| **slug** | `three-layer-separation` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Mandatory |
| **auto** | `[judgment]` |
| **check** | Do commands stay in the procedure layer (what/when/branching/user gates), skills in the knowledge layer (how), and agents in the execution layer (tool calls, reads, writes)? |
| **fix** | Move domain knowledge from command bodies into skills; move file I/O and tool calls from commands into dedicated agents. |
| **related** | `ARC-ACA`, `ARC-ASO`, `ARC-AH`, `ARC-AFD` |
| **example** | NG: command body that directly edits files. OK: command that dispatches an agent to edit files, loading a skill for context. |

---

### ARC-MVP

| Field | Value |
|---|---|
| **id** | `ARC-MVP` |
| **slug** | `minimum-viable-plugin` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Mandatory |
| **auto** | `[auto]` |
| **check** | Does `.claude-plugin/plugin.json` exist at the plugin root and contain a `name` field? |
| **fix** | Create `.claude-plugin/plugin.json` with at minimum `{"name": "your-plugin-name"}`. |
| **related** | `ARC-SDL` |
| **example** | NG: missing `.claude-plugin/` directory. NG: `plugin.json` with no `name` key. OK: `{"name": "code-review", "version": "1.0"}`. |

---

### ARC-AFD

| Field | Value |
|---|---|
| **id** | `ARC-AFD` |
| **slug** | `archetype-first-decision` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | Is the plugin's archetype (A: command+agent, B: skill-only, C: hybrid) consistent with its stated purpose, and does the component mix match that archetype without unnecessary additions? |
| **fix** | Identify the intended archetype explicitly in README.md; remove components that don't serve the plugin's purpose; add missing components for the archetype. |
| **related** | `ARC-ACA`, `ARC-ASO`, `ARC-AH`, `ARC-TLS` |
| **example** | NG: plugin with a single skill that also has an empty `commands/` directory. OK: plugin whose README states "Archetype B" and contains only `skills/`. |

---

### ARC-CCH

| Field | Value |
|---|---|
| **id** | `ARC-CCH` |
| **slug** | `component-choice-heuristic` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | Is each component type present only when its specific capability is needed — commands for user-triggered workflows, agents for isolated execution with side effects, skills for knowledge injection, hooks for unconditional enforcement? |
| **fix** | Replace over-specified components with simpler alternatives (e.g., agent returning only read-only analysis → command phase; command containing hard policy → hook). |
| **related** | `ARC-AFD`, `ARC-TLS` |
| **example** | NG: agent whose only job is returning static guidance (should be a skill). OK: agent that reads the diff, writes a patch, and returns results. |

---

### ARC-PNE

| Field | Value |
|---|---|
| **id** | `ARC-PNE` |
| **slug** | `propose-not-execute` |
| **domain** | ARC |
| **applies_to** | command, agent (content-conditional: plugin produces irreversible outputs — file writes, pushes, API calls, PR creation) |
| **severity** | Recommended |
| **auto** | `[judgment]` |
| **check** | When the plugin performs irreversible actions, does it present a plan and obtain explicit user approval before executing? |
| **fix** | Add an explicit confirmation phase: display the proposed changes, wait for user approval (`y/n` or equivalent), then execute. |
| **related** | — |
| **example** | NG: command that creates a PR without displaying the draft first. OK: command that shows PR title/body and asks "Proceed? [y/n]" before calling `gh pr create`. |

---

### ARC-AC

| Field | Value |
|---|---|
| **id** | `ARC-AC` |
| **slug** | `applicability-criteria` |
| **domain** | ARC |
| **applies_to** | all |
| **severity** | Quality |
| **auto** | `[judgment]` |
| **check** | Does README.md state when the plugin is and is not appropriate to use — at minimum, what project types, input formats, or workflows it targets? |
| **fix** | Add "Suitable for" and "Not suitable for" sections to README.md with concrete examples of each. |
| **related** | — |
| **example** | NG: README with only a feature list and no applicability guidance. OK: README with "Suitable for: PRs with code changes. Not suitable for: documentation-only PRs." |

---

<!-- SPC, PRM, FLW, CTX entries pending -->
