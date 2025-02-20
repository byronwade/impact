<?php
/**
 * Custom Navigation Walker for Desktop Menu
 *
 * @package wades
 */

class Wade_Nav_Walker extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        
        $indent = ($depth) ? str_repeat($t, $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        // Add our custom classes
        $classes[] = 'text-sm font-medium hover:text-primary';
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= $indent . '<a' . $class_names . ' href="' . esc_url($item->url) . '">';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }
    
    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $n = '';
        } else {
            $n = "\n";
        }
        $output .= $n;
    }
} 