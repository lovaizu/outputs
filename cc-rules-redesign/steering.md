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

## Steering の運用ルール

作業を開始する前に必ずこのファイルを読み、以下を守ること：

1. **作業前**: ゴールから end-state を導出し、タスクリストを記載する。各タスクには「何をするか」だけでなく「なぜするか・何を確認するか・達成基準」を書く
2. **作業中**: タスクを完了したらその場で `[x]` に更新し、発見した事実・判断・変更理由を記録する
3. **変更したら即コミット・プッシュ**: ファイルを変更したら確認なしでコミットしてプッシュする
4. **中断時**: 現在どのタスクのどのステップまで完了したかを「現在地」に記録してから停止する
5. **再開時**: 「現在地」から読み直し、前回の判断・文脈を復元してから作業を続ける

意図を残さないとドリフトする。タスクリストだけでは不十分。

---

## 現在のタスク: `assay` スキル作成

### ゴール

`/assay` を起動すると、渡された成果物またはワークフローを Action Principles・Evaluation Principles に照らして評価し、「どの原則のどの項目に違反しているか」「どう改善するか」を返す。ユーザーまたは AI が起動できる。

### End-state

`.claude/commands/assay.md` が存在し、以下を満たす：
- 入力: 評価対象の成果物またはワークフロー記述
- 出力: 違反箇所（原則・項目番号付き）+ 改善案
- 評価フロー: Evaluation Principles に従い独立した evaluator を使用
- 意図的に原則違反を含むワークフローに対して違反を正しく検出できる

### ゴール達成の評価基準（C で使用）

1. テスト用の「原則違反を含むワークフロー」を用意し、assay がそれを検出できるか
2. 検出された違反が具体的な原則の項目（例: A.2, C.1）にトレースできるか
3. 改善案が具体的で実行可能か
4. ユーザーが `/assay` と打って起動できるか、AI がツールとして呼べるか

### タスクリスト

- [ ] **T1: 事実確認**
  - *なぜ*: 仮定に基づいて設計すると A.1 違反。スキルの構造・制約を知らずに設計できない
  - *何を確認するか*: 既存スキルファイル（`.claude/commands/` 内）の構造と記法、action.md・evaluation.md の正確な文言、CC スキルのベストプラクティス（WebSearch）
  - *達成基準*: スキルファイルの必須要素・使えるツール・サブエージェント起動方法が事実として確認できた

- [ ] **T2: 仕様設計**
  - *なぜ*: B.2 に従い、end-state から逆算して設計する。何を入力とし何を出力するかが決まらないと実装できない
  - *何を決めるか*: 入力フォーマット、出力フォーマット（違反項目・改善案の構造）、evaluator の役割分担、AI からの自律起動の仕組み
  - *達成基準*: 設計を読めば実装できる、かつ上記のゴール達成基準を満たす設計になっている

- [ ] **T3: 仕様評価**
  - *なぜ*: C.1 に従い、実装前に仕様が正しいかを評価する。間違った仕様のまま実装するとコストが高い
  - *何をするか*: 3 evaluators（Evaluation Principles プロトコル）、ゴール評価者と Expert 評価者それぞれがテスト用違反ワークフローで検証
  - *達成基準*: quorum で valid finding がゼロになるまで修正ループを繰り返す

- [ ] **T4: 実装**
  - *なぜ*: 確定した仕様をファイルに落とす
  - *何をするか*: `.claude/commands/assay.md` を作成
  - *達成基準*: T2 の仕様を完全に実装している

- [ ] **T5: 実装評価**
  - *なぜ*: C.1 に従い、成果物完成後にも評価が必要。仕様評価とは独立して実施
  - *何をするか*: T3 と同じプロトコル、テスト用違反ワークフローで実際に assay を動かして検証
  - *達成基準*: 手応えをつかむまで修正→再評価ループ。quorum で valid finding がゼロ

