#!/usr/bin/env bash
set -euo pipefail

CLI="docker compose run --rm cli wp --allow-root"
THEME_IMG=/var/www/html/wp-content/themes/chee-portfolio/assets/images

echo "=== Importing media ==="
IMG_ARTMAKE=$($CLI media import "$THEME_IMG/下層ページ_LP詳細_アートメイク.png" --title="ArtMake LP mockup" --porcelain)
IMG_POSIJOB=$($CLI media import "$THEME_IMG/下層ページ_LP詳細_ポジジョブ.png" --title="PosiJob LP mockup" --porcelain)
IMG_FV=$($CLI media import "$THEME_IMG/sec01-fv.jpg" --title="FV carousel" --porcelain)
echo "  Media IDs: ArtMake=$IMG_ARTMAKE PosiJob=$IMG_POSIJOB FV=$IMG_FV"

echo "=== Creating Works posts ==="
# W1: ArtMake LP — created first (older date) so it appears second in DESC order
W1=$($CLI post create --post_type=works --post_title="[眉毛アートメイクLP] TWC ART MAKE STUDIO様" --post_status=publish --post_date="2024-02-01 00:00:00" --porcelain)
$CLI post meta update "$W1" client_name "TWC ART MAKE STUDIO"
$CLI post meta update "$W1" mockup_image "$IMG_ARTMAKE"
$CLI post meta update "$W1" fv_featured "1"
$CLI post meta update "$W1" fv_order "1"
$CLI post term set "$W1" works-category lp-design ad-operation ad-banner
$CLI post update "$W1" --post_content="神戸 三宮でアートメイクを施術しているTWC ART MAKE STUDIOのLPデザインとStudio実装、広告バナー制作、Meta広告運用を行いました。"
echo "  Works #$W1 created (ArtMake)"

# W2: PosiJob LP — older date, appears second
W2=$($CLI post create --post_type=works --post_title="[転職エージェントLP] ポジジョブ様" --post_status=publish --post_date="2024-01-01 00:00:00" --porcelain)
$CLI post meta update "$W2" client_name "ポジジョブ"
$CLI post meta update "$W2" mockup_image "$IMG_POSIJOB"
$CLI post meta update "$W2" fv_featured "1"
$CLI post meta update "$W2" fv_order "2"
$CLI post term set "$W2" works-category lp-design direction
$CLI post update "$W2" --post_content="転職者の働く本質に寄り添うサービスを展開されているポジジョブ様のリニューアルを致しました。原稿から全てを差し替えて対応致しました。"
echo "  Works #$W2 created (PosiJob)"

echo "=== Creating Voice posts ==="
# Voice posts are displayed DESC by date — create in reverse order so V1=01, V2=02 etc.
# voice_photo is intentionally omitted: avatar illustrations must be provided by the client as separate files.

V4=$($CLI post create --post_type=voice --post_title="Voice 1 — WEBディレクター Y.Tさん" --post_status=publish --post_date="2024-04-01 00:00:00" --porcelain)
$CLI post meta update "$V4" voice_role "WEBディレクター"
$CLI post meta update "$V4" voice_name "Y.Tさん"
$CLI post meta update "$V4" voice_catchphrase "安心して相談できるデザインパートナー"
$CLI post meta update "$V4" voice_body "コミュニケーションが非常にスムーズで、意図や不明点を丁寧に確認しながら進めてくださるため、安心してお任せできます。デザインの方向性に迷う場面では複数案を提示していただけるなど提案力もあり、毎回想像以上のアウトプットをいただいています。

進行中にも改善提案をいただけるため、当初想定していた以上のクオリティで仕上がることが多く、スピード感と品質の両面で満足しています。また、判断に迷う場合も選択肢を提示していただけるため、納得感を持って意思決定できる点も助かっています。

自社にデザイナーがいない制作会社や個人事業者で、安心して任せられるデザインパートナーを探している方におすすめです。"
echo "  Voice #$V4 created (01 Y.Tさん)"

V3=$($CLI post create --post_type=voice --post_title="Voice 2 — 小学校PTA会長 Fさん" --post_status=publish --post_date="2024-03-01 00:00:00" --porcelain)
$CLI post meta update "$V3" voice_role "小学校PTA会長"
$CLI post meta update "$V3" voice_name "Fさん"
$CLI post meta update "$V3" voice_catchphrase "こんなところ、最初からお願いすればよかった！"
$CLI post meta update "$V3" voice_body "PTA主催のイベントも活用できるものを提案してもらい、想像以上のクオリティでHPを仕上げていただきました。何よりありがたかったのは、信頼できる担当をしてくださったこと。

いつまでに、どういう内容で、どんな希望を実現するかを明確にしてくれたので、スムーズに進めることができました。

親身になってくださったおかげで無事に公開することができました。"
echo "  Voice #$V3 created (02 Fさん)"

V2=$($CLI post create --post_type=voice --post_title="Voice 3 — スピリチュアルコンサルタント Nさん" --post_status=publish --post_date="2024-02-15 00:00:00" --porcelain)
$CLI post meta update "$V2" voice_role "スピリチュアルコンサルタント"
$CLI post meta update "$V2" voice_name "Nさん"
$CLI post meta update "$V2" voice_catchphrase "ニッチなジャンルでも寄り添う、ターゲットにかなりLP"
$CLI post meta update "$V2" voice_body "コンサルとして活動を始めたばかりのしっかりとしたLPを作りたいと思い、商品ディスプレイや事業のニッチなジャンルだったため、デザイナーさんに断られることも不安でした。

伊藤さんにお願いしたところ、最初から私の状況をしっかりと把握して提案してくれたので、私の商品のターゲットにピッタリ合うLPが作成できました。"
echo "  Voice #$V2 created (03 Nさん)"

V1=$($CLI post create --post_type=voice --post_title="Voice 4 — セールスライター Yさん" --post_status=publish --post_date="2024-01-15 00:00:00" --porcelain)
$CLI post meta update "$V1" voice_role "セールスライター"
$CLI post meta update "$V1" voice_name "Yさん"
$CLI post meta update "$V1" voice_catchphrase "理想通りのLPデザイン！"
$CLI post meta update "$V1" voice_body "以前にLP制作を依頼したことがありデザインとコーディングのクオリティの高さを確認済みでしたので、クライアントのインスタグラムの投稿を元に、クライアントの表現したい世界観を確認。

フォースビューインタビューに同席し、クライアントの表現したい世界観を確認。現地のイラストを参考にしていただいたデザインからブランドカラーを中心に配色を決定。

ターゲットに刺さるLPを的確に仕上げることができました。スムーズな仕事ができました。"
echo "  Voice #$V1 created (04 Yさん)"

echo "=== Done ==="
$CLI post list --post_type=works --fields=ID,post_title,post_status
$CLI post list --post_type=voice --fields=ID,post_title,post_status
