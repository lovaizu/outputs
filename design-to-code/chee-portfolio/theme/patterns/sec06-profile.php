<?php
/**
 * Title: Profile — プロフィール
 * Slug: chee-portfolio/sec06-profile
 * Categories: chee-portfolio
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"sec-profile","backgroundColor":"bg-main","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-profile has-bg-main-background-color has-background" style="padding-top:80px;padding-bottom:80px" id="profile">

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","textTransform":"uppercase"}},"fontFamily":"roboto-condensed","fontSize":"2xl","textColor":"border"} -->
<p class="has-roboto-condensed-font-family has-2xl-font-size has-border-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase">Profile</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontFamily":"noto-sans-jp","fontSize":"lg"} -->
<h2 class="wp-block-heading has-noto-sans-jp-font-family has-lg-font-size" style="font-style:normal;font-weight:700">プロフィール</h2>
<!-- /wp:heading -->

<!-- wp:group {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:16px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px">

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:paragraph {"fontSize":"sm"} -->
<p class="has-sm-font-size">百貨店の販売職でお客様との関わり方を学び、2005年にWeb制作会社へ転職。通販部門の立ち上げから運営・プロジェクトの横展開まで幅広く携わりました。</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"sm"} -->
<p class="has-sm-font-size">以前から関心のあったWebデザインを軸に在宅での独立を決意。LP制作から広告運用に携わり、クライアントの集客に貢献します。</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"sm","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
<p class="has-sm-font-size" style="font-style:normal;font-weight:500">「感情が動く」「クリックされる」ことを起点に、デザイン制作も運用も考えています。</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"constrained","contentSize":"160px"}} -->
<div class="wp-block-group">
<!-- wp:image {"url":"<?php echo esc_url(get_template_directory_uri() . '/assets/images/sec06-Profile.jpg'); ?>","alt":"伊藤 千晶","style":{"border":{"radius":"100%"}}} -->
<figure class="wp-block-image" style="border-radius:100%"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sec06-Profile.jpg'); ?>" alt="伊藤 千晶" style="border-radius:100%"/></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"textAlign":"center","fontSize":"xs","textColor":"text-secondary"} --><p class="has-text-align-center has-text-secondary-color has-text-color has-xs-font-size">WEBデザイナー</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"md"} --><p class="has-text-align-center has-md-font-size" style="font-style:normal;font-weight:500">伊藤 千晶</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"textAlign":"center","fontSize":"xs","textColor":"accent","fontFamily":"jost"} --><p class="has-text-align-center has-accent-color has-text-color has-jost-font-family has-xs-font-size">Chiaki Itoh</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"32px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:32px">

<!-- wp:group {"style":{"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}},"spacing":{"padding":{"top":"16px","bottom":"16px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:16px;padding-bottom:16px">
<!-- wp:paragraph {"fontSize":"xs","textColor":"text-secondary","style":{"layout":{"selfStretch":"fixed","flexSize":"180px"}}} --><p class="has-text-secondary-color has-text-color has-xs-font-size">ご提供できること</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">LP（ランディングページ）制作、広告バナー制作<br>Meta広告運用<br>HP（ホームページ）制作</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}},"spacing":{"padding":{"top":"16px","bottom":"16px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:16px;padding-bottom:16px">
<!-- wp:paragraph {"fontSize":"xs","textColor":"text-secondary","style":{"layout":{"selfStretch":"fixed","flexSize":"180px"}}} --><p class="has-text-secondary-color has-text-color has-xs-font-size">居住地</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">神奈川県</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"16px","bottom":"16px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="padding-top:16px;padding-bottom:16px">
<!-- wp:paragraph {"fontSize":"xs","textColor":"text-secondary","style":{"layout":{"selfStretch":"fixed","flexSize":"180px"}}} --><p class="has-text-secondary-color has-text-color has-xs-font-size">趣味</p><!-- /wp:paragraph -->
<!-- wp:paragraph {"fontSize":"sm"} --><p class="has-sm-font-size">漫画 / 映画鑑賞 / 岩盤浴 / 温泉</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

</div>
<!-- /wp:group -->

</section>
<!-- /wp:group -->
