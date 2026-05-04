# Continuation Plan — principles.md ゼロベース再設計

## Status

principles.md v2 が完了。3ラウンドの検証で CRITICAL/MAJOR 0 を確認。コミット・プッシュ済み。

## ブランチ

`cc-rules-redesign` — PR #10 (https://github.com/lovaizu/outputs/pull/10)

## 完了済み

### ゼロベース再設計
- 8ルール → 5ルール → 4原則に段階的蒸留
- 4原則: Fact-grounded / Goal-anchored / Verified at every stage / Proposed for judgment

### ループホール除去（4ラウンド、v1）
- Round 1: 主観的修飾語除去
- Round 2: "independent" の構造的定義追加
- Round 3: スポーン不可時のフォールバック追加、再帰ガード追加
- Round 4: 正当化パス除去

### ルール準拠の再検証（3ラウンド、v1 → v2）
- Round 1: 14件（CRITICAL 2, MAJOR 7, MINOR 5）— pain points 3,4,6 が未対応、定義の曖昧さ多数
- Round 2: 8件（CRITICAL 1, MAJOR 4, MINOR 3）— self-verify exception がエスケープハッチ再導入
- Round 3: 3件（CRITICAL 0, MAJOR 0, MINOR 3）— 全て cross-rule reinforcement で緩和済み

### v2 の主要変更
- Rule 1: "verified fact" を direct observation で定義、population scope 定義、impossibility threshold 強化
- Rule 2: ideal end-state 導出 + backward planning + 実行前明示を追加
- Rule 3: サブセクション構造化、stage 定義、self-verify 削除、evaluator finding の扱い明確化
- Rule 4: "present every result"（universal）+ "await judgment when tradeoff"（conditional）、narrative flow 無条件化

### 発見されたメタ問題
- F14: ルールが答えを決めている場面で Rule 4 が不要な確認を強制 → v2 で Rule 4 scope を修正

### PR #10 レビュー対応
- Comment A・B (principles.md): 対応済み
- Comment C (wf-rev SKILL.md): G4 誤適用2件 REVERT 済み

### コミット履歴
- `8d7a270` refactor: zero-base redesign from 8 rules to 5
- `6f9776a` refactor: distill to 4 absolute principles
- `365c298` fix: close 5 loopholes found by expert review + adversarial sim
- `c5abd0e` fix: require separate agent for BOTH simulation and review
- `27ed85f` fix: add spawning fallback and clarify recursion guard
- `5928025` fix: remove justification escape hatch from Rule 3
- `a0506f7` fix: resolve 14 findings from rule-compliant verification ← 最新

## 現在のファイル

- `.claude/rules/principles.md` — v2 最終版（4原則、~35行）
- `.claude/skills/wf-rev/SKILL.md` — PR レビュー対応済み

## 残作業

1. **下流タスク（次フェーズ）**: aiya-jam 作成 → AIYA コマンド作成 → ACC 作成

## 設計判断の記録

- **コアルール = 短い絶対原則**: 「常にこうしろ」。具体例・手順はチェックリスト（aiya-jam）側
- **無限再帰の解決**: "Evaluators are not required to spawn their own evaluators"
- **偽陽性の処理**: evaluator findings は "inputs requiring resolution" — dismissed 不可だが verified 扱いにもしない
- **検証の構造的分離**: producer ≠ simulation agent ≠ review agent（全て別エージェント）
- **比例原則**: "could affect whether or how the goal is achieved" で stage をフィルタ。self-verify は許可しない
- **Rule 4 scope**: 提示は常に行う。判断要求は tradeoff/ambiguity 時のみ
- **Narrative flow**: Rule 4 のゲート外に配置し無条件適用
