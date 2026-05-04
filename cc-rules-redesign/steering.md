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

## PR goal

principles.md が完成した。このPRのゴールは **principles を CC のセッションで活用できる状態にすること**。

aiya コマンド（/hi, /go, /ty, /gm, /bb）の作成は別PR。

## Completed

- [x] Collect pain points
- [x] Draft principles → `.claude/rules/principles.md`
- [x] Archive existing `.claude/rules/` files → `cc-rules-redesign/rules-backup/`
- [x] principles.md を 4原則に整理・洗練（名称変更・抽象度統一）

## Next tasks

1. [ ] principles を CC のセッションで活用する方法をゼロベースで設計する
2. [ ] 設計を実装する
3. [ ] PR #10 完成

## Session context

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
