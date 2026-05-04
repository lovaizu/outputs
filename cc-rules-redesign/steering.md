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

CCが産出するあらゆる成果物（計画・スキル・コマンド・エージェント設計など）に、生成原則と検証原則が取り込まれた状態にする。

aiya コマンド（/hi, /go, /ty, /gm, /bb）の作成は別PR。

## 活動の定義

| 活動 | 定義 |
|---|---|
| 生成 | AIがユーザーの目的を達成するプロダクト・ドキュメントを生成する |
| 検証 | AIが生成物がユーザーのゴールを達成しているかを有効な方法で評価する |

## Completed

- [x] Collect pain points
- [x] Draft principles → `.claude/rules/principles.md`
- [x] Archive existing `.claude/rules/` files → `cc-rules-redesign/rules-backup/`
- [x] principles.md を 4原則に整理・洗練（名称変更・抽象度統一）
- [x] 生成原則・検証原則の設計（要件合意）

## 生成原則（合意済み・変更なし）

ファイル: `.claude/rules/principles.md` → **`generation.md` にリネーム**

現行4原則をそのまま使用。

## 検証原則（合意済み・未実装）

ファイル: `.claude/rules/verification.md`（新規作成）

1. **Independence** — 評価者は生成プロセスのコンテキストを持たず、互いの評価結果も知らない
2. **Goal-derived** — 評価基準はゴールから導出する。ゴールに紐づかない基準は無効
3. **Criteria-bound** — 評価は事前に明示された基準に対して行う
4. **Quorum** — 独立した3評価者が並列評価し、2/3以上一致した指摘のみ有効
5. **Resolution** — 有効な指摘はすべて解消またはユーザーへエスカレーション。解消されるまでループ

## 評価メカニズムの要件（合意済み・未実装）

1. トリガーが2種類 — ユーザー起動 / AI自律起動
2. メインコンテキストには結論だけ返す — 評価詳細はサブエージェント内に閉じる
3. バイアスなし — 独立したサブエージェントで評価
4. 最新のprinciples を動的に参照する
5. 評価者同士が互いの出力を見ない
6. 発見は原則単位でトレーサブル
7. 改善後もゴールが保たれているか確認
8. 終了条件：すべての指摘が解消されたらループ終了
9. 3評価者・2/3以上一致 = 有効な指摘

## Next tasks（次セッションの起点）

1. [ ] `principles.md` → `generation.md` にリネーム
2. [ ] `verification.md` を作成（検証原則5つ）
3. [ ] **オープン問題**: CCが計画やスキルを作るとき、どのような仕組みで両原則が取り込まれるか設計する
4. [ ] 3の設計を実装する
5. [ ] PR #10 完成

## Session context

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
