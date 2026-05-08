# Goal

コマンド名を変えて、zz->sv bk->re
reはカレントブランチとコミット履歴を見てどの作業を再開するか判断して、判断できなかった場合のみ聞いて

# Assumptions

- コマンドファイルは `.claude/commands/` に配置
- `zz.md` → `sv.md`、`bk.md` → `re.md` にリネーム（ファイル名とコマンド名両方）
- `re` の自動判断ロジック: カレントブランチ名 + `git log` から作業コンテキストを推定し、一致する steering.md を選択

# Rules

- 1 task = 1 commit

# Tasks

- [ ] #1: rename zz.md to sv.md and update command name/references
- [ ] #2: rename bk.md to re.md, update command name, add auto-detect logic from branch + log
