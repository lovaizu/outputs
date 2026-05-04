# T6 Review — assay スキル

## 1. 成果物

`.claude/commands/assay.md`

**目的**: Action Principles・Evaluation Principles への準拠を評価し、違反を原則番号付きで報告する。

**起動方法**:
- ユーザー: `/assay <artifact text or file path>`
- AI エージェント: `$ARGUMENTS` にテキストまたはパスを渡す

**動作フロー**:
1. 引数からターゲットを取得（テキスト or ファイルパス）
2. `action.md` と `evaluation.md` を読み込む
3. 3つの独立サブエージェントを並列起動（各 evaluator は会話コンテキストなし）
4. 各 evaluator が criteria を先に宣言 → 評価 → 違反を principle 番号付きで報告
5. 2/3 quorum で valid finding、1/3 は informational として区別
6. PASS/FAIL verdict + valid findings + informational の3層レポートを返す

---

## 2. 動作サンプル

**入力（原則違反を含む架空のワークフロー）**:

```
Workflow: Deploy to production
Step 1: Assume the tests passed based on yesterday's run.
Step 2: Deploy immediately without checking current branch state.
Step 3: If something breaks, roll back later.
```

**出力イメージ**:

```
## Assay Report

**Verdict**: FAIL

### Valid Findings
- [A.1] "Assume the tests passed based on yesterday's run" — Acting on an unverified
  assumption rather than a directly observed fact. → Improvement: Run the test suite
  and confirm the result before proceeding to deploy.
- [A.2] "Deploy immediately without checking current branch state" — The relevant
  population (branch state, pending changes) was not fully verified before acting.
  → Improvement: Run git status and review all staged changes before deploying.

### Informational (1 evaluator only — not actionable)
- [B.2] No end-state was derived from the goal before planning the steps — flagged
  by evaluator 2 only.
```

---

## 3. 評価ループで発見・解消した問題

| Finding | 原則 | 解消内容 |
|---|---|---|
| ゴール確認の欠如 | EB.1 | evaluation goal を冒頭に明示宣言 |
| 基準のトレーサビリティ不足 | EB.2 | evaluator に criteria を先に書かせる instruction を追加 |
| valid result の定義が曖昧 | ED.4 | 無効応答の具体例（空応答・拒否・criteria なし等）を明記 |
| autonomous 起動時の空引数処理 | — | 空引数の場合は中立なガイダンスメッセージを返すよう明記 |
| allowed-tools 未宣言 | — | frontmatter に `Read`, `Agent` を追加 |
| quorum の boolean 説明が曖昧 | ED | boolean 説明を除去、具体的な数字（2/3）のみに統一 |

**エスカレーション（EA.1）の結果**:

evaluator が「instruction が独立性の唯一の機構」として EA.1 違反を指摘。事実確認の結果、Agent tool のサブエージェントは親の会話履歴に構造的にアクセスできないことを確認。finding は誤った前提に基づいていたため **reject**（3 evaluators 全員合意）。

---

## 4. 許容している制約（トレードオフなし・設計上の限界として明示済み）

**システムコンテキストに自動ロードされるファイルを評価する場合**:

CLAUDE.md や rules ファイルなど、評価対象がシステムコンテキストに自動で読み込まれるファイルの場合、evaluator の完全な構造的隔離は実現不可能（target content が subagent のコンテキストに入る）。

- 別ディレクトリ構成で回避する方法もあるが、作業負荷が高い
- この制約は assay.md の Step 3 末尾に **Known constraint** として明示済み
- evaluator はこの制約を認識しつつ評価を継続する設計

---

## 承認依頼

上記内容を確認のうえ、承認をお願いします。承認後、T7（PR作成）に進みます。
