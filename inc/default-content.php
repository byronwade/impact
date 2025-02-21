<?php
/**
 * Default content creation for theme
 *
 * @package wades
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Helper function to check if a page exists in the navigation menu
 */
function wades_check_menu_item_exists($page_id) {
    $menu_items = wp_get_nav_menu_items('Primary Menu');
    if ($menu_items) {
        foreach ($menu_items as $item) {
            if ($item->object_id == $page_id) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Create default pages and set up navigation on theme activation
 */
function wades_create_default_pages() {
    // Only run this during theme activation
    if (!get_option('wades_theme_activated')) {
        // Create default pages
        $default_pages = array(
            'home' => array(
                'title' => 'Home',
                'template' => 'templates/home.php',
                'content' => '<!-- wp:paragraph --><p>Welcome to Impact Marine Group</p><!-- /wp:paragraph -->'
            ),
            'blog' => array(
                'title' => 'Blog',
                'content' => '<!-- wp:paragraph --><p>Stay updated with our latest news and insights.</p><!-- /wp:paragraph -->'
            ),
            'services' => array(
                'title' => 'Services',
                'template' => 'templates/services.php',
                'content' => '<!-- wp:paragraph --><p>Explore our marine services.</p><!-- /wp:paragraph -->'
            ),
            'boats' => array(
                'title' => 'Boats',
                'template' => 'templates/boats.php',
                'content' => '<!-- wp:paragraph --><p>Browse our boat inventory.</p><!-- /wp:paragraph -->'
            )
        );

        $page_ids = array();

        // Create pages
        foreach ($default_pages as $slug => $page_data) {
            $existing_page = get_page_by_path($slug);
            if (!$existing_page) {
                $args = array(
                    'post_title' => $page_data['title'],
                    'post_name' => $slug,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_content' => $page_data['content']
                );
                
                $page_id = wp_insert_post($args);
                
                if (isset($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
                
                $page_ids[$slug] = $page_id;
            } else {
                $page_ids[$slug] = $existing_page->ID;
            }
        }

        // Set the blog page as the posts page
        update_option('page_for_posts', $page_ids['blog']);
        
        // Set the home page as the front page
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_ids['home']);

        // Create or get the primary menu
        $menu_name = 'Primary Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);
        
        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
        } else {
            $menu_id = $menu_exists->term_id;
        }

        // Add pages to menu in specific order
        $menu_order = array('home', 'boats', 'services', 'blog');
        
        foreach ($menu_order as $index => $slug) {
            if (isset($page_ids[$slug]) && !wades_check_menu_item_exists($page_ids[$slug])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $default_pages[$slug]['title'],
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page_ids[$slug],
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $index
                ));
            }
        }

        // Set up the menu location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['menu-1'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);

        // Mark theme as activated
        update_option('wades_theme_activated', true);
    }
}
add_action('after_switch_theme', 'wades_create_default_pages'); 