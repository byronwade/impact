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
    // Boats Post Type
    register_post_type('boat', array(
        'labels' => array(
            'name'                  => _x('Boats', 'Post type general name', 'wades'),
            'singular_name'         => _x('Boat', 'Post type singular name', 'wades'),
            'menu_name'            => _x('Boats', 'Admin Menu text', 'wades'),
            'name_admin_bar'       => _x('Boat', 'Add New on Toolbar', 'wades'),
            'add_new'              => __('Add New', 'wades'),
            'add_new_item'         => __('Add New Boat', 'wades'),
            'new_item'             => __('New Boat', 'wades'),
            'edit_item'            => __('Edit Boat', 'wades'),
            'view_item'            => __('View Boat', 'wades'),
            'all_items'            => __('All Boats', 'wades'),
            'search_items'         => __('Search Boats', 'wades'),
            'not_found'            => __('No boats found.', 'wades'),
            'not_found_in_trash'   => __('No boats found in Trash.', 'wades'),
        ),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-ship',
        'hierarchical'          => false,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'           => true,
        'rewrite'              => array('slug' => 'boats'),
        'show_in_rest'          => true,
    ));

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
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-admin-tools',
        'hierarchical'          => false,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive'           => true,
        'rewrite'              => array('slug' => 'services'),
        'show_in_rest'          => true,
    ));
}
add_action('init', 'wades_register_post_types');

/**
 * Register Custom Taxonomies
 */
function wades_register_taxonomies() {
    // Boat Categories
    register_taxonomy('boat_category', array('boat'), array(
        'labels' => array(
            'name'              => _x('Boat Categories', 'taxonomy general name', 'wades'),
            'singular_name'     => _x('Boat Category', 'taxonomy singular name', 'wades'),
            'search_items'      => __('Search Boat Categories', 'wades'),
            'all_items'         => __('All Boat Categories', 'wades'),
            'parent_item'       => __('Parent Boat Category', 'wades'),
            'parent_item_colon' => __('Parent Boat Category:', 'wades'),
            'edit_item'         => __('Edit Boat Category', 'wades'),
            'update_item'       => __('Update Boat Category', 'wades'),
            'add_new_item'      => __('Add New Boat Category', 'wades'),
            'new_item_name'     => __('New Boat Category Name', 'wades'),
            'menu_name'         => __('Categories', 'wades'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'boat-category'),
        'show_in_rest'      => true,
    ));

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
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'wades_register_taxonomies');

/**
 * Add Custom Fields
 */
function wades_add_custom_fields() {
    if (function_exists('acf_add_local_field_group')) {
        // Boat Fields
        acf_add_local_field_group(array(
            'key' => 'group_boat_details',
            'title' => 'Boat Details',
            'fields' => array(
                array(
                    'key' => 'field_boat_price',
                    'label' => 'Price',
                    'name' => 'boat_price',
                    'type' => 'number',
                    'instructions' => 'Enter the boat price',
                    'required' => 1,
                    'min' => 0,
                ),
                array(
                    'key' => 'field_boat_year',
                    'label' => 'Year',
                    'name' => 'boat_year',
                    'type' => 'number',
                    'instructions' => 'Enter the boat year',
                    'required' => 1,
                    'min' => 1900,
                ),
                array(
                    'key' => 'field_boat_length',
                    'label' => 'Length',
                    'name' => 'boat_length',
                    'type' => 'number',
                    'instructions' => 'Enter the boat length in feet',
                    'required' => 1,
                    'min' => 0,
                ),
                array(
                    'key' => 'field_boat_specifications',
                    'label' => 'Specifications',
                    'name' => 'boat_specifications',
                    'type' => 'repeater',
                    'instructions' => 'Add boat specifications',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_spec_label',
                            'label' => 'Label',
                            'name' => 'label',
                            'type' => 'text',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_spec_value',
                            'label' => 'Value',
                            'name' => 'value',
                            'type' => 'text',
                            'required' => 1,
                        ),
                    ),
                ),
                array(
                    'key' => 'field_boat_gallery',
                    'label' => 'Gallery',
                    'name' => 'boat_gallery',
                    'type' => 'gallery',
                    'instructions' => 'Add boat images',
                    'min' => 0,
                    'max' => 20,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'boat',
                    ),
                ),
            ),
        ));

        // Service Fields
        acf_add_local_field_group(array(
            'key' => 'group_service_details',
            'title' => 'Service Details',
            'fields' => array(
                array(
                    'key' => 'field_service_icon',
                    'label' => 'Icon',
                    'name' => 'service_icon',
                    'type' => 'text',
                    'instructions' => 'Enter the FontAwesome icon class (e.g., fa-wrench)',
                ),
                array(
                    'key' => 'field_service_features',
                    'label' => 'Features',
                    'name' => 'service_features',
                    'type' => 'repeater',
                    'instructions' => 'Add service features',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_feature_title',
                            'label' => 'Title',
                            'name' => 'title',
                            'type' => 'text',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_feature_description',
                            'label' => 'Description',
                            'name' => 'description',
                            'type' => 'textarea',
                            'required' => 1,
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'service',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'wades_add_custom_fields');

/**
 * Flush rewrite rules on theme activation
 */
function wades_rewrite_flush() {
    wades_register_post_types();
    wades_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'wades_rewrite_flush');

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
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'boats'),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-ship',
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('boat', $args);

    // Register Boat Manufacturer Taxonomy
    register_taxonomy('boat_manufacturer', 'boat', array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => 'Manufacturers',
            'singular_name'     => 'Manufacturer',
            'search_items'      => 'Search Manufacturers',
            'all_items'         => 'All Manufacturers',
            'edit_item'         => 'Edit Manufacturer',
            'update_item'       => 'Update Manufacturer',
            'add_new_item'      => 'Add New Manufacturer',
            'new_item_name'     => 'New Manufacturer Name',
            'menu_name'         => 'Manufacturers',
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'manufacturer'),
    ));
}
add_action('init', 'wades_register_boat_post_type'); 