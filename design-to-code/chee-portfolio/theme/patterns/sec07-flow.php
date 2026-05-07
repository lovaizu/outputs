<?php
/**
 * Title: Flow — 制作フロー
 * Slug: chee-portfolio/sec07-flow
 * Categories: chee-portfolio
 * Block Types: core/group
 */
$steps = [
  ['num' => '1', 'title' => '無料ご相談、お問合せ',    'body' => 'お問合せフォーム、あるいは公式LINEやSNSのDMよりお問合せください。'],
  ['num' => '2', 'title' => 'お打ち合わせ、見積もり、ご契約', 'body' => '制作の背景や目的からお伺い致します。'],
  ['num' => '3', 'title' => 'ご提案、設計',            'body' => '競合他社やお客様の目的、ユーザーの利用しやすいサイトを考えます。'],
  ['num' => '4', 'title' => 'デザイン',               'body' => 'ファーストビュー決定後、全体のデザインを制作いたします。'],
  ['num' => '5', 'title' => 'コーディング、表示確認、調整', 'body' => 'インターネット環境で表示確認をお願いします。'],
  ['num' => '6', 'title' => '納品　アフターフォロー2週間', 'body' => 'テキストの修正や画像の差し替えなど軽微な修正に対応致します。'],
  ['num' => '7', 'title' => 'ご請求、お支払い',          'body' => '初めてのお取引の場合、着手金として50％お振込みをお願いしております。'],
];
?>
<!-- wp:group {"tagName":"section","className":"sec-flow","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-flow" style="padding-top:80px;padding-bottom:80px" id="flow">

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","textTransform":"uppercase"}},"fontFamily":"roboto-condensed","fontSize":"2xl","textColor":"border"} -->
<p class="has-roboto-condensed-font-family has-2xl-font-size has-border-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase">Flow</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontFamily":"noto-sans-jp","fontSize":"lg"} -->
<h2 class="wp-block-heading has-noto-sans-jp-font-family has-lg-font-size" style="font-style:normal;font-weight:700">制作フロー</h2>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"blockGap":"24px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<?php foreach ($steps as $step): ?>
<!-- wp:group {"className":"flow-step","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group flow-step">

<!-- wp:group {"className":"flow-step__num","backgroundColor":"bg-main","style":{"border":{"radius":"4px"},"spacing":{"padding":{"top":"8px","bottom":"8px","left":"16px","right":"16px"}},"layout":{"selfStretch":"fixed","flexSize":"56px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group flow-step__num has-bg-main-background-color has-background" style="border-radius:4px;padding-top:8px;padding-right:16px;padding-bottom:8px;padding-left:16px">
<!-- wp:paragraph {"textAlign":"center","textColor":"accent","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontFamily":"jost","fontSize":"md"} -->
<p class="has-text-align-center has-accent-color has-text-color has-jost-font-family has-md-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html($step['num']); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontFamily":"noto-sans-jp","fontSize":"md"} -->
<h3 class="wp-block-heading has-noto-sans-jp-font-family has-md-font-size" style="font-style:normal;font-weight:700"><?php echo esc_html($step['title']); ?></h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"sm","textColor":"text-secondary"} -->
<p class="has-sm-font-size has-text-secondary-color has-text-color"><?php echo esc_html($step['body']); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
