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
- [B.1] "Assume the tests passed based on yesterday's run" — Acting on an unverified
  assumption rather than a directly observed fact. → Improvement: Run the test suite
  and confirm the result before proceeding to deploy.
- [B.2] "Deploy immediately without checking current branch state" — The relevant
  population (branch state, pending changes) was not fully verified before acting.
  → Improvement: Run git status and review all staged changes before deploying.

### Informational (1 evaluator only — not actionable)
- [A.2] No end-state was derived from the goal before planning the steps — flagged
  by evaluator 2 only.
```

---

## 3. 評価ループで発見・解消した問題

**事前定義した仮説と検証方法**: assay.md は「原則違反を含む任意の成果物に対して正しい principle コード付きの findings を返す」という仮説のもとで設計した。検証方法は C.4 に従い2つの独立したパスで実施した: (1) goal alignment — assay.md のゴールを達成しているか、(2) quality — ベストプラクティスに従っているか。

評価は assay.md の evaluator instruction（「First, write out all evaluation criteria you will use... These are the only valid criteria」）に従い、各 evaluator が評価開始前に criteria を宣言した状態で実施した。

### Pass 1: Goal alignment（成果物がゴールを達成しているか）

| Finding | 原則 | 解消内容 |
|---|---|---|
| ゴール確認の欠如 | EB.1 | evaluation goal を冒頭に明示宣言 |
| 基準のトレーサビリティ不足 | EB.2 | evaluator に criteria を先に書かせる instruction を追加 |
| autonomous 起動時の空引数処理 | — | 空引数の場合は中立なガイダンスメッセージを返すよう明記 |

Informational findings（sub-quorum）: なし

### Pass 2: Quality（ベストプラクティスに従っているか）

| Finding | 原則 | 解消内容 |
|---|---|---|
| valid result の定義が曖昧 | ED.4 | 無効応答の具体例（空応答・拒否・criteria なし等）を明記 |
| allowed-tools 未宣言 | — | frontmatter に `Read`, `Agent` を追加 |
| quorum の boolean 説明が曖昧 | ED | boolean 説明を除去、具体的な数字（2/3）のみに統一 |

Informational findings（sub-quorum）: なし

**エスカレーション（EA.1）の結果**:

evaluator が「instruction が独立性の唯一の機構」として EA.1 違反を指摘。assay.md 設計前に Agent tool を使って動作確認を実施し、サブエージェントが親の会話履歴を受け取らないことを確認した。公式の一次ドキュメントは未確認であるため、これは動作確認による知見として扱う。finding の前提（instruction のみが独立性の機構）は誤っており、finding は無効と判断した。**User direction: reject**（ユーザーが明示）。

---

## 4. 許容している制約

**Issue**: assay がシステムコンテキストに自動ロードされるファイル（rules ファイル等）を評価する場合、評価者の完全な独立性が保てない。評価原則 EA.2 は独立性が確保できない場合に停止を要求している。停止するか、開示して続行するか。

**Conclusion**: 開示して続行する（現行設計を維持）。

**Rationale**: EA.2 の目的は「評価されない成果物を届けない」ことにある。評価者が target content をコンテキストに持っていても評価機能は失われない。停止を選ぶと assay 自体が依拠する rules ファイルを評価できなくなり、このPRの主要ユースケースが失われる。

**Evidence**: assay.md の設計・実装・評価を通じて合計5ラウンドの評価を実施した。この制約下でも evaluator は実質的な findings を返した（valid finding 6件解消、escalation 1件）。assay.md は Step 2 で `action.md` と `evaluation.md` を読み込む設計のため、停止を適用するとこの2ファイルが評価対象外となる。この制約は assay.md Step 3 末尾に Evaluation Principle A — Independence 2（以下 EA.2）への対応として Known Constraint として明示済み（assay.md line 49 の文言: "If the evaluation target is a file that is automatically loaded into the system context (e.g. project instructions, rules files), complete structural isolation of evaluators is architecturally impossible — the target content will be present in each subagent's context regardless of the prompt. This is an accepted limitation. Evaluators should note this constraint but must proceed and report findings as usual."）。

**User direction**: accept（ユーザーが明示）

---

## 承認依頼

上記内容を確認のうえ、承認をお願いします。承認後、T7（PR作成）に進みます。
