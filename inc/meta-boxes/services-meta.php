<?php
/**
 * Meta Boxes for Services Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Remove any conflicting template registration
if (function_exists('wades_register_services_template')) {
    remove_filter('theme_page_templates', 'wades_register_services_template');
}

/**
 * Add meta boxes for Services template
 */
function wades_add_services_meta_boxes() {
    // Get current screen
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'page') {
        return;
    }

    // Get current template
    $template = get_page_template_slug();
    wades_debug_log('Services meta box - Current template: ' . $template);
    
    // Only add these meta boxes for the services template
    if ($template !== 'templates/services.php') {
        return;
    }

    // Remove the separate page header meta box
    remove_meta_box('wades_page_header', 'page', 'normal');

    // Add services settings meta box
    add_meta_box(
        'wades_services_settings',
        __('Services Page Settings', 'wades'),
        'wades_services_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wades_add_services_meta_boxes', 20);

/**
 * Services Settings Meta Box Callback
 */
function wades_services_settings_callback($post) {
    wp_nonce_field('wades_services_meta', 'wades_services_meta_nonce');

    // Get all meta data
    $meta = array(
        'services_title' => get_post_meta($post->ID, '_services_title', true),
        'services_description' => get_post_meta($post->ID, '_services_description', true),
        'services_grid' => get_post_meta($post->ID, '_services_grid', true) ?: array(),
        'why_choose_us' => get_post_meta($post->ID, '_why_choose_us', true) ?: array(),
        'service_image' => get_post_meta($post->ID, '_service_image', true),
        'winterization_packages' => get_post_meta($post->ID, '_winterization_packages', true) ?: array(),
        'service_policies' => get_post_meta($post->ID, '_service_policies', true) ?: array(),
        'grid_columns' => get_post_meta($post->ID, '_grid_columns', true) ?: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'services_per_page' => get_post_meta($post->ID, '_services_per_page', true) ?: '12',
        'show_search' => get_post_meta($post->ID, '_show_search', true) ?: '1',
        'show_filters' => get_post_meta($post->ID, '_show_filters', true) ?: '1',
        'section_order' => get_post_meta($post->ID, '_section_order', true) ?: 'services,why_choose_us,winterization,policies',
        'sections_visibility' => get_post_meta($post->ID, '_sections_visibility', true) ?: array(
            'services' => '1',
            'why_choose_us' => '1',
            'winterization' => '1',
            'policies' => '1'
        ),
        'hero_background_image' => get_post_meta($post->ID, '_hero_background_image', true),
        'hero_overlay_opacity' => get_post_meta($post->ID, '_hero_overlay_opacity', true) ?: '40',
        'hero_height' => get_post_meta($post->ID, '_hero_height', true) ?: '70'
    );
    ?>
    <div class="meta-box-container">
        <!-- Tab Navigation -->
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="layout">Layout & Order</button>
            <button type="button" class="tab-button" data-tab="header">Page Header</button>
            <button type="button" class="tab-button" data-tab="services">Services Grid</button>
            <button type="button" class="tab-button" data-tab="why-choose">Why Choose Us</button>
            <button type="button" class="tab-button" data-tab="winterization">Winterization</button>
            <button type="button" class="tab-button" data-tab="policies">Policies</button>
        </div>

        <!-- Layout & Order Tab -->
        <div class="tab-content active" data-tab="layout">
            <div class="meta-box-section">
                <h3>Section Order & Visibility</h3>
                <p class="description">Enable/disable sections and drag to reorder them.</p>
                <div class="sections-list" style="margin-top: 15px;">
                    <?php
                    $sections = explode(',', $meta['section_order']);
                    $section_labels = array(
                        'services' => 'Main Services Grid Section',
                        'why_choose_us' => 'Why Choose Our Service Department Section',
                        'winterization' => 'Winterization & Service Packages Section',
                        'policies' => 'Service Policies & Requirements Section'
                    );
                    foreach ($sections as $section_id) :
                        if (isset($section_labels[$section_id])) :
                    ?>
                        <div class="section-item" style="padding: 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 5px;">
                            <input type="hidden" 
                                   name="section_order" 
                                   value="<?php echo esc_attr($meta['section_order']); ?>"
                                   class="section-order">
                            <label style="display: flex; align-items: center; gap: 10px;">
                                <span class="dashicons dashicons-menu" style="cursor: move;"></span>
                                <input type="checkbox" 
                                       name="sections_visibility[<?php echo esc_attr($section_id); ?>]" 
                                       value="1"
                                       <?php checked($meta['sections_visibility'][$section_id], '1'); ?>>
                                <div>
                                    <strong><?php echo esc_html($section_labels[$section_id]); ?></strong>
                                    <p class="description" style="margin: 2px 0 0 0; font-size: 12px;">
                                        <?php
                                        switch ($section_id) {
                                            case 'services':
                                                echo 'The main grid displaying all available services with search and filtering options.';
                                                break;
                                            case 'why_choose_us':
                                                echo 'Highlights our expertise, certifications, and service guarantees.';
                                                break;
                                            case 'winterization':
                                                echo 'Displays winterization service packages and seasonal maintenance options.';
                                                break;
                                            case 'policies':
                                                echo 'Lists important service policies, requirements, and warranty information.';
                                                break;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </label>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            </div>

            <div class="meta-box-section">
                <h3>Grid Layout Settings</h3>
                <p>
                    <label for="grid_columns">Number of Columns:</label><br>
                    <select id="grid_columns" name="grid_columns" class="widefat">
                        <option value="grid-cols-1 md:grid-cols-1 lg:grid-cols-2" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-1 lg:grid-cols-2'); ?>>2 Columns (Large)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-3" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?>>3 Columns (Default)</option>
                        <option value="grid-cols-1 md:grid-cols-2 lg:grid-cols-4" <?php selected($meta['grid_columns'], 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'); ?>>4 Columns</option>
                    </select>
                </p>
                <p>
                    <label for="services_per_page">Services Per Page:</label><br>
                    <input type="number" id="services_per_page" name="services_per_page" 
                           value="<?php echo esc_attr($meta['services_per_page']); ?>" 
                           class="small-text" min="1" max="100">
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_search" value="1" 
                               <?php checked($meta['show_search'], '1'); ?>>
                        Show Search Bar
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_filters" value="1" 
                               <?php checked($meta['show_filters'], '1'); ?>>
                        Show Location Filter
                    </label>
                </p>
            </div>
        </div>

        <!-- Header Tab -->
        <div class="tab-content" data-tab="header">
            <div class="meta-box-section">
                <h3>Page Header Settings</h3>
                <!-- Background Image -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium">Background Image</label>
                    <div class="flex items-start gap-4">
                        <div>
                            <input type="hidden" name="hero_background_image" id="hero_background_image" 
                                   value="<?php echo esc_attr($meta['hero_background_image']); ?>">
                            <div class="button-group">
                                <button type="button" class="button upload-image" id="upload_hero_image">Select Image</button>
                                <button type="button" class="button remove-image">Remove Image</button>
                            </div>
                        </div>
                        <div id="hero_image_preview" class="max-w-xs">
                            <?php 
                            $bg_image = $meta['hero_background_image'];
                            if ($bg_image) {
                                echo wp_get_attachment_image($bg_image, 'thumbnail');
                            }
                            ?>
                        </div>
                    </div>
                    <p class="description mt-2">Recommended size: 1920x1080px or larger</p>
                </div>

                <!-- Overlay Opacity -->
                <div class="mb-6">
                    <label for="hero_overlay_opacity" class="block mb-2 font-medium">
                        Overlay Opacity (%)
                    </label>
                    <input type="number" id="hero_overlay_opacity" name="hero_overlay_opacity" 
                           value="<?php echo esc_attr($meta['hero_overlay_opacity']); ?>"
                           class="regular-text" min="0" max="100" step="5">
                    <p class="description mt-2">Adjust the darkness of the overlay on the background image</p>
                </div>

                <!-- Header Height -->
                <div class="mb-6">
                    <label for="hero_height" class="block mb-2 font-medium">
                        Header Height (vh)
                    </label>
                    <input type="number" id="hero_height" name="hero_height" 
                           value="<?php echo esc_attr($meta['hero_height']); ?>"
                           class="regular-text" min="30" max="100" step="5">
                    <p class="description mt-2">Set the height of the header (70 = 70% of viewport height)</p>
                </div>
            </div>
        </div>

        <!-- Services Grid Tab -->
        <div class="tab-content" data-tab="services">
            <div class="meta-box-section">
                <h3>Services Grid Content</h3>
                <p>
                    <label for="services_title">Section Title:</label>
                    <input type="text" id="services_title" name="services_title" 
                           value="<?php echo esc_attr($meta['services_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="services_description">Section Description:</label>
                    <textarea id="services_description" name="services_description" rows="3" 
                              class="widefat"><?php echo esc_textarea($meta['services_description']); ?></textarea>
                </p>
            </div>
        </div>

        <!-- Why Choose Us Tab -->
        <div class="tab-content" data-tab="why-choose">
            <div class="meta-box-section">
                <h3>Why Choose Us Content</h3>
                <div class="reasons-list">
                    <?php foreach ($meta['why_choose_us'] as $index => $reason) : ?>
                        <p>
                            <input type="text" name="why_choose_us[]" 
                                   value="<?php echo esc_attr($reason); ?>" class="widefat">
                            <button type="button" class="button remove-reason">Remove</button>
                        </p>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-reason">Add Reason</button>
                <p>
                    <label>Featured Image:</label><br>
                    <input type="hidden" name="service_image" 
                           value="<?php echo esc_attr($meta['service_image']); ?>" class="widefat">
                    <div class="button-group">
                        <button type="button" class="button upload-image">Select Image</button>
                        <button type="button" class="button remove-image">Remove Image</button>
                    </div>
                    <div class="image-preview">
                        <?php if ($meta['service_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['service_image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>
        </div>

        <!-- Winterization Tab -->
        <div class="tab-content" data-tab="winterization">
            <div class="meta-box-section">
                <h3>Winterization Packages</h3>
                <div class="packages-list">
                    <?php foreach ($meta['winterization_packages'] as $index => $package) : ?>
                        <div class="package-item card">
                            <div class="card-header">
                                <h4>Package <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-package">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Title:</label>
                                    <input type="text" name="winterization_packages[<?php echo $index; ?>][title]" 
                                           value="<?php echo esc_attr($package['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label>
                                    <textarea name="winterization_packages[<?php echo $index; ?>][description]" 
                                              rows="2" class="widefat"><?php echo esc_textarea($package['description']); ?></textarea>
                                </p>
                                <div class="package-services">
                                    <label>Services:</label>
                                    <?php foreach ($package['services'] as $service_index => $service) : ?>
                                        <p>
                                            <input type="text" name="winterization_packages[<?php echo $index; ?>][services][]" 
                                                   value="<?php echo esc_attr($service); ?>" class="widefat">
                                            <button type="button" class="button remove-package-service">Remove</button>
                                        </p>
                                    <?php endforeach; ?>
                                    <button type="button" class="button add-package-service">Add Service</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-package">Add Package</button>
            </div>
        </div>

        <!-- Policies Tab -->
        <div class="tab-content" data-tab="policies">
            <div class="meta-box-section">
                <h3>Service Policies</h3>
                <div class="policies-list">
                    <?php foreach ($meta['service_policies'] as $index => $policy) : ?>
                        <p>
                            <input type="text" name="service_policies[]" 
                                   value="<?php echo esc_attr($policy); ?>" class="widefat">
                            <button type="button" class="button remove-policy">Remove</button>
                        </p>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button add-policy">Add Policy</button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.tab-button').on('click', function() {
            $('.tab-button').removeClass('active');
            $('.tab-content').removeClass('active');
            $(this).addClass('active');
            $('.tab-content[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        });

        // Section ordering
        $('.sections-list').sortable({
            handle: '.dashicons-menu',
            update: function(event, ui) {
                var order = [];
                $('.sections-list .section-item').each(function(index) {
                    $(this).find('.section-order').val((index + 1) * 10);
                });
            }
        });

        // Image upload functionality
        function initImageUpload(button, input, preview) {
            button.on('click', function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: 'Select Image',
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    input.val(attachment.id);
                    preview.html($('<img>', {
                        src: attachment.url,
                        style: 'max-width: 200px; height: auto;'
                    }));
                });

                frame.open();
            });
        }

        // Initialize image uploads
        $('.upload-image').each(function() {
            var container = $(this).closest('.meta-box-section');
            initImageUpload(
                $(this),
                container.find('input[type="hidden"]'),
                container.find('.image-preview')
            );
        });

        // Remove image functionality
        $('.remove-image').on('click', function() {
            var container = $(this).closest('.meta-box-section');
            container.find('input[type="hidden"]').val('');
            container.find('.image-preview').empty();
        });

        // Dynamic list functionality
        function setupDynamicList(addButton, container, template) {
            $(addButton).on('click', function() {
                var index = container.children().length;
                container.append(template.replace(/\{index\}/g, index));
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.dynamic-item').remove();
            });
        }

        // Setup dynamic lists
        setupDynamicList('.add-reason', $('.reasons-list'), 
            '<p><input type="text" name="why_choose_us[]" class="widefat"><button type="button" class="button remove-reason">Remove</button></p>');
        
        setupDynamicList('.add-policy', $('.policies-list'), 
            '<p><input type="text" name="service_policies[]" class="widefat"><button type="button" class="button remove-policy">Remove</button></p>');
    });
    </script>
    <?php
}

