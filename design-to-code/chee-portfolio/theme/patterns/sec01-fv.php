<?php
/**
 * Title: FV — First View
 * Slug: chee-portfolio/sec01-fv
 * Categories: chee-portfolio
 * Block Types: core/group
 */
$fv_works = get_posts([
  'post_type'      => 'works',
  'posts_per_page' => 10,
  'meta_query'     => [['key' => 'fv_featured', 'value' => '1']],
  'meta_key'       => 'fv_order',
  'orderby'        => 'meta_value_num',
  'order'          => 'ASC',
]);
?>
<!-- wp:group {"tagName":"section","className":"sec-fv","backgroundColor":"bg-sub","layout":{"type":"default"}} -->
<section class="wp-block-group sec-fv has-bg-sub-background-color has-background" id="fv">

<!-- wp:group {"className":"fv-text-area","layout":{"type":"constrained"}} -->
<div class="wp-block-group fv-text-area">

<!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.3","fontSize":"clamp(40px, 5vw, 60px)"}},"fontFamily":"noto-sans-jp"} -->
<h1 class="wp-block-heading has-noto-sans-jp-font-family" style="font-style:normal;font-weight:700;line-height:1.3;font-size:clamp(40px, 5vw, 60px)">LP制作×広告運用</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1.6"}},"fontFamily":"noto-sans-jp","fontSize":"sm","textColor":"text-secondary"} -->
<p class="has-noto-sans-jp-font-family has-sm-font-size has-text-secondary-color has-text-color" style="font-style:normal;font-weight:400;line-height:1.6">提案も、デザインも、運用も</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="fv-lower">
  <div class="fv-lower__inner">
    <div class="splide fv-splide" aria-label="制作実績スライダー">
      <div class="splide__track">
        <ul class="splide__list">
          <?php
          foreach ($fv_works as $work) {
            $img_id  = get_post_meta($work->ID, 'mockup_image', true);
            $img_url = $img_id ? wp_get_attachment_image_url((int) $img_id, 'medium') : '';
            if (!$img_url) continue;
            echo '<li class="splide__slide">';
            echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($work->post_title) . '" loading="lazy">';
            echo '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<p class="fv-deco-text" aria-hidden="true">Chee Design</p>
<!-- /wp:html -->

</section>
<!-- /wp:group -->
