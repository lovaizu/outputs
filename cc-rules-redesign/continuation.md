# Continuation

## ブランチ・PR

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
- PR: #10

## 現在のファイル状態

最新コミット: `d840962` — A/B/C見出し + 番号付きリスト形式に変換済み。

- `.claude/rules/generation.md` — A/B/C/D見出し、各原則の文を番号付きリストに分割
- `.claude/rules/verification.md` — A/B/C/D/E見出し、各原則の文を番号付きリストに分割

## 次のタスク：項目の切れ目を意図単位に直す

### 問題

今は「文の区切り = 項目の区切り」になっている。正しくは「意図の区切り = 項目の区切り」。

例（generation.md A原則）：
```
現在:
3. Verify the complete population relevant to the goal, never sample.
4. The population is everything the goal's success depends on.
5. If uncertain, state the assumed scope and confirm with the user.

あるべき姿（3〜5は「母集団の定義と扱い」という一つの意図）:
3. Verify the complete population relevant to the goal, never sample. The population is everything the goal's success depends on; if uncertain, state the assumed scope and confirm with the user.
```

### ルール

- 意図・論理的まとまりを単位として項目を組み直す
- 文言は一切変えない
- 変更前後で意味が等価であることを確認する

### 手順

1. generation.md を意図単位で組み直す
2. verification.md を意図単位で組み直す
3. コミット・プッシュ（確認不要）

## その先のタスク（steering.md より）

- オープン問題: CCが計画やスキルを作るとき、両原則が取り込まれる仕組みの設計・実装
- PR #10 完成
