<?php
/**
 * Title: FV — First View
 * Slug: chee-portfolio/sec01-fv
 * Categories: chee-portfolio
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"sec-fv","backgroundColor":"bg-sub","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-fv has-bg-sub-background-color has-background" style="padding-top:80px;padding-bottom:80px" id="fv">

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group">

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">

<!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.3","fontSize":"clamp(40px, 5vw, 60px)"}},"fontFamily":"noto-sans-jp"} -->
<h1 class="wp-block-heading has-noto-sans-jp-font-family" style="font-style:normal;font-weight:700;line-height:1.3;font-size:clamp(40px, 5vw, 60px)">LP制作×広告運用</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1.6"}},"fontFamily":"noto-sans-jp","fontSize":"sm","textColor":"text-secondary"} -->
<p class="has-noto-sans-jp-font-family has-sm-font-size has-text-secondary-color has-text-color" style="font-style:normal;font-weight:400;line-height:1.6">感情が動く、クリック される。</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontFamily":"jost","fontSize":"hero","textColor":"text-primary"} -->
<p class="has-jost-font-family has-hero-font-size has-text-primary-color has-text-color" style="font-style:normal;font-weight:500">Design</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:group {"className":"fv-carousel-wrap","layout":{"type":"constrained","contentSize":"360px"}} -->
<div class="wp-block-group fv-carousel-wrap">
<!-- wp:html -->
<div class="splide fv-splide" aria-label="制作実績スライダー">
  <div class="splide__track">
    <ul class="splide__list">
      <?php
      $fv_works = get_posts([
        'post_type'      => 'works',
        'posts_per_page' => 10,
        'meta_query'     => [['key' => 'fv_featured', 'value' => '1']],
        'meta_key'       => 'fv_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
      ]);
      foreach ($fv_works as $work) {
        $img_id = get_post_meta($work->ID, 'mockup_image', true);
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
<!-- /wp:html -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
