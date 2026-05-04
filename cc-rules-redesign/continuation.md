# Continuation — 番号付き箇条書きへの変換

## ブランチ・PR

- Branch: `cc-rules-redesign`
- Worktree: `/Users/kiyo/work/lovaizu/outputs/.claude/worktrees/rules/`
- PR: #10

## 現在のファイル状態

両ファイルとも最良の検証済み版に戻した状態（コミット `edab8df`）。

- `.claude/rules/generation.md` → `4187463` の内容（前セッションで洗練済みの散文版）
- `.claude/rules/verification.md` → `3dcc50b` の内容（9ラウンドのループホールレビュー完了版）

## 次のタスク

**番号付き箇条書きへの変換**（generation.md・verification.md 両方）

### ルール

- 各原則の本文を `1.1`、`1.2`... 形式の番号付き項目に分割する
- 各項目は完全な文（句読点あり）にする
- em dash（—）不使用、コロン（:）不使用
- 条件付き文は "If X, Y." 形式
- **内容を一切変えない** — 変換前後で意味が等価であることを一文ずつ確認する

### 前回の失敗パターン（避けること）

- "The user's stated goal is the fixed point." → 消えた（重大な劣化）
- "not assumptions" → "only" に弱体化
- "Present every result to the user" → 消えた
- 評価者の役割説明 "(can they achieve their goal?)" → 消えた
- 3.3 に "or escalating" を追記してAIに逃げ道を与えた

### 手順

1. generation.md を変換（内容照合しながら）
2. ユーザーにPRで確認してもらう前に、元の散文と変換後を並べて差分レビュー
3. verification.md を変換
4. 両ファイルをコミット・プッシュ

## その先のタスク（steering.md より）

- オープン問題: CCが計画やスキルを作るとき、両原則が取り込まれる仕組みの設計・実装
- PR #10 完成
