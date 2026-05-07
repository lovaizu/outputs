#!/usr/bin/env bash
set -euo pipefail

CLI="docker compose run --rm cli wp --allow-root"
THEME_IMG=/var/www/html/wp-content/themes/chee-portfolio/assets/images

echo "=== Importing media ==="
IMG1=$($CLI media import "$THEME_IMG/sec01-fv.jpg" --title="FV mockup 1" --porcelain)
IMG2=$($CLI media import "$THEME_IMG/sec02-works.jpg" --title="FV mockup 2" --porcelain)
IMG3=$($CLI media import "$THEME_IMG/sec04-Service.jpg" --title="Works thumbnail" --porcelain)
IMG4=$($CLI media import "$THEME_IMG/sec03-Voice.jpg" --title="Voice photo" --porcelain)
echo "  Media IDs: $IMG1 $IMG2 $IMG3 $IMG4"

echo "=== Creating Works posts ==="
W1=$($CLI post create --post_type=works --post_title="ArtMake LP" --post_status=publish --porcelain)
$CLI post meta update "$W1" client_name "GLOW Beauty"
$CLI post meta update "$W1" category_label "眉毛アートメイク"
$CLI post meta update "$W1" thumbnail "$IMG3"
$CLI post meta update "$W1" mockup_image "$IMG1"
$CLI post meta update "$W1" fv_featured "1"
$CLI post meta update "$W1" fv_order "1"
$CLI post term set "$W1" works-category lp-design
echo "  Works #$W1 created"

W2=$($CLI post create --post_type=works --post_title="PosiJob HP" --post_status=publish --porcelain)
$CLI post meta update "$W2" client_name "PosiJob Inc."
$CLI post meta update "$W2" category_label "求人サイト"
$CLI post meta update "$W2" thumbnail "$IMG3"
$CLI post meta update "$W2" mockup_image "$IMG2"
$CLI post meta update "$W2" fv_featured "1"
$CLI post meta update "$W2" fv_order "2"
$CLI post term set "$W2" works-category hp-design
echo "  Works #$W2 created"

W3=$($CLI post create --post_type=works --post_title="Ad banner set" --post_status=publish --porcelain)
$CLI post meta update "$W3" client_name "ABC Corp"
$CLI post meta update "$W3" category_label "広告バナーセット"
$CLI post meta update "$W3" thumbnail "$IMG3"
$CLI post meta update "$W3" mockup_image "$IMG1"
$CLI post meta update "$W3" fv_featured "0"
$CLI post meta update "$W3" fv_order "0"
$CLI post term set "$W3" works-category ad-banner
echo "  Works #$W3 created"

echo "=== Creating Voice posts ==="
V1=$($CLI post create --post_type=voice --post_title="Voice 1" --post_status=publish --porcelain)
$CLI post meta update "$V1" voice_photo "$IMG4"
$CLI post meta update "$V1" voice_role "セールスライター"
$CLI post meta update "$V1" voice_name "Tさん"
$CLI post meta update "$V1" voice_body "デザインのクオリティが高く、修正対応も迅速でした。おかげさまでCVRが大幅に改善しました。"
$CLI post meta update "$V1" voice_catchphrase "売上が2倍に！"
echo "  Voice #$V1 created"

V2=$($CLI post create --post_type=voice --post_title="Voice 2" --post_status=publish --porcelain)
$CLI post meta update "$V2" voice_photo "$IMG4"
$CLI post meta update "$V2" voice_role "美容サロンオーナー"
$CLI post meta update "$V2" voice_name "Mさん"
$CLI post meta update "$V2" voice_body "ブランドイメージにぴったりのサイトを作っていただきました。集客にもつながっています。"
$CLI post meta update "$V2" voice_catchphrase "理想通りのデザイン"
echo "  Voice #$V2 created"

V3=$($CLI post create --post_type=voice --post_title="Voice 3" --post_status=publish --porcelain)
$CLI post meta update "$V3" voice_photo "$IMG4"
$CLI post meta update "$V3" voice_role "マーケティング担当"
$CLI post meta update "$V3" voice_name "Kさん"
$CLI post meta update "$V3" voice_body "バナー広告のデザインをお願いしました。CTRが改善し、広告費の効率が上がりました。"
$CLI post meta update "$V3" voice_catchphrase "広告効果が劇的改善"
echo "  Voice #$V3 created"

V4=$($CLI post create --post_type=voice --post_title="Voice 4" --post_status=publish --porcelain)
$CLI post meta update "$V4" voice_photo "$IMG4"
$CLI post meta update "$V4" voice_role "フリーランスコーチ"
$CLI post meta update "$V4" voice_name "Sさん"
$CLI post meta update "$V4" voice_body "LP制作からバナーまでワンストップで対応いただけるので助かっています。リピート確定です。"
$CLI post meta update "$V4" voice_catchphrase "ワンストップで安心"
echo "  Voice #$V4 created"

echo "=== Done ==="
$CLI post list --post_type=works --fields=ID,post_title,post_status
$CLI post list --post_type=voice --fields=ID,post_title,post_status
