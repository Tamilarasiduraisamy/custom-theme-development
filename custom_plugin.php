<?php
/**
 * Plugin Name: Custom Testimonials
 * Plugin URI: https://example.com
 * Description: Adds a 'Testimonial' section via a shortcode and Gutenberg block.
 * Version: 1.0
 * Author: Your Name
 * License: GPL-2.0+
 * Text Domain: custom-testimonials
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Testimonial Custom Post Type
function custom_testimonial_cpt() {
    $args = array(
        'label' => __('Testimonials', 'custom-testimonials'),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'testimonials'),
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'custom_testimonial_cpt');

// Shortcode to Display Testimonials
function custom_testimonial_shortcode() {
    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => 5,
    );
    $query = new WP_Query($args);
    $output = '<div class="testimonials">';
    
    while ($query->have_posts()) {
        $query->the_post();
        $output .= '<div class="testimonial-item">';
        $output .= '<h3>' . get_the_title() . '</h3>';
        $output .= '<p>' . get_the_content() . '</p>';
        $output .= '</div>';
    }
    wp_reset_postdata();
    
    $output .= '</div>';
    return $output;
}
add_shortcode('testimonials', 'custom_testimonial_shortcode');

// Register Gutenberg Block
function custom_testimonial_register_block() {
    wp_register_script(
        'testimonial-block',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-editor', 'wp-components', 'wp-i18n'),
        true
    );
    
    register_block_type('custom/testimonial-block', array(
        'editor_script' => 'testimonial-block',
    ));
}
add_action('init', 'custom_testimonial_register_block');

// AJAX Testimonial Submission Form
function custom_testimonial_form() {
    ob_start();
    ?>
    <form id="testimonial-form">
        <input type="text" id="testimonial-name" placeholder="Your Name" required>
        <textarea id="testimonial-message" placeholder="Your Testimonial" required></textarea>
        <button type="submit">Submit</button>
    </form>
    <div id="testimonial-response"></div>
    <script>
        jQuery(document).ready(function($) {
            $('#testimonial-form').submit(function(e) {
                e.preventDefault();
                var data = {
                    action: 'submit_testimonial',
                    name: $('#testimonial-name').val(),
                    message: $('#testimonial-message').val(),
                };
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                    $('#testimonial-response').html(response);
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('testimonial_form', 'custom_testimonial_form');

// Handle AJAX Request
function custom_testimonial_ajax_handler() {
    if (!isset($_POST['name']) || !isset($_POST['message'])) {
        wp_send_json_error('Invalid input');
    }
    
    $name = sanitize_text_field($_POST['name']);
    $message = sanitize_textarea_field($_POST['message']);
    
    $post_id = wp_insert_post(array(
        'post_type' => 'testimonial',
        'post_title' => $name,
        'post_content' => $message,
        'post_status' => 'pending',
    ));
    
    if ($post_id) {
        wp_send_json_success('Thank you for your testimonial!');
    } else {
        wp_send_json_error('Submission failed.');
    }
}
add_action('wp_ajax_submit_testimonial', 'custom_testimonial_ajax_handler');
add_action('wp_ajax_nopriv_submit_testimonial', 'custom_testimonial_ajax_handler');

// REST API Endpoint
function custom_testimonial_rest_endpoint() {
    register_rest_route('custom/v1', '/testimonials/', array(
        'methods' => 'GET',
        'callback' => function() {
            $args = array(
                'post_type' => 'testimonial',
                'posts_per_page' => 5,
            );
            $testimonials = get_posts($args);
            return rest_ensure_response($testimonials);
        },
    ));
}
add_action('rest_api_init', 'custom_testimonial_rest_endpoint');
https://github.com/Tamilarasiduraisamy/custom-theme-development.git
