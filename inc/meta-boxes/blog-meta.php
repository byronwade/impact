<?php
/**
 * Custom Meta Boxes for Blog Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add meta boxes for Blog template
 */
function wades_blog_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the blog template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        
        if ($template === 'templates/blog.php' || basename($template) === 'blog.php') {
            add_meta_box(
                'blog_settings',
                'Blog Page Settings',
                'wades_blog_settings_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_blog_meta_boxes');

/**
 * Blog Settings Meta Box Callback
 */
function wades_blog_settings_callback($post) {
    wp_nonce_field('wades_blog_meta', 'wades_blog_meta_nonce');

    $meta = array(
        'blog_title' => get_post_meta($post->ID, '_blog_title', true),
        'blog_description' => get_post_meta($post->ID, '_blog_description', true),
        'show_featured_post' => get_post_meta($post->ID, '_show_featured_post', true) !== 'no',
        'show_categories' => get_post_meta($post->ID, '_show_categories', true) !== 'no',
        'posts_per_page' => get_post_meta($post->ID, '_posts_per_page', true) ?: 9,
        'blog_layout' => get_post_meta($post->ID, '_blog_layout', true) ?: 'grid'
    );
    ?>
    <div class="blog-meta-box">
        <p>
            <label for="blog_title">Blog Title:</label><br>
            <input type="text" id="blog_title" name="blog_title" value="<?php echo esc_attr($meta['blog_title']); ?>" class="widefat" placeholder="Latest News & Updates">
            <span class="description">Leave empty to use default title</span>
        </p>

        <p>
            <label for="blog_description">Blog Description:</label><br>
            <textarea id="blog_description" name="blog_description" rows="3" class="widefat"><?php echo esc_textarea($meta['blog_description']); ?></textarea>
            <span class="description">Leave empty to use default description</span>
        </p>

        <p>
            <label>
                <input type="checkbox" name="show_featured_post" value="1" <?php checked($meta['show_featured_post']); ?>>
                Show Featured Post
            </label>
        </p>

        <p>
            <label>
                <input type="checkbox" name="show_categories" value="1" <?php checked($meta['show_categories']); ?>>
                Show Categories Filter
            </label>
        </p>

        <p>
            <label for="posts_per_page">Posts Per Page:</label><br>
            <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo esc_attr($meta['posts_per_page']); ?>" class="small-text" min="1" max="100">
        </p>

        <p>
            <label for="blog_layout">Layout:</label><br>
            <select id="blog_layout" name="blog_layout" class="widefat">
                <option value="grid" <?php selected($meta['blog_layout'], 'grid'); ?>>Grid</option>
                <option value="list" <?php selected($meta['blog_layout'], 'list'); ?>>List</option>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Save Blog Meta Box Data
 */
function wades_save_blog_meta($post_id) {
    if (!isset($_POST['wades_blog_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_blog_meta_nonce'], 'wades_blog_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save text fields
    $text_fields = array(
        'blog_title',
        'blog_description',
        'blog_layout'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save checkboxes
    $checkboxes = array(
        'show_featured_post',
        'show_categories'
    );

    foreach ($checkboxes as $field) {
        update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? 'yes' : 'no');
    }

    // Save posts per page
    if (isset($_POST['posts_per_page'])) {
        $posts_per_page = absint($_POST['posts_per_page']);
        if ($posts_per_page > 0) {
            update_post_meta($post_id, '_posts_per_page', $posts_per_page);
        }
    }
}
add_action('save_post', 'wades_save_blog_meta'); 