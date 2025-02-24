<?php
/**
 * Custom Post Types for Wades Marine
 *
 * @package wades
 */

/**
 * Register Custom Post Types
 */
function wades_register_post_types() {
    // Services Post Type
    register_post_type('service', array(
        'labels' => array(
            'name'                  => _x('Services', 'Post type general name', 'wades'),
            'singular_name'         => _x('Service', 'Post type singular name', 'wades'),
            'menu_name'            => _x('Services', 'Admin Menu text', 'wades'),
            'name_admin_bar'       => _x('Service', 'Add New on Toolbar', 'wades'),
            'add_new'              => __('Add New', 'wades'),
            'add_new_item'         => __('Add New Service', 'wades'),
            'new_item'             => __('New Service', 'wades'),
            'edit_item'            => __('Edit Service', 'wades'),
            'view_item'            => __('View Service', 'wades'),
            'all_items'            => __('All Services', 'wades'),
            'search_items'         => __('Search Services', 'wades'),
            'not_found'            => __('No services found.', 'wades'),
            'not_found_in_trash'   => __('No services found in Trash.', 'wades'),
        ),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-admin-tools',
        'hierarchical'          => false,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'has_archive'           => false,
        'rewrite'              => array(
            'slug' => 'service',
            'with_front' => false
        ),
        'show_in_rest'          => true,
        'capability_type'       => 'post',
        'map_meta_cap'         => true,
    ));

    // Register Boat Post Type
    register_post_type('boat', array(
        'labels' => array(
            'name'               => 'Boats',
            'singular_name'      => 'Boat',
            'menu_name'          => 'Boats',
            'add_new'           => 'Add New Boat',
            'add_new_item'      => 'Add New Boat',
            'edit_item'         => 'Edit Boat',
            'new_item'          => 'New Boat',
            'view_item'         => 'View Boat',
            'search_items'      => 'Search Boats',
            'not_found'         => 'No boats found',
            'not_found_in_trash'=> 'No boats found in Trash',
        ),
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array(
            'slug' => 'boats',
            'with_front' => false,
            'pages' => true,
            'feeds' => true,
        ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-store',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
    ));
}
add_action('init', 'wades_register_post_types');

/**
 * Register Custom Taxonomies
 */
function wades_register_taxonomies() {
    // Service Categories
    register_taxonomy('service_category', array('service'), array(
        'labels' => array(
            'name'              => _x('Service Categories', 'taxonomy general name', 'wades'),
            'singular_name'     => _x('Service Category', 'taxonomy singular name', 'wades'),
            'search_items'      => __('Search Service Categories', 'wades'),
            'all_items'         => __('All Service Categories', 'wades'),
            'parent_item'       => __('Parent Service Category', 'wades'),
            'parent_item_colon' => __('Parent Service Category:', 'wades'),
            'edit_item'         => __('Edit Service Category', 'wades'),
            'update_item'       => __('Update Service Category', 'wades'),
            'add_new_item'      => __('Add New Service Category', 'wades'),
            'new_item_name'     => __('New Service Category Name', 'wades'),
            'menu_name'         => __('Categories', 'wades'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug' => 'service-category',
            'with_front' => false
        ),
        'show_in_rest'      => true,
    ));

    // Boat Taxonomies
    $boat_taxonomies = array(
        'boat_manufacturer' => array(
            'label' => 'Manufacturers',
            'slug' => 'manufacturer',
            'hierarchical' => true
        ),
        'boat_location' => array(
            'label' => 'Locations',
            'slug' => 'location',
            'hierarchical' => true
        ),
        'boat_condition' => array(
            'label' => 'Conditions',
            'slug' => 'condition',
            'hierarchical' => true
        ),
        'boat_status' => array(
            'label' => 'Status',
            'slug' => 'status',
            'hierarchical' => true
        )
    );

    foreach ($boat_taxonomies as $tax_name => $tax_args) {
        register_taxonomy($tax_name, 'boat', array(
            'hierarchical'      => $tax_args['hierarchical'],
            'labels'            => array(
                'name'              => $tax_args['label'],
                'singular_name'     => rtrim($tax_args['label'], 's'),
                'search_items'      => 'Search ' . $tax_args['label'],
                'all_items'         => 'All ' . $tax_args['label'],
                'edit_item'         => 'Edit ' . rtrim($tax_args['label'], 's'),
                'update_item'       => 'Update ' . rtrim($tax_args['label'], 's'),
                'add_new_item'      => 'Add New ' . rtrim($tax_args['label'], 's'),
                'new_item_name'     => 'New ' . rtrim($tax_args['label'], 's') . ' Name',
                'menu_name'         => $tax_args['label'],
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array(
                'slug' => $tax_args['slug'],
                'with_front' => false
            ),
            'show_in_rest'      => true,
        ));
    }
}
add_action('init', 'wades_register_taxonomies');

/**
 * Redirect service archive and taxonomy pages to services page
 */
function wades_redirect_service_pages() {
    // Get the services page
    $services_page = get_page_by_path('services');
    
    if ($services_page && (is_post_type_archive('service') || is_tax('service_category'))) {
        wp_redirect(get_permalink($services_page->ID), 301);
        exit;
    }
}
add_action('template_redirect', 'wades_redirect_service_pages');

/**
 * Fix boat permalinks
 */
function wades_boat_post_link($post_link, $post) {
    if ($post->post_type === 'boat') {
        return home_url('boats/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'wades_boat_post_link', 10, 2);

/**
 * Flush rewrite rules when needed
 */
function wades_flush_rewrite_rules() {
    // Register post types and taxonomies first
    wades_register_post_types();
    wades_register_taxonomies();
    
    // Then flush rewrite rules
    flush_rewrite_rules();
}

// Flush rules on theme activation
add_action('after_switch_theme', 'wades_flush_rewrite_rules');

// Flush rules when saving permalinks
function wades_flush_rules_on_save() {
    if (isset($_POST['permalink_structure']) || isset($_POST['category_base'])) {
        wades_flush_rewrite_rules();
    }
}
add_action('admin_init', 'wades_flush_rules_on_save'); 