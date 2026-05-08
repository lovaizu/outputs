# Goal

assayのベースになってるevaluation.mdがミスリードするので削除しましょう。evaluationのに内容はsmithに入ってますよね？プラグインのノウハウをチェックするスキルです。まだないけど設計が作業ディレクトリにあります。

# Assumptions

- `.claude/rules/evaluation.md` を削除する
- `assay.md` は evaluation.md を参照しているので、action.md のみを評価基準とするよう更新する
- `CLAUDE.md` も evaluation.md への言及を削除する
- smith-design.md はそのまま残す（smithはまだ実装されていない）

# Rules

- evaluation.md 削除後、assay は action.md のみを評価基準とする

# Tasks

- [x] #1: delete `.claude/rules/evaluation.md`
- [x] #2: update `assay.md` to remove evaluation.md references
- [x] #3: update `CLAUDE.md` to remove evaluation.md reference
