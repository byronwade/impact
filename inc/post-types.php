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
}
add_action('init', 'wades_register_taxonomies');

/**
 * Flush rewrite rules on theme activation
 */
function wades_rewrite_flush() {
    wades_register_post_types();
    wades_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'wades_rewrite_flush');

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

// Register Boat Post Type
function wades_register_boat_post_type() {
    $labels = array(
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
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => false,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array(
            'slug' => 'boat',
            'with_front' => false
        ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-store',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('boat', $args);

    // Add redirect for boat archive to boats page
    add_action('template_redirect', function() {
        if (is_post_type_archive('boat')) {
            $boats_page = get_page_by_path('boats');
            if ($boats_page) {
                wp_redirect(get_permalink($boats_page->ID), 301);
                exit;
            }
        }
    });

    // Flush rewrite rules when saving a boat post
    add_action('save_post_boat', function() {
        flush_rewrite_rules();
    });

    // Register additional taxonomies
    $taxonomies = array(
        'boat_manufacturer' => array(
            'label' => 'Manufacturers',
            'slug' => 'manufacturer'
        ),
        'boat_location' => array(
            'label' => 'Locations',
            'slug' => 'location'
        ),
        'boat_condition' => array(
            'label' => 'Conditions',
            'slug' => 'condition'
        ),
        'boat_status' => array(
            'label' => 'Status',
            'slug' => 'status'
        )
    );

    foreach ($taxonomies as $tax_name => $tax_args) {
        register_taxonomy($tax_name, 'boat', array(
            'hierarchical'      => true,
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
            'rewrite'           => array('slug' => $tax_args['slug']),
            'show_in_rest'      => true,
        ));
    }

    // Register meta fields
    $meta_fields = array(
        '_boat_stock_number' => 'string',
        '_boat_model_year' => 'string',
        '_boat_model' => 'string',
        '_boat_trim' => 'string',
        '_boat_retail_price' => 'number',
        '_boat_sales_price' => 'number',
        '_boat_web_price' => 'number',
        '_boat_discount' => 'number',
        '_boat_floorplan' => 'string'
    );

    foreach ($meta_fields as $meta_key => $meta_type) {
        register_post_meta('boat', $meta_key, array(
            'type' => $meta_type,
            'single' => true,
            'show_in_rest' => true,
        ));
    }
}
add_action('init', 'wades_register_boat_post_type'); 