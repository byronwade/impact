<?php
/**
 * Service Post Type
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Service Post Type
 */
function wades_register_service_post_type() {
    $labels = array(
        'name'               => _x('Services', 'post type general name', 'wades'),
        'singular_name'      => _x('Service', 'post type singular name', 'wades'),
        'menu_name'         => _x('Services', 'admin menu', 'wades'),
        'name_admin_bar'    => _x('Service', 'add new on admin bar', 'wades'),
        'add_new'           => _x('Add New', 'service', 'wades'),
        'add_new_item'      => __('Add New Service', 'wades'),
        'new_item'          => __('New Service', 'wades'),
        'edit_item'         => __('Edit Service', 'wades'),
        'view_item'         => __('View Service', 'wades'),
        'all_items'         => __('All Services', 'wades'),
        'search_items'      => __('Search Services', 'wades'),
        'not_found'         => __('No services found.', 'wades'),
        'not_found_in_trash'=> __('No services found in Trash.', 'wades')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'services'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 5,
        'menu_icon'         => 'dashicons-admin-tools',
        'supports'          => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'      => false
    );

    register_post_type('service', $args);
}
add_action('init', 'wades_register_service_post_type');

/**
 * Add Service Meta Boxes
 */
function wades_add_service_meta_boxes() {
    add_meta_box(
        'service_details',
        __('Service Details', 'wades'),
        'wades_service_details_callback',
        'service',
        'normal',
        'high'
    );

    add_meta_box(
        'service_features',
        __('Service Features', 'wades'),
        'wades_service_features_callback',
        'service',
        'normal',
        'high'
    );

    add_meta_box(
        'service_display',
        __('Display Settings', 'wades'),
        'wades_service_display_callback',
        'service',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'wades_add_service_meta_boxes');

/**
 * Service Details Meta Box Callback
 */
function wades_service_details_callback($post) {
    wp_nonce_field('wades_service_meta', 'wades_service_meta_nonce');

    $meta = array(
        'icon' => get_post_meta($post->ID, '_service_icon', true),
        'icon_color' => get_post_meta($post->ID, '_service_icon_color', true) ?: '#0f766e', // Default teal color
        'price' => get_post_meta($post->ID, '_service_price', true),
        'duration' => get_post_meta($post->ID, '_service_duration', true),
        'location' => get_post_meta($post->ID, '_service_location', true),
        'card_style' => get_post_meta($post->ID, '_card_style', true) ?: 'default',
        'hover_effect' => get_post_meta($post->ID, '_hover_effect', true) ?: 'scale',
        'seo_title' => get_post_meta($post->ID, '_seo_title', true),
        'seo_description' => get_post_meta($post->ID, '_seo_description', true),
        'schema_type' => get_post_meta($post->ID, '_schema_type', true) ?: 'Service'
    );
    ?>
    <div class="service-meta-box">
        <div class="service-meta-tabs">
            <button type="button" class="tab-button active" data-tab="basic">Basic Info</button>
            <button type="button" class="tab-button" data-tab="appearance">Appearance</button>
            <button type="button" class="tab-button" data-tab="seo">SEO & Schema</button>
        </div>

        <div class="tab-content active" data-tab="basic">
            <p>
                <label for="service_icon">Icon (Lucide icon name):</label><br>
                <div class="icon-selector">
                    <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr($meta['icon']); ?>" class="widefat">
                    <button type="button" class="button icon-picker">Browse Icons</button>
                </div>
                <span class="description">Enter a Lucide icon name (e.g., 'wrench', 'anchor', 'tool')</span>
            </p>
            <p>
                <label for="service_icon_color">Icon Color:</label><br>
                <input type="color" id="service_icon_color" name="service_icon_color" value="<?php echo esc_attr($meta['icon_color']); ?>" class="widefat">
            </p>
            <p>
                <label for="service_price">Starting Price:</label><br>
                <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($meta['price']); ?>" class="widefat">
                <span class="description">Enter the starting price or price range</span>
            </p>
            <p>
                <label for="service_duration">Estimated Duration:</label><br>
                <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($meta['duration']); ?>" class="widefat">
                <span class="description">e.g., "2-3 hours", "1-2 days"</span>
            </p>
            <p>
                <label for="service_location">Service Location:</label><br>
                <select id="service_location" name="service_location" class="widefat">
                    <option value="shop" <?php selected($meta['location'], 'shop'); ?>>At Our Shop</option>
                    <option value="mobile" <?php selected($meta['location'], 'mobile'); ?>>Mobile Service</option>
                    <option value="both" <?php selected($meta['location'], 'both'); ?>>Both Available</option>
                </select>
            </p>
        </div>

        <div class="tab-content" data-tab="appearance">
            <p>
                <label for="card_style">Card Style:</label><br>
                <select id="card_style" name="card_style" class="widefat">
                    <option value="default" <?php selected($meta['card_style'], 'default'); ?>>Default</option>
                    <option value="minimal" <?php selected($meta['card_style'], 'minimal'); ?>>Minimal</option>
                    <option value="featured" <?php selected($meta['card_style'], 'featured'); ?>>Featured</option>
                    <option value="bordered" <?php selected($meta['card_style'], 'bordered'); ?>>Bordered</option>
                </select>
            </p>
            <p>
                <label for="hover_effect">Hover Effect:</label><br>
                <select id="hover_effect" name="hover_effect" class="widefat">
                    <option value="scale" <?php selected($meta['hover_effect'], 'scale'); ?>>Scale</option>
                    <option value="lift" <?php selected($meta['hover_effect'], 'lift'); ?>>Lift</option>
                    <option value="glow" <?php selected($meta['hover_effect'], 'glow'); ?>>Glow</option>
                    <option value="none" <?php selected($meta['hover_effect'], 'none'); ?>>None</option>
                </select>
            </p>
        </div>

        <div class="tab-content" data-tab="seo">
            <p>
                <label for="seo_title">SEO Title:</label><br>
                <input type="text" id="seo_title" name="seo_title" value="<?php echo esc_attr($meta['seo_title']); ?>" class="widefat">
                <span class="description">Override the default title for search engines (optional)</span>
            </p>
            <p>
                <label for="seo_description">SEO Description:</label><br>
                <textarea id="seo_description" name="seo_description" rows="3" class="widefat"><?php echo esc_textarea($meta['seo_description']); ?></textarea>
                <span class="description">Custom meta description for search engines (optional)</span>
            </p>
            <p>
                <label for="schema_type">Schema Type:</label><br>
                <select id="schema_type" name="schema_type" class="widefat">
                    <option value="Service" <?php selected($meta['schema_type'], 'Service'); ?>>Service</option>
                    <option value="MaintenanceService" <?php selected($meta['schema_type'], 'MaintenanceService'); ?>>Maintenance Service</option>
                    <option value="RepairService" <?php selected($meta['schema_type'], 'RepairService'); ?>>Repair Service</option>
                    <option value="ProfessionalService" <?php selected($meta['schema_type'], 'ProfessionalService'); ?>>Professional Service</option>
                </select>
            </p>
        </div>
    </div>

    <style>
        .service-meta-tabs {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .tab-button {
            padding: 10px 20px;
            margin-right: 5px;
            border: none;
            background: none;
            cursor: pointer;
        }
        .tab-button.active {
            border-bottom: 2px solid #2271b1;
            font-weight: bold;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .icon-selector {
            display: flex;
            gap: 10px;
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Tab functionality
            $('.tab-button').on('click', function() {
                $('.tab-button').removeClass('active');
                $('.tab-content').removeClass('active');
                $(this).addClass('active');
                $('.tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
            });
        });
    </script>
    <?php
}

/**
 * Service Features Meta Box Callback
 */
function wades_service_features_callback($post) {
    $features = get_post_meta($post->ID, '_service_features', true) ?: array();
    ?>
    <div class="features-container">
        <div class="features-list">
            <?php 
            if (!empty($features)) :
                foreach ($features as $index => $feature) : 
            ?>
                <p>
                    <input type="text" name="service_features[]" value="<?php echo esc_attr($feature); ?>" class="widefat">
                    <button type="button" class="button remove-feature">Remove</button>
                </p>
            <?php 
                endforeach;
            else :
            ?>
                <p>
                    <input type="text" name="service_features[]" class="widefat" placeholder="Enter a feature">
                </p>
            <?php endif; ?>
        </div>
        <button type="button" class="button add-feature">Add Feature</button>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $('.add-feature').on('click', function() {
                $('.features-list').append('<p><input type="text" name="service_features[]" class="widefat" placeholder="Enter a feature"><button type="button" class="button remove-feature">Remove</button></p>');
            });

            $(document).on('click', '.remove-feature', function() {
                $(this).parent('p').remove();
            });

            $('.features-list').sortable({
                cursor: 'move',
                opacity: 0.6
            });
        });
    </script>
    <?php
}

/**
 * Service Display Settings Meta Box Callback
 */
function wades_service_display_callback($post) {
    $show_on_home = get_post_meta($post->ID, '_show_on_home', true);
    $home_order = get_post_meta($post->ID, '_home_order', true) ?: 0;
    ?>
    <p>
        <label>
            <input type="checkbox" name="show_on_home" value="1" <?php checked($show_on_home, '1'); ?>>
            Show on Homepage
        </label>
    </p>
    <p>
        <label for="home_order">Homepage Display Order:</label><br>
        <input type="number" id="home_order" name="home_order" value="<?php echo esc_attr($home_order); ?>" class="small-text">
        <span class="description">Lower numbers appear first</span>
    </p>
    <?php
}

/**
 * Save Service Meta Box Data
 */
function wades_save_service_meta($post_id) {
    if (!isset($_POST['wades_service_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_service_meta_nonce'], 'wades_service_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save service details
    $fields = array(
        'service_icon' => 'text',
        'service_icon_color' => 'text',
        'service_price' => 'text',
        'service_duration' => 'text',
        'service_location' => 'text',
        'card_style' => 'text',
        'hover_effect' => 'text',
        'seo_title' => 'text',
        'seo_description' => 'textarea',
        'schema_type' => 'text'
    );

    foreach ($fields as $field => $type) {
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? wp_kses_post($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    // Save features
    if (isset($_POST['service_features'])) {
        $features = array_map('sanitize_text_field', array_filter($_POST['service_features']));
        update_post_meta($post_id, '_service_features', $features);
    }

    // Save display settings
    update_post_meta($post_id, '_show_on_home', isset($_POST['show_on_home']) ? '1' : '');
    update_post_meta($post_id, '_home_order', isset($_POST['home_order']) ? absint($_POST['home_order']) : 0);
}
add_action('save_post_service', 'wades_save_service_meta');

/**
 * Add custom columns to service list
 */
function wades_service_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['service_type'] = __('Type', 'wades');
            $new_columns['service_price'] = __('Price', 'wades');
            $new_columns['service_location'] = __('Location', 'wades');
        } else if ($key === 'date') {
            $new_columns['service_type'] = __('Type', 'wades');
            $new_columns['service_price'] = __('Price', 'wades');
            $new_columns['service_location'] = __('Location', 'wades');
            $new_columns[$key] = $value;
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_service_posts_columns', 'wades_service_columns');

/**
 * Add content to custom columns
 */
function wades_service_column_content($column, $post_id) {
    switch ($column) {
        case 'service_type':
            $type = get_post_meta($post_id, '_service_type', true);
            $types = array(
                'maintenance' => 'Maintenance',
                'repair' => 'Repair',
                'installation' => 'Installation',
                'inspection' => 'Inspection',
                'winterization' => 'Winterization',
                'detailing' => 'Detailing'
            );
            echo isset($types[$type]) ? esc_html($types[$type]) : '';
            break;

        case 'service_price':
            $price = get_post_meta($post_id, '_service_base_price', true);
            $type = get_post_meta($post_id, '_service_price_type', true);
            if ($type === 'quote') {
                echo 'Quote Required';
            } else if ($price) {
                echo '$' . esc_html($price);
                if ($type === 'starting') {
                    echo ' (Starting)';
                } else if ($type === 'hourly') {
                    echo '/hour';
                }
            }
            break;

        case 'service_location':
            $location = get_post_meta($post_id, '_service_location', true);
            $locations = array(
                'shop' => 'At Shop',
                'mobile' => 'Mobile',
                'both' => 'Both'
            );
            echo isset($locations[$location]) ? esc_html($locations[$location]) : '';
            break;
    }
}
add_action('manage_service_posts_custom_column', 'wades_service_column_content', 10, 2); 