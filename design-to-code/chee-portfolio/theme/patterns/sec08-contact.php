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

<!-- wp:html -->
<form class="contact-form" action="#" method="post">

  <div class="contact-form__field">
    <label class="contact-form__label" for="cf-company">会社名(任意)</label>
    <input class="contact-form__input" type="text" id="cf-company" name="company" placeholder="">
  </div>

  <div class="contact-form__field">
    <label class="contact-form__label" for="cf-name">お名前</label>
    <input class="contact-form__input" type="text" id="cf-name" name="name" placeholder="">
  </div>

  <div class="contact-form__field">
    <label class="contact-form__label" for="cf-email">メールアドレス</label>
    <input class="contact-form__input" type="email" id="cf-email" name="email" placeholder="">
  </div>

  <div class="contact-form__field">
    <label class="contact-form__label" for="cf-message">お問合せ内容</label>
    <textarea class="contact-form__textarea" id="cf-message" name="message"></textarea>
  </div>

  <div class="contact-form__field">
    <p class="contact-form__label" style="margin-bottom:8px">プライバシーポリシーを確認</p>
    <div class="contact-form__privacy-box">プライバシーポリシーのテキストを記載する<br>枠内で下にスクロールして見れるようにする</div>
  </div>

  <div class="contact-form__checkbox-row">
    <input type="checkbox" id="cf-privacy" name="privacy">
    <label for="cf-privacy">プライバシーポリシーに同意する</label>
  </div>

  <div class="contact-form__submit-wrap">
    <button class="contact-form__submit" type="submit">送信する</button>
  </div>

</form>
<!-- /wp:html -->

</section>
<!-- /wp:group -->
