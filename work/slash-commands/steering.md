# Goal

カスタムスラッシュコマンドをいくつか作って
・作業を開始する：go
　・作業指示をヒアリングする
　・作業ディレクトリを作る
　・作業ディレクトリにステアリング（steering.md）を作り要件、前提、ルールを追記する
　・1タスク＝1コミットの単位でタスク分割してステアリングにタスクリストを追記する
　・以降、作業はステアリングのタスクリストに従って進める
　・作業実態に合わせてステアリングを随時更新する
・作業を中断する：zz
　・ステアリングを最新化する
　・変更差分や未追跡ファイルがないクリーンな状態にする
・作業を再開する：bk
　・ステアリングとコミット履歴を確認する
　・ステアリングのタスクリストに従って進める

# Assumptions

- commands は `.claude/commands/` に配置（プロジェクトスコープ）
- グローバル化は `~/.claude/commands/` へのコピーでユーザーが行う
- action.md のみ適用（evaluation.md は今セッションでは対象外）
- branch: `worktree-commands`

# Rules

- 1 task = 1 commit
- action.md の4原則（Goal / Fact / Hypothesis / Proposal）に従う

# Tasks

<!-- paused: 2026-05-07 — next: #4 verify artifact and open PR -->

- [x] #1: create /go, /zz, /bk command files in .claude/commands/
- [x] #2: refine commands based on prompt engineering review (critical + major fixes)
- [x] #3: rebase onto latest origin/main
- [ ] #4: verify artifact against goal alignment and quality (action.md C), then open PR to main

# Verification criteria for #4

Goal alignment:
- /go が「ヒアリング → ディレクトリ → steering.md → タスク分割 → 実行」の流れを指示しているか
- /zz が「steering最新化 → クリーン状態」を保証しているか
- /bk が「steering + コミット確認 → 次タスク実行」を指示しているか

Quality:
- 命令が曖昧でなく、LLM が一意に解釈できるか
- エッジケース（未追跡ファイル、複数 steering.md 等）がカバーされているか
