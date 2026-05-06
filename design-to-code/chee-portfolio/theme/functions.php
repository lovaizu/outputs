<?php
declare(strict_types=1);

add_action('after_setup_theme', function (): void {
    add_theme_support('wp-block-styles');
});

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'chee-portfolio-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
});
