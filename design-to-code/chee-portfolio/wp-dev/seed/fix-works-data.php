<?php
/**
 * Fix works post data to match design comp:
 * - post_title: project name only (not [label] client_name様)
 * - post_excerpt: description text (was in post_content)
 * - post_content: structured block markup with ◆ sections
 * - client_name: add 様 suffix
 */

require '/var/www/html/wp-load.php';

$works = get_posts([
    'post_type'      => 'works',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

foreach ($works as $work) {
    $old_title    = $work->post_title;
    $old_content  = $work->post_content;
    $cat_label    = get_post_meta($work->ID, 'category_label', true);
    $client       = get_post_meta($work->ID, 'client_name', true);

    // Extract project name from old title: "[project_name] client_name様" → "project_name"
    if (preg_match('/^\[(.+?)\]\s*/', $old_title, $m)) {
        $new_title = $m[1];
    } else {
        $new_title = $old_title;
    }

    // Add 様 to client_name if not empty and not already ending with 様
    if ($client && !str_ends_with($client, '様') && !str_ends_with($client, 'クライアント')) {
        $new_client = $client . '様';
        update_post_meta($work->ID, 'client_name', $new_client);
        echo "  client_name: {$client} → {$new_client}\n";
    }

    // Move old post_content to excerpt
    $excerpt = $old_content;

    // Build structured post_content with block markup
    $content = build_structured_content($work->ID, $cat_label);

    wp_update_post([
        'ID'           => $work->ID,
        'post_title'   => $new_title,
        'post_excerpt' => $excerpt,
        'post_content' => $content,
    ]);

    echo "#{$work->ID}: \"{$old_title}\" → \"{$new_title}\"\n";
}

echo "\nDone. " . count($works) . " posts updated.\n";

function build_structured_content(int $post_id, string $cat_label): string {
    $data = get_content_data($post_id);

    $blocks = [];

    foreach ($data as $section) {
        $blocks[] = '<!-- wp:heading {"level":3,"className":"works-section-title"} -->';
        $blocks[] = '<h3 class="wp-block-heading works-section-title">' . esc_html($section['title']) . '</h3>';
        $blocks[] = '<!-- /wp:heading -->';
        $blocks[] = '';

        foreach ((array) $section['body'] as $line) {
            $blocks[] = '<!-- wp:paragraph -->';
            $blocks[] = '<p>' . esc_html($line) . '</p>';
            $blocks[] = '<!-- /wp:paragraph -->';
            $blocks[] = '';
        }
    }

    return implode("\n", $blocks);
}

function get_content_data(int $post_id): array {
    $cat_label = get_post_meta($post_id, 'category_label', true);
    $client    = get_post_meta($post_id, 'client_name', true);

    // Content mapped by category_label for known detail pages
    $content_map = [
        '眉毛アートメイク' => [
            ['title' => '目的', 'body' => ['公式LINEの登録']],
            ['title' => '担当範囲', 'body' => ['原稿リライト・デザイン・実装・広告バナー制作・広告運用・改善レポート作成']],
            ['title' => '制作期間', 'body' => ['構成から実装まで約１ヶ月']],
            ['title' => '制作のポイント', 'body' => [
                '・競合他社のHPやSNS、Googleマップの口コミをリサーチ',
                '・ターゲットから絞り込んだペルソナの詳細設計',
                '・ファーストビュー',
                'キャッチコピーと同時に「眉毛」に目が行くように配置。「５分時短」「駅徒歩８分」「Googleマップの口コミ」など具体的な数値をいれて安心材料を提示し、スクロールを誘う設計。化粧する時間は朝が多いので、光を取り入れて朝の雰囲気を演出。',
                '・ボタンの上に表示したマイクロコピーに割引クーポンを伝えて予約のハードルを下げています。',
                '・文字が多い部分はタイトルの文字色の変更、本文を改行したり読みやすく工夫',
                '・「よくあるご質問」最下部の質問に、申込方法のQAを加え、ボタンへの誘導しやすい構成にしました。',
            ]],
            ['title' => '制作ツール', 'body' => ['Figma, Photoshop, Studio（実装環境）']],
            ['title' => '広告運用', 'body' => [
                'Meta広告, 広告バナー（静止画）を2パターン配信しました。',
                '運用結果と改善案を作成しました。',
            ]],
        ],
        '転職エージェント' => [
            ['title' => '目的', 'body' => ['無料相談の申込']],
            ['title' => '担当範囲', 'body' => ['ディレクション・デザイン']],
            ['title' => '制作期間', 'body' => ['デザイン２週間']],
            ['title' => '制作のポイント', 'body' => [
                '・運用されているInstagramの投稿をリサーチ。',
                '・ターゲットオーディエンスに関連し、クライアントの表現したい世界観を確認',
                '・既存のロゴとの統一感を持たせてデザインしつつも、ユーザーに信頼感を持ってもらえるようなデザインに。',
                '・ブランドカラーの黄色を中心に配色を決定。',
                '・求職者が知りたいこと・サービスの特長をわかりやすくまとめた構成にしました。',
            ]],
            ['title' => '制作ツール', 'body' => ['Photoshop, Illustrator']],
        ],
    ];

    if (isset($content_map[$cat_label])) {
        return $content_map[$cat_label];
    }

    // Default structured content for entries without detail comp
    return [
        ['title' => '目的', 'body' => ['集客・お問合せの獲得']],
        ['title' => '担当範囲', 'body' => ['デザイン・実装']],
        ['title' => '制作期間', 'body' => ['約２〜３週間']],
        ['title' => '制作のポイント', 'body' => [
            '・ターゲットに合わせたデザインと構成',
            '・視認性を重視したレイアウト設計',
        ]],
        ['title' => '制作ツール', 'body' => ['Figma, Photoshop']],
    ];
}
