<?php
/**
 * Custom Meta Boxes for Blog/Posts Page
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Debug function
 */
function wades_blog_meta_debug_log($message) {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        error_log('BLOG META: ' . print_r($message, true));
    }
}

/**
 * Disable Gutenberg for Blog pages
 */
function wades_disable_gutenberg_for_blog($use_block_editor, $post_type) {
    if ($post_type === 'page') {
        $current_post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : false);
        if (!$current_post_id) {
            return $use_block_editor;
        }

        $posts_page_id = get_option('page_for_posts');
        $template = get_page_template_slug($current_post_id);

        if ($current_post_id == $posts_page_id || $template === 'templates/blog.php') {
            return false;
        }
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'wades_disable_gutenberg_for_blog', 10, 2);

/**
 * Add meta boxes for Blog/Posts page
 */
function wades_add_blog_meta_boxes($post_type) {
    // Only add meta box for pages
    if ($post_type !== 'page') {
        return;
    }

    // Get current post ID
    $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : false);
    if (!$post_id) {
        return;
    }

    // Check if using blog template
    $template = get_page_template_slug($post_id);
    if ($template === 'templates/blog.php') {
        add_meta_box(
            'wades_blog_settings',
            'Blog Page Settings',
            'wades_blog_settings_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'wades_add_blog_meta_boxes', 1);

/**
 * Callback function for blog settings meta box
 */
function wades_blog_settings_callback($post) {
    // Add nonce for security
    wp_nonce_field('wades_blog_meta', 'wades_blog_meta_nonce');

    // Get saved values with defaults
    $meta = array(
        // Header Settings
        'custom_header_title' => get_post_meta($post->ID, '_custom_header_title', true) ?: 'Latest News & Updates',
        'custom_header_subtext' => get_post_meta($post->ID, '_custom_header_subtext', true) ?: 'Stay informed with our latest articles, tips, and industry insights.',
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true),
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '70',
        
        // Layout Settings
        'show_featured' => get_post_meta($post->ID, '_show_featured', true) !== 'no',
        'show_categories' => get_post_meta($post->ID, '_show_categories', true) !== 'no',
        'posts_per_page' => absint(get_post_meta($post->ID, '_posts_per_page', true)) ?: 9,
        'show_sidebar' => get_post_meta($post->ID, '_show_sidebar', true) !== 'no',
        'grid_columns' => get_post_meta($post->ID, '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'card_style' => get_post_meta($post->ID, '_card_style', true) ?: 'default',
        'hover_effect' => get_post_meta($post->ID, '_hover_effect', true) ?: 'scale',
        
        // Featured Post Settings
        'featured_post_id' => get_post_meta($post->ID, '_featured_post_id', true),
        'featured_layout' => get_post_meta($post->ID, '_featured_layout', true) ?: 'horizontal',
        
        // Post Card Settings
        'show_excerpt' => get_post_meta($post->ID, '_show_excerpt', true) !== 'no',
        'excerpt_length' => get_post_meta($post->ID, '_excerpt_length', true) ?: 20,
        'show_author' => get_post_meta($post->ID, '_show_author', true) !== 'no',
        'show_date' => get_post_meta($post->ID, '_show_date', true) !== 'no',
        'show_read_time' => get_post_meta($post->ID, '_show_read_time', true) !== 'no',
        
        // Pagination Settings
        'pagination_style' => get_post_meta($post->ID, '_pagination_style', true) ?: 'numbered',
        'load_more_text' => get_post_meta($post->ID, '_load_more_text', true) ?: 'Load More Posts'
    );
    ?>
    <div class="wades-meta-box-container">
        <!-- Tab Navigation -->
        <div class="wades-meta-box-tabs">
            <button type="button" class="wades-tab-button active" data-tab="header">Header</button>
            <button type="button" class="wades-tab-button" data-tab="layout">Layout</button>
            <button type="button" class="wades-tab-button" data-tab="featured">Featured Post</button>
            <button type="button" class="wades-tab-button" data-tab="cards">Post Cards</button>
            <button type="button" class="wades-tab-button" data-tab="pagination">Pagination</button>
        </div>

        <!-- Header Tab -->
        <div class="wades-tab-content active" data-tab="header">
            <div class="wades-meta-section">
                <h3>Page Header Options</h3>
                <div class="wades-meta-field">
                    <label for="custom_header_title"><strong>Header Title</strong></label>
                    <input type="text" id="custom_header_title" name="custom_header_title" 
                           value="<?php echo esc_attr($meta['custom_header_title']); ?>" class="widefat"
                           placeholder="Latest News & Updates">
                    <p class="description">Override the default page title in the header section.</p>
                </div>

                <div class="wades-meta-field">
                    <label for="custom_header_subtext"><strong>Header Description</strong></label>
                    <textarea id="custom_header_subtext" name="custom_header_subtext" 
                            class="widefat" rows="3"
                            placeholder="Stay informed with our latest articles, tips, and industry insights."><?php echo esc_textarea($meta['custom_header_subtext']); ?></textarea>
                    <p class="description">Add a subtitle or description that appears below the main title.</p>
                </div>

                <div class="wades-meta-field">
                    <label><strong>Background Image</strong></label>
                    <input type="hidden" id="hero_background_image" name="hero_background_image" 
                           value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                    <div class="button-group">
                        <button type="button" class="button upload-image">Select Image</button>
                        <button type="button" class="button remove-image">Remove Image</button>
                    </div>
                    <div class="image-preview">
                        <?php 
                        if ($meta['hero_background_image']) : 
                            echo wp_get_attachment_image($meta['hero_background_image'], 'medium');
                        endif; 
                        ?>
                    </div>
                    <p class="description">Recommended size: 1920x1080px or larger</p>
                </div>

                <div class="wades-meta-field">
                    <label for="hero_overlay_opacity"><strong>Overlay Opacity (%)</strong></label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>" class="small-text"
                           min="0" max="100" step="5">
                    <p class="description">Adjust the darkness of the overlay on the background image</p>
                </div>

                <div class="wades-meta-field">
                    <label for="hero_height"><strong>Header Height (vh)</strong></label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>" class="small-text"
                           min="10" max="100" step="5">
                    <p class="description">Set the height of the header (70 = 70% of viewport height)</p>
                </div>
            </div>
        </div>

        <!-- Layout Tab -->
        <div class="wades-tab-content" data-tab="layout">
            <div class="wades-meta-section">
                <h3>Layout Settings</h3>
                <div class="wades-grid wades-grid-2">
                    <div class="wades-meta-field">
                        <label for="grid_columns">Grid Columns:</label>
                        <select id="grid_columns" name="grid_columns" class="widefat">
                            <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-3" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?>>3 Columns (Default)</option>
                            <option value="grid-cols-1 md:grid-cols-2" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2'); ?>>2 Columns</option>
                            <option value="grid-cols-1" <?php selected($meta['grid_columns'], 'grid-cols-1'); ?>>1 Column</option>
                        </select>
                    </div>

                    <div class="wades-meta-field">
                        <label for="card_style">Card Style:</label>
                        <select id="card_style" name="card_style" class="widefat">
                            <option value="default" <?php selected($meta['card_style'], 'default'); ?>>Default</option>
                            <option value="minimal" <?php selected($meta['card_style'], 'minimal'); ?>>Minimal</option>
                            <option value="bordered" <?php selected($meta['card_style'], 'bordered'); ?>>Bordered</option>
                            <option value="featured" <?php selected($meta['card_style'], 'featured'); ?>>Featured</option>
                        </select>
                    </div>
                </div>

                <div class="wades-meta-field">
                    <label for="hover_effect">Hover Effect:</label>
                    <select id="hover_effect" name="hover_effect" class="widefat">
                        <option value="scale" <?php selected($meta['hover_effect'], 'scale'); ?>>Scale</option>
                        <option value="lift" <?php selected($meta['hover_effect'], 'lift'); ?>>Lift</option>
                        <option value="glow" <?php selected($meta['hover_effect'], 'glow'); ?>>Glow</option>
                        <option value="none" <?php selected($meta['hover_effect'], 'none'); ?>>None</option>
                    </select>
                </div>

                <div class="wades-checkbox-group">
                    <label>
                        <input type="checkbox" name="show_featured" value="1" <?php checked($meta['show_featured'], true); ?>>
                        Show Featured Post
                    </label>
                    <label>
                        <input type="checkbox" name="show_categories" value="1" <?php checked($meta['show_categories'], true); ?>>
                        Show Category Filter
                    </label>
                    <label>
                        <input type="checkbox" name="show_sidebar" value="1" <?php checked($meta['show_sidebar'], true); ?>>
                        Show Sidebar
                    </label>
                </div>

                <div class="wades-meta-field">
                    <label for="posts_per_page">Posts Per Page:</label>
                    <input type="number" id="posts_per_page" name="posts_per_page" 
                           value="<?php echo esc_attr($meta['posts_per_page']); ?>" 
                           class="small-text" min="1" max="100">
                </div>
            </div>
        </div>

        <!-- Featured Post Tab -->
        <div class="wades-tab-content" data-tab="featured">
            <div class="wades-meta-section">
                <h3>Featured Post Settings</h3>
                <div class="wades-meta-field">
                    <label for="featured_post_id">Featured Post:</label>
                    <select id="featured_post_id" name="featured_post_id" class="widefat">
                        <option value="">Select a post</option>
                        <?php
                        $recent_posts = get_posts(array(
                            'posts_per_page' => 10,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        foreach ($recent_posts as $recent_post) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr($recent_post->ID),
                                selected($meta['featured_post_id'], $recent_post->ID, false),
                                esc_html($recent_post->post_title)
                            );
                        }
                        ?>
                    </select>
                </div>

                <div class="wades-meta-field">
                    <label for="featured_layout">Featured Post Layout:</label>
                    <select id="featured_layout" name="featured_layout" class="widefat">
                        <option value="horizontal" <?php selected($meta['featured_layout'], 'horizontal'); ?>>Horizontal (Image Left)</option>
                        <option value="vertical" <?php selected($meta['featured_layout'], 'vertical'); ?>>Vertical (Image Top)</option>
                        <option value="overlay" <?php selected($meta['featured_layout'], 'overlay'); ?>>Overlay (Text Over Image)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Post Cards Tab -->
        <div class="wades-tab-content" data-tab="cards">
            <div class="wades-meta-section">
                <h3>Post Card Settings</h3>
                <div class="wades-checkbox-group">
                    <label>
                        <input type="checkbox" name="show_excerpt" value="1" <?php checked($meta['show_excerpt'], true); ?>>
                        Show Excerpt
                    </label>
                    <label>
                        <input type="checkbox" name="show_author" value="1" <?php checked($meta['show_author'], true); ?>>
                        Show Author
                    </label>
                    <label>
                        <input type="checkbox" name="show_date" value="1" <?php checked($meta['show_date'], true); ?>>
                        Show Date
                    </label>
                    <label>
                        <input type="checkbox" name="show_read_time" value="1" <?php checked($meta['show_read_time'], true); ?>>
                        Show Read Time
                    </label>
                </div>

                <div class="wades-meta-field">
                    <label for="excerpt_length">Excerpt Length (words):</label>
                    <input type="number" id="excerpt_length" name="excerpt_length" 
                           value="<?php echo esc_attr($meta['excerpt_length']); ?>" 
                           class="small-text" min="10" max="100">
                </div>
            </div>
        </div>

        <!-- Pagination Tab -->
        <div class="wades-tab-content" data-tab="pagination">
            <div class="wades-meta-section">
                <h3>Pagination Settings</h3>
                <div class="wades-meta-field">
                    <label for="pagination_style">Pagination Style:</label>
                    <select id="pagination_style" name="pagination_style" class="widefat">
                        <option value="numbered" <?php selected($meta['pagination_style'], 'numbered'); ?>>Numbered Pages</option>
                        <option value="load-more" <?php selected($meta['pagination_style'], 'load-more'); ?>>Load More Button</option>
                        <option value="infinite" <?php selected($meta['pagination_style'], 'infinite'); ?>>Infinite Scroll</option>
                    </select>
                </div>

                <div class="wades-meta-field">
                    <label for="load_more_text">Load More Button Text:</label>
                    <input type="text" id="load_more_text" name="load_more_text" 
                           value="<?php echo esc_attr($meta['load_more_text']); ?>" class="widefat">
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.wades-tab-button').on('click', function() {
            $('.wades-tab-button').removeClass('active');
            $('.wades-tab-content').removeClass('active');
            $(this).addClass('active');
            $('.wades-tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        });

        // Image upload functionality
        $('.upload-image').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.wades-meta-field');
            var imageInput = container.find('input[type="hidden"]');
            var imagePreview = container.find('.image-preview');

            var frame = wp.media({
                title: 'Select Image',
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                imageInput.val(attachment.id);
                
                var preview = $('<img>')
                    .attr('src', attachment.sizes.medium.url)
                    .css({
                        'max-width': '300px',
                        'height': 'auto'
                    });
                
                imagePreview.html(preview);
            });

            frame.open();
        });

        // Image removal functionality
        $('.remove-image').on('click', function(e) {
            e.preventDefault();
            var container = $(this).closest('.wades-meta-field');
            container.find('input[type="hidden"]').val('');
            container.find('.image-preview').empty();
        });
    });
    </script>

    <style>
    .wades-meta-box-container {
        margin: -6px -12px -12px;
    }
    .wades-meta-box-tabs {
        background: #f0f0f1;
        border-bottom: 1px solid #ddd;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 0;
    }
    .wades-tab-button {
        padding: 12px 16px;
        border: none;
        background: none;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        color: #646970;
        font-size: 13px;
        font-weight: 500;
    }
    .wades-tab-button:hover {
        color: #2271b1;
        background: #f6f7f7;
    }
    .wades-tab-button.active {
        border-bottom-color: #2271b1;
        color: #1d2327;
        background: #fff;
    }
    .wades-tab-content {
        display: none;
        padding: 20px;
    }
    .wades-tab-content.active {
        display: block;
    }
    .wades-meta-section {
        margin-bottom: 20px;
    }
    .wades-meta-field {
        margin-bottom: 12px;
    }
    .wades-meta-field label {
        display: block;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .wades-checkbox-group {
        margin: 12px 0;
    }
    .wades-checkbox-group label {
        display: block;
        margin-bottom: 8px;
    }
    .wades-grid {
        display: grid;
        gap: 12px;
    }
    .wades-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    </style>
    <?php
}

