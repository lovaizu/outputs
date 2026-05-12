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
<!-- wp:group {"tagName":"section","align":"full","className":"sec-fv","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull sec-fv" id="fv">

<!-- wp:group {"className":"fv-text-area","layout":{"type":"default"}} -->
<div class="wp-block-group fv-text-area">

<!-- wp:paragraph {"className":"fv-catchcopy","style":{"typography":{"fontStyle":"normal","fontWeight":"400","fontSize":"16px"}},"textColor":"text-primary"} -->
<p class="fv-catchcopy has-text-primary-color has-text-color" style="font-style:normal;font-weight:400;font-size:16px">LP制作×広告運用</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1.4","fontSize":"28px"}},"fontFamily":"noto-sans-jp","textColor":"text-primary"} -->
<h1 class="wp-block-heading has-noto-sans-jp-font-family has-text-primary-color has-text-color" style="font-style:normal;font-weight:400;line-height:1.4;font-size:28px">提案も、デザインも、運用も</h1>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="fv-identity-divider" aria-hidden="true"></div>
<!-- /wp:html -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3","fontSize":"20px"}},"textColor":"accent","fontFamily":"jost"} -->
<p class="has-accent-color has-text-color has-jost-font-family" style="font-style:normal;font-weight:500;line-height:1.3;font-size:20px">Chiaki Itoh</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1.3","fontSize":"14px"}},"textColor":"accent","fontFamily":"jost"} -->
<p class="has-accent-color has-text-color has-jost-font-family" style="font-style:normal;font-weight:400;line-height:1.3;font-size:14px">LP Design,Meta Ads</p>
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
            $link = get_permalink($work->ID);
            echo '<li class="splide__slide">';
            echo '<a href="' . esc_url($link) . '">';
            echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($work->post_title) . '" loading="lazy">';
            echo '</a>';
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
