<?php
/**
 * Title: Works — 制作実績
 * Slug: chee-portfolio/sec02-works
 * Categories: chee-portfolio
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"sec-works","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-works" style="padding-top:80px;padding-bottom:80px" id="works">

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","textTransform":"uppercase"}},"fontFamily":"roboto-condensed","fontSize":"2xl","textColor":"border"} -->
<p class="has-roboto-condensed-font-family has-2xl-font-size has-border-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase">Works</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontFamily":"noto-sans-jp","fontSize":"lg"} -->
<h2 class="wp-block-heading has-noto-sans-jp-font-family has-lg-font-size" style="font-style:normal;font-weight:700">制作実績</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":2,"query":{"postType":"works","perPage":6,"orderBy":"date","order":"desc"},"layout":{"type":"default"}} -->
<div class="wp-block-query">

<!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->

<!-- wp:group {"className":"works-card","style":{"border":{"width":"1px","color":"var:preset|color|border","radius":"8px"},"spacing":{"padding":{"top":"16px","bottom":"16px","left":"16px","right":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group works-card" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:8px;padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px">

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"sm"} /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group">
<!-- wp:mfb/meta-field-block {"fieldName":"client_name","className":"works-card__client","fontSize":"xs"} /-->
<!-- wp:mfb/meta-field-block {"fieldName":"category_label","className":"works-card__label","fontSize":"xs","textColor":"accent"} /-->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- /wp:post-template -->

</div>
<!-- /wp:query -->

<!-- wp:group {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-group">
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"accent","textColor":"white","fontSize":"xs","style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button has-custom-font-size has-xs-font-size"><a class="wp-block-button__link has-white-color has-accent-background-color has-text-color has-background wp-element-button" style="border-radius:4px" href="/works/">制作実績一覧へ</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
