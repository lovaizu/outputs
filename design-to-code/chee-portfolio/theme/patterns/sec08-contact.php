<?php
/**
 * Title: Contact — お問合せ
 * Slug: chee-portfolio/sec08-contact
 * Categories: chee-portfolio
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"sec-contact","backgroundColor":"bg-sub","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-contact has-bg-sub-background-color has-background" style="padding-top:80px;padding-bottom:80px" id="contact">

<!-- wp:heading {"level":2,"className":"sec-title","textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontFamily":"noto-sans-jp","fontSize":"xl","textColor":"text-primary"} -->
<h2 class="wp-block-heading sec-title has-text-align-center has-noto-sans-jp-font-family has-xl-font-size has-text-primary-color has-text-color" style="font-style:normal;font-weight:500">Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"contact-lead","textAlign":"center","fontFamily":"zen-kurenaido","fontSize":"md"} -->
<p class="contact-lead has-text-align-center has-zen-kurenaido-font-family has-md-font-size">まずはご相談だけでも構いません。お気軽にお問合せください。<br>1〜2営業日以内にご返信いたします。</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"32px"}}},"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group" style="padding-top:32px">
<!-- wp:shortcode -->
[fluentform id="1"]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
