# CC Rules Redesign — Steering

## Original intent

> Rethink CC rules from scratch. Existing rules may be referenced but must not constrain the design.

## Pain points (raw input from user)

### Thinking quality

1. Judges and implements based on assumptions, not facts
2. Samples rather than checking exhaustively
3. Does not always derive the ideal state from the goal
4. Does not plan by working backwards from the goal

### Communication quality

5. Explanations start too detailed — high cognitive load; lead with the point first
6. Documents have high cognitive load yet lack narrative flow — unreadable top-to-bottom

## Design decisions

- Principles alone won't be followed. Workflows operationalize the principles.
- Principles serve as a checklist when designing workflows (not converted into workflows themselves).
- Each workflow is a custom slash command for aiya.
- File naming: this file is `steering.md`; slash command definitions go under `.claude/skills/`.
- Scope agreement upfront (before autonomous execution) belongs in the `/go` workflow, not principles.

## Current phase

**wf-rev 再設計中。** principles.md と wf-rev を整理・洗練した後、未解決の設計判断が残っている。

### principles.md の状態（完了）

- ファイル名: `core-rules.md` → `principles.md`
- タイトル: "Core Rules" → "Principles"
- 4原則に整理済み（Fact-grounded / Goal-anchored / Verified at every stage / Proposed for judgment）
- Principle 3 を他の原則と同じ抽象度に整理済み
- スポーン失敗フォールバック節を削除済み

### wf-rev の状態（設計判断待ち）

現在の wf-rev は「ワークフロー定義ファイルを principles に照らしてレビューする」という狭いスコープ。

ユーザーの意図は異なる：**CC のあらゆる活動に principles を適用する**スキルが欲しい。つまり、ワークフローのレビューツールではなく、CC が何をするときも 4原則に従って動くためのフレームワーク。

**未回答の確認事項（次のセッションで確認）：**
このスキルは `/hi` `/go` などの aiya コマンドから呼ばれる sub-skill として想定しているか？それとも単体で使う汎用スキルか？

## Completed

- [x] Collect pain points
- [x] Draft principles → `.claude/rules/principles.md`
- [x] Archive existing `.claude/rules/` files → `cc-rules-redesign/rules-backup/`
- [x] principles.md を 4原則に整理・洗練（名称変更・抽象度統一・フォールバック削除）
- [x] wf-rev をゼロベースで再作成（principles v2 準拠、simulation/review の観点を明示）

## Next tasks

1. [ ] wf-rev の設計方針を確定（sub-skill か汎用か → ユーザーに確認）
2. [ ] wf-rev をゼロベースで再設計・作成
3. [ ] aiya コマンド（/hi, /go, /ty, /gm, /bb）の作成

## Session context

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
- aiya commands: /hi, /go, /ty, /gm, /bb (defined in `agents-in-your-area/docs/aiya-jam.md`)
