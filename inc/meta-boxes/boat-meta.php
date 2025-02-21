<?php
/**
 * Boat Meta Boxes
 */

function wades_add_boat_meta_boxes() {
    add_meta_box(
        'boat_details',
        'Boat Details',
        'wades_boat_details_callback',
        'boat',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_boat_meta_boxes');

function wades_boat_details_callback($post) {
    wp_nonce_field('wades_boat_details', 'wades_boat_details_nonce');

    $boat_meta = array(
        'condition' => get_post_meta($post->ID, '_boat_condition', true),
        'price' => get_post_meta($post->ID, '_boat_price', true),
        'status' => get_post_meta($post->ID, '_boat_status', true),
        'year' => get_post_meta($post->ID, '_boat_year', true),
        'type' => get_post_meta($post->ID, '_boat_type', true),
        'model' => get_post_meta($post->ID, '_boat_model', true),
        'featured' => get_post_meta($post->ID, '_boat_featured', true),
        'length' => get_post_meta($post->ID, '_boat_length', true),
        'capacity' => get_post_meta($post->ID, '_boat_capacity', true),
        'speed' => get_post_meta($post->ID, '_boat_speed', true)
    );
    ?>
    <div class="boat-meta-box">
        <p>
            <label for="boat_featured">
                <input type="checkbox" id="boat_featured" name="boat_featured" value="1" <?php checked($boat_meta['featured'], '1'); ?>>
                Feature this boat on homepage
            </label>
        </p>
        <p>
            <label for="boat_condition">Condition:</label>
            <select name="boat_condition" id="boat_condition" class="widefat">
                <option value="new" <?php selected($boat_meta['condition'], 'new'); ?>>New</option>
                <option value="used" <?php selected($boat_meta['condition'], 'used'); ?>>Used</option>
            </select>
        </p>
        <p>
            <label for="boat_price">Price:</label>
            <input type="number" id="boat_price" name="boat_price" value="<?php echo esc_attr($boat_meta['price']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_status">Status:</label>
            <select name="boat_status" id="boat_status" class="widefat">
                <option value="Available" <?php selected($boat_meta['status'], 'Available'); ?>>Available</option>
                <option value="Sold" <?php selected($boat_meta['status'], 'Sold'); ?>>Sold</option>
                <option value="On Order" <?php selected($boat_meta['status'], 'On Order'); ?>>On Order</option>
            </select>
        </p>
        <p>
            <label for="boat_year">Year:</label>
            <input type="number" id="boat_year" name="boat_year" value="<?php echo esc_attr($boat_meta['year']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_type">Type:</label>
            <input type="text" id="boat_type" name="boat_type" value="<?php echo esc_attr($boat_meta['type']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_model">Model:</label>
            <input type="text" id="boat_model" name="boat_model" value="<?php echo esc_attr($boat_meta['model']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_length">Length (ft):</label>
            <input type="number" id="boat_length" name="boat_length" value="<?php echo esc_attr($boat_meta['length']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_capacity">Capacity (guests):</label>
            <input type="number" id="boat_capacity" name="boat_capacity" value="<?php echo esc_attr($boat_meta['capacity']); ?>" class="widefat">
        </p>
        <p>
            <label for="boat_speed">Speed (knots):</label>
            <input type="number" id="boat_speed" name="boat_speed" value="<?php echo esc_attr($boat_meta['speed']); ?>" class="widefat">
        </p>
    </div>
    <?php
}

function wades_save_boat_meta($post_id) {
    if (!isset($_POST['wades_boat_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_boat_details_nonce'], 'wades_boat_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'boat_condition' => 'text',
        'boat_price' => 'number',
        'boat_status' => 'text',
        'boat_year' => 'number',
        'boat_type' => 'text',
        'boat_model' => 'text',
        'boat_length' => 'number',
        'boat_capacity' => 'number',
        'boat_speed' => 'number'
    );

    foreach ($fields as $field => $type) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            if ($type === 'number') {
                $value = absint($value);
            } else {
                $value = sanitize_text_field($value);
            }
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    // Save featured status
    $featured = isset($_POST['boat_featured']) ? '1' : '';
    update_post_meta($post_id, '_boat_featured', $featured);
}
add_action('save_post_boat', 'wades_save_boat_meta'); 