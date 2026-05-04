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

CCが産出するあらゆる成果物（計画・スキル・コマンド・エージェント設計など）に、行動原則と評価原則が取り込まれた状態にする。

aiya コマンド（/hi, /go, /ty, /gm, /bb）の作成は別PR。

## 活動の定義

| 活動 | 定義 |
|---|---|
| 生成 | AIがユーザーの目的を達成するプロダクト・ドキュメントを生成する |
| 評価 | AIが生成物がユーザーのゴールを達成しているかを有効な方法で評価する |

## Completed

- [x] Collect pain points
- [x] Draft principles → `.claude/rules/principles.md`
- [x] Archive existing `.claude/rules/` files → `cc-rules-redesign/rules-backup/`
- [x] principles.md を 4原則に整理・洗練（名称変更・抽象度統一）
- [x] 生成原則・評価原則の設計（要件合意）
- [x] `generation.md` → `action.md` にリネーム（Action Principles）
- [x] `verification.md` → `evaluation.md` にリネーム（Evaluation Principles）
- [x] 両原則の文言精査・整備

## 現在のファイル状態

最新コミット: `16d6674`

- `.claude/rules/action.md` — Action Principles
  - A: Fact-grounded（4項目）
  - B: Goal-anchored（5項目）— B.3に「アプローチ失敗時は代替手段を探す」追加済み
  - C: Verified at every stage — C.2をgoal perspectiveに修正済み
  - D: Proposed for judgment
- `.claude/rules/evaluation.md` — Evaluation Principles
  - A: Independence
  - B: Goal-derived
  - C: Criteria-bound
  - D: Quorum
  - E: Resolution — 修正がデフォルト、エスカレーションは例外。Fixed/Escalatedをサブ項目化。E.3をアプローチ変更要件に修正

## Next tasks（次セッションの起点）

1. [ ] **オープン問題の設計・実装**: CCが計画やスキルを作るとき、Action PrinciplesとEvaluation Principlesが取り込まれる仕組みをどう設計するか
2. [ ] PR #10 完成

## Session context

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