/**
 * Save Services Meta Box Data
 */
function wades_save_services_meta($post_id) {
    if (!isset($_POST['wades_services_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wades_services_meta_nonce'], 'wades_services_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save basic content fields
    $text_fields = array(
        'services_title',
        'services_description',
        'grid_columns',
        'services_per_page',
        'section_order',
        'hero_background_image',
        'hero_overlay_opacity',
        'hero_height'
    );

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save checkboxes
    $checkboxes = array('show_search', 'show_filters');
    foreach ($checkboxes as $checkbox) {
        update_post_meta($post_id, '_' . $checkbox, isset($_POST[$checkbox]) ? '1' : '');
    }

    // Save section visibility
    if (isset($_POST['sections_visibility']) && is_array($_POST['sections_visibility'])) {
        $visibility = array();
        foreach ($_POST['sections_visibility'] as $section => $value) {
            $visibility[$section] = '1';
        }
        update_post_meta($post_id, '_sections_visibility', $visibility);
    }

    // Save why choose us
    if (isset($_POST['why_choose_us'])) {
        $reasons = array_map('sanitize_text_field', array_filter($_POST['why_choose_us']));
        update_post_meta($post_id, '_why_choose_us', $reasons);
    }

    // Save service image
    if (isset($_POST['service_image'])) {
        update_post_meta($post_id, '_service_image', absint($_POST['service_image']));
    }

    // Save winterization packages
    if (isset($_POST['winterization_packages'])) {
        $packages = array();
        foreach ($_POST['winterization_packages'] as $package) {
            if (!empty($package['title'])) {
                $packages[] = array(
                    'title' => sanitize_text_field($package['title']),
                    'description' => wp_kses_post($package['description']),
                    'services' => isset($package['services']) ? array_map('sanitize_text_field', array_filter($package['services'])) : array()
                );
            }
        }
        update_post_meta($post_id, '_winterization_packages', $packages);
    }

    // Save service policies
    if (isset($_POST['service_policies'])) {
        $policies = array_map('sanitize_text_field', array_filter($_POST['service_policies']));
        update_post_meta($post_id, '_service_policies', $policies);
    }
}
add_action('save_post', 'wades_save_services_meta');

/**
 * Enqueue admin scripts and styles
 */
function wades_services_admin_scripts($hook) {
    global $post;
    
    // Only load on post.php or post-new.php
    if (!in_array($hook, array('post.php', 'post-new.php'))) {
        return;
    }

    // Only load for pages using the services template
    if (get_post_type($post) === 'page') {
        $template = get_page_template_slug($post->ID);
        if ($template === 'templates/services.php' || basename($template) === 'services.php') {
            // Enqueue WordPress media scripts
            wp_enqueue_media();
            
            // Enqueue jQuery UI for sortable functionality
            wp_enqueue_script('jquery-ui-sortable');
            
            // Enqueue your custom admin script
            wp_enqueue_script(
                'services-admin',
                get_template_directory_uri() . '/assets/js/services-admin.js',
                array('jquery', 'jquery-ui-sortable'),
                filemtime(get_template_directory() . '/assets/js/services-admin.js'),
                true
            );

            // Add custom admin styles
            wp_add_inline_style('wp-admin', '
                .meta-box-tabs {
                    margin-bottom: 20px;
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 0;
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
                .meta-box-section {
                    margin-bottom: 30px;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 4px;
                }
                .section-order-list {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }
                .section-order-list li {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    margin-bottom: 5px;
                    background: #fff;
                    border: 1px solid #ddd;
                    cursor: move;
                }
                .section-order-list .dashicons {
                    margin-right: 10px;
                    color: #666;
                }
            ');
        }
    }
}
add_action('admin_enqueue_scripts', 'wades_services_admin_scripts'); 