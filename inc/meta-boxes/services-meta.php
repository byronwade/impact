<?php
/**
 * Meta Boxes for Services Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for Services template
 */
function wades_services_meta_boxes() {
    // Only add meta boxes on page edit screen
    if (!is_admin()) {
        return;
    }

    global $post;
    if (!$post) {
        return;
    }

    // Check if we're on a page and using the services template
    if (get_post_type($post) === 'page') {
        // Get the current template
        $template = get_page_template_slug($post->ID);
        
        // Check if this is the services template
        if ($template === 'templates/services.php' || basename($template) === 'services.php') {
            add_meta_box(
                'services_content',
                'Services Page Content',
                'wades_services_content_callback',
                'page',
                'normal',
                'high'
            );
        }
    }
}
add_action('add_meta_boxes', 'wades_services_meta_boxes');

/**
 * Services Content Meta Box Callback
 */
function wades_services_content_callback($post) {
    wp_nonce_field('wades_services_meta', 'wades_services_meta_nonce');

    $meta = array(
        'services_title' => get_post_meta($post->ID, '_services_title', true),
        'services_description' => get_post_meta($post->ID, '_services_description', true),
        'services_grid' => get_post_meta($post->ID, '_services_grid', true),
        'why_choose_us' => get_post_meta($post->ID, '_why_choose_us', true),
        'service_image' => get_post_meta($post->ID, '_service_image', true),
        'winterization_packages' => get_post_meta($post->ID, '_winterization_packages', true),
        'service_policies' => get_post_meta($post->ID, '_service_policies', true),
        // New layout options
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
        )
    );
    ?>
    <div class="services-meta-box">
        <div class="meta-box-tabs">
            <button type="button" class="tab-button active" data-tab="content">Content</button>
            <button type="button" class="tab-button" data-tab="layout">Layout & Display</button>
            <button type="button" class="tab-button" data-tab="sections">Sections</button>
        </div>

        <!-- Content Tab -->
        <div class="tab-content active" data-tab="content">
            <!-- Hero Section -->
            <div class="meta-box-section">
                <h3>Hero Section</h3>
                <p>
                    <label for="services_title">Page Title:</label><br>
                    <input type="text" id="services_title" name="services_title" value="<?php echo esc_attr($meta['services_title']); ?>" class="widefat">
                </p>
                <p>
                    <label for="services_description">Page Description:</label><br>
                    <textarea id="services_description" name="services_description" rows="3" class="widefat"><?php echo esc_textarea($meta['services_description']); ?></textarea>
                </p>
            </div>

            <!-- Services Grid -->
            <div class="meta-box-section">
                <h3>Services Grid</h3>
                <div class="services-grid">
                    <?php
                    $services = $meta['services_grid'] ?: array();
                    if (!empty($services)) :
                        foreach ($services as $index => $service) :
                    ?>
                        <div class="service-item card">
                            <div class="card-header">
                                <h4>Service <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-service">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Icon (Lucide icon name):</label><br>
                                    <input type="text" name="services_grid[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($service['icon']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Title:</label><br>
                                    <input type="text" name="services_grid[<?php echo $index; ?>][title]" value="<?php echo esc_attr($service['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label><br>
                                    <textarea name="services_grid[<?php echo $index; ?>][description]" rows="3" class="widefat"><?php echo esc_textarea($service['description']); ?></textarea>
                                </p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="button add-service">Add Service</button>
            </div>

            <!-- Why Choose Us -->
            <div class="meta-box-section">
                <h3>Why Choose Us Section</h3>
                <div class="reasons-list">
                    <?php
                    $reasons = $meta['why_choose_us'] ?: array();
                    if (!empty($reasons)) :
                        foreach ($reasons as $index => $reason) :
                    ?>
                        <p>
                            <input type="text" name="why_choose_us[]" value="<?php echo esc_attr($reason); ?>" class="widefat">
                            <button type="button" class="button remove-reason">Remove</button>
                        </p>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="button add-reason">Add Reason</button>
                <p>
                    <label>Service Department Image:</label><br>
                    <input type="hidden" name="service_image" value="<?php echo esc_attr($meta['service_image']); ?>" class="widefat">
                    <button type="button" class="button upload-image">Upload Image</button>
                    <div class="image-preview">
                        <?php if ($meta['service_image']) : ?>
                            <?php echo wp_get_attachment_image($meta['service_image'], 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                </p>
            </div>

            <!-- Winterization Packages -->
            <div class="meta-box-section">
                <h3>Winterization Packages</h3>
                <div class="packages-list">
                    <?php
                    $packages = $meta['winterization_packages'] ?: array();
                    if (!empty($packages)) :
                        foreach ($packages as $index => $package) :
                    ?>
                        <div class="package-item card">
                            <div class="card-header">
                                <h4>Package <?php echo $index + 1; ?></h4>
                                <button type="button" class="button remove-package">Remove</button>
                            </div>
                            <div class="card-body">
                                <p>
                                    <label>Title:</label><br>
                                    <input type="text" name="winterization_packages[<?php echo $index; ?>][title]" value="<?php echo esc_attr($package['title']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Description:</label><br>
                                    <textarea name="winterization_packages[<?php echo $index; ?>][description]" rows="2" class="widefat"><?php echo esc_textarea($package['description']); ?></textarea>
                                </p>
                                <div class="package-services">
                                    <label>Services:</label>
                                    <?php if (!empty($package['services'])) : foreach ($package['services'] as $service_index => $service) : ?>
                                        <p>
                                            <input type="text" name="winterization_packages[<?php echo $index; ?>][services][]" value="<?php echo esc_attr($service); ?>" class="widefat">
                                            <button type="button" class="button remove-package-service">Remove</button>
                                        </p>
                                    <?php endforeach; endif; ?>
                                    <button type="button" class="button add-package-service">Add Service</button>
                                </div>
                                <p>
                                    <label>Price:</label><br>
                                    <input type="text" name="winterization_packages[<?php echo $index; ?>][price]" value="<?php echo esc_attr($package['price']); ?>" class="widefat">
                                </p>
                                <p>
                                    <label>Note:</label><br>
                                    <input type="text" name="winterization_packages[<?php echo $index; ?>][note]" value="<?php echo esc_attr($package['note']); ?>" class="widefat">
                                </p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="button add-package">Add Package</button>
            </div>

            <!-- Service Policies -->
            <div class="meta-box-section">
                <h3>Service Policies</h3>
                <div class="policies-list">
                    <?php
                    $policies = $meta['service_policies'] ?: array();
                    if (!empty($policies)) :
                        foreach ($policies as $index => $policy) :
                    ?>
                        <p>
                            <input type="text" name="service_policies[]" value="<?php echo esc_attr($policy); ?>" class="widefat">
                            <button type="button" class="button remove-policy">Remove</button>
                        </p>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <button type="button" class="button add-policy">Add Policy</button>
            </div>
        </div>

        <!-- Layout Tab -->
        <div class="tab-content" data-tab="layout">
            <div class="meta-box-section">
                <h3>Grid Layout</h3>
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
                    <input type="number" id="services_per_page" name="services_per_page" value="<?php echo esc_attr($meta['services_per_page']); ?>" class="small-text" min="1" max="100">
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Search & Filters</h3>
                <p>
                    <label>
                        <input type="checkbox" name="show_search" value="1" <?php checked($meta['show_search'], '1'); ?>>
                        Show Search Bar
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="show_filters" value="1" <?php checked($meta['show_filters'], '1'); ?>>
                        Show Location Filter
                    </label>
                </p>
            </div>
        </div>

        <!-- Sections Tab -->
        <div class="tab-content" data-tab="sections">
            <div class="meta-box-section">
                <h3>Section Visibility</h3>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[services]" value="1" <?php checked($meta['sections_visibility']['services'], '1'); ?>>
                        Show Services Grid
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[why_choose_us]" value="1" <?php checked($meta['sections_visibility']['why_choose_us'], '1'); ?>>
                        Show Why Choose Us Section
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[winterization]" value="1" <?php checked($meta['sections_visibility']['winterization'], '1'); ?>>
                        Show Winterization Packages
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" name="sections_visibility[policies]" value="1" <?php checked($meta['sections_visibility']['policies'], '1'); ?>>
                        Show Service Policies
                    </label>
                </p>
            </div>

            <div class="meta-box-section">
                <h3>Section Order</h3>
                <p class="description">Drag and drop sections to reorder them on the page.</p>
                <ul id="section-order" class="section-order-list">
                    <?php
                    $sections = explode(',', $meta['section_order']);
                    $section_labels = array(
                        'services' => 'Services Grid',
                        'why_choose_us' => 'Why Choose Us',
                        'winterization' => 'Winterization Packages',
                        'policies' => 'Service Policies'
                    );
                    foreach ($sections as $section) :
                        if (isset($section_labels[$section])) :
                    ?>
                        <li data-section="<?php echo esc_attr($section); ?>">
                            <i class="dashicons dashicons-menu"></i>
                            <?php echo esc_html($section_labels[$section]); ?>
                        </li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
                <input type="hidden" name="section_order" id="section-order-input" value="<?php echo esc_attr($meta['section_order']); ?>">
            </div>
        </div>
    </div>

    <style>
        .meta-box-tabs {
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
        .meta-box-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .meta-box-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }
        .section-order-list {
            margin: 0;
            padding: 0;
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
        .image-preview {
            margin-top: 10px;
        }
        .image-preview img {
            max-width: 150px;
            height: auto;
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

        // Section order sorting
        $('#section-order').sortable({
            handle: '.dashicons-menu',
            update: function() {
                var order = [];
                $('#section-order li').each(function() {
                    order.push($(this).data('section'));
                });
                $('#section-order-input').val(order.join(','));
            }
        });
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
        'section_order'
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

    // Save services grid
    if (isset($_POST['services_grid'])) {
        $services = array();
        foreach ($_POST['services_grid'] as $service) {
            if (!empty($service['title'])) {
                $services[] = array(
                    'icon' => sanitize_text_field($service['icon']),
                    'title' => sanitize_text_field($service['title']),
                    'description' => wp_kses_post($service['description'])
                );
            }
        }
        update_post_meta($post_id, '_services_grid', $services);
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
                    'services' => isset($package['services']) ? array_map('sanitize_text_field', array_filter($package['services'])) : array(),
                    'price' => sanitize_text_field($package['price']),
                    'note' => sanitize_text_field($package['note'])
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

/**
 * Make sure the services template file is properly registered
 */
function wades_register_services_template($post_templates) {
    $post_templates['templates/services.php'] = __('Services Template', 'wades');
    return $post_templates;
}
add_filter('theme_page_templates', 'wades_register_services_template'); 