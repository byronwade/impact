<?php
/**
 * Helper functions for the theme
 *
 * @package wades
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get default image URL or ID
 * 
 * @param string $type Whether to return 'url' or 'id'
 * @return string|int URL or attachment ID of default image
 */
function wades_get_default_image($type = 'url') {
    $default_image = get_template_directory_uri() . '/assets/images/default.jpg';
    $default_image_id = attachment_url_to_postid($default_image);
    
    if ($type === 'id') {
        return $default_image_id ? $default_image_id : 0;
    }
    
    return $default_image;
}

/**
 * Get image URL with fallback to default
 * 
 * @param int $image_id Attachment ID
 * @param string $size Image size
 * @return string Image URL
 */
function wades_get_image_url($image_id, $size = 'full') {
    if ($image_id && wp_get_attachment_image_url($image_id, $size)) {
        return wp_get_attachment_image_url($image_id, $size);
    }
    return wades_get_default_image();
}

/**
 * Get image HTML with fallback to default
 * 
 * @param int $image_id Attachment ID
 * @param string $size Image size
 * @param array $attr Additional attributes
 * @return string Image HTML
 */
function wades_get_image_html($image_id, $size = 'full', $attr = array()) {
    if ($image_id && wp_get_attachment_image($image_id, $size)) {
        return wp_get_attachment_image($image_id, $size, false, $attr);
    }
    
    $default_attr = array(
        'src' => wades_get_default_image(),
        'alt' => isset($attr['alt']) ? $attr['alt'] : 'Default Image'
    );
    
    $attr = wp_parse_args($attr, $default_attr);
    $html = '<img';
    foreach ($attr as $name => $value) {
        $html .= ' ' . $name . '="' . esc_attr($value) . '"';
    }
    $html .= ' />';
    
    return $html;
}

/**
 * Format price with proper formatting
 * 
 * @param float $price The price to format
 * @param bool $show_dollar Whether to show dollar sign
 * @return string Formatted price
 */
function wades_format_price($price, $show_dollar = true) {
    if (!$price) {
        return 'Call for Price';
    }
    
    $formatted = number_format($price);
    return $show_dollar ? '$' . $formatted : $formatted;
}

/**
 * Get boat title with year and model
 * 
 * @param int $post_id Post ID
 * @return string Formatted boat title
 */
function wades_get_boat_title($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $year = get_post_meta($post_id, '_boat_model_year', true);
    $model = get_post_meta($post_id, '_boat_model', true);
    $title = get_the_title($post_id);
    
    $parts = array_filter([$year, $model, $title]);
    return implode(' ', $parts);
} 