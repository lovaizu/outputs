# Component Templates

> Fill-in-the-blank skeletons for each artifact smith emits, with best practices **baked in** — frontmatter, role lead, common-rules block, Phase>Step>Action body, approval gates, output contract, safe hook scripts. smith fills the `<...>` placeholders in Phase 5; the structure is fixed.
>
> Templates are the **what** (the skeleton); `workflow-patterns.md` is the **how** (the control-flow patterns used inside). Each template notes the Actions (`actions.md`) it embodies. Feeds the `smith-prompt-patterns` / `smith-structure-patterns` skills (steering #3).

## How to use

1. Pick the component roles in Phase 5-1 (driver / knowledge / execution).
2. For each part, copy the matching template below.
3. Fill placeholders in 5-3; polish wording in 5-4.
4. XML appears only where these templates already place it (structural blocks); the linear step sequence stays markdown — per `workflow-patterns.md`.

---

## `plugin.json` (manifest)

Keep minimal — only `name` is strictly required; the harness validates the rest (so smith does not over-specify).

```json
{
  "name": "<plugin-name>",
  "description": "<one line: what the plugin does and when to use it>",
  "version": "0.1.0"
}
```

---

## Driver skill — `skills/<name>/SKILL.md`

The user-invoked entry point (the modern slash command). Embodies: SPC-DMI, SPC-ATR, PRM-LFD/SPC-DOM (description), PRM-RLA (role lead), the Phase>Step>Action skeleton (FLW-PC), common-rules/on-failure blocks, FLW-EAG/PRM-RGC (gates), PRM-OSD (output contract).

```markdown
---
name: <kebab-name>                 # optional; defaults to directory name
description: <Third-person, active. What it does + when to use it. e.g. "Reviews SQL migrations and reports risky changes. Use when the user asks to check or review a migration.">   # NO XML tags here (validation forbids it)
disable-model-invocation: true     # user-triggered, not auto-fired
allowed-tools: Read, Glob, Grep, Edit, Write, Bash(git:*), Task   # least privilege — trim to what's needed
---

You are <role lead — one sentence: who this skill is and its stance>.   # PRM-RLA

<common_rules>
- Never write outside the repository.
- Stop and ask before any destructive or irreversible action.   # PRM-RGC
- Report facts; if a step is skipped or fails, say so.
</common_rules>

## Phase 1 — <name>
**Goal:** <what this phase achieves.>
1. **<step>** — <imperative action>.
2. **<step>** — <imperative action>.
**Exit:** <condition that must hold before Phase 2.>

## Phase 2 — <name>
**Goal:** <...>
1. **<step>** — <action>.
<case condition="<branch A>">
1. <action>.
</case>
<case condition="<branch B>">
1. <action>.
</case>

**Get explicit user approval before <irreversible action>.**   # calibrated emphasis, irreversible only — PRM-CPM/FLW-EAG

<on_failure>
Halt at the failing step. Do not auto-rollback. Report the partial state and point the user at `git status`. Never claim completion while the failure stands.
</on_failure>

## Output
<What to return: structure first, then format rules.>   # PRM-OSD/PRM-OFD
<What to return when there is nothing to report.>          # PRM-NRP
```

---

## Knowledge skill — `skills/<name>/SKILL.md`

On-demand reference loaded by the driver during a phase. Embodies: CTX-PD (progressive disclosure), PRM-LFD (description as trigger), CTX-SDW (no duplication), SPC-RTT-replacement (<500 lines, detail in references/).

```markdown
---
name: <kebab-name>
description: <When this knowledge applies — third person. e.g. "Best-practice patterns for authoring Claude Code subagents. Loaded when designing or writing an agent.">
user-invocable: false              # loaded by the driver, not the user
---

# <Title>

<Core knowledge that the consumer needs inline. Keep the body focused; under ~500 lines.>

## When to apply
<The trigger / situation in which this knowledge is used.>

## Detail
For <edge cases / long reference material>, see `references/<topic>.md` — loaded on demand.   # progressive disclosure
```

---

## Execution subagent — `agents/<name>.md`

A bounded, isolated worker dispatched by the driver (subagents cannot dispatch subagents). Embodies: SPC-AFM (frontmatter), PRM-LFD (delegation-trigger description), FLW-MTS (model by judgment density), SPC-ATR (least privilege), PRM-RLA (role), PRM-ESL (explicit scope), PRM-EI-CA (example), PRM-CTX (XML for mixed content), PRM-NRP.

```markdown
---
name: <kebab-name>
description: <When to delegate to this agent — third person, the trigger. e.g. "Use to review a single changed file for correctness bugs.">
model: inherit                     # or opus (deepest judgment) / sonnet (bounded analysis)
tools: Read, Glob, Grep            # least privilege; omit Write if it only reports
---

You are <role lead — one sentence>.   # PRM-RLA

<instructions>
<The scoped task.> Apply to <explicit scope — e.g. every changed line>, not just the first.   # PRM-ESL
</instructions>

<context>
!`<command that injects the live context, e.g. git diff>`
</context>

<examples>
<example>
Input: <concrete input>
Output: <concrete output>
</example>
</examples>

## Output
Return <output contract>. If <nothing is found>, return <explicit empty result>.   # PRM-NRP
```

---

## Hook — `hooks/hooks.json` + `hooks-handlers/<name>.sh`

Deterministic enforcement. Embodies: SPC-HER (correct event), SPC-THT (`type: command`), SPC-PRV (`${CLAUDE_PLUGIN_ROOT}`), SPC-HIV (safe script), SPC-HSV (exit-2 reason to stderr), SPC-HI (idempotent).

`hooks/hooks.json`:
```json
{
  "hooks": {
    "PreToolUse": [
      {
        "matcher": "<ToolName>",
        "hooks": [
          { "type": "command", "command": "${CLAUDE_PLUGIN_ROOT}/hooks-handlers/<name>.sh" }
        ]
      }
    ]
  }
}
```

`hooks-handlers/<name>.sh`:
```bash
#!/usr/bin/env bash
set -euo pipefail

input=$(cat)
tool_name=$(printf '%s' "$input" | jq -r '.tool_name')
file_path=$(printf '%s' "$input" | jq -r '.tool_input.file_path // empty')

# Reject path traversal
case "$file_path" in
  *..*) echo "Refusing path outside the workspace: $file_path" >&2; exit 2 ;;   # reason → stderr (SPC-HSV)
esac

# <Block the dangerous condition this hook exists for>
if <dangerous condition>; then
  echo "<why this was blocked>" >&2
  exit 2
fi

exit 0
```

---

## CLAUDE.md rule (when a project-level rule is the right tool)

Embodies: CTX-CIR ("would removing this cause mistakes?"), CTX-CMP (placement), SPC-HAR (rule vs hook).

```markdown
## <Rule name>

<The rule, stated imperatively. Include the "because" so Claude generalizes.>   # PRM-IR
```

> Use a hook instead (template above) when the rule **must** fire unconditionally and a soft instruction has proven unreliable — CTX-RHM.
