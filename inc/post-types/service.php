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
        'name'                  => _x('Services', 'Post type general name', 'wades'),
        'singular_name'         => _x('Service', 'Post type singular name', 'wades'),
        'menu_name'            => _x('Services', 'Admin Menu text', 'wades'),
        'name_admin_bar'        => _x('Service', 'Add New on Toolbar', 'wades'),
        'add_new'              => __('Add New', 'wades'),
        'add_new_item'         => __('Add New Service', 'wades'),
        'new_item'             => __('New Service', 'wades'),
        'edit_item'            => __('Edit Service', 'wades'),
        'view_item'            => __('View Service', 'wades'),
        'all_items'            => __('All Services', 'wades'),
        'search_items'         => __('Search Services', 'wades'),
        'not_found'            => __('No services found.', 'wades'),
        'not_found_in_trash'   => __('No services found in Trash.', 'wades'),
        'featured_image'       => __('Service Image', 'wades'),
        'set_featured_image'   => __('Set service image', 'wades'),
        'remove_featured_image' => __('Remove service image', 'wades'),
        'use_featured_image'   => __('Use as service image', 'wades'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'          => 'dashicons-admin-tools',
        'hierarchical'        => false,
        'supports'            => array('title', 'thumbnail'),
        'has_archive'         => true,
        'rewrite'            => array('slug' => 'services'),
        'show_in_rest'       => false, // Disable Gutenberg
        'publicly_queryable' => true,
        'capability_type'    => 'post',
    );

    register_post_type('service', $args);
}
add_action('init', 'wades_register_service_post_type');

/**
 * Add meta boxes for Service post type
 */
function wades_add_service_meta_boxes() {
    add_meta_box(
        'service_details',
        'Service Details',
        'wades_service_details_callback',
        'service',
        'normal',
        'high'
    );

    add_meta_box(
        'service_pricing',
        'Service Pricing',
        'wades_service_pricing_callback',
        'service',
        'normal',
        'high'
    );

    add_meta_box(
        'service_features',
        'Service Features',
        'wades_service_features_callback',
        'service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_service_meta_boxes');

/**
 * Service Details Meta Box Callback
 */
function wades_service_details_callback($post) {
    wp_nonce_field('wades_service_meta', 'wades_service_meta_nonce');

    $service_meta = array(
        'short_description' => get_post_meta($post->ID, '_service_short_description', true),
        'service_type' => get_post_meta($post->ID, '_service_type', true),
        'estimated_time' => get_post_meta($post->ID, '_service_estimated_time', true),
        'service_location' => get_post_meta($post->ID, '_service_location', true),
    );

    $service_types = array(
        'maintenance' => 'Maintenance',
        'repair' => 'Repair',
        'installation' => 'Installation',
        'inspection' => 'Inspection',
        'winterization' => 'Winterization',
        'detailing' => 'Detailing'
    );
    ?>
    <div class="service-meta-box">
        <p>
            <label for="service_short_description">Short Description:</label><br>
            <textarea id="service_short_description" name="service_short_description" rows="3" class="widefat"><?php echo esc_textarea($service_meta['short_description']); ?></textarea>
            <span class="description">Brief description of the service (displayed in listings and summaries)</span>
        </p>

        <p>
            <label for="service_type">Service Type:</label><br>
            <select id="service_type" name="service_type" class="widefat">
                <option value="">Select Type</option>
                <?php foreach ($service_types as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($service_meta['service_type'], $value); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="service_estimated_time">Estimated Time:</label><br>
            <input type="text" id="service_estimated_time" name="service_estimated_time" value="<?php echo esc_attr($service_meta['estimated_time']); ?>" class="widefat">
            <span class="description">e.g., "2-3 hours", "1-2 days"</span>
        </p>

        <p>
            <label for="service_location">Service Location:</label><br>
            <select id="service_location" name="service_location" class="widefat">
                <option value="shop" <?php selected($service_meta['service_location'], 'shop'); ?>>At Our Shop</option>
                <option value="mobile" <?php selected($service_meta['service_location'], 'mobile'); ?>>Mobile Service</option>
                <option value="both" <?php selected($service_meta['service_location'], 'both'); ?>>Both Available</option>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Service Pricing Meta Box Callback
 */
function wades_service_pricing_callback($post) {
    $pricing_meta = array(
        'base_price' => get_post_meta($post->ID, '_service_base_price', true),
        'price_type' => get_post_meta($post->ID, '_service_price_type', true),
        'price_note' => get_post_meta($post->ID, '_service_price_note', true),
    );
    ?>
    <div class="pricing-meta-box">
        <p>
            <label for="service_base_price">Base Price:</label><br>
            <input type="text" id="service_base_price" name="service_base_price" value="<?php echo esc_attr($pricing_meta['base_price']); ?>" class="widefat">
            <span class="description">Enter the base price or starting price for this service</span>
        </p>

        <p>
            <label for="service_price_type">Price Type:</label><br>
            <select id="service_price_type" name="service_price_type" class="widefat">
                <option value="fixed" <?php selected($pricing_meta['price_type'], 'fixed'); ?>>Fixed Price</option>
                <option value="starting" <?php selected($pricing_meta['price_type'], 'starting'); ?>>Starting At</option>
                <option value="hourly" <?php selected($pricing_meta['price_type'], 'hourly'); ?>>Per Hour</option>
                <option value="quote" <?php selected($pricing_meta['price_type'], 'quote'); ?>>Quote Required</option>
            </select>
        </p>

        <p>
            <label for="service_price_note">Pricing Note:</label><br>
            <textarea id="service_price_note" name="service_price_note" rows="2" class="widefat"><?php echo esc_textarea($pricing_meta['price_note']); ?></textarea>
            <span class="description">Additional information about pricing (e.g., "Price may vary based on boat size")</span>
        </p>
    </div>
    <?php
}

/**
 * Service Features Meta Box Callback
 */
function wades_service_features_callback($post) {
    $features = get_post_meta($post->ID, '_service_features', true);
    
    if (!is_array($features)) {
        $features = array();
    }
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
 * Save Service Meta Box Data
 */
function wades_save_service_meta($post_id) {
    // Check if our nonce is set and verify it
    if (!isset($_POST['wades_service_meta_nonce']) || !wp_verify_nonce($_POST['wades_service_meta_nonce'], 'wades_service_meta')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Service Details
    if (isset($_POST['service_short_description'])) {
        update_post_meta($post_id, '_service_short_description', wp_kses_post($_POST['service_short_description']));
    }
    if (isset($_POST['service_type'])) {
        update_post_meta($post_id, '_service_type', sanitize_text_field($_POST['service_type']));
    }
    if (isset($_POST['service_estimated_time'])) {
        update_post_meta($post_id, '_service_estimated_time', sanitize_text_field($_POST['service_estimated_time']));
    }
    if (isset($_POST['service_location'])) {
        update_post_meta($post_id, '_service_location', sanitize_text_field($_POST['service_location']));
    }

    // Pricing
    if (isset($_POST['service_base_price'])) {
        update_post_meta($post_id, '_service_base_price', sanitize_text_field($_POST['service_base_price']));
    }
    if (isset($_POST['service_price_type'])) {
        update_post_meta($post_id, '_service_price_type', sanitize_text_field($_POST['service_price_type']));
    }
    if (isset($_POST['service_price_note'])) {
        update_post_meta($post_id, '_service_price_note', sanitize_textarea_field($_POST['service_price_note']));
    }

    // Features
    if (isset($_POST['service_features'])) {
        $features = array_map('sanitize_text_field', array_filter($_POST['service_features']));
        update_post_meta($post_id, '_service_features', $features);
    }
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