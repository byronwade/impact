<?php
/**
 * Shared Meta Box Callbacks
 * 
 * @package wades
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Prevent duplicate inclusion
if (!defined('WADES_SHARED_META_LOADED')) {
    define('WADES_SHARED_META_LOADED', true);

    /**
     * Helper function for page header fields
     */
    function wades_render_header_fields($post) {
        // Get existing values
        $custom_title = get_post_meta($post->ID, '_custom_header_title', true);
        $custom_subheader = get_post_meta($post->ID, '_custom_header_subtext', true);
        $background_image = get_post_meta($post->ID, '_hero_background_image', true);
        $overlay_opacity = get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40';
        $header_height = get_post_meta($post->ID, '_hero_height', true) ?: '70';
        ?>
        <div class="wades-meta-box-container">
            <div class="wades-meta-box-tabs-nav">
                <button type="button" class="wades-tab-button active" data-tab="header">Header</button>
                <button type="button" class="wades-tab-button" data-tab="content">Content</button>
                <button type="button" class="wades-tab-button" data-tab="settings">Settings</button>
            </div>

            <!-- Header Tab -->
            <div class="wades-tab-content active" data-tab="header">
                <div class="wades-meta-section">
                    <h3>Page Header Options</h3>
                    <div class="wades-meta-field">
                        <label for="custom_header_title"><strong>Custom Header Title</strong></label>
                        <input type="text" id="custom_header_title" name="custom_header_title" 
                               value="<?php echo esc_attr($custom_title); ?>" class="widefat"
                               placeholder="Leave empty to use default page title">
                        <p class="description">Override the default page title in the header section.</p>
                    </div>

                    <div class="wades-meta-field">
                        <label for="custom_header_subtext"><strong>Header Subtext</strong></label>
                        <textarea id="custom_header_subtext" name="custom_header_subtext" 
                                class="widefat" rows="3"
                                placeholder="Add a subtitle or description for this page"><?php echo esc_textarea($custom_subheader); ?></textarea>
                        <p class="description">Add a subtitle or description that appears below the main title.</p>
                    </div>

                    <div class="wades-meta-field">
                        <label><strong>Background Image</strong></label>
                        <input type="hidden" id="hero_background_image" name="hero_background_image" 
                               value="<?php echo esc_attr($background_image); ?>">
                        <div class="button-group">
                            <button type="button" class="button upload-image">Select Image</button>
                            <button type="button" class="button remove-image">Remove Image</button>
                        </div>
                        <div class="image-preview">
                            <?php if ($background_image) : ?>
                                <?php echo wp_get_attachment_image($background_image, 'medium'); ?>
                            <?php endif; ?>
                        </div>
                        <p class="description">Recommended size: 1920x1080px or larger</p>
                    </div>

                    <div class="wades-meta-field">
                        <label for="hero_overlay_opacity"><strong>Overlay Opacity (%)</strong></label>
                        <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                               value="<?php echo esc_attr($overlay_opacity); ?>" class="small-text"
                               min="0" max="100" step="5">
                        <p class="description">Adjust the darkness of the overlay on the background image</p>
                    </div>

                    <div class="wades-meta-field">
                        <label for="hero_height"><strong>Header Height (vh)</strong></label>
                        <input type="number" id="hero_height" name="hero_height" 
                               value="<?php echo esc_attr($header_height); ?>" class="small-text"
                               min="10" max="100" step="5">
                        <p class="description">Set the height of the header (70 = 70% of viewport height)</p>
                    </div>
                </div>
            </div>

            <!-- Content Tab -->
            <div class="wades-tab-content" data-tab="content">
                <?php do_action('wades_meta_box_content_tab', $post); ?>
            </div>

            <!-- Settings Tab -->
            <div class="wades-tab-content" data-tab="settings">
                <?php do_action('wades_meta_box_settings_tab', $post); ?>
            </div>
        </div>

        <style>
        .wades-meta-box-container {
            margin: -6px -12px -12px -12px;
        }
        .wades-meta-box-tabs-nav {
            background: #f0f0f1;
            border-bottom: 1px solid #ccc;
            padding: 0 12px;
            margin-bottom: 15px;
        }
        .wades-tab-button {
            background: none;
            border: none;
            padding: 8px 12px;
            margin: 0;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            color: #646970;
        }
        .wades-tab-button:hover {
            color: #2271b1;
        }
        .wades-tab-button.active {
            border-bottom-color: #2271b1;
            color: #1d2327;
            font-weight: 600;
        }
        .wades-tab-content {
            display: none;
            padding: 0 12px;
        }
        .wades-tab-content.active {
            display: block;
        }
        .wades-meta-section {
            margin-bottom: 20px;
        }
        .wades-meta-field {
            margin-bottom: 15px;
        }
        .wades-meta-field label {
            display: block;
            margin-bottom: 5px;
        }
        .button-group {
            margin: 10px 0;
        }
        .image-preview {
            margin: 10px 0;
            max-width: 300px;
        }
        .image-preview img {
            max-width: 100%;
            height: auto;
        }
        </style>

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
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    imageInput.val(attachment.id);
                    imagePreview.html($('<img>', {
                        src: attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url,
                        style: 'max-width: 100%; height: auto;'
                    }));
                });

                frame.open();
            });

            // Remove image functionality
            $('.remove-image').on('click', function() {
                var container = $(this).closest('.wades-meta-field');
                container.find('input[type="hidden"]').val('');
                container.find('.image-preview').empty();
            });
        });
        </script>
        <?php
    }

    /**
     * Save header fields
     */
    function wades_save_header_fields($post_id) {
        // Save custom title
        if (isset($_POST['custom_header_title'])) {
            update_post_meta($post_id, '_custom_header_title', sanitize_text_field($_POST['custom_header_title']));
        }

        // Save subheader
        if (isset($_POST['custom_header_subtext'])) {
            update_post_meta($post_id, '_custom_header_subtext', sanitize_textarea_field($_POST['custom_header_subtext']));
        }

        // Save background image
        if (isset($_POST['hero_background_image'])) {
            update_post_meta($post_id, '_hero_background_image', absint($_POST['hero_background_image']));
        }

        // Save overlay opacity
        if (isset($_POST['hero_overlay_opacity'])) {
            $opacity = max(0, min(100, absint($_POST['hero_overlay_opacity'])));
            update_post_meta($post_id, '_hero_overlay_opacity', $opacity);
        }

        // Save header height
        if (isset($_POST['hero_height'])) {
            $height = max(10, min(100, absint($_POST['hero_height'])));
            update_post_meta($post_id, '_hero_height', $height);
        }
    }

    /**
     * Helper function for page select fields
     */
    if (!function_exists('wades_page_select_field')) {
        function wades_page_select_field($name, $selected_id = '', $class = 'widefat') {
            $pages = get_pages(array(
                'sort_column' => 'menu_order,post_title',
                'post_status' => 'publish'
            ));
            
            $output = '<select name="' . esc_attr($name) . '" class="' . esc_attr($class) . '">';
            $output .= '<option value="">Select a page</option>';
            
            foreach ($pages as $page) {
                $output .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($page->ID),
                    selected($selected_id, $page->ID, false),
                    esc_html($page->post_title)
                );
            }
            
            $output .= '</select>';
            return $output;
        }
    }
} 