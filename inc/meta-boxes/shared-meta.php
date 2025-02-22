<?php
/**
 * Shared Meta Box Callbacks
 * 
 * @package wades
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Prevent duplicate inclusion
if (!defined('WADES_SHARED_META_LOADED')) {
    define('WADES_SHARED_META_LOADED', true);

    /**
     * Helper function for page select fields
     */
    if (!function_exists('wades_page_select_field')) {
        function wades_page_select_field($name, $selected_id = '', $class = 'widefat') {
            $pages = get_pages(array(
                'sort_column' => 'menu_order,post_title',
                'post_status' => 'publish'
            ));
            
            $output = '<select name="' . esc_attr($name) . '" class="' . esc_attr($class) . '">';
            $output .= '<option value="">Select a page</option>';
            
            foreach ($pages as $page) {
                $output .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($page->ID),
                    selected($selected_id, $page->ID, false),
                    esc_html($page->post_title)
                );
            }
            
            $output .= '</select>';
            return $output;
        }
    }
} 