- [ ] **T6: ユーザーレビュー・承認**
  - *なぜ*: D.1 に従い、成果物と根拠をユーザーに提示して判断を仰ぐ
  - *何を提示するか*: assay の動作サンプル（実際の入力と出力）、評価で発見された問題と解消内容、未解決のトレードオフ（あれば）
  - *達成基準*: ユーザーが承認を明示する

- [ ] **T7: PR #10 完成・マージ**
  - *なぜ*: PR ゴール達成のための最終ステップ
  - *何をするか*: コミット、プッシュ、PR 完成

### T1 完了メモ

- スキルファイル形式: frontmatter（description, context, agent 等）+ markdown 指示本文
- `$ARGUMENTS` で入力受け取り。`$0`, `$1` で位置引数アクセス可能
- Agent ツールで独立サブエージェント起動 → 会話履歴なし = Evaluation A.1 の構造的独立性を満たす
- デフォルトでユーザー（/assay）・AI 両方から起動可能
- `context: fork` はスキル全体をサブエージェントに渡す用途。今回はオーケストレーター役なので使わない
- スキル本文が「Claude への指示」として機能する

### T2–T5 完了メモ

- T2（仕様設計）+ T4（実装）: `.claude/commands/assay.md` 作成
- T3+T5（評価）: 5ラウンド実施。各ラウンドで valid findings を修正
- 修正済み: EB.1（ゴール確認）, EB.2（基準トレーサビリティ）, EA.1（会話コンテキスト除外の明示）, ED.4（valid result 定義）, GOAL-3（autonomous 起動パス）, EXPERT-3（空ファイル・unreadable principles）, EXPERT-4（allowed-tools 宣言）, EXPERT-1（Boolean 曖昧さ）, GOAL-3（autonomous 空引数の中立メッセージ）

**エスカレーション結果**:
- [EA.1] **reject** — 事実テストで確認：Agent tool のサブエージェントは親の会話履歴に構造的にアクセスできない。evaluators の finding は「instruction が独立性の唯一の機構」という誤った前提に基づいていた。3 evaluators が全員 reject に同意。
- Edge case として記録：評価対象が rules ファイル自体の場合、そのファイルがシステムコンテキストに入っているため完全隔離は不可能。この場合 EA.2（停止・報告）が適用される。assay はこの制約を明示していないが軽微な残課題。

### 現在地

**T6 評価完了（PASS）— ユーザー承認待ち → T7（PR）**

t6-review.md の assay評価が7ラウンドで PASS（valid finding ゼロ）。ユーザーの承認を受けたら T7 に進む。

**確定済みファイル状態（コミット 812bb23）:**
- `.claude/rules/action.md` — Action Principles（A: Goal-oriented, B: Fact-oriented, C: Hypothesis-driven, D: Proposal-oriented）
- `.claude/rules/evaluation.md` — Evaluation Principles（EA: Independence, EB: Goal-derived, EC: Criteria-bound, ED: Quorum, EE: Resolution）
- `.claude/commands/assay.md` — /assay スキル（3独立 evaluator、2/3 quorum、PASS/FAIL レポート）
- `cc-rules-redesign/t6-review.md` — T6 レビュー文書（7ラウンド評価済み、PASS）

**T7 でやること:**
1. steering.md の T6 タスクを [x] にする
2. commit & push
3. PR #10 を作成してマージ（base: main）

---

## Completed

- [x] Collect pain points
- [x] Draft principles → `.claude/rules/principles.md`
- [x] Archive existing `.claude/rules/` files → `cc-rules-redesign/rules-backup/`
- [x] principles.md を 4原則に整理・洗練（名称変更・抽象度統一）
- [x] 生成原則・評価原則の設計（要件合意）
- [x] `generation.md` → `action.md` にリネーム（Action Principles）
- [x] `verification.md` → `evaluation.md` にリネーム（Evaluation Principles）
- [x] 両原則の文言精査・整備
- [x] `assay` スキル名・設計方針の合意

## Session context

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
