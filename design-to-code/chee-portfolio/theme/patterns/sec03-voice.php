<?php
/**
 * Title: Voice — お客様の声
 * Slug: chee-portfolio/sec03-voice
 * Categories: chee-portfolio
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"sec-voice","backgroundColor":"bg-main","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group sec-voice has-bg-main-background-color has-background" style="padding-top:80px;padding-bottom:80px" id="voice">

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","textTransform":"uppercase"}},"fontFamily":"roboto-condensed","fontSize":"2xl","textColor":"border"} -->
<p class="has-roboto-condensed-font-family has-2xl-font-size has-border-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase">Voice</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontFamily":"noto-sans-jp","fontSize":"lg"} -->
<h2 class="wp-block-heading has-noto-sans-jp-font-family has-lg-font-size" style="font-style:normal;font-weight:700">お客様の声</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="splide voice-splide" aria-label="お客様の声">
  <div class="splide__track">
    <ul class="splide__list">
      <?php
      $voices = get_posts(['post_type' => 'voice', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
      foreach ($voices as $voice) {
        $photo_id   = get_post_meta($voice->ID, 'voice_photo', true);
        $photo_url  = $photo_id ? wp_get_attachment_image_url((int) $photo_id, 'thumbnail') : '';
        $role       = get_post_meta($voice->ID, 'voice_role', true);
        $name       = get_post_meta($voice->ID, 'voice_name', true);
        $body       = get_post_meta($voice->ID, 'voice_body', true);
        $catchphrase = get_post_meta($voice->ID, 'voice_catchphrase', true);
        echo '<li class="splide__slide">';
        echo '<div class="voice-card">';
        if ($photo_url) echo '<img class="voice-card__photo" src="' . esc_url($photo_url) . '" alt="' . esc_attr($name) . '">';
        if ($catchphrase) echo '<p class="voice-card__catchphrase">' . esc_html($catchphrase) . '</p>';
        if ($body) echo '<p class="voice-card__body">' . esc_html($body) . '</p>';
        if ($name) echo '<p class="voice-card__name">' . esc_html($name) . '</p>';
        if ($role) echo '<p class="voice-card__role">' . esc_html($role) . '</p>';
        echo '</div>';
        echo '</li>';
      }
      ?>
    </ul>
  </div>
</div>
<!-- /wp:html -->

</section>
<!-- /wp:group -->
