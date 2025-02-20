<?php
/**
 * Boats Page Meta Boxes
 */

function wades_boats_meta_boxes() {
    add_meta_box(
        'wades_boats_settings',
        'Boats Page Settings',
        'wades_boats_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_boats_meta_boxes');

function wades_boats_settings_callback($post) {
    wp_nonce_field('wades_boats_meta', 'wades_boats_meta_nonce');

    $settings = array(
        'title' => get_post_meta($post->ID, '_boats_page_title', true),
        'description' => get_post_meta($post->ID, '_boats_page_description', true),
        'posts_per_page' => get_post_meta($post->ID, '_boats_per_page', true) ?: 12
    );
    ?>
    <div class="boats-meta-box">
        <p>
            <label for="boats_page_title">Page Title:</label>
            <input type="text" id="boats_page_title" name="boats_page_title" 
                   value="<?php echo esc_attr($settings['title']); ?>" class="widefat">
        </p>
        <p>
            <label for="boats_page_description">Page Description:</label>
            <textarea id="boats_page_description" name="boats_page_description" 
                      class="widefat" rows="4"><?php echo esc_textarea($settings['description']); ?></textarea>
        </p>
        <p>
            <label for="boats_per_page">Boats per page:</label>
            <input type="number" id="boats_per_page" name="boats_per_page" 
                   value="<?php echo esc_attr($settings['posts_per_page']); ?>" min="1" max="100">
        </p>
    </div>
    <?php
}

function wades_save_boats_meta($post_id) {
    if (!isset($_POST['wades_boats_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_boats_meta_nonce'], 'wades_boats_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'boats_page_title' => 'text',
        'boats_page_description' => 'textarea',
        'boats_per_page' => 'number'
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
}
add_action('save_post', 'wades_save_boats_meta'); 