/**
 * Save Blog Meta Box Data
 */
function wades_save_blog_settings($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['wades_blog_settings_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['wades_blog_settings_nonce'], 'wades_blog_settings_nonce')) {
        return;
    }

    // If this is an autosave, our form has not been submitted
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    // Layout Settings
    $text_fields = array(
        'grid_columns',
        'card_style',
        'hover_effect',
        'featured_layout',
        'pagination_style',
        'load_more_text',
        'seo_title',
        'seo_description',
        'schema_type',
        'article_section'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Numeric Fields
    $numeric_fields = array(
        'posts_per_page' => 9,
        'excerpt_length' => 20,
        'featured_post_id' => 0
    );

    foreach ($numeric_fields as $field => $default) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, absint($_POST[$field]));
        } else {
            update_post_meta($post_id, '_' . $field, $default);
        }
    }

    // Checkbox Fields
    $checkbox_fields = array(
        'show_featured',
        'show_categories',
        'show_sidebar',
        'show_excerpt',
        'show_author',
        'show_date',
        'show_read_time'
    );

    foreach ($checkbox_fields as $field) {
        update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? '1' : 'no');
    }

    // Array Fields
    if (isset($_POST['sidebar_widgets']) && is_array($_POST['sidebar_widgets'])) {
        update_post_meta($post_id, '_sidebar_widgets', array_map('sanitize_text_field', $_POST['sidebar_widgets']));
    }

    if (isset($_POST['excluded_categories']) && is_array($_POST['excluded_categories'])) {
        update_post_meta($post_id, '_excluded_categories', array_map('absint', $_POST['excluded_categories']));
    }
}
add_action('save_post', 'wades_save_blog_settings');

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

    // Save header fields
    $header_fields = array(
        'custom_header_title' => 'sanitize_text_field',
        'custom_header_subtext' => 'wp_kses_post',
        'hero_background_image' => 'absint',
        'hero_overlay_opacity' => function($value) {
            return max(0, min(100, absint($value)));
        },
        'hero_height' => function($value) {
            return max(10, min(100, absint($value)));
        }
    );

    foreach ($header_fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            $value = is_callable($sanitize_callback) 
                ? $sanitize_callback($_POST[$field])
                : call_user_func($sanitize_callback, $_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    // Save layout fields
    $layout_fields = array(
        'show_featured' => 'wades_sanitize_checkbox',
        'show_categories' => 'wades_sanitize_checkbox',
        'posts_per_page' => 'absint',
        'show_sidebar' => 'wades_sanitize_checkbox'
    );

    foreach ($layout_fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitize_callback, $_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'wades_save_blog_meta');

/**
 * Helper function to sanitize checkboxes
 */
function wades_sanitize_checkbox($value) {
    return isset($value) ? '1' : 'no';
}

/**
 * Add Schema.org markup for the blog page
 */
function wades_add_blog_schema($post_id) {
    if (get_page_template_slug($post_id) !== 'templates/blog.php') {
        return;
    }

    $meta = array(
        'schema_type' => get_post_meta($post_id, '_schema_type', true) ?: 'Blog',
        'article_section' => get_post_meta($post_id, '_article_section', true) ?: 'Marine',
        'organization_name' => get_post_meta($post_id, '_organization_name', true) ?: 'Impact Marine Group',
        'organization_logo' => get_post_meta($post_id, '_organization_logo', true)
    );

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $meta['schema_type'],
        'headline' => get_the_title($post_id),
        'description' => get_post_meta($post_id, '_blog_description', true),
        'articleSection' => $meta['article_section'],
        'publisher' => array(
            '@type' => 'Organization',
            'name' => $meta['organization_name'],
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => wp_get_attachment_image_url($meta['organization_logo'], 'full')
            )
        )
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_footer', 'wades_add_blog_schema');

/**
 * Enqueue admin scripts and styles for blog meta boxes
 */
function wades_blog_meta_admin_scripts($hook) {
    // Debug
    wades_blog_meta_debug_log('Admin scripts hook: ' . $hook);
    
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        global $post;
        
        // Debug
        wades_blog_meta_debug_log('Current post type: ' . $post->post_type);
        
        // Get the Posts page ID
        $posts_page_id = get_option('page_for_posts');
        
        // Only enqueue for the posts page
        if ($post->post_type === 'page' && $post->ID == $posts_page_id) {
            wades_blog_meta_debug_log('Enqueuing scripts for Posts page');
            
            // Enqueue WordPress media scripts
            wp_enqueue_media();
            
            // Add inline styles
            wp_add_inline_style('wp-admin', '
                .wades-meta-box-tabs {
                    margin: -6px -12px -12px;
                }
                .wades-tab-buttons {
                    margin: 0;
                    padding: 0;
                    border-bottom: 1px solid #ddd;
                    background: #f0f0f1;
                }
                .wades-tab-button {
                    padding: 8px 12px;
                    margin: 0;
                    border: none;
                    background: none;
                    cursor: pointer;
                    border-bottom: 2px solid transparent;
                }
                .wades-tab-button.active {
                    background: #fff;
                    border-bottom-color: #2271b1;
                }
                .wades-tab-content {
                    display: none;
                    padding: 12px;
                }
                .wades-tab-content.active {
                    display: block;
                }
                .wades-meta-section {
                    margin-bottom: 20px;
                }
                .wades-meta-field {
                    margin-bottom: 12px;
                }
                .wades-meta-field label {
                    display: block;
                    margin-bottom: 4px;
                    font-weight: 600;
                }
                .image-upload-field {
                    margin-top: 8px;
                }
                .image-preview {
                    margin-top: 8px;
                    max-width: 200px;
                }
                .image-preview img {
                    max-width: 100%;
                    height: auto;
                }
            ');
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_blog_meta_admin_scripts');

/**
 * Get blog meta values with defaults
 */
function wades_get_blog_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return array(
        // Layout Settings
        'show_featured' => get_post_meta($post_id, '_show_featured', true) !== 'no',
        'show_categories' => get_post_meta($post_id, '_show_categories', true) !== 'no',
        'posts_per_page' => absint(get_post_meta($post_id, '_posts_per_page', true)) ?: 9,
        'show_sidebar' => get_post_meta($post_id, '_show_sidebar', true) !== 'no',
        'grid_columns' => get_post_meta($post_id, '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'card_style' => get_post_meta($post_id, '_card_style', true) ?: 'default',
        'hover_effect' => get_post_meta($post_id, '_hover_effect', true) ?: 'scale',
        
        // Featured Post Settings
        'featured_post_id' => get_post_meta($post_id, '_featured_post_id', true),
        'featured_layout' => get_post_meta($post_id, '_featured_layout', true) ?: 'horizontal',
        
        // Category Settings
        'category_style' => get_post_meta($post_id, '_category_style', true) ?: 'pills',
        'excluded_categories' => get_post_meta($post_id, '_excluded_categories', true) ?: array(),
        
        // Sidebar Settings
        'sidebar_position' => get_post_meta($post_id, '_sidebar_position', true) ?: 'right',
        'sidebar_widgets' => get_post_meta($post_id, '_sidebar_widgets', true) ?: array(
            'categories',
            'recent-posts',
            'tags'
        ),
        
        // Post Card Settings
        'show_excerpt' => get_post_meta($post_id, '_show_excerpt', true) !== 'no',
        'excerpt_length' => get_post_meta($post_id, '_excerpt_length', true) ?: 20,
        'show_author' => get_post_meta($post_id, '_show_author', true) !== 'no',
        'show_date' => get_post_meta($post_id, '_show_date', true) !== 'no',
        'show_read_time' => get_post_meta($post_id, '_show_read_time', true) !== 'no',
        
        // Pagination Settings
        'pagination_style' => get_post_meta($post_id, '_pagination_style', true) ?: 'numbered',
        'load_more_text' => get_post_meta($post_id, '_load_more_text', true) ?: 'Load More Posts',
        
        // SEO Settings
        'seo_title' => get_post_meta($post_id, '_seo_title', true),
        'seo_description' => get_post_meta($post_id, '_seo_description', true),
        'schema_type' => get_post_meta($post_id, '_schema_type', true) ?: 'Blog',
        'article_section' => get_post_meta($post_id, '_article_section', true) ?: 'Marine'
    );
} 