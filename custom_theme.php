<?php
/**
 * Theme Name: Custom WP Theme
 * Theme URI: https://example.com
 * Author: Your Name
 * Description: A lightweight, SEO-optimized, and responsive WordPress theme.
 * Version: 1.0
 * License: GPL-2.0+
 * Text Domain: custom-wp-theme
 */

// Enqueue Styles and Scripts
function custom_theme_scripts() {
    wp_enqueue_style('custom-style', get_stylesheet_uri());
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_theme_scripts');

// Theme Setup
function custom_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'custom-wp-theme'),
    ));
}
add_action('after_setup_theme', 'custom_theme_setup');

// Register Custom Post Type - Portfolio
function custom_portfolio_cpt() {
    $args = array(
        'label' => __('Portfolio', 'custom-wp-theme'),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'portfolio'),
    );
    register_post_type('portfolio', $args);
}
add_action('init', 'custom_portfolio_cpt');

// Theme Customizer Settings
function custom_theme_customizer($wp_customize) {
    $wp_customize->add_section('custom_theme_colors', array(
        'title' => __('Theme Colors', 'custom-wp-theme'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('custom_theme_primary_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_theme_primary_color', array(
        'label' => __('Primary Color', 'custom-wp-theme'),
        'section' => 'custom_theme_colors',
        'settings' => 'custom_theme_primary_color',
    )));
}
add_action('customize_register', 'custom_theme_customizer');

// Pagination Function
function custom_pagination() {
    echo paginate_links(array(
        'prev_text' => __('&laquo; Prev', 'custom-wp-theme'),
        'next_text' => __('Next &raquo;', 'custom-wp-theme'),
    ));
}

?>

