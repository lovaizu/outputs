#!/bin/sh
input=$(cat)

# Context window percentage
used_pct=$(echo "$input" | jq -r '.context_window.used_percentage // empty')
if [ -n "$used_pct" ]; then
  c_int=$(printf '%.0f' "$used_pct")
  seg1="C:${c_int}%"
else
  seg1="C:0%"
fi

# Model abbreviation: Opus→O, Sonnet→S, Haiku→H + version
display=$(echo "$input" | jq -r '.model.display_name // .model.id // "unknown"')
abbrev=$(echo "$display" | sed -E \
  -e 's/Claude //' \
  -e 's/Opus ?/O/' \
  -e 's/Sonnet ?/S/' \
  -e 's/Haiku ?/H/')

# Effort level first letter
effort=$(echo "$input" | jq -r '.effort.level // empty')
if [ -n "$effort" ]; then
  e=$(printf '%.1s' "$effort")
  seg2="${abbrev}/${e}"
else
  seg2="${abbrev}"
fi

# Directory basename @ git branch
dir=$(echo "$input" | jq -r '.workspace.current_dir // empty')
dirname=$(basename "${dir:-$(pwd)}")
branch=$(cd "${dir:-.}" 2>/dev/null && git rev-parse --abbrev-ref HEAD 2>/dev/null)
if [ -n "$branch" ]; then
  seg3="${dirname}@${branch}"
else
  seg3="${dirname}"
fi

# Max plan rate limits — append to seg1
five_h_pct=$(echo "$input" | jq -r '.rate_limits.five_hour.used_percentage // empty')
seven_d_pct=$(echo "$input" | jq -r '.rate_limits.seven_day.used_percentage // empty')

if [ -n "$five_h_pct" ]; then
  five_h_int=$(printf '%.0f' "$five_h_pct")
  seg1="${seg1} 5h:${five_h_int}%"
fi
if [ -n "$seven_d_pct" ]; then
  seven_d_int=$(printf '%.0f' "$seven_d_pct")
  seg1="${seg1} 7d:${seven_d_int}%"
fi

printf '%s | %s | %s' "$seg1" "$seg2" "$seg3"