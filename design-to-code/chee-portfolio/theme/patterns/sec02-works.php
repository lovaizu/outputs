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

<!-- wp:heading {"level":2,"className":"sec-title","textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontFamily":"noto-sans-jp","fontSize":"xl","textColor":"text-primary"} -->
<h2 class="wp-block-heading sec-title has-text-align-center has-noto-sans-jp-font-family has-xl-font-size has-text-primary-color has-text-color" style="font-style:normal;font-weight:500">Works</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<?php
$works = get_posts([
  'post_type'      => 'works',
  'posts_per_page' => 6,
  'orderby'        => 'date',
  'order'          => 'DESC',
]);
?>
<div class="works-list">
<?php foreach ($works as $work):
  $client    = get_post_meta($work->ID, 'client_name', true);
  $img_id    = get_post_meta($work->ID, 'mockup_image', true);
  $img_url   = $img_id ? wp_get_attachment_image_url((int) $img_id, 'large') : '';
  $terms     = get_the_terms($work->ID, 'works-category');
  $excerpt   = has_excerpt($work->ID) ? get_the_excerpt($work->ID) : wp_trim_words($work->post_content, 40, '');
?>
<div class="work-card">
  <div class="work-card-body">
    <div class="work-card-header">
      <h3 class="work-card-title">
        <a href="<?php echo get_permalink($work->ID); ?>"><?php echo esc_html($work->post_title); ?><?php if ($client): ?><span class="work-card-client"><?php echo esc_html($client); ?>様</span><?php endif; ?></a>
        <svg class="work-card-arrow" viewBox="0 0 24 24" width="24" height="24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="11" stroke="#4EB0EA" stroke-width="1.5"/><path d="M10 12h5m-2-2 2 2-2 2" stroke="#4EB0EA" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </h3>
    </div>
    <?php if ($terms && ! is_wp_error($terms)): ?>
    <div class="work-card-pills">
      <?php foreach ($terms as $term): ?>
      <span class="work-pill"><?php echo esc_html($term->name); ?></span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($excerpt): ?>
    <p class="work-card-desc"><?php echo esc_html($excerpt); ?></p>
    <?php endif; ?>
  </div>
  <?php if ($img_url): ?>
  <div class="work-card-image">
    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($work->post_title); ?>" loading="lazy">
  </div>
  <?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<div class="works-more-wrap">
  <a href="/works/" class="works-more-link">制作実績の一覧へ <svg viewBox="0 0 20 20" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="9"/><path d="M8 10h5m-2-2 2 2-2 2"/></svg></a>
</div>
<!-- /wp:html -->

</section>
<!-- /wp:group -->
