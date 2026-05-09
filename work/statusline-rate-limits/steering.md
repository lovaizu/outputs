# Goal

ステータスラインにmaxプランの5時間制限、週間制限の%を追加して

# Assumptions

- `~/.claude/statusline.sh` が既存のステータスライン実装
- `rate_limits.five_hour.used_percentage` と `rate_limits.seven_day.used_percentage` がMaxプランで利用可能
- Maxプラン以外ではフィールドが存在しないため、存在する場合のみ表示する

# Rules

- `~/.claude/statusline.sh` のみ変更する（リポジトリ外ファイルだがstatusline設定は専用エージェント経由で実施）
- Maxプランのフィールドがない場合は現行の出力と同じになること

# Tasks

- [x] #1: update ~/.claude/statusline.sh to add 5h and 7d rate limit percentages
