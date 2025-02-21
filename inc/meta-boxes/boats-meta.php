<?php
/**
 * Meta Boxes for Boats Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Boats template
 */
function wades_boats_meta_boxes() {
    // Get the current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get the current template
    $template = get_page_template_slug(get_the_ID());
    
    if ($template === 'templates/boats.php') {
        add_meta_box(
            'boats_content',
            'Boats Page Settings',
            'wades_boats_content_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'wades_boats_meta_boxes');

/**
 * Boats Content Meta Box Callback
 */
function wades_boats_content_callback($post) {
    wp_nonce_field('wades_boats_meta', 'wades_boats_meta_nonce');

    $meta = array(
        'boats_title' => get_post_meta($post->ID, '_boats_title', true),
        'boats_description' => get_post_meta($post->ID, '_boats_description', true),
        'boats_seo_title' => get_post_meta($post->ID, '_boats_seo_title', true),
        'boats_seo_description' => get_post_meta($post->ID, '_boats_seo_description', true)
    );
    ?>
    <div class="boats-meta-box">
        <!-- Page Content Settings -->
        <div class="meta-box-section">
            <h3>Page Content</h3>
            <p>
                <label for="boats_title">Page Title:</label><br>
                <input type="text" id="boats_title" name="boats_title" value="<?php echo esc_attr($meta['boats_title']); ?>" class="widefat">
            </p>
            <p>
                <label for="boats_description">Page Description:</label><br>
                <textarea id="boats_description" name="boats_description" rows="3" class="widefat"><?php echo esc_textarea($meta['boats_description']); ?></textarea>
            </p>
        </div>

        <!-- SEO Settings -->
        <div class="meta-box-section">
            <h3>SEO Settings</h3>
            <p>
                <label for="boats_seo_title">SEO Title:</label><br>
                <input type="text" id="boats_seo_title" name="boats_seo_title" value="<?php echo esc_attr($meta['boats_seo_title']); ?>" class="widefat">
                <span class="description">Leave blank to use the page title</span>
            </p>
            <p>
                <label for="boats_seo_description">SEO Description:</label><br>
                <textarea id="boats_seo_description" name="boats_seo_description" rows="3" class="widefat"><?php echo esc_textarea($meta['boats_seo_description']); ?></textarea>
                <span class="description">Leave blank to use the page description</span>
            </p>
        </div>
    </div>

    <style>
        .meta-box-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .meta-box-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .description {
            color: #666;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
    </style>
    <?php
}

/**
 * Save Boats Meta Box Data
 */
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

    // Save page content settings
    $fields = array(
        'boats_title',
        'boats_description',
        'boats_seo_title',
        'boats_seo_description'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                '_' . $field,
                $field === 'boats_description' || $field === 'boats_seo_description'
                    ? wp_kses_post($_POST[$field])
                    : sanitize_text_field($_POST[$field])
            );
        }
    }
}
add_action('save_post', 'wades_save_boats_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_boats_admin_scripts($hook) {
    global $post;
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if (is_object($post) && get_page_template_slug($post->ID) == 'templates/boats.php') {
            wp_enqueue_media();
            wp_enqueue_script(
                'boats-admin',
                get_template_directory_uri() . '/assets/js/boats-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                _S_VERSION,
                true
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_boats_admin_scripts'); 