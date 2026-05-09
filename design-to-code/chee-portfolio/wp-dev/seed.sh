#!/usr/bin/env bash
set -euo pipefail

CLI="docker compose run --rm cli wp --allow-root"
SEED_DIR=/var/www/html/seed
SEED_JSON="$(dirname "$0")/seed"

echo "=== Importing media ==="

# Profile photo
IMG_PROFILE=$($CLI media import "$SEED_DIR/images/profile.webp" --title="プロフィール写真" --porcelain)
$CLI option update chee_profile_photo_id "$IMG_PROFILE"
echo "  Profile photo ID: $IMG_PROFILE"

# Works images — collect all unique filenames from thumbnail + mockup_image
declare -A IMG_MAP
while IFS= read -r filename; do
  [ -z "$filename" ] && continue
  id=$($CLI media import "$SEED_DIR/images/$filename" --title="$filename" --porcelain)
  IMG_MAP["$filename"]="$id"
  echo "  Imported $filename → ID $id"
done < <(jq -r '.[].thumbnail, .[].mockup_image | select(. != "")' "$SEED_JSON/works.json" | sort -u)

# Voice photos
declare -A VOICE_IMG_MAP
while IFS= read -r filename; do
  [ -z "$filename" ] && continue
  if [ -z "${VOICE_IMG_MAP[$filename]+x}" ]; then
    id=$($CLI media import "$SEED_DIR/images/$filename" --title="$filename" --porcelain)
    VOICE_IMG_MAP["$filename"]="$id"
    echo "  Imported $filename → ID $id"
  fi
done < <(jq -r '.[].voice_photo | select(. != "")' "$SEED_JSON/voice.json" | sort -u)

echo "=== Creating Works posts ==="

count=$(jq 'length' "$SEED_JSON/works.json")
for ((i=0; i<count; i++)); do
  post_title=$(jq -r ".[$i].post_title" "$SEED_JSON/works.json")
  post_date=$(jq -r ".[$i].post_date" "$SEED_JSON/works.json")
  post_content=$(jq -r ".[$i].post_content" "$SEED_JSON/works.json")
  client_name=$(jq -r ".[$i].client_name" "$SEED_JSON/works.json")
  category_label=$(jq -r ".[$i].category_label" "$SEED_JSON/works.json")
  thumbnail=$(jq -r ".[$i].thumbnail" "$SEED_JSON/works.json")
  mockup_image=$(jq -r ".[$i].mockup_image" "$SEED_JSON/works.json")
  fv_featured=$(jq -r ".[$i].fv_featured" "$SEED_JSON/works.json")
  fv_order=$(jq -r ".[$i].fv_order" "$SEED_JSON/works.json")

  PID=$($CLI post create \
    --post_type=works \
    --post_title="$post_title" \
    --post_status=publish \
    --post_date="$post_date" \
    --porcelain)

  $CLI post update "$PID" --post_content="$post_content"
  [ -n "$client_name" ]    && $CLI post meta update "$PID" client_name "$client_name"
  [ -n "$category_label" ] && $CLI post meta update "$PID" category_label "$category_label"
  [ -n "$thumbnail" ]      && $CLI post meta update "$PID" thumbnail "${IMG_MAP[$thumbnail]}"
  [ -n "$mockup_image" ]   && $CLI post meta update "$PID" mockup_image "${IMG_MAP[$mockup_image]}"
  $CLI post meta update "$PID" fv_featured "$fv_featured"
  $CLI post meta update "$PID" fv_order "$fv_order"

  categories=$(jq -r ".[$i].categories[]" "$SEED_JSON/works.json" | tr '\n' ' ')
  # shellcheck disable=SC2086
  $CLI post term set "$PID" works-category $categories

  echo "  Works #$PID: $post_title"
done

echo "=== Creating Voice posts ==="

# Create in reverse order so posts display V1→V4 in DESC date order
count=$(jq 'length' "$SEED_JSON/voice.json")
for ((i=count-1; i>=0; i--)); do
  post_title=$(jq -r ".[$i].post_title" "$SEED_JSON/voice.json")
  post_date=$(jq -r ".[$i].post_date" "$SEED_JSON/voice.json")
  voice_photo=$(jq -r ".[$i].voice_photo" "$SEED_JSON/voice.json")
  voice_role=$(jq -r ".[$i].voice_role" "$SEED_JSON/voice.json")
  voice_name=$(jq -r ".[$i].voice_name" "$SEED_JSON/voice.json")
  voice_catchphrase=$(jq -r ".[$i].voice_catchphrase" "$SEED_JSON/voice.json")
  voice_body=$(jq -r ".[$i].voice_body" "$SEED_JSON/voice.json")

  PID=$($CLI post create \
    --post_type=voice \
    --post_title="$post_title" \
    --post_status=publish \
    --post_date="$post_date" \
    --porcelain)

  [ -n "$voice_photo" ] && $CLI post meta update "$PID" voice_photo "${VOICE_IMG_MAP[$voice_photo]}"
  $CLI post meta update "$PID" voice_role "$voice_role"
  $CLI post meta update "$PID" voice_name "$voice_name"
  $CLI post meta update "$PID" voice_catchphrase "$voice_catchphrase"
  $CLI post meta update "$PID" voice_body "$voice_body"

  echo "  Voice #$PID: $post_title"
done

echo "=== Done ==="
$CLI post list --post_type=works --fields=ID,post_title,post_status
$CLI post list --post_type=voice --fields=ID,post_title,post_